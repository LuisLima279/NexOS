<?php
session_start();
require_once __DIR__ . '/../Models/Usuario.php';

$acao = $_GET['acao'] ?? 'login';

if ($acao === 'login' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $senha = trim($_POST['senha'] ?? '');

    if (empty($email) || empty($senha)) {
        header("Location: ../Views/login.php?erro=" . urlencode("Preencha o e-mail e a senha."));
        exit();
    }

    $usuarioModel = new Usuario();
    $usuario = $usuarioModel->autenticar($email, $senha);

    if ($usuario) {
        // Registra os dados do usuário na sessão (RN01)
        $_SESSION['usuario_logado'] = true;
        $_SESSION['usuario_id']     = $usuario['id_usuario'];
        $_SESSION['usuario_nome']   = $usuario['nome'];
        $_SESSION['usuario_email']  = $usuario['email'];

        header("Location: ../Views/consulta_clientes.php");
        exit();
    } else {
        header("Location: ../Views/login.php?erro=" . urlencode("E-mail ou senha inválidos."));
        exit();
    }
}

// Ação de Logout
if ($acao === 'logout') {
    session_destroy();
    header("Location: ../Views/login.php?sucesso=" . urlencode("Sessão encerrada com sucesso."));
    exit();
}