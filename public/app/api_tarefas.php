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
            $assessor_id = isset($_GET['assessor_id']) ? intval($_GET['assessor_id']) : 0;
            
            $sql = "
                SELECT t.*, u1.nome as assessor_nome, u2.nome as criador_nome
                FROM tarefas t
                LEFT JOIN usuarios u1 ON t.assessor_id = u1.id
                LEFT JOIN usuarios u2 ON t.criador_id = u2.id
                WHERE t.cliente_id = :cliente_id
            ";
            $params = [':cliente_id' => $cliente_id];
            
            if (!empty($status)) {
                $sql .= " AND t.status = :status";
                $params[':status'] = $status;
            }
            if (!empty($prioridade)) {
                $sql .= " AND t.prioridade = :prioridade";
                $params[':prioridade'] = $prioridade;
            }
            if ($assessor_id > 0) {
                $sql .= " AND t.assessor_id = :assessor_id";
                $params[':assessor_id'] = $assessor_id;
            }
            
            $sql .= " ORDER BY t.prazo ASC, t.created_at DESC";
            
            $stmt = $pdo->prepare($sql);
            $stmt->execute($params);
            $tarefas = $stmt->fetchAll();
            
            foreach ($tarefas as &$tarefa) {
                if (!empty($tarefa['tags'])) {
                    $tarefa['tags'] = json_decode($tarefa['tags'], true) ?: [];
                }
                if (!empty($tarefa['anexos'])) {
                    $tarefa['anexos'] = json_decode($tarefa['anexos'], true) ?: [];
                }
                if (!empty($tarefa['dependencias'])) {
                    $tarefa['dependencias'] = json_decode($tarefa['dependencias'], true) ?: [];
                }
            }
            
            sendSuccessResponse($tarefas);
            break;
            
        case 'get':
            $id = intval($_GET['id'] ?? 0);
            if (!$id) {
                sendErrorResponse('ID não fornecido', 400);
            }
            
            $stmt = $pdo->prepare("
                SELECT t.*, u1.nome as assessor_nome, u2.nome as criador_nome
                FROM tarefas t
                LEFT JOIN usuarios u1 ON t.assessor_id = u1.id
                LEFT JOIN usuarios u2 ON t.criador_id = u2.id
                WHERE t.id = :id AND t.cliente_id = :cliente_id
            ");
            $stmt->execute([
                ':id' => $id,
                ':cliente_id' => $cliente_id
            ]);
            $tarefa = $stmt->fetch();
            
            if (!$tarefa) {
                sendErrorResponse('Tarefa não encontrada', 404);
            }
            
            if (!empty($tarefa['tags'])) {
                $tarefa['tags'] = json_decode($tarefa['tags'], true) ?: [];
            }
            if (!empty($tarefa['anexos'])) {
                $tarefa['anexos'] = json_decode($tarefa['anexos'], true) ?: [];
            }
            if (!empty($tarefa['dependencias'])) {
                $tarefa['dependencias'] = json_decode($tarefa['dependencias'], true) ?: [];
            }
            
            sendSuccessResponse($tarefa);
            break;
            
        case 'search':
            $termo = sanitize_input($_GET['q'] ?? '');
            if (empty($termo)) {
                sendErrorResponse('Termo de busca não fornecido', 400);
            }
            
            $stmt = $pdo->prepare("
                SELECT t.*, u1.nome as assessor_nome
                FROM tarefas t
                LEFT JOIN usuarios u1 ON t.assessor_id = u1.id
                WHERE t.cliente_id = :cliente_id 
                AND (t.titulo LIKE :termo OR t.descricao LIKE :termo)
                ORDER BY t.prazo ASC
                LIMIT 50
            ");
            $termoLike = "%{$termo}%";
            $stmt->execute([
                ':cliente_id' => $cliente_id,
                ':termo' => $termoLike
            ]);
            $tarefas = $stmt->fetchAll();
            
            sendSuccessResponse($tarefas);
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
    $status = sanitize_input($data['status'] ?? 'em_andamento');
    $prioridade = sanitize_input($data['prioridade'] ?? 'media');
    $assessor_id = isset($data['assessor_id']) ? intval($data['assessor_id']) : getCurrentUserId();
    $gabinete_id = getCurrentGabineteId();
    $cliente_id = getCurrentClienteId();
    $criador_id = getCurrentUserId();
    $prazo = sanitize_input($data['prazo'] ?? '');
    $categoria = sanitize_input($data['categoria'] ?? '');
    $observacoes = sanitize_input($data['observacoes'] ?? '');
    $progresso = isset($data['progresso']) ? intval($data['progresso']) : 0;
    $tempo_estimado = isset($data['tempo_estimado']) ? intval($data['tempo_estimado']) : null;
    $tags = isset($data['tags']) && is_array($data['tags']) ? $data['tags'] : [];
    $anexos = isset($data['anexos']) && is_array($data['anexos']) ? $data['anexos'] : [];
    $dependencias = isset($data['dependencias']) && is_array($data['dependencias']) ? $data['dependencias'] : [];
    
    if (empty($titulo)) {
        sendErrorResponse('Título é obrigatório', 400);
    }
    
    if (!in_array($status, ['em_andamento', 'concluida'])) {
        sendErrorResponse('Status inválido', 400);
    }
    
    if (!in_array($prioridade, ['baixa', 'media', 'alta', 'urgente'])) {
        sendErrorResponse('Prioridade inválida', 400);
    }
    
    if ($progresso < 0 || $progresso > 100) {
        sendErrorResponse('Progresso deve estar entre 0 e 100', 400);
    }
    
    $stmt = $pdo->prepare("
        INSERT INTO tarefas (titulo, descricao, status, prioridade, assessor_id, gabinete_id, 
                            cliente_id, criador_id, prazo, categoria, observacoes, progresso, 
                            tempo_estimado, tags, anexos, dependencias, created_at, updated_at)
        VALUES (:titulo, :descricao, :status, :prioridade, :assessor_id, :gabinete_id, 
                :cliente_id, :criador_id, :prazo, :categoria, :observacoes, :progresso, 
                :tempo_estimado, :tags, :anexos, :dependencias, NOW(), NOW())
    ");
    
    $stmt->execute([
        ':titulo' => $titulo,
        ':descricao' => $descricao,
        ':status' => $status,
        ':prioridade' => $prioridade,
        ':assessor_id' => $assessor_id,
        ':gabinete_id' => $gabinete_id,
        ':cliente_id' => $cliente_id,
        ':criador_id' => $criador_id,
        ':prazo' => $prazo ?: null,
        ':categoria' => $categoria,
        ':observacoes' => $observacoes,
        ':progresso' => $progresso,
        ':tempo_estimado' => $tempo_estimado,
        ':tags' => json_encode($tags),
        ':anexos' => json_encode($anexos),
        ':dependencias' => json_encode($dependencias)
    ]);
    
    $id = $pdo->lastInsertId();
    
    logAction($pdo, getCurrentUserId(), 'create', 'tarefa', [
        'tarefa_id' => $id,
        'titulo' => $titulo
    ]);
    
    sendSuccessResponse(['id' => $id], 'Tarefa criada com sucesso');
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
    
    $stmt = $pdo->prepare("SELECT id FROM tarefas WHERE id = :id AND cliente_id = :cliente_id");
    $stmt->execute([':id' => $id, ':cliente_id' => $cliente_id]);
    if (!$stmt->fetch()) {
        sendErrorResponse('Tarefa não encontrada', 404);
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
    if (isset($data['status']) && in_array($data['status'], ['em_andamento', 'concluida'])) {
        $updates[] = "status = :status";
        $params[':status'] = sanitize_input($data['status']);
        if ($data['status'] === 'concluida' && !isset($data['data_conclusao'])) {
            $updates[] = "data_conclusao = NOW()";
        }
    }
    if (isset($data['prioridade']) && in_array($data['prioridade'], ['baixa', 'media', 'alta', 'urgente'])) {
        $updates[] = "prioridade = :prioridade";
        $params[':prioridade'] = sanitize_input($data['prioridade']);
    }
    if (isset($data['assessor_id'])) {
        $updates[] = "assessor_id = :assessor_id";
        $params[':assessor_id'] = intval($data['assessor_id']);
    }
    if (isset($data['prazo'])) {
        $updates[] = "prazo = :prazo";
        $params[':prazo'] = sanitize_input($data['prazo']) ?: null;
    }
    if (isset($data['categoria'])) {
        $updates[] = "categoria = :categoria";
        $params[':categoria'] = sanitize_input($data['categoria']);
    }
    if (isset($data['observacoes'])) {
        $updates[] = "observacoes = :observacoes";
        $params[':observacoes'] = sanitize_input($data['observacoes']);
    }
    if (isset($data['progresso'])) {
        $progresso = intval($data['progresso']);
        if ($progresso < 0 || $progresso > 100) {
            sendErrorResponse('Progresso deve estar entre 0 e 100', 400);
        }
        $updates[] = "progresso = :progresso";
        $params[':progresso'] = $progresso;
    }
    if (isset($data['tempo_estimado'])) {
        $updates[] = "tempo_estimado = :tempo_estimado";
        $params[':tempo_estimado'] = intval($data['tempo_estimado']) ?: null;
    }
    if (isset($data['tempo_realizado'])) {
        $updates[] = "tempo_realizado = :tempo_realizado";
        $params[':tempo_realizado'] = intval($data['tempo_realizado']) ?: null;
    }
    if (isset($data['tags']) && is_array($data['tags'])) {
        $updates[] = "tags = :tags";
        $params[':tags'] = json_encode($data['tags']);
    }
    if (isset($data['anexos']) && is_array($data['anexos'])) {
        $updates[] = "anexos = :anexos";
        $params[':anexos'] = json_encode($data['anexos']);
    }
    if (isset($data['dependencias']) && is_array($data['dependencias'])) {
        $updates[] = "dependencias = :dependencias";
        $params[':dependencias'] = json_encode($data['dependencias']);
    }
    
    if (empty($updates)) {
        sendErrorResponse('Nenhum campo para atualizar', 400);
    }
    
    $updates[] = "updated_at = NOW()";
    $sql = "UPDATE tarefas SET " . implode(', ', $updates) . " WHERE id = :id AND cliente_id = :cliente_id";
    
    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    
    logAction($pdo, getCurrentUserId(), 'update', 'tarefa', ['tarefa_id' => $id]);
    
    sendSuccessResponse(null, 'Tarefa atualizada com sucesso');
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
    
    $stmt = $pdo->prepare("SELECT id, titulo FROM tarefas WHERE id = :id AND cliente_id = :cliente_id");
    $stmt->execute([':id' => $id, ':cliente_id' => $cliente_id]);
    $tarefa = $stmt->fetch();
    
    if (!$tarefa) {
        sendErrorResponse('Tarefa não encontrada', 404);
    }
    
    $stmt = $pdo->prepare("DELETE FROM tarefas WHERE id = :id");
    $stmt->execute([':id' => $id]);
    
    logAction($pdo, getCurrentUserId(), 'delete', 'tarefa', [
        'tarefa_id' => $id,
        'titulo' => $tarefa['titulo']
    ]);
    
    sendSuccessResponse(null, 'Tarefa removida com sucesso');
}

?>

