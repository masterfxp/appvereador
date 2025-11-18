<div class="main-content">
        <!-- Page Header -->
        <div class="page-header">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="page-title">Gestão de Projetos</h1>
                    <p class="mt-2 text-gray-600">Gerencie projetos legislativos e acompanhe seu progresso</p>
                </div>
                <button id="btnNovoProjeto" class="btn btn-primary">
                    <i class="fas fa-plus"></i>
                    Novo Projeto
                </button>
            </div>
        </div>

        <!-- Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
            <div class="bg-white rounded-xl shadow-sm p-6 card-hover">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-green-100">
                        <i class="fas fa-check-circle text-green-600 text-xl"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Aprovados</p>
                        <p class="text-2xl font-bold text-gray-900" id="statsAprovados">0</p>
                    </div>
                </div>
            </div>
            <div class="bg-white rounded-xl shadow-sm p-6 card-hover">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-yellow-100">
                        <i class="fas fa-clock text-yellow-600 text-xl"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Em Tramitação</p>
                        <p class="text-2xl font-bold text-gray-900" id="statsTramitacao">0</p>
                    </div>
                </div>
            </div>
            <div class="bg-white rounded-xl shadow-sm p-6 card-hover">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-red-100">
                        <i class="fas fa-times-circle text-red-600 text-xl"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Rejeitados</p>
                        <p class="text-2xl font-bold text-gray-900" id="statsRejeitados">0</p>
                    </div>
                </div>
            </div>
            <div class="bg-white rounded-xl shadow-sm p-6 card-hover">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-gray-100">
                        <i class="fas fa-archive text-gray-600 text-xl"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Arquivados</p>
                        <p class="text-2xl font-bold text-gray-900" id="statsArquivados">0</p>
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
                        <option value="aprovado">Aprovado</option>
                        <option value="em_tramitacao">Em Tramitação</option>
                        <option value="rejeitado">Rejeitado</option>
                        <option value="arquivado">Arquivado</option>
                    </select>
                </div>
                <div class="flex items-center space-x-2">
                    <label class="text-sm font-medium text-gray-700">Tipo:</label>
                    <select id="tipoFilter" class="border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">Todos</option>
                        <option value="projeto_lei">Projeto de Lei</option>
                        <option value="indicacao">Indicação</option>
                        <option value="requerimento">Requerimento</option>
                        <option value="mocao">Moção</option>
                    </select>
                </div>
                <button onclick="clearFilters()" class="text-sm text-gray-500 hover:text-gray-700 flex items-center">
                    <i class="fas fa-times mr-1"></i>
                    Limpar Filtros
                </button>
            </div>
        </div>

        <!-- Projects Grid -->
        <div id="projectsContainer" class="space-y-4">
            <!-- Projects will be loaded here -->
        </div>

        <!-- Loading State -->
        <div id="loadingState" class="text-center py-12">
            <div class="inline-block animate-spin rounded-full h-8 w-8 border-b-2 border-blue-600"></div>
            <p class="mt-2 text-gray-600">Carregando projetos...</p>
        </div>

        <!-- Empty State -->
        <div id="emptyState" class="text-center py-12 hidden">
            <i class="fas fa-file-alt text-6xl text-gray-300 mb-4"></i>
            <h3 class="text-lg font-medium text-gray-900 mb-2">Nenhum projeto encontrado</h3>
            <p class="text-gray-600 mb-6">Comece criando seu primeiro projeto legislativo.</p>
            <button id="btnNovoProjeto2" class="btn btn-primary">
                <i class="fas fa-plus"></i>
                Novo Projeto
            </button>
        </div>
    </div>

    <!-- Modal de Projeto -->
    <div id="projetoModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
        <div class="relative top-20 mx-auto p-5 border w-11/12 md:w-3/4 lg:w-1/2 shadow-lg rounded-md bg-white">
            <div class="mt-3">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-medium text-gray-900" id="modalTitle">Novo Projeto</h3>
                    <button onclick="closeProjetoModal()" class="text-gray-400 hover:text-gray-600">
                        <i class="fas fa-times text-xl"></i>
                    </button>
                </div>
                <form id="projetoForm" class="space-y-4">
                    <input type="hidden" id="projetoId">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Título *</label>
                        <input type="text" id="titulo" required class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Tipo *</label>
                            <select id="tipo" required class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                                <option value="projeto_lei">Projeto de Lei</option>
                                <option value="indicacao">Indicação</option>
                                <option value="requerimento">Requerimento</option>
                                <option value="mocao">Moção</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Status *</label>
                            <select id="status" required class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                                <option value="em_tramitacao">Em Tramitação</option>
                                <option value="aprovado">Aprovado</option>
                                <option value="rejeitado">Rejeitado</option>
                                <option value="arquivado">Arquivado</option>
                            </select>
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Descrição</label>
                        <textarea id="descricao" rows="3" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"></textarea>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Conteúdo</label>
                        <textarea id="conteudo" rows="6" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"></textarea>
                    </div>
                    <div class="flex justify-end space-x-3 pt-4">
                        <button type="button" onclick="closeProjetoModal()" class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-500">
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

    <script>
        // Definir funções no escopo global IMEDIATAMENTE para que estejam disponíveis quando os botões forem clicados
        window.openProjetoModal = function(projetoId = null) {
            const modal = document.getElementById('projetoModal');
            const form = document.getElementById('projetoForm');
            const modalTitle = document.getElementById('modalTitle');

            if (!modal || !form) {
                console.error('⚠️ Modal ou formulário não encontrado');
                return;
            }

            // Se projetoId for fornecido, precisamos buscar os projetos (mas isso pode não estar disponível ainda)
            // Por enquanto, apenas abrir o modal
            if (modalTitle) {
                modalTitle.textContent = projetoId ? 'Editar Projeto' : 'Novo Projeto';
            }
            if (!projetoId) {
                form.reset();
                const projetoIdInput = document.getElementById('projetoId');
                if (projetoIdInput) projetoIdInput.value = '';
            }

            // Remover classe hidden e garantir display com !important
            modal.classList.remove('hidden');
            modal.style.setProperty('display', 'flex', 'important');
            modal.style.setProperty('align-items', 'center', 'important');
            modal.style.setProperty('justify-content', 'center', 'important');
            modal.style.setProperty('z-index', '9999', 'important');
            console.log('✅ Modal de projeto aberto');
        };

        window.closeProjetoModal = function() {
            const modal = document.getElementById('projetoModal');
            if (modal) {
                modal.classList.add('hidden');
                modal.style.display = 'none';
            }
        };
        
        const API_BASE = 'http://localhost:3000/api';
        let projetos = [];
        let filteredProjetos = [];

        // Load projects on page load
        document.addEventListener('DOMContentLoaded', function() {
            loadUserInfo();
            loadProjetos();
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
            const projetoForm = document.getElementById('projetoForm');
            
            if (searchInput) searchInput.addEventListener('input', filterProjetos);
            if (statusFilter) statusFilter.addEventListener('change', filterProjetos);
            if (tipoFilter) tipoFilter.addEventListener('change', filterProjetos);
            if (projetoForm) projetoForm.addEventListener('submit', handleFormSubmit);
        }

        async function loadProjetos() {
            const loadingState = document.getElementById('loadingState');
            const maxWaitTime = 10000; // 10 segundos máximo
            const timeoutId = setTimeout(() => {
                console.warn('⚠️ Timeout ao carregar projetos. Usando dados mock.');
                if (loadingState) loadingState.classList.add('hidden');
                projetos = getMockProjetos();
                filteredProjetos = [...projetos];
                renderProjetos();
                updateStats();
            }, maxWaitTime);

            try {
                const token = localStorage.getItem('token');
                if (!token) {
                    clearTimeout(timeoutId);
                    projetos = getMockProjetos();
                    filteredProjetos = [...projetos];
                    renderProjetos();
                    updateStats();
                    return;
                }

                const controller = new AbortController();
                const timeoutId2 = setTimeout(() => controller.abort(), 8000);

                const response = await fetch(`${API_BASE}/projetos`, {
                    headers: {
                        'Authorization': `Bearer ${token}`
                    },
                    signal: controller.signal
                });

                clearTimeout(timeoutId2);

                clearTimeout(timeoutId);

                if (response.ok) {
                    projetos = await response.json();
                    filteredProjetos = [...projetos];
                    renderProjetos();
                    updateStats();
                } else {
                    // Fallback to mock data
                    projetos = getMockProjetos();
                    filteredProjetos = [...projetos];
                    renderProjetos();
                    updateStats();
                }
            } catch (error) {
                clearTimeout(timeoutId);
                console.error('Erro ao carregar projetos:', error);
                if (loadingState) loadingState.classList.add('hidden');
                projetos = getMockProjetos();
                filteredProjetos = [...projetos];
                renderProjetos();
                updateStats();
            }
        }

        function getMockProjetos() {
            return [
                {
                    id: 1,
                    titulo: 'Projeto de Lei 001/2024',
                    tipo: 'projeto_lei',
                    status: 'em_tramitacao',
                    descricao: 'Projeto de lei para melhoria da infraestrutura urbana',
                    data_criacao: '2024-01-15',
                    autor: 'João Silva'
                },
                {
                    id: 2,
                    titulo: 'Indicação para Construção de Praça',
                    tipo: 'indicacao',
                    status: 'aprovado',
                    descricao: 'Indicação para construção de praça no bairro Centro',
                    data_criacao: '2024-01-10',
                    autor: 'Maria Santos'
                },
                {
                    id: 3,
                    titulo: 'Requerimento de Informações sobre Obras',
                    tipo: 'requerimento',
                    status: 'rejeitado',
                    descricao: 'Requerimento de informações sobre andamento das obras municipais',
                    data_criacao: '2024-01-05',
                    autor: 'Pedro Costa'
                }
            ];
        }

        function renderProjetos() {
            const container = document.getElementById('projectsContainer');
            const loadingState = document.getElementById('loadingState');
            const emptyState = document.getElementById('emptyState');

            // Sempre esconder loading, mesmo se elementos não existirem
            if (loadingState) {
                loadingState.classList.add('hidden');
            }

            if (!container) {
                console.error('⚠️ Container de projetos não encontrado');
                return;
            }

            if (!emptyState) {
                console.warn('⚠️ Empty state não encontrado');
            }

            if (filteredProjetos.length === 0) {
                emptyState.classList.remove('hidden');
                container.innerHTML = '';
                return;
            }

            emptyState.classList.add('hidden');
            container.innerHTML = filteredProjetos.map(projeto => `
                <div class="bg-white rounded-xl shadow-sm p-6 card-hover">
                    <div class="flex items-start justify-between">
                        <div class="flex-1">
                            <div class="flex items-center space-x-3 mb-2">
                                <h3 class="text-lg font-semibold text-gray-900">${projeto.titulo}</h3>
                                <span class="status-label status-${projeto.status}">${getStatusLabel(projeto.status)}</span>
                            </div>
                            <p class="text-sm text-gray-600 mb-3">${projeto.descricao || ''}</p>
                            <div class="flex items-center space-x-4 text-xs text-gray-500">
                                <span><i class="fas fa-calendar mr-1"></i>${formatDate(projeto.data_criacao)}</span>
                                <span><i class="fas fa-user mr-1"></i>${projeto.autor || 'Autor'}</span>
                                <span><i class="fas fa-tag mr-1"></i>${getTipoLabel(projeto.tipo)}</span>
                            </div>
                        </div>
                        <div class="flex items-center space-x-2 ml-4">
                            <button onclick="editProjeto(${projeto.id})" class="btn btn-secondary btn-sm">
                                <i class="fas fa-edit"></i>
                            </button>
                            <button onclick="deleteProjeto(${projeto.id})" class="btn btn-danger btn-sm">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    </div>
                </div>
            `).join('');
        }

        function getStatusLabel(status) {
            const labels = {
                'em_tramitacao': 'Em Tramitação',
                'aprovado': 'Aprovado',
                'rejeitado': 'Rejeitado',
                'arquivado': 'Arquivado'
            };
            return labels[status] || status;
        }

        function getTipoLabel(tipo) {
            const labels = {
                'projeto_lei': 'Projeto de Lei',
                'indicacao': 'Indicação',
                'requerimento': 'Requerimento',
                'mocao': 'Moção'
            };
            return labels[tipo] || tipo;
        }

        function formatDate(dateString) {
            if (!dateString) return '';
            const date = new Date(dateString);
            return date.toLocaleDateString('pt-BR');
        }

        function updateStats() {
            const statsAprovados = document.getElementById('statsAprovados');
            const statsTramitacao = document.getElementById('statsTramitacao');
            const statsRejeitados = document.getElementById('statsRejeitados');
            const statsArquivados = document.getElementById('statsArquivados');

            if (statsAprovados) statsAprovados.textContent = projetos.filter(p => p.status === 'aprovado').length;
            if (statsTramitacao) statsTramitacao.textContent = projetos.filter(p => p.status === 'em_tramitacao').length;
            if (statsRejeitados) statsRejeitados.textContent = projetos.filter(p => p.status === 'rejeitado').length;
            if (statsArquivados) statsArquivados.textContent = projetos.filter(p => p.status === 'arquivado').length;
        }

        function filterProjetos() {
            const searchInput = document.getElementById('searchInput');
            const statusFilter = document.getElementById('statusFilter');
            const tipoFilter = document.getElementById('tipoFilter');

            const searchTerm = searchInput ? searchInput.value.toLowerCase() : '';
            const statusValue = statusFilter ? statusFilter.value : '';
            const tipoValue = tipoFilter ? tipoFilter.value : '';

            filteredProjetos = projetos.filter(projeto => {
                const matchesSearch = !searchTerm || projeto.titulo.toLowerCase().includes(searchTerm) || 
                                     (projeto.descricao && projeto.descricao.toLowerCase().includes(searchTerm));
                const matchesStatus = !statusValue || projeto.status === statusValue;
                const matchesTipo = !tipoValue || projeto.tipo === tipoValue;
                return matchesSearch && matchesStatus && matchesTipo;
            });

            renderProjetos();
        }

        function clearFilters() {
            const searchInput = document.getElementById('searchInput');
            const statusFilter = document.getElementById('statusFilter');
            const tipoFilter = document.getElementById('tipoFilter');

            if (searchInput) searchInput.value = '';
            if (statusFilter) statusFilter.value = '';
            if (tipoFilter) tipoFilter.value = '';

            filteredProjetos = [...projetos];
            renderProjetos();
        }

        // Funções já definidas no escopo global acima, atualizar para incluir lógica de edição
        // Redefinir para incluir a lógica completa de edição
        window.openProjetoModal = function(projetoId = null) {
            const modal = document.getElementById('projetoModal');
            const form = document.getElementById('projetoForm');
            const modalTitle = document.getElementById('modalTitle');

            if (!modal || !form) {
                console.error('⚠️ Modal ou formulário não encontrado');
                return;
            }

            if (projetoId) {
                const projeto = projetos.find(p => p.id === projetoId);
                if (projeto) {
                    if (modalTitle) modalTitle.textContent = 'Editar Projeto';
                    document.getElementById('projetoId').value = projeto.id;
                    document.getElementById('titulo').value = projeto.titulo;
                    document.getElementById('tipo').value = projeto.tipo;
                    document.getElementById('status').value = projeto.status;
                    document.getElementById('descricao').value = projeto.descricao || '';
                    document.getElementById('conteudo').value = projeto.conteudo || '';
                }
            } else {
                if (modalTitle) modalTitle.textContent = 'Novo Projeto';
                form.reset();
                document.getElementById('projetoId').value = '';
            }

            // Remover classe hidden e garantir display com !important
            modal.classList.remove('hidden');
            modal.style.setProperty('display', 'flex', 'important');
            modal.style.setProperty('align-items', 'center', 'important');
            modal.style.setProperty('justify-content', 'center', 'important');
            modal.style.setProperty('z-index', '9999', 'important');
            console.log('✅ Modal de projeto aberto');
        };

        async function handleFormSubmit(e) {
            e.preventDefault();
            
            const projetoId = document.getElementById('projetoId').value;
            const projetoData = {
                titulo: document.getElementById('titulo').value,
                tipo: document.getElementById('tipo').value,
                status: document.getElementById('status').value,
                descricao: document.getElementById('descricao').value,
                conteudo: document.getElementById('conteudo').value
            };

            try {
                const token = localStorage.getItem('token');
                const url = projetoId ? `${API_BASE}/projetos/${projetoId}` : `${API_BASE}/projetos`;
                const method = projetoId ? 'PUT' : 'POST';

                const response = await fetch(url, {
                    method: method,
                    headers: {
                        'Content-Type': 'application/json',
                        'Authorization': `Bearer ${token}`
                    },
                    body: JSON.stringify(projetoData)
                });

                if (response.ok) {
                    closeProjetoModal();
                    loadProjetos();
                } else {
                    alert('Erro ao salvar projeto');
                }
            } catch (error) {
                console.error('Erro ao salvar projeto:', error);
                alert('Erro ao salvar projeto');
            }
        }

        window.editProjeto = function(id) {
            window.openProjetoModal(id);
        };

        window.deleteProjeto = async function(id) {
            if (!confirm('Tem certeza que deseja excluir este projeto?')) return;

            try {
                const token = localStorage.getItem('token');
                const response = await fetch(`${API_BASE}/projetos/${id}`, {
                    method: 'DELETE',
                    headers: {
                        'Authorization': `Bearer ${token}`
                    }
                });

                if (response.ok) {
                    loadProjetos();
                } else {
                    alert('Erro ao excluir projeto');
                }
            } catch (error) {
                console.error('Erro ao excluir projeto:', error);
                alert('Erro ao excluir projeto');
            }
        }

        // Fechar modal ao clicar fora
        document.addEventListener('click', function(e) {
            const modal = document.getElementById('projetoModal');
            if (modal && e.target === modal) {
                window.closeProjetoModal();
            }
        });
    </script>
    <?php