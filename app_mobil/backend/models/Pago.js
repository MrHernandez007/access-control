const mongoose = require('mongoose');

const pagoSchema = new mongoose.Schema({
  residente_id: { type: mongoose.Schema.Types.ObjectId, ref: 'Residente', required: true },
  concepto: { type: String, required: true },
  monto: { type: Number, required: true },
  fecha_pago: { type: Date, required: true },
  // usa minúsculas para consistencia: 'pendiente' | 'pagado'
  estado: { type: String, enum: ['pendiente', 'pagado'], default: 'pendiente' },
}, { timestamps: true });

module.exports = mongoose.model('Pago', pagoSchema);
