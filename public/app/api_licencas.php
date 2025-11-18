<?php
require 'conexao.php';
require 'auth_functions.php';
require 'api_config.php';

secure_session_start();
require_admin('index.php');
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
    
    switch ($action) {
        case 'list':
            $ativa = isset($_GET['ativa']) ? intval($_GET['ativa']) : null;
            $usada = isset($_GET['usada']) ? intval($_GET['usada']) : null;
            
            $sql = "SELECT * FROM licencas WHERE 1=1";
            $params = [];
            
            if ($ativa !== null) {
                $sql .= " AND ativa = :ativa";
                $params[':ativa'] = $ativa;
            }
            if ($usada !== null) {
                $sql .= " AND usada = :usada";
                $params[':usada'] = $usada;
            }
            
            $sql .= " ORDER BY created_at DESC";
            
            $stmt = $pdo->prepare($sql);
            $stmt->execute($params);
            $licencas = $stmt->fetchAll();
            
            sendSuccessResponse($licencas);
            break;
            
        case 'get':
            $id = intval($_GET['id'] ?? 0);
            if (!$id) {
                sendErrorResponse('ID não fornecido', 400);
            }
            
            $stmt = $pdo->prepare("SELECT * FROM licencas WHERE id = :id");
            $stmt->execute([':id' => $id]);
            $licenca = $stmt->fetch();
            
            if (!$licenca) {
                sendErrorResponse('Licença não encontrada', 404);
            }
            
            sendSuccessResponse($licenca);
            break;
            
        case 'search':
            $termo = sanitize_input($_GET['q'] ?? '');
            if (empty($termo)) {
                sendErrorResponse('Termo de busca não fornecido', 400);
            }
            
            $stmt = $pdo->prepare("
                SELECT id, guid, nome, email, nivel, ativa, usada, data_uso
                FROM licencas 
                WHERE (nome LIKE :termo OR email LIKE :termo OR guid LIKE :termo)
                ORDER BY created_at DESC
                LIMIT 50
            ");
            $termoLike = "%{$termo}%";
            $stmt->execute([':termo' => $termoLike]);
            $licencas = $stmt->fetchAll();
            
            sendSuccessResponse($licencas);
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
    
    $nome = sanitize_input($data['nome'] ?? '');
    $email = sanitize_input($data['email'] ?? '');
    $nivel = sanitize_input($data['nivel'] ?? 'assessor');
    $criado_por = getCurrentUserId();
    
    if (empty($nome) || empty($email)) {
        sendErrorResponse('Nome e email são obrigatórios', 400);
    }
    
    if (!in_array($nivel, ['vereador', 'assessor'])) {
        sendErrorResponse('Nível inválido', 400);
    }
    
    $guid = generate_guid();
    
    $stmt = $pdo->prepare("
        INSERT INTO licencas (guid, nome, email, nivel, ativa, usada, criado_por, created_at, updated_at)
        VALUES (:guid, :nome, :email, :nivel, 1, 0, :criado_por, NOW(), NOW())
    ");
    
    $stmt->execute([
        ':guid' => $guid,
        ':nome' => $nome,
        ':email' => $email,
        ':nivel' => $nivel,
        ':criado_por' => $criado_por
    ]);
    
    $id = $pdo->lastInsertId();
    
    logAction($pdo, getCurrentUserId(), 'create', 'licenca', [
        'licenca_id' => $id,
        'guid' => $guid,
        'nome' => $nome
    ]);
    
    sendSuccessResponse(['id' => $id, 'guid' => $guid], 'Licença criada com sucesso');
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
    
    $stmt = $pdo->prepare("SELECT id FROM licencas WHERE id = :id");
    $stmt->execute([':id' => $id]);
    if (!$stmt->fetch()) {
        sendErrorResponse('Licença não encontrada', 404);
    }
    
    $updates = [];
    $params = [':id' => $id];
    
    if (isset($data['nome'])) {
        $updates[] = "nome = :nome";
        $params[':nome'] = sanitize_input($data['nome']);
    }
    if (isset($data['email'])) {
        $updates[] = "email = :email";
        $params[':email'] = sanitize_input($data['email']);
    }
    if (isset($data['nivel']) && in_array($data['nivel'], ['vereador', 'assessor'])) {
        $updates[] = "nivel = :nivel";
        $params[':nivel'] = sanitize_input($data['nivel']);
    }
    if (isset($data['ativa'])) {
        $updates[] = "ativa = :ativa";
        $params[':ativa'] = intval($data['ativa']);
    }
    if (isset($data['usada'])) {
        $updates[] = "usada = :usada";
        $params[':usada'] = intval($data['usada']);
    }
    
    if (empty($updates)) {
        sendErrorResponse('Nenhum campo para atualizar', 400);
    }
    
    $updates[] = "updated_at = NOW()";
    $sql = "UPDATE licencas SET " . implode(', ', $updates) . " WHERE id = :id";
    
    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    
    logAction($pdo, getCurrentUserId(), 'update', 'licenca', ['licenca_id' => $id]);
    
    sendSuccessResponse(null, 'Licença atualizada com sucesso');
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
    
    $stmt = $pdo->prepare("SELECT id, guid, nome FROM licencas WHERE id = :id");
    $stmt->execute([':id' => $id]);
    $licenca = $stmt->fetch();
    
    if (!$licenca) {
        sendErrorResponse('Licença não encontrada', 404);
    }
    
    // Soft delete - marcar como inativa
    $stmt = $pdo->prepare("UPDATE licencas SET ativa = 0, updated_at = NOW() WHERE id = :id");
    $stmt->execute([':id' => $id]);
    
    logAction($pdo, getCurrentUserId(), 'delete', 'licenca', [
        'licenca_id' => $id,
        'guid' => $licenca['guid'],
        'nome' => $licenca['nome']
    ]);
    
    sendSuccessResponse(null, 'Licença removida com sucesso');
}

?>

