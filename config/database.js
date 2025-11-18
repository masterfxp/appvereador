const { Sequelize } = require('sequelize');
require('dotenv').config();

// Configuração do banco de dados - MySQL ou SQLite como fallback
let sequelize;

// Verificar se há configuração MySQL nas variáveis de ambiente
const dbHost = process.env.DB_HOST || process.env.MYSQL_HOST;
const dbPort = process.env.DB_PORT || process.env.MYSQL_PORT || 3306;
const dbName = process.env.DB_NAME || process.env.MYSQL_DATABASE || 'assessor_digital';
const dbUser = process.env.DB_USER || process.env.MYSQL_USER || 'root';
const dbPassword = process.env.DB_PASSWORD || process.env.MYSQL_PASSWORD || '';

// Se houver configuração MySQL, usar MySQL, senão usar SQLite
if (dbHost && dbName && dbUser) {
  console.log('📊 Configurando banco de dados MySQL...');
  console.log(`   Host: ${dbHost}`);
  console.log(`   Porta: ${dbPort}`);
  console.log(`   Banco: ${dbName}`);
  console.log(`   Usuário: ${dbUser}`);
  
  sequelize = new Sequelize(dbName, dbUser, dbPassword, {
    host: dbHost,
    port: dbPort,
    dialect: 'mysql',
    logging: process.env.NODE_ENV === 'development' ? console.log : false,
    define: {
      timestamps: true,
      underscored: false,
      freezeTableName: true
    },
    dialectOptions: {
      // Configurações adicionais do MySQL
      charset: 'utf8mb4',
      collate: 'utf8mb4_unicode_ci',
      connectTimeout: 60000,
      acquireTimeout: 60000,
      timeout: 60000
    },
    pool: {
      max: 5,
      min: 0,
      acquire: 30000,
      idle: 10000
    },
    retry: {
      max: 3
    }
  });
} else {
  console.log('📊 Configurando banco de dados SQLite (fallback)...');
  sequelize = new Sequelize({
    dialect: 'sqlite',
    storage: './database.sqlite',
    logging: process.env.NODE_ENV === 'development' ? console.log : false,
    define: {
      timestamps: true,
      underscored: false,
      freezeTableName: true
    },
    dialectOptions: {
      foreignKeys: false
    }
  });
}

// Testar conexão
const testConnection = async () => {
  try {
    await sequelize.authenticate();
    console.log('✅ Conexão com o banco de dados estabelecida com sucesso.');
    
    // Verificar se o banco de dados existe (apenas para MySQL)
    if (sequelize.getDialect() === 'mysql') {
      const [results] = await sequelize.query("SELECT DATABASE() as current_db");
      console.log(`✅ Banco de dados ativo: ${results[0].current_db}`);
    }
  } catch (error) {
    console.error('❌ Erro ao conectar com o banco de dados:', error.message);
    
    // Se for MySQL e o banco não existir, tentar criar
    if (sequelize.getDialect() === 'mysql' && error.message.includes('Unknown database')) {
      console.log('⚠️  Banco de dados não encontrado. Tentando criar...');
      try {
        // Criar conexão sem especificar o banco
        const tempSequelize = new Sequelize('', dbUser, dbPassword, {
          host: dbHost,
          port: dbPort,
          dialect: 'mysql',
          logging: false
        });
        
        await tempSequelize.query(`CREATE DATABASE IF NOT EXISTS \`${dbName}\` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci`);
        await tempSequelize.close();
        
        console.log(`✅ Banco de dados '${dbName}' criado com sucesso!`);
        console.log('🔄 Reconectando...');
        
        // Reconectar
        await sequelize.authenticate();
        console.log('✅ Conexão estabelecida com sucesso!');
      } catch (createError) {
        console.error('❌ Erro ao criar banco de dados:', createError.message);
        console.error('💡 Certifique-se de que o MySQL está rodando e as credenciais estão corretas.');
        process.exit(1);
      }
    } else {
      process.exit(1);
    }
  }
};

module.exports = { sequelize, testConnection };