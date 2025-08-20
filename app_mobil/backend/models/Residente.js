const mongoose = require('mongoose');

const residenteSchema = new mongoose.Schema({
  nombre: String,
  apellido: String,
  usuario: String,
  correo: String,
  telefono: String,
  contrasena: String,
  imagen: String,
  estado: { type: String, default: 'activo' },
}, {
  timestamps: true,
  toJSON: { virtuals: true },
  toObject: { virtuals: true },
});

residenteSchema.virtual('vivienda', {
  ref: 'Vivienda',
  localField: '_id',
  foreignField: 'residente_id',
  justOne: true,
});

module.exports = mongoose.model('Residente', residenteSchema);
