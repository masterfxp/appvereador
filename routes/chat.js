const express = require('express');
const { body, validationResult, query } = require('express-validator');
const { Chat, Usuario, Gabinete } = require('../models');
const { authMiddleware, gabineteMiddleware } = require('../middleware/auth');
const multer = require('multer');
const path = require('path');

const router = express.Router();

// Configurar multer para upload de arquivos
const storage = multer.diskStorage({
  destination: (req, file, cb) => {
    cb(null, 'uploads/chat/');
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

// @route   GET /api/chat/mensagens
// @desc    Listar mensagens do chat
// @access  Private
router.get('/mensagens', [authMiddleware, gabineteMiddleware], async (req, res) => {
  try {
    const { 
      page = 1, 
      limit = 50,
      destinatario_id,
      grupo,
      data_inicio,
      data_fim
    } = req.query;

    const where = {
      gabinete_id: req.user.gabinete_id,
      excluida: false
    };

    // Se for grupo, buscar mensagens do grupo
    if (grupo === 'true') {
      where.grupo = true;
    } else if (destinatario_id) {
      // Conversa privada
      where[require('sequelize').Op.or] = [
        {
          remetente_id: req.user.id,
          destinatario_id: destinatario_id
        },
        {
          remetente_id: destinatario_id,
          destinatario_id: req.user.id
        }
      ];
    } else {
      // Todas as mensagens do gabinete
      where[require('sequelize').Op.or] = [
        { remetente_id: req.user.id },
        { destinatario_id: req.user.id },
        { grupo: true }
      ];
    }

    if (data_inicio && data_fim) {
      where.created_at = {
        [require('sequelize').Op.between]: [data_inicio, data_fim]
      };
    }

    const mensagens = await Chat.findAndCountAll({
      where,
      include: [
        { model: Usuario, as: 'remetente', attributes: ['id', 'nome', 'foto', 'nivel'] },
        { model: Usuario, as: 'destinatario', attributes: ['id', 'nome', 'foto', 'nivel'] }
      ],
      order: [['created_at', 'ASC']],
      limit: parseInt(limit),
      offset: (parseInt(page) - 1) * parseInt(limit)
    });

    res.json({
      mensagens: mensagens.rows,
      pagination: {
        currentPage: parseInt(page),
        totalPages: Math.ceil(mensagens.count / parseInt(limit)),
        totalItems: mensagens.count,
        itemsPerPage: parseInt(limit)
      }
    });

  } catch (error) {
    console.error('Erro ao listar mensagens:', error);
    res.status(500).json({ error: 'Erro interno do servidor' });
  }
});

// @route   POST /api/chat/mensagem
// @desc    Enviar mensagem
// @access  Private
router.post('/mensagem', [
  authMiddleware,
  gabineteMiddleware,
  upload.single('anexo'),
  body('mensagem').notEmpty().withMessage('Mensagem é obrigatória'),
  body('destinatario_id').optional().isInt().withMessage('ID do destinatário deve ser um número'),
  body('grupo').optional().isBoolean().withMessage('Grupo deve ser booleano'),
  body('nome_grupo').optional().isString().withMessage('Nome do grupo deve ser string')
], async (req, res) => {
  try {
    const errors = validationResult(req);
    if (!errors.isEmpty()) {
      return res.status(400).json({ errors: errors.array() });
    }

    const {
      mensagem,
      destinatario_id,
      grupo = false,
      nome_grupo,
      participantes_grupo
    } = req.body;

    // Verificar se destinatário existe e pertence ao gabinete
    if (destinatario_id && !grupo) {
      const destinatario = await Usuario.findOne({
        where: {
          id: destinatario_id,
          gabinete_id: req.user.gabinete_id
        }
      });

      if (!destinatario) {
        return res.status(404).json({ error: 'Destinatário não encontrado' });
      }
    }

    // Processar anexo
    const anexo = req.file ? {
      nome: req.file.originalname,
      arquivo: req.file.filename,
      tamanho: req.file.size,
      tipo: req.file.mimetype
    } : null;

    const tipo = anexo ? 'arquivo' : 'texto';

    const chat = await Chat.create({
      remetente_id: req.user.id,
      destinatario_id: grupo ? null : destinatario_id,
      gabinete_id: req.user.gabinete_id,
      mensagem,
      tipo,
      anexo: anexo ? anexo.arquivo : null,
      grupo: grupo === 'true',
      nome_grupo: grupo ? nome_grupo : null,
      participantes_grupo: grupo && participantes_grupo ? JSON.parse(participantes_grupo) : []
    });

    // Buscar mensagem com relacionamentos
    const mensagemCompleta = await Chat.findByPk(chat.id, {
      include: [
        { model: Usuario, as: 'remetente', attributes: ['id', 'nome', 'foto', 'nivel'] },
        { model: Usuario, as: 'destinatario', attributes: ['id', 'nome', 'foto', 'nivel'] }
      ]
    });

    res.status(201).json({
      message: 'Mensagem enviada com sucesso',
      mensagem: mensagemCompleta
    });

  } catch (error) {
    console.error('Erro ao enviar mensagem:', error);
    res.status(500).json({ error: 'Erro interno do servidor' });
  }
});

// @route   PUT /api/chat/mensagem/:id
// @desc    Editar mensagem
// @access  Private
router.put('/mensagem/:id', [
  authMiddleware,
  gabineteMiddleware,
  body('mensagem').notEmpty().withMessage('Mensagem é obrigatória')
], async (req, res) => {
  try {
    const errors = validationResult(req);
    if (!errors.isEmpty()) {
      return res.status(400).json({ errors: errors.array() });
    }

    const { mensagem } = req.body;

    const chat = await Chat.findOne({
      where: {
        id: req.params.id,
        remetente_id: req.user.id,
        gabinete_id: req.user.gabinete_id
      }
    });

    if (!chat) {
      return res.status(404).json({ error: 'Mensagem não encontrada' });
    }

    await chat.update({
      mensagem,
      editada: true,
      data_edicao: new Date()
    });

    res.json({
      message: 'Mensagem editada com sucesso',
      mensagem: chat
    });

  } catch (error) {
    console.error('Erro ao editar mensagem:', error);
    res.status(500).json({ error: 'Erro interno do servidor' });
  }
});

// @route   DELETE /api/chat/mensagem/:id
// @desc    Excluir mensagem
// @access  Private
router.delete('/mensagem/:id', [authMiddleware, gabineteMiddleware], async (req, res) => {
  try {
    const chat = await Chat.findOne({
      where: {
        id: req.params.id,
        remetente_id: req.user.id,
        gabinete_id: req.user.gabinete_id
      }
    });

    if (!chat) {
      return res.status(404).json({ error: 'Mensagem não encontrada' });
    }

    await chat.update({
      excluida: true,
      data_exclusao: new Date()
    });

    res.json({ message: 'Mensagem excluída com sucesso' });

  } catch (error) {
    console.error('Erro ao excluir mensagem:', error);
    res.status(500).json({ error: 'Erro interno do servidor' });
  }
});

// @route   GET /api/chat/conversas
// @desc    Listar conversas do usuário
// @access  Private
router.get('/conversas', [authMiddleware, gabineteMiddleware], async (req, res) => {
  try {
    // Buscar conversas privadas
    const conversasPrivadas = await Chat.findAll({
      where: {
        gabinete_id: req.user.gabinete_id,
        grupo: false,
        [require('sequelize').Op.or]: [
          { remetente_id: req.user.id },
          { destinatario_id: req.user.id }
        ]
      },
      include: [
        { model: Usuario, as: 'remetente', attributes: ['id', 'nome', 'foto', 'nivel'] },
        { model: Usuario, as: 'destinatario', attributes: ['id', 'nome', 'foto', 'nivel'] }
      ],
      order: [['created_at', 'DESC']],
      group: ['destinatario_id', 'remetente_id']
    });

    // Buscar grupos
    const grupos = await Chat.findAll({
      where: {
        gabinete_id: req.user.gabinete_id,
        grupo: true
      },
      include: [
        { model: Usuario, as: 'remetente', attributes: ['id', 'nome', 'foto', 'nivel'] }
      ],
      order: [['created_at', 'DESC']],
      group: ['nome_grupo']
    });

    // Processar conversas
    const conversas = [];

    // Adicionar conversas privadas
    conversasPrivadas.forEach(chat => {
      const outroUsuario = chat.remetente_id === req.user.id ? chat.destinatario : chat.remetente;
      if (outroUsuario) {
        conversas.push({
          id: `privada_${outroUsuario.id}`,
          tipo: 'privada',
          nome: outroUsuario.nome,
          foto: outroUsuario.foto,
          ultima_mensagem: chat.mensagem,
          data_ultima_mensagem: chat.created_at,
          nao_lidas: chat.destinatario_id === req.user.id ? !chat.lida : 0
        });
      }
    });

    // Adicionar grupos
    grupos.forEach(chat => {
      conversas.push({
        id: `grupo_${chat.nome_grupo}`,
        tipo: 'grupo',
        nome: chat.nome_grupo,
        foto: null,
        ultima_mensagem: chat.mensagem,
        data_ultima_mensagem: chat.created_at,
        nao_lidas: 0
      });
    });

    // Ordenar por data da última mensagem
    conversas.sort((a, b) => new Date(b.data_ultima_mensagem) - new Date(a.data_ultima_mensagem));

    res.json({ conversas });

  } catch (error) {
    console.error('Erro ao listar conversas:', error);
    res.status(500).json({ error: 'Erro interno do servidor' });
  }
});

// @route   POST /api/chat/marcar-lida
// @desc    Marcar mensagem como lida
// @access  Private
router.post('/marcar-lida', [
  authMiddleware,
  gabineteMiddleware,
  body('mensagem_id').isInt().withMessage('ID da mensagem é obrigatório')
], async (req, res) => {
  try {
    const errors = validationResult(req);
    if (!errors.isEmpty()) {
      return res.status(400).json({ errors: errors.array() });
    }

    const { mensagem_id } = req.body;

    const chat = await Chat.findOne({
      where: {
        id: mensagem_id,
        destinatario_id: req.user.id,
        gabinete_id: req.user.gabinete_id
      }
    });

    if (!chat) {
      return res.status(404).json({ error: 'Mensagem não encontrada' });
    }

    await chat.update({
      lida: true,
      data_leitura: new Date()
    });

    res.json({ message: 'Mensagem marcada como lida' });

  } catch (error) {
    console.error('Erro ao marcar mensagem como lida:', error);
    res.status(500).json({ error: 'Erro interno do servidor' });
  }
});

// @route   GET /api/chat/usuarios
// @desc    Listar usuários do gabinete para chat
// @access  Private
router.get('/usuarios', [authMiddleware, gabineteMiddleware], async (req, res) => {
  try {
    const usuarios = await Usuario.findAll({
      where: {
        gabinete_id: req.user.gabinete_id,
        id: { [require('sequelize').Op.ne]: req.user.id },
        ativo: true
      },
      attributes: ['id', 'nome', 'foto', 'nivel', 'ultimo_acesso'],
      order: [['nome', 'ASC']]
    });

    res.json({ usuarios });

  } catch (error) {
    console.error('Erro ao listar usuários:', error);
    res.status(500).json({ error: 'Erro interno do servidor' });
  }
});

module.exports = router;

