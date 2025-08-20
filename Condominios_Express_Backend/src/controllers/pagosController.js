const Pago = require('../models/Pago');

// Obtener todos los pagos
const index = async (req, res) => {
  try {
    const pagos = await Pago.find().populate('residente_id');
    res.json(pagos);
  } catch (error) {
    res.status(500).json({ mensaje: 'Error al obtener los pagos', error: error.message });
  }
};

// Obtener un solo pago por ID
const show = async (req, res) => {
  try {
    const pago = await Pago.findById(req.params.id).populate('residente_id');
    if (!pago) return res.status(404).json({ mensaje: 'Pago no encontrado' });
    res.json(pago);
  } catch (error) {
    res.status(500).json({ mensaje: 'Error al obtener el pago', error: error.message });
  }
};

// Actualizar un pago (por ejemplo, cambiar estado a "pagado")
const update = async (req, res) => {
  try {
    const pago = await Pago.findByIdAndUpdate(req.params.id, req.body, { new: true });
    if (!pago) return res.status(404).json({ mensaje: 'Pago no encontrado' });
    res.json({ mensaje: 'Pago actualizado', pago });
  } catch (error) {
    res.status(500).json({ mensaje: 'Error al actualizar el pago', error: error.message });
  }
};

// Eliminar (anular) un pago — solo cambia el estado
// Eliminar (anular) un pago — solo cambia el estado
const destroy = async (req, res) => {
  try {
    const pago = await Pago.findByIdAndUpdate(
      req.params.id,
      { estado: 'anulado' },
      { new: true }
    );

    if (!pago)
      return res.status(404).json({ mensaje: 'Pago no encontrado' });

    res.json({ mensaje: 'Pago anulado (no eliminado físicamente)', pago });
  } catch (error) {
    res.status(500).json({
      mensaje: 'Error al anular el pago',
      error: error.message
    });
  }
};



const destroy_viejo = async (req, res) => {
  try {
    const pago = await Pago.findByIdAndUpdate(req.params.id, { estado: 'anulado' }, { new: true });
    if (!pago) return res.status(404).json({ mensaje: 'Pago no encontrado' });
    res.json({ mensaje: 'Pago anulado (no eliminado físicamente)', pago });
  } catch (error) {
    res.status(500).json({ mensaje: 'Error al anular el pago', error: error.message });
  }
};

const indexResidente = async (req, res) => {
  try {
    const residenteId = req.user.sub; // ← El ID del residente desde el token
    const pagos = await Pago.find({ residente_id: residenteId });

    res.json(pagos);
  } catch (error) {
    res.status(500).json({ mensaje: 'Error al obtener tus pagos', error: error.message });
  }
};
module.exports = {
  index,
  show,
  update,
  destroy,
  indexResidente
};