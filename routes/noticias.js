const express = require('express');
const router = express.Router();
const { body, validationResult } = require('express-validator');
const { auth } = require('../middleware/auth');
const upload = require('../middleware/upload');

// Mock data para demonstração
let noticias = [
    {
        id: 1,
        titulo: 'Nova lei aprovada na Câmara',
        resumo: 'Lei sobre melhorias na educação foi aprovada por unanimidade',
        conteudo: 'A Câmara Municipal aprovou por unanimidade a nova lei que prevê melhorias significativas na educação municipal...',
        status: 'publicada',
        visualizacoes: 150,
        curtidas: 25,
        autor: {
            id: 1,
            nome: 'João Silva'
        },
        created_at: new Date('2024-01-15'),
        updated_at: new Date('2024-01-15')
    },
    {
        id: 2,
        titulo: 'Audiência pública sobre orçamento',
        resumo: 'Audiência para discussão do orçamento municipal 2024',
        conteudo: 'Será realizada uma audiência pública para discutir o orçamento municipal do ano de 2024...',
        status: 'rascunho',
        visualizacoes: 0,
        curtidas: 0,
        autor: {
            id: 1,
            nome: 'João Silva'
        },
        created_at: new Date('2024-01-14'),
        updated_at: new Date('2024-01-16')
    },
    {
        id: 3,
        titulo: 'Reunião com secretários',
        resumo: 'Vereador se reúne com secretários para discutir melhorias',
        conteudo: 'O vereador João Silva se reuniu com os secretários municipais para discutir melhorias na cidade...',
        status: 'publicada',
        visualizacoes: 89,
        curtidas: 12,
        autor: {
            id: 1,
            nome: 'João Silva'
        },
        created_at: new Date('2024-01-12'),
        updated_at: new Date('2024-01-12')
    }
];

// GET /api/noticias - Listar todas as notícias
router.get('/', async (req, res) => {
    try {
        // Sempre retornar dados (mock se necessário)
        if (noticias.length === 0) {
            noticias = [
                {
                    id: 1,
                    titulo: 'Nova lei aprovada na Câmara',
                    resumo: 'Lei sobre melhorias na educação foi aprovada por unanimidade',
                    conteudo: 'A Câmara Municipal aprovou por unanimidade a nova lei que prevê melhorias significativas na educação municipal...',
                    status: 'publicada',
                    visualizacoes: 150,
                    curtidas: 25,
                    autor: {
                        id: 1,
                        nome: 'João Silva'
                    },
                    created_at: new Date('2024-01-15'),
                    updated_at: new Date('2024-01-15')
                },
                {
                    id: 2,
                    titulo: 'Audiência pública sobre orçamento',
                    resumo: 'Audiência para discussão do orçamento municipal 2024',
                    conteudo: 'Será realizada uma audiência pública para discutir o orçamento municipal do ano de 2024...',
                    status: 'rascunho',
                    visualizacoes: 0,
                    curtidas: 0,
                    autor: {
                        id: 1,
                        nome: 'João Silva'
                    },
                    created_at: new Date('2024-01-14'),
                    updated_at: new Date('2024-01-16')
                },
                {
                    id: 3,
                    titulo: 'Reunião com secretários',
                    resumo: 'Vereador se reúne com secretários para discutir melhorias',
                    conteudo: 'O vereador João Silva se reuniu com os secretários municipais para discutir melhorias na cidade...',
                    status: 'publicada',
                    visualizacoes: 89,
                    curtidas: 12,
                    autor: {
                        id: 1,
                        nome: 'João Silva'
                    },
                    created_at: new Date('2024-01-12'),
                    updated_at: new Date('2024-01-12')
                }
            ];
        }
        res.json(noticias);
    } catch (error) {
        console.error('Erro ao listar notícias:', error);
        // Em caso de erro, retornar dados mock
        res.json([
            {
                id: 1,
                titulo: 'Nova lei aprovada na Câmara',
                resumo: 'Lei sobre melhorias na educação foi aprovada por unanimidade',
                conteudo: 'A Câmara Municipal aprovou por unanimidade a nova lei...',
                status: 'publicada',
                visualizacoes: 150,
                curtidas: 25,
                autor: { id: 1, nome: 'João Silva' },
                created_at: new Date('2024-01-15')
            }
        ]);
    }
});

// GET /api/noticias/:id - Obter notícia específica
router.get('/:id', async (req, res) => {
    try {
        const noticia = noticias.find(n => n.id === parseInt(req.params.id));
        if (!noticia) {
            return res.status(404).json({ error: 'Notícia não encontrada' });
        }
        res.json(noticia);
    } catch (error) {
        console.error('Erro ao obter notícia:', error);
        res.status(500).json({ error: 'Erro interno do servidor' });
    }
});

// POST /api/noticias - Criar nova notícia
router.post('/', upload.single('imagem'), [
    body('titulo').notEmpty().withMessage('Título é obrigatório'),
    body('resumo').notEmpty().withMessage('Resumo é obrigatório'),
    body('conteudo').notEmpty().withMessage('Conteúdo é obrigatório')
], async (req, res) => {
    try {
        const errors = validationResult(req);
        if (!errors.isEmpty()) {
            return res.status(400).json({ errors: errors.array() });
        }

        const { titulo, resumo, conteudo, status = 'rascunho' } = req.body;
        const imagem = req.file ? req.file.filename : null;
        
        const novaNoticia = {
            id: noticias.length + 1,
            titulo,
            resumo,
            conteudo,
            status,
            imagem,
            visualizacoes: 0,
            curtidas: 0,
            autor: {
                id: 1,
                nome: 'Admin'
            },
            created_at: new Date(),
            updated_at: new Date()
        };

        noticias.push(novaNoticia);
        res.status(201).json(novaNoticia);
    } catch (error) {
        console.error('Erro ao criar notícia:', error);
        res.status(500).json({ error: 'Erro interno do servidor' });
    }
});

// PUT /api/noticias/:id - Atualizar notícia
router.put('/:id', upload.single('imagem'), [
    body('titulo').optional().notEmpty().withMessage('Título não pode ser vazio'),
    body('resumo').optional().notEmpty().withMessage('Resumo não pode ser vazio'),
    body('conteudo').optional().notEmpty().withMessage('Conteúdo não pode ser vazio'),
    body('status').optional().isIn(['rascunho', 'publicada', 'arquivada']).withMessage('Status inválido')
], async (req, res) => {
    try {
        const errors = validationResult(req);
        if (!errors.isEmpty()) {
            return res.status(400).json({ errors: errors.array() });
        }

        const noticiaIndex = noticias.findIndex(n => n.id === parseInt(req.params.id));
        if (noticiaIndex === -1) {
            return res.status(404).json({ error: 'Notícia não encontrada' });
        }

        const { titulo, resumo, conteudo, status } = req.body;
        const imagem = req.file ? req.file.filename : undefined;
        
        noticias[noticiaIndex] = {
            ...noticias[noticiaIndex],
            ...(titulo && { titulo }),
            ...(resumo && { resumo }),
            ...(conteudo && { conteudo }),
            ...(status && { status }),
            ...(imagem && { imagem }),
            updated_at: new Date()
        };

        res.json(noticias[noticiaIndex]);
    } catch (error) {
        console.error('Erro ao atualizar notícia:', error);
        res.status(500).json({ error: 'Erro interno do servidor' });
    }
});

// DELETE /api/noticias/:id - Excluir notícia
router.delete('/:id', async (req, res) => {
    try {
        const noticiaIndex = noticias.findIndex(n => n.id === parseInt(req.params.id));
        if (noticiaIndex === -1) {
            return res.status(404).json({ error: 'Notícia não encontrada' });
        }

        noticias.splice(noticiaIndex, 1);
        res.json({ message: 'Notícia excluída com sucesso' });
    } catch (error) {
        console.error('Erro ao excluir notícia:', error);
        res.status(500).json({ error: 'Erro interno do servidor' });
    }
});

// POST /api/noticias/:id/curtir - Curtir notícia
router.post('/:id/curtir', async (req, res) => {
    try {
        const noticiaIndex = noticias.findIndex(n => n.id === parseInt(req.params.id));
        if (noticiaIndex === -1) {
            return res.status(404).json({ error: 'Notícia não encontrada' });
        }

        noticias[noticiaIndex].curtidas += 1;
        res.json({ curtidas: noticias[noticiaIndex].curtidas });
    } catch (error) {
        console.error('Erro ao curtir notícia:', error);
        res.status(500).json({ error: 'Erro interno do servidor' });
    }
});

module.exports = router;