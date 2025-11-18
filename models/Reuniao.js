const { DataTypes } = require('sequelize');
const { sequelize } = require('../config/database');

const Reuniao = sequelize.define('Reuniao', {
  id: {
    type: DataTypes.INTEGER,
    primaryKey: true,
    autoIncrement: true
  },
  titulo: {
    type: DataTypes.STRING(255),
    allowNull: false
  },
  descricao: {
    type: DataTypes.TEXT,
    allowNull: true
  },
  data: {
    type: DataTypes.DATE,
    allowNull: false
  },
  hora_inicio: {
    type: DataTypes.TIME,
    allowNull: true
  },
  hora_fim: {
    type: DataTypes.TIME,
    allowNull: true
  },
  local: {
    type: DataTypes.STRING(255),
    allowNull: true
  },
  endereco: {
    type: DataTypes.STRING(255),
    allowNull: true
  },
  tipo: {
    type: DataTypes.ENUM('oficial', 'reuniao_gabinete', 'evento_publico', 'visita', 'outro'),
    allowNull: false,
    defaultValue: 'oficial'
  },
  status: {
    type: DataTypes.ENUM('agendada', 'realizada', 'cancelada'),
    allowNull: false,
    defaultValue: 'agendada'
  },
  gabinete_id: {
    type: DataTypes.INTEGER,
    allowNull: false,
    references: {
      model: 'gabinetes',
      key: 'id'
    }
  },
  cliente_id: {
    type: DataTypes.INTEGER,
    allowNull: false,
    references: {
      model: 'clientes',
      key: 'id'
    }
  },
  organizador_id: {
    type: DataTypes.INTEGER,
    allowNull: false,
    references: {
      model: 'usuarios',
      key: 'id'
    }
  },
  participantes: {
    type: DataTypes.JSON,
    allowNull: true,
    defaultValue: []
  },
  pauta: {
    type: DataTypes.TEXT,
    allowNull: true
  },
  anexos: {
    type: DataTypes.JSON,
    allowNull: true,
    defaultValue: []
  },
  observacoes: {
    type: DataTypes.TEXT,
    allowNull: true
  },
  publico: {
    type: DataTypes.BOOLEAN,
    defaultValue: false
  },
  lembrete_enviado: {
    type: DataTypes.BOOLEAN,
    defaultValue: false
  },
  data_lembrete: {
    type: DataTypes.DATE,
    allowNull: true
  },
  link_meet: {
    type: DataTypes.STRING(255),
    allowNull: true
  },
  latitude: {
    type: DataTypes.DECIMAL(10, 8),
    allowNull: true
  },
  longitude: {
    type: DataTypes.DECIMAL(11, 8),
    allowNull: true
  }
}, {
  tableName: 'reunioes'
});

module.exports = Reuniao;

