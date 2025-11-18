/**
 * Middleware para sistema multi-tenant
 * Filtra dados automaticamente por cliente_id do usuário logado
 */

/**
 * Middleware que adiciona filtro de cliente_id nas consultas
 * @param {Object} req - Request object
 * @param {Object} res - Response object  
 * @param {Function} next - Next function
 */
const filterByClient = (req, res, next) => {
  if (req.user && req.user.cliente_id) {
    // Adicionar cliente_id ao body para criação
    if (req.method === 'POST' || req.method === 'PUT') {
      req.body.cliente_id = req.user.cliente_id;
    }
    
    // Adicionar cliente_id aos query params para consultas
    if (req.method === 'GET') {
      req.query.cliente_id = req.user.cliente_id;
    }
    
    // Armazenar cliente_id no req para uso nas rotas
    req.cliente_id = req.user.cliente_id;
  }
  
  next();
};

/**
 * Middleware que força o filtro por cliente_id em consultas
 * @param {Object} req - Request object
 * @param {Object} res - Response object
 * @param {Function} next - Next function
 */
const enforceClientFilter = (req, res, next) => {
  if (!req.user || !req.user.cliente_id) {
    return res.status(401).json({ 
      error: 'Usuário não autenticado ou sem cliente associado',
      code: 'NO_CLIENT'
    });
  }
  
  req.cliente_id = req.user.cliente_id;
  next();
};

/**
 * Função helper para adicionar filtro de cliente em consultas Sequelize
 * @param {Object} where - Objeto where do Sequelize
 * @param {Number} cliente_id - ID do cliente
 * @returns {Object} Objeto where com filtro de cliente
 */
const addClientFilter = (where = {}, cliente_id) => {
  return {
    ...where,
    cliente_id: cliente_id
  };
};

/**
 * Função helper para adicionar cliente_id em dados de criação
 * @param {Object} data - Dados para criação
 * @param {Number} cliente_id - ID do cliente
 * @returns {Object} Dados com cliente_id
 */
const addClientToData = (data, cliente_id) => {
  return {
    ...data,
    cliente_id: cliente_id
  };
};

module.exports = {
  filterByClient,
  enforceClientFilter,
  addClientFilter,
  addClientToData
};

