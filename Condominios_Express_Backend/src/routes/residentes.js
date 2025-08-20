const express = require('express');
const router = express.Router();
const controller = require('../controllers/residentesController');
const upload = require('../middlewares/upload'); // si se usa imagen
const { verifyToken, requireRole } = require('../middlewares/auth');


router.get('/', controller.index);
router.get('/perfil', verifyToken, requireRole('residente','admin','superadmin'), controller.perfil);
router.get('/:id', controller.show);
router.post('/', upload.single('imagen'), controller.store);
router.put('/:id', upload.single('imagen'), controller.update);
router.delete('/:id', controller.destroy);


module.exports = router;
