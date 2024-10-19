-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Tempo de geração: 15/10/2024 às 02:45
-- Versão do servidor: 10.4.32-MariaDB
-- Versão do PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Banco de dados: `bd_sorveteria`
--

DELIMITER $$
--
-- Procedimentos
--
CREATE DEFINER=`root`@`localhost` PROCEDURE `AtualizarCliente` (IN `emailIN` VARCHAR(255), IN `newEmailIN` VARCHAR(60), IN `nomeIN` VARCHAR(50), IN `telefoneIN` VARCHAR(25), IN `ruaEnd` VARCHAR(255), IN `numeroEnd` INT, IN `complementoEnd` VARCHAR(255), IN `bairroEnd` VARCHAR(255), IN `cepEnd` VARCHAR(20), IN `cidadeEnd` VARCHAR(255), IN `estadoEnd` VARCHAR(255))   BEGIN
	IF NOT EXISTS (SELECT nome from clientes where email like emailIN)
	THEN
		SELECT '403' AS 'Status', 'ERROR_EMAIL_NAO_ENCONTRADO' AS 'Error', '' AS 'Message';
	ELSE
		SET @enderecoId := (SELECT idEndereco FROM clientes WHERE email like emailIN LIMIT 1);
		UPDATE endereco SET
			cep = cepEnd, 
			rua = ruaEnd, 
			numero = numeroEnd, 
			complemento = complementoEnd, 
			bairro = bairroEnd,
            cidade = cidadeEnd,
            estado = estadoEnd
            WHERE idEndereco = @enderecoId;
		UPDATE clientes SET
			nome = nomeIN,
			email = newEmailIN,
			telefone = telefoneIN
			WHERE email like emailIN;
		SELECT 
			'204' AS 'Status',
			'' AS 'Error',
			'SUCCESS_UPDATED' AS 'Message';
	END IF; 
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `CancelarPedido` (`idPedidoIN` INT, `DtCancelamentoIN` DATETIME, `motivoCancelamentoIN` VARCHAR(255))   BEGIN
    IF NOT EXISTS (SELECT * FROM pedidos WHERE idPedido = idPedidoIN)
    THEN
        SELECT '403' AS 'Status', 'ERROR_PEDIDO_NAO_ENCONTRADO' AS 'Error', '' AS 'Message';
    ELSE
        UPDATE pedidos SET DtCancelamento = DtCancelamentoIN, motivoCancelamento = motivoCancelamentoIN WHERE idPedido = idPedidoIN;
        SELECT 
            '201' AS 'Status',
            '' AS 'Error',
            'SUCCESS_CREATED' AS 'Message';
    END IF;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `DeletarClientePorEmail` (IN `emailIN` VARCHAR(255))   BEGIN
	IF NOT EXISTS (SELECT email from clientes where email like emailIN)
	THEN
		SELECT '403' AS 'Status', 'ERROR_CLIENTE_NAO_ENCONTRADO' AS 'Error', '' AS 'Message';
	ELSE
		SET @enderecoId := (SELECT idEndereco FROM cliente WHERE email like emailIN LIMIT 1);
		UPDATE endereco SET
			cep = NULL, 
			rua = NULL, 
			numero = NULL, 
			complemento = NULL, 
			bairro = NULL,
            cidade = NULL,
            estado = NULL
            WHERE idEndereco = @enderecoId;
        UPDATE cliente SET
			desativado = 1,
			nome = NULL,
			email = NULL,
            senha = NULL,
			telefone = NULL
			WHERE email like emailIN;
        SELECT
			'204' AS 'Status',
			'' AS 'Error',
			'SUCCESS_DELETED' AS 'Message';
	END IF;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `DesativarCliente` (`emailIN` VARCHAR(255))   BEGIN
    IF NOT EXISTS (SELECT email from clientes where email like emailIN)
    THEN
        SELECT '403' AS 'Status', 'ERROR_CLIENTE_NAO_ENCONTRADO' AS 'Error', '' AS 'Message';
    ELSE
        SET @enderecoId := (SELECT idEndereco FROM cliente WHERE email like emailIN LIMIT 1);
        UPDATE endereco SET
            cep = NULL, 
            rua = NULL, 
            numero = NULL, 
            complemento = NULL, 
            bairro = NULL,
            cidade = NULL,
            estado = NULL
            WHERE idEndereco = @enderecoId;
        UPDATE cliente SET desativado = 1 WHERE email like emailIN;
        SELECT
            '204' AS 'Status',
            '' AS 'Error',
            'SUCCESS_DELETED' AS 'Message';
    END IF;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `DesativarEstoqueProdutoPorId` (IN `idEstoqueIN` INT, IN `idProdutoIN` INT)   BEGIN
	IF NOT EXISTS (SELECT codigoProduto from estoque where idEstoque like idEstoqueIN)
	THEN
		SELECT '403' AS 'Status', 'ERROR_PRODUTO_INEXISTENTE' AS 'Error', '' AS 'Message';
    ELSE
        UPDATE estoque SET desativado = 1 WHERE idEstoque = idEstoqueIN;
        UPDATE produtos SET desativado = 1 WHERE idProduto = idProdutoIN;
        UPDATE variacaoProduto SET desativado = 1 WHERE idProduto = idProdutoIN;
    END IF;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `DesativarFornecedorPorId` (IN `idFornecedorIN` INT)   BEGIN
    IF NOT EXISTS (SELECT idFornecedor FROM fornecedores WHERE idFornecedor like idFornecedorIN AND desativado != 1)
    THEN
        SELECT '403' AS 'Status', 'ERROR_FORNECEDOR_NAO_ENCONTRADO' AS 'Error', '' AS 'Message';
    ELSE
        UPDATE fornecedores SET desativado = 1 WHERE idFornecedor = idFornecedorIN;
        SELECT
            '204' AS 'Status',
            '' AS 'Error',
            'SUCCESS_DELETED' AS 'Message';
    END IF;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `DesativarFuncionarioPorEmail` (IN `emailIN` VARCHAR(255))   BEGIN
	IF NOT EXISTS (SELECT email FROM funcionarios WHERE email like emailIN AND desativado != 1)
	THEN
		SELECT '403' AS 'Status', 'ERROR_FUNCIONARIO_NAO_ENCONTRADO' AS 'Error', '' AS 'Message';
	ELSE
        UPDATE funcionarios SET
			desativado = 1,
            senha = NULL
			WHERE email like emailIN;
        SELECT
			'204' AS 'Status',
			'' AS 'Error',
			'SUCCESS_DELETED' AS 'Message';
	END IF;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `DesativarProdutoPorID` (`idProdutoIN` VARCHAR(255))   BEGIN
	IF NOT EXISTS (SELECT idProduto FROM produtos WHERE idProduto like idProdutoIN AND desativado != 1)
	THEN
		SELECT '403' AS 'Status', 'ERROR_PRODUTO_NAO_ENCONTRADO' AS 'Error', '' AS 'Message';
	ELSE
        SET @id_estoque := (SELECT idEstoque FROM estoque WHERE idProduto = idProdutoIN);
        UPDATE estoque SET desativado = 1 WHERE idEstoque = idEstoqueIN;
        UPDATE produtos SET desativado = 1 WHERE idProduto = idProdutoIN;
        UPDATE variacaoProduto SET desativado = 1 WHERE idProduto = idProdutoIN;
        SELECT
			'204' AS 'Status',
			'' AS 'Error',
			'SUCCESS_DELETED' AS 'Message';
	END IF;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `EditarCliente` (`idClienteIN` INT, `nomeIN` VARCHAR(255), `emailIN` VARCHAR(255), `telefoneIN` VARCHAR(25), `idEnderecoIN` INT, `ruaEnd` VARCHAR(255), `numeroEnd` INT, `complementoEnd` VARCHAR(255), `bairroEnd` VARCHAR(255), `cepEnd` VARCHAR(20), `cidadeEnd` VARCHAR(255), `estadoEnd` VARCHAR(255))   BEGIN
    IF NOT EXISTS (SELECT idCliente from clientes where idCliente like idClienteIN)
    THEN
        SELECT '403' AS 'Status', 'ERROR_CLIENTE_NAO_ENCONTRADO' AS 'Error', '' AS 'Message';
    ELSE
        UPDATE endereco SET
            cep = cepEnd, 
            rua = ruaEnd, 
            numero = numeroEnd, 
            complemento = complementoEnd, 
            bairro = bairroEnd,
            cidade = cidadeEnd,
            estado = estadoEnd
            WHERE idEndereco = idEnderecoIN;
        UPDATE clientes SET
            nome = nomeIN,
            email = emailIN,
            telefone = telefoneIN
            WHERE idCliente = idClienteIN;
        SELECT 
            '204' AS 'Status',
            '' AS 'Error',
            'SUCCESS_UPDATED' AS 'Message';
    END IF; 
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `EditarEmpresa` (IN `cnpjIN` VARCHAR(20), IN `nomeFantasiaIN` VARCHAR(255), IN `cnpjNovoIN` VARCHAR(20), IN `telefoneIN` VARCHAR(255), IN `emailIN` VARCHAR(255), IN `ruaEnd` VARCHAR(255), IN `numeroEnd` INT, IN `complementoEnd` VARCHAR(255), IN `bairroEnd` VARCHAR(255), IN `cepEnd` VARCHAR(20), IN `cidadeEnd` VARCHAR(255), IN `estadoEnd` VARCHAR(255))   BEGIN
	IF NOT EXISTS (SELECT nomeFantasia from empresa where cnpj like cnpjIN)
	THEN
		SELECT '403' AS 'Status', 'ERROR_EMAIL_NAO_ENCONTRADO' AS 'Error', '' AS 'Message';
	ELSE
		SET @enderecoId := (SELECT idEndereco FROM empresa WHERE cnpj like cnpjIN LIMIT 1);
		UPDATE endereco SET
			cep = cepEnd, 
			rua = ruaEnd, 
			numero = numeroEnd, 
			complemento = complementoEnd, 
			bairro = bairroEnd,
            cidade = cidadeEnd,
            estado = estadoEnd
            WHERE idEndereco = @enderecoId;
		UPDATE empresa SET
			nomeFantasia = nomeFantasiaIN,
			cpnj = cnpjNovoIN,
			endereco = @enderecoId,
            telefone = telefoneIN,
            email = emailIN
			WHERE cnpj like cnpjIN;
		SELECT 
			'204' AS 'Status',
			'' AS 'Error',
			'SUCCESS_UPDATED' AS 'Message';
	END IF; 
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `EditarEstoque` (`idEstoqueIN` INT, `dtEntradaIN` DATE, `quantidadeIN` INT, `dtFabricacaoIN` DATE, `dtVencimentoIN` DATE, `precoCompraIN` DECIMAL(15,2), `qtdMinimaIN` INT, `qtdVendidaIN` INT)   BEGIN
    IF NOT EXISTS (SELECT idEstoque from estoque where idEstoque like idEstoqueIN)
    THEN
        SELECT '403' AS 'Status', 'ERROR_PRODUTO_INEXISTENTE' AS 'Error', '' AS 'Message';
    ELSE
        UPDATE estoque SET dtEntrada = dtEntradaIN, quantidade = quantidadeIN, dtFabricacao = dtFabricacaoIN, 
        dtVencimento = dtVencimentoIN, precoCompra = precoCompraIN, qtdMinima = qtdMinimaIN, qtdVendida = qtdVendidaIN
        WHERE idEstoque = idEstoqueIN;
        SELECT
            '201' AS 'Status',
            '' AS 'Error',
            'SUCCESS_CREATED' AS 'Message';
    END IF;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `EditarFuncionarioPorEmail` (IN `emailIN` VARCHAR(255), IN `emailNovoIN` VARCHAR(255), IN `nomeIN` VARCHAR(255), IN `telefoneIN` VARCHAR(25))   BEGIN
	IF NOT EXISTS (SELECT email from funcionarios where email like emailIN)
	THEN
		SELECT '403' AS 'Status', 'ERROR_EMAIL_NAO_ENCONTRADO' AS 'Error', '' AS 'Message';
	ELSE
		UPDATE funcionarios SET
			nome = nomeIN,
			email = emailNovoIN,
			telefone = telefoneIN
			WHERE email like emailIN;
		SELECT 
			'204' AS 'Status',
			'' AS 'Error',
			'SUCCESS_UPDATED' AS 'Message';
	END IF; 
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `EditarPedidoStatus` (`idPedidoIN` INT, `statusPedidoIN` VARCHAR(20))   BEGIN
	SET @total_do_pedido := (SELECT sum(subtotal) FROM pedidoProduto WHERE idPedido = idProdutoIN);
	UPDATE pedidos SET
		dataPedido = dataIN,
        totalPedido = @total_do_pedido,
		statusPedido = statusP,
		funcionarios_idFuncionario = funcionario,
		enderecos_idEndereco = endereco
		WHERE idPedido = idP;
	SELECT 
		'204' AS 'Status',
		'' AS 'Error',
		'SUCCESS_UPDATED' AS 'Message';
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `EditarProdutoPorId` (`idProdutoIN` VARCHAR(255), `idFornecedorIN` INT, `nomeIN` VARCHAR(255), `marcaIN` VARCHAR(255), `codigoProdutoIN` INT, `descricaoIN` VARCHAR(255))   BEGIN
	IF NOT EXISTS (SELECT nomeProduto from produtos where idProduto like idProdutoIN)
	THEN
		SELECT '403' AS 'Status', 'ERROR_PRODUTO_NAO_ENCONTRADO' AS 'Error', '' AS 'Message';
	ELSE
		UPDATE produtos SET IdFornecedor = idFornecedorIN, nome = nomeIN, marca = marcaIN, codigoProduto = codigoProdutoIN, descricao = descricaoIN WHERE idProduto = idProdutoIN;
		SELECT 
			'204' AS 'Status',
			'' AS 'Error',
			'SUCCESS_UPDATED' AS 'Message';
	END IF; 
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `EditarSenha` (`emailIN` VARCHAR(255), `novaSenhaIN` VARCHAR(255))   BEGIN
	IF EXISTS (SELECT email FROM funcionarios WHERE email like emailIN)
    THEN
		UPDATE funcionarios SET
			senha = novaSenhaIN
			WHERE email like emailIN;
		SELECT 
			'204' AS 'Status',
			'' AS 'Error',
			'SUCCESS_UPDATED' AS 'Message';
	ELSEIF EXISTS (SELECT email from clientes where email like emailIN)
		THEN
			UPDATE clientes SET
				senha = novaSenhaIN
				WHERE email like emailIN;
			SELECT 
				'204' AS 'Status',
				'' AS 'Error',
				'SUCCESS_UPDATED' AS 'Message';
	ELSE
		SELECT '403' AS 'Status', 'ERROR_EMAIL_NAO_ENCONTRADO' AS 'Error', '' AS 'Message';
    END IF;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `EditarVariacao` (`idVariacaoIN` VARCHAR(255), `nomeVariacaoIN` VARCHAR(255), `precoVariacaoIN` DECIMAL(10,2), `fotoVariacaoIN` VARCHAR(255), `idProduto` INT)   BEGIN
	IF NOT EXISTS (SELECT nomeVariacao from variacaoProduto where idVariacao like idVariacaoIN AND desativado != 1)
	THEN
		SELECT '403' AS 'Status', 'ERROR_NOME_NAO_ENCONTRADO' AS 'Error', '' AS 'Message';
	ELSE
		UPDATE variacaoProduto SET nomeVariacao = nomeVariacaoIN, precoVariacao = precoVariacaoIN, fotos = fotosVariacaoIN, idProduto = idProduto 
            WHERE idVariacao = idVariacaoIN;
		SELECT 
			'204' AS 'Status',
			'' AS 'Error',
			'SUCCESS_UPDATED' AS 'Message';
	END IF; 
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `InserirCliente` (IN `nomeIN` VARCHAR(255), IN `emailIN` VARCHAR(255), IN `senhaIN` VARCHAR(255), IN `telefoneIN` VARCHAR(25), IN `ruaEnd` VARCHAR(255), IN `numeroEnd` INT, IN `complementoEnd` VARCHAR(255), IN `bairroEnd` VARCHAR(255), IN `cepEnd` VARCHAR(20), IN `cidadeEnd` VARCHAR(255), IN `estadoEnd` VARCHAR(255))   BEGIN
	IF EXISTS (SELECT email from clientes where email like emailIN)
	THEN
		SELECT '403' AS 'Status', 'ERROR_EMAIL_CADASTRADO' AS 'Error', '' AS 'Message';
	ELSE
		INSERT INTO enderecos(`cep`, `rua`, `numero`, `complemento`, `bairro`, `cidade`, `estado`) 
		VALUES (cepEnd, ruaEnd, numeroEnd, complementoEnd, bairroEnd, cidadeEnd, estadoEnd
		);
        SET @last_id_in_enderecos = LAST_INSERT_ID();
        INSERT INTO clientes(
			`nome`,
			`email`,
			`senha`,
			`telefone`,
			`idEndereco`, `desativado`) 
		VALUES (nomeIN, emailIN, senhaIN, telefoneIN, @last_id_in_enderecos, 0
		);
		SELECT 
			'201' AS 'Status',
			'' AS 'Error',
			'SUCCESS_CREATED' AS 'Message';
	END IF; 
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `InserirEstoque` (IN `codigoProdutoIN` INT, IN `dtEntradaIN` DATE, IN `quantidadeIN` INT, IN `dtFabricacaoIN` DATE, IN `dtVencimentoIN` DATE, IN `precoCompraIN` DOUBLE, IN `qtdMinimaIN` INT, IN `qtdVendidaIN` INT, IN `qtdOcorrenciaIN` INT, IN `nomeIN` VARCHAR(255), IN `fabricanteIN` VARCHAR(255), IN `marcaIN` VARCHAR(255), IN `descricaoIN` VARCHAR(255), IN `imagemIN` VARCHAR(255))   BEGIN
	IF EXISTS (SELECT nome FROM produtos where codigoProduto like codigoProdutoIN)
	THEN
		SELECT '403' AS 'Status', 'ERROR_PRODUTO_EXISTENTE' AS 'Error', '' AS 'Message';
	ELSE
        INSERT INTO produtos(`fabricanteId`, `nome`, `marca`, `codigoProduto`, `descricao`, `imagem`)
			VALUES (fabricanteIdIN, nomeIN, marcaIN, codigoProdutoIN, descricaoIN, imagemIN);
            SET @last_id_in_produtos = LAST_INSERT_ID();
        INSERT INTO estoque(`idProduto`, `codigoProduto`, `dtEntrada`, `quantidade`, `dtFabricacao`, 
                            `dtVencimento`, `precoCompra`, `qtdMinima`, `qtdVendida`, `qtdOcorrencia`)
        VALUES (@last_id_in_produtos, codigoProdutoIN, dtEntradaIN, quantidadeIN, dtFabricacaoIN, 
                dtVencimentoIN, precoCompraIN, qtdMinimaIN, qtdVendidaIN, qtdOcorrenciaIN);
		SELECT 
			'201' AS 'Status',
			'' AS 'Error',
			'SUCCESS_CREATED' AS 'Message';
	END IF;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `InserirFornecedor` (IN `nomeFornecedorIN` VARCHAR(255), IN `telefoneIN` VARCHAR(25), IN `emailIN` VARCHAR(255), IN `cnpjIN` VARCHAR(25), IN `idEnderecoIN` INT)   BEGIN
    IF EXISTS (SELECT cnpj from fornecedores where cnpj like cnpjIN)
    THEN
        SELECT '403' AS 'Status', 'ERROR_CNPJ_CADASTRADO' AS 'Error', '' AS 'Message';
    ELSE
        INSERT INTO fornecedores(`nomeFornecedor`, `telefone`, `email`, `cnpj`, `idEndereco`) VALUES (nomeFornecedorIN, telefoneIN, emailIN, cnpjIN, idEnderecoIN);
        SELECT 
            '201' AS 'Status', 
            '' AS 'Error', 
            'SUCCESS_CREATED' AS 'Message';
    END IF;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `InserirFuncionario` (IN `nomeIN` VARCHAR(255), IN `emailIN` VARCHAR(255), IN `telefoneIN` VARCHAR(255), IN `senhaIN` VARCHAR(25), IN `admIN` TINYINT)   BEGIN
	IF EXISTS (SELECT email from funcionarios where email like emailIN)
	THEN
		SELECT '403' AS 'Status', 'ERROR_EMAIL_CADASTRADO' AS 'Error', '' AS 'Message';
	ELSE
        INSERT INTO funcionarios(
			`nome`,
			`email`,
			`telefone`,
			`senha`,
			`adm`)
			VALUES (nomeIN, emailIN, telefoneIN, senhaIN, admIN);
		SELECT 
			'201' AS 'Status',
			'' AS 'Error',
			'SUCCESS_CREATED' AS 'Message';
	END IF; 
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `InserirOcorrenciaEstoque` (`idEstoqueIN` INT, `ocorrenciaIN` VARCHAR(1000), `dtOcorrenciaIN` DATE)   BEGIN
    IF NOT EXISTS (SELECT idEstoque from estoque where idEstoque like idEstoqueIN)
    THEN
        SELECT '403' AS 'Status', 'ERROR_PRODUTO_INEXISTENTE' AS 'Error', '' AS 'Message';
    ELSE
        UPDATE estoque SET ocorrencia = ocorrenciaIN, dtOcorrencia = dtOcorrenciaIN
            WHERE idEstoque = idEstoqueIN;
        SELECT
            '201' AS 'Status',
            '' AS 'Error',
            'SUCCESS_CREATED' AS 'Message';
    END IF;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `InserirPedido` (`emailIN` VARCHAR(255), `dtPedidoIN` DATETIME, `tipoFreteIN` INT, `qtdItemsIN` INT)   BEGIN
	IF NOT EXISTS (SELECT rua FROM enderecos WHERE idEndereco = idEnderecoIN)
	THEN
		SELECT '403' AS 'Status', 'ERROR_ENDERECO_NAO_ENCONTRADO' AS 'Error', '' AS 'Message';
	ELSE
		SET @status_pedido := 'Aguardando Pagamento';
        SET @id_cliente := (SELECT idCliente FROM clientes WHERE email like emailIN);
        SET @id_endereco := (SELECT idEndereco FROM clientes WHERE idCliente like id_cliente);

        INSERT INTO pedidos(`idCliente`, `dtPedido`, `tipoFrete`, `idEndereco`, `qtdItems`, `statusPedido`)
			VALUES (@id_cliente, dtPedidoIN, tipoFreteIN, @id_endereco, qtdItems, @status_pedido);
        SET @id_pedido := LAST_INSERT_ID();
		SELECT 
			'201' AS 'Status',
			'' AS 'Error',
			'SUCCESS_CREATED' AS 'Message',
            LAST_INSERT_ID() AS 'Body';
	END IF;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `InserirPedidoDtPagamento` (`idPedidoIN` INT, `dtPagamentoIN` DATETIME)   BEGIN
    IF NOT EXISTS (SELECT * FROM pedidos WHERE idPedido = idPedidoIN)
    THEN
        SELECT '403' AS 'Status', 'ERROR_PEDIDO_NAO_ENCONTRADO' AS 'Error', '' AS 'Message';
    ELSE
        UPDATE pedidos SET dataPagamento = dtPagamentoIN WHERE idPedido = idPedidoIN;
        SELECT 
            '201' AS 'Status',
            '' AS 'Error',
            'SUCCESS_CREATED' AS 'Message';
    END IF;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `InserirPedidoProduto` (`idPedidoIN` INT, `idVariacaoIN` INT, `qntdIN` INT)   BEGIN
	SET @idProduto := (SELECT idProduto FROM variacaoProduto WHERE idVariacao = idVariacaoIN);
    SET @variacaoPreco := (SELECT precoVariacao FROM variacaoProduto WHERE idVariacao = idVariacaoIN);
    SET @total := @preco * qntd;

	INSERT INTO pedidoProduto(`idPedido`, `idProduto`, `idVariacao`, `quantidade`, `total`)	VALUES (idPedidoIN, @produto, idVariacaoIN, qntdIN, @total);
	SET @total_do_pedido := (SELECT sum(subtotal) FROM pedidoProduto WHERE idPedido = idPedidoIN);
    UPDATE pedidos SET valorTotal = @total_do_pedido WHERE idPedido = pedido;
	SELECT
		'201' AS 'Status',
		'' AS 'Error',
		'SUCCESS_CREATED' AS 'Message';
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `InserirVaricao` (`nomeVariacaoIN` VARCHAR(255), `precoVariacaoIN` DECIMAL(10,2), `fotoVaricaoIN` VARCHAR(255), `idProdutoIN` INT, `desativadoIN` TINYINT, `nomeProdutoIN` VARCHAR(255))   BEGIN
	IF EXISTS (SELECT nomeVariacao FROM variacaoProduto WHERE nomeVariacao like nomeVariacaoIN)
    THEN
		SELECT '403' AS 'Status', 'ERROR_VARIACAO_CADASTRADA' AS 'Error', '' AS 'Message';
	ELSEIF NOT EXISTS (SELECT nome FROM produtos where nome like nomeProdutoIN)
		THEN
			SELECT '403' AS 'Status', 'ERROR_PRODUTO_NAO_CADASTRADO' AS 'Error', '' AS 'Message';
	ELSE
		SET @produto_id := (SELECT idProduto FROM produtos WHERE nome like nomeProdutoIN);
        INSERT INTO variacaoProduto(`nomeVariacao`, `precoVariacao`, `fotos`, `idProduto`, `desativado`) VALUES (nomeVariacaoIN, precoVariacaoIN, fotoVaricaoIN, @produto_id, desativadoIN);
		SELECT 
			'201' AS 'Status',
			'' AS 'Error',
			'SUCCESS_CREATED' AS 'Message';
	END IF; 
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `ListarClientePorEmail` (IN `emailIN` VARCHAR(255))   BEGIN
	IF NOT EXISTS (SELECT email from clientes where email like emailIN)
	THEN
		SELECT '403' AS 'Status', 'ERROR_EMAIL_NAO_ENCONTRADO' AS 'Error', '' AS 'Message';
	ELSE
		SELECT * 
			FROM clientes 
			INNER JOIN enderecos
            ON cliente.idEndereco = enderecos.idEndereco
            WHERE email like emailIN LIMIT 1;
        SELECT 
			'201' AS 'Status',
			'' AS 'Error',
			'SUCCESS_CREATED' AS 'Message';
	END IF;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `ListarEmpresa` (IN `cnpjIN` VARCHAR(20))   BEGIN
	IF NOT EXISTS (SELECT nomeFantasia from empresa where cnpj like cnpjIN)
	THEN
		SELECT '403' AS 'Status', 'ERROR_EMAIL_NAO_ENCONTRADO' AS 'Error', '' AS 'Message';
	ELSE
		SELECT * 
			FROM empresa 
			INNER JOIN enderecos
            ON empresa.idEndereco = enderecos.idEndereco
            WHERE cnpj like cnpjIN LIMIT 1;
        SELECT
            '201' AS 'Status',
            '' AS 'Error',
            'SUCCESS_CREATED' AS 'Message';
	END IF;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `ListarEstoque` ()   BEGIN
    IF NOT EXISTS (SELECT * FROM estoque WHERE desativado != 1)
	THEN
		SELECT '403' AS 'Status', 'ERROR_ESTOQUE_VAZIO' AS 'Error', '' AS 'Message';
	ELSE
        SELECT * FROM estoque WHERE desativado = 0;
        SELECT
            '201' AS 'Status',
            '' AS 'Error',
            'SUCCESS_CREATED' AS 'Message';
    END IF;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `ListarEstoquePorProduto` (`idProdutoIN` INT)   BEGIN
    IF NOT EXISTS (SELECT idProduto from estoque where idProduto like idProdutoIN)
    THEN
        SELECT '403' AS 'Status', 'ERROR_PRODUTO_INEXISTENTE' AS 'Error', '' AS 'Message';
    ELSE
        SELECT * FROM estoque WHERE idProduto = idProdutoIN;
        SELECT
            '201' AS 'Status',
            '' AS 'Error',
            'SUCCESS_CREATED' AS 'Message';
    END IF;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `ListarFornecedores` ()   BEGIN 
    IF NOT EXISTS (SELECT * FROM fornecedores WHERE desativado != 1) 
    THEN
        SELECT '403' AS 'Status', 'ERROR_FORNECEDORES_EXISTENTES' AS 'Error', '' AS 'Message';
    ELSE
        SELECT * 
            FROM fornecedores WHERE desativado != 1;
        SELECT
            '201' AS 'Status',
            '' AS 'Error',
            'SUCCESS_CREATED' AS 'Message';
    END IF;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `ListarFornecedorPorID` (`idFornecedorIN` INT)   BEGIN
    IF NOT EXISTS (SELECT idFornecedor from fornecedores where idFornecedor like idFornecedorIN)
    THEN
        SELECT '403' AS 'Status', 'ERROR_FORNECEDOR_NAO_ENCONTRADO' AS 'Error', '' AS 'Message';
    ELSE
        SELECT * FROM fornecedores WHERE idFornecedor = idFornecedorIN;
        SELECT
            '201' AS 'Status',
            '' AS 'Error',
            'SUCCESS_CREATED' AS 'Message';
    END IF;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `ListarFuncionarioPorEmail` (`emailIN` VARCHAR(255))   BEGIN
	IF NOT EXISTS (SELECT email from funcionarios where email like emailIN)
	THEN
		SELECT '403' AS 'Status', 'ERROR_EMAIL_NAO_ENCONTRADO' AS 'Error', '' AS 'Message';
	ELSE
		SELECT * FROM funcionarios WHERE email like emailIN LIMIT 1;
        SELECT
            '201' AS 'Status',
            '' AS 'Error',
            'SUCCESS_CREATED' AS 'Message';
	END IF;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `ListarFuncionarios` ()   BEGIN
    IF NOT EXISTS (SELECT * FROM funcionarios WHERE desativado = 0) 
    THEN
        SELECT '403' AS 'Status', 'ERROR_FUNCIONARIOS_EXISTENTES' AS 'Error', '' AS 'Message';
    ELSE
        SELECT * 
            FROM funcionarios WHERE desativado = 0;
        SELECT
            '201' AS 'Status',
            '' AS 'Error',
            'SUCCESS_CREATED' AS 'Message';
    END IF;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `ListarPedidoPorCliente` (`emailIN` VARCHAR(255))   BEGIN
	SET @id_cliente := (SELECT idCliente FROM clientes WHERE email like emailIN);
    IF (isnull(@id_cliente))
    THEN
		SELECT '403' AS 'Status', 'ERROR_CLIENTE_NAO_ENCONTRADO' AS 'Error', '' AS 'Message';
	ELSE
		SELECT * FROM pedidos 
            LEFT OUTER JOIN pedidoProduto
            ON pedidos.idPedido = pedidoProduto.idPedido
			WHERE pedidos.idCliente = @id_cliente;
	END IF;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `ListarPedidoPorID` (`idPedidoIN` INT)   BEGIN
	SELECT * FROM pedidos
        LEFT OUTER JOIN pedidoProduto
		ON pedidos.idPedido = pedidoProduto.idPedido
        WHERE pedidos.idPedido = idPedidoIN;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `ListarPedidoPorStatus` (`statusPedidoIN` VARCHAR(20))   BEGIN
	SELECT * 
		FROM pedidos
        LEFT OUTER JOIN pedidoProduto
		ON pedidos.idPedido = pedidoProduto.idPedido
        WHERE pedidos.statusPedido like statusPedidoIN;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `ListarPedidosEmAndamento` ()   BEGIN
    IF NOT EXISTS (SELECT * FROM pedidos WHERE statusPedido like 'Aguardando Pagamento' OR statusPedido like 'Em Andamento')
    then 
    	SELECT '403' AS 'Status', 'ERROR_PEDIDOS_NAO_ENCONTRADO' AS 'Error', '' AS 'Message';
	ELSE
	SELECT * FROM pedidos LEFT OUTER JOIN pedidoProduto ON pedidos.idPedido = pedidoProduto.pedidos_idPedido 
        WHERE (pedidos.statusPedido not like 'Finalizado' AND pedidos.statusPedido not like 'Entregue');
    END IF;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `ListarProdutoPorID` (`idProdutoIN` INT, `limitF` INT, `offsetF` INT)   BEGIN
	SELECT * FROM produtos WHERE idProduto = idProdutoIN AND desativado = 0 LIMIT limitF OFFSET offsetF;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `ListarProdutos` (`limitF` INT, `offsetF` INT)   BEGIN
	SELECT * FROM produtos WHERE desativado = 0  LIMIT limitF OFFSET offsetF;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `ListarVariacao` (`limitF` INT, `offsetF` INT)   BEGIN
	SELECT * FROM variacaoProduto WHERE desativado != 1 LIMIT limitF OFFSET offsetF;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `ListarVariacaoPorID` (`idVaricaoIN` INT)   BEGIN
	SELECT * FROM variacaoProduto WHERE desativado != 1 AND idVariacao = idVaricaoIN;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `ListarVaricaoPorCodigoProduto` (`codigoProdutoIN` VARCHAR(255))   BEGIN
	SET @produto_id := (SELECT idProduto FROM produtos WHERE codigoProduto like codigoProdutoIN);
	SELECT * FROM variacaoProduto WHERE desativado != 1 AND idProduto = @produto_id;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `ListarVaricaoPorProduto` (`idProduto` INT)   BEGIN
	SELECT * FROM variacaoProduto WHERE desativado != 1 AND idProduto = idProduto;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `Login` (IN `emailIN` VARCHAR(255))   BEGIN
	IF EXISTS (SELECT email FROM funcionarios WHERE email like emailIN)
    THEN
		SELECT * FROM funcionarios WHERE email LIKE email LIMIT 1;
	ELSEIF EXISTS (SELECT email FROM clientes WHERE email LIKE emailIN)
		THEN
			SELECT * FROM clientes INNER JOIN enderecos ON clientes.idEndereco = enderecos.idEndereco WHERE email LIKE email LIMIT 1;
	ELSE
		SELECT '403' AS 'Status', 'ERROR_EMAIL_NAO_ENCONTRADO' AS 'Error', '' AS 'Message', '' AS 'Body';
    END IF;
END$$

DELIMITER ;

-- --------------------------------------------------------

--
-- Estrutura para tabela `clientes`
--

CREATE TABLE `clientes` (
  `idCliente` int(11) NOT NULL,
  `desativado` tinyint(4) DEFAULT 0,
  `nome` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `senha` varchar(255) NOT NULL,
  `telefone` varchar(25) DEFAULT NULL,
  `perfil` char(4) DEFAULT 'CLIE',
  `idEndereco` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `clientes`
--

INSERT INTO `clientes` (`idCliente`, `desativado`, `nome`, `email`, `senha`, `telefone`, `perfil`, `idEndereco`) VALUES
(1, 0, 'joao', 'jo@email.com', '1234', '44564-2132', 'CLIE', 1);

-- --------------------------------------------------------

--
-- Estrutura para tabela `empresa`
--

CREATE TABLE `empresa` (
  `idEmpresa` int(11) NOT NULL,
  `nomeFantasia` varchar(255) NOT NULL,
  `cnpj` varchar(14) NOT NULL,
  `idEndereco` int(11) NOT NULL,
  `telefone` varchar(15) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `enderecos`
--

CREATE TABLE `enderecos` (
  `idEndereco` int(11) NOT NULL,
  `cep` varchar(20) DEFAULT NULL,
  `rua` varchar(255) DEFAULT NULL,
  `numero` int(11) DEFAULT NULL,
  `complemento` varchar(255) DEFAULT NULL,
  `bairro` varchar(255) DEFAULT NULL,
  `cidade` varchar(225) DEFAULT NULL,
  `estado` varchar(225) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `enderecos`
--

INSERT INTO `enderecos` (`idEndereco`, `cep`, `rua`, `numero`, `complemento`, `bairro`, `cidade`, `estado`) VALUES
(1, '7884562', 'Rua dos Anjos', 2, '', 'Jardins', 'Guarulhos', 'SP'),
(2, '90050-340', 'Rua das Flores', 123, 'Apto 101', 'Centro', 'Porto Alegre', 'RS'),
(3, '20040-001', 'Av. Atlântica', 456, NULL, 'Copacabana', 'Rio de Janeiro', 'RJ'),
(4, '30130-010', 'Rua da Harmonia', 789, 'Loja B', 'Savassi', 'Belo Horizonte', 'MG'),
(5, '01001-000', 'Praça da Sé', 321, 'Sala 5', 'Centro', 'São Paulo', 'SP'),
(6, '40020-030', 'Rua da Praia', 654, NULL, 'Barra', 'Salvador', 'BA');

-- --------------------------------------------------------

--
-- Estrutura para tabela `estoque`
--

CREATE TABLE `estoque` (
  `idEstoque` int(11) NOT NULL,
  `idProduto` int(11) DEFAULT NULL,
  `dtEntrada` date DEFAULT NULL COMMENT 'YYYY/MM/DD',
  `quantidade` int(11) DEFAULT 0,
  `dtFabricacao` date DEFAULT NULL COMMENT 'YYYY/MM/DD',
  `dtVencimento` date DEFAULT NULL COMMENT 'YYYY/MM/DD',
  `precoCompra` decimal(15,2) DEFAULT NULL,
  `qtdMinima` int(11) NOT NULL,
  `qtdVendida` int(11) DEFAULT NULL,
  `qtdOcorrencia` int(11) DEFAULT NULL,
  `ocorrencia` varchar(1024) DEFAULT NULL,
  `desativado` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `fornecedores`
--

CREATE TABLE `fornecedores` (
  `idFornecedor` int(11) NOT NULL,
  `nomeFornecedor` varchar(255) NOT NULL,
  `telefone` varchar(20) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `cnpj` varchar(20) NOT NULL,
  `desativado` int(11) DEFAULT NULL,
  `idEndereco` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `fornecedores`
--

INSERT INTO `fornecedores` (`idFornecedor`, `nomeFornecedor`, `telefone`, `email`, `cnpj`, `desativado`, `idEndereco`) VALUES
(1, 'Sorvetes do Sul', '51987654321', 'contato@sorvetesdosul.com.br', '12.345.678/0001-99', 0, 2),
(2, 'Gelados Tropical', '21987654321', 'vendas@geladostropical.com.br', '98.765.432/0001-11', 0, 3),
(3, 'Doces e Sorvetes Ltda', NULL, 'info@docesesorvetes.com.br', '56.789.012/0001-55', 1, 4),
(4, 'IceDream Sorvetes', '31987654321', NULL, '23.456.789/0001-77', 0, 5),
(5, 'Delícias Geladas', NULL, NULL, '34.567.890/0001-88', 1, 6);

-- --------------------------------------------------------

--
-- Estrutura para tabela `funcionarios`
--

CREATE TABLE `funcionarios` (
  `idFuncionario` int(11) NOT NULL,
  `desativado` tinyint(4) DEFAULT 0,
  `adm` tinyint(4) DEFAULT NULL,
  `perfil` char(4) DEFAULT 'FUNC',
  `nome` varchar(255) DEFAULT NULL,
  `telefone` varchar(25) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `senha` varchar(255) DEFAULT NULL,
  `idEndereco` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `funcionarios`
--

INSERT INTO `funcionarios` (`idFuncionario`, `desativado`, `adm`, `perfil`, `nome`, `telefone`, `email`, `senha`, `idEndereco`) VALUES
(1, 0, 1, 'FUNC', 'Jessica', '96309-8589', 'je@email.com', '1234', 1);

-- --------------------------------------------------------

--
-- Estrutura para tabela `pedidoproduto`
--

CREATE TABLE `pedidoproduto` (
  `idPedidoProduto` int(11) NOT NULL,
  `idPedido` int(11) NOT NULL,
  `idProduto` int(11) NOT NULL,
  `idVariacao` int(11) NOT NULL,
  `quantidade` int(11) DEFAULT NULL,
  `total` decimal(10,0) NOT NULL,
  `desativado` tinyint(4) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `pedidos`
--

CREATE TABLE `pedidos` (
  `idPedido` int(11) NOT NULL,
  `idCliente` int(11) NOT NULL,
  `dtPedido` datetime DEFAULT NULL,
  `dtPagamento` datetime DEFAULT NULL,
  `tipoFrete` int(11) DEFAULT 0 COMMENT '{ { 0, Retirada }, { 1, MOTOBOY } }',
  `rastreioFrete` varchar(255) DEFAULT NULL,
  `idEndereco` int(11) DEFAULT NULL,
  `valorTotal` decimal(12,2) DEFAULT NULL,
  `qtdItems` int(11) NOT NULL,
  `dtCancelamento` datetime DEFAULT NULL,
  `motivoCancelamento` text DEFAULT NULL,
  `statusPedido` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `produtos`
--

CREATE TABLE `produtos` (
  `idProduto` int(11) NOT NULL,
  `idFornecedor` int(11) NOT NULL,
  `nome` varchar(255) NOT NULL,
  `marca` varchar(255) NOT NULL,
  `codigoProduto` int(11) NOT NULL,
  `descricao` text DEFAULT NULL,
  `desativado` int(11) DEFAULT NULL,
  `foto` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `produtos`
--

INSERT INTO `produtos` (`idProduto`, `idFornecedor`, `nome`, `marca`, `codigoProduto`, `descricao`, `desativado`, `foto`) VALUES
(1, 1, 'Pote de Sorvete', 'Marca', 0, 'Pote de sorvete', 0, 'poteLogo.png'),
(2, 2, 'Picolé', 'Marca', 0, 'Picolé', 0, 'picoleLogo.png');

-- --------------------------------------------------------

--
-- Estrutura para tabela `variacaoproduto`
--

CREATE TABLE `variacaoproduto` (
  `idVariacao` int(11) NOT NULL,
  `desativado` tinyint(4) DEFAULT 0,
  `nomeVariacao` varchar(255) NOT NULL,
  `precoVariacao` decimal(10,2) NOT NULL,
  `fotoVariacao` varchar(255) NOT NULL,
  `idProduto` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `variacaoproduto`
--

INSERT INTO `variacaoproduto` (`idVariacao`, `desativado`, `nomeVariacao`, `precoVariacao`, `fotoVariacao`, `idProduto`) VALUES
(1, 0, 'Havaiano', 25.99, 'havaianoPote.png', 1),
(2, 0, 'Chocolitano', 22.50, 'chocolitanoPote.png', 1);

--
-- Índices para tabelas despejadas
--

--
-- Índices de tabela `clientes`
--
ALTER TABLE `clientes`
  ADD PRIMARY KEY (`idCliente`),
  ADD KEY `fk_cliente_endereco` (`idEndereco`);

--
-- Índices de tabela `empresa`
--
ALTER TABLE `empresa`
  ADD PRIMARY KEY (`idEmpresa`),
  ADD KEY `fk_empresa_endereco` (`idEndereco`);

--
-- Índices de tabela `enderecos`
--
ALTER TABLE `enderecos`
  ADD PRIMARY KEY (`idEndereco`);

--
-- Índices de tabela `estoque`
--
ALTER TABLE `estoque`
  ADD PRIMARY KEY (`idEstoque`),
  ADD KEY `fk_estoque_produto` (`idProduto`);

--
-- Índices de tabela `fornecedores`
--
ALTER TABLE `fornecedores`
  ADD PRIMARY KEY (`idFornecedor`),
  ADD KEY `fk_fornecedor_endereco` (`idEndereco`);

--
-- Índices de tabela `funcionarios`
--
ALTER TABLE `funcionarios`
  ADD PRIMARY KEY (`idFuncionario`),
  ADD KEY `fk_funcionario_endereco` (`idEndereco`);

--
-- Índices de tabela `pedidoproduto`
--
ALTER TABLE `pedidoproduto`
  ADD PRIMARY KEY (`idPedidoProduto`),
  ADD KEY `fk_pedidoproduto_pedido` (`idPedido`),
  ADD KEY `fk_pedidoproduto_variacao` (`idVariacao`),
  ADD KEY `fk_pedidoproduto_produto` (`idProduto`);

--
-- Índices de tabela `pedidos`
--
ALTER TABLE `pedidos`
  ADD PRIMARY KEY (`idPedido`),
  ADD KEY `fk_pedido_endereco` (`idEndereco`),
  ADD KEY `fk_pedido_cliente` (`idCliente`);

--
-- Índices de tabela `produtos`
--
ALTER TABLE `produtos`
  ADD PRIMARY KEY (`idProduto`),
  ADD KEY `fk_produto_fornecedor` (`idFornecedor`);

--
-- Índices de tabela `variacaoproduto`
--
ALTER TABLE `variacaoproduto`
  ADD PRIMARY KEY (`idVariacao`),
  ADD KEY `fk_variacaoproduto_produto` (`idProduto`);

--
-- AUTO_INCREMENT para tabelas despejadas
--

--
-- AUTO_INCREMENT de tabela `clientes`
--
ALTER TABLE `clientes`
  MODIFY `idCliente` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de tabela `empresa`
--
ALTER TABLE `empresa`
  MODIFY `idEmpresa` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `enderecos`
--
ALTER TABLE `enderecos`
  MODIFY `idEndereco` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de tabela `estoque`
--
ALTER TABLE `estoque`
  MODIFY `idEstoque` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `fornecedores`
--
ALTER TABLE `fornecedores`
  MODIFY `idFornecedor` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de tabela `funcionarios`
--
ALTER TABLE `funcionarios`
  MODIFY `idFuncionario` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de tabela `pedidoproduto`
--
ALTER TABLE `pedidoproduto`
  MODIFY `idPedidoProduto` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `pedidos`
--
ALTER TABLE `pedidos`
  MODIFY `idPedido` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `produtos`
--
ALTER TABLE `produtos`
  MODIFY `idProduto` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de tabela `variacaoproduto`
--
ALTER TABLE `variacaoproduto`
  MODIFY `idVariacao` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Restrições para tabelas despejadas
--

--
-- Restrições para tabelas `clientes`
--
ALTER TABLE `clientes`
  ADD CONSTRAINT `fk_cliente_endereco` FOREIGN KEY (`idEndereco`) REFERENCES `enderecos` (`idEndereco`);

--
-- Restrições para tabelas `empresa`
--
ALTER TABLE `empresa`
  ADD CONSTRAINT `fk_empresa_endereco` FOREIGN KEY (`idEndereco`) REFERENCES `enderecos` (`idEndereco`);

--
-- Restrições para tabelas `estoque`
--
ALTER TABLE `estoque`
  ADD CONSTRAINT `fk_estoque_produto` FOREIGN KEY (`idProduto`) REFERENCES `produtos` (`idProduto`);

--
-- Restrições para tabelas `fornecedores`
--
ALTER TABLE `fornecedores`
  ADD CONSTRAINT `fk_fornecedor_endereco` FOREIGN KEY (`idEndereco`) REFERENCES `enderecos` (`idEndereco`);

--
-- Restrições para tabelas `funcionarios`
--
ALTER TABLE `funcionarios`
  ADD CONSTRAINT `fk_funcionario_endereco` FOREIGN KEY (`idEndereco`) REFERENCES `enderecos` (`idEndereco`);

--
-- Restrições para tabelas `pedidoproduto`
--
ALTER TABLE `pedidoproduto`
  ADD CONSTRAINT `fk_pedidoproduto_pedido` FOREIGN KEY (`idPedido`) REFERENCES `pedidos` (`idPedido`),
  ADD CONSTRAINT `fk_pedidoproduto_produto` FOREIGN KEY (`idProduto`) REFERENCES `produtos` (`idProduto`),
  ADD CONSTRAINT `fk_pedidoproduto_variacao` FOREIGN KEY (`idVariacao`) REFERENCES `variacaoproduto` (`idVariacao`);

--
-- Restrições para tabelas `pedidos`
--
ALTER TABLE `pedidos`
  ADD CONSTRAINT `fk_pedido_cliente` FOREIGN KEY (`idCliente`) REFERENCES `clientes` (`idCliente`),
  ADD CONSTRAINT `fk_pedido_endereco` FOREIGN KEY (`idEndereco`) REFERENCES `enderecos` (`idEndereco`);

--
-- Restrições para tabelas `produtos`
--
ALTER TABLE `produtos`
  ADD CONSTRAINT `fk_produto_fornecedor` FOREIGN KEY (`idFornecedor`) REFERENCES `fornecedores` (`idFornecedor`);

--
-- Restrições para tabelas `variacaoproduto`
--
ALTER TABLE `variacaoproduto`
  ADD CONSTRAINT `fk_variacaoproduto_produto` FOREIGN KEY (`idProduto`) REFERENCES `produtos` (`idProduto`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
