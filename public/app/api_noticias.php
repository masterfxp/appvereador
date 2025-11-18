<?php
require 'conexao.php';
require 'auth_functions.php';
require 'api_config.php';

secure_session_start();
require_auth('index-login.php');
setApiHeaders();

$csrf_token = generate_csrf_token();
$method = $_SERVER['REQUEST_METHOD'];
$action = $_GET['action'] ?? '';

try {
    switch ($method) {
        case 'GET': handleGet($action); break;
        case 'POST': handlePost($action); break;
        case 'PUT': handlePut($action); break;
        case 'DELETE': handleDelete($action); break;
        default:
            http_response_code(405);
            echo json_encode(['error' => 'Método não permitido']);
    }
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Erro interno: ' . $e->getMessage()]);
}

function handleGet($action) {
    global $pdo;
    $cliente_id = getCurrentClienteId();
    
    switch ($action) {
        case 'list':
            $status = sanitize_input($_GET['status'] ?? '');
            $categoria = sanitize_input($_GET['categoria'] ?? '');
            $publico = isset($_GET['publico']) ? intval($_GET['publico']) : null;
            
            $sql = "
                SELECT n.*, u.nome as autor_nome
                FROM noticias n
                LEFT JOIN usuarios u ON n.autor_id = u.id
                WHERE n.cliente_id = :cliente_id
            ";
            $params = [':cliente_id' => $cliente_id];
            
            if (!empty($status)) {
                $sql .= " AND n.status = :status";
                $params[':status'] = $status;
            }
            if (!empty($categoria)) {
                $sql .= " AND n.categoria = :categoria";
                $params[':categoria'] = $categoria;
            }
            if ($publico !== null) {
                $sql .= " AND n.publico = :publico";
                $params[':publico'] = $publico;
            }
            
            $sql .= " ORDER BY n.created_at DESC";
            
            $stmt = $pdo->prepare($sql);
            $stmt->execute($params);
            $noticias = $stmt->fetchAll();
            
            foreach ($noticias as &$noticia) {
                if (!empty($noticia['galeria'])) {
                    $noticia['galeria'] = json_decode($noticia['galeria'], true) ?: [];
                }
                if (!empty($noticia['tags'])) {
                    $noticia['tags'] = json_decode($noticia['tags'], true) ?: [];
                }
                if (!empty($noticia['anexos'])) {
                    $noticia['anexos'] = json_decode($noticia['anexos'], true) ?: [];
                }
                if (!empty($noticia['redes_sociais'])) {
                    $noticia['redes_sociais'] = json_decode($noticia['redes_sociais'], true) ?: [];
                }
            }
            
            sendSuccessResponse($noticias);
            break;
            
        case 'get':
            $id = intval($_GET['id'] ?? 0);
            if (!$id) {
                sendErrorResponse('ID não fornecido', 400);
            }
            
            $stmt = $pdo->prepare("
                SELECT n.*, u.nome as autor_nome
                FROM noticias n
                LEFT JOIN usuarios u ON n.autor_id = u.id
                WHERE n.id = :id AND n.cliente_id = :cliente_id
            ");
            $stmt->execute([
                ':id' => $id,
                ':cliente_id' => $cliente_id
            ]);
            $noticia = $stmt->fetch();
            
            if (!$noticia) {
                sendErrorResponse('Notícia não encontrada', 404);
            }
            
            // Incrementar visualizações
            $stmt = $pdo->prepare("UPDATE noticias SET visualizacoes = visualizacoes + 1 WHERE id = :id");
            $stmt->execute([':id' => $id]);
            $noticia['visualizacoes'] = intval($noticia['visualizacoes']) + 1;
            
            if (!empty($noticia['galeria'])) {
                $noticia['galeria'] = json_decode($noticia['galeria'], true) ?: [];
            }
            if (!empty($noticia['tags'])) {
                $noticia['tags'] = json_decode($noticia['tags'], true) ?: [];
            }
            if (!empty($noticia['anexos'])) {
                $noticia['anexos'] = json_decode($noticia['anexos'], true) ?: [];
            }
            if (!empty($noticia['redes_sociais'])) {
                $noticia['redes_sociais'] = json_decode($noticia['redes_sociais'], true) ?: [];
            }
            
            sendSuccessResponse($noticia);
            break;
            
        case 'search':
            $termo = sanitize_input($_GET['q'] ?? '');
            if (empty($termo)) {
                sendErrorResponse('Termo de busca não fornecido', 400);
            }
            
            $stmt = $pdo->prepare("
                SELECT n.*, u.nome as autor_nome
                FROM noticias n
                LEFT JOIN usuarios u ON n.autor_id = u.id
                WHERE n.cliente_id = :cliente_id 
                AND (n.titulo LIKE :termo OR n.resumo LIKE :termo OR n.conteudo LIKE :termo)
                ORDER BY n.created_at DESC
                LIMIT 50
            ");
            $termoLike = "%{$termo}%";
            $stmt->execute([
                ':cliente_id' => $cliente_id,
                ':termo' => $termoLike
            ]);
            $noticias = $stmt->fetchAll();
            
            sendSuccessResponse($noticias);
            break;
            
        default:
            sendErrorResponse('Ação não reconhecida', 400);
    }
}

function handlePost($action) {
    global $pdo;
    
    if ($action !== 'create') {
        sendErrorResponse('Ação não reconhecida', 400);
    }
    
    $data = getJsonInput();
    
    if (!validate_csrf_token($data['csrf_token'] ?? '')) {
        sendErrorResponse('Token CSRF inválido', 403);
    }
    
    $titulo = sanitize_input($data['titulo'] ?? '');
    $conteudo = sanitize_input($data['conteudo'] ?? '');
    $resumo = sanitize_input($data['resumo'] ?? '');
    $imagem = sanitize_input($data['imagem'] ?? '');
    $categoria = sanitize_input($data['categoria'] ?? '');
    $status = sanitize_input($data['status'] ?? 'rascunho');
    $publico = isset($data['publico']) ? intval($data['publico']) : 1;
    $autor_id = getCurrentUserId();
    $gabinete_id = getCurrentGabineteId();
    $cliente_id = getCurrentClienteId();
    $fonte = sanitize_input($data['fonte'] ?? '');
    $link_externo = sanitize_input($data['link_externo'] ?? '');
    $galeria = isset($data['galeria']) && is_array($data['galeria']) ? $data['galeria'] : [];
    $tags = isset($data['tags']) && is_array($data['tags']) ? $data['tags'] : [];
    $anexos = isset($data['anexos']) && is_array($data['anexos']) ? $data['anexos'] : [];
    $redes_sociais = isset($data['redes_sociais']) && is_array($data['redes_sociais']) ? $data['redes_sociais'] : [];
    $seo_title = sanitize_input($data['seo_title'] ?? '');
    $seo_description = sanitize_input($data['seo_description'] ?? '');
    $seo_keywords = sanitize_input($data['seo_keywords'] ?? '');
    
    if (empty($titulo) || empty($conteudo)) {
        sendErrorResponse('Título e conteúdo são obrigatórios', 400);
    }
    
    if (!in_array($status, ['rascunho', 'publicado', 'arquivado'])) {
        sendErrorResponse('Status inválido', 400);
    }
    
    $data_publicacao = null;
    if ($status === 'publicado') {
        $data_publicacao = date('Y-m-d H:i:s');
    }
    
    $stmt = $pdo->prepare("
        INSERT INTO noticias (titulo, conteudo, resumo, imagem, categoria, status, publico, 
                             autor_id, gabinete_id, cliente_id, fonte, link_externo, galeria, 
                             tags, anexos, redes_sociais, seo_title, seo_description, seo_keywords, 
                             data_publicacao, created_at, updated_at)
        VALUES (:titulo, :conteudo, :resumo, :imagem, :categoria, :status, :publico, 
                :autor_id, :gabinete_id, :cliente_id, :fonte, :link_externo, :galeria, 
                :tags, :anexos, :redes_sociais, :seo_title, :seo_description, :seo_keywords, 
                :data_publicacao, NOW(), NOW())
    ");
    
    $stmt->execute([
        ':titulo' => $titulo,
        ':conteudo' => $conteudo,
        ':resumo' => $resumo,
        ':imagem' => $imagem,
        ':categoria' => $categoria,
        ':status' => $status,
        ':publico' => $publico,
        ':autor_id' => $autor_id,
        ':gabinete_id' => $gabinete_id,
        ':cliente_id' => $cliente_id,
        ':fonte' => $fonte,
        ':link_externo' => $link_externo,
        ':galeria' => json_encode($galeria),
        ':tags' => json_encode($tags),
        ':anexos' => json_encode($anexos),
        ':redes_sociais' => json_encode($redes_sociais),
        ':seo_title' => $seo_title,
        ':seo_description' => $seo_description,
        ':seo_keywords' => $seo_keywords,
        ':data_publicacao' => $data_publicacao
    ]);
    
    $id = $pdo->lastInsertId();
    
    logAction($pdo, getCurrentUserId(), 'create', 'noticia', [
        'noticia_id' => $id,
        'titulo' => $titulo
    ]);
    
    sendSuccessResponse(['id' => $id], 'Notícia criada com sucesso');
}

function handlePut($action) {
    global $pdo;
    
    if ($action !== 'update') {
        sendErrorResponse('Ação não reconhecida', 400);
    }
    
    $data = getJsonInput();
    
    if (!validate_csrf_token($data['csrf_token'] ?? '')) {
        sendErrorResponse('Token CSRF inválido', 403);
    }
    
    $id = intval($data['id'] ?? 0);
    if (!$id) {
        sendErrorResponse('ID não fornecido', 400);
    }
    
    $cliente_id = getCurrentClienteId();
    
    $stmt = $pdo->prepare("SELECT id FROM noticias WHERE id = :id AND cliente_id = :cliente_id");
    $stmt->execute([':id' => $id, ':cliente_id' => $cliente_id]);
    if (!$stmt->fetch()) {
        sendErrorResponse('Notícia não encontrada', 404);
    }
    
    $updates = [];
    $params = [':id' => $id, ':cliente_id' => $cliente_id];
    
    if (isset($data['titulo'])) {
        $updates[] = "titulo = :titulo";
        $params[':titulo'] = sanitize_input($data['titulo']);
    }
    if (isset($data['conteudo'])) {
        $updates[] = "conteudo = :conteudo";
        $params[':conteudo'] = sanitize_input($data['conteudo']);
    }
    if (isset($data['resumo'])) {
        $updates[] = "resumo = :resumo";
        $params[':resumo'] = sanitize_input($data['resumo']);
    }
    if (isset($data['imagem'])) {
        $updates[] = "imagem = :imagem";
        $params[':imagem'] = sanitize_input($data['imagem']);
    }
    if (isset($data['categoria'])) {
        $updates[] = "categoria = :categoria";
        $params[':categoria'] = sanitize_input($data['categoria']);
    }
    if (isset($data['status']) && in_array($data['status'], ['rascunho', 'publicado', 'arquivado'])) {
        $updates[] = "status = :status";
        $params[':status'] = sanitize_input($data['status']);
        if ($data['status'] === 'publicado') {
            $updates[] = "data_publicacao = NOW()";
        }
    }
    if (isset($data['publico'])) {
        $updates[] = "publico = :publico";
        $params[':publico'] = intval($data['publico']);
    }
    if (isset($data['galeria']) && is_array($data['galeria'])) {
        $updates[] = "galeria = :galeria";
        $params[':galeria'] = json_encode($data['galeria']);
    }
    if (isset($data['tags']) && is_array($data['tags'])) {
        $updates[] = "tags = :tags";
        $params[':tags'] = json_encode($data['tags']);
    }
    if (isset($data['anexos']) && is_array($data['anexos'])) {
        $updates[] = "anexos = :anexos";
        $params[':anexos'] = json_encode($data['anexos']);
    }
    if (isset($data['redes_sociais']) && is_array($data['redes_sociais'])) {
        $updates[] = "redes_sociais = :redes_sociais";
        $params[':redes_sociais'] = json_encode($data['redes_sociais']);
    }
    
    if (empty($updates)) {
        sendErrorResponse('Nenhum campo para atualizar', 400);
    }
    
    $updates[] = "updated_at = NOW()";
    $sql = "UPDATE noticias SET " . implode(', ', $updates) . " WHERE id = :id AND cliente_id = :cliente_id";
    
    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    
    logAction($pdo, getCurrentUserId(), 'update', 'noticia', ['noticia_id' => $id]);
    
    sendSuccessResponse(null, 'Notícia atualizada com sucesso');
}

function handleDelete($action) {
    global $pdo;
    
    if ($action !== 'delete') {
        sendErrorResponse('Ação não reconhecida', 400);
    }
    
    $data = getJsonInput();
    
    if (!validate_csrf_token($data['csrf_token'] ?? '')) {
        sendErrorResponse('Token CSRF inválido', 403);
    }
    
    $id = intval($data['id'] ?? 0);
    if (!$id) {
        sendErrorResponse('ID não fornecido', 400);
    }
    
    $cliente_id = getCurrentClienteId();
    
    $stmt = $pdo->prepare("SELECT id, titulo FROM noticias WHERE id = :id AND cliente_id = :cliente_id");
    $stmt->execute([':id' => $id, ':cliente_id' => $cliente_id]);
    $noticia = $stmt->fetch();
    
    if (!$noticia) {
        sendErrorResponse('Notícia não encontrada', 404);
    }
    
    $stmt = $pdo->prepare("UPDATE noticias SET status = 'arquivado', updated_at = NOW() WHERE id = :id");
    $stmt->execute([':id' => $id]);
    
    logAction($pdo, getCurrentUserId(), 'delete', 'noticia', [
        'noticia_id' => $id,
        'titulo' => $noticia['titulo']
    ]);
    
    sendSuccessResponse(null, 'Notícia arquivada com sucesso');
}

?>

