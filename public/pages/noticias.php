<div class="main-content">
    <!-- Page Header -->
    <div class="page-header">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="page-title">Gestão de Notícias</h1>
                <p class="mt-2 text-gray-600">Gerencie notícias e comunique com os cidadãos</p>
            </div>
            <button id="btnNovaNoticia" class="bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 text-white px-6 py-3 rounded-lg font-medium shadow-lg hover:shadow-xl transition-all duration-200 flex items-center">
                <i class="fas fa-plus mr-2"></i>
                Nova Notícia
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
                    <p class="text-sm font-medium text-gray-600">Publicadas</p>
                    <p class="text-2xl font-bold text-gray-900" id="statsPublicadas">0</p>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-xl shadow-sm p-6 card-hover">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-gray-100">
                    <i class="fas fa-edit text-gray-600 text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Rascunhos</p>
                    <p class="text-2xl font-bold text-gray-900" id="statsRascunhos">0</p>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-xl shadow-sm p-6 card-hover">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-blue-100">
                    <i class="fas fa-eye text-blue-600 text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Visualizações</p>
                    <p class="text-2xl font-bold text-gray-900" id="statsVisualizacoes">0</p>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-xl shadow-sm p-6 card-hover">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-yellow-100">
                    <i class="fas fa-heart text-yellow-600 text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Curtidas</p>
                    <p class="text-2xl font-bold text-gray-900" id="statsCurtidas">0</p>
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
                    <option value="rascunho">Rascunho</option>
                    <option value="publicado">Publicado</option>
                    <option value="arquivado">Arquivado</option>
                </select>
            </div>
            <div class="flex items-center space-x-2">
                <label class="text-sm font-medium text-gray-700">Categoria:</label>
                <select id="categoriaFilter" class="border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="">Todas</option>
                    <option value="Política">Política</option>
                    <option value="Obras">Obras</option>
                    <option value="Eventos">Eventos</option>
                    <option value="Geral">Geral</option>
                </select>
            </div>
            <button onclick="clearFilters()" class="text-sm text-gray-500 hover:text-gray-700 flex items-center">
                <i class="fas fa-times mr-1"></i>
                Limpar Filtros
            </button>
        </div>
    </div>

    <!-- News Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6" id="newsGrid">
        <!-- News cards will be loaded here -->
    </div>

    <!-- Loading State -->
    <div id="loadingState" class="text-center py-12">
        <div class="inline-block animate-spin rounded-full h-8 w-8 border-b-2 border-blue-600"></div>
        <p class="mt-2 text-gray-600">Carregando notícias...</p>
    </div>

    <!-- Empty State -->
    <div id="emptyState" class="text-center py-12 hidden">
        <i class="fas fa-newspaper text-6xl text-gray-300 mb-4"></i>
        <h3 class="text-lg font-medium text-gray-900 mb-2">Nenhuma notícia encontrada</h3>
        <p class="text-gray-600 mb-6">Comece criando sua primeira notícia.</p>
        <button id="btnNovaNoticia2" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg font-medium">
            <i class="fas fa-plus mr-2"></i>
            Nova Notícia
        </button>
    </div>
</div>

<!-- News Modal -->
<div id="noticiaModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
    <div class="relative top-10 mx-auto p-5 border w-11/12 md:w-4/5 lg:w-3/4 xl:w-2/3 shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-medium text-gray-900" id="modalTitle">Nova Notícia</h3>
                <button onclick="closeNoticiaModal()" class="text-gray-400 hover:text-gray-600">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>
            <form id="noticiaForm" class="space-y-4">
                <input type="hidden" id="noticiaId">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Título *</label>
                    <input type="text" id="titulo" required class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Resumo</label>
                    <textarea id="resumo" rows="2" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Breve resumo da notícia..."></textarea>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Categoria</label>
                        <select id="categoria" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option value="">Selecione a categoria</option>
                            <option value="Política">Política</option>
                            <option value="Obras">Obras</option>
                            <option value="Eventos">Eventos</option>
                            <option value="Geral">Geral</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                        <select id="status" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option value="rascunho">Rascunho</option>
                            <option value="publicado">Publicado</option>
                            <option value="arquivado">Arquivado</option>
                        </select>
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Imagem de Destaque</label>
                    <input type="file" id="imagem" accept="image/*" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <p class="text-xs text-gray-500 mt-1">Formatos aceitos: JPG, PNG (máx. 5MB)</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Conteúdo *</label>
                    <textarea id="conteudo" required rows="8" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Escreva o conteúdo da notícia aqui..."></textarea>
                </div>
                <div class="flex justify-end space-x-3 pt-4">
                    <button type="button" onclick="closeNoticiaModal()" class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-500">
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
window.openNoticiaModal = function(noticia = null) {
    const modal = document.getElementById('noticiaModal');
    const form = document.getElementById('noticiaForm');
    const title = document.getElementById('modalTitle');

    if (!modal || !form || !title) {
        console.error('⚠️ Modal, formulário ou título não encontrado');
        return;
    }

    if (noticia) {
        title.textContent = 'Editar Notícia';
        const noticiaId = document.getElementById('noticiaId');
        if (noticiaId) noticiaId.value = noticia.id;
        const titulo = document.getElementById('titulo');
        if (titulo) titulo.value = noticia.titulo || '';
        const resumo = document.getElementById('resumo');
        if (resumo) resumo.value = noticia.resumo || '';
        const categoria = document.getElementById('categoria');
        if (categoria) categoria.value = noticia.categoria || '';
        const status = document.getElementById('status');
        if (status) status.value = noticia.status || 'rascunho';
        const conteudo = document.getElementById('conteudo');
        if (conteudo) conteudo.value = noticia.conteudo || '';
    } else {
        title.textContent = 'Nova Notícia';
        form.reset();
        const noticiaId = document.getElementById('noticiaId');
        if (noticiaId) noticiaId.value = '';
    }

    // Remover classe hidden e garantir display com !important
    modal.classList.remove('hidden');
    modal.style.setProperty('display', 'flex', 'important');
    modal.style.setProperty('align-items', 'center', 'important');
    modal.style.setProperty('justify-content', 'center', 'important');
    modal.style.setProperty('z-index', '9999', 'important');
    console.log('✅ Modal de notícia aberto');
};

window.closeNoticiaModal = function() {
    const modal = document.getElementById('noticiaModal');
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
    
    let noticias = [];
    let filteredNoticias = [];

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
        loadNoticias();
        setupEventListeners();
    });

    function setupEventListeners() {
        const searchInput = document.getElementById('searchInput');
        if (searchInput) {
            searchInput.addEventListener('input', filterNoticias);
        }
        
        const statusFilter = document.getElementById('statusFilter');
        if (statusFilter) {
            statusFilter.addEventListener('change', filterNoticias);
        }
        
        const categoriaFilter = document.getElementById('categoriaFilter');
        if (categoriaFilter) {
            categoriaFilter.addEventListener('change', filterNoticias);
        }
        
        const noticiaForm = document.getElementById('noticiaForm');
        if (noticiaForm) {
            noticiaForm.addEventListener('submit', handleFormSubmit);
        }
    }

    async function loadNoticias() {
        const loadingState = document.getElementById('loadingState');
        const maxWaitTime = 10000; // 10 segundos máximo
        const timeoutId = setTimeout(() => {
            console.warn('⚠️ Timeout ao carregar notícias. Usando dados mock.');
            if (loadingState) loadingState.classList.add('hidden');
            noticias = getMockNoticias();
            filteredNoticias = [...noticias];
            renderNoticias();
            updateStats();
        }, maxWaitTime);

        try {
            const token = localStorage.getItem('token');
            if (!token) {
                clearTimeout(timeoutId);
                noticias = getMockNoticias();
                filteredNoticias = [...noticias];
                renderNoticias();
                updateStats();
                return;
            }

            const controller = new AbortController();
            const timeoutId2 = setTimeout(() => controller.abort(), 8000);

            const response = await fetch(`${API_BASE}/noticias`, {
                headers: {
                    'Authorization': `Bearer ${token}`
                },
                signal: controller.signal
            });

            clearTimeout(timeoutId);
            clearTimeout(timeoutId2);

            if (response.ok) {
                const data = await response.json();
                noticias = Array.isArray(data) ? data : (data.noticias || []);
                filteredNoticias = [...noticias];
                renderNoticias();
                updateStats();
            } else {
                noticias = getMockNoticias();
                filteredNoticias = [...noticias];
                renderNoticias();
                updateStats();
            }
        } catch (error) {
            clearTimeout(timeoutId);
            console.error('Erro ao carregar notícias:', error);
            if (loadingState) loadingState.classList.add('hidden');
            noticias = getMockNoticias();
            filteredNoticias = [...noticias];
            renderNoticias();
            updateStats();
        }
    }

    function getMockNoticias() {
        return [
            {
                id: 1,
                titulo: "Nova Praça é Inaugurada no Centro da Cidade",
                resumo: "A prefeitura inaugurou uma nova praça com área de lazer e equipamentos modernos",
                conteudo: "A nova praça foi inaugurada com a presença do prefeito e vereadores. A obra contou com investimento de R$ 500.000 e beneficia mais de 10.000 moradores da região central.",
                categoria: "Obras",
                status: "publicado",
                imagem: "praça-centro.jpg",
                data_publicacao: "2024-01-15",
                visualizacoes: 1250,
                curtidas: 89,
                autor: "João Silva"
            },
            {
                id: 2,
                titulo: "Sessão da Câmara Aprova Projeto de Lei",
                resumo: "Projeto que melhora o transporte público foi aprovado por unanimidade",
                conteudo: "O projeto de lei que visa melhorar o transporte público municipal foi aprovado por unanimidade na sessão de ontem. A lei prevê investimentos de R$ 2 milhões em melhorias.",
                categoria: "Política",
                status: "publicado",
                imagem: "sessao-camara.jpg",
                data_publicacao: "2024-01-12",
                visualizacoes: 890,
                curtidas: 45,
                autor: "Maria Santos"
            },
            {
                id: 3,
                titulo: "Evento Cultural no Parque Municipal",
                resumo: "Festival de música e arte acontece neste fim de semana",
                conteudo: "O parque municipal receberá neste fim de semana um festival de música e arte com apresentações gratuitas. O evento contará com food trucks e atividades para toda a família.",
                categoria: "Eventos",
                status: "rascunho",
                imagem: null,
                data_publicacao: null,
                visualizacoes: 0,
                curtidas: 0,
                autor: "Pedro Costa"
            }
        ];
    }

    function renderNoticias() {
        const container = document.getElementById('newsGrid');
        const loadingState = document.getElementById('loadingState');
        const emptyState = document.getElementById('emptyState');

        // Sempre esconder loading primeiro
        if (loadingState) {
            loadingState.classList.add('hidden');
        }

        if (!container) {
            console.error('⚠️ Container de notícias não encontrado');
            return;
        }

        if (!emptyState) {
            console.warn('⚠️ Empty state não encontrado');
        }

        if (filteredNoticias.length === 0) {
            if (emptyState) emptyState.classList.remove('hidden');
            container.innerHTML = '';
            return;
        }

        if (emptyState) emptyState.classList.add('hidden');

        container.innerHTML = filteredNoticias.map(noticia => `
            <div class="bg-white rounded-xl shadow-sm overflow-hidden card-hover fade-in">
                <div class="h-48 bg-gradient-to-br from-gray-200 to-gray-300 flex items-center justify-center">
                    ${noticia.imagem ? 
                        `<img src="/uploads/${noticia.imagem}" alt="${noticia.titulo}" class="w-full h-full object-cover">` :
                        `<i class="fas fa-image text-4xl text-gray-400"></i>`
                    }
                </div>
                <div class="p-6">
                    <div class="flex items-center justify-between mb-3">
                        <span class="px-3 py-1 rounded-full text-xs font-medium ${getStatusClass(noticia.status)}">
                            ${getStatusText(noticia.status)}
                        </span>
                        ${noticia.categoria ? `<span class="px-2 py-1 text-xs bg-gray-100 text-gray-700 rounded-full">${noticia.categoria}</span>` : ''}
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-2 line-clamp-2">${noticia.titulo}</h3>
                    <p class="text-gray-600 text-sm mb-4 line-clamp-3">${noticia.resumo || (noticia.conteudo ? noticia.conteudo.substring(0, 150) + '...' : '')}</p>
                    <div class="flex items-center justify-between text-sm text-gray-500 mb-4">
                        <span class="flex items-center">
                            <i class="fas fa-user mr-1"></i>
                            ${noticia.autor || 'Autor'}
                        </span>
                        <span class="flex items-center">
                            <i class="fas fa-calendar mr-1"></i>
                            ${noticia.data_publicacao ? formatDate(noticia.data_publicacao) : 'Não publicado'}
                        </span>
                    </div>
                    <div class="flex items-center justify-between text-sm text-gray-500 mb-4">
                        <span class="flex items-center">
                            <i class="fas fa-eye mr-1"></i>
                            ${noticia.visualizacoes || 0}
                        </span>
                        <span class="flex items-center">
                            <i class="fas fa-heart mr-1"></i>
                            ${noticia.curtidas || 0}
                        </span>
                    </div>
                    <div class="flex items-center justify-between">
                        <button onclick="viewNoticia(${noticia.id})" class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                            <i class="fas fa-eye mr-1"></i>
                            Ver Notícia
                        </button>
                        <div class="flex items-center space-x-2">
                            <button onclick="editNoticia(${noticia.id})" class="p-2 text-blue-600 hover:bg-blue-50 rounded-lg transition-colors" title="Editar">
                                <i class="fas fa-edit"></i>
                            </button>
                            <button onclick="deleteNoticia(${noticia.id})" class="p-2 text-red-600 hover:bg-red-50 rounded-lg transition-colors" title="Excluir">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        `).join('');
    }

    function getStatusClass(status) {
        const classes = {
            'rascunho': 'bg-gray-600 text-white',
            'publicado': 'bg-green-600 text-white',
            'arquivado': 'bg-red-600 text-white'
        };
        return classes[status] || 'bg-gray-600 text-white';
    }

    function getStatusText(status) {
        const texts = {
            'rascunho': 'Rascunho',
            'publicado': 'Publicado',
            'arquivado': 'Arquivado'
        };
        return texts[status] || 'Rascunho';
    }

    function formatDate(dateString) {
        if (!dateString) return 'Sem data';
        const date = new Date(dateString);
        return date.toLocaleDateString('pt-BR');
    }

    function updateStats() {
        const stats = {
            publicadas: noticias.filter(n => n.status === 'publicado').length,
            rascunhos: noticias.filter(n => n.status === 'rascunho').length,
            visualizacoes: noticias.reduce((sum, n) => sum + (n.visualizacoes || 0), 0),
            curtidas: noticias.reduce((sum, n) => sum + (n.curtidas || 0), 0)
        };

        const statsPublicadas = document.getElementById('statsPublicadas');
        const statsRascunhos = document.getElementById('statsRascunhos');
        const statsVisualizacoes = document.getElementById('statsVisualizacoes');
        const statsCurtidas = document.getElementById('statsCurtidas');

        if (statsPublicadas) statsPublicadas.textContent = stats.publicadas;
        if (statsRascunhos) statsRascunhos.textContent = stats.rascunhos;
        if (statsVisualizacoes) statsVisualizacoes.textContent = stats.visualizacoes;
        if (statsCurtidas) statsCurtidas.textContent = stats.curtidas;
    }

    function filterNoticias() {
        const searchInput = document.getElementById('searchInput');
        const statusFilter = document.getElementById('statusFilter');
        const categoriaFilter = document.getElementById('categoriaFilter');

        const searchTerm = searchInput ? searchInput.value.toLowerCase() : '';
        const statusValue = statusFilter ? statusFilter.value : '';
        const categoriaValue = categoriaFilter ? categoriaFilter.value : '';

        filteredNoticias = noticias.filter(noticia => {
            const matchesSearch = !searchTerm || 
                noticia.titulo.toLowerCase().includes(searchTerm) ||
                (noticia.resumo && noticia.resumo.toLowerCase().includes(searchTerm)) ||
                (noticia.conteudo && noticia.conteudo.toLowerCase().includes(searchTerm));
            const matchesStatus = !statusValue || noticia.status === statusValue;
            const matchesCategoria = !categoriaValue || noticia.categoria === categoriaValue;

            return matchesSearch && matchesStatus && matchesCategoria;
        });

        renderNoticias();
    }

    window.clearFilters = function() {
        const searchInput = document.getElementById('searchInput');
        const statusFilter = document.getElementById('statusFilter');
        const categoriaFilter = document.getElementById('categoriaFilter');

        if (searchInput) searchInput.value = '';
        if (statusFilter) statusFilter.value = '';
        if (categoriaFilter) categoriaFilter.value = '';
        filterNoticias();
    };

    // Funções já definidas no escopo global acima, apenas redefinir se necessário para atualizar
    // (já estão definidas antes do IIFE)

    window.editNoticia = function(id) {
        const noticia = noticias.find(n => n.id === id);
        if (noticia) {
            window.openNoticiaModal(noticia);
        }
    };

    window.viewNoticia = function(id) {
        window.open(`noticia-publica.html?id=${id}`, '_blank');
    };

    window.deleteNoticia = async function(id) {
        if (confirm('Tem certeza que deseja excluir esta notícia?')) {
            try {
                const token = localStorage.getItem('token');
                if (!token) {
                    noticias = noticias.filter(n => n.id !== id);
                    filteredNoticias = [...noticias];
                    renderNoticias();
                    updateStats();
                    showToast('Notícia excluída com sucesso!', 'success');
                    return;
                }

                const response = await fetch(`${API_BASE}/noticias/${id}`, {
                    method: 'DELETE',
                    headers: {
                        'Authorization': `Bearer ${token}`
                    }
                });

                if (response.ok) {
                    showToast('Notícia excluída com sucesso!', 'success');
                    loadNoticias();
                } else {
                    showToast('Erro ao excluir notícia', 'error');
                }
            } catch (error) {
                console.error('Erro ao excluir notícia:', error);
                showToast('Erro ao excluir notícia', 'error');
            }
        }
    };

    async function handleFormSubmit(e) {
        e.preventDefault();

        const titulo = document.getElementById('titulo');
        const resumo = document.getElementById('resumo');
        const categoria = document.getElementById('categoria');
        const status = document.getElementById('status');
        const conteudo = document.getElementById('conteudo');
        const noticiaId = document.getElementById('noticiaId');

        if (!titulo || !conteudo) return;

        const formData = {
            titulo: titulo.value,
            resumo: resumo ? resumo.value : '',
            categoria: categoria ? categoria.value : '',
            status: status ? status.value : 'rascunho',
            conteudo: conteudo.value
        };

        const id = noticiaId ? noticiaId.value : '';
        const isEdit = !!id;

        try {
            const token = localStorage.getItem('token');
            if (!token) {
                if (isEdit) {
                    const index = noticias.findIndex(n => n.id == id);
                    if (index !== -1) {
                        noticias[index] = { ...noticias[index], ...formData };
                    }
                } else {
                    const newId = noticias.length > 0 ? Math.max(...noticias.map(n => n.id)) + 1 : 1;
                    noticias.push({ ...formData, id: newId, data_publicacao: new Date().toISOString().split('T')[0], visualizacoes: 0, curtidas: 0, autor: 'Usuário' });
                }
                filteredNoticias = [...noticias];
                renderNoticias();
                updateStats();
                showToast(isEdit ? 'Notícia atualizada com sucesso!' : 'Notícia criada com sucesso!', 'success');
                closeNoticiaModal();
                return;
            }

            const url = isEdit ? `${API_BASE}/noticias/${id}` : `${API_BASE}/noticias`;
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
                showToast(isEdit ? 'Notícia atualizada com sucesso!' : 'Notícia criada com sucesso!', 'success');
                closeNoticiaModal();
                loadNoticias();
            } else {
                const error = await response.json();
                showToast(error.message || 'Erro ao salvar notícia', 'error');
            }
        } catch (error) {
            console.error('Erro ao salvar notícia:', error);
            showToast('Erro ao salvar notícia', 'error');
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
