<?php
session_start();
require_once '../app/conexao.php';

try {
    // Buscar os 4 livros com a maior quantidade, ordenados do maior para o menor.
    $sqlDestaques = "SELECT id, titulo, autor, ano_publicacao, genero, quantidade, capa, sinopse 
                     FROM livros 
                     ORDER BY quantidade DESC 
                     LIMIT 4";

    $stmtDestaques = $conn->prepare($sqlDestaques);
    $stmtDestaques->execute();
    $livrosDestaque = $stmtDestaques->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    // Se der erro, define o array como vazio para não quebrar a página.
    $livrosDestaque = [];
}
$conn = null;
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BiblioEtec</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="assets/CSS/index.css">
    <link rel="stylesheet" href="assets/CSS/livros.css"> 
    <link rel="stylesheet" href="assets/CSS/estilo_font.css">

</head>
<body>
<?php
include 'navbar.php';
?>

  <div class="container-geral">
    
    </nav>
    </header>

    <main>
      <div id="carouselExampleIndicators" class="carousel slide">
        <div class="carousel-indicators">
          <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="0" class="active" aria-current="true" aria-label="Slide 1"></button>
          <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="1" aria-label="Slide 2"></button>
          <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="2" aria-label="Slide 3"></button>
          <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="3" aria-label="Slide 4"></button>
        </div>
        
        <div class="carousel-inner">
          <div class="carousel-item active">
            <img src="assets/imagens/carrosel1.jpg" class="d-block w-100" alt="slide1">
          </div>
          <div class="carousel-item">
            <img src="assets/imagens/carrosel2.jpg" class="d-block w-100" alt="slide2">
          </div>
          <div class="carousel-item">
            <img src="assets/imagens/carrosel3.jpg" class="d-block w-100" alt="slide3">
          </div>
          <div class="carousel-item">
            <img src="assets/imagens/carrosel4.jpg" class="d-block w-100" alt="slide4">
          </div>
        </div>
        
        <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide="prev">
          <span class="carousel-control-prev-icon" aria-hidden="true"></span>
          <span class="visually-hidden">Anterior</span>
        </button>
        <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide="next">
          <span class="carousel-control-next-icon" aria-hidden="true"></span>
          <span class="visually-hidden">Próximo</span>
        </button>
      </div>

      <section class="container my-5">
        <div class="text-center mb-4">
            <h2 class="display-6">Livros em Destaque</h2>
            <p class="lead text-muted">Os títulos mais populares e com mais exemplares disponíveis!</p>
        </div>
        
        <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-lg-4 g-4">
            <?php if (empty($livrosDestaque)): ?>
                <div class="col-12">
                    <div class="alert alert-info text-center">
                        Nenhum livro em destaque no momento.
                    </div>
                </div>
            <?php else: ?>
                <?php foreach ($livrosDestaque as $livro): ?>
                    <div class="col">
                        <div class="card h-100 shadow-sm book-card">
                            <img src="<?= htmlspecialchars($livro['capa']) ?>" class="card-img-top book-cover" alt="Capa de <?= htmlspecialchars($livro['titulo']) ?>">
                            <div class="card-body d-flex flex-column">
                                <h5 class="card-title book-title"><?= htmlspecialchars($livro['titulo']) ?></h5>
                                <p class="card-text text-muted book-author"><?= htmlspecialchars($livro['autor']) ?></p>
                                <div class="mt-auto text-center">
                                    <a href="#"
                                       class="btn btn-sm"
                                       style="background-color: #593E25 !important; color: white !important; border-radius: 12px !important; font-weight: bold !important; transition: 0.3s !important;"
                                       data-bs-toggle="modal"
                                       data-bs-target="#modalDetalhesLivro"
                                       data-id="<?= $livro['id'] ?>"
                                       data-titulo="<?= htmlspecialchars($livro['titulo']) ?>"
                                       data-autor="<?= htmlspecialchars($livro['autor']) ?>"
                                       data-sinopse="<?= htmlspecialchars($livro['sinopse']) ?>"
                                       data-capa="<?= htmlspecialchars($livro['capa']) ?>"
                                       data-genero="<?= htmlspecialchars($livro['genero']) ?>"
                                       data-ano_publicacao="<?= htmlspecialchars($livro['ano_publicacao']) ?>"
                                       data-quantidade="<?= htmlspecialchars($livro['quantidade']) ?>"
                                       data-etec="Etec de São José do Rio Pardo"> Ver Detalhes
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
      </section>
    </main>
  </div>

  <footer>
    <p>Contato: biblioetec@escola.com | Horário: 8h - 17h</p>
    <p>&copy; <?= date('Y') ?> Biblioteca Escolar</p>
  </footer>

  <div class="modal fade" id="modalDetalhesLivro" tabindex="-1" aria-labelledby="modalDetalhesLivroLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="modalDetalhesLivroLabel">Detalhes do Livro</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <div class="row">
            <div class="col-md-4">
              <img id="modalImagemCapa" src="" class="img-fluid rounded shadow-sm" alt="Capa do Livro">
            </div>
            <div class="col-md-8">
              <h3 id="modalTitulo" class="mb-3"></h3>
              <p class="mb-2"><strong>Autor:</strong> <span id="modalAutor"></span></p>
              <p class="mb-2"><strong>Gênero:</strong> <span id="modalGenero"></span></p>
              <p class="mb-2"><strong>Ano de Publicação:</strong> <span id="modalAnoPublicacao"></span></p>
              <p class="mb-2"><strong>Unidade:</strong> <span id="modalEtec"></span></p>
              <p class="mb-2"><strong>Exemplares disponíveis:</strong> <span id="modalQuantidade"></span></p>
              <hr>
              <p><strong>Sinopse:</strong></p>
              <p id="modalSinopse" style="text-align: justify;"></p>
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
          <div id="modalBotaoContainer">
              </div>
        </div>
      </div>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/js/bootstrap.bundle.min.js"></script>
  
  <script>
    const modalDetalhesLivro = document.getElementById('modalDetalhesLivro');
    const isUserLoggedIn = <?= isset($_SESSION['usuario_id']) ? 'true' : 'false' ?>;

    modalDetalhesLivro.addEventListener('show.bs.modal', function (event) {
      const button = event.relatedTarget;

      const id = button.getAttribute('data-id');
      const titulo = button.getAttribute('data-titulo');
      const autor = button.getAttribute('data-autor');
      const genero = button.getAttribute('data-genero');
      const anoPublicacao = button.getAttribute('data-ano_publicacao');
      const sinopse = button.getAttribute('data-sinopse');
      const quantidade = parseInt(button.getAttribute('data-quantidade'));
      const capa = button.getAttribute('data-capa');
      const etec = button.getAttribute('data-etec');

      const modalHeaderTitle = modalDetalhesLivro.querySelector('.modal-title');
      const modalImagemCapa = modalDetalhesLivro.querySelector('#modalImagemCapa');
      const modalTitulo = modalDetalhesLivro.querySelector('#modalTitulo');
      const modalAutor = modalDetalhesLivro.querySelector('#modalAutor');
      const modalGenero = modalDetalhesLivro.querySelector('#modalGenero');
      const modalAnoPublicacao = modalDetalhesLivro.querySelector('#modalAnoPublicacao');
      const modalSinopse = modalDetalhesLivro.querySelector('#modalSinopse');
      const modalQuantidade = modalDetalhesLivro.querySelector('#modalQuantidade');
      const modalEtec = modalDetalhesLivro.querySelector('#modalEtec');
      
      modalHeaderTitle.textContent = titulo;
      modalImagemCapa.src = capa;
      modalTitulo.textContent = titulo;
      modalAutor.textContent = autor;
      modalGenero.textContent = genero;
      modalAnoPublicacao.textContent = anoPublicacao;
      modalSinopse.textContent = sinopse;
      modalQuantidade.textContent = quantidade;
      modalEtec.textContent = etec;

      const botaoContainer = modalDetalhesLivro.querySelector('#modalBotaoContainer');
      let botaoHTML = '';

      if (isUserLoggedIn) {
          if (quantidade > 0) {
              botaoHTML = `
                  <form action="../app/reservar.php" method="POST" class="d-inline">
                      <input type="hidden" name="livro_id" value="${id}">
                      <button type="submit" class="btn btn-success">
                          <i class="bi bi-check-circle"></i> Reservar Livro
                      </button>
                  </form>
              `;
          } else {
              botaoHTML = `
                  <button type="button" class="btn btn-warning" disabled>
                      <i class="bi bi-hourglass-split"></i> Indisponível no momento
                  </button>
              `;
          }
      } else {
          botaoHTML = `
              <a href="login.php" class="btn btn-primary">
                  <i class="bi bi-box-arrow-in-right"></i> Faça login para reservar
              </a>
          `;
      }
      botaoContainer.innerHTML = botaoHTML;
    });
    
  //Vlibras - Plugin//  
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