// backend/controllers/qrController.js
const Visita = require('../models/Visita');

exports.verificarQR = async (req, res) => {
  try {
    const { codigo } = req.body;
    if (!codigo) {
      return res.status(400).json({ message: 'Código QR no proporcionado' });
    }

    // Buscar la visita por el código QR
    const visita = await Visita.findOne({ qrCode: codigo });

    if (!visita) {
      return res.status(404).json({ message: 'QR no encontrado' });
    }

    // Verificar si ya expiró
    if (new Date() > visita.qrExpiresAt) {
      visita.estado = 'Expirado';
      await visita.save();
      return res.status(410).json({ message: 'QR expirado', estado: visita.estado });
    }

    // Verificar si ya se alcanzó el máximo de usos
    if (visita.scanCount >= visita.maxScans) {
      return res.status(403).json({ message: 'QR sin usos disponibles', estado: visita.estado });
    }

    // Incrementar el contador de lecturas
    visita.scanCount += 1;
    visita.lastScanAt = new Date();

    // Si alcanza el máximo de lecturas, finalizar visita
    if (visita.scanCount >= visita.maxScans) {
      visita.estado = 'Finalizada';
    }

    await visita.save();

    return res.status(200).json({
      message: 'QR válido',
      estado: visita.estado,
      scanCount: visita.scanCount,
      maxScans: visita.maxScans
    });
  } catch (error) {
    console.error('Error en verificarQR:', error);
    return res.status(500).json({ message: 'Error al verificar QR' });
  }
};
