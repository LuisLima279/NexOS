<?php
require_once __DIR__ . '/../../config/Database.php';

class Cliente {
    private $conn;

    public function __construct() {
        $this->conn = Database::getConnection();
    }

    // Função auxiliar para remover pontos, traços, parênteses e espaços
    private function apenasNumeros($valor) {
        return preg_replace('/[^0-9]/', '', $valor);
    }

    // RN02: Validação de duplicidade para CPF/CNPJ, E-mail e Telefone
    public function verificarDuplicidade($cpf_cnpj, $email, $telefone) {
        $cpf_cnpj_limpo = $this->apenasNumeros($cpf_cnpj);

        // Validação básica do tamanho do documento
        if (strlen($cpf_cnpj_limpo) !== 11 && strlen($cpf_cnpj_limpo) !== 14) {
            throw new Exception("Documento inválido. Digite um CPF (11 dígitos) ou CNPJ (14 dígitos) completo.");
        }

        // Checa se o CPF/CNPJ já existe no banco
        $stmt = $this->conn->prepare("SELECT id_cliente FROM clientes WHERE cpf_cnpj = :cpf_cnpj");
        $stmt->bindParam(':cpf_cnpj', $cpf_cnpj);
        $stmt->execute();
        if ($stmt->fetch()) {
            throw new Exception("RN02 Violada: Já existe um cliente cadastrado com este CPF/CNPJ.");
        }

        // Checa se o E-mail já existe no banco
        $stmt = $this->conn->prepare("SELECT id_cliente FROM clientes WHERE email = :email");
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        if ($stmt->fetch()) {
            throw new Exception("RN02 Violada: Já existe um cliente cadastrado com este E-mail.");
        }

        // Checa se o Telefone já existe no banco (apenas se preenchido)
        if (!empty($telefone)) {
            $stmt = $this->conn->prepare("SELECT id_cliente FROM clientes WHERE telefone = :telefone");
            $stmt->bindParam(':telefone', $telefone);
            $stmt->execute();
            if ($stmt->fetch()) {
                throw new Exception("RN02 Violada: Já existe um cliente cadastrado com este Telefone.");
            }
        }
    }

    // Módulo de Cadastro (Create)
    public function cadastrar($nome, $telefone, $cpf_cnpj, $email) {
        // Executa a checagem de duplicidade antes de inserir
        $this->verificarDuplicidade($cpf_cnpj, $email, $telefone);

        $stmt = $this->conn->prepare("INSERT INTO clientes (nome, telefone, cpf_cnpj, email) VALUES (:nome, :telefone, :cpf_cnpj, :email)");
        $stmt->bindParam(':nome', $nome);
        $stmt->bindParam(':telefone', $telefone);
        $stmt->bindParam(':cpf_cnpj', $cpf_cnpj);
        $stmt->bindParam(':email', $email);
        
        return $stmt->execute();
    }

    // Módulo de Consulta (Read) com suporte a busca geral
    public function listar($busca = '') {
        $sql = "SELECT * FROM clientes";
        if (!empty($busca)) {
            $sql .= " WHERE nome LIKE :busca OR cpf_cnpj LIKE :busca OR email LIKE :busca OR telefone LIKE :busca";
        }
        $sql .= " ORDER BY id_cliente DESC";

        $stmt = $this->conn->prepare($sql);
        if (!empty($busca)) {
            $termo = "%$busca%";
            $stmt->bindParam(':busca', $termo);
        }
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Módulo de Consulta Avançada com painel colapsável (Filtros Individuais)
    public function listarFiltrosAvancados($filtros = []) {
        $sql = "SELECT * FROM clientes WHERE 1=1";
        $params = [];

        if (!empty($filtros['nome'])) {
            $sql .= " AND nome LIKE :nome";
            $params[':nome'] = '%' . trim($filtros['nome']) . '%';
        }

        if (!empty($filtros['cpf_cnpj'])) {
            $sql .= " AND cpf_cnpj LIKE :cpf_cnpj";
            $params[':cpf_cnpj'] = '%' . trim($filtros['cpf_cnpj']) . '%';
        }

        if (!empty($filtros['email'])) {
            $sql .= " AND email LIKE :email";
            $params[':email'] = '%' . trim($filtros['email']) . '%';
        }

        if (!empty($filtros['telefone'])) {
            $sql .= " AND telefone LIKE :telefone";
            $params[':telefone'] = '%' . trim($filtros['telefone']) . '%';
        }

        $sql .= " ORDER BY id_cliente DESC";

        $stmt = $this->conn->prepare($sql);
        foreach ($params as $chave => $valor) {
            $stmt->bindValue($chave, $valor);
        }
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}