const { DataTypes } = require('sequelize');
const { sequelize } = require('../config/database');

const Projeto = sequelize.define('Projeto', {
  id: {
    type: DataTypes.INTEGER,
    primaryKey: true,
    autoIncrement: true
  },
  titulo: {
    type: DataTypes.STRING(255),
    allowNull: false
  },
  tipo: {
    type: DataTypes.ENUM('projeto_lei', 'indicacao', 'requerimento', 'mocao'),
    allowNull: false
  },
  status: {
    type: DataTypes.ENUM('aprovado', 'em_tramitacao', 'rejeitado', 'arquivado'),
    allowNull: false,
    defaultValue: 'em_tramitacao'
  },
  descricao: {
    type: DataTypes.TEXT,
    allowNull: false
  },
  conteudo: {
    type: DataTypes.TEXT('long'),
    allowNull: true
  },
  autor_id: {
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
  numero_protocolo: {
    type: DataTypes.STRING(50),
    allowNull: true
  },
  data_protocolo: {
    type: DataTypes.DATE,
    allowNull: true
  },
  data_votacao: {
    type: DataTypes.DATE,
    allowNull: true
  },
  resultado_votacao: {
    type: DataTypes.ENUM('aprovado', 'rejeitado', 'adiado'),
    allowNull: true
  },
  votos_favoraveis: {
    type: DataTypes.INTEGER,
    defaultValue: 0
  },
  votos_contrarios: {
    type: DataTypes.INTEGER,
    defaultValue: 0
  },
  votos_abstencao: {
    type: DataTypes.INTEGER,
    defaultValue: 0
  },
  observacoes: {
    type: DataTypes.TEXT,
    allowNull: true
  },
  anexos: {
    type: DataTypes.JSON,
    allowNull: true,
    defaultValue: []
  },
  tags: {
    type: DataTypes.JSON,
    allowNull: true,
    defaultValue: []
  },
  publico: {
    type: DataTypes.BOOLEAN,
    defaultValue: false
  },
  data_publicacao: {
    type: DataTypes.DATE,
    allowNull: true
  }
}, {
  tableName: 'projetos'
});

module.exports = Projeto;

