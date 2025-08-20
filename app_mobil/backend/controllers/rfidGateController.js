// backend/controllers/rfidGateController.js
const Residente = require('../models/Residente');

exports.permitirRFID = async (req, res) => {
  try {
    const { residente_id } = req.body;
    if (!residente_id) return res.status(400).json({ message: 'Falta residente_id' });

    const r = await Residente.findById(residente_id).lean();
    if (!r) return res.status(404).json({ message: 'Residente no encontrado' });

    if (r.estado !== 'activo') {
      return res.status(403).json({ message: 'Residente inactivo' });
    }

    return res.status(200).json({ message: 'Acceso permitido', residente_id: r._id });
  } catch (e) {
    console.error('permitirRFID error:', e);
    return res.status(500).json({ message: 'Error del servidor' });
  }
};
