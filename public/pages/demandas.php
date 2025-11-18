<div class="main-content">
        <!-- Page Header -->
        <div class="page-header">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="page-title">Gestão de Demandas</h1>
                    <p class="mt-2 text-gray-600">Gerencie solicitações e demandas da comunidade</p>
                </div>
                <button id="btnNovaDemanda" class="btn btn-primary">
                    <i class="fas fa-plus"></i>
                    Nova Demanda
                </button>
            </div>
        </div>

        <!-- Indicadores de Demandas -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <div class="bg-gradient-to-r from-green-500 to-green-600 rounded-lg shadow-lg p-6 text-white card-hover">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-green-100 text-sm font-medium">Total de Demandas</p>
                        <p class="text-3xl font-bold" id="totalDemandas">0</p>
                        <p class="text-green-100 text-xs mt-1">Este mês: <span id="demandasMes">0</span></p>
                    </div>
                    <div class="p-3 rounded-full bg-green-400 bg-opacity-30">
                        <i class="fas fa-comments text-2xl"></i>
                    </div>
                </div>
            </div>
            
            <div class="bg-gradient-to-r from-blue-500 to-blue-600 rounded-lg shadow-lg p-6 text-white card-hover">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-blue-100 text-sm font-medium">Resolvidas</p>
                        <p class="text-3xl font-bold" id="demandasResolvidas">0</p>
                        <p class="text-blue-100 text-xs mt-1">Taxa: <span id="taxaResolucao">0%</span></p>
                    </div>
                    <div class="p-3 rounded-full bg-blue-400 bg-opacity-30">
                        <i class="fas fa-check-circle text-2xl"></i>
                    </div>
                </div>
            </div>
            
            <div class="bg-gradient-to-r from-yellow-500 to-yellow-600 rounded-lg shadow-lg p-6 text-white card-hover">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-yellow-100 text-sm font-medium">Em Andamento</p>
                        <p class="text-3xl font-bold" id="demandasAndamento">0</p>
                        <p class="text-yellow-100 text-xs mt-1">Pendentes: <span id="demandasPendentes">0</span></p>
                    </div>
                    <div class="p-3 rounded-full bg-yellow-400 bg-opacity-30">
                        <i class="fas fa-clock text-2xl"></i>
                    </div>
                </div>
            </div>
            
            <div class="bg-gradient-to-r from-red-500 to-red-600 rounded-lg shadow-lg p-6 text-white card-hover">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-red-100 text-sm font-medium">Urgentes</p>
                        <p class="text-3xl font-bold" id="demandasUrgentes">0</p>
                        <p class="text-red-100 text-xs mt-1">Alta prioridade: <span id="demandasAltaPrioridade">0</span></p>
                    </div>
                    <div class="p-3 rounded-full bg-red-400 bg-opacity-30">
                        <i class="fas fa-exclamation-triangle text-2xl"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filtros e Busca -->
        <div class="card">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                    <select id="filtroStatus" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">Todos</option>
                        <option value="pendente">Pendente</option>
                        <option value="em_andamento">Em Andamento</option>
                        <option value="resolvido">Resolvido</option>
                        <option value="cancelado">Cancelado</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Categoria</label>
                    <select id="filtroCategoria" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">Todas</option>
                        <option value="infraestrutura">Infraestrutura</option>
                        <option value="saude">Saúde</option>
                        <option value="educacao">Educação</option>
                        <option value="seguranca">Segurança</option>
                        <option value="outros">Outros</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Prioridade</label>
                    <select id="filtroPrioridade" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">Todas</option>
                        <option value="baixa">Baixa</option>
                        <option value="media">Média</option>
                        <option value="alta">Alta</option>
                        <option value="urgente">Urgente</option>
                    </select>
                </div>
                <div class="flex items-end">
                    <button onclick="aplicarFiltros()" class="w-full inline-flex justify-center items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        <i class="fas fa-filter mr-2"></i>
                        Aplicar Filtros
                    </button>
                </div>
            </div>
        </div>

        <!-- Lista de Demandas -->
        <div class="bg-white shadow rounded-lg">
            <div class="px-6 py-4 border-b border-gray-200">
                <div class="flex items-center justify-between">
                    <h3 class="text-lg font-medium text-gray-900">Demandas</h3>
                    <div class="flex items-center space-x-4">
                        <span class="text-sm text-gray-500" id="totalDemandasLista">0 demandas</span>
                        <div class="flex space-x-2">
                            <button onclick="exportarDemandas()" class="text-sm text-blue-600 hover:text-blue-800">
                                <i class="fas fa-download mr-1"></i>
                                Exportar
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            <div id="demandasList" class="divide-y divide-gray-200">
                <!-- Demandas serão carregadas aqui -->
            </div>
            <div id="loadingDemandas" class="p-6 text-center">
                <i class="fas fa-spinner fa-spin text-2xl text-gray-400"></i>
                <p class="mt-2 text-gray-500">Carregando demandas...</p>
            </div>
            <div id="emptyState" class="hidden p-6 text-center">
                <i class="fas fa-comments text-4xl text-gray-300 mb-4"></i>
                <h3 class="text-lg font-medium text-gray-900 mb-2">Nenhuma demanda encontrada</h3>
                <p class="text-gray-500">Comece criando sua primeira demanda.</p>
            </div>
        </div>
    </div>

    <!-- Modal de Demanda -->
    <div id="demandaModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
        <div class="relative top-20 mx-auto p-5 border w-11/12 md:w-3/4 lg:w-1/2 shadow-lg rounded-md bg-white">
            <div class="mt-3">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-medium text-gray-900" id="modalTitle">Nova Demanda</h3>
                    <button onclick="closeDemandaModal()" class="text-gray-400 hover:text-gray-600">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
                <form id="demandaForm">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Título</label>
                            <input type="text" id="titulo" name="titulo" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Categoria</label>
                            <select id="categoria" name="categoria" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                                <option value="">Selecione a categoria</option>
                                <option value="infraestrutura">Infraestrutura</option>
                                <option value="saude">Saúde</option>
                                <option value="educacao">Educação</option>
                                <option value="seguranca">Segurança</option>
                                <option value="outros">Outros</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                            <select id="status" name="status" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                                <option value="pendente">Pendente</option>
                                <option value="em_andamento">Em Andamento</option>
                                <option value="resolvido">Resolvido</option>
                                <option value="cancelado">Cancelado</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Prioridade</label>
                            <select id="prioridade" name="prioridade" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                                <option value="baixa">Baixa</option>
                                <option value="media">Média</option>
                                <option value="alta">Alta</option>
                                <option value="urgente">Urgente</option>
                            </select>
                        </div>
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Descrição</label>
                            <textarea id="descricao" name="descricao" rows="3" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"></textarea>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Localização</label>
                            <input type="text" id="localizacao" name="localizacao" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Responsável</label>
                            <select id="responsavel" name="responsavel" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                                <option value="">Selecione o responsável</option>
                            </select>
                        </div>
                    </div>
                    <div class="flex justify-end space-x-3 mt-6">
                        <button type="button" onclick="closeDemandaModal()" class="px-4 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            Cancelar
                        </button>
                        <button type="submit" class="px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
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
        window.openDemandaModal = function(demanda = null) {
            const modal = document.getElementById('demandaModal');
            const form = document.getElementById('demandaForm');
            const title = document.getElementById('modalTitle');

            if (!modal || !form || !title) {
                console.error('⚠️ Modal, formulário ou título não encontrado');
                return;
            }

            // Se demanda for fornecida, preencher o formulário (mas isso pode não estar disponível ainda)
            if (title) {
                title.textContent = demanda ? 'Editar Demanda' : 'Nova Demanda';
            }
            if (!demanda) {
                form.reset();
            }

            // Remover classe hidden e garantir display com !important
            modal.classList.remove('hidden');
            modal.style.setProperty('display', 'flex', 'important');
            modal.style.setProperty('align-items', 'center', 'important');
            modal.style.setProperty('justify-content', 'center', 'important');
            modal.style.setProperty('z-index', '9999', 'important');
            console.log('✅ Modal de demanda aberto');
        };

        window.closeDemandaModal = function() {
            const modal = document.getElementById('demandaModal');
            if (modal) {
                modal.classList.add('hidden');
                modal.style.display = 'none';
            }
        };
        
        const API_BASE = 'http://localhost:3000/api';
        let currentUser = null;
        let demandas = [];
        let editingDemanda = null;

        // Carregar dados do usuário
        async function loadUserInfo() {
            try {
                const user = JSON.parse(localStorage.getItem('user'));
                if (user) {
                    currentUser = user;
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
                    
                    // Mostrar menu admin se for administrador
                    const adminMenu = document.getElementById('adminMenu');
                    if (adminMenu && user.nivel === 'administrador') {
                        adminMenu.classList.remove('hidden');
                    }
                } else {
                    console.log('⚠️ Usuário não encontrado no localStorage');
                    window.location.href = 'index-login.php';
                }
            } catch (error) {
                console.error('Erro ao carregar dados do usuário:', error);
                window.location.href = 'index-login.php';
            }
        }

        // Carregar demandas
        async function loadDemandas() {
            try {
                const token = localStorage.getItem('token');
                if (!token) {
                    window.location.href = 'index-login.php';
                    return;
                }

                const loadingEl = document.getElementById('loadingDemandas');
                const emptyEl = document.getElementById('emptyState');
                
                if (loadingEl) loadingEl.classList.remove('hidden');
                if (emptyEl) emptyEl.classList.add('hidden');

                const response = await fetch(`${API_BASE}/demandas`, {
                    headers: { 'Authorization': `Bearer ${token}` }
                });

                if (response.ok) {
                    demandas = await response.json();
                    renderDemandas();
                    updateStats();
                } else {
                    throw new Error('Erro ao carregar demandas');
                }
            } catch (error) {
                console.error('Erro ao carregar demandas:', error);
                showToast('Erro ao carregar demandas', 'error');
            } finally {
                const loadingEl = document.getElementById('loadingDemandas');
                if (loadingEl) loadingEl.classList.add('hidden');
            }
        }

        // Renderizar demandas
        function renderDemandas() {
            const container = document.getElementById('demandasList');
            const emptyEl = document.getElementById('emptyState');
            
            if (!container) return;
            
            if (demandas.length === 0) {
                if (emptyEl) emptyEl.classList.remove('hidden');
                container.innerHTML = '';
                return;
            }

            if (emptyEl) emptyEl.classList.add('hidden');

            container.innerHTML = demandas.map(demanda => {
                const statusConfig = getStatusConfig(demanda.status);
                const prioridadeConfig = getPrioridadeConfig(demanda.prioridade);
                const dataFormatada = demanda.created_at ? new Date(demanda.created_at).toLocaleDateString('pt-BR') : 'Data não informada';
                const titulo = demanda.assunto || demanda.titulo || 'Sem título';
                const descricao = demanda.descricao || 'Sem descrição';
                const localizacao = demanda.localizacao ? (typeof demanda.localizacao === 'string' ? demanda.localizacao : demanda.localizacao.endereco || 'Não informado') : 'Não informado';
                const cidadao = demanda.cidadao ? (typeof demanda.cidadao === 'string' ? demanda.cidadao : demanda.cidadao.nome || 'Não informado') : 'Não informado';

                return `
                    <div class="p-6 hover:bg-gray-50">
                        <div class="flex items-start justify-between">
                            <div class="flex-1">
                                <div class="flex items-center flex-wrap gap-2">
                                    <h4 class="text-lg font-medium text-gray-900">${titulo}</h4>
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium ${statusConfig.bgColor} ${statusConfig.textColor}">
                                        ${statusConfig.label}
                                    </span>
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium ${prioridadeConfig.bgColor} ${prioridadeConfig.textColor}">
                                        ${prioridadeConfig.label}
                                    </span>
                                </div>
                                <p class="mt-1 text-sm text-gray-600">${descricao}</p>
                                <div class="mt-2 flex items-center flex-wrap gap-4 text-sm text-gray-500">
                                    <span>
                                        <i class="fas fa-user mr-1"></i>
                                        ${cidadao}
                                    </span>
                                    <span>
                                        <i class="fas fa-map-marker-alt mr-1"></i>
                                        ${localizacao}
                                    </span>
                                    <span>
                                        <i class="fas fa-calendar mr-1"></i>
                                        ${dataFormatada}
                                    </span>
                                </div>
                            </div>
                            <div class="ml-4 flex-shrink-0">
                                <div class="flex space-x-2">
                                    <button onclick="editDemanda(${demanda.id})" class="text-blue-600 hover:text-blue-800" title="Editar">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <button onclick="deleteDemanda(${demanda.id})" class="text-red-600 hover:text-red-800" title="Excluir">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                `;
            }).join('');

            const totalEl = document.getElementById('totalDemandasLista');
            if (totalEl) totalEl.textContent = `${demandas.length} demanda${demandas.length !== 1 ? 's' : ''}`;
        }

        // Obter configuração do status
        function getStatusConfig(status) {
            const configs = {
                pendente: { label: 'Pendente', bgColor: 'bg-yellow-100', textColor: 'text-yellow-800' },
                em_andamento: { label: 'Em Andamento', bgColor: 'bg-blue-100', textColor: 'text-blue-800' },
                resolvido: { label: 'Resolvido', bgColor: 'bg-green-100', textColor: 'text-green-800' },
                cancelado: { label: 'Cancelado', bgColor: 'bg-red-100', textColor: 'text-red-800' }
            };
            return configs[status] || configs.pendente;
        }

        // Obter configuração da prioridade
        function getPrioridadeConfig(prioridade) {
            const configs = {
                baixa: { label: 'Baixa', bgColor: 'bg-gray-100', textColor: 'text-gray-800' },
                media: { label: 'Média', bgColor: 'bg-blue-100', textColor: 'text-blue-800' },
                alta: { label: 'Alta', bgColor: 'bg-yellow-100', textColor: 'text-yellow-800' },
                urgente: { label: 'Urgente', bgColor: 'bg-red-100', textColor: 'text-red-800' }
            };
            return configs[prioridade] || configs.media;
        }

        // Obter label da categoria
        function getCategoriaLabel(categoria) {
            const labels = {
                infraestrutura: 'Infraestrutura',
                saude: 'Saúde',
                educacao: 'Educação',
                seguranca: 'Segurança',
                outros: 'Outros'
            };
            return labels[categoria] || categoria;
        }

        // Atualizar estatísticas
        function updateStats() {
            const total = demandas.length;
            const resolvidas = demandas.filter(d => d.status === 'resolvido').length;
            const andamento = demandas.filter(d => d.status === 'em_andamento').length;
            const pendentes = demandas.filter(d => d.status === 'pendente').length;
            const urgentes = demandas.filter(d => d.prioridade === 'urgente').length;
            const altaPrioridade = demandas.filter(d => d.prioridade === 'alta').length;

            const agora = new Date();
            const demandasMes = demandas.filter(d => {
                const data = new Date(d.created_at);
                return data.getMonth() === agora.getMonth() && data.getFullYear() === agora.getFullYear();
            }).length;

            const taxaResolucao = total > 0 ? Math.round((resolvidas / total) * 100) : 0;

            const totalEl = document.getElementById('totalDemandas');
            const mesEl = document.getElementById('demandasMes');
            const resolvidasEl = document.getElementById('demandasResolvidas');
            const taxaEl = document.getElementById('taxaResolucao');
            const andamentoEl = document.getElementById('demandasAndamento');
            const pendentesEl = document.getElementById('demandasPendentes');
            const urgentesEl = document.getElementById('demandasUrgentes');
            const altaEl = document.getElementById('demandasAltaPrioridade');
            
            if (totalEl) totalEl.textContent = total;
            if (mesEl) mesEl.textContent = demandasMes;
            if (resolvidasEl) resolvidasEl.textContent = resolvidas;
            if (taxaEl) taxaEl.textContent = taxaResolucao + '%';
            if (andamentoEl) andamentoEl.textContent = andamento;
            if (pendentesEl) pendentesEl.textContent = pendentes;
            if (urgentesEl) urgentesEl.textContent = urgentes;
            if (altaEl) altaEl.textContent = altaPrioridade;
        }

        // Funções já definidas no escopo global acima, atualizar para incluir lógica de edição
        // Redefinir para incluir a lógica completa de edição
        window.openDemandaModal = function(demanda = null) {
            editingDemanda = demanda;
            const modal = document.getElementById('demandaModal');
            const form = document.getElementById('demandaForm');
            const title = document.getElementById('modalTitle');

            if (!modal || !form || !title) {
                console.error('⚠️ Modal, formulário ou título não encontrado');
                return;
            }

            if (demanda) {
                title.textContent = 'Editar Demanda';
                form.titulo.value = demanda.assunto || demanda.titulo || '';
                form.categoria.value = demanda.categoria || '';
                form.status.value = demanda.status || 'pendente';
                form.prioridade.value = demanda.prioridade || 'media';
                form.descricao.value = demanda.descricao || '';
                const localizacao = demanda.localizacao ? (typeof demanda.localizacao === 'string' ? demanda.localizacao : demanda.localizacao.endereco || '') : '';
                form.localizacao.value = localizacao;
                form.responsavel.value = demanda.responsavel_id || '';
            } else {
                title.textContent = 'Nova Demanda';
                form.reset();
            }

            // Remover classe hidden e garantir display com !important
            modal.classList.remove('hidden');
            modal.style.setProperty('display', 'flex', 'important');
            modal.style.setProperty('align-items', 'center', 'important');
            modal.style.setProperty('justify-content', 'center', 'important');
            modal.style.setProperty('z-index', '9999', 'important');
            console.log('✅ Modal de demanda aberto');
        };

        window.closeDemandaModal = function() {
            const modal = document.getElementById('demandaModal');
            if (modal) {
                modal.classList.add('hidden');
                modal.style.display = 'none';
            }
            editingDemanda = null;
        };

        // Salvar demanda
        async function saveDemanda(event) {
            event.preventDefault();
            
            try {
                const token = localStorage.getItem('token');
                const formData = new FormData(event.target);
                const data = Object.fromEntries(formData);
                
                // Converter titulo para assunto (formato da API)
                if (data.titulo) {
                    data.assunto = data.titulo;
                    delete data.titulo;
                }
                
                // Converter localizacao para objeto se necessário
                if (data.localizacao && typeof data.localizacao === 'string') {
                    data.localizacao = {
                        endereco: data.localizacao
                    };
                }

                const url = editingDemanda ? `${API_BASE}/demandas/${editingDemanda.id}` : `${API_BASE}/demandas`;
                const method = editingDemanda ? 'PUT' : 'POST';

                const response = await fetch(url, {
                    method,
                    headers: {
                        'Content-Type': 'application/json',
                        'Authorization': `Bearer ${token}`
                    },
                    body: JSON.stringify(data)
                });

                if (response.ok) {
                    showToast(editingDemanda ? 'Demanda atualizada com sucesso!' : 'Demanda criada com sucesso!', 'success');
                    closeDemandaModal();
                    loadDemandas();
                } else {
                    const error = await response.json();
                    throw new Error(error.error || 'Erro ao salvar demanda');
                }
            } catch (error) {
                console.error('Erro ao salvar demanda:', error);
                showToast(error.message, 'error');
            }
        }

        // Editar demanda - função no escopo global
        window.editDemanda = function(id) {
            const demanda = demandas.find(d => d.id === id);
            if (demanda) {
                window.openDemandaModal(demanda);
            }
        };

        // Excluir demanda - função no escopo global
        window.deleteDemanda = async function(id) {
            if (!confirm('Tem certeza que deseja excluir esta demanda?')) {
                return;
            }

            try {
                const token = localStorage.getItem('token');
                const response = await fetch(`${API_BASE}/demandas/${id}`, {
                    method: 'DELETE',
                    headers: { 'Authorization': `Bearer ${token}` }
                });

                if (response.ok) {
                    showToast('Demanda excluída com sucesso!', 'success');
                    loadDemandas();
                } else {
                    throw new Error('Erro ao excluir demanda');
                }
            } catch (error) {
                console.error('Erro ao excluir demanda:', error);
                showToast('Erro ao excluir demanda', 'error');
            }
        }

        // Aplicar filtros
        function aplicarFiltros() {
            const status = document.getElementById('filtroStatus')?.value || '';
            const categoria = document.getElementById('filtroCategoria')?.value || '';
            const prioridade = document.getElementById('filtroPrioridade')?.value || '';

            let demandasFiltradas = [...demandas];

            if (status) {
                demandasFiltradas = demandasFiltradas.filter(d => d.status === status);
            }
            if (categoria) {
                demandasFiltradas = demandasFiltradas.filter(d => d.categoria === categoria);
            }
            if (prioridade) {
                demandasFiltradas = demandasFiltradas.filter(d => d.prioridade === prioridade);
            }

            // Renderizar demandas filtradas
            const container = document.getElementById('demandasList');
            const emptyEl = document.getElementById('emptyState');
            
            if (!container) return;
            
            if (demandasFiltradas.length === 0) {
                if (emptyEl) emptyEl.classList.remove('hidden');
                container.innerHTML = '';
                return;
            }

            if (emptyEl) emptyEl.classList.add('hidden');
            
            // Reutilizar a função de renderização com as demandas filtradas
            const demandasOriginais = demandas;
            demandas = demandasFiltradas;
            renderDemandas();
            demandas = demandasOriginais;
        }

        // Exportar demandas
        function exportarDemandas() {
            const csvContent = "data:text/csv;charset=utf-8," + 
                "Título,Categoria,Status,Prioridade,Localização,Data Criação\n" +
                demandas.map(d => 
                    `"${d.assunto || d.titulo || ''}","${getCategoriaLabel(d.categoria)}","${getStatusConfig(d.status).label}","${getPrioridadeConfig(d.prioridade).label}","${d.localizacao ? (typeof d.localizacao === 'string' ? d.localizacao : d.localizacao.endereco || '') : ''}","${new Date(d.created_at).toLocaleDateString('pt-BR')}"`
                ).join("\n");
            
            const encodedUri = encodeURI(csvContent);
            const link = document.createElement("a");
            link.setAttribute("href", encodedUri);
            link.setAttribute("download", "demandas.csv");
            document.body.appendChild(link);
            link.click();
            document.body.removeChild(link);
        }

        // Toast function
        function showToast(message, type = 'info') {
            const toast = document.createElement('div');
            const iconClass = type === 'error' ? 'exclamation-circle' : type === 'success' ? 'check-circle' : 'info-circle';
            
            toast.className = `fixed top-4 right-4 z-50 px-6 py-3 rounded-lg text-white font-medium shadow-lg transform transition-all duration-300 translate-x-full ${
                type === 'error' ? 'bg-red-500' : type === 'success' ? 'bg-green-500' : 'bg-blue-500'
            }`;
            
            toast.innerHTML = `
                <div class="flex items-center">
                    <i class="fas fa-${iconClass} mr-2"></i>
                    ${message}
                </div>
            `;
            
            document.body.appendChild(toast);
            
            // Animar entrada
            setTimeout(() => {
                toast.classList.remove('translate-x-full');
            }, 100);
            
            // Remover após 3 segundos
            setTimeout(() => {
                toast.classList.add('translate-x-full');
                setTimeout(() => {
                    if (document.body.contains(toast)) {
                        document.body.removeChild(toast);
                    }
                }, 300);
            }, 3000);
        }

        // Inicializar página
        document.addEventListener('DOMContentLoaded', function() {
            loadUserInfo();
            loadDemandas();
            
            // Event listeners
            const form = document.getElementById('demandaForm');
            if (form) {
                form.addEventListener('submit', saveDemanda);
            }
            
            // Atualizar a cada 5 minutos
            setInterval(loadDemandas, 300000);
            
            // Fechar modal ao clicar fora
            document.addEventListener('click', function(e) {
                const modal = document.getElementById('demandaModal');
                if (modal && e.target === modal) {
                    window.closeDemandaModal();
                }
            });
        });
    </script>
    <?php