<?php
require_once __DIR__ . '/../../config/Database.php';

class Produto {
    private $conn;

    public function __construct() {
        $this->conn = Database::getConnection();
    }

    public function cadastrar($nome, $descricao, $preco_venda, $qtd_estoque) {
        $stmt = $this->conn->prepare("INSERT INTO produtos (nome, descricao, preco_venda, qtd_estoque) VALUES (:nome, :descricao, :preco_venda, :qtd_estoque)");
        $stmt->bindParam(':nome', $nome);
        $stmt->bindParam(':descricao', $descricao);
        $stmt->bindParam(':preco_venda', $preco_venda);
        $stmt->bindParam(':qtd_estoque', $qtd_estoque);
        
        return $stmt->execute();
    }

    public function listarTodos() {
        $stmt = $this->conn->query("SELECT * FROM produtos ORDER BY id_produto DESC");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

   public function excluir($id_produto) {
        $stmt = $this->conn->prepare("DELETE FROM produtos WHERE id_produto = :id");
        $stmt->bindParam(':id', $id_produto, PDO::PARAM_INT);
        return $stmt->execute();
    }

    public function buscarPorId($id_produto) {
        $stmt = $this->conn->prepare("SELECT * FROM produtos WHERE id_produto = :id");
        $stmt->bindParam(':id', $id_produto, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function atualizar($id_produto, $nome, $descricao, $preco_venda, $qtd_estoque) {
        $stmt = $this->conn->prepare("UPDATE produtos SET nome = :nome, descricao = :descricao, preco_venda = :preco_venda, qtd_estoque = :qtd_estoque WHERE id_produto = :id");
        $stmt->bindParam(':id', $id_produto, PDO::PARAM_INT);
        $stmt->bindParam(':nome', $nome);
        $stmt->bindParam(':descricao', $descricao);
        $stmt->bindParam(':preco_venda', $preco_venda);
        $stmt->bindParam(':qtd_estoque', $qtd_estoque);
        
        return $stmt->execute();
    }
}