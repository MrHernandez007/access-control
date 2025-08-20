import { Slot } from 'expo-router';
import { AuthProvider } from '../app/providers/AuthProviders';

export default function RootLayout() {
  return (
    <AuthProvider>
      <Slot />
    </AuthProvider>
  );
}
