import React from 'react';
import { Controller } from 'react-hook-form';
import { StyleSheet, Text, TextInput, TextInputProps, View } from 'react-native';

type EntradaProps = {
control: any;
name: string;
} & TextInputProps;
export default function Entrada({control, name, ...props}: EntradaProps) {
return (
    <Controller
        control={control}
        name={name}
        // rules={{required: 'Campo requerido'}}
        defaultValue=''
        render={({field: { value, onChange, onBlur}, fieldState: {error},
        }) => (
                <View style = {styles.container}>
            <TextInput 
            {...props}
            style={[styles.input, props.style]}
            value={value}
            onChangeText={onChange}
            onBlur={onBlur}
            />
            {error && <Text style={styles.error}>{error?.message}</Text>}
            </View>
        )}
/>
);
}
const styles = StyleSheet.create({
    container: {
        marginBottom: 10,
        alignItems: 'center',
    },
input:{
borderWidth:1,
padding:10,
borderRadius:5,
borderColor:'#ccc',
},
error:{
    color: "red",
    fontSize: 12,
    marginTop: 5,
}
})