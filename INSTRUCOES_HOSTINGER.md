# Instruções para Configurar o Servidor Node.js na Hostinger

## ⚠️ Problema Atual
O erro "senha incorreta" está ocorrendo porque o servidor Node.js não está rodando. A API precisa estar ativa para processar o login.

## 📋 Pré-requisitos
1. Acesso SSH ao servidor Hostinger (VPS ou hospedagem com Node.js)
2. Node.js instalado (versão 16 ou superior)
3. NPM instalado

## 🚀 Passos para Configurar

### 1. Conectar via SSH
```bash
ssh seu-usuario@82.25.67.216
```

### 2. Navegar para o diretório do projeto
```bash
cd public_html
```

### 3. Instalar dependências
```bash
npm install
```

### 4. Configurar variáveis de ambiente
Crie um arquivo `.env` na raiz do projeto:
```bash
nano .env
```

Adicione as configurações:
```env
# Banco de Dados
DB_HOST=localhost
DB_PORT=3306
DB_NAME=assessor_digital
DB_USER=seu_usuario_mysql
DB_PASSWORD=sua_senha_mysql

# JWT
JWT_SECRET=seu_jwt_secret_muito_seguro_aqui_123456789
JWT_EXPIRES_IN=24h

# Servidor
NODE_ENV=production
PORT=3000

# Frontend URL
FRONTEND_URL=https://uniassessor.com.br
```

### 5. Inicializar o banco de dados (se necessário)
```bash
npm run init-db
```

### 6. Instalar PM2 (gerenciador de processos)
```bash
npm install -g pm2
```

### 7. Iniciar o servidor com PM2
```bash
pm2 start server.js --name uniassessor-api
```

### 8. Configurar PM2 para iniciar automaticamente
```bash
pm2 startup
pm2 save
```

### 9. Verificar se o servidor está rodando
```bash
pm2 status
pm2 logs uniassessor-api
```

## 🔧 Configuração do Proxy Reverso (Nginx/Apache)

Se você estiver usando Nginx, adicione ao arquivo de configuração:

```nginx
server {
    listen 80;
    server_name uniassessor.com.br;

    location /api {
        proxy_pass http://localhost:3000;
        proxy_http_version 1.1;
        proxy_set_header Upgrade $http_upgrade;
        proxy_set_header Connection 'upgrade';
        proxy_set_header Host $host;
        proxy_cache_bypass $http_upgrade;
    }

    location / {
        root /home/usuario/public_html/public;
        try_files $uri $uri/ /index.php?$query_string;
    }
}
```

## ✅ Verificar se está funcionando

1. Teste a API diretamente:
```bash
curl https://uniassessor.com.br/api/health
```

2. Deve retornar:
```json
{
  "status": "OK",
  "timestamp": "...",
  "version": "1.0.0"
}
```

## 🔍 Comandos Úteis do PM2

- Ver status: `pm2 status`
- Ver logs: `pm2 logs uniassessor-api`
- Reiniciar: `pm2 restart uniassessor-api`
- Parar: `pm2 stop uniassessor-api`
- Deletar: `pm2 delete uniassessor-api`

## ⚠️ Importante

- O servidor Node.js precisa estar rodando **24/7** para que a API funcione
- Use PM2 para manter o servidor ativo mesmo após reinicializações
- Verifique os logs regularmente para identificar problemas

## 🆘 Solução de Problemas

### Erro: "Cannot find module"
```bash
cd public_html
npm install
```

### Erro: "Port 3000 already in use"
```bash
# Verificar qual processo está usando a porta
lsof -i :3000
# Ou mudar a porta no .env
```

### Erro de conexão com banco de dados
- Verifique as credenciais no arquivo `.env`
- Certifique-se de que o MySQL está rodando
- Verifique se o banco de dados existe

