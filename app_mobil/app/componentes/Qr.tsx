import React from 'react';
import { StyleSheet, View } from 'react-native';
import QRCode from 'react-native-qrcode-svg';

interface QrProps {
  value: string;
  size?: number;
}

const Qr: React.FC<QrProps> = ({ value, size = 200 }) => (
  <View style={styles.container}>
    <QRCode value={value} size={size} />
  </View>
);

const styles = StyleSheet.create({
  container: { alignItems: 'center', marginVertical: 10 },
});

export default Qr;
