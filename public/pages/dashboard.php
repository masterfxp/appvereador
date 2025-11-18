<div class="main-content">
        <!-- Page Header -->
        <div class="mb-8">
            <h1 class="page-title">Dashboard Executivo</h1>
            <p class="mt-2 text-gray-600">Visão completa das atividades e indicadores do gabinete</p>
        </div>

        <!-- Indicadores Principais -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <div class="bg-gradient-to-r from-blue-500 to-blue-600 rounded-lg shadow-lg p-6 text-white card-hover">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-blue-100 text-sm font-medium">Projetos Apresentados</p>
                        <p class="text-3xl font-bold" id="projetosApresentados">0</p>
                        <p class="text-blue-100 text-xs mt-1">Este mês: <span id="projetosMes">0</span></p>
                    </div>
                    <div class="p-3 rounded-full bg-blue-400 bg-opacity-30">
                        <i class="fas fa-file-alt text-2xl"></i>
                    </div>
                </div>
            </div>
            
            <div class="bg-gradient-to-r from-green-500 to-green-600 rounded-lg shadow-lg p-6 text-white card-hover">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-green-100 text-sm font-medium">Demandas Atendidas</p>
                        <p class="text-3xl font-bold" id="demandasAtendidas">0</p>
                        <p class="text-green-100 text-xs mt-1">Taxa: <span id="taxaAtendimento">0%</span></p>
                    </div>
                    <div class="p-3 rounded-full bg-green-400 bg-opacity-30">
                        <i class="fas fa-handshake text-2xl"></i>
                    </div>
                </div>
            </div>
            
            <div class="bg-gradient-to-r from-purple-500 to-purple-600 rounded-lg shadow-lg p-6 text-white card-hover">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-purple-100 text-sm font-medium">Reuniões Realizadas</p>
                        <p class="text-3xl font-bold" id="reunioesRealizadas">0</p>
                        <p class="text-purple-100 text-xs mt-1">Esta semana: <span id="reunioesSemana">0</span></p>
                    </div>
                    <div class="p-3 rounded-full bg-purple-400 bg-opacity-30">
                        <i class="fas fa-users text-2xl"></i>
                    </div>
                </div>
            </div>
            
            <div class="bg-gradient-to-r from-orange-500 to-orange-600 rounded-lg shadow-lg p-6 text-white card-hover">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-orange-100 text-sm font-medium">Cidadãos Atendidos</p>
                        <p class="text-3xl font-bold" id="cidadaosAtendidos">0</p>
                        <p class="text-orange-100 text-xs mt-1">Novos: <span id="novosCidadaos">0</span></p>
                    </div>
                    <div class="p-3 rounded-full bg-orange-400 bg-opacity-30">
                        <i class="fas fa-user-friends text-2xl"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Indicadores Secundários -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <div class="card">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-indigo-100 text-indigo-600">
                        <i class="fas fa-bullhorn text-xl"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Indicações</p>
                        <p class="text-2xl font-semibold text-gray-900" id="indicacoes">0</p>
                    </div>
                </div>
            </div>
            
            <div class="card">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-pink-100 text-pink-600">
                        <i class="fas fa-tasks text-xl"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Tarefas Pendentes</p>
                        <p class="text-2xl font-semibold text-gray-900" id="tarefasPendentes">0</p>
                    </div>
                </div>
            </div>
            
            <div class="card">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-teal-100 text-teal-600">
                        <i class="fas fa-calendar-check text-xl"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Eventos Hoje</p>
                        <p class="text-2xl font-semibold text-gray-900" id="eventosHoje">0</p>
                    </div>
                </div>
            </div>
            
            <div class="card">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-red-100 text-red-600">
                        <i class="fas fa-exclamation-triangle text-xl"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Urgências</p>
                        <p class="text-2xl font-semibold text-gray-900" id="urgencias">0</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Gráficos e Análises -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
            <!-- Gráfico de Projetos -->
            <div class="card">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-medium text-gray-900">Projetos por Status</h3>
                    <div class="flex space-x-2">
                        <button class="px-3 py-1 text-xs bg-blue-100 text-blue-800 rounded-full">Este mês</button>
                        <button class="px-3 py-1 text-xs bg-gray-100 text-gray-600 rounded-full">Últimos 6 meses</button>
                    </div>
                </div>
                <div style="height: 300px; position: relative;">
                    <canvas id="projetosChart"></canvas>
                </div>
            </div>

            <!-- Gráfico de Demandas -->
            <div class="card">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-medium text-gray-900">Demandas por Bairro</h3>
                    <div class="flex space-x-2">
                        <button class="px-3 py-1 text-xs bg-green-100 text-green-800 rounded-full">Top 5</button>
                    </div>
                </div>
                <div style="height: 300px; position: relative;">
                    <canvas id="demandasChart"></canvas>
                </div>
            </div>
        </div>

        <!-- Atividades Recentes e Próximas -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
            <!-- Atividades Recentes -->
            <div class="card">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900">Atividades Recentes</h3>
                </div>
                <div class="divide-y divide-gray-200" id="atividadesRecentes">
                    <!-- Atividades serão carregadas aqui -->
                </div>
            </div>

            <!-- Próximas Atividades -->
            <div class="card">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900">Próximas Atividades</h3>
                </div>
                <div class="divide-y divide-gray-200" id="proximasAtividades">
                    <!-- Próximas atividades serão carregadas aqui -->
                </div>
            </div>
        </div>

        <!-- Notificações Inteligentes -->
        <div class="card">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-medium text-gray-900">Notificações Inteligentes</h3>
            </div>
            <div class="p-6">
                <div class="space-y-4" id="notificacoesInteligentes">
                    <!-- Notificações serão carregadas aqui -->
                </div>
            </div>
        </div>
    </div>
    <script>
        (function() {
            'use strict';
            const API_BASE = 'http://localhost:3000/api';
            let currentUser = null;

            // Carregar dados do usuário
            async function loadUserInfo() {
                try {
                    const user = JSON.parse(localStorage.getItem('user'));
                    if (user) {
                        currentUser = user;
                        
                        // Atualizar nome do usuário
                        const userNameEl = document.getElementById('userName');
                        const userLevelEl = document.getElementById('userLevel');
                        const userInitialsEl = document.getElementById('userInitials');
                        
                        if (userNameEl) userNameEl.textContent = user.nome;
                        const nivelTitle = user.nivel === 'vereador' ? 'Vereador' : 
                                           user.nivel === 'assessor' ? 'Assessor' : 
                                           user.nivel === 'administrador' ? 'Administrador' : user.nivel;
                        if (userLevelEl) userLevelEl.textContent = nivelTitle;
                        
                        // Gerar iniciais (primeiro e último nome)
                        const partesNome = user.nome.split(' ');
                        let iniciais = '';
                        if (partesNome.length >= 2) {
                            iniciais = partesNome[0].charAt(0).toUpperCase() + partesNome[partesNome.length - 1].charAt(0).toUpperCase();
                        } else {
                            iniciais = user.nome.substring(0, 2).toUpperCase();
                        }
                        if (userInitialsEl) userInitialsEl.textContent = iniciais;
                        
                        // Mostrar menu admin se for administrador
                        if (user.nivel === 'administrador') {
                            const adminMenu = document.getElementById('adminMenu');
                            if (adminMenu) adminMenu.classList.remove('hidden');
                        }
                    } else {
                        console.log('⚠️ Nenhum usuário encontrado no localStorage. Faça login primeiro.');
                    }
                } catch (error) {
                    console.error('Erro ao carregar dados do usuário:', error);
                }
            }

        // Carregar indicadores do dashboard
        async function loadDashboardData() {
            try {
                const token = localStorage.getItem('token');
                if (!token) {
                    console.error('Token não encontrado');
                    return;
                }

                // Carregar dados da API em paralelo
                const [projetosRes, demandasRes, reunioesRes, tarefasRes, noticiasRes] = await Promise.allSettled([
                    fetch(`${API_BASE}/projetos`, {
                        headers: { 'Authorization': `Bearer ${token}` }
                    }),
                    fetch(`${API_BASE}/demandas`, {
                        headers: { 'Authorization': `Bearer ${token}` }
                    }),
                    fetch(`${API_BASE}/reunioes`, {
                        headers: { 'Authorization': `Bearer ${token}` }
                    }),
                    fetch(`${API_BASE}/tarefas`, {
                        headers: { 'Authorization': `Bearer ${token}` }
                    }),
                    fetch(`${API_BASE}/noticias`, {
                        headers: { 'Authorization': `Bearer ${token}` }
                    })
                ]);

                // Processar respostas
                const projetos = projetosRes.status === 'fulfilled' && projetosRes.value.ok 
                    ? await projetosRes.value.json().then(data => data.projetos || data || [])
                    : [];
                
                const demandas = demandasRes.status === 'fulfilled' && demandasRes.value.ok 
                    ? await demandasRes.value.json().then(data => data.demandas || data || [])
                    : [];
                
                const reunioes = reunioesRes.status === 'fulfilled' && reunioesRes.value.ok 
                    ? await reunioesRes.value.json().then(data => data.reunioes || data || [])
                    : [];
                
                const tarefas = tarefasRes.status === 'fulfilled' && tarefasRes.value.ok 
                    ? await tarefasRes.value.json().then(data => data.tarefas || data || [])
                    : [];
                
                const noticias = noticiasRes.status === 'fulfilled' && noticiasRes.value.ok 
                    ? await noticiasRes.value.json().then(data => data.noticias || data || [])
                    : [];

                // Atualizar indicadores com dados reais
                updateIndicators(projetos, demandas, reunioes, tarefas, noticias);
                createCharts(projetos, demandas);
                loadRecentActivities(projetos, demandas, reunioes, tarefas);
                loadUpcomingActivities(reunioes, tarefas);
                loadIntelligentNotifications(projetos, demandas, reunioes, tarefas);

            } catch (error) {
                console.error('Erro ao carregar dados do dashboard:', error);
                // Em caso de erro, usar dados vazios para não quebrar a interface
                updateIndicators([], [], [], [], []);
            }
        }

        // Atualizar indicadores
        function updateIndicators(projetos, demandas, reunioes, tarefas, noticias) {
            // Projetos apresentados
            const projetosAprovados = projetos.filter(p => p.status === 'aprovado').length;
            const projetosMes = projetos.filter(p => {
                const data = new Date(p.created_at);
                const agora = new Date();
                return data.getMonth() === agora.getMonth() && data.getFullYear() === agora.getFullYear();
            }).length;

            const projetosApresentadosEl = document.getElementById('projetosApresentados');
            const projetosMesEl = document.getElementById('projetosMes');
            if (projetosApresentadosEl) projetosApresentadosEl.textContent = projetosAprovados;
            if (projetosMesEl) projetosMesEl.textContent = projetosMes;

            // Demandas atendidas
            const demandasResolvidas = demandas.filter(d => d.status === 'resolvido').length;
            const taxaAtendimento = demandas.length > 0 ? Math.round((demandasResolvidas / demandas.length) * 100) : 0;

            const demandasAtendidasEl = document.getElementById('demandasAtendidas');
            const taxaAtendimentoEl = document.getElementById('taxaAtendimento');
            if (demandasAtendidasEl) demandasAtendidasEl.textContent = demandasResolvidas;
            if (taxaAtendimentoEl) taxaAtendimentoEl.textContent = taxaAtendimento + '%';

            // Reuniões realizadas
            const reunioesRealizadas = reunioes.filter(r => r.status === 'realizada').length;
            const reunioesSemana = reunioes.filter(r => {
                const data = new Date(r.data);
                const agora = new Date();
                const diffTime = Math.abs(agora - data);
                const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));
                return diffDays <= 7;
            }).length;

            const reunioesRealizadasEl = document.getElementById('reunioesRealizadas');
            const reunioesSemanaEl = document.getElementById('reunioesSemana');
            if (reunioesRealizadasEl) reunioesRealizadasEl.textContent = reunioesRealizadas;
            if (reunioesSemanaEl) reunioesSemanaEl.textContent = reunioesSemana;

            // Cidadãos atendidos (simulado)
            const cidadaosAtendidos = demandasResolvidas * 2; // Estimativa
            const novosCidadaos = demandas.filter(d => {
                const data = new Date(d.created_at);
                const agora = new Date();
                const diffTime = Math.abs(agora - data);
                const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));
                return diffDays <= 30;
            }).length;

            const cidadaosAtendidosEl = document.getElementById('cidadaosAtendidos');
            const novosCidadaosEl = document.getElementById('novosCidadaos');
            if (cidadaosAtendidosEl) cidadaosAtendidosEl.textContent = cidadaosAtendidos;
            if (novosCidadaosEl) novosCidadaosEl.textContent = novosCidadaos;

            // Indicadores secundários
            const indicacoesEl = document.getElementById('indicacoes');
            const tarefasPendentesEl = document.getElementById('tarefasPendentes');
            const eventosHojeEl = document.getElementById('eventosHoje');
            const urgenciasEl = document.getElementById('urgencias');
            
            if (indicacoesEl) indicacoesEl.textContent = projetos.filter(p => p.tipo === 'indicacao').length;
            if (tarefasPendentesEl) tarefasPendentesEl.textContent = tarefas.filter(t => t.status === 'pendente').length;
            
            const hoje = new Date().toISOString().split('T')[0];
            if (eventosHojeEl) eventosHojeEl.textContent = reunioes.filter(r => r.data === hoje).length;
            if (urgenciasEl) urgenciasEl.textContent = demandas.filter(d => d.prioridade === 'alta').length;
        }

        // Variáveis para armazenar instâncias dos gráficos
        let projetosChart = null;
        let demandasChart = null;

        // Criar gráficos
        function createCharts(projetos, demandas) {
            try {
                // Destruir gráficos existentes se existirem
                if (projetosChart) {
                    projetosChart.destroy();
                }
                if (demandasChart) {
                    demandasChart.destroy();
                }

                // Aguardar um pouco para garantir que o DOM está pronto
                setTimeout(() => {
                    // Gráfico de Projetos por Status
                    const projetosCtx = document.getElementById('projetosChart');
                    if (projetosCtx && typeof Chart !== 'undefined') {
                        const projetosPorStatus = {
                            'em_elaboracao': projetos.filter(p => p.status === 'em_elaboracao').length,
                            'protocolado': projetos.filter(p => p.status === 'protocolado').length,
                            'em_votacao': projetos.filter(p => p.status === 'em_votacao').length,
                            'aprovado': projetos.filter(p => p.status === 'aprovado').length,
                            'rejeitado': projetos.filter(p => p.status === 'rejeitado').length
                        };

                        projetosChart = new Chart(projetosCtx, {
                            type: 'doughnut',
                            data: {
                                labels: ['Em Elaboração', 'Protocolado', 'Em Votação', 'Aprovado', 'Rejeitado'],
                                datasets: [{
                                    data: Object.values(projetosPorStatus),
                                    backgroundColor: ['#F59E0B', '#3B82F6', '#8B5CF6', '#10B981', '#EF4444'],
                                    borderWidth: 0
                                }]
                            },
                            options: {
                                responsive: true,
                                maintainAspectRatio: false,
                                plugins: {
                                    legend: {
                                        position: 'bottom',
                                        labels: {
                                            padding: 20,
                                            usePointStyle: true
                                        }
                                    }
                                }
                            }
                        });
                    }

                    // Gráfico de Demandas por Bairro
                    const demandasCtx = document.getElementById('demandasChart');
                    if (demandasCtx && typeof Chart !== 'undefined') {
                        const bairros = ['Centro', 'Vila Nova', 'Jardim das Flores', 'São José', 'Santa Maria'];
                        const demandasPorBairro = [5, 8, 3, 12, 7]; // Dados fixos

                        demandasChart = new Chart(demandasCtx, {
                            type: 'bar',
                            data: {
                                labels: bairros,
                                datasets: [{
                                    label: 'Demandas',
                                    data: demandasPorBairro,
                                    backgroundColor: '#10B981',
                                    borderRadius: 4,
                                    borderSkipped: false,
                                    maxBarThickness: 50
                                }]
                            },
                            options: {
                                responsive: true,
                                maintainAspectRatio: false,
                                plugins: {
                                    legend: {
                                        display: false
                                    }
                                },
                                scales: {
                                    y: {
                                        beginAtZero: true,
                                        max: 15,
                                        ticks: {
                                            stepSize: 5
                                        },
                                        grid: {
                                            display: true
                                        }
                                    },
                                    x: {
                                        ticks: {
                                            maxRotation: 45
                                        },
                                        grid: {
                                            display: false
                                        }
                                    }
                                },
                                layout: {
                                    padding: {
                                        top: 10,
                                        bottom: 10,
                                        left: 10,
                                        right: 10
                                    }
                                }
                            }
                        });
                    }
                }, 100);
            } catch (error) {
                console.error('Erro ao criar gráficos:', error);
            }
        }

        // Carregar atividades recentes
        function loadRecentActivities(projetos, demandas, reunioes, tarefas) {
            console.log('📋 Carregando atividades recentes...');
            const container = document.getElementById('atividadesRecentes');
            if (!container) {
                console.warn('⚠️ Container atividadesRecentes não encontrado');
                return;
            }
            
            const atividades = [];

            // Adicionar projetos recentes
            projetos.slice(0, 3).forEach(projeto => {
                atividades.push({
                    tipo: 'projeto',
                    titulo: projeto.titulo || 'Sem título',
                    data: new Date(projeto.created_at || projeto.data_criacao),
                    status: projeto.status,
                    icon: 'fas fa-file-alt',
                    color: 'text-blue-500'
                });
            });

            // Adicionar demandas recentes
            demandas.slice(0, 2).forEach(demanda => {
                atividades.push({
                    tipo: 'demanda',
                    titulo: demanda.titulo || demanda.assunto || 'Sem título',
                    data: new Date(demanda.created_at),
                    status: demanda.status,
                    icon: 'fas fa-comments',
                    color: 'text-green-500'
                });
            });

            // Ordenar por data
            atividades.sort((a, b) => b.data - a.data);

            console.log('📋 Atividades encontradas:', atividades.length);

            // Renderizar
            if (atividades.length === 0) {
                container.innerHTML = '<div class="px-6 py-4 text-center text-gray-500">Nenhuma atividade recente</div>';
                console.log('📋 Nenhuma atividade para exibir');
            } else {
                container.innerHTML = atividades.slice(0, 5).map(atividade => `
                    <div class="px-6 py-4 flex items-center">
                        <div class="flex-shrink-0">
                            <i class="${atividade.icon} ${atividade.color}"></i>
                    </div>
                    <div class="ml-4 flex-1">
                        <p class="text-sm font-medium text-gray-900">${atividade.titulo}</p>
                        <p class="text-sm text-gray-500">${atividade.data.toLocaleDateString('pt-BR')}</p>
                    </div>
                    <div class="ml-4">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                            ${atividade.status}
                        </span>
                    </div>
                </div>
            `).join('');
        }

        // Carregar próximas atividades
        function loadUpcomingActivities(reunioes, tarefas) {
            const container = document.getElementById('proximasAtividades');
            if (!container) return;
            
            const proximas = [];

            // Adicionar reuniões próximas
            const hoje = new Date();
            const proximos7Dias = new Date(hoje.getTime() + 7 * 24 * 60 * 60 * 1000);

            reunioes.forEach(reuniao => {
                const dataReuniao = new Date(reuniao.data);
                if (dataReuniao >= hoje && dataReuniao <= proximos7Dias) {
                    proximas.push({
                        tipo: 'reuniao',
                        titulo: reuniao.titulo,
                        data: dataReuniao,
                        hora: reuniao.hora,
                        icon: 'fas fa-calendar',
                        color: 'text-purple-500'
                    });
                }
            });

            // Adicionar tarefas próximas do vencimento
            tarefas.forEach(tarefa => {
                if (tarefa.data_vencimento) {
                    const dataVencimento = new Date(tarefa.data_vencimento);
                    if (dataVencimento >= hoje && dataVencimento <= proximos7Dias) {
                        proximas.push({
                            tipo: 'tarefa',
                            titulo: tarefa.titulo,
                            data: dataVencimento,
                            hora: null,
                            icon: 'fas fa-tasks',
                            color: 'text-orange-500'
                        });
                    }
                }
            });

            // Ordenar por data
            proximas.sort((a, b) => a.data - b.data);

            // Renderizar
            container.innerHTML = proximas.slice(0, 5).map(atividade => `
                <div class="px-6 py-4 flex items-center">
                    <div class="flex-shrink-0">
                        <i class="${atividade.icon} ${atividade.color}"></i>
                    </div>
                    <div class="ml-4 flex-1">
                        <p class="text-sm font-medium text-gray-900">${atividade.titulo}</p>
                        <p class="text-sm text-gray-500">
                            ${atividade.data.toLocaleDateString('pt-BR')}
                            ${atividade.hora ? ` às ${atividade.hora}` : ''}
                        </p>
                    </div>
                </div>
            `).join('');
        }

        // Carregar notificações inteligentes
        function loadIntelligentNotifications(projetos, demandas, reunioes, tarefas) {
            const container = document.getElementById('notificacoesInteligentes');
            if (!container) return;
            
            // Notificações simples e limitadas
            const notificacoes = [
                {
                    tipo: 'info',
                    titulo: 'Sistema Funcionando',
                    descricao: 'Todas as funcionalidades estão operacionais',
                    icon: 'fas fa-check-circle',
                    action: 'Ver Status'
                },
                {
                    tipo: 'warning',
                    titulo: 'Tarefas Pendentes',
                    descricao: 'Você tem ' + tarefas.filter(t => t.status === 'pendente').length + ' tarefa(s) pendente(s)',
                    icon: 'fas fa-clock',
                    action: 'Ver Tarefas'
                }
            ];

            // Renderizar notificações (limitado a 3)
            container.innerHTML = notificacoes.slice(0, 3).map(notif => `
                <div class="flex items-start p-4 bg-${notif.tipo === 'error' ? 'red' : notif.tipo === 'warning' ? 'yellow' : 'blue'}-50 rounded-lg">
                    <div class="flex-shrink-0">
                        <i class="${notif.icon} text-${notif.tipo === 'error' ? 'red' : notif.tipo === 'warning' ? 'yellow' : 'blue'}-500"></i>
                    </div>
                    <div class="ml-3 flex-1">
                        <h4 class="text-sm font-medium text-gray-900">${notif.titulo}</h4>
                        <p class="text-sm text-gray-600">${notif.descricao}</p>
                    </div>
                    <div class="ml-4">
                        <button class="text-sm font-medium text-${notif.tipo === 'error' ? 'red' : notif.tipo === 'warning' ? 'yellow' : 'blue'}-600 hover:text-${notif.tipo === 'error' ? 'red' : notif.tipo === 'warning' ? 'yellow' : 'blue'}-800">
                            ${notif.action}
                        </button>
                    </div>
                </div>
            `).join('');
        }

            // Inicializar dashboard
            function initDashboard() {
                console.log('📋 Inicializando dashboard...');
                // Verificar se já foi inicializado
                if (window.dashboardInitialized) {
                    console.log('⚠️ Dashboard já foi inicializado, pulando...');
                    return;
                }
                window.dashboardInitialized = true;
                
                console.log('👤 Carregando informações do usuário...');
                loadUserInfo();
                console.log('📊 Carregando dados do dashboard...');
                loadDashboardData();
            }

            // Executar quando o DOM estiver pronto
            if (typeof document !== 'undefined') {
                if (document.readyState === 'loading') {
                    document.addEventListener('DOMContentLoaded', initDashboard);
                } else {
                    // DOM já está pronto, executar imediatamente
                    initDashboard();
                }

                // Também executar após um pequeno delay para garantir que o DOM está completamente renderizado
                setTimeout(initDashboard, 500);
            }
        })();
    </script>