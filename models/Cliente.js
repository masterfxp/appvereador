const { DataTypes } = require('sequelize');
const { sequelize } = require('../config/database');

const Cliente = sequelize.define('Cliente', {
    id: {
      type: DataTypes.INTEGER,
      primaryKey: true,
      autoIncrement: true
    },
    nome: {
      type: DataTypes.STRING(255),
      allowNull: false
    },
    email: {
      type: DataTypes.STRING(255),
      allowNull: false,
      unique: true
    },
    telefone: {
      type: DataTypes.STRING(20),
      allowNull: true
    },
    endereco: {
      type: DataTypes.TEXT,
      allowNull: true
    },
    cidade: {
      type: DataTypes.STRING(100),
      allowNull: true
    },
    estado: {
      type: DataTypes.STRING(2),
      allowNull: true
    },
    cep: {
      type: DataTypes.STRING(10),
      allowNull: true
    },
    cnpj: {
      type: DataTypes.STRING(18),
      allowNull: true,
      unique: true
    },
    empresa: {
      type: DataTypes.STRING(255),
      allowNull: true
    },
    ativo: {
      type: DataTypes.BOOLEAN,
      defaultValue: true
    },
        tipo: {
          type: DataTypes.ENUM('gabinete', 'empresa', 'organizacao'),
          defaultValue: 'gabinete'
        },
        tipo_contrato: {
          type: DataTypes.STRING(100),
          allowNull: true
        },
        data_inicio_contrato: {
          type: DataTypes.DATE,
          allowNull: true
        },
        data_fim_contrato: {
          type: DataTypes.DATE,
          allowNull: true
        },
        limite_usuarios: {
          type: DataTypes.INTEGER,
          defaultValue: 10
        },
        limite_entidades: {
          type: DataTypes.INTEGER,
          defaultValue: 5
        },
        limite_projetos: {
          type: DataTypes.INTEGER,
          defaultValue: 50
        },
        limite_espaco_mb: {
          type: DataTypes.INTEGER,
          defaultValue: 1000
        }
  }, {
    tableName: 'clientes',
    timestamps: true,
    underscored: false,
    freezeTableName: true
  });

module.exports = { Cliente };
