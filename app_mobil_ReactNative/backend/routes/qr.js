const express = require('express');
const router = express.Router();
const { verificarQR } = require('../controllers/qrController');

router.post('/verificar', verificarQR);

module.exports = router;
