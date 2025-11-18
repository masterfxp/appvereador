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
            $ativo = isset($_GET['ativo']) ? intval($_GET['ativo']) : null;
            
            $sql = "SELECT * FROM tipos_contrato WHERE 1=1";
            $params = [];
            
            if ($ativo !== null) {
                $sql .= " AND ativo = :ativo";
                $params[':ativo'] = $ativo;
            }
            
            $sql .= " ORDER BY nome ASC";
            
            $stmt = $pdo->prepare($sql);
            $stmt->execute($params);
            $tipos = $stmt->fetchAll();
            
            sendSuccessResponse($tipos);
            break;
            
        case 'get':
            $id = intval($_GET['id'] ?? 0);
            if (!$id) {
                sendErrorResponse('ID não fornecido', 400);
            }
            
            $stmt = $pdo->prepare("SELECT * FROM tipos_contrato WHERE id = :id");
            $stmt->execute([':id' => $id]);
            $tipo = $stmt->fetch();
            
            if (!$tipo) {
                sendErrorResponse('Tipo de contrato não encontrado', 404);
            }
            
            sendSuccessResponse($tipo);
            break;
            
        case 'search':
            $termo = sanitize_input($_GET['q'] ?? '');
            if (empty($termo)) {
                sendErrorResponse('Termo de busca não fornecido', 400);
            }
            
            $stmt = $pdo->prepare("
                SELECT id, nome, max_usuarios, dias_validade, preco_mensal, ativo
                FROM tipos_contrato 
                WHERE nome LIKE :termo
                ORDER BY nome
                LIMIT 50
            ");
            $termoLike = "%{$termo}%";
            $stmt->execute([':termo' => $termoLike]);
            $tipos = $stmt->fetchAll();
            
            sendSuccessResponse($tipos);
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
    $max_usuarios = isset($data['max_usuarios']) ? intval($data['max_usuarios']) : 1;
    $dias_validade = isset($data['dias_validade']) ? intval($data['dias_validade']) : 30;
    $preco_mensal = isset($data['preco_mensal']) ? floatval($data['preco_mensal']) : 0;
    $descricao = sanitize_input($data['descricao'] ?? '');
    
    if (empty($nome)) {
        sendErrorResponse('Nome é obrigatório', 400);
    }
    
    if ($max_usuarios < 1) {
        sendErrorResponse('Máximo de usuários deve ser maior que zero', 400);
    }
    
    if ($dias_validade < 1) {
        sendErrorResponse('Dias de validade deve ser maior que zero', 400);
    }
    
    // Verificar se nome já existe
    $stmt = $pdo->prepare("SELECT id FROM tipos_contrato WHERE nome = :nome");
    $stmt->execute([':nome' => $nome]);
    if ($stmt->fetch()) {
        sendErrorResponse('Tipo de contrato já existe', 400);
    }
    
    $stmt = $pdo->prepare("
        INSERT INTO tipos_contrato (nome, max_usuarios, dias_validade, preco_mensal, descricao, ativo, created_at, updated_at)
        VALUES (:nome, :max_usuarios, :dias_validade, :preco_mensal, :descricao, 1, NOW(), NOW())
    ");
    
    $stmt->execute([
        ':nome' => $nome,
        ':max_usuarios' => $max_usuarios,
        ':dias_validade' => $dias_validade,
        ':preco_mensal' => $preco_mensal,
        ':descricao' => $descricao
    ]);
    
    $id = $pdo->lastInsertId();
    
    logAction($pdo, getCurrentUserId(), 'create', 'tipo_contrato', [
        'tipo_contrato_id' => $id,
        'nome' => $nome
    ]);
    
    sendSuccessResponse(['id' => $id], 'Tipo de contrato criado com sucesso');
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
    
    $stmt = $pdo->prepare("SELECT id FROM tipos_contrato WHERE id = :id");
    $stmt->execute([':id' => $id]);
    if (!$stmt->fetch()) {
        sendErrorResponse('Tipo de contrato não encontrado', 404);
    }
    
    $updates = [];
    $params = [':id' => $id];
    
    if (isset($data['nome'])) {
        $stmt = $pdo->prepare("SELECT id FROM tipos_contrato WHERE nome = :nome AND id != :id");
        $stmt->execute([':nome' => $data['nome'], ':id' => $id]);
        if ($stmt->fetch()) {
            sendErrorResponse('Tipo de contrato já existe', 400);
        }
        $updates[] = "nome = :nome";
        $params[':nome'] = sanitize_input($data['nome']);
    }
    if (isset($data['max_usuarios'])) {
        $max_usuarios = intval($data['max_usuarios']);
        if ($max_usuarios < 1) {
            sendErrorResponse('Máximo de usuários deve ser maior que zero', 400);
        }
        $updates[] = "max_usuarios = :max_usuarios";
        $params[':max_usuarios'] = $max_usuarios;
    }
    if (isset($data['dias_validade'])) {
        $dias_validade = intval($data['dias_validade']);
        if ($dias_validade < 1) {
            sendErrorResponse('Dias de validade deve ser maior que zero', 400);
        }
        $updates[] = "dias_validade = :dias_validade";
        $params[':dias_validade'] = $dias_validade;
    }
    if (isset($data['preco_mensal'])) {
        $updates[] = "preco_mensal = :preco_mensal";
        $params[':preco_mensal'] = floatval($data['preco_mensal']);
    }
    if (isset($data['descricao'])) {
        $updates[] = "descricao = :descricao";
        $params[':descricao'] = sanitize_input($data['descricao']);
    }
    if (isset($data['ativo'])) {
        $updates[] = "ativo = :ativo";
        $params[':ativo'] = intval($data['ativo']);
    }
    
    if (empty($updates)) {
        sendErrorResponse('Nenhum campo para atualizar', 400);
    }
    
    $updates[] = "updated_at = NOW()";
    $sql = "UPDATE tipos_contrato SET " . implode(', ', $updates) . " WHERE id = :id";
    
    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    
    logAction($pdo, getCurrentUserId(), 'update', 'tipo_contrato', ['tipo_contrato_id' => $id]);
    
    sendSuccessResponse(null, 'Tipo de contrato atualizado com sucesso');
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
    
    $stmt = $pdo->prepare("SELECT id, nome FROM tipos_contrato WHERE id = :id");
    $stmt->execute([':id' => $id]);
    $tipo = $stmt->fetch();
    
    if (!$tipo) {
        sendErrorResponse('Tipo de contrato não encontrado', 404);
    }
    
    // Soft delete - marcar como inativo
    $stmt = $pdo->prepare("UPDATE tipos_contrato SET ativo = 0, updated_at = NOW() WHERE id = :id");
    $stmt->execute([':id' => $id]);
    
    logAction($pdo, getCurrentUserId(), 'delete', 'tipo_contrato', [
        'tipo_contrato_id' => $id,
        'nome' => $tipo['nome']
    ]);
    
    sendSuccessResponse(null, 'Tipo de contrato removido com sucesso');
}

?>

