<?php
require 'conexao.php';

try {
    $stmt = $pdo->query("SHOW TABLES");
    $tables = $stmt->fetchAll();

    echo "<h2>Conexão OK! 🎉</h2>";
    echo "<pre>";
    print_r($tables);
    echo "</pre>";

} catch (Exception $e) {
    echo "<h2>Erro executando query ❌</h2>";
    echo $e->getMessage();
}

