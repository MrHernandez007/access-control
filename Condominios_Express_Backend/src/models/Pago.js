const mongoose = require('mongoose');

const PagoSchema = new mongoose.Schema({
  residente_id: {
    type: mongoose.Schema.Types.ObjectId,
    ref: 'Residente',
    required: true
  },
  vivienda_id: {
    type: mongoose.Schema.Types.ObjectId,
    ref: 'Vivienda',
    required: true  // si quieres que sea opcional, o true si obligatorio
  },
  concepto: { type: String, required: true }, // Ejemplo: "Mantenimiento mensual"
  monto: { type: Number, required: true },
  fecha_pago: { type: Date, required: true },
  estado: {
    type: String,
    enum: ['pendiente', 'pagado', 'vencido', 'anulado'],
    default: 'pendiente'
  }
}, {
  collection: 'pagos',
  timestamps: false
});

module.exports = mongoose.model('Pago', PagoSchema);