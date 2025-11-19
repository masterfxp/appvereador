# Assessor Digital

Sistema SaaS completo para gestão de gabinetes de vereadores, desenvolvido com Node.js, React e React Native.

## 🚀 Funcionalidades

### 🏛️ Apoio Legislativo
- CRUD de projetos de lei, indicações, requerimentos e moções
- Histórico de tramitação
- Geração automática de documentos com IA
- Biblioteca de templates por município

### 👥 Atendimento ao Públicoa
- Formulário de solicitações da população
- Upload de imagens e PDFs
- Integração com WhatsApp e e-dmail
- Dashboard de indicadores
- Mapa com georreferenciamento (Google Maps)

### 🤝 Articulação Política
- Agenda de reuniões
- Registro de visitas e eventos
- Histórico de contatos
- CRUD de compromissos com lembretes

### 📣 Comunicação e Imagem
- Agenda pública
- Publicador de notícias e discursos
- Integração com redes sociais
- Biblioteca de textos

### 🏢 Gestão de Gabinete
- Controle de equipe de assessores
- Cadastro do plano de governo
- Assinatura digital
- Editor de texto (TinyMCE)
- Chat interno

### 📊 Dashboard de Indicadores
- Métricas em tempo real
- Gráficos e KPIs
- Relatórios em PDF e Excel

## 🛠️ Tecnologias

### Backend
- **Node.js** + Express
- **MySQL** com Sequelize ORM
- **JWT** para autenticação
- **Multer** para upload de arquivos
- **Nodemailer** para envio de e-mails
- **Socket.io** para chat em tempo real

### Frontend
- **React** + TailwindCSS
- **Chart.js** para gráficos
- **Axios** para requisições HTTP
- **React Router** para navegação

### Mobile
- **React Native**
- **Expo** para desenvolvimento
- **Push Notifications**

## 📦 Instalação

### Pré-requisitos
- Node.js 16+
- MySQL 8+
- npm ou yarn

### 1. Clone o repositório
```bash
git clone https://github.com/seu-usuario/assessor-digital.git
cd assessor-digital
```

### 2. Instale as dependências
```bash
npm install
```

### 3. Configure as variáveis de ambiente
```bash
cp env.example .env
```

Edite o arquivo `.env` com suas configurações:
```env
# Configurações do Servidor
PORT=3000
NODE_ENV=development

# Banco de Dados MySQL
DB_HOST=localhost
DB_PORT=3306
DB_NAME=assessor_digital
DB_USER=root
DB_PASSWORD=sua_senha

# JWT
JWT_SECRET=seu_jwt_secret_super_seguro_aqui
JWT_EXPIRES_IN=24h

# E-mail
EMAIL_HOST=smtp.gmail.com
EMAIL_PORT=587
EMAIL_USER=seu_email@gmail.com
EMAIL_PASS=sua_senha_app

# Google Maps API
GOOGLE_MAPS_API_KEY=sua_chave_google_maps

# Upload de arquivos
UPLOAD_PATH=./uploads
MAX_FILE_SIZE=10485760

# URL do Frontend
FRONTEND_URL=http://localhost:3001
```

### 4. Configure o banco de dados
```bash
# Crie o banco de dados
mysql -u root -p
CREATE DATABASE assessor_digital;
```

### 5. Inicialize o banco de dados
```bash
npm run init-db
```

### 6. Execute o servidor
```bash
npm run dev
```

O servidor estará rodando em `http://localhost:3000`

## 📚 Documentação da API

Acesse a documentação interativa em:
`http://localhost:3000/api/docs`

## 🗂️ Estrutura do Projeto

```
assessor-digital/
├── config/
│   └── database.js          # Configuração do banco
├── middleware/
│   └── auth.js              # Middleware de autenticação
├── models/
│   ├── Usuario.js           # Modelo de usuário
│   ├── Gabinete.js          # Modelo de gabinete
│   ├── Projeto.js           # Modelo de projeto
│   ├── Demanda.js           # Modelo de demanda
│   ├── Reuniao.js           # Modelo de reunião
│   ├── Tarefa.js            # Modelo de tarefa
│   ├── Noticia.js           # Modelo de notícia
│   ├── Indicador.js         # Modelo de indicador
│   ├── Chat.js              # Modelo de chat
│   └── index.js             # Associações dos modelos
├── routes/
│   ├── auth.js              # Rotas de autenticação
│   ├── users.js             # Rotas de usuários
│   ├── projetos.js          # Rotas de projetos
│   ├── demandas.js          # Rotas de demandas
│   ├── reunioes.js          # Rotas de reuniões
│   ├── tarefas.js           # Rotas de tarefas
│   ├── noticias.js          # Rotas de notícias
│   ├── indicadores.js       # Rotas de indicadores
│   ├── admin.js             # Rotas administrativas
│   └── chat.js              # Rotas de chat
├── scripts/
│   └── init-db.js           # Script de inicialização
├── uploads/                 # Diretório de uploads
├── utils/
│   └── jwt.js               # Utilitários JWT
├── server.js                # Servidor principal
├── package.json
├── swagger.json             # Documentação da API
└── README.md
```

## 🔐 Níveis de Acesso

### Administrador (Vereador)
- Acesso total ao sistema
- Gerencia usuários e configurações
- Controla todo o gabinete

### Assessor
- Acesso às funções liberadas pelo administrador
- Pode criar e editar projetos, demandas, etc.
- Acesso ao chat interno

### Cidadão
- Registra demandas via aplicativo
- Consulta status das solicitações
- Acesso à agenda pública e notícias

## 🚀 Deploy

### Hostinger VPS
1. Configure o servidor VPS
2. Instale Node.js e MySQL
3. Clone o repositório
4. Configure as variáveis de ambiente
5. Execute `npm run build`
6. Configure PM2 para gerenciar o processo

### Frontend
O frontend React pode ser hospedado em:
- Vercel
- Netlify
- Hostinger

## 📱 App Mobile

Para executar o app mobile:

```bash
cd mobile
npm install
npx expo start
```

## 🧪 Testes

```bash
npm test
```

## 📄 Licença

Este projeto está sob a licença MIT. Veja o arquivo [LICENSE](LICENSE) para mais detalhes.

## 🤝 Contribuição

1. Faça um fork do projeto
2. Crie uma branch para sua feature (`git checkout -b feature/AmazingFeature`)
3. Commit suas mudanças (`git commit -m 'Add some AmazingFeature'`)
4. Push para a branch (`git push origin feature/AmazingFeature`)
5. Abra um Pull Request

## 📞 Suporte

Para suporte, entre em contato:
- Email: contato@assessordigital.com
- WhatsApp: (11) 99999-9999

## 🎯 Roadmap

- [ ] Integração com IA para geração de documentos
- [ ] Sistema de assinatura digital
- [ ] Integração com redes sociais
- [ ] App mobile nativo
- [ ] Sistema de notificações push
- [ ] Relatórios avançados
- [ ] Integração com sistemas da Câmara

---

Desenvolvido com ❤️ para vereadores e assessores
Deploy atualizadao chama aaa5chamttestea 🚀


