// controllers/visitanteController.js
const Visitante = require('../models/Visitante');
// const Visita = require('../models/Visita'); 

// GET /api/visitantes
exports.obtenerVisitantes = async (req, res) => {
  try {
    const residenteId = req.residente.id;

    const visitantes = await Visitante.find({
      estado: 'activo',
      residente_id: residenteId,
    }).sort({ createdAt: -1 });

    return res.json(visitantes);
  } catch (error) {
    console.error('Error al obtener visitantes:', error);
    return res.status(500).json({ msg: 'Error del servidor' });
  }
};

// POST /api/visitantes/registrar
// Ahora SOLO registra al visitante. NO crea la visita.
exports.registrarVisitanteYVisita = async (req, res) => {
  try {
    const { nombre, apellido, telefono, tipo, vehiculo /*, dia_visita*/ } = req.body;
    const residente_id = req.residente.id;

    if (!nombre || !apellido || !telefono || !vehiculo?.placa) {
      return res.status(400).json({ msg: 'Faltan campos requeridos' });
    }

    
    const nuevoVisitante = new Visitante({
      nombre: nombre.trim(),
      apellido: apellido.trim(),
      telefono: String(telefono).trim(),
      tipo: (tipo || 'visita').trim(),
      vehiculo: {
        placa: vehiculo.placa?.toUpperCase().trim(),
        color: vehiculo.color?.trim(),
        modelo: vehiculo.modelo?.trim(),
        tipo: vehiculo.tipo?.trim(),
      },
      residente_id,
      estado: 'activo',
    });

    const visitanteGuardado = await nuevoVisitante.save();

    // Ya no creamos la visita aquí
    // const nuevaVisita = new Visita({
    //   visitante_id: visitanteGuardado._id,
    //   residente_id,
    //   dia_visita: dia_visita || new Date(),
    //   estado: 'En curso',
    // });
    // const visitaGuardada = await nuevaVisita.save();

    return res.status(201).json({
      msg: 'Visitante creado correctamente',
      visitante: visitanteGuardado,
      // visita: visitaGuardada, // ya no existe en este flujo
    });
  } catch (error) {
    console.error('Error al registrar visitante:', error);
    return res.status(500).json({ msg: 'Error del servidor' });
  }
};
