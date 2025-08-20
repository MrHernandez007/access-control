const mongoose = require('mongoose');

const VehiculoSchema = new mongoose.Schema({
  placa: { type: String, maxlength: 20 },
  color: { type: String, maxlength: 30 },
  modelo: { type: String, maxlength: 50 },
  tipo: { type: String, maxlength: 30 },
}, { _id: false });

const VisitanteSchema = new mongoose.Schema({
  nombre: { type: String, required: true, maxlength: 50 },
  apellido: { type: String, required: true, maxlength: 50 },
  telefono: { type: String, maxlength: 20 },
  tipo: { type: String, enum: ['visita', 'proveedor'], required: true },
  vehiculo: { type: VehiculoSchema, default: {} },
  estado: { type: String, enum: ['activo', 'inactivo'], required: true, default: 'activo' },
  residente_id: { type: mongoose.Schema.Types.ObjectId, ref: 'Residente', required: true }
}, {
  collection: 'visitantes',
  timestamps: false
});

module.exports = mongoose.model('Visitante', VisitanteSchema);
