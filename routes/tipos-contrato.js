const express = require('express');
const router = express.Router();
const { body, validationResult } = require('express-validator');
const { TipoContrato } = require('../models');
const { Op } = require('sequelize');

// GET /api/tipos-contrato - Listar todos os tipos de contrato
router.get('/', async (req, res) => {
  try {
    const tipos = await TipoContrato.findAll({
      where: { ativo: true },
      order: [['nome', 'ASC']]
    });
    res.json(tipos);
  } catch (error) {
    console.error('Erro ao listar tipos de contrato:', error);
    res.status(500).json({ error: 'Erro interno do servidor' });
  }
});

// GET /api/tipos-contrato/:id - Buscar tipo de contrato por ID
router.get('/:id', async (req, res) => {
  try {
    const tipoId = parseInt(req.params.id);
    const tipo = await TipoContrato.findByPk(tipoId);
    
    if (!tipo) {
      return res.status(404).json({ error: 'Tipo de contrato não encontrado' });
    }
    
    res.json(tipo);
  } catch (error) {
    console.error('Erro ao buscar tipo de contrato:', error);
    res.status(500).json({ error: 'Erro interno do servidor' });
  }
});

// POST /api/tipos-contrato - Criar novo tipo de contrato
router.post('/', [
  body('nome').notEmpty().withMessage('Nome é obrigatório'),
  body('max_usuarios').isInt({ min: 1 }).withMessage('Máximo de usuários deve ser um número maior que 0'),
  body('dias_validade').isInt({ min: 1 }).withMessage('Dias de validade deve ser um número maior que 0'),
  body('preco_mensal').isFloat({ min: 0 }).withMessage('Preço mensal deve ser um número maior ou igual a 0')
], async (req, res) => {
  try {
    const errors = validationResult(req);
    if (!errors.isEmpty()) {
      return res.status(400).json({ errors: errors.array() });
    }

    const { nome, max_usuarios, dias_validade, preco_mensal, descricao } = req.body;
    
    // Verificar se nome já existe
    const tipoExistente = await TipoContrato.findOne({ where: { nome } });
    if (tipoExistente) {
      return res.status(400).json({ error: 'Nome do tipo de contrato já existe' });
    }

    const tipo = await TipoContrato.create({
      nome,
      max_usuarios,
      dias_validade,
      preco_mensal,
      descricao: descricao || null,
      ativo: true
    });

    res.status(201).json(tipo);
  } catch (error) {
    console.error('Erro ao criar tipo de contrato:', error);
    res.status(500).json({ error: 'Erro interno do servidor' });
  }
});

// PUT /api/tipos-contrato/:id - Atualizar tipo de contrato
router.put('/:id', [
  body('nome').optional().notEmpty().withMessage('Nome não pode ser vazio'),
  body('max_usuarios').optional().isInt({ min: 1 }).withMessage('Máximo de usuários deve ser um número maior que 0'),
  body('dias_validade').optional().isInt({ min: 1 }).withMessage('Dias de validade deve ser um número maior que 0'),
  body('preco_mensal').optional().isFloat({ min: 0 }).withMessage('Preço mensal deve ser um número maior ou igual a 0')
], async (req, res) => {
  try {
    const errors = validationResult(req);
    if (!errors.isEmpty()) {
      return res.status(400).json({ errors: errors.array() });
    }

    const tipoId = parseInt(req.params.id);
    
    // Verificar se o tipo existe
    const tipo = await TipoContrato.findByPk(tipoId);
    if (!tipo) {
      return res.status(404).json({ error: 'Tipo de contrato não encontrado' });
    }

    const { nome, max_usuarios, dias_validade, preco_mensal, descricao } = req.body;
    
    // Verificar se nome já existe (exceto para o próprio tipo)
    if (nome && nome !== tipo.nome) {
      const tipoExistente = await TipoContrato.findOne({ 
        where: { 
          nome: nome,
          id: { [Op.ne]: tipoId }
        } 
      });
      if (tipoExistente) {
        return res.status(400).json({ error: 'Nome do tipo de contrato já existe' });
      }
    }
    
    // Preparar dados para atualização
    const dadosAtualizacao = {
      ...(nome && { nome }),
      ...(max_usuarios !== undefined && { max_usuarios }),
      ...(dias_validade !== undefined && { dias_validade }),
      ...(preco_mensal !== undefined && { preco_mensal }),
      ...(descricao !== undefined && { descricao })
    };

    // Atualizar tipo de contrato no banco de dados
    await TipoContrato.update(dadosAtualizacao, {
      where: { id: tipoId }
    });

    // Buscar tipo atualizado
    const tipoAtualizado = await TipoContrato.findByPk(tipoId);
    res.json(tipoAtualizado);
  } catch (error) {
    console.error('Erro ao atualizar tipo de contrato:', error);
    res.status(500).json({ error: 'Erro interno do servidor' });
  }
});

// DELETE /api/tipos-contrato/:id - Excluir tipo de contrato (soft delete)
router.delete('/:id', async (req, res) => {
  try {
    const tipoId = parseInt(req.params.id);
    
    // Verificar se o tipo existe
    const tipo = await TipoContrato.findByPk(tipoId);
    if (!tipo) {
      return res.status(404).json({ error: 'Tipo de contrato não encontrado' });
    }

    // Soft delete - marcar como inativo
    await TipoContrato.update(
      { ativo: false },
      { where: { id: tipoId } }
    );

    res.json({ message: 'Tipo de contrato excluído com sucesso' });
  } catch (error) {
    console.error('Erro ao excluir tipo de contrato:', error);
    res.status(500).json({ error: 'Erro interno do servidor' });
  }
});

module.exports = router;