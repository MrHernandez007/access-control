require('dotenv').config();
const jwt = require('jsonwebtoken');
const Residente = require('../models/Residente');

const verificarToken = async (req, res, next) => {
  const token = req.header('Authorization')?.split(' ')[1];
  if (!token) return res.status(401).json({ msg: 'Token no proporcionado' });

  if (!process.env.JWT_SECRET) {
    return res.status(500).json({ msg: 'Error del servidor: JWT_SECRET no configurado' });
  }

  try {
    const decoded = jwt.verify(token, process.env.JWT_SECRET);
    const residente = await Residente.findById(decoded.id).populate('vivienda');
    if (!residente) return res.status(404).json({ msg: 'Residente no encontrado' });

    req.residente = residente;
    next();
  } catch (error) {
    return res.status(401).json({ msg: 'Token inválido o expirado' });
  }
};

module.exports = { verificarToken };
