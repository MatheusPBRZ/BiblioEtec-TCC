<?php
session_start(); // Inicia a sessão para que a navbar funcione corretamente
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sobre Nós - BiblioEtec</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="assets/CSS/sobre.css">
    <link rel="stylesheet" href="assets/CSS/estilo_font.css">
</head>
<body>

    <?php
    include 'navbar.php';
    ?>

    <div class="container-geral">
        <main>
            <header class="sobre-header text-center text-white py-5">
                <div class="container">
                    <h1 class="display-4 fw-bold">Sobre o Projeto BiblioEtec</h1>
                    <p class="lead">Modernizando o acesso à cultura e ao conhecimento.</p>
                </div>
            </header>

            <section class="sobre-section py-5">
                <div class="container">
                    <div class="row align-items-center">
                        <div class="col-lg-6">
                            <h2>Nossa Missão</h2>
                            <p>
                                O projeto BiblioEtec foi desenvolvido como um Trabalho de Conclusão de Curso com o objetivo de criar uma solução digital para o gerenciamento do acervo da biblioteca da nossa Etec. A nossa missão é fornecer uma plataforma onde os alunos possam pesquisar, descobrir e reservar livros de forma simples e intuitiva.
                            </p>
                            <p>
                                Acreditamos que a tecnologia é uma poderosa aliada da educação. Ao facilitar o acesso aos livros, incentivamos o hábito da leitura e apoiamos a jornada de aprendizado de cada estudante, tornando o conhecimento mais acessível a todos.
                            </p>
                        </div>
                        <div class="col-lg-6 text-center">
                            <img src="assets/imagens/livro.png" class="img-fluid rounded-circle shadow-lg" alt="Ilustração de um livro" style="width: 250px; height: 250px; object-fit: cover;">
                        </div>
                    </div>
                </div>
            </section>

             <section class="team-section text-center py-5">
                <div class="container">
                    <h2 class="mb-5">A Equipe</h2>
                    <div class="row justify-content-center">
                        <div class="col-md-4">
                            <div class="team-member">
                                <img src="assets/imagens/iconeusuario.png" class="team-photo" alt="Foto do Matheus">
                                <h5 class="mt-4">Matheus Passos</h5>
                                <p class="text-muted">Responsável por:
                                Full stack
                                </p>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="team-member">
                                <img src="assets/imagens/iconeusuario.png" class="team-photo" alt="Foto do Rafael ">
                                <h5 class="mt-4">Rafael Botacini</h5>
                                <p class="text-muted">Responsável por:
                                Full stack
                                </p>
                            </div>
                            </div>

                            <div class="col-md-3">
                            <div class="team-member">
                                <img src="assets/imagens/iconeusuario.png" class="team-photo" alt="Foto da Marjorie ">
                                <h5 class="mt-4">Marjorie Carolinne</h5>
                                <p class="text-muted">Responsável por:
                                Parte escrita do Trabalho de Conclusão de Curso (TCC)
                                </p>
                            </div>
                            </div>

                            <div class="col-md-4">
                            <div class="team-member">
                                <img src="assets/imagens/iconeusuario.png" class="team-photo" alt="Foto do Miguel ">
                                <h5 class="mt-4">Miguel da Rocha</h5>
                                <p class="text-muted">Responsável por:
                                Acabamento Final
                                </p>
                            </div>
                            </div>
            
                            <div class="col-md-4">
                            <div class="team-member">
                            <img src="assets/imagens/iconeusuario.png" class="team-photo" alt="Foto do Renan ">
                            <h5 class="mt-4">Renan Azarias</h5>
                            <p class="text-muted">Responsável por:
                            Acabamento Final
                            </p>
                            </div>
                        </div>







                        
                    </div>
                </div>
            </section>
        </main>
    </div>

    <footer>
        <p>Contato: biblioetec@escola.com | Horário: 8h - 17h</p>
        <p>&copy; <?= date('Y') ?> Biblioteca Escolar</p>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/js/bootstrap.bundle.min.js">

//Vlibras - Plugin//  
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