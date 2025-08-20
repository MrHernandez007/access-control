import BackButton from '@/app/componentes/BackButton';
import { useAuth } from '@/app/providers/AuthProviders';
import API_URL from '@/backend/config/API_URL';
import { LinearGradient } from 'expo-linear-gradient';
import React, { useCallback, useEffect, useState } from 'react';
import {
  ActivityIndicator,
  RefreshControl,
  ScrollView,
  StyleSheet,
  Text,
  View,
} from 'react-native';

type Vivienda = {
  numero: string;
  tipo?: string;
  calle?: string;
};

const PerfilResidente = () => {
  const { user, token } = useAuth();
  const [vivienda, setVivienda] = useState<Vivienda | null>(null);
  const [loading, setLoading] = useState<boolean>(true);

  const fetchVivienda = useCallback(async () => {
    try {
      setLoading(true);
      const res = await fetch(`${API_URL}/api/viviendas/mi-vivienda`, {
        headers: { Authorization: `Bearer ${token}` },
      });
      if (res.ok) {
        const data = await res.json();
        setVivienda(data);
      } else {
        setVivienda(null);
      }
    } catch {
      setVivienda(null);
    } finally {
      setLoading(false);
    }
  }, [token]);

  useEffect(() => {
    fetchVivienda();
  }, [fetchVivienda]);

  return (
    <LinearGradient
      colors={['#9AA6B2', '#F8FAFC']}
      start={{ x: 0, y: 0 }}
      end={{ x: 1, y: 1 }}
      style={styles.gradient_background}
    >
      <View style={styles.header}>
        <BackButton />
      </View>

      <ScrollView
        style={styles.container}
        refreshControl={
          <RefreshControl refreshing={loading} onRefresh={fetchVivienda} />
        }
      >
        <Text style={styles.titulo}>Perfil del Residente</Text>

        <View style={styles.seccion}>
          <Text style={styles.label}>Nombre:</Text>
          <Text style={styles.valor}>{user?.nombre} {user?.apellido}</Text>

          <Text style={styles.label}>Correo:</Text>
          <Text style={styles.valor}>{user?.correo}</Text>

          <Text style={styles.label}>Teléfono:</Text>
          <Text style={styles.valor}>{user?.telefono}</Text>

          <Text style={styles.label}>Usuario:</Text>
          <Text style={styles.valor}>{user?.usuario}</Text>

          <Text style={styles.label}>Estado:</Text>
          <Text style={styles.valor}>{user?.estado}</Text>

          <Text style={styles.label}>Vivienda:</Text>

          {loading ? (
            <View style={{ paddingVertical: 12 }}>
              <ActivityIndicator size="small" />
            </View>
          ) : vivienda ? (
            <>
              <Text style={styles.valor}>Número: {vivienda.numero}</Text>
              <Text style={styles.valor}>Tipo: {vivienda.tipo || 'No especificado'}</Text>
              <Text style={styles.valor}>Calle: {vivienda.calle || 'No especificada'}</Text>
            </>
          ) : (
            <Text style={styles.valor}>No asignada</Text>
          )}
        </View>
      </ScrollView>
    </LinearGradient>
  );
};

const styles = StyleSheet.create({
  gradient_background: { flex: 1 },
  header: {
    paddingTop: 50,
    paddingHorizontal: 20,
    position: 'absolute',
    top: 0, left: 0, zIndex: 10,
  },
  container: { padding: 20, paddingTop: 100 },
  titulo: {
    fontSize: 28, fontWeight: 'bold', color: '#1E293B',
    textAlign: 'center', marginBottom: 30,
  },
  seccion: {
    backgroundColor: '#E2E8F0', padding: 20, borderRadius: 12, marginBottom: 20,
    shadowColor: '#000', shadowOffset: { width: 0, height: 2 },
    shadowOpacity: 0.1, shadowRadius: 4, elevation: 3, alignItems: 'center',
  },
  label: { fontSize: 16, fontWeight: '600', color: '#334155', marginTop: 10 },
  valor: { fontSize: 16, color: '#475569', marginBottom: 8 },
});

export default PerfilResidente;
