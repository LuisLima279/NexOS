<?php
session_start();
if (!isset($_SESSION['usuario_logado'])) {
    header("Location: login.php?erro=" . urlencode("Acesso restrito."));
    exit();
}

require_once __DIR__ . '/../Models/OrdemServico.php';
require_once __DIR__ . '/../Models/Cliente.php';
require_once __DIR__ . '/../Models/Produto.php';
require_once __DIR__ . '/../Models/Servico.php';

$id_os = intval($_GET['id'] ?? 0);
$osModel = new OrdemServico();
$os = $osModel->buscarPorId($id_os);

if (!$os) {
    header("Location: consulta_os.php?erro=" . urlencode("Ordem de Serviço não encontrada."));
    exit();
}

$clienteModel = new Cliente();
$clientes = $clienteModel->listarTodos();

$produtoModel = new Produto();
$produtos = $produtoModel->listarTodos();

$servicoModel = new Servico();
$servicos = $servicoModel->listarTodos();

$produtosOs = $osModel->listarProdutosOs($id_os);
$servicosOs = $osModel->listarServicosOs($id_os);
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detalhes da OS #<?= $os['id_os'] ?> - NexoOS</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
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
        <a href="consulta_servicos.php" class="nav-link">Serviços</a>
        <a href="consulta_os.php" class="nav-link active">Ordens de serviço</a>
        <a href="#" class="nav-link">Relatórios</a>
    </nav>
</div>

<div class="app-wrapper">
    <small class="text-muted">Início / Ordens de Serviço / <strong class="text-dark">OS #<?= $os['id_os'] ?></strong></small>

    <div class="d-flex justify-content-between align-items-center my-3">
        <div>
            <h2 class="fw-bold mb-1">Ordem de Serviço #<?= $os['id_os'] ?></h2>
            <p class="text-muted small mb-0">Aberta em <?= date('d/m/Y H:i', strtotime($os['data_abertura'])) ?></p>
        </div>
        <div class="fs-4 fw-bold text-teal">
            Valor Total: R$ <?= number_format($os['valor_total'], 2, ',', '.') ?>
        </div>
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

    <!-- Formulário Principal da OS -->
    <form action="../Controllers/OrdemServicoController.php" method="POST" class="mb-4">
        <input type="hidden" name="id_os" value="<?= $os['id_os'] ?>">

        <div class="card border-0 shadow-sm p-4 mb-4" style="border-radius: 10px;">
            <h5 class="fw-bold mb-3 text-secondary">Informações Principais</h5>
            <div class="row g-3 mb-3">
                <div class="col-md-6">
                    <label class="form-label small text-muted fw-semibold">Cliente *</label>
                    <select class="form-select" name="id_cliente" required>
                        <?php foreach ($clientes as $cli): ?>
                            <option value="<?= $cli['id_cliente'] ?>" <?= $cli['id_cliente'] == $os['id_cliente'] ? 'selected' : '' ?>>
                                <?= htmlspecialchars($cli['nome']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label small text-muted fw-semibold">Status da OS *</label>
                    <select class="form-select" name="status_os">
                        <option value="Em análise" <?= $os['status_os'] === 'Em análise' ? 'selected' : '' ?>>Em análise</option>
                        <option value="Aguardando envio de orçamento" <?= $os['status_os'] === 'Aguardando envio de orçamento' ? 'selected' : '' ?>>Aguardando envio de orçamento</option>
                        <option value="Aguardando aprovação de orçamento" <?= $os['status_os'] === 'Aguardando aprovação de orçamento' ? 'selected' : '' ?>>Aguardando aprovação de orçamento</option>
                        <option value="Aguardando peça" <?= $os['status_os'] === 'Aguardando peça' ? 'selected' : '' ?>>Aguardando peça</option>
                        <option value="Concluída" <?= $os['status_os'] === 'Concluída' ? 'selected' : '' ?>>Concluída</option>
                        <option value="Pago aguardando retirada" <?= $os['status_os'] === 'Pago aguardando retirada' ? 'selected' : '' ?>>Pago aguardando retirada</option>
                        <option value="Cancelada" <?= $os['status_os'] === 'Cancelada' ? 'selected' : '' ?>>Cancelada</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label small text-muted fw-semibold">Equipamento / Modelo *</label>
                    <input type="text" class="form-control" name="equipamento" value="<?= htmlspecialchars($os['equipamento']) ?>" required>
                </div>
            </div>

            <div class="row g-3 mb-3">
                <div class="col-md-12">
                    <label class="form-label small text-muted fw-semibold">Defeito Relatado</label>
                    <textarea class="form-control" name="defeito_relatado" rows="2"><?= htmlspecialchars($os['defeito_relatado']) ?></textarea>
                </div>
            </div>

            <div class="d-flex justify-content-end gap-2">
                <a href="consulta_os.php" class="btn btn-light text-secondary border px-4 py-2 btn-cancelar-hover">Voltar / Cancelar</a>
                <button type="submit" class="btn btn-teal px-4 py-2">Salvar Alterações</button>
            </div>
        </div>
    </form>

    <!-- Seção de Produtos -->
    <div class="card border-0 shadow-sm p-4 mb-4" style="border-radius: 10px;">
        <h5 class="fw-bold mb-3 text-secondary">Produtos Utilizados</h5>
        
        <form action="../Controllers/OrdemServicoController.php?acao=adicionar_produto" method="POST" class="row g-2 mb-3 align-items-end">
            <input type="hidden" name="id_os" value="<?= $os['id_os'] ?>">
            <div class="col-md-7">
                <label class="form-label small text-muted">Selecionar Produto do Estoque</label>
                <select class="form-select form-select-sm" name="id_produto" required>
                    <option value="">Selecione...</option>
                    <?php foreach ($produtos as $p): ?>
                        <option value="<?= $p['id_produto'] ?>"><?= htmlspecialchars($p['nome']) ?> - R$ <?= number_format($p['preco_venda'], 2, ',', '.') ?> (Estoque: <?= $p['qtd_estoque'] ?>)</option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label small text-muted">Quantidade</label>
                <input type="number" class="form-control form-control-sm" name="quantidade" value="1" min="1" required>
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-sm btn-teal w-100">+ Adicionar</button>
            </div>
        </form>

        <div class="table-responsive">
            <table class="table table-sm align-middle">
                <thead class="table-light">
                    <tr>
                        <th>Produto</th>
                        <th>Qtd</th>
                        <th>Preço Unit.</th>
                        <th>Subtotal</th>
                        <th class="text-end">Ação</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (count($produtosOs) > 0): ?>
                        <?php foreach ($produtosOs as $po): ?>
                            <tr>
                                <td><?= htmlspecialchars($po['nome']) ?></td>
                                <td><?= $po['quantidade'] ?></td>
                                <td>R$ <?= number_format($po['preco_unitario'], 2, ',', '.') ?></td>
                                <td>R$ <?= number_format($po['quantidade'] * $po['preco_unitario'], 2, ',', '.') ?></td>
                                <td class="text-end">
                                    <a href="../Controllers/OrdemServicoController.php?acao=remover_produto&id_os_produto=<?= $po['id_os_produto'] ?>&id_os=<?= $os['id_os'] ?>" class="text-danger" title="Remover item" onclick="return confirm('Remover este produto?')"><i class="bi bi-trash"></i></a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr><td colspan="5" class="text-muted text-center py-2">Nenhum produto adicionado.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Seção de Serviços (Sem quantidade) -->
    <div class="card border-0 shadow-sm p-4 mb-4" style="border-radius: 10px;">
        <h5 class="fw-bold mb-3 text-secondary">Serviços Realizados</h5>
        
        <form action="../Controllers/OrdemServicoController.php?acao=adicionar_servico" method="POST" class="row g-2 mb-3 align-items-end">
            <input type="hidden" name="id_os" value="<?= $os['id_os'] ?>">
            <div class="col-md-9">
                <label class="form-label small text-muted">Selecionar Serviço Cadastrado</label>
                <select class="form-select form-select-sm" name="id_servico" required>
                    <option value="">Selecione...</option>
                    <?php foreach ($servicos as $s): ?>
                        <option value="<?= $s['id_servico'] ?>"><?= htmlspecialchars($s['nome']) ?> - R$ <?= number_format($s['preco_base'], 2, ',', '.') ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-md-3">
                <button type="submit" class="btn btn-sm btn-teal w-100">+ Adicionar Serviço</button>
            </div>
        </form>

        <div class="table-responsive">
            <table class="table table-sm align-middle">
                <thead class="table-light">
                    <tr>
                        <th>Serviço</th>
                        <th>Preço Base</th>
                        <th class="text-end">Ação</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (count($servicosOs) > 0): ?>
                        <?php foreach ($servicosOs as $so): ?>
                            <tr>
                                <td><?= htmlspecialchars($so['nome']) ?></td>
                                <td>R$ <?= number_format($so['preco_unitario'], 2, ',', '.') ?></td>
                                <td class="text-end">
                                    <a href="../Controllers/OrdemServicoController.php?acao=remover_servico&id_os_item=<?= $so['id_os_item'] ?>&id_os=<?= $os['id_os'] ?>" class="text-danger" title="Remover serviço" onclick="return confirm('Remover este serviço?')"><i class="bi bi-trash"></i></a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr><td colspan="3" class="text-muted text-center py-2">Nenhum serviço adicionado.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>