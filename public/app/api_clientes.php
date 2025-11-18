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
            
            $sql = "SELECT * FROM clientes WHERE 1=1";
            $params = [];
            
            if ($ativo !== null) {
                $sql .= " AND ativo = :ativo";
                $params[':ativo'] = $ativo;
            }
            
            $sql .= " ORDER BY created_at DESC";
            
            $stmt = $pdo->prepare($sql);
            $stmt->execute($params);
            $clientes = $stmt->fetchAll();
            
            sendSuccessResponse($clientes);
            break;
            
        case 'get':
            $id = intval($_GET['id'] ?? 0);
            if (!$id) {
                sendErrorResponse('ID não fornecido', 400);
            }
            
            $stmt = $pdo->prepare("SELECT * FROM clientes WHERE id = :id");
            $stmt->execute([':id' => $id]);
            $cliente = $stmt->fetch();
            
            if (!$cliente) {
                sendErrorResponse('Cliente não encontrado', 404);
            }
            
            sendSuccessResponse($cliente);
            break;
            
        case 'search':
            $termo = sanitize_input($_GET['q'] ?? '');
            if (empty($termo)) {
                sendErrorResponse('Termo de busca não fornecido', 400);
            }
            
            $stmt = $pdo->prepare("
                SELECT id, nome, email, telefone, empresa, tipo, ativo
                FROM clientes 
                WHERE (nome LIKE :termo OR email LIKE :termo OR empresa LIKE :termo)
                ORDER BY nome
                LIMIT 50
            ");
            $termoLike = "%{$termo}%";
            $stmt->execute([':termo' => $termoLike]);
            $clientes = $stmt->fetchAll();
            
            sendSuccessResponse($clientes);
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
    $telefone = sanitize_input($data['telefone'] ?? '');
    $endereco = sanitize_input($data['endereco'] ?? '');
    $cidade = sanitize_input($data['cidade'] ?? '');
    $estado = sanitize_input($data['estado'] ?? '');
    $cep = sanitize_input($data['cep'] ?? '');
    $cnpj = sanitize_input($data['cnpj'] ?? '');
    $empresa = sanitize_input($data['empresa'] ?? '');
    $tipo = sanitize_input($data['tipo'] ?? 'gabinete');
    $tipo_contrato = sanitize_input($data['tipo_contrato'] ?? '');
    $data_inicio_contrato = sanitize_input($data['data_inicio_contrato'] ?? '');
    $data_fim_contrato = sanitize_input($data['data_fim_contrato'] ?? '');
    $limite_usuarios = isset($data['limite_usuarios']) ? intval($data['limite_usuarios']) : 10;
    $limite_entidades = isset($data['limite_entidades']) ? intval($data['limite_entidades']) : 5;
    $limite_projetos = isset($data['limite_projetos']) ? intval($data['limite_projetos']) : 50;
    $limite_espaco_mb = isset($data['limite_espaco_mb']) ? intval($data['limite_espaco_mb']) : 1000;
    
    if (empty($nome) || empty($email)) {
        sendErrorResponse('Nome e email são obrigatórios', 400);
    }
    
    if (!in_array($tipo, ['gabinete', 'empresa', 'organizacao'])) {
        sendErrorResponse('Tipo inválido', 400);
    }
    
    // Verificar se email já existe
    $stmt = $pdo->prepare("SELECT id FROM clientes WHERE email = :email");
    $stmt->execute([':email' => $email]);
    if ($stmt->fetch()) {
        sendErrorResponse('Email já cadastrado', 400);
    }
    
    if (!empty($cnpj)) {
        $stmt = $pdo->prepare("SELECT id FROM clientes WHERE cnpj = :cnpj");
        $stmt->execute([':cnpj' => $cnpj]);
        if ($stmt->fetch()) {
            sendErrorResponse('CNPJ já cadastrado', 400);
        }
    }
    
    if (empty($data_inicio_contrato)) {
        $data_inicio_contrato = date('Y-m-d');
    }
    
    $stmt = $pdo->prepare("
        INSERT INTO clientes (nome, email, telefone, endereco, cidade, estado, cep, cnpj, empresa, 
                             tipo, tipo_contrato, data_inicio_contrato, data_fim_contrato, 
                             limite_usuarios, limite_entidades, limite_projetos, limite_espaco_mb, 
                             ativo, created_at, updated_at)
        VALUES (:nome, :email, :telefone, :endereco, :cidade, :estado, :cep, :cnpj, :empresa, 
                :tipo, :tipo_contrato, :data_inicio_contrato, :data_fim_contrato, 
                :limite_usuarios, :limite_entidades, :limite_projetos, :limite_espaco_mb, 
                1, NOW(), NOW())
    ");
    
    $stmt->execute([
        ':nome' => $nome,
        ':email' => $email,
        ':telefone' => $telefone,
        ':endereco' => $endereco,
        ':cidade' => $cidade,
        ':estado' => $estado,
        ':cep' => $cep,
        ':cnpj' => $cnpj ?: null,
        ':empresa' => $empresa,
        ':tipo' => $tipo,
        ':tipo_contrato' => $tipo_contrato,
        ':data_inicio_contrato' => $data_inicio_contrato,
        ':data_fim_contrato' => $data_fim_contrato ?: null,
        ':limite_usuarios' => $limite_usuarios,
        ':limite_entidades' => $limite_entidades,
        ':limite_projetos' => $limite_projetos,
        ':limite_espaco_mb' => $limite_espaco_mb
    ]);
    
    $id = $pdo->lastInsertId();
    
    logAction($pdo, getCurrentUserId(), 'create', 'cliente', [
        'cliente_id' => $id,
        'nome' => $nome,
        'email' => $email
    ]);
    
    sendSuccessResponse(['id' => $id], 'Cliente criado com sucesso');
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
    
    $stmt = $pdo->prepare("SELECT id FROM clientes WHERE id = :id");
    $stmt->execute([':id' => $id]);
    if (!$stmt->fetch()) {
        sendErrorResponse('Cliente não encontrado', 404);
    }
    
    $updates = [];
    $params = [':id' => $id];
    
    if (isset($data['nome'])) {
        $updates[] = "nome = :nome";
        $params[':nome'] = sanitize_input($data['nome']);
    }
    if (isset($data['email'])) {
        $stmt = $pdo->prepare("SELECT id FROM clientes WHERE email = :email AND id != :id");
        $stmt->execute([':email' => $data['email'], ':id' => $id]);
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
    if (isset($data['cidade'])) {
        $updates[] = "cidade = :cidade";
        $params[':cidade'] = sanitize_input($data['cidade']);
    }
    if (isset($data['estado'])) {
        $updates[] = "estado = :estado";
        $params[':estado'] = sanitize_input($data['estado']);
    }
    if (isset($data['cep'])) {
        $updates[] = "cep = :cep";
        $params[':cep'] = sanitize_input($data['cep']);
    }
    if (isset($data['cnpj'])) {
        if (!empty($data['cnpj'])) {
            $stmt = $pdo->prepare("SELECT id FROM clientes WHERE cnpj = :cnpj AND id != :id");
            $stmt->execute([':cnpj' => $data['cnpj'], ':id' => $id]);
            if ($stmt->fetch()) {
                sendErrorResponse('CNPJ já cadastrado', 400);
            }
        }
        $updates[] = "cnpj = :cnpj";
        $params[':cnpj'] = sanitize_input($data['cnpj']) ?: null;
    }
    if (isset($data['empresa'])) {
        $updates[] = "empresa = :empresa";
        $params[':empresa'] = sanitize_input($data['empresa']);
    }
    if (isset($data['tipo']) && in_array($data['tipo'], ['gabinete', 'empresa', 'organizacao'])) {
        $updates[] = "tipo = :tipo";
        $params[':tipo'] = sanitize_input($data['tipo']);
    }
    if (isset($data['tipo_contrato'])) {
        $updates[] = "tipo_contrato = :tipo_contrato";
        $params[':tipo_contrato'] = sanitize_input($data['tipo_contrato']);
    }
    if (isset($data['data_inicio_contrato'])) {
        $updates[] = "data_inicio_contrato = :data_inicio_contrato";
        $params[':data_inicio_contrato'] = sanitize_input($data['data_inicio_contrato']);
    }
    if (isset($data['data_fim_contrato'])) {
        $updates[] = "data_fim_contrato = :data_fim_contrato";
        $params[':data_fim_contrato'] = sanitize_input($data['data_fim_contrato']) ?: null;
    }
    if (isset($data['limite_usuarios'])) {
        $updates[] = "limite_usuarios = :limite_usuarios";
        $params[':limite_usuarios'] = intval($data['limite_usuarios']);
    }
    if (isset($data['limite_entidades'])) {
        $updates[] = "limite_entidades = :limite_entidades";
        $params[':limite_entidades'] = intval($data['limite_entidades']);
    }
    if (isset($data['limite_projetos'])) {
        $updates[] = "limite_projetos = :limite_projetos";
        $params[':limite_projetos'] = intval($data['limite_projetos']);
    }
    if (isset($data['limite_espaco_mb'])) {
        $updates[] = "limite_espaco_mb = :limite_espaco_mb";
        $params[':limite_espaco_mb'] = intval($data['limite_espaco_mb']);
    }
    if (isset($data['ativo'])) {
        $updates[] = "ativo = :ativo";
        $params[':ativo'] = intval($data['ativo']);
    }
    
    if (empty($updates)) {
        sendErrorResponse('Nenhum campo para atualizar', 400);
    }
    
    $updates[] = "updated_at = NOW()";
    $sql = "UPDATE clientes SET " . implode(', ', $updates) . " WHERE id = :id";
    
    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    
    logAction($pdo, getCurrentUserId(), 'update', 'cliente', ['cliente_id' => $id]);
    
    sendSuccessResponse(null, 'Cliente atualizado com sucesso');
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
    
    $stmt = $pdo->prepare("SELECT id, nome FROM clientes WHERE id = :id");
    $stmt->execute([':id' => $id]);
    $cliente = $stmt->fetch();
    
    if (!$cliente) {
        sendErrorResponse('Cliente não encontrado', 404);
    }
    
    // Soft delete - marcar como inativo
    $stmt = $pdo->prepare("UPDATE clientes SET ativo = 0, updated_at = NOW() WHERE id = :id");
    $stmt->execute([':id' => $id]);
    
    logAction($pdo, getCurrentUserId(), 'delete', 'cliente', [
        'cliente_id' => $id,
        'nome' => $cliente['nome']
    ]);
    
    sendSuccessResponse(null, 'Cliente removido com sucesso');
}

?>

