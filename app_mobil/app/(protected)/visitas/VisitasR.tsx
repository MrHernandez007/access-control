import BackButton from '@/app/componentes/BackButton';
import Qr from '@/app/componentes/Qr';
import { useAuth } from '@/app/providers/AuthProviders';
import API_URL from '@/backend/config/API_URL';
import { LinearGradient } from 'expo-linear-gradient';
import React, { useCallback, useEffect, useState } from 'react';
import {
  ActivityIndicator,
  FlatList,
  Modal,
  Pressable,
  RefreshControl,
  StyleSheet,
  Text,
  View,
} from 'react-native';

export default function UsuariosScreen() {
  const { token } = useAuth();
  const [visitas, setVisitas] = useState<any[]>([]);
  const [loading, setLoading] = useState(false);
  const [errorMsg, setErrorMsg] = useState<string | null>(null);

  // Modal QR
  const [modalVisible, setModalVisible] = useState(false);
  const [qrSeleccionado, setQrSeleccionado] = useState<string | null>(null);

  const fetchVisitas = useCallback(async () => {
    try {
      setLoading(true);
      setErrorMsg(null);

      const response = await fetch(`${API_URL}/api/visitas/activas`, {
        headers: {
          Authorization: `Bearer ${token}`,
          'Content-Type': 'application/json',
        },
      });

      if (!response.ok) {
        await response.text().catch(() => null);
        setErrorMsg('No se pudieron cargar las visitas.');
        setVisitas([]);
        return;
      }

      const data = await response.json();

      if (Array.isArray(data)) {
        // ordenar por fecha (más recientes primero)
        const ordenadas = [...data].sort((a, b) => {
          const fa = new Date(a?.dia_visita || 0).getTime();
          const fb = new Date(b?.dia_visita || 0).getTime();
          return fb - fa;
        });
        setVisitas(ordenadas);
      } else {
        setVisitas([]);
      }
    } catch (error) {
      setErrorMsg('Error al obtener visitas activas.');
      setVisitas([]);
    } finally {
      setLoading(false);
    }
  }, [token]);

  useEffect(() => {
    fetchVisitas();
  }, [fetchVisitas]);

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
          <Text style={styles.title}>
            Visitas Activas {visitas.length ? `(${visitas.length})` : ''}
          </Text>

          {errorMsg ? (
            <Text style={{ textAlign: 'center', color: '#334155', marginBottom: 10 }}>
              {errorMsg}
            </Text>
          ) : null}

          {loading && visitas.length === 0 ? (
            <View style={{ paddingTop: 60, alignItems: 'center' }}>
              <ActivityIndicator size="large" />
            </View>
          ) : (
            <FlatList
              data={visitas}
              keyExtractor={(item) => item?._id ?? String(item?.dia_visita ?? Math.random())}
              renderItem={({ item }) => {
                const visitante = item?.visitante_id;
                const fechaStr = item?.dia_visita
                  ? new Date(item.dia_visita).toLocaleDateString('es-MX', {
                      day: '2-digit',
                      month: 'short',
                      year: 'numeric',
                    })
                  : '—';

                return (
                  <View style={styles.card}>
                    <Text style={styles.cardTitle}>
                      Visitante: {visitante?.nombre ?? '—'} {visitante?.apellido ?? ''}
                    </Text>
                    <Text style={styles.cardText}>Teléfono: {visitante?.telefono ?? '—'}</Text>
                    <Text style={styles.cardText}>Fecha: {fechaStr}</Text>

                    {visitante?.vehiculo && (
                      <>
                        <Text style={styles.cardText}>
                          Vehículo: {visitante.vehiculo?.modelo ?? '—'} ({visitante.vehiculo?.color ?? '—'})
                        </Text>
                        <Text style={styles.cardText}>
                          Placa: {visitante.vehiculo?.placa ?? '—'}
                        </Text>
                      </>
                    )}

                    {/* Botón Ver QR */}
                    <Pressable
                      style={styles.btnVerQR}
                      onPress={() => {
                        const valorQR = item?.qrCode || item?._id;
                        setQrSeleccionado(valorQR || null);
                        setModalVisible(true);
                      }}
                    >
                      <Text style={styles.btnText}>Ver QR</Text>
                    </Pressable>
                  </View>
                );
              }}
              refreshControl={<RefreshControl refreshing={loading} onRefresh={fetchVisitas} />}
              ListEmptyComponent={
                !loading ? (
                  <Text style={{ textAlign: 'center', color: '#334155' }}>
                    No hay visitas activas
                  </Text>
                ) : null
              }
              contentContainerStyle={{ paddingBottom: 24 }}
            />
          )}
        </View>
      </View>

      {/* Modal para mostrar el QR */}
      <Modal
        animationType="slide"
        transparent={true}
        visible={modalVisible}
        onRequestClose={() => setModalVisible(false)}
      >
        <View style={styles.modalBackground}>
          <View style={styles.modalContainer}>
            <Text style={styles.modalTitle}>Código QR de la Visita</Text>
            {qrSeleccionado ? <Qr value={qrSeleccionado} size={260} /> : <Text>—</Text>}

            <Pressable
              style={[styles.btnVerQR, { marginTop: 20, backgroundColor: '#ef4444' }]}
              onPress={() => setModalVisible(false)}
            >
              <Text style={styles.btnText}>Cerrar</Text>
            </Pressable>
          </View>
        </View>
      </Modal>
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
    padding: 20,
    marginTop: 20,
  },
  title: {
    fontSize: 28,
    fontWeight: 'bold',
    textAlign: 'center',
    color: '#1E293B',
    marginBottom: 30,
  },
  card: {
    backgroundColor: '#FFFFFF',
    borderRadius: 16,
    padding: 20,
    marginBottom: 20,
    borderWidth: 1,
    borderColor: '#CBD5E1',
    shadowColor: '#000',
    shadowOffset: { width: 0, height: 3 },
    shadowOpacity: 0.15,
    shadowRadius: 6,
    elevation: 5,
  },
  cardTitle: {
    fontSize: 18,
    fontWeight: '600',
    color: '#0F172A',
    marginBottom: 10,
  },
  cardText: {
    fontSize: 16,
    color: '#334155',
    marginBottom: 6,
  },
  btnVerQR: {
    marginTop: 10,
    backgroundColor: '#3b82f6',
    paddingVertical: 10,
    borderRadius: 8,
    alignItems: 'center',
  },
  btnText: {
    color: '#fff',
    fontSize: 16,
    fontWeight: '600',
  },
  modalBackground: {
    flex: 1,
    backgroundColor: 'rgba(0,0,0,0.5)',
    justifyContent: 'center',
    alignItems: 'center',
    padding: 16,
  },
  modalContainer: {
    backgroundColor: '#fff',
    borderRadius: 12,
    padding: 20,
    alignItems: 'center',
    width: '100%',
    maxWidth: 420,
  },
  modalTitle: {
    fontSize: 20,
    fontWeight: 'bold',
    marginBottom: 15,
    textAlign: 'center',
    color: '#0F172A',
  },
});
