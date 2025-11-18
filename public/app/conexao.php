<?php
// Conexão com MySQL da Hostinger

$dbHost = 'auth-db1965.hostgtr.io'; // 👉 host correto
$dbPort = 3306;
$dbName = 'u698920850_appvereador';
$dbUser = 'u698920850_appuser';
$dbPassword = 'Dudu28122007!'; // coloque a senha do banco

try {
    $dsn = "mysql:host={$dbHost};port={$dbPort};dbname={$dbName};charset=utf8mb4";
    $options = [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false,
        PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8mb4 COLLATE utf8mb4_unicode_ci"
    ];
    $pdo = new PDO($dsn, $dbUser, $dbPassword, $options);

} catch (PDOException $e) {
    http_response_code(500);
    die(json_encode([
        'error' => 'Erro ao conectar ao banco de dados',
        'details' => $e->getMessage()
    ]));
}
