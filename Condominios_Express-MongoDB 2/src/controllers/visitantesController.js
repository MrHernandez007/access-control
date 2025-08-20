const Visitante = require('../models/Visitante');
const mongoose = require('mongoose');

// 🔹 Listar visitantes
const index = async (req, res) => {
  try {
    let filtro = {};

    // Filtrar por estado si viene en query (activo, inactivo)
    if (req.query.estado && ['activo', 'inactivo'].includes(req.query.estado)) {
      filtro.estado = req.query.estado;
    }

    if (req.user.rol === 'admin' || req.user.rol === 'superadmin') {
      // Admin y superadmin ven todos, con filtro opcional
      // No agregamos filtro por residente_id
    } else {
      // Residente ve sólo sus visitantes
      filtro.residente_id = req.user.sub;
    }

    const visitantes = await Visitante.find(filtro);
    res.status(200).json(visitantes);
  } catch (error) {
    res.status(500).json({ error: 'Error al obtener los visitantes' });
  }
};


// 🔹 Mostrar un visitante específico
const show = async (req, res) => {
  try {
    let visitante;

    if (req.user.rol === 'admin' || req.user.rol === 'superadmin') {
      visitante = await Visitante.findById(req.params.id);
    } else {
      visitante = await Visitante.findOne({
        _id: req.params.id,
        residente_id: req.user.sub
      });
    }

    if (!visitante) return res.status(404).json({ error: 'Visitante no encontrado o no autorizado' });

    res.status(200).json(visitante);
  } catch (error) {
    res.status(500).json({ error: 'Error al buscar visitante' });
  }
};

// 🔹 Crear nuevo visitante (siempre asociado a residente autenticado)
const store = async (req, res) => {
  try {
    const { nombre, apellido, telefono, tipo, vehiculo, estado } = req.body;

    if (!nombre || !apellido || !tipo || !estado) {
      return res.status(400).json({ error: 'Faltan campos requeridos' });
    }

    const visitante = new Visitante({
      nombre,
      apellido,
      telefono,
      tipo,
      estado,
      vehiculo: vehiculo || {},
      residente_id: req.user.sub
    });

    await visitante.save();
    res.status(201).json({ message: 'Visitante creado correctamente', data: visitante });
  } catch (error) {
    res.status(500).json({ error: 'Error al crear visitante' });
  }
};

// 🔹 Actualizar visitante
const update = async (req, res) => {
  try {
    let visitante;

    if (req.user.rol === 'admin' || req.user.rol === 'superadmin') {
      visitante = await Visitante.findById(req.params.id);
    } else {
      visitante = await Visitante.findOne({
        _id: req.params.id,
        residente_id: req.user.sub
      });
    }

    if (!visitante) return res.status(404).json({ error: 'Visitante no encontrado o no autorizado' });

    const { nombre, apellido, telefono, tipo, vehiculo, estado } = req.body;

    visitante.nombre = nombre;
    visitante.apellido = apellido;
    visitante.telefono = telefono || null;
    visitante.tipo = tipo;
    visitante.estado = estado || 'activo';
    visitante.vehiculo = vehiculo || {};

    await visitante.save();
    res.status(200).json({ message: 'Visitante actualizado correctamente', data: visitante });
  } catch (error) {
    res.status(500).json({ error: 'Error al actualizar visitante' });
  }
};

// 🔹 Eliminar (inactivar) visitante
const destroy = async (req, res) => {
  try {
    let visitante;

    if (req.user.rol === 'admin' || req.user.rol === 'superadmin') {
      visitante = await Visitante.findById(req.params.id);
    } else {
      visitante = await Visitante.findOne({
        _id: req.params.id,
        residente_id: req.user.sub
      });
    }

    if (!visitante) return res.status(404).json({ error: 'Visitante no encontrado o no autorizado' });

    visitante.estado = 'inactivo';
    await visitante.save();

    res.status(200).json({ message: 'Visitante eliminado correctamente' });
  } catch (error) {
    res.status(500).json({ error: 'Error al eliminar visitante' });
  }
};

module.exports = {
  index,
  show,
  store,
  update,
  destroy
};
