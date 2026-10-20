<?php
session_start();
if (!isset($_SESSION['usuario_logado'])) {
    header("Location: login.php?erro=" . urlencode("Acesso restrito."));
    exit();
}

require_once __DIR__ . '/../Models/Produto.php';

$id_produto = intval($_GET['id'] ?? 0);
$produtoModel = new Produto();
$produto = $produtoModel->buscarPorId($id_produto);

if (!$produto) {
    header("Location: consulta_produtos.php?erro=" . urlencode("Produto não encontrado."));
    exit();
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Produto - NexOS</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../../public/css/style.css">
    <style>
        /* Estilo personalizado para o botão Cancelar ficar vermelho ao passar o mouse */
        .btn-cancelar-hover:hover {
            background-color: #dc3545 !important;
            color: #fff !important;
            border-color: #dc3545 !important;
        }
    </style>
</head>
<body>

<!-- Sidebar Lateral -->
<div class="app-sidebar">
    <div class="sidebar-brand">
        <span class="logo-icon">N</span> NexOS
    </div>
    <div class="sidebar-menu-title">Menu Principal</div>
    <nav class="sidebar-nav">
        <a href="#" class="nav-link">Visão geral</a>
        <a href="consulta_clientes.php" class="nav-link">Clientes</a>
        <a href="consulta_produtos.php" class="nav-link active">Estoque</a>
        <a href="consulta_servicos.php" class="nav-link">Serviços</a>
        <a href="#" class="nav-link">Ordens de serviço</a>
        <a href="#" class="nav-link">Relatórios</a>
    </nav>
</div>

<!-- Conteúdo Principal -->
<div class="app-wrapper">
    <small class="text-muted">Início / Estoque / <strong class="text-dark">Editar produto</strong></small>

    <form action="../Controllers/ProdutoController.php" method="POST">
        <!-- Campo oculto para enviar o ID do produto que está sendo editado -->
        <input type="hidden" name="id_produto" value="<?= $produto['id_produto'] ?>">

        <!-- Cabeçalho limpo sem o botão duplicado no topo -->
        <div class="my-3">
            <h2 class="fw-bold mb-1">Editar produto</h2>
            <p class="text-muted small mb-0">Atualize as informações do item em estoque.</p>
        </div>

        <?php if (isset($_GET['erro'])): ?>
            <div class="alert alert-danger alert-dismissible fade show mb-4" role="alert">
                <?= htmlspecialchars($_GET['erro']) ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <div class="card border-0 shadow-sm p-4 mb-4" style="border-radius: 10px;">
            
            <!-- Identificação do Produto -->
            <h6 class="fw-bold mb-3">Identificação do produto</h6>
            <div class="row g-3 mb-4">
                <div class="col-md-12">
                    <label class="form-label small text-muted fw-semibold">Nome do produto *</label>
                    <input type="text" class="form-control" name="nome" value="<?= htmlspecialchars($produto['nome']) ?>" required>
                </div>
            </div>

            <!-- Valores e Estoque -->
            <h6 class="fw-bold mb-3">Valores e estoque</h6>
            <div class="row g-3 mb-4">
                <div class="col-md-6">
                    <label class="form-label small text-muted fw-semibold">Preço de venda *</label>
                    <input type="text" class="form-control" name="preco_venda" value="<?= number_format($produto['preco_venda'], 2, ',', '.') ?>" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label small text-muted fw-semibold">Quantidade em estoque *</label>
                    <input type="number" class="form-control" name="qtd_estoque" value="<?= $produto['qtd_estoque'] ?>" min="0" required>
                </div>
            </div>

            <!-- Informações Adicionais -->
            <h6 class="fw-bold mb-3">Informações adicionais</h6>
            <div class="row g-3 mb-3">
                <div class="col-md-12">
                    <label class="form-label small text-muted fw-semibold">Descrição / Observações</label>
                    <textarea class="form-control" name="descricao" rows="3"><?= htmlspecialchars($produto['descricao']) ?></textarea>
                </div>
            </div>

            <!-- Botões Inferiores -->
            <div class="d-flex justify-content-end gap-2 mt-4">
                <a href="consulta_produtos.php" class="btn btn-light text-secondary border px-4 py-2 btn-cancelar-hover">Cancelar</a>
                <button type="submit" class="btn btn-teal px-4 py-2">Salvar alterações</button>
            </div>
        </div>
    </form>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>