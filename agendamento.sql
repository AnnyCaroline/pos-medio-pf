-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Tempo de geração: 20-Ago-2022 às 13:10
-- Versão do servidor: 10.4.24-MariaDB
-- versão do PHP: 8.1.6

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Banco de dados: `agendamento`
--

-- --------------------------------------------------------

--
-- Estrutura da tabela `consultas`
--

CREATE TABLE `consultas` (
  `id` int(11) NOT NULL,
  `data` date NOT NULL,
  `hora` time NOT NULL,
  `paciente` int(11) NOT NULL,
  `medico` int(11) NOT NULL,
  `sala` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Extraindo dados da tabela `consultas`
--

INSERT INTO `consultas` (`id`, `data`, `hora`, `paciente`, `medico`, `sala`) VALUES
(7, '2011-11-11', '11:00:00', 19, 18, 6),
(12, '2022-08-02', '11:11:00', 17, 18, NULL),
(13, '2022-08-03', '11:11:00', 17, 18, NULL);

-- --------------------------------------------------------

--
-- Estrutura da tabela `salas`
--

CREATE TABLE `salas` (
  `id` int(11) NOT NULL,
  `nome` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Extraindo dados da tabela `salas`
--

INSERT INTO `salas` (`id`, `nome`) VALUES
(1, 'A'),
(5, 'B'),
(6, '123'),
(7, 'C'),
(8, 'AAA'),
(10, 'Novo'),
(12, 'Novo1'),
(13, 'nNNN');

-- --------------------------------------------------------

--
-- Estrutura da tabela `usuarios`
--

CREATE TABLE `usuarios` (
  `id` int(11) NOT NULL,
  `nome` text NOT NULL,
  `email` text NOT NULL,
  `senha` text NOT NULL,
  `nivel` int(11) NOT NULL,
  `telefone` text NOT NULL,
  `ativo` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Extraindo dados da tabela `usuarios`
--

INSERT INTO `usuarios` (`id`, `nome`, `email`, `senha`, `nivel`, `telefone`, `ativo`) VALUES
(16, '111', 'adm@adm.com', '$2y$10$iEtHDK69yRXmZ9cgMB0.Oe1ANDQjB0any46CxyT7m9oPd5efa4lzC', 1, '(11', 1),
(17, 'Anny', 'annycarolinegnr@gmail.com', '$2y$10$iEtHDK69yRXmZ9cgMB0.Oe1ANDQjB0any46CxyT7m9oPd5efa4lzC', 3, '(11) 11111-1111', 1),
(18, 'Doutor  Leaandro', 'aaa@aaa.com', '$2y$10$iEtHDK69yRXmZ9cgMB0.Oe1ANDQjB0any46CxyT7m9oPd5efa4lzC', 2, '(11) 11111-1111', 1),
(19, 'bbb', 'bbb@bbb.com', '$2y$10$j4wqZibSwf0HTasamqn1/.T3FcMmk.2rRYcK553hFcVaCrzCaY1XG', 3, '', 0);

--
-- Índices para tabelas despejadas
--

--
-- Índices para tabela `consultas`
--
ALTER TABLE `consultas`
  ADD PRIMARY KEY (`id`),
  ADD KEY `paciente` (`paciente`),
  ADD KEY `medico` (`medico`),
  ADD KEY `sala` (`sala`);

--
-- Índices para tabela `salas`
--
ALTER TABLE `salas`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `nome` (`nome`) USING HASH;

--
-- Índices para tabela `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`) USING HASH;

--
-- AUTO_INCREMENT de tabelas despejadas
--

--
-- AUTO_INCREMENT de tabela `consultas`
--
ALTER TABLE `consultas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT de tabela `salas`
--
ALTER TABLE `salas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT de tabela `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- Restrições para despejos de tabelas
--

--
-- Limitadores para a tabela `consultas`
--
ALTER TABLE `consultas`
  ADD CONSTRAINT `medico` FOREIGN KEY (`medico`) REFERENCES `usuarios` (`id`) ON DELETE NO ACTION,
  ADD CONSTRAINT `paciente` FOREIGN KEY (`paciente`) REFERENCES `usuarios` (`id`),
  ADD CONSTRAINT `sala` FOREIGN KEY (`sala`) REFERENCES `salas` (`id`) ON DELETE NO ACTION;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
