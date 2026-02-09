<?php
session_start();
require_once '../app/conexao.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (!isset($_SESSION['usuario_id']) || $_SESSION['usuario_tipo'] != 'admin') {
        header("Location: login.php?erro=acesso_negado");
        exit();
    }

    $emprestimo_id = filter_input(INPUT_POST, 'emprestimo_id', FILTER_VALIDATE_INT);
    $acao = filter_input(INPUT_POST, 'acao', FILTER_SANITIZE_STRING);

    if ($emprestimo_id && $acao) {
        try {
            $conn->beginTransaction();

            if ($acao == 'livro_retirado') {
                $sql_update = "UPDATE emprestimos SET status = 'devolucao_pendente' WHERE id = :emprestimo_id";
                $stmt_update = $conn->prepare($sql_update);
                $stmt_update->bindValue(':emprestimo_id', $emprestimo_id);
                $stmt_update->execute();

            } elseif ($acao == 'devolvido') {
                $sql_get_livro = "SELECT livro_id FROM emprestimos WHERE id = :emprestimo_id";
                $stmt_get_livro = $conn->prepare($sql_get_livro);
                $stmt_get_livro->bindValue(':emprestimo_id', $emprestimo_id);
                $stmt_get_livro->execute();
                $livro_id = $stmt_get_livro->fetchColumn();

                if ($livro_id) {
                    $sql_update_emprestimo = "UPDATE emprestimos SET status = 'concluido' WHERE id = :emprestimo_id";
                    $stmt_update_emprestimo = $conn->prepare($sql_update_emprestimo);
                    $stmt_update_emprestimo->bindValue(':emprestimo_id', $emprestimo_id);
                    $stmt_update_emprestimo->execute();

                    $sql_update_livro = "UPDATE livros SET quantidade = quantidade + 1 WHERE id = :livro_id";
                    $stmt_update_livro = $conn->prepare($sql_update_livro);
                    $stmt_update_livro->bindValue(':livro_id', $livro_id);
                    $stmt_update_livro->execute();
                }
            }
            
            $conn->commit();
            header("Location: reservados.php?update=sucesso");
            exit();

        } catch (PDOException $e) {
            $conn->rollBack();
            header("Location: reservados.php?update=erro");
            exit();
        }
    }
}

if (!isset($_SESSION['usuario_id'])) {
    header("Location: login.php?erro=acesso_negado");
    exit();
}

$sql = "
    SELECT 
        e.id AS emprestimo_id, u.nome AS usuario_nome, l.titulo AS livro_titulo,
        l.capa AS livro_capa, e.data_agendamento, e.data_devolucao, e.status
    FROM emprestimos AS e
    JOIN usuarios AS u ON e.usuario_id = u.id
    JOIN livros AS l ON e.livro_id = l.id
";

if ($_SESSION['usuario_tipo'] != 'admin') {
    $sql .= " WHERE e.usuario_id = :usuario_id";
}
$sql .= " ORDER BY e.data_agendamento DESC";

$stmt = $conn->prepare($sql);
if ($_SESSION['usuario_tipo'] != 'admin') {
    $stmt->bindValue(':usuario_id', $_SESSION['usuario_id']);
}
$stmt->execute();
$emprestimos = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gerenciamento de Empréstimos - BiblioEtec</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet"> 
    <link rel="stylesheet" href="assets/CSS/reservados.css">
    <style>
        body {  display: flex; 
                flex-direction: column; 
                min-height: 100vh; 
                background-color: #f8f9fa; 
            }

        main { flex-grow: 1; 
        }

        .capa-livro-tabela { width: 60px; 
            height: 90px; 
            object-fit: 
            cover; 
        }

    </style>
</head>
<body class="d-flex flex-column min-vh-100">

    <?php include 'navbar.php'; ?>

    <main class="container my-5">
        
        <?php if (isset($_GET['update'])): ?>
            <div class="alert <?php echo $_GET['update'] == 'sucesso' ? 'alert-success' : 'alert-danger'; ?> alert-dismissible fade show" role="alert">
                <?php echo $_GET['update'] == 'sucesso' ? 'Status atualizado com sucesso!' : 'Ocorreu um erro ao atualizar o status.'; ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>

        <div class="card shadow-sm">
            <div class="card-header bg-dark text-white d-flex justify-content-between align-items-center">
                <h1 class="h4 mb-0"><i class="bi bi-calendar-check-fill me-2"></i>
                    <?php echo ($_SESSION['usuario_tipo'] == 'admin') ? 'Gerenciamento de Empréstimos' : 'Meus Empréstimos'; ?>
                </h1>
            </div>
            <div class="card-body">
                <?php if (count($emprestimos) > 0): ?>
                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead>
                                <tr>
                                    <th>Capa</th> <th>Livro</th>
                                    <?php if ($_SESSION['usuario_tipo'] == 'admin'): ?><th>Usuário</th><?php endif; ?>
                                    <th>Data Reserva</th> <th>Prazo Devolução</th> <th>Status</th>
                                    <?php if ($_SESSION['usuario_tipo'] == 'admin'): ?><th class="text-center">Ações</th><?php endif; ?>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($emprestimos as $emprestimo): ?>
                                    <?php
                                        $status_atual = $emprestimo['status'];
                                        if ($status_atual == 'devolucao_pendente' && date('Y-m-d') > $emprestimo['data_devolucao']) {
                                            $status_atual = 'atrasado';
                                        }

                                        $classe_badge = '';
                                        switch($status_atual) {
                                            case 'pendente':
                                            case 'reservado':
                                                $classe_badge = 'bg-info text-dark';
                                                break;
                                            case 'devolucao_pendente':
                                                $classe_badge = 'bg-warning text-dark';
                                                break;
                                            case 'atrasado':
                                                $classe_badge = 'bg-danger';
                                                break;
                                            case 'concluido':
                                                $classe_badge = 'bg-success';
                                                break;
                                            default:
                                                $classe_badge = 'bg-secondary';
                                        }
                                    ?>
                                    <tr>
                                        <td><img src="<?php echo htmlspecialchars($emprestimo['livro_capa']); ?>" alt="Capa" class="img-thumbnail capa-livro-tabela"></td>
                                        <td><?php echo htmlspecialchars($emprestimo['livro_titulo']); ?></td>
                                        <?php if ($_SESSION['usuario_tipo'] == 'admin'): ?><td><?php echo htmlspecialchars($emprestimo['usuario_nome']); ?></td><?php endif; ?>
                                        <td><?php echo date('d/m/Y', strtotime($emprestimo['data_agendamento'])); ?></td>
                                        <td><?php echo $emprestimo['data_devolucao'] ? date('d/m/Y', strtotime($emprestimo['data_devolucao'])) : '-'; ?></td>
                                        <td><span class="badge <?php echo $classe_badge; ?>"><?php echo str_replace('_', ' ', ucfirst($status_atual)); ?></span></td>
                                        
                                        <?php if ($_SESSION['usuario_tipo'] == 'admin'): ?>
                                            <td class="text-center">
                                                <form action="reservados.php" method="POST" class="d-inline">
                                                    <input type="hidden" name="emprestimo_id" value="<?php echo $emprestimo['emprestimo_id']; ?>">
                                                    <?php 
                                                    if ($status_atual == 'pendente' || $status_atual == 'reservado'): ?>
                                                        <input type="hidden" name="acao" value="livro_retirado">
                                                        <button type="submit" class="btn btn-sm btn-primary">Livro Retirado</button>
                                                    <?php elseif ($status_atual == 'devolucao_pendente' || $status_atual == 'atrasado'): ?>
                                                        <input type="hidden" name="acao" value="devolvido">
                                                        <button type="submit" class="btn btn-sm btn-success">Devolvido</button>
                                                    <?php else: ?>
                                                        <span>-</span>
                                                    <?php endif; ?>
                                                </form>
                                            </td>
                                        <?php endif; ?>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php else: ?>
                    <div class="alert alert-info text-center">Nenhum empréstimo encontrado.</div>
                <?php endif; ?>
            </div>
        </div>
    </main>

    <footer>
        <p class="mb-0">Contato: biblioetec@escola.com | Horário: 8h - 17h</p>
        <p class="mb-0">&copy; <?php echo date('Y'); ?> Biblioteca Escolar</p>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/js/bootstrap.bundle.min.js"></script>



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