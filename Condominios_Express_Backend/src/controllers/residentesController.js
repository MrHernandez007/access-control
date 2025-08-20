const Residente = require('../models/Residente');
const bcrypt = require('bcryptjs');
const fs = require('fs');
const path = require('path');

// Listar todos o filtrados por estado según rol y query
const index = async (req, res) => {
  try {
    let filtro = {};

    // Si viene ?estado=activo o ?estado=inactivo, lo agregamos al filtro
    if (req.query.estado) {
      filtro.estado = req.query.estado;
    }

    // Si no es admin ni superadmin, solo verá residentes con estado y _id igual a su id (o solo su propio perfil)
    if (req.user.rol !== 'admin' && req.user.rol !== 'superadmin') {
      // Si quieres que vea solo su propio perfil:
      filtro._id = req.user.sub;

      // O si quieres que vea todos los residentes activos (pero no inactivos):
      // filtro.estado = 'activo';
    }

    const residentes = await Residente.find(filtro);
    res.status(200).json(residentes);
  } catch (error) {
    res.status(500).json({ error: 'Error al obtener residentes' });
  }
};

// Mostrar uno
const show = async (req, res) => {
  try {
    const residente = await Residente.findById(req.params.id);
    if (!residente) return res.status(404).json({ error: 'Residente no encontrado' });
    res.status(200).json(residente);
  } catch (error) {
    res.status(500).json({ error: 'Error al buscar residente' });
  }
};

// Crear nuevo
const store = async (req, res) => {
  try {
    const { nombre, apellido, usuario, correo, telefono, contrasena, estado } = req.body;

    if (!nombre || !apellido || !usuario || !correo || !contrasena || !estado) {
      return res.status(400).json({ error: 'Faltan campos requeridos' });
    }

    if (await Residente.findOne({ usuario })) {
      return res.status(422).json({ error: 'El usuario ya existe' });
    }

    if (await Residente.findOne({ correo })) {
      return res.status(422).json({ error: 'El correo ya existe' });
    }

    // Aquí el hash de la contraseña
    const hashedPassword = await bcrypt.hash(contrasena, 10);

    const nuevoResidente = new Residente({
      nombre,
      apellido,
      usuario,
      correo,
      telefono,
      contrasena: hashedPassword,
      estado,
      imagen: req.file ? req.file.filename : ''
    });

    await nuevoResidente.save();
    res.status(201).json(nuevoResidente);
  } catch (error) {
    res.status(500).json({ error: 'Error al crear residente' });
  }
};


// Actualizar
const update = async (req, res) => {
  try {
    const residente = await Residente.findById(req.params.id);
    if (!residente) return res.status(404).json({ error: 'Residente no encontrado' });

    const { nombre, apellido, usuario, correo, telefono, contrasena, estado } = req.body;

    const existenteUsuario = await Residente.findOne({ usuario, _id: { $ne: req.params.id } });
    if (existenteUsuario) return res.status(422).json({ error: 'El usuario ya está registrado' });

    const existenteCorreo = await Residente.findOne({ correo, _id: { $ne: req.params.id } });
    if (existenteCorreo) return res.status(422).json({ error: 'El correo ya está registrado' });

    residente.nombre = nombre;
    residente.apellido = apellido;
    residente.usuario = usuario;
    residente.correo = correo;
    residente.telefono = telefono;
    residente.estado = estado;

    if (contrasena) {
      residente.contrasena = await bcrypt.hash(contrasena, 10);
    }

    if (req.file) {
      if (residente.imagen) {
        const rutaAnterior = path.join(__dirname, '../public/residentes/', residente.imagen);
        if (fs.existsSync(rutaAnterior)) {
          fs.unlinkSync(rutaAnterior);
        }
      }
      residente.imagen = req.file.filename;
    }

    await residente.save();
    res.status(200).json(residente);
  } catch (error) {
    res.status(500).json({ error: 'Error al actualizar residente' });
  }
};

// Eliminar (cambiar estado)
const destroy = async (req, res) => {
  try {
    const residente = await Residente.findById(req.params.id);
    if (!residente) return res.status(404).json({ error: 'Residente no encontrado' });

    residente.estado = 'inactivo';
    await residente.save();

    res.status(200).json({ message: 'Residente eliminado correctamente' });
  } catch (error) {
    res.status(500).json({ error: 'Error al eliminar residente' });
  }
};

const perfil = async (req, res) => {
  try {
    const residenteId = req.user.sub;
    const residente = await Residente.findById(residenteId).select('-contrasena');
    if (!residente) return res.status(404).json({ error: 'Residente no encontrado' });

    res.status(200).json({
      estado: true,
      mensaje: 'Perfil obtenido correctamente',
      data: residente
    });
  } catch (error) {
    res.status(500).json({ error: 'Error al obtener perfil' });
  }
};

module.exports = {
  index,
  show,
  store,
  update,
  destroy,
  perfil
};
