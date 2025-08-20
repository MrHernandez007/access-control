const Visita = require('../models/Visita');
const mongoose = require('mongoose');

// GET /api/visitas
const index = async (req, res) => {
  try {
    const userRole = req.user.rol;
    let visitas;

    if (userRole === 'residente') {
      visitas = await Visita.find({ residente_id: req.user.sub }).populate('visitante_id');
    } else if (userRole === 'admin' || userRole === 'superadmin') {
      visitas = await Visita.find().populate('visitante_id');
    }

    res.json(visitas);
  } catch (error) {
    res.status(500).json({ error: 'Error al obtener visitas' });
  }
};


// GET /api/visitas/:id
const show = async (req, res) => {
  try {
    const visita = await Visita.findById(req.params.id);
    if (!visita) {
      return res.status(404).json({ error: 'Visita no encontrada' });
    }
    res.status(200).json(visita);
  } catch (error) {
    res.status(500).json({ error: 'Error al buscar visita' });
  }
};

// POST /api/visitas
const store = async (req, res) => {
  try {
    const { visitante_id, residente_id, dia_visita } = req.body;

    if (!visitante_id || !residente_id || !dia_visita) {
      return res.status(400).json({ error: 'Faltan campos requeridos' });
    }

    const visita = new Visita({
      visitante_id: new mongoose.Types.ObjectId(visitante_id),
      residente_id: new mongoose.Types.ObjectId(residente_id),
      dia_visita: new Date(dia_visita),
      estado: 'En curso'
    });

    await visita.save();
    res.status(201).json({ message: 'Visita registrada correctamente', data: visita });
  } catch (error) {
    res.status(500).json({ error: 'Error al registrar visita' });
  }
};

// PUT /api/visitas/:id
const update = async (req, res) => {
  try {
    const visita = await Visita.findById(req.params.id);
    if (!visita) {
      return res.status(404).json({ error: 'Visita no encontrada' });
    }

    const { fecha, estado } = req.body;

    if (fecha) visita.fecha = new Date(fecha);
    if (estado) visita.estado = estado;

    await visita.save();
    res.status(200).json({ message: 'Visita actualizada correctamente', data: visita });
  } catch (error) {
    res.status(500).json({ error: 'Error al actualizar visita' });
  }
};

// DELETE /api/visitas/:id
const destroy = async (req, res) => {
  try {
    const visita = await Visita.findById(req.params.id);
    if (!visita) {
      return res.status(404).json({ error: 'Visita no encontrada' });
    }

    visita.estado = 'terminada';
    await visita.save();

    res.status(200).json({ message: 'Visita eliminada correctamente' });
  } catch (error) {
    res.status(500).json({ error: 'Error al eliminar visita' });
  }
};

module.exports = {
  index,
  show,
  store,
  update,
  destroy
};
