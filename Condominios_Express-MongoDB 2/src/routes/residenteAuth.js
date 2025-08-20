const express = require('express');
const router = express.Router();
const residenteAuthController = require('../controllers/residenteAuthController');

// Vista del login
// router.get('/residente/login', residenteAuthController.showLoginForm);

// Login
router.post('/residente/login', residenteAuthController.login);

// Logout
router.get('/residente/logout', residenteAuthController.logout);


module.exports = router;
