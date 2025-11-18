const express = require('express');
const router = express.Router();
const { body, validationResult } = require('express-validator');
const { auth } = require('../middleware/auth');

// Mock data para demonstração
let reunioes = [
    {
        id: 1,
        titulo: 'Reunião com Secretário de Educação',
        descricao: 'Discussão sobre melhorias na educação municipal',
        data: '2024-01-20',
        hora_inicio: '14:00',
        hora_fim: '16:00',
        local: 'Sala de Reuniões - Prefeitura',
        status: 'agendada',
        participantes: [
            { nome: 'João Silva', cargo: 'Vereador' },
            { nome: 'Maria Santos', cargo: 'Secretária de Educação' }
        ],
        created_at: new Date('2024-01-15'),
        updated_at: new Date('2024-01-15')
    },
    {
        id: 2,
        titulo: 'Audiência Pública - Orçamento 2024',
        descricao: 'Audiência para discussão do orçamento municipal',
        data: '2024-01-25',
        hora_inicio: '19:00',
        hora_fim: '22:00',
        local: 'Câmara Municipal',
        status: 'agendada',
        participantes: [
            { nome: 'João Silva', cargo: 'Vereador' },
            { nome: 'Carlos Lima', cargo: 'Prefeito' }
        ],
        created_at: new Date('2024-01-10'),
        updated_at: new Date('2024-01-10')
    }
];

// GET /api/reunioes - Listar todas as reuniões
router.get('/', auth, async (req, res) => {
    try {
        // Sempre retornar dados (mock se necessário)
        if (reunioes.length === 0) {
            const hoje = new Date();
            const amanha = new Date(hoje);
            amanha.setDate(amanha.getDate() + 1);
            
            reunioes = [
                {
                    id: 1,
                    titulo: 'Reunião com Secretário de Educação',
                    descricao: 'Discussão sobre melhorias na educação municipal',
                    data: amanha.toISOString().split('T')[0],
                    hora_inicio: '14:00',
                    hora_fim: '16:00',
                    local: 'Sala de Reuniões - Prefeitura',
                    status: 'agendada',
                    participantes: [
                        { nome: 'João Silva', cargo: 'Vereador' },
                        { nome: 'Maria Santos', cargo: 'Secretária de Educação' }
                    ],
                    created_at: new Date('2024-01-15'),
                    updated_at: new Date('2024-01-15')
                },
                {
                    id: 2,
                    titulo: 'Audiência Pública - Orçamento 2024',
                    descricao: 'Audiência para discussão do orçamento municipal',
                    data: amanha.toISOString().split('T')[0],
                    hora_inicio: '19:00',
                    hora_fim: '22:00',
                    local: 'Câmara Municipal',
                    status: 'agendada',
                    participantes: [
                        { nome: 'João Silva', cargo: 'Vereador' },
                        { nome: 'Carlos Lima', cargo: 'Prefeito' }
                    ],
                    created_at: new Date('2024-01-10'),
                    updated_at: new Date('2024-01-10')
                }
            ];
        }
        res.json(reunioes);
    } catch (error) {
        console.error('Erro ao listar reuniões:', error);
        // Em caso de erro, retornar dados mock
        const hoje = new Date();
        const amanha = new Date(hoje);
        amanha.setDate(amanha.getDate() + 1);
        res.json([
            {
                id: 1,
                titulo: 'Reunião com Secretário de Educação',
                descricao: 'Discussão sobre melhorias na educação municipal',
                data: amanha.toISOString().split('T')[0],
                hora_inicio: '14:00',
                hora_fim: '16:00',
                local: 'Sala de Reuniões - Prefeitura',
                status: 'agendada',
                created_at: new Date('2024-01-15')
            }
        ]);
    }
});

// GET /api/reunioes/:id - Obter reunião específica
router.get('/:id', auth, async (req, res) => {
    try {
        const reuniao = reunioes.find(r => r.id === parseInt(req.params.id));
        if (!reuniao) {
            return res.status(404).json({ error: 'Reunião não encontrada' });
        }
        res.json(reuniao);
    } catch (error) {
        console.error('Erro ao obter reunião:', error);
        res.status(500).json({ error: 'Erro interno do servidor' });
    }
});

// POST /api/reunioes - Criar nova reunião
router.post('/', [
    auth,
    body('titulo').notEmpty().withMessage('Título é obrigatório'),
    body('data').isISO8601().withMessage('Data é obrigatória'),
    body('hora_inicio').notEmpty().withMessage('Hora de início é obrigatória'),
    body('hora_fim').notEmpty().withMessage('Hora de fim é obrigatória')
], async (req, res) => {
    try {
        const errors = validationResult(req);
        if (!errors.isEmpty()) {
            return res.status(400).json({ errors: errors.array() });
        }

        const { titulo, descricao, data, hora_inicio, hora_fim, local, participantes = [] } = req.body;
        
        const novaReuniao = {
            id: reunioes.length + 1,
            titulo,
            descricao,
            data,
            hora_inicio,
            hora_fim,
            local,
            status: 'agendada',
            participantes,
            created_at: new Date(),
            updated_at: new Date()
        };

        reunioes.push(novaReuniao);
        res.status(201).json(novaReuniao);
    } catch (error) {
        console.error('Erro ao criar reunião:', error);
        res.status(500).json({ error: 'Erro interno do servidor' });
    }
});

// PUT /api/reunioes/:id - Atualizar reunião
router.put('/:id', [
    auth,
    body('titulo').optional().notEmpty().withMessage('Título não pode ser vazio'),
    body('data').optional().isISO8601().withMessage('Data inválida'),
    body('status').optional().isIn(['agendada', 'em_andamento', 'concluida', 'cancelada', 'adiada']).withMessage('Status inválido')
], async (req, res) => {
    try {
        const errors = validationResult(req);
        if (!errors.isEmpty()) {
            return res.status(400).json({ errors: errors.array() });
        }

        const reuniaoIndex = reunioes.findIndex(r => r.id === parseInt(req.params.id));
        if (reuniaoIndex === -1) {
            return res.status(404).json({ error: 'Reunião não encontrada' });
        }

        const { titulo, descricao, data, hora_inicio, hora_fim, local, status, participantes } = req.body;
        
        reunioes[reuniaoIndex] = {
            ...reunioes[reuniaoIndex],
            ...(titulo && { titulo }),
            ...(descricao && { descricao }),
            ...(data && { data }),
            ...(hora_inicio && { hora_inicio }),
            ...(hora_fim && { hora_fim }),
            ...(local && { local }),
            ...(status && { status }),
            ...(participantes && { participantes }),
            updated_at: new Date()
        };

        res.json(reunioes[reuniaoIndex]);
    } catch (error) {
        console.error('Erro ao atualizar reunião:', error);
        res.status(500).json({ error: 'Erro interno do servidor' });
    }
});

// DELETE /api/reunioes/:id - Excluir reunião
router.delete('/:id', auth, async (req, res) => {
    try {
        const reuniaoIndex = reunioes.findIndex(r => r.id === parseInt(req.params.id));
        if (reuniaoIndex === -1) {
            return res.status(404).json({ error: 'Reunião não encontrada' });
        }

        reunioes.splice(reuniaoIndex, 1);
        res.json({ message: 'Reunião excluída com sucesso' });
    } catch (error) {
        console.error('Erro ao excluir reunião:', error);
        res.status(500).json({ error: 'Erro interno do servidor' });
    }
});

module.exports = router;