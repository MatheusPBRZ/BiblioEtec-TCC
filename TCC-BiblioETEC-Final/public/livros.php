<?php
session_start();
require_once '../app/conexao.php'; 

if (isset($_SESSION['usuario_id']) && $_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['acao_desejo'])) {
    $livro_id = filter_input(INPUT_POST, 'livro_id', FILTER_VALIDATE_INT);
    $usuario_id = $_SESSION['usuario_id'];
    $acao = $_POST['acao_desejo'];

    if ($livro_id) {
        if ($acao == 'adicionar') {
            $sql_acao = "INSERT IGNORE INTO lista_desejos (usuario_id, livro_id) VALUES (:usuario_id, :livro_id)";
        } elseif ($acao == 'remover') {
            $sql_acao = "DELETE FROM lista_desejos WHERE usuario_id = :usuario_id AND livro_id = :livro_id";
        }
        if (isset($sql_acao)) {
            $stmt_acao = $conn->prepare($sql_acao);
            $stmt_acao->execute(['usuario_id' => $usuario_id, 'livro_id' => $livro_id]);
        }
    }
    header("Location: " . $_SERVER['PHP_SELF'] . '?' . http_build_query($_GET));
    exit();
}

$lista_desejos_usuario = [];
if (isset($_SESSION['usuario_id'])) {
    $sql_desejos = "SELECT livro_id FROM lista_desejos WHERE usuario_id = :usuario_id";
    $stmt_desejos = $conn->prepare($sql_desejos);
    $stmt_desejos->execute(['usuario_id' => $_SESSION['usuario_id']]);
    $lista_desejos_usuario = $stmt_desejos->fetchAll(PDO::FETCH_COLUMN, 0);
}


$busca = $_GET['busca'] ?? '';
$autorFiltro = $_GET['autor'] ?? ''; 
$generoFiltro = $_GET['genero'] ?? ''; 
$ordenar = $_GET['ordenar'] ?? 'titulo_asc';
$paginaAtual = isset($_GET['pagina']) ? (int)$_GET['pagina'] : 1; 
$limite = isset($_GET['limite']) ? (int)$_GET['limite'] : 12;

$orderMap = [
    'titulo_asc'  => 'titulo ASC',
    'titulo_desc' => 'titulo DESC',
    'autor_asc'   => 'autor ASC, titulo ASC',
    'autor_desc'  => 'autor DESC, titulo ASC',
    'ano_desc'    => 'ano_publicacao DESC',
    'ano_asc'     => 'ano_publicacao ASC',
];
$orderByClause = $orderMap[$ordenar] ?? $orderMap['titulo_asc'];

$sqlBase = "FROM livros";
$whereConditions = [];
$params = [];

if (!empty($busca)) {
    $whereConditions[] = "(titulo LIKE :busca OR autor LIKE :busca)";
    $params[':busca'] = '%' . $busca . '%';
}
if (!empty($autorFiltro)) {
    $whereConditions[] = "autor = :autor";
    $params[':autor'] = $autorFiltro;
}

// --- CORREÇÃO APLICADA AO FILTRO DE GÊNERO ---
if (!empty($generoFiltro)) {
    // Procura por livros ONDE a coluna 'genero' CONTÉM o gênero filtrado.
    $whereConditions[] = "genero LIKE :genero";
    $params[':genero'] = '%' . $generoFiltro . '%';
}
// --- FIM DA CORREÇÃO ---

$whereClause = !empty($whereConditions) ? " WHERE " . implode(' AND ', $whereConditions) : '';

$totalSql = "SELECT COUNT(id) " . $sqlBase . $whereClause;
$totalStmt = $conn->prepare($totalSql);
$totalStmt->execute($params);
$totalLivros = $totalStmt->fetchColumn();

$totalPaginas = ceil($totalLivros / $limite);
$offset = ($paginaAtual - 1) * $limite;

$sql = "SELECT id, titulo, autor, ano_publicacao, genero, quantidade, capa, sinopse " . $sqlBase . $whereClause . " ORDER BY " . $orderByClause . " LIMIT :limit OFFSET :offset";
$stmt = $conn->prepare($sql);
foreach ($params as $key => &$val) {
    $stmt->bindParam($key, $val);
}
$stmt->bindValue(':limit', $limite, PDO::PARAM_INT);
$stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
$stmt->execute();
$livros = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Busca a lista de autores para o filtro
$autoresStmt = $conn->query("SELECT DISTINCT autor FROM livros ORDER BY autor ASC");
$listaAutores = $autoresStmt->fetchAll(PDO::FETCH_ASSOC);

// --- CORREÇÃO: Usa uma lista fixa de gêneros para o filtro ---
$listaGeneros = [
    'Aventura',
    'Biografia',
    'Fantasia',
    'Ficção',
    'História',
    'Mistério',
    'Romance',
    'Suspense',
    'Terror'
];
sort($listaGeneros); // Garante que a lista esteja sempre em ordem alfabética
// --- FIM DA CORREÇÃO ---

$conn = null;

// Lógica de mensagens de alerta (sem alterações)
$alertMessage = '';
$alertClass = '';
if (isset($_GET['status']) && $_GET['status'] == 'sucesso') {
    $alertMessage = 'Livro reservado com sucesso! Você pode ver suas reservas na sua área de perfil.';
    $alertClass = 'alert-success';
}
if (isset($_GET['erro'])) {
    $alertClass = 'alert-danger';
    switch ($_GET['erro']) {
        case 'reserva_falhou':
            $alertMessage = 'Ocorreu um erro ao tentar reservar o livro. Tente novamente mais tarde.';
            break;
        case 'login_necessario':
            $alertMessage = 'Você precisa fazer login para reservar um livro.';
            break;
        default:
            $alertMessage = 'Ocorreu um erro inesperado.';
            break;
    }
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Acervo de Livros - BiblioEtec</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <link href="assets/CSS/livros.css" rel="stylesheet">
    <link rel="stylesheet" href="assets/CSS/estilo_font.css">
</head>
<body>

    <?php include 'navbar.php'; ?>

    <main class="container my-5">
        <div class="text-center mb-5">
            <h1 class="display-5">Nossos Livros</h1>
            <p class="lead text-muted">Explore, busque e encontre sua próxima leitura.</p>
        </div>

        <?php if (!empty($alertMessage)): ?>
            <div class="alert <?= $alertClass ?> alert-dismissible fade show" role="alert">
                <?= htmlspecialchars($alertMessage) ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>

        <div class="card shadow-sm mb-5 sticky-top bg-light py-2">
            <div class="card-body">
                <form action="livros.php" method="GET" class="row g-3 align-items-center">
                    <div class="col-xl-3 col-lg-12 mb-2 mb-xl-0">
                        <div class="input-group">
                            <span class="input-group-text"><i class="bi bi-search"></i></span>
                            <input type="search" class="form-control" name="busca" placeholder="Buscar por título ou autor..." value="<?= htmlspecialchars($busca) ?>">
                        </div>
                    </div>
                    <div class="col-xl-2 col-md-6">
                        <select name="autor" class="form-select">
                            <option value="">Todos os Autores</option>
                            <?php foreach ($listaAutores as $autorItem): ?>
                                <option value="<?= htmlspecialchars($autorItem['autor']) ?>" <?= ($autorFiltro == $autorItem['autor']) ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($autorItem['autor']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    
                    <div class="col-xl-2 col-md-6">
                        <select name="genero" class="form-select">
                            <option value="">Todos os Gêneros</option>
                            <?php foreach ($listaGeneros as $generoItem): ?>
                                <option value="<?= htmlspecialchars($generoItem) ?>" <?= ($generoFiltro == $generoItem) ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($generoItem) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="col-xl-2 col-md-6">
                       <select name="ordenar" class="form-select">
                            <option value="titulo_asc" <?= $ordenar == 'titulo_asc' ? 'selected' : '' ?>>Título (A-Z)</option>
                            <option value="titulo_desc" <?= $ordenar == 'titulo_desc' ? 'selected' : '' ?>>Título (Z-A)</option>
                            <option value="autor_asc" <?= $ordenar == 'autor_asc' ? 'selected' : '' ?>>Autor (A-Z)</option>
                            <option value="autor_desc" <?= $ordenar == 'autor_desc' ? 'selected' : '' ?>>Autor (Z-A)</option>
                            <option value="ano_desc" <?= $ordenar == 'ano_desc' ? 'selected' : '' ?>>Mais Recentes</option>
                            <option value="ano_asc" <?= $ordenar == 'ano_asc' ? 'selected' : '' ?>>Mais Antigos</option>
                        </select>
                    </div>
                    <div class="col-xl-1 col-md-6">
                        <select name="limite" class="form-select">
                            <option value="12" <?= $limite == 12 ? 'selected' : '' ?>>12 por pág.</option>
                            <option value="16" <?= $limite == 16 ? 'selected' : '' ?>>16 por pág.</option>
                            <option value="24" <?= $limite == 24 ? 'selected' : '' ?>>24 por pág.</option>
                            <option value="32" <?= $limite == 32 ? 'selected' : '' ?>>32 por pág.</option>
                        </select>
                    </div>
                    <div class="col-xl-2 col-lg-12 d-grid">
                        <button type="submit" style="background-color: #593E25 !important; border: none !important; color: white !important; border-radius: 12px !important; font-weight: bold !important; transition: 0.3s !important;" class="btn btn-primary">Aplicar</button>
                    </div>
                </form>
            </div>
        </div>

        <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-lg-4 g-4">
            <?php if (empty($livros)): ?>
                <div class="col-12">
                    <div class="alert alert-warning text-center">
                        Nenhum livro encontrado com os critérios informados.
                    </div>
                </div>
            <?php else: ?>
                <?php foreach ($livros as $livro): ?>
                    <?php
                        $esta_na_lista = in_array($livro['id'], $lista_desejos_usuario);
                    ?>
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
                                       data-etec="Etec de São José do Rio Pardo"
                                       data-in-wishlist="<?= $esta_na_lista ? 'true' : 'false' ?>"> Ver Detalhes
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>

        <?php if ($totalPaginas > 1): ?>
        <nav aria-label="Navegação de página" class="mt-5 d-flex justify-content-center">
            <ul class="pagination shadow-sm">
                <li class="page-item <?= ($paginaAtual <= 1) ? 'disabled' : '' ?>">
                    <a class="page-link" style="text-decoration:none;color:#FFFFFF;font-weight: bold; background-color:#593E25" href="?<?= http_build_query(array_merge($_GET, ['pagina' => $paginaAtual - 1])) ?>">Anterior</a>
                </li>
                <?php for ($i = 1; $i <= $totalPaginas; $i++): ?>
                    <li class="page-item <?= ($i == $paginaAtual) ? 'active' : '' ?>" aria-current="page">
                        <a class="page-link" style="text-decoration:none;color:#593E25;font-weight: bold; background-color:#FFFFFF" href="?<?= http_build_query(array_merge($_GET, ['pagina' => $i])) ?>"><?= $i ?></a>
                    </li>
                <?php endfor; ?>
                <li class="page-item <?= ($paginaAtual >= $totalPaginas) ? 'disabled' : '' ?>">
                    <a class="page-link" style="text-decoration:none;color:#FFFFFF;font-weight: bold; background-color:#593E25" href="?<?= http_build_query(array_merge($_GET, ['pagina' => $paginaAtual + 1])) ?>">Próxima</a>
                </li>
            </ul>
        </nav>
        <?php endif; ?>
    </main>

    <footer>
        <p>Contato: biblioetec@escola.com | Horário: 8h - 17h</p>
        <p>&copy; <?php echo date('Y'); ?> Biblioteca Escolar</p>
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
                    <div id="modalBotaoContainer" class="d-flex flex-wrap gap-2">
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        const modalDetalhesLivro = document.getElementById('modalDetalhesLivro');
        const UsuarioLogado = <?= isset($_SESSION['usuario_id']) ? 'true' : 'false' ?>;

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
            
            const taNaLista = button.getAttribute('data-in-wishlist') === 'true';

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

            let listdesejoBotaoHTML = '';

            if (UsuarioLogado) {
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

                if (taNaLista) {
                    listdesejoBotaoHTML = `
                        <form action="livros.php" method="POST" class="d-inline">
                            <input type="hidden" name="livro_id" value="${id}">
                            <input type="hidden" name="acao_desejo" value="remover">
                            <button type="submit" class="btn btn-danger">
                                <i class="bi bi-heart-fill"></i> Na Lista
                            </button>
                        </form>`;
                } else {
                    listdesejoBotaoHTML = `
                        <form action="livros.php" method="POST" class="d-inline">
                            <input type="hidden" name="livro_id" value="${id}">
                            <input type="hidden" name="acao_desejo" value="adicionar">
                            <button type="submit" class="btn btn-outline-danger">
                                <i class="bi bi-heart"></i> Add à Lista
                            </button>
                        </form>`;
                }

            } else {
                // --- CÓDIGO NOVO: Cria o link com o "endereço" do modal ---
                const urlDeRetorno = `livros.php?openModal=${id}`;
                
                botaoHTML = `
                    <a href="login.php?redirect=${encodeURIComponent(urlDeRetorno)}" class="btn btn-primary">
                        <i class="bi bi-box-arrow-in-right"></i> Faça login para reservar
                    </a>
                `;
            }
            
            botaoContainer.innerHTML = listdesejoBotaoHTML + botaoHTML;
        });

        // --- CÓDIGO NOVO: Reabre o modal se tiver o comando na URL ---
        document.addEventListener('DOMContentLoaded', function() {
            const urlParams = new URLSearchParams(window.location.search);
            const livroIdParaAbrir = urlParams.get('openModal');

            if (livroIdParaAbrir) {
                const botaoDoLivro = document.querySelector(`[data-bs-target="#modalDetalhesLivro"][data-id="${livroIdParaAbrir}"]`);
                if (botaoDoLivro) {
                    setTimeout(() => { botaoDoLivro.click(); }, 100);
                }
            }
        });
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