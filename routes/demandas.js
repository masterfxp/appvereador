const express = require('express');
const router = express.Router();
const { body, validationResult } = require('express-validator');
const { auth } = require('../middleware/auth');

// Mock data para demonstração
let demandas = [
    {
        id: 1,
        assunto: 'Buraco na rua principal',
        descricao: 'Existe um buraco grande na rua principal que está causando problemas para os veículos',
        cidadao: {
            nome: 'João Silva',
            email: 'joao@email.com',
            telefone: '(11) 99999-9999'
        },
        status: 'pendente',
        prioridade: 'alta',
        localizacao: {
            endereco: 'Rua Principal, 123',
            latitude: -23.5505,
            longitude: -46.6333
        },
        created_at: new Date('2024-01-15'),
        updated_at: new Date('2024-01-15')
    },
    {
        id: 2,
        assunto: 'Iluminação pública',
        descricao: 'Poste de luz quebrado na praça central',
        cidadao: {
            nome: 'Maria Santos',
            email: 'maria@email.com',
            telefone: '(11) 88888-8888'
        },
        status: 'em_andamento',
        prioridade: 'media',
        localizacao: {
            endereco: 'Praça Central',
            latitude: -23.5515,
            longitude: -46.6343
        },
        created_at: new Date('2024-01-14'),
        updated_at: new Date('2024-01-16')
    }
];

// GET /api/demandas - Listar todas as demandas
router.get('/', auth, async (req, res) => {
    try {
        // Sempre retornar dados (mock se necessário)
        if (demandas.length === 0) {
            demandas = [
                {
                    id: 1,
                    assunto: 'Buraco na rua principal',
                    titulo: 'Buraco na rua principal',
                    descricao: 'Existe um buraco grande na rua principal que está causando problemas para os veículos',
                    cidadao: {
                        nome: 'João Silva',
                        email: 'joao@email.com',
                        telefone: '(11) 99999-9999'
                    },
                    status: 'pendente',
                    prioridade: 'alta',
                    localizacao: {
                        endereco: 'Rua Principal, 123',
                        latitude: -23.5505,
                        longitude: -46.6333
                    },
                    created_at: new Date('2024-01-15'),
                    updated_at: new Date('2024-01-15')
                },
                {
                    id: 2,
                    assunto: 'Iluminação pública',
                    titulo: 'Iluminação pública',
                    descricao: 'Poste de luz quebrado na praça central',
                    cidadao: {
                        nome: 'Maria Santos',
                        email: 'maria@email.com',
                        telefone: '(11) 88888-8888'
                    },
                    status: 'em_andamento',
                    prioridade: 'media',
                    localizacao: {
                        endereco: 'Praça Central',
                        latitude: -23.5515,
                        longitude: -46.6343
                    },
                    created_at: new Date('2024-01-14'),
                    updated_at: new Date('2024-01-16')
                }
            ];
        }
        res.json(demandas);
    } catch (error) {
        console.error('Erro ao listar demandas:', error);
        // Em caso de erro, retornar dados mock
        res.json([
            {
                id: 1,
                assunto: 'Buraco na rua principal',
                titulo: 'Buraco na rua principal',
                descricao: 'Existe um buraco grande na rua principal que está causando problemas para os veículos',
                cidadao: { nome: 'João Silva', email: 'joao@email.com', telefone: '(11) 99999-9999' },
                status: 'pendente',
                prioridade: 'alta',
                localizacao: { endereco: 'Rua Principal, 123' },
                created_at: new Date('2024-01-15')
            }
        ]);
    }
});

// GET /api/demandas/:id - Obter demanda específica
router.get('/:id', auth, async (req, res) => {
    try {
        const demanda = demandas.find(d => d.id === parseInt(req.params.id));
        if (!demanda) {
            return res.status(404).json({ error: 'Demanda não encontrada' });
        }
        res.json(demanda);
    } catch (error) {
        console.error('Erro ao obter demanda:', error);
        res.status(500).json({ error: 'Erro interno do servidor' });
    }
});

// POST /api/demandas - Criar nova demanda
router.post('/', [
    auth,
    body('assunto').notEmpty().withMessage('Assunto é obrigatório'),
    body('descricao').notEmpty().withMessage('Descrição é obrigatória'),
    body('cidadao.nome').notEmpty().withMessage('Nome do cidadão é obrigatório'),
    body('cidadao.email').isEmail().withMessage('Email do cidadão é obrigatório')
], async (req, res) => {
    try {
        const errors = validationResult(req);
        if (!errors.isEmpty()) {
            return res.status(400).json({ errors: errors.array() });
        }

        const { assunto, descricao, cidadao, prioridade = 'media', localizacao } = req.body;
        
        const novaDemanda = {
            id: demandas.length + 1,
            assunto,
            descricao,
            cidadao,
            status: 'pendente',
            prioridade,
            localizacao,
            created_at: new Date(),
            updated_at: new Date()
        };

        demandas.push(novaDemanda);
        res.status(201).json(novaDemanda);
    } catch (error) {
        console.error('Erro ao criar demanda:', error);
        res.status(500).json({ error: 'Erro interno do servidor' });
    }
});

// PUT /api/demandas/:id - Atualizar demanda
router.put('/:id', [
    auth,
    body('assunto').optional().notEmpty().withMessage('Assunto não pode ser vazio'),
    body('descricao').optional().notEmpty().withMessage('Descrição não pode ser vazia'),
    body('status').optional().isIn(['pendente', 'em_andamento', 'resolvido', 'arquivado']).withMessage('Status inválido'),
    body('prioridade').optional().isIn(['baixa', 'media', 'alta', 'urgente']).withMessage('Prioridade inválida')
], async (req, res) => {
    try {
        const errors = validationResult(req);
        if (!errors.isEmpty()) {
            return res.status(400).json({ errors: errors.array() });
        }

        const demandaIndex = demandas.findIndex(d => d.id === parseInt(req.params.id));
        if (demandaIndex === -1) {
            return res.status(404).json({ error: 'Demanda não encontrada' });
        }

        const { assunto, descricao, status, prioridade, localizacao } = req.body;
        
        demandas[demandaIndex] = {
            ...demandas[demandaIndex],
            ...(assunto && { assunto }),
            ...(descricao && { descricao }),
            ...(status && { status }),
            ...(prioridade && { prioridade }),
            ...(localizacao && { localizacao }),
            updated_at: new Date()
        };

        res.json(demandas[demandaIndex]);
    } catch (error) {
        console.error('Erro ao atualizar demanda:', error);
        res.status(500).json({ error: 'Erro interno do servidor' });
    }
});

// DELETE /api/demandas/:id - Excluir demanda
router.delete('/:id', auth, async (req, res) => {
    try {
        const demandaIndex = demandas.findIndex(d => d.id === parseInt(req.params.id));
        if (demandaIndex === -1) {
            return res.status(404).json({ error: 'Demanda não encontrada' });
        }

        demandas.splice(demandaIndex, 1);
        res.json({ message: 'Demanda excluída com sucesso' });
    } catch (error) {
        console.error('Erro ao excluir demanda:', error);
        res.status(500).json({ error: 'Erro interno do servidor' });
    }
});

module.exports = router;