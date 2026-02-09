<?php
session_start();
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastrar Livro - BiblioEtec</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.14.0-beta3/css/bootstrap-select.min.css">
    <link rel="stylesheet" href="assets/CSS/style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
</head>
<body>
  
<?php include 'navbar.php'; ?>

<div class="container-geral">
<main>
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card shadow p-4">
                    <h3 class="text-center mb-3">Cadastrar Livro</h3>
                    <form action="upload_back.php" method="post" enctype="multipart/form-data">  
                        
                        <div class="mb-3">
                            <label class="form-label">Título:</label>
                            <input type="text" class="form-control" name="titulo" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Autor:</label>
                            <input type="text" class="form-control" name="autor" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Ano de Publicação:</label>
                            <input type="date" class="form-control" name="ano_publicacao" required>
                        </div>

                        <div class="mb-3">
                            <label for="genero" class="form-label">Gênero(s):</label>
                            <select class="selectpicker form-control" id="genero" name="genero[]" multiple data-live-search="true" title="Selecione um ou mais gêneros..." required>
                                <option value="Ficção">Ficção</option>
                                <option value="Romance">Romance</option>
                                <option value="Fantasia">Fantasia</option>
                                <option value="Mistério">Mistério</option>
                                <option value="Aventura">Aventura</option>
                                <option value="Biografia">Biografia</option>
                                <option value="Suspense">Suspense</option>
                                <option value="Terror">Terror</option>
                                <option value="História">História</option>
                            </select>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Quantidade:</label>
                            <input type="number" class="form-control" name="quantidade" min="1" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Sinopse:</label>
                            <input type="text" class="form-control" name="sinopse" required>
                        </div>

                        <div class="mb-3 text-center">
                            <label for="file" class="form-label">Capa (JPG máx 5MB):</label>
                            <input type="file" class="form-control" name="file" id="file" accept="image/jpeg, image/png" required>
                            
                            <div class="d-flex justify-content-center mt-3">
                                <img id="preview" src="" alt="Pré-visualização" 
                                    style="display:none; max-width:120px; border:1px solid #ccc; 
                                            padding:5px; border-radius:8px;">
                            </div>
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="painel.php" class="btn btn-danger">Painel</a>
                            <a href="livros.php" class="btn btn-secondary">Consultar</a>
                            <button type="submit" name="BtnUpload" class="btn btn-primary">Salvar</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</main>
</div>

<footer>
    <p>Contato: biblioetec@escola.com | Horário: 8h - 17h</p>
    <p>&copy; <?php echo date('Y'); ?> Biblioteca Escolar</p>
</footer>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.14.0-beta3/js/bootstrap-select.min.js"></script>

<script>
    // Inicializa o bootstrap-select
    $('.selectpicker').selectpicker();

    // Script para pré-visualização da capa
    const fileInput = document.getElementById('file');
    const previewImage = document.getElementById('preview');

    fileInput.addEventListener('change', function() {
        const file = this.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(event) {
                previewImage.src = event.target.result;
                previewImage.style.display = 'block';
            }
            reader.readAsDataURL(file);
        }
    });
</script>

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