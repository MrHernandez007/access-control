const express = require('express');
const router = express.Router();
const controller = require('../controllers/pagosController');
const verificarJWT = require('../middlewares/verificarToken');
const { verifyToken, requireRole } = require('../middlewares/auth');

// // Para residentes (autenticado)
// router.get('/residente', verificarJWT, controller.indexResidente);
// ✅ Ruta protegida para obtener pagos del residente autenticado
router.get('/residente', verifyToken, requireRole('residente'), controller.indexResidente);


// // Para administradores (autenticado)
// router.get('/admin', verificarJWT, controller.indexAdmin);

// GET /api/pagos
router.get('/', controller.index);

// GET /api/pagos/:id
router.get('/:id', controller.show);

// PUT /api/pagos/:id
router.put('/:id', controller.update);

// DELETE /api/pagos/:id
router.delete('/:id', controller.destroy);

module.exports = router;