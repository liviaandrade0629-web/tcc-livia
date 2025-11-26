-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Tempo de geração: 05/08/2025 às 16:00
-- Versão do servidor: 10.4.28-MariaDB
-- Versão do PHP: 8.0.28

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Banco de dados: `cooperativa`
--

-- --------------------------------------------------------

--
-- Estrutura para tabela `itens_pedido`
--

CREATE TABLE `itens_pedido` (
  `id` int(11) NOT NULL,
  `pedido_id` int(11) DEFAULT NULL,
  `produto_id` int(11) DEFAULT NULL,
  `quantidade` int(11) DEFAULT NULL,
  `preco_unit` decimal(10,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `itens_pedido`
--

INSERT INTO `itens_pedido` (`id`, `pedido_id`, `produto_id`, `quantidade`, `preco_unit`) VALUES
(1, 1, 22, 1, 15.00),
(2, 2, 4, 1, 30.00);

-- --------------------------------------------------------

--
-- Estrutura para tabela `pedidos`
--

CREATE TABLE `pedidos` (
  `id` int(11) NOT NULL,
  `usuario_id` int(10) UNSIGNED DEFAULT NULL,
  `data` datetime DEFAULT current_timestamp(),
  `status` enum('pendente','entregue','cancelado') DEFAULT 'pendente'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `pedidos`
--

INSERT INTO `pedidos` (`id`, `usuario_id`, `data`, `status`) VALUES
(1, 2, '2025-07-31 22:37:20', 'pendente'),
(2, 2, '2025-08-05 08:06:24', 'pendente');

-- --------------------------------------------------------

--
-- Estrutura para tabela `produtos`
--

CREATE TABLE `produtos` (
  `id` int(11) NOT NULL,
  `nome` varchar(100) NOT NULL,
  `descricao` text DEFAULT NULL,
  `preco` decimal(10,2) NOT NULL,
  `estoque` int(11) DEFAULT 0,
  `categoria` enum('queijo','hortalica','carne','outros') NOT NULL,
  `imagem` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `produtos`
--

INSERT INTO `produtos` (`id`, `nome`, `descricao`, `preco`, `estoque`, `categoria`, `imagem`) VALUES
(3, 'Leite', 'valor Por litro', 12.00, 60, 'outros', '688d2e47b66c2.jpeg'),
(4, 'Queijo', 'Queijo pedaço', 30.00, 9, 'queijo', '688d2d3927004.jpg'),
(5, 'Queijo Fresco', 'Pequeno', 20.00, 10, 'queijo', '688d2d4230bf1.jpg'),
(6, 'Doce de Leite', 'Caseiro no pote', 15.00, 10, 'outros', '68920dc19f7bd.jpeg'),
(7, 'Ovos', 'Valor por Duzia', 7.00, 60, 'outros', '688d2e4e7fb6e.jpeg'),
(8, 'Porco', 'Valor do referente ao arroba\r\nPor pedaço', 0.00, 15, 'carne', '688d2d6c8f0b3.jpeg'),
(9, 'Linguiça', 'Por kilo', 25.00, 20, 'carne', '688d2d725c25e.jpeg'),
(10, 'Linguiça Defumada', 'Por kilo', 30.00, 20, 'carne', '688d2d7842c64.jpeg'),
(11, 'Defumados', 'Em geral por kilo', 35.00, 40, 'carne', '688d2d7f863ec.jpeg'),
(12, 'Gordura de Porco', 'Por Garrafa', 30.00, 40, 'outros', '688d2e741a1ee.jpg'),
(13, 'Carneiro', 'Por kilo', 35.00, 20, 'carne', '688d2d8fbd700.jpeg'),
(14, 'Mel', 'Por pote', 40.00, 20, 'outros', '688d2e638b2d2.jpeg'),
(15, 'Tilapia', 'Por porção', 15.00, 30, 'carne', '688d2e8648086.jpeg'),
(16, 'Pacu', 'Por kilo / sujo', 20.00, 50, 'carne', '688d2dcd76772.jpeg'),
(17, 'Mandioca', 'Por kilo', 5.00, 45, 'hortalica', '688d2d4da7ff5.jpeg'),
(18, 'Alface - Crespo', 'Por porção', 3.50, 40, 'hortalica', '688d2d557fb82.jpeg'),
(19, 'Alface - Roxo', 'Por porção', 3.50, 40, 'hortalica', '688d2d5c94a57.jpeg'),
(20, 'Alface Folha lisa', 'Por porção', 3.50, 40, 'hortalica', '688d2d6304ab6.jpeg'),
(21, 'Galinha caipira', 'Unitario', 15.00, 30, 'carne', '68920e228e32e.jpeg'),
(22, 'Frango de Granja', 'Por kilo', 15.00, 29, 'carne', '68920e4ab473c.jpeg');

-- --------------------------------------------------------

--
-- Estrutura para tabela `usuarios`
--

CREATE TABLE `usuarios` (
  `id` int(10) UNSIGNED NOT NULL,
  `nome` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `senha` varchar(255) NOT NULL,
  `tipo` enum('admin','cliente') DEFAULT 'cliente',
  `telefone` varchar(20) DEFAULT NULL,
  `endereco` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `usuarios`
--

INSERT INTO `usuarios` (`id`, `nome`, `email`, `senha`, `tipo`, `telefone`, `endereco`) VALUES
(1, 'Gustavo Henrique Genésio Da Silva', 'silvagus012@gmail.com', '$2y$10$CX8veDyRLbzpnrELksTWs.VnlxxiD6rTiiprlxzPW.9T01dOopRAq', 'admin', '999', 'Casa'),
(2, 'bea', 'bea@gmail.com', '$2y$10$auz2tJklYQbA5JBcV.zkzexIbWK9peD.V/zFW4QrDpeldSjvUlfey', 'cliente', '998', 'Casa');

--
-- Índices para tabelas despejadas
--

--
-- Índices de tabela `itens_pedido`
--
ALTER TABLE `itens_pedido`
  ADD PRIMARY KEY (`id`),
  ADD KEY `pedido_id` (`pedido_id`),
  ADD KEY `itens_pedido_ibfk_2` (`produto_id`);

--
-- Índices de tabela `pedidos`
--
ALTER TABLE `pedidos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `usuario_id` (`usuario_id`);

--
-- Índices de tabela `produtos`
--
ALTER TABLE `produtos`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT para tabelas despejadas
--

--
-- AUTO_INCREMENT de tabela `itens_pedido`
--
ALTER TABLE `itens_pedido`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de tabela `pedidos`
--
ALTER TABLE `pedidos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de tabela `produtos`
--
ALTER TABLE `produtos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT de tabela `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Restrições para tabelas despejadas
--

--
-- Restrições para tabelas `itens_pedido`
--
ALTER TABLE `itens_pedido`
  ADD CONSTRAINT `itens_pedido_ibfk_1` FOREIGN KEY (`pedido_id`) REFERENCES `pedidos` (`id`),
  ADD CONSTRAINT `itens_pedido_ibfk_2` FOREIGN KEY (`produto_id`) REFERENCES `produtos` (`id`) ON DELETE CASCADE;

--
-- Restrições para tabelas `pedidos`
--
ALTER TABLE `pedidos`
  ADD CONSTRAINT `pedidos_ibfk_1` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
