<div class="main-content">
        <!-- Chat Content -->
        <div class="flex-1 flex overflow-hidden">
            <!-- Conversations Sidebar -->
            <div class="w-1/3 bg-white border-r border-gray-200 flex flex-col">
                <div class="p-4 border-b border-gray-200">
                    <h2 class="text-lg font-semibold text-gray-900">Conversas</h2>
                </div>
                <div id="conversationsList" class="flex-1 overflow-y-auto p-2">
                    <!-- Conversations will be loaded here -->
                </div>
            </div>

            <!-- Chat Area -->
            <div class="flex-1 flex flex-col bg-gray-50">
                <!-- Chat Header (hidden by default) -->
                <div id="chatHeader" class="hidden bg-white border-b border-gray-200 p-4">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-3">
                            <div class="h-10 w-10 rounded-full bg-gray-300 flex items-center justify-center">
                                <i class="fas fa-user text-gray-600"></i>
                            </div>
                            <div>
                                <p class="text-sm font-semibold text-gray-900" id="chatUserName">Nome do Usuário</p>
                                <p class="text-xs text-gray-500" id="chatUserRole">Função</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Empty Chat State -->
                <div id="emptyChatState" class="flex-1 flex items-center justify-center">
                    <div class="text-center">
                        <i class="fas fa-comments text-6xl text-gray-300 mb-4"></i>
                        <h3 class="text-lg font-medium text-gray-900 mb-2">Selecione uma conversa</h3>
                        <p class="text-gray-600">Escolha uma conversa da lista para começar a conversar</p>
                    </div>
                </div>

                <!-- Messages List (hidden by default) -->
                <div id="messagesList" class="hidden flex-1 overflow-y-auto p-4 space-y-4">
                    <!-- Messages will be loaded here -->
                </div>

                <!-- Message Input (hidden by default) -->
                <div id="messageInputContainer" class="hidden bg-white border-t border-gray-200 p-4">
                    <div class="flex items-center space-x-3">
                        <button class="p-2 text-gray-400 hover:text-gray-600">
                            <i class="fas fa-paperclip"></i>
                        </button>
                        <div class="flex-1">
                            <input id="messageInput" type="text" class="block w-full px-3 py-2 border border-gray-300 rounded-lg text-sm placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent" placeholder="Digite sua mensagem...">
                        </div>
                        <button id="sendButton" onclick="sendMessage()" class="p-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <i class="fas fa-paper-plane"></i>
                        </button>
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
        
        let currentUser = null;
        let selectedConversation = null;
        let conversations = [];
        let messages = [];
        let messageInterval = null;

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
            loadUser();
            loadConversations();
            setupEventListeners();
        });

        function setupEventListeners() {
            const messageInput = document.getElementById('messageInput');
            if (messageInput) {
                messageInput.addEventListener('keypress', function(e) {
                    if (e.key === 'Enter') {
                        sendMessage();
                    }
                });
            }

            const conversationSearch = document.getElementById('conversationSearch');
            if (conversationSearch) {
                conversationSearch.addEventListener('input', filterConversations);
            }
        }

        function loadUser() {
            try {
                const user = JSON.parse(localStorage.getItem('user'));
                if (user) {
                    currentUser = user;
                } else {
                    console.log('⚠️ Usuário não encontrado no localStorage');
                }
            } catch (error) {
                console.error('Erro ao carregar dados do usuário:', error);
            }
        }

        function loadConversations() {
            // Mock conversations data
            conversations = [
                {
                    id: 1,
                    nome: 'João Silva',
                    role: 'Vereador',
                    ultimaMensagem: 'Obrigado pela informação!',
                    timestamp: '10:30',
                    unread: 2,
                    online: true
                },
                {
                    id: 2,
                    nome: 'Maria Santos',
                    role: 'Assessora',
                    ultimaMensagem: 'Vou verificar isso para você',
                    timestamp: '09:15',
                    unread: 0,
                    online: false
                },
                {
                    id: 3,
                    nome: 'Pedro Costa',
                    role: 'Vereador',
                    ultimaMensagem: 'Preciso de ajuda com o projeto',
                    timestamp: 'Ontem',
                    unread: 1,
                    online: true
                },
                {
                    id: 4,
                    nome: 'Ana Oliveira',
                    role: 'Assessora',
                    ultimaMensagem: 'Documentos enviados',
                    timestamp: 'Ontem',
                    unread: 0,
                    online: false
                }
            ];

            renderConversations();
        }

        function renderConversations() {
            const container = document.getElementById('conversationsList');
            if (!container) return;

            container.innerHTML = conversations.map(conv => `
                <div class="conversation-item p-3 rounded-lg cursor-pointer hover:bg-gray-50 transition-colors ${selectedConversation?.id === conv.id ? 'bg-blue-50 border border-blue-200' : ''}" onclick="selectConversation(${conv.id})">
                    <div class="flex items-center">
                        <div class="relative">
                            <div class="h-10 w-10 rounded-full bg-gray-300 flex items-center justify-center">
                                <i class="fas fa-user text-gray-600"></i>
                            </div>
                            ${conv.online ? '<div class="online-indicator"></div>' : ''}
                        </div>
                        <div class="ml-3 flex-1 min-w-0">
                            <div class="flex items-center justify-between">
                                <p class="text-sm font-medium text-gray-900 truncate">${conv.nome}</p>
                                <p class="text-xs text-gray-500">${conv.timestamp}</p>
                            </div>
                            <div class="flex items-center justify-between">
                                <p class="text-xs text-gray-500 truncate">${conv.ultimaMensagem}</p>
                                ${conv.unread > 0 ? `<span class="inline-flex items-center justify-center px-2 py-1 text-xs font-bold leading-none text-white bg-red-500 rounded-full">${conv.unread}</span>` : ''}
                            </div>
                        </div>
                    </div>
                </div>
            `).join('');
        }

        function filterConversations() {
            const searchInput = document.getElementById('conversationSearch');
            if (!searchInput) return;

            const searchTerm = searchInput.value.toLowerCase();
            const filtered = conversations.filter(conv => 
                conv.nome.toLowerCase().includes(searchTerm) || 
                conv.role.toLowerCase().includes(searchTerm)
            );
            
            const container = document.getElementById('conversationsList');
            if (!container) return;

            container.innerHTML = filtered.map(conv => `
                <div class="conversation-item p-3 rounded-lg cursor-pointer hover:bg-gray-50 transition-colors ${selectedConversation?.id === conv.id ? 'bg-blue-50 border border-blue-200' : ''}" onclick="selectConversation(${conv.id})">
                    <div class="flex items-center">
                        <div class="relative">
                            <div class="h-10 w-10 rounded-full bg-gray-300 flex items-center justify-center">
                                <i class="fas fa-user text-gray-600"></i>
                            </div>
                            ${conv.online ? '<div class="online-indicator"></div>' : ''}
                        </div>
                        <div class="ml-3 flex-1 min-w-0">
                            <div class="flex items-center justify-between">
                                <p class="text-sm font-medium text-gray-900 truncate">${conv.nome}</p>
                                <p class="text-xs text-gray-500">${conv.timestamp}</p>
                            </div>
                            <div class="flex items-center justify-between">
                                <p class="text-xs text-gray-500 truncate">${conv.ultimaMensagem}</p>
                                ${conv.unread > 0 ? `<span class="inline-flex items-center justify-center px-2 py-1 text-xs font-bold leading-none text-white bg-red-500 rounded-full">${conv.unread}</span>` : ''}
                            </div>
                        </div>
                    </div>
                </div>
            `).join('');
        }

        window.selectConversation = function(conversationId) {
            selectedConversation = conversations.find(c => c.id === conversationId);
            
            // Update UI
            const chatHeader = document.getElementById('chatHeader');
            const emptyChatState = document.getElementById('emptyChatState');
            const messagesList = document.getElementById('messagesList');
            const messageInputContainer = document.getElementById('messageInputContainer');

            if (chatHeader) chatHeader.classList.remove('hidden');
            if (emptyChatState) emptyChatState.classList.add('hidden');
            if (messagesList) messagesList.classList.remove('hidden');
            if (messageInputContainer) messageInputContainer.classList.remove('hidden');
            
            // Update chat header
            const chatUserName = document.getElementById('chatUserName');
            const chatUserRole = document.getElementById('chatUserRole');
            if (chatUserName && selectedConversation) chatUserName.textContent = selectedConversation.nome;
            if (chatUserRole && selectedConversation) chatUserRole.textContent = selectedConversation.role;
            
            // Load messages
            loadMessages(conversationId);
            
            // Start polling for new messages
            if (messageInterval) {
                clearInterval(messageInterval);
            }
            messageInterval = setInterval(() => {
                loadMessages(conversationId);
            }, 2000);
            
            // Mark as read
            markAsRead(conversationId);
            
            // Re-render conversations to update selection
            renderConversations();
        };

        function loadMessages(conversationId) {
            if (!currentUser) {
                currentUser = { id: 1, nome: 'Usuário' };
            }

            // Mock messages data
            const mockMessages = {
                1: [
                    { id: 1, sender_id: 1, sender_name: 'João Silva', message: 'Olá! Preciso de ajuda com um projeto de lei.', timestamp: '10:25', sent: false },
                    { id: 2, sender_id: currentUser.id, sender_name: currentUser.nome, message: 'Olá João! Como posso ajudar?', timestamp: '10:26', sent: true },
                    { id: 3, sender_id: 1, sender_name: 'João Silva', message: 'Estou trabalhando em um projeto sobre melhorias no transporte público. Você tem alguma sugestão?', timestamp: '10:28', sent: false },
                    { id: 4, sender_id: currentUser.id, sender_name: currentUser.nome, message: 'Claro! Posso ajudar com pesquisas e análises. Que tipo de melhorias você está pensando?', timestamp: '10:30', sent: true },
                    { id: 5, sender_id: 1, sender_name: 'João Silva', message: 'Obrigado pela informação!', timestamp: '10:30', sent: false }
                ],
                2: [
                    { id: 1, sender_id: 2, sender_name: 'Maria Santos', message: 'Bom dia! Temos uma reunião hoje às 14h.', timestamp: '09:10', sent: false },
                    { id: 2, sender_id: currentUser.id, sender_name: currentUser.nome, message: 'Bom dia Maria! Sim, estou ciente. Vou estar presente.', timestamp: '09:12', sent: true },
                    { id: 3, sender_id: 2, sender_name: 'Maria Santos', message: 'Vou verificar isso para você', timestamp: '09:15', sent: false }
                ],
                3: [
                    { id: 1, sender_id: 3, sender_name: 'Pedro Costa', message: 'Preciso de ajuda com o projeto de revitalização do centro.', timestamp: 'Ontem 16:30', sent: false },
                    { id: 2, sender_id: currentUser.id, sender_name: currentUser.nome, message: 'Claro Pedro! Que tipo de ajuda você precisa?', timestamp: 'Ontem 16:35', sent: true }
                ],
                4: [
                    { id: 1, sender_id: 4, sender_name: 'Ana Oliveira', message: 'Os documentos que você solicitou estão prontos.', timestamp: 'Ontem 14:20', sent: false },
                    { id: 2, sender_id: currentUser.id, sender_name: currentUser.nome, message: 'Perfeito! Obrigado Ana.', timestamp: 'Ontem 14:25', sent: true },
                    { id: 3, sender_id: 4, sender_name: 'Ana Oliveira', message: 'Documentos enviados', timestamp: 'Ontem 14:30', sent: false }
                ]
            };

            messages = mockMessages[conversationId] || [];
            renderMessages();
        }

        function renderMessages() {
            const container = document.getElementById('messagesList');
            if (!container) return;

            container.innerHTML = messages.map(msg => `
                <div class="message-bubble ${msg.sent ? 'message-sent' : 'message-received'} p-3 rounded-lg fade-in">
                    <div class="flex items-start space-x-2">
                        <div class="flex-shrink-0">
                            <div class="h-8 w-8 rounded-full bg-gray-300 flex items-center justify-center">
                                <i class="fas fa-user text-gray-600 text-xs"></i>
                            </div>
                        </div>
                        <div class="flex-1">
                            <div class="flex items-center space-x-2 mb-1">
                                <span class="text-xs font-medium ${msg.sent ? 'text-blue-100' : 'text-gray-600'}">${msg.sender_name}</span>
                                <span class="text-xs ${msg.sent ? 'text-blue-200' : 'text-gray-500'}">${msg.timestamp}</span>
                            </div>
                            <p class="text-sm">${msg.message}</p>
                        </div>
                    </div>
                </div>
            `).join('');

            // Scroll to bottom
            container.scrollTop = container.scrollHeight;
        }

        window.sendMessage = function() {
            const input = document.getElementById('messageInput');
            if (!input) return;

            const message = input.value.trim();
            
            if (!message || !selectedConversation) {
                return;
            }

            if (!currentUser) {
                currentUser = { id: 1, nome: 'Usuário' };
            }

            // Add message to local array
            const newMessage = {
                id: Date.now(),
                sender_id: currentUser.id,
                sender_name: currentUser.nome,
                message: message,
                timestamp: new Date().toLocaleTimeString('pt-BR', { hour: '2-digit', minute: '2-digit' }),
                sent: true
            };

            messages.push(newMessage);
            renderMessages();

            // Clear input
            input.value = '';

            // Simulate response (in real app, this would be sent to server)
            setTimeout(() => {
                const responseMessage = {
                    id: Date.now() + 1,
                    sender_id: selectedConversation.id,
                    sender_name: selectedConversation.nome,
                    message: 'Mensagem recebida! Vou responder em breve.',
                    timestamp: new Date().toLocaleTimeString('pt-BR', { hour: '2-digit', minute: '2-digit' }),
                    sent: false
                };
                messages.push(responseMessage);
                renderMessages();
            }, 1000);
        };

        function markAsRead(conversationId) {
            const conversation = conversations.find(c => c.id === conversationId);
            if (conversation) {
                conversation.unread = 0;
                renderConversations();
            }
        }
    })();
</script>
