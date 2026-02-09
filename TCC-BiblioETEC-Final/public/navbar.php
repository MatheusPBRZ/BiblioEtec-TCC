<?php
// Garante que a sessão seja iniciada caso ainda não tenha sido
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
?>
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container">
        <a class="navbar-brand d-flex align-items-center" href="index.php">
            <i class="bi bi-mortarboard-fill fs-4 me-2"></i>
            <span>BiblioEtec</span>
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#nvbCollapse" aria-controls="nvbCollapse" aria-expanded="false" aria-label="Alternar navegação">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="nvbCollapse">
            <ul class="navbar-nav ms-auto align-items-lg-center">
                <li class="nav-item">
                    <a class="nav-link" href="index.php"><i class="bi bi-house-door-fill me-1"></i>Home</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="livros.php"><i class="bi bi-journal-bookmark-fill me-1"></i>Livros</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="sobre.php"><i class="bi bi-info-circle-fill me-1"></i>Sobre</a>
                </li>

                <?php if (isset($_SESSION['usuario_id'])) : ?>
                    <?php if (isset($_SESSION['usuario_tipo']) && $_SESSION['usuario_tipo'] == 'admin') : ?>
                        <li class="nav-item">
                            <a class="nav-link" href="admin.php"><i class="bi bi-gear-fill me-1"></i>Admin</a>
                        </li>
                    <?php endif; ?>
                    <li class="nav-item">
                        <a href="perfil.php" class="nav-link d-flex align-items-center">
                            <img src="assets/imagens/iconeusuario.png" alt="Usuário" class="icone-usuario " style="width: 42px; height: 42px;
                            border-radius: 50%; /* arredonda se for um avatar */
                            object-fit: cover;">
                            <span>Olá, <?php echo htmlspecialchars($_SESSION['usuario_nome']); ?></span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#" data-bs-toggle="modal" data-bs-target="#confirmLogoutModal">
                            <i class="bi bi-box-arrow-right me-1"></i>Sair
                        </a>
                    </li>

                <?php else : ?>
                    <li class="nav-item">
                        <a class="nav-link" href="usuario.php"><i class="bi bi-person-plus-fill me-1"></i>Cadastro</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="login.php"><i class="bi bi-box-arrow-in-right me-1"></i>Login</a>
                    </li>
                <?php endif; ?>
            </ul>
        </div>
    </div>
</nav>

<div class="modal fade" id="confirmLogoutModal" tabindex="-1" aria-labelledby="confirmLogoutModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="confirmLogoutModalLabel">Confirmar Saída</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
            </div>
            <div class="modal-body">
                Você tem certeza que deseja sair da sua conta?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <a href="../app/logout.php" class="btn btn-danger">Sair</a>
            </div>
        </div>
    </div>
</div>