// componentes/_layout.tsx
import { LinearGradient } from 'expo-linear-gradient';
import { useRouter } from 'expo-router';
import React from 'react';
import { ScrollView, StyleSheet, TouchableOpacity } from 'react-native';
import Icon from 'react-native-vector-icons/Ionicons';

interface Props {
  children: React.ReactNode;
  showBackButton?: boolean;
}

const GradientLayout = ({ children, showBackButton }: Props) => {
  const router = useRouter();

  return (
    <LinearGradient
      colors={['#9AA6B2', '#F8FAFC']}
      start={{ x: 0, y: 0 }}
      end={{ x: 1, y: 1 }}
      style={styles.gradient_background}
    >
      {showBackButton && (
        <TouchableOpacity
          style={styles.backButton}
          onPress={() => router.replace('/(auth)')} // Aquí haces la redirección deseada
        >
          <Icon name="arrow-back" size={24} color="#fff" />
        </TouchableOpacity>
      )}
      <ScrollView contentContainerStyle={styles.scroll_container}>
        {children}
      </ScrollView>
    </LinearGradient>
  );
};

const styles = StyleSheet.create({
  gradient_background: {
    flex: 1,
    justifyContent: 'center',
  },
  scroll_container: {
    flexGrow: 1,
    padding: 20,
    alignItems: 'center',
    justifyContent: 'center',
  },
  backButton: {
    position: 'absolute',
    top: 50,
    left: 20,
    zIndex: 10,
  },
});

export default GradientLayout;
