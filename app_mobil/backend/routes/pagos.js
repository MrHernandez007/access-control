const express = require('express');
const router = express.Router();
const { verificarToken } = require('../middleware/authMiddleware');
const { obtenerPagosPendientes, marcarComoPagado } = require('../controllers/pagoController');

router.get('/pendientes', verificarToken, obtenerPagosPendientes);
router.put('/:id/pagar', verificarToken, marcarComoPagado);

module.exports = router;
