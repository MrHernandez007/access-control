const express = require('express');
const router = express.Router();
const { verificarToken } = require('../middleware/authMiddleware');
const { obtenerMiVivienda } = require('../controllers/viviendacontroller');

router.get('/mi-vivienda', verificarToken, obtenerMiVivienda);

module.exports = router;
