const Residente = require('../models/Residente');

exports.getAllResidentes = async (req, res) => {
  try {
    const residentes = await Residente.find();
    res.json(residentes);
  } catch (err) {
    res.status(500).json({ message: err.message });
  }
};

exports.getResidenteById = async (req, res) => {
  try {
    const residente = await Residente.findById(req.params.id);
    if (!residente) return res.status(404).json({ message: 'No encontrado' });
    res.json(residente);
  } catch (err) {
    res.status(500).json({ message: err.message });
  }
};
