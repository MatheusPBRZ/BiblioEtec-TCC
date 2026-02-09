-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Tempo de geração: 11/10/2025 às 01:37
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
CREATE DATABASE IF NOT EXISTS `biblioteca` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `biblioteca`;

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
  `status` enum('pendente','reservado','devolucao_pendente','atrasado','concluido') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `usuario_id` (`usuario_id`),
  KEY `livro_id` (`livro_id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `lista_desejos`
--

DROP TABLE IF EXISTS `lista_desejos`;
CREATE TABLE IF NOT EXISTS `lista_desejos` (
  `id` int NOT NULL AUTO_INCREMENT,
  `usuario_id` int NOT NULL,
  `livro_id` int NOT NULL,
  `data_adicao` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `idx_usuario_livro_unico` (`usuario_id`,`livro_id`),
  KEY `usuario_id` (`usuario_id`),
  KEY `livro_id` (`livro_id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `livros`
--

DROP TABLE IF EXISTS `livros`;
CREATE TABLE IF NOT EXISTS `livros` (
  `id` int NOT NULL AUTO_INCREMENT,
  `titulo` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `autor` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `ano_publicacao` date NOT NULL,
  `genero` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `quantidade` int NOT NULL,
  `capa` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `sinopse` varchar(1000) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=33 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `livros`
--

INSERT INTO `livros` (`id`, `titulo`, `autor`, `ano_publicacao`, `genero`, `quantidade`, `capa`, `sinopse`) VALUES
(19, 'O Diário de Anne Frank', 'Anne Frank', '2021-08-17', 'Biografia, História', 5, 'upload/Arquivos/capa-10-10-2025-21-53-02-1325.jpg', 'Imagine um confinamento de mais de dois anos, num tempo em que não havia celular, internet e nem mesmo televisão. Foi essa a saída que um grupo de oito judeus buscou, no auge da Segunda Guerra Mundial, para escapar dos nazistas. Tempos difíceis, porém, cheios de esperança. Os detalhes de como passavam o dia, o que comiam, como faziam sua higiene e até se divertiam, foram registrados por uma garota em seu diário. Ela não sobreviveu ao Holocausto. Porém o Diário de Anne Frank sobreviveu ao tempo. Foi publicado pela primeira vez em 1947 e se tornou um dos livros mais lidos do mundo, traduzido para mais de 60 idiomas.'),
(20, 'Harry Potter e a Pedra Filosofal', 'Série Harry Potter por J.K. Rowling', '1997-01-01', 'Fantasia, Aventura', 5, 'upload/Arquivos/capa-10-10-2025-22-05-13-3228.jpg', 'Harry Potter, um garoto órfão que vive com seus tios desagradáveis, descobre em seu aniversário de onze anos que é um bruxo e que está destinado a frequentar a Escola de Magia e Bruxaria de Hogwarts. Lá, ele faz amigos, descobre um novo mundo e começa a desvendar os mistérios de seu passado e do bruxo das trevas que assassinou seus pais.'),
(21, 'Harry Potter e a Câmara Secreta', 'Série Harry Potter por J.K. Rowling', '1998-01-01', 'Fantasia, Aventura', 6, 'upload/Arquivos/capa-10-10-2025-22-07-14-7824.jpg', 'Sirius Black, um perigoso prisioneiro da fortaleza de Azkaban, escapa e tudo indica que ele está atrás de Harry. Para piorar, a escola passa a ser vigiada pelos Dementadores, seres sombrios que sugam a felicidade. Harry precisa descobrir a verdade sobre o passado de seus pais e a ligação de Black com sua família.'),
(22, 'Harry Potter e o Cálice de Fogo', 'Série Harry Potter por J.K. Rowling', '2000-01-01', 'Fantasia, Aventura', 7, 'upload/Arquivos/capa-10-10-2025-22-09-11-4100.jpg', 'Hogwarts sedia o Torneio Tribruxo, uma competição perigosa entre três escolas de magia. Misteriosamente, Harry é escolhido como o quarto competidor, mesmo sem ter idade para participar. Ele precisa enfrentar tarefas mortais enquanto o retorno de Lord Voldemort se torna cada vez mais iminente.'),
(23, 'Harry Potter e a Ordem da Fênix', 'Série Harry Potter por J.K. Rowling', '2003-01-01', 'Fantasia, Aventura', 9, 'upload/Arquivos/capa-10-10-2025-22-10-48-5638.jpg', 'Após o retorno de Voldemort, o Ministério da Magia se recusa a acreditar e inicia uma campanha para desacreditar Harry e Dumbledore. Uma nova e autoritária professora de Defesa Contra as Artes das Trevas é imposta em Hogwarts, forçando Harry a criar um grupo secreto para ensinar feitiços de defesa aos alunos.'),
(24, 'Crepúsculo', 'Saga Crepúsculo por Stephenie Meyer', '2005-01-01', 'Romance, Fantasia', 4, 'upload/Arquivos/capa-10-10-2025-22-13-09-6318.jpg', 'Isabella Swan se muda para a chuvosa cidade de Forks e se sente imediatamente atraída pelo misterioso e belo Edward Cullen. Ela logo descobre que Edward e sua família são vampiros, e seu amor proibido os coloca em grande perigo.'),
(25, 'Lua Nova', 'Saga Crepúsculo por Stephenie Meyer', '2006-01-01', 'Romance, Fantasia', 4, 'upload/Arquivos/capa-10-10-2025-22-15-10-7563.jpg', 'Após um incidente que quase expõe seu segredo, Edward deixa Bella para protegê-la. Com o coração partido, ela encontra consolo em sua amizade com Jacob Black, que também guarda um segredo sobrenatural: ele é um lobisomem, inimigo natural dos vampiros.'),
(27, 'Eclipse', 'Saga Crepúsculo por Stephenie Meyer', '2007-01-01', 'Romance, Fantasia', 4, 'upload/Arquivos/capa-10-10-2025-22-24-41-5086.jpg', 'Seattle é devastada por uma série de assassinatos misteriosos, e uma vampira vingativa continua sua busca por Bella. Em meio ao perigo, Bella é forçada a escolher entre seu amor por Edward e sua amizade com Jacob, sabendo que sua decisão pode reacender a guerra entre vampiros e lobisomens.'),
(28, 'Amanhecer', 'Saga Crepúsculo por Stephenie Meyer', '2008-01-01', 'Romance, Fantasia', 3, 'upload/Arquivos/capa-10-10-2025-22-25-58-4175.jpg', 'Bella e Edward finalmente se casam, mas a lua de mel é interrompida quando Bella descobre estar grávida de uma criança híbrida, cuja existência ameaça a paz do mundo sobrenatural e coloca a família Cullen na mira dos Volturi, o clã de vampiros mais poderoso.'),
(29, 'A Seleção', 'Série A Seleção por Kiera Cass', '2012-01-01', 'Romance', 11, 'upload/Arquivos/capa-10-10-2025-22-27-55-1930.jpg', ' Em um futuro distópico, a sociedade é dividida em castas. Para 35 garotas, a \"Seleção\" é a chance de escapar de sua casta e competir pelo coração do Príncipe Maxon. Para America Singer, no entanto, é um pesadelo que a força a deixar seu amor secreto para trás. Mas ao conhecer o príncipe, ela começa a questionar tudo o que planejava para seu futuro.'),
(30, 'A Elite', 'Série A Seleção por Kiera Cass', '2013-01-01', 'Romance', 4, 'upload/Arquivos/capa-10-10-2025-22-29-43-6247.jpg', 'America Singer está entre as seis garotas restantes na competição para se tornar a noiva do Príncipe Maxon. Conforme se aproxima da coroa, a competição se torna mais acirrada e ela se vê mais confusa sobre seus sentimentos por Maxon e seu ex-namorado, Aspen.'),
(31, 'A Escolha', 'Série A Seleção por Kiera Cass', '2014-01-01', 'Romance', 6, 'upload/Arquivos/capa-10-10-2025-22-30-44-6095.jpg', 'A competição chega ao seu clímax. America, agora uma das favoritas, precisa tomar sua decisão final entre o Príncipe Maxon e Aspen. Enquanto isso, a ameaça de rebeldes contra a monarquia se intensifica, e a escolha de America pode mudar o destino do país.'),
(32, 'A Sereia', 'Kiera Cass', '2009-01-01', 'Romance, Fantasia', 10, 'upload/Arquivos/capa-10-10-2025-22-32-28-2762.jpg', ' Kahlen foi salva de um naufrágio e se tornou uma sereia, servindo à Água por cem anos. Sua voz é fatal para os humanos. Quando ela conhece o gentil e belo Akinli, tudo o que ela sabe sobre seu destino é posto à prova, e ela está disposta a arriscar tudo por uma chance de amor.');

-- --------------------------------------------------------

--
-- Estrutura para tabela `usuarios`
--

DROP TABLE IF EXISTS `usuarios`;
CREATE TABLE IF NOT EXISTS `usuarios` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nome` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `email` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `senha` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `data_cadastro` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `tipo` enum('comum','admin') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT 'comum',
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `usuarios`
--

INSERT INTO `usuarios` (`id`, `nome`, `email`, `senha`, `data_cadastro`, `tipo`) VALUES
(1, 'Rafael', 'rafael098@gmail.com', '123456', '2025-09-17 05:19:00', 'admin'),
(2, 'Rafael', 'botacinirafael@gmail.com', '$2y$10$1102g9ahe5HiEYhzxZbVyOuKwFscb0bmV676.h5kUvoDotUIgevPu', '2025-09-17 05:27:09', 'admin'),
(3, 'gerson', 'gerson@gmail.com', '$2y$10$m5caUxkZJoWiovmwAwZvF.Cl3W9nWCyWFMb51Qre4kZMnR6cFvlsG', '2025-09-17 06:02:35', 'comum'),
(4, 'Gerson', 'ggg@gmail.com', '$2y$10$LIAPefpqZKzTF.sQig3VfuNp7NlhOZdpY.0w/NkwjxNstNs.QfpCO', '2025-09-19 20:05:43', 'comum'),
(5, 'tatu', 'tatu@gmail.com', '$2y$10$sH2y8qMpge6v5wTKmUw8N.y/VkfLakmk8DE02szNql1mxAORmTWXm', '2025-10-09 00:12:02', 'comum'),
(6, 'Matheus Passos', 'matheuspassos6677@gmail.com', '$2y$10$zzjJST1nQAyPU2iTgJIWX.9EqhfWU8Ar7bZ/RXvTKVqtN6ibmb6HO', '2025-10-09 02:53:35', 'admin'),
(7, 'Marcelo Passos', 'cqmarcelopassos@gmail.com', '$2y$10$aW.jTjl1mt/BWGPZ0.Cgg.7yqg8FwpzXVNNRNClEbb/YNJGi.4BgG', '2025-10-09 21:25:28', 'comum');

--
-- Restrições para tabelas despejadas
--

--
-- Restrições para tabelas `emprestimos`
--
ALTER TABLE `emprestimos`
  ADD CONSTRAINT `emprestimos_ibfk_1` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`),
  ADD CONSTRAINT `emprestimos_ibfk_2` FOREIGN KEY (`livro_id`) REFERENCES `livros` (`id`);

--
-- Restrições para tabelas `lista_desejos`
--
ALTER TABLE `lista_desejos`
  ADD CONSTRAINT `lista_desejos_ibfk_1` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `lista_desejos_ibfk_2` FOREIGN KEY (`livro_id`) REFERENCES `livros` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
