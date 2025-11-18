const { DataTypes } = require('sequelize');
const { sequelize } = require('../config/database');

const Demanda = sequelize.define('Demanda', {
  id: {
    type: DataTypes.INTEGER,
    primaryKey: true,
    autoIncrement: true
  },
  cidadao_id: {
    type: DataTypes.INTEGER,
    allowNull: false,
    references: {
      model: 'usuarios',
      key: 'id'
    }
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
  assunto: {
    type: DataTypes.STRING(255),
    allowNull: false
  },
  descricao: {
    type: DataTypes.TEXT,
    allowNull: false
  },
  status: {
    type: DataTypes.ENUM('pendente', 'em_andamento', 'resolvido', 'arquivado'),
    allowNull: false,
    defaultValue: 'pendente'
  },
  prioridade: {
    type: DataTypes.ENUM('baixa', 'media', 'alta', 'urgente'),
    allowNull: false,
    defaultValue: 'media'
  },
  categoria: {
    type: DataTypes.STRING(100),
    allowNull: true
  },
  endereco: {
    type: DataTypes.STRING(255),
    allowNull: true
  },
  rua: {
    type: DataTypes.STRING(255),
    allowNull: true
  },
  bairro: {
    type: DataTypes.STRING(100),
    allowNull: true
  },
  latitude: {
    type: DataTypes.DECIMAL(10, 8),
    allowNull: true
  },
  longitude: {
    type: DataTypes.DECIMAL(11, 8),
    allowNull: true
  },
  telefone_contato: {
    type: DataTypes.STRING(20),
    allowNull: true
  },
  email_contato: {
    type: DataTypes.STRING(100),
    allowNull: true
  },
  anexos: {
    type: DataTypes.JSON,
    allowNull: true,
    defaultValue: []
  },
  responsavel_id: {
    type: DataTypes.INTEGER,
    allowNull: true,
    references: {
      model: 'usuarios',
      key: 'id'
    }
  },
  data_resolucao: {
    type: DataTypes.DATE,
    allowNull: true
  },
  observacoes_resolucao: {
    type: DataTypes.TEXT,
    allowNull: true
  },
  feedback_cidadao: {
    type: DataTypes.TEXT,
    allowNull: true
  },
  nota_satisfacao: {
    type: DataTypes.INTEGER,
    allowNull: true,
    validate: {
      min: 1,
      max: 5
    }
  },
  whatsapp_enviado: {
    type: DataTypes.BOOLEAN,
    defaultValue: false
  },
  email_enviado: {
    type: DataTypes.BOOLEAN,
    defaultValue: false
  }
}, {
  tableName: 'demandas'
});

module.exports = Demanda;

