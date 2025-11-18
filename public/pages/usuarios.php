<div class="main-content">
                        <!-- Page Header -->
    <div class="page-header">
        <div class="flex items-center justify-between">
            <div>
                            <h1 class="page-title">Gestão de Usuários</h1>
                            <p class="mt-2 text-gray-600">Gerencie usuários e permissões do sistema</p>
                        </div>
            <button onclick="openUsuarioModal()" class="bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 text-white px-6 py-3 rounded-lg font-medium shadow-lg hover:shadow-xl transition-all duration-200 flex items-center">
                                <i class="fas fa-plus mr-2"></i>
                                Novo Usuário
                            </button>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <div class="bg-gradient-to-r from-blue-500 to-blue-600 rounded-lg shadow-lg p-6 text-white card-hover">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-blue-100 text-sm font-medium">Total de Usuários</p>
                    <p class="text-3xl font-bold" id="totalUsuarios">0</p>
                    <p class="text-blue-100 text-xs mt-1">Ativos: <span id="usuariosAtivos">0</span></p>
                </div>
                <div class="p-3 rounded-full bg-blue-400 bg-opacity-30">
                    <i class="fas fa-users text-2xl"></i>
                </div>
            </div>
        </div>
        
        <div class="bg-gradient-to-r from-green-500 to-green-600 rounded-lg shadow-lg p-6 text-white card-hover">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-green-100 text-sm font-medium">Administradores</p>
                    <p class="text-3xl font-bold" id="administradores">0</p>
                    <p class="text-green-100 text-xs mt-1">Vereadores: <span id="vereadores">0</span></p>
                </div>
                <div class="p-3 rounded-full bg-green-400 bg-opacity-30">
                    <i class="fas fa-user-shield text-2xl"></i>
                </div>
            </div>
        </div>
        
        <div class="bg-gradient-to-r from-purple-500 to-purple-600 rounded-lg shadow-lg p-6 text-white card-hover">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-purple-100 text-sm font-medium">Assessores</p>
                    <p class="text-3xl font-bold" id="assessores">0</p>
                    <p class="text-purple-100 text-xs mt-1">Online: <span id="usuariosOnline">0</span></p>
                </div>
                <div class="p-3 rounded-full bg-purple-400 bg-opacity-30">
                    <i class="fas fa-user text-2xl"></i>
                </div>
            </div>
        </div>
        
        <div class="bg-gradient-to-r from-orange-500 to-orange-600 rounded-lg shadow-lg p-6 text-white card-hover">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-orange-100 text-sm font-medium">Novos Usuários</p>
                    <p class="text-3xl font-bold" id="novosUsuarios">0</p>
                    <p class="text-orange-100 text-xs mt-1">Este mês: <span id="usuariosMes">0</span></p>
                </div>
                <div class="p-3 rounded-full bg-orange-400 bg-opacity-30">
                    <i class="fas fa-user-plus text-2xl"></i>
                </div>
            </div>
        </div>
                        </div>

                        <!-- Main Content -->
    <div class="bg-white rounded-xl shadow-sm overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200">
            <div class="flex justify-between items-center">
                <h3 class="text-lg font-semibold text-gray-900">Usuários Cadastrados</h3>
                                    <div class="flex items-center space-x-4">
                                        <span class="text-sm text-gray-500" id="totalUsuariosLista">0 usuários</span>
                                        <a href="#" class="text-blue-600 hover:text-blue-800 text-sm flex items-center">
                                            <i class="fas fa-download mr-1"></i>
                                            Exportar
                                        </a>
                </div>
                                    </div>
                                </div>

                                <!-- Loading State -->
                                <div id="loadingUsuarios" class="text-center py-8 hidden">
            <div class="inline-block animate-spin rounded-full h-8 w-8 border-b-2 border-blue-600"></div>
                                    <p class="mt-2 text-gray-500">Carregando usuários...</p>
                                </div>

                                <!-- Empty State -->
                                <div id="emptyState" class="text-center py-12 hidden">
            <i class="fas fa-users text-4xl text-gray-300 mb-4"></i>
            <h3 class="text-lg font-medium text-gray-900 mb-2">Nenhum usuário cadastrado</h3>
            <p class="text-gray-500 mb-6">Comece criando um novo usuário.</p>
            <button onclick="openUsuarioModal()" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg font-medium">
                <i class="fas fa-plus mr-2"></i>
                Novo Usuário
            </button>
                                </div>

                                <!-- Table -->
        <div class="overflow-hidden" id="usuariosTable" style="display: none;">
                                    <table class="min-w-full divide-y divide-gray-300">
                                        <thead class="bg-gray-50">
                                            <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Usuário</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nível</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Cliente</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Ações</th>
                                            </tr>
                                        </thead>
                                        <tbody id="usuariosTableBody" class="bg-white divide-y divide-gray-200">
                                            <!-- Usuários serão carregados aqui -->
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>

    <!-- Modal Novo Usuário -->
    <div id="usuarioModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
            <div class="mt-3">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-medium text-gray-900">Novo Usuário</h3>
                    <button onclick="closeUsuarioModal()" class="text-gray-400 hover:text-gray-600">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
                <form id="usuarioForm">
                    <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Nome *</label>
                        <input type="text" id="usuarioNome" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                    </div>
                    <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Email *</label>
                        <input type="email" id="usuarioEmail" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                    </div>
                    <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Senha *</label>
                        <input type="password" id="usuarioSenha" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                    </div>
                    <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Nível *</label>
                        <select id="usuarioNivel" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                            <option value="">Selecione...</option>
                            <option value="administrador">Administrador</option>
                            <option value="vereador">Vereador</option>
                            <option value="assessor">Assessor</option>
                        </select>
                    </div>
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Cliente *</label>
                        <select id="usuarioCliente" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                            <option value="">Selecione um cliente...</option>
                        </select>
                        <p class="mt-1 text-xs text-gray-500">Primeiro cadastre um cliente na página de <a href="index.php?page=clientes" class="text-blue-600 hover:text-blue-800">Clientes</a></p>
                    </div>
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Telefone</label>
                        <input type="text" id="usuarioTelefone" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Partido</label>
                        <input type="text" id="usuarioPartido" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Cargo</label>
                        <input type="text" id="usuarioCargo" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                    <div class="flex justify-end space-x-3">
                    <button type="button" onclick="closeUsuarioModal()" class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-200 rounded-md hover:bg-gray-300">Cancelar</button>
                    <button type="submit" class="px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded-md hover:bg-blue-700">Salvar</button>
                    </div>
                </form>
        </div>
    </div>
</div>

<!-- Modal Editar Usuário -->
<div id="editUsuarioModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-medium text-gray-900">Editar Usuário</h3>
                <button onclick="closeEditUsuarioModal()" class="text-gray-400 hover:text-gray-600">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <form id="editUsuarioForm">
                <input type="hidden" id="editUsuarioId">
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Nome *</label>
                    <input type="text" id="editUsuarioNome" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                </div>
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Email *</label>
                    <input type="email" id="editUsuarioEmail" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                </div>
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Nova Senha</label>
                    <input type="password" id="editUsuarioSenha" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Deixe em branco para manter a senha atual">
                    <p class="mt-1 text-xs text-gray-500">Deixe em branco para manter a senha atual</p>
                </div>
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Nível *</label>
                    <select id="editUsuarioNivel" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                        <option value="administrador">Administrador</option>
                        <option value="vereador">Vereador</option>
                        <option value="assessor">Assessor</option>
                    </select>
                </div>
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Cliente *</label>
                    <select id="editUsuarioCliente" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                        <option value="">Selecione um cliente...</option>
                    </select>
                </div>
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Telefone</label>
                    <input type="text" id="editUsuarioTelefone" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Partido</label>
                    <input type="text" id="editUsuarioPartido" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Cargo</label>
                    <input type="text" id="editUsuarioCargo" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                <div class="flex justify-end space-x-3">
                    <button type="button" onclick="closeEditUsuarioModal()" class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-200 rounded-md hover:bg-gray-300">Cancelar</button>
                    <button type="submit" class="px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded-md hover:bg-blue-700">Salvar Alterações</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
// Definir funções no escopo global IMEDIATAMENTE para que estejam disponíveis quando os botões forem clicados
window.openUsuarioModal = function() {
    const modal = document.getElementById('usuarioModal');
    if (modal) {
        modal.classList.remove('hidden');
        modal.style.setProperty('display', 'flex', 'important');
        modal.style.setProperty('align-items', 'center', 'important');
        modal.style.setProperty('justify-content', 'center', 'important');
        modal.style.setProperty('z-index', '9999', 'important');
        // loadClientes será chamado quando o IIFE executar
        if (typeof loadClientes === 'function') {
            loadClientes();
        }
        console.log('✅ Modal de usuário aberto');
    }
};

window.closeUsuarioModal = function() {
    const modal = document.getElementById('usuarioModal');
    const form = document.getElementById('usuarioForm');
    if (modal) {
        modal.classList.add('hidden');
        modal.style.display = 'none';
    }
    if (form) form.reset();
};

window.openEditUsuarioModal = function() {
    const modal = document.getElementById('editUsuarioModal');
    if (modal) {
        modal.classList.remove('hidden');
        modal.style.setProperty('display', 'flex', 'important');
        modal.style.setProperty('align-items', 'center', 'important');
        modal.style.setProperty('justify-content', 'center', 'important');
        modal.style.setProperty('z-index', '9999', 'important');
        console.log('✅ Modal de edição de usuário aberto');
    }
};

window.closeEditUsuarioModal = function() {
    const modal = document.getElementById('editUsuarioModal');
    const form = document.getElementById('editUsuarioForm');
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
    
    let usuarios = [];
    let clientes = [];

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
        loadUsuarios();
        setupEventListeners();
    });

    function setupEventListeners() {
        const usuarioForm = document.getElementById('usuarioForm');
        if (usuarioForm) {
            usuarioForm.addEventListener('submit', handleUsuarioFormSubmit);
        }

        const editUsuarioForm = document.getElementById('editUsuarioForm');
        if (editUsuarioForm) {
            editUsuarioForm.addEventListener('submit', handleEditUsuarioFormSubmit);
        }
    }

    async function loadClientes() {
        try {
            const token = localStorage.getItem('token');
            const response = await fetch(`${API_BASE}/clientes`, {
                headers: token ? { 'Authorization': `Bearer ${token}` } : {}
            });

            if (response.ok) {
                clientes = await response.json();
                if (!Array.isArray(clientes)) {
                    clientes = [];
                }
                populateClienteSelect();
            } else {
                clientes = [
                    { id: 1, nome: 'Gabinete Municipal - Cidade A', empresa: 'Cidade A' },
                    { id: 2, nome: 'Gabinete Municipal - Cidade B', empresa: 'Cidade B' }
                ];
                populateClienteSelect();
            }
        } catch (error) {
            console.error('Erro ao carregar clientes:', error);
            clientes = [
                { id: 1, nome: 'Gabinete Municipal - Cidade A', empresa: 'Cidade A' },
                { id: 2, nome: 'Gabinete Municipal - Cidade B', empresa: 'Cidade B' }
            ];
            populateClienteSelect();
        }
    }

    function populateClienteSelect() {
        const select = document.getElementById('usuarioCliente');
        const editSelect = document.getElementById('editUsuarioCliente');
        
        const populateSelect = (selectElement) => {
            if (!selectElement) return;
            selectElement.innerHTML = '<option value="">Selecione um cliente...</option>';
            clientes.forEach(cliente => {
                const option = document.createElement('option');
                option.value = cliente.id;
                option.textContent = `${cliente.nome || cliente.empresa} - ${cliente.empresa || cliente.nome || 'N/A'}`;
                selectElement.appendChild(option);
            });
        };
        
        populateSelect(select);
        populateSelect(editSelect);
    }

    async function loadUsuarios() {
        try {
            const loadingEl = document.getElementById('loadingUsuarios');
            const emptyStateEl = document.getElementById('emptyState');
            const tableEl = document.getElementById('usuariosTable');
            
            if (loadingEl) loadingEl.classList.remove('hidden');
            if (emptyStateEl) emptyStateEl.classList.add('hidden');
            if (tableEl) tableEl.style.display = 'none';

            const token = localStorage.getItem('token');
            const response = await fetch(`${API_BASE}/usuarios`, {
                headers: token ? { 'Authorization': `Bearer ${token}` } : {}
            });

            if (response.ok) {
                usuarios = await response.json();
                if (!Array.isArray(usuarios)) {
                    usuarios = [];
                }
                
                usuarios = await Promise.all(usuarios.map(async (usuario) => {
                    if (usuario.cliente_id) {
                        let cliente = clientes.find(c => c.id === usuario.cliente_id);
                        if (!cliente && clientes.length > 0) {
                            try {
                                const clienteResponse = await fetch(`${API_BASE}/clientes/${usuario.cliente_id}`);
                                if (clienteResponse.ok) {
                                    cliente = await clienteResponse.json();
                                }
                            } catch (e) {
                                console.warn('Erro ao buscar cliente:', e);
                            }
                        }
                        
                        if (cliente) {
                            usuario.cliente = {
                                id: cliente.id,
                                nome: cliente.nome || cliente.empresa,
                                empresa: cliente.empresa || cliente.nome
                            };
                        } else {
                            usuario.cliente = null;
                        }
                    }
                    return usuario;
                }));
                
                renderUsuarios();
                updateStats();
            } else {
                usuarios = getMockUsuarios();
                renderUsuarios();
                updateStats();
            }
        } catch (error) {
            console.error('Erro ao carregar usuários:', error);
            usuarios = getMockUsuarios();
            renderUsuarios();
            updateStats();
        } finally {
            const loadingEl = document.getElementById('loadingUsuarios');
            if (loadingEl) loadingEl.classList.add('hidden');
        }
    }

    function getMockUsuarios() {
        return [
            {
                id: 1,
                nome: 'Administrador do Sistema',
                email: 'admin@admin.com',
                nivel: 'administrador',
                ativo: true,
                telefone: '(11) 99999-9999',
                partido: 'Sistema',
                cargo: 'Administrador',
                cliente: { id: 1, nome: 'Sistema', empresa: 'Sistema' }
            }
        ];
    }

    function renderUsuarios() {
        const tbody = document.getElementById('usuariosTableBody');
        const table = document.getElementById('usuariosTable');
        const emptyState = document.getElementById('emptyState');

        if (!tbody || !table || !emptyState) return;

        if (usuarios.length === 0) {
            table.style.display = 'none';
            emptyState.classList.remove('hidden');
            return;
        }

        table.style.display = 'block';
        emptyState.classList.add('hidden');

        tbody.innerHTML = usuarios.map(usuario => `
            <tr class="hover:bg-gray-50">
                <td class="px-6 py-4 whitespace-nowrap">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 h-10 w-10">
                            <div class="h-10 w-10 rounded-full bg-blue-500 flex items-center justify-center text-white font-medium">
                                ${(usuario.nome || 'U').charAt(0).toUpperCase()}
                            </div>
                        </div>
                        <div class="ml-4">
                            <div class="text-sm font-medium text-gray-900">${usuario.nome || 'N/A'}</div>
                            <div class="text-sm text-gray-500">${usuario.telefone || 'N/A'}</div>
                        </div>
                    </div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                    <div class="text-sm text-gray-900">${usuario.email || 'N/A'}</div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                    <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full ${getNivelColor(usuario.nivel)}">
                        ${getNivelLabel(usuario.nivel)}
                    </span>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                    ${usuario.cliente ? `${usuario.cliente.nome || usuario.cliente.empresa || 'N/A'}` : 'N/A'}
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                    <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full ${usuario.ativo ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'}">
                        ${usuario.ativo ? 'Ativo' : 'Inativo'}
                    </span>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                    <button onclick="editUsuario(${usuario.id})" class="text-blue-600 hover:text-blue-900 mr-3" title="Editar">
                        <i class="fas fa-edit"></i>
                    </button>
                    <button onclick="deleteUsuario(${usuario.id})" class="text-red-600 hover:text-red-900" title="Excluir">
                        <i class="fas fa-trash"></i>
                    </button>
                </td>
            </tr>
        `).join('');

        const totalUsuariosLista = document.getElementById('totalUsuariosLista');
        if (totalUsuariosLista) {
            totalUsuariosLista.textContent = `${usuarios.length} usuário${usuarios.length !== 1 ? 's' : ''}`;
        }
    }

    function getNivelColor(nivel) {
        const colors = {
            'administrador': 'bg-red-100 text-red-800',
            'vereador': 'bg-blue-100 text-blue-800',
            'assessor': 'bg-green-100 text-green-800'
        };
        return colors[nivel] || 'bg-gray-100 text-gray-800';
    }

    function getNivelLabel(nivel) {
        const labels = {
            'administrador': 'Administrador',
            'vereador': 'Vereador',
            'assessor': 'Assessor'
        };
        return labels[nivel] || nivel;
    }

    function updateStats() {
        const total = usuarios.length;
        const ativos = usuarios.filter(u => u.ativo).length;
        const administradores = usuarios.filter(u => u.nivel === 'administrador').length;
        const vereadores = usuarios.filter(u => u.nivel === 'vereador').length;
        const assessores = usuarios.filter(u => u.nivel === 'assessor').length;
        
        const totalUsuariosEl = document.getElementById('totalUsuarios');
        const usuariosAtivosEl = document.getElementById('usuariosAtivos');
        const administradoresEl = document.getElementById('administradores');
        const vereadoresEl = document.getElementById('vereadores');
        const assessoresEl = document.getElementById('assessores');
        
        if (totalUsuariosEl) totalUsuariosEl.textContent = total;
        if (usuariosAtivosEl) usuariosAtivosEl.textContent = ativos;
        if (administradoresEl) administradoresEl.textContent = administradores;
        if (vereadoresEl) vereadoresEl.textContent = vereadores;
        if (assessoresEl) assessoresEl.textContent = assessores;
    }

    // Funções já definidas no escopo global acima, atualizar para incluir lógica completa
    // Redefinir para incluir a lógica completa de carregamento
    window.openUsuarioModal = function() {
        const modal = document.getElementById('usuarioModal');
        if (modal) {
            modal.classList.remove('hidden');
            modal.style.setProperty('display', 'flex', 'important');
            modal.style.setProperty('align-items', 'center', 'important');
            modal.style.setProperty('justify-content', 'center', 'important');
            modal.style.setProperty('z-index', '9999', 'important');
            if (typeof loadClientes === 'function') {
                loadClientes();
            }
            console.log('✅ Modal de usuário aberto');
        }
    };
    
    window.closeUsuarioModal = function() {
        const modal = document.getElementById('usuarioModal');
        const form = document.getElementById('usuarioForm');
        if (modal) {
            modal.classList.add('hidden');
            modal.style.display = 'none';
        }
        if (form) form.reset();
    };
    
    window.openEditUsuarioModal = function() {
        const modal = document.getElementById('editUsuarioModal');
        if (modal) {
            modal.classList.remove('hidden');
            modal.style.setProperty('display', 'flex', 'important');
            modal.style.setProperty('align-items', 'center', 'important');
            modal.style.setProperty('justify-content', 'center', 'important');
            modal.style.setProperty('z-index', '9999', 'important');
            console.log('✅ Modal de edição de usuário aberto');
        }
    };
    
    window.closeEditUsuarioModal = function() {
        const modal = document.getElementById('editUsuarioModal');
        const form = document.getElementById('editUsuarioForm');
        if (modal) {
            modal.classList.add('hidden');
            modal.style.display = 'none';
        }
        if (form) form.reset();
    };
    
    window.editUsuario = function(id) {
        const usuario = usuarios.find(u => u.id === id);
        if (usuario) {
            const idInput = document.getElementById('editUsuarioId');
            const nome = document.getElementById('editUsuarioNome');
            const email = document.getElementById('editUsuarioEmail');
            const nivel = document.getElementById('editUsuarioNivel');
            const cliente = document.getElementById('editUsuarioCliente');
            const telefone = document.getElementById('editUsuarioTelefone');
            const partido = document.getElementById('editUsuarioPartido');
            const cargo = document.getElementById('editUsuarioCargo');
            
            if (idInput) idInput.value = usuario.id;
            if (nome) nome.value = usuario.nome || '';
            if (email) email.value = usuario.email || '';
            if (nivel) nivel.value = usuario.nivel || '';
            if (cliente) cliente.value = usuario.cliente_id || '';
            if (telefone) telefone.value = usuario.telefone || '';
            if (partido) partido.value = usuario.partido || '';
            if (cargo) cargo.value = usuario.cargo || '';
            
            loadClientes();
            openEditUsuarioModal();
        }
    };
    
    window.deleteUsuario = async function(id) {
        if (confirm('Tem certeza que deseja excluir este usuário?')) {
            try {
                const token = localStorage.getItem('token');
                if (!token) {
                    usuarios = usuarios.filter(u => u.id !== id);
                    renderUsuarios();
                    updateStats();
                    showToast('Usuário excluído com sucesso!', 'success');
                    return;
                }

                const response = await fetch(`${API_BASE}/usuarios/${id}`, {
                    method: 'DELETE',
                    headers: {
                        'Authorization': `Bearer ${token}`
                    }
                });
                
                if (response.ok) {
                    showToast('Usuário excluído com sucesso!', 'success');
                    loadUsuarios();
                } else {
                    const error = await response.json();
                    showToast(error.error || 'Erro ao excluir usuário', 'error');
                }
            } catch (error) {
                console.error('Erro ao excluir usuário:', error);
                showToast('Erro ao excluir usuário', 'error');
            }
        }
    };

    async function handleUsuarioFormSubmit(e) {
        e.preventDefault();
        
        const nome = document.getElementById('usuarioNome');
        const email = document.getElementById('usuarioEmail');
        const senha = document.getElementById('usuarioSenha');
        const nivel = document.getElementById('usuarioNivel');
        const cliente = document.getElementById('usuarioCliente');
        const telefone = document.getElementById('usuarioTelefone');
        const partido = document.getElementById('usuarioPartido');
        const cargo = document.getElementById('usuarioCargo');
        
        if (!nome || !email || !senha || !nivel || !cliente) return;

        const formData = {
            nome: nome.value,
            email: email.value,
            senha: senha.value,
            nivel: nivel.value,
            cliente_id: cliente.value,
            telefone: telefone ? telefone.value : '',
            partido: partido ? partido.value : '',
            cargo: cargo ? cargo.value : ''
        };
        
        try {
            const token = localStorage.getItem('token');
            if (!token) {
                const newId = usuarios.length > 0 ? Math.max(...usuarios.map(u => u.id)) + 1 : 1;
                usuarios.push({ ...formData, id: newId, ativo: true, cliente: clientes.find(c => c.id == formData.cliente_id) });
                renderUsuarios();
                updateStats();
                showToast('Usuário criado com sucesso!', 'success');
                closeUsuarioModal();
                return;
            }

            const response = await fetch(`${API_BASE}/usuarios`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Authorization': `Bearer ${token}`
                },
                body: JSON.stringify(formData)
            });
            
            if (response.ok) {
                showToast('Usuário criado com sucesso!', 'success');
                closeUsuarioModal();
                await loadUsuarios();
            } else {
                const error = await response.json();
                showToast(error.error || 'Erro ao criar usuário', 'error');
            }
        } catch (error) {
            console.error('Erro ao criar usuário:', error);
            showToast('Erro ao criar usuário', 'error');
        }
    }

    async function handleEditUsuarioFormSubmit(e) {
        e.preventDefault();
        
        const usuarioId = document.getElementById('editUsuarioId');
        const nome = document.getElementById('editUsuarioNome');
        const email = document.getElementById('editUsuarioEmail');
        const senha = document.getElementById('editUsuarioSenha');
        const nivel = document.getElementById('editUsuarioNivel');
        const cliente = document.getElementById('editUsuarioCliente');
        const telefone = document.getElementById('editUsuarioTelefone');
        const partido = document.getElementById('editUsuarioPartido');
        const cargo = document.getElementById('editUsuarioCargo');
        
        if (!usuarioId || !nome || !email || !nivel || !cliente) return;

        const formData = {
            nome: nome.value,
            email: email.value,
            nivel: nivel.value,
            cliente_id: cliente.value,
            telefone: telefone ? telefone.value : '',
            partido: partido ? partido.value : '',
            cargo: cargo ? cargo.value : ''
        };
        
        const novaSenha = senha ? senha.value : '';
        if (novaSenha.trim() !== '') {
            formData.senha = novaSenha;
        }
        
        try {
            const token = localStorage.getItem('token');
            if (!token) {
                const index = usuarios.findIndex(u => u.id == usuarioId.value);
                if (index !== -1) {
                    usuarios[index] = { ...usuarios[index], ...formData, cliente: clientes.find(c => c.id == formData.cliente_id) };
                }
                renderUsuarios();
                updateStats();
                showToast('Usuário atualizado com sucesso!', 'success');
                closeEditUsuarioModal();
                return;
            }

            const response = await fetch(`${API_BASE}/usuarios/${usuarioId.value}`, {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/json',
                    'Authorization': `Bearer ${token}`
                },
                body: JSON.stringify(formData)
            });
            
            if (response.ok) {
                showToast('Usuário atualizado com sucesso!', 'success');
                closeEditUsuarioModal();
                await loadUsuarios();
            } else {
                const error = await response.json();
                showToast(error.error || 'Erro ao atualizar usuário', 'error');
            }
        } catch (error) {
            console.error('Erro ao atualizar usuário:', error);
            showToast('Erro ao atualizar usuário', 'error');
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
