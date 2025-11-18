<?php
/**
 * Router para gerenciar as rotas do sistema
 */

// Mapeamento de páginas permitidas
// Usar os arquivos PHP da pasta pages/ que têm apenas o conteúdo principal
$allowedPages = [
    'dashboard' => 'pages/dashboard.php',
    'projetos' => 'pages/projetos.php',
    'demandas' => 'pages/demandas.php',
    'reunioes' => 'pages/reunioes.php',
    'tarefas' => 'pages/tarefas.php',
    'noticias' => 'pages/noticias.php',
    'indicadores' => 'pages/indicadores.php',
    'chat' => 'pages/chat.php',
    'perfil' => 'pages/perfil.php',
    'notificacoes' => 'pages/notificacoes.php',
    'agenda' => 'pages/agenda.php',
    'clientes' => 'pages/clientes.php',
    'usuarios' => 'pages/usuarios.php',
    'relatorios' => 'pages/relatorios.php',
    'sistema' => 'pages/sistema.php',
];

// Verificar se o arquivo existe, caso contrário usar dashboard
foreach ($allowedPages as $key => $file) {
    if (!file_exists($file)) {
        $allowedPages[$key] = 'pages/dashboard.php';
    }
}

// Obter página solicitada
// Primeiro tenta pegar do parâmetro GET (vindo do .htaccess)
if (isset($_GET['page']) && !empty($_GET['page'])) {
    $page = $_GET['page'];
} else {
    // Se não houver parâmetro, verificar URL limpa
    $requestUri = $_SERVER['REQUEST_URI'];
    $requestPath = parse_url($requestUri, PHP_URL_PATH);
    
    // Remover a barra inicial e qualquer diretório base
    $requestPath = ltrim($requestPath, '/');
    $pathParts = explode('/', $requestPath);
    
    // Remover 'public' ou 'index.php' se estiver no caminho
    $pathParts = array_filter($pathParts, function($part) {
        return $part !== 'index.php' && $part !== 'public' && !empty($part);
    });
    
    if (!empty($pathParts)) {
        $page = reset($pathParts);
    } else {
        // Página padrão
        $page = 'dashboard';
    }
}

// Validar se a página existe
if (!isset($allowedPages[$page])) {
    $page = 'dashboard';
}

// Definir título da página
$pageTitles = [
    'dashboard' => 'Dashboard',
    'projetos' => 'Gestão de Projetos',
    'demandas' => 'Gestão de Demandas',
    'reunioes' => 'Reuniões',
    'tarefas' => 'Tarefas',
    'noticias' => 'Notícias',
    'indicadores' => 'Indicadores',
    'chat' => 'Chat',
    'perfil' => 'Meu Perfil',
    'notificacoes' => 'Notificações',
    'agenda' => 'Agenda',
    'clientes' => 'Clientes',
    'usuarios' => 'Usuários',
    'relatorios' => 'Relatórios',
    'sistema' => 'Sistema',
];

$pageTitle = isset($pageTitles[$page]) ? $pageTitles[$page] : 'UniAssessor';

// Caminho do arquivo da página
$pageFile = $allowedPages[$page];

// Retornar informações da rota
return [
    'page' => $page,
    'title' => $pageTitle,
    'file' => $pageFile,
];