<?php
require_once 'conexao.php'; // Inclui a conexão

// Verifica se o formulário foi enviado
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nome = $_POST['nome'];
    $email = $_POST['email'];
    $senha = $_POST['senha'];
    $confirma_senha = $_POST['confirma_senha']; // Pega o valor do novo campo

    // --- VERIFICAÇÃO DE SEGURANÇA NO BACK-END ---
    if ($senha !== $confirma_senha) {
        // Se as senhas não conferem, redireciona de volta com uma mensagem de erro
        header("Location: ../public/usuario.php?erro=senhas_nao_conferem");
        exit(); // Para a execução do script imediatamente
    }

    // Se as senhas conferem, continua com o processo de cadastro

    // Verifica se o e-mail já existe
    $stmt_check = $conn->prepare("SELECT id FROM usuarios WHERE email = :email");
    $stmt_check->bindValue(':email', $email);
    $stmt_check->execute();

    if ($stmt_check->rowCount() > 0) {
        // Se o e-mail já existe, redireciona com erro
        header("Location: ../public/usuario.php?erro=email_existente");
        exit();
    }

    // Criptografa a senha antes de salvar no banco
    $senha_hash = password_hash($senha, PASSWORD_DEFAULT);
    
    // (Opcional: Define o tipo do usuário. Padrão é 'comum')
    $tipo = 'comum';

    try {
        $sql = "INSERT INTO usuarios (nome, email, senha, tipo) VALUES (:nome, :email, :senha, :tipo)";
        $stmt = $conn->prepare($sql);

        $stmt->bindValue(':nome', $nome);
        $stmt->bindValue(':email', $email);
        $stmt->bindValue(':senha', $senha_hash);
        $stmt->bindValue(':tipo', $tipo);

        $stmt->execute();

        // Redireciona para a página de login com mensagem de sucesso
        header("Location: ../public/login.php?status=cadastro_sucesso");
        exit();

    } catch (PDOException $e) {
        // Em caso de erro no banco, redireciona com erro
        header("Location: ../public/usuario.php?erro=db_error");
        exit();
    }
} else {
    // Se o acesso não for via POST, redireciona para a página de cadastro
    header("Location: ../public/usuario.php");
    exit();
}
?>