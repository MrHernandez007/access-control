const Pago = require('../models/Pago');

exports.obtenerPagosPendientes = async (req, res) => {
  try {
    const residenteId = req.residente?._id || req.residente?.id;
    if (!residenteId) return res.status(401).json({ msg: 'Sin residente en contexto' });

    const pagos = await Pago.find({
      residente_id: residenteId,
      // acepta 'pendiente' en cualquier casing por si hay registros viejos
      estado: { $regex: /^pendiente$/i },
    }).sort({ fecha_pago: 1 });

    res.json(pagos);
  } catch (error) {
    console.error('obtenerPagosPendientes:', error);
    res.status(500).json({ msg: 'Error al obtener pagos pendientes' });
  }
};

exports.marcarComoPagado = async (req, res) => {
  try {
    const residenteId = req.residente?._id || req.residente?.id;
    const { id } = req.params;

    // asegura que solo actualizas pagos del residente autenticado
    const pago = await Pago.findOneAndUpdate(
      { _id: id, residente_id: residenteId },
      { estado: 'pagado' },
      { new: true }
    );

    if (!pago) return res.status(404).json({ msg: 'Pago no encontrado' });

    res.json({ msg: 'Pago marcado como pagado', pago });
  } catch (error) {
    console.error('marcarComoPagado:', error);
    res.status(500).json({ msg: 'Error al actualizar pago' });
  }
};
