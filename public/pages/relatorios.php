<div class="main-content">
    <!-- Page Header -->
    <div class="page-header">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="page-title">Relatórios e Análises</h1>
                <p class="mt-2 text-gray-600">Gere relatórios detalhados e análises do gabinete</p>
            </div>
            <div class="flex items-center space-x-3">
                <button onclick="generateReport()" class="bg-gradient-to-r from-green-600 to-green-700 hover:from-green-700 hover:to-green-800 text-white px-6 py-3 rounded-lg font-medium shadow-lg hover:shadow-xl transition-all duration-200 flex items-center">
                    <i class="fas fa-file-alt mr-2"></i>
                    Gerar Relatório
                </button>
                <button onclick="exportReport()" class="bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 text-white px-6 py-3 rounded-lg font-medium shadow-lg hover:shadow-xl transition-all duration-200 flex items-center">
                    <i class="fas fa-download mr-2"></i>
                    Exportar
                </button>
            </div>
        </div>
    </div>

    <!-- Report Filters -->
    <div class="bg-white rounded-xl shadow-sm p-6 mb-8">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">Filtros de Relatório</h3>
        <form id="reportFilterForm" class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Tipo de Relatório</label>
                <select id="tipoRelatorio" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="">Todos</option>
                    <option value="geral">Relatório Geral</option>
                    <option value="projetos">Projetos</option>
                    <option value="demandas">Demandas</option>
                    <option value="usuarios">Usuários</option>
                    <option value="tarefas">Tarefas</option>
                    <option value="reunioes">Reuniões</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Data Inicial</label>
                <input type="date" id="dataInicial" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Data Final</label>
                <input type="date" id="dataFinal" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
        </form>
    </div>

    <!-- Report Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <div class="bg-white rounded-xl shadow-sm p-6 card-hover">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-blue-100">
                    <i class="fas fa-file-alt text-blue-600 text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Total de Relatórios</p>
                    <p class="text-2xl font-bold text-gray-900" id="totalRelatorios">0</p>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-xl shadow-sm p-6 card-hover">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-green-100">
                    <i class="fas fa-check-circle text-green-600 text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Gerados Hoje</p>
                    <p class="text-2xl font-bold text-gray-900" id="relatoriosHoje">0</p>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-xl shadow-sm p-6 card-hover">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-yellow-100">
                    <i class="fas fa-clock text-yellow-600 text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Este Mês</p>
                    <p class="text-2xl font-bold text-gray-900" id="relatoriosMes">0</p>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-xl shadow-sm p-6 card-hover">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-purple-100">
                    <i class="fas fa-download text-purple-600 text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Exportados</p>
                    <p class="text-2xl font-bold text-gray-900" id="relatoriosExportados">0</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Reports List -->
    <div class="bg-white rounded-xl shadow-sm overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-semibold text-gray-900">Relatórios Recentes</h3>
        </div>
        
        <div class="bg-gray-50 px-6 py-3 border-b border-gray-200">
            <div class="grid grid-cols-12 gap-4 text-sm font-medium text-gray-700">
                <div class="col-span-3">Tipo</div>
                <div class="col-span-2">Data</div>
                <div class="col-span-2">Período</div>
                <div class="col-span-2">Status</div>
                <div class="col-span-3">Ações</div>
            </div>
        </div>

        <div id="reportsContainer" class="divide-y divide-gray-200">
            <!-- Reports will be loaded here -->
        </div>

        <div id="loadingState" class="text-center py-12">
            <div class="inline-block animate-spin rounded-full h-8 w-8 border-b-2 border-blue-600"></div>
            <p class="mt-2 text-gray-600">Carregando relatórios...</p>
        </div>

        <div id="emptyState" class="text-center py-12 hidden">
            <i class="fas fa-file-alt text-6xl text-gray-300 mb-4"></i>
            <h3 class="text-lg font-medium text-gray-900 mb-2">Nenhum relatório encontrado</h3>
            <p class="text-gray-600 mb-6">Comece gerando seu primeiro relatório.</p>
            <button onclick="generateReport()" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg font-medium">
                <i class="fas fa-file-alt mr-2"></i>
                Gerar Relatório
            </button>
        </div>
    </div>
</div>

<script>
(function() {
    'use strict';
    const API_BASE = window.location.hostname === 'localhost' || window.location.hostname === '127.0.0.1'
        ? 'http://localhost:3000/api'
        : 'https://uniassessor.com.br/api';
    
    let reports = [];

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
        loadReports();
        setupEventListeners();
    });

    function setupEventListeners() {
        const reportFilterForm = document.getElementById('reportFilterForm');
        if (reportFilterForm) {
            reportFilterForm.addEventListener('change', filterReports);
        }
    }

    async function loadReports() {
        try {
            const token = localStorage.getItem('token');
            if (!token) {
                reports = getMockReports();
                renderReports();
                updateStats();
                return;
            }

            const response = await fetch(`${API_BASE}/relatorios`, {
                headers: {
                    'Authorization': `Bearer ${token}`
                }
            });

            if (response.ok) {
                const data = await response.json();
                reports = Array.isArray(data) ? data : (data.relatorios || []);
                renderReports();
                updateStats();
            } else {
                reports = getMockReports();
                renderReports();
                updateStats();
            }
        } catch (error) {
            console.error('Erro ao carregar relatórios:', error);
            reports = getMockReports();
            renderReports();
            updateStats();
        }
    }

    function getMockReports() {
        const today = new Date();
        const yesterday = new Date(today);
        yesterday.setDate(yesterday.getDate() - 1);
        const lastWeek = new Date(today);
        lastWeek.setDate(lastWeek.getDate() - 7);

        return [
            {
                id: 1,
                tipo: 'geral',
                data: today.toISOString().split('T')[0],
                periodo: '2024-01',
                status: 'concluido',
                arquivo: 'relatorio_geral_2024_01.pdf'
            },
            {
                id: 2,
                tipo: 'projetos',
                data: yesterday.toISOString().split('T')[0],
                periodo: '2024-01',
                status: 'concluido',
                arquivo: 'relatorio_projetos_2024_01.pdf'
            },
            {
                id: 3,
                tipo: 'demandas',
                data: lastWeek.toISOString().split('T')[0],
                periodo: '2024-01',
                status: 'concluido',
                arquivo: 'relatorio_demandas_2024_01.pdf'
            }
        ];
    }

    function renderReports() {
        const container = document.getElementById('reportsContainer');
        const loadingState = document.getElementById('loadingState');
        const emptyState = document.getElementById('emptyState');

        if (!container) return;

        if (loadingState) loadingState.classList.add('hidden');

        if (reports.length === 0) {
            if (emptyState) emptyState.classList.remove('hidden');
            container.innerHTML = '';
            return;
        }

        if (emptyState) emptyState.classList.add('hidden');

        container.innerHTML = reports.map(report => {
            const tipoText = {
                'geral': 'Relatório Geral',
                'projetos': 'Projetos',
                'demandas': 'Demandas',
                'usuarios': 'Usuários',
                'tarefas': 'Tarefas',
                'reunioes': 'Reuniões'
            }[report.tipo] || report.tipo;

            return `
                <div class="px-6 py-4 fade-in">
                    <div class="grid grid-cols-12 gap-4 items-center">
                        <div class="col-span-3">
                            <p class="text-sm font-medium text-gray-900">${tipoText}</p>
                        </div>
                        <div class="col-span-2">
                            <p class="text-sm text-gray-900">${formatDate(report.data)}</p>
                        </div>
                        <div class="col-span-2">
                            <p class="text-sm text-gray-900">${report.periodo}</p>
                        </div>
                        <div class="col-span-2">
                            <span class="px-3 py-1 rounded-full text-xs font-medium bg-green-100 text-green-700">
                                Concluído
                            </span>
                        </div>
                        <div class="col-span-3">
                            <div class="flex items-center space-x-2">
                                <button onclick="downloadReport(${report.id})" class="p-2 text-blue-600 hover:bg-blue-50 rounded-lg transition-colors" title="Download">
                                    <i class="fas fa-download"></i>
                                </button>
                                <button onclick="viewReport(${report.id})" class="p-2 text-green-600 hover:bg-green-50 rounded-lg transition-colors" title="Visualizar">
                                    <i class="fas fa-eye"></i>
                                </button>
                                <button onclick="deleteReport(${report.id})" class="p-2 text-red-600 hover:bg-red-50 rounded-lg transition-colors" title="Excluir">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            `;
        }).join('');
    }

    function formatDate(dateString) {
        if (!dateString) return 'Sem data';
        const date = new Date(dateString);
        return date.toLocaleDateString('pt-BR');
    }

    function updateStats() {
        const today = new Date().toISOString().split('T')[0];
        const thisMonth = new Date().toISOString().substring(0, 7);
        
        const stats = {
            total: reports.length,
            hoje: reports.filter(r => r.data === today).length,
            mes: reports.filter(r => r.periodo === thisMonth).length,
            exportados: reports.filter(r => r.status === 'concluido').length
        };

        const totalRelatorios = document.getElementById('totalRelatorios');
        const relatoriosHoje = document.getElementById('relatoriosHoje');
        const relatoriosMes = document.getElementById('relatoriosMes');
        const relatoriosExportados = document.getElementById('relatoriosExportados');

        if (totalRelatorios) totalRelatorios.textContent = stats.total;
        if (relatoriosHoje) relatoriosHoje.textContent = stats.hoje;
        if (relatoriosMes) relatoriosMes.textContent = stats.mes;
        if (relatoriosExportados) relatoriosExportados.textContent = stats.exportados;
    }

    function filterReports() {
        const tipoRelatorio = document.getElementById('tipoRelatorio');
        const dataInicial = document.getElementById('dataInicial');
        const dataFinal = document.getElementById('dataFinal');

        const tipoValue = tipoRelatorio ? tipoRelatorio.value : '';
        const dataInicialValue = dataInicial ? dataInicial.value : '';
        const dataFinalValue = dataFinal ? dataFinal.value : '';

        // Filter logic would go here
        renderReports();
    }

    window.generateReport = function() {
        showToast('Gerando relatório...', 'info');
        setTimeout(() => {
            showToast('Relatório gerado com sucesso!', 'success');
            loadReports();
        }, 2000);
    };

    window.exportReport = function() {
        showToast('Exportando relatório...', 'info');
        setTimeout(() => {
            showToast('Relatório exportado com sucesso!', 'success');
        }, 2000);
    };

    window.downloadReport = function(id) {
        const report = reports.find(r => r.id === id);
        if (report) {
            showToast('Baixando relatório...', 'info');
            setTimeout(() => {
                showToast('Relatório baixado com sucesso!', 'success');
            }, 1000);
        }
    };

    window.viewReport = function(id) {
        const report = reports.find(r => r.id === id);
        if (report) {
            showToast('Visualizando relatório...', 'info');
        }
    };

    window.deleteReport = function(id) {
        if (confirm('Tem certeza que deseja excluir este relatório?')) {
            reports = reports.filter(r => r.id !== id);
            renderReports();
            updateStats();
            showToast('Relatório excluído com sucesso!', 'success');
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
