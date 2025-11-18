<?php
// Página de registro
session_start();
if (isset($_SESSION['user'])) {
    header('Location: index.php?page=dashboard');
    exit;
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>UniAssessor - Cadastro</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8fafc;
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
        }
        .register-container {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 1rem;
        }
        .register-card {
            background: white;
            border-radius: 12px;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
            padding: 2rem;
            width: 100%;
            max-width: 500px;
        }
        .form-input {
            width: 100%;
            padding: 0.75rem 1rem;
            border: 1px solid #d1d5db;
            border-radius: 8px;
            font-size: 1rem;
            transition: all 0.2s;
            background-color: white;
        }
        .form-input:focus {
            outline: none;
            border-color: #3b82f6;
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
        }
        .btn-primary {
            width: 100%;
            background-color: #3b82f6;
            color: white;
            padding: 0.75rem 1rem;
            border-radius: 8px;
            font-weight: 600;
            transition: all 0.2s;
            border: none;
            cursor: pointer;
        }
        .btn-primary:hover {
            background-color: #2563eb;
        }
        .btn-primary:disabled {
            background-color: #9ca3af;
            cursor: not-allowed;
        }
        .btn-secondary {
            width: 100%;
            background-color: white;
            color: #6b7280;
            padding: 0.75rem 1rem;
            border-radius: 8px;
            font-weight: 600;
            transition: all 0.2s;
            border: 1px solid #d1d5db;
            cursor: pointer;
            text-decoration: none;
            display: inline-block;
            text-align: center;
        }
        .btn-secondary:hover {
            background-color: #f9fafb;
        }
        .loading {
            display: none;
        }
        .loading.show {
            display: inline-block;
        }
        .toast {
            position: fixed;
            top: 1rem;
            right: 1rem;
            padding: 1rem 1.5rem;
            border-radius: 8px;
            color: white;
            font-weight: 500;
            z-index: 1000;
            transform: translateX(100%);
            transition: transform 0.3s ease;
        }
        .toast.show {
            transform: translateX(0);
        }
        .toast.success {
            background-color: #10b981;
        }
        .toast.error {
            background-color: #ef4444;
        }
        .password-toggle {
            position: absolute;
            right: 0.75rem;
            top: 50%;
            transform: translateY(-50%);
            cursor: pointer;
            color: #6b7280;
        }
        .input-group {
            position: relative;
        }
    </style>
</head>
<body>
    <div class="register-container">
        <div class="register-card">
            <!-- Logo e Título -->
            <div class="text-center mb-8">
                <div class="inline-flex items-center justify-center w-16 h-16 bg-blue-100 rounded-full mb-4">
                    <i class="fas fa-building text-2xl text-blue-600"></i>
                </div>
                <h1 class="text-2xl font-bold text-gray-900 mb-2">Criar Conta</h1>
                <p class="text-gray-600 text-sm">Cadastre-se no UniAssessor</p>
            </div>

            <!-- Aviso sobre Licença -->
            <div class="bg-gray-50 border border-gray-200 rounded-lg p-4 mb-6">
                <div class="flex items-start">
                    <i class="fas fa-info-circle text-blue-500 mt-1 mr-3"></i>
                    <div>
                        <h3 class="text-sm font-medium text-gray-900 mb-1">Licença Necessária</h3>
                        <p class="text-sm text-gray-600">
                            Para se registrar, você precisa de um GUID de licença válido fornecido pela sua empresa.
                        </p>
                    </div>
                </div>
            </div>

            <!-- Formulário de Cadastro -->
            <form id="registerForm" class="space-y-6">
                <div>
                    <label for="guid_licenca" class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-key mr-2"></i>GUID da Licença *
                    </label>
                    <input type="text" id="guid_licenca" name="guid_licenca" required
                           class="form-input" placeholder="Digite o GUID da licença fornecida">
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="nome" class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-user mr-2"></i>Nome Completo *
                        </label>
                        <input type="text" id="nome" name="nome" required
                               class="form-input" placeholder="Seu nome completo">
                    </div>
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-envelope mr-2"></i>E-mail *
                        </label>
                        <input type="email" id="email" name="email" required
                               class="form-input" placeholder="seu@email.com">
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="senha" class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-lock mr-2"></i>Senha *
                        </label>
                        <div class="input-group">
                            <input type="password" id="senha" name="senha" required
                                   class="form-input pr-10" placeholder="Mínimo 6 caracteres">
                            <i class="fas fa-eye password-toggle" id="togglePassword"></i>
                        </div>
                    </div>
                    <div>
                        <label for="confirmarSenha" class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-lock mr-2"></i>Confirmar Senha *
                        </label>
                        <div class="input-group">
                            <input type="password" id="confirmarSenha" name="confirmarSenha" required
                                   class="form-input pr-10" placeholder="Digite novamente">
                            <i class="fas fa-eye password-toggle" id="toggleConfirmPassword"></i>
                        </div>
                    </div>
                </div>

                <div>
                    <label for="nivel" class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-user-tag mr-2"></i>Tipo de Usuário *
                    </label>
                    <select id="nivel" name="nivel" required class="form-input">
                        <option value="">Selecione seu tipo</option>
                        <option value="vereador">Vereador</option>
                        <option value="assessor">Assessor</option>
                    </select>
                </div>

                <div>
                    <label for="telefone" class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-phone mr-2"></i>Telefone
                    </label>
                    <input type="tel" id="telefone" name="telefone"
                           class="form-input" placeholder="(11) 99999-9999">
                </div>

                <div class="flex items-center">
                    <input type="checkbox" id="aceito" name="aceito" required
                           class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                    <label for="aceito" class="ml-2 block text-sm text-gray-700">
                        Aceito os <a href="#" class="text-blue-600 hover:text-blue-500">termos de uso</a> e 
                        <a href="#" class="text-blue-600 hover:text-blue-500">política de privacidade</a>
                    </label>
                </div>

                <button type="submit" id="registerBtn" class="btn-primary">
                    <span class="loading" id="loading">
                        <i class="fas fa-spinner fa-spin mr-2"></i>
                    </span>
                    <span id="btnText">
                        <i class="fas fa-user-plus mr-2"></i>Criar Conta
                    </span>
                </button>
            </form>

            <!-- Link para Login -->
            <div class="text-center mt-6">
                <p class="text-gray-600 text-sm">
                    Já tem uma conta? 
                    <a href="index.html" class="text-blue-600 hover:text-blue-500 font-medium">
                        Faça login
                    </a>
                </p>
            </div>
        </div>
    </div>

    <!-- Toast para mensagens -->
    <div id="toast" class="toast"></div>

    <script>
        const API_BASE = '/api';

        // Elementos do DOM
        const registerForm = document.getElementById('registerForm');
        const registerBtn = document.getElementById('registerBtn');
        const loading = document.getElementById('loading');
        const btnText = document.getElementById('btnText');
        const togglePassword = document.getElementById('togglePassword');
        const toggleConfirmPassword = document.getElementById('toggleConfirmPassword');
        const senhaInput = document.getElementById('senha');
        const confirmarSenhaInput = document.getElementById('confirmarSenha');

        // Toggle de visibilidade da senha
        togglePassword.addEventListener('click', function() {
            const type = senhaInput.getAttribute('type') === 'password' ? 'text' : 'password';
            senhaInput.setAttribute('type', type);
            
            const icon = this;
            if (type === 'password') {
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            } else {
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            }
        });

        // Toggle de visibilidade da confirmação de senha
        toggleConfirmPassword.addEventListener('click', function() {
            const type = confirmarSenhaInput.getAttribute('type') === 'password' ? 'text' : 'password';
            confirmarSenhaInput.setAttribute('type', type);
            
            const icon = this;
            if (type === 'password') {
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            } else {
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            }
        });

        // Função para mostrar loading
        function showLoading() {
            loading.classList.add('show');
            btnText.style.display = 'none';
            registerBtn.disabled = true;
        }

        // Função para esconder loading
        function hideLoading() {
            loading.classList.remove('show');
            btnText.style.display = 'inline';
            registerBtn.disabled = false;
        }

        // Função para mostrar toast
        function showToast(message, type = 'success') {
            const toast = document.getElementById('toast');
            toast.textContent = message;
            toast.className = `toast ${type}`;
            toast.classList.add('show');
            
            setTimeout(() => {
                toast.classList.remove('show');
            }, 3000);
        }

        // Event listener para o formulário
        registerForm.addEventListener('submit', async function(e) {
            e.preventDefault();
            e.stopPropagation();
            
            const formData = new FormData(registerForm);
            const data = Object.fromEntries(formData.entries());
            
            // Validações
            if (data.senha !== data.confirmarSenha) {
                showToast('As senhas não coincidem', 'error');
                return;
            }
            
            if (data.senha.length < 6) {
                showToast('A senha deve ter pelo menos 6 caracteres', 'error');
                return;
            }
            
            if (!data.aceito) {
                showToast('Você deve aceitar os termos de uso', 'error');
                return;
            }
            
            showLoading();
            
            try {
                const response = await fetch(`${API_BASE}/auth/register`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({
                        guid_licenca: data.guid_licenca,
                        nome: data.nome,
                        email: data.email,
                        senha: data.senha,
                        nivel: data.nivel,
                        telefone: data.telefone || null
                    })
                });
                
                const result = await response.json();
                
                if (response.ok) {
                    showToast('Conta criada com sucesso!', 'success');
                    
                    // Redirecionar para login após 2 segundos
                    setTimeout(() => {
                        window.location.href = 'index-login.php';
                    }, 2000);
                } else {
                    showToast(result.error || 'Erro ao criar conta', 'error');
                }
            } catch (error) {
                console.error('Erro:', error);
                showToast('Erro de conexão. Tente novamente.', 'error');
            } finally {
                hideLoading();
            }
        });

        // Verificar se já está logado
        const token = localStorage.getItem('token');
        if (token) {
            window.location.href = 'dashboard.html';
        }
    </script>
</body>
</html>