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
            $prioridade = sanitize_input($_GET['prioridade'] ?? '');
            
            $sql = "
                SELECT d.*, u.nome as cidadao_nome, u2.nome as responsavel_nome
                FROM demandas d
                LEFT JOIN usuarios u ON d.cidadao_id = u.id
                LEFT JOIN usuarios u2 ON d.responsavel_id = u2.id
                WHERE d.cliente_id = :cliente_id
            ";
            $params = [':cliente_id' => $cliente_id];
            
            if (!empty($status)) {
                $sql .= " AND d.status = :status";
                $params[':status'] = $status;
            }
            if (!empty($prioridade)) {
                $sql .= " AND d.prioridade = :prioridade";
                $params[':prioridade'] = $prioridade;
            }
            
            $sql .= " ORDER BY d.created_at DESC";
            
            $stmt = $pdo->prepare($sql);
            $stmt->execute($params);
            $demandas = $stmt->fetchAll();
            
            foreach ($demandas as &$demanda) {
                if (!empty($demanda['anexos'])) {
                    $demanda['anexos'] = json_decode($demanda['anexos'], true) ?: [];
                }
            }
            
            sendSuccessResponse($demandas);
            break;
            
        case 'get':
            $id = intval($_GET['id'] ?? 0);
            if (!$id) {
                sendErrorResponse('ID não fornecido', 400);
            }
            
            $stmt = $pdo->prepare("
                SELECT d.*, u.nome as cidadao_nome, u2.nome as responsavel_nome
                FROM demandas d
                LEFT JOIN usuarios u ON d.cidadao_id = u.id
                LEFT JOIN usuarios u2 ON d.responsavel_id = u2.id
                WHERE d.id = :id AND d.cliente_id = :cliente_id
            ");
            $stmt->execute([
                ':id' => $id,
                ':cliente_id' => $cliente_id
            ]);
            $demanda = $stmt->fetch();
            
            if (!$demanda) {
                sendErrorResponse('Demanda não encontrada', 404);
            }
            
            if (!empty($demanda['anexos'])) {
                $demanda['anexos'] = json_decode($demanda['anexos'], true) ?: [];
            }
            
            sendSuccessResponse($demanda);
            break;
            
        case 'search':
            $termo = sanitize_input($_GET['q'] ?? '');
            if (empty($termo)) {
                sendErrorResponse('Termo de busca não fornecido', 400);
            }
            
            $stmt = $pdo->prepare("
                SELECT d.*, u.nome as cidadao_nome
                FROM demandas d
                LEFT JOIN usuarios u ON d.cidadao_id = u.id
                WHERE d.cliente_id = :cliente_id 
                AND (d.assunto LIKE :termo OR d.descricao LIKE :termo)
                ORDER BY d.created_at DESC
                LIMIT 50
            ");
            $termoLike = "%{$termo}%";
            $stmt->execute([
                ':cliente_id' => $cliente_id,
                ':termo' => $termoLike
            ]);
            $demandas = $stmt->fetchAll();
            
            sendSuccessResponse($demandas);
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
    
    $assunto = sanitize_input($data['assunto'] ?? '');
    $descricao = sanitize_input($data['descricao'] ?? '');
    $status = sanitize_input($data['status'] ?? 'pendente');
    $prioridade = sanitize_input($data['prioridade'] ?? 'media');
    $categoria = sanitize_input($data['categoria'] ?? '');
    $endereco = sanitize_input($data['endereco'] ?? '');
    $rua = sanitize_input($data['rua'] ?? '');
    $bairro = sanitize_input($data['bairro'] ?? '');
    $telefone_contato = sanitize_input($data['telefone_contato'] ?? '');
    $email_contato = sanitize_input($data['email_contato'] ?? '');
    $latitude = isset($data['latitude']) ? floatval($data['latitude']) : null;
    $longitude = isset($data['longitude']) ? floatval($data['longitude']) : null;
    $responsavel_id = isset($data['responsavel_id']) ? intval($data['responsavel_id']) : null;
    $cidadao_id = getCurrentUserId();
    $gabinete_id = getCurrentGabineteId();
    $cliente_id = getCurrentClienteId();
    $anexos = isset($data['anexos']) && is_array($data['anexos']) ? $data['anexos'] : [];
    
    if (empty($assunto) || empty($descricao)) {
        sendErrorResponse('Assunto e descrição são obrigatórios', 400);
    }
    
    if (!in_array($status, ['pendente', 'em_andamento', 'resolvido', 'arquivado'])) {
        sendErrorResponse('Status inválido', 400);
    }
    
    if (!in_array($prioridade, ['baixa', 'media', 'alta', 'urgente'])) {
        sendErrorResponse('Prioridade inválida', 400);
    }
    
    $stmt = $pdo->prepare("
        INSERT INTO demandas (cidadao_id, gabinete_id, cliente_id, assunto, descricao, status, 
                             prioridade, categoria, endereco, rua, bairro, telefone_contato, 
                             email_contato, latitude, longitude, responsavel_id, anexos, 
                             created_at, updated_at)
        VALUES (:cidadao_id, :gabinete_id, :cliente_id, :assunto, :descricao, :status, 
                :prioridade, :categoria, :endereco, :rua, :bairro, :telefone_contato, 
                :email_contato, :latitude, :longitude, :responsavel_id, :anexos, 
                NOW(), NOW())
    ");
    
    $stmt->execute([
        ':cidadao_id' => $cidadao_id,
        ':gabinete_id' => $gabinete_id,
        ':cliente_id' => $cliente_id,
        ':assunto' => $assunto,
        ':descricao' => $descricao,
        ':status' => $status,
        ':prioridade' => $prioridade,
        ':categoria' => $categoria,
        ':endereco' => $endereco,
        ':rua' => $rua,
        ':bairro' => $bairro,
        ':telefone_contato' => $telefone_contato,
        ':email_contato' => $email_contato,
        ':latitude' => $latitude,
        ':longitude' => $longitude,
        ':responsavel_id' => $responsavel_id,
        ':anexos' => json_encode($anexos)
    ]);
    
    $id = $pdo->lastInsertId();
    
    logAction($pdo, getCurrentUserId(), 'create', 'demanda', [
        'demanda_id' => $id,
        'assunto' => $assunto
    ]);
    
    sendSuccessResponse(['id' => $id], 'Demanda criada com sucesso');
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
    
    $stmt = $pdo->prepare("SELECT id FROM demandas WHERE id = :id AND cliente_id = :cliente_id");
    $stmt->execute([':id' => $id, ':cliente_id' => $cliente_id]);
    if (!$stmt->fetch()) {
        sendErrorResponse('Demanda não encontrada', 404);
    }
    
    $updates = [];
    $params = [':id' => $id, ':cliente_id' => $cliente_id];
    
    if (isset($data['assunto'])) {
        $updates[] = "assunto = :assunto";
        $params[':assunto'] = sanitize_input($data['assunto']);
    }
    if (isset($data['descricao'])) {
        $updates[] = "descricao = :descricao";
        $params[':descricao'] = sanitize_input($data['descricao']);
    }
    if (isset($data['status']) && in_array($data['status'], ['pendente', 'em_andamento', 'resolvido', 'arquivado'])) {
        $updates[] = "status = :status";
        $params[':status'] = sanitize_input($data['status']);
        if ($data['status'] === 'resolvido' && !isset($data['data_resolucao'])) {
            $updates[] = "data_resolucao = NOW()";
        }
    }
    if (isset($data['prioridade']) && in_array($data['prioridade'], ['baixa', 'media', 'alta', 'urgente'])) {
        $updates[] = "prioridade = :prioridade";
        $params[':prioridade'] = sanitize_input($data['prioridade']);
    }
    if (isset($data['categoria'])) {
        $updates[] = "categoria = :categoria";
        $params[':categoria'] = sanitize_input($data['categoria']);
    }
    if (isset($data['responsavel_id'])) {
        $updates[] = "responsavel_id = :responsavel_id";
        $params[':responsavel_id'] = intval($data['responsavel_id']) ?: null;
    }
    if (isset($data['observacoes_resolucao'])) {
        $updates[] = "observacoes_resolucao = :observacoes_resolucao";
        $params[':observacoes_resolucao'] = sanitize_input($data['observacoes_resolucao']);
    }
    if (isset($data['anexos']) && is_array($data['anexos'])) {
        $updates[] = "anexos = :anexos";
        $params[':anexos'] = json_encode($data['anexos']);
    }
    
    if (empty($updates)) {
        sendErrorResponse('Nenhum campo para atualizar', 400);
    }
    
    $updates[] = "updated_at = NOW()";
    $sql = "UPDATE demandas SET " . implode(', ', $updates) . " WHERE id = :id AND cliente_id = :cliente_id";
    
    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    
    logAction($pdo, getCurrentUserId(), 'update', 'demanda', ['demanda_id' => $id]);
    
    sendSuccessResponse(null, 'Demanda atualizada com sucesso');
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
    
    $stmt = $pdo->prepare("SELECT id, assunto FROM demandas WHERE id = :id AND cliente_id = :cliente_id");
    $stmt->execute([':id' => $id, ':cliente_id' => $cliente_id]);
    $demanda = $stmt->fetch();
    
    if (!$demanda) {
        sendErrorResponse('Demanda não encontrada', 404);
    }
    
    $stmt = $pdo->prepare("UPDATE demandas SET status = 'arquivado', updated_at = NOW() WHERE id = :id");
    $stmt->execute([':id' => $id]);
    
    logAction($pdo, getCurrentUserId(), 'delete', 'demanda', [
        'demanda_id' => $id,
        'assunto' => $demanda['assunto']
    ]);
    
    sendSuccessResponse(null, 'Demanda arquivada com sucesso');
}

?>

