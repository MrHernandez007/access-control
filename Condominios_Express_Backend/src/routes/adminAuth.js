const express = require('express');
const router = express.Router();
const adminAuth = require('../controllers/adminAuthController');

//router.get('/admin/login', adminAuth.showLoginForm);
router.post('/admin/login', adminAuth.login);
router.get('/admin/logout', adminAuth.logout);

module.exports = router;
