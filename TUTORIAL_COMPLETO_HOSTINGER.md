# 📚 Tutorial Completo: Configurar Node.js na Hostinger

## 🎯 Objetivo
Configurar o servidor Node.js para que a API funcione e o login do sistema UniAssessor funcione corretamente.

---

## 📋 PARTE 1: CONECTAR VIA SSH

### ⚠️ IMPORTANTE: Verificar Acesso SSH na Hostinger

**ANTES DE TENTAR CONECTAR:**
1. Acesse o painel da Hostinger (hpanel.hostinger.com)
2. Procure por "SSH Access" ou "Acesso SSH"
3. **Ative o acesso SSH** se estiver desativado
4. Anote a **porta SSH** (pode ser diferente de 22)
5. Verifique se o **IP está correto**

### Opção A: Usando PowerShell (Windows 10/11)

#### Passo 1.1: Abrir PowerShell
1. Pressione `Windows + X`
2. Clique em "Windows PowerShell" ou "Terminal"
3. Ou pesquise "PowerShell" no menu Iniciar

#### Passo 1.2: Verificar se SSH está disponível
Digite no PowerShell:
```powershell
ssh
```

**O que esperar:** Se aparecer uma mensagem de ajuda do SSH, está funcionando. Se não, você precisa instalar o OpenSSH.

#### Passo 1.3: Conectar ao servidor
Use as informações do seu servidor:
- **IP do servidor:** `82.25.67.216`
- **Usuário:** `u698920850` (ou o usuário que a Hostinger forneceu)
- **Porta:** Normalmente `22`, mas pode ser diferente (verifique no painel)

**Comando básico (porta 22):**
```powershell
ssh u698920850@82.25.67.216
```

**Se a porta for diferente (exemplo: 65002):**
```powershell
ssh -p 65002 u698920850@82.25.67.216
```

**⚠️ IMPORTANTE - Porta SSH da Hostinger:**
- A porta SSH da Hostinger geralmente NÃO é 22
- Verifique no painel: "Acesso SSH" → "Porta"
- Use a porta correta: `ssh -p PORTA u698920850@82.25.67.216`

**Se aparecer erro "Connection timed out":**
- Verifique se o SSH está habilitado no painel da Hostinger
- Verifique se está usando a porta correta (não é sempre 22!)
- Tente usar o File Manager da Hostinger (alternativa sem SSH)
- Veja a seção "Alternativa: Usar File Manager" abaixo

**Se aparecer "Connection closed" após aceitar a chave:**
- Isso geralmente significa que a senha está incorreta
- OU o usuário não tem permissão de shell
- Veja a seção de solução de problemas abaixo

**O que vai acontecer:**
1. Na primeira vez, aparecerá uma mensagem perguntando se você confia no servidor
2. Digite `yes` e pressione Enter
3. Será solicitada a senha (a senha do seu FTP/SSH da Hostinger)
4. **IMPORTANTE:** Ao digitar a senha, nada aparecerá na tela (nem asteriscos). Isso é normal! Apenas digite e pressione Enter.

**Exemplo do que você verá:**
```
The authenticity of host '82.25.67.216' can't be established.
ECDSA key fingerprint is SHA256:...
Are you sure you want to continue connecting (yes/no/[fingerprint])? yes
u698920850@82.25.67.216's password: [digite a senha aqui - nada aparecerá]
```

#### Passo 1.4: Verificar se conectou com sucesso
Após digitar a senha corretamente, você verá algo como:
```
Welcome to Ubuntu...
u698920850@server:~$
```

**✅ Se você vê o prompt `$` ou `#`, você está conectado!**

---

### Opção B: Usando PuTTY (Windows - Alternativa)

#### Passo 1.1: Baixar e instalar PuTTY
1. Acesse: https://www.putty.org/
2. Baixe o instalador
3. Instale normalmente

#### Passo 1.2: Abrir PuTTY
1. Abra o programa PuTTY
2. Na tela inicial, você verá campos para preencher

#### Passo 1.3: Configurar conexão
1. **Host Name (or IP address):** Digite `82.25.67.216`
2. **Port:** Deixe `22` (porta padrão SSH)
3. **Connection type:** Selecione `SSH`
4. (Opcional) Clique em "Save" para salvar esta configuração

#### Passo 1.4: Conectar
1. Clique no botão "Open"
2. Uma janela preta (terminal) abrirá
3. Se aparecer um aviso de segurança, clique em "Yes"
4. Digite o usuário: `u698920850`
5. Pressione Enter
6. Digite a senha (nada aparecerá na tela)
7. Pressione Enter

**✅ Se você vê o prompt `$`, está conectado!**

---

## 🔄 ALTERNATIVA: Se SSH não funcionar - Usar File Manager da Hostinger

Se você não conseguir conectar via SSH, você pode usar o **File Manager** (Gerenciador de Arquivos) do painel da Hostinger para fazer algumas configurações básicas.

### Como acessar:
1. Acesse o painel da Hostinger
2. Procure por "File Manager" ou "Gerenciador de Arquivos"
3. Navegue até a pasta `public_html`

### Limitações:
- ❌ Não pode executar comandos `npm install`
- ❌ Não pode executar `pm2 start`
- ✅ Pode criar/editar arquivos
- ✅ Pode verificar se arquivos existem

### O que fazer:
Se SSH não funcionar, você precisará:
1. **Contatar o suporte da Hostinger** para habilitar SSH
2. **OU** usar um serviço de hospedagem Node.js (como Railway, Render, Heroku) apenas para a API
3. **OU** configurar via painel se a Hostinger tiver interface para Node.js

---

## 📋 PARTE 2: NAVEGAR ATÉ O DIRETÓRIO DO PROJETO

### Passo 2.1: Ver onde você está
Digite:
```bash
pwd
```

**O que esperar:** Algo como `/home/u698920850` ou `/home/u698920850/domains/uniassessor.com.br`

### Passo 2.2: Listar arquivos e pastas
Digite:
```bash
ls -la
```

**O que esperar:** Uma lista de arquivos e pastas. Procure por `public_html` ou `domains`.

### Passo 2.3: Entrar na pasta public_html
Digite:
```bash
cd public_html
```

**O que esperar:** Nada acontece (isso é bom!). O prompt pode mudar para mostrar `public_html`.

### Passo 2.4: Verificar se está no lugar certo
Digite:
```bash
pwd
```

**O que esperar:** Deve mostrar algo como `/home/u698920850/public_html` ou `/home/u698920850/domains/uniassessor.com.br/public_html`

### Passo 2.5: Listar arquivos do projeto
Digite:
```bash
ls -la
```

**O que esperar:** Você deve ver arquivos como:
- `server.js`
- `package.json`
- `public/`
- `routes/`
- `models/`
- etc.

**✅ Se você vê esses arquivos, está no lugar certo!**

---

## 📋 PARTE 3: VERIFICAR SE NODE.JS ESTÁ INSTALADO

### Passo 3.1: Verificar versão do Node.js
Digite:
```bash
node --version
```

**O que esperar:**
- ✅ Se aparecer algo como `v16.x.x` ou `v18.x.x` ou superior: Node.js está instalado!
- ❌ Se aparecer `command not found`: Node.js não está instalado (veja solução abaixo)

### Passo 3.2: Verificar versão do NPM
Digite:
```bash
npm --version
```

**O que esperar:**
- ✅ Se aparecer algo como `8.x.x` ou superior: NPM está instalado!
- ❌ Se aparecer `command not found`: NPM não está instalado

### ⚠️ Se Node.js NÃO estiver instalado:

#### Opção 1: Usar NVM (Node Version Manager) - RECOMENDADO
```bash
# Instalar NVM
curl -o- https://raw.githubusercontent.com/nvm-sh/nvm/v0.39.0/install.sh | bash

# Recarregar o terminal
source ~/.bashrc

# Instalar Node.js versão 18 (LTS)
nvm install 18

# Usar Node.js 18
nvm use 18

# Verificar instalação
node --version
npm --version
```

#### Opção 2: Instalar via gerenciador de pacotes
Se você tem acesso root (sudo):
```bash
# Para Ubuntu/Debian
curl -fsSL https://deb.nodesource.com/setup_18.x | sudo -E bash -
sudo apt-get install -y nodejs

# Verificar
node --version
npm --version
```

**💡 Dica:** Se você não tem acesso root, use a Opção 1 (NVM).

---

## 📋 PARTE 4: INSTALAR DEPENDÊNCIAS DO PROJETO

### Passo 4.1: Garantir que está no diretório correto
```bash
pwd
# Deve mostrar: .../public_html
```

Se não estiver, volte:
```bash
cd public_html
```

### Passo 4.2: Verificar se package.json existe
```bash
ls package.json
```

**O que esperar:** Se aparecer `package.json`, está correto. Se aparecer `No such file`, você não está no diretório certo.

### Passo 4.3: Instalar dependências
```bash
npm install
```

**O que vai acontecer:**
- O NPM começará a baixar e instalar todos os pacotes necessários
- Isso pode levar de 2 a 10 minutos dependendo da velocidade da internet
- Você verá muitas linhas de texto rolando

**O que esperar:**
- ✅ No final, deve aparecer algo como: `added 234 packages in 2m`
- ❌ Se aparecer erros, anote a mensagem de erro

**⚠️ IMPORTANTE:** 
- Se aparecer avisos (warnings), pode ignorar (são apenas avisos)
- Se aparecer ERROS (errors), você precisa resolver antes de continuar

### Passo 4.4: Verificar se node_modules foi criado
```bash
ls -la | grep node_modules
```

**O que esperar:** Deve aparecer uma linha com `node_modules` (é uma pasta).

**✅ Se você vê `node_modules`, as dependências foram instaladas!**

---

## 📋 PARTE 5: CONFIGURAR VARIÁVEIS DE AMBIENTE

### Passo 5.1: Criar arquivo .env
```bash
nano .env
```

**O que vai acontecer:** Abrirá o editor de texto Nano (um editor simples no terminal).

### Passo 5.2: Adicionar configurações
Copie e cole o seguinte conteúdo no editor:

```env
# Banco de Dados MySQL
DB_HOST=localhost
DB_PORT=3306
DB_NAME=assessor_digital
DB_USER=u698920850_assessor
DB_PASSWORD=sua_senha_mysql_aqui

# JWT (Token de segurança)
JWT_SECRET=seu_jwt_secret_muito_seguro_aqui_123456789_altere_esta_senha
JWT_EXPIRES_IN=24h

# Servidor
NODE_ENV=production
PORT=3000

# Frontend URL
FRONTEND_URL=https://uniassessor.com.br
```

**⚠️ IMPORTANTE - O que você precisa alterar:**

1. **DB_USER:** Substitua `u698920850_assessor` pelo seu usuário MySQL da Hostinger
   - Você encontra isso no painel da Hostinger → MySQL Databases
   
2. **DB_PASSWORD:** Substitua `sua_senha_mysql_aqui` pela senha do MySQL
   - Use a senha que você configurou no painel da Hostinger
   
3. **DB_NAME:** Se você criou um banco com nome diferente, altere `assessor_digital`
   - Verifique no painel da Hostinger → MySQL Databases

4. **JWT_SECRET:** Altere para uma senha aleatória longa e segura
   - Exemplo: `MinhaSenhaSuperSecreta123456789!@#$%`

### Passo 5.3: Salvar o arquivo
1. Pressione `Ctrl + O` (letra O, não zero)
2. Pressione `Enter` para confirmar o nome do arquivo
3. Pressione `Ctrl + X` para sair do editor

**✅ Arquivo .env criado com sucesso!**

### Passo 5.4: Verificar se o arquivo foi criado
```bash
ls -la .env
```

**O que esperar:** Deve aparecer uma linha mostrando o arquivo `.env`

### Passo 5.5: (Opcional) Ver conteúdo do arquivo
```bash
cat .env
```

**⚠️ CUIDADO:** Isso mostra a senha na tela. Feche o terminal depois se estiver em local público.

---

## 📋 PARTE 6: CRIAR/VERIFICAR BANCO DE DADOS

### Passo 6.1: Verificar se o banco de dados existe
No painel da Hostinger:
1. Acesse "MySQL Databases"
2. Verifique se existe um banco chamado `assessor_digital` (ou o nome que você usou)
3. Se não existir, crie um novo banco de dados

### Passo 6.2: Verificar credenciais do MySQL
No painel da Hostinger:
1. Anote o nome do usuário MySQL
2. Anote a senha do MySQL
3. Certifique-se de que o usuário tem permissão no banco de dados

### Passo 6.3: Testar conexão com o banco (Opcional)
Se você tem acesso ao MySQL via terminal:
```bash
mysql -u seu_usuario_mysql -p
# Digite a senha quando solicitado
```

Se conseguir entrar, digite:
```sql
SHOW DATABASES;
USE assessor_digital;
SHOW TABLES;
exit;
```

---

## 📋 PARTE 7: INICIALIZAR BANCO DE DADOS (SE NECESSÁRIO)

### Passo 7.1: Executar script de inicialização
```bash
npm run init-db
```

**O que vai acontecer:**
- O script tentará criar as tabelas no banco de dados
- Você verá mensagens sobre criação de tabelas

**O que esperar:**
- ✅ Mensagens de sucesso sobre tabelas criadas
- ❌ Se aparecer erro de conexão, verifique o arquivo `.env`

**⚠️ IMPORTANTE:** 
- Se o banco já tiver tabelas, pode aparecer avisos. Isso é normal.
- Se aparecer erros, verifique as credenciais no `.env`

---

## 📋 PARTE 8: INSTALAR PM2 (GERENCIADOR DE PROCESSOS)

### Passo 8.1: Instalar PM2 globalmente
```bash
npm install -g pm2
```

**O que vai acontecer:**
- O NPM instalará o PM2 globalmente
- Isso pode levar 1-2 minutos

**O que esperar:**
- ✅ Mensagem de sucesso: `+ pm2@5.x.x`
- ❌ Se aparecer erro de permissão, veja solução abaixo

### ⚠️ Se aparecer erro de permissão:
```bash
# Tentar com sudo (se tiver acesso)
sudo npm install -g pm2

# OU instalar localmente (sem -g)
npm install pm2
# Depois usar: ./node_modules/.bin/pm2 ao invés de pm2
```

### Passo 8.2: Verificar se PM2 foi instalado
```bash
pm2 --version
```

**O que esperar:** Deve aparecer algo como `5.x.x`

**✅ Se você vê a versão, PM2 está instalado!**

---

## 📋 PARTE 9: INICIAR O SERVIDOR COM PM2

### Passo 9.1: Garantir que está no diretório correto
```bash
pwd
# Deve mostrar: .../public_html
```

### Passo 9.2: Verificar se server.js existe
```bash
ls server.js
```

**O que esperar:** Deve aparecer `server.js`

### Passo 9.3: Iniciar o servidor
```bash
pm2 start server.js --name uniassessor-api
```

**O que vai acontecer:**
- O PM2 iniciará o servidor Node.js
- Você verá uma tabela mostrando o status

**O que esperar:**
```
┌─────┬──────────────────┬─────────┬─────────┬──────────┐
│ id  │ name             │ status  │ restart │ uptime   │
├─────┼──────────────────┼─────────┼─────────┼──────────┤
│ 0   │ uniassessor-api  │ online  │ 0       │ 0s       │
└─────┴──────────────────┴─────────┴─────────┴──────────┘
```

**✅ Se você vê `online` na coluna status, o servidor está rodando!**

### Passo 9.4: Ver logs do servidor
```bash
pm2 logs uniassessor-api
```

**O que esperar:**
- Você verá mensagens do servidor
- Procure por: `🚀 Servidor rodando na porta 3000`
- Se aparecer erros, anote a mensagem

**Para sair dos logs:** Pressione `Ctrl + C`

### Passo 9.5: Ver status novamente
```bash
pm2 status
```

**O que esperar:** Deve mostrar o servidor como `online`

---

## 📋 PARTE 10: CONFIGURAR PM2 PARA INICIAR AUTOMATICAMENTE

### Passo 10.1: Configurar startup
```bash
pm2 startup
```

**O que vai acontecer:**
- O PM2 mostrará um comando que você precisa executar
- Será algo como: `sudo env PATH=... pm2 startup systemd -u u698920850 --hp /home/u698920850`

**⚠️ IMPORTANTE:** 
- Copie o comando completo que aparecer
- Cole e execute (pode precisar de `sudo`)

### Passo 10.2: Salvar configuração atual
```bash
pm2 save
```

**O que esperar:** Mensagem: `[PM2] Saving current process list...`

**✅ Agora o servidor iniciará automaticamente quando o servidor reiniciar!**

---

## 📋 PARTE 11: VERIFICAR SE ESTÁ FUNCIONANDO

### Passo 11.1: Testar API localmente (no servidor)
```bash
curl http://localhost:3000/api/health
```

**O que esperar:**
```json
{"status":"OK","timestamp":"2024-01-01T12:00:00.000Z","version":"1.0.0"}
```

**✅ Se você vê essa resposta, a API está funcionando!**

### Passo 11.2: Testar API externamente
No seu navegador, acesse:
```
https://uniassessor.com.br/api/health
```

**O que esperar:**
- Deve aparecer o mesmo JSON de resposta
- Se aparecer erro 404 ou "Não encontrado", você precisa configurar o proxy reverso (veja Parte 12)

---

## 📋 PARTE 12: CONFIGURAR PROXY REVERSO (SE NECESSÁRIO)

### ⚠️ IMPORTANTE:
Se ao acessar `https://uniassessor.com.br/api/health` não funcionar, você precisa configurar o proxy reverso.

### Opção A: Se a Hostinger usa Apache

Crie ou edite o arquivo `.htaccess` na pasta `public_html`:

```bash
nano public_html/.htaccess
```

Adicione estas linhas (se já existir um .htaccess, adicione no final):

```apache
# Proxy para API Node.js
RewriteEngine On
RewriteCond %{REQUEST_URI} ^/api [NC]
RewriteRule ^api/(.*)$ http://localhost:3000/api/$1 [P,L]
```

Salve: `Ctrl + O`, `Enter`, `Ctrl + X`

### Opção B: Se a Hostinger usa Nginx

Você precisará de acesso ao arquivo de configuração do Nginx. Normalmente em:
```
/etc/nginx/sites-available/uniassessor.com.br
```

Adicione dentro do bloco `server {`:

```nginx
location /api {
    proxy_pass http://localhost:3000;
    proxy_http_version 1.1;
    proxy_set_header Upgrade $http_upgrade;
    proxy_set_header Connection 'upgrade';
    proxy_set_header Host $host;
    proxy_set_header X-Real-IP $remote_addr;
    proxy_set_header X-Forwarded-For $proxy_add_x_forwarded_for;
    proxy_set_header X-Forwarded-Proto $scheme;
    proxy_cache_bypass $http_upgrade;
}
```

Depois recarregue o Nginx:
```bash
sudo nginx -t  # Testar configuração
sudo systemctl reload nginx  # Recarregar
```

---

## 📋 PARTE 13: TESTAR O LOGIN

### Passo 13.1: Acessar o site
No navegador, acesse:
```
https://uniassessor.com.br/index-login.php
```

### Passo 13.2: Tentar fazer login
1. Digite o email: `dudu0072812@gmail.com`
2. Digite a senha: `123456` (ou a senha que você configurou)
3. Clique em "Entrar"

### Passo 13.3: Verificar se funcionou
- ✅ Se redirecionar para o dashboard: **SUCESSO!**
- ❌ Se aparecer erro: Veja a Parte 14 (Solução de Problemas)

---

## 📋 PARTE 14: SOLUÇÃO DE PROBLEMAS COMUNS

### Problema 0: "Connection timed out" ao tentar SSH

**Sintomas:**
```
ssh: connect to host 82.25.67.216 port 22: Connection timed out
```

**Soluções:**

1. **Verificar se SSH está habilitado no painel:**
   - Acesse hpanel.hostinger.com
   - Procure por "SSH Access" ou "Acesso SSH"
   - Ative se estiver desativado
   - Aguarde alguns minutos após ativar

2. **Verificar a porta SSH (MUITO IMPORTANTE!):**
   - No painel da Hostinger, veja qual porta SSH está configurada
   - **A Hostinger geralmente usa portas diferentes de 22** (ex: 65002, 2222, etc.)
   - Use a porta correta: `ssh -p PORTA u698920850@82.25.67.216`
   - Exemplo: `ssh -p 65002 u698920850@82.25.67.216`

3. **Verificar se o IP está correto:**
   - Confirme o IP do servidor no painel da Hostinger
   - Pode ter mudado ou estar diferente

4. **Tentar de outra rede:**
   - Alguns provedores bloqueiam porta 22
   - Tente de outra internet (celular, outra rede)

5. **Contatar suporte da Hostinger:**
   - Se nada funcionar, abra um ticket
   - Peça para habilitar acesso SSH
   - Informe que precisa para rodar Node.js

6. **Alternativa: Usar File Manager:**
   - Use o gerenciador de arquivos do painel
   - Mas você ainda precisará de SSH para rodar Node.js
   - Ou considere usar outro serviço para a API

### Problema 0.5: "Connection closed" após aceitar a chave SSH

**Sintomas:**
```
Warning: Permanently added '[82.25.67.216]:65002' (ED25519) to the list of known hosts.
Connection closed by 82.25.67.216 port 65002
```

**O que significa:**
- A conexão foi estabelecida (a chave foi aceita)
- Mas o servidor fechou a conexão imediatamente
- Geralmente significa problema de autenticação ou permissões

**Soluções:**

1. **Verificar se a senha está correta:**
   - Use a senha SSH do painel da Hostinger (não a senha FTP)
   - Se necessário, altere a senha SSH no painel
   - Tente conectar novamente

2. **Verificar se o usuário tem permissão de shell:**
   - Alguns planos da Hostinger podem ter restrições
   - Contate o suporte para verificar se seu plano permite shell access

3. **Tentar com modo verbose para ver mais detalhes:**
   ```powershell
   ssh -v -p 65002 u698920850@82.25.67.216
   ```
   Isso mostrará mais informações sobre o que está acontecendo

4. **Verificar se precisa usar autenticação por chave:**
   - Alguns servidores exigem chave SSH ao invés de senha
   - No painel da Hostinger, veja se há opção para gerar/upload de chave SSH

5. **Contatar suporte da Hostinger:**
   - Abra um ticket explicando o problema
   - Informe que a conexão é estabelecida mas fecha imediatamente
   - Peça para verificar permissões de shell do usuário

### Problema 1: "Cannot find module"
**Solução:**
```bash
cd public_html
npm install
pm2 restart uniassessor-api
```

### Problema 2: "Port 3000 already in use"
**Solução:**
```bash
# Ver o que está usando a porta
pm2 list
# Se houver outro processo, pare-o:
pm2 stop nome-do-processo
# Ou mude a porta no .env para 3001
```

### Problema 3: "ECONNREFUSED" ou erro de conexão com banco
**Solução:**
1. Verifique o arquivo `.env`:
```bash
cat .env
```
2. Confirme que as credenciais estão corretas
3. Teste a conexão MySQL no painel da Hostinger
4. Verifique se o banco de dados existe

### Problema 4: Servidor para de funcionar
**Solução:**
```bash
# Ver status
pm2 status

# Ver logs de erro
pm2 logs uniassessor-api --err

# Reiniciar
pm2 restart uniassessor-api
```

### Problema 5: "401 Unauthorized" no login
**Solução:**
1. Verifique se o usuário existe no banco de dados
2. Verifique se a senha está correta (pode precisar resetar)
3. Verifique os logs:
```bash
pm2 logs uniassessor-api
```

### Problema 6: API não responde externamente
**Solução:**
1. Teste localmente primeiro:
```bash
curl http://localhost:3000/api/health
```
2. Se funcionar localmente, o problema é o proxy reverso
3. Verifique a configuração do Apache/Nginx
4. Verifique se a porta 3000 está aberta no firewall

---

## 📋 COMANDOS ÚTEIS - REFERÊNCIA RÁPIDA

### PM2
```bash
pm2 status                    # Ver status de todos os processos
pm2 logs uniassessor-api      # Ver logs em tempo real
pm2 logs uniassessor-api --lines 50  # Ver últimas 50 linhas
pm2 restart uniassessor-api   # Reiniciar servidor
pm2 stop uniassessor-api      # Parar servidor
pm2 delete uniassessor-api    # Remover do PM2
pm2 monit                     # Monitor visual
```

### Navegação
```bash
pwd                          # Ver diretório atual
ls -la                       # Listar arquivos
cd public_html               # Entrar na pasta
cd ..                        # Voltar uma pasta
```

### Verificar processos
```bash
ps aux | grep node           # Ver processos Node.js
netstat -tulpn | grep 3000   # Ver o que está usando porta 3000
```

### Editar arquivos
```bash
nano arquivo.txt             # Editar com Nano
cat arquivo.txt              # Ver conteúdo
head -20 arquivo.txt         # Ver primeiras 20 linhas
tail -20 arquivo.txt         # Ver últimas 20 linhas
```

---

## ✅ CHECKLIST FINAL

Antes de considerar concluído, verifique:

- [ ] Consegui conectar via SSH
- [ ] Node.js está instalado (`node --version`)
- [ ] NPM está instalado (`npm --version`)
- [ ] Dependências instaladas (`npm install` completou)
- [ ] Arquivo `.env` criado e configurado
- [ ] Banco de dados existe e está acessível
- [ ] PM2 instalado (`pm2 --version`)
- [ ] Servidor rodando (`pm2 status` mostra `online`)
- [ ] API responde localmente (`curl http://localhost:3000/api/health`)
- [ ] API responde externamente (`https://uniassessor.com.br/api/health`)
- [ ] Login funciona no navegador

---

## 🆘 PRECISA DE AJUDA?

Se você ficar travado em algum passo:

1. **Anote a mensagem de erro completa**
2. **Verifique em qual parte do tutorial você está**
3. **Consulte a Parte 14 (Solução de Problemas)**
4. **Verifique os logs:** `pm2 logs uniassessor-api`

---

## 📝 NOTAS IMPORTANTES

- ⚠️ **Nunca compartilhe** o arquivo `.env` - ele contém senhas!
- ⚠️ **Mantenha o servidor rodando** - use PM2 para isso
- ⚠️ **Faça backup** do banco de dados regularmente
- ✅ **Monitore os logs** periodicamente para identificar problemas
- ✅ **Atualize as dependências** regularmente: `npm update`

---

**Boa sorte! 🚀**

