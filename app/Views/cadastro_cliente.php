<?php
session_start();
if (!isset($_SESSION['usuario_logado'])) {
    header("Location: login.php?erro=" . urlencode("Acesso restrito. Faça login para continuar."));
    exit();
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro de Cliente - NexoOS</title>
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
        <a href="consulta_clientes.php" class="nav-link active">Clientes</a>
        <a href="#" class="nav-link">Estoque</a>
        <a href="#" class="nav-link">Ordens de serviço</a>
        <a href="#" class="nav-link">Relatórios</a>
    </nav>
</div>

<!-- Conteúdo Principal -->
<div class="app-wrapper">
    <small class="text-muted">Início / <strong class="text-dark">Cadastro de cliente</strong></small>

    <div class="my-3">
        <h2 class="fw-bold mb-1">Cadastro de cliente</h2>
        <p class="text-muted small mb-0">Inclua os dados do novo cliente.</p>
    </div>

    <?php if (isset($_GET['erro'])): ?>
        <div class="alert alert-danger alert-dismissible fade show mb-4" role="alert">
            <?= htmlspecialchars($_GET['erro']) ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <form action="../Controllers/ClienteController.php" method="POST">
        <div class="card border-0 shadow-sm p-4 mb-4" style="border-radius: 10px;">
            <h6 class="fw-bold mb-3">Dados Cadastrais</h6>
            
            <div class="row g-3 mb-3">
                <div class="col-md-6">
                    <label class="form-label small text-muted fw-semibold">Nome Completo *</label>
                    <input type="text" class="form-control" name="nome" placeholder="Digite o nome do cliente" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label small text-muted fw-semibold">CPF / CNPJ *</label>
                    <input type="text" class="form-control" id="cpf_cnpj" name="cpf_cnpj" placeholder="000.000.000-00" required>
                </div>
            </div>

            <div class="row g-3 mb-4">
                <div class="col-md-6">
                    <label class="form-label small text-muted fw-semibold">E-mail *</label>
                    <input type="email" class="form-control" name="email" placeholder="nome@exemplo.com" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label small text-muted fw-semibold">Telefone / Celular</label>
                    <input type="text" class="form-control" id="telefone" name="telefone" placeholder="(00) 00000-0000">
                </div>
            </div>

            <!-- Botões Inferiores -->
            <div class="d-flex justify-content-end gap-2">
                <a href="consulta_clientes.php" class="btn btn-light text-secondary border px-4 py-2">Cancelar</a>
                <button type="submit" class="btn btn-teal px-4 py-2">Salvar cliente</button>
            </div>
        </div>
    </form>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://unpkg.com/imask"></script>
<script>
IMask(document.getElementById('cpf_cnpj'), {
  mask: [
    { mask: '000.000.000-00', max: 11 },
    { mask: '00.000.000/0000-00' }
  ]
});

IMask(document.getElementById('telefone'), {
  mask: [
    { mask: '(00) 0000-0000' },
    { mask: '(00) 00000-0000' }
  ]
});
</script>
</body>
</html>