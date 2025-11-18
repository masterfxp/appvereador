const { Notificacao } = require('../models/Notificacao');
const { Usuario } = require('../models/Usuario');
const { Projeto } = require('../models/Projeto');
const { Demanda } = require('../models/Demanda');
const { Reuniao } = require('../models/Reuniao');
const { Tarefa } = require('../models/Tarefa');

class NotificacaoService {
  
  // Criar notificação
  static async criarNotificacao(usuarioId, tipo, titulo, mensagem, opcoes = {}) {
    try {
      const notificacao = await Notificacao.create({
        usuario_id: usuarioId,
        tipo,
        titulo,
        mensagem,
        lida: false,
        acao_url: opcoes.acao_url || null,
        acao_texto: opcoes.acao_texto || null,
        dados_extras: opcoes.dados_extras || null,
        prioridade: opcoes.prioridade || 'media',
        expira_em: opcoes.expira_em || null
      });
      
      return notificacao;
    } catch (error) {
      console.error('Erro ao criar notificação:', error);
      throw error;
    }
  }

  // Buscar notificações do usuário
  static async buscarNotificacoes(usuarioId, filtros = {}) {
    try {
      const where = { usuario_id: usuarioId };
      
      if (filtros.lida !== undefined) {
        where.lida = filtros.lida;
      }
      
      if (filtros.tipo) {
        where.tipo = filtros.tipo;
      }
      
      if (filtros.prioridade) {
        where.prioridade = filtros.prioridade;
      }

      // Não mostrar notificações expiradas
      where[Op.or] = [
        { expira_em: null },
        { expira_em: { [Op.gt]: new Date() } }
      ];

      const notificacoes = await Notificacao.findAll({
        where,
        order: [['created_at', 'DESC']],
        limit: filtros.limit || 50
      });

      return notificacoes;
    } catch (error) {
      console.error('Erro ao buscar notificações:', error);
      throw error;
    }
  }

  // Marcar notificação como lida
  static async marcarComoLida(notificacaoId, usuarioId) {
    try {
      await Notificacao.update(
        { 
          lida: true, 
          data_leitura: new Date() 
        },
        { 
          where: { 
            id: notificacaoId, 
            usuario_id: usuarioId 
          } 
        }
      );
    } catch (error) {
      console.error('Erro ao marcar notificação como lida:', error);
      throw error;
    }
  }

  // Marcar todas como lidas
  static async marcarTodasComoLidas(usuarioId) {
    try {
      await Notificacao.update(
        { 
          lida: true, 
          data_leitura: new Date() 
        },
        { 
          where: { 
            usuario_id: usuarioId,
            lida: false
          } 
        }
      );
    } catch (error) {
      console.error('Erro ao marcar todas as notificações como lidas:', error);
      throw error;
    }
  }

  // Contar notificações não lidas
  static async contarNaoLidas(usuarioId) {
    try {
      const count = await Notificacao.count({
        where: {
          usuario_id: usuarioId,
          lida: false,
          [Op.or]: [
            { expira_em: null },
            { expira_em: { [Op.gt]: new Date() } }
          ]
        }
      });
      
      return count;
    } catch (error) {
      console.error('Erro ao contar notificações não lidas:', error);
      throw error;
    }
  }

  // Gerar notificações inteligentes
  static async gerarNotificacoesInteligentes(usuarioId) {
    try {
      const notificacoes = [];

      // 1. Verificar projetos em atraso
      const projetosAtrasados = await Projeto.findAll({
        where: {
          responsavel_id: usuarioId,
          status: ['em_elaboracao', 'protocolado'],
          data_vencimento: { [Op.lt]: new Date() }
        }
      });

      for (const projeto of projetosAtrasados) {
        notificacoes.push({
          usuario_id: usuarioId,
          tipo: 'warning',
          titulo: 'Projeto em Atraso',
          mensagem: `O projeto "${projeto.titulo}" está com prazo vencido`,
          prioridade: 'alta',
          acao_url: '/projetos',
          acao_texto: 'Ver Projeto',
          dados_extras: { projeto_id: projeto.id }
        });
      }

      // 2. Verificar demandas urgentes
      const demandasUrgentes = await Demanda.findAll({
        where: {
          responsavel_id: usuarioId,
          prioridade: 'alta',
          status: ['pendente', 'em_andamento']
        }
      });

      for (const demanda of demandasUrgentes) {
        notificacoes.push({
          usuario_id: usuarioId,
          tipo: 'error',
          titulo: 'Demanda Urgente',
          mensagem: `A demanda "${demanda.titulo}" requer atenção imediata`,
          prioridade: 'urgente',
          acao_url: '/demandas',
          acao_texto: 'Ver Demanda',
          dados_extras: { demanda_id: demanda.id }
        });
      }

      // 3. Verificar reuniões próximas (próximas 2 horas)
      const agora = new Date();
      const proximas2Horas = new Date(agora.getTime() + 2 * 60 * 60 * 1000);

      const reunioesProximas = await Reuniao.findAll({
        where: {
          responsavel_id: usuarioId,
          data: {
            [Op.gte]: agora.toISOString().split('T')[0],
            [Op.lte]: proximas2Horas.toISOString().split('T')[0]
          },
          hora: {
            [Op.gte]: agora.toTimeString().split(' ')[0],
            [Op.lte]: proximas2Horas.toTimeString().split(' ')[0]
          }
        }
      });

      for (const reuniao of reunioesProximas) {
        notificacoes.push({
          usuario_id: usuarioId,
          tipo: 'info',
          titulo: 'Reunião Próxima',
          mensagem: `Você tem uma reunião "${reuniao.titulo}" em breve`,
          prioridade: 'media',
          acao_url: '/reunioes',
          acao_texto: 'Ver Reunião',
          dados_extras: { reuniao_id: reuniao.id }
        });
      }

      // 4. Verificar tarefas próximas do vencimento (próximos 3 dias)
      const proximos3Dias = new Date(agora.getTime() + 3 * 24 * 60 * 60 * 1000);

      const tarefasProximas = await Tarefa.findAll({
        where: {
          responsavel_id: usuarioId,
          status: ['pendente', 'em_andamento'],
          data_vencimento: {
            [Op.gte]: agora,
            [Op.lte]: proximos3Dias
          }
        }
      });

      for (const tarefa of tarefasProximas) {
        const diasRestantes = Math.ceil((new Date(tarefa.data_vencimento) - agora) / (1000 * 60 * 60 * 24));
        
        notificacoes.push({
          usuario_id: usuarioId,
          tipo: diasRestantes <= 1 ? 'warning' : 'info',
          titulo: 'Tarefa Próxima do Vencimento',
          mensagem: `A tarefa "${tarefa.titulo}" vence em ${diasRestantes} dia(s)`,
          prioridade: diasRestantes <= 1 ? 'alta' : 'media',
          acao_url: '/tarefas',
          acao_texto: 'Ver Tarefa',
          dados_extras: { tarefa_id: tarefa.id, dias_restantes: diasRestantes }
        });
      }

      // 5. Verificar baixa produtividade (menos de 2 atividades nos últimos 7 dias)
      const umaSemanaAtras = new Date(agora.getTime() - 7 * 24 * 60 * 60 * 1000);
      
      const atividadesRecentes = await Promise.all([
        Projeto.count({
          where: {
            responsavel_id: usuarioId,
            created_at: { [Op.gte]: umaSemanaAtras }
          }
        }),
        Demanda.count({
          where: {
            responsavel_id: usuarioId,
            created_at: { [Op.gte]: umaSemanaAtras }
          }
        }),
        Tarefa.count({
          where: {
            responsavel_id: usuarioId,
            created_at: { [Op.gte]: umaSemanaAtras }
          }
        })
      ]);

      const totalAtividades = atividadesRecentes.reduce((sum, count) => sum + count, 0);

      if (totalAtividades < 2) {
        notificacoes.push({
          usuario_id: usuarioId,
          tipo: 'info',
          titulo: 'Baixa Atividade',
          mensagem: 'Você teve poucas atividades nos últimos 7 dias. Que tal planejar algumas ações?',
          prioridade: 'baixa',
          acao_url: '/dashboard',
          acao_texto: 'Ver Dashboard'
        });
      }

      // Criar notificações no banco
      for (const notificacao of notificacoes) {
        await this.criarNotificacao(
          notificacao.usuario_id,
          notificacao.tipo,
          notificacao.titulo,
          notificacao.mensagem,
          {
            prioridade: notificacao.prioridade,
            acao_url: notificacao.acao_url,
            acao_texto: notificacao.acao_texto,
            dados_extras: notificacao.dados_extras
          }
        );
      }

      return notificacoes.length;
    } catch (error) {
      console.error('Erro ao gerar notificações inteligentes:', error);
      throw error;
    }
  }

  // Limpar notificações antigas (mais de 30 dias)
  static async limparNotificacoesAntigas() {
    try {
      const trintaDiasAtras = new Date();
      trintaDiasAtras.setDate(trintaDiasAtras.getDate() - 30);

      await Notificacao.destroy({
        where: {
          created_at: { [Op.lt]: trintaDiasAtras }
        }
      });
    } catch (error) {
      console.error('Erro ao limpar notificações antigas:', error);
      throw error;
    }
  }
}

module.exports = NotificacaoService;


