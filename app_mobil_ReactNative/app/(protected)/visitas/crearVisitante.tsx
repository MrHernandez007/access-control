import BackButton from '@/app/componentes/BackButton';
import { useAuth } from '@/app/providers/AuthProviders';
import API_URL from '@/backend/config/API_URL';
import { LinearGradient } from 'expo-linear-gradient';
import React, { useEffect, useState } from 'react';
import {
  Alert,
  Button,
  FlatList,
  StyleSheet,
  Text,
  TextInput,
  View,
} from 'react-native';

interface Visitante {
  _id: string;
  nombre: string;
  apellido: string;
  telefono: string;
  vehiculo: {
    placa: string;
    modelo?: string;
    color?: string;
    tipo?: string;
  };
}

type Errors = {
  nombre?: string;
  apellido?: string;
  telefono?: string;
  placas?: string;
  tipo?: string;
};

export default function CrearVisitante() {
  const [visitantes, setVisitantes] = useState<Visitante[]>([]);
  const [nombre, setNombre] = useState('');
  const [apellido, setApellido] = useState('');
  const [telefono, setTelefono] = useState('');
  const [placas, setPlacas] = useState('');
  const [tipo, setTipo] = useState('');
  const [color, setColor] = useState('');
  const [modelo, setModelo] = useState('');
  const [errors, setErrors] = useState<Errors>({});
  const [loading, setLoading] = useState(false);
  const { token } = useAuth();

  useEffect(() => {
    const fetchVisitantes = async () => {
      try {
        const response = await fetch(`${API_URL}/api/visitantes`, {
          headers: {
            Authorization: `Bearer ${token}`,
            'Content-Type': 'application/json',
          },
        });

        let data: any = [];
        try {
          data = await response.json();
        } catch {}

        if (Array.isArray(data)) {
          setVisitantes(data);
        } else {
          console.warn('Respuesta inesperada:', data);
        }
      } catch (error) {
        console.error('Error al obtener visitantes:', error);
      }
    };

    fetchVisitantes();
  }, [token]);

  const onlyLetters = (v: string) => /^[A-Za-zÁÉÍÓÚáéíóúÑñ\s'-]+$/.test(v.trim());
  const onlyDigits = (v: string) => /^\d+$/.test(v);
  const placaRegex = /^[A-Z0-9-]{5,8}$/; 

  const validate = (): boolean => {
    const e: Errors = {};

    const n = nombre.trim();
    const a = apellido.trim();
    const t = telefono.trim();
    const p = placas.trim().toUpperCase();
    const tp = tipo.trim();

    if (!n) e.nombre = 'Ingresa el nombre';
    else if (!onlyLetters(n)) e.nombre = 'Solo letras y espacios';

    if (!a) e.apellido = 'Ingresa el apellido';
    else if (!onlyLetters(a)) e.apellido = 'Solo letras y espacios';

    if (!t) e.telefono = 'Ingresa el teléfono';
    else if (!onlyDigits(t)) e.telefono = 'Solo números';
    else if (t.length < 10) e.telefono = 'Debe tener 10 dígitos';

    if (!p) e.placas = 'Ingresa las placas';
    else if (!placaRegex.test(p)) e.placas = 'Formato inválido (5–8, mayúsculas y números)';

    if (!tp) e.tipo = 'Ingresa el tipo de vehículo';

    setErrors(e);
    return Object.keys(e).length === 0;
  };

  const agregarVisitante = async () => {
    if (!validate()) return;

    try {
      setLoading(true);
      const response = await fetch(`${API_URL}/api/visitantes/registrar`, {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          Authorization: `Bearer ${token}`,
        },
        body: JSON.stringify({
          nombre: nombre.trim(),
          apellido: apellido.trim(),
          telefono: telefono.trim(),
          tipo: 'visita',
          vehiculo: {
            placa: placas.trim().toUpperCase(),
            color: color.trim(),
            modelo: modelo.trim(),
            tipo: tipo.trim(),
          },
        }),
      });

      let data: any = null;
      try {
        data = await response.json();
      } catch {}

      if (!response.ok) {
        const text = data ? JSON.stringify(data) : '(sin cuerpo JSON)';
        console.error(`Registrar visitante falló [${response.status}]: ${text}`);
        Alert.alert('Error', (data && (data.msg || data.message)) || `Error del servidor (${response.status})`);
        return;
      }

      Alert.alert('Éxito', 'Visitante registrado correctamente');

      const updatedList = await fetch(`${API_URL}/api/visitantes`, {
        headers: {
          Authorization: `Bearer ${token}`,
          'Content-Type': 'application/json',
        },
      });

      let newData: any = [];
      try {
        newData = await updatedList.json();
      } catch {}

      if (Array.isArray(newData)) {
        setVisitantes(newData);
      }

      setNombre('');
      setApellido('');
      setTelefono('');
      setPlacas('');
      setTipo('');
      setColor('');
      setModelo('');
      setErrors({});
    } catch (error) {
      console.error('Error de red:', error);
      Alert.alert('Error de red', 'No se pudo conectar con el servidor');
    } finally {
      setLoading(false);
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
          <Text style={styles.title}>Registrar Visitante</Text>

          <TextInput
            placeholder="Nombre"
            style={styles.input}
            value={nombre}
            onChangeText={(v) => {
              setNombre(v);
              if (errors.nombre) setErrors((e) => ({ ...e, nombre: undefined }));
            }}
            onBlur={() => setNombre((v) => v.trim())}
          />
          {errors.nombre ? <Text style={styles.error}>{errors.nombre}</Text> : null}

          <TextInput
            placeholder="Apellido"
            style={styles.input}
            value={apellido}
            onChangeText={(v) => {
              setApellido(v);
              if (errors.apellido) setErrors((e) => ({ ...e, apellido: undefined }));
            }}
            onBlur={() => setApellido((v) => v.trim())}
          />
          {errors.apellido ? <Text style={styles.error}>{errors.apellido}</Text> : null}

          <TextInput
            placeholder="Teléfono (10 dígitos)"
            keyboardType="number-pad"
            style={styles.input}
            value={telefono}
            onChangeText={(v) => {
              const digits = v.replace(/\D/g, '');
              setTelefono(digits);
              if (errors.telefono) setErrors((e) => ({ ...e, telefono: undefined }));
            }}
            maxLength={10}
          />
          {errors.telefono ? <Text style={styles.error}>{errors.telefono}</Text> : null}

          <TextInput
            placeholder="Placas del vehículo (5–8, mayúsculas/números)"
            autoCapitalize="characters"
            style={styles.input}
            value={placas}
            onChangeText={(v) => {
              setPlacas(v.toUpperCase());
              if (errors.placas) setErrors((e) => ({ ...e, placas: undefined }));
            }}
            onBlur={() => setPlacas((v) => v.toUpperCase().trim())}
          />
          {errors.placas ? <Text style={styles.error}>{errors.placas}</Text> : null}

          <TextInput
            placeholder="Tipo de vehículo (Sedán, SUV, etc.)"
            style={styles.input}
            value={tipo}
            onChangeText={(v) => {
              setTipo(v);
              if (errors.tipo) setErrors((e) => ({ ...e, tipo: undefined }));
            }}
            onBlur={() => setTipo((v) => v.trim())}
          />
          {errors.tipo ? <Text style={styles.error}>{errors.tipo}</Text> : null}

          <TextInput
            placeholder="Color (opcional)"
            style={styles.input}
            value={color}
            onChangeText={setColor}
            onBlur={() => setColor((v) => v.trim())}
          />

          <TextInput
            placeholder="Modelo (opcional)"
            style={styles.input}
            value={modelo}
            onChangeText={setModelo}
            onBlur={() => setModelo((v) => v.trim())}
          />

          <Button title={loading ? 'Guardando...' : 'Agregar visitante'} onPress={agregarVisitante} color="#2563eb" disabled={loading} />

          <FlatList
            data={visitantes}
            keyExtractor={(item) => item._id}
            ListHeaderComponent={<Text style={styles.subtitle}>Visitantes Registrados</Text>}
            contentContainerStyle={{ paddingBottom: 20 }}
            renderItem={({ item }) => (
              <View style={styles.visitanteRow}>
                <View style={{ flex: 1 }}>
                  <Text style={styles.bold}>
                    {item.nombre} {item.apellido}
                  </Text>
                  <Text>Tel: {item.telefono}</Text>
                  <Text>Placas: {item.vehiculo?.placa || 'N/A'}</Text>
                  <Text>Tipo de Vehículo: {item.vehiculo?.tipo}</Text>
                </View>
              </View>
            )}
          />
        </View>
      </View>
    </LinearGradient>
  );
}

const styles = StyleSheet.create({
  gradient_background: {
    flex: 1,
    justifyContent: 'center',
  },
  containerbackground: {
    flex: 1,
    paddingTop: 60,
  },
  container: {
    padding: 20,
    marginTop: 10,
  },
  title: {
    fontSize: 22,
    fontWeight: 'bold',
    marginBottom: 16,
    textAlign: 'center',
  },
  subtitle: {
    fontSize: 18,
    marginTop: 20,
    marginBottom: 8,
    textAlign: 'center',
  },
  input: {
    backgroundColor: '#fff',
    borderWidth: 1,
    borderColor: '#ccc',
    padding: 10,
    marginBottom: 6,
    borderRadius: 6,
  },
  visitanteRow: {
    flexDirection: 'row',
    alignItems: 'flex-start',
    justifyContent: 'space-between',
    paddingVertical: 10,
    borderBottomWidth: 1,
    borderColor: '#eee',
  },
  bold: {
    fontWeight: 'bold',
  },
  error: {
    color: 'red',
    fontSize: 12,
    marginBottom: 6,
    marginLeft: 2,
  },
});
