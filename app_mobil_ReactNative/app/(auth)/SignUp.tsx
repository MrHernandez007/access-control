// import { zodResolver } from '@hookform/resolvers/zod';
// import { LinearGradient } from 'expo-linear-gradient';
// import { useRouter } from 'expo-router';
// import { StatusBar } from 'expo-status-bar';
// import React from 'react';
// import { useForm } from 'react-hook-form';
// import {
//   KeyboardAvoidingView,
//   Platform,
//   ScrollView,
//   StyleSheet,
//   Text,
//   TouchableOpacity,
//   View,
// } from 'react-native';
// import { z } from 'zod';
// import BackButton from '../componentes/BackButton';
// import Button from '../componentes/Button';
// import Entrada from '../componentes/Entrada';

// const signUpSchema = z.object({
//   nombre: z.string().min(1, 'El nombre es obligatorio'),
//   apellido: z.string().min(1, 'El apellido es obligatorio'),
//   imagen: z.string().url('Debe ser una URL válida de imagen'),
//   zona: z.string().min(1, 'Zona requerida'),
//   numCasa: z.string().min(1, 'Número de casa requerido'),
//   telefono: z.string().min(10, 'Teléfono inválido'),
//   email: z.string().email('Email inválido'),
//   password: z.string().min(6, 'La contraseña debe tener al menos 6 caracteres'),
// });

// type SignUpForm = z.infer<typeof signUpSchema>;

// const SignUp = () => {
//   const router = useRouter();

//   const {
//     control,
//     handleSubmit,
//     formState: { errors },
//   } = useForm<SignUpForm>({
//     resolver: zodResolver(signUpSchema),
//     defaultValues: {
//       nombre: '',
//       apellido: '',
//       imagen: '',
//       zona: '',
//       numCasa: '',
//       telefono: '',
//       email: '',
//       password: '',
//     },
//   });

//   const onSignUp = async (data: SignUpForm) => {
//     try {
//       const payload = {
//         nombre: data.nombre,
//         apellido: data.apellido,
//         usuario: data.email, 
//         contrasena: data.password,
//         correo: data.email,
//         telefono: data.telefono,
//         estado: 'activo',
//         imagen: data.imagen,
//         vivienda_id: parseInt(data.numCasa, 10) || null,
//       };

//       // Cambia esta URL por la IP y puerto de tu backend
//       const response = await fetch('http://192.168.100.3:3000/residentes/', {
//         method: 'POST',
//         headers: { 'Content-Type': 'application/json' },
//         body: JSON.stringify(payload),
//       });

//       if (!response.ok) {
//         const errorData = await response.json();
//         console.error('Error al registrar:', errorData);
//         alert('Error al registrar: ' + (errorData.message || 'Revisa los datos'));
//         return;
//       }

//       const result = await response.json();
//       console.log('Usuario registrado:', result);
//       alert('Registro exitoso!');
//       router.push('/(auth)'); 
//     } catch (error) {
//       console.error('Error en la solicitud:', error);
//       alert('Error en la conexión. Intenta más tarde.');
//     }
//   };

//   return (
//     <LinearGradient
//       colors={['#9AA6B2', '#F8FAFC']}
//       start={{ x: 0, y: 0 }}
//       end={{ x: 1, y: 1 }}
//       style={styles.gradient_background}
//     >
//       <View>
//         <Text style={styles.header}>Crear una cuenta</Text>
//       </View>
//       <BackButton />
//       <ScrollView contentContainerStyle={styles.scroll_container}>
//         <KeyboardAvoidingView
//           behavior={Platform.OS === 'ios' ? 'padding' : 'height'}
//           style={styles.keyboard_container}
//         >
//           <Text style={styles.title}>Registrarse</Text>
//           <View style={styles.form_row}>
//             <View style={styles.column}>
//               <Entrada control={control} name="nombre" placeholder="Nombre" />
//               {/* <Entrada control={control} name="imagen" placeholder="URL de tu imagen" /> */}
//               <Entrada
//                 control={control}
//                 name="telefono"
//                 placeholder="Número de teléfono"
//                 keyboardType="phone-pad"
//               />
//               <Entrada
//                 control={control}
//                 name="password"
//                 placeholder="contraseña"
//                 secureTextEntry
//               />
//             </View>

//             <View style={styles.column}>
//               <Entrada control={control} name="apellido" placeholder="Apellido" />
//               {/* <Entrada control={control} name="zona" placeholder="Zona" /> */}
//               <Entrada control={control} name="numCasa" placeholder="Número de casa" />
//               <Entrada
//                 control={control}
//                 name="email"
//                 placeholder="Correo electrónico"
//                 autoCapitalize="none"
//                 keyboardType="email-address"
//               />
//             </View>
//           </View>

//           <Button title="Registrarse" onPress={handleSubmit(onSignUp)} />

//           <TouchableOpacity onPress={() => router.push('/(auth)')}>
//             <Text style={styles.create_account_text}>
//               ¿Ya tienes una cuenta? <Text style={styles.link_text}>Inicia sesión aquí</Text>
//             </Text>
//           </TouchableOpacity>
//         </KeyboardAvoidingView>
//         <StatusBar style="auto" />
//       </ScrollView>
//     </LinearGradient>
//   );
// };

// export default SignUp;

// const styles = StyleSheet.create({
//   gradient_background: {
//     flex: 1,
//     justifyContent: 'center',
//   },
//   scroll_container: {
//     flexGrow: 1,
//     alignItems: 'center',
//     justifyContent: 'center',
//     padding: 20,
//   },
//   keyboard_container: {
//     width: '100%',
//     alignItems: 'center',
//   },
//   title: {
//     color: '#000',
//     fontSize: 26,
//     fontWeight: 'bold',
//     marginBottom: 20,
//     textAlign: 'center',
//   },
//   form_row: {
//     flexDirection: 'row',
//     justifyContent: 'space-between',
//     gap: 10,
//   },
//   column: {
//     flex: 1,
//     gap: 12,
//   },
//   create_account_text: {
//     marginTop: 15,
//     color: '#000',
//     fontSize: 14,
//     textAlign: 'center',
//   },
//   link_text: {
//     color: '#007AFF',
//     fontWeight: 'bold',
//   },
//   header: {
//     textAlign: 'center',
//     fontWeight: 'bold',
//     marginTop: 15,
//     fontSize: 18,
//   },
// });
