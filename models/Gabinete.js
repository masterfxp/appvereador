const { DataTypes } = require('sequelize');
const { sequelize } = require('../config/database');

const Gabinete = sequelize.define('Gabinete', {
  id: {
    type: DataTypes.INTEGER,
    primaryKey: true,
    autoIncrement: true
  },
  nome: {
    type: DataTypes.STRING(255),
    allowNull: false,
    validate: {
      notEmpty: true
    }
  },
  vereador_nome: {
    type: DataTypes.STRING(255),
    allowNull: false,
    validate: {
      notEmpty: true
    }
  },
  partido: {
    type: DataTypes.STRING(50),
    allowNull: true
  },
  telefone: {
    type: DataTypes.STRING(20),
    allowNull: true
  },
  email: {
    type: DataTypes.STRING(100),
    allowNull: true,
    validate: {
      isEmail: true
    }
  },
  endereco: {
    type: DataTypes.STRING(255),
    allowNull: true
  },
  logo: {
    type: DataTypes.STRING(255),
    allowNull: true
  },
  cores: {
    type: DataTypes.JSON,
    allowNull: true,
    defaultValue: {
      primaria: '#3B82F6',
      secundaria: '#1E40AF'
    }
  },
  redes_sociais: {
    type: DataTypes.JSON,
    allowNull: true,
    defaultValue: {}
  },
  configuracoes: {
    type: DataTypes.JSON,
    allowNull: true,
    defaultValue: {}
  },
  plano_governo: {
    type: DataTypes.TEXT,
    allowNull: true
  },
  metas: {
    type: DataTypes.JSON,
    allowNull: true,
    defaultValue: []
  },
  ativo: {
    type: DataTypes.BOOLEAN,
    defaultValue: true
  },
  municipio: {
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
  biografia: {
    type: DataTypes.TEXT,
    allowNull: true
  },
  foto_vereador: {
    type: DataTypes.STRING(255),
    allowNull: true
  }
}, {
  tableName: 'gabinetes',
  timestamps: true,
  createdAt: 'created_at',
  updatedAt: 'updated_at'
});

module.exports = { Gabinete };