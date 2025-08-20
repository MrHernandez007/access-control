const express = require('express');
const router = express.Router();
const controller = require('../controllers/administradoresController');
const multer = require('multer');
const upload = multer({ dest: 'public/uploads/' });
const verificarToken = require('../middlewares/verificarToken'); // 👈 Asegúrate de tener esto

// CRUD
router.get('/', controller.index);
router.get('/:id', controller.show);
router.post('/', upload.single('imagen'), controller.store);
router.put('/:id', upload.single('imagen'), controller.update);
router.delete('/:id', controller.destroy);

// 🛡️ Ruta protegida del perfil autenticado
router.get('/auth/perfil', verificarToken, controller.perfil);

module.exports = router;



// const express = require('express');
// const router = express.Router();
// const administradoresController = require('../controllers/administradoresController');
// const multer = require('multer');

// // Configuración básica para subir imagen
// const storage = multer.diskStorage({
//   destination: 'public/administradores/',
//   filename: (req, file, cb) => {
//     cb(null, Date.now() + '-' + file.originalname);
//   }
// });
// const upload = multer({ storage });

// // Rutas
// router.get('/', administradoresController.index);
// router.get('/:id', administradoresController.show);
// router.post('/', upload.single('imagen'), administradoresController.store);
// router.put('/:id', upload.single('imagen'), administradoresController.update);
// router.delete('/:id', administradoresController.destroy);

// module.exports = router;
