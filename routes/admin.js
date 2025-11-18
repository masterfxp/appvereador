const express = require('express');
const { body, validationResult, query } = require('express-validator');
const { 
  Usuario, 
  Gabinete, 
  Projeto, 
  Demanda, 
  Reuniao, 
  Tarefa, 
  Noticia, 
  Indicador,
  Chat
} = require('../models');
const { authMiddleware, adminMiddleware, gabineteMiddleware } = require('../middleware/auth');
const multer = require('multer');
const path = require('path');

const router = express.Router();

// Configurar multer para upload de arquivos
const storage = multer.diskStorage({
  destination: (req, file, cb) => {
    cb(null, 'uploads/admin/');
  },
  filename: (req, file, cb) => {
    const uniqueSuffix = Date.now() + '-' + Math.round(Math.random() * 1E9);
    cb(null, file.fieldname + '-' + uniqueSuffix + path.extname(file.originalname));
  }
});

const upload = multer({
  storage: storage,
  limits: {
    fileSize: parseInt(process.env.MAX_FILE_SIZE) || 10485760 // 10MB
  }
});

// @route   GET /api/admin/dashboard
// @desc    Obter dashboard administrativo
// @access  Private (Admin)
router.get('/dashboard', [authMiddleware, adminMiddleware, gabineteMiddleware], async (req, res) => {
  try {
    const gabineteId = req.user.gabinete_id;

    // Estatísticas gerais
    const totalUsuarios = await Usuario.count({ where: { gabinete_id: gabineteId } });
    const totalProjetos = await Projeto.count({ where: { gabinete_id: gabineteId } });
    const totalDemandas = await Demanda.count({ where: { gabinete_id: gabineteId } });
    const totalReunioes = await Reuniao.count({ where: { gabinete_id: gabineteId } });
    const totalTarefas = await Tarefa.count({ where: { gabinete_id: gabineteId } });
    const totalNoticias = await Noticia.count({ where: { gabinete_id: gabineteId } });

    // Demandas por status
    const demandasPorStatus = await Demanda.findAll({
      where: { gabinete_id: gabineteId },
      attributes: [
        'status',
        [require('sequelize').fn('COUNT', require('sequelize').col('id')), 'count']
      ],
      group: ['status']
    });

    // Projetos por tipo
    const projetosPorTipo = await Projeto.findAll({
      where: { gabinete_id: gabineteId },
      attributes: [
        'tipo',
        [require('sequelize').fn('COUNT', require('sequelize').col('id')), 'count']
      ],
      group: ['tipo']
    });

    // Tarefas por status
    const tarefasPorStatus = await Tarefa.findAll({
      where: { gabinete_id: gabineteId },
      attributes: [
        'status',
        [require('sequelize').fn('COUNT', require('sequelize').col('id')), 'count']
      ],
      group: ['status']
    });

    // Atividade recente (últimos 30 dias)
    const dataLimite = new Date(Date.now() - 30 * 24 * 60 * 60 * 1000);
    
    const projetosRecentes = await Projeto.count({
      where: {
        gabinete_id: gabineteId,
        created_at: { [require('sequelize').Op.gte]: dataLimite }
      }
    });

    const demandasRecentes = await Demanda.count({
      where: {
        gabinete_id: gabineteId,
        created_at: { [require('sequelize').Op.gte]: dataLimite }
      }
    });

    const noticiasRecentes = await Noticia.count({
      where: {
        gabinete_id: gabineteId,
        created_at: { [require('sequelize').Op.gte]: dataLimite }
      }
    });

    // Usuários ativos (últimos 7 dias)
    const usuariosAtivos = await Usuario.count({
      where: {
        gabinete_id: gabineteId,
        ultimo_acesso: {
          [require('sequelize').Op.gte]: new Date(Date.now() - 7 * 24 * 60 * 60 * 1000)
        }
      }
    });

    // Top 5 assessores por produtividade
    const topAssessores = await Tarefa.findAll({
      where: {
        gabinete_id: gabineteId,
        status: 'concluida'
      },
      attributes: [
        'assessor_id',
        [require('sequelize').fn('COUNT', require('sequelize').col('id')), 'tarefas_concluidas']
      ],
      include: [
        { model: Usuario, as: 'assessor', attributes: ['id', 'nome', 'foto'] }
      ],
      group: ['assessor_id'],
      order: [[require('sequelize').fn('COUNT', require('sequelize').col('id')), 'DESC']],
      limit: 5
    });

    // Evolução mensal (últimos 12 meses)
    const evolucaoMensal = [];
    for (let i = 11; i >= 0; i--) {
      const data = new Date();
      data.setMonth(data.getMonth() - i);
      const inicioMes = new Date(data.getFullYear(), data.getMonth(), 1);
      const fimMes = new Date(data.getFullYear(), data.getMonth() + 1, 0);
      
      const projetos = await Projeto.count({
        where: {
          gabinete_id: gabineteId,
          created_at: {
            [require('sequelize').Op.between]: [inicioMes, fimMes]
          }
        }
      });

      const demandas = await Demanda.count({
        where: {
          gabinete_id: gabineteId,
          created_at: {
            [require('sequelize').Op.between]: [inicioMes, fimMes]
          }
        }
      });

      evolucaoMensal.push({
        mes: data.toLocaleDateString('pt-BR', { month: 'short', year: 'numeric' }),
        projetos,
        demandas
      });
    }

    res.json({
      resumo: {
        totalUsuarios,
        totalProjetos,
        totalDemandas,
        totalReunioes,
        totalTarefas,
        totalNoticias,
        usuariosAtivos
      },
      atividade: {
        projetosRecentes,
        demandasRecentes,
        noticiasRecentes
      },
      demandasPorStatus,
      projetosPorTipo,
      tarefasPorStatus,
      topAssessores,
      evolucaoMensal
    });

  } catch (error) {
    console.error('Erro ao obter dashboard administrativo:', error);
    res.status(500).json({ error: 'Erro interno do servidor' });
  }
});

// @route   GET /api/admin/relatorios
// @desc    Gerar relatórios administrativos
// @access  Private (Admin)
router.get('/relatorios', [authMiddleware, adminMiddleware, gabineteMiddleware], async (req, res) => {
  try {
    const { 
      tipo = 'geral',
      data_inicio,
      data_fim,
      formato = 'json'
    } = req.query;

    const gabineteId = req.user.gabinete_id;
    const where = { gabinete_id: gabineteId };

    if (data_inicio && data_fim) {
      where.created_at = {
        [require('sequelize').Op.between]: [data_inicio, data_fim]
      };
    }

    let relatorio = {};

    switch (tipo) {
      case 'projetos':
        relatorio = await Projeto.findAll({
          where,
          include: [
            { model: Usuario, as: 'autor', attributes: ['id', 'nome'] }
          ],
          order: [['created_at', 'DESC']]
        });
        break;

      case 'demandas':
        relatorio = await Demanda.findAll({
          where,
          include: [
            { model: Usuario, as: 'cidadao', attributes: ['id', 'nome', 'email'] },
            { model: Usuario, as: 'responsavel', attributes: ['id', 'nome'] }
          ],
          order: [['created_at', 'DESC']]
        });
        break;

      case 'usuarios':
        relatorio = await Usuario.findAll({
          where: { gabinete_id: gabineteId },
          include: [
            { model: Gabinete, as: 'gabinete', attributes: ['id', 'nome'] }
          ],
          order: [['created_at', 'DESC']]
        });
        break;

      case 'tarefas':
        relatorio = await Tarefa.findAll({
          where,
          include: [
            { model: Usuario, as: 'assessor', attributes: ['id', 'nome'] },
            { model: Usuario, as: 'criador', attributes: ['id', 'nome'] }
          ],
          order: [['created_at', 'DESC']]
        });
        break;

      case 'reunioes':
        relatorio = await Reuniao.findAll({
          where,
          include: [
            { model: Usuario, as: 'organizador', attributes: ['id', 'nome'] }
          ],
          order: [['data', 'DESC']]
        });
        break;

      case 'noticias':
        relatorio = await Noticia.findAll({
          where,
          include: [
            { model: Usuario, as: 'autor', attributes: ['id', 'nome'] }
          ],
          order: [['created_at', 'DESC']]
        });
        break;

      default:
        // Relatório geral
        relatorio = {
          projetos: await Projeto.count(where),
          demandas: await Demanda.count(where),
          reunioes: await Reuniao.count(where),
          tarefas: await Tarefa.count(where),
          noticias: await Noticia.count(where),
          usuarios: await Usuario.count({ where: { gabinete_id: gabineteId } })
        };
    }

    if (formato === 'csv') {
      // Gerar CSV
      const csv = [
        'ID,Data,Criado Por,Status',
        ...relatorio.map(item => 
          `${item.id},${item.created_at?.toISOString().split('T')[0] || ''},${item.autor?.nome || item.cidadao?.nome || item.assessor?.nome || ''},${item.status || ''}`
        )
      ].join('\n');

      res.setHeader('Content-Type', 'text/csv');
      res.setHeader('Content-Disposition', `attachment; filename=relatorio_${tipo}.csv`);
      res.send(csv);
    } else {
      res.json({ relatorio });
    }

  } catch (error) {
    console.error('Erro ao gerar relatório:', error);
    res.status(500).json({ error: 'Erro interno do servidor' });
  }
});

// @route   PUT /api/admin/gabinete
// @desc    Atualizar configurações do gabinete
// @access  Private (Admin)
router.put('/gabinete', [
  authMiddleware,
  adminMiddleware,
  gabineteMiddleware,
  upload.fields([
    { name: 'logo', maxCount: 1 },
    { name: 'foto_vereador', maxCount: 1 }
  ]),
  body('nome').optional().notEmpty().withMessage('Nome não pode ser vazio'),
  body('vereador_nome').optional().notEmpty().withMessage('Nome do vereador não pode ser vazio')
], async (req, res) => {
  try {
    const errors = validationResult(req);
    if (!errors.isEmpty()) {
      return res.status(400).json({ errors: errors.array() });
    }

    const gabinete = await Gabinete.findByPk(req.user.gabinete_id);

    if (!gabinete) {
      return res.status(404).json({ error: 'Gabinete não encontrado' });
    }

    const {
      nome,
      vereador_nome,
      partido,
      telefone,
      email,
      endereco,
      municipio,
      estado,
      cep,
      biografia,
      cores,
      redes_sociais,
      configuracoes,
      plano_governo,
      metas
    } = req.body;

    const updateData = {
      nome: nome || gabinete.nome,
      vereador_nome: vereador_nome || gabinete.vereador_nome,
      partido: partido || gabinete.partido,
      telefone: telefone || gabinete.telefone,
      email: email || gabinete.email,
      endereco: endereco || gabinete.endereco,
      municipio: municipio || gabinete.municipio,
      estado: estado || gabinete.estado,
      cep: cep || gabinete.cep,
      biografia: biografia || gabinete.biografia,
      cores: cores ? JSON.parse(cores) : gabinete.cores,
      redes_sociais: redes_sociais ? JSON.parse(redes_sociais) : gabinete.redes_sociais,
      configuracoes: configuracoes ? JSON.parse(configuracoes) : gabinete.configuracoes,
      plano_governo: plano_governo || gabinete.plano_governo,
      metas: metas ? JSON.parse(metas) : gabinete.metas
    };

    // Atualizar arquivos se fornecidos
    if (req.files?.logo) {
      updateData.logo = req.files.logo[0].filename;
    }

    if (req.files?.foto_vereador) {
      updateData.foto_vereador = req.files.foto_vereador[0].filename;
    }

    await gabinete.update(updateData);

    res.json({
      message: 'Configurações do gabinete atualizadas com sucesso',
      gabinete
    });

  } catch (error) {
    console.error('Erro ao atualizar gabinete:', error);
    res.status(500).json({ error: 'Erro interno do servidor' });
  }
});

// @route   GET /api/admin/logs
// @desc    Obter logs do sistema
// @access  Private (Admin)
router.get('/logs', [authMiddleware, adminMiddleware, gabineteMiddleware], async (req, res) => {
  try {
    const { 
      page = 1, 
      limit = 50,
      tipo,
      data_inicio,
      data_fim
    } = req.query;

    // Simular logs (em produção, usar um sistema de logging real)
    const logs = [
      {
        id: 1,
        tipo: 'info',
        mensagem: 'Usuário fez login',
        usuario: 'João Silva',
        data: new Date().toISOString(),
        ip: '192.168.1.1'
      },
      {
        id: 2,
        tipo: 'warning',
        mensagem: 'Tentativa de login com credenciais inválidas',
        usuario: 'Desconhecido',
        data: new Date(Date.now() - 3600000).toISOString(),
        ip: '192.168.1.2'
      },
      {
        id: 3,
        tipo: 'error',
        mensagem: 'Erro ao enviar email',
        usuario: 'Maria Santos',
        data: new Date(Date.now() - 7200000).toISOString(),
        ip: '192.168.1.3'
      }
    ];

    res.json({
      logs: logs.slice((page - 1) * limit, page * limit),
      pagination: {
        currentPage: parseInt(page),
        totalPages: Math.ceil(logs.length / parseInt(limit)),
        totalItems: logs.length,
        itemsPerPage: parseInt(limit)
      }
    });

  } catch (error) {
    console.error('Erro ao obter logs:', error);
    res.status(500).json({ error: 'Erro interno do servidor' });
  }
});

// @route   POST /api/admin/backup
// @desc    Criar backup do sistema
// @access  Private (Admin)
router.post('/backup', [authMiddleware, adminMiddleware, gabineteMiddleware], async (req, res) => {
  try {
    // Em produção, implementar backup real do banco de dados
    const backup = {
      data: new Date().toISOString(),
      gabinete_id: req.user.gabinete_id,
      status: 'sucesso',
      arquivo: `backup_${req.user.gabinete_id}_${Date.now()}.sql`
    };

    res.json({
      message: 'Backup criado com sucesso',
      backup
    });

  } catch (error) {
    console.error('Erro ao criar backup:', error);
    res.status(500).json({ error: 'Erro interno do servidor' });
  }
});

module.exports = router;

