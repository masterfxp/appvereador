const { DataTypes } = require('sequelize');
const { sequelize } = require('../config/database');

const Indicador = sequelize.define('Indicador', {
  id: {
    type: DataTypes.INTEGER,
    primaryKey: true,
    autoIncrement: true
  },
  tipo: {
    type: DataTypes.ENUM(
      'projetos_apresentados',
      'demandas_atendidas',
      'reunioes_realizadas',
      'indicacoes_protocoladas',
      'cidadaos_atendidos',
      'acessos_app',
      'noticias_publicadas',
      'tarefas_concluidas'
    ),
    allowNull: false
  },
  valor: {
    type: DataTypes.INTEGER,
    allowNull: false,
    defaultValue: 0
  },
  periodo: {
    type: DataTypes.ENUM('diario', 'semanal', 'mensal', 'anual'),
    allowNull: false,
    defaultValue: 'mensal'
  },
  data_referencia: {
    type: DataTypes.DATE,
    allowNull: false
  },
  gabinete_id: {
    type: DataTypes.INTEGER,
    allowNull: false,
    references: {
      model: 'gabinetes',
      key: 'id'
    }
  },
  detalhes: {
    type: DataTypes.JSON,
    allowNull: true,
    defaultValue: {}
  },
  meta: {
    type: DataTypes.INTEGER,
    allowNull: true
  },
  percentual_meta: {
    type: DataTypes.DECIMAL(5, 2),
    allowNull: true
  },
  comparacao_anterior: {
    type: DataTypes.INTEGER,
    allowNull: true
  },
  variacao_percentual: {
    type: DataTypes.DECIMAL(5, 2),
    allowNull: true
  }
}, {
  tableName: 'indicadores'
});

module.exports = Indicador;

