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
            
            $sql = "SELECT * FROM gabinetes WHERE 1=1";
            $params = [];
            
            if ($ativo !== null) {
                $sql .= " AND ativo = :ativo";
                $params[':ativo'] = $ativo;
            }
            
            $sql .= " ORDER BY created_at DESC";
            
            $stmt = $pdo->prepare($sql);
            $stmt->execute($params);
            $gabinetes = $stmt->fetchAll();
            
            foreach ($gabinetes as &$gabinete) {
                if (!empty($gabinete['cores'])) {
                    $gabinete['cores'] = json_decode($gabinete['cores'], true) ?: [];
                }
                if (!empty($gabinete['redes_sociais'])) {
                    $gabinete['redes_sociais'] = json_decode($gabinete['redes_sociais'], true) ?: [];
                }
                if (!empty($gabinete['configuracoes'])) {
                    $gabinete['configuracoes'] = json_decode($gabinete['configuracoes'], true) ?: [];
                }
                if (!empty($gabinete['metas'])) {
                    $gabinete['metas'] = json_decode($gabinete['metas'], true) ?: [];
                }
            }
            
            sendSuccessResponse($gabinetes);
            break;
            
        case 'get':
            $id = intval($_GET['id'] ?? 0);
            if (!$id) {
                sendErrorResponse('ID não fornecido', 400);
            }
            
            $stmt = $pdo->prepare("SELECT * FROM gabinetes WHERE id = :id");
            $stmt->execute([':id' => $id]);
            $gabinete = $stmt->fetch();
            
            if (!$gabinete) {
                sendErrorResponse('Gabinete não encontrado', 404);
            }
            
            if (!empty($gabinete['cores'])) {
                $gabinete['cores'] = json_decode($gabinete['cores'], true) ?: [];
            }
            if (!empty($gabinete['redes_sociais'])) {
                $gabinete['redes_sociais'] = json_decode($gabinete['redes_sociais'], true) ?: [];
            }
            if (!empty($gabinete['configuracoes'])) {
                $gabinete['configuracoes'] = json_decode($gabinete['configuracoes'], true) ?: [];
            }
            if (!empty($gabinete['metas'])) {
                $gabinete['metas'] = json_decode($gabinete['metas'], true) ?: [];
            }
            
            sendSuccessResponse($gabinete);
            break;
            
        case 'search':
            $termo = sanitize_input($_GET['q'] ?? '');
            if (empty($termo)) {
                sendErrorResponse('Termo de busca não fornecido', 400);
            }
            
            $stmt = $pdo->prepare("
                SELECT id, nome, vereador_nome, partido, municipio, estado, ativo
                FROM gabinetes 
                WHERE (nome LIKE :termo OR vereador_nome LIKE :termo OR municipio LIKE :termo)
                ORDER BY nome
                LIMIT 50
            ");
            $termoLike = "%{$termo}%";
            $stmt->execute([':termo' => $termoLike]);
            $gabinetes = $stmt->fetchAll();
            
            sendSuccessResponse($gabinetes);
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
    $vereador_nome = sanitize_input($data['vereador_nome'] ?? '');
    $partido = sanitize_input($data['partido'] ?? '');
    $telefone = sanitize_input($data['telefone'] ?? '');
    $email = sanitize_input($data['email'] ?? '');
    $endereco = sanitize_input($data['endereco'] ?? '');
    $municipio = sanitize_input($data['municipio'] ?? '');
    $estado = sanitize_input($data['estado'] ?? '');
    $cep = sanitize_input($data['cep'] ?? '');
    $logo = sanitize_input($data['logo'] ?? '');
    $foto_vereador = sanitize_input($data['foto_vereador'] ?? '');
    $biografia = sanitize_input($data['biografia'] ?? '');
    $plano_governo = sanitize_input($data['plano_governo'] ?? '');
    $cores = isset($data['cores']) && is_array($data['cores']) ? $data['cores'] : [];
    $redes_sociais = isset($data['redes_sociais']) && is_array($data['redes_sociais']) ? $data['redes_sociais'] : [];
    $configuracoes = isset($data['configuracoes']) && is_array($data['configuracoes']) ? $data['configuracoes'] : [];
    $metas = isset($data['metas']) && is_array($data['metas']) ? $data['metas'] : [];
    
    if (empty($nome) || empty($vereador_nome)) {
        sendErrorResponse('Nome e nome do vereador são obrigatórios', 400);
    }
    
    $stmt = $pdo->prepare("
        INSERT INTO gabinetes (nome, vereador_nome, partido, telefone, email, endereco, municipio, 
                              estado, cep, logo, foto_vereador, biografia, plano_governo, cores, 
                              redes_sociais, configuracoes, metas, ativo, created_at, updated_at)
        VALUES (:nome, :vereador_nome, :partido, :telefone, :email, :endereco, :municipio, 
                :estado, :cep, :logo, :foto_vereador, :biografia, :plano_governo, :cores, 
                :redes_sociais, :configuracoes, :metas, 1, NOW(), NOW())
    ");
    
    $stmt->execute([
        ':nome' => $nome,
        ':vereador_nome' => $vereador_nome,
        ':partido' => $partido,
        ':telefone' => $telefone,
        ':email' => $email,
        ':endereco' => $endereco,
        ':municipio' => $municipio,
        ':estado' => $estado,
        ':cep' => $cep,
        ':logo' => $logo,
        ':foto_vereador' => $foto_vereador,
        ':biografia' => $biografia,
        ':plano_governo' => $plano_governo,
        ':cores' => json_encode($cores),
        ':redes_sociais' => json_encode($redes_sociais),
        ':configuracoes' => json_encode($configuracoes),
        ':metas' => json_encode($metas)
    ]);
    
    $id = $pdo->lastInsertId();
    
    logAction($pdo, getCurrentUserId(), 'create', 'gabinete', [
        'gabinete_id' => $id,
        'nome' => $nome
    ]);
    
    sendSuccessResponse(['id' => $id], 'Gabinete criado com sucesso');
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
    
    $stmt = $pdo->prepare("SELECT id FROM gabinetes WHERE id = :id");
    $stmt->execute([':id' => $id]);
    if (!$stmt->fetch()) {
        sendErrorResponse('Gabinete não encontrado', 404);
    }
    
    $updates = [];
    $params = [':id' => $id];
    
    if (isset($data['nome'])) {
        $updates[] = "nome = :nome";
        $params[':nome'] = sanitize_input($data['nome']);
    }
    if (isset($data['vereador_nome'])) {
        $updates[] = "vereador_nome = :vereador_nome";
        $params[':vereador_nome'] = sanitize_input($data['vereador_nome']);
    }
    if (isset($data['partido'])) {
        $updates[] = "partido = :partido";
        $params[':partido'] = sanitize_input($data['partido']);
    }
    if (isset($data['telefone'])) {
        $updates[] = "telefone = :telefone";
        $params[':telefone'] = sanitize_input($data['telefone']);
    }
    if (isset($data['email'])) {
        $updates[] = "email = :email";
        $params[':email'] = sanitize_input($data['email']);
    }
    if (isset($data['endereco'])) {
        $updates[] = "endereco = :endereco";
        $params[':endereco'] = sanitize_input($data['endereco']);
    }
    if (isset($data['municipio'])) {
        $updates[] = "municipio = :municipio";
        $params[':municipio'] = sanitize_input($data['municipio']);
    }
    if (isset($data['estado'])) {
        $updates[] = "estado = :estado";
        $params[':estado'] = sanitize_input($data['estado']);
    }
    if (isset($data['cep'])) {
        $updates[] = "cep = :cep";
        $params[':cep'] = sanitize_input($data['cep']);
    }
    if (isset($data['logo'])) {
        $updates[] = "logo = :logo";
        $params[':logo'] = sanitize_input($data['logo']);
    }
    if (isset($data['foto_vereador'])) {
        $updates[] = "foto_vereador = :foto_vereador";
        $params[':foto_vereador'] = sanitize_input($data['foto_vereador']);
    }
    if (isset($data['biografia'])) {
        $updates[] = "biografia = :biografia";
        $params[':biografia'] = sanitize_input($data['biografia']);
    }
    if (isset($data['plano_governo'])) {
        $updates[] = "plano_governo = :plano_governo";
        $params[':plano_governo'] = sanitize_input($data['plano_governo']);
    }
    if (isset($data['cores']) && is_array($data['cores'])) {
        $updates[] = "cores = :cores";
        $params[':cores'] = json_encode($data['cores']);
    }
    if (isset($data['redes_sociais']) && is_array($data['redes_sociais'])) {
        $updates[] = "redes_sociais = :redes_sociais";
        $params[':redes_sociais'] = json_encode($data['redes_sociais']);
    }
    if (isset($data['configuracoes']) && is_array($data['configuracoes'])) {
        $updates[] = "configuracoes = :configuracoes";
        $params[':configuracoes'] = json_encode($data['configuracoes']);
    }
    if (isset($data['metas']) && is_array($data['metas'])) {
        $updates[] = "metas = :metas";
        $params[':metas'] = json_encode($data['metas']);
    }
    if (isset($data['ativo'])) {
        $updates[] = "ativo = :ativo";
        $params[':ativo'] = intval($data['ativo']);
    }
    
    if (empty($updates)) {
        sendErrorResponse('Nenhum campo para atualizar', 400);
    }
    
    $updates[] = "updated_at = NOW()";
    $sql = "UPDATE gabinetes SET " . implode(', ', $updates) . " WHERE id = :id";
    
    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    
    logAction($pdo, getCurrentUserId(), 'update', 'gabinete', ['gabinete_id' => $id]);
    
    sendSuccessResponse(null, 'Gabinete atualizado com sucesso');
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
    
    $stmt = $pdo->prepare("SELECT id, nome FROM gabinetes WHERE id = :id");
    $stmt->execute([':id' => $id]);
    $gabinete = $stmt->fetch();
    
    if (!$gabinete) {
        sendErrorResponse('Gabinete não encontrado', 404);
    }
    
    // Soft delete - marcar como inativo
    $stmt = $pdo->prepare("UPDATE gabinetes SET ativo = 0, updated_at = NOW() WHERE id = :id");
    $stmt->execute([':id' => $id]);
    
    logAction($pdo, getCurrentUserId(), 'delete', 'gabinete', [
        'gabinete_id' => $id,
        'nome' => $gabinete['nome']
    ]);
    
    sendSuccessResponse(null, 'Gabinete removido com sucesso');
}

?>

