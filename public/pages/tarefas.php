<div class="main-content">
        <!-- Page Header -->
        <div class="page-header">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="page-title">Tarefas Administrativas</h1>
                    <p class="mt-2 text-gray-600">Gerencie tarefas e acompanhe o progresso da equipe</p>
                </div>
                <button id="btnNovaTarefa" class="bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 text-white px-6 py-3 rounded-lg font-medium shadow-lg hover:shadow-xl transition-all duration-200 flex items-center">
                    <i class="fas fa-plus mr-2"></i>
                    Nova Tarefa
                </button>
            </div>
        </div>

        <!-- Summary Cards -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
            <div class="bg-white rounded-xl shadow-sm p-6 card-hover">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-green-100">
                        <i class="fas fa-check-circle text-green-600 text-xl"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Concluídas</p>
                        <p class="text-2xl font-bold text-gray-900" id="statsConcluidas">0</p>
                    </div>
                </div>
            </div>
            <div class="bg-white rounded-xl shadow-sm p-6 card-hover">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-blue-100">
                        <i class="fas fa-cog text-blue-600 text-xl"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Em Andamento</p>
                        <p class="text-2xl font-bold text-gray-900" id="statsAndamento">0</p>
                    </div>
                </div>
            </div>
            <div class="bg-white rounded-xl shadow-sm p-6 card-hover">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-red-100">
                        <i class="fas fa-exclamation-triangle text-red-600 text-xl"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Atrasadas</p>
                        <p class="text-2xl font-bold text-gray-900" id="statsAtrasadas">0</p>
                    </div>
                </div>
            </div>
            <div class="bg-white rounded-xl shadow-sm p-6 card-hover">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-yellow-100">
                        <i class="fas fa-clock text-yellow-600 text-xl"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Urgentes</p>
                        <p class="text-2xl font-bold text-gray-900" id="statsUrgentes">0</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filters -->
        <div class="bg-white rounded-xl shadow-sm p-6 mb-6">
            <div class="flex flex-wrap items-center gap-4">
                <div class="flex items-center space-x-2">
                    <label class="text-sm font-medium text-gray-700">Status:</label>
                    <select id="statusFilter" class="border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">Todos</option>
                        <option value="em_andamento">Em Andamento</option>
                        <option value="concluida">Concluída</option>
                    </select>
                </div>
                <div class="flex items-center space-x-2">
                    <label class="text-sm font-medium text-gray-700">Prioridade:</label>
                    <select id="prioridadeFilter" class="border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">Todas</option>
                        <option value="baixa">Baixa</option>
                        <option value="media">Média</option>
                        <option value="alta">Alta</option>
                        <option value="urgente">Urgente</option>
                    </select>
                </div>
                <div class="flex items-center space-x-2">
                    <label class="text-sm font-medium text-gray-700">Responsável:</label>
                    <select id="responsavelFilter" class="border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">Todos</option>
                        <option value="João Silva">João Silva</option>
                        <option value="Maria Santos">Maria Santos</option>
                        <option value="Pedro Costa">Pedro Costa</option>
                    </select>
                </div>
                <button onclick="clearFilters()" class="text-sm text-gray-500 hover:text-gray-700 flex items-center">
                    <i class="fas fa-times mr-1"></i>
                    Limpar Filtros
                </button>
            </div>
        </div>

        <!-- Tasks Table -->
        <div class="bg-white rounded-xl shadow-sm overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-900">Lista de Tarefas</h3>
            </div>
            
            <!-- Table Header -->
            <div class="bg-gray-50 px-6 py-3 border-b border-gray-200">
                <div class="grid grid-cols-12 gap-4 text-sm font-medium text-gray-700">
                    <div class="col-span-4">Descrição</div>
                    <div class="col-span-2">Responsável</div>
                    <div class="col-span-2">Prazo</div>
                    <div class="col-span-2">Status</div>
                    <div class="col-span-2">Ações</div>
                </div>
            </div>

            <!-- Tasks List -->
            <div id="tasksContainer" class="divide-y divide-gray-200">
                <!-- Tasks will be loaded here -->
            </div>

            <!-- Loading State -->
            <div id="loadingState" class="text-center py-12">
                <div class="inline-block animate-spin rounded-full h-8 w-8 border-b-2 border-blue-600"></div>
                <p class="mt-2 text-gray-600">Carregando tarefas...</p>
            </div>

            <!-- Empty State -->
            <div id="emptyState" class="text-center py-12 hidden">
                <i class="fas fa-tasks text-6xl text-gray-300 mb-4"></i>
                <h3 class="text-lg font-medium text-gray-900 mb-2">Nenhuma tarefa encontrada</h3>
                <p class="text-gray-600 mb-6">Comece criando sua primeira tarefa administrativa.</p>
                <button id="btnNovaTarefa2" class="btn btn-primary">
                    <i class="fas fa-plus mr-2"></i>
                    Nova Tarefa
                </button>
            </div>
        </div>

        <!-- Task Modal -->
    <div id="tarefaModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
        <div class="relative top-20 mx-auto p-5 border w-11/12 md:w-3/4 lg:w-1/2 shadow-lg rounded-md bg-white">
            <div class="mt-3">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-medium text-gray-900" id="modalTitle">Nova Tarefa</h3>
                    <button onclick="closeTarefaModal()" class="text-gray-400 hover:text-gray-600">
                        <i class="fas fa-times text-xl"></i>
                    </button>
                </div>
                <form id="tarefaForm" class="space-y-4">
                    <input type="hidden" id="tarefaId">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Título *</label>
                        <input type="text" id="titulo" required class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Descrição</label>
                        <textarea id="descricao" rows="3" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"></textarea>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Status *</label>
                            <select id="status" required class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                                <option value="em_andamento">Em Andamento</option>
                                <option value="concluida">Concluída</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Prioridade *</label>
                            <select id="prioridade" required class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                                <option value="baixa">Baixa</option>
                                <option value="media">Média</option>
                                <option value="alta">Alta</option>
                                <option value="urgente">Urgente</option>
                            </select>
                        </div>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Responsável</label>
                            <select id="responsavel" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                                <option value="">Selecione o responsável</option>
                                <option value="João Silva">João Silva</option>
                                <option value="Maria Santos">Maria Santos</option>
                                <option value="Pedro Costa">Pedro Costa</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Prazo</label>
                            <input type="date" id="prazo" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Progresso (%)</label>
                        <input type="range" id="progresso" min="0" max="100" value="0" class="w-full h-2 bg-gray-200 rounded-lg appearance-none cursor-pointer">
                        <div class="flex justify-between text-sm text-gray-600 mt-1">
                            <span>0%</span>
                            <span id="progressoValue">0%</span>
                            <span>100%</span>
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Categoria</label>
                        <input type="text" id="categoria" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Observações</label>
                        <textarea id="observacoes" rows="3" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"></textarea>
                    </div>
                    <div class="flex justify-end space-x-3 pt-4">
                        <button type="button" onclick="closeTarefaModal()" class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-500">
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
</div>
<script>
    // Definir funções no escopo global IMEDIATAMENTE para que estejam disponíveis quando os botões forem clicados
    window.openTarefaModal = function(tarefa = null) {
        const modal = document.getElementById('tarefaModal');
        const form = document.getElementById('tarefaForm');
        const title = document.getElementById('modalTitle');

        if (!modal || !form || !title) {
            console.error('⚠️ Modal, formulário ou título não encontrado');
            return;
        }

        // Se tarefa for fornecida, preencher o formulário (mas isso pode não estar disponível ainda)
        if (title) {
            title.textContent = tarefa ? 'Editar Tarefa' : 'Nova Tarefa';
        }
        if (!tarefa) {
            form.reset();
            const tarefaId = document.getElementById('tarefaId');
            if (tarefaId) tarefaId.value = '';
            const progressoValue = document.getElementById('progressoValue');
            if (progressoValue) progressoValue.textContent = '0%';
        }

        // Remover classe hidden e garantir display com !important
        modal.classList.remove('hidden');
        modal.style.setProperty('display', 'flex', 'important');
        modal.style.setProperty('align-items', 'center', 'important');
        modal.style.setProperty('justify-content', 'center', 'important');
        modal.style.setProperty('z-index', '9999', 'important');
        console.log('✅ Modal de tarefa aberto');
    };

    window.closeTarefaModal = function() {
        const modal = document.getElementById('tarefaModal');
        if (modal) {
            modal.classList.add('hidden');
            modal.style.display = 'none';
        }
    };

    (function() {
        'use strict';
        const API_BASE = window.location.hostname === 'localhost' || window.location.hostname === '127.0.0.1'
            ? 'http://localhost:3000/api'
            : 'https://uniassessor.com.br/api';
        
        let tarefas = [];
        let filteredTarefas = [];

        function executeWhenReady(callback) {
            if (typeof document !== 'undefined') {
                if (document.readyState === 'loading') {
                    document.addEventListener('DOMContentLoaded', callback);
                } else {
                    callback();
                }
            }
        }

        executeWhenReady(function() {
            loadTarefas();
            setupEventListeners();
        });

        function setupEventListeners() {
            const searchInput = document.getElementById('searchInput');
            if (searchInput) {
                searchInput.addEventListener('input', filterTarefas);
            }
            
            const statusFilter = document.getElementById('statusFilter');
            if (statusFilter) {
                statusFilter.addEventListener('change', filterTarefas);
            }
            
            const prioridadeFilter = document.getElementById('prioridadeFilter');
            if (prioridadeFilter) {
                prioridadeFilter.addEventListener('change', filterTarefas);
            }
            
            const responsavelFilter = document.getElementById('responsavelFilter');
            if (responsavelFilter) {
                responsavelFilter.addEventListener('change', filterTarefas);
            }
            
            const tarefaForm = document.getElementById('tarefaForm');
            if (tarefaForm) {
                tarefaForm.addEventListener('submit', handleFormSubmit);
            }
            
            const progresso = document.getElementById('progresso');
            if (progresso) {
                progresso.addEventListener('input', function() {
                    const progressoValue = document.getElementById('progressoValue');
                    if (progressoValue) {
                        progressoValue.textContent = this.value + '%';
                    }
                });
            }
        }

        async function loadTarefas() {
            const loadingState = document.getElementById('loadingState');
            const maxWaitTime = 10000; // 10 segundos máximo
            const timeoutId = setTimeout(() => {
                console.warn('⚠️ Timeout ao carregar tarefas. Usando dados mock.');
                if (loadingState) loadingState.classList.add('hidden');
                tarefas = getMockTarefas();
                filteredTarefas = [...tarefas];
                renderTarefas();
                updateStats();
            }, maxWaitTime);

            try {
                const token = localStorage.getItem('token');
                if (!token) {
                    clearTimeout(timeoutId);
                    tarefas = getMockTarefas();
                    filteredTarefas = [...tarefas];
                    renderTarefas();
                    updateStats();
                    return;
                }

                const controller = new AbortController();
                const timeoutId2 = setTimeout(() => controller.abort(), 8000);

                const response = await fetch(`${API_BASE}/tarefas`, {
                    headers: {
                        'Authorization': `Bearer ${token}`
                    },
                    signal: controller.signal
                });

                clearTimeout(timeoutId);
                clearTimeout(timeoutId2);

                if (response.ok) {
                    const data = await response.json();
                    tarefas = Array.isArray(data) ? data : (data.tarefas || []);
                    filteredTarefas = [...tarefas];
                    renderTarefas();
                    updateStats();
                } else {
                    tarefas = getMockTarefas();
                    filteredTarefas = [...tarefas];
                    renderTarefas();
                    updateStats();
                }
            } catch (error) {
                clearTimeout(timeoutId);
                console.error('Erro ao carregar tarefas:', error);
                if (loadingState) loadingState.classList.add('hidden');
                tarefas = getMockTarefas();
                filteredTarefas = [...tarefas];
                renderTarefas();
                updateStats();
            }
        }

        function getMockTarefas() {
            const today = new Date();
            const tomorrow = new Date(today);
            tomorrow.setDate(tomorrow.getDate() + 1);
            const yesterday = new Date(today);
            yesterday.setDate(yesterday.getDate() - 1);

            return [
                {
                    id: 1,
                    titulo: "Revisar documentos da sessão",
                    descricao: "Revisar todos os documentos que serão apresentados na próxima sessão da câmara",
                    status: "em_andamento",
                    prioridade: "alta",
                    responsavel: "João Silva",
                    prazo: tomorrow.toISOString().split('T')[0],
                    progresso: 75,
                    categoria: "Documentação",
                    data_criacao: "2024-01-15"
                },
                {
                    id: 2,
                    titulo: "Preparar relatório mensal",
                    descricao: "Elaborar relatório das atividades do gabinete no mês de janeiro",
                    status: "concluida",
                    prioridade: "media",
                    responsavel: "Maria Santos",
                    prazo: yesterday.toISOString().split('T')[0],
                    progresso: 100,
                    categoria: "Relatórios",
                    data_criacao: "2024-01-10"
                },
                {
                    id: 3,
                    titulo: "Organizar arquivo físico",
                    descricao: "Organizar e catalogar documentos físicos do gabinete",
                    status: "em_andamento",
                    prioridade: "baixa",
                    responsavel: "Pedro Costa",
                    prazo: today.toISOString().split('T')[0],
                    progresso: 30,
                    categoria: "Organização",
                    data_criacao: "2024-01-12"
                },
                {
                    id: 4,
                    titulo: "Atualizar site do gabinete",
                    descricao: "Atualizar informações e notícias no site oficial do gabinete",
                    status: "em_andamento",
                    prioridade: "urgente",
                    responsavel: "João Silva",
                    prazo: yesterday.toISOString().split('T')[0],
                    progresso: 20,
                    categoria: "Comunicação",
                    data_criacao: "2024-01-08"
                }
            ];
        }

        function renderTarefas() {
            const container = document.getElementById('tasksContainer');
            const loadingState = document.getElementById('loadingState');
            const emptyState = document.getElementById('emptyState');

            // Sempre esconder loading primeiro
            if (loadingState) {
                loadingState.classList.add('hidden');
            }

            if (!container) {
                console.error('⚠️ Container de tarefas não encontrado');
                return;
            }

            if (!emptyState) {
                console.warn('⚠️ Empty state não encontrado');
            }

            if (filteredTarefas.length === 0) {
                if (emptyState) emptyState.classList.remove('hidden');
                container.innerHTML = '';
                return;
            }

            if (emptyState) emptyState.classList.add('hidden');

            container.innerHTML = filteredTarefas.map(tarefa => {
                const isOverdue = tarefa.prazo && new Date(tarefa.prazo) < new Date() && tarefa.status !== 'concluida';
                const cardClass = isOverdue ? 'overdue' : getPriorityClass(tarefa.prioridade);
                
                return `
                    <div class="px-6 py-4 ${cardClass} fade-in">
                        <div class="grid grid-cols-12 gap-4 items-center">
                            <div class="col-span-4">
                                <h4 class="text-sm font-medium text-gray-900">${tarefa.titulo || 'Sem título'}</h4>
                                <p class="text-sm text-gray-600 mt-1">${tarefa.descricao || 'Sem descrição'}</p>
                                <div class="flex items-center mt-2 space-x-2">
                                    ${tarefa.categoria ? `<span class="px-2 py-1 text-xs bg-gray-100 text-gray-700 rounded-full">${tarefa.categoria}</span>` : ''}
                                    ${isOverdue ? '<span class="px-2 py-1 text-xs bg-red-100 text-red-700 rounded-full">Atrasada</span>' : ''}
                                </div>
                            </div>
                            <div class="col-span-2">
                                <p class="text-sm text-gray-900">${tarefa.responsavel || 'Não atribuído'}</p>
                            </div>
                            <div class="col-span-2">
                                <p class="text-sm text-gray-900 ${isOverdue ? 'text-red-600 font-medium' : ''}">
                                    ${tarefa.prazo ? formatDate(tarefa.prazo) : 'Sem prazo'}
                                </p>
                            </div>
                            <div class="col-span-2">
                                <div class="flex items-center space-x-2">
                                    <span class="px-3 py-1 rounded-full text-xs font-medium ${getStatusClass(tarefa.status)}">
                                        ${getStatusText(tarefa.status)}
                                    </span>
                                </div>
                                <div class="mt-2">
                                    <div class="w-full bg-gray-200 rounded-full h-2">
                                        <div class="progress-bar h-2 rounded-full ${tarefa.status === 'concluida' ? 'completed' : ''}" style="width: ${tarefa.progresso || 0}%"></div>
                                    </div>
                                    <p class="text-xs text-gray-500 mt-1">${tarefa.progresso || 0}% concluído</p>
                                </div>
                            </div>
                            <div class="col-span-2">
                                <div class="flex items-center space-x-2">
                                    <button onclick="editTarefa(${tarefa.id})" class="p-2 text-blue-600 hover:bg-blue-50 rounded-lg transition-colors" title="Editar">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <button onclick="deleteTarefa(${tarefa.id})" class="p-2 text-red-600 hover:bg-red-50 rounded-lg transition-colors" title="Excluir">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                `;
            }).join('');
        }

        function getStatusClass(status) {
            const classes = {
                'em_andamento': 'status-em-andamento',
                'concluida': 'status-concluida'
            };
            return classes[status] || 'status-em-andamento';
        }

        function getStatusText(status) {
            const texts = {
                'em_andamento': 'Em Andamento',
                'concluida': 'Concluída'
            };
            return texts[status] || 'Em Andamento';
        }

        function getPriorityClass(prioridade) {
            const classes = {
                'baixa': 'priority-baixa',
                'media': 'priority-media',
                'alta': 'priority-alta',
                'urgente': 'priority-urgente'
            };
            return classes[prioridade] || 'priority-media';
        }

        function formatDate(dateString) {
            if (!dateString) return 'Sem data';
            const date = new Date(dateString);
            return date.toLocaleDateString('pt-BR');
        }

        function updateStats() {
            const today = new Date().toISOString().split('T')[0];
            const stats = {
                concluidas: tarefas.filter(t => t.status === 'concluida').length,
                andamento: tarefas.filter(t => t.status === 'em_andamento').length,
                atrasadas: tarefas.filter(t => t.prazo && new Date(t.prazo) < new Date(today) && t.status !== 'concluida').length,
                urgentes: tarefas.filter(t => t.prioridade === 'urgente').length
            };

            const statsConcluidas = document.getElementById('statsConcluidas');
            const statsAndamento = document.getElementById('statsAndamento');
            const statsAtrasadas = document.getElementById('statsAtrasadas');
            const statsUrgentes = document.getElementById('statsUrgentes');

            if (statsConcluidas) statsConcluidas.textContent = stats.concluidas;
            if (statsAndamento) statsAndamento.textContent = stats.andamento;
            if (statsAtrasadas) statsAtrasadas.textContent = stats.atrasadas;
            if (statsUrgentes) statsUrgentes.textContent = stats.urgentes;
        }

        function filterTarefas() {
            const searchInput = document.getElementById('searchInput');
            const statusFilter = document.getElementById('statusFilter');
            const prioridadeFilter = document.getElementById('prioridadeFilter');
            const responsavelFilter = document.getElementById('responsavelFilter');

            const searchTerm = searchInput ? searchInput.value.toLowerCase() : '';
            const statusValue = statusFilter ? statusFilter.value : '';
            const prioridadeValue = prioridadeFilter ? prioridadeFilter.value : '';
            const responsavelValue = responsavelFilter ? responsavelFilter.value : '';

            filteredTarefas = tarefas.filter(tarefa => {
                const matchesSearch = !searchTerm || 
                    (tarefa.titulo && tarefa.titulo.toLowerCase().includes(searchTerm)) ||
                    (tarefa.descricao && tarefa.descricao.toLowerCase().includes(searchTerm));
                const matchesStatus = !statusValue || tarefa.status === statusValue;
                const matchesPrioridade = !prioridadeValue || tarefa.prioridade === prioridadeValue;
                const matchesResponsavel = !responsavelValue || tarefa.responsavel === responsavelValue;

                return matchesSearch && matchesStatus && matchesPrioridade && matchesResponsavel;
            });

            renderTarefas();
        }

        function clearFilters() {
            const searchInput = document.getElementById('searchInput');
            const statusFilter = document.getElementById('statusFilter');
            const prioridadeFilter = document.getElementById('prioridadeFilter');
            const responsavelFilter = document.getElementById('responsavelFilter');

            if (searchInput) searchInput.value = '';
            if (statusFilter) statusFilter.value = '';
            if (prioridadeFilter) prioridadeFilter.value = '';
            if (responsavelFilter) responsavelFilter.value = '';
            filterTarefas();
        }

        // Funções já definidas no escopo global acima, atualizar para incluir lógica de edição
        // Redefinir para incluir a lógica completa de edição
        window.openTarefaModal = function(tarefa = null) {
            const modal = document.getElementById('tarefaModal');
            const form = document.getElementById('tarefaForm');
            const title = document.getElementById('modalTitle');

            if (!modal || !form || !title) {
                console.error('⚠️ Modal, formulário ou título não encontrado');
                return;
            }

            if (tarefa) {
                title.textContent = 'Editar Tarefa';
                const tarefaId = document.getElementById('tarefaId');
                if (tarefaId) tarefaId.value = tarefa.id;
                const titulo = document.getElementById('titulo');
                if (titulo) titulo.value = tarefa.titulo || '';
                const descricao = document.getElementById('descricao');
                if (descricao) descricao.value = tarefa.descricao || '';
                const status = document.getElementById('status');
                if (status) status.value = tarefa.status || 'em_andamento';
                const prioridade = document.getElementById('prioridade');
                if (prioridade) prioridade.value = tarefa.prioridade || 'media';
                const responsavel = document.getElementById('responsavel');
                if (responsavel) responsavel.value = tarefa.responsavel || '';
                const prazo = document.getElementById('prazo');
                if (prazo) prazo.value = tarefa.prazo || '';
                const progresso = document.getElementById('progresso');
                if (progresso) progresso.value = tarefa.progresso || 0;
                const progressoValue = document.getElementById('progressoValue');
                if (progressoValue) progressoValue.textContent = (tarefa.progresso || 0) + '%';
                const categoria = document.getElementById('categoria');
                if (categoria) categoria.value = tarefa.categoria || '';
                const observacoes = document.getElementById('observacoes');
                if (observacoes) observacoes.value = tarefa.observacoes || '';
            } else {
                title.textContent = 'Nova Tarefa';
                form.reset();
                const tarefaId = document.getElementById('tarefaId');
                if (tarefaId) tarefaId.value = '';
                const progressoValue = document.getElementById('progressoValue');
                if (progressoValue) progressoValue.textContent = '0%';
            }

            // Remover classe hidden e garantir display com !important
            modal.classList.remove('hidden');
            modal.style.setProperty('display', 'flex', 'important');
            modal.style.setProperty('align-items', 'center', 'important');
            modal.style.setProperty('justify-content', 'center', 'important');
            modal.style.setProperty('z-index', '9999', 'important');
            console.log('✅ Modal de tarefa aberto');
        };

        window.closeTarefaModal = function() {
            const modal = document.getElementById('tarefaModal');
            if (modal) {
                modal.classList.add('hidden');
                modal.style.display = 'none';
            }
        };

        window.editTarefa = function(id) {
            const tarefa = tarefas.find(t => t.id === id);
            if (tarefa) {
                window.openTarefaModal(tarefa);
            }
        };

        window.deleteTarefa = async function(id) {
            if (confirm('Tem certeza que deseja excluir esta tarefa?')) {
                try {
                    const token = localStorage.getItem('token');
                    if (!token) {
                        // Remover da lista local
                        tarefas = tarefas.filter(t => t.id !== id);
                        filteredTarefas = [...tarefas];
                        renderTarefas();
                        updateStats();
                        showToast('Tarefa excluída com sucesso!', 'success');
                        return;
                    }

                    const response = await fetch(`${API_BASE}/tarefas/${id}`, {
                        method: 'DELETE',
                        headers: {
                            'Authorization': `Bearer ${token}`
                        }
                    });

                    if (response.ok) {
                        showToast('Tarefa excluída com sucesso!', 'success');
                        loadTarefas();
                    } else {
                        showToast('Erro ao excluir tarefa', 'error');
                    }
                } catch (error) {
                    console.error('Erro ao excluir tarefa:', error);
                    showToast('Erro ao excluir tarefa', 'error');
                }
            }
        };

        async function handleFormSubmit(e) {
            e.preventDefault();

            const titulo = document.getElementById('titulo');
            const descricao = document.getElementById('descricao');
            const status = document.getElementById('status');
            const prioridade = document.getElementById('prioridade');
            const responsavel = document.getElementById('responsavel');
            const prazo = document.getElementById('prazo');
            const progresso = document.getElementById('progresso');
            const categoria = document.getElementById('categoria');
            const observacoes = document.getElementById('observacoes');
            const tarefaId = document.getElementById('tarefaId');

            if (!titulo || !status || !prioridade) return;

            const formData = {
                titulo: titulo.value,
                descricao: descricao ? descricao.value : '',
                status: status.value,
                prioridade: prioridade.value,
                responsavel: responsavel ? responsavel.value : '',
                prazo: prazo ? prazo.value : '',
                progresso: progresso ? parseInt(progresso.value) : 0,
                categoria: categoria ? categoria.value : '',
                observacoes: observacoes ? observacoes.value : ''
            };

            const id = tarefaId ? tarefaId.value : '';
            const isEdit = !!id;

            try {
                const token = localStorage.getItem('token');
                if (!token) {
                    // Modo local - adicionar/editar na lista
                    if (isEdit) {
                        const index = tarefas.findIndex(t => t.id == id);
                        if (index !== -1) {
                            tarefas[index] = { ...tarefas[index], ...formData };
                        }
                    } else {
                        const newId = tarefas.length > 0 ? Math.max(...tarefas.map(t => t.id)) + 1 : 1;
                        tarefas.push({ ...formData, id: newId, data_criacao: new Date().toISOString().split('T')[0] });
                    }
                    filteredTarefas = [...tarefas];
                    renderTarefas();
                    updateStats();
                    showToast(isEdit ? 'Tarefa atualizada com sucesso!' : 'Tarefa criada com sucesso!', 'success');
                    closeTarefaModal();
                    return;
                }

                const url = isEdit ? `${API_BASE}/tarefas/${id}` : `${API_BASE}/tarefas`;
                const method = isEdit ? 'PUT' : 'POST';

                const response = await fetch(url, {
                    method: method,
                    headers: {
                        'Content-Type': 'application/json',
                        'Authorization': `Bearer ${token}`
                    },
                    body: JSON.stringify(formData)
                });

                if (response.ok) {
                    showToast(isEdit ? 'Tarefa atualizada com sucesso!' : 'Tarefa criada com sucesso!', 'success');
                    closeTarefaModal();
                    loadTarefas();
                } else {
                    const error = await response.json();
                    showToast(error.message || 'Erro ao salvar tarefa', 'error');
                }
            } catch (error) {
                console.error('Erro ao salvar tarefa:', error);
                showToast('Erro ao salvar tarefa', 'error');
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
                toast.remove();
            }, 3000);
        }
    })();
</script>