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
        case 'get':
            $stmt = $pdo->prepare("
                SELECT id, nome, email, nivel, telefone, endereco, bairro, foto, partido, 
                       cargo, gabinete_id, cliente_id, ultimo_acesso, created_at, updated_at
                FROM usuarios 
                WHERE id = :id AND cliente_id = :cliente_id
            ");
            $stmt->execute([
                ':id' => $usuario_id,
                ':cliente_id' => $cliente_id
            ]);
            $usuario = $stmt->fetch();
            
            if (!$usuario) {
                sendErrorResponse('Usuário não encontrado', 404);
            }
            
            // Buscar estatísticas do usuário
            $stats = [];
            
            // Projetos
            $stmt = $pdo->prepare("SELECT COUNT(*) as total FROM projetos WHERE autor_id = :usuario_id");
            $stmt->execute([':usuario_id' => $usuario_id]);
            $stats['projetos'] = intval($stmt->fetch()['total']);
            
            // Demandas
            $stmt = $pdo->prepare("SELECT COUNT(*) as total FROM demandas WHERE cidadao_id = :usuario_id");
            $stmt->execute([':usuario_id' => $usuario_id]);
            $stats['demandas'] = intval($stmt->fetch()['total']);
            
            // Reuniões
            $stmt = $pdo->prepare("SELECT COUNT(*) as total FROM reunioes WHERE organizador_id = :usuario_id");
            $stmt->execute([':usuario_id' => $usuario_id]);
            $stats['reunioes'] = intval($stmt->fetch()['total']);
            
            // Tarefas
            $stmt = $pdo->prepare("SELECT COUNT(*) as total FROM tarefas WHERE assessor_id = :usuario_id");
            $stmt->execute([':usuario_id' => $usuario_id]);
            $stats['tarefas'] = intval($stmt->fetch()['total']);
            
            $usuario['estatisticas'] = $stats;
            
            sendSuccessResponse($usuario);
            break;
            
        default:
            sendErrorResponse('Ação não reconhecida', 400);
    }
}

function handlePost($action) {
    sendErrorResponse('Método POST não suportado', 405);
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
    
    $usuario_id = getCurrentUserId();
    $cliente_id = getCurrentClienteId();
    
    // Verificar se usuário existe
    $stmt = $pdo->prepare("SELECT id FROM usuarios WHERE id = :id AND cliente_id = :cliente_id");
    $stmt->execute([':id' => $usuario_id, ':cliente_id' => $cliente_id]);
    if (!$stmt->fetch()) {
        sendErrorResponse('Usuário não encontrado', 404);
    }
    
    $updates = [];
    $params = [':id' => $usuario_id];
    
    if (isset($data['nome'])) {
        $updates[] = "nome = :nome";
        $params[':nome'] = sanitize_input($data['nome']);
    }
    if (isset($data['email'])) {
        // Verificar se email já existe em outro usuário
        $stmt = $pdo->prepare("SELECT id FROM usuarios WHERE email = :email AND id != :id");
        $stmt->execute([':email' => $data['email'], ':id' => $usuario_id]);
        if ($stmt->fetch()) {
            sendErrorResponse('Email já cadastrado', 400);
        }
        $updates[] = "email = :email";
        $params[':email'] = sanitize_input($data['email']);
    }
    if (isset($data['telefone'])) {
        $updates[] = "telefone = :telefone";
        $params[':telefone'] = sanitize_input($data['telefone']);
    }
    if (isset($data['endereco'])) {
        $updates[] = "endereco = :endereco";
        $params[':endereco'] = sanitize_input($data['endereco']);
    }
    if (isset($data['bairro'])) {
        $updates[] = "bairro = :bairro";
        $params[':bairro'] = sanitize_input($data['bairro']);
    }
    if (isset($data['foto'])) {
        $updates[] = "foto = :foto";
        $params[':foto'] = sanitize_input($data['foto']);
    }
    if (isset($data['partido'])) {
        $updates[] = "partido = :partido";
        $params[':partido'] = sanitize_input($data['partido']);
    }
    if (isset($data['cargo'])) {
        $updates[] = "cargo = :cargo";
        $params[':cargo'] = sanitize_input($data['cargo']);
    }
    if (isset($data['senha']) && !empty($data['senha'])) {
        if (strlen($data['senha']) < 6) {
            sendErrorResponse('Senha deve ter no mínimo 6 caracteres', 400);
        }
        $updates[] = "senha = :senha";
        $params[':senha'] = password_hash($data['senha'], PASSWORD_DEFAULT);
    }
    
    if (empty($updates)) {
        sendErrorResponse('Nenhum campo para atualizar', 400);
    }
    
    $updates[] = "updated_at = NOW()";
    $sql = "UPDATE usuarios SET " . implode(', ', $updates) . " WHERE id = :id";
    
    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    
    logAction($pdo, getCurrentUserId(), 'update', 'perfil', ['usuario_id' => $usuario_id]);
    
    sendSuccessResponse(null, 'Perfil atualizado com sucesso');
}

function handleDelete($action) {
    sendErrorResponse('Método DELETE não suportado para perfil', 405);
}

?>

