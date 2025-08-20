// backend/routes/rfidGateRoutes.js
const express = require('express');
const router = express.Router();
const { permitirRFID } = require('../controllers/rfidGateController');

router.post('/permitir', permitirRFID);

module.exports = router; // ← importante
