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
    $gabinete_id = getCurrentGabineteId();
    
    switch ($action) {
        case 'list':
            $tipo = sanitize_input($_GET['tipo'] ?? '');
            $periodo = sanitize_input($_GET['periodo'] ?? '');
            
            $sql = "
                SELECT * FROM indicadores 
                WHERE gabinete_id = :gabinete_id
            ";
            $params = [':gabinete_id' => $gabinete_id];
            
            if (!empty($tipo)) {
                $sql .= " AND tipo = :tipo";
                $params[':tipo'] = $tipo;
            }
            if (!empty($periodo)) {
                $sql .= " AND periodo = :periodo";
                $params[':periodo'] = $periodo;
            }
            
            $sql .= " ORDER BY data_referencia DESC, created_at DESC";
            
            $stmt = $pdo->prepare($sql);
            $stmt->execute($params);
            $indicadores = $stmt->fetchAll();
            
            foreach ($indicadores as &$indicador) {
                if (!empty($indicador['detalhes'])) {
                    $indicador['detalhes'] = json_decode($indicador['detalhes'], true) ?: [];
                }
            }
            
            sendSuccessResponse($indicadores);
            break;
            
        case 'get':
            $id = intval($_GET['id'] ?? 0);
            if (!$id) {
                sendErrorResponse('ID não fornecido', 400);
            }
            
            $stmt = $pdo->prepare("
                SELECT * FROM indicadores 
                WHERE id = :id AND gabinete_id = :gabinete_id
            ");
            $stmt->execute([
                ':id' => $id,
                ':gabinete_id' => $gabinete_id
            ]);
            $indicador = $stmt->fetch();
            
            if (!$indicador) {
                sendErrorResponse('Indicador não encontrado', 404);
            }
            
            if (!empty($indicador['detalhes'])) {
                $indicador['detalhes'] = json_decode($indicador['detalhes'], true) ?: [];
            }
            
            sendSuccessResponse($indicador);
            break;
            
        case 'dashboard':
            // Retornar indicadores consolidados para dashboard
            $cliente_id = getCurrentClienteId();
            $gabinete_id = getCurrentGabineteId();
            
            // Projetos
            $stmt = $pdo->prepare("
                SELECT COUNT(*) as total,
                       SUM(CASE WHEN status = 'aprovado' THEN 1 ELSE 0 END) as aprovados,
                       SUM(CASE WHEN status = 'em_tramitacao' THEN 1 ELSE 0 END) as em_tramitacao
                FROM projetos 
                WHERE cliente_id = :cliente_id
            ");
            $stmt->execute([':cliente_id' => $cliente_id]);
            $projetos = $stmt->fetch();
            
            // Demandas
            $stmt = $pdo->prepare("
                SELECT COUNT(*) as total,
                       SUM(CASE WHEN status = 'resolvido' THEN 1 ELSE 0 END) as resolvidas,
                       SUM(CASE WHEN status = 'pendente' THEN 1 ELSE 0 END) as pendentes
                FROM demandas 
                WHERE cliente_id = :cliente_id
            ");
            $stmt->execute([':cliente_id' => $cliente_id]);
            $demandas = $stmt->fetch();
            
            // Reuniões
            $stmt = $pdo->prepare("
                SELECT COUNT(*) as total,
                       SUM(CASE WHEN status = 'realizada' THEN 1 ELSE 0 END) as realizadas
                FROM reunioes 
                WHERE cliente_id = :cliente_id
            ");
            $stmt->execute([':cliente_id' => $cliente_id]);
            $reunioes = $stmt->fetch();
            
            // Tarefas
            $stmt = $pdo->prepare("
                SELECT COUNT(*) as total,
                       SUM(CASE WHEN status = 'concluida' THEN 1 ELSE 0 END) as concluidas,
                       SUM(CASE WHEN status = 'em_andamento' THEN 1 ELSE 0 END) as em_andamento
                FROM tarefas 
                WHERE cliente_id = :cliente_id
            ");
            $stmt->execute([':cliente_id' => $cliente_id]);
            $tarefas = $stmt->fetch();
            
            sendSuccessResponse([
                'projetos' => $projetos,
                'demandas' => $demandas,
                'reunioes' => $reunioes,
                'tarefas' => $tarefas
            ]);
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
    
    $tipo = sanitize_input($data['tipo'] ?? '');
    $valor = isset($data['valor']) ? intval($data['valor']) : 0;
    $periodo = sanitize_input($data['periodo'] ?? 'mensal');
    $data_referencia = sanitize_input($data['data_referencia'] ?? date('Y-m-d'));
    $gabinete_id = getCurrentGabineteId();
    $meta = isset($data['meta']) ? intval($data['meta']) : null;
    $detalhes = isset($data['detalhes']) && is_array($data['detalhes']) ? $data['detalhes'] : [];
    
    if (empty($tipo)) {
        sendErrorResponse('Tipo é obrigatório', 400);
    }
    
    if (!in_array($tipo, ['projetos_apresentados', 'demandas_atendidas', 'reunioes_realizadas', 
                          'indicacoes_protocoladas', 'cidadaos_atendidos', 'acessos_app', 
                          'noticias_publicadas', 'tarefas_concluidas'])) {
        sendErrorResponse('Tipo inválido', 400);
    }
    
    if (!in_array($periodo, ['diario', 'semanal', 'mensal', 'anual'])) {
        sendErrorResponse('Período inválido', 400);
    }
    
    $stmt = $pdo->prepare("
        INSERT INTO indicadores (tipo, valor, periodo, data_referencia, gabinete_id, meta, detalhes, created_at, updated_at)
        VALUES (:tipo, :valor, :periodo, :data_referencia, :gabinete_id, :meta, :detalhes, NOW(), NOW())
    ");
    
    $stmt->execute([
        ':tipo' => $tipo,
        ':valor' => $valor,
        ':periodo' => $periodo,
        ':data_referencia' => $data_referencia,
        ':gabinete_id' => $gabinete_id,
        ':meta' => $meta,
        ':detalhes' => json_encode($detalhes)
    ]);
    
    $id = $pdo->lastInsertId();
    
    logAction($pdo, getCurrentUserId(), 'create', 'indicador', [
        'indicador_id' => $id,
        'tipo' => $tipo
    ]);
    
    sendSuccessResponse(['id' => $id], 'Indicador criado com sucesso');
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
    
    $gabinete_id = getCurrentGabineteId();
    
    $stmt = $pdo->prepare("SELECT id FROM indicadores WHERE id = :id AND gabinete_id = :gabinete_id");
    $stmt->execute([':id' => $id, ':gabinete_id' => $gabinete_id]);
    if (!$stmt->fetch()) {
        sendErrorResponse('Indicador não encontrado', 404);
    }
    
    $updates = [];
    $params = [':id' => $id, ':gabinete_id' => $gabinete_id];
    
    if (isset($data['tipo']) && in_array($data['tipo'], ['projetos_apresentados', 'demandas_atendidas', 'reunioes_realizadas', 
                                                          'indicacoes_protocoladas', 'cidadaos_atendidos', 'acessos_app', 
                                                          'noticias_publicadas', 'tarefas_concluidas'])) {
        $updates[] = "tipo = :tipo";
        $params[':tipo'] = sanitize_input($data['tipo']);
    }
    if (isset($data['valor'])) {
        $updates[] = "valor = :valor";
        $params[':valor'] = intval($data['valor']);
    }
    if (isset($data['periodo']) && in_array($data['periodo'], ['diario', 'semanal', 'mensal', 'anual'])) {
        $updates[] = "periodo = :periodo";
        $params[':periodo'] = sanitize_input($data['periodo']);
    }
    if (isset($data['data_referencia'])) {
        $updates[] = "data_referencia = :data_referencia";
        $params[':data_referencia'] = sanitize_input($data['data_referencia']);
    }
    if (isset($data['meta'])) {
        $updates[] = "meta = :meta";
        $params[':meta'] = intval($data['meta']) ?: null;
    }
    if (isset($data['detalhes']) && is_array($data['detalhes'])) {
        $updates[] = "detalhes = :detalhes";
        $params[':detalhes'] = json_encode($data['detalhes']);
    }
    
    if (empty($updates)) {
        sendErrorResponse('Nenhum campo para atualizar', 400);
    }
    
    $updates[] = "updated_at = NOW()";
    $sql = "UPDATE indicadores SET " . implode(', ', $updates) . " WHERE id = :id AND gabinete_id = :gabinete_id";
    
    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    
    logAction($pdo, getCurrentUserId(), 'update', 'indicador', ['indicador_id' => $id]);
    
    sendSuccessResponse(null, 'Indicador atualizado com sucesso');
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
    
    $gabinete_id = getCurrentGabineteId();
    
    $stmt = $pdo->prepare("SELECT id, tipo FROM indicadores WHERE id = :id AND gabinete_id = :gabinete_id");
    $stmt->execute([':id' => $id, ':gabinete_id' => $gabinete_id]);
    $indicador = $stmt->fetch();
    
    if (!$indicador) {
        sendErrorResponse('Indicador não encontrado', 404);
    }
    
    $stmt = $pdo->prepare("DELETE FROM indicadores WHERE id = :id");
    $stmt->execute([':id' => $id]);
    
    logAction($pdo, getCurrentUserId(), 'delete', 'indicador', [
        'indicador_id' => $id,
        'tipo' => $indicador['tipo']
    ]);
    
    sendSuccessResponse(null, 'Indicador removido com sucesso');
}

?>

