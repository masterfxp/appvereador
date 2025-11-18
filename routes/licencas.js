const express = require('express');
const router = express.Router();
const { body, validationResult } = require('express-validator');
const { auth } = require('../middleware/auth');
const { Licenca, Usuario } = require('../models');
const { v4: uuidv4 } = require('uuid');

// GET /api/licencas - Listar licenças (apenas admin)
router.get('/', auth, async (req, res) => {
    try {
        // Verificar se é administrador
        if (req.user.nivel !== 'administrador') {
            return res.status(403).json({ error: 'Acesso negado' });
        }

        const licencas = await Licenca.findAll({
            include: [
                {
                    model: Usuario,
                    as: 'criador',
                    attributes: ['id', 'nome', 'email']
                },
                {
                    model: Usuario,
                    as: 'usuario',
                    attributes: ['id', 'nome', 'email']
                }
            ],
            order: [['created_at', 'DESC']]
        });

        res.json(licencas);
    } catch (error) {
        console.error('Erro ao listar licenças:', error);
        res.status(500).json({ error: 'Erro interno do servidor' });
    }
});

// POST /api/licencas - Criar nova licença (apenas admin)
router.post('/', [
    auth,
    body('nome').notEmpty().withMessage('Nome é obrigatório'),
    body('email').isEmail().withMessage('Email inválido'),
    body('nivel').isIn(['vereador', 'assessor']).withMessage('Nível inválido')
], async (req, res) => {
    try {
        // Verificar se é administrador
        if (req.user.nivel !== 'administrador') {
            return res.status(403).json({ error: 'Acesso negado' });
        }

        const errors = validationResult(req);
        if (!errors.isEmpty()) {
            return res.status(400).json({ errors: errors.array() });
        }

        const { nome, email, nivel } = req.body;

        // Verificar se já existe licença para este email
        const licencaExistente = await Licenca.findOne({ where: { email } });
        if (licencaExistente) {
            return res.status(400).json({ error: 'Já existe licença para este email' });
        }

        // Gerar GUID único
        const guid = uuidv4();

        // Criar licença
        const licenca = await Licenca.create({
            guid,
            nome,
            email,
            nivel,
            criado_por: req.user.id
        });

        res.status(201).json({
            id: licenca.id,
            guid: licenca.guid,
            nome: licenca.nome,
            email: licenca.email,
            nivel: licenca.nivel,
            ativa: licenca.ativa,
            usada: licenca.usada,
            created_at: licenca.created_at
        });
    } catch (error) {
        console.error('Erro ao criar licença:', error);
        res.status(500).json({ error: 'Erro interno do servidor' });
    }
});

// GET /api/licencas/:id - Obter licença específica
router.get('/:id', auth, async (req, res) => {
    try {
        const licenca = await Licenca.findByPk(req.params.id, {
            include: [
                {
                    model: Usuario,
                    as: 'criador',
                    attributes: ['id', 'nome', 'email']
                },
                {
                    model: Usuario,
                    as: 'usuario',
                    attributes: ['id', 'nome', 'email']
                }
            ]
        });

        if (!licenca) {
            return res.status(404).json({ error: 'Licença não encontrada' });
        }

        // Verificar se é admin ou se é a própria licença
        if (req.user.nivel !== 'administrador' && licenca.criado_por !== req.user.id) {
            return res.status(403).json({ error: 'Acesso negado' });
        }

        res.json(licenca);
    } catch (error) {
        console.error('Erro ao obter licença:', error);
        res.status(500).json({ error: 'Erro interno do servidor' });
    }
});

// PUT /api/licencas/:id - Atualizar licença
router.put('/:id', [
    auth,
    body('ativa').optional().isBoolean().withMessage('Ativa deve ser booleano')
], async (req, res) => {
    try {
        // Verificar se é administrador
        if (req.user.nivel !== 'administrador') {
            return res.status(403).json({ error: 'Acesso negado' });
        }

        const errors = validationResult(req);
        if (!errors.isEmpty()) {
            return res.status(400).json({ errors: errors.array() });
        }

        const licenca = await Licenca.findByPk(req.params.id);
        if (!licenca) {
            return res.status(404).json({ error: 'Licença não encontrada' });
        }

        const { ativa } = req.body;

        await licenca.update({
            ...(ativa !== undefined && { ativa })
        });

        res.json(licenca);
    } catch (error) {
        console.error('Erro ao atualizar licença:', error);
        res.status(500).json({ error: 'Erro interno do servidor' });
    }
});

// DELETE /api/licencas/:id - Deletar licença
router.delete('/:id', auth, async (req, res) => {
    try {
        // Verificar se é administrador
        if (req.user.nivel !== 'administrador') {
            return res.status(403).json({ error: 'Acesso negado' });
        }

        const licenca = await Licenca.findByPk(req.params.id);
        if (!licenca) {
            return res.status(404).json({ error: 'Licença não encontrada' });
        }

        // Verificar se a licença já foi usada
        if (licenca.usada) {
            return res.status(400).json({ error: 'Não é possível deletar licença já utilizada' });
        }

        await licenca.destroy();
        res.json({ message: 'Licença deletada com sucesso' });
    } catch (error) {
        console.error('Erro ao deletar licença:', error);
        res.status(500).json({ error: 'Erro interno do servidor' });
    }
});

// POST /api/licencas/validar - Validar GUID de licença (para registro)
router.post('/validar', [
    body('guid').notEmpty().withMessage('GUID é obrigatório')
], async (req, res) => {
    try {
        const errors = validationResult(req);
        if (!errors.isEmpty()) {
            return res.status(400).json({ errors: errors.array() });
        }

        const { guid } = req.body;

        const licenca = await Licenca.findOne({
            where: { guid, ativa: true, usada: false }
        });

        if (!licenca) {
            return res.status(400).json({ error: 'Licença inválida ou já utilizada' });
        }

        res.json({
            valida: true,
            nome: licenca.nome,
            email: licenca.email,
            nivel: licenca.nivel
        });
    } catch (error) {
        console.error('Erro ao validar licença:', error);
        res.status(500).json({ error: 'Erro interno do servidor' });
    }
});

module.exports = router;


