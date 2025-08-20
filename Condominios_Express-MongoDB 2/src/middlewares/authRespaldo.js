// middleware/auth.js
const jwt = require('jsonwebtoken');
const blacklist = require('../blacklist');

const JWT_SECRET = 'tu_clave_secreta_bien_segura';

exports.verificarToken = (req, res, next) => {
    const token = req.headers.authorization?.split(' ')[1];
    if (!token) return res.status(401).json({ message: 'Token no proporcionado' });

    if (blacklist.has(token)) {
        return res.status(401).json({ message: 'Token inválido (revocado)' });
    }

    jwt.verify(token, JWT_SECRET, (err, decoded) => {
        if (err) return res.status(401).json({ message: 'Token inválido' });
        req.user = decoded;
        next();
    });
};
