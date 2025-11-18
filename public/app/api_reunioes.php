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
            $data_inicio = sanitize_input($_GET['data_inicio'] ?? '');
            $data_fim = sanitize_input($_GET['data_fim'] ?? '');
            
            $sql = "
                SELECT r.*, u.nome as organizador_nome
                FROM reunioes r
                LEFT JOIN usuarios u ON r.organizador_id = u.id
                WHERE r.cliente_id = :cliente_id
            ";
            $params = [':cliente_id' => $cliente_id];
            
            if (!empty($status)) {
                $sql .= " AND r.status = :status";
                $params[':status'] = $status;
            }
            if (!empty($tipo)) {
                $sql .= " AND r.tipo = :tipo";
                $params[':tipo'] = $tipo;
            }
            if (!empty($data_inicio)) {
                $sql .= " AND r.data >= :data_inicio";
                $params[':data_inicio'] = $data_inicio;
            }
            if (!empty($data_fim)) {
                $sql .= " AND r.data <= :data_fim";
                $params[':data_fim'] = $data_fim;
            }
            
            $sql .= " ORDER BY r.data DESC, r.hora_inicio ASC";
            
            $stmt = $pdo->prepare($sql);
            $stmt->execute($params);
            $reunioes = $stmt->fetchAll();
            
            foreach ($reunioes as &$reuniao) {
                if (!empty($reuniao['participantes'])) {
                    $reuniao['participantes'] = json_decode($reuniao['participantes'], true) ?: [];
                }
                if (!empty($reuniao['anexos'])) {
                    $reuniao['anexos'] = json_decode($reuniao['anexos'], true) ?: [];
                }
            }
            
            sendSuccessResponse($reunioes);
            break;
            
        case 'get':
            $id = intval($_GET['id'] ?? 0);
            if (!$id) {
                sendErrorResponse('ID não fornecido', 400);
            }
            
            $stmt = $pdo->prepare("
                SELECT r.*, u.nome as organizador_nome
                FROM reunioes r
                LEFT JOIN usuarios u ON r.organizador_id = u.id
                WHERE r.id = :id AND r.cliente_id = :cliente_id
            ");
            $stmt->execute([
                ':id' => $id,
                ':cliente_id' => $cliente_id
            ]);
            $reuniao = $stmt->fetch();
            
            if (!$reuniao) {
                sendErrorResponse('Reunião não encontrada', 404);
            }
            
            if (!empty($reuniao['participantes'])) {
                $reuniao['participantes'] = json_decode($reuniao['participantes'], true) ?: [];
            }
            if (!empty($reuniao['anexos'])) {
                $reuniao['anexos'] = json_decode($reuniao['anexos'], true) ?: [];
            }
            
            sendSuccessResponse($reuniao);
            break;
            
        case 'search':
            $termo = sanitize_input($_GET['q'] ?? '');
            if (empty($termo)) {
                sendErrorResponse('Termo de busca não fornecido', 400);
            }
            
            $stmt = $pdo->prepare("
                SELECT r.*, u.nome as organizador_nome
                FROM reunioes r
                LEFT JOIN usuarios u ON r.organizador_id = u.id
                WHERE r.cliente_id = :cliente_id 
                AND (r.titulo LIKE :termo OR r.descricao LIKE :termo)
                ORDER BY r.data DESC
                LIMIT 50
            ");
            $termoLike = "%{$termo}%";
            $stmt->execute([
                ':cliente_id' => $cliente_id,
                ':termo' => $termoLike
            ]);
            $reunioes = $stmt->fetchAll();
            
            sendSuccessResponse($reunioes);
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
    $descricao = sanitize_input($data['descricao'] ?? '');
    $data_reuniao = sanitize_input($data['data'] ?? '');
    $hora_inicio = sanitize_input($data['hora_inicio'] ?? '');
    $hora_fim = sanitize_input($data['hora_fim'] ?? '');
    $local = sanitize_input($data['local'] ?? '');
    $endereco = sanitize_input($data['endereco'] ?? '');
    $tipo = sanitize_input($data['tipo'] ?? 'oficial');
    $status = sanitize_input($data['status'] ?? 'agendada');
    $pauta = sanitize_input($data['pauta'] ?? '');
    $observacoes = sanitize_input($data['observacoes'] ?? '');
    $publico = isset($data['publico']) ? intval($data['publico']) : 0;
    $link_meet = sanitize_input($data['link_meet'] ?? '');
    $latitude = isset($data['latitude']) ? floatval($data['latitude']) : null;
    $longitude = isset($data['longitude']) ? floatval($data['longitude']) : null;
    $organizador_id = getCurrentUserId();
    $gabinete_id = getCurrentGabineteId();
    $cliente_id = getCurrentClienteId();
    $participantes = isset($data['participantes']) && is_array($data['participantes']) ? $data['participantes'] : [];
    $anexos = isset($data['anexos']) && is_array($data['anexos']) ? $data['anexos'] : [];
    
    if (empty($titulo) || empty($data_reuniao)) {
        sendErrorResponse('Título e data são obrigatórios', 400);
    }
    
    if (!in_array($tipo, ['oficial', 'reuniao_gabinete', 'evento_publico', 'visita', 'outro'])) {
        sendErrorResponse('Tipo inválido', 400);
    }
    
    if (!in_array($status, ['agendada', 'realizada', 'cancelada'])) {
        sendErrorResponse('Status inválido', 400);
    }
    
    $stmt = $pdo->prepare("
        INSERT INTO reunioes (titulo, descricao, data, hora_inicio, hora_fim, local, endereco, 
                             tipo, status, organizador_id, gabinete_id, cliente_id, pauta, 
                             observacoes, publico, link_meet, latitude, longitude, participantes, 
                             anexos, created_at, updated_at)
        VALUES (:titulo, :descricao, :data, :hora_inicio, :hora_fim, :local, :endereco, 
                :tipo, :status, :organizador_id, :gabinete_id, :cliente_id, :pauta, 
                :observacoes, :publico, :link_meet, :latitude, :longitude, :participantes, 
                :anexos, NOW(), NOW())
    ");
    
    $stmt->execute([
        ':titulo' => $titulo,
        ':descricao' => $descricao,
        ':data' => $data_reuniao,
        ':hora_inicio' => $hora_inicio ?: null,
        ':hora_fim' => $hora_fim ?: null,
        ':local' => $local,
        ':endereco' => $endereco,
        ':tipo' => $tipo,
        ':status' => $status,
        ':organizador_id' => $organizador_id,
        ':gabinete_id' => $gabinete_id,
        ':cliente_id' => $cliente_id,
        ':pauta' => $pauta,
        ':observacoes' => $observacoes,
        ':publico' => $publico,
        ':link_meet' => $link_meet,
        ':latitude' => $latitude,
        ':longitude' => $longitude,
        ':participantes' => json_encode($participantes),
        ':anexos' => json_encode($anexos)
    ]);
    
    $id = $pdo->lastInsertId();
    
    logAction($pdo, getCurrentUserId(), 'create', 'reuniao', [
        'reuniao_id' => $id,
        'titulo' => $titulo
    ]);
    
    sendSuccessResponse(['id' => $id], 'Reunião criada com sucesso');
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
    
    $stmt = $pdo->prepare("SELECT id FROM reunioes WHERE id = :id AND cliente_id = :cliente_id");
    $stmt->execute([':id' => $id, ':cliente_id' => $cliente_id]);
    if (!$stmt->fetch()) {
        sendErrorResponse('Reunião não encontrada', 404);
    }
    
    $updates = [];
    $params = [':id' => $id, ':cliente_id' => $cliente_id];
    
    if (isset($data['titulo'])) {
        $updates[] = "titulo = :titulo";
        $params[':titulo'] = sanitize_input($data['titulo']);
    }
    if (isset($data['descricao'])) {
        $updates[] = "descricao = :descricao";
        $params[':descricao'] = sanitize_input($data['descricao']);
    }
    if (isset($data['data'])) {
        $updates[] = "data = :data";
        $params[':data'] = sanitize_input($data['data']);
    }
    if (isset($data['hora_inicio'])) {
        $updates[] = "hora_inicio = :hora_inicio";
        $params[':hora_inicio'] = sanitize_input($data['hora_inicio']) ?: null;
    }
    if (isset($data['hora_fim'])) {
        $updates[] = "hora_fim = :hora_fim";
        $params[':hora_fim'] = sanitize_input($data['hora_fim']) ?: null;
    }
    if (isset($data['local'])) {
        $updates[] = "local = :local";
        $params[':local'] = sanitize_input($data['local']);
    }
    if (isset($data['endereco'])) {
        $updates[] = "endereco = :endereco";
        $params[':endereco'] = sanitize_input($data['endereco']);
    }
    if (isset($data['tipo']) && in_array($data['tipo'], ['oficial', 'reuniao_gabinete', 'evento_publico', 'visita', 'outro'])) {
        $updates[] = "tipo = :tipo";
        $params[':tipo'] = sanitize_input($data['tipo']);
    }
    if (isset($data['status']) && in_array($data['status'], ['agendada', 'realizada', 'cancelada'])) {
        $updates[] = "status = :status";
        $params[':status'] = sanitize_input($data['status']);
    }
    if (isset($data['pauta'])) {
        $updates[] = "pauta = :pauta";
        $params[':pauta'] = sanitize_input($data['pauta']);
    }
    if (isset($data['observacoes'])) {
        $updates[] = "observacoes = :observacoes";
        $params[':observacoes'] = sanitize_input($data['observacoes']);
    }
    if (isset($data['publico'])) {
        $updates[] = "publico = :publico";
        $params[':publico'] = intval($data['publico']);
    }
    if (isset($data['link_meet'])) {
        $updates[] = "link_meet = :link_meet";
        $params[':link_meet'] = sanitize_input($data['link_meet']);
    }
    if (isset($data['participantes']) && is_array($data['participantes'])) {
        $updates[] = "participantes = :participantes";
        $params[':participantes'] = json_encode($data['participantes']);
    }
    if (isset($data['anexos']) && is_array($data['anexos'])) {
        $updates[] = "anexos = :anexos";
        $params[':anexos'] = json_encode($data['anexos']);
    }
    
    if (empty($updates)) {
        sendErrorResponse('Nenhum campo para atualizar', 400);
    }
    
    $updates[] = "updated_at = NOW()";
    $sql = "UPDATE reunioes SET " . implode(', ', $updates) . " WHERE id = :id AND cliente_id = :cliente_id";
    
    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    
    logAction($pdo, getCurrentUserId(), 'update', 'reuniao', ['reuniao_id' => $id]);
    
    sendSuccessResponse(null, 'Reunião atualizada com sucesso');
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
    
    $stmt = $pdo->prepare("SELECT id, titulo FROM reunioes WHERE id = :id AND cliente_id = :cliente_id");
    $stmt->execute([':id' => $id, ':cliente_id' => $cliente_id]);
    $reuniao = $stmt->fetch();
    
    if (!$reuniao) {
        sendErrorResponse('Reunião não encontrada', 404);
    }
    
    $stmt = $pdo->prepare("UPDATE reunioes SET status = 'cancelada', updated_at = NOW() WHERE id = :id");
    $stmt->execute([':id' => $id]);
    
    logAction($pdo, getCurrentUserId(), 'delete', 'reuniao', [
        'reuniao_id' => $id,
        'titulo' => $reuniao['titulo']
    ]);
    
    sendSuccessResponse(null, 'Reunião cancelada com sucesso');
}

?>

