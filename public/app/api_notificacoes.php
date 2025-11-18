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
    $usuario_id = getCurrentUserId();
    $cliente_id = getCurrentClienteId();
    
    switch ($action) {
        case 'list':
            $lida = isset($_GET['lida']) ? intval($_GET['lida']) : null;
            $tipo = sanitize_input($_GET['tipo'] ?? '');
            
            $sql = "
                SELECT * FROM notificacoes 
                WHERE usuario_id = :usuario_id AND cliente_id = :cliente_id
            ";
            $params = [
                ':usuario_id' => $usuario_id,
                ':cliente_id' => $cliente_id
            ];
            
            if ($lida !== null) {
                $sql .= " AND lida = :lida";
                $params[':lida'] = $lida;
            }
            if (!empty($tipo)) {
                $sql .= " AND tipo = :tipo";
                $params[':tipo'] = $tipo;
            }
            
            $sql .= " ORDER BY created_at DESC LIMIT 100";
            
            $stmt = $pdo->prepare($sql);
            $stmt->execute($params);
            $notificacoes = $stmt->fetchAll();
            
            foreach ($notificacoes as &$notificacao) {
                if (!empty($notificacao['dados_extras'])) {
                    $notificacao['dados_extras'] = json_decode($notificacao['dados_extras'], true) ?: [];
                }
            }
            
            sendSuccessResponse($notificacoes);
            break;
            
        case 'get':
            $id = intval($_GET['id'] ?? 0);
            if (!$id) {
                sendErrorResponse('ID não fornecido', 400);
            }
            
            $stmt = $pdo->prepare("
                SELECT * FROM notificacoes 
                WHERE id = :id AND usuario_id = :usuario_id AND cliente_id = :cliente_id
            ");
            $stmt->execute([
                ':id' => $id,
                ':usuario_id' => $usuario_id,
                ':cliente_id' => $cliente_id
            ]);
            $notificacao = $stmt->fetch();
            
            if (!$notificacao) {
                sendErrorResponse('Notificação não encontrada', 404);
            }
            
            if (!empty($notificacao['dados_extras'])) {
                $notificacao['dados_extras'] = json_decode($notificacao['dados_extras'], true) ?: [];
            }
            
            // Marcar como lida ao visualizar
            if (!$notificacao['lida']) {
                $stmt = $pdo->prepare("UPDATE notificacoes SET lida = 1, data_leitura = NOW() WHERE id = :id");
                $stmt->execute([':id' => $id]);
                $notificacao['lida'] = 1;
                $notificacao['data_leitura'] = date('Y-m-d H:i:s');
            }
            
            sendSuccessResponse($notificacao);
            break;
            
        case 'unread':
            $stmt = $pdo->prepare("
                SELECT COUNT(*) as total FROM notificacoes 
                WHERE usuario_id = :usuario_id AND cliente_id = :cliente_id AND lida = 0
            ");
            $stmt->execute([
                ':usuario_id' => $usuario_id,
                ':cliente_id' => $cliente_id
            ]);
            $result = $stmt->fetch();
            
            sendSuccessResponse(['total' => intval($result['total'])]);
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
    
    $usuario_id = isset($data['usuario_id']) ? intval($data['usuario_id']) : getCurrentUserId();
    $cliente_id = getCurrentClienteId();
    $tipo = sanitize_input($data['tipo'] ?? 'info');
    $titulo = sanitize_input($data['titulo'] ?? '');
    $mensagem = sanitize_input($data['mensagem'] ?? '');
    $prioridade = sanitize_input($data['prioridade'] ?? 'media');
    $acao_url = sanitize_input($data['acao_url'] ?? '');
    $acao_texto = sanitize_input($data['acao_texto'] ?? '');
    $dados_extras = isset($data['dados_extras']) && is_array($data['dados_extras']) ? $data['dados_extras'] : [];
    $expira_em = sanitize_input($data['expira_em'] ?? '');
    
    if (empty($titulo) || empty($mensagem)) {
        sendErrorResponse('Título e mensagem são obrigatórios', 400);
    }
    
    if (!in_array($tipo, ['info', 'warning', 'error', 'success'])) {
        sendErrorResponse('Tipo inválido', 400);
    }
    
    if (!in_array($prioridade, ['baixa', 'media', 'alta', 'urgente'])) {
        sendErrorResponse('Prioridade inválida', 400);
    }
    
    $stmt = $pdo->prepare("
        INSERT INTO notificacoes (usuario_id, cliente_id, tipo, titulo, mensagem, prioridade, 
                                 acao_url, acao_texto, dados_extras, expira_em, lida, created_at, updated_at)
        VALUES (:usuario_id, :cliente_id, :tipo, :titulo, :mensagem, :prioridade, 
                :acao_url, :acao_texto, :dados_extras, :expira_em, 0, NOW(), NOW())
    ");
    
    $stmt->execute([
        ':usuario_id' => $usuario_id,
        ':cliente_id' => $cliente_id,
        ':tipo' => $tipo,
        ':titulo' => $titulo,
        ':mensagem' => $mensagem,
        ':prioridade' => $prioridade,
        ':acao_url' => $acao_url,
        ':acao_texto' => $acao_texto,
        ':dados_extras' => json_encode($dados_extras),
        ':expira_em' => $expira_em ?: null
    ]);
    
    $id = $pdo->lastInsertId();
    
    logAction($pdo, getCurrentUserId(), 'create', 'notificacao', [
        'notificacao_id' => $id,
        'usuario_id' => $usuario_id,
        'titulo' => $titulo
    ]);
    
    sendSuccessResponse(['id' => $id], 'Notificação criada com sucesso');
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
    
    $usuario_id = getCurrentUserId();
    $cliente_id = getCurrentClienteId();
    
    $stmt = $pdo->prepare("
        SELECT id FROM notificacoes 
        WHERE id = :id AND usuario_id = :usuario_id AND cliente_id = :cliente_id
    ");
    $stmt->execute([
        ':id' => $id,
        ':usuario_id' => $usuario_id,
        ':cliente_id' => $cliente_id
    ]);
    if (!$stmt->fetch()) {
        sendErrorResponse('Notificação não encontrada', 404);
    }
    
    $updates = [];
    $params = [':id' => $id];
    
    if (isset($data['lida'])) {
        $updates[] = "lida = :lida";
        $params[':lida'] = intval($data['lida']);
        if ($data['lida'] && !isset($data['data_leitura'])) {
            $updates[] = "data_leitura = NOW()";
        }
    }
    if (isset($data['tipo']) && in_array($data['tipo'], ['info', 'warning', 'error', 'success'])) {
        $updates[] = "tipo = :tipo";
        $params[':tipo'] = sanitize_input($data['tipo']);
    }
    if (isset($data['titulo'])) {
        $updates[] = "titulo = :titulo";
        $params[':titulo'] = sanitize_input($data['titulo']);
    }
    if (isset($data['mensagem'])) {
        $updates[] = "mensagem = :mensagem";
        $params[':mensagem'] = sanitize_input($data['mensagem']);
    }
    if (isset($data['prioridade']) && in_array($data['prioridade'], ['baixa', 'media', 'alta', 'urgente'])) {
        $updates[] = "prioridade = :prioridade";
        $params[':prioridade'] = sanitize_input($data['prioridade']);
    }
    
    if (empty($updates)) {
        sendErrorResponse('Nenhum campo para atualizar', 400);
    }
    
    $updates[] = "updated_at = NOW()";
    $sql = "UPDATE notificacoes SET " . implode(', ', $updates) . " WHERE id = :id";
    
    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    
    logAction($pdo, getCurrentUserId(), 'update', 'notificacao', ['notificacao_id' => $id]);
    
    sendSuccessResponse(null, 'Notificação atualizada com sucesso');
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
    
    $usuario_id = getCurrentUserId();
    $cliente_id = getCurrentClienteId();
    
    $stmt = $pdo->prepare("
        SELECT id, titulo FROM notificacoes 
        WHERE id = :id AND usuario_id = :usuario_id AND cliente_id = :cliente_id
    ");
    $stmt->execute([
        ':id' => $id,
        ':usuario_id' => $usuario_id,
        ':cliente_id' => $cliente_id
    ]);
    $notificacao = $stmt->fetch();
    
    if (!$notificacao) {
        sendErrorResponse('Notificação não encontrada', 404);
    }
    
    $stmt = $pdo->prepare("DELETE FROM notificacoes WHERE id = :id");
    $stmt->execute([':id' => $id]);
    
    logAction($pdo, getCurrentUserId(), 'delete', 'notificacao', [
        'notificacao_id' => $id,
        'titulo' => $notificacao['titulo']
    ]);
    
    sendSuccessResponse(null, 'Notificação removida com sucesso');
}

?>

