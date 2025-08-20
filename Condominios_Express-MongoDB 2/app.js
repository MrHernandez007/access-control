const express = require('express');
const path = require('path');
const session = require('express-session');
const mongoose = require('mongoose');
const dotenv = require('dotenv');
const app = express();
// const { verificarToken } = require('./src/middlewares/verificarToken');
const verificarToken = require('./src/middlewares/verificarToken');
// const verificarToken = require('./src/middlewares/verificarTokenResi');
const { verifyToken, requireRole } = require('./src/middlewares/auth');
const adminAuthRoutes = require('./src/routes/adminAuth');

const cors = require('cors');
app.use(cors({
  origin: ['http://localhost:8000', 'http://127.0.0.1:8000'],
  credentials: true,
}));
// 🔐 Variables de entorno
dotenv.config();

// 🔌 Conexión a MongoDB
mongoose.connect(process.env.MONGO_URI || 'mongodb+srv://2124200406:vyHyL8k3NoA8UKJ7@cluster0.ovnbvwg.mongodb.net/condominios?retryWrites=true&w=majority')
  .then(() => console.log('🟢 Conectado a MongoDB'))
  .catch(err => console.error('🔴 Error al conectar a MongoDB:', err));

// 📦 Middlewares base
app.use(express.json());
app.use(express.urlencoded({ extended: true }));
app.use(express.static(path.join(__dirname, 'public')));

// 🧠 Sesiones para guardar JWT
app.use(session({
  secret: 'clave_super_segura',
  resave: false,
  saveUninitialized: false,
  cookie: { maxAge: 3600000 } // 1 hora
}));

// 🖼️ Motor de vistas (usa EJS o el que prefieras)
app.set('view engine', 'ejs');
app.set('views', path.join(__dirname, 'src', 'views'));

// 🛣️ Rutas de autenticación
// const adminAuthRoutes = require('./src/routes/adminAuth');
const residenteAuthRoutes = require('./src/routes/residenteAuth');


// 🛣️ Rutas de API
const administradoresRoutes = require('./src/routes/administradores');
const residentesRoutes = require('./src/routes/residentes');
const visitantesRoutes = require('./src/routes/visitantes');
const viviendasRoutes = require('./src/routes/viviendas');
const visitasRoutes = require('./src/routes/visitas');
const pagosRoutes = require('./src/routes/pagos');

// // 🧭 Rutas API protegidas con JWT
// app.use('/api/administradores', verificarToken, administradoresRoutes);
// app.use('/api/residentes', verificarToken, residentesRoutes); //añadir verificarToken
// app.use('/api/visitantes', verificarToken, visitantesRoutes);
// app.use('/api/viviendas', verificarToken, viviendasRoutes);
// app.use('/api/visitas', verificarToken, visitasRoutes);
// app.use('/api/pagos', verificarToken, pagosRoutes);

// 🧭 Usar rutas de autenticación — SIN protección
app.use('/', adminAuthRoutes); // ✅ válido, crea /admin/logout
app.use('/', residenteAuthRoutes);

// 🧭 Rutas API protegidas con JWT y por rol
app.use('/api/administradores', verifyToken, requireRole('admin', 'superadmin'), administradoresRoutes);
app.use('/api/residentes', verifyToken, requireRole('residente','admin', 'superadmin'), residentesRoutes);
app.use('/api/visitantes', verifyToken, requireRole('residente','admin', 'superadmin'), visitantesRoutes);
app.use('/api/viviendas', verifyToken, requireRole('admin', 'superadmin'), viviendasRoutes);
app.use('/api/visitas', verifyToken, requireRole('residente','admin', 'superadmin'), visitasRoutes);
app.use('/api/pagos', verifyToken, requireRole('residente','admin', 'superadmin'), pagosRoutes);
app.use('/uploads', express.static(path.join(__dirname, 'public/uploads')));
app.use('/api/residentes', verifyToken, requireRole('residente','admin','superadmin'), residentesRoutes);


// Ruta raíz — muestra mensaje simple para verificar que el servidor corre
app.get('/', (req, res) => {
  res.send('¡Servidor Express y MongoDB funcionando!');
});

// 🔎 Ruta 404 — para todo lo que no haya sido capturado arriba
app.use((req, res) => {
  res.status(404).send('Página no encontrada');
});

// 🚀 Iniciar servidor
const PORT = process.env.PORT || 3000;
app.listen(PORT, () => {
  console.log(`🚀 Servidor escuchando en http://localhost:${PORT}`);
});

require('./src/jobs/generarCobros');