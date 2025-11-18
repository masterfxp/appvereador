const { sequelize, testConnection } = require('../config/database');
const { 
  Usuario, 
  Gabinete, 
  Projeto, 
  Demanda, 
  Reuniao, 
  Tarefa, 
  Noticia, 
  Indicador,
  Chat,
  Cliente
} = require('../models');

const initDatabase = async () => {
  try {
    console.log('🔄 Inicializando banco de dados...');
    
    // Testar conexão
    await testConnection();
    
    // Sincronizar modelos
    await sequelize.sync({ force: false, alter: true });
    console.log('✅ Modelos sincronizados com sucesso');
    
    // Criar dados iniciais se não existirem
    await createInitialData();
    
    console.log('🎉 Banco de dados inicializado com sucesso!');
    
  } catch (error) {
    console.error('❌ Erro ao inicializar banco de dados:', error);
    process.exit(1);
  }
};

const createInitialData = async () => {
  try {
    // Verificar se já existem dados
    const gabineteExistente = await Gabinete.findOne();
    if (gabineteExistente) {
      console.log('📊 Dados já existem no banco');
      
      // Verificar se existe usuário padrão
      const usuarioPadrao = await Usuario.findOne({ where: { email: 'dudu0072812@gmail.com' } });
      if (!usuarioPadrao) {
        console.log('👤 Criando usuário padrão...');
        await createDefaultUser();
      }
      
      return;
    }

    console.log('🌱 Criando dados iniciais...');

    // Criar gabinete de exemplo
    const gabinete = await Gabinete.create({
      nome: 'Gabinete do Vereador João Silva',
      vereador_nome: 'João Silva',
      partido: 'PSDB',
      telefone: '(11) 99999-9999',
      email: 'joao.silva@camara.sp.gov.br',
      endereco: 'Rua das Flores, 123',
      municipio: 'São Paulo',
      estado: 'SP',
      cep: '01234-567',
      biografia: 'Vereador eleito com 15 anos de experiência em políticas públicas',
      cores: {
        primaria: '#1e40af',
        secundaria: '#3b82f6',
        accent: '#f59e0b'
      },
      redes_sociais: {
        facebook: 'https://facebook.com/joaosilva',
        instagram: 'https://instagram.com/joaosilva',
        twitter: 'https://twitter.com/joaosilva'
      },
      plano_governo: 'Foco em educação, saúde e infraestrutura urbana',
      metas: [
        'Aumentar investimento em educação em 20%',
        'Melhorar atendimento na saúde pública',
        'Reformar 50% das ruas do bairro'
      ]
    });

    // Criar usuário administrador
    const admin = await Usuario.create({
      nome: 'João Silva',
      email: 'admin@assessordigital.com',
      senha: '123456',
      nivel: 'administrador',
      telefone: '(11) 99999-9999',
      endereco: 'Rua das Flores, 123',
      bairro: 'Centro',
      partido: 'PSDB',
      cargo: 'Vereador',
      gabinete_id: gabinete.id
    });

    // Criar assessores
    const assessor1 = await Usuario.create({
      nome: 'Maria Santos',
      email: 'maria@assessordigital.com',
      senha: '123456',
      nivel: 'assessor',
      telefone: '(11) 88888-8888',
      endereco: 'Rua das Palmeiras, 456',
      bairro: 'Vila Nova',
      cargo: 'Assessora de Comunicação',
      gabinete_id: gabinete.id
    });

    const assessor2 = await Usuario.create({
      nome: 'Pedro Costa',
      email: 'pedro@assessordigital.com',
      senha: '123456',
      nivel: 'assessor',
      telefone: '(11) 77777-7777',
      endereco: 'Rua dos Lírios, 789',
      bairro: 'Jardim das Flores',
      cargo: 'Assessor Legislativo',
      gabinete_id: gabinete.id
    });

    // Criar cidadãos
    const cidadao1 = await Usuario.create({
      nome: 'Ana Oliveira',
      email: 'ana@email.com',
      senha: '123456',
      nivel: 'cidadao',
      telefone: '(11) 66666-6666',
      endereco: 'Rua das Rosas, 321',
      bairro: 'Centro',
      gabinete_id: gabinete.id
    });

    const cidadao2 = await Usuario.create({
      nome: 'Carlos Mendes',
      email: 'carlos@email.com',
      senha: '123456',
      nivel: 'cidadao',
      telefone: '(11) 55555-5555',
      endereco: 'Rua das Margaridas, 654',
      bairro: 'Vila Nova',
      gabinete_id: gabinete.id
    });

    // Criar projetos de exemplo
    await Projeto.create({
      titulo: 'Projeto de Lei para Melhoria da Iluminação Pública',
      tipo: 'projeto_lei',
      status: 'protocolado',
      descricao: 'Projeto que visa melhorar a iluminação pública em toda a cidade',
      conteudo: 'Artigo 1º - Fica estabelecido o programa de modernização da iluminação pública...',
      autor_id: admin.id,
      gabinete_id: gabinete.id,
      numero_protocolo: 'PL-001/2024',
      data_protocolo: new Date(),
      publico: true,
      tags: ['iluminação', 'infraestrutura', 'segurança']
    });

    await Projeto.create({
      titulo: 'Indicação para Construção de Creche no Bairro Centro',
      tipo: 'indicacao',
      status: 'elaboracao',
      descricao: 'Indicação para construção de creche municipal no bairro Centro',
      conteudo: 'Solicito ao Excelentíssimo Prefeito a construção de uma creche...',
      autor_id: admin.id,
      gabinete_id: gabinete.id,
      publico: true,
      tags: ['educação', 'creche', 'infraestrutura']
    });

    // Criar demandas de exemplo
    await Demanda.create({
      cidadao_id: cidadao1.id,
      gabinete_id: gabinete.id,
      assunto: 'Buraco na Rua das Rosas',
      descricao: 'Existe um buraco grande na Rua das Rosas que está causando problemas para os moradores',
      status: 'resolvido',
      prioridade: 'alta',
      categoria: 'Infraestrutura',
      endereco: 'Rua das Rosas, 321',
      bairro: 'Centro',
      latitude: -23.5505,
      longitude: -46.6333,
      telefone_contato: '(11) 66666-6666',
      email_contato: 'ana@email.com',
      responsavel_id: assessor2.id,
      data_resolucao: new Date(),
      observacoes_resolucao: 'Buraco foi tapado pela equipe da prefeitura',
      feedback_cidadao: 'Problema resolvido rapidamente, muito obrigada!',
      nota_satisfacao: 5
    });

    await Demanda.create({
      cidadao_id: cidadao2.id,
      gabinete_id: gabinete.id,
      assunto: 'Falta de Coleta de Lixo',
      descricao: 'O lixo não está sendo coletado regularmente no bairro Vila Nova',
      status: 'em_andamento',
      prioridade: 'media',
      categoria: 'Limpeza Pública',
      endereco: 'Rua das Margaridas, 654',
      bairro: 'Vila Nova',
      latitude: -23.5505,
      longitude: -46.6333,
      telefone_contato: '(11) 55555-5555',
      email_contato: 'carlos@email.com',
      responsavel_id: assessor1.id
    });

    // Criar reuniões de exemplo
    await Reuniao.create({
      titulo: 'Reunião com Secretário de Educação',
      descricao: 'Discussão sobre melhorias na educação municipal',
      data: new Date(Date.now() + 7 * 24 * 60 * 60 * 1000), // 7 dias no futuro
      hora_inicio: '14:00',
      hora_fim: '16:00',
      local: 'Câmara Municipal',
      endereco: 'Praça da Câmara, 1',
      tipo: 'oficial',
      status: 'agendada',
      organizador_id: admin.id,
      gabinete_id: gabinete.id,
      participantes: [
        { nome: 'Secretário de Educação', cargo: 'Secretário' },
        { nome: 'Maria Santos', cargo: 'Assessora' }
      ],
      pauta: '1. Apresentação do projeto de creche\n2. Discussão sobre recursos\n3. Cronograma de execução',
      publico: false
    });

    // Criar tarefas de exemplo
    await Tarefa.create({
      titulo: 'Elaborar parecer sobre projeto de lei',
      descricao: 'Analisar projeto de lei sobre iluminação pública e elaborar parecer',
      status: 'em_andamento',
      prioridade: 'alta',
      categoria: 'Legislativo',
      assessor_id: assessor2.id,
      criador_id: admin.id,
      gabinete_id: gabinete.id,
      prazo: new Date(Date.now() + 3 * 24 * 60 * 60 * 1000), // 3 dias no futuro
      progresso: 60,
      tags: ['parecer', 'projeto', 'iluminação']
    });

    await Tarefa.create({
      titulo: 'Atualizar redes sociais',
      descricao: 'Postar sobre as atividades da semana nas redes sociais',
      status: 'pendente',
      prioridade: 'media',
      categoria: 'Comunicação',
      assessor_id: assessor1.id,
      criador_id: admin.id,
      gabinete_id: gabinete.id,
      prazo: new Date(Date.now() + 1 * 24 * 60 * 60 * 1000), // 1 dia no futuro
      progresso: 0,
      tags: ['redes sociais', 'comunicação']
    });

    // Criar notícias de exemplo
    await Noticia.create({
      titulo: 'Vereador apresenta projeto para melhorar iluminação pública',
      conteudo: 'O vereador João Silva apresentou na Câmara Municipal um projeto de lei que visa melhorar a iluminação pública em toda a cidade. O projeto prevê a substituição de lâmpadas antigas por LED, que são mais eficientes e econômicas...',
      resumo: 'Projeto de lei para modernização da iluminação pública com tecnologia LED',
      categoria: 'Legislativo',
      autor_id: assessor1.id,
      gabinete_id: gabinete.id,
      status: 'publicado',
      publico: true,
      data_publicacao: new Date(),
      tags: ['iluminação', 'projeto de lei', 'infraestrutura'],
      visualizacoes: 150,
      curtidas: 25
    });

    // Criar indicadores de exemplo
    const hoje = new Date();
    const inicioMes = new Date(hoje.getFullYear(), hoje.getMonth(), 1);
    
    await Indicador.create({
      tipo: 'projetos_apresentados',
      valor: 2,
      periodo: 'mensal',
      data_referencia: inicioMes,
      gabinete_id: gabinete.id
    });

    await Indicador.create({
      tipo: 'demandas_atendidas',
      valor: 1,
      periodo: 'mensal',
      data_referencia: inicioMes,
      gabinete_id: gabinete.id
    });

    await Indicador.create({
      tipo: 'reunioes_realizadas',
      valor: 1,
      periodo: 'mensal',
      data_referencia: inicioMes,
      gabinete_id: gabinete.id
    });

    // Criar mensagens de chat de exemplo
    await Chat.create({
      remetente_id: admin.id,
      destinatario_id: assessor1.id,
      gabinete_id: gabinete.id,
      mensagem: 'Olá Maria, como está o andamento da atualização das redes sociais?',
      tipo: 'texto',
      lida: false
    });

    await Chat.create({
      remetente_id: assessor1.id,
      destinatario_id: admin.id,
      gabinete_id: gabinete.id,
      mensagem: 'Olá João! Está tudo certo, vou postar hoje ainda.',
      tipo: 'texto',
      lida: true,
      data_leitura: new Date()
    });

    console.log('✅ Dados iniciais criados com sucesso!');
    console.log('👤 Usuários criados:');
    console.log('   - Admin: admin@assessordigital.com (senha: 123456)');
    console.log('   - Assessor 1: maria@assessordigital.com (senha: 123456)');
    console.log('   - Assessor 2: pedro@assessordigital.com (senha: 123456)');
    console.log('   - Cidadão 1: ana@email.com (senha: 123456)');
    console.log('   - Cidadão 2: carlos@email.com (senha: 123456)');

  } catch (error) {
    console.error('❌ Erro ao criar dados iniciais:', error);
    throw error;
  }
};

// Função para criar usuário padrão
const createDefaultUser = async () => {
  try {
    // Buscar ou criar um cliente padrão
    let cliente = await Cliente.findOne();
    if (!cliente) {
      cliente = await Cliente.create({
        nome: 'Cliente Padrão',
        email: 'cliente@exemplo.com',
        telefone: '(00) 00000-0000',
        ativo: true
      });
    }

    // Buscar ou criar um gabinete padrão
    let gabinete = await Gabinete.findOne();
    if (!gabinete) {
      gabinete = await Gabinete.create({
        nome: 'Gabinete Padrão',
        vereador_nome: 'Vereador',
        partido: 'Independente',
        telefone: '(00) 00000-0000',
        email: 'gabinete@exemplo.com',
        municipio: 'São Paulo',
        estado: 'SP'
      });
    }

    // Criar usuário padrão
    const usuarioPadrao = await Usuario.create({
      nome: 'Usuário Padrão',
      email: 'dudu0072812@gmail.com',
      senha: '123456',
      nivel: 'administrador',
      telefone: '(00) 00000-0000',
      gabinete_id: gabinete.id,
      cliente_id: cliente.id,
      ativo: true
    });

    console.log('✅ Usuário padrão criado com sucesso!');
    console.log('   Email: dudu0072812@gmail.com');
    console.log('   Senha: 123456');
    
    return usuarioPadrao;
  } catch (error) {
    console.error('❌ Erro ao criar usuário padrão:', error);
    throw error;
  }
};

// Executar se chamado diretamente
if (require.main === module) {
  initDatabase().then(() => {
    console.log('🎉 Inicialização concluída!');
    process.exit(0);
  }).catch(error => {
    console.error('❌ Erro na inicialização:', error);
    process.exit(1);
  });
}

module.exports = { initDatabase, createInitialData };


