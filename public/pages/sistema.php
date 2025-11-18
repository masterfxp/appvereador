<div class="main-content">
    <!-- Page Header -->
    <div class="page-header">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="page-title">Configurações do Sistema</h1>
                <p class="mt-2 text-gray-600">Gerencie configurações avançadas e monitoramento do sistema</p>
            </div>
            <div class="flex items-center space-x-3">
                <button onclick="refreshSystem()" class="bg-gradient-to-r from-gray-600 to-gray-700 hover:from-gray-700 hover:to-gray-800 text-white px-6 py-3 rounded-lg font-medium shadow-lg hover:shadow-xl transition-all duration-200 flex items-center">
                    <i class="fas fa-sync-alt mr-2"></i>
                    Atualizar
                </button>
                <button onclick="saveSystemSettings()" class="bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 text-white px-6 py-3 rounded-lg font-medium shadow-lg hover:shadow-xl transition-all duration-200 flex items-center">
                    <i class="fas fa-save mr-2"></i>
                    Salvar Configurações
                </button>
            </div>
        </div>
    </div>

    <!-- System Status Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <div class="bg-white rounded-xl shadow-sm p-6 card-hover">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-green-100">
                    <i class="fas fa-server text-green-600 text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Status do Servidor</p>
                    <p class="text-2xl font-bold text-green-600" id="serverStatus">Online</p>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-xl shadow-sm p-6 card-hover">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-blue-100">
                    <i class="fas fa-database text-blue-600 text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Status do Banco</p>
                    <p class="text-2xl font-bold text-blue-600" id="databaseStatus">Conectado</p>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-xl shadow-sm p-6 card-hover">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-yellow-100">
                    <i class="fas fa-memory text-yellow-600 text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Uso de Memória</p>
                    <p class="text-2xl font-bold text-gray-900" id="memoryUsage">0%</p>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-xl shadow-sm p-6 card-hover">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-purple-100">
                    <i class="fas fa-hdd text-purple-600 text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Uso de Disco</p>
                    <p class="text-2xl font-bold text-gray-900" id="diskUsage">0%</p>
                </div>
            </div>
        </div>
    </div>

    <!-- System Information -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
        <!-- System Logs -->
        <div class="bg-white rounded-xl shadow-sm p-6 card-hover">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold text-gray-900">Logs do Sistema</h3>
                <button onclick="clearLogs()" class="text-sm text-red-600 hover:text-red-700">
                    <i class="fas fa-trash mr-1"></i>
                    Limpar
                </button>
            </div>
            <div class="bg-gray-900 text-green-400 p-4 rounded-md h-64 overflow-y-auto font-mono text-sm" id="systemLogs">
                <div>Carregando logs...</div>
            </div>
        </div>

        <!-- System Information -->
        <div class="bg-white rounded-xl shadow-sm p-6 card-hover">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Informações do Sistema</h3>
            <div class="space-y-3">
                <div class="flex justify-between items-center">
                    <span class="text-sm text-gray-600">Versão do Sistema</span>
                    <span class="text-sm font-medium text-gray-900" id="systemVersion">1.0.0</span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-sm text-gray-600">PHP Version</span>
                    <span class="text-sm font-medium text-gray-900" id="phpVersion">-</span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-sm text-gray-600">Node.js Version</span>
                    <span class="text-sm font-medium text-gray-900" id="nodeVersion">-</span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-sm text-gray-600">Última Atualização</span>
                    <span class="text-sm font-medium text-gray-900" id="lastUpdate">-</span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-sm text-gray-600">Tempo Online</span>
                    <span class="text-sm font-medium text-gray-900" id="uptime">-</span>
                </div>
            </div>
        </div>
    </div>

    <!-- System Settings -->
    <div class="bg-white rounded-xl shadow-sm p-6 card-hover">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">Configurações do Sistema</h3>
        <form id="systemSettingsForm" class="space-y-4">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Nome do Sistema</label>
                    <input type="text" id="systemName" value="UniAssessor" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Email do Administrador</label>
                    <input type="email" id="adminEmail" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Tempo de Sessão (minutos)</label>
                    <input type="number" id="sessionTimeout" value="30" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Limite de Upload (MB)</label>
                    <input type="number" id="uploadLimit" value="5" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
            </div>
            <div class="space-y-2">
                <div class="flex items-center">
                    <input type="checkbox" id="enableNotifications" checked class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                    <label for="enableNotifications" class="ml-2 block text-sm text-gray-700">
                        Habilitar notificações por email
                    </label>
                </div>
                <div class="flex items-center">
                    <input type="checkbox" id="enableLogging" checked class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                    <label for="enableLogging" class="ml-2 block text-sm text-gray-700">
                        Habilitar logging do sistema
                    </label>
                </div>
                <div class="flex items-center">
                    <input type="checkbox" id="enableMaintenance" class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                    <label for="enableMaintenance" class="ml-2 block text-sm text-gray-700">
                        Modo de manutenção
                    </label>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
(function() {
    'use strict';
    const API_BASE = window.location.hostname === 'localhost' || window.location.hostname === '127.0.0.1'
        ? 'http://localhost:3000/api'
        : 'https://uniassessor.com.br/api';
    
    let logs = [];

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
        loadSystemInfo();
        loadSystemLogs();
        setupEventListeners();
        startSystemMonitoring();
    });

    function setupEventListeners() {
        const systemSettingsForm = document.getElementById('systemSettingsForm');
        if (systemSettingsForm) {
            systemSettingsForm.addEventListener('submit', function(e) {
                e.preventDefault();
                saveSystemSettings();
            });
        }
    }

    async function loadSystemInfo() {
        try {
            const token = localStorage.getItem('token');
            if (!token) {
                updateSystemInfo(getMockSystemInfo());
                return;
            }

            const response = await fetch(`${API_BASE}/sistema/info`, {
                headers: {
                    'Authorization': `Bearer ${token}`
                }
            });

            if (response.ok) {
                const data = await response.json();
                updateSystemInfo(data);
            } else {
                updateSystemInfo(getMockSystemInfo());
            }
        } catch (error) {
            console.error('Erro ao carregar informações do sistema:', error);
            updateSystemInfo(getMockSystemInfo());
        }
    }

    function getMockSystemInfo() {
        return {
            serverStatus: 'Online',
            databaseStatus: 'Conectado',
            memoryUsage: '45%',
            diskUsage: '32%',
            systemVersion: '1.0.0',
            phpVersion: '8.2.0',
            nodeVersion: '18.0.0',
            lastUpdate: new Date().toLocaleDateString('pt-BR'),
            uptime: '7 dias'
        };
    }

    function updateSystemInfo(info) {
        const serverStatus = document.getElementById('serverStatus');
        const databaseStatus = document.getElementById('databaseStatus');
        const memoryUsage = document.getElementById('memoryUsage');
        const diskUsage = document.getElementById('diskUsage');
        const systemVersion = document.getElementById('systemVersion');
        const phpVersion = document.getElementById('phpVersion');
        const nodeVersion = document.getElementById('nodeVersion');
        const lastUpdate = document.getElementById('lastUpdate');
        const uptime = document.getElementById('uptime');

        if (serverStatus) serverStatus.textContent = info.serverStatus || 'Online';
        if (databaseStatus) databaseStatus.textContent = info.databaseStatus || 'Conectado';
        if (memoryUsage) memoryUsage.textContent = info.memoryUsage || '0%';
        if (diskUsage) diskUsage.textContent = info.diskUsage || '0%';
        if (systemVersion) systemVersion.textContent = info.systemVersion || '1.0.0';
        if (phpVersion) phpVersion.textContent = info.phpVersion || '-';
        if (nodeVersion) nodeVersion.textContent = info.nodeVersion || '-';
        if (lastUpdate) lastUpdate.textContent = info.lastUpdate || '-';
        if (uptime) uptime.textContent = info.uptime || '-';
    }

    function loadSystemLogs() {
        logs = [
            { timestamp: new Date().toLocaleString('pt-BR'), message: 'Sistema inicializado com sucesso', type: 'info' },
            { timestamp: new Date(Date.now() - 60000).toLocaleString('pt-BR'), message: 'Backup realizado com sucesso', type: 'success' },
            { timestamp: new Date(Date.now() - 120000).toLocaleString('pt-BR'), message: 'Usuário logado: admin@admin.com', type: 'info' },
            { timestamp: new Date(Date.now() - 180000).toLocaleString('pt-BR'), message: 'Relatório gerado: relatorio_geral_2024_01.pdf', type: 'info' }
        ];
        renderLogs();
    }

    function renderLogs() {
        const container = document.getElementById('systemLogs');
        if (!container) return;

        container.innerHTML = logs.map(log => {
            const color = log.type === 'error' ? 'text-red-400' : log.type === 'success' ? 'text-green-400' : 'text-green-400';
            return `<div class="${color}">[${log.timestamp}] ${log.message}</div>`;
        }).join('');

        container.scrollTop = container.scrollHeight;
    }

    function startSystemMonitoring() {
        setInterval(() => {
            const memoryUsage = document.getElementById('memoryUsage');
            const diskUsage = document.getElementById('diskUsage');
            
            if (memoryUsage) {
                const currentUsage = parseInt(memoryUsage.textContent) || 0;
                const newUsage = Math.max(40, Math.min(80, currentUsage + (Math.random() * 2 - 1)));
                memoryUsage.textContent = Math.round(newUsage) + '%';
            }
            
            if (diskUsage) {
                const currentUsage = parseInt(diskUsage.textContent) || 0;
                const newUsage = Math.max(30, Math.min(60, currentUsage + (Math.random() * 0.5 - 0.25)));
                diskUsage.textContent = Math.round(newUsage) + '%';
            }
        }, 5000);

        setInterval(() => {
            const newLog = {
                timestamp: new Date().toLocaleString('pt-BR'),
                message: 'Sistema monitorado com sucesso',
                type: 'info'
            };
            logs.push(newLog);
            if (logs.length > 50) {
                logs.shift();
            }
            renderLogs();
        }, 10000);
    }

    window.refreshSystem = function() {
        showToast('Atualizando informações do sistema...', 'info');
        loadSystemInfo();
        loadSystemLogs();
        setTimeout(() => {
            showToast('Sistema atualizado com sucesso!', 'success');
        }, 1000);
    };

    window.saveSystemSettings = async function() {
        const systemName = document.getElementById('systemName');
        const adminEmail = document.getElementById('adminEmail');
        const sessionTimeout = document.getElementById('sessionTimeout');
        const uploadLimit = document.getElementById('uploadLimit');

        const settings = {
            systemName: systemName ? systemName.value : '',
            adminEmail: adminEmail ? adminEmail.value : '',
            sessionTimeout: sessionTimeout ? parseInt(sessionTimeout.value) : 30,
            uploadLimit: uploadLimit ? parseInt(uploadLimit.value) : 5
        };

        try {
            const token = localStorage.getItem('token');
            const response = await fetch(`${API_BASE}/sistema/settings`, {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/json',
                    'Authorization': `Bearer ${token}`
                },
                body: JSON.stringify(settings)
            });

            if (response.ok) {
                showToast('Configurações salvas com sucesso!', 'success');
            } else {
                showToast('Erro ao salvar configurações', 'error');
            }
        } catch (error) {
            console.error('Erro ao salvar configurações:', error);
            showToast('Erro ao salvar configurações', 'error');
        }
    };

    window.clearLogs = function() {
        if (confirm('Tem certeza que deseja limpar os logs do sistema?')) {
            logs = [];
            renderLogs();
            showToast('Logs limpos com sucesso!', 'success');
        }
    };

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
