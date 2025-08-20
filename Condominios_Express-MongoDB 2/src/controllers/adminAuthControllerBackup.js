const Administrador = require('../models/Administrador');
const bcrypt = require('bcryptjs');
const jwt = require('jsonwebtoken');

const JWT_SECRET = process.env.JWT_SECRET || 'clave_secreta_segura';

// Procesar login (API JSON)
exports.login = async (req, res) => {
  try {
    const { usuario, contrasena } = req.body;
    console.log('Usuario recibido:', usuario);
    console.log('Contraseña recibida:', contrasena);

    const admin = await Administrador.findOne({ usuario });
    console.log('Admin encontrado:', admin);

    if (!admin) {
      return res.status(401).json({
        ok: false,
        mensaje: 'Usuario no encontrado'
      });
    }

    const passValido = await bcrypt.compare(contrasena, admin.contrasena);
    console.log('Contraseña válida:', passValido);

    if (!passValido) {
      return res.status(401).json({
        ok: false,
        mensaje: 'Credenciales incorrectas'
      });
    }

    const payload = {
      sub: admin._id,
      rol: 'admin',
      nombre: admin.nombre,
      exp: Math.floor(Date.now() / 1000) + 3600
    };

    const token = jwt.sign(payload, JWT_SECRET, { algorithm: 'HS256' });

    return res.json({
      ok: true,
      token,
      rol: 'admin',
      nombre: admin.nombre
    });
  } catch (error) {
    console.error('Error en login admin:', error);
    return res.status(500).json({
      ok: false,
      mensaje: 'Error del servidor'
    });
  }
};

// Logout (opcional si solo borras el token del frontend)
exports.logout = (req, res) => {
  // En una API, normalmente solo se borra el token del cliente
  return res.json({
    ok: true,
    mensaje: 'Sesión cerrada correctamente'
  });
};
