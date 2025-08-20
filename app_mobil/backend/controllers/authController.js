const Residente = require('../models/Residente');
const bcrypt = require('bcrypt');
const jwt = require('jsonwebtoken');

exports.register = async (req, res) => {
  const { nombre, apellido, usuario, telefono, contrasena } = req.body;

  try {
    const existe = await Residente.findOne({ usuario });
    if (existe) return res.status(400).json({ msg: 'Usuario ya registrado' });

    const salt = await bcrypt.genSalt(10);
    const hash = await bcrypt.hash(contrasena, salt);

    const nuevoResidente = new Residente({
      nombre,
      apellido,
      usuario,
      telefono,
      contrasena: hash,
      estado: 'activo',
    });

    await nuevoResidente.save();

    const token = jwt.sign(
      { id: nuevoResidente._id },
      process.env.JWT_SECRET || 'clave_temporal',
      { expiresIn: '1h' }
    );

    res.status(201).json({
      token,
      residente: {
        id: nuevoResidente._id,
        nombre: nuevoResidente.nombre,
        usuario: nuevoResidente.usuario,
      },
    });
  } catch (error) {
    console.error(error);
    res.status(500).json({ msg: 'Error del servidor' });
  }
};

exports.login = async (req, res) => {
  const { usuario, contrasena } = req.body;

  try {
    const residente = await Residente.findOne({ usuario });
    if (!residente) return res.status(404).json({ msg: 'Usuario no encontrado' });

    const isMatch = await bcrypt.compare(contrasena, residente.contrasena);
    if (!isMatch) return res.status(401).json({ msg: 'Contraseña incorrecta' });

    if (residente.estado !== 'activo')
      return res.status(403).json({ msg: 'Usuario no activo' });

   const token = jwt.sign(
  { id: residente._id },
  process.env.JWT_SECRET,
  { expiresIn: '24H' }
);

    const residenteObj = residente.toObject();
    const { contrasena: pwd, __v, ...residenteSinContrasena } = residenteObj;

    res.json({
      token,
      residente: residenteSinContrasena,
    });
  } catch (error) {
    console.error('Error en login:', error);
    res.status(500).json({ msg: 'Error del servidor' });
  }
};
