const Administrador = require('../models/Administrador');
const bcrypt = require('bcryptjs');
const fs = require('fs');
const path = require('path');

// Obtener todos los administradores
exports.index = async (req, res) => {
  try {
    const administradores = await Administrador.find();
    res.status(200).json(administradores);
  } catch (error) {
    res.status(500).json({ error: 'Error al obtener administradores' });
  }
};

// Mostrar un administrador por ID
exports.show = async (req, res) => {
  try {
    const admin = await Administrador.findById(req.params.id);
    if (!admin) return res.status(404).json({ error: 'Administrador no encontrado' });
    res.json(admin);
  } catch (error) {
    res.status(500).json({ error: 'Error al buscar administrador' });
  }
};

// Crear un nuevo administrador
exports.store = async (req, res) => {
  try {
    const { nombre, apellido, usuario, correo, telefono, contrasena, tipo, estado } = req.body;

    if (await Administrador.findOne({ usuario })) {
      return res.status(422).json({ error: 'El usuario ya existe' });
    }

    if (await Administrador.findOne({ correo })) {
      return res.status(422).json({ error: 'El correo ya existe' });
    }

    const hashedPassword = await bcrypt.hash(contrasena, 10);

    const admin = new Administrador({
      nombre,
      apellido,
      usuario,
      correo,
      telefono,
      contrasena: hashedPassword,
      tipo,
      estado,
      imagen: req.file ? `uploads/${req.file.filename}` : ''
    });

    await admin.save();
    res.status(201).json(admin);

  } catch (error) {
    console.error(error);
    res.status(500).json({ error: 'Error al crear administrador', detalle: error.message });
  }
};

// Actualizar administrador
exports.update = async (req, res) => {
  try {
    const { nombre, apellido, usuario, correo, telefono, contrasena, tipo, estado } = req.body;
    const id = req.params.id;

    const admin = await Administrador.findById(id);
    if (!admin) return res.status(404).json({ error: 'Administrador no encontrado' });

    const usuarioExistente = await Administrador.findOne({ usuario, _id: { $ne: id } });
    if (usuarioExistente) {
      return res.status(422).json({ error: 'El usuario ya está registrado' });
    }

    const correoExistente = await Administrador.findOne({ correo, _id: { $ne: id } });
    if (correoExistente) {
      return res.status(422).json({ error: 'El correo ya está registrado' });
    }

    if (contrasena) {
      admin.contrasena = await bcrypt.hash(contrasena, 10);
    }

    if (req.file) {
      // Elimina la imagen anterior si existe
      if (admin.imagen) {
        const oldPath = path.join(__dirname, '..', 'public', admin.imagen);
        if (fs.existsSync(oldPath)) {
          fs.unlinkSync(oldPath);
        }
      }

      admin.imagen = `uploads/${req.file.filename}`;
    }

    admin.nombre = nombre;
    admin.apellido = apellido;
    admin.usuario = usuario;
    admin.correo = correo;
    admin.telefono = telefono;
    admin.tipo = tipo;
    admin.estado = estado;

    await admin.save();
    res.status(200).json(admin);
  } catch (error) {
    res.status(500).json({ error: 'Error al actualizar administrador', detalle: error.message });
  }
};

// Eliminar (inactivar) administrador
exports.destroy = async (req, res) => {
  try {
    const admin = await Administrador.findById(req.params.id);
    if (!admin) return res.status(404).json({ error: 'Administrador no encontrado' });

    admin.estado = 'inactivo';
    await admin.save();

    res.json({ message: 'Administrador desactivado' });
  } catch (error) {
    res.status(500).json({ error: 'Error al eliminar administrador' });
  }
};

// Perfil del administrador autenticado
exports.perfil = async (req, res) => {
  try {
    const adminId = req.user.sub;
    const admin = await Administrador.findById(adminId).select('-contrasena');
    if (!admin) return res.status(404).json({ error: 'Administrador no encontrado' });

    res.status(200).json({
      estado: true,
      mensaje: 'Perfil obtenido correctamente',
      data: admin
    });
  } catch (error) {
    res.status(500).json({ error: 'Error al obtener perfil' });
  }
};
