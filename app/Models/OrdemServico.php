<?php
require_once __DIR__ . '/../../config/Database.php';

class OrdemServico {
    private $conn;

    public function __construct() {
        $database = new Database();
        $this->conn =$database->getConnection();
    }

    public function listarTodas() {
        $query = "SELECT os.*, c.nome as nome_cliente 
                  FROM ordens_servico os 
                  INNER JOIN clientes c ON os.id_cliente = c.id_cliente 
                  ORDER BY os.id_os DESC";
        $stmt = $this->conn->query($query);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function cadastrar($id_cliente, $equipamento,$defeito_relatado, $status_os,$valor_total) {
        $stmt =$this->conn->prepare("INSERT INTO ordens_servico (id_cliente, equipamento, defeito_relatado, status_os, valor_total) VALUES (:id_cliente, :equipamento, :defeito_relatado, :status_os, :valor_total)");
        $stmt->bindParam(':id_cliente', $id_cliente, PDO::PARAM_INT);$stmt->bindParam(':equipamento', $equipamento);$stmt->bindParam(':defeito_relatado', $defeito_relatado);$stmt->bindParam(':status_os', $status_os);$stmt->bindParam(':valor_total', $valor_total);$stmt->execute();
        return $this->conn->lastInsertId();
    }

    public function buscarPorId($id_os) {
        $stmt =$this->conn->prepare("SELECT os.*, c.nome as nome_cliente FROM ordens_servico os INNER JOIN clientes c ON os.id_cliente = c.id_cliente WHERE os.id_os = :id");
        $stmt->bindParam(':id', $id_os, PDO::PARAM_INT);$stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function atualizar($id_os, $id_cliente,$equipamento, $defeito_relatado,$status_os) {
        $stmt =$this->conn->prepare("UPDATE ordens_servico SET id_cliente = :id_cliente, equipamento = :equipamento, defeito_relatado = :defeito_relatado, status_os = :status_os WHERE id_os = :id");
        $stmt->bindParam(':id',$id_os, PDO::PARAM_INT);
        $stmt->bindParam(':id_cliente',$id_cliente, PDO::PARAM_INT);
        $stmt->bindParam(':equipamento',$equipamento);
        $stmt->bindParam(':defeito_relatado',$defeito_relatado);
        $stmt->bindParam(':status_os',$status_os);
        return $stmt->execute();
    }

    // Gerenciar Produtos da OS
    public function listarProdutosOs($id_os) {
        $stmt =$this->conn->prepare("SELECT op.*, p.nome FROM ordem_servico_produtos op INNER JOIN produtos p ON op.id_produto = p.id_produto WHERE op.id_os = :id_os");
        $stmt->bindParam(':id_os', $id_os, PDO::PARAM_INT);$stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function adicionarProduto($id_os,$id_produto, $quantidade,$preco_unitario) {
        $stmt =$this->conn->prepare("INSERT INTO ordem_servico_produtos (id_os, id_produto, quantidade, preco_unitario) VALUES (:id_os, :id_produto, :quantidade, :preco_unitario)");
        $stmt->bindParam(':id_os', $id_os, PDO::PARAM_INT);$stmt->bindParam(':id_produto', $id_produto, PDO::PARAM_INT);$stmt->bindParam(':quantidade', $quantidade, PDO::PARAM_INT);$stmt->bindParam(':preco_unitario', $preco_unitario);$stmt->execute();
        $this->atualizarValorTotal($id_os);
    }

    public function removerProduto($id_os_produto,$id_os) {
        $stmt =$this->conn->prepare("DELETE FROM ordem_servico_produtos WHERE id_os_produto = :id");
        $stmt->bindParam(':id', $id_os_produto, PDO::PARAM_INT);$stmt->execute();
        $this->atualizarValorTotal($id_os);
    }

    // Gerenciar Serviços da OS (Sem quantidade/horas)
    public function listarServicosOs($id_os) {
        $stmt =$this->conn->prepare("SELECT oi.*, s.nome FROM ordem_servico_itens oi INNER JOIN servicos s ON oi.id_servico = s.id_servico WHERE oi.id_os = :id_os");
        $stmt->bindParam(':id_os', $id_os, PDO::PARAM_INT);$stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function adicionarServico($id_os,$id_servico, $preco_unitario) {$quantidade = 1; // Fixo em 1
        $stmt =$this->conn->prepare("INSERT INTO ordem_servico_itens (id_os, id_servico, quantidade, preco_unitario) VALUES (:id_os, :id_servico, :quantidade, :preco_unitario)");
        $stmt->bindParam(':id_os', $id_os, PDO::PARAM_INT);$stmt->bindParam(':id_servico', $id_servico, PDO::PARAM_INT);$stmt->bindParam(':quantidade', $quantidade, PDO::PARAM_INT);$stmt->bindParam(':preco_unitario', $preco_unitario);$stmt->execute();
        $this->atualizarValorTotal($id_os);
    }

    public function removerServico($id_os_item,$id_os) {
        $stmt =$this->conn->prepare("DELETE FROM ordem_servico_itens WHERE id_os_item = :id");
        $stmt->bindParam(':id', $id_os_item, PDO::PARAM_INT);$stmt->execute();
        $this->atualizarValorTotal($id_os);
    }

    public function atualizarValorTotal($id_os) {
        $stmt =$this->conn->prepare("SELECT SUM(quantidade * preco_unitario) as total_prod FROM ordem_servico_produtos WHERE id_os = :id_os");
        $stmt->bindParam(':id_os', $id_os, PDO::PARAM_INT);$stmt->execute();
        $resProd =$stmt->fetch(PDO::FETCH_ASSOC);
        $totalProd =$resProd['total_prod'] ?? 0;

        $stmt =$this->conn->prepare("SELECT SUM(quantidade * preco_unitario) as total_serv FROM ordem_servico_itens WHERE id_os = :id_os");
        $stmt->bindParam(':id_os', $id_os, PDO::PARAM_INT);$stmt->execute();
        $resServ =$stmt->fetch(PDO::FETCH_ASSOC);
        $totalServ =$resServ['total_serv'] ?? 0;

        $totalGeral = $totalProd +$totalServ;

        $stmtUp =$this->conn->prepare("UPDATE ordens_servico SET valor_total = :total WHERE id_os = :id_os");
        $stmtUp->bindParam(':total', $totalGeral);$stmtUp->bindParam(':id_os', $id_os, PDO::PARAM_INT);$stmtUp->execute();
    }

    public function excluir($id_os) {
        $stmt =$this->conn->prepare("DELETE FROM ordens_servico WHERE id_os = :id");
        $stmt->bindParam(':id',$id_os, PDO::PARAM_INT);
        return $stmt->execute();
    }
}