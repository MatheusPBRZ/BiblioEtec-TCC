<?php
// 1. Inicia a sessão para ter acesso aos dados do usuário logado
session_start();
require_once 'conexao.php'; // Inclui a conexão com o banco

// 2. VERIFICAÇÃO DE SEGURANÇA:
// Garante que apenas usuários logados possam tentar reservar um livro.
if (!isset($_SESSION['usuario_id'])) {
    header("Location: ../public/login.php?erro=login_necessario");
    exit();
}

// 3. Verifica se o formulário foi enviado (método POST) e se o ID do livro foi recebido
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['livro_id'])) {

    // 4. CAPTURA E VALIDAÇÃO DOS DADOS:
    // Pega o ID do livro do formulário e garante que é um número inteiro
    $livro_id = filter_input(INPUT_POST, 'livro_id', FILTER_VALIDATE_INT);
    // Pega o ID do usuário que está LOGADO NA SESSÃO ATUAL
    $usuario_id = $_SESSION['usuario_id'];

    // Se o livro_id for inválido (não for um número), interrompe.
    if (!$livro_id) {
        header("Location: ../public/livros.php?erro=livro_invalido");
        exit();
    }

    try {
        
        // Garante que todas as operações sejam executadas com sucesso.
        $conn->beginTransaction();
       
        // "FOR UPDATE" bloqueia a linha para evitar que dois usuários reservem o último livro ao mesmo tempo.
        $sql_check = "SELECT quantidade FROM livros WHERE id = :livro_id FOR UPDATE";
        $stmt_check = $conn->prepare($sql_check);
        $stmt_check->bindValue(':livro_id', $livro_id);
        $stmt_check->execute();
        $livro = $stmt_check->fetch(PDO::FETCH_ASSOC);

        // Se o livro não for encontrado ou não tiver exemplares, cancela a transação.
        if (!$livro || $livro['quantidade'] <= 0) {
            $conn->rollBack(); // Desfaz a transação
            header("Location: ../public/livros.php?erro=livro_indisponivel");
            exit();
        }

        // Se o livro está disponível, diminui o número de exemplares.
        $sql_update = "UPDATE livros SET quantidade = quantidade - 1 WHERE id = :livro_id";
        $stmt_update = $conn->prepare($sql_update);
        $stmt_update->bindValue(':livro_id', $livro_id);
        $stmt_update->execute();

        // Inserir o registo de empréstimo na tabela 'emprestimos'
        $data_agendamento = date('Y-m-d'); // Data de hoje
        $data_devolucao = date('Y-m-d', strtotime('+14 days')); // Devolução em 14 dias
        $status = 'pendente';

        $sql_insert = "INSERT INTO emprestimos (usuario_id, livro_id, data_agendamento, data_devolucao, status) 
                       VALUES (:usuario_id, :livro_id, :data_agendamento, :data_devolucao, :status)";

        $stmt_insert = $conn->prepare($sql_insert);
        $stmt_insert->bindValue(':usuario_id', $usuario_id);
        $stmt_insert->bindValue(':livro_id', $livro_id);
        $stmt_insert->bindValue(':data_agendamento', $data_agendamento);
        $stmt_insert->bindValue(':data_devolucao', $data_devolucao);
        $stmt_insert->bindValue(':status', $status);
        $stmt_insert->execute();

        // Se tudo deu certo, salva as alterações no banco.
        $conn->commit();


        // Mensagem de sucesso
        header("Location: ../public/reservados.php?status=sucesso");
        exit();

    } catch (PDOException $e) {
        // Se qualquer operação falhar, desfaz toda a transação.
        if ($conn->inTransaction()) {
            $conn->rollBack();
        }
        
        header("Location: ../public/livros.php?erro=reserva_falhou");
        exit();
    }

} else {
    // Se alguém tentar acessar o arquivo diretamente, redireciona para a página de livros
    header("Location: ../public/livros.php");
    exit();
}
?>