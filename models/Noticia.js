const { DataTypes } = require('sequelize');
const { sequelize } = require('../config/database');

const Noticia = sequelize.define('Noticia', {
  id: {
    type: DataTypes.INTEGER,
    primaryKey: true,
    autoIncrement: true
  },
  titulo: {
    type: DataTypes.STRING(255),
    allowNull: false
  },
  conteudo: {
    type: DataTypes.TEXT('long'),
    allowNull: false
  },
  resumo: {
    type: DataTypes.TEXT,
    allowNull: true
  },
  imagem: {
    type: DataTypes.STRING(255),
    allowNull: true
  },
  galeria: {
    type: DataTypes.JSON,
    allowNull: true,
    defaultValue: []
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
  categoria: {
    type: DataTypes.STRING(100),
    allowNull: true
  },
  tags: {
    type: DataTypes.JSON,
    allowNull: true,
    defaultValue: []
  },
  status: {
    type: DataTypes.ENUM('rascunho', 'publicado', 'arquivado'),
    allowNull: false,
    defaultValue: 'rascunho'
  },
  publico: {
    type: DataTypes.BOOLEAN,
    defaultValue: true
  },
  data_publicacao: {
    type: DataTypes.DATE,
    allowNull: true
  },
  visualizacoes: {
    type: DataTypes.INTEGER,
    defaultValue: 0
  },
  curtidas: {
    type: DataTypes.INTEGER,
    defaultValue: 0
  },
  compartilhamentos: {
    type: DataTypes.INTEGER,
    defaultValue: 0
  },
  anexos: {
    type: DataTypes.JSON,
    allowNull: true,
    defaultValue: []
  },
  fonte: {
    type: DataTypes.STRING(255),
    allowNull: true
  },
  link_externo: {
    type: DataTypes.STRING(255),
    allowNull: true
  },
  redes_sociais: {
    type: DataTypes.JSON,
    allowNull: true,
    defaultValue: {}
  },
  seo_title: {
    type: DataTypes.STRING(255),
    allowNull: true
  },
  seo_description: {
    type: DataTypes.TEXT,
    allowNull: true
  },
  seo_keywords: {
    type: DataTypes.TEXT,
    allowNull: true
  }
}, {
  tableName: 'noticias'
});

module.exports = Noticia;

