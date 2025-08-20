// middlewares/verificarToken.js
const jwt = require('jsonwebtoken');
const blacklist = require('../blacklist');

const JWT_SECRET = process.env.JWT_SECRET || 'clave_secreta_segura';

module.exports = (req, res, next) => {
  const token = req.headers.authorization?.split(' ')[1];

  if (!token) return res.status(401).json({ message: 'Token no proporcionado' });
  if (blacklist.has(token)) return res.status(401).json({ message: 'Token inválido (blacklist)' });

  try {
    const decoded = jwt.verify(token, JWT_SECRET);
    req.user = decoded; // Ahora puedes usar req.user.sub, req.user.rol, etc.
    next();
  } catch (err) {
    return res.status(401).json({ message: 'Token inválido o expirado' });
  }
};
