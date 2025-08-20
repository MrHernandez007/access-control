import BackButton from '@/app/componentes/BackButton';
import Qr from '@/app/componentes/Qr';
import { useAuth } from '@/app/providers/AuthProviders';
import API_URL from '@/backend/config/API_URL';
import { Picker } from '@react-native-picker/picker';
import { LinearGradient } from 'expo-linear-gradient';
import React, { useEffect, useState } from 'react';
import {
  Alert,
  Modal,
  Pressable,
  StyleSheet,
  Text,
  TouchableOpacity,
  View
} from 'react-native';
import { Calendar } from 'react-native-calendars';

interface Visitante {
  _id: string;
  nombre: string;
  apellido: string;
}

interface CrearVisitaResponse {
  message?: string;
  data?: {
    _id: string;
    visitante_id: string;
    residente_id: string;
    dia_visita: string;
    estado: 'En curso' | 'Finalizada' | 'Expirado';
    qrCode: string;
    maxScans: number;
    qrExpiresAt: string; // ISO
  };
  // fallback si el backend devuelve el doc plano:
  _id?: string;
  qrCode?: string;
  maxScans?: number;
  qrExpiresAt?: string;
}

export default function AgendarVisitaScreen() {
  const { token } = useAuth();
  const [visitantes, setVisitantes] = useState<Visitante[]>([]);
  const [visitanteSeleccionado, setVisitanteSeleccionado] = useState<string>('');
  const [fecha, setFecha] = useState(''); // YYYY-MM-DD
  const [qrValue, setQrValue] = useState<string>('');
  const [modalVisible, setModalVisible] = useState(false);

  // info adicional
  const [qrRestante, setQrRestante] = useState<number | undefined>(undefined);
  const [qrExpiresAt, setQrExpiresAt] = useState<string | undefined>(undefined);

  useEffect(() => {
    const obtenerVisitantes = async () => {
      try {
        const response = await fetch(`${API_URL}/api/visitantes`, {
          headers: { Authorization: `Bearer ${token}` },
        });

        if (!response.ok) {
          const errorData = await response.json().catch(() => ({}));
          console.error('Error al obtener visitantes:', errorData);
          return;
        }

        const data: Visitante[] = await response.json();
        setVisitantes(data);
      } catch (error) {
        console.error('Error al conectar con el servidor:', error);
      }
    };

    obtenerVisitantes();
  }, [token]);

  const generarQR = async () => {
    if (!visitanteSeleccionado || !fecha) {
      Alert.alert('Campos requeridos', 'Selecciona un visitante y una fecha');
      return;
    }

    try {
      const response = await fetch(`${API_URL}/api/visitas`, {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          Authorization: `Bearer ${token}`,
        },
        body: JSON.stringify({
          visitante_id: visitanteSeleccionado,
          dia_visita: fecha, 
        }),
      });

      const data: CrearVisitaResponse = await response.json();

      if (!response.ok) {
        console.error('Error al generar visita:', data);
        Alert.alert('Error', data?.message || 'No se pudo generar la visita');
        return;
      }


      const payload = data.data ?? (data as any);
      const qrCode = payload?.qrCode;

      if (!qrCode) {
        Alert.alert('Error', 'El backend no devolvió qrCode');
        return;
      }

      setQrValue(qrCode);
      setQrRestante(payload?.maxScans);
      setQrExpiresAt(payload?.qrExpiresAt);
      setModalVisible(true);

      const visitante = visitantes.find((v) => v._id === visitanteSeleccionado);
      Alert.alert(
        'Visita generada correctamente',
        `Visitante: ${visitante?.nombre} ${visitante?.apellido}\nFecha: ${fecha}`
      );
    } catch (error) {
      console.error('Error de red:', error);
      Alert.alert('Error', 'Error de conexión con el servidor');
    }
  };

  return (
    <LinearGradient
      colors={['#9AA6B2', '#F8FAFC']}
      start={{ x: 0, y: 0 }}
      end={{ x: 1, y: 1 }}
      style={styles.gradient_background}
    >
      <View style={styles.containerbackground}>
        <BackButton />
        <View style={styles.container}>
          <Text style={styles.title}>Agendar Visita</Text>

          <Text style={styles.label}>Selecciona un visitante:</Text>
          <View style={styles.pickerWrapper}>
            <Picker
              selectedValue={visitanteSeleccionado}
              onValueChange={(itemValue) => setVisitanteSeleccionado(itemValue)}
            >
              <Picker.Item label="Selecciona un visitante..." value="" />
              {visitantes.map((v) => (
                <Picker.Item key={v._id} label={`${v.nombre} ${v.apellido}`} value={v._id} />
              ))}
            </Picker>
          </View>

          <Text style={styles.label}>Selecciona una fecha:</Text>
          <View style={styles.calendarWrapper}>
            <Calendar
              onDayPress={(day) => setFecha(day.dateString)}
              markedDates={{
                [fecha]: { selected: true, marked: true, selectedColor: '#2563eb' },
              }}
            />
          </View>

          <TouchableOpacity style={styles.button} onPress={generarQR}>
            <Text style={styles.buttonText}>Generar Visita (QR)</Text>
          </TouchableOpacity>
        </View>

        {/* Modal QR */}
        <Modal
          animationType="slide"
          transparent={true}
          visible={modalVisible}
          onRequestClose={() => setModalVisible(false)}
        >
          <View style={styles.modalOverlay}>
            <View style={styles.modalContent}>
              <Text style={styles.modalTitle}>Código QR de la Visita</Text>
              {qrValue ? <Qr value={qrValue} size={300} /> : <Text>Generando...</Text>}

              {qrExpiresAt && (
                <Text style={styles.metaText}>
                  Expira: {new Date(qrExpiresAt).toLocaleString()}
                </Text>
              )}
              {typeof qrRestante === 'number' && (
                <Text style={styles.metaText}>
                  Usos disponibles: {qrRestante}
                </Text>
              )}

              <Pressable
                style={styles.closeButton}
                onPress={() => setModalVisible(false)}
              >
                <Text style={styles.closeButtonText}>Cerrar</Text>
              </Pressable>
            </View>
          </View>
        </Modal>
      </View>
    </LinearGradient>
  );
}

const styles = StyleSheet.create({
  containerbackground: {
    flex: 1,
    paddingTop: 60,
  },
  gradient_background: {
    flex: 1,
    justifyContent: 'center',
  },
  container: {
    padding: 16,
    marginTop: 40,
  },
  title: {
    fontSize: 24,
    fontWeight: 'bold',
    marginBottom: 20,
    textAlign: 'center',
    color: '#1f2937',
  },
  label: {
    fontSize: 16,
    marginTop: 10,
    marginBottom: 4,
  },
  pickerWrapper: {
    backgroundColor: '#fff',
    borderRadius: 8,
    marginBottom: 15,
    overflow: 'hidden',
  },
  button: {
    backgroundColor: '#2563eb',
    padding: 14,
    borderRadius: 10,
    alignItems: 'center',
    marginTop: 10,
  },
  buttonText: {
    color: '#fff',
    fontWeight: 'bold',
    fontSize: 16,
  },
  calendarWrapper: {
    borderRadius: 8,
    backgroundColor: '#fff',
    marginBottom: 15,
    overflow: 'hidden',
  },
  modalOverlay: {
    flex: 1,
    backgroundColor: 'rgba(0,0,0,0.5)',
    justifyContent: 'center',
    alignItems: 'center',
    padding: 16,
  },
  modalContent: {
    backgroundColor: 'white',
    padding: 20,
    borderRadius: 15,
    alignItems: 'center',
    shadowColor: '#000',
    shadowOffset: { width: 0, height: 2 },
    shadowOpacity: 0.25,
    shadowRadius: 4,
    elevation: 5,
    width: '100%',
    maxWidth: 420,
  },
  modalTitle: {
    fontSize: 20,
    fontWeight: 'bold',
    marginBottom: 15,
    textAlign: 'center',
  },
  metaText: {
    marginTop: 8,
    fontSize: 14,
    color: '#374151',
  },
  closeButton: {
    marginTop: 20,
    backgroundColor: '#2563eb',
    paddingVertical: 10,
    paddingHorizontal: 25,
    borderRadius: 8,
  },
  closeButtonText: {
    color: '#fff',
    fontWeight: 'bold',
    fontSize: 16,
  },
});
