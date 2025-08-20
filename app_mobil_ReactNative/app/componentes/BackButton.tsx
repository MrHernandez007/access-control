// componentes/BackButton.tsx
import { useRouter } from 'expo-router';
import React from 'react';
import { StyleSheet, TouchableOpacity } from 'react-native';
import Icon from 'react-native-vector-icons/Ionicons';

interface BackButtonProps {
  fallback?: string;  // Ruta a usar si no hay historial
  color?: string;
}

const BackButton = ({ fallback = '/(auth)', color = '#fff' }: BackButtonProps) => {
  const router = useRouter();

  return (
    <TouchableOpacity
      style={styles.backButton}
      onPress={() => {
        try {
          router.back(); // Intenta regresar
        } catch (e) {
          router.push('/(protected)/home'); 
        }
      }}
    >
      <Icon name="arrow-back" size={24} color={color} />
    </TouchableOpacity>
  );
};

const styles = StyleSheet.create({
  backButton: {
    position: 'absolute',
    top: 50,
    left: 20,
    zIndex: 10,
  },
});

export default BackButton;
