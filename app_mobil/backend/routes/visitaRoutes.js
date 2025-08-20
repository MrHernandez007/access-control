const express = require('express');
const router = express.Router();
const { verificarToken } = require('../middleware/authMiddleware');
const { crearVisita, obtenerVisitasActivas } = require('../controllers/visitaController');

router.post('/', verificarToken, crearVisita);
router.get('/activas', verificarToken, obtenerVisitasActivas); // ← Aquí la ruta nueva

module.exports = router;
