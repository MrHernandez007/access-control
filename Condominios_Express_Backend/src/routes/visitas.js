const express = require('express');
const router = express.Router();
const visitasController = require('../controllers/visitasController');
const { verifyToken, requireRole } = require('../middlewares/auth');

// PERMITIR múltiples roles
router.get('/', verifyToken, requireRole('residente', 'admin', 'superadmin'), visitasController.index);
router.get('/:id', verifyToken, requireRole('residente', 'admin', 'superadmin'), visitasController.show);
router.post('/', verifyToken, requireRole('residente'), visitasController.store); // solo residentes pueden registrar
router.delete('/:id', verifyToken, requireRole('residente', 'admin', 'superadmin'), visitasController.destroy);

module.exports = router;
