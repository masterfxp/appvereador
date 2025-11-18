const express = require('express');
const router = express.Router();
const { body, validationResult } = require('express-validator');
const { auth } = require('../middleware/auth');
const { Gabinete } = require('../models/Gabinete');
const { Usuario } = require('../models/Usuario');

// GET /api/gabinetes - Listar todos os gabinetes
router.get('/', auth, async (req, res) => {
    try {
        const gabinetes = await Gabinete.findAll({
            include: [{
                model: Usuario,
                as: 'usuarios',
                attributes: ['id', 'nome', 'email', 'nivel', 'ativo']
            }],
            order: [['created_at', 'DESC']]
        });
        
        res.json(gabinetes);
    } catch (error) {
        console.error('Erro ao listar gabinetes:', error);
        res.status(500).json({ error: 'Erro interno do servidor' });
    }
});

// GET /api/gabinetes/:id - Obter gabinete específico
router.get('/:id', auth, async (req, res) => {
    try {
        const gabinete = await Gabinete.findByPk(req.params.id, {
            include: [{
                model: Usuario,
                as: 'usuarios',
                attributes: ['id', 'nome', 'email', 'nivel', 'ativo', 'created_at']
            }]
        });
        
        if (!gabinete) {
            return res.status(404).json({ error: 'Gabinete não encontrado' });
        }
        
        res.json(gabinete);
    } catch (error) {
        console.error('Erro ao obter gabinete:', error);
        res.status(500).json({ error: 'Erro interno do servidor' });
    }
});

// POST /api/gabinetes - Criar novo gabinete
router.post('/', [
    auth,
    body('nome').notEmpty().withMessage('Nome do gabinete é obrigatório'),
    body('vereador_nome').notEmpty().withMessage('Nome do vereador é obrigatório'),
    body('partido').notEmpty().withMessage('Partido é obrigatório'),
    body('municipio').notEmpty().withMessage('Município é obrigatório'),
    body('estado').isLength({ min: 2, max: 2 }).withMessage('Estado deve ter 2 caracteres')
], async (req, res) => {
    try {
        const errors = validationResult(req);
        if (!errors.isEmpty()) {
            return res.status(400).json({ errors: errors.array() });
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
            plano_governo,
            cores,
            redes_sociais
        } = req.body;
        
        const gabinete = await Gabinete.create({
            nome,
            vereador_nome,
            partido,
            telefone: telefone || null,
            email: email || null,
            endereco: endereco || null,
            municipio,
            estado,
            cep: cep || null,
            biografia: biografia || null,
            plano_governo: plano_governo || null,
            cores: cores || { primaria: '#3B82F6', secundaria: '#1E40AF' },
            redes_sociais: redes_sociais || {},
            ativo: true
        });

        res.status(201).json(gabinete);
    } catch (error) {
        console.error('Erro ao criar gabinete:', error);
        res.status(500).json({ error: 'Erro interno do servidor' });
    }
});

// PUT /api/gabinetes/:id - Atualizar gabinete
router.put('/:id', [
    auth,
    body('nome').optional().notEmpty().withMessage('Nome do gabinete não pode ser vazio'),
    body('vereador_nome').optional().notEmpty().withMessage('Nome do vereador não pode ser vazio'),
    body('estado').optional().isLength({ min: 2, max: 2 }).withMessage('Estado deve ter 2 caracteres')
], async (req, res) => {
    try {
        const errors = validationResult(req);
        if (!errors.isEmpty()) {
            return res.status(400).json({ errors: errors.array() });
        }

        const gabinete = await Gabinete.findByPk(req.params.id);
        if (!gabinete) {
            return res.status(404).json({ error: 'Gabinete não encontrado' });
        }

        const dadosAtualizacao = req.body;
        await Gabinete.update(dadosAtualizacao, {
            where: { id: req.params.id }
        });

        const gabineteAtualizado = await Gabinete.findByPk(req.params.id);
        res.json(gabineteAtualizado);
    } catch (error) {
        console.error('Erro ao atualizar gabinete:', error);
        res.status(500).json({ error: 'Erro interno do servidor' });
    }
});

// DELETE /api/gabinetes/:id - Excluir gabinete
router.delete('/:id', auth, async (req, res) => {
    try {
        const gabinete = await Gabinete.findByPk(req.params.id);
        if (!gabinete) {
            return res.status(404).json({ error: 'Gabinete não encontrado' });
        }

        // Verificar se há usuários associados
        const usuariosCount = await Usuario.count({
            where: { gabinete_id: req.params.id }
        });

        if (usuariosCount > 0) {
            return res.status(400).json({ 
                error: 'Não é possível excluir gabinete com usuários associados' 
            });
        }

        await Gabinete.destroy({
            where: { id: req.params.id }
        });

        res.json({ message: 'Gabinete excluído com sucesso' });
    } catch (error) {
        console.error('Erro ao excluir gabinete:', error);
        res.status(500).json({ error: 'Erro interno do servidor' });
    }
});

// GET /api/gabinetes/:id/usuarios - Listar usuários do gabinete
router.get('/:id/usuarios', auth, async (req, res) => {
    try {
        const usuarios = await Usuario.findAll({
            where: { gabinete_id: req.params.id },
            attributes: { exclude: ['senha'] },
            order: [['created_at', 'DESC']]
        });
        
        res.json(usuarios);
    } catch (error) {
        console.error('Erro ao listar usuários do gabinete:', error);
        res.status(500).json({ error: 'Erro interno do servidor' });
    }
});

module.exports = router;


