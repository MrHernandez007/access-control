const mongoose = require('mongoose');

const VisitaSchema = new mongoose.Schema({
  visitante_id: { type: mongoose.Schema.Types.ObjectId, ref: 'Visitante', required: true },
  residente_id: { type: mongoose.Schema.Types.ObjectId, ref: 'Residente', required: true },
  dia_visita:   { type: Date, default: Date.now },

  // Control del QR
  qrCode:      { type: String, required: true, unique: true, index: true },
  scanCount:   { type: Number, default: 0 },          // lecturas hechas
  maxScans:    { type: Number, default: 2 },          // lecturas permitidas
  qrExpiresAt: { type: Date, required: true },        // vencimiento (24h)
  lastScanAt:  { type: Date },

  estado: { type: String, enum: ['En curso', 'Finalizada', 'Expirado'], default: 'En curso' },
}, { timestamps: true });

module.exports = mongoose.model('Visita', VisitaSchema);
