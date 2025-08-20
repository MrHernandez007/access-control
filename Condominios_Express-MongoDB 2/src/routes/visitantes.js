const express = require('express');
const router = express.Router();
const controller = require('../controllers/visitantesController');
const upload = require('../middlewares/upload');
const { verifyToken, requireRole } = require('../middlewares/auth');

// 🔹 Obtener visitantes del residente autenticado
router.get('/', verifyToken, requireRole('residente', 'admin', 'superadmin'), controller.index);

// 🔹 Obtener visitante específico
router.get('/:id', verifyToken, requireRole('residente', 'admin', 'superadmin'), controller.show);

// 🔹 Crear visitante
router.post('/', verifyToken, requireRole('residente', 'admin', 'superadmin'), upload.single('imagen'), controller.store);

// 🔹 Actualizar visitante
router.put('/:id', verifyToken, requireRole('residente', 'admin', 'superadmin'), upload.single('imagen'), controller.update);

// 🔹 Eliminar (inactivar) visitante
router.delete('/:id', verifyToken, requireRole('residente', 'admin', 'superadmin'), controller.destroy);

module.exports = router;
