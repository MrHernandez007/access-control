const Administrador = require('../models/Administrador');
const bcrypt = require('bcryptjs');
const jwt = require('jsonwebtoken');

const JWT_SECRET = process.env.JWT_SECRET || 'clave_secreta_segura';

exports.login = async (req, res) => {
console.log('Login recibido:', req.body);
  try {
    const { usuario, contrasena } = req.body;

    const admin = await Administrador.findOne({ usuario });

    if (!admin) {
      return res.status(401).json({ ok: false, mensaje: 'Usuario no encontrado' });
    }

    const passValido = await bcrypt.compare(contrasena, admin.contrasena);
    if (!passValido) {
      return res.status(401).json({ ok: false, mensaje: 'Credenciales incorrectas' });
    }

    const payload = {
  sub: admin._id,
  rol: admin.tipo || 'admin', // puede ser 'admin' o 'superadmin'
  nombre: admin.nombre,
  exp: Math.floor(Date.now() / 1000) + 3600,
  };

    const token = jwt.sign(payload, JWT_SECRET, { algorithm: 'HS256' });

    return res.json({
      ok: true,
      token,
      rol: payload.rol,
      nombre: admin.nombre,
      mensaje: 'Login exitoso',
    });
  } catch (error) {
    console.error('Error en login admin:', error);
    return res.status(500).json({ ok: false, mensaje: 'Error del servidor' });
  }
};

exports.logout = (req, res) => {
  console.log('Se llamó a /admin/logout'); // 👈 importante para probar
  return res.json({ ok: true, mensaje: 'Sesión cerrada correctamente' });
};
