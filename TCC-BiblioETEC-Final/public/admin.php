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
    <link rel="stylesheet" href="assets/CSS/admin.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">

 <!-- Font Awesome (ícones) -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">


</head>
<body>
  
<?php
include 'navbar.php';
?>
  </nav>
<div class="container-geral">
<main class="container mt-5 mb-5">
    <div class="row justify-content-center">
        <div class="col-md-8 col-lg-6">

            <div class="card panel-card">
                <div class="card-header panel-header text-center">
                    <h2 class="mb-0"><i class="bi bi-gear-fill me-2"></i>Painel Administrativo</h2>
                </div>
                <div class="card-body p-4">
                    <p class="text-center text-muted mb-4">
                        Bem-vindo, <?php echo htmlspecialchars($_SESSION['usuario_nome']); ?>! Você tem acesso administrativo aos seguintes privilégios:
                    </p>

                    <div class="d-grid gap-3">
                        <a href="upload_front.php" class="btn btn-primary btn-panel" style="background-color: #A67C63;">
                            <i class="bi bi-book-half me-2"></i>Cadastrar Novo Livro
                        </a>

                        <a href="excluir.php" class="btn btn-danger btn-panel" style="background-color: #593C32;">
                            <i class="bi bi-trash-fill me-2"></i>Excluir Livro
                        </a>

                        <a href="reservados.php" class="btn btn-info btn-panel text-white" style="background-color: #D9C6BF; color:black!important;">
                            <i class="bi bi-calendar-check-fill me-2"></i>Gerenciar Reservas

                            <a href="excluir_reservas.php" class="btn btn-info btn-panel text-white" style="background-color: #A64826;">
                            <i class="bi bi-calendar-check-fill me-2"></i>Excluir Reservas
                        </a>
                        </a>
                    </div>
                    </div>
            </div>

        </div>
    </div>
</main>
  
  

        


</div>
</main>




        

    <footer>
        <p>Contato: biblioetec@escola.com | Horário: 8h - 17h</p>
        <p>&copy; 2025 Biblioteca Escolar</p>
    </footer>
</body>
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
</html>
    
  
  
  
  
  
  
  
  
  
  
  
  
  
  
  
  
  
  
  
  
  
  
  
  
  
