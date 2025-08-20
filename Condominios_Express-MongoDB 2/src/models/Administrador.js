const mongoose = require('mongoose');
const bcrypt = require('bcryptjs');

const AdministradorSchema = new mongoose.Schema({
  nombre: { type: String, required: true, maxlength: 50 },
  apellido: { type: String, required: true, maxlength: 50 },
  telefono: { type: String, default: '', maxlength: 50 },
  correo: {
    type: String,
    required: true,
    unique: true,
    match: /^[^\s@]+@[^\s@]+\.[^\s@]+$/
  },
  usuario: { type: String, required: true, unique: true, maxlength: 50 },
  contrasena: { type: String, required: true },
  tipo: {
    type: String,
    enum: ['admin', 'superadmin'],
    required: true
  },
  estado: {
    type: String,
    enum: ['activo', 'inactivo'],
    required: true,
    default: 'activo'
  },
  imagen: { type: String, default: '' }
}, {
  collection: 'administradores',
  timestamps: false
});

// // Encriptar contraseña antes de guardar
// AdministradorSchema.pre('save', async function (next) {
//   if (!this.isModified('contrasena')) return next();
//   this.contrasena = await bcrypt.hash(this.contrasena, 10);
//   next();
// });

module.exports = mongoose.model('Administrador', AdministradorSchema);
