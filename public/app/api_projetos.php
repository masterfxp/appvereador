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
            $tipo = sanitize_input($_GET['tipo'] ?? '');
            
            $sql = "
                SELECT p.*, u.nome as autor_nome
                FROM projetos p
                LEFT JOIN usuarios u ON p.autor_id = u.id
                WHERE p.cliente_id = :cliente_id
            ";
            $params = [':cliente_id' => $cliente_id];
            
            if (!empty($status)) {
                $sql .= " AND p.status = :status";
                $params[':status'] = $status;
            }
            if (!empty($tipo)) {
                $sql .= " AND p.tipo = :tipo";
                $params[':tipo'] = $tipo;
            }
            
            $sql .= " ORDER BY p.created_at DESC";
            
            $stmt = $pdo->prepare($sql);
            $stmt->execute($params);
            $projetos = $stmt->fetchAll();
            
            // Decodificar JSON fields
            foreach ($projetos as &$projeto) {
                if (!empty($projeto['anexos'])) {
                    $projeto['anexos'] = json_decode($projeto['anexos'], true) ?: [];
                }
                if (!empty($projeto['tags'])) {
                    $projeto['tags'] = json_decode($projeto['tags'], true) ?: [];
                }
            }
            
            sendSuccessResponse($projetos);
            break;
            
        case 'get':
            $id = intval($_GET['id'] ?? 0);
            if (!$id) {
                sendErrorResponse('ID não fornecido', 400);
            }
            
            $stmt = $pdo->prepare("
                SELECT p.*, u.nome as autor_nome
                FROM projetos p
                LEFT JOIN usuarios u ON p.autor_id = u.id
                WHERE p.id = :id AND p.cliente_id = :cliente_id
            ");
            $stmt->execute([
                ':id' => $id,
                ':cliente_id' => $cliente_id
            ]);
            $projeto = $stmt->fetch();
            
            if (!$projeto) {
                sendErrorResponse('Projeto não encontrado', 404);
            }
            
            if (!empty($projeto['anexos'])) {
                $projeto['anexos'] = json_decode($projeto['anexos'], true) ?: [];
            }
            if (!empty($projeto['tags'])) {
                $projeto['tags'] = json_decode($projeto['tags'], true) ?: [];
            }
            
            sendSuccessResponse($projeto);
            break;
            
        case 'search':
            $termo = sanitize_input($_GET['q'] ?? '');
            if (empty($termo)) {
                sendErrorResponse('Termo de busca não fornecido', 400);
            }
            
            $stmt = $pdo->prepare("
                SELECT p.*, u.nome as autor_nome
                FROM projetos p
                LEFT JOIN usuarios u ON p.autor_id = u.id
                WHERE p.cliente_id = :cliente_id 
                AND (p.titulo LIKE :termo OR p.descricao LIKE :termo)
                ORDER BY p.created_at DESC
                LIMIT 50
            ");
            $termoLike = "%{$termo}%";
            $stmt->execute([
                ':cliente_id' => $cliente_id,
                ':termo' => $termoLike
            ]);
            $projetos = $stmt->fetchAll();
            
            sendSuccessResponse($projetos);
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
    $tipo = sanitize_input($data['tipo'] ?? '');
    $descricao = sanitize_input($data['descricao'] ?? '');
    $conteudo = sanitize_input($data['conteudo'] ?? '');
    $status = sanitize_input($data['status'] ?? 'em_tramitacao');
    $autor_id = getCurrentUserId();
    $gabinete_id = getCurrentGabineteId();
    $cliente_id = getCurrentClienteId();
    $numero_protocolo = sanitize_input($data['numero_protocolo'] ?? '');
    $observacoes = sanitize_input($data['observacoes'] ?? '');
    $publico = isset($data['publico']) ? intval($data['publico']) : 0;
    $anexos = isset($data['anexos']) && is_array($data['anexos']) ? $data['anexos'] : [];
    $tags = isset($data['tags']) && is_array($data['tags']) ? $data['tags'] : [];
    
    if (empty($titulo) || empty($tipo) || empty($descricao)) {
        sendErrorResponse('Título, tipo e descrição são obrigatórios', 400);
    }
    
    if (!in_array($tipo, ['projeto_lei', 'indicacao', 'requerimento', 'mocao'])) {
        sendErrorResponse('Tipo inválido', 400);
    }
    
    if (!in_array($status, ['aprovado', 'em_tramitacao', 'rejeitado', 'arquivado'])) {
        sendErrorResponse('Status inválido', 400);
    }
    
    $stmt = $pdo->prepare("
        INSERT INTO projetos (titulo, tipo, status, descricao, conteudo, autor_id, gabinete_id, 
                             cliente_id, numero_protocolo, observacoes, publico, anexos, tags, 
                             created_at, updated_at)
        VALUES (:titulo, :tipo, :status, :descricao, :conteudo, :autor_id, :gabinete_id, 
                :cliente_id, :numero_protocolo, :observacoes, :publico, :anexos, :tags, 
                NOW(), NOW())
    ");
    
    $stmt->execute([
        ':titulo' => $titulo,
        ':tipo' => $tipo,
        ':status' => $status,
        ':descricao' => $descricao,
        ':conteudo' => $conteudo,
        ':autor_id' => $autor_id,
        ':gabinete_id' => $gabinete_id,
        ':cliente_id' => $cliente_id,
        ':numero_protocolo' => $numero_protocolo,
        ':observacoes' => $observacoes,
        ':publico' => $publico,
        ':anexos' => json_encode($anexos),
        ':tags' => json_encode($tags)
    ]);
    
    $id = $pdo->lastInsertId();
    
    logAction($pdo, getCurrentUserId(), 'create', 'projeto', [
        'projeto_id' => $id,
        'titulo' => $titulo,
        'tipo' => $tipo
    ]);
    
    sendSuccessResponse(['id' => $id], 'Projeto criado com sucesso');
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
    
    // Verificar se projeto existe
    $stmt = $pdo->prepare("SELECT id FROM projetos WHERE id = :id AND cliente_id = :cliente_id");
    $stmt->execute([':id' => $id, ':cliente_id' => $cliente_id]);
    if (!$stmt->fetch()) {
        sendErrorResponse('Projeto não encontrado', 404);
    }
    
    $updates = [];
    $params = [':id' => $id, ':cliente_id' => $cliente_id];
    
    if (isset($data['titulo'])) {
        $updates[] = "titulo = :titulo";
        $params[':titulo'] = sanitize_input($data['titulo']);
    }
    if (isset($data['tipo']) && in_array($data['tipo'], ['projeto_lei', 'indicacao', 'requerimento', 'mocao'])) {
        $updates[] = "tipo = :tipo";
        $params[':tipo'] = sanitize_input($data['tipo']);
    }
    if (isset($data['status']) && in_array($data['status'], ['aprovado', 'em_tramitacao', 'rejeitado', 'arquivado'])) {
        $updates[] = "status = :status";
        $params[':status'] = sanitize_input($data['status']);
    }
    if (isset($data['descricao'])) {
        $updates[] = "descricao = :descricao";
        $params[':descricao'] = sanitize_input($data['descricao']);
    }
    if (isset($data['conteudo'])) {
        $updates[] = "conteudo = :conteudo";
        $params[':conteudo'] = sanitize_input($data['conteudo']);
    }
    if (isset($data['numero_protocolo'])) {
        $updates[] = "numero_protocolo = :numero_protocolo";
        $params[':numero_protocolo'] = sanitize_input($data['numero_protocolo']);
    }
    if (isset($data['observacoes'])) {
        $updates[] = "observacoes = :observacoes";
        $params[':observacoes'] = sanitize_input($data['observacoes']);
    }
    if (isset($data['publico'])) {
        $updates[] = "publico = :publico";
        $params[':publico'] = intval($data['publico']);
    }
    if (isset($data['anexos']) && is_array($data['anexos'])) {
        $updates[] = "anexos = :anexos";
        $params[':anexos'] = json_encode($data['anexos']);
    }
    if (isset($data['tags']) && is_array($data['tags'])) {
        $updates[] = "tags = :tags";
        $params[':tags'] = json_encode($data['tags']);
    }
    if (isset($data['data_protocolo'])) {
        $updates[] = "data_protocolo = :data_protocolo";
        $params[':data_protocolo'] = $data['data_protocolo'];
    }
    if (isset($data['data_votacao'])) {
        $updates[] = "data_votacao = :data_votacao";
        $params[':data_votacao'] = $data['data_votacao'];
    }
    if (isset($data['resultado_votacao']) && in_array($data['resultado_votacao'], ['aprovado', 'rejeitado', 'adiado'])) {
        $updates[] = "resultado_votacao = :resultado_votacao";
        $params[':resultado_votacao'] = sanitize_input($data['resultado_votacao']);
    }
    
    if (empty($updates)) {
        sendErrorResponse('Nenhum campo para atualizar', 400);
    }
    
    $updates[] = "updated_at = NOW()";
    $sql = "UPDATE projetos SET " . implode(', ', $updates) . " WHERE id = :id AND cliente_id = :cliente_id";
    
    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    
    logAction($pdo, getCurrentUserId(), 'update', 'projeto', ['projeto_id' => $id]);
    
    sendSuccessResponse(null, 'Projeto atualizado com sucesso');
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
    
    // Verificar se projeto existe
    $stmt = $pdo->prepare("SELECT id, titulo FROM projetos WHERE id = :id AND cliente_id = :cliente_id");
    $stmt->execute([':id' => $id, ':cliente_id' => $cliente_id]);
    $projeto = $stmt->fetch();
    
    if (!$projeto) {
        sendErrorResponse('Projeto não encontrado', 404);
    }
    
    // Soft delete - arquivar
    $stmt = $pdo->prepare("UPDATE projetos SET status = 'arquivado', updated_at = NOW() WHERE id = :id");
    $stmt->execute([':id' => $id]);
    
    logAction($pdo, getCurrentUserId(), 'delete', 'projeto', [
        'projeto_id' => $id,
        'titulo' => $projeto['titulo']
    ]);
    
    sendSuccessResponse(null, 'Projeto arquivado com sucesso');
}

?>

