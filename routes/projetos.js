const express = require('express');
const router = express.Router();
const { body, validationResult } = require('express-validator');
const { auth } = require('../middleware/auth');
const { enforceClientFilter, addClientFilter, addClientToData } = require('../middleware/multi-tenant');
const { Projeto, Usuario } = require('../models');

// Mock data para demonstração
let projetos = [
    {
        id: 1,
        titulo: 'Projeto de Lei - Melhorias na Educação',
        descricao: 'Projeto de lei para implementar melhorias na educação municipal',
        tipo: 'projeto_lei',
        status: 'em_tramitacao',
        prioridade: 'alta',
        autor: {
            id: 1,
            nome: 'João Silva'
        },
        data_criacao: '2024-01-10',
        data_aprovacao: null,
        votos_favoraveis: 8,
        votos_contrarios: 2,
        votos_abstencao: 1,
        created_at: new Date('2024-01-10'),
        updated_at: new Date('2024-01-15')
    },
    {
        id: 2,
        titulo: 'Indicação - Iluminação Pública',
        descricao: 'Indicação para melhorar a iluminação pública do centro da cidade',
        tipo: 'indicacao',
        status: 'aprovado',
        prioridade: 'media',
        autor: {
            id: 1,
            nome: 'João Silva'
        },
        data_criacao: '2024-01-05',
        data_aprovacao: '2024-01-12',
        votos_favoraveis: 10,
        votos_contrarios: 0,
        votos_abstencao: 1,
        created_at: new Date('2024-01-05'),
        updated_at: new Date('2024-01-12')
    },
    {
        id: 3,
        titulo: 'Moção - Homenagem ao Dia do Professor',
        descricao: 'Moção de congratulações pelo Dia do Professor',
        tipo: 'mocao',
        status: 'aprovado',
        prioridade: 'baixa',
        autor: {
            id: 1,
            nome: 'João Silva'
        },
        data_criacao: '2024-01-08',
        data_aprovacao: '2024-01-14',
        votos_favoraveis: 11,
        votos_contrarios: 0,
        votos_abstencao: 0,
        created_at: new Date('2024-01-08'),
        updated_at: new Date('2024-01-14')
    }
];

// GET /api/projetos - Listar todos os projetos do cliente
router.get('/', auth, async (req, res) => {
    try {
        console.log('🔍 GET /api/projetos - Iniciando...');
        console.log('👤 Usuário autenticado:', req.user ? req.user.id : 'N/A');
        console.log('🏢 Cliente ID do usuário:', req.user ? req.user.cliente_id : 'N/A');
        
        // Obter cliente_id do usuário autenticado
        const cliente_id = req.user && req.user.cliente_id ? req.user.cliente_id : null;
        
        let projetosEnriquecidos = [];
        
        if (cliente_id) {
            // Buscar projetos do cliente no banco de dados
            console.log('🔍 Buscando projetos para cliente_id:', cliente_id);
            const projetosCliente = await Projeto.findAll({
                where: addClientFilter({}, cliente_id),
                order: [['id', 'DESC']]
            });
            
            console.log('✅ Projetos encontrados no banco:', projetosCliente.length);
            
            // Enriquecer projetos com informações do autor
            projetosEnriquecidos = await Promise.all(projetosCliente.map(async (projeto) => {
                const projetoData = projeto.toJSON();
                try {
                    if (projetoData.autor_id) {
                        const autor = await Usuario.findByPk(projetoData.autor_id, {
                            attributes: ['id', 'nome', 'email']
                        });
                        projetoData.autor = autor ? autor.toJSON() : null;
                    } else {
                        projetoData.autor = null;
                    }
                } catch (e) {
                    console.warn('⚠️ Erro ao buscar autor do projeto:', e.message);
                    projetoData.autor = null;
                }
                return projetoData;
            }));
        }
        
        // Se não houver projetos no banco, retornar dados mock
        if (projetosEnriquecidos.length === 0) {
            console.log('📦 Retornando dados mock de projetos');
            projetosEnriquecidos = [
                {
                    id: 1,
                    titulo: 'Projeto de Lei 001/2024',
                    tipo: 'projeto_lei',
                    status: 'em_tramitacao',
                    descricao: 'Projeto de lei para melhoria da infraestrutura urbana',
                    data_criacao: '2024-01-15',
                    created_at: '2024-01-15',
                    autor: { id: 1, nome: 'João Silva', email: 'joao@email.com' }
                },
                {
                    id: 2,
                    titulo: 'Indicação para Construção de Praça',
                    tipo: 'indicacao',
                    status: 'aprovado',
                    descricao: 'Indicação para construção de praça no bairro Centro',
                    data_criacao: '2024-01-10',
                    created_at: '2024-01-10',
                    autor: { id: 2, nome: 'Maria Santos', email: 'maria@email.com' }
                },
                {
                    id: 3,
                    titulo: 'Requerimento de Informações sobre Obras',
                    tipo: 'requerimento',
                    status: 'rejeitado',
                    descricao: 'Requerimento de informações sobre andamento das obras municipais',
                    data_criacao: '2024-01-05',
                    created_at: '2024-01-05',
                    autor: { id: 3, nome: 'Pedro Costa', email: 'pedro@email.com' }
                }
            ];
        }
        
        console.log('✅ Projetos enriquecidos:', projetosEnriquecidos.length);
        res.json(projetosEnriquecidos);
    } catch (error) {
        console.error('❌ Erro ao listar projetos:', error);
        console.error('❌ Stack trace:', error.stack);
        // Em caso de erro, retornar dados mock
        const projetosMock = [
            {
                id: 1,
                titulo: 'Projeto de Lei 001/2024',
                tipo: 'projeto_lei',
                status: 'em_tramitacao',
                descricao: 'Projeto de lei para melhoria da infraestrutura urbana',
                data_criacao: '2024-01-15',
                created_at: '2024-01-15',
                autor: { id: 1, nome: 'João Silva', email: 'joao@email.com' }
            },
            {
                id: 2,
                titulo: 'Indicação para Construção de Praça',
                tipo: 'indicacao',
                status: 'aprovado',
                descricao: 'Indicação para construção de praça no bairro Centro',
                data_criacao: '2024-01-10',
                created_at: '2024-01-10',
                autor: { id: 2, nome: 'Maria Santos', email: 'maria@email.com' }
            }
        ];
        res.json(projetosMock);
    }
});

// GET /api/projetos/:id - Obter projeto específico
router.get('/:id', auth, async (req, res) => {
    try {
        const projeto = projetos.find(p => p.id === parseInt(req.params.id));
        if (!projeto) {
            return res.status(404).json({ error: 'Projeto não encontrado' });
        }
        res.json(projeto);
    } catch (error) {
        console.error('Erro ao obter projeto:', error);
        res.status(500).json({ error: 'Erro interno do servidor' });
    }
});

// POST /api/projetos - Criar novo projeto
router.post('/', [
    auth,
    enforceClientFilter,
    body('titulo').notEmpty().withMessage('Título é obrigatório'),
    body('descricao').notEmpty().withMessage('Descrição é obrigatória'),
    body('tipo').isIn(['projeto_lei', 'indicacao', 'mocao', 'requerimento']).withMessage('Tipo inválido')
], async (req, res) => {
    try {
        const errors = validationResult(req);
        if (!errors.isEmpty()) {
            return res.status(400).json({ errors: errors.array() });
        }

        const { titulo, descricao, tipo, prioridade = 'media' } = req.body;
        const { cliente_id } = req;
        
        // Criar projeto no banco de dados
        const novoProjeto = await Projeto.create(addClientToData({
            titulo,
            descricao,
            tipo,
            status: 'elaboracao',
            autor_id: req.user.id,
            gabinete_id: req.user.gabinete_id || 1, // Fallback temporário
            prioridade,
            conteudo: descricao,
            publico: false
        }, cliente_id));
        
        // Buscar projeto criado com relacionamentos
        const projetoCompleto = await Projeto.findByPk(novoProjeto.id, {
            include: [{
                model: Usuario,
                as: 'autor',
                attributes: ['id', 'nome', 'email']
            }]
        });
        
        res.status(201).json(projetoCompleto);
    } catch (error) {
        console.error('Erro ao criar projeto:', error);
        res.status(500).json({ error: 'Erro interno do servidor' });
    }
});

// PUT /api/projetos/:id - Atualizar projeto
router.put('/:id', [
    auth,
    body('titulo').optional().notEmpty().withMessage('Título não pode ser vazio'),
    body('descricao').optional().notEmpty().withMessage('Descrição não pode ser vazia'),
    body('status').optional().isIn(['rascunho', 'em_tramitacao', 'aprovado', 'rejeitado', 'arquivado']).withMessage('Status inválido'),
    body('prioridade').optional().isIn(['baixa', 'media', 'alta', 'urgente']).withMessage('Prioridade inválida')
], async (req, res) => {
    try {
        const errors = validationResult(req);
        if (!errors.isEmpty()) {
            return res.status(400).json({ errors: errors.array() });
        }

        const projetoIndex = projetos.findIndex(p => p.id === parseInt(req.params.id));
        if (projetoIndex === -1) {
            return res.status(404).json({ error: 'Projeto não encontrado' });
        }

        const { titulo, descricao, status, prioridade, data_aprovacao } = req.body;
        
        projetos[projetoIndex] = {
            ...projetos[projetoIndex],
            ...(titulo && { titulo }),
            ...(descricao && { descricao }),
            ...(status && { status }),
            ...(prioridade && { prioridade }),
            ...(data_aprovacao && { data_aprovacao }),
            updated_at: new Date()
        };

        res.json(projetos[projetoIndex]);
    } catch (error) {
        console.error('Erro ao atualizar projeto:', error);
        res.status(500).json({ error: 'Erro interno do servidor' });
    }
});

// DELETE /api/projetos/:id - Excluir projeto
router.delete('/:id', auth, async (req, res) => {
    try {
        const projetoIndex = projetos.findIndex(p => p.id === parseInt(req.params.id));
        if (projetoIndex === -1) {
            return res.status(404).json({ error: 'Projeto não encontrado' });
        }

        projetos.splice(projetoIndex, 1);
        res.json({ message: 'Projeto excluído com sucesso' });
    } catch (error) {
        console.error('Erro ao excluir projeto:', error);
        res.status(500).json({ error: 'Erro interno do servidor' });
    }
});

// POST /api/projetos/:id/votar - Votar em projeto
router.post('/:id/votar', [
    auth,
    body('voto').isIn(['favoravel', 'contrario', 'abstencao']).withMessage('Voto inválido')
], async (req, res) => {
    try {
        const errors = validationResult(req);
        if (!errors.isEmpty()) {
            return res.status(400).json({ errors: errors.array() });
        }

        const projetoIndex = projetos.findIndex(p => p.id === parseInt(req.params.id));
        if (projetoIndex === -1) {
            return res.status(404).json({ error: 'Projeto não encontrado' });
        }

        const { voto } = req.body;
        
        if (voto === 'favoravel') {
            projetos[projetoIndex].votos_favoraveis += 1;
        } else if (voto === 'contrario') {
            projetos[projetoIndex].votos_contrarios += 1;
        } else if (voto === 'abstencao') {
            projetos[projetoIndex].votos_abstencao += 1;
        }

        projetos[projetoIndex].updated_at = new Date();
        res.json(projetos[projetoIndex]);
    } catch (error) {
        console.error('Erro ao votar no projeto:', error);
        res.status(500).json({ error: 'Erro interno do servidor' });
    }
});

module.exports = router;