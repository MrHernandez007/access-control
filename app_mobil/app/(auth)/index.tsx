import { zodResolver } from '@hookform/resolvers/zod';
import { LinearGradient } from 'expo-linear-gradient';
import { useRouter } from 'expo-router';
import { StatusBar } from 'expo-status-bar';
import React, { useState } from 'react';
import { useForm } from 'react-hook-form';
import {
  Alert,
  Image,
  KeyboardAvoidingView,
  Platform,
  ScrollView,
  StyleSheet,
  Text,
  View,
} from 'react-native';
import { z } from 'zod';
import API_URL from '../../backend/config/API_URL';

import Button from '../componentes/Button';
import Entrada from '../componentes/Entrada';
import { useAuth } from '../providers/AuthProviders';

const signInSchema = z.object({
  usuario: z.string({ message: 'El usuario es requerido' }).min(1, 'El usuario es obligatorio'),
  password: z.string({ message: 'La contraseña es obligatoria' }).min(1, 'Contraseña incorrecta'),
});

type SignInForm = z.infer<typeof signInSchema>;

export default function LoginScreen() {
  const router = useRouter();
  const { signIn } = useAuth();
  const [loading, setLoading] = useState(false);

  const {
    control,
    handleSubmit,
    formState: { errors },
  } = useForm<SignInForm>({
    resolver: zodResolver(signInSchema),
    defaultValues: {
      usuario: '',
      password: '',
    },
  });

  const onSignIn = async (data: SignInForm) => {
    setLoading(true);
    try {
      const response = await fetch(`${API_URL}/api/auth/login`, {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
        },
        body: JSON.stringify({
          usuario: data.usuario,
          contrasena: data.password,
        }),
      });

      const resData = await response.json();

      if (!response.ok) {
        Alert.alert('Error', resData.msg || 'Error al iniciar sesión');
        setLoading(false);
        return;
      }

      signIn(resData.token, resData.residente);
      router.replace('/(protected)');
    } catch (error) {
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
      <ScrollView contentContainerStyle={styles.scroll_container}>
        <KeyboardAvoidingView
          behavior={Platform.OS === 'ios' ? 'padding' : 'height'}
          style={styles.keyboard_container}
        >
          {/* Logo */}
          <Text style={styles.header}>Bienvenido a</Text>
          <Image
            source={require('../../assets/logo.png')}
            style={styles.logo}
            resizeMode="contain"
          />

          {/* Encabezado */}
          <Text style={styles.title}>Iniciar Sesión</Text>

          {/* Formulario */}
          <View style={styles.form}>
            <Entrada
              placeholder="Usuario"
              autoFocus
              autoCapitalize="none"
              control={control}
              name="usuario"
              style={styles.input}
            />
            <Entrada
              control={control}
              name="password"
              placeholder="Contraseña"
              secureTextEntry={true}
              style={styles.input}
            />
          </View>

          <Button
            title={loading ? 'Cargando...' : 'Iniciar Sesión'}
            onPress={handleSubmit(onSignIn)}
            disabled={loading}
          />
        </KeyboardAvoidingView>
        <StatusBar style="auto" />
      </ScrollView>
    </LinearGradient>
  );
}

const styles = StyleSheet.create({
  gradient_background: {
    flex: 1,
    justifyContent: 'center',
  },
  scroll_container: {
    flexGrow: 1,
    alignItems: 'center',
    justifyContent: 'center',
    padding: 20,
  },
  keyboard_container: {
    width: '100%',
    alignItems: 'center',
  },
  logo: {
    width: 150,
    height: 150,
    marginBottom: 10,
  },
  header: {
    fontSize: 20,
    fontWeight: '600',
    textAlign: 'center',
    marginBottom: 4,
    color: '#1E293B',
  },
  title: {
    color: '#000',
    fontSize: 26,
    fontWeight: 'bold',
    marginBottom: 20,
    textAlign: 'center',
  },
  form: {
    width: '100%',
    gap: 12,
    marginBottom: 20,
  },
  input: {
    paddingHorizontal: 14,
    paddingVertical: 12,
    fontSize: 16,
    borderRadius: 8,
  },
  error: {
    color: 'red',
    fontSize: 12,
  },
});
