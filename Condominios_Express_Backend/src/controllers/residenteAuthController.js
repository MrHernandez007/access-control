const Residente = require('../models/Residente');
const bcrypt = require('bcryptjs');
const jwt = require('jsonwebtoken');

const JWT_SECRET = process.env.JWT_SECRET || 'clave_secreta_segura';

exports.login = async (req, res) => {
  try {
    const { usuario, contrasena } = req.body;

    if (!usuario || !contrasena) {
      return res.status(400).json({ error: 'Usuario y contraseña son requeridos' });
    }

    const residente = await Residente.findOne({ usuario });

    if (!residente) {
      return res.status(401).json({ error: 'Credenciales incorrectas' });
    }

    const validPassword = await bcrypt.compare(contrasena, residente.contrasena);
    if (!validPassword) {
      return res.status(401).json({ error: 'Credenciales incorrectas' });
    }

    const payload = {
      sub: residente._id,
      rol: residente.rol || 'residente',
      nombre: residente.nombre,
      exp: Math.floor(Date.now() / 1000) + 3600,
    };

    const token = jwt.sign(payload, JWT_SECRET, { algorithm: 'HS256' });

    return res.json({
      token,
      rol: payload.rol,
      nombre: residente.nombre,
      mensaje: 'Login exitoso',
    });
  } catch (error) {
    console.error('Error en login de residente:', error);
    return res.status(500).json({ error: 'Error en el servidor. Intenta más tarde.' });
  }
};

exports.logout = (req, res) => {
  return res.json({ mensaje: 'Logout exitoso' });
};
