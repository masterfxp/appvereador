const express = require('express');
const router = express.Router();
const { Cliente } = require('../models');
const { auth } = require('../middleware/auth');

// GET /api/clientes - Listar todos os clientes
router.get('/', async (req, res) => {
  try {
    const clientes = await Cliente.findAll({
      order: [['nome', 'ASC']]
    });
    res.json(clientes);
  } catch (error) {
    console.error('Erro ao listar clientes:', error);
    res.status(500).json({ error: 'Erro interno do servidor' });
  }
});

// GET /api/clientes/:id - Buscar cliente por ID
router.get('/:id', auth, async (req, res) => {
  try {
    const cliente = await Cliente.findByPk(req.params.id);
    if (!cliente) {
      return res.status(404).json({ error: 'Cliente não encontrado' });
    }
    res.json(cliente);
  } catch (error) {
    console.error('Erro ao buscar cliente:', error);
    res.status(500).json({ error: 'Erro interno do servidor' });
  }
});

// POST /api/clientes - Criar novo cliente
router.post('/', async (req, res) => {
  try {
    const { 
      nome, 
      email, 
      telefone, 
      empresa,
      tipo_contrato,
      data_inicio_contrato,
      data_fim_contrato,
      limite_usuarios,
      limite_entidades,
      limite_projetos,
      limite_espaco_mb,
      ativo 
    } = req.body;
    
    // Verificar se email já existe
    const clienteExistente = await Cliente.findOne({ where: { email } });
    if (clienteExistente) {
      return res.status(400).json({ error: 'Email já cadastrado' });
    }

    // Se não informou data de início, usar data atual
    const dataInicio = data_inicio_contrato ? new Date(data_inicio_contrato) : new Date();
    
    // Se não informou data de fim, calcular 1 ano a partir da data de início
    let dataFim = null;
    if (data_fim_contrato) {
      dataFim = new Date(data_fim_contrato);
    } else {
      dataFim = new Date(dataInicio);
      dataFim.setFullYear(dataFim.getFullYear() + 1);
    }

    const cliente = await Cliente.create({
      nome,
      email,
      telefone,
      empresa,
      tipo_contrato,
      data_inicio_contrato: dataInicio,
      data_fim_contrato: dataFim,
      limite_usuarios: limite_usuarios || 10,
      limite_entidades: limite_entidades || 5,
      limite_projetos: limite_projetos || 50,
      limite_espaco_mb: limite_espaco_mb || 1000,
      ativo: ativo !== undefined ? ativo : true,
      tipo: 'gabinete' // Padrão para gabinete
    });

    res.status(201).json(cliente);
  } catch (error) {
    console.error('Erro ao criar cliente:', error);
    res.status(500).json({ error: 'Erro interno do servidor' });
  }
});

// PUT /api/clientes/:id - Atualizar cliente
router.put('/:id', auth, async (req, res) => {
  try {
    const cliente = await Cliente.findByPk(req.params.id);
    if (!cliente) {
      return res.status(404).json({ error: 'Cliente não encontrado' });
    }

    const { nome, email, telefone, endereco, cidade, estado, cep, cnpj, tipo, ativo } = req.body;
    
    // Verificar se email já existe (exceto para o próprio cliente)
    if (email && email !== cliente.email) {
      const clienteExistente = await Cliente.findOne({ 
        where: { 
          email,
          id: { [require('sequelize').Op.ne]: req.params.id }
        } 
      });
      if (clienteExistente) {
        return res.status(400).json({ error: 'Email já cadastrado' });
      }
    }

    await cliente.update({
      nome,
      email,
      telefone,
      endereco,
      cidade,
      estado,
      cep,
      cnpj,
      tipo,
      ativo
    });

    res.json(cliente);
  } catch (error) {
    console.error('Erro ao atualizar cliente:', error);
    res.status(500).json({ error: 'Erro interno do servidor' });
  }
});

// DELETE /api/clientes/:id - Excluir cliente
router.delete('/:id', auth, async (req, res) => {
  try {
    const cliente = await Cliente.findByPk(req.params.id);
    if (!cliente) {
      return res.status(404).json({ error: 'Cliente não encontrado' });
    }

    // Verificar se há usuários vinculados
    const { Usuario } = require('../models');
    const usuariosVinculados = await Usuario.count({ where: { cliente_id: req.params.id } });
    if (usuariosVinculados > 0) {
      return res.status(400).json({ 
        error: 'Não é possível excluir cliente com usuários vinculados' 
      });
    }

    await cliente.destroy();
    res.json({ message: 'Cliente excluído com sucesso' });
  } catch (error) {
    console.error('Erro ao excluir cliente:', error);
    res.status(500).json({ error: 'Erro interno do servidor' });
  }
});

module.exports = router;
