<?php
session_start();
require "../app/conexao.php"; // Conexão com o banco

// VERIFICAÇÃO DE SEGURANÇA (Apenas admins podem acessar)
if (!isset($_SESSION['usuario_id']) || $_SESSION['usuario_tipo'] != 'admin') {
    header("Location: login.php?erro=acesso_negado");
    exit();
}

$mensagem = null;

// LÓGICA PARA EXCLUIR/CANCELAR A RESERVA
if (isset($_GET['id'])) {
    $emprestimo_id = (int) $_GET['id'];

    try {
        $conn->beginTransaction(); // Inicia uma transação para garantir consistência

        // 1. Busca o ID do livro antes de excluir a reserva
        $sql_busca_livro = "SELECT livro_id FROM emprestimos WHERE id = :id";
        $stmt_busca_livro = $conn->prepare($sql_busca_livro);
        $stmt_busca_livro->bindValue(':id', $emprestimo_id, PDO::PARAM_INT);
        $stmt_busca_livro->execute();
        $emprestimo = $stmt_busca_livro->fetch(PDO::FETCH_ASSOC);

        if ($emprestimo) {
            $livro_id = $emprestimo['livro_id'];

            // 2. Exclui a reserva da tabela 'emprestimos'
            $sql_del = "DELETE FROM emprestimos WHERE id = :id";
            $stmt_del = $conn->prepare($sql_del);
            $stmt_del->bindValue(':id', $emprestimo_id, PDO::PARAM_INT);
            $stmt_del->execute();

            // 3. Incrementa a quantidade do livro na tabela 'livros'
            $sql_update = "UPDATE livros SET quantidade = quantidade + 1 WHERE id = :livro_id";
            $stmt_update = $conn->prepare($sql_update);
            $stmt_update->bindValue(':livro_id', $livro_id, PDO::PARAM_INT);
            $stmt_update->execute();
            
            $conn->commit(); // Confirma as alterações se tudo deu certo
            $mensagem = "Reserva cancelada e livro devolvido ao acervo com sucesso!";
        } else {
            $mensagem = "Reserva não encontrada.";
            $conn->rollBack(); // Desfaz as alterações se a reserva não existir
        }
    } catch (PDOException $e) {
        $conn->rollBack(); // Desfaz as alterações em caso de erro
        $mensagem = "Erro ao cancelar a reserva: " . $e->getMessage();
    }
}

// LÓGICA PARA LISTAR TODOS OS EMPRÉSTIMOS
$sql_listar = "
    SELECT 
        e.id AS emprestimo_id, u.nome AS usuario_nome, l.titulo AS livro_titulo,
        e.data_agendamento, e.status
    FROM emprestimos AS e
    JOIN usuarios AS u ON e.usuario_id = u.id
    JOIN livros AS l ON e.livro_id = l.id
    ORDER BY e.data_agendamento DESC
";
$stmt_listar = $conn->query($sql_listar);
$emprestimos = $stmt_listar->fetchAll(PDO::FETCH_ASSOC);
$conn = null;


?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cancelar Reservas - BiblioEtec</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <link href="assets/CSS/excluir.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</head>
<body class="d-flex flex-column min-vh-100 bg-light">

    <?php include 'navbar.php'; ?>

    <main class="container my-5 flex-grow-1">
        <div class="text-center mb-5">
            <h1 class="display-5">Cancelar Reservas</h1>
            <p class="lead text-muted">Gerencie e cancele os empréstimos ativos no sistema.</p>
        </div>

        <?php if ($mensagem): ?>
            <div class="alert alert-info text-center" role="alert">
                <?= htmlspecialchars($mensagem) ?>
            </div>
        <?php endif; ?>

        <div class="card shadow-sm">
            <div class="card-body">
                <?php if (empty($emprestimos)): ?>
                    <div class="alert alert-success text-center">
                        <p class="h4">Nenhuma reserva ativa no momento.</p>
                    </div>
                <?php else: ?>
                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th>Livro</th>
                                    <th>Usuário</th>
                                    <th>Data do Agendamento</th>
                                    <th>Status</th>
                                    <th class="text-end">Ação</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($emprestimos as $emprestimo): ?>
                                    <tr>
                                        <td><?= htmlspecialchars($emprestimo['livro_titulo']) ?></td>
                                        <td><?= htmlspecialchars($emprestimo['usuario_nome']) ?></td>
                                        <td><?= date('d/m/Y', strtotime($emprestimo['data_agendamento'])) ?></td>
                                        <td>
                                            <span class="badge bg-warning text-dark">
                                                <?= ucfirst($emprestimo['status']) ?>
                                            </span>
                                        </td>
                                        <td class="text-end">
                                            <a href="excluir_reservas.php?id=<?= $emprestimo['emprestimo_id'] ?>" 
                                               class="btn btn-danger btn-sm" 
                                               onclick="return confirm('Tem certeza que deseja cancelar esta reserva? O livro será devolvido ao acervo.')">
                                                <i class="bi bi-x-circle-fill"></i> Cancelar
                                            </a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <div class="text-center mt-4">
        <a href="admin.php" class="btn btn-primary" style="background-color: #A67360!important;;">Voltar para o painel</a>
    </div>


    </main>

  <footer>
  <p>Contato: biblioetec@escola.com | Horário: 8h - 17h</p>
  <p>&copy; 2025 Biblioteca Escolar</p>
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