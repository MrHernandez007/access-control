import BackButton from '@/app/componentes/BackButton';
import { LinearGradient } from 'expo-linear-gradient';
import { useRouter } from 'expo-router';
import React from 'react';
import { StyleSheet, Text, TouchableOpacity, View } from 'react-native';
import Icon from 'react-native-vector-icons/Ionicons';

interface MenuItemProps {
  title: string;
  iconName: string;
  color: string;
  onPress: () => void;
}

const MenuItem = ({ title, iconName, color, onPress }: MenuItemProps) => (
  <TouchableOpacity
    style={[styles.menuItem, { backgroundColor: color }]}
    onPress={onPress}
    activeOpacity={0.85}
  >
    <Icon name={iconName} size={32} color="#fff" />
    <Text style={styles.menuText}>{title}</Text>
  </TouchableOpacity>
);

export default function HomeScreen() {
  const router = useRouter();
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
          <Text style={styles.menuTitle}>Menú</Text>

          <View style={styles.grid}>
            <MenuItem
              title="Agendar Visita"
              iconName="calendar-outline"
              color="#2196F3"
              onPress={() => router.push('/(protected)/visitas/Agendar')}
            />
            <MenuItem
              title="Visitas"
              iconName="person-outline"
              color="#FF5722"
              onPress={() => router.push('/(protected)/visitas/VisitasR')}
            />
            <MenuItem
              title="Visitantes"
              iconName="person-outline"
              color="#a078f7e1"
              onPress={() => router.push('/(protected)/visitas/crearVisitante')}
            />
          </View>
        </View>
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
    flex: 1,
    alignItems: 'center',
    justifyContent: 'center', // 🔹 Centrado vertical
  },
  menuTitle: {
    fontSize: 26,
    fontWeight: 'bold',
    textAlign: 'center',
    color: '#1E293B',
    marginBottom: 20,
  },
  grid: {
    flexDirection: 'row',
    flexWrap: 'wrap',
    justifyContent: 'center', // 🔹 Centrado horizontal
    alignItems: 'center', // 🔹 Centrado vertical dentro del contenedor
    gap: 10,
  },
  menuItem: {
    width: 120,
    height: 120,
    margin: 10,
    borderRadius: 12,
    justifyContent: 'center',
    alignItems: 'center',
    elevation: 3,
    shadowColor: '#000',
    shadowOpacity: 0.1,
    shadowOffset: { width: 2, height: 2 },
    shadowRadius: 4,
  },
  menuText: {
    color: '#fff',
    marginTop: 6,
    fontSize: 13,
    textAlign: 'center',
    fontWeight: '600',
  },
});
