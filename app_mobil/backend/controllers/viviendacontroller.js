const Vivienda = require('../models/Vivienda');

exports.obtenerMiVivienda = async (req, res) => {
  try {
    const residenteId = req.residente?._id || req.residente?.id;
    if (!residenteId) return res.status(401).json({ msg: 'Sin residente en contexto' });

    const vivienda = await Vivienda.findOne({ residente_id: residenteId });
    if (!vivienda) return res.status(404).json({ msg: 'Vivienda no encontrada' });

    res.json(vivienda);
  } catch (error) {
    res.status(500).json({ msg: 'No se pudo obtener la vivienda' });
  }
};
