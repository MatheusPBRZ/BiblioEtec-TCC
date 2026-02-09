<?php
session_start();

if (!isset($_SESSION["usuario_id"])) {
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Painel do Usuário</title> <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-SgOJa3DmI69IUzQ2PVdRZhwQ+dy64/BUtbMJw1MZ8t5HZApcHrRKUc4W0kG879m7" crossorigin="anonymous">
    <link rel="stylesheet" href="assets/CSS/login.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="assets/CSS/estilo_font.css">
</head>
<body>

<?php
include 'navbar.php';
?>

<div class="container-geral">
    <main>
        <div class="login-box">
            <div class="p-4">
                <h1 class="text-center">Bem-vindo, <?php echo htmlspecialchars($_SESSION["usuario_nome"]); ?>!</h1>
                <p class="text-center">Você está logado no sistema da BiblioEtec</p>

                <div class="text-center mt-4">
                    <a href="../app/logout.php" class="btn btn-danger">Desconectar</a>
                    <a href="index.php" class="btn btn-success">Continuar</a>
                </div>
            </div>
        </div>
    </main>
</div>

<footer>
    <p>Contato: biblioetec@escola.com | Horário: 8h - 17h</p>
    <p>&copy; 2025 Biblioteca Escolar</p>
</footer>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/js/bootstrap.bundle.min.js"></script>

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