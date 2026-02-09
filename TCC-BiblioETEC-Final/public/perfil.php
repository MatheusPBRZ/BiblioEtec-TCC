<?php
session_start();
include '../app/conexao.php';

if (!isset($_SESSION['usuario_id'])) {
    header("Location: login.php");
    exit;
}

$usuario_id = $_SESSION['usuario_id'];


if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['remover_livro_id'])) {
    $livro_id_para_remover = filter_input(INPUT_POST, 'remover_livro_id', FILTER_VALIDATE_INT);
    if ($livro_id_para_remover) {
        $sql_delete = "DELETE FROM lista_desejos WHERE usuario_id = :usuario_id AND livro_id = :livro_id";
        $stmt_delete = $conn->prepare($sql_delete);
        $stmt_delete->execute(['usuario_id' => $usuario_id, 'livro_id' => $livro_id_para_remover]);
        header("Location: perfil.php?removido=sucesso#lista-desejos"); 
        exit();
    }
}

$sqlUsuario = "SELECT nome, email, data_cadastro FROM usuarios WHERE id = :id";
$stmtUsuario = $conn->prepare($sqlUsuario);
$stmtUsuario->execute(['id' => $usuario_id]);
$usuario = $stmtUsuario->fetch(PDO::FETCH_ASSOC);

$busca = $_GET['busca'] ?? '';
$sqlEmprestimos = "SELECT l.titulo, l.autor, l.capa, e.data_agendamento, e.data_devolucao, e.status
                   FROM emprestimos e
                   JOIN livros l ON e.livro_id = l.id
                   WHERE e.usuario_id = :id";
$params = ['id' => $usuario_id];
if (!empty($busca)) {
    $sqlEmprestimos .= " AND (l.titulo LIKE :busca OR l.autor LIKE :busca)";
    $params[':busca'] = '%' . $busca . '%';
}
$sqlEmprestimos .= " ORDER BY e.data_agendamento DESC";
$stmtEmprestimos = $conn->prepare($sqlEmprestimos);
$stmtEmprestimos->execute($params);
$emprestimos = $stmtEmprestimos->fetchAll(PDO::FETCH_ASSOC);

$sqlDesejos = "SELECT l.id, l.titulo, l.autor, l.capa, l.quantidade
               FROM lista_desejos AS ld
               JOIN livros AS l ON ld.livro_id = l.id
               WHERE ld.usuario_id = :id
               ORDER BY ld.data_adicao DESC";
$stmtDesejos = $conn->prepare($sqlDesejos);
$stmtDesejos->execute(['id' => $usuario_id]);
$livros_desejados = $stmtDesejos->fetchAll(PDO::FETCH_ASSOC);

?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>BiblioEtec - Perfil</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/css/bootstrap.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/js/bootstrap.bundle.min.js"></script>
<link rel="stylesheet" href="assets/CSS/perfil.css">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
<link rel="stylesheet" href="assets/CSS/estilo_font.css">
</head>
<body>

<?php include 'navbar.php'; ?>

<main class="container my-5">
    <h1>Perfil de <?= htmlspecialchars($usuario['nome']); ?></h1>
    <div class="Informations mb-4">
        <p><strong>Email:</strong> <?= htmlspecialchars($usuario['email']); ?></p>
        <p><strong>Usuário desde:</strong> <?= date('d/m/Y', strtotime($usuario['data_cadastro'])); ?></p>
    </div>

    <h2 class="mt-4">Meus Livros Reservados/Emprestados</h2>
    <div class="col-lg-4 mb-3">
        <form method="GET" action="">
            <div class="input-group">
                <span class="input-group-text"><i class="bi bi-search"></i></span>
                <input type="search" class="form-control" name="busca" placeholder="Buscar por título ou autor..." value="<?= htmlspecialchars($busca) ?>">
            </div>
        </form>
    </div>
    <table class="table table-striped align-middle">
        <thead>
            <tr>
                <th>Capa</th> <th>Título</th> <th>Autor</th> <th>Data Reserva</th> <th>Prazo Devolução</th> <th>Status</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($emprestimos)): ?>
                <?php foreach ($emprestimos as $row): ?>
                    <?php
                        $status_atual = $row['status'];
                        if ($status_atual == 'devolucao_pendente' && date('Y-m-d') > $row['data_devolucao']) {
                            $status_atual = 'atrasado';
                        }
                        $classe_badge = match($status_atual) {
                            'pendente', 'reservado' => 'bg-info text-dark',
                            'devolucao_pendente' => 'bg-warning text-dark',
                            'atrasado' => 'bg-danger',
                            'concluido' => 'bg-success',
                            'cancelado' => 'bg-danger',
                            default => 'bg-secondary',
                        };
                    ?>
                    <tr>
                        <td><img src="<?= htmlspecialchars($row['capa']); ?>" alt="Capa" class="capa-livro"></td>
                        <td><?= htmlspecialchars($row['titulo']); ?></td>
                        <td><?= htmlspecialchars($row['autor']); ?></td>
                        <td><?= date('d/m/Y', strtotime($row['data_agendamento'])); ?></td>
                        <td><?= $row['data_devolucao'] ? date('d/m/Y', strtotime($row['data_devolucao'])) : '-'; ?></td>
                        <td><span class="badge <?= $classe_badge; ?>"><?= str_replace('_', ' ', ucfirst($status_atual)); ?></span></td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr><td colspan="6" class="text-center">Nenhum livro reservado ou emprestado.</td></tr>
            <?php endif; ?>
        </tbody>
    </table>

    <hr class="my-5">

    <h2 class="mt-4" id="lista-desejos">Minha Lista de Desejos</h2>
    <?php if (empty($livros_desejados)): ?>
        <div class="alert alert-info">
            Sua lista de desejos está vazia. Adicione livros a partir do <a href="livros.php" class="alert-link">catálogo</a>!
        </div>
    <?php else: ?>
        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead>
                    <tr>
                        <th>Capa</th>
                        <th>Título</th>
                        <th>Autor</th>
                        <th class="text-center">Status</th>
                        <th class="text-center">Ações</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($livros_desejados as $livro): ?>
                        <tr>
                            <td><img src="<?= htmlspecialchars($livro['capa']); ?>" alt="Capa" class="capa-livro"></td>
                            <td><?= htmlspecialchars($livro['titulo']); ?></td>
                            <td><?= htmlspecialchars($livro['autor']); ?></td>
                            <td class="text-center">
                                <?php if ($livro['quantidade'] > 0): ?>
                                    <span class="badge bg-success">Disponível</span>
                                <?php else: ?>
                                    <span class="badge bg-secondary">Indisponível</span>
                                <?php endif; ?>
                            </td>
                            <td class="text-center">
                                <form action="perfil.php" method="POST" class="d-inline">
                                    <input type="hidden" name="livro_id" value="<?= $livro['id'] ?>">
                                    <button type="submit" formaction="../app/reservar.php" class="btn btn-sm btn-primary" <?= $livro['quantidade'] > 0 ? '' : 'disabled' ?>>Reservar</button>
                                </form>
                                <form action="perfil.php" method="POST" class="d-inline">
                                    <input type="hidden" name="remover_livro_id" value="<?= $livro['id'] ?>">
                                    <button type="submit" class="btn btn-sm btn-danger" title="Remover da Lista">
                                        <i class="bi bi-trash-fill"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>

</main>

<footer class="text-center mt-5">
    <p>Contato: biblioetec@escola.com | Horário: 8h - 17h</p>
    <p>&copy; <?= date('Y'); ?> Biblioteca Escolar</p>
</footer>

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