<?php
require_once __DIR__ . '/../Models/Produto.php';

session_start();

// Verifica se foi passada uma ação de exclusão via GET
if (isset($_GET['acao']) && $_GET['acao'] === 'excluir' && isset($_GET['id'])) {
    $id_produto = intval($_GET['id']);

    try {
        $produto = new Produto();
        $produto->excluir($id_produto);

        header("Location: ../Views/consulta_produtos.php?sucesso=" . urlencode("Produto excluído com sucesso!"));
        exit();
    } catch (Exception $e) {
        header("Location: ../Views/consulta_produtos.php?erro=" . urlencode("Erro ao excluir o produto: " . $e->getMessage()));
        exit();
    }
}

// No topo ou junto com as outras verificações do Controller:
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_produto = intval($_POST['id_produto'] ?? 0);
    $nome = trim($_POST['nome'] ?? '');
    $descricao = trim($_POST['descricao'] ?? '');
    $preco_venda = str_replace(',', '.', trim($_POST['preco_venda'] ?? '0'));
    $qtd_estoque = intval($_POST['qtd_estoque'] ?? 0);

    if (empty($nome) || empty($preco_venda)) {
        $redireciona = $id_produto > 0 ? "editar_produto.php?id=$id_produto&erro=" : "cadastro_produto.php?erro=";
        header("Location: ../Views/" . $redireciona . urlencode("Preencha os campos obrigatórios."));
        exit();
    }

    try {
        $produto = new Produto();

        if ($id_produto > 0) {
            // Atualizar produto existente
            $produto->atualizar($id_produto, $nome, $descricao, $preco_venda, $qtd_estoque);
            $msg = "Produto atualizado com sucesso!";
        } else {
            // Cadastrar novo produto
            $produto->cadastrar($nome, $descricao, $preco_venda, $qtd_estoque);
            $msg = "Produto cadastrado com sucesso!";
        }
        
        header("Location: ../Views/consulta_produtos.php?sucesso=" . urlencode($msg));
        exit();
    } catch (Exception $e) {
        $redireciona = $id_produto > 0 ? "editar_produto.php?id=$id_produto&erro=" : "cadastro_produto.php?erro=";
        header("Location: ../Views/" . $redireciona . urlencode("Erro: " . $e->getMessage()));
        exit();
    }
}

// Caso seja o envio do formulário de cadastro (POST)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = trim($_POST['nome'] ?? '');
    $descricao = trim($_POST['descricao'] ?? '');
    $preco_venda = str_replace(',', '.', trim($_POST['preco_venda'] ?? '0'));
    $qtd_estoque = intval($_POST['qtd_estoque'] ?? 0);

    if (empty($nome) || empty($preco_venda)) {
        header("Location: ../Views/cadastro_produto.php?erro=" . urlencode("Preencha os campos obrigatórios."));
        exit();
    }

    try {
        $produto = new Produto();
        $produto->cadastrar($nome, $descricao, $preco_venda, $qtd_estoque);
        
        header("Location: ../Views/consulta_produtos.php?sucesso=" . urlencode("Produto cadastrado com sucesso!"));
        exit();
    } catch (Exception $e) {
        header("Location: ../Views/cadastro_produto.php?erro=" . urlencode("Erro ao cadastrar: " . $e->getMessage()));
        exit();
    }
}