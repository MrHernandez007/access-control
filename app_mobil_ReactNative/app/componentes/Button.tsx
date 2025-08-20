import React from 'react';
import { GestureResponderEvent, Pressable, StyleSheet, Text } from 'react-native';

interface ButtonProps {
  title: string;
  onPress: (event: GestureResponderEvent) => void;
  disabled?: boolean;
}

const Button = ({ title, onPress, disabled = false }: ButtonProps) => {
  return (
    <Pressable
      style={[styles.button, disabled && styles.buttonDisabled]}
      onPress={onPress}
      disabled={disabled}
    >
      <Text style={[styles.button_text, disabled && styles.textDisabled]}>
        {title}
      </Text>
    </Pressable>
  );
};

const styles = StyleSheet.create({
  button: {
    backgroundColor: 'white',
    textAlign: 'center',
    borderRadius: 30,
    padding: 10,
    margin: 10,
  },
  buttonDisabled: {
    backgroundColor: '#ccc',
  },
  button_text: {
    color: 'black',
    fontSize: 16,
    textAlign: 'center',
  },
  textDisabled: {
    color: '#888',
  },
});

export default Button;
