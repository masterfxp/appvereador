<?php
// Habilitar exibição de erros temporariamente para debug
error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);

session_start();

// Verificar autenticação - se não houver sessão, redirecionar para login
// A verificação via JavaScript também será feita, mas esta é uma camada de segurança adicional
if (!isset($_SESSION['user']) && !isset($_SESSION['token'])) {
    // Não redirecionar aqui, deixar o JavaScript fazer isso para evitar problemas com localStorage
}

// Incluir router
$route = require 'router.php';

// Definir título da página
$pageTitle = $route['title'] . ' - UniAssessor';
$currentPage = $route['page'];
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($pageTitle); ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="css/style.css">
    <style>
        /* CSS Global para Modais */
        .modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            justify-content: center;
            align-items: center;
            z-index: 9999;
        }
        
        .modal-content {
            background: #fff;
            padding: 20px;
            border-radius: 8px;
            width: 450px;
            max-width: 95%;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
            position: relative;
        }
        
        .modal .close {
            position: absolute;
            top: 10px;
            right: 15px;
            font-size: 22px;
            color: #666;
            cursor: pointer;
        }
        
        .modal .close:hover {
            color: #000;
        }
    </style>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        .gradient-bg {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
        .card-hover {
            transition: all 0.3s ease;
        }
        .card-hover:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(0,0,0,0.1);
        }
        .fade-in {
            animation: fadeIn 0.5s ease-in;
        }
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .slide-in {
            animation: slideIn 0.3s ease-out;
        }
        @keyframes slideIn {
            from { transform: translateX(-100%); }
            to { transform: translateX(0); }
        }
    </style>
</head>
<body class="bg-gray-50">
    <!-- Verificação de autenticação antes de renderizar qualquer conteúdo -->
    <script>
        // Verificação imediata ANTES de qualquer renderização
        (function() {
            const token = localStorage.getItem('token');
            const user = localStorage.getItem('user');
            
            if (!token || !user) {
                // Limpar dados e redirecionar imediatamente
                localStorage.clear();
                sessionStorage.clear();
                window.location.replace('index-login.php');
                // Parar execução
                throw new Error('Redirecionando para login');
            }
        })();
    </script>
    <div class="min-h-screen flex">
        <?php include 'includes/sidebar.php'; ?>

        <!-- Main Content -->
        <div class="flex flex-col w-0 flex-1 overflow-hidden">
            <?php include 'includes/header.php'; ?>

            <!-- Page Content -->
            <main class="flex-1 relative overflow-y-auto focus:outline-none" id="mainContent">
                <div class="py-6">
                    <?php
                    // Carregar conteúdo da página
                    $pageFile = $route['file'];
                    
                    // Verificação de segurança adicional
                    $allowedPages = ['dashboard', 'projetos', 'demandas', 'reunioes', 'tarefas', 'noticias', 'indicadores', 'chat', 'perfil', 'clientes', 'usuarios', 'relatorios', 'sistema'];
                    $pageName = $route['page'];
                    
                    if (!in_array($pageName, $allowedPages)) {
                        $pageFile = 'dashboard.php';
                    }
                    
                    // Verificar se o arquivo existe e está no diretório correto
                    if (!file_exists($pageFile) || !is_file($pageFile)) {
                        echo '<div class="main-content"><div class="text-center py-12"><h1 class="text-2xl font-bold text-gray-900 mb-4">Página não encontrada</h1><p class="text-gray-600">A página solicitada não existe.</p></div></div>';
                    } else {
                        // Definir uma variável global para indicar que está sendo incluído
                        $GLOBALS['isIncludedInIndex'] = true;
                        
                        // Capturar o output do arquivo PHP
                        ob_start();
                        try {
                            include $pageFile;
                        } catch (Exception $e) {
                            ob_end_clean();
                            echo '<div class="main-content"><div class="text-center py-12"><h1 class="text-2xl font-bold text-red-600 mb-4">Erro ao carregar página</h1><p class="text-gray-600">' . htmlspecialchars($e->getMessage()) . '</p></div></div>';
                            $pageContent = '';
                        }
                        $pageContent = ob_get_clean();
                        
                        // Extrair apenas o conteúdo dentro de <div class="main-content">
                        // Usar uma abordagem mais robusta para capturar o conteúdo completo
                        // Primeiro, tentar encontrar o primeiro <div class="main-content"> e capturar até o fechamento correspondente
                        // Usar uma função recursiva para contar as divs aninhadas
                        $extractedContent = '';
                        
                        // Tentar encontrar a primeira ocorrência de <div class="main-content">
                        $startPos = strpos($pageContent, '<div class="main-content"');
                        if ($startPos !== false) {
                            // Encontrar a posição de início do conteúdo (após a tag de abertura)
                            $contentStart = strpos($pageContent, '>', $startPos) + 1;
                            
                            // Contar divs aninhadas para encontrar o fechamento correto
                            $depth = 1;
                            $pos = $contentStart;
                            $contentLength = 0;
                            
                            while ($pos < strlen($pageContent) && $depth > 0) {
                                $nextOpen = strpos($pageContent, '<div', $pos);
                                $nextClose = strpos($pageContent, '</div>', $pos);
                                
                                if ($nextClose === false) {
                                    break;
                                }
                                
                                if ($nextOpen !== false && $nextOpen < $nextClose) {
                                    $depth++;
                                    $pos = $nextOpen + 4;
                                } else {
                                    $depth--;
                                    if ($depth > 0) {
                                        $pos = $nextClose + 6;
                                    } else {
                                        $contentLength = $nextClose - $contentStart;
                                    }
                                }
                            }
                            
                            if ($contentLength > 0) {
                                $extractedContent = substr($pageContent, $contentStart, $contentLength);
                            }
                        }
                        
                        // Se não conseguiu extrair, tentar regex simples
                        if (empty($extractedContent) || strlen(trim(strip_tags($extractedContent))) < 10) {
                            if (preg_match('/<div class="main-content"[^>]*>([\s\S]*?)<\/div>/', $pageContent, $matches)) {
                                $extractedContent = $matches[1];
                            } elseif (preg_match('/<div class="py-6">\s*<div class="main-content"[^>]*>([\s\S]*?)<\/div>\s*<\/div>/', $pageContent, $matches)) {
                                $extractedContent = $matches[1];
                            } elseif (preg_match('/<main[^>]*>([\s\S]*?)<\/main>/', $pageContent, $matches)) {
                                $mainContent = $matches[1];
                                $mainContent = preg_replace('/<div class="py-6">\s*/', '', $mainContent);
                                $mainContent = preg_replace('/\s*<\/div>\s*$/', '', $mainContent);
                                if (preg_match('/<div class="main-content"[^>]*>([\s\S]*?)<\/div>/', $mainContent, $contentMatch)) {
                                    $extractedContent = $contentMatch[1];
                                } else {
                                    $extractedContent = $mainContent;
                                }
                            } else {
                                // Se não encontrar, usar o conteúdo completo (mas remover tags HTML duplicadas)
                                $cleanContent = preg_replace('/<!DOCTYPE[^>]*>/i', '', $pageContent);
                                $cleanContent = preg_replace('/<html[^>]*>/i', '', $cleanContent);
                                $cleanContent = preg_replace('/<\/html>/i', '', $cleanContent);
                                $cleanContent = preg_replace('/<head[^>]*>[\s\S]*?<\/head>/i', '', $cleanContent);
                                $cleanContent = preg_replace('/<body[^>]*>/i', '', $cleanContent);
                                $cleanContent = preg_replace('/<\/body>/i', '', $cleanContent);
                                $extractedContent = $cleanContent;
                            }
                        }
                        
                        // Extrair modais que estão fora do main-content
                        $modals = '';
                        $modalIds = ['projetoModal', 'demandaModal', 'reuniaoModal', 'tarefaModal', 'noticiaModal', 'compromissoModal', 'clienteModal', 'usuarioModal', 'editUsuarioModal', 'tipoContratoModal', 'novoTipoContratoModal', 'photoModal'];
                        
                        foreach ($modalIds as $modalId) {
                            // Verificar se o modal não está no conteúdo já extraído
                            if (strpos($extractedContent, $modalId) !== false) {
                                continue; // Modal já está no conteúdo extraído
                            }
                            
                            // Procurar pelo ID do modal no conteúdo completo usando regex mais robusto
                            $modalPattern = '/<div[^>]*\sid=["\']' . preg_quote($modalId, '/') . '["\'][^>]*>[\s\S]*?<\/div>/';
                            
                            // Primeiro tentar com regex simples
                            if (preg_match($modalPattern, $pageContent, $match)) {
                                // Verificar se não está no conteúdo extraído
                                if (strpos($extractedContent, $modalId) === false) {
                                    // Encontrar a posição do match no conteúdo completo
                                    $matchPos = strpos($pageContent, $match[0]);
                                    if ($matchPos !== false) {
                                        // Encontrar o início real da tag <div
                                        $divStart = strrpos(substr($pageContent, 0, $matchPos), '<div');
                                        if ($divStart === false) {
                                            $divStart = $matchPos;
                                        }
                                        
                                        // Contar divs aninhadas para encontrar o fechamento completo
                                        $modalDepth = 1;
                                        $openTagEnd = strpos($pageContent, '>', $divStart);
                                        if ($openTagEnd === false) continue;
                                        
                                        $modalPos = $openTagEnd + 1;
                                        
                                        while ($modalPos < strlen($pageContent) && $modalDepth > 0) {
                                            $nextOpen = strpos($pageContent, '<div', $modalPos);
                                            $nextClose = strpos($pageContent, '</div>', $modalPos);
                                            
                                            if ($nextClose === false) break;
                                            
                                            if ($nextOpen !== false && $nextOpen < $nextClose) {
                                                $modalDepth++;
                                                $modalPos = $nextClose + 6;
                                            } else {
                                                $modalDepth--;
                                                if ($modalDepth > 0) {
                                                    $modalPos = $nextClose + 6;
                                                } else {
                                                    $modalEnd = $nextClose + 6;
                                                    $fullModal = substr($pageContent, $divStart, $modalEnd - $divStart);
                                                    
                                                    // Verificar se já não foi adicionado
                                                    if (strpos($modals, $modalId) === false) {
                                                        $modals .= $fullModal . "\n";
                                                    }
                                                    break;
                                                }
                                            }
                                        }
                                    }
                                }
                            } else {
                                // Método alternativo: procurar pelo ID diretamente
                                $idPattern1 = 'id="' . $modalId . '"';
                                $idPattern2 = "id='" . $modalId . "'";
                                
                                $idPos = strpos($pageContent, $idPattern1);
                                if ($idPos === false) {
                                    $idPos = strpos($pageContent, $idPattern2);
                                }
                                
                                if ($idPos !== false) {
                                    // Encontrar o início da tag <div
                                    $searchStart = max(0, $idPos - 200);
                                    $beforeId = substr($pageContent, $searchStart, $idPos - $searchStart);
                                    $divStart = strrpos($beforeId, '<div');
                                    
                                    if ($divStart !== false) {
                                        $divStart = $searchStart + $divStart;
                                        
                                        // Encontrar o fechamento completo
                                        $openTagEnd = strpos($pageContent, '>', $divStart);
                                        if ($openTagEnd === false) continue;
                                        
                                        $modalDepth = 1;
                                        $modalPos = $openTagEnd + 1;
                                        
                                        while ($modalPos < strlen($pageContent) && $modalDepth > 0) {
                                            $nextOpen = strpos($pageContent, '<div', $modalPos);
                                            $nextClose = strpos($pageContent, '</div>', $modalPos);
                                            
                                            if ($nextClose === false) break;
                                            
                                            if ($nextOpen !== false && $nextOpen < $nextClose) {
                                                $modalDepth++;
                                                $modalPos = $nextClose + 6;
                                            } else {
                                                $modalDepth--;
                                                if ($modalDepth > 0) {
                                                    $modalPos = $nextClose + 6;
                                                } else {
                                                    $modalEnd = $nextClose + 6;
                                                    $fullModal = substr($pageContent, $divStart, $modalEnd - $divStart);
                                                    
                                                    if (strpos($modals, $modalId) === false) {
                                                        $modals .= $fullModal . "\n";
                                                    }
                                                    break;
                                                }
                                            }
                                        }
                                    }
                                }
                            }
                        }
                        
                        // Exibir o conteúdo extraído
                        if (!empty($extractedContent)) {
                            echo '<div class="main-content">' . $extractedContent . '</div>';
                        } else {
                            // Fallback: exibir mensagem de erro
                            echo '<div class="main-content"><div class="text-center py-12"><h1 class="text-2xl font-bold text-red-600 mb-4">Erro ao carregar conteúdo</h1><p class="text-gray-600">Não foi possível extrair o conteúdo da página.</p><pre style="text-align: left; max-width: 800px; margin: 20px auto; padding: 20px; background: #f5f5f5; border-radius: 8px; overflow: auto;">' . htmlspecialchars(substr($pageContent, 0, 500)) . '...</pre></div></div>';
                        }
                        
                        // Incluir modais extraídos
                        if (!empty($modals)) {
                            echo $modals;
                        }
                        
                        // Extrair e incluir scripts JavaScript do arquivo
                        if (preg_match_all('/<script[^>]*>([\s\S]*?)<\/script>/', $pageContent, $scriptMatches)) {
                            foreach ($scriptMatches[0] as $script) {
                                // Não incluir scripts que já estão no index.php
                                if (strpos($script, 'toggleProfileMenu') === false && 
                                    strpos($script, 'logout') === false &&
                                    strpos($script, 'toggleNotificationsModal') === false &&
                                    strpos($script, 'closeNotificationsModal') === false &&
                                    strpos($script, 'loadNotifications') === false &&
                                    strpos($script, 'executeWhenReady') === false &&
                                    strpos($script, 'loadUserData') === false &&
                                    strpos($script, 'tryLoadUserData') === false) {
                                    
                                    // Extrair apenas o conteúdo do script (sem as tags)
                                    if (preg_match('/<script[^>]*>([\s\S]*?)<\/script>/', $script, $contentMatch)) {
                                        $scriptContent = $contentMatch[1];
                                        
                                        // Substituir DOMContentLoaded por executeWhenReady para garantir execução
                                        // Capturar todas as variações de DOMContentLoaded (com suporte a múltiplas linhas)
                                        $scriptContent = preg_replace_callback(
                                            '/document\.addEventListener\([\'"](DOMContentLoaded|load)[\'"],\s*function\s*\([^)]*\)\s*\{([\s\S]*?)\}\)\s*;/',
                                            function($matches) {
                                                return 'executeWhenReady(function() {' . $matches[2] . '});';
                                            },
                                            $scriptContent
                                        );
                                        
                                        // Envolver o script em uma tag script e executar após o DOM estar pronto
                                        // Usar setTimeout para garantir que o DOM está completamente renderizado
                                        echo '<script>';
                                        echo '(function() {';
                                        echo 'function runScript() {';
                                        echo $scriptContent;
                                        echo '}';
                                        echo 'if (typeof executeWhenReady === "function") {';
                                        echo 'executeWhenReady(function() {';
                                        echo 'setTimeout(runScript, 100);';
                                        echo '});';
                                        echo '} else {';
                                        echo 'if (document.readyState === "loading") {';
                                        echo 'document.addEventListener("DOMContentLoaded", function() {';
                                        echo 'setTimeout(runScript, 100);';
                                        echo '});';
                                        echo '} else {';
                                        echo 'setTimeout(runScript, 100);';
                                        echo '}';
                                        echo '}';
                                        echo '})();';
                                        echo '</script>';
                                    } else {
                                        // Se não conseguir extrair, usar o script original
                                        echo $script;
                                    }
                                }
                            }
                        }
                    }
                    ?>
                </div>
            </main>
        </div>
    </div>

    <!-- Modals globais (notificações, etc) -->
    <div id="notificationsModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
        <div class="relative top-20 mx-auto p-5 border w-11/12 md:w-1/3 shadow-lg rounded-md bg-white">
            <div class="mt-3">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-medium text-gray-900">Notificações</h3>
                    <button onclick="closeNotificationsModal()" class="text-gray-400 hover:text-gray-600">
                        <i class="fas fa-times text-xl"></i>
                    </button>
                </div>
                <div id="notificationsList" class="space-y-3 max-h-96 overflow-y-auto">
                    <!-- Notifications will be loaded here -->
                </div>
            </div>
        </div>
    </div>

    <!-- JavaScript global -->
    <script>
        // VERIFICAÇÃO DE AUTENTICAÇÃO OBRIGATÓRIA
        // Verificar se há token e usuário no localStorage ANTES de carregar qualquer conteúdo
        (function() {
            const token = localStorage.getItem('token');
            const user = localStorage.getItem('user');
            
            // Se não houver token OU usuário, redirecionar imediatamente para login
            if (!token || !user) {
                console.log('⚠️ Usuário não autenticado. Redirecionando para login...');
                // Limpar qualquer dado residual
                localStorage.clear();
                sessionStorage.clear();
                // Redirecionar para página de login
                window.location.replace('index-login.php');
                // Retornar para evitar que o resto do código execute
                return;
            }
            
            // Verificar se o token é válido (opcional - pode fazer uma requisição à API)
            // Por enquanto, apenas verificar se existe
            
            // Se chegou aqui, há token e usuário, pode continuar
            console.log('✅ Usuário autenticado. Carregando aplicação...');
        })();

        // Função para executar quando o DOM estiver pronto
        function executeWhenReady(fn) {
            if (document.readyState === 'loading') {
                document.addEventListener('DOMContentLoaded', fn);
            } else {
                fn();
            }
        }

        // Variável global para evitar múltiplas tentativas simultâneas
        let userDataLoaded = false;
        let userDataLoading = false;

        // Função para carregar e atualizar dados do usuário
        function loadUserData() {
            // Evitar múltiplas chamadas simultâneas
            if (userDataLoading || userDataLoaded) {
                return userDataLoaded;
            }
            
            userDataLoading = true;
            
            try {
                const token = localStorage.getItem('token');
                const userStr = localStorage.getItem('user');
                
                // Verificação obrigatória: se não houver token OU usuário, redirecionar
                if (!token || !userStr) {
                    console.warn('⚠️ Sessão expirada ou inválida. Redirecionando para login...');
                    localStorage.clear();
                    sessionStorage.clear();
                    window.location.replace('index-login.php');
                    userDataLoading = false;
                    return false;
                }
                
                const user = JSON.parse(userStr);
                
                if (!user || !user.nome) {
                    console.warn('⚠️ Dados do usuário inválidos. Redirecionando para login...');
                    localStorage.clear();
                    sessionStorage.clear();
                    window.location.replace('index-login.php');
                    userDataLoading = false;
                    return false;
                }
                
                const userNameEl = document.getElementById('userName');
                const userLevelEl = document.getElementById('userLevel');
                const userInitialsEl = document.getElementById('userInitials');
                const adminMenuSection = document.getElementById('adminMenuSection');
                
                // Verificar se todos os elementos necessários estão presentes
                if (!userNameEl || !userLevelEl || !userInitialsEl) {
                    userDataLoading = false;
                    return false;
                }
                
                // Atualizar nome do usuário
                userNameEl.textContent = user.nome;
                
                // Converter nível para título
                const nivelTitle = user.nivel === 'vereador' ? 'Vereador' : 
                                   user.nivel === 'assessor' ? 'Assessor' : 
                                   user.nivel === 'administrador' ? 'Administrador' : 
                                   user.nivel || 'Usuário';
                
                userLevelEl.textContent = nivelTitle;
                
                // Gerar iniciais
                const partesNome = user.nome.split(' ').filter(p => p.length > 0);
                let iniciais = '';
                if (partesNome.length >= 2) {
                    iniciais = partesNome[0].charAt(0).toUpperCase() + partesNome[partesNome.length - 1].charAt(0).toUpperCase();
                } else if (partesNome.length === 1) {
                    iniciais = partesNome[0].substring(0, 2).toUpperCase();
                } else {
                    iniciais = 'NU';
                }
                
                userInitialsEl.textContent = iniciais;
                
                // Mostrar menu de administração se o usuário for administrador
                if (adminMenuSection && user.nivel === 'administrador') {
                    adminMenuSection.classList.remove('hidden');
                }
                
                userDataLoaded = true;
                userDataLoading = false;
                console.log('✅ Dados do usuário carregados:', user.nome);
                return true;
            } catch (error) {
                console.error('❌ Erro ao carregar dados do usuário:', error);
                userDataLoading = false;
                return false;
            }
        }

        // Função para tentar carregar dados do usuário com retry (máximo 3 tentativas)
        function tryLoadUserData(retries = 3, delay = 300) {
            if (userDataLoaded) {
                return; // Já carregado, não tentar novamente
            }
            
            if (retries <= 0) {
                // Não logar erro se não houver usuário - isso é normal se não estiver logado
                const userStr = localStorage.getItem('user');
                if (!userStr) {
                    // Usuário não está logado, isso é normal
                    return;
                }
                console.error('❌ Falha ao carregar dados do usuário após múltiplas tentativas');
                return;
            }
            
            if (loadUserData()) {
                // Sucesso, não precisa tentar novamente
                return;
            } else {
                // Tentar novamente se ainda houver tentativas
                setTimeout(() => tryLoadUserData(retries - 1, delay), delay);
            }
        }

        // Carregar dados do usuário quando o DOM estiver pronto
        executeWhenReady(function() {
            tryLoadUserData();
        });
        
        // Também tentar carregar após um pequeno delay para garantir que os elementos estão no DOM
        setTimeout(() => tryLoadUserData(), 500);
        
        // Garantir que o menu de administração permaneça visível ao navegar
        executeWhenReady(function() {
            const checkAdminMenu = function() {
                const userStr = localStorage.getItem('user');
                if (userStr) {
                    try {
                        const user = JSON.parse(userStr);
                        const adminMenuSection = document.getElementById('adminMenuSection');
                        if (adminMenuSection && user.nivel === 'administrador') {
                            adminMenuSection.classList.remove('hidden');
                        }
                    } catch (e) {
                        // Ignorar erros
                    }
                }
            };
            
            // Verificar periodicamente se o menu precisa ser exibido
            setInterval(checkAdminMenu, 1000);
            
            // Verificar quando a página muda (para SPA)
            const observer = new MutationObserver(function(mutations) {
                checkAdminMenu();
            });
            
            const sidebar = document.querySelector('nav');
            if (sidebar) {
                observer.observe(sidebar, { childList: true, subtree: true });
            }
        });

        // Funções do menu de perfil
        function toggleProfileMenu() {
            const menu = document.getElementById('profileMenu');
            if (menu) {
                if (menu.style.display === 'none') {
                    menu.style.display = 'block';
                } else {
                    menu.style.display = 'none';
                }
            }
        }

        // Script unificado para gerenciar todos os modais
        function initModals() {
            const modais = [
                { botao: 'btnNovoProjeto', modal: 'projetoModal' },
                { botao: 'btnNovoProjeto2', modal: 'projetoModal' },
                { botao: 'btnNovaDemanda', modal: 'demandaModal' },
                { botao: 'btnNovaReuniao', modal: 'reuniaoModal' },
                { botao: 'btnNovaReuniao2', modal: 'reuniaoModal' },
                { botao: 'btnNovaTarefa', modal: 'tarefaModal' },
                { botao: 'btnNovaTarefa2', modal: 'tarefaModal' },
                { botao: 'btnNovaNoticia', modal: 'noticiaModal' },
                { botao: 'btnNovaNoticia2', modal: 'noticiaModal' },
                { botao: 'btnNovoCliente', modal: 'clienteModal' },
                { botao: 'btnNovoUsuario', modal: 'usuarioModal' },
            ];

            modais.forEach(({ botao, modal }) => {
                const btn = document.getElementById(botao);
                const mdl = document.getElementById(modal);

                if (!btn || !mdl) {
                    // Não logar warning se o botão/modal não existir (pode não estar na página atual)
                    return;
                }

                // Adicionar event listener ao botão
                btn.addEventListener('click', function(e) {
                    e.preventDefault();
                    e.stopPropagation();
                    
                    // Remover classe hidden e garantir display
                    mdl.classList.remove('hidden');
                    mdl.style.setProperty('display', 'flex', 'important');
                    mdl.style.setProperty('align-items', 'center', 'important');
                    mdl.style.setProperty('justify-content', 'center', 'important');
                    mdl.style.setProperty('z-index', '9999', 'important');
                    
                    console.log(`✅ Modal ${modal} aberto via botão ${botao}`);
                });

                // Adicionar event listeners aos botões de fechar
                const closeButtons = mdl.querySelectorAll('.close, [onclick*="close"], button[type="button"]');
                closeButtons.forEach(closeBtn => {
                    // Verificar se é um botão de fechar (pode ter onclick ou ser um botão de cancelar)
                    if (closeBtn.onclick || closeBtn.textContent.includes('Cancelar') || closeBtn.classList.contains('close') || closeBtn.querySelector('.fa-times')) {
                        closeBtn.addEventListener('click', function(e) {
                            e.preventDefault();
                            e.stopPropagation();
                            
                            mdl.classList.add('hidden');
                            mdl.style.display = 'none';
                            
                            console.log(`✅ Modal ${modal} fechado`);
                        });
                    }
                });

                // Fechar modal ao clicar fora dele
                mdl.addEventListener('click', function(e) {
                    if (e.target === mdl) {
                        mdl.classList.add('hidden');
                        mdl.style.display = 'none';
                        console.log(`✅ Modal ${modal} fechado (clique fora)`);
                    }
                });
            });
        }

        // Inicializar modais quando o DOM estiver pronto
        function executeWhenReady(callback) {
            if (typeof document !== 'undefined') {
                if (document.readyState === 'loading') {
                    document.addEventListener('DOMContentLoaded', callback);
                } else {
                    callback();
                }
            }
        }

        // Inicializar modais após o conteúdo ser carregado
        executeWhenReady(function() {
            // Aguardar um pouco para garantir que os modais foram extraídos e incluídos
            setTimeout(function() {
                initModals();
            }, 100);
        });

        // Re-inicializar modais quando o conteúdo da página mudar (SPA)
        const mainContent = document.getElementById('mainContent');
        if (mainContent) {
            const observer = new MutationObserver(function(mutations) {
                mutations.forEach(function(mutation) {
                    if (mutation.addedNodes.length > 0) {
                        // Aguardar um pouco para garantir que os novos modais foram renderizados
                        setTimeout(function() {
                            initModals();
                        }, 200);
                    }
                });
            });
            
            observer.observe(mainContent, { childList: true, subtree: true });
        }

        function logout() {
            // Limpar todos os dados de autenticação
            localStorage.clear();
            sessionStorage.clear();
            // Redirecionar para login
            window.location.replace('index-login.php');
        }
        
        // Verificar autenticação periodicamente (a cada 30 segundos)
        setInterval(function() {
            const token = localStorage.getItem('token');
            const user = localStorage.getItem('user');
            
            if (!token || !user) {
                console.warn('⚠️ Sessão expirada. Redirecionando para login...');
                localStorage.clear();
                sessionStorage.clear();
                window.location.replace('index-login.php');
            }
        }, 30000); // Verificar a cada 30 segundos

        // Fechar menu de perfil ao clicar fora (evitar múltiplos listeners)
        if (!window.profileMenuListenerAdded) {
            window.profileMenuListenerAdded = true;
            document.addEventListener('click', function(e) {
                try {
                    const profileMenu = document.getElementById('profileMenu');
                    const profileButton = e.target.closest('[onclick="toggleProfileMenu()"]');
                    
                    if (profileMenu && !profileButton && !profileMenu.contains(e.target)) {
                        profileMenu.style.display = 'none';
                    }
                } catch (error) {
                    // Ignorar erros silenciosamente
                }
            });
        }

        // Funções do modal de notificações
        function toggleNotificationsModal() {
            try {
                const modal = document.getElementById('notificationsModal');
                if (modal) {
                    modal.classList.toggle('hidden');
                    if (!modal.classList.contains('hidden')) {
                        loadNotifications();
                    }
                }
            } catch (error) {
                console.error('Erro ao abrir modal de notificações:', error);
            }
        }

        function closeNotificationsModal() {
            try {
                const modal = document.getElementById('notificationsModal');
                if (modal) {
                    modal.classList.add('hidden');
                }
            } catch (error) {
                console.error('Erro ao fechar modal de notificações:', error);
            }
        }

        // Suprimir erros de extensões do navegador (runtime.lastError)
        const originalError = window.onerror;
        window.onerror = function(message, source, lineno, colno, error) {
            // Suprimir erros de extensões do navegador
            if (message && (
                message.includes('runtime.lastError') ||
                message.includes('message port closed') ||
                message.includes('Extension context invalidated')
            )) {
                return true; // Suprimir o erro
            }
            // Para outros erros, usar o handler original se existir
            if (originalError) {
                return originalError(message, source, lineno, colno, error);
            }
            return false;
        };

        // Suprimir erros de unhandled promise rejections relacionados a extensões
        window.addEventListener('unhandledrejection', function(e) {
            if (e.reason && (
                e.reason.message && (
                    e.reason.message.includes('runtime.lastError') ||
                    e.reason.message.includes('message port closed') ||
                    e.reason.message.includes('Extension context invalidated')
                )
            )) {
                e.preventDefault();
                return false;
            }
        });

        // Suprimir erros de message port do Chrome
        if (typeof chrome !== 'undefined' && chrome.runtime && chrome.runtime.onMessage) {
            try {
                chrome.runtime.onMessage.addListener(function(request, sender, sendResponse) {
                    // Sempre responder para evitar erros de message port
                    try {
                        sendResponse({success: true});
                    } catch (e) {
                        // Ignorar erros silenciosamente
                    }
                    return true; // Indica que vamos responder assincronamente
                });
            } catch (e) {
                // Ignorar erros silenciosamente
            }
        }
    </script>
</body>
</html>