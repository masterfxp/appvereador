<?php
// Funções de autenticação e segurança

function secure_session_start() {
    if (session_status() === PHP_SESSION_NONE) {
        ini_set('session.cookie_httponly', 1);
        ini_set('session.use_only_cookies', 1);
        ini_set('session.cookie_secure', isset($_SERVER['HTTPS']));
        session_start();
    }
}

function require_admin($redirect = 'index.php') {
    if (!isset($_SESSION['user_id']) || !isset($_SESSION['user_nivel'])) {
        header("Location: {$redirect}");
        exit;
    }
    
    if ($_SESSION['user_nivel'] !== 'administrador') {
        http_response_code(403);
        die(json_encode(['error' => 'Acesso negado. Apenas administradores.']));
    }
}

function require_auth($redirect = 'index-login.php') {
    if (!isset($_SESSION['user_id'])) {
        header("Location: {$redirect}");
        exit;
    }
}

function generate_csrf_token() {
    if (!isset($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

function validate_csrf_token($token) {
    return isset($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $token);
}

function sanitize_input($data) {
    if (is_array($data)) {
        return array_map('sanitize_input', $data);
    }
    return htmlspecialchars(strip_tags(trim($data)), ENT_QUOTES, 'UTF-8');
}

function generate_guid() {
    return sprintf(
        '%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
        mt_rand(0, 0xffff), mt_rand(0, 0xffff),
        mt_rand(0, 0xffff),
        mt_rand(0, 0x0fff) | 0x4000,
        mt_rand(0, 0x3fff) | 0x8000,
        mt_rand(0, 0xffff), mt_rand(0, 0xffff), mt_rand(0, 0xffff)
    );
}

function logAction($pdo, $usuario_id, $acao, $tipo, $detalhes = null) {
    try {
        $stmt = $pdo->prepare("
            INSERT INTO logs (usuario_id, acao, tipo, detalhes, ip, created_at)
            VALUES (:usuario_id, :acao, :tipo, :detalhes, :ip, NOW())
        ");
        $stmt->execute([
            ':usuario_id' => $usuario_id,
            ':acao' => $acao,
            ':tipo' => $tipo,
            ':detalhes' => $detalhes ? json_encode($detalhes) : null,
            ':ip' => $_SERVER['REMOTE_ADDR'] ?? 'unknown'
        ]);
    } catch (PDOException $e) {
        error_log("Erro ao registrar log: " . $e->getMessage());
    }
}

function getCurrentUserId() {
    return $_SESSION['user_id'] ?? null;
}

function getCurrentClienteId() {
    return $_SESSION['cliente_id'] ?? null;
}

function getCurrentGabineteId() {
    return $_SESSION['gabinete_id'] ?? null;
}

