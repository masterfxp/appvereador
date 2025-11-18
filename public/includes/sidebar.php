<?php
// Determinar página ativa
$requestUri = $_SERVER['REQUEST_URI'];
$requestPath = parse_url($requestUri, PHP_URL_PATH);
$requestPath = ltrim($requestPath, '/');
$pathParts = explode('/', $requestPath);

if (basename($_SERVER['SCRIPT_NAME']) === 'index.php' && isset($_GET['page'])) {
    $currentPage = $_GET['page'];
} elseif (!empty($pathParts[0]) && $pathParts[0] !== 'index.php' && $pathParts[0] !== 'public') {
    $currentPage = $pathParts[0];
} else {
    $currentPage = 'dashboard';
}
$isAdmin = isset($_SESSION['user']) && isset($_SESSION['user']['nivel']) && $_SESSION['user']['nivel'] === 'administrador';
?>
<!-- Sidebar -->
<div class="hidden md:flex md:w-64 md:flex-col">
    <div class="flex flex-col flex-grow pt-5 bg-white overflow-y-auto border-r border-gray-200">
        <div class="flex items-center flex-shrink-0 px-4">
            <i class="fas fa-landmark text-2xl text-blue-600 mr-2"></i>
            <span class="text-xl font-bold text-gray-900">UniAssessor</span>
        </div>
        <div class="mt-5 flex-grow flex flex-col">
            <nav class="flex-1 px-2 space-y-1">
                <a href="index.php?page=dashboard" class="group flex items-center px-2 py-2 text-sm font-medium rounded-md <?php echo $currentPage === 'dashboard' ? 'text-white bg-blue-600' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900'; ?>">
                    <i class="fas fa-tachometer-alt mr-3 <?php echo $currentPage === 'dashboard' ? '' : 'text-gray-400 group-hover:text-gray-500'; ?>"></i>
                    Dashboard
                </a>
                <a href="index.php?page=projetos" class="group flex items-center px-2 py-2 text-sm font-medium rounded-md <?php echo $currentPage === 'projetos' ? 'text-white bg-blue-600' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900'; ?>">
                    <i class="fas fa-file-alt mr-3 <?php echo $currentPage === 'projetos' ? '' : 'text-gray-400 group-hover:text-gray-500'; ?>"></i>
                    Projetos
                </a>
                <a href="index.php?page=demandas" class="group flex items-center px-2 py-2 text-sm font-medium rounded-md <?php echo $currentPage === 'demandas' ? 'text-white bg-blue-600' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900'; ?>">
                    <i class="fas fa-comments mr-3 <?php echo $currentPage === 'demandas' ? '' : 'text-gray-400 group-hover:text-gray-500'; ?>"></i>
                    Demandas
                </a>
                <a href="index.php?page=reunioes" class="group flex items-center px-2 py-2 text-sm font-medium rounded-md <?php echo $currentPage === 'reunioes' ? 'text-white bg-blue-600' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900'; ?>">
                    <i class="fas fa-calendar-alt mr-3 <?php echo $currentPage === 'reunioes' ? '' : 'text-gray-400 group-hover:text-gray-500'; ?>"></i>
                    Reuniões
                </a>
                <a href="index.php?page=tarefas" class="group flex items-center px-2 py-2 text-sm font-medium rounded-md <?php echo $currentPage === 'tarefas' ? 'text-white bg-blue-600' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900'; ?>">
                    <i class="fas fa-tasks mr-3 <?php echo $currentPage === 'tarefas' ? '' : 'text-gray-400 group-hover:text-gray-500'; ?>"></i>
                    Tarefas
                </a>
                <a href="index.php?page=noticias" class="group flex items-center px-2 py-2 text-sm font-medium rounded-md <?php echo $currentPage === 'noticias' ? 'text-white bg-blue-600' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900'; ?>">
                    <i class="fas fa-newspaper mr-3 <?php echo $currentPage === 'noticias' ? '' : 'text-gray-400 group-hover:text-gray-500'; ?>"></i>
                    Notícias
                </a>
                <a href="index.php?page=indicadores" class="group flex items-center px-2 py-2 text-sm font-medium rounded-md <?php echo $currentPage === 'indicadores' ? 'text-white bg-blue-600' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900'; ?>">
                    <i class="fas fa-chart-bar mr-3 <?php echo $currentPage === 'indicadores' ? '' : 'text-gray-400 group-hover:text-gray-500'; ?>"></i>
                    Indicadores
                </a>
                <a href="index.php?page=chat" class="group flex items-center px-2 py-2 text-sm font-medium rounded-md <?php echo $currentPage === 'chat' ? 'text-white bg-blue-600' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900'; ?>">
                    <i class="fas fa-comment-dots mr-3 <?php echo $currentPage === 'chat' ? '' : 'text-gray-400 group-hover:text-gray-500'; ?>"></i>
                    Chat
                </a>
                <a href="index.php?page=perfil" class="group flex items-center px-2 py-2 text-sm font-medium rounded-md <?php echo $currentPage === 'perfil' ? 'text-white bg-blue-600' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900'; ?>">
                    <i class="fas fa-user mr-3 <?php echo $currentPage === 'perfil' ? '' : 'text-gray-400 group-hover:text-gray-500'; ?>"></i>
                    Perfil
                </a>
                <a href="index.php?page=agenda" class="group flex items-center px-2 py-2 text-sm font-medium rounded-md <?php echo $currentPage === 'agenda' ? 'text-white bg-blue-600' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900'; ?>">
                    <i class="fas fa-calendar-check mr-3 <?php echo $currentPage === 'agenda' ? '' : 'text-gray-400 group-hover:text-gray-500'; ?>"></i>
                    Agenda
                </a>
                <!-- Menu de Administração (será mostrado via JavaScript se o usuário for admin) -->
                <div id="adminMenuSection" class="hidden">
                    <div class="border-t border-gray-200 my-4"></div>
                    <div class="px-2">
                        <h3 class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Administração</h3>
                    </div>
                    <a href="index.php?page=clientes" class="group flex items-center px-2 py-2 text-sm font-medium rounded-md <?php echo $currentPage === 'clientes' ? 'text-white bg-blue-600' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900'; ?>">
                        <i class="fas fa-building mr-3 <?php echo $currentPage === 'clientes' ? '' : 'text-gray-400 group-hover:text-gray-500'; ?>"></i>
                        Clientes
                    </a>
                    <a href="index.php?page=usuarios" class="group flex items-center px-2 py-2 text-sm font-medium rounded-md <?php echo $currentPage === 'usuarios' ? 'text-white bg-blue-600' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900'; ?>">
                        <i class="fas fa-users mr-3 <?php echo $currentPage === 'usuarios' ? '' : 'text-gray-400 group-hover:text-gray-500'; ?>"></i>
                        Usuários
                    </a>
                    <a href="index.php?page=relatorios" class="group flex items-center px-2 py-2 text-sm font-medium rounded-md <?php echo $currentPage === 'relatorios' ? 'text-white bg-blue-600' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900'; ?>">
                        <i class="fas fa-chart-line mr-3 <?php echo $currentPage === 'relatorios' ? '' : 'text-gray-400 group-hover:text-gray-500'; ?>"></i>
                        Relatórios
                    </a>
                    <a href="index.php?page=sistema" class="group flex items-center px-2 py-2 text-sm font-medium rounded-md <?php echo $currentPage === 'sistema' ? 'text-white bg-blue-600' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900'; ?>">
                        <i class="fas fa-server mr-3 <?php echo $currentPage === 'sistema' ? '' : 'text-gray-400 group-hover:text-gray-500'; ?>"></i>
                        Sistema
                    </a>
                </div>
            </nav>
        </div>
    </div>
</div>

