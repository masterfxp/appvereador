const { Usuario } = require('./Usuario');
const { Gabinete } = require('./Gabinete');
const { Licenca } = require('./Licenca');
const { Chat } = require('./Chat');
const { Notificacao } = require('./Notificacao');
const { Cliente } = require('./Cliente');
const TipoContrato = require('./TipoContrato');
const Projeto = require('./Projeto');
const Demanda = require('./Demanda');
const Reuniao = require('./Reuniao');
const Tarefa = require('./Tarefa');
const Noticia = require('./Noticia');

// Definir associações após carregar todos os modelos
setTimeout(() => {
  Usuario.belongsTo(Gabinete, {
    foreignKey: 'gabinete_id',
    as: 'gabinete'
  });

  Gabinete.hasMany(Usuario, {
    foreignKey: 'gabinete_id',
    as: 'usuarios'
  });

  // Associações com Cliente
  Usuario.belongsTo(Cliente, {
    foreignKey: 'cliente_id',
    as: 'cliente'
  });

  Cliente.hasMany(Usuario, {
    foreignKey: 'cliente_id',
    as: 'usuarios'
  });

  // Associações com Cliente para todos os modelos
  // (Comentadas temporariamente para evitar erros)
  
  // Projeto
  // Cliente.hasMany(Projeto, {
  //   foreignKey: 'cliente_id',
  //   as: 'projetos'
  // });
  // Projeto.belongsTo(Cliente, {
  //   foreignKey: 'cliente_id',
  //   as: 'cliente'
  // });

  // Demanda
  // Cliente.hasMany(Demanda, {
  //   foreignKey: 'cliente_id',
  //   as: 'demandas'
  // });
  // Demanda.belongsTo(Cliente, {
  //   foreignKey: 'cliente_id',
  //   as: 'cliente'
  // });

  // Reuniao
  // Cliente.hasMany(Reuniao, {
  //   foreignKey: 'cliente_id',
  //   as: 'reunioes'
  // });
  // Reuniao.belongsTo(Cliente, {
  //   foreignKey: 'cliente_id',
  //   as: 'cliente'
  // });

  // Tarefa
  // Cliente.hasMany(Tarefa, {
  //   foreignKey: 'cliente_id',
  //   as: 'tarefas'
  // });
  // Tarefa.belongsTo(Cliente, {
  //   foreignKey: 'cliente_id',
  //   as: 'cliente'
  // });

  // Noticia
  // Cliente.hasMany(Noticia, {
  //   foreignKey: 'cliente_id',
  //   as: 'noticias'
  // });
  // Noticia.belongsTo(Cliente, {
  //   foreignKey: 'cliente_id',
  //   as: 'cliente'
  // });

  // Chat
  // Cliente.hasMany(Chat, {
  //   foreignKey: 'cliente_id',
  //   as: 'chats'
  // });
  // Chat.belongsTo(Cliente, {
  //   foreignKey: 'cliente_id',
  //   as: 'cliente'
  // });

  // Notificacao
  // Cliente.hasMany(Notificacao, {
  //   foreignKey: 'cliente_id',
  //   as: 'notificacoes'
  // });
  // Notificacao.belongsTo(Cliente, {
  //   foreignKey: 'cliente_id',
  //   as: 'cliente'
  // });
}, 100);

// Associações com Licença (comentadas temporariamente)
// Usuario.belongsTo(Licenca, {
//   foreignKey: 'licenca_id',
//   as: 'licenca'
// });

// Licenca.belongsTo(Usuario, {
//   foreignKey: 'criado_por',
//   as: 'criador'
// });

// Licenca.belongsTo(Usuario, {
//   foreignKey: 'usuario_id',
//   as: 'usuario'
// });

module.exports = {
  Usuario,
  Gabinete,
  Licenca,
  Chat,
  Notificacao,
  Cliente,
  TipoContrato,
  Projeto,
  Demanda,
  Reuniao,
  Tarefa,
  Noticia
};