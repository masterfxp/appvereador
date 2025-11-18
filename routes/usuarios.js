const express = require('express');
const router = express.Router();
const { body, validationResult } = require('express-validator');
const { auth } = require('../middleware/auth');
const { Usuario, Cliente, Gabinete } = require('../models');
const { Op } = require('sequelize');
const bcrypt = require('bcrypt');

// Rotas de usuários usando banco de dados

// GET /api/usuarios - Listar usuários (filtrado por gabinete se não for admin)
router.get('/', async (req, res) => {
    try {
        let whereClause = {};
        
        // Como removemos a autenticação, não filtrar por gabinete
        // whereClause permanece vazio para listar todos os usuários
        
        // Buscar usuários do banco de dados com relacionamentos
        const usuarios = await Usuario.findAll({
            where: whereClause,
            attributes: { exclude: ['senha'] }, // Não retornar senha
            include: [
                {
                    model: Cliente,
                    as: 'cliente',
                    attributes: ['id', 'nome', 'email', 'tipo']
                },
                {
                    model: Gabinete,
                    as: 'gabinete',
                    attributes: ['id', 'nome', 'endereco']
                }
            ],
            order: [['created_at', 'DESC']]
        });
        
        res.json(usuarios);
    } catch (error) {
        console.error('Erro ao listar usuários:', error);
        res.status(500).json({ error: 'Erro interno do servidor' });
    }
});

// GET /api/usuarios/:id - Obter usuário específico
router.get('/:id', async (req, res) => {
    try {
        const usuarioId = parseInt(req.params.id);
        const usuario = await Usuario.findByPk(usuarioId);
        
        if (!usuario) {
            return res.status(404).json({ error: 'Usuário não encontrado' });
        }
        
        // Não retornar senha - o método toJSON já remove a senha
        res.json(usuario.toJSON());
    } catch (error) {
        console.error('Erro ao obter usuário:', error);
        res.status(500).json({ error: 'Erro interno do servidor' });
    }
});

// POST /api/usuarios - Criar novo usuário
router.post('/', [
    body('nome').notEmpty().withMessage('Nome é obrigatório'),
    body('email').isEmail().withMessage('Email é obrigatório'),
    body('senha').isLength({ min: 6 }).withMessage('Senha deve ter pelo menos 6 caracteres'),
    body('nivel').isIn(['administrador', 'vereador', 'assessor']).withMessage('Nível inválido'),
    body('cliente_id').notEmpty().withMessage('Cliente é obrigatório')
], async (req, res) => {
    try {
        const errors = validationResult(req);
        if (!errors.isEmpty()) {
            return res.status(400).json({ errors: errors.array() });
        }

        const { nome, email, senha, nivel, telefone, partido, cargo, ativo, gabinete_id, cliente_id } = req.body;
        
        // Verificar se email já existe no banco
        const usuarioExistente = await Usuario.findOne({ where: { email } });
        if (usuarioExistente) {
            return res.status(400).json({ error: 'Email já cadastrado' });
        }
        
        // Como removemos a autenticação, usar gabinete_id como null (apenas cliente)
        let gabineteId = null;
        
        // Verificar se cliente existe
        const cliente = await Cliente.findByPk(cliente_id);
        if (!cliente) {
            return res.status(400).json({ error: 'Cliente não encontrado' });
        }

        // Criar usuário no banco de dados
        const novoUsuario = await Usuario.create({
            nome,
            email,
            senha, // O modelo já faz o hash automaticamente
            nivel,
            telefone: telefone || null,
            partido: partido || null,
            cargo: cargo || null,
            ativo: ativo !== undefined ? ativo : true,
            gabinete_id: gabineteId,
            cliente_id: cliente_id
        });
        
        // Não retornar senha
        const { senha: _, ...usuarioSemSenha } = novoUsuario.toJSON();
        res.status(201).json(usuarioSemSenha);
    } catch (error) {
        console.error('Erro ao criar usuário:', error);
        res.status(500).json({ error: 'Erro interno do servidor' });
    }
});

// PUT /api/usuarios/:id - Atualizar usuário
router.put('/:id', [
    body('nome').optional().notEmpty().withMessage('Nome não pode ser vazio'),
    body('email').optional().isEmail().withMessage('Email inválido'),
    body('nivel').optional().isIn(['administrador', 'vereador', 'assessor']).withMessage('Nível inválido')
], async (req, res) => {
    try {
        const errors = validationResult(req);
        if (!errors.isEmpty()) {
            return res.status(400).json({ errors: errors.array() });
        }

        const usuarioId = parseInt(req.params.id);
        
        // Verificar se o usuário existe no banco
        const usuario = await Usuario.findByPk(usuarioId);
        if (!usuario) {
            return res.status(404).json({ error: 'Usuário não encontrado' });
        }

        const { nome, email, senha, nivel, telefone, partido, cargo, ativo } = req.body;
        
        // Verificar se email já existe (exceto para o próprio usuário)
        if (email && email !== usuario.email) {
            const usuarioExistente = await Usuario.findOne({ 
                where: { 
                    email: email,
                    id: { [Op.ne]: usuarioId }
                } 
            });
            if (usuarioExistente) {
                return res.status(400).json({ error: 'Email já cadastrado' });
            }
        }
        
        // Preparar dados para atualização
        const dadosAtualizacao = {
            ...(nome && { nome }),
            ...(email && { email }),
            ...(nivel && { nivel }),
            ...(telefone !== undefined && { telefone }),
            ...(partido !== undefined && { partido }),
            ...(cargo !== undefined && { cargo }),
            ...(ativo !== undefined && { ativo })
        };

        // Atualizar senha se fornecida
        if (senha) {
            dadosAtualizacao.senha = await bcrypt.hash(senha, 10);
        }

        // Atualizar usuário no banco de dados
        await Usuario.update(dadosAtualizacao, {
            where: { id: usuarioId }
        });

        // Buscar usuário atualizado
        const usuarioAtualizado = await Usuario.findByPk(usuarioId);
        
        // Não retornar senha
        const { senha: _, ...usuarioSemSenha } = usuarioAtualizado.toJSON();
        res.json(usuarioSemSenha);
    } catch (error) {
        console.error('Erro ao atualizar usuário:', error);
        res.status(500).json({ error: 'Erro interno do servidor' });
    }
});

// DELETE /api/usuarios/:id - Excluir usuário
router.delete('/:id', async (req, res) => {
    try {
        const usuarioId = parseInt(req.params.id);
        
        // Verificar se o usuário existe no banco
        const usuario = await Usuario.findByPk(usuarioId);
        if (!usuario) {
            return res.status(404).json({ error: 'Usuário não encontrado' });
        }

        // Como removemos a autenticação, permitir exclusão de qualquer usuário
        // (removida validação de auto-exclusão)

        // Excluir registros relacionados primeiro
        const { sequelize } = require('../config/database');
        
        // Excluir chats
        await sequelize.query('DELETE FROM chats WHERE remetente_id = ? OR destinatario_id = ?', {
            replacements: [usuarioId, usuarioId]
        });
        
        // Excluir tarefas
        await sequelize.query('DELETE FROM tarefas WHERE assessor_id = ? OR responsavel_id = ?', {
            replacements: [usuarioId, usuarioId]
        });
        
        // Excluir demandas
        await sequelize.query('DELETE FROM demandas WHERE responsavel_id = ?', {
            replacements: [usuarioId]
        });
        
        // Excluir projetos
        await sequelize.query('DELETE FROM projetos WHERE responsavel_id = ?', {
            replacements: [usuarioId]
        });
        
        // Excluir reuniões
        await sequelize.query('DELETE FROM reunioes WHERE responsavel_id = ?', {
            replacements: [usuarioId]
        });
        
        // Excluir notícias
        await sequelize.query('DELETE FROM noticias WHERE autor_id = ?', {
            replacements: [usuarioId]
        });
        
        // Depois excluir o usuário
        await Usuario.destroy({
            where: { id: usuarioId }
        });

        res.json({ message: 'Usuário excluído com sucesso' });
    } catch (error) {
        console.error('Erro ao excluir usuário:', error);
        res.status(500).json({ error: 'Erro interno do servidor' });
    }
});

// PUT /api/usuarios/:id/toggle-status - Ativar/Desativar usuário
router.put('/:id/toggle-status', async (req, res) => {
    try {
        const usuarioId = parseInt(req.params.id);
        
        // Verificar se o usuário existe no banco
        const usuario = await Usuario.findByPk(usuarioId);
        if (!usuario) {
            return res.status(404).json({ error: 'Usuário não encontrado' });
        }

        // Como removemos a autenticação, permitir alteração de status de qualquer usuário
        // (removida validação de auto-desativação)

        // Alternar status do usuário
        const novoStatus = !usuario.ativo;
        await Usuario.update(
            { ativo: novoStatus },
            { where: { id: usuarioId } }
        );

        res.json({ 
            message: `Usuário ${novoStatus ? 'ativado' : 'desativado'} com sucesso`,
            ativo: novoStatus
        });
    } catch (error) {
        console.error('Erro ao alterar status do usuário:', error);
        res.status(500).json({ error: 'Erro interno do servidor' });
    }
});

module.exports = router;
