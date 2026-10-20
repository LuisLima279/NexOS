<?php
require_once __DIR__ . '/../Models/Servico.php';

session_start();

// Ação de Exclusão via GET
if (isset($_GET['acao']) && $_GET['acao'] === 'excluir' && isset($_GET['id'])) {
    $id_servico = intval($_GET['id']);

    try {
        $servico = new Servico();
        $servico->excluir($id_servico);

        header("Location: ../Views/consulta_servicos.php?sucesso=" . urlencode("Serviço excluído com sucesso!"));
        exit();
    } catch (Exception $e) {
        header("Location: ../Views/consulta_servicos.php?erro=" . urlencode("Erro ao excluir o serviço: " . $e->getMessage()));
        exit();
    }
}

// Ações de Cadastro e Edição via POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_servico = intval($_POST['id_servico'] ?? 0);
    $nome = trim($_POST['nome'] ?? '');
    $descricao = trim($_POST['descricao'] ?? '');
    $preco_base = str_replace(',', '.', trim($_POST['preco_base'] ?? '0'));

    if (empty($nome) || empty($preco_base)) {
        $redireciona = $id_servico > 0 ? "editar_servico.php?id=$id_servico&erro=" : "cadastro_servico.php?erro=";
        header("Location: ../Views/" . $redireciona . urlencode("Preencha os campos obrigatórios."));
        exit();
    }

    try {
        $servico = new Servico();

        if ($id_servico > 0) {
            $servico->atualizar($id_servico, $nome, $descricao, $preco_base);
            $msg = "Serviço atualizado com sucesso!";
        } else {
            $servico->cadastrar($nome, $descricao, $preco_base);
            $msg = "Serviço cadastrado com sucesso!";
        }
        
        header("Location: ../Views/consulta_servicos.php?sucesso=" . urlencode($msg));
        exit();
    } catch (Exception $e) {
        $redireciona = $id_servico > 0 ? "editar_servico.php?id=$id_servico&erro=" : "cadastro_servico.php?erro=";
        header("Location: ../Views/" . $redireciona . urlencode("Erro: " . $e->getMessage()));
        exit();
    }
}