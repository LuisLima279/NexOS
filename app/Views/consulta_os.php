<?php
session_start();
if (!isset($_SESSION['usuario_logado'])) {
    header("Location: login.php?erro=" . urlencode("Acesso restrito."));
    exit();
}

require_once __DIR__ . '/../Models/OrdemServico.php';
$osModel = new OrdemServico();
$ordens = $osModel->listarTodas();
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ordens de Serviço - NexoOS</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <link rel="stylesheet" href="../../public/css/style.css">
    <style>
        /* Cores personalizadas e modernas para os Status da OS */
        .badge-analise { background-color: #ffc107; color: #212529; }
        .badge-orcamento-envio { background-color: #0dcaf0; color: #212529; }
        .badge-orcamento-aprovacao { background-color: #0d6efd; color: #fff; }
        .badge-peca { background-color: #6c757d; color: #fff; }
        .badge-concluida { background-color: #198754; color: #fff; }
        .badge-retirada { background-color: #20c997; color: #fff; }
        .badge-cancelada { background-color: #dc3545; color: #fff; }
    </style>
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
        <a href="consulta_produtos.php" class="nav-link">Estoque</a>
        <a href="consulta_servicos.php" class="nav-link">Serviços</a>
        <a href="consulta_os.php" class="nav-link active">Ordens de serviço</a>
        <a href="#" class="nav-link">Relatórios</a>
    </nav>
</div>

<!-- Conteúdo Principal -->
<div class="app-wrapper">
    <small class="text-muted">Início / <strong class="text-dark">Ordens de Serviço</strong></small>

    <div class="d-flex justify-content-between align-items-center my-3">
        <div>
            <h2 class="fw-bold mb-1">Controle de Ordens de Serviço</h2>
            <p class="text-muted small mb-0">Gerencie os atendimentos e manutenções da oficina.</p>
        </div>
        <a href="cadastro_os.php" class="btn btn-teal px-4 py-2">+ Nova OS</a>
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
                        <th style="width: 8%;">ID</th>
                        <th style="width: 25%;">Cliente</th>
                        <th style="width: 20%;">Equipamento</th>
                        <th style="width: 18%;">Status</th>
                        <th style="width: 12%;">Valor Total</th>
                        <th style="width: 17%;" class="text-end">Ações</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (count($ordens) > 0): ?>
                        <?php foreach ($ordens as $os): ?>
                            <?php 
                                $badgeClass = 'bg-secondary';
                                if ($os['status_os'] === 'Em análise') {
                                    $badgeClass = 'badge-analise';
                                } elseif ($os['status_os'] === 'Aguardando envio de orçamento') {
                                    $badgeClass = 'badge-orcamento-envio';
                                } elseif ($os['status_os'] === 'Aguardando aprovação de orçamento') {
                                    $badgeClass = 'badge-orcamento-aprovacao';
                                } elseif ($os['status_os'] === 'Aguardando peça') {
                                    $badgeClass = 'badge-peca';
                                } elseif ($os['status_os'] === 'Concluída') {
                                    $badgeClass = 'badge-concluida';
                                } elseif ($os['status_os'] === 'Pago aguardando retirada') {
                                    $badgeClass = 'badge-retirada';
                                } elseif ($os['status_os'] === 'Cancelada') {
                                    $badgeClass = 'badge-cancelada';
                                }
                            ?>
                            <tr>
                                <td>#<?= $os['id_os'] ?></td>
                                <td><strong><?= htmlspecialchars($os['nome_cliente']) ?></strong></td>
                                <td><?= htmlspecialchars($os['equipamento']) ?></td>
                                <td><span class="badge <?= $badgeClass ?> px-2 py-1"><?= $os['status_os'] ?></span></td>
                                <td>R$ <?= number_format($os['valor_total'], 2, ',', '.') ?></td>
                                <td class="text-end">
                                    <a href="editar_os.php?id=<?= $os['id_os'] ?>" class="btn btn-light btn-sm text-primary px-2 py-1 border-0 shadow-sm me-1" title="Detalhes / Editar" style="background-color: #f8f9fa; border-radius: 6px;">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    <a href="../Controllers/OrdemServicoController.php?acao=excluir&id=<?= $os['id_os'] ?>" class="btn btn-light btn-sm text-danger px-2 py-1 border-0 shadow-sm" title="Excluir OS" onclick="return confirm('Deseja realmente excluir esta Ordem de Serviço?')" style="background-color: #f8f9fa; border-radius: 6px;">
                                        <i class="bi bi-trash"></i>
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="6" class="text-center text-muted py-4">Nenhuma ordem de serviço registrada.</td>
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