const Vivienda = require('../models/Vivienda');
const mongoose = require('mongoose');

// Obtener todas
const index = async (req, res) => {
    try {
        const viviendas = await Vivienda.find();
        res.status(200).json(viviendas);
    } catch (error) {
        res.status(500).json({ error: 'Error al obtener viviendas' });
    }
};

// Obtener una por ID
const show = async (req, res) => {
    try {
        const vivienda = await Vivienda.findById(req.params.id);
        if (!vivienda) {
            return res.status(404).json({ error: 'Vivienda no encontrada' });
        }
        res.status(200).json(vivienda);
    } catch (error) {
        res.status(500).json({ error: 'Error al buscar vivienda' });
    }
};

// Crear nueva
const store = async (req, res) => {
    try {
        const { numero, tipo, calle, residente_id } = req.body;

        if (!numero || !tipo || !calle) {
            return res.status(400).json({ error: 'Faltan campos requeridos' });
        }

        if (residente_id && !mongoose.Types.ObjectId.isValid(residente_id)) {
            return res.status(400).json({ error: 'ID de residente inválido' });
        }

        const nuevaVivienda = new Vivienda({
            numero,
            tipo,
            calle,
            residente_id: residente_id || null
        });

        await nuevaVivienda.save();

        res.status(201).json(nuevaVivienda);
    } catch (error) {
        console.error(error);
        res.status(500).json({ error: 'Error al crear vivienda', detalle: error.message });
    }
};

// Actualizar
const update = async (req, res) => {
    try {
        console.log('Recibiendo solicitud PUT para actualizar vivienda:', req.params.id);
        console.log('Datos recibidos en el body:', req.body);
        
        const vivienda = await Vivienda.findById(req.params.id);
        if (!vivienda) {
            console.log('Error: Vivienda no encontrada.');
            return res.status(404).json({ error: 'Vivienda no encontrada' });
        }

        const { numero, tipo, calle, estado } = req.body;

        vivienda.numero = numero;
        vivienda.tipo = tipo;
        vivienda.calle = calle;

        // Convierte el estado a minúsculas antes de guardarlo.
        if (estado) {
            vivienda.estado = estado.toLowerCase();
        }

        await vivienda.save();
        console.log('Vivienda actualizada exitosamente:', vivienda);
        res.status(200).json(vivienda);
    } catch (error) {
        console.error('Error al actualizar vivienda:', error);
        res.status(500).json({ error: 'Error al actualizar vivienda', detalle: error.message });
    }
};

// Eliminar (cambia estado a inactivo)
const destroy = async (req, res) => {
    try {
        const vivienda = await Vivienda.findById(req.params.id);
        if (!vivienda) {
            return res.status(404).json({ error: 'Vivienda no encontrada' });
        }

        vivienda.estado = 'inactivo';
        await vivienda.save();

        res.status(200).json({ message: 'Vivienda eliminada correctamente' });
    } catch (error) {
        res.status(500).json({ error: 'Error al eliminar vivienda' });
    }
};

module.exports = {
    index,
    show,
    store,
    update,
    destroy
};