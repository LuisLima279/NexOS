<?php
session_start();
require_once __DIR__ . '/../Models/Cliente.php';

// RN01: Exigência de autenticação
if (!isset($_SESSION['usuario_logado'])) {
    header("Location: ../Views/login.php?erro=autenticacao_requerida");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome     = trim($_POST['nome'] ?? '');
    $telefone = trim($_POST['telefone'] ?? '');
    $cpf_cnpj = trim($_POST['cpf_cnpj'] ?? '');
    $email    = trim($_POST['email'] ?? '');

    if (empty($nome) || empty($cpf_cnpj) || empty($email)) {
        header("Location: ../Views/cadastro_cliente.php?erro=" . urlencode("Preencha todos os campos obrigatórios."));
        exit();
    }

    try {
        $clienteModel = new Cliente();
        $clienteModel->cadastrar($nome, $telefone, $cpf_cnpj, $email);
        header("Location: ../Views/consulta_clientes.php?sucesso=1");
        exit();
    } catch (Exception $e) {
        header("Location: ../Views/cadastro_cliente.php?erro=" . urlencode($e->getMessage()));
        exit();
    }
}