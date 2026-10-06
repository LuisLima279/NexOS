<?php
require_once __DIR__ . '/config/Database.php';

echo "<h1>Projeto Iniciado com Sucesso!</h1>";

try {
    $db = Database::getConnection();
    echo "<p style='color: green;'>✅ Conexão com o banco de dados MySQL via PDO realizada com sucesso!</p>";
} catch (Exception $e) {
    echo "<p style='color: red;'>❌ Erro ao conectar: " . $e->getMessage() . "</p>";
}