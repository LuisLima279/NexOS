<?php
session_start();
if (!isset($_SESSION['usuario_logado'])) {
    header("Location: login.php?erro=" . urlencode("Acesso restrito."));
    exit();
}

require_once __DIR__ . '/../Models/Produto.php';
$produtoModel = new Produto();
$produtos = $produtoModel->listarTodos();
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gerenciamento de Estoque - NexoOS</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../../public/css/style.css">
</head>
<body>

<!-- Sidebar Lateral -->
<div class="app-sidebar">
    <div class="sidebar-brand">
        <span class="logo-icon">N</span> Nex<span style="color:var(--primary-teal)">o</span>OS
    </div>
    <div class="sidebar-menu-title">Menu Principal</div>
    <nav class="sidebar-nav">
        <a href="#" class="nav-link">Visão geral</a>
        <a href="consulta_clientes.php" class="nav-link">Clientes</a>
        <a href="consulta_produtos.php" class="nav-link active">Estoque</a>
        <a href="#" class="nav-link">Ordens de serviço</a>
        <a href="#" class="nav-link">Relatórios</a>
    </nav>
</div>

<!-- Conteúdo Principal -->
<div class="app-wrapper">
    <small class="text-muted">Início / <strong class="text-dark">Estoque</strong></small>

    <div class="d-flex justify-content-between align-items-center my-3">
        <div>
            <h2 class="fw-bold mb-1">Controle de estoque</h2>
            <p class="text-muted small mb-0">Gerencie os produtos cadastrados no sistema.</p>
        </div>
        <!-- Botão que leva para a tela de cadastro -->
        <a href="cadastro_produto.php" class="btn btn-teal px-4 py-2">+ Novo produto</a>
    </div>

    <?php if (isset($_GET['sucesso'])): ?>
        <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
            <?= htmlspecialchars($_GET['sucesso']) ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <div class="card border-0 shadow-sm p-4 mb-4" style="border-radius: 10px;">
    <div class="table-responsive">
        <table class="table table-hover align-middle">
            <thead class="table-light">
                <tr>
                    <th scope="col" style="width: 8%;">ID</th>
                    <th scope="col" style="width: 25%;">Nome</th>
                    <th scope="col" style="width: 30%;">Descrição</th>
                    <th scope="col" style="width: 15%;">Preço de Venda</th>
                    <th scope="col" style="width: 10%;">Estoque</th>
                    <th scope="col" style="width: 12%;" class="text-end">Ações</th>
                </tr>
            </thead>
            <tbody>
                <?php if (count($produtos) > 0): ?>
                    <?php foreach ($produtos as $prod): ?>
                        <tr>
                            <td>#<?= $prod['id_produto'] ?></td>
                            <td><strong><?= htmlspecialchars($prod['nome']) ?></strong></td>
                            <td><?= htmlspecialchars($prod['descricao']) ?></td>
                            <td>R$ <?= number_format($prod['preco_venda'], 2, ',', '.') ?></td>
                            <td><span class="badge bg-secondary"><?= $prod['qtd_estoque'] ?> un.</span></td>
                            <td class="text-end">
                                <a href="editar_produto.php?id=<?= $prod['id_produto'] ?>" class="btn btn-sm btn-outline-primary me-1">Editar</a>
                                <a href="../Controllers/ProdutoController.php?acao=excluir&id=<?= $prod['id_produto'] ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('Deseja realmente excluir este produto?')">Excluir</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="6" class="text-center text-muted py-4">Nenhum produto cadastrado no estoque.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>