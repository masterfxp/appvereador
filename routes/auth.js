const express = require('express');
const bcrypt = require('bcrypt');
const jwt = require('jsonwebtoken');
const { body, validationResult } = require('express-validator');
const { Usuario, Gabinete, Licenca, Cliente } = require('../models');
const { generateToken, verifyToken } = require('../utils/jwt');

const router = express.Router();

// @route   POST /api/auth/login
// @desc    Login do usuário
// @access  Public
router.post('/login', [
  body('email').isEmail().withMessage('Email inválido'),
  body('senha').notEmpty().withMessage('Senha é obrigatória')
], async (req, res) => {
  try {
    console.log('🔐 Tentativa de login:', req.body.email);
    
    const errors = validationResult(req);
    if (!errors.isEmpty()) {
      console.log('❌ Erro de validação:', errors.array());
      return res.status(400).json({ errors: errors.array() });
    }

    const { email, senha } = req.body;

    // Buscar usuário
    const usuario = await Usuario.findOne({ 
      where: { email },
      include: [{
        model: Gabinete,
        as: 'gabinete'
      }, {
        model: Cliente,
        as: 'cliente'
      }]
    });

    if (!usuario) {
      console.log('❌ Usuário não encontrado:', email);
      return res.status(401).json({ error: 'Credenciais inválidas' });
    }

    if (!usuario.ativo) {
      console.log('❌ Usuário inativo:', email);
      return res.status(401).json({ error: 'Conta desativada' });
    }

    // Verificar senha
    const senhaValida = await bcrypt.compare(senha, usuario.senha);
    if (!senhaValida) {
      console.log('❌ Senha inválida para:', email);
      return res.status(401).json({ error: 'Credenciais inválidas' });
    }

    // Atualizar último acesso
    await usuario.update({ ultimo_acesso: new Date() });

    // Gerar token
    const token = generateToken({
      id: usuario.id,
      email: usuario.email,
      nivel: usuario.nivel,
      gabinete_id: usuario.gabinete_id,
      cliente_id: usuario.cliente_id
    });

    console.log('✅ Login bem-sucedido para:', email);

    res.json({
      message: 'Login realizado com sucesso',
      token,
      user: {
        id: usuario.id,
        nome: usuario.nome,
        email: usuario.email,
        nivel: usuario.nivel,
        gabinete: usuario.gabinete,
        cliente: usuario.cliente,
        cliente_id: usuario.cliente_id
      }
    });

  } catch (error) {
    console.error('❌ Erro no login:', error);
    res.status(500).json({ error: 'Erro interno do servidor' });
  }
});

// @route   POST /api/auth/register
// @desc    Registrar novo usuário
// @access  Public
router.post('/register', [
  body('guid_licenca').notEmpty().withMessage('GUID da licença é obrigatório'),
  body('nome').notEmpty().withMessage('Nome é obrigatório'),
  body('email').isEmail().withMessage('Email inválido'),
  body('senha').isLength({ min: 6 }).withMessage('Senha deve ter pelo menos 6 caracteres'),
  body('nivel').isIn(['vereador', 'assessor']).withMessage('Nível inválido')
], async (req, res) => {
  try {
    const errors = validationResult(req);
    if (!errors.isEmpty()) {
      return res.status(400).json({ errors: errors.array() });
    }

    const { guid_licenca, nome, email, senha, nivel, telefone } = req.body;

    // Validar licença
    const licenca = await Licenca.findOne({
      where: { 
        guid: guid_licenca, 
        ativa: true, 
        usada: false 
      }
    });

    if (!licenca) {
      return res.status(400).json({ error: 'Licença inválida ou já utilizada' });
    }

    // Verificar se o email da licença confere
    if (licenca.email !== email) {
      return res.status(400).json({ error: 'Email não confere com a licença' });
    }

    // Verificar se usuário já existe
    const usuarioExistente = await Usuario.findOne({ where: { email } });
    if (usuarioExistente) {
      return res.status(400).json({ error: 'Usuário já existe com este email' });
    }

    // Hash da senha
    const senhaHash = await bcrypt.hash(senha, 10);

    // Criar usuário
    const usuario = await Usuario.create({
      nome,
      email,
      senha: senhaHash,
      nivel,
      telefone: telefone || null,
      licenca_id: licenca.id,
      ativo: true
    });

    // Marcar licença como usada
    await licenca.update({
      usada: true,
      data_uso: new Date(),
      usuario_id: usuario.id
    });

    // Gerar token
    const token = generateToken({
      id: usuario.id,
      email: usuario.email,
      nivel: usuario.nivel
    });

    res.status(201).json({
      message: 'Usuário criado com sucesso',
      token,
      user: {
        id: usuario.id,
        nome: usuario.nome,
        email: usuario.email,
        nivel: usuario.nivel,
        gabinete: gabinete
      }
    });

  } catch (error) {
    console.error('Erro no registro:', error);
    res.status(500).json({ error: 'Erro interno do servidor' });
  }
});

// @route   GET /api/auth/me
// @desc    Obter dados do usuário logado
// @access  Private
router.get('/me', verifyToken, async (req, res) => {
  try {
    const usuario = await Usuario.findByPk(req.user.id, {
      include: [{
        model: Gabinete,
        as: 'gabinete'
      }]
    });

    if (!usuario) {
      return res.status(404).json({ error: 'Usuário não encontrado' });
    }

    res.json({ user: usuario.toJSON() });
  } catch (error) {
    console.error('Erro ao obter dados do usuário:', error);
    res.status(500).json({ error: 'Erro interno do servidor' });
  }
});

// @route   POST /api/auth/logout
// @desc    Logout do usuário
// @access  Private
router.post('/logout', verifyToken, (req, res) => {
  res.json({ message: 'Logout realizado com sucesso' });
});

module.exports = router;