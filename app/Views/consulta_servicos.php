<?php
session_start();
if (!isset($_SESSION['usuario_logado'])) {
    header("Location: login.php?erro=" . urlencode("Acesso restrito."));
    exit();
}

require_once __DIR__ . '/../Models/Servico.php';
$servicoModel = new Servico();
$servicos = $servicoModel->listarTodos();
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gerenciamento de Serviços - NexOS</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <link rel="stylesheet" href="../../public/css/style.css">
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
        <a href="consulta_produtos.php" class="nav-link">Estoque</a>
        <a href="consulta_servicos.php" class="nav-link active">Serviços</a>
        <a href="#" class="nav-link">Ordens de serviço</a>
        <a href="#" class="nav-link">Relatórios</a>
    </nav>
</div>

<!-- Conteúdo Principal -->
<div class="app-wrapper">
    <small class="text-muted">Início / <strong class="text-dark">Serviços</strong></small>

    <div class="d-flex justify-content-between align-items-center my-3">
        <div>
            <h2 class="fw-bold mb-1">Controle de serviços</h2>
            <p class="text-muted small mb-0">Gerencie os serviços prestados pela empresa.</p>
        </div>
        <a href="cadastro_servico.php" class="btn btn-teal px-4 py-2">+ Novo serviço</a>
    </div>

    <?php if (isset($_GET['sucesso'])): ?>
        <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
            <?= htmlspecialchars($_GET['sucesso']) ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <?php if (isset($_GET['erro'])): ?>
        <div class="alert alert-danger alert-dismissible fade show mb-4" role="alert">
            <?= htmlspecialchars($_GET['erro']) ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <div class="card border-0 shadow-sm p-4 mb-4" style="border-radius: 10px;">
        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th scope="col" style="width: 8%;">ID</th>
                        <th scope="col" style="width: 30%;">Nome</th>
                        <th scope="col" style="width: 40%;">Descrição</th>
                        <th scope="col" style="width: 15%;">Preço Base</th>
                        <th scope="col" style="width: 12%;" class="text-end">Ações</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (count($servicos) > 0): ?>
                        <?php foreach ($servicos as $serv): ?>
                            <tr>
                                <td>#<?= $serv['id_servico'] ?></td>
                                <td><strong><?= htmlspecialchars($serv['nome']) ?></strong></td>
                                <td><?= htmlspecialchars($serv['descricao']) ?></td>
                                <td>R$ <?= number_format($serv['preco_base'], 2, ',', '.') ?></td>
                                <td class="text-end">
                                    <a href="editar_servico.php?id=<?= $serv['id_servico'] ?>" class="btn btn-light btn-sm text-primary px-2 py-1 border-0 shadow-sm me-1" title="Editar serviço" style="background-color: #f8f9fa; border-radius: 6px;">
                                        <i class="bi bi-pencil-square"></i>
                                    </a>
                                    <a href="../Controllers/ServicoController.php?acao=excluir&id=<?= $serv['id_servico'] ?>" class="btn btn-light btn-sm text-danger px-2 py-1 border-0 shadow-sm" title="Excluir serviço" onclick="return confirm('Deseja realmente excluir este serviço?')" style="background-color: #f8f9fa; border-radius: 6px;">
                                        <i class="bi bi-trash"></i>
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="5" class="text-center text-muted py-4">Nenhum serviço cadastrado.</td>
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