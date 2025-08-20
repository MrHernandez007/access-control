import { KeyboardAvoidingView, StyleSheet, Text, View } from 'react-native';
import GradientLayout from '../componentes/_layout';

const HomeScreen = () => {
  return (
    <GradientLayout showBackButton>
      <View>
        <Text style={{ color: 'white', fontSize: 20 }}> Iniciar Sesion</Text>
      </View>
            <KeyboardAvoidingView style={styles.KeyboardAvoidingView}></KeyboardAvoidingView>

    </GradientLayout>
  );
};

export default HomeScreen;
const styles = StyleSheet.create({
  KeyboardAvoidingView: {
    flex: 1,}
});
