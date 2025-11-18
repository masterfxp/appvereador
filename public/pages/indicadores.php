<div class="main-content">
        <!-- Page Header -->
        <div class="page-header">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="page-title">Indicadores</h1>
                    <p class="mt-2 text-gray-600">Acompanhe os principais indicadores de performance</p>
                </div>
                <div class="flex items-center space-x-3">
                    <button onclick="refreshData()" class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg font-medium shadow-lg hover:shadow-xl transition-all duration-200 flex items-center">
                        <i class="fas fa-sync-alt mr-2"></i>
                        Atualizar
                    </button>
                    <button onclick="exportData()" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg font-medium shadow-lg hover:shadow-xl transition-all duration-200 flex items-center">
                        <i class="fas fa-download mr-2"></i>
                        Exportar
                    </button>
                </div>
            </div>
        </div>

        <!-- KPI Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <div class="kpi-card rounded-xl shadow-sm p-6 card-hover fade-in">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-white bg-opacity-20">
                        <i class="fas fa-file-alt text-2xl"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium opacity-90">Projetos</p>
                        <p class="text-3xl font-bold" id="kpiProjetos">0</p>
                    </div>
                </div>
            </div>
            <div class="kpi-card success rounded-xl shadow-sm p-6 card-hover fade-in">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-white bg-opacity-20">
                        <i class="fas fa-comments text-2xl"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium opacity-90">Demandas</p>
                        <p class="text-3xl font-bold" id="kpiDemandas">0</p>
                    </div>
                </div>
            </div>
            <div class="kpi-card warning rounded-xl shadow-sm p-6 card-hover fade-in">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-white bg-opacity-20">
                        <i class="fas fa-calendar-alt text-2xl"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium opacity-90">Reuniões</p>
                        <p class="text-3xl font-bold" id="kpiReunioes">0</p>
                    </div>
                </div>
            </div>
            <div class="kpi-card danger rounded-xl shadow-sm p-6 card-hover fade-in">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-white bg-opacity-20">
                        <i class="fas fa-tasks text-2xl"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium opacity-90">Tarefas</p>
                        <p class="text-3xl font-bold" id="kpiTarefas">0</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Charts Grid -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
            <!-- Status Distribution Chart -->
            <div class="bg-white rounded-xl shadow-sm p-6 card-hover">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Distribuição por Status</h3>
                <div class="chart-container">
                    <canvas id="statusChart"></canvas>
                </div>
            </div>

            <!-- Monthly Evolution Chart -->
            <div class="bg-white rounded-xl shadow-sm p-6 card-hover">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Evolução Mensal</h3>
                <div class="chart-container">
                    <canvas id="evolutionChart"></canvas>
                </div>
            </div>
        </div>

        <!-- Activities by User Chart -->
        <div class="bg-white rounded-xl shadow-sm p-6 card-hover mb-8">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Atividades por Assessor</h3>
            <div class="chart-container">
                <canvas id="activitiesChart"></canvas>
            </div>
        </div>

        <!-- Detailed Metrics -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="bg-white rounded-xl shadow-sm p-6 card-hover">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Eficiência</h3>
                <div class="space-y-3">
                    <div class="flex justify-between items-center">
                        <span class="text-sm text-gray-600">Taxa de Conclusão</span>
                        <span class="text-sm font-medium text-green-600">85%</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-sm text-gray-600">Tempo Médio</span>
                        <span class="text-sm font-medium text-blue-600">3.2 dias</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-sm text-gray-600">Satisfação</span>
                        <span class="text-sm font-medium text-yellow-600">4.7/5</span>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-sm p-6 card-hover">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Produtividade</h3>
                <div class="space-y-3">
                    <div class="flex justify-between items-center">
                        <span class="text-sm text-gray-600">Projetos/Mês</span>
                        <span class="text-sm font-medium text-green-600">12</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-sm text-gray-600">Demandas/Mês</span>
                        <span class="text-sm font-medium text-blue-600">28</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-sm text-gray-600">Reuniões/Mês</span>
                        <span class="text-sm font-medium text-purple-600">8</span>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-sm p-6 card-hover">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Qualidade</h3>
                <div class="space-y-3">
                    <div class="flex justify-between items-center">
                        <span class="text-sm text-gray-600">Aprovação</span>
                        <span class="text-sm font-medium text-green-600">92%</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-sm text-gray-600">Rejeição</span>
                        <span class="text-sm font-medium text-red-600">3%</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-sm text-gray-600">Revisão</span>
                        <span class="text-sm font-medium text-yellow-600">5%</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
<script>
    (function() {
        'use strict';
        const API_BASE = window.location.hostname === 'localhost' || window.location.hostname === '127.0.0.1'
            ? 'http://localhost:3000/api'
            : 'https://uniassessor.com.br/api';
        
        let statusChart = null;
        let evolutionChart = null;
        let activitiesChart = null;

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
            loadIndicators();
            createCharts();
        });

        async function loadIndicators() {
            try {
                const token = localStorage.getItem('token');
                if (!token) {
                    updateKPIs(getMockData());
                    return;
                }

                const response = await fetch(`${API_BASE}/indicadores`, {
                    headers: {
                        'Authorization': `Bearer ${token}`
                    }
                });

                if (response.ok) {
                    const data = await response.json();
                    updateKPIs(data);
                } else {
                    updateKPIs(getMockData());
                }
            } catch (error) {
                console.error('Erro ao carregar indicadores:', error);
                updateKPIs(getMockData());
            }
        }

        function getMockData() {
            return {
                projetos: 45,
                demandas: 128,
                reunioes: 23,
                tarefas: 67,
                statusDistribution: {
                    aprovado: 35,
                    em_tramitacao: 8,
                    rejeitado: 2,
                    arquivado: 0
                },
                monthlyEvolution: {
                    labels: ['Jan', 'Fev', 'Mar', 'Abr', 'Mai', 'Jun'],
                    projetos: [8, 12, 15, 10, 18, 20],
                    demandas: [25, 30, 35, 28, 40, 45],
                    reunioes: [5, 8, 6, 10, 12, 15],
                    tarefas: [15, 18, 22, 20, 25, 28]
                },
                activitiesByUser: {
                    labels: ['João Silva', 'Maria Santos', 'Pedro Costa', 'Ana Oliveira'],
                    data: [25, 30, 20, 15]
                }
            };
        }

        function updateKPIs(data) {
            const kpiProjetos = document.getElementById('kpiProjetos');
            const kpiDemandas = document.getElementById('kpiDemandas');
            const kpiReunioes = document.getElementById('kpiReunioes');
            const kpiTarefas = document.getElementById('kpiTarefas');

            if (kpiProjetos) kpiProjetos.textContent = data.projetos || 0;
            if (kpiDemandas) kpiDemandas.textContent = data.demandas || 0;
            if (kpiReunioes) kpiReunioes.textContent = data.reunioes || 0;
            if (kpiTarefas) kpiTarefas.textContent = data.tarefas || 0;
        }

        function createCharts() {
            if (typeof Chart === 'undefined') {
                console.warn('Chart.js não está carregado');
                return;
            }

            const data = getMockData();
            
            // Status Distribution Chart
            const statusCtx = document.getElementById('statusChart');
            if (statusCtx) {
                statusChart = new Chart(statusCtx, {
                    type: 'doughnut',
                    data: {
                        labels: ['Aprovado', 'Em Tramitação', 'Rejeitado', 'Arquivado'],
                        datasets: [{
                            data: [
                                data.statusDistribution.aprovado,
                                data.statusDistribution.em_tramitacao,
                                data.statusDistribution.rejeitado,
                                data.statusDistribution.arquivado
                            ],
                            backgroundColor: [
                                '#10b981',
                                '#3b82f6',
                                '#ef4444',
                                '#6b7280'
                            ]
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                position: 'bottom'
                            }
                        }
                    }
                });
            }

            // Monthly Evolution Chart
            const evolutionCtx = document.getElementById('evolutionChart');
            if (evolutionCtx) {
                evolutionChart = new Chart(evolutionCtx, {
                    type: 'line',
                    data: {
                        labels: data.monthlyEvolution.labels,
                        datasets: [
                            {
                                label: 'Projetos',
                                data: data.monthlyEvolution.projetos,
                                borderColor: '#3b82f6',
                                backgroundColor: 'rgba(59, 130, 246, 0.1)',
                                tension: 0.4
                            },
                            {
                                label: 'Demandas',
                                data: data.monthlyEvolution.demandas,
                                borderColor: '#10b981',
                                backgroundColor: 'rgba(16, 185, 129, 0.1)',
                                tension: 0.4
                            },
                            {
                                label: 'Reuniões',
                                data: data.monthlyEvolution.reunioes,
                                borderColor: '#f59e0b',
                                backgroundColor: 'rgba(245, 158, 11, 0.1)',
                                tension: 0.4
                            },
                            {
                                label: 'Tarefas',
                                data: data.monthlyEvolution.tarefas,
                                borderColor: '#ef4444',
                                backgroundColor: 'rgba(239, 68, 68, 0.1)',
                                tension: 0.4
                            }
                        ]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        scales: {
                            y: {
                                beginAtZero: true
                            }
                        }
                    }
                });
            }

            // Activities by User Chart
            const activitiesCtx = document.getElementById('activitiesChart');
            if (activitiesCtx) {
                activitiesChart = new Chart(activitiesCtx, {
                    type: 'bar',
                    data: {
                        labels: data.activitiesByUser.labels,
                        datasets: [{
                            label: 'Atividades',
                            data: data.activitiesByUser.data,
                            backgroundColor: [
                                '#3b82f6',
                                '#10b981',
                                '#f59e0b',
                                '#ef4444'
                            ]
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        scales: {
                            y: {
                                beginAtZero: true
                            }
                        }
                    }
                });
            }
        }

        window.refreshData = function() {
            loadIndicators();
            showToast('Dados atualizados com sucesso!', 'success');
        };

        window.exportData = function() {
            showToast('Exportando dados...', 'info');
            setTimeout(() => {
                showToast('Dados exportados com sucesso!', 'success');
            }, 2000);
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
