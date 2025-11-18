const express = require('express');
const router = express.Router();
const { body, validationResult } = require('express-validator');
const { auth } = require('../middleware/auth');

// Mock data para demonstração
let tarefas = [
    {
        id: 1,
        titulo: 'Revisar projeto de lei',
        descricao: 'Revisar o projeto de lei sobre melhorias na educação',
        responsavel: {
            id: 1,
            nome: 'João Silva'
        },
        prioridade: 'alta',
        status: 'em_andamento',
        prazo: '2024-01-25',
        created_at: new Date('2024-01-15'),
        updated_at: new Date('2024-01-16')
    },
    {
        id: 2,
        titulo: 'Preparar relatório mensal',
        descricao: 'Elaborar relatório de atividades do mês',
        responsavel: {
            id: 2,
            nome: 'Maria Santos'
        },
        prioridade: 'media',
        status: 'pendente',
        prazo: '2024-01-30',
        created_at: new Date('2024-01-14'),
        updated_at: new Date('2024-01-14')
    },
    {
        id: 3,
        titulo: 'Reunião com secretário',
        descricao: 'Agendar reunião com secretário de obras',
        responsavel: {
            id: 1,
            nome: 'João Silva'
        },
        prioridade: 'baixa',
        status: 'concluida',
        prazo: '2024-01-20',
        created_at: new Date('2024-01-10'),
        updated_at: new Date('2024-01-18')
    }
];

// GET /api/tarefas - Listar todas as tarefas
router.get('/', auth, async (req, res) => {
    try {
        // Sempre retornar dados (mock se necessário)
        if (tarefas.length === 0) {
            const hoje = new Date();
            const amanha = new Date(hoje);
            amanha.setDate(amanha.getDate() + 1);
            
            tarefas = [
                {
                    id: 1,
                    titulo: 'Revisar projeto de lei',
                    descricao: 'Revisar o projeto de lei sobre melhorias na educação',
                    responsavel: {
                        id: 1,
                        nome: 'João Silva'
                    },
                    prioridade: 'alta',
                    status: 'em_andamento',
                    prazo: amanha.toISOString().split('T')[0],
                    created_at: new Date('2024-01-15'),
                    updated_at: new Date('2024-01-16')
                },
                {
                    id: 2,
                    titulo: 'Preparar relatório mensal',
                    descricao: 'Elaborar relatório de atividades do mês',
                    responsavel: {
                        id: 2,
                        nome: 'Maria Santos'
                    },
                    prioridade: 'media',
                    status: 'pendente',
                    prazo: amanha.toISOString().split('T')[0],
                    created_at: new Date('2024-01-14'),
                    updated_at: new Date('2024-01-14')
                },
                {
                    id: 3,
                    titulo: 'Reunião com secretário',
                    descricao: 'Agendar reunião com secretário de obras',
                    responsavel: {
                        id: 1,
                        nome: 'João Silva'
                    },
                    prioridade: 'baixa',
                    status: 'concluida',
                    prazo: hoje.toISOString().split('T')[0],
                    created_at: new Date('2024-01-10'),
                    updated_at: new Date('2024-01-18')
                }
            ];
        }
        res.json(tarefas);
    } catch (error) {
        console.error('Erro ao listar tarefas:', error);
        // Em caso de erro, retornar dados mock
        const hoje = new Date();
        const amanha = new Date(hoje);
        amanha.setDate(amanha.getDate() + 1);
        res.json([
            {
                id: 1,
                titulo: 'Revisar projeto de lei',
                descricao: 'Revisar o projeto de lei sobre melhorias na educação',
                responsavel: { id: 1, nome: 'João Silva' },
                prioridade: 'alta',
                status: 'em_andamento',
                prazo: amanha.toISOString().split('T')[0],
                created_at: new Date('2024-01-15')
            }
        ]);
    }
});

// GET /api/tarefas/:id - Obter tarefa específica
router.get('/:id', auth, async (req, res) => {
    try {
        const tarefa = tarefas.find(t => t.id === parseInt(req.params.id));
        if (!tarefa) {
            return res.status(404).json({ error: 'Tarefa não encontrada' });
        }
        res.json(tarefa);
    } catch (error) {
        console.error('Erro ao obter tarefa:', error);
        res.status(500).json({ error: 'Erro interno do servidor' });
    }
});

// POST /api/tarefas - Criar nova tarefa
router.post('/', [
    auth,
    body('titulo').notEmpty().withMessage('Título é obrigatório'),
    body('descricao').notEmpty().withMessage('Descrição é obrigatória'),
    body('responsavel_id').isInt().withMessage('Responsável é obrigatório'),
    body('prazo').isISO8601().withMessage('Prazo é obrigatório')
], async (req, res) => {
    try {
        const errors = validationResult(req);
        if (!errors.isEmpty()) {
            return res.status(400).json({ errors: errors.array() });
        }

        const { titulo, descricao, responsavel_id, prioridade = 'media', prazo } = req.body;
        
        const novaTarefa = {
            id: tarefas.length + 1,
            titulo,
            descricao,
            responsavel: {
                id: responsavel_id,
                nome: 'Usuário ' + responsavel_id
            },
            prioridade,
            status: 'pendente',
            prazo,
            created_at: new Date(),
            updated_at: new Date()
        };

        tarefas.push(novaTarefa);
        res.status(201).json(novaTarefa);
    } catch (error) {
        console.error('Erro ao criar tarefa:', error);
        res.status(500).json({ error: 'Erro interno do servidor' });
    }
});

// PUT /api/tarefas/:id - Atualizar tarefa
router.put('/:id', [
    auth,
    body('titulo').optional().notEmpty().withMessage('Título não pode ser vazio'),
    body('descricao').optional().notEmpty().withMessage('Descrição não pode ser vazia'),
    body('status').optional().isIn(['pendente', 'em_andamento', 'concluida', 'atrasada']).withMessage('Status inválido'),
    body('prioridade').optional().isIn(['baixa', 'media', 'alta', 'urgente']).withMessage('Prioridade inválida')
], async (req, res) => {
    try {
        const errors = validationResult(req);
        if (!errors.isEmpty()) {
            return res.status(400).json({ errors: errors.array() });
        }

        const tarefaIndex = tarefas.findIndex(t => t.id === parseInt(req.params.id));
        if (tarefaIndex === -1) {
            return res.status(404).json({ error: 'Tarefa não encontrada' });
        }

        const { titulo, descricao, status, prioridade, prazo, responsavel_id } = req.body;
        
        tarefas[tarefaIndex] = {
            ...tarefas[tarefaIndex],
            ...(titulo && { titulo }),
            ...(descricao && { descricao }),
            ...(status && { status }),
            ...(prioridade && { prioridade }),
            ...(prazo && { prazo }),
            ...(responsavel_id && { 
                responsavel: {
                    id: responsavel_id,
                    nome: 'Usuário ' + responsavel_id
                }
            }),
            updated_at: new Date()
        };

        res.json(tarefas[tarefaIndex]);
    } catch (error) {
        console.error('Erro ao atualizar tarefa:', error);
        res.status(500).json({ error: 'Erro interno do servidor' });
    }
});

// DELETE /api/tarefas/:id - Excluir tarefa
router.delete('/:id', auth, async (req, res) => {
    try {
        const tarefaIndex = tarefas.findIndex(t => t.id === parseInt(req.params.id));
        if (tarefaIndex === -1) {
            return res.status(404).json({ error: 'Tarefa não encontrada' });
        }

        tarefas.splice(tarefaIndex, 1);
        res.json({ message: 'Tarefa excluída com sucesso' });
    } catch (error) {
        console.error('Erro ao excluir tarefa:', error);
        res.status(500).json({ error: 'Erro interno do servidor' });
    }
});

module.exports = router;