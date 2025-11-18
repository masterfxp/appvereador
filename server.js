const express = require('express');
const cors = require('cors');
const helmet = require('helmet');
const rateLimit = require('express-rate-limit');
const fs = require('fs');
const path = require('path');

// Carregar variáveis de ambiente
require('dotenv').config();

// Se não houver .env, tentar carregar config.env
if (!fs.existsSync('.env') && fs.existsSync('config.env')) {
  const configEnv = fs.readFileSync('config.env', 'utf8');
  const lines = configEnv.split('\n');
  lines.forEach(line => {
    const trimmed = line.trim();
    if (trimmed && !trimmed.startsWith('#')) {
      const [key, ...valueParts] = trimmed.split('=');
      if (key && valueParts.length > 0) {
        const value = valueParts.join('=').trim();
        if (!process.env[key.trim()]) {
          process.env[key.trim()] = value;
        }
      }
    }
  });
}

const app = express();
const PORT = process.env.PORT || 3000;

// Middlewares de segurança
app.use(helmet({
  contentSecurityPolicy: {
    directives: {
      defaultSrc: ["'self'"],
      styleSrc: ["'self'", "'unsafe-inline'", "https://cdnjs.cloudflare.com", "https://cdn.tailwindcss.com"],
      scriptSrc: ["'self'", "'unsafe-inline'", "https://cdn.tailwindcss.com", "https://cdn.jsdelivr.net"],
      scriptSrcAttr: ["'unsafe-inline'"],
      imgSrc: ["'self'", "data:", "https:"],
      connectSrc: ["'self'"],
      fontSrc: ["'self'", "https://cdnjs.cloudflare.com"],
      objectSrc: ["'none'"],
      mediaSrc: ["'self'"],
      frameSrc: ["'none'"],
    },
  },
}));
app.use(cors({
  origin: process.env.FRONTEND_URL || ['http://localhost:3000', 'http://localhost:8080'],
  credentials: true
}));

// Rate limiting (mais permissivo em desenvolvimento)
const limiter = rateLimit({
  windowMs: 15 * 60 * 1000, // 15 minutos
  max: process.env.NODE_ENV === 'development' ? 10000 : 1000, // 10k em dev, 1k em produção
  message: {
    error: 'Muitas requisições. Tente novamente em alguns minutos.',
    retryAfter: '15 minutos'
  },
  standardHeaders: true,
  legacyHeaders: false,
  skip: (req) => {
    // Pular rate limiting para health checks
    return req.path === '/api/health';
  }
});
app.use(limiter);

// Middlewares básicos
app.use(express.json({ limit: '10mb' }));
app.use(express.urlencoded({ extended: true }));

// Servir arquivos estáticos
app.use('/uploads', express.static('uploads'));
app.use(express.static('public'));

// Importar e sincronizar banco de dados
const { sequelize } = require('./config/database');
require('./models'); // Importar todos os modelos

// Sincronizar banco de dados
console.log('🔍 Iniciando sincronização do banco de dados...');
sequelize.sync({ alter: true })
  .then(() => {
    console.log('✅ Banco de dados sincronizado com sucesso');
  })
  .catch(err => {
    console.error('❌ Erro ao sincronizar banco de dados:', err);
    console.error('Detalhes do erro:', err.message);
  });

// Importar rotas
const authRoutes = require('./routes/auth');
const projetosRoutes = require('./routes/projetos');
const demandasRoutes = require('./routes/demandas');
const reunioesRoutes = require('./routes/reunioes');
const tarefasRoutes = require('./routes/tarefas');
const noticiasRoutes = require('./routes/noticias');
const usuariosRoutes = require('./routes/usuarios');
const indicadoresRoutes = require('./routes/indicadores');
const licencasRoutes = require('./routes/licencas');
const gabinetesRoutes = require('./routes/gabinetes');
const tiposContratoRoutes = require('./routes/tipos-contrato');
const notificacoesRoutes = require('./routes/notificacoes');
const clientesRoutes = require('./routes/clientes');
const perfilRoutes = require('./routes/perfil');

// Rotas da API
app.use('/api/auth', authRoutes);
app.use('/api/projetos', projetosRoutes);
app.use('/api/demandas', demandasRoutes);
app.use('/api/reunioes', reunioesRoutes);
app.use('/api/tarefas', tarefasRoutes);
app.use('/api/noticias', noticiasRoutes);
app.use('/api/usuarios', usuariosRoutes);
app.use('/api/indicadores', indicadoresRoutes);
app.use('/api/licencas', licencasRoutes);
app.use('/api/gabinetes', gabinetesRoutes);
app.use('/api/tipos-contrato', tiposContratoRoutes);
app.use('/api/notificacoes', notificacoesRoutes);
app.use('/api/clientes', clientesRoutes);
app.use('/api/perfil', perfilRoutes);

// Rota de health check
app.get('/api/health', (req, res) => {
  res.json({
    status: 'OK',
    timestamp: new Date().toISOString(),
    version: '1.0.0'
  });
});

// Middleware de tratamento de erros
app.use((err, req, res, next) => {
  console.error(err.stack);
  res.status(500).json({ 
    error: 'Algo deu errado!',
    message: process.env.NODE_ENV === 'development' ? err.message : 'Erro interno do servidor'
  });
});

// Rota 404
app.use('*', (req, res) => {
  res.status(404).json({ error: 'Rota não encontrada' });
});

// Inicializar servidor
app.listen(PORT, () => {
  console.log(`🚀 Servidor rodando na porta ${PORT}`);
  console.log(`📚 Documentação da API: http://localhost:${PORT}/api/docs`);
  console.log(`🌐 Frontend: http://localhost:${PORT}`);
});

module.exports = app;