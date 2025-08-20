const mongoose = require('mongoose');
require('dotenv').config(); 

const connectDB = async () => {
  try {
    await mongoose.connect(process.env.MONGO_URI, {
      useNewUrlParser: true,
      useUnifiedTopology: true,
    });
    console.log('MongoDB conectada');
  } catch (err) {
    console.error('Error de conexión:', err.message);
    process.exit(1);
  }
};

module.exports = connectDB;
