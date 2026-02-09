<?php
session_start();
require "../app/conexao.php"; // Conexão com o banco

// VERIFICAÇÃO DE SEGURANÇA (Apenas admins podem acessar)
if (!isset($_SESSION['usuario_id']) || $_SESSION['usuario_tipo'] != 'admin') {
    header("Location: login.php?erro=acesso_negado");
    exit();
}

$mensagem = null;

// LÓGICA PARA EXCLUIR O LIVRO (se um ID for passado na URL)
if (isset($_GET['id'])) {
    $id = (int) $_GET['id'];
    try {
        // 1) Verifica se há empréstimos vinculados ao livro
        $check = $conn->prepare("SELECT COUNT(*) FROM emprestimos WHERE livro_id = :id");
        $check->bindValue(':id', $id, PDO::PARAM_INT);
        $check->execute();
        $temEmprestimos = (int) $check->fetchColumn();

        if ($temEmprestimos > 0) {
            $mensagem = "Este livro possui {$temEmprestimos} empréstimo(s) ativos e não pode ser excluído.";
        } else {
            // 2) Busca o caminho da capa para remover o arquivo físico
            $q = $conn->prepare("SELECT capa FROM livros WHERE id = :id");
            $q->bindValue(':id', $id, PDO::PARAM_INT);
            $q->execute();
            $livro = $q->fetch(PDO::FETCH_ASSOC);

            // 3) Exclui o registro do livro no banco de dados
            $del = $conn->prepare("DELETE FROM livros WHERE id = :id");
            $del->bindValue(':id', $id, PDO::PARAM_INT);

            if ($del->execute()) {
                // 4) Remove o arquivo da capa do servidor
                if ($livro && !empty($livro['capa'])) {
                    $capaFisica = __DIR__ . '/' . $livro['capa']; // Caminho relativo à pasta public
                    if (file_exists($capaFisica)) {
                        @unlink($capaFisica);
                    }
                }
                $mensagem = "Livro excluído com sucesso!";
            } else {
                $mensagem = "Erro ao tentar excluir o livro do banco de dados.";
            }
        }
    } catch (PDOException $e) {
        $mensagem = "Erro de conexão com o banco: " . $e->getMessage();
    }
}

// LÓGICA PARA LISTAR TODOS OS LIVROS
$stmt = $conn->query("SELECT id, titulo, autor, capa FROM livros ORDER BY titulo ASC");
$livros = $stmt->fetchAll(PDO::FETCH_ASSOC);
$conn = null;
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Excluir Livros - BiblioEtec</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <link href="assets/CSS/excluir.css" rel="stylesheet"> </head>
<body class="d-flex flex-column min-vh-100">

    <?php include 'navbar.php'; ?>

    <main class="container my-5 flex-grow-1">
        <div class="text-center mb-5">
            <h1 class="display-5">Gerenciar Acervo</h1>
            <p class="lead text-muted">Selecione um livro para remover permanentemente do sistema.</p>
        </div>

        <?php if ($mensagem): ?>
            <div class="alert alert-info text-center" role="alert">
                <?= htmlspecialchars($mensagem) ?>
            </div>
        <?php endif; ?>

        <div class="bookshelf">
            <?php if (empty($livros)): ?>
                <div class="col-12 text-center mt-5">
                    <p class="h4 text-muted">Nenhum livro cadastrado no momento.</p>
                    <a href="upload_front.php" class="btn btn-primary mt-3">Cadastrar o Primeiro Livro</a>
                </div>
            <?php else: ?>
                <?php foreach ($livros as $livro): ?>
                    <div class="book">
                        <img src="<?= htmlspecialchars($livro['capa']) ?>" alt="Capa do livro <?= htmlspecialchars($livro['titulo']) ?>">
                        <div class="book-info">
                            <h5 class="book-title"><?= htmlspecialchars($livro['titulo']) ?></h5>
                            <p class="book-author"><?= htmlspecialchars($livro['autor']) ?></p>
                            <a href="excluir.php?id=<?= $livro['id'] ?>" 
                               class="btn btn-danger btn-sm w-100" 
                               onclick="return confirm('Tem certeza que deseja excluir o livro \'<?= addslashes(htmlspecialchars($livro['titulo'])) ?>\'? Esta ação não pode ser desfeita.')">
                                <i class="bi bi-trash-fill"></i> Excluir
                            </a>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>

        <div class="text-center mt-4">
        <a href="admin.php" class="btn btn-primary" style="background-color: #D9C6BF!important; color:black!important;">Voltar para o Painel</a>
    </div>

    </main>

   <footer>
  <p>Contato: biblioetec@escola.com | Horário: 8h - 17h</p>
  <p>&copy; 2025 Biblioteca Escolar</p>
  </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
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