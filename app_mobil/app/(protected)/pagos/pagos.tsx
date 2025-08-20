import BackButton from '@/app/componentes/BackButton';
import { useAuth } from '@/app/providers/AuthProviders';
import API_URL from '@/backend/config/API_URL';
import { LinearGradient } from 'expo-linear-gradient';
import React, { useCallback, useEffect, useState } from 'react';
import { FlatList, RefreshControl, StyleSheet, Text, View } from 'react-native';

type Pago = {
  _id: string;
  concepto: string;
  fecha_pago: string; // viene de mongo
  monto: number;
  estado: 'pendiente' | 'pagado' | string;
};

const PagosScreen = () => {
  const { token } = useAuth();
  const [pagos, setPagos] = useState<Pago[]>([]);
  const [loading, setLoading] = useState(false);

  const fetchPagos = useCallback(async () => {
    try {
      setLoading(true);
      const response = await fetch(`${API_URL}/api/pagos/pendientes`, {
        headers: { Authorization: `Bearer ${token}` },
      });
      if (!response.ok) throw new Error('Error al obtener pagos pendientes');
      const data = await response.json();
      setPagos(Array.isArray(data) ? data : []);
    } catch (error) {
      console.error('Error:', error);
    } finally {
      setLoading(false);
    }
  }, [token]);

  useEffect(() => {
    fetchPagos();
  }, [fetchPagos]);

  const marcarComoPagado = async (id: string) => {
    try {
      const response = await fetch(`${API_URL}/api/pagos/${id}/pagar`, {
        method: 'PUT',
        headers: {
          Authorization: `Bearer ${token}`,
          'Content-Type': 'application/json',
        },
      });

      if (response.ok) {
        setPagos(prev => prev.filter(p => p._id !== id));
      } else {
        console.warn('No se pudo actualizar el estado del pago');
      }
    } catch (err) {
      console.error('Error al pagar:', err);
    }
  };

  const renderPago = ({ item }: { item: Pago }) => (
    <View style={styles.card}>
      <Text style={styles.concepto}>{item.concepto}</Text>
      <Text style={styles.text}>
        Fecha: {item.fecha_pago ? item.fecha_pago.slice(0, 10) : '—'}
      </Text>
      <Text style={styles.text}>Monto: ${item.monto}</Text>
      {/* <TouchableOpacity style={styles.botonPagar} onPress={() => marcarComoPagado(item._id)}>
        <Text style={styles.textoBoton}>Marcar como Pagado</Text>
      </TouchableOpacity> */}
    </View>
  );

  return (
    <LinearGradient colors={['#9AA6B2', '#F8FAFC']} style={styles.container}>
      <BackButton />
      <Text style={styles.titulo}>Pagos Pendientes</Text>

      <FlatList
        data={pagos}
        keyExtractor={(item) => item._id}
        renderItem={renderPago}
        contentContainerStyle={styles.lista}
        refreshControl={
          <RefreshControl refreshing={loading} onRefresh={fetchPagos} />
        }
        ListEmptyComponent={
          !loading ? <Text style={{ textAlign: 'center', color: '#444' }}>No tienes pagos pendientes</Text> : null
        }
      />
    </LinearGradient>
  );
};

export default PagosScreen;

const styles = StyleSheet.create({
  container: {
    flex: 1,
    paddingTop: 50,
    paddingHorizontal: 20,
  },
  titulo: {
    fontSize: 26,
    fontWeight: 'bold',
    marginBottom: 20,
    textAlign: 'center',
  },
  lista: {
    gap: 15,
  },
  card: {
    backgroundColor: '#fff',
    borderRadius: 12,
    padding: 15,
    shadowColor: '#000',
    shadowOpacity: 0.1,
    shadowRadius: 4,
    elevation: 2,
  },
  concepto: {
    fontSize: 18,
    fontWeight: '600',
    marginBottom: 6,
  },
  text: {
    fontSize: 14,
    color: '#444',
  },
  botonPagar: {
    marginTop: 10,
    backgroundColor: '#0F766E',
    paddingVertical: 10,
    borderRadius: 8,
    alignItems: 'center',
  },
  textoBoton: {
    color: '#fff',
    fontWeight: 'bold',
  },
});
