const { DataTypes } = require('sequelize');
const { sequelize } = require('../config/database');

const Chat = sequelize.define('Chat', {
  id: {
    type: DataTypes.INTEGER,
    primaryKey: true,
    autoIncrement: true
  },
  remetente_id: {
    type: DataTypes.INTEGER,
    allowNull: false,
    references: {
      model: 'usuarios',
      key: 'id'
    }
  },
  destinatario_id: {
    type: DataTypes.INTEGER,
    allowNull: true,
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
  mensagem: {
    type: DataTypes.TEXT,
    allowNull: false
  },
  tipo: {
    type: DataTypes.ENUM('texto', 'imagem', 'arquivo', 'sistema'),
    allowNull: false,
    defaultValue: 'texto'
  },
  anexo: {
    type: DataTypes.STRING(255),
    allowNull: true
  },
  lida: {
    type: DataTypes.BOOLEAN,
    defaultValue: false
  },
  data_leitura: {
    type: DataTypes.DATE,
    allowNull: true
  },
  grupo: {
    type: DataTypes.BOOLEAN,
    defaultValue: false
  },
  nome_grupo: {
    type: DataTypes.STRING(100),
    allowNull: true
  },
  participantes_grupo: {
    type: DataTypes.JSON,
    allowNull: true,
    defaultValue: []
  },
  editada: {
    type: DataTypes.BOOLEAN,
    defaultValue: false
  },
  data_edicao: {
    type: DataTypes.DATE,
    allowNull: true
  },
  excluida: {
    type: DataTypes.BOOLEAN,
    defaultValue: false
  },
  data_exclusao: {
    type: DataTypes.DATE,
    allowNull: true
  }
}, {
  tableName: 'chats'
});

module.exports = Chat;

