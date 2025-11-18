const { DataTypes } = require('sequelize');
const { sequelize } = require('../config/database');

const Tarefa = sequelize.define('Tarefa', {
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
  status: {
    type: DataTypes.ENUM('em_andamento', 'concluida'),
    allowNull: false,
    defaultValue: 'em_andamento'
  },
  prioridade: {
    type: DataTypes.ENUM('baixa', 'media', 'alta', 'urgente'),
    allowNull: false,
    defaultValue: 'media'
  },
  assessor_id: {
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
  criador_id: {
    type: DataTypes.INTEGER,
    allowNull: false,
    references: {
      model: 'usuarios',
      key: 'id'
    }
  },
  prazo: {
    type: DataTypes.DATE,
    allowNull: true
  },
  data_conclusao: {
    type: DataTypes.DATE,
    allowNull: true
  },
  progresso: {
    type: DataTypes.INTEGER,
    defaultValue: 0,
    validate: {
      min: 0,
      max: 100
    }
  },
  categoria: {
    type: DataTypes.STRING(100),
    allowNull: true
  },
  tags: {
    type: DataTypes.JSON,
    allowNull: true,
    defaultValue: []
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
  dependencias: {
    type: DataTypes.JSON,
    allowNull: true,
    defaultValue: []
  },
  tempo_estimado: {
    type: DataTypes.INTEGER,
    allowNull: true,
    comment: 'Tempo estimado em minutos'
  },
  tempo_realizado: {
    type: DataTypes.INTEGER,
    allowNull: true,
    comment: 'Tempo realizado em minutos'
  },
  lembrete_enviado: {
    type: DataTypes.BOOLEAN,
    defaultValue: false
  },
  data_lembrete: {
    type: DataTypes.DATE,
    allowNull: true
  }
}, {
  tableName: 'tarefas'
});

module.exports = Tarefa;

