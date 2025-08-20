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
    activeOpacity={0.8}
  >
    <Icon name={iconName} size={32} color="#fff" style={{ marginBottom: 8 }} />
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
        <Text style={styles.menuTitle}>Menú</Text>
        <View style={styles.row}>
          <MenuItem
            title="Visitas"
            iconName="walk-outline"
            color="#2196F3"
            onPress={() => router.push('/(protected)/visitas')}
          />
          <MenuItem
            title="Perfil"
            iconName="person-outline"
            color="#46c25aff"
            onPress={() => router.push('/(protected)/profile/profile')}
          />
          <MenuItem
            title="Pagos"
            iconName="card-outline"
            color="#9C27B0"
            onPress={() => router.push('/(protected)/pagos/pagos')}
          />
          <MenuItem
            title="Cerrar Sesión"
            iconName="log-out-outline"
            color="#ff0202ff"
            onPress={() => router.push('/(auth)')}
          />
        </View>
      </View>
    </LinearGradient>
  );
}

const styles = StyleSheet.create({
  containerbackground: {
    flex: 1,
    justifyContent: 'center',
    paddingTop: 20,
  },
  gradient_background: {
    flex: 1,
    justifyContent: 'center',
  },
  menuTitle: {
    fontSize: 26,
    fontWeight: 'bold',
    textAlign: 'center',
    color: '#1E293B',
    marginBottom: 30,
  },
  row: {
    flexDirection: 'row',
    flexWrap: 'wrap',
    justifyContent: 'center',
    gap: 15,
  },
  menuItem: {
    width: 120,
    height: 120,
    margin: 10,
    borderRadius: 16,
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
    fontSize: 14,
    textAlign: 'center',
    fontWeight: '600',
  },
});
