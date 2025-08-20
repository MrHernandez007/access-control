const sequelize = require('../config/dbconfig');
const { DataTypes } = require('sequelize');

const Residente = require('./residente')(sequelize, DataTypes);

module.exports = {
  sequelize,
  Residente
};