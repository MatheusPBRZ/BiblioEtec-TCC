<?php
session_start();
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BiblioEtec</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/css/bootstrap.min.css" rel="stylesheet" xintegrity="sha384-SgOJa3DmI69IUzQ2PVdRZhwQ+dy64/BUtbMJw1MZ8t5HZApcHrRKUc4W0kG879m7" crossorigin="anonymous">
    <link rel="stylesheet" href="assets/CSS/login.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark">
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
                <li class="nav-item">
                    <a class="nav-link active" href="usuario.php"><i class="bi bi-person-circle me-1"></i>Cadastro/Login</a>
                </li>
            </ul>
        </div>
    </div>
</nav>
<div class="container-geral">
    <main>
        <div class="login-box" id="login-form-container">
            <div class="text-center mb-4">
                <h2>Login BiblioEtec</h2>
            </div>
            
            <?php if (isset($_GET['erro'])): ?>
                <div class="alert alert-danger">
                    <?php
                        if ($_GET['erro'] == 'senha') echo 'E-mail ou senha incorretos.';
                        elseif ($_GET['erro'] == 'usuario') echo 'Usuário não encontrado.';
                        else echo 'Ocorreu um erro. Tente novamente.';
                    ?>
                </div>
            <?php endif; ?>

            <form id="login-form" method="POST" action="upload_back.php">
                <!-- --- CÓDIGO NOVO: Guarda o link de retorno --- -->
                <?php if (isset($_GET['redirect'])): ?>
                    <input type="hidden" name="redirect" value="<?= htmlspecialchars($_GET['redirect']) ?>">
                <?php endif; ?>

                <div class="mb-3">
                    <label for="email" class="form-label">E-mail:</label>
                    <input type="email" class="form-control" name="email" required>
                </div>
                <div class="mb-3">
                    <label for="senha" class="form-label">Senha:</label>
                    <input type="password" class="form-control" id="senha" name="senha" required>
                </div>
                <div class="form-check mb-3">
                    <input class="form-check-input" type="checkbox" id="mostrarSenha">
                    <label class="form-check-label" for="mostrarSenha">Mostrar Senha</label>
                </div>
                <p class="espacamento"></p>
                <button type="submit" class="btn btn-primary w-100">Entrar</button>
                <div class="text-center mt-4">
                    <a>Não tem um Cadastro ? <a href="usuario.php" class="login-button" style="text-decoration:none;color:#9B6E60;font-weight: bold">Cadastre-se</a></a>
                </div>
                <div class="mb-4">
                    <a href="index.php" class="back-button">Voltar</a>
                </div>
            </form>
            <div id="login-message"></div>
        </div>
    </main>
</div>
<footer>
    <p>Contato: biblioetec@escola.com | Horário: 8h - 17h</p>
    <p>&copy; 2025 Biblioteca Escolar</p>
</footer>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/js/bootstrap.bundle.min.js"></script>
<script>
    const mostrarSenhaCheckbox = document.getElementById('mostrarSenha');
    const senhaInput = document.getElementById('senha');

    mostrarSenhaCheckbox.addEventListener('change', function() {
        senhaInput.type = this.checked ? 'text' : 'password';
    });
</script>
</body>
</html>