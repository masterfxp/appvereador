<div class="main-content">
                        <!-- Page Header -->
    <div class="page-header">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="page-title">Gerenciamento de Clientes</h1>
                <p class="mt-2 text-gray-600">Gerencie clientes, contratos e licenças do sistema</p>
                                </div>
            <div class="flex items-center space-x-3">
                <button onclick="openTipoContratoModal()" class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg font-medium shadow-lg hover:shadow-xl transition-all duration-200 flex items-center">
                                        <i class="fas fa-file-contract mr-2"></i>
                                        Tipos de Contrato
                                    </button>
                <button onclick="openClienteModal()" class="bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 text-white px-6 py-3 rounded-lg font-medium shadow-lg hover:shadow-xl transition-all duration-200 flex items-center">
                                        <i class="fas fa-plus mr-2"></i>
                                        Novo Cliente
                                    </button>
                                </div>
                            </div>
                        </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <div class="bg-white rounded-xl shadow-sm p-6 card-hover">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-blue-100">
                    <i class="fas fa-building text-blue-600 text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Total de Clientes</p>
                    <p class="text-2xl font-bold text-gray-900" id="totalClientes">0</p>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-xl shadow-sm p-6 card-hover">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-green-100">
                    <i class="fas fa-check-circle text-green-600 text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Clientes Ativos</p>
                    <p class="text-2xl font-bold text-gray-900" id="clientesAtivos">0</p>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-xl shadow-sm p-6 card-hover">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-purple-100">
                    <i class="fas fa-users text-purple-600 text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Total de Usuários</p>
                    <p class="text-2xl font-bold text-gray-900" id="totalUsuarios">0</p>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-xl shadow-sm p-6 card-hover">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-orange-100">
                    <i class="fas fa-file-contract text-orange-600 text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Contratos Ativos</p>
                    <p class="text-2xl font-bold text-gray-900" id="contratosAtivos">0</p>
                </div>
            </div>
        </div>
    </div>

                        <!-- Main Content -->
    <div class="bg-white rounded-xl shadow-sm overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200">
            <div class="flex justify-between items-center">
                <h3 class="text-lg font-semibold text-gray-900">Clientes Cadastrados</h3>
                                    <div class="flex items-center space-x-4">
                                        <span class="text-sm text-gray-500" id="totalClientesLista">0 clientes</span>
                                        <a href="#" class="text-blue-600 hover:text-blue-800 text-sm flex items-center">
                                            <i class="fas fa-download mr-1"></i>
                                            Exportar
                                        </a>
                </div>
                                    </div>
                                </div>

                                <!-- Loading State -->
                                <div id="loadingClientes" class="text-center py-8 hidden">
            <div class="inline-block animate-spin rounded-full h-8 w-8 border-b-2 border-blue-600"></div>
                                    <p class="mt-2 text-gray-500">Carregando clientes...</p>
                                </div>

                                <!-- Empty State -->
                                <div id="emptyState" class="text-center py-12 hidden">
            <i class="fas fa-building text-4xl text-gray-300 mb-4"></i>
            <h3 class="text-lg font-medium text-gray-900 mb-2">Nenhum cliente cadastrado</h3>
            <p class="text-gray-500 mb-6">Comece criando um novo cliente.</p>
            <button onclick="openClienteModal()" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg font-medium">
                <i class="fas fa-plus mr-2"></i>
                Novo Cliente
            </button>
                                </div>

                                <!-- Table -->
        <div class="overflow-hidden" id="clientesTable" style="display: none;">
                                    <table class="min-w-full divide-y divide-gray-300">
                                        <thead class="bg-gray-50">
                                            <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Empresa</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Contato</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tipo de Contrato</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Validade</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Usuários</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Ações</th>
                                            </tr>
                                        </thead>
                                        <tbody id="clientesTableBody" class="bg-white divide-y divide-gray-200">
                                            <!-- Clientes serão carregados aqui -->
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>

    <!-- Modal de Novo Cliente -->
    <div id="clienteModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
            <div class="flex justify-between items-center pb-3">
                <h3 class="text-lg font-bold text-gray-900">Novo Cliente</h3>
                <button onclick="closeClienteModal()" class="text-gray-400 hover:text-gray-600">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <form id="clienteForm">
                <div class="mb-4">
                    <label for="clienteNome" class="block text-sm font-medium text-gray-700">Nome do Contato *</label>
                    <input type="text" id="clienteNome" name="nome" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500" required>
                </div>
                <div class="mb-4">
                    <label for="clienteEmail" class="block text-sm font-medium text-gray-700">E-mail *</label>
                    <input type="email" id="clienteEmail" name="email" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500" required>
                </div>
                <div class="mb-4">
                    <label for="clienteTelefone" class="block text-sm font-medium text-gray-700">Telefone</label>
                    <input type="tel" id="clienteTelefone" name="telefone" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                </div>
                <div class="mb-4">
                    <label for="clienteEmpresa" class="block text-sm font-medium text-gray-700">Empresa *</label>
                    <input type="text" id="clienteEmpresa" name="empresa" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500" required>
                </div>
                <div class="mb-4">
                    <label for="clienteTipoContrato" class="block text-sm font-medium text-gray-700">Tipo de Contrato *</label>
                    <select id="clienteTipoContrato" name="tipo_contrato" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500" required>
                        <option value="">Selecione um tipo...</option>
                        <option value="APENAS TESTE">APENAS TESTE</option>
                        <option value="BASICO">BÁSICO</option>
                        <option value="INTERMEDIARIO">INTERMEDIÁRIO</option>
                        <option value="AVANCADO">AVANÇADO</option>
                        <option value="PREMIUM">PREMIUM</option>
                        <option value="VITALICIO">VITALÍCIO</option>
                    </select>
                </div>
                <div class="mb-4">
                    <label for="clienteDataInicio" class="block text-sm font-medium text-gray-700">Data Inicial do Contrato</label>
                    <input type="date" id="clienteDataInicio" name="data_inicio_contrato" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                    <p class="mt-1 text-xs text-gray-500">Deixe em branco para usar a data atual</p>
                </div>
                <div class="mb-4">
                    <label for="clienteDataFim" class="block text-sm font-medium text-gray-700">Data Final do Contrato</label>
                    <input type="date" id="clienteDataFim" name="data_fim_contrato" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                    <p class="mt-1 text-xs text-gray-500">Deixe em branco para calcular automaticamente</p>
                </div>
            <div class="flex justify-end space-x-3">
                <button type="button" onclick="closeClienteModal()" class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-200 rounded-md hover:bg-gray-300">Cancelar</button>
                    <button type="submit" class="px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded-md hover:bg-blue-700">Criar Cliente</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal Tipos de Contrato -->
    <div id="tipoContratoModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
        <div class="relative top-20 mx-auto p-5 border w-full max-w-2xl shadow-lg rounded-md bg-white">
            <div class="flex justify-between items-center pb-3">
                <h3 class="text-lg font-bold text-gray-900">Gerenciar Tipos de Contrato</h3>
                <button onclick="closeTipoContratoModal()" class="text-gray-400 hover:text-gray-600">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            
            <div class="mb-4">
                <button onclick="openNovoTipoContratoModal()" class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700 flex items-center">
                    <i class="fas fa-plus mr-2"></i>
                    Novo Tipo de Contrato
                </button>
            </div>
            
            <div id="tiposContratoList" class="space-y-3">
                <!-- Os tipos serão carregados aqui dinamicamente -->
            </div>
        </div>
    </div>

<!-- Modal Novo/Editar Tipo de Contrato -->
<div id="novoTipoContratoModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
    <div class="relative top-20 mx-auto p-5 border w-full max-w-lg shadow-lg rounded-md bg-white">
        <div class="flex justify-between items-center pb-3">
            <h3 class="text-lg font-bold text-gray-900" id="tipoContratoModalTitle">Novo Tipo de Contrato</h3>
            <button onclick="closeNovoTipoContratoModal()" class="text-gray-400 hover:text-gray-600">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <form id="tipoContratoForm">
            <input type="hidden" id="tipoContratoId">
            <div class="mb-4">
                <label for="tipoContratoNome" class="block text-sm font-medium text-gray-700 mb-2">Nome do Contrato *</label>
                <input type="text" id="tipoContratoNome" name="nome" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" required>
            </div>
            <div class="mb-4">
                <label for="tipoContratoMaxUsuarios" class="block text-sm font-medium text-gray-700 mb-2">Máximo de Usuários *</label>
                <input type="number" id="tipoContratoMaxUsuarios" name="max_usuarios" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" min="1" required>
            </div>
            <div class="mb-4">
                <label for="tipoContratoDiasValidade" class="block text-sm font-medium text-gray-700 mb-2">Dias de Validade *</label>
                <input type="number" id="tipoContratoDiasValidade" name="dias_validade" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" min="1" required>
                <p class="mt-1 text-xs text-gray-500">Número de dias que o contrato é válido</p>
            </div>
            <div class="mb-4">
                <label for="tipoContratoPrecoMensal" class="block text-sm font-medium text-gray-700 mb-2">Preço Mensal (R$) *</label>
                <input type="number" id="tipoContratoPrecoMensal" name="preco_mensal" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" min="0" step="0.01" required>
            </div>
            <div class="mb-4">
                <label for="tipoContratoDescricao" class="block text-sm font-medium text-gray-700 mb-2">Descrição</label>
                <textarea id="tipoContratoDescricao" name="descricao" rows="3" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Descrição opcional do tipo de contrato"></textarea>
            </div>
            <div class="flex justify-end space-x-3">
                <button type="button" onclick="closeNovoTipoContratoModal()" class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-200 rounded-md hover:bg-gray-300">Cancelar</button>
                <button type="submit" class="px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded-md hover:bg-blue-700">Salvar</button>
            </div>
        </form>
    </div>
</div>

<script>
// Definir funções no escopo global IMEDIATAMENTE para que estejam disponíveis quando os botões forem clicados
window.openClienteModal = function() {
    const modal = document.getElementById('clienteModal');
    if (modal) {
        modal.classList.remove('hidden');
        modal.style.setProperty('display', 'flex', 'important');
        modal.style.setProperty('align-items', 'center', 'important');
        modal.style.setProperty('justify-content', 'center', 'important');
        modal.style.setProperty('z-index', '9999', 'important');
        const hoje = new Date().toISOString().split('T')[0];
        const dataInicio = document.getElementById('clienteDataInicio');
        if (dataInicio) dataInicio.value = hoje;
        console.log('✅ Modal de cliente aberto');
    }
};

window.closeClienteModal = function() {
    const modal = document.getElementById('clienteModal');
    const form = document.getElementById('clienteForm');
    if (modal) {
        modal.classList.add('hidden');
        modal.style.display = 'none';
    }
    if (form) form.reset();
};

window.openTipoContratoModal = function() {
    const modal = document.getElementById('tipoContratoModal');
    if (modal) {
        modal.classList.remove('hidden');
        modal.style.setProperty('display', 'flex', 'important');
        modal.style.setProperty('align-items', 'center', 'important');
        modal.style.setProperty('justify-content', 'center', 'important');
        modal.style.setProperty('z-index', '9999', 'important');
        console.log('✅ Modal de tipo de contrato aberto');
    }
};

window.closeTipoContratoModal = function() {
    const modal = document.getElementById('tipoContratoModal');
    const form = document.getElementById('tipoContratoForm');
    if (modal) {
        modal.classList.add('hidden');
        modal.style.display = 'none';
    }
    if (form) form.reset();
};

(function() {
    'use strict';
    const API_BASE = window.location.hostname === 'localhost' || window.location.hostname === '127.0.0.1'
        ? 'http://localhost:3000/api'
        : 'https://uniassessor.com.br/api';
    
    let clientes = [];
    let tiposContrato = [];

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
        loadClientes();
        loadTiposContratoParaCliente();
        setupEventListeners();
    });

    function setupEventListeners() {
        const clienteForm = document.getElementById('clienteForm');
        if (clienteForm) {
            clienteForm.addEventListener('submit', handleClienteFormSubmit);
        }

        const tipoContratoForm = document.getElementById('tipoContratoForm');
        if (tipoContratoForm) {
            tipoContratoForm.addEventListener('submit', handleTipoContratoFormSubmit);
        }
    }

    async function loadClientes() {
        try {
            const loadingEl = document.getElementById('loadingClientes');
            const emptyStateEl = document.getElementById('emptyState');
            const tableEl = document.getElementById('clientesTable');
            
            if (loadingEl) loadingEl.classList.remove('hidden');
            if (emptyStateEl) emptyStateEl.classList.add('hidden');
            if (tableEl) tableEl.style.display = 'none';

            const token = localStorage.getItem('token');
            const response = await fetch(`${API_BASE}/clientes`, {
                headers: token ? { 'Authorization': `Bearer ${token}` } : {}
            });

            if (response.ok) {
                clientes = await response.json();
                if (!Array.isArray(clientes)) {
                    clientes = [];
                }
                
                if (clientes.length > 0) {
                    clientes = await Promise.all(clientes.map(async (cliente) => {
                        if (!cliente.usuarios_count) {
                            try {
                                const usuariosResponse = await fetch(`${API_BASE}/usuarios?cliente_id=${cliente.id}`);
                                if (usuariosResponse.ok) {
                                    const usuarios = await usuariosResponse.json();
                                    cliente.usuarios_count = Array.isArray(usuarios) ? usuarios.length : 0;
                                } else {
                                    cliente.usuarios_count = 0;
                                }
                            } catch (e) {
                                cliente.usuarios_count = 0;
                            }
                        }
                        return cliente;
                    }));
                }
                
                renderClientes();
                updateStats();
            } else {
                clientes = getMockClientes();
                renderClientes();
                updateStats();
            }
        } catch (error) {
            console.error('Erro ao carregar clientes:', error);
            clientes = getMockClientes();
            renderClientes();
            updateStats();
        } finally {
            const loadingEl = document.getElementById('loadingClientes');
            if (loadingEl) loadingEl.classList.add('hidden');
        }
    }

    function getMockClientes() {
        return [
            {
                id: 1,
                nome: 'João Silva',
                email: 'joao@humaita.rs.gov.br',
                telefone: '(55) 99607-6851',
                empresa: 'Humaita RS',
                tipo_contrato: 'APENAS TESTE',
                data_inicio_contrato: '2024-01-01',
                data_fim_contrato: '2024-12-31',
                limite_usuarios: 4,
                usuarios_count: 3,
                ativo: true
            }
        ];
    }

    function renderClientes() {
        const tbody = document.getElementById('clientesTableBody');
        const table = document.getElementById('clientesTable');
        const emptyState = document.getElementById('emptyState');

        if (!tbody || !table || !emptyState) return;

        if (clientes.length === 0) {
            table.style.display = 'none';
            emptyState.classList.remove('hidden');
            return;
        }

        table.style.display = 'block';
        emptyState.classList.add('hidden');

        tbody.innerHTML = clientes.map(cliente => `
            <tr class="hover:bg-gray-50">
                <td class="px-6 py-4 whitespace-nowrap">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 h-10 w-10">
                            <div class="h-10 w-10 rounded-full bg-blue-500 flex items-center justify-center text-white font-medium">
                                ${(cliente.empresa || cliente.nome || 'C').charAt(0).toUpperCase()}
                            </div>
                        </div>
                        <div class="ml-4">
                            <div class="text-sm font-medium text-gray-900">${cliente.empresa || cliente.nome}</div>
                        </div>
                    </div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                    <div class="text-sm text-gray-900">${cliente.email || 'N/A'}</div>
                    <div class="text-sm text-gray-500">${cliente.telefone || 'N/A'}</div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                    <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800">
                        ${cliente.tipo_contrato || 'N/A'}
                    </span>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                    ${cliente.data_fim_contrato ? formatDate(cliente.data_fim_contrato) : 'N/A'}
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                    <div class="flex items-center">
                        <div class="text-sm text-gray-900">${cliente.usuarios_count || 0}/${cliente.limite_usuarios || 0}</div>
                        <div class="ml-2 w-16 bg-gray-200 rounded-full h-2">
                            <div class="bg-blue-600 h-2 rounded-full" style="width: ${cliente.limite_usuarios ? ((cliente.usuarios_count || 0) / cliente.limite_usuarios) * 100 : 0}%"></div>
                        </div>
                    </div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                    <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full ${cliente.ativo ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'}">
                        ${cliente.ativo ? 'ATIVO' : 'INATIVO'}
                    </span>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                    <button onclick="editCliente(${cliente.id})" class="text-blue-600 hover:text-blue-900 mr-3" title="Editar">
                        <i class="fas fa-edit"></i>
                    </button>
                    <button onclick="deleteCliente(${cliente.id})" class="text-red-600 hover:text-red-900" title="Excluir">
                        <i class="fas fa-trash"></i>
                    </button>
                </td>
            </tr>
        `).join('');
    }

    function formatDate(dateString) {
        if (!dateString) return 'Sem data';
        const date = new Date(dateString);
        return date.toLocaleDateString('pt-BR');
    }

    function updateStats() {
        const totalClientes = clientes.length;
        const clientesAtivos = clientes.filter(c => c.ativo).length;
        const totalUsuarios = clientes.reduce((sum, c) => sum + (c.usuarios_count || 0), 0);
        const contratosAtivos = clientes.filter(c => c.ativo && c.data_fim_contrato && new Date(c.data_fim_contrato) > new Date()).length;

        const totalClientesEl = document.getElementById('totalClientes');
        const clientesAtivosEl = document.getElementById('clientesAtivos');
        const totalUsuariosEl = document.getElementById('totalUsuarios');
        const contratosAtivosEl = document.getElementById('contratosAtivos');
        const totalClientesListaEl = document.getElementById('totalClientesLista');

        if (totalClientesEl) totalClientesEl.textContent = totalClientes;
        if (clientesAtivosEl) clientesAtivosEl.textContent = clientesAtivos;
        if (totalUsuariosEl) totalUsuariosEl.textContent = totalUsuarios;
        if (contratosAtivosEl) contratosAtivosEl.textContent = contratosAtivos;
        if (totalClientesListaEl) totalClientesListaEl.textContent = `${totalClientes} cliente${totalClientes !== 1 ? 's' : ''}`;
    }

    async function loadTiposContratoParaCliente() {
        try {
            const response = await fetch(`${API_BASE}/tipos-contrato`);
            if (response.ok) {
                const tipos = await response.json();
                populateTipoContratoSelect(tipos);
            } else {
                const tiposMock = [
                    { id: 1, nome: 'BÁSICO' },
                    { id: 2, nome: 'INTERMEDIÁRIO' },
                    { id: 3, nome: 'AVANÇADO' },
                    { id: 4, nome: 'PREMIUM' },
                    { id: 5, nome: 'VITALÍCIO' }
                ];
                populateTipoContratoSelect(tiposMock);
            }
        } catch (error) {
            console.error('Erro ao carregar tipos de contrato:', error);
            const tiposMock = [
                { id: 1, nome: 'BÁSICO' },
                { id: 2, nome: 'INTERMEDIÁRIO' },
                { id: 3, nome: 'AVANÇADO' }
            ];
            populateTipoContratoSelect(tiposMock);
        }
    }

    function populateTipoContratoSelect(tipos) {
        const select = document.getElementById('clienteTipoContrato');
        if (!select) return;
        
        select.innerHTML = '<option value="">Selecione um tipo...</option>';
        tipos.forEach(tipo => {
            const option = document.createElement('option');
            option.value = tipo.nome || tipo.id;
            option.textContent = tipo.nome || tipo.id;
            select.appendChild(option);
        });
    }

    // Funções já definidas no escopo global acima, atualizar para incluir lógica completa
    // Redefinir para incluir a lógica completa de carregamento
    window.openClienteModal = function() {
        const modal = document.getElementById('clienteModal');
        if (modal) {
            modal.classList.remove('hidden');
            modal.style.setProperty('display', 'flex', 'important');
            modal.style.setProperty('align-items', 'center', 'important');
            modal.style.setProperty('justify-content', 'center', 'important');
            modal.style.setProperty('z-index', '9999', 'important');
            const hoje = new Date().toISOString().split('T')[0];
            const dataInicio = document.getElementById('clienteDataInicio');
            if (dataInicio) dataInicio.value = hoje;
            if (typeof loadTiposContratoParaCliente === 'function') {
                loadTiposContratoParaCliente();
            }
            console.log('✅ Modal de cliente aberto');
        }
    };

    window.closeClienteModal = function() {
        const modal = document.getElementById('clienteModal');
        const form = document.getElementById('clienteForm');
        if (modal) {
            modal.classList.add('hidden');
            modal.style.display = 'none';
        }
        if (form) form.reset();
    };

    window.editCliente = function(id) {
        const cliente = clientes.find(c => c.id === id);
        if (cliente) {
            const nome = document.getElementById('clienteNome');
            const email = document.getElementById('clienteEmail');
            const telefone = document.getElementById('clienteTelefone');
            const empresa = document.getElementById('clienteEmpresa');
            const tipoContrato = document.getElementById('clienteTipoContrato');
            const dataInicio = document.getElementById('clienteDataInicio');
            const dataFim = document.getElementById('clienteDataFim');
            
            if (nome) nome.value = cliente.nome || '';
            if (email) email.value = cliente.email || '';
            if (telefone) telefone.value = cliente.telefone || '';
            if (empresa) empresa.value = cliente.empresa || '';
            if (tipoContrato) tipoContrato.value = cliente.tipo_contrato || '';
            if (dataInicio) dataInicio.value = cliente.data_inicio_contrato || '';
            if (dataFim) dataFim.value = cliente.data_fim_contrato || '';
            
            openClienteModal();
        }
    };

    window.deleteCliente = async function(id) {
        if (confirm('Tem certeza que deseja excluir este cliente?')) {
            try {
                const token = localStorage.getItem('token');
                if (!token) {
                    clientes = clientes.filter(c => c.id !== id);
                    renderClientes();
                    updateStats();
                    showToast('Cliente excluído com sucesso!', 'success');
                    return;
                }

                const response = await fetch(`${API_BASE}/clientes/${id}`, {
                    method: 'DELETE',
                    headers: {
                        'Authorization': `Bearer ${token}`
                    }
                });

                if (response.ok) {
                    showToast('Cliente excluído com sucesso!', 'success');
                    loadClientes();
                } else {
                    showToast('Erro ao excluir cliente', 'error');
                }
            } catch (error) {
                console.error('Erro ao excluir cliente:', error);
                showToast('Erro ao excluir cliente', 'error');
            }
        }
    };

    async function handleClienteFormSubmit(e) {
        e.preventDefault();
        
        const formData = new FormData(e.target);
        const clienteData = {
            nome: formData.get('nome'),
            email: formData.get('email'),
            telefone: formData.get('telefone'),
            empresa: formData.get('empresa'),
            tipo_contrato: formData.get('tipo_contrato'),
            data_inicio_contrato: formData.get('data_inicio_contrato'),
            data_fim_contrato: formData.get('data_fim_contrato'),
            ativo: true
        };

        try {
            const token = localStorage.getItem('token');
            if (!token) {
                const newId = clientes.length > 0 ? Math.max(...clientes.map(c => c.id)) + 1 : 1;
                clientes.push({ ...clienteData, id: newId, usuarios_count: 0, limite_usuarios: 10 });
                renderClientes();
                updateStats();
                showToast('Cliente criado com sucesso!', 'success');
                closeClienteModal();
                return;
            }

            const response = await fetch(`${API_BASE}/clientes`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Authorization': `Bearer ${token}`
                },
                body: JSON.stringify(clienteData)
            });

            if (response.ok) {
                showToast('Cliente criado com sucesso!', 'success');
                closeClienteModal();
                loadClientes();
            } else {
                const error = await response.json();
                showToast(error.message || 'Erro ao criar cliente', 'error');
            }
        } catch (error) {
            console.error('Erro ao criar cliente:', error);
            showToast('Erro ao criar cliente', 'error');
        }
    }

    // Funções já definidas no escopo global acima, atualizar para incluir lógica completa
    window.openTipoContratoModal = function() {
        const modal = document.getElementById('tipoContratoModal');
        if (modal) {
            modal.classList.remove('hidden');
            modal.style.setProperty('display', 'flex', 'important');
            modal.style.setProperty('align-items', 'center', 'important');
            modal.style.setProperty('justify-content', 'center', 'important');
            modal.style.setProperty('z-index', '9999', 'important');
            if (typeof loadTiposContrato === 'function') {
                loadTiposContrato();
            }
            console.log('✅ Modal de tipo de contrato aberto');
        }
    };

    window.closeTipoContratoModal = function() {
        const modal = document.getElementById('tipoContratoModal');
        const form = document.getElementById('tipoContratoForm');
        if (modal) {
            modal.classList.add('hidden');
            modal.style.display = 'none';
        }
        if (form) form.reset();
    };

    window.openNovoTipoContratoModal = function() {
        const modal = document.getElementById('novoTipoContratoModal');
        const title = document.getElementById('tipoContratoModalTitle');
        const form = document.getElementById('tipoContratoForm');
        const id = document.getElementById('tipoContratoId');
        
        if (modal) modal.classList.remove('hidden');
        if (title) title.textContent = 'Novo Tipo de Contrato';
        if (form) form.reset();
        if (id) id.value = '';
    };

    window.closeNovoTipoContratoModal = function() {
        const modal = document.getElementById('novoTipoContratoModal');
        if (modal) modal.classList.add('hidden');
    };

    async function loadTiposContrato() {
        try {
            const response = await fetch(`${API_BASE}/tipos-contrato`);
            if (response.ok) {
                tiposContrato = await response.json();
                renderTiposContrato();
            } else {
                tiposContrato = [
                    {
                        id: 1,
                        nome: 'BÁSICO',
                        max_usuarios: 5,
                        dias_validade: 365,
                        preco_mensal: 99.90,
                        descricao: 'Plano básico para pequenos gabinetes'
                    },
                    {
                        id: 2,
                        nome: 'INTERMEDIÁRIO',
                        max_usuarios: 15,
                        dias_validade: 365,
                        preco_mensal: 199.90,
                        descricao: 'Plano intermediário para gabinetes médios'
                    },
                    {
                        id: 3,
                        nome: 'AVANÇADO',
                        max_usuarios: 50,
                        dias_validade: 365,
                        preco_mensal: 399.90,
                        descricao: 'Plano avançado para grandes gabinetes'
                    }
                ];
                renderTiposContrato();
            }
        } catch (error) {
            console.error('Erro ao carregar tipos de contrato:', error);
            tiposContrato = [
                {
                    id: 1,
                    nome: 'BÁSICO',
                    max_usuarios: 5,
                    dias_validade: 365,
                    preco_mensal: 99.90,
                    descricao: 'Plano básico para pequenos gabinetes'
                }
            ];
            renderTiposContrato();
        }
    }

    function renderTiposContrato() {
        const container = document.getElementById('tiposContratoList');
        if (!container) return;
        
        container.innerHTML = tiposContrato.map(tipo => `
            <div class="bg-gray-50 rounded-lg p-4 border">
                <div class="flex justify-between items-start">
                    <div class="flex-1">
                        <h4 class="text-lg font-medium text-gray-900">${tipo.nome}</h4>
                        <div class="mt-2 grid grid-cols-2 gap-4 text-sm text-gray-600">
                            <div>
                                <span class="font-medium">Máx. Usuários:</span> ${tipo.max_usuarios}
                            </div>
                            <div>
                                <span class="font-medium">Validade:</span> ${tipo.dias_validade} dias
                            </div>
                            <div>
                                <span class="font-medium">Preço:</span> R$ ${(tipo.preco_mensal || 0).toFixed(2)}/mês
                            </div>
                        </div>
                        ${tipo.descricao ? `<p class="mt-2 text-sm text-gray-500">${tipo.descricao}</p>` : ''}
                    </div>
                    <div class="flex space-x-2 ml-4">
                        <button onclick="editTipoContrato(${tipo.id})" class="text-blue-600 hover:text-blue-800" title="Editar">
                            <i class="fas fa-edit"></i>
                        </button>
                        <button onclick="deleteTipoContrato(${tipo.id})" class="text-red-600 hover:text-red-800" title="Excluir">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                </div>
            </div>
        `).join('');
    }

    window.editTipoContrato = function(id) {
        const tipo = tiposContrato.find(t => t.id === id);
        if (tipo) {
            const title = document.getElementById('tipoContratoModalTitle');
            const idInput = document.getElementById('tipoContratoId');
            const nome = document.getElementById('tipoContratoNome');
            const maxUsuarios = document.getElementById('tipoContratoMaxUsuarios');
            const diasValidade = document.getElementById('tipoContratoDiasValidade');
            const precoMensal = document.getElementById('tipoContratoPrecoMensal');
            const descricao = document.getElementById('tipoContratoDescricao');
            
            if (title) title.textContent = 'Editar Tipo de Contrato';
            if (idInput) idInput.value = tipo.id;
            if (nome) nome.value = tipo.nome || '';
            if (maxUsuarios) maxUsuarios.value = tipo.max_usuarios || '';
            if (diasValidade) diasValidade.value = tipo.dias_validade || '';
            if (precoMensal) precoMensal.value = tipo.preco_mensal || '';
            if (descricao) descricao.value = tipo.descricao || '';
            
            openNovoTipoContratoModal();
        }
    };

    window.deleteTipoContrato = function(id) {
        if (confirm('Tem certeza que deseja excluir este tipo de contrato?')) {
            tiposContrato = tiposContrato.filter(t => t.id !== id);
            renderTiposContrato();
            showToast('Tipo de contrato excluído com sucesso!', 'success');
        }
    };

    async function handleTipoContratoFormSubmit(e) {
        e.preventDefault();
        
        const formData = new FormData(e.target);
        const tipoData = {
            nome: formData.get('nome'),
            max_usuarios: parseInt(formData.get('max_usuarios')),
            dias_validade: parseInt(formData.get('dias_validade')),
            preco_mensal: parseFloat(formData.get('preco_mensal')),
            descricao: formData.get('descricao')
        };

        const tipoId = document.getElementById('tipoContratoId');
        const id = tipoId ? tipoId.value : '';
        const isEdit = id !== '';

        try {
            const token = localStorage.getItem('token');
            if (!token) {
                if (isEdit) {
                    const index = tiposContrato.findIndex(t => t.id == id);
                    if (index !== -1) {
                        tiposContrato[index] = { ...tiposContrato[index], ...tipoData };
                    }
                } else {
                    const newId = tiposContrato.length > 0 ? Math.max(...tiposContrato.map(t => t.id)) + 1 : 1;
                    tiposContrato.push({ ...tipoData, id: newId });
                }
                renderTiposContrato();
                showToast(isEdit ? 'Tipo de contrato atualizado!' : 'Tipo de contrato criado!', 'success');
                closeNovoTipoContratoModal();
                return;
            }

            const url = isEdit ? `${API_BASE}/tipos-contrato/${id}` : `${API_BASE}/tipos-contrato`;
            const method = isEdit ? 'PUT' : 'POST';
            
            const response = await fetch(url, {
                method: method,
                headers: {
                    'Content-Type': 'application/json',
                    'Authorization': `Bearer ${token}`
                },
                body: JSON.stringify(tipoData)
            });

            if (response.ok) {
                showToast(isEdit ? 'Tipo de contrato atualizado!' : 'Tipo de contrato criado!', 'success');
                closeNovoTipoContratoModal();
                await loadTiposContrato();
            } else {
                const error = await response.json();
                showToast(error.message || 'Erro ao salvar tipo de contrato', 'error');
            }
        } catch (error) {
            console.error('Erro ao salvar tipo de contrato:', error);
            showToast('Erro ao salvar tipo de contrato', 'error');
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
