const mongoose = require('mongoose');

const ViviendaSchema = new mongoose.Schema({
    numero: { type: String, required: true, maxlength: 20 },
    tipo: { type: String, required: true, maxlength: 50 },
    calle: { type: String, required: true, maxlength: 100 },
    estado: {
        type: String,
        enum: ['activo', 'inactivo'],
        default: 'activo',
        required: true
    },
    residente_id: {
        type: mongoose.Schema.Types.ObjectId,
        ref: 'Residente',
        default: null
    }
}, {
    collection: 'viviendas',
    timestamps: false
});

module.exports = mongoose.model('Vivienda', ViviendaSchema);