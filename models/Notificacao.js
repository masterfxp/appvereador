const { DataTypes } = require('sequelize');
const { sequelize } = require('../config/database');

const Notificacao = sequelize.define('Notificacao', {
  id: {
    type: DataTypes.INTEGER,
    primaryKey: true,
    autoIncrement: true
  },
  usuario_id: {
    type: DataTypes.INTEGER,
    allowNull: false,
    references: {
      model: 'usuarios',
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
  tipo: {
    type: DataTypes.ENUM('info', 'warning', 'error', 'success'),
    allowNull: false,
    defaultValue: 'info'
  },
  titulo: {
    type: DataTypes.STRING(200),
    allowNull: false
  },
  mensagem: {
    type: DataTypes.TEXT,
    allowNull: false
  },
  lida: {
    type: DataTypes.BOOLEAN,
    defaultValue: false
  },
  data_leitura: {
    type: DataTypes.DATE,
    allowNull: true
  },
  acao_url: {
    type: DataTypes.STRING(500),
    allowNull: true
  },
  acao_texto: {
    type: DataTypes.STRING(100),
    allowNull: true
  },
  dados_extras: {
    type: DataTypes.JSON,
    allowNull: true
  },
  prioridade: {
    type: DataTypes.ENUM('baixa', 'media', 'alta', 'urgente'),
    defaultValue: 'media'
  },
  expira_em: {
    type: DataTypes.DATE,
    allowNull: true
  }
}, {
  tableName: 'notificacoes',
  timestamps: true,
  createdAt: 'created_at',
  updatedAt: 'updated_at'
});

module.exports = { Notificacao };

