<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Cadastro de Livro</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/js/bootstrap.bundle.min.js" integrity="sha384-k6d4wzSIapyDyv1kpU366/PK5hCdSbCRGRCMv+eplOQJWyd1fbcAu9OCUj5zNLiq" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="../public/assets/CSS/livro.css" />
</head>
<body>

<div class="container py-5">
    <div class="row">
        <div class="col-md-12 text-center">
            <h2>Cadastro de Livros com Pré-visualização</h2>
        </div>
    </div>
</div>

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-6 form-container">
            <form method="POST" enctype="multipart/form-data">
                <div class="mb-3">
                    <label for="titulo" class="form-label">Título</label>
                    <input type="text" class="form-control" name="titulo" required>
                </div>

                <div class="mb-3">
                    <label for="autor" class="form-label">Autor</label>
                    <input type="text" class="form-control" name="autor" required>
                </div>

                <div class="mb-3">
                    <label for="quantidade" class="form-label">Quantidade</label>
                    <input type="number" class="form-control" name="quantidade" required>
                </div>

                <div class="mb-3">
                    <label for="ano_publicacao" class="form-label">Ano de Publicação</label>
                    <input type="date" class="form-control" name="ano_publicacao" required>
                </div>

                <div class="mb-3">
                    <label for="genero" class="form-label">Gênero</label>
                    <input type="text" class="form-control" name="genero" required>
                </div>

                <div class="mb-3">
                    <label for="sinopse" class="form-label">Sinopse</label>
                    <input type="text" class="form-control" name="sinopse" required>
                </div>

                <div class="mb-3">
                    <label for="file" class="form-label">Capa do Livro (.jpg até 1MB)</label>
                    <input type="file" class="form-control" id="file" name="file" accept=".jpg" onchange="previewImage()" required>
                    <img id="preview" src="#" alt="Pré-visualização da capa" />
                </div>

                <button type="submit" name="BtnUpload" class="btn btn-primary w-100">Cadastrar</button>
                
            </form>
            
        </div>
    </div>
</div>
<div class="text-center mt-3">
                Voltar para o <a href="../public/livros.php" class="login-button">Catálogo</a>
            </div>
<div class="container py-3">
    <div class="row">
        <div class="col-md-12 text-center">
<?php
if (isset($_POST["BtnUpload"])) {
    require_once "conexao.php";

    $titulo = $_POST["titulo"];
    $autor = $_POST["autor"];
    $ano_publicacao = $_POST["ano_publicacao"];
    $genero = $_POST["genero"];
    $quantidade = $_POST["quantidade"];
    $sinopse = $_POST["sinopse"];

    $pasta = 'upload/Arquivos/';
    if (!is_dir($pasta)) mkdir($pasta, 0777, true);

    $arquivoTemp = $_FILES['file']['tmp_name'];
    $nomeOriginal = $_FILES['file']['name'];
    $ext = strtolower(pathinfo($nomeOriginal, PATHINFO_EXTENSION));
    $tamanho = $_FILES['file']['size'];

    if ($ext != 'jpg') {
        echo "<div class='alert alert-danger'>Arquivo inválido. Use apenas .jpg</div>";
        exit;
    }

    if ($tamanho > 1048576) {
        echo "<div class='alert alert-danger'>Arquivo muito grande. Máximo: 1MB</div>";
        exit;
    }

    date_default_timezone_set('America/Sao_Paulo');
    $novoNome = "capa_" . date("Ymd_His") . "_" . rand(1000, 9999) . "." . $ext;
    $destino = $pasta . $novoNome;

    if (move_uploaded_file($arquivoTemp, $destino)) {
        try {
            $sql = "INSERT INTO livros (titulo, autor, ano_publicacao, genero, quantidade, capa, sinopse)
                    VALUES (:titulo, :autor, :ano_publicacao, :genero, :quantidade, :capa, :sinopse)";
            $stmt = $conn->prepare($sql);
            $stmt->bindValue(':titulo', $titulo);
            $stmt->bindValue(':autor', $autor);
            $stmt->bindValue(':ano_publicacao', $ano_publicacao);
            $stmt->bindValue(':genero', $genero);
            $stmt->bindValue(':quantidade', $quantidade);
            $stmt->bindValue(':sinopse', $sinopse);
            $stmt->bindValue(':capa', $destino);
            $stmt->execute();

            echo "<div class='alert alert-success'>Livro cadastrado com sucesso!</div>";
        } catch (PDOException $e) {
            echo "<div class='alert alert-danger'>Erro ao gravar no banco: " . $e->getMessage() . "</div>";
        }
    } else {
        echo "<div class='alert alert-danger'>Erro ao mover o arquivo para o servidor.</div>";
    }
}
?>
        </div>
    </div>
</div>

<script>
function previewImage() {
    const input = document.getElementById('file');
    const preview = document.getElementById('preview');
    const file = input.files[0];

    if (file && file.type === "image/jpeg") {
        const reader = new FileReader();
        reader.onload = function (e) {
            preview.src = e.target.result;
            preview.style.display = 'block';
        }
        reader.readAsDataURL(file);
    } else {
        preview.style.display = 'none';
    }
}
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>