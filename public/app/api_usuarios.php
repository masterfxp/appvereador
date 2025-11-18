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
            $cliente_id = getCurrentClienteId();
            $stmt = $pdo->prepare("
                SELECT id, nome, email, nivel, ativo, telefone, endereco, bairro, 
                       foto, partido, cargo, gabinete_id, cliente_id, ultimo_acesso, 
                       created_at, updated_at
                FROM usuarios 
                WHERE cliente_id = :cliente_id
                ORDER BY created_at DESC
            ");
            $stmt->execute([':cliente_id' => $cliente_id]);
            $usuarios = $stmt->fetchAll();
            
            // Remover senha e tokens
            foreach ($usuarios as &$usuario) {
                unset($usuario['senha']);
                unset($usuario['reset_password_token']);
                unset($usuario['reset_password_expires']);
            }
            
            sendSuccessResponse($usuarios);
            break;
            
        case 'get':
            $id = intval($_GET['id'] ?? 0);
            if (!$id) {
                sendErrorResponse('ID não fornecido', 400);
            }
            
            $stmt = $pdo->prepare("
                SELECT id, nome, email, nivel, ativo, telefone, endereco, bairro, 
                       foto, partido, cargo, gabinete_id, cliente_id, ultimo_acesso, 
                       created_at, updated_at
                FROM usuarios 
                WHERE id = :id AND cliente_id = :cliente_id
            ");
            $stmt->execute([
                ':id' => $id,
                ':cliente_id' => getCurrentClienteId()
            ]);
            $usuario = $stmt->fetch();
            
            if (!$usuario) {
                sendErrorResponse('Usuário não encontrado', 404);
            }
            
            sendSuccessResponse($usuario);
            break;
            
        case 'search':
            $termo = sanitize_input($_GET['q'] ?? '');
            if (empty($termo)) {
                sendErrorResponse('Termo de busca não fornecido', 400);
            }
            
            $cliente_id = getCurrentClienteId();
            $stmt = $pdo->prepare("
                SELECT id, nome, email, nivel, ativo, telefone, foto, partido, cargo
                FROM usuarios 
                WHERE cliente_id = :cliente_id 
                AND (nome LIKE :termo OR email LIKE :termo)
                ORDER BY nome
                LIMIT 50
            ");
            $termoLike = "%{$termo}%";
            $stmt->execute([
                ':cliente_id' => $cliente_id,
                ':termo' => $termoLike
            ]);
            $usuarios = $stmt->fetchAll();
            
            sendSuccessResponse($usuarios);
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
    $senha = $data['senha'] ?? '';
    $nivel = sanitize_input($data['nivel'] ?? 'assessor');
    $telefone = sanitize_input($data['telefone'] ?? '');
    $endereco = sanitize_input($data['endereco'] ?? '');
    $bairro = sanitize_input($data['bairro'] ?? '');
    $partido = sanitize_input($data['partido'] ?? '');
    $cargo = sanitize_input($data['cargo'] ?? '');
    $gabinete_id = intval($data['gabinete_id'] ?? 0);
    $cliente_id = getCurrentClienteId();
    
    if (empty($nome) || empty($email) || empty($senha)) {
        sendErrorResponse('Nome, email e senha são obrigatórios', 400);
    }
    
    if (!in_array($nivel, ['administrador', 'vereador', 'assessor'])) {
        sendErrorResponse('Nível inválido', 400);
    }
    
    if (strlen($senha) < 6) {
        sendErrorResponse('Senha deve ter no mínimo 6 caracteres', 400);
    }
    
    // Verificar se email já existe
    $stmt = $pdo->prepare("SELECT id FROM usuarios WHERE email = :email");
    $stmt->execute([':email' => $email]);
    if ($stmt->fetch()) {
        sendErrorResponse('Email já cadastrado', 400);
    }
    
    // Hash da senha
    $senhaHash = password_hash($senha, PASSWORD_DEFAULT);
    
    $stmt = $pdo->prepare("
        INSERT INTO usuarios (nome, email, senha, nivel, telefone, endereco, bairro, 
                             partido, cargo, gabinete_id, cliente_id, ativo, created_at, updated_at)
        VALUES (:nome, :email, :senha, :nivel, :telefone, :endereco, :bairro, 
                :partido, :cargo, :gabinete_id, :cliente_id, 1, NOW(), NOW())
    ");
    
    $stmt->execute([
        ':nome' => $nome,
        ':email' => $email,
        ':senha' => $senhaHash,
        ':nivel' => $nivel,
        ':telefone' => $telefone,
        ':endereco' => $endereco,
        ':bairro' => $bairro,
        ':partido' => $partido,
        ':cargo' => $cargo,
        ':gabinete_id' => $gabinete_id ?: null,
        ':cliente_id' => $cliente_id
    ]);
    
    $id = $pdo->lastInsertId();
    
    logAction($pdo, getCurrentUserId(), 'create', 'usuario', [
        'usuario_id' => $id,
        'nome' => $nome,
        'email' => $email
    ]);
    
    sendSuccessResponse(['id' => $id], 'Usuário criado com sucesso');
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
    
    // Verificar se usuário existe e pertence ao cliente
    $stmt = $pdo->prepare("SELECT id FROM usuarios WHERE id = :id AND cliente_id = :cliente_id");
    $stmt->execute([':id' => $id, ':cliente_id' => $cliente_id]);
    if (!$stmt->fetch()) {
        sendErrorResponse('Usuário não encontrado', 404);
    }
    
    $nome = sanitize_input($data['nome'] ?? '');
    $email = sanitize_input($data['email'] ?? '');
    $nivel = sanitize_input($data['nivel'] ?? '');
    $telefone = sanitize_input($data['telefone'] ?? '');
    $endereco = sanitize_input($data['endereco'] ?? '');
    $bairro = sanitize_input($data['bairro'] ?? '');
    $partido = sanitize_input($data['partido'] ?? '');
    $cargo = sanitize_input($data['cargo'] ?? '');
    $gabinete_id = intval($data['gabinete_id'] ?? 0);
    $ativo = isset($data['ativo']) ? intval($data['ativo']) : null;
    
    $updates = [];
    $params = [':id' => $id];
    
    if (!empty($nome)) {
        $updates[] = "nome = :nome";
        $params[':nome'] = $nome;
    }
    if (!empty($email)) {
        // Verificar se email já existe em outro usuário
        $stmt = $pdo->prepare("SELECT id FROM usuarios WHERE email = :email AND id != :id");
        $stmt->execute([':email' => $email, ':id' => $id]);
        if ($stmt->fetch()) {
            sendErrorResponse('Email já cadastrado', 400);
        }
        $updates[] = "email = :email";
        $params[':email'] = $email;
    }
    if (!empty($nivel) && in_array($nivel, ['administrador', 'vereador', 'assessor'])) {
        $updates[] = "nivel = :nivel";
        $params[':nivel'] = $nivel;
    }
    if (isset($data['telefone'])) {
        $updates[] = "telefone = :telefone";
        $params[':telefone'] = $telefone;
    }
    if (isset($data['endereco'])) {
        $updates[] = "endereco = :endereco";
        $params[':endereco'] = $endereco;
    }
    if (isset($data['bairro'])) {
        $updates[] = "bairro = :bairro";
        $params[':bairro'] = $bairro;
    }
    if (isset($data['partido'])) {
        $updates[] = "partido = :partido";
        $params[':partido'] = $partido;
    }
    if (isset($data['cargo'])) {
        $updates[] = "cargo = :cargo";
        $params[':cargo'] = $cargo;
    }
    if (isset($data['gabinete_id'])) {
        $updates[] = "gabinete_id = :gabinete_id";
        $params[':gabinete_id'] = $gabinete_id ?: null;
    }
    if ($ativo !== null) {
        $updates[] = "ativo = :ativo";
        $params[':ativo'] = $ativo;
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
    $sql = "UPDATE usuarios SET " . implode(', ', $updates) . " WHERE id = :id AND cliente_id = :cliente_id";
    $params[':cliente_id'] = $cliente_id;
    
    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    
    logAction($pdo, getCurrentUserId(), 'update', 'usuario', ['usuario_id' => $id]);
    
    sendSuccessResponse(null, 'Usuário atualizado com sucesso');
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
    
    // Verificar se usuário existe
    $stmt = $pdo->prepare("SELECT id, nome FROM usuarios WHERE id = :id AND cliente_id = :cliente_id");
    $stmt->execute([':id' => $id, ':cliente_id' => $cliente_id]);
    $usuario = $stmt->fetch();
    
    if (!$usuario) {
        sendErrorResponse('Usuário não encontrado', 404);
    }
    
    // Soft delete - marcar como inativo ao invés de deletar
    $stmt = $pdo->prepare("UPDATE usuarios SET ativo = 0, updated_at = NOW() WHERE id = :id");
    $stmt->execute([':id' => $id]);
    
    logAction($pdo, getCurrentUserId(), 'delete', 'usuario', [
        'usuario_id' => $id,
        'nome' => $usuario['nome']
    ]);
    
    sendSuccessResponse(null, 'Usuário removido com sucesso');
}

?>

