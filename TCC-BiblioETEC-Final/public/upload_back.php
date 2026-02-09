<?php
session_start();

// =========== ROTEADOR DE AÇÕES ===========

// AÇÃO 1: PROCESSAR LOGIN
if (isset($_POST["email"]) && isset($_POST["senha"])) {
    try {
        require_once "../app/conexao.php";
        $email = $_POST["email"];
        $senha = $_POST["senha"];
        $buscar = $conn->prepare("SELECT * FROM usuarios WHERE email = :email");
        $buscar->bindParam(':email', $email);
        $buscar->execute();
        
        if ($buscar->rowCount() == 1) {
            $usuario = $buscar->fetch(PDO::FETCH_ASSOC);
            if (password_verify($senha, $usuario["senha"])) {
                $_SESSION["usuario_id"] = $usuario["id"];
                $_SESSION["usuario_nome"] = $usuario["nome"];
                $_SESSION["usuario_tipo"] = $usuario["tipo"];
                
                // --- CÓDIGO NOVO: O Redirecionamento Inteligente ---
                if (isset($_POST['redirect']) && !empty($_POST['redirect'])) {
                    header("Location: " . $_POST['redirect']);
                } else {
                    header("Location: painel.php");
                }
                exit();

            } else {
                header("Location: login.php?erro=senha");
                exit();
            }
        } else {
            header("Location: login.php?erro=usuario");
            exit();
        }

    } catch (PDOException $erro) {
        header("Location: login.php?erro=db_error");
        exit();
    }
}

// AÇÃO 2: PROCESSAR CADASTRO DE LIVRO
// O CÓDIGO A SEGUIR PERMANECE INTACTO
else if (isset($_POST["BtnUpload"])) {
    // Pega o array de gêneros selecionados. Se nenhum for enviado, cria um array vazio.
    $generos_selecionados = $_POST['genero'] ?? [];
    // Junta os gêneros do array em um único texto, separados por vírgula e espaço.
    $genero_para_db = implode(', ', $generos_selecionados);
    
    // Configurações de upload
    $pasta = __DIR__ . '/upload/Arquivos/';
    $urlBase = 'upload/Arquivos/';
    if (!is_dir($pasta)) {
        mkdir($pasta, 0777, true);
    }
    if (isset($_FILES['file']) && $_FILES['file']['error'] == 0) {
        $arquivoTemp = $_FILES['file']['tmp_name'];
        $nomeArquivoOriginal = basename($_FILES['file']['name']);
        $tamanhoArquivo = $_FILES['file']['size'];
    } else {
        die("Erro: Nenhum arquivo foi enviado ou houve um erro no upload.");
    }
    $ext = strtolower(pathinfo($nomeArquivoOriginal, PATHINFO_EXTENSION));
    $permitidos = ['jpg', 'jpeg', 'png'];
    if (!in_array($ext, $permitidos) || $tamanhoArquivo > 5000000) {
        die("Arquivo inválido, muito grande ou formato não suportado.");
    }

    // Dados do formulário
    $titulo = $_POST['titulo'];
    $autor = $_POST['autor'];
    $quantidade_adicionar = (int)$_POST['quantidade'];

    try {
        require_once "../app/conexao.php";

        // 1. VERIFICA SE O LIVRO JÁ EXISTE
        $sql_verificar = "SELECT id, quantidade FROM livros WHERE titulo = :titulo AND autor = :autor";
        $stmt_verificar = $conn->prepare($sql_verificar);
        $stmt_verificar->bindValue(':titulo', $titulo);
        $stmt_verificar->bindValue(':autor', $autor);
        $stmt_verificar->execute();
        $livro_existente = $stmt_verificar->fetch(PDO::FETCH_ASSOC);
        $mensagem = "";
        
        if ($livro_existente) {
            // 2. SE EXISTE, ATUALIZA A QUANTIDADE (UPDATE)
            $nova_quantidade = $livro_existente['quantidade'] + $quantidade_adicionar;
            $sql_update = "UPDATE livros SET quantidade = :quantidade WHERE id = :id";
            $stmt_update = $conn->prepare($sql_update);
            $stmt_update->bindValue(':quantidade', $nova_quantidade);
            $stmt_update->bindValue(':id', $livro_existente['id']);
            $stmt_update->execute();
            
            unlink($arquivoTemp); // Descarta a nova capa, pois o livro já existe
            $mensagem = "Estoque do livro '" . htmlspecialchars($titulo) . "' atualizado com sucesso!";

        } else {
            // 3. SE NÃO EXISTE, CADASTRA O NOVO LIVRO (INSERT)
            date_default_timezone_set('America/Sao_Paulo');
            $nomeArquivo = "capa-" . date("d-m-Y-H-i-s") . "-" . rand(1000, 9999) . "." . $ext;
            $caminhoArquivo = $pasta . $nomeArquivo;
            $caminhoBanco = $urlBase . $nomeArquivo;
            
            if (move_uploaded_file($arquivoTemp, $caminhoArquivo)) {
                $sql_insert = "INSERT INTO livros (titulo, autor, ano_publicacao, genero, quantidade, capa, sinopse) 
                                 VALUES (:titulo, :autor, :ano_publicacao, :genero, :quantidade, :capa, :sinopse)";
                $stmt_insert = $conn->prepare($sql_insert);
                $stmt_insert->bindValue(':titulo', $titulo);
                $stmt_insert->bindValue(':autor', $autor);
                $stmt_insert->bindValue(':ano_publicacao', $_POST['ano_publicacao']);
                $stmt_insert->bindValue(':genero', $genero_para_db);
                $stmt_insert->bindValue(':quantidade', $quantidade_adicionar, PDO::PARAM_INT);
                $stmt_insert->bindValue(':capa', $caminhoBanco);
                $stmt_insert->bindValue(':sinopse', $_POST['sinopse']);
                $stmt_insert->execute();
                $mensagem = "Novo livro cadastrado com sucesso!";
            } else {
                throw new Exception("Falha ao mover o arquivo da capa.");
            }
        }

        // Exibe o pop-up de sucesso e redireciona
        echo "<script>
                    alert('" . addslashes($mensagem) . "');
                    window.location.href = 'livros.php';
                  </script>";
        exit();

    } catch (Exception $e) {
        if (isset($arquivoTemp) && file_exists($arquivoTemp)) {
            unlink($arquivoTemp);
        }
        die("Erro: " . $e->getMessage());
    }
}

// Se nenhum formulário conhecido for enviado, redireciona para a home.
else {
    header("Location: index.php");
    exit();
}
?>