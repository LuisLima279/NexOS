<?php

class Database {
    private static $instance = null;

    // Método estático para obter uma única conexão PDO com o MySQL (Padrão Singleton)
    public static function getConnection() {
        if (self::$instance === null) {
            // Configurações do seu banco de dados
            $host = 'localhost';
            $db   = 'nexos'; // Altere para o nome exato do seu banco no XAMPP
            $user = 'root';
            $pass = '';        // Senha padrão do XAMPP é vazia
            $charset = 'utf8mb4';

            $dsn = "mysql:host=$host;dbname=$db;charset=$charset";
            
            // Configurações do PDO para segurança e relatórios de erros
            $options = [
                PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES   => false,
            ];

            try {
                self::$instance = new PDO($dsn, $user, $pass, $options);
            } catch (\PDOException $e) {
                // Em caso de erro na conexão, lança uma exceção com a mensagem de erro
                die("Erro na conexão com o banco de dados: " . $e->getMessage());
            }
        }
        return self::$instance;
    }
}