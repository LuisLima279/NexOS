<?php
require_once __DIR__ . '/../Models/OrdemServico.php';
require_once __DIR__ . '/../Models/Produto.php';
require_once __DIR__ . '/../Models/Servico.php';

session_start();

$acao = $_GET['acao'] ?? '';
$id_os = intval($_POST['id_os'] ?? $_GET['id_os'] ?? 0);

if ($acao === 'excluir' && isset($_GET['id'])) {
    try {
        $osModel = new OrdemServico();
        $osModel->excluir(intval($_GET['id']));
        header("Location: ../Views/consulta_os.php?sucesso=" . urlencode("Ordem de Serviço excluída com sucesso!"));
        exit();
    } catch (Exception $e) {
        header("Location: ../Views/consulta_os.php?erro=" . urlencode("Erro ao excluir OS: " . $e->getMessage()));
        exit();
    }
}

if ($acao === 'adicionar_produto') {
    $id_produto = intval($_POST['id_produto'] ?? 0);
    $quantidade = intval($_POST['quantidade'] ?? 1);

    if ($id_produto > 0 && $id_os > 0) {
        $prodModel = new Produto();
        $produto = $prodModel->buscarPorId($id_produto);
        if ($produto) {
            $osModel = new OrdemServico();
            $osModel->adicionarProduto($id_os, $id_produto, $quantidade, $produto['preco_venda']);
        }
    }
    header("Location: ../Views/editar_os.php?id=$id_os");
    exit();
}

if ($acao === 'remover_produto') {
    $id_os_produto = intval($_GET['id_os_produto'] ?? 0);
    if ($id_os_produto > 0 && $id_os > 0) {
        $osModel = new OrdemServico();
        $osModel->removerProduto($id_os_produto, $id_os);
    }
    header("Location: ../Views/editar_os.php?id=$id_os");
    exit();
}

if ($acao === 'adicionar_servico') {
    $id_servico = intval($_POST['id_servico'] ?? 0);

    if ($id_servico > 0 && $id_os > 0) {
        $servModel = new Servico();
        $servico = $servModel->buscarPorId($id_servico);
        if ($servico) {
            $osModel = new OrdemServico();
            $osModel->adicionarServico($id_os, $id_servico, $servico['preco_base']);
        }
    }
    header("Location: ../Views/editar_os.php?id=$id_os");
    exit();
}

if ($acao === 'remover_servico') {
    $id_os_item = intval($_GET['id_os_item'] ?? 0);
    if ($id_os_item > 0 && $id_os > 0) {
        $osModel = new OrdemServico();
        $osModel->removerServico($id_os_item, $id_os);
    }
    header("Location: ../Views/editar_os.php?id=$id_os");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && empty($acao)) {
    $id_cliente = intval($_POST['id_cliente'] ?? 0);
    $equipamento = trim($_POST['equipamento'] ?? '');
    $defeito_relatado = trim($_POST['defeito_relatado'] ?? '');
    $status_os = trim($_POST['status_os'] ?? 'Em análise');

    try {
        $osModel = new OrdemServico();
        if ($id_os > 0) {
            // Atualizar e retornar para a consulta
            $osModel->atualizar($id_os, $id_cliente, $equipamento, $defeito_relatado, $status_os);
            header("Location: ../Views/consulta_os.php?sucesso=" . urlencode("Ordem de Serviço atualizada com sucesso!"));
            exit();
        } else {
            // Cadastrar nova OS e redirecionar direto para a consulta
            $osModel->cadastrar($id_cliente, $equipamento, $defeito_relatado, 'Em análise', 0.00);
            header("Location: ../Views/consulta_os.php?sucesso=" . urlencode("Ordem de Serviço aberta com sucesso!"));
            exit();
        }
    } catch (Exception $e) {
        $redireciona = $id_os > 0 ? "editar_os.php?id=$id_os&erro=" : "cadastro_os.php?erro=";
        header("Location: ../Views/" . $redireciona . urlencode("Erro: " . $e->getMessage()));
        exit();
    }
}