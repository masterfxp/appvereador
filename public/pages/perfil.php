<div class="main-content">
    <!-- Page Header -->
    <div class="page-header">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="page-title">Meu Perfil</h1>
                <p class="mt-2 text-gray-600">Gerencie suas informações pessoais e configurações</p>
            </div>
            <button onclick="saveProfile()" class="bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 text-white px-6 py-3 rounded-lg font-medium shadow-lg hover:shadow-xl transition-all duration-200 flex items-center">
                <i class="fas fa-save mr-2"></i>
                Salvar Alterações
            </button>
        </div>
    </div>

    <!-- Profile Card -->
    <div class="bg-white rounded-xl shadow-sm overflow-hidden mb-8">
        <div class="gradient-bg px-6 py-8">
            <div class="flex items-center">
                <div class="relative">
                    <img id="profilePhoto" src="" alt="Foto do perfil" class="profile-photo" style="display: none; width: 120px; height: 120px; border-radius: 50%; object-fit: cover; border: 4px solid white; box-shadow: 0 4px 12px rgba(0,0,0,0.15);">
                    <div id="profilePhotoPlaceholder" class="profile-photo bg-white bg-opacity-20 flex items-center justify-center" style="width: 120px; height: 120px; border-radius: 50%; border: 4px solid white; box-shadow: 0 4px 12px rgba(0,0,0,0.15);">
                        <i class="fas fa-user text-4xl text-white"></i>
                    </div>
                    <button onclick="openPhotoModal()" class="absolute bottom-0 right-0 bg-blue-600 hover:bg-blue-700 text-white p-2 rounded-full shadow-lg">
                        <i class="fas fa-camera text-sm"></i>
                    </button>
                </div>
                <div class="ml-6 text-white">
                    <h2 id="profileName" class="text-2xl font-bold">Nome do Usuário</h2>
                    <p id="profileRole" class="text-lg opacity-90">Cargo/Função</p>
                    <p id="profileEmail" class="text-sm opacity-75">email@exemplo.com</p>
                </div>
            </div>
        </div>
        
        <!-- Profile Stats -->
        <div class="px-6 py-4 bg-gray-50 border-t border-gray-200">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div class="text-center">
                    <p class="text-2xl font-bold text-gray-900" id="statsProjetos">0</p>
                    <p class="text-sm text-gray-600">Projetos</p>
                </div>
                <div class="text-center">
                    <p class="text-2xl font-bold text-gray-900" id="statsDemandas">0</p>
                    <p class="text-sm text-gray-600">Demandas</p>
                </div>
                <div class="text-center">
                    <p class="text-2xl font-bold text-gray-900" id="statsReunioes">0</p>
                    <p class="text-sm text-gray-600">Reuniões</p>
                </div>
                <div class="text-center">
                    <p class="text-2xl font-bold text-gray-900" id="statsTarefas">0</p>
                    <p class="text-sm text-gray-600">Tarefas</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Profile Form -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <!-- Personal Information -->
        <div class="bg-white rounded-xl shadow-sm p-6 card-hover">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Informações Pessoais</h3>
            <form id="profileForm" class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Nome Completo *</label>
                    <input type="text" id="nome" required class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">E-mail *</label>
                    <input type="email" id="email" required class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Telefone</label>
                    <input type="tel" id="telefone" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Cargo/Função</label>
                    <input type="text" id="cargo" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Foto de Perfil</label>
                    <input type="file" id="foto" accept="image/*" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <p class="text-xs text-gray-500 mt-1">Formatos aceitos: JPG, PNG, GIF. Tamanho máximo: 5MB</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Data de Nascimento</label>
                    <input type="date" id="dataNascimento" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
            </form>
        </div>

        <!-- Account Settings -->
        <div class="bg-white rounded-xl shadow-sm p-6 card-hover">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Configurações da Conta</h3>
            <form id="accountForm" class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Nova Senha</label>
                    <input type="password" id="novaSenha" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Deixe em branco para manter a atual">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Confirmar Nova Senha</label>
                    <input type="password" id="confirmarSenha" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Confirme a nova senha">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Senha Atual *</label>
                    <input type="password" id="senhaAtual" required class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Digite sua senha atual para confirmar">
                </div>
                <div class="flex items-center">
                    <input type="checkbox" id="receberNotificacoes" class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                    <label for="receberNotificacoes" class="ml-2 block text-sm text-gray-700">
                        Receber notificações por e-mail
                    </label>
                </div>
                <div class="flex items-center">
                    <input type="checkbox" id="receberLembretes" class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                    <label for="receberLembretes" class="ml-2 block text-sm text-gray-700">
                        Receber lembretes de reuniões
                    </label>
                </div>
            </form>
        </div>
    </div>

    <!-- Activity History -->
    <div class="bg-white rounded-xl shadow-sm p-6 card-hover mt-8">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">Histórico de Atividades</h3>
        <div class="space-y-4">
            <div class="flex items-center p-3 bg-gray-50 rounded-lg">
                <div class="flex-shrink-0">
                    <div class="h-8 w-8 bg-blue-100 rounded-full flex items-center justify-center">
                        <i class="fas fa-file-alt text-blue-600 text-sm"></i>
                    </div>
                </div>
                <div class="ml-3 flex-1">
                    <p class="text-sm font-medium text-gray-900">Projeto "Melhorias no Transporte" criado</p>
                    <p class="text-xs text-gray-500">Há 2 horas</p>
                </div>
            </div>
            <div class="flex items-center p-3 bg-gray-50 rounded-lg">
                <div class="flex-shrink-0">
                    <div class="h-8 w-8 bg-green-100 rounded-full flex items-center justify-center">
                        <i class="fas fa-comments text-green-600 text-sm"></i>
                    </div>
                </div>
                <div class="ml-3 flex-1">
                    <p class="text-sm font-medium text-gray-900">Nova demanda recebida</p>
                    <p class="text-xs text-gray-500">Ontem</p>
                </div>
            </div>
            <div class="flex items-center p-3 bg-gray-50 rounded-lg">
                <div class="flex-shrink-0">
                    <div class="h-8 w-8 bg-yellow-100 rounded-full flex items-center justify-center">
                        <i class="fas fa-calendar-alt text-yellow-600 text-sm"></i>
                    </div>
                </div>
                <div class="ml-3 flex-1">
                    <p class="text-sm font-medium text-gray-900">Reunião agendada para amanhã</p>
                    <p class="text-xs text-gray-500">2 dias atrás</p>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Photo Upload Modal -->
<div id="photoModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
    <div class="relative top-20 mx-auto p-5 border w-11/12 md:w-1/2 lg:w-1/3 shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-medium text-gray-900">Alterar Foto do Perfil</h3>
                <button onclick="closePhotoModal()" class="text-gray-400 hover:text-gray-600">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>
            
            <div class="space-y-4">
                <!-- Current Photo Preview -->
                <div class="text-center">
                    <div class="relative inline-block">
                        <img id="photoPreview" src="" alt="Preview" class="w-32 h-32 rounded-full object-cover mx-auto" style="display: none;">
                        <div id="photoPreviewPlaceholder" class="w-32 h-32 bg-gray-200 rounded-full flex items-center justify-center mx-auto">
                            <i class="fas fa-user text-4xl text-gray-400"></i>
                        </div>
                    </div>
                </div>

                <!-- Upload Area -->
                <div id="photoUploadArea" class="photo-upload-area p-6 text-center border-2 border-dashed border-gray-300 rounded-lg">
                    <i class="fas fa-cloud-upload-alt text-3xl text-gray-400 mb-2"></i>
                    <p class="text-sm text-gray-600 mb-2">Arraste e solte uma imagem aqui ou</p>
                    <input type="file" id="photoInput" accept="image/*" class="hidden">
                    <button onclick="document.getElementById('photoInput').click()" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg font-medium">
                        Escolher Arquivo
                    </button>
                    <p class="text-xs text-gray-500 mt-2">JPG, PNG ou GIF (máx. 5MB)</p>
                </div>

                <!-- Actions -->
                <div class="flex justify-end space-x-3 pt-4">
                    <button onclick="closePhotoModal()" class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-500">
                        Cancelar
                    </button>
                    <button onclick="savePhoto()" class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <i class="fas fa-save mr-2"></i>
                        Salvar Foto
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
    let selectedPhoto = null;

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
        loadUserProfile();
        setupEventListeners();
    });

    function setupEventListeners() {
        const photoInput = document.getElementById('photoInput');
        if (photoInput) {
            photoInput.addEventListener('change', handlePhotoSelect);
        }
        
        const uploadArea = document.getElementById('photoUploadArea');
        if (uploadArea) {
            uploadArea.addEventListener('dragover', handleDragOver);
            uploadArea.addEventListener('dragleave', handleDragLeave);
            uploadArea.addEventListener('drop', handleDrop);
        }
    }

    function loadUserProfile() {
        try {
            const user = JSON.parse(localStorage.getItem('user'));
            if (user) {
                currentUser = user;
                
                const profileName = document.getElementById('profileName');
                const profileRole = document.getElementById('profileRole');
                const profileEmail = document.getElementById('profileEmail');
                
                if (profileName) profileName.textContent = user.nome || 'Nome do Usuário';
                if (profileRole) profileRole.textContent = user.nivel || 'Cargo/Função';
                if (profileEmail) profileEmail.textContent = user.email || 'email@exemplo.com';
                
                const nome = document.getElementById('nome');
                const email = document.getElementById('email');
                const telefone = document.getElementById('telefone');
                const cargo = document.getElementById('cargo');
                const dataNascimento = document.getElementById('dataNascimento');
                
                if (nome) nome.value = user.nome || '';
                if (email) email.value = user.email || '';
                if (telefone) telefone.value = user.telefone || '';
                if (cargo) cargo.value = user.cargo || user.nivel || '';
                if (dataNascimento) dataNascimento.value = user.dataNascimento || '';

                if (user.foto) {
                    const profilePhoto = document.getElementById('profilePhoto');
                    const profilePhotoPlaceholder = document.getElementById('profilePhotoPlaceholder');
                    if (profilePhoto) {
                        profilePhoto.src = `/uploads/${user.foto}`;
                        profilePhoto.style.display = 'block';
                    }
                    if (profilePhotoPlaceholder) {
                        profilePhotoPlaceholder.style.display = 'none';
                    }
                }

                loadUserStats();
            } else {
                console.log('⚠️ Usuário não encontrado no localStorage');
            }
        } catch (error) {
            console.error('Erro ao carregar dados do usuário:', error);
        }
    }

    function loadUserStats() {
        const statsProjetos = document.getElementById('statsProjetos');
        const statsDemandas = document.getElementById('statsDemandas');
        const statsReunioes = document.getElementById('statsReunioes');
        const statsTarefas = document.getElementById('statsTarefas');
        
        if (statsProjetos) statsProjetos.textContent = '12';
        if (statsDemandas) statsDemandas.textContent = '28';
        if (statsReunioes) statsReunioes.textContent = '8';
        if (statsTarefas) statsTarefas.textContent = '15';
    }

    window.openPhotoModal = function() {
        const modal = document.getElementById('photoModal');
        if (modal) {
            modal.classList.remove('hidden');
        }
    };

    window.closePhotoModal = function() {
        const modal = document.getElementById('photoModal');
        if (modal) {
            modal.classList.add('hidden');
        }
        selectedPhoto = null;
        const photoPreview = document.getElementById('photoPreview');
        const photoPreviewPlaceholder = document.getElementById('photoPreviewPlaceholder');
        if (photoPreview) photoPreview.style.display = 'none';
        if (photoPreviewPlaceholder) photoPreviewPlaceholder.style.display = 'flex';
    };

    function handlePhotoSelect(event) {
        const file = event.target.files[0];
        if (file) {
            selectedPhoto = file;
            previewPhoto(file);
        }
    }

    function handleDragOver(event) {
        event.preventDefault();
        const uploadArea = document.getElementById('photoUploadArea');
        if (uploadArea) {
            uploadArea.classList.add('dragover');
        }
    }

    function handleDragLeave(event) {
        const uploadArea = document.getElementById('photoUploadArea');
        if (uploadArea) {
            uploadArea.classList.remove('dragover');
        }
    }

    function handleDrop(event) {
        event.preventDefault();
        const uploadArea = document.getElementById('photoUploadArea');
        if (uploadArea) {
            uploadArea.classList.remove('dragover');
        }
        
        const files = event.dataTransfer.files;
        if (files.length > 0) {
            const file = files[0];
            if (file.type.startsWith('image/')) {
                selectedPhoto = file;
                previewPhoto(file);
            } else {
                showToast('Por favor, selecione apenas arquivos de imagem.', 'error');
            }
        }
    }

    function previewPhoto(file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            const photoPreview = document.getElementById('photoPreview');
            const photoPreviewPlaceholder = document.getElementById('photoPreviewPlaceholder');
            if (photoPreview) {
                photoPreview.src = e.target.result;
                photoPreview.style.display = 'block';
            }
            if (photoPreviewPlaceholder) {
                photoPreviewPlaceholder.style.display = 'none';
            }
        };
        reader.readAsDataURL(file);
    }

    window.savePhoto = function() {
        if (!selectedPhoto) {
            showToast('Por favor, selecione uma foto.', 'error');
            return;
        }

        showToast('Foto atualizada com sucesso!', 'success');
        closePhotoModal();
        
        const reader = new FileReader();
        reader.onload = function(e) {
            const profilePhoto = document.getElementById('profilePhoto');
            const profilePhotoPlaceholder = document.getElementById('profilePhotoPlaceholder');
            if (profilePhoto) {
                profilePhoto.src = e.target.result;
                profilePhoto.style.display = 'block';
            }
            if (profilePhotoPlaceholder) {
                profilePhotoPlaceholder.style.display = 'none';
            }
        };
        reader.readAsDataURL(selectedPhoto);
    };

    window.saveProfile = async function() {
        const nome = document.getElementById('nome');
        const email = document.getElementById('email');
        const cargo = document.getElementById('cargo');
        const foto = document.getElementById('foto');
        
        if (!nome || !email || !cargo) return;

        const formData = new FormData();
        formData.append('nome', nome.value);
        formData.append('email', email.value);
        formData.append('cargo', cargo.value);

        const fotoFile = foto ? foto.files[0] : null;
        if (fotoFile) {
            formData.append('foto', fotoFile);
        }

        try {
            const token = localStorage.getItem('token');
            const response = await fetch(`${API_BASE}/perfil`, {
                method: 'PUT',
                headers: {
                    'Authorization': `Bearer ${token}`
                },
                body: formData
            });

            if (response.ok) {
                const result = await response.json();
                showToast('Perfil atualizado com sucesso!', 'success');
                
                const profileName = document.getElementById('profileName');
                const profileRole = document.getElementById('profileRole');
                const profileEmail = document.getElementById('profileEmail');
                
                if (profileName) profileName.textContent = result.nome || nome.value;
                if (profileRole) profileRole.textContent = result.cargo || cargo.value;
                if (profileEmail) profileEmail.textContent = result.email || email.value;
            } else {
                const error = await response.json();
                showToast(error.message || 'Erro ao atualizar perfil', 'error');
            }
        } catch (error) {
            console.error('Erro ao salvar perfil:', error);
            showToast('Erro ao atualizar perfil', 'error');
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
