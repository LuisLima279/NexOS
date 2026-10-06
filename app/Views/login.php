<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - NexoOS</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../../public/css/style.css">
</head>
<body>

<div class="container-fluid p-0 vh-100">
    <div class="row g-0 h-100">
        <!-- Lado Esquerdo - Branding -->
        <div class="col-md-5 d-none d-md-flex flex-column justify-content-center align-items-center text-white" style="background-color: var(--sidebar-bg);">
            <div class="text-center px-4">
                <div class="mb-3">
                    <span class="logo-icon fs-1 p-3" style="width:70px; height:70px; background-color: var(--primary-teal); border-radius:50%; display:inline-flex; align-items:center; justify-content:center;">N</span>
                </div>
                <h1 class="fw-bold fs-2">Nex<span style="color:var(--primary-teal)">o</span>OS</h1>
                <p class="fs-5 text-muted mt-2">Gestão que conecta.</p>
                <p class="small text-secondary mt-4">Serviços, clientes e produtos<br>organizados em um só lugar.</p>
            </div>
        </div>

        <!-- Lado Direito - Form de Login -->
        <div class="col-md-7 d-flex align-items-center justify-content-center bg-light">
            <div class="card border-0 shadow-sm p-4" style="width: 100%; max-width: 420px; border-radius: 12px;">
                <div class="card-body">
                    <h3 class="fw-bold mb-1">Bem-vinda ao NexoOS</h3>
                    <p class="text-muted small mb-4">Acesse sua conta para continuar.</p>

                    <?php if (isset($_GET['erro'])): ?>
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <?= htmlspecialchars($_GET['erro']) ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    <?php endif; ?>

                    <form action="../Controllers/AuthController.php?acao=login" method="POST">
                        <div class="mb-3">
                            <label for="email" class="form-label small fw-semibold text-muted">E-mail</label>
                            <input type="email" class="form-control form-control-lg fs-6" id="email" name="email" required autofocus>
                        </div>
                        
                        <div class="mb-3">
                            <label for="senha" class="form-label small fw-semibold text-muted">Senha</label>
                            <input type="password" class="form-control form-control-lg fs-6" id="senha" name="senha" required>
                        </div>

                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="lembrar">
                                <label class="form-check-label small text-muted" for="lembrar">Lembrar-me</label>
                            </div>
                            <a href="#" class="small text-decoration-none fw-semibold" style="color: var(--primary-teal)">Esqueci minha senha</a>
                        </div>

                        <button type="submit" class="btn btn-teal btn-lg w-100 fs-6 py-2">Entrar no sistema</button>
                    </form>

                    <div class="text-center mt-4 pt-3 border-top">
                        <small class="text-muted">NexoOS • Gestão para pequenas empresas</small><br>
                        <small class="text-muted" style="font-size:0.75rem;">© 2026 NexoOS</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>