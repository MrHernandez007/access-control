const mongoose = require('mongoose');
const Schema = mongoose.Schema;

const generadorQRSchema = new Schema({
  codigo_qr: { type: String, required: true, unique: true },
  fecha_visita: { type: Date, required: true },
  residente_id: { type: mongoose.Schema.Types.ObjectId, ref: 'Residente' },
  visitante_id: { type: mongoose.Schema.Types.ObjectId, ref: 'Visitante' },
}, { collection: 'generador_qr'});

module.exports = mongoose.model('GeneradorQR', generadorQRSchema);
