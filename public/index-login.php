<?php
// Página de login
// Se já estiver logado, redirecionar para o dashboard
session_start();
if (isset($_SESSION['user'])) {
    header('Location: /dashboard');
    exit;
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>UniAssessor - Sistema de Gestão</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        .gradient-bg {
            background: linear-gradient(135deg, #1e3a8a 0%, #3730a3 25%, #581c87 50%, #7c2d12 75%, #dc2626 100%);
            background-size: 400% 400%;
            animation: gradientShift 15s ease infinite;
        }
        @keyframes gradientShift {
            0% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }
        .card-hover {
            transition: all 0.3s ease;
        }
        .card-hover:hover {
            transform: translateY(-2px);
            box-shadow: 0 20px 40px rgba(0,0,0,0.15);
        }
        .glass-effect {
            background: rgba(255, 255, 255, 0.98);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.3);
            box-shadow: 0 25px 50px rgba(0, 0, 0, 0.1);
        }
        .floating-animation {
            animation: float 6s ease-in-out infinite;
        }
        @keyframes float {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-10px); }
        }
        .pulse-glow {
            animation: pulse-glow 2s ease-in-out infinite alternate;
        }
        @keyframes pulse-glow {
            from { box-shadow: 0 0 20px rgba(102, 126, 234, 0.4); }
            to { box-shadow: 0 0 30px rgba(102, 126, 234, 0.8); }
        }
    </style>
</head>
<body class="gradient-bg min-h-screen flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8 relative overflow-hidden">
    <!-- Background Elements -->
    <div class="absolute inset-0 overflow-hidden">
        <div class="absolute -top-40 -right-40 w-80 h-80 bg-white opacity-5 rounded-full floating-animation"></div>
        <div class="absolute -bottom-40 -left-40 w-80 h-80 bg-white opacity-5 rounded-full floating-animation" style="animation-delay: -3s;"></div>
        <div class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 w-96 h-96 bg-white opacity-3 rounded-full floating-animation" style="animation-delay: -1.5s;"></div>
    </div>

    <div class="max-w-md w-full space-y-8 relative z-10">
        <!-- Logo e Título -->
        <div class="text-center">
            <div class="mx-auto h-20 w-20 bg-white rounded-full flex items-center justify-center shadow-2xl pulse-glow floating-animation">
                <i class="fas fa-landmark text-4xl text-blue-600"></i>
            </div>
            <h2 class="mt-8 text-4xl font-extrabold text-white tracking-tight">
                UniAssessor
            </h2>
            <p class="mt-3 text-lg text-blue-100 font-medium">
                Sistema de Gestão para Gabinetes de Vereadores
            </p>
            <div class="mt-4 flex justify-center space-x-2">
                <div class="w-2 h-2 bg-white rounded-full opacity-60"></div>
                <div class="w-2 h-2 bg-white rounded-full opacity-80"></div>
                <div class="w-2 h-2 bg-white rounded-full opacity-60"></div>
            </div>
        </div>

        <!-- Formulário de Login -->
        <div class="glass-effect rounded-2xl shadow-2xl p-8 card-hover">
            <div class="mb-6">
                <h3 class="text-2xl font-bold text-gray-900 text-center mb-2">Bem-vindo de volta</h3>
                <p class="text-gray-600 text-center">Faça login para acessar sua conta</p>
            </div>

            <form id="loginForm" class="space-y-6">
                <div>
                    <label for="email" class="block text-sm font-semibold text-gray-700 mb-2">
                        <i class="fas fa-envelope mr-2 text-blue-600"></i>
                        E-mail
                    </label>
                    <div class="relative">
                        <input id="email" name="email" type="email" autocomplete="email" required 
                               class="w-full pl-12 pr-4 py-3 border-2 border-gray-200 rounded-xl text-gray-900 placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 bg-gray-50 focus:bg-white" 
                               placeholder="seu@email.com">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <i class="fas fa-envelope text-gray-400"></i>
                        </div>
                    </div>
                </div>

                <div>
                    <label for="password" class="block text-sm font-semibold text-gray-700 mb-2">
                        <i class="fas fa-lock mr-2 text-blue-600"></i>
                        Senha
                    </label>
                    <div class="relative">
                        <input id="password" name="password" type="password" autocomplete="current-password" required 
                               class="w-full pl-12 pr-12 py-3 border-2 border-gray-200 rounded-xl text-gray-900 placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 bg-gray-50 focus:bg-white" 
                               placeholder="Sua senha">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <i class="fas fa-lock text-gray-400"></i>
                        </div>
                        <button type="button" onclick="togglePassword()" class="absolute inset-y-0 right-0 pr-4 flex items-center">
                            <i id="passwordToggle" class="fas fa-eye text-gray-400 hover:text-gray-600"></i>
                        </button>
                    </div>
                </div>

                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <input id="remember-me" name="remember-me" type="checkbox" 
                               class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                        <label for="remember-me" class="ml-3 block text-sm font-medium text-gray-700">
                            Lembrar de mim
                        </label>
                    </div>

                    <div class="text-sm">
                        <a href="#" class="font-semibold text-blue-600 hover:text-blue-500 transition-colors duration-200">
                            Esqueceu a senha?
                        </a>
                    </div>
                </div>

                <div class="space-y-4">
                    <button type="submit" 
                            class="group relative w-full flex justify-center py-3 px-4 border border-transparent text-sm font-bold rounded-xl text-white bg-gradient-to-r from-blue-600 to-purple-600 hover:from-blue-700 hover:to-purple-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-all duration-200 transform hover:scale-105 shadow-lg">
                        <span class="absolute left-0 inset-y-0 flex items-center pl-4">
                            <i class="fas fa-sign-in-alt text-blue-200 group-hover:text-white transition-colors duration-200"></i>
                        </span>
                        Entrar no Sistema
                    </button>
                    
                    <div class="text-center">
                        <p class="text-sm text-gray-600">
                            Não tem uma conta? 
                            <a href="#" class="font-semibold text-blue-600 hover:text-blue-500 transition-colors duration-200">
                                Entre em contato com o administrador
                            </a>
                        </p>
                    </div>
                </div>
            </form>

        </div>

        <!-- Footer -->
        <div class="text-center mt-8">
            <p class="text-xs text-blue-200 opacity-80">
                © 2024 UniAssessor. Todos os direitos reservados.
            </p>
        </div>
    </div>

    <script>
        // Detectar URL da API automaticamente
        const API_BASE = window.location.hostname === 'localhost' || window.location.hostname === '127.0.0.1'
            ? 'http://localhost:3000/api'
            : 'https://uniassessor.com.br/api';

        // Verificar se já está logado - verificação obrigatória
        (function() {
            const token = localStorage.getItem('token');
            const user = localStorage.getItem('user');
            
            // Se houver token E usuário, redirecionar para dashboard
            if (token && user) {
                try {
                    // Verificar se os dados do usuário são válidos
                    const userData = JSON.parse(user);
                    if (userData && userData.nome) {
                        console.log('✅ Usuário já autenticado. Redirecionando para dashboard...');
                        const redirectUrl = window.location.origin + '/index.php?page=dashboard';
                        window.location.replace(redirectUrl);
                        return;
                    }
                } catch (e) {
                    // Se houver erro ao parsear, limpar e continuar na página de login
                    console.warn('⚠️ Dados de usuário inválidos. Limpando...');
                    localStorage.clear();
                    sessionStorage.clear();
                }
            } else {
                // Se não houver token ou usuário, garantir que está limpo
                localStorage.removeItem('token');
                localStorage.removeItem('user');
                sessionStorage.clear();
            }
        })();

        // Toggle password visibility
        function togglePassword() {
            const passwordInput = document.getElementById('password');
            const toggleIcon = document.getElementById('passwordToggle');
            
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                toggleIcon.classList.remove('fa-eye');
                toggleIcon.classList.add('fa-eye-slash');
            } else {
                passwordInput.type = 'password';
                toggleIcon.classList.remove('fa-eye-slash');
                toggleIcon.classList.add('fa-eye');
            }
        }

        // Event listener para o formulário
        document.getElementById('loginForm').addEventListener('submit', async function(e) {
            e.preventDefault();
            
            const submitButton = e.target.querySelector('button[type="submit"]');
            const originalText = submitButton.innerHTML;
            
            // Loading state
            submitButton.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Entrando...';
            submitButton.disabled = true;
            
            const formData = new FormData(this);
            const loginData = {
                email: formData.get('email'),
                senha: formData.get('password')
            };

            try {
                console.log('🔗 Tentando conectar com API:', `${API_BASE}/auth/login`);
                
                const response = await fetch(`${API_BASE}/auth/login`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify(loginData),
                    // Adicionar timeout
                    signal: AbortSignal.timeout(10000) // 10 segundos
                });

                if (response.ok) {
                    const data = await response.json();
                    console.log('✅ Login bem-sucedido:', data);
                    localStorage.setItem('token', data.token);
                    localStorage.setItem('user', JSON.stringify(data.user));
                    
                    // Success state
                    submitButton.innerHTML = '<i class="fas fa-check mr-2"></i>Sucesso!';
                    submitButton.classList.remove('from-blue-600', 'to-purple-600');
                    submitButton.classList.add('from-green-600', 'to-green-700');
                    
                    // Redirecionar imediatamente
                    console.log('🔄 Redirecionando para dashboard...');
                    const redirectUrl = window.location.origin + '/index.php?page=dashboard';
                    window.location.replace(redirectUrl);
                } else {
                    let errorMessage = 'Credenciais inválidas';
                    try {
                        const error = await response.json();
                        errorMessage = error.message || error.error || 'Credenciais inválidas';
                        console.error('❌ Erro da API:', error);
                    } catch (e) {
                        console.error('❌ Erro ao processar resposta:', response.status, response.statusText);
                        if (response.status === 0 || response.status >= 500) {
                            errorMessage = 'Servidor não está respondendo. Verifique se o servidor Node.js está rodando.';
                        } else if (response.status === 401) {
                            errorMessage = 'Email ou senha incorretos.';
                        } else {
                            errorMessage = `Erro ${response.status}: ${response.statusText}`;
                        }
                    }
                    throw new Error(errorMessage);
                }
            } catch (error) {
                console.error('❌ Erro no login:', error);
                
                // Error state
                submitButton.innerHTML = '<i class="fas fa-times mr-2"></i>Erro!';
                submitButton.classList.remove('from-blue-600', 'to-purple-600');
                submitButton.classList.add('from-red-600', 'to-red-700');
                
                let errorMsg = 'Erro ao fazer login. ';
                if (error.name === 'AbortError' || error.message.includes('fetch')) {
                    errorMsg += 'Não foi possível conectar ao servidor. Verifique se o servidor Node.js está rodando na Hostinger.';
                } else {
                    errorMsg += error.message || 'Verifique suas credenciais.';
                }
                
                setTimeout(() => {
                    submitButton.innerHTML = originalText;
                    submitButton.classList.remove('from-red-600', 'to-red-700');
                    submitButton.classList.add('from-blue-600', 'to-purple-600');
                    submitButton.disabled = false;
                }, 3000);
                
                alert(errorMsg);
            }
        });

        // Preencher campos automaticamente para teste
        document.addEventListener('DOMContentLoaded', function() {
            // Preencher com o email do usuário existente
            document.getElementById('email').value = 'dudu0072812@gmail.com';
            document.getElementById('password').value = '123456';
        });
    </script>
</body>
</html>