const mongoose = require('mongoose');
const bcrypt = require('bcryptjs');

const ResidenteSchema = new mongoose.Schema({
  nombre: { type: String, required: true, maxlength: 50 },
  apellido: { type: String, required: true, maxlength: 50 },
  usuario: { type: String, required: true, unique: true, maxlength: 50 },
  correo: {
    type: String,
    required: true,
    unique: true,
    match: /^[^\s@]+@[^\s@]+\.[^\s@]+$/
  },
  telefono: { type: String, maxlength: 50 },
  contrasena: { type: String, required: true },
  imagen: { type: String, default: '' },
  estado: {
    type: String,
    enum: ['activo', 'inactivo'],
    required: true,
    default: 'activo'
  }
}, {
  collection: 'residentes',
  timestamps: false
});

// Hashear la contraseña antes de guardar
// ResidenteSchema.pre('save', async function (next) {
//   if (!this.isModified('contrasena')) return next();
//   this.contrasena = await bcrypt.hash(this.contrasena, 10);
//   next();
// });

module.exports = mongoose.model('Residente', ResidenteSchema);
