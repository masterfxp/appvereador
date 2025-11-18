const express = require('express');
const router = express.Router();
const { auth } = require('../middleware/auth');
const NotificacaoService = require('../services/NotificacaoService');
const { Op } = require('sequelize');

// GET /api/notificacoes - Listar notificações do usuário
router.get('/', auth, async (req, res) => {
    try {
        const { lida, tipo, prioridade, limit = 50 } = req.query;
        
        const filtros = {};
        if (lida !== undefined) filtros.lida = lida === 'true';
        if (tipo) filtros.tipo = tipo;
        if (prioridade) filtros.prioridade = prioridade;
        if (limit) filtros.limit = parseInt(limit);

        const notificacoes = await NotificacaoService.buscarNotificacoes(req.user.id, filtros);
        
        res.json(notificacoes);
    } catch (error) {
        console.error('Erro ao buscar notificações:', error);
        res.status(500).json({ error: 'Erro interno do servidor' });
    }
});

// GET /api/notificacoes/contar - Contar notificações não lidas
router.get('/contar', auth, async (req, res) => {
    try {
        const count = await NotificacaoService.contarNaoLidas(req.user.id);
        res.json({ count });
    } catch (error) {
        console.error('Erro ao contar notificações:', error);
        res.status(500).json({ error: 'Erro interno do servidor' });
    }
});

// PUT /api/notificacoes/:id/lida - Marcar notificação como lida
router.put('/:id/lida', auth, async (req, res) => {
    try {
        const { id } = req.params;
        await NotificacaoService.marcarComoLida(id, req.user.id);
        res.json({ message: 'Notificação marcada como lida' });
    } catch (error) {
        console.error('Erro ao marcar notificação como lida:', error);
        res.status(500).json({ error: 'Erro interno do servidor' });
    }
});

// PUT /api/notificacoes/marcar-todas - Marcar todas as notificações como lidas
router.put('/marcar-todas', auth, async (req, res) => {
    try {
        await NotificacaoService.marcarTodasComoLidas(req.user.id);
        res.json({ message: 'Todas as notificações foram marcadas como lidas' });
    } catch (error) {
        console.error('Erro ao marcar todas as notificações como lidas:', error);
        res.status(500).json({ error: 'Erro interno do servidor' });
    }
});

// POST /api/notificacoes/gerar-inteligentes - Gerar notificações inteligentes
router.post('/gerar-inteligentes', auth, async (req, res) => {
    try {
        const count = await NotificacaoService.gerarNotificacoesInteligentes(req.user.id);
        res.json({ 
            message: 'Notificações inteligentes geradas com sucesso',
            count 
        });
    } catch (error) {
        console.error('Erro ao gerar notificações inteligentes:', error);
        res.status(500).json({ error: 'Erro interno do servidor' });
    }
});

// DELETE /api/notificacoes/:id - Excluir notificação
router.delete('/:id', auth, async (req, res) => {
    try {
        const { id } = req.params;
        
        // Verificar se a notificação pertence ao usuário
        const notificacao = await NotificacaoService.buscarNotificacoes(req.user.id, { id });
        if (notificacao.length === 0) {
            return res.status(404).json({ error: 'Notificação não encontrada' });
        }

        await NotificacaoService.excluirNotificacao(id, req.user.id);
        res.json({ message: 'Notificação excluída com sucesso' });
    } catch (error) {
        console.error('Erro ao excluir notificação:', error);
        res.status(500).json({ error: 'Erro interno do servidor' });
    }
});

// POST /api/notificacoes/limpar-antigas - Limpar notificações antigas (admin)
router.post('/limpar-antigas', auth, async (req, res) => {
    try {
        // Verificar se é administrador
        if (req.user.nivel !== 'administrador') {
            return res.status(403).json({ error: 'Acesso negado' });
        }

        await NotificacaoService.limparNotificacoesAntigas();
        res.json({ message: 'Notificações antigas foram limpas' });
    } catch (error) {
        console.error('Erro ao limpar notificações antigas:', error);
        res.status(500).json({ error: 'Erro interno do servidor' });
    }
});

module.exports = router;


