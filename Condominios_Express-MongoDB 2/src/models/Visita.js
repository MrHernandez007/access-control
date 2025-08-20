const mongoose = require('mongoose');

const VisitaSchema = new mongoose.Schema({
  visitante_id: {
    type: mongoose.Schema.Types.ObjectId,
    ref: 'Visitante',
    required: true
  },
  residente_id: {
    type: mongoose.Schema.Types.ObjectId,
    ref: 'Residente',
    required: true
  },
  fecha: {
    type: Date,
    required: true
  },
  estado: {
    type: String,
    enum: ['En curso', 'Finalizada', 'Cancelada', 'terminada'],
    default: 'En curso'
  }
}, {
  collection: 'visitas',
  timestamps: false
});

module.exports = mongoose.model('Visita', VisitaSchema);
