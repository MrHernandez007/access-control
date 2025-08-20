const cron = require('node-cron');
const moment = require('moment');
const Pago = require('../models/Pago');
const Vivienda = require('../models/Vivienda');

// Ejecutar cada día 1 del mes a las 00:00
// Para pruebas cada minuto: '*/1 * * * *'
// Para producción cada mes: '0 0 1 * *'
cron.schedule('*/1 * * * *', async () => {
  try {
    const mesActual = moment().format('YYYY-MM');
    const fechaPago = new Date(); // o new Date(`${mesActual}-01`) para primer día del mes

    const viviendas = await Vivienda.find({ estado: 'activo' });

    for (const vivienda of viviendas) {
      // Validar que tenga residente asignado
      if (!vivienda.residente_id) {
        console.warn(`Vivienda ${vivienda.numero} no tiene residente asignado. Saltando`);
        continue;
      }

      // Evitar cobros duplicados
      const yaExiste = await Pago.findOne({
        residente_id: vivienda.residente_id,
        concepto: `pago mensual ${mesActual}`
      });

      if (yaExiste) {
        console.log(`⏩ Ya existe pago de ${mesActual} para residente ${vivienda.residente_id}`);
        continue;
      }

      // Crear nuevo pago
      await Pago.create({
        residente_id: vivienda.residente_id,
        vivienda_id: vivienda._id,
        concepto: `pago mensual ${mesActual}`,
        monto: 1200,
        fecha_pago: fechaPago,
        estado: 'pendiente'
      });

      console.log(`✅ Generado cobro para vivienda ${vivienda.numero}, residente ${vivienda.residente_id}`);
    }

    console.log('🎉 Cobros mensuales procesados');
  } catch (error) {
    console.error(`❌ Error al generar cobros mensuales: ${error.message}`);
  }
});
