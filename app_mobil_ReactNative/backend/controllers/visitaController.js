const crypto = require('crypto');
const Visita = require('../models/Visita');

const generarQrCode = () => crypto.randomBytes(8).toString('hex').toUpperCase();

// POST /api/visitas
// body: { visitante_id, dia_visita }
exports.crearVisita = async (req, res) => {
  try {
    const residenteId = req.residente?._id || req.residente?.id || req.residenteId; // según tu middleware
    const { visitante_id, dia_visita } = req.body;

    if (!visitante_id || !dia_visita) {
      return res.status(400).json({ message: 'Faltan datos requeridos' });
    }

    const qrCode = generarQrCode();
    const qrExpiresAt = new Date(Date.now() + 24 * 60 * 60 * 1000); 

    const nuevaVisita = await Visita.create({
      residente_id: residenteId,
      visitante_id,
      dia_visita,
      qrCode,
      scanCount: 0,
      maxScans: 2,
      qrExpiresAt,
      estado: 'En curso',
    });

    return res.status(201).json({
      message: 'Visita creada',
      data: {
        _id: nuevaVisita._id,
        visitante_id: nuevaVisita.visitante_id,
        residente_id: nuevaVisita.residente_id,
        dia_visita: nuevaVisita.dia_visita,
        estado: nuevaVisita.estado,
        qrCode: nuevaVisita.qrCode,
        maxScans: nuevaVisita.maxScans,
        qrExpiresAt: nuevaVisita.qrExpiresAt
      }
    });
  } catch (err) {
    console.error('Error al crear la visita:', err);
    if (err?.code === 11000 && err?.keyPattern?.qrCode) {
      return res.status(409).json({ message: 'Colisión de QR, intenta de nuevo' });
    }
    return res.status(500).json({ message: 'Error al crear la visita' });
  }
};

exports.obtenerVisitasActivas = async (req, res) => {
  try {
    const residenteId = req.residente?._id || req.residente?.id || req.residenteId;

    const visitas = await Visita.find({
      estado: 'En curso',
      residente_id: residenteId,
    }).populate('visitante_id');

    return res.status(200).json(visitas);
  } catch (error) {
    console.error('Error al obtener visitas activas:', error);
    return res.status(500).json({ message: 'Error al obtener visitas activas' });
  }
};
