<?php
// Carregar dados do usuário da sessão ou localStorage (via JavaScript)
$userName = isset($_SESSION['user']['nome']) ? $_SESSION['user']['nome'] : 'Nome do Usuário';
$userLevel = isset($_SESSION['user']['nivel']) ? $_SESSION['user']['nivel'] : 'Função';
$userEmail = isset($_SESSION['user']['email']) ? $_SESSION['user']['email'] : '';

// Converter nível para título
$nivelTitle = '';
if ($userLevel === 'vereador') {
    $nivelTitle = 'Vereador';
} elseif ($userLevel === 'assessor') {
    $nivelTitle = 'Assessor';
} elseif ($userLevel === 'administrador') {
    $nivelTitle = 'Administrador';
} else {
    $nivelTitle = $userLevel;
}

// Gerar iniciais
$partesNome = explode(' ', $userName);
$iniciais = '';
if (count($partesNome) >= 2) {
    $iniciais = strtoupper(substr($partesNome[0], 0, 1) . substr($partesNome[count($partesNome) - 1], 0, 1));
} else {
    $iniciais = strtoupper(substr($userName, 0, 2));
}
?>
<!-- Header -->
<div class="relative z-10 flex-shrink-0 flex h-16 bg-white shadow">
    <div class="flex-1 px-4 flex justify-between">
        <div class="flex-1 flex">
            <div class="w-full flex md:ml-0">
                <div class="relative w-full text-gray-400 focus-within:text-gray-600">
                    <div class="absolute inset-y-0 left-0 flex items-center pointer-events-none">
                        <i class="fas fa-search"></i>
                    </div>
                    <input id="searchInput" class="block w-full h-full pl-8 pr-3 py-2 border-transparent text-gray-900 placeholder-gray-500 focus:outline-none focus:placeholder-gray-400 focus:ring-0 focus:border-transparent" placeholder="Buscar..." type="search">
                </div>
            </div>
        </div>
        <div class="ml-4 flex items-center md:ml-6">
            <!-- Notifications -->
            <button onclick="toggleNotificationsModal()" class="bg-white p-1 rounded-full text-gray-400 hover:text-gray-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 relative">
                <i class="fas fa-bell text-xl"></i>
                <span class="absolute -mt-1 -mr-1 h-5 w-5 rounded-full bg-red-500 text-white text-xs flex items-center justify-center" id="notificationCount">0</span>
            </button>

            <!-- Profile dropdown -->
            <div class="ml-3 relative">
                <button onclick="toggleProfileMenu()" class="flex items-center space-x-3 hover:bg-gray-100 rounded-lg px-3 py-2 transition-colors">
                    <div class="text-right">
                        <p class="text-sm font-semibold text-gray-900" id="userName"><?php echo htmlspecialchars($userName); ?></p>
                        <p class="text-xs text-gray-500" id="userLevel"><?php echo htmlspecialchars($nivelTitle); ?></p>
                    </div>
                    <div class="h-10 w-10 rounded-full bg-gradient-to-br from-purple-500 to-purple-700 flex items-center justify-center text-white font-bold text-lg" id="userInitials"><?php echo htmlspecialchars($iniciais); ?></div>
                    <i class="fas fa-chevron-down text-gray-500 text-xs"></i>
                </button>
                <div id="profileMenu" style="position: absolute; top: 100%; right: 0; margin-top: 8px; width: 220px; background: white; border-radius: 8px; box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05); padding: 6px 0; z-index: 9999; display: none;">
                    <a href="#" class="flex items-center px-4 py-3 hover:bg-gray-50">
                        <i class="fas fa-moon" style="margin-right: 12px; color: #6b7280;"></i>
                        <span style="color: #374151; font-size: 14px;">Tema Escuro</span>
                        <div style="margin-left: auto; width: 40px; height: 20px; background: #d1d5db; border-radius: 10px; position: relative;">
                            <div style="position: absolute; top: 2px; left: 2px; width: 16px; height: 16px; background: white; border-radius: 50%; transition: all 0.3s;"></div>
                        </div>
                    </a>
                    <a href="/perfil" style="display: block; padding: 12px 16px; font-size: 14px; color: #374151; text-decoration: none;" onmouseover="this.style.backgroundColor='#f9fafb'" onmouseout="this.style.backgroundColor='white'">
                        <i class="fas fa-user" style="margin-right: 12px;"></i>
                        Meu Perfil
                    </a>
                    <div style="border-top: 1px solid #f3f4f6; margin: 4px 0;"></div>
                    <a href="#" onclick="logout()" style="display: block; padding: 12px 16px; font-size: 14px; color: #374151; text-decoration: none;" onmouseover="this.style.backgroundColor='#f9fafb'" onmouseout="this.style.backgroundColor='white'">
                        <i class="fas fa-sign-out-alt" style="margin-right: 12px;"></i>
                        Sair
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

