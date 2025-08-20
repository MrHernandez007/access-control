require('dotenv').config();
const express = require('express');
const cors = require('cors');
const connectDB = require('./config/dbconfig');
const visitaRoutes = require('./routes/visitaRoutes');
const app = express();
const rfidGateRoutes = require('./routes/rfidGateRoutes');


connectDB();

app.use(cors());
app.use(express.json());

app.use('/api/auth', require('./routes/residenteRoutes'));
app.use('/api/visitantes', require('./routes/visitanteRoutes'));
app.use('/api/visitas', visitaRoutes);
app.use('/api/qr', require('./routes/qr'));
app.use('/uploads', express.static('uploads'));
app.use('/api/pagos', require('./routes/pagos'));
app.use('/api/viviendas', require('./routes/viviendaRoutes'));
app.use('/api/rfid', rfidGateRoutes);

const Visita = require('./models/Visita');
setInterval(async () => {
  try {
    const now = new Date();
    const r = await Visita.updateMany(
      { estado: { $ne: 'Finalizada' }, qrExpiresAt: { $lte: now } },
      { $set: { estado: 'Finalizada' } }
    );
    if (r.modifiedCount) {
      console.log(`⏰ Finalizadas por expiración: ${r.modifiedCount}`);
    }
  } catch (e) {
    console.error('Job expiración error:', e.message);
  }
}, 30 * 1000);
const PORT = process.env.PORT || 5000;

app.get('/ping', (req, res) => {
  console.log('ESP32 se conectó');
  res.json({ mensaje: 'Conexión exitosa con Node.js' });
});

app.listen(PORT, () => {
  console.log(`Servidor corriendo en puerto ${PORT}`);
});
