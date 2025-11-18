<div class="main-content">
        <!-- Page Header -->
        <div class="page-header">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="page-title">Gestão de Reuniões</h1>
                    <p class="mt-2 text-gray-600">Gerencie reuniões e eventos do gabinete</p>
                </div>
                <button id="btnNovaReuniao" class="btn btn-primary">
                    <i class="fas fa-plus"></i>
                    Nova Reunião
                </button>
            </div>
        </div>

        <!-- Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
            <div class="bg-white rounded-xl shadow-sm p-6 card-hover">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-blue-100">
                        <i class="fas fa-calendar-check text-blue-600 text-xl"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Agendadas</p>
                        <p class="text-2xl font-bold text-gray-900" id="statsAgendadas">0</p>
                    </div>
                </div>
            </div>
            <div class="bg-white rounded-xl shadow-sm p-6 card-hover">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-green-100">
                        <i class="fas fa-check-circle text-green-600 text-xl"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Realizadas</p>
                        <p class="text-2xl font-bold text-gray-900" id="statsRealizadas">0</p>
                    </div>
                </div>
            </div>
            <div class="bg-white rounded-xl shadow-sm p-6 card-hover">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-red-100">
                        <i class="fas fa-times-circle text-red-600 text-xl"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Canceladas</p>
                        <p class="text-2xl font-bold text-gray-900" id="statsCanceladas">0</p>
                    </div>
                </div>
            </div>
            <div class="bg-white rounded-xl shadow-sm p-6 card-hover">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-yellow-100">
                        <i class="fas fa-clock text-yellow-600 text-xl"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Hoje</p>
                        <p class="text-2xl font-bold text-gray-900" id="statsHoje">0</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Calendar View -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Upcoming Meetings -->
            <div class="lg:col-span-2">
                <div class="bg-white rounded-xl shadow-sm p-6">
                    <div class="flex items-center justify-between mb-6">
                        <h2 class="text-xl font-semibold text-gray-900">Próximas Reuniões</h2>
                        <div class="flex items-center space-x-2">
                            <button onclick="toggleView('calendar')" class="px-3 py-1 text-sm bg-blue-100 text-blue-700 rounded-lg hover:bg-blue-200 transition-colors">
                                <i class="fas fa-calendar mr-1"></i>
                                Calendário
                            </button>
                            <button onclick="toggleView('list')" class="px-3 py-1 text-sm text-gray-600 hover:bg-gray-100 rounded-lg transition-colors">
                                <i class="fas fa-list mr-1"></i>
                                Lista
                            </button>
                        </div>
                    </div>
                    
                    <!-- Calendar Cards -->
                    <div id="calendarView" class="space-y-4">
                        <!-- Calendar cards will be loaded here -->
                    </div>
                    
                    <!-- List View -->
                    <div id="listView" class="hidden">
                        <div class="space-y-4">
                            <!-- List items will be loaded here -->
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="space-y-6">
                <!-- Filters -->
                <div class="bg-white rounded-xl shadow-sm p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Filtros</h3>
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                            <select id="statusFilter" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                                <option value="">Todos</option>
                                <option value="agendada">Agendada</option>
                                <option value="realizada">Realizada</option>
                                <option value="cancelada">Cancelada</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Tipo</label>
                            <select id="tipoFilter" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                                <option value="">Todos</option>
                                <option value="oficial">Oficial</option>
                                <option value="reuniao_gabinete">Reunião de Gabinete</option>
                                <option value="evento_publico">Evento Público</option>
                                <option value="visita">Visita</option>
                                <option value="outro">Outro</option>
                            </select>
                        </div>
                        <button onclick="clearFilters()" class="w-full text-sm text-gray-500 hover:text-gray-700 flex items-center justify-center">
                            <i class="fas fa-times mr-1"></i>
                            Limpar Filtros
                        </button>
                    </div>
                </div>

                <!-- Past Meetings -->
                <div class="bg-white rounded-xl shadow-sm p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Reuniões Passadas</h3>
                    <div id="pastMeetings" class="space-y-3 max-h-64 overflow-y-auto">
                        <!-- Past meetings will be loaded here -->
                    </div>
                </div>
            </div>
        </div>

        <!-- Loading State -->
        <div id="loadingState" class="text-center py-12">
            <div class="inline-block animate-spin rounded-full h-8 w-8 border-b-2 border-blue-600"></div>
            <p class="mt-2 text-gray-600">Carregando reuniões...</p>
        </div>

        <!-- Empty State -->
        <div id="emptyState" class="text-center py-12 hidden">
            <i class="fas fa-calendar-alt text-6xl text-gray-300 mb-4"></i>
            <h3 class="text-lg font-medium text-gray-900 mb-2">Nenhuma reunião encontrada</h3>
            <p class="text-gray-600 mb-6">Comece agendando sua primeira reunião.</p>
            <button id="btnNovaReuniao2" class="btn btn-primary">
                <i class="fas fa-plus mr-2"></i>
                Nova Reunião
            </button>
        </div>
    </div>

    <!-- Meeting Modal -->
    <div id="reuniaoModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
        <div class="relative top-20 mx-auto p-5 border w-11/12 md:w-3/4 lg:w-1/2 shadow-lg rounded-md bg-white">
            <div class="mt-3">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-medium text-gray-900" id="modalTitle">Nova Reunião</h3>
                    <button onclick="closeReuniaoModal()" class="text-gray-400 hover:text-gray-600">
                        <i class="fas fa-times text-xl"></i>
                    </button>
                </div>
                <form id="reuniaoForm" class="space-y-4">
                    <input type="hidden" id="reuniaoId">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Título *</label>
                            <input type="text" id="titulo" required class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Tipo *</label>
                            <select id="tipo" required class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                                <option value="oficial">Oficial</option>
                                <option value="reuniao_gabinete">Reunião de Gabinete</option>
                                <option value="evento_publico">Evento Público</option>
                                <option value="visita">Visita</option>
                                <option value="outro">Outro</option>
                            </select>
                        </div>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Data *</label>
                            <input type="date" id="data" required class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Status *</label>
                            <select id="status" required class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                                <option value="agendada">Agendada</option>
                                <option value="realizada">Realizada</option>
                                <option value="cancelada">Cancelada</option>
                            </select>
                        </div>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Hora Início</label>
                            <input type="time" id="horaInicio" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Hora Fim</label>
                            <input type="time" id="horaFim" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Local</label>
                        <input type="text" id="local" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Endereço</label>
                        <input type="text" id="endereco" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Descrição</label>
                        <textarea id="descricao" rows="3" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"></textarea>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Pauta</label>
                        <textarea id="pauta" rows="4" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"></textarea>
                    </div>
                    <div class="flex justify-end space-x-3 pt-4">
                        <button type="button" onclick="closeReuniaoModal()" class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-500">
                            Cancelar
                        </button>
                        <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <i class="fas fa-save mr-2"></i>
                            Salvar
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <?php
    // Incluir scripts JavaScript
    ?>
    <script>
        // Definir funções no escopo global IMEDIATAMENTE para que estejam disponíveis quando os botões forem clicados
        window.openReuniaoModal = function(reuniao = null) {
            const modal = document.getElementById('reuniaoModal');
            const form = document.getElementById('reuniaoForm');
            const title = document.getElementById('modalTitle');
            
            if (!modal || !form || !title) {
                console.error('⚠️ Modal, formulário ou título não encontrado');
                return;
            }

            if (reuniao) {
                title.textContent = 'Editar Reunião';
                document.getElementById('reuniaoId').value = reuniao.id;
                document.getElementById('titulo').value = reuniao.titulo || '';
                document.getElementById('tipo').value = reuniao.tipo || '';
                document.getElementById('data').value = reuniao.data || '';
                document.getElementById('status').value = reuniao.status || 'agendada';
                document.getElementById('horaInicio').value = reuniao.hora_inicio || '';
                document.getElementById('horaFim').value = reuniao.hora_fim || '';
                document.getElementById('local').value = reuniao.local || '';
                document.getElementById('endereco').value = reuniao.endereco || '';
                document.getElementById('descricao').value = reuniao.descricao || '';
                document.getElementById('pauta').value = reuniao.pauta || '';
            } else {
                title.textContent = 'Nova Reunião';
                form.reset();
                document.getElementById('reuniaoId').value = '';
            }

            // Remover classe hidden e garantir display com !important
            modal.classList.remove('hidden');
            modal.style.setProperty('display', 'flex', 'important');
            modal.style.setProperty('align-items', 'center', 'important');
            modal.style.setProperty('justify-content', 'center', 'important');
            modal.style.setProperty('z-index', '9999', 'important');
            console.log('✅ Modal de reunião aberto');
        };

        window.closeReuniaoModal = function() {
            const modal = document.getElementById('reuniaoModal');
            if (modal) {
                modal.classList.add('hidden');
                modal.style.display = 'none';
            }
        };
        
        const API_BASE = 'http://localhost:3000/api';
        let reunioes = [];
        let filteredReunioes = [];
        let currentView = 'calendar';

        // Load meetings on page load
        document.addEventListener('DOMContentLoaded', function() {
            loadUserInfo();
            loadReunioes();
            setupEventListeners();
        });

        // Load user information
        async function loadUserInfo() {
            try {
                const user = JSON.parse(localStorage.getItem('user'));
                if (user) {
                    const userNameEl = document.getElementById('userName');
                    const userLevelEl = document.getElementById('userLevel');
                    const userInitialsEl = document.getElementById('userInitials');
                    
                    if (userNameEl) userNameEl.textContent = user.nome;
                    
                    // Converter nível para título
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
                } else {
                    console.log('⚠️ Usuário não encontrado no localStorage');
                    window.location.href = 'index-login.php';
                }
            } catch (error) {
                console.error('Erro ao carregar dados do usuário:', error);
                window.location.href = 'index-login.php';
            }
        }

        function setupEventListeners() {
            const searchInput = document.getElementById('searchInput');
            const statusFilter = document.getElementById('statusFilter');
            const tipoFilter = document.getElementById('tipoFilter');
            const reuniaoForm = document.getElementById('reuniaoForm');
            
            if (searchInput) searchInput.addEventListener('input', filterReunioes);
            if (statusFilter) statusFilter.addEventListener('change', filterReunioes);
            if (tipoFilter) tipoFilter.addEventListener('change', filterReunioes);
            if (reuniaoForm) reuniaoForm.addEventListener('submit', handleFormSubmit);
        }

        async function loadReunioes() {
            const loadingState = document.getElementById('loadingState');
            const maxWaitTime = 10000; // 10 segundos máximo
            const timeoutId = setTimeout(() => {
                console.warn('⚠️ Timeout ao carregar reuniões. Usando dados mock.');
                if (loadingState) loadingState.classList.add('hidden');
                reunioes = getMockReunioes();
                filteredReunioes = [...reunioes];
                renderReunioes();
                updateStats();
            }, maxWaitTime);

            try {
                const token = localStorage.getItem('token');
                if (!token) {
                    clearTimeout(timeoutId);
                    reunioes = getMockReunioes();
                    filteredReunioes = [...reunioes];
                    renderReunioes();
                    updateStats();
                    return;
                }

                const controller = new AbortController();
                const timeoutId2 = setTimeout(() => controller.abort(), 8000);

                const response = await fetch(`${API_BASE}/reunioes`, {
                    headers: {
                        'Authorization': `Bearer ${token}`
                    },
                    signal: controller.signal
                });

                clearTimeout(timeoutId);
                clearTimeout(timeoutId2);

                if (response.ok) {
                    reunioes = await response.json();
                    filteredReunioes = [...reunioes];
                    renderReunioes();
                    updateStats();
                } else {
                    // Fallback to mock data
                    reunioes = getMockReunioes();
                    filteredReunioes = [...reunioes];
                    renderReunioes();
                    updateStats();
                }
            } catch (error) {
                clearTimeout(timeoutId);
                console.error('Erro ao carregar reuniões:', error);
                if (loadingState) loadingState.classList.add('hidden');
                reunioes = getMockReunioes();
                filteredReunioes = [...reunioes];
                renderReunioes();
                updateStats();
            }
        }

        function getMockReunioes() {
            const today = new Date();
            const tomorrow = new Date(today);
            tomorrow.setDate(tomorrow.getDate() + 1);
            const nextWeek = new Date(today);
            nextWeek.setDate(nextWeek.getDate() + 7);

            return [
                {
                    id: 1,
                    titulo: "Reunião com Secretário de Obras",
                    tipo: "oficial",
                    status: "agendada",
                    data: tomorrow.toISOString().split('T')[0],
                    hora_inicio: "14:00",
                    hora_fim: "15:30",
                    local: "Câmara Municipal",
                    endereco: "Praça da República, 1",
                    descricao: "Discussão sobre projetos de infraestrutura",
                    pauta: "1. Apresentação de projetos\n2. Cronograma de execução\n3. Orçamento disponível"
                },
                {
                    id: 2,
                    titulo: "Reunião de Gabinete",
                    tipo: "reuniao_gabinete",
                    status: "agendada",
                    data: today.toISOString().split('T')[0],
                    hora_inicio: "09:00",
                    hora_fim: "10:30",
                    local: "Gabinete",
                    endereco: "Câmara Municipal - Gabinete 15",
                    descricao: "Reunião semanal da equipe",
                    pauta: "1. Relatório semanal\n2. Demandas pendentes\n3. Próximas atividades"
                },
                {
                    id: 3,
                    titulo: "Evento Público - Inauguração",
                    tipo: "evento_publico",
                    status: "realizada",
                    data: "2024-01-10",
                    hora_inicio: "16:00",
                    hora_fim: "18:00",
                    local: "Praça Central",
                    endereco: "Praça Central, Centro",
                    descricao: "Inauguração da nova praça",
                    pauta: "1. Cerimônia de inauguração\n2. Discurso do vereador\n3. Apresentação cultural"
                },
                {
                    id: 4,
                    titulo: "Visita Técnica - Obras",
                    tipo: "visita",
                    status: "cancelada",
                    data: "2024-01-08",
                    hora_inicio: "10:00",
                    hora_fim: "12:00",
                    local: "Obra da Rua Principal",
                    endereco: "Rua Principal, 100",
                    descricao: "Visita para acompanhar andamento das obras",
                    pauta: "1. Inspeção das obras\n2. Verificação de cronograma\n3. Relatório de andamento"
                }
            ];
        }

        function renderReunioes() {
            const loadingState = document.getElementById('loadingState');
            const emptyState = document.getElementById('emptyState');
            const calendarView = document.getElementById('calendarView');
            const listView = document.getElementById('listView');

            // Sempre esconder loading primeiro
            if (loadingState) {
                loadingState.classList.add('hidden');
            }

            if (!emptyState || !calendarView || !listView) {
                console.warn('⚠️ Elementos necessários não encontrados para renderizar reuniões');
                return;
            }

            if (filteredReunioes.length === 0) {
                emptyState.classList.remove('hidden');
                calendarView.innerHTML = '';
                listView.innerHTML = '';
                return;
            }

            emptyState.classList.add('hidden');

            // Sort meetings by date
            const sortedReunioes = [...filteredReunioes].sort((a, b) => new Date(a.data) - new Date(b.data));
            
            // Render calendar view
            renderCalendarView(sortedReunioes);
            
            // Render list view
            renderListView(sortedReunioes);
            
            // Render past meetings
            renderPastMeetings(sortedReunioes);
        }

        function renderCalendarView(reunioes) {
            const calendarView = document.getElementById('calendarView');
            if (!calendarView) return;
            
            const today = new Date().toISOString().split('T')[0];
            
            calendarView.innerHTML = reunioes.map(reuniao => {
                const isToday = reuniao.data === today;
                const isUrgent = isToday && reuniao.status === 'agendada';
                const isPast = new Date(reuniao.data) < new Date(today);
                
                return `
                    <div class="calendar-card p-4 rounded-lg fade-in ${isToday ? 'today' : ''} ${isUrgent ? 'urgent pulse-animation' : ''}">
                        <div class="flex items-start justify-between">
                            <div class="flex-1">
                                <div class="flex items-center space-x-3 mb-2">
                                    <h3 class="text-lg font-semibold text-gray-900">${reuniao.titulo}</h3>
                                    <span class="px-3 py-1 rounded-full text-xs font-medium ${getStatusClass(reuniao.status)}">
                                        ${getStatusText(reuniao.status)}
                                    </span>
                                </div>
                                <div class="flex items-center space-x-4 text-sm text-gray-600 mb-2">
                                    <span class="flex items-center">
                                        <i class="fas fa-calendar mr-1"></i>
                                        ${formatDate(reuniao.data)}
                                    </span>
                                    ${reuniao.hora_inicio ? `
                                    <span class="flex items-center">
                                        <i class="fas fa-clock mr-1"></i>
                                        ${reuniao.hora_inicio}${reuniao.hora_fim ? ` - ${reuniao.hora_fim}` : ''}
                                    </span>
                                    ` : ''}
                                    ${reuniao.local ? `
                                    <span class="flex items-center">
                                        <i class="fas fa-map-marker-alt mr-1"></i>
                                        ${reuniao.local}
                                    </span>
                                    ` : ''}
                                </div>
                                <p class="text-gray-600 text-sm">${reuniao.descricao || 'Sem descrição'}</p>
                            </div>
                            <div class="flex items-center space-x-2 ml-4">
                                <button onclick="editReuniao(${reuniao.id})" class="p-2 text-blue-600 hover:bg-blue-50 rounded-lg transition-colors" title="Editar">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button onclick="deleteReuniao(${reuniao.id})" class="p-2 text-red-600 hover:bg-red-50 rounded-lg transition-colors" title="Excluir">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                `;
            }).join('');
        }

        function renderListView(reunioes) {
            const listView = document.getElementById('listView');
            if (!listView) return;
            
            listView.innerHTML = reunioes.map(reuniao => `
                <div class="bg-white border border-gray-200 rounded-lg p-4 fade-in">
                    <div class="flex items-center justify-between">
                        <div class="flex-1">
                            <div class="flex items-center space-x-3 mb-2">
                                <h3 class="text-lg font-semibold text-gray-900">${reuniao.titulo}</h3>
                                <span class="px-3 py-1 rounded-full text-xs font-medium ${getStatusClass(reuniao.status)}">
                                    ${getStatusText(reuniao.status)}
                                </span>
                                <span class="px-2 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                    ${getTipoText(reuniao.tipo)}
                                </span>
                            </div>
                            <div class="flex items-center space-x-4 text-sm text-gray-600">
                                <span class="flex items-center">
                                    <i class="fas fa-calendar mr-1"></i>
                                    ${formatDate(reuniao.data)}
                                </span>
                                ${reuniao.hora_inicio ? `
                                <span class="flex items-center">
                                    <i class="fas fa-clock mr-1"></i>
                                    ${reuniao.hora_inicio}${reuniao.hora_fim ? ` - ${reuniao.hora_fim}` : ''}
                                </span>
                                ` : ''}
                                ${reuniao.local ? `
                                <span class="flex items-center">
                                    <i class="fas fa-map-marker-alt mr-1"></i>
                                    ${reuniao.local}
                                </span>
                                ` : ''}
                            </div>
                        </div>
                        <div class="flex items-center space-x-2 ml-4">
                            <button onclick="editReuniao(${reuniao.id})" class="p-2 text-blue-600 hover:bg-blue-50 rounded-lg transition-colors" title="Editar">
                                <i class="fas fa-edit"></i>
                            </button>
                            <button onclick="deleteReuniao(${reuniao.id})" class="p-2 text-red-600 hover:bg-red-50 rounded-lg transition-colors" title="Excluir">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    </div>
                </div>
            `).join('');
        }

        function renderPastMeetings(reunioes) {
            const pastMeetings = document.getElementById('pastMeetings');
            if (!pastMeetings) return;
            
            const today = new Date().toISOString().split('T')[0];
            const pastReunioes = reunioes.filter(reuniao => 
                new Date(reuniao.data) < new Date(today) || reuniao.status === 'realizada'
            ).slice(0, 5);
            
            pastMeetings.innerHTML = pastReunioes.map(reuniao => `
                <div class="flex items-center space-x-3 p-3 bg-gray-50 rounded-lg">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 rounded-full ${getStatusClass(reuniao.status)} flex items-center justify-center">
                            <i class="fas fa-${reuniao.status === 'realizada' ? 'check' : 'times'} text-xs"></i>
                        </div>
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-medium text-gray-900 truncate">${reuniao.titulo}</p>
                        <p class="text-xs text-gray-500">${formatDate(reuniao.data)}</p>
                    </div>
                </div>
            `).join('');
        }

        function getStatusClass(status) {
            const classes = {
                'agendada': 'status-agendada',
                'realizada': 'status-realizada',
                'cancelada': 'status-cancelada'
            };
            return classes[status] || 'status-agendada';
        }

        function getStatusText(status) {
            const texts = {
                'agendada': 'Agendada',
                'realizada': 'Realizada',
                'cancelada': 'Cancelada'
            };
            return texts[status] || 'Agendada';
        }

        function getTipoText(tipo) {
            const texts = {
                'oficial': 'Oficial',
                'reuniao_gabinete': 'Reunião de Gabinete',
                'evento_publico': 'Evento Público',
                'visita': 'Visita',
                'outro': 'Outro'
            };
            return texts[tipo] || tipo;
        }

        function formatDate(dateString) {
            const date = new Date(dateString);
            return date.toLocaleDateString('pt-BR');
        }

        function updateStats() {
            const today = new Date().toISOString().split('T')[0];
            const stats = {
                agendadas: reunioes.filter(r => r.status === 'agendada').length,
                realizadas: reunioes.filter(r => r.status === 'realizada').length,
                canceladas: reunioes.filter(r => r.status === 'cancelada').length,
                hoje: reunioes.filter(r => r.data === today && r.status === 'agendada').length
            };

            const agendadasEl = document.getElementById('statsAgendadas');
            const realizadasEl = document.getElementById('statsRealizadas');
            const canceladasEl = document.getElementById('statsCanceladas');
            const hojeEl = document.getElementById('statsHoje');
            
            if (agendadasEl) agendadasEl.textContent = stats.agendadas;
            if (realizadasEl) realizadasEl.textContent = stats.realizadas;
            if (canceladasEl) canceladasEl.textContent = stats.canceladas;
            if (hojeEl) hojeEl.textContent = stats.hoje;
        }

        function filterReunioes() {
            const searchInput = document.getElementById('searchInput');
            const statusFilter = document.getElementById('statusFilter');
            const tipoFilter = document.getElementById('tipoFilter');
            
            const searchTerm = searchInput?.value.toLowerCase() || '';
            const status = statusFilter?.value || '';
            const tipo = tipoFilter?.value || '';

            filteredReunioes = reunioes.filter(reuniao => {
                const matchesSearch = reuniao.titulo.toLowerCase().includes(searchTerm) ||
                                    (reuniao.descricao && reuniao.descricao.toLowerCase().includes(searchTerm));
                const matchesStatus = !status || reuniao.status === status;
                const matchesTipo = !tipo || reuniao.tipo === tipo;

                return matchesSearch && matchesStatus && matchesTipo;
            });

            renderReunioes();
        }

        function clearFilters() {
            const searchInput = document.getElementById('searchInput');
            const statusFilter = document.getElementById('statusFilter');
            const tipoFilter = document.getElementById('tipoFilter');
            
            if (searchInput) searchInput.value = '';
            if (statusFilter) statusFilter.value = '';
            if (tipoFilter) tipoFilter.value = '';
            filterReunioes();
        }

        function toggleView(view) {
            currentView = view;
            const calendarView = document.getElementById('calendarView');
            const listView = document.getElementById('listView');
            const buttons = document.querySelectorAll('[onclick^="toggleView"]');
            
            if (!calendarView || !listView) return;
            
            buttons.forEach(btn => {
                btn.classList.remove('bg-blue-100', 'text-blue-700');
                btn.classList.add('text-gray-600', 'hover:bg-gray-100');
            });
            
            if (view === 'calendar') {
                calendarView.classList.remove('hidden');
                listView.classList.add('hidden');
                if (buttons[0]) {
                    buttons[0].classList.add('bg-blue-100', 'text-blue-700');
                    buttons[0].classList.remove('text-gray-600', 'hover:bg-gray-100');
                }
            } else {
                calendarView.classList.add('hidden');
                listView.classList.remove('hidden');
                if (buttons[1]) {
                    buttons[1].classList.add('bg-blue-100', 'text-blue-700');
                    buttons[1].classList.remove('text-gray-600', 'hover:bg-gray-100');
                }
            }
        }

        // Funções já definidas no escopo global acima, apenas redefinir se necessário para atualizar
        // (já estão definidas antes do DOMContentLoaded)

        window.editReuniao = function(id) {
            const reuniao = reunioes.find(r => r.id === id);
            if (reuniao) {
                window.openReuniaoModal(reuniao);
            }
        };

        window.deleteReuniao = async function(id) {
            if (!confirm('Tem certeza que deseja excluir esta reunião?')) {
                return;
            }

            try {
                const token = localStorage.getItem('token');
                const response = await fetch(`${API_BASE}/reunioes/${id}`, {
                    method: 'DELETE',
                    headers: {
                        'Authorization': `Bearer ${token}`
                    }
                });

                if (response.ok) {
                    showToast('Reunião excluída com sucesso!', 'success');
                    loadReunioes();
                } else {
                    throw new Error('Erro ao excluir reunião');
                }
            } catch (error) {
                console.error('Erro ao excluir reunião:', error);
                showToast('Erro ao excluir reunião', 'error');
            }
        }

        async function handleFormSubmit(event) {
            event.preventDefault();
            
            try {
                const token = localStorage.getItem('token');
                const formData = {
                    titulo: document.getElementById('titulo').value,
                    tipo: document.getElementById('tipo').value,
                    data: document.getElementById('data').value,
                    status: document.getElementById('status').value,
                    hora_inicio: document.getElementById('horaInicio').value,
                    hora_fim: document.getElementById('horaFim').value,
                    local: document.getElementById('local').value,
                    endereco: document.getElementById('endereco').value,
                    descricao: document.getElementById('descricao').value,
                    pauta: document.getElementById('pauta').value
                };

                const reuniaoId = document.getElementById('reuniaoId').value;
                const url = reuniaoId ? `${API_BASE}/reunioes/${reuniaoId}` : `${API_BASE}/reunioes`;
                const method = reuniaoId ? 'PUT' : 'POST';

                const response = await fetch(url, {
                    method,
                    headers: {
                        'Content-Type': 'application/json',
                        'Authorization': `Bearer ${token}`
                    },
                    body: JSON.stringify(formData)
                });

                if (response.ok) {
                    showToast(reuniaoId ? 'Reunião atualizada com sucesso!' : 'Reunião criada com sucesso!', 'success');
                    closeReuniaoModal();
                    loadReunioes();
                } else {
                    const error = await response.json();
                    showToast(error.message || 'Erro ao salvar reunião', 'error');
                }
            } catch (error) {
                console.error('Erro ao salvar reunião:', error);
                showToast('Erro ao salvar reunião', 'error');
            }
        }

        function showToast(message, type = 'info') {
            const toast = document.createElement('div');
            const bgColor = type === 'success' ? 'bg-green-500' : type === 'error' ? 'bg-red-500' : 'bg-blue-500';
            
            toast.className = `fixed top-4 right-4 ${bgColor} text-white px-6 py-3 rounded-lg shadow-lg z-50 fade-in`;
            toast.innerHTML = `
                <div class="flex items-center">
                    <i class="fas fa-${type === 'success' ? 'check' : type === 'error' ? 'times' : 'info'}-circle mr-2"></i>
                    ${message}
                </div>
            `;

            document.body.appendChild(toast);

            setTimeout(() => {
                if (document.body.contains(toast)) {
                    toast.remove();
                }
            }, 3000);
        }
        
        // Fechar modal ao clicar fora
        document.addEventListener('click', function(e) {
            const modal = document.getElementById('reuniaoModal');
            if (modal && e.target === modal) {
                window.closeReuniaoModal();
            }
        });
    </script>
    <?php