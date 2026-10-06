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
    <title>Clientes - NexoOS</title>
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
    <div class="position-absolute bottom-0 w-100 p-3 border-top border-secondary d-flex align-items-center">
        <div class="avatar-initials me-2" style="background:#fff; color:var(--sidebar-bg)">A</div>
        <small class="text-truncate"><?= htmlspecialchars($_SESSION['usuario_nome'] ?? 'Administrador') ?></small>
    </div>
</div>

<!-- Conteúdo Principal -->
<div class="app-wrapper">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <small class="text-muted">Início / <strong class="text-dark">Clientes</strong></small>
        <a href="../Controllers/AuthController.php?acao=logout" class="btn btn-sm btn-outline-danger">Sair</a>
    </div>

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold mb-1">Clientes</h2>
            <p class="text-muted small mb-0">Consulte e gerencie os clientes cadastrados.</p>
        </div>
        <a href="cadastro_cliente.php" class="btn btn-teal px-3 py-2">+ Novo cliente</a>
    </div>

    <!-- Botão do Painel Retrátil de Filtros -->
    <div class="mb-3">
        <button class="btn btn-outline-secondary btn-sm" type="button" data-bs-toggle="collapse" data-bs-target="#painelFiltros" aria-expanded="false">
            🔍 Filtros de Pesquisa (Clique para abrir/fechar)
        </button>
    </div>

    <!-- Painel Colapsável de Filtros Individuais -->
    <div class="collapse mb-4" id="painelFiltros">
        <div class="card card-body border-0 shadow-sm">
            <div class="row g-3">
                <div class="col-md-3">
                    <label class="form-label small text-muted">Nome</label>
                    <input type="text" class="form-control filtro-input" id="filtroNome" placeholder="Buscar por nome">
                </div>
                <div class="col-md-3">
                    <label class="form-label small text-muted">CPF / CNPJ</label>
                    <input type="text" class="form-control filtro-input" id="filtroCpfCnpj" placeholder="Buscar por documento">
                </div>
                <div class="col-md-3">
                    <label class="form-label small text-muted">E-mail</label>
                    <input type="text" class="form-control filtro-input" id="filtroEmail" placeholder="Buscar por e-mail">
                </div>
                <div class="col-md-3">
                    <label class="form-label small text-muted">Telefone</label>
                    <input type="text" class="form-control filtro-input" id="filtroTelefone" placeholder="Buscar por telefone">
                </div>
                <div class="col-12 text-end">
                    <button type="button" id="btnLimpar" class="btn btn-light btn-sm text-muted">Limpar Filtros</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Tabela de Clientes -->
    <div class="card border-0 shadow-sm" style="border-radius: 10px;">
        <div class="card-header bg-white border-0 py-3 d-flex justify-content-between align-items-center">
            <span class="fw-bold text-dark">Clientes cadastrados</span>
            <small class="text-muted" id="contadorResultados">0 resultados</small>
        </div>
        <div class="table-responsive">
            <table class="table align-middle mb-0">
                <thead class="table-light text-muted small">
                    <tr>
                        <th class="ps-4">Cliente</th>
                        <th>Documento (CPF/CNPJ)</th>
                        <th>E-mail</th>
                        <th>Telefone</th>
                    </tr>
                </thead>
                <tbody id="tabelaCorpo" class="fs-6">
                </tbody>
            </table>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
function carregarClientes() {
    let nome = document.getElementById('filtroNome').value;
    let cpf_cnpj = document.getElementById('filtroCpfCnpj').value;
    let email = document.getElementById('filtroEmail').value;
    let telefone = document.getElementById('filtroTelefone').value;

    let url = `../Controllers/BuscarClientesController.php?nome=${encodeURIComponent(nome)}&cpf_cnpj=${encodeURIComponent(cpf_cnpj)}&email=${encodeURIComponent(email)}&telefone=${encodeURIComponent(telefone)}`;

    fetch(url)
        .then(response => response.json())
        .then(data => {
            let html = '';
            document.getElementById('contadorResultados').innerText = `${data.length} resultados`;

            if (data.length === 0) {
                html = '<tr><td colspan="4" class="text-center text-muted p-4">Nenhum cliente encontrado com os filtros aplicados.</td></tr>';
            } else {
                data.forEach(c => {
                    let iniciais = c.nome.split(' ').map(n => n[0]).slice(0, 2).join('').toUpperCase();
                    html += `<tr>
                        <td class="ps-4">
                            <div class="d-flex align-items-center">
                                <div class="avatar-initials me-3">${iniciais}</div>
                                <div class="fw-bold text-dark">${c.nome}</div>
                            </div>
                        </td>
                        <td>${c.cpf_cnpj || '-'}</td>
                        <td>${c.email || '-'}</td>
                        <td>${c.telefone || '-'}</td>
                    </tr>`;
                });
            }
            document.getElementById('tabelaCorpo').innerHTML = html;
        });
}

document.querySelectorAll('.filtro-input').forEach(input => {
    input.addEventListener('keyup', carregarClientes);
});

document.getElementById('btnLimpar').addEventListener('click', function() {
    document.querySelectorAll('.filtro-input').forEach(input => input.value = '');
    carregarClientes();
});

carregarClientes();
</script>
</body>
</html>