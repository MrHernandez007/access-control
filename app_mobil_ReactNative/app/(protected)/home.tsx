import { LinearGradient } from 'expo-linear-gradient';
import React from 'react';
import { Image, Pressable, ScrollView, StyleSheet, Text, View } from 'react-native';
import BackButton from '../componentes/BackButton';

const SSFN = () => {
  return (
    <LinearGradient
      colors={['#9AA6B2', '#F8FAFC']}
      start={{ x: 0, y: 0 }}
      end={{ x: 1, y: 1 }}
      style={styles.gradient_background}
    >
      <ScrollView contentContainerStyle={styles.scroll_container}>
        <BackButton></BackButton>
        <View style={styles.container_top}>
          <Text style={styles.text_top}>Bienvenido a SC</Text>
          <Text style={styles.text_top}>Hola *Nombre del usuario*</Text>

          <View style={styles.imagen_usuario}>
            <Image
              source={{
                uri: 'https://via.placeholder.com/100',
              }}
              style={styles.image}
            />
          </View>
        </View>

        <View style={styles.container_bottom}>
          <Text style={styles.bottom_text}>Contenido adicional</Text>
          <View style={styles.form}>
              <Pressable>
                Iniciar Sesion
              </Pressable>

          </View>
        </View>
      </ScrollView>
    </LinearGradient>
  );
};

const styles = StyleSheet.create({
  gradient_background: {
    flex: 1,
  },
  scroll_container: {
    flexGrow: 1,
    padding: 20,
    alignItems: 'center',
  },
  container_top: {
    alignItems: 'center',
    marginBottom: 20,
  },
  text_top: {
    color: '#fff',
    fontSize: 18,
    fontFamily: 'sans-serif',
    marginVertical: 10,
    textAlign: 'center',
  },
  imagen_usuario: {
    backgroundColor: 'white',
    borderRadius: 100,
    padding: 5,
    marginTop: 20,
  },
  image: {
    width: 100,
    height: 100,
    borderRadius: 50,
  },
  container_bottom: {
    marginTop: 30,
  },
  bottom_text: {
    color: 'white',
    fontSize: 16,
    textAlign: 'center',
  },
  form: {
    backgroundColor: 'white',
    textAlign: 'center',
    borderRadius: 30 ,

  },
});

export default SSFN;
