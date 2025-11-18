const express = require('express');
const router = express.Router();
const { body, validationResult } = require('express-validator');
const { auth } = require('../middleware/auth');

// Mock data para demonstração
let indicadores = [
    {
        id: 1,
        nome: 'Taxa de Aprovação de Projetos',
        descricao: 'Percentual de projetos aprovados em relação ao total apresentados',
        categoria: 'legislativo',
        tipo: 'percentual',
        valor_atual: 75.5,
        valor_meta: 80.0,
        valor_anterior: 72.3,
        unidade: '%',
        periodo: 'mensal',
        data_atualizacao: '2024-01-15',
        tendencia: 'crescimento',
        cor: 'green',
        created_at: new Date('2024-01-01'),
        updated_at: new Date('2024-01-15')
    },
    {
        id: 2,
        nome: 'Tempo Médio de Tramitação',
        descricao: 'Tempo médio em dias para tramitação de projetos',
        categoria: 'legislativo',
        tipo: 'numerico',
        valor_atual: 45.2,
        valor_meta: 30.0,
        valor_anterior: 52.1,
        unidade: 'dias',
        periodo: 'mensal',
        data_atualizacao: '2024-01-15',
        tendencia: 'melhoria',
        cor: 'blue',
        created_at: new Date('2024-01-01'),
        updated_at: new Date('2024-01-15')
    },
    {
        id: 3,
        nome: 'Satisfação dos Cidadãos',
        descricao: 'Índice de satisfação com os serviços públicos',
        categoria: 'social',
        tipo: 'percentual',
        valor_atual: 8.2,
        valor_meta: 9.0,
        valor_anterior: 7.8,
        unidade: '/10',
        periodo: 'trimestral',
        data_atualizacao: '2024-01-10',
        tendencia: 'crescimento',
        cor: 'green',
        created_at: new Date('2024-01-01'),
        updated_at: new Date('2024-01-10')
    },
    {
        id: 4,
        nome: 'Eficiência Orçamentária',
        descricao: 'Percentual de execução do orçamento municipal',
        categoria: 'financeiro',
        tipo: 'percentual',
        valor_atual: 68.5,
        valor_meta: 85.0,
        valor_anterior: 65.2,
        unidade: '%',
        periodo: 'mensal',
        data_atualizacao: '2024-01-15',
        tendencia: 'crescimento',
        cor: 'yellow',
        created_at: new Date('2024-01-01'),
        updated_at: new Date('2024-01-15')
    },
    {
        id: 5,
        nome: 'Participação em Reuniões',
        descricao: 'Percentual de presença nas reuniões da câmara',
        categoria: 'legislativo',
        tipo: 'percentual',
        valor_atual: 92.3,
        valor_meta: 95.0,
        valor_anterior: 89.7,
        unidade: '%',
        periodo: 'mensal',
        data_atualizacao: '2024-01-15',
        tendencia: 'crescimento',
        cor: 'green',
        created_at: new Date('2024-01-01'),
        updated_at: new Date('2024-01-15')
    },
    {
        id: 6,
        nome: 'Tempo de Resposta a Demandas',
        descricao: 'Tempo médio para resposta a demandas dos cidadãos',
        categoria: 'atendimento',
        tipo: 'numerico',
        valor_atual: 3.2,
        valor_meta: 2.0,
        valor_anterior: 4.1,
        unidade: 'dias',
        periodo: 'semanal',
        data_atualizacao: '2024-01-14',
        tendencia: 'melhoria',
        cor: 'blue',
        created_at: new Date('2024-01-01'),
        updated_at: new Date('2024-01-14')
    }
];

// GET /api/indicadores - Listar todos os indicadores
router.get('/', auth, async (req, res) => {
    try {
        const { categoria, periodo, tipo } = req.query;
        
        let filteredIndicadores = [...indicadores];
        
        if (categoria) {
            filteredIndicadores = filteredIndicadores.filter(i => i.categoria === categoria);
        }
        
        if (periodo) {
            filteredIndicadores = filteredIndicadores.filter(i => i.periodo === periodo);
        }
        
        if (tipo) {
            filteredIndicadores = filteredIndicadores.filter(i => i.tipo === tipo);
        }
        
        res.json(filteredIndicadores);
    } catch (error) {
        console.error('Erro ao listar indicadores:', error);
        res.status(500).json({ error: 'Erro interno do servidor' });
    }
});

// GET /api/indicadores/:id - Obter indicador específico
router.get('/:id', auth, async (req, res) => {
    try {
        const indicador = indicadores.find(i => i.id === parseInt(req.params.id));
        if (!indicador) {
            return res.status(404).json({ error: 'Indicador não encontrado' });
        }
        res.json(indicador);
    } catch (error) {
        console.error('Erro ao obter indicador:', error);
        res.status(500).json({ error: 'Erro interno do servidor' });
    }
});

// POST /api/indicadores - Criar novo indicador
router.post('/', [
    auth,
    body('nome').notEmpty().withMessage('Nome é obrigatório'),
    body('descricao').notEmpty().withMessage('Descrição é obrigatória'),
    body('categoria').isIn(['legislativo', 'social', 'financeiro', 'atendimento', 'infraestrutura']).withMessage('Categoria inválida'),
    body('tipo').isIn(['percentual', 'numerico', 'monetario', 'tempo']).withMessage('Tipo inválido'),
    body('valor_atual').isNumeric().withMessage('Valor atual deve ser numérico'),
    body('valor_meta').isNumeric().withMessage('Valor meta deve ser numérico')
], async (req, res) => {
    try {
        const errors = validationResult(req);
        if (!errors.isEmpty()) {
            return res.status(400).json({ errors: errors.array() });
        }

        const { 
            nome, 
            descricao, 
            categoria, 
            tipo, 
            valor_atual, 
            valor_meta, 
            valor_anterior = valor_atual,
            unidade = '%',
            periodo = 'mensal'
        } = req.body;
        
        const novoIndicador = {
            id: indicadores.length + 1,
            nome,
            descricao,
            categoria,
            tipo,
            valor_atual: parseFloat(valor_atual),
            valor_meta: parseFloat(valor_meta),
            valor_anterior: parseFloat(valor_anterior),
            unidade,
            periodo,
            data_atualizacao: new Date().toISOString().split('T')[0],
            tendencia: valor_atual > valor_anterior ? 'crescimento' : valor_atual < valor_anterior ? 'queda' : 'estavel',
            cor: valor_atual >= valor_meta ? 'green' : valor_atual >= valor_meta * 0.8 ? 'yellow' : 'red',
            created_at: new Date(),
            updated_at: new Date()
        };

        indicadores.push(novoIndicador);
        res.status(201).json(novoIndicador);
    } catch (error) {
        console.error('Erro ao criar indicador:', error);
        res.status(500).json({ error: 'Erro interno do servidor' });
    }
});

// PUT /api/indicadores/:id - Atualizar indicador
router.put('/:id', [
    auth,
    body('nome').optional().notEmpty().withMessage('Nome não pode ser vazio'),
    body('descricao').optional().notEmpty().withMessage('Descrição não pode ser vazia'),
    body('valor_atual').optional().isNumeric().withMessage('Valor atual deve ser numérico'),
    body('valor_meta').optional().isNumeric().withMessage('Valor meta deve ser numérico')
], async (req, res) => {
    try {
        const errors = validationResult(req);
        if (!errors.isEmpty()) {
            return res.status(400).json({ errors: errors.array() });
        }

        const indicadorIndex = indicadores.findIndex(i => i.id === parseInt(req.params.id));
        if (indicadorIndex === -1) {
            return res.status(404).json({ error: 'Indicador não encontrado' });
        }

        const { 
            nome, 
            descricao, 
            valor_atual, 
            valor_meta, 
            valor_anterior,
            unidade,
            periodo 
        } = req.body;
        
        const indicadorAtual = indicadores[indicadorIndex];
        const novoValorAtual = valor_atual !== undefined ? parseFloat(valor_atual) : indicadorAtual.valor_atual;
        const novoValorAnterior = valor_anterior !== undefined ? parseFloat(valor_anterior) : indicadorAtual.valor_anterior;
        
        indicadores[indicadorIndex] = {
            ...indicadorAtual,
            ...(nome && { nome }),
            ...(descricao && { descricao }),
            ...(valor_atual !== undefined && { valor_atual: novoValorAtual }),
            ...(valor_meta !== undefined && { valor_meta: parseFloat(valor_meta) }),
            ...(valor_anterior !== undefined && { valor_anterior: novoValorAnterior }),
            ...(unidade && { unidade }),
            ...(periodo && { periodo }),
            tendencia: novoValorAtual > novoValorAnterior ? 'crescimento' : novoValorAtual < novoValorAnterior ? 'queda' : 'estavel',
            cor: novoValorAtual >= (valor_meta || indicadorAtual.valor_meta) ? 'green' : novoValorAtual >= (valor_meta || indicadorAtual.valor_meta) * 0.8 ? 'yellow' : 'red',
            data_atualizacao: new Date().toISOString().split('T')[0],
            updated_at: new Date()
        };

        res.json(indicadores[indicadorIndex]);
    } catch (error) {
        console.error('Erro ao atualizar indicador:', error);
        res.status(500).json({ error: 'Erro interno do servidor' });
    }
});

// DELETE /api/indicadores/:id - Excluir indicador
router.delete('/:id', auth, async (req, res) => {
    try {
        const indicadorIndex = indicadores.findIndex(i => i.id === parseInt(req.params.id));
        if (indicadorIndex === -1) {
            return res.status(404).json({ error: 'Indicador não encontrado' });
        }

        indicadores.splice(indicadorIndex, 1);
        res.json({ message: 'Indicador excluído com sucesso' });
    } catch (error) {
        console.error('Erro ao excluir indicador:', error);
        res.status(500).json({ error: 'Erro interno do servidor' });
    }
});

// GET /api/indicadores/dashboard/resumo - Resumo para dashboard
router.get('/dashboard/resumo', auth, async (req, res) => {
    try {
        const totalIndicadores = indicadores.length;
        const indicadoresVerdes = indicadores.filter(i => i.cor === 'green').length;
        const indicadoresAmarelos = indicadores.filter(i => i.cor === 'yellow').length;
        const indicadoresVermelhos = indicadores.filter(i => i.cor === 'red').length;
        
        const mediaGeral = indicadores.reduce((acc, i) => acc + i.valor_atual, 0) / totalIndicadores;
        const indicadoresMeta = indicadores.filter(i => i.valor_atual >= i.valor_meta).length;
        
        res.json({
            total: totalIndicadores,
            verdes: indicadoresVerdes,
            amarelos: indicadoresAmarelos,
            vermelhos: indicadoresVermelhos,
            media_geral: parseFloat(mediaGeral.toFixed(2)),
            meta_atingida: indicadoresMeta,
            percentual_meta: parseFloat(((indicadoresMeta / totalIndicadores) * 100).toFixed(1))
        });
    } catch (error) {
        console.error('Erro ao obter resumo dos indicadores:', error);
        res.status(500).json({ error: 'Erro interno do servidor' });
    }
});

module.exports = router;