// routes/visitantes.js
const express = require('express');
const router = express.Router();
const { verificarToken } = require('../middleware/authMiddleware');
const {
  registrarVisitanteYVisita,
  obtenerVisitantes
} = require('../controllers/visitanteController');

// Registrar visitante
router.post('/registrar', verificarToken, registrarVisitanteYVisita);

// Obtener visitantes del residente autenticado
router.get('/', verificarToken, obtenerVisitantes);

module.exports = router;
