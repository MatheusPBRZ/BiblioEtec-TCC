-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Tempo de geração: 18/09/2025 às 07:56
-- Versão do servidor: 9.1.0
-- Versão do PHP: 8.3.14

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Banco de dados: `biblioteca`
--

-- --------------------------------------------------------

--
-- Estrutura para tabela `emprestimos`
--

DROP TABLE IF EXISTS `emprestimos`;
CREATE TABLE IF NOT EXISTS `emprestimos` (
  `id` int NOT NULL AUTO_INCREMENT,
  `usuario_id` int DEFAULT NULL,
  `livro_id` int DEFAULT NULL,
  `data_agendamento` date DEFAULT NULL,
  `data_devolucao` date DEFAULT NULL,
  `status` enum('pendente','concluído','cancelado') COLLATE utf8mb4_general_ci DEFAULT 'pendente',
  PRIMARY KEY (`id`),
  KEY `usuario_id` (`usuario_id`),
  KEY `livro_id` (`livro_id`)
) ENGINE=InnoDB AUTO_INCREMENT=22 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `livros`
--

DROP TABLE IF EXISTS `livros`;
CREATE TABLE IF NOT EXISTS `livros` (
  `id` int NOT NULL AUTO_INCREMENT,
  `titulo` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `autor` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `ano_publicacao` date NOT NULL,
  `genero` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `quantidade` int NOT NULL,
  `capa` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `sinopse` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `livros`
--

INSERT INTO `livros` (`id`, `titulo`, `autor`, `ano_publicacao`, `genero`, `quantidade`, `capa`, `sinopse`) VALUES
(2, 'Zoio Vs. Kaique A Batalha do Século', 'Tatu Mantega', '2025-09-03', 'Aventura, Suspense, História', 6, 'upload/Arquivos/capa-16-09-2025-23-50-41-2335.jpg', 'O mestre do caos Zoio e o influenciador estratégico Kaique travam uma guerra de desafios insanos pelo título de Rei da Internet. Nesta batalha épica, tudo vale por likes e inscritos. Quem vencerá a Batalha do Século?'),
(3, 'Bolsonaro Preso', 'Tatu Mantega', '2025-09-03', 'Mistério, Suspense, Terror', 3, 'upload/Arquivos/capa-17-09-2025-00-12-05-5319.jpg', 'Após a prisão do ex-presidente, o Brasil mergulha no caos. Este thriller político investiga os bastidores da operação, as provas sigilosas e a guerra jurídica que se seguiu. Entre segredos e conspirações, o futuro da nação está em jogo.'),
(4, 'Super Xandão O Novo Presidente?', 'Tatu Mantega', '2025-09-03', 'Aventura, História', 2, 'upload/Arquivos/capa-17-09-2025-00-14-59-3322.jpg', 'Cansado da política tradicional, o Brasil busca um salvador e o encontra em Super Xandão. Esta sátira acompanha a ascensão do \"último herói da Terra\" à presidência, onde decretos são gravados na academia e a diplomacia é resolvida no supino.'),
(5, 'Redarnank O Anticristo', 'Tatu Mantega', '2025-09-03', 'Mistério, Suspense, Terror, História', 10, 'upload/Arquivos/capa-17-09-2025-00-19-16-3610.jpg', 'Quando a esperança se torna uma ilusão, uma figura ascende das sombras: Redarnank. Ele não busca destruir o mundo, mas a fé que o sustenta. Este conto sombrio explora a chegada do AntiCristo, que conquista almas com a verdade nua e o abraço do vazio.'),
(6, 'UI UX Design Full Course | UI UX Course | UI UX Training', 'Intellipaat', '2024-02-18', 'Biografia', 3, 'upload/Arquivos/capa-17-09-2025-15-54-23-3411.jpg', 'Domine o UI/UX Design do zero ao profissional. Este curso completo aborda desde os fundamentos de usabilidade e pesquisa até a criação de interfaces impactantes e protótipos interativos. Transforme suas ideias em experiências digitais inesquecíveis.'),
(7, 'Os Discípulos de Gerson Visagista', 'Tatu Mantega', '2000-12-13', 'Ficção, Romance, Fantasia, Mistério, Aventura, Biografia, Suspense, Terror, História', 12, 'upload/Arquivos/capa-17-09-2025-17-29-21-6529.jpg', 'Na barbearia do mestre Gerson, os aprendizes Tatu e Mantega transformam cortes de cabelo em desastres cômicos. Para se provarem dignos, eles aceitam a missão de suas vidas: cuidar do visual de uma noiva no dia do casamento. O que pode dar errado?'),
(8, 'Daniel Pintor: Entre Pinceladas e Respingos', 'Tatu Mantega', '2000-12-13', 'Romance, História', 8, 'upload/Arquivos/capa-17-09-2025-17-36-20-1135.jpg', 'Daniel é um pintor em busca da pincelada perfeita, mas sua vida é feita de respingos: um amor conturbado, prazos impossíveis e uma crise criativa. Preso entre a arte que almeja e a realidade caótica, ele precisa encontrar beleza na imperfeição.'),
(9, 'Mais Que Uma Relação Aluno e Professor', 'Tatu Mantega', '2000-12-08', 'Romance, Aventura', 4, 'upload/Arquivos/capa-17-09-2025-17-41-40-4910.jpeg', 'O jovem Bulldog busca o mestre Raul para aperfeiçoar suas técnicas de musculação. Mais que um instrutor, Raul é encantador e o fascínio de Bulldog transcende a admiração, transformando a busca por conhecimento em uma paixão inesperada.'),
(11, 'Os Dois Lados da Mesma Parede', 'Tatu Mantega', '2014-02-12', 'Romance, Biografia, História', 5, 'upload/Arquivos/capa-17-09-2025-17-45-06-9817.jpg', 'Separados por uma parede, dois estranhos se encontram no olhar. Uma conexão silenciosa floresce entre eles, provando que a verdadeira intimidade não precisa de palavras. Eles descobrem que, mesmo com uma barreira, seus corações estão nos dois lados da mes'),
(12, 'O Beiço e a Tesoura', 'Tatu Mantega', '2018-07-28', 'Romance, Mistério, Suspense', 18, 'upload/Arquivos/capa-17-09-2025-17-48-07-4671.jpg', 'O renomado visagista Gerson se depara com um desafio: o influenciador Rômolo, com seu beiço irresistível. Entre cortes e paixão, a trama se aprofunda e o mestre se arrisca a perder mais que um cliente. Será que a tesoura de Gerson tem poder sobre o coraçã'),
(13, 'A Recuperação de Artes', 'Tatu Mantega', '2018-07-28', 'Aventura, Suspense', 2, 'upload/Arquivos/capa-17-09-2025-17-55-12-9501.jpg', 'Após um projeto desastroso, os amigos Mantega, Bota, Redarnank e Nikito ficam à beira da reprovação em Artes. Para se salvarem, eles enfrentam o desafio final da professora Renata: criar uma obra de arte única que capture a essência da amizade.'),
(14, 'A Redenção de Braz Adventure', 'Tatu Mantega', '2024-10-31', 'Mistério, Aventura, História', 11, 'upload/Arquivos/capa-17-09-2025-17-58-20-3710.jpg', 'Para escapar da reprovação, o grupo de amigos Mantega, Bota, Diego e Nikito se une em uma última missão: conquistar o professor Sérgio. Eles precisam transformar a vida de Braz Adventure, um antigo ícone da internet, em um documentário.'),
(15, 'Japanese Girl Meet\'s Brazil', 'Tatu Mantega', '2024-05-08', 'Romance, Mistério, Aventura, Suspense', 7, 'upload/Arquivos/capa-17-09-2025-18-01-03-4305.jpg', 'Bota, uma linda estudante japonesa, chega ao Brasil e conhece Nikito, um local tão reservado quanto ela. Entre as barreiras da cultura e da timidez, os dois descobrem uma conexão profunda, provando que o amor pode florescer até mesmo no mais silencioso do'),
(16, 'Sérgio Bento na ETEC', 'Tatu Mantega', '2024-11-08', 'Fantasia, Aventura', 8, 'upload/Arquivos/capa-17-09-2025-18-04-28-6140.jpg', 'Após Sérgio Bento chegar na ETEC, é surpreendido ao ser rodeado de alunos veteranos, mas não se abalou com isso, pelo contrário, deu uma palinha de sua música \'\'Arara Azul da Etec\", onde diz que Rodolpho está no céu orando por nós.'),
(17, 'A Fuga do Relógio de César 10', 'Tatu Mantega', '2024-11-13', 'Ficção, Fantasia, Aventura', 12, 'upload/Arquivos/capa-17-09-2025-18-07-31-3573.jpg', 'Os aliens do relógio de nosso herói César 10 acabando fugindo por um erro de programação no relógio feito por Redarnank. Os aliens, livres e soltos na sociedade, causam um fuzuê e acabam parando na Etec de Rio Pardo, tendo até que agir como alunos realiza'),
(18, 'Pescaria Ecológica', 'Tatu Mantega', '2023-10-21', 'Mistério, Aventura, Suspense', 13, 'upload/Arquivos/capa-17-09-2025-18-15-16-3953.jpg', 'Os jovens Bota, RodrigoHacker e Mantega saem para uma aventura, porém, acabam se deparando com desafios ao longo do caminho, incluindo uma pesca misteriosa. Eles concluem o desafio da pesca, mas algo que os supreendem aparece. O que será que está por vir?');

-- --------------------------------------------------------

--
-- Estrutura para tabela `usuarios`
--

DROP TABLE IF EXISTS `usuarios`;
CREATE TABLE IF NOT EXISTS `usuarios` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nome` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `email` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `senha` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `data_cadastro` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `tipo` enum('comum','admin') COLLATE utf8mb4_general_ci DEFAULT 'comum',
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `usuarios`
--

INSERT INTO `usuarios` (`id`, `nome`, `email`, `senha`, `data_cadastro`, `tipo`) VALUES
(1, 'Rafael', 'rafael098@gmail.com', '123456', '2025-09-17 02:19:00', 'admin'),
(2, 'Rafael', 'botacinirafael@gmail.com', '$2y$10$1102g9ahe5HiEYhzxZbVyOuKwFscb0bmV676.h5kUvoDotUIgevPu', '2025-09-17 02:27:09', 'admin'),
(3, 'gerson', 'gerson@gmail.com', '$2y$10$m5caUxkZJoWiovmwAwZvF.Cl3W9nWCyWFMb51Qre4kZMnR6cFvlsG', '2025-09-17 03:02:35', 'comum');

--
-- Restrições para tabelas despejadas
--

--
-- Restrições para tabelas `emprestimos`
--
ALTER TABLE `emprestimos`
  ADD CONSTRAINT `emprestimos_ibfk_1` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`),
  ADD CONSTRAINT `emprestimos_ibfk_2` FOREIGN KEY (`livro_id`) REFERENCES `livros` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
