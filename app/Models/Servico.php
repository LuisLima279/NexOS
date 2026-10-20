<?php
require_once __DIR__ . '/../../config/Database.php'; // Ajuste o caminho da conexão se necessário

class Servico {
    private $conn;

    public function __construct() {
        $database = new Database();
        $this->conn = $database->getConnection();
    }

    public function listarTodos() {
        $stmt = $this->conn->query("SELECT * FROM servicos ORDER BY nome ASC");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function cadastrar($nome, $descricao, $preco_base) {
        $stmt = $this->conn->prepare("INSERT INTO servicos (nome, descricao, preco_base) VALUES (:nome, :descricao, :preco_base)");
        $stmt->bindParam(':nome', $nome);
        $stmt->bindParam(':descricao', $descricao);
        $stmt->bindParam(':preco_base', $preco_base);
        return $stmt->execute();
    }

    public function buscarPorId($id_servico) {
        $stmt = $this->conn->prepare("SELECT * FROM servicos WHERE id_servico = :id");
        $stmt->bindParam(':id', $id_servico, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function atualizar($id_servico, $nome, $descricao, $preco_base) {
        $stmt = $this->conn->prepare("UPDATE servicos SET nome = :nome, descricao = :descricao, preco_base = :preco_base WHERE id_servico = :id");
        $stmt->bindParam(':id', $id_servico, PDO::PARAM_INT);
        $stmt->bindParam(':nome', $nome);
        $stmt->bindParam(':descricao', $descricao);
        $stmt->bindParam(':preco_base', $preco_base);
        return $stmt->execute();
    }

    public function excluir($id_servico) {
        $stmt = $this->conn->prepare("DELETE FROM servicos WHERE id_servico = :id");
        $stmt->bindParam(':id', $id_servico, PDO::PARAM_INT);
        return $stmt->execute();
    }
}