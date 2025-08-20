const mongoose = require('mongoose');

const viviendaSchema = new mongoose.Schema({
  numero: { type: String, required: true },
  tipo: { type: String },
  calle: { type: String },
  estado: { type: String, default: 'activo' },
  residente_id: {
    type: mongoose.Schema.Types.ObjectId,
    ref: 'Residente',
    required: true,
  },
}, { timestamps: true });

module.exports = mongoose.model('Vivienda', viviendaSchema);
