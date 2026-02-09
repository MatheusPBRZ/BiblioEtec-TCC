<?php
session_start();
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BiblioEtec</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-SgOJa3DmI69IUzQ2PVdRZhwQ+dy64/BUtbMJw1MZ8t5HZApcHrRKUc4W0kG879m7" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/js/bootstrap.bundle.min.js" integrity="sha384-k6d4wzSIapyDyv1kpU366/PK5hCdSbCRGRCMv+eplOQJWyd1fbcAu9OCUj5zNLiq" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="assets/CSS/cadastro.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">

 <!-- Font Awesome (ícones) -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">


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
            <a class="nav-link active" href="livros.php"><i class="bi bi-journal-bookmark-fill me-1"></i>Livros</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="sobre.php"><i class="bi bi-info-circle-fill me-1"></i>Sobre</a>
        </li>

        <?php if (isset($_SESSION['usuario_id'])): // Se o usuário está logado ?>
            <li class="nav-item">
                <a class="nav-link" href="meus_agendamentos.php"><i class="bi bi-calendar-check-fill me-1"></i>Meus Agendamentos</a>
            </li>

            <?php if (isset($_SESSION['usuario_tipo']) && $_SESSION['usuario_tipo'] == 'admin'): // Se for admin ?>
                <li class="nav-item">
                    <a class="nav-link" href="admin/dashboard.php"><i class="bi bi-gear-fill me-1"></i>Painel Admin</a>
                </li>
            <?php endif; ?>
            
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                    <img src="assets/imagens/iconeusuario.png" alt="Usuário" class="icone-usuario me-2">
                    <span>Olá, <?php echo htmlspecialchars($_SESSION['usuario_nome']); ?></span>
                </a>
                <ul class="dropdown-menu dropdown-menu-end">
                    <li><a class="dropdown-item" href="perfil.php">Meu Perfil</a></li>
                    <li><hr class="dropdown-divider"></li>
                    <li><a class="dropdown-item" href="../app/logout.php">Desconectar</a></li>
                </ul>
            </li>

        <?php else: // Se o usuário não está logado ?>
            <li class="nav-item">
                <a class="nav-link" href="login.php"><i class="bi bi-person-circle me-1"></i>Cadastro/Login</a>
            </li>
        <?php endif; ?>
    </ul>
</div>
    </div>
  </nav>
<div class="container-geral">
<main>
  
<div class="cadastro-box">
<div class="text-center mb-4">

        <h2>Bem-vindo ao Formulário de Cadastro</h2>
    </div>
    <div class="cadastro-card p-4 rounded shadow-sm">
        <h2 class="text-center mb-3">Cadastro</h2>
        <form name="form" method="POST" action="../app/cadastrarUsuario.php">
            <div class="mb-3">
              <label for="nome" class="form-label">Nome</label>
              <input type="text" class="form-control" id="nome" name="nome"  required>
            </div>

            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="email" class="form-control" id="email" name="email"  required>
            </div>

            <div class="mb-3">
    <label for="senha" class="form-label">Senha:</label>
    <input type="password" class="form-control" id="senha" name="senha" required>
</div>

<div class="mb-3">
    <label for="confirma_senha" class="form-label">Confirmar Senha:</label>
    <input type="password" class="form-control" id="confirma_senha" name="confirma_senha" required>
</div>

<div id="mensagem-senha" class="form-text text-danger mb-2"></div>

<button type="submit" class="btn btn-primary w-100">Cadastrar</button>


            <div class="text-center mt-4">
            <a>Já tem um Login ? <a href="login.php" class="login-button">Faça login</a>
            <a href="index.php" class="voltar">Voltar</a>
            </div>
        </form>
       </div>
 

    <div class="text-center mt-4">
    </div>
    </main>
</div>






 

<footer>
  <p>Contato: biblioetec@escola.com | Horário: 8h - 17h</p>
  <p>&copy; 2025 Biblioteca Escolar</p>
  </footer>

<script>
    // Seleciona os elementos do formulário
    const form = document.querySelector('form');
    const senha = document.getElementById('senha');
    const confirmaSenha = document.getElementById('confirma_senha');
    const mensagemSenha = document.getElementById('mensagem-senha');

    // Adiciona um "ouvinte" para o evento de envio do formulário
    form.addEventListener('submit', function(event) {
        // Verifica se as senhas são diferentes
        if (senha.value !== confirmaSenha.value) {
            // Mostra a mensagem de erro
            mensagemSenha.textContent = 'As senhas não conferem!';
            
            // Impede o envio do formulário
            event.preventDefault(); 
        } else {
            // Limpa a mensagem de erro se as senhas conferirem
            mensagemSenha.textContent = '';
        }
    });

    // Bônus: Validação em tempo real enquanto o usuário digita
    confirmaSenha.addEventListener('input', function() {
        if (senha.value !== confirmaSenha.value) {
            mensagemSenha.textContent = 'As senhas não conferem!';
        } else {
            mensagemSenha.textContent = '';
        }
    });

</script>

</script>
   <div vw class="enabled">
        <div vw-access-button class="active"></div>
        <div vw-plugin-wrapper>
            <div class="vw-plugin-top-wrapper"></div>
        </div>
    </div>
    <script src="https://vlibras.gov.br/app/vlibras-plugin.js"></script>
    <script>
    new window.VLibras.Widget('https://vlibras.gov.br/app');
    </script>
</body>
</html>