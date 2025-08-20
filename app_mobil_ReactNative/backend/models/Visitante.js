const mongoose = require('mongoose');

const visitanteSchema = new mongoose.Schema({
  nombre: { type: String, required: true },
  apellido: { type: String },
  telefono: { type: String },
  tipo: { type: String, default: 'visita' },
  estado: { type: String, default: 'activo' },
  vehiculo: {
    placa: String,
    color: String,
    modelo: String,
    tipo: String,
  },
  residente_id: {
    type: mongoose.Schema.Types.ObjectId,
    ref: 'Residente',
    required: true
  },
}, {
  timestamps: true,
});

module.exports = mongoose.model('Visitante', visitanteSchema);
