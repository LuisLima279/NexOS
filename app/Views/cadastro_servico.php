<?php
session_start();
if (!isset($_SESSION['usuario_logado'])) {
    header("Location: login.php?erro=" . urlencode("Acesso restrito."));
    exit();
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro de Serviço - NexOS</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../../public/css/style.css">
    <style>
        .btn-cancelar-hover:hover {
            background-color: #dc3545 !important;
            color: #fff !important;
            border-color: #dc3545 !important;
        }
    </style>
</head>
<body>

<div class="app-sidebar">
    <div class="sidebar-brand">
        <span class="logo-icon">N</span> NexOS
    </div>
    <div class="sidebar-menu-title">Menu Principal</div>
    <nav class="sidebar-nav">
        <a href="#" class="nav-link">Visão geral</a>
        <a href="consulta_clientes.php" class="nav-link">Clientes</a>
        <a href="consulta_produtos.php" class="nav-link">Estoque</a>
        <a href="consulta_servicos.php" class="nav-link active">Serviços</a>
        <a href="#" class="nav-link">Ordens de serviço</a>
        <a href="#" class="nav-link">Relatórios</a>
    </nav>
</div>

<div class="app-wrapper">
    <small class="text-muted">Início / Serviços / <strong class="text-dark">Cadastro de serviço</strong></small>

    <form action="../Controllers/ServicoController.php" method="POST">
        <div class="my-3">
            <h2 class="fw-bold mb-1">Cadastro de serviço</h2>
            <p class="text-muted small mb-0">Adicione um novo serviço prestado pela empresa.</p>
        </div>

        <?php if (isset($_GET['erro'])): ?>
            <div class="alert alert-danger alert-dismissible fade show mb-4" role="alert">
                <?= htmlspecialchars($_GET['erro']) ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <div class="card border-0 shadow-sm p-4 mb-4" style="border-radius: 10px;">
            <h6 class="fw-bold mb-3">Identificação do serviço</h6>
            <div class="row g-3 mb-4">
                <div class="col-md-12">
                    <label class="form-label small text-muted fw-semibold">Nome do serviço *</label>
                    <input type="text" class="form-control" name="nome" placeholder="Ex: Formatação e Backup" required>
                </div>
            </div>

            <h6 class="fw-bold mb-3">Valores</h6>
            <div class="row g-3 mb-4">
                <div class="col-md-12">
                    <label class="form-label small text-muted fw-semibold">Preço base *</label>
                    <input type="text" class="form-control" name="preco_base" placeholder="R$ 120,00" required>
                </div>
            </div>

            <h6 class="fw-bold mb-3">Informações adicionais</h6>
            <div class="row g-3 mb-3">
                <div class="col-md-12">
                    <label class="form-label small text-muted fw-semibold">Descrição / Observações</label>
                    <textarea class="form-control" name="descricao" rows="3" placeholder="Detalhes do que o serviço abrange..."></textarea>
                </div>
            </div>

            <div class="d-flex justify-content-end gap-2 mt-4">
                <a href="consulta_servicos.php" class="btn btn-light text-secondary border px-4 py-2 btn-cancelar-hover">Cancelar</a>
                <button type="submit" class="btn btn-teal px-4 py-2">Salvar serviço</button>
            </div>
        </div>
    </form>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>