-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Tempo de geração: 07/05/2025 às 18:47
-- Versão do servidor: 10.4.32-MariaDB
-- Versão do PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

SET NAMES utf8mb4;

--
-- Banco de dados: `db_sorveteria`
--

-- --------------------------------------------------------

CREATE TABLE `categoriaproduto` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nome` varchar(255) NOT NULL,
  `marca` varchar(255) DEFAULT NULL,
  `descricao` text DEFAULT NULL,
  `idFornecedor` int(11) DEFAULT NULL,
  `foto` varchar(255) DEFAULT NULL,
  `desativado` int(1) DEFAULT 0,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

CREATE TABLE `cliente` (
  `idCliente` int(11) NOT NULL AUTO_INCREMENT,
  `desativado` tinyint(4) DEFAULT 0,
  `nome` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `senha` varchar(255) NOT NULL,
  `telefone` varchar(25) DEFAULT NULL,
  `perfil` char(4) DEFAULT 'CLIE',
  `idEndereco` int(11) DEFAULT NULL,
  PRIMARY KEY (`idCliente`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

CREATE TABLE `empresa` (
  `idEmpresa` int(11) NOT NULL AUTO_INCREMENT,
  `nomeFantasia` varchar(255) NOT NULL,
  `cnpj` varchar(14) NOT NULL,
  `idEndereco` int(11) NOT NULL,
  `telefone` varchar(15) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`idEmpresa`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

CREATE TABLE `enderecos` (
  `idEndereco` int(11) NOT NULL AUTO_INCREMENT,
  `cep` varchar(20) DEFAULT NULL,
  `rua` varchar(255) DEFAULT NULL,
  `numero` int(11) DEFAULT NULL,
  `complemento` varchar(255) DEFAULT NULL,
  `bairro` varchar(255) DEFAULT NULL,
  `cidade` varchar(225) DEFAULT NULL,
  `estado` varchar(225) DEFAULT NULL,
  PRIMARY KEY (`idEndereco`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

CREATE TABLE `entregador` (
  `idEntregador` int(11) NOT NULL AUTO_INCREMENT,
  `desativado` int(11) DEFAULT 0,
  `perfil` varchar(100) DEFAULT NULL,
  `nome` varchar(100) NOT NULL,
  `telefone` varchar(15) DEFAULT NULL,
  `email` varchar(100) NOT NULL,
  `senha` varchar(255) NOT NULL,
  `cnh` varchar(20) NOT NULL,
  PRIMARY KEY (`idEntregador`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

CREATE TABLE `estoque` (
  `idEstoque` int(11) NOT NULL AUTO_INCREMENT,
  `idCategoria` int(11) DEFAULT NULL,
  `idProduto` int(11) DEFAULT NULL,
  `lote` int(11) NOT NULL,
  `dtEntrada` date DEFAULT NULL COMMENT 'YYYY/MM/DD',
  `quantidade` int(11) DEFAULT 0,
  `dtFabricacao` date DEFAULT NULL COMMENT 'YYYY/MM/DD',
  `dtVencimento` date DEFAULT NULL COMMENT 'YYYY/MM/DD',
  `precoCompra` decimal(15,2) DEFAULT NULL,
  `qtdMinima` int(11) NOT NULL,
  `qtdVendida` int(11) DEFAULT NULL,
  `qtdOcorrencia` int(11) DEFAULT NULL,
  `ocorrencia` varchar(1024) DEFAULT NULL,
  `desativado` int(11) NOT NULL,
  PRIMARY KEY (`idEstoque`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

CREATE TABLE `fornecedor` (
  `idFornecedor` int(11) NOT NULL AUTO_INCREMENT,
  `nome` varchar(255) NOT NULL,
  `telefone` varchar(20) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `cnpj` varchar(20) NOT NULL,
  `desativado` int(11) DEFAULT NULL,
  `idEndereco` int(11) DEFAULT NULL,
  PRIMARY KEY (`idFornecedor`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;


--
-- Despejando dados para a tabela `categoriaproduto`
--

INSERT INTO `categoriaproduto` (`id`, `nome`, `marca`, `descricao`, `idFornecedor`, `foto`, `desativado`) VALUES
(1, 'Pote', 'Nestlé', 'Potes de Sorvete', 1, '98fb6a95c11ab1b4270121f66ced7c98.png', 1),
(2, 'Picolé', 'Marca', 'Picolé', 2, 'picoleLogo.png', 0),
(3, 'ChupChup', 'Garoto', 'ChupChup', 2, 'chupLogo.png', 0),
(4, 'Sundae', 'Nestle', 'Sundae', 2, 'sundaeLogo.png', 0),
(5, 'Açaí', 'AcaiGalaxy', 'Açai', 1, 'acaiLogo.png', 0);

-- --------------------------------------------------------

--
-- Despejando dados para a tabela `cliente`
--

INSERT INTO `cliente` (`idCliente`, `desativado`, `nome`, `email`, `senha`, `telefone`, `perfil`, `idEndereco`) VALUES
(0, 0, 'Cliente Desconhecido', 'desconhecido', '1234', NULL, 'CLIE', 1),
(1, 0, 'joao Lucas', 'jo@email.com', '$2y$10$VxfyRb4qZtF8nrk/BJs1NuvJy/sG5WxHGJFbyS9gjB7SQ6.lnI1yC', '(11) 94564-2135', 'CLIE', 1),
(2, 0, 'Caroliny Rocha Sampaio', 'carol@email.com', '$2y$10$VxfyRb4qZtF8nrk/BJs1NuvJy/sG5WxHGJFbyS9gjB7SQ6.lnI1yC', '44564-2132', 'CLIE', 5),
(3, 0, 'Joelita Rocha', 'joelita@email.com', '$2y$10$hMHoDvGNbpdT9285sSbvVOUD49txnbVnFGdr0aE6pKrYlHnKiFkNW', '(11) 99898-4901', 'CLIE', 6);


--
-- Despejando dados para a tabela `enderecos`
--

INSERT INTO `enderecos` (`idEndereco`, `cep`, `rua`, `numero`, `complemento`, `bairro`, `cidade`, `estado`) VALUES
(0, '00000-000', 'Rua Exemplo', NULL, NULL, NULL, NULL, NULL),
(1, '08110-520', 'Rua Edson de Carvalho', 150, '', 'Vila Alabama', 'São Paulo', 'SP'),
(2, '08110520', 'Rua Edson de Carvalho Guimarães', 19, NULL, 'Vila Alabama', 'São Paulo', 'SP'),
(3, '08110492', 'Rua Moisés José Pereira', 50, '', 'Vila Alabama', 'São Paulo', 'SP'),
(4, '08110640', 'Rua Raimundo Mendes Figueiredo', 152, '', 'Vila Alabama', 'São Paulo', 'SP'),
(5, '08110210', 'Rua Enseada das Garoupas', 401, '', 'Vila Silva Teles', 'São Paulo', 'SP'),
(6, '08110600', 'Rua São Sebastião do Tocantins', 123, 'Casa', 'Vila Imac.', 'São Paulo', 'SP'),
(14, '08110600', 'Rua Padre Montoya', 37, '', 'Vila Alabama', 'São Paulo', 'SP'),
(15, '08110600', 'Rua Padre Montoya', 37, '', 'Vila Alabama', 'São Paulo', 'SP');

-- --------------------------------------------------------

--
-- Despejando dados para a tabela `entregador`
--

INSERT INTO `entregador` (`idEntregador`, `desativado`, `perfil`, `nome`, `telefone`, `email`, `senha`, `cnh`) VALUES
(1, 0, 'ENTR', 'João Silva', '1234567890', 'joao.silva@email.com', '$2y$10$VxfyRb4qZtF8nrk/BJs1NuvJy/sG5WxHGJFbyS9gjB7SQ6.lnI1yC', '12345678900'),
(2, 0, 'ENTR', 'Maria Oliveira', '0987654321', 'maria.oliveira@example.com', '$2y$10$VxfyRb4qZtF8nrk/BJs1NuvJy/sG5WxHGJFbyS9gjB7SQ6.lnI1yC', '09876543210'),
(3, 0, 'ENTR', 'Carlos Santos', '1122334455', 'carlos.santos@example.com', '$2y$10$VxfyRb4qZtF8nrk/BJs1NuvJy/sG5WxHGJFbyS9gjB7SQ6.lnI1yC', '11223344550'),
(4, 0, 'ENTR', 'Ana Costa', '2233445566', 'ana.costa@example.com', '$2y$10$VxfyRb4qZtF8nrk/BJs1NuvJy/sG5WxHGJFbyS9gjB7SQ6.lnI1yC', '22334455660'),
(5, 0, 'ENTR', 'Pedro Lima', '3344556677', 'pedro.lima@example.com', '$2y$10$VxfyRb4qZtF8nrk/BJs1NuvJy/sG5WxHGJFbyS9gjB7SQ6.lnI1yC', '33445566770');

-- --------------------------------------------------------

--
-- Despejando dados para a tabela `estoque`
--

INSERT INTO `estoque` (`idEstoque`, `idCategoria`, `idProduto`, `lote`, `dtEntrada`, `quantidade`, `dtFabricacao`, `dtVencimento`, `precoCompra`, `qtdMinima`, `qtdVendida`, `qtdOcorrencia`, `ocorrencia`, `desativado`) VALUES
(1, 1, 1, 1, '2025-01-01', 11, '2025-01-01', '2025-12-31', 99.98, 5, NULL, NULL, '0', 1),
(2, 1, 2, 1, '2025-01-08', 11, '2024-09-18', '2025-04-09', 15.50, 10, NULL, 0, ' ', 0),
(3, 1, 3, 1, '2025-01-08', 63, '2024-06-06', '2025-01-24', 34.50, 10, NULL, NULL, NULL, 0),
(4, 3, 4, 1, '2025-01-08', 68, '2024-12-08', '2025-03-23', 3.99, 10, NULL, NULL, NULL, 0),
(5, 3, 5, 1, '2025-01-08', 82, '2024-03-19', '2025-04-30', 3.97, 10, NULL, NULL, NULL, 0),
(6, 3, 6, 1, '2025-01-08', 69, '2024-06-13', '2025-06-01', 3.99, 10, NULL, NULL, NULL, 1),
(7, 3, 7, 1, '2025-01-08', 30, '2024-12-09', '2025-04-12', 3.99, 10, NULL, NULL, NULL, 0),
(8, 2, 8, 1, '2025-01-08', 37, '2024-10-12', '2025-01-31', 7.98, 10, NULL, NULL, NULL, 0),
(9, 2, 9, 1, '2025-01-08', 92, '2024-11-08', '2025-01-27', 6.99, 10, NULL, NULL, NULL, 0),
(10, 2, 10, 1, '0000-00-00', 4, '2024-03-02', '2025-02-07', 7.99, 10, NULL, NULL, NULL, 0),
(11, 2, 11, 1, '2025-01-08', 28, '2024-02-27', '2025-04-09', 7.99, 10, NULL, NULL, NULL, 0),
(12, 4, 12, 1, '2025-01-08', 95, '2024-11-03', '2025-01-24', 16.99, 10, NULL, NULL, NULL, 0),
(13, 4, 13, 1, '2025-01-08', 90, '2024-10-17', '2025-03-28', 16.99, 10, NULL, NULL, NULL, 0),
(14, 1, 14, 1, '2025-01-08', 53, '2024-09-17', '2025-07-01', 36.50, 10, NULL, NULL, NULL, 0),
(15, 5, 15, 1, '2025-01-08', 93, '2024-04-29', '2025-05-17', 46.50, 10, NULL, NULL, NULL, 0),
(16, 5, 16, 1, '2025-01-08', 51, '2024-09-02', '2025-02-22', 46.50, 10, NULL, NULL, NULL, 0),
(17, 5, 17, 1, '2025-01-08', 23, '2024-09-06', '2025-01-15', 46.50, 10, NULL, NULL, NULL, 0),
(18, 5, 18, 1, '2025-01-08', 19, '2024-03-21', '2025-04-02', 37.50, 10, NULL, NULL, NULL, 0),
(19, 5, 19, 1, '2025-01-08', 93, '2024-10-22', '2025-03-03', 36.50, 10, NULL, NULL, NULL, 0),
(20, 5, 20, 1, '0000-00-00', 88, '2024-07-19', '2025-05-20', 40.00, 10, NULL, NULL, NULL, 0);

-- --------------------------------------------------------

--
-- Despejando dados para a tabela `fornecedor`
--

INSERT INTO `fornecedor` (`idFornecedor`, `nome`, `telefone`, `email`, `cnpj`, `desativado`, `idEndereco`) VALUES
(1, 'Sorvetes do Sull', '11 998986754', 'contato@sorvetesdosul.com.br', '12.345.678/0001-99', 0, 1),
(2, 'Gelados Tropical', '21987654321', 'vendas@geladostropical.com.br', '98.765.432/0001-11', 0, 2),
(3, 'Doces e Sorvetes Ltda', '(73) 98789-6087', 'info@docesesorvetes.com.br', '56.789.012/0001-55', 0, 3),
(4, 'IceDream Sorvetes', '31987654321', 'icecream@email.com', '23.456.789/0001-77', 0, 4),
(5, 'Delícias Geladas', '(98) 98925-4608', 'delicias_geladas@email.com', '34.567.890/0001-88', 0, 5);

-- --------------------------------------------------------

-- --------------------------------------------------------
-- Estrutura para tabela `funcionario`
--

CREATE TABLE `funcionario` (
  `idFuncionario` int(11) NOT NULL AUTO_INCREMENT,
  `desativado` tinyint(4) DEFAULT 0,
  `adm` tinyint(4) DEFAULT NULL,
  `perfil` char(4) DEFAULT 'FUNC',
  `nome` varchar(255) DEFAULT NULL,
  `telefone` varchar(25) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `senha` varchar(255) DEFAULT NULL,
  `idEndereco` int(11) DEFAULT NULL,
  PRIMARY KEY (`idFuncionario`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `funcionario`
--

INSERT INTO `funcionario` (`idFuncionario`, `desativado`, `adm`, `perfil`, `nome`, `telefone`, `email`, `senha`, `idEndereco`) VALUES
(1, 0, 1, 'FUNC', 'Jessica', '96309-85895', 'je@email.com', '$2y$10$VxfyRb4qZtF8nrk/BJs1NuvJy/sG5WxHGJFbyS9gjB7SQ6.lnI1yC', 1),
(3, 0, NULL, 'FUNC', 'Carol', '(11) 99999-9900', 'ca@email.com', '$2y$10$VxfyRb4qZtF8nrk/BJs1NuvJy/sG5WxHGJFbyS9gjB7SQ6.lnI1yC', NULL);

-- --------------------------------------------------------

-- --------------------------------------------------------
-- Estrutura para tabela `pedidos`
--

CREATE TABLE `pedidos` (
  `idPedido` int(11) NOT NULL AUTO_INCREMENT,
  `idCliente` int(11) NOT NULL,
  `dtPedido` datetime DEFAULT NULL,
  `dtPagamento` datetime DEFAULT NULL,
  `tipoFrete` int(11) DEFAULT 0 COMMENT '{ { 0, Retirada }, { 1, MOTOBOY } }',
  `idEndereco` int(11) DEFAULT NULL,
  `valorTotal` decimal(12,2) DEFAULT NULL,
  `dtCancelamento` datetime DEFAULT NULL,
  `motivoCancelamento` text DEFAULT NULL,
  `statusPedido` enum('Aguardando Confirmação','Preparando pedido','Aguardando Retirada','Aguardando Envio','A Caminho','Entregue','Concluído','Cancelado','Entrega Falhou') DEFAULT 'Aguardando Confirmação',
  `idEntregador` int(11) DEFAULT NULL,
  `frete` double DEFAULT NULL,
  `meioPagamento` enum('Cartão de Débito','Cartão de Crédito','Dinheiro') NOT NULL DEFAULT 'Dinheiro',
  `trocoPara` float DEFAULT NULL,
  PRIMARY KEY (`idPedido`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `pedidos`
--

INSERT INTO `pedidos` (`idPedido`, `idCliente`, `dtPedido`, `dtPagamento`, `tipoFrete`, `idEndereco`, `valorTotal`, `dtCancelamento`, `motivoCancelamento`, `statusPedido`, `idEntregador`, `frete`, `meioPagamento`, `trocoPara`) VALUES
(187, 1, '2025-01-04 23:01:35', NULL, 0, 1, 80.94, '2025-02-05 19:01:55', 'oiwe', 'Cancelado', NULL, 0, 'Cartão de Débito', NULL),
(188, 1, '2025-01-09 13:21:32', NULL, 1, 1, 73.21, '2025-02-05 19:02:38', 'sla', 'Cancelado', 2, 22.72, 'Cartão de Crédito', NULL),
(189, 1, '0000-00-00 00:00:00', NULL, 0, 1, 16.99, NULL, NULL, 'Concluído', NULL, 0, 'Cartão de Débito', NULL),
(190, 1, '2025-01-09 09:57:36', NULL, 0, 1, 25.99, '2025-03-18 20:12:02', 'Não tenho como retirar o produto', 'Cancelado', NULL, 0, 'Cartão de Crédito', NULL),
(191, 1, '2025-01-29 18:43:18', NULL, 0, 1, 25.99, NULL, NULL, 'Preparando pedido', NULL, 0, 'Cartão de Débito', NULL),
(192, 2, '2025-01-31 17:53:03', NULL, 0, 5, 25.99, NULL, NULL, 'Aguardando Confirmação', NULL, 0, 'Dinheiro', NULL),
(193, 1, '2025-01-31 17:54:50', NULL, 0, 1, 20.00, NULL, NULL, 'Preparando pedido', NULL, 0, 'Cartão de Crédito', NULL),
(194, 1, '2025-01-31 18:09:27', NULL, 0, 1, 20.00, NULL, NULL, 'Preparando pedido', NULL, 13, 'Cartão de Crédito', NULL),
(195, 0, '2025-01-31 18:12:23', NULL, 0, 1, 20.00, NULL, NULL, 'Aguardando Confirmação', NULL, 0, 'Cartão de Crédito', NULL),
(196, 1, '2025-02-07 18:23:21', NULL, 0, 1, 6.99, NULL, 'teste', 'Cancelado', NULL, 0, 'Dinheiro', 10),
(197, 0, '2025-02-07 18:24:09', NULL, 0, 1, 20.00, NULL, NULL, 'Aguardando Confirmação', NULL, 13, 'Dinheiro', NULL),
(198, 1, '2025-02-07 18:28:58', NULL, 1, 1, 6.99, NULL, NULL, 'Aguardando Confirmação', 1, 0, 'Dinheiro', NULL),
(199, 1, '2025-02-07 18:34:30', NULL, 1, 1, 3.99, NULL, NULL, 'Entregue', 3, 0, 'Dinheiro', 5),
(200, 1, '2025-02-07 18:38:32', NULL, 0, 1, 123.98, NULL, NULL, 'Preparando pedido', NULL, 0, 'Dinheiro', 200),
(201, 2, '2025-02-10 15:37:25', NULL, 1, 5, 27.77, NULL, NULL, 'Concluído', 1, 10.78, 'Cartão de Crédito', NULL),
(202, 1, '2025-05-06 17:17:46', NULL, 0, 1, 3.99, NULL, NULL, 'Preparando pedido', NULL, 0, 'Cartão de Crédito', NULL);

--
-- Acionadores `pedidos`
--
DELIMITER $$
CREATE TRIGGER `ValidarStatusPedidos` BEFORE UPDATE ON `pedidos` FOR EACH ROW BEGIN
    IF NEW.tipoFrete = 0 AND 
       NEW.statusPedido NOT IN ('Aguardando Confirmação', 'Preparando pedido', 'Aguardando Retirada', 'Concluído', 'Cancelado') THEN
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'Status inválido para pedidos com retirada';
    END IF;

    IF NEW.tipoFrete = 1 AND 
       NEW.statusPedido NOT IN ('Aguardando Confirmação', 'Aguardando Envio', 'Preparando Pedido', 'A Caminho', 'Entregue', 'Cancelado', 'Concluído', 'Entrega Falhou') THEN
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'Status inválido para pedidos com motoboy';
    END IF;
END
$$
DELIMITER ;

-- --------------------------------------------------------
-- Estrutura para tabela `produto`
--

CREATE TABLE `produto` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `desativado` tinyint(4) DEFAULT NULL,
  `nome` varchar(255) DEFAULT NULL,
  `preco` decimal(10,2) DEFAULT NULL,
  `foto` varchar(255) DEFAULT NULL,
  `categoria` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `produto`
--

INSERT INTO `produto` (`id`, `desativado`, `nome`, `preco`, `foto`, `categoria`) VALUES
(1, 1, 'Havaiano', 25.99, 'havaianoPote.png', 1),
(2, 1, 'Chocolitano', 22.50, 'chocolitanoPote.png', 1),
(3, 1, 'Milho Verde - Pote 2L', 34.50, 'milho-verdePote.png', 1),
(4, 0, 'ChupChup - Unicórnio', 3.99, 'unicornioChup.png', 3),
(5, 0, 'Chup Chup - Coco', 3.97, 'cocoChup.png', 3),
(6, 0, 'Chup Chup - Morango', 3.99, 'morangoChup.png', 3),
(7, 0, 'ChupChup - Maracujá', 3.99, 'maracujaChup.png', 3),
(8, 0, 'Picolé - Mousse de Doce de Leite', 7.98, 'mousse-doce-leitePicole.png', 2),
(9, 0, ' Picolé - Coraçãozinho', 6.99, '71724a9521477340ecde4400800ba580.png', 2),
(10, 0, 'Picolé - Açaí', 7.99, 'acaiPicole.png', 2),
(11, 0, 'Picolé - Flocos', 7.99, 'flocosPicole.png', 2),
(12, 0, 'Sundae - Morango', 16.99, 'morangoSundae.png', 4),
(13, 0, 'Sundae - Baunilha', 16.99, 'baunilhaSundae.png', 4),
(14, 1, 'Napolitano - Pote 2L', 36.50, 'napolitanoPote.png', 1),
(15, 0, 'Açai com banana', 46.50, 'acai-bananaAcai.png', 5),
(16, 0, 'Açai com morango', 46.50, 'acai-morangoAcai.png', 5),
(17, 0, 'Açai com leite', 46.50, 'acai-leitinhoAcai.png', 5),
(18, 0, 'Açai com iorgute', 37.50, 'acai-iogurteAcai.png', 5),
(19, 0, 'Açai com guarana', 36.50, 'acai-guaranaAcai.png', 5),
(20, 0, 'Açai Original', 40.00, 'acaiLogo.png', 5);

-- --------------------------------------------------------
-- Estrutura para tabela `itens_pedido`, com a chave estrangeira corrigida
CREATE TABLE `itens_pedido` (
  `idPedido` int(11) NOT NULL,
  `idProduto` int(11) NOT NULL,
  `quantidade` int(11) DEFAULT NULL,
  PRIMARY KEY (`idPedido`, `idProduto`),
  CONSTRAINT `fk_itens_pedido_pedidos` FOREIGN KEY (`idPedido`) REFERENCES `pedidos` (`idPedido`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_itens_pedido_produto` FOREIGN KEY (`idProduto`) REFERENCES `produto` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------
-- Despejando dados para a tabela `itens_pedido`
--

INSERT INTO `itens_pedido` (`idPedido`, `idProduto`, `quantidade`) VALUES
(187, 1, 1),
(187, 9, 3),
(187, 12, 2),
(188, 4, 1),
(188, 17, 1),
(189, 12, 1),
(190, 1, 1),
(191, 1, 1),
(192, 1, 1),
(193, 1, 1),
(193, 2, 1),
(194, 1, 1),
(194, 2, 1),
(195, 1, 1),
(195, 2, 1),
(196, 9, 1),
(197, 1, 1),
(197, 2, 1),
(198, 9, 1),
(199, 7, 1),
(200, 13, 1),
(200, 17, 1),
(200, 1, 1),
(200, 3, 1),
(201, 13, 1),
(1, 6, 0);


--
-- Índices para tabelas despejadas
--

--
-- Índices de tabela `categoriaproduto`
--
ALTER TABLE `categoriaproduto`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `cliente`
--
ALTER TABLE `cliente`

  ADD PRIMARY KEY (`idCliente`);

--
-- Índices de tabela `enderecos`
--
ALTER TABLE `enderecos`
  ADD PRIMARY KEY (`idEndereco`);

--
-- AUTO_INCREMENT para tabelas despejadas
--

--
-- AUTO_INCREMENT de tabela `cliente`
--
ALTER TABLE `cliente`
  MODIFY `idCliente` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
