<div class="main-content">
    <!-- Page Header -->
    <div class="page-header">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="page-title">Agenda</h1>
                <p class="mt-2 text-gray-600">Gerencie seus compromissos e eventos</p>
            </div>
            <div class="flex items-center space-x-3">
                <button onclick="exportPDF()" class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg font-medium shadow-lg hover:shadow-xl transition-all duration-200 flex items-center">
                    <i class="fas fa-file-pdf mr-2"></i>
                    Exportar PDF
                </button>
                <button onclick="openCompromissoModal()" class="bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 text-white px-6 py-3 rounded-lg font-medium shadow-lg hover:shadow-xl transition-all duration-200 flex items-center">
                    <i class="fas fa-plus mr-2"></i>
                    Adicionar Compromisso
                </button>
            </div>
        </div>
    </div>

    <!-- Search and Filters -->
    <div class="bg-white rounded-xl shadow-sm p-6 mb-6">
        <div class="flex flex-wrap items-center gap-4">
            <div class="flex-1 min-w-64">
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <i class="fas fa-search text-gray-400"></i>
                    </div>
                    <input type="text" id="searchInput" placeholder="Buscar compromissos..." class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
            </div>
            <div class="flex items-center space-x-2">
                <label class="text-sm font-medium text-gray-700">Status:</label>
                <select id="statusFilter" class="border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="">Todos</option>
                    <option value="agendado">Agendado</option>
                    <option value="realizado">Realizado</option>
                    <option value="cancelado">Cancelado</option>
                </select>
            </div>
            <div class="flex items-center space-x-2">
                <label class="text-sm font-medium text-gray-700">Visualização:</label>
                <select id="viewMode" class="border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="month">Mês</option>
                    <option value="week">Semana</option>
                    <option value="day">Dia</option>
                </select>
            </div>
        </div>
    </div>

    <!-- Calendar Navigation -->
    <div class="bg-white rounded-xl shadow-sm p-4 mb-6">
        <div class="flex items-center justify-between">
            <div class="flex items-center space-x-4">
                <button onclick="previousPeriod()" class="p-2 hover:bg-gray-100 rounded-lg transition-colors">
                    <i class="fas fa-chevron-left text-gray-600"></i>
                </button>
                <h2 id="calendarTitle" class="text-xl font-bold text-gray-900">Janeiro 2024</h2>
                <button onclick="nextPeriod()" class="p-2 hover:bg-gray-100 rounded-lg transition-colors">
                    <i class="fas fa-chevron-right text-gray-600"></i>
                </button>
                <button onclick="goToToday()" class="ml-4 px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 text-sm font-medium">
                    Hoje
                </button>
            </div>
            <div class="flex items-center space-x-2">
                <span class="text-sm text-gray-600">Legenda:</span>
                <span class="flex items-center text-sm">
                    <span class="w-3 h-3 bg-blue-500 rounded mr-1"></span>
                    Agendado
                </span>
                <span class="flex items-center text-sm ml-2">
                    <span class="w-3 h-3 bg-green-500 rounded mr-1"></span>
                    Realizado
                </span>
                <span class="flex items-center text-sm ml-2">
                    <span class="w-3 h-3 bg-red-500 rounded mr-1"></span>
                    Cancelado
                </span>
            </div>
        </div>
    </div>

    <!-- Calendar Container -->
    <div id="calendarContainer" class="bg-white rounded-xl shadow-sm p-6">
        <!-- Calendar will be rendered here -->
    </div>
</div>

<!-- Modal de Compromisso -->
<div id="compromissoModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
    <div class="relative top-10 mx-auto p-5 border w-11/12 md:w-3/4 lg:w-2/3 xl:w-1/2 shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-medium text-gray-900" id="modalTitle">Novo Compromisso</h3>
                <button onclick="closeCompromissoModal()" class="text-gray-400 hover:text-gray-600">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>
            <form id="compromissoForm" class="space-y-4">
                <input type="hidden" id="compromissoId">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Data *</label>
                        <input type="date" id="data" required class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Hora</label>
                        <input type="time" id="hora" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Título do Compromisso *</label>
                    <input type="text" id="titulo" required class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Assunto *</label>
                    <input type="text" id="assunto" required class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Descrição *</label>
                    <textarea id="descricao" required rows="3" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"></textarea>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Observações</label>
                    <textarea id="observacoes" rows="2" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"></textarea>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Status *</label>
                    <select id="status" required class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="agendado">Agendado</option>
                        <option value="realizado">Realizado</option>
                        <option value="cancelado">Cancelado</option>
                    </select>
                </div>
                <div id="motivoCancelamentoContainer" class="hidden">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Motivo do Cancelamento *</label>
                    <textarea id="motivoCancelamento" rows="2" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"></textarea>
                </div>
                <div class="flex justify-end space-x-3 pt-4">
                    <button type="button" onclick="closeCompromissoModal()" class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-500">
                        Cancelar
                    </button>
                    <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <i class="fas fa-save mr-2"></i>
                        Salvar Compromisso
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal de Detalhes -->
<div id="detalhesModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
    <div class="relative top-10 mx-auto p-5 border w-11/12 md:w-3/4 lg:w-2/3 xl:w-1/2 shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-medium text-gray-900">Detalhes do Compromisso</h3>
                <button onclick="closeDetalhesModal()" class="text-gray-400 hover:text-gray-600">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>
            <div id="detalhesContent" class="space-y-4">
                <!-- Detalhes serão carregados aqui -->
            </div>
            <div class="flex justify-end space-x-3 pt-4 mt-6 border-t">
                <button onclick="closeDetalhesModal()" class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50">
                    Fechar
                </button>
                <button onclick="editFromDetalhes()" class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                    <i class="fas fa-edit mr-2"></i>
                    Editar
                </button>
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
    
    let compromissos = [];
    let currentDate = new Date();
    let currentView = 'month';
    let currentCompromisso = null;

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
        loadCompromissos();
        setupEventListeners();
        renderCalendar();
    });

    function setupEventListeners() {
        const searchInput = document.getElementById('searchInput');
        if (searchInput) {
            searchInput.addEventListener('input', filterCompromissos);
        }

        const statusFilter = document.getElementById('statusFilter');
        if (statusFilter) {
            statusFilter.addEventListener('change', filterCompromissos);
        }

        const viewMode = document.getElementById('viewMode');
        if (viewMode) {
            viewMode.addEventListener('change', function() {
                currentView = this.value;
                renderCalendar();
            });
        }

        const statusSelect = document.getElementById('status');
        if (statusSelect) {
            statusSelect.addEventListener('change', function() {
                const motivoContainer = document.getElementById('motivoCancelamentoContainer');
                const motivoInput = document.getElementById('motivoCancelamento');
                if (this.value === 'cancelado') {
                    motivoContainer.classList.remove('hidden');
                    motivoInput.required = true;
                } else {
                    motivoContainer.classList.add('hidden');
                    motivoInput.required = false;
                    motivoInput.value = '';
                }
            });
        }

        const compromissoForm = document.getElementById('compromissoForm');
        if (compromissoForm) {
            compromissoForm.addEventListener('submit', handleFormSubmit);
        }
    }

    async function loadCompromissos() {
        try {
            const token = localStorage.getItem('token');
            const response = await fetch(`${API_BASE}/agenda`, {
                headers: token ? { 'Authorization': `Bearer ${token}` } : {}
            });

            if (response.ok) {
                compromissos = await response.json();
                if (!Array.isArray(compromissos)) {
                    compromissos = [];
                }
            } else {
                compromissos = loadFromLocalStorage();
            }
        } catch (error) {
            console.error('Erro ao carregar compromissos:', error);
            compromissos = loadFromLocalStorage();
        }
        renderCalendar();
    }

    function loadFromLocalStorage() {
        try {
            const stored = localStorage.getItem('agenda_compromissos');
            if (stored) {
                return JSON.parse(stored);
            }
        } catch (e) {
            console.error('Erro ao carregar do localStorage:', e);
        }
        return [];
    }

    function saveToLocalStorage() {
        try {
            localStorage.setItem('agenda_compromissos', JSON.stringify(compromissos));
        } catch (e) {
            console.error('Erro ao salvar no localStorage:', e);
        }
    }

    async function saveCompromisso(compromisso) {
        try {
            const token = localStorage.getItem('token');
            if (token) {
                const isEdit = compromisso.id;
                const url = isEdit ? `${API_BASE}/agenda/${compromisso.id}` : `${API_BASE}/agenda`;
                const method = isEdit ? 'PUT' : 'POST';

                const response = await fetch(url, {
                    method: method,
                    headers: {
                        'Content-Type': 'application/json',
                        'Authorization': `Bearer ${token}`
                    },
                    body: JSON.stringify(compromisso)
                });

                if (response.ok) {
                    return await response.json();
                }
            }
        } catch (error) {
            console.error('Erro ao salvar na API:', error);
        }

        // Fallback para localStorage
        if (!compromisso.id) {
            compromisso.id = Date.now();
        }
        
        const index = compromissos.findIndex(c => c.id === compromisso.id);
        if (index !== -1) {
            compromissos[index] = compromisso;
        } else {
            compromissos.push(compromisso);
        }
        
        saveToLocalStorage();
        return compromisso;
    }

    async function deleteCompromisso(id) {
        try {
            const token = localStorage.getItem('token');
            if (token) {
                const response = await fetch(`${API_BASE}/agenda/${id}`, {
                    method: 'DELETE',
                    headers: {
                        'Authorization': `Bearer ${token}`
                    }
                });

                if (response.ok) {
                    return true;
                }
            }
        } catch (error) {
            console.error('Erro ao deletar na API:', error);
        }

        // Fallback para localStorage
        compromissos = compromissos.filter(c => c.id !== id);
        saveToLocalStorage();
        return true;
    }

    function renderCalendar() {
        const container = document.getElementById('calendarContainer');
        if (!container) return;

        const title = document.getElementById('calendarTitle');
        if (title) {
            title.textContent = getCalendarTitle();
        }

        if (currentView === 'month') {
            container.innerHTML = renderMonthView();
        } else if (currentView === 'week') {
            container.innerHTML = renderWeekView();
        } else {
            container.innerHTML = renderDayView();
        }
    }

    function getCalendarTitle() {
        const months = ['Janeiro', 'Fevereiro', 'Março', 'Abril', 'Maio', 'Junho', 'Julho', 'Agosto', 'Setembro', 'Outubro', 'Novembro', 'Dezembro'];
        const days = ['Domingo', 'Segunda-feira', 'Terça-feira', 'Quarta-feira', 'Quinta-feira', 'Sexta-feira', 'Sábado'];
        
        if (currentView === 'month') {
            return `${months[currentDate.getMonth()]} ${currentDate.getFullYear()}`;
        } else if (currentView === 'week') {
            const weekStart = getWeekStart(currentDate);
            const weekEnd = new Date(weekStart);
            weekEnd.setDate(weekEnd.getDate() + 6);
            return `${weekStart.getDate()}/${weekStart.getMonth() + 1} - ${weekEnd.getDate()}/${weekEnd.getMonth() + 1} ${weekEnd.getFullYear()}`;
        } else {
            return `${days[currentDate.getDay()]}, ${currentDate.getDate()} de ${months[currentDate.getMonth()]} de ${currentDate.getFullYear()}`;
        }
    }

    function renderMonthView() {
        const year = currentDate.getFullYear();
        const month = currentDate.getMonth();
        const firstDay = new Date(year, month, 1);
        const lastDay = new Date(year, month + 1, 0);
        const daysInMonth = lastDay.getDate();
        const startingDayOfWeek = firstDay.getDay();

        let html = '<div class="grid grid-cols-7 gap-2">';
        
        // Headers
        const dayHeaders = ['Dom', 'Seg', 'Ter', 'Qua', 'Qui', 'Sex', 'Sáb'];
        dayHeaders.forEach(day => {
            html += `<div class="text-center font-semibold text-gray-700 py-2">${day}</div>`;
        });

        // Empty cells for days before month starts
        for (let i = 0; i < startingDayOfWeek; i++) {
            html += '<div class="min-h-24 border border-gray-200 rounded-lg p-2"></div>';
        }

        // Days of the month
        for (let day = 1; day <= daysInMonth; day++) {
            const date = new Date(year, month, day);
            const dayCompromissos = getCompromissosForDate(date);
            const dayClass = isToday(date) ? 'bg-blue-50 border-blue-300' : 'border-gray-200';
            
            html += `<div class="min-h-24 border rounded-lg p-2 ${dayClass} cursor-pointer hover:bg-gray-50" onclick="selectDate(${year}, ${month}, ${day})">`;
            html += `<div class="font-semibold text-gray-900 mb-1">${day}</div>`;
            
            dayCompromissos.slice(0, 3).forEach(comp => {
                const statusColor = getStatusColor(comp.status);
                html += `<div class="text-xs p-1 mb-1 rounded ${statusColor} text-white truncate cursor-pointer" onclick="event.stopPropagation(); viewCompromisso(${comp.id})" title="${comp.titulo}">${comp.titulo}</div>`;
            });
            
            if (dayCompromissos.length > 3) {
                html += `<div class="text-xs text-gray-500">+${dayCompromissos.length - 3} mais</div>`;
            }
            
            html += '</div>';
        }

        html += '</div>';
        return html;
    }

    function renderWeekView() {
        const weekStart = getWeekStart(currentDate);
        let html = '<div class="space-y-2">';
        
        for (let i = 0; i < 7; i++) {
            const date = new Date(weekStart);
            date.setDate(date.getDate() + i);
            const dayCompromissos = getCompromissosForDate(date);
            const dayName = ['Dom', 'Seg', 'Ter', 'Qua', 'Qui', 'Sex', 'Sáb'][i];
            const isTodayClass = isToday(date) ? 'bg-blue-50 border-blue-300' : 'border-gray-200';
            
            html += `<div class="border rounded-lg p-4 ${isTodayClass}">`;
            html += `<div class="font-semibold text-gray-900 mb-2">${dayName}, ${date.getDate()}/${date.getMonth() + 1}</div>`;
            
            if (dayCompromissos.length === 0) {
                html += '<div class="text-sm text-gray-400">Nenhum compromisso</div>';
            } else {
                dayCompromissos.forEach(comp => {
                    const statusColor = getStatusColor(comp.status);
                    html += `<div class="p-2 mb-2 rounded ${statusColor} text-white cursor-pointer" onclick="viewCompromisso(${comp.id})">`;
                    html += `<div class="font-medium">${comp.titulo}</div>`;
                    if (comp.hora) {
                        html += `<div class="text-xs opacity-90">${comp.hora}</div>`;
                    }
                    html += '</div>';
                });
            }
            
            html += '</div>';
        }
        
        html += '</div>';
        return html;
    }

    function renderDayView() {
        const dayCompromissos = getCompromissosForDate(currentDate);
        let html = '<div class="space-y-3">';
        
        if (dayCompromissos.length === 0) {
            html += '<div class="text-center py-12 text-gray-400">Nenhum compromisso para este dia</div>';
        } else {
            dayCompromissos.forEach(comp => {
                const statusColor = getStatusColor(comp.status);
                html += `<div class="border rounded-lg p-4 cursor-pointer hover:shadow-md transition-shadow" onclick="viewCompromisso(${comp.id})">`;
                html += `<div class="flex items-start justify-between">`;
                html += `<div class="flex-1">`;
                html += `<div class="flex items-center mb-2">`;
                html += `<span class="w-3 h-3 rounded-full ${statusColor} mr-2"></span>`;
                html += `<h3 class="font-semibold text-gray-900">${comp.titulo}</h3>`;
                html += `</div>`;
                if (comp.hora) {
                    html += `<div class="text-sm text-gray-600 mb-1"><i class="fas fa-clock mr-1"></i>${comp.hora}</div>`;
                }
                html += `<div class="text-sm text-gray-700 mb-1"><strong>Assunto:</strong> ${comp.assunto}</div>`;
                html += `<div class="text-sm text-gray-600">${comp.descricao}</div>`;
                html += `</div>`;
                html += `<div class="ml-4">`;
                html += `<span class="px-2 py-1 text-xs rounded ${statusColor} text-white">${comp.status}</span>`;
                html += `</div>`;
                html += `</div>`;
                html += `</div>`;
            });
        }
        
        html += '</div>';
        return html;
    }

    function getWeekStart(date) {
        const d = new Date(date);
        const day = d.getDay();
        const diff = d.getDate() - day;
        return new Date(d.setDate(diff));
    }

    function getCompromissosForDate(date) {
        const dateStr = formatDateForComparison(date);
        return compromissos.filter(c => {
            const compDate = formatDateForComparison(new Date(c.data));
            return compDate === dateStr;
        });
    }

    function formatDateForComparison(date) {
        const year = date.getFullYear();
        const month = String(date.getMonth() + 1).padStart(2, '0');
        const day = String(date.getDate()).padStart(2, '0');
        return `${year}-${month}-${day}`;
    }

    function isToday(date) {
        const today = new Date();
        return date.getDate() === today.getDate() &&
               date.getMonth() === today.getMonth() &&
               date.getFullYear() === today.getFullYear();
    }

    function getStatusColor(status) {
        const colors = {
            'agendado': 'bg-blue-500',
            'realizado': 'bg-green-500',
            'cancelado': 'bg-red-500'
        };
        return colors[status] || 'bg-gray-500';
    }

    window.previousPeriod = function() {
        if (currentView === 'month') {
            currentDate.setMonth(currentDate.getMonth() - 1);
        } else if (currentView === 'week') {
            currentDate.setDate(currentDate.getDate() - 7);
        } else {
            currentDate.setDate(currentDate.getDate() - 1);
        }
        renderCalendar();
    };

    window.nextPeriod = function() {
        if (currentView === 'month') {
            currentDate.setMonth(currentDate.getMonth() + 1);
        } else if (currentView === 'week') {
            currentDate.setDate(currentDate.getDate() + 7);
        } else {
            currentDate.setDate(currentDate.getDate() + 1);
        }
        renderCalendar();
    };

    window.goToToday = function() {
        currentDate = new Date();
        renderCalendar();
    };

    window.selectDate = function(year, month, day) {
        currentDate = new Date(year, month, day);
        if (currentView === 'day') {
            renderCalendar();
        } else {
            currentView = 'day';
            document.getElementById('viewMode').value = 'day';
            renderCalendar();
        }
    };

    window.openCompromissoModal = function(compromisso = null) {
        const modal = document.getElementById('compromissoModal');
        const form = document.getElementById('compromissoForm');
        const title = document.getElementById('modalTitle');
        
        if (!modal || !form || !title) return;

        if (compromisso) {
            currentCompromisso = compromisso;
            title.textContent = 'Editar Compromisso';
            document.getElementById('compromissoId').value = compromisso.id;
            document.getElementById('data').value = compromisso.data;
            document.getElementById('hora').value = compromisso.hora || '';
            document.getElementById('titulo').value = compromisso.titulo || '';
            document.getElementById('assunto').value = compromisso.assunto || '';
            document.getElementById('descricao').value = compromisso.descricao || '';
            document.getElementById('observacoes').value = compromisso.observacoes || '';
            document.getElementById('status').value = compromisso.status || 'agendado';
            
            const motivoContainer = document.getElementById('motivoCancelamentoContainer');
            const motivoInput = document.getElementById('motivoCancelamento');
            if (compromisso.status === 'cancelado') {
                motivoContainer.classList.remove('hidden');
                motivoInput.required = true;
                motivoInput.value = compromisso.motivoCancelamento || '';
            } else {
                motivoContainer.classList.add('hidden');
                motivoInput.required = false;
            }
        } else {
            currentCompromisso = null;
            title.textContent = 'Novo Compromisso';
            form.reset();
            document.getElementById('compromissoId').value = '';
            document.getElementById('data').value = formatDateForInput(currentDate);
            document.getElementById('motivoCancelamentoContainer').classList.add('hidden');
        }

        modal.classList.remove('hidden');
    };

    window.closeCompromissoModal = function() {
        const modal = document.getElementById('compromissoModal');
        if (modal) {
            modal.classList.add('hidden');
        }
        currentCompromisso = null;
    };

    window.viewCompromisso = function(id) {
        const compromisso = compromissos.find(c => c.id === id);
        if (!compromisso) return;

        currentCompromisso = compromisso;
        const modal = document.getElementById('detalhesModal');
        const content = document.getElementById('detalhesContent');
        
        if (!modal || !content) return;

        const statusColor = getStatusColor(compromisso.status);
        const statusLabel = compromisso.status.charAt(0).toUpperCase() + compromisso.status.slice(1);

        content.innerHTML = `
            <div class="space-y-4">
                <div class="flex items-center justify-between">
                    <h4 class="text-lg font-semibold text-gray-900">${compromisso.titulo}</h4>
                    <span class="px-3 py-1 text-sm rounded ${statusColor} text-white">${statusLabel}</span>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="text-sm font-medium text-gray-600">Data</label>
                        <p class="text-gray-900">${formatDateDisplay(compromisso.data)}</p>
                    </div>
                    ${compromisso.hora ? `
                    <div>
                        <label class="text-sm font-medium text-gray-600">Hora</label>
                        <p class="text-gray-900">${compromisso.hora}</p>
                    </div>
                    ` : ''}
                </div>
                <div>
                    <label class="text-sm font-medium text-gray-600">Assunto</label>
                    <p class="text-gray-900">${compromisso.assunto}</p>
                </div>
                <div>
                    <label class="text-sm font-medium text-gray-600">Descrição</label>
                    <p class="text-gray-900 whitespace-pre-wrap">${compromisso.descricao}</p>
                </div>
                ${compromisso.observacoes ? `
                <div>
                    <label class="text-sm font-medium text-gray-600">Observações</label>
                    <p class="text-gray-900 whitespace-pre-wrap">${compromisso.observacoes}</p>
                </div>
                ` : ''}
                ${compromisso.motivoCancelamento ? `
                <div>
                    <label class="text-sm font-medium text-gray-600">Motivo do Cancelamento</label>
                    <p class="text-gray-900 whitespace-pre-wrap">${compromisso.motivoCancelamento}</p>
                </div>
                ` : ''}
            </div>
        `;

        modal.classList.remove('hidden');
    };

    window.closeDetalhesModal = function() {
        const modal = document.getElementById('detalhesModal');
        if (modal) {
            modal.classList.add('hidden');
        }
        currentCompromisso = null;
    };

    window.editFromDetalhes = function() {
        closeDetalhesModal();
        if (currentCompromisso) {
            openCompromissoModal(currentCompromisso);
        }
    };

    function formatDateForInput(date) {
        const year = date.getFullYear();
        const month = String(date.getMonth() + 1).padStart(2, '0');
        const day = String(date.getDate()).padStart(2, '0');
        return `${year}-${month}-${day}`;
    }

    function formatDateDisplay(dateStr) {
        const date = new Date(dateStr);
        const day = String(date.getDate()).padStart(2, '0');
        const month = String(date.getMonth() + 1).padStart(2, '0');
        const year = date.getFullYear();
        return `${day}/${month}/${year}`;
    }

    async function handleFormSubmit(e) {
        e.preventDefault();

        const motivoContainer = document.getElementById('motivoCancelamentoContainer');
        const status = document.getElementById('status').value;
        const motivoInput = document.getElementById('motivoCancelamento');

        if (status === 'cancelado' && (!motivoInput.value || motivoInput.value.trim() === '')) {
            showToast('Por favor, informe o motivo do cancelamento', 'error');
            return;
        }

        const compromisso = {
            id: document.getElementById('compromissoId').value || null,
            data: document.getElementById('data').value,
            hora: document.getElementById('hora').value || null,
            titulo: document.getElementById('titulo').value,
            assunto: document.getElementById('assunto').value,
            descricao: document.getElementById('descricao').value,
            observacoes: document.getElementById('observacoes').value || null,
            status: status,
            motivoCancelamento: status === 'cancelado' ? motivoInput.value : null
        };

        try {
            await saveCompromisso(compromisso);
            showToast('Compromisso salvo com sucesso!', 'success');
            closeCompromissoModal();
            loadCompromissos();
        } catch (error) {
            console.error('Erro ao salvar compromisso:', error);
            showToast('Erro ao salvar compromisso', 'error');
        }
    }

    function filterCompromissos() {
        renderCalendar();
    }

    window.exportPDF = function() {
        showToast('Exportando PDF...', 'info');
        // Implementação básica de exportação
        const month = currentDate.getMonth() + 1;
        const year = currentDate.getFullYear();
        const monthCompromissos = compromissos.filter(c => {
            const compDate = new Date(c.data);
            return compDate.getMonth() + 1 === month && compDate.getFullYear() === year;
        });

        let content = `Relatório de Compromissos - ${month}/${year}\n\n`;
        monthCompromissos.forEach(comp => {
            content += `${formatDateDisplay(comp.data)} ${comp.hora ? comp.hora : ''} - ${comp.titulo} (${comp.status})\n`;
            content += `Assunto: ${comp.assunto}\n`;
            content += `Descrição: ${comp.descricao}\n\n`;
        });

        const blob = new Blob([content], { type: 'text/plain' });
        const url = window.URL.createObjectURL(blob);
        const a = document.createElement('a');
        a.href = url;
        a.download = `agenda_${month}_${year}.txt`;
        a.click();
        window.URL.revokeObjectURL(url);
        
        showToast('Relatório exportado!', 'success');
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





