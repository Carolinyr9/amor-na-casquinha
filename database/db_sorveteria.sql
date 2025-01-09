-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Tempo de geração: 09/01/2025 às 14:45
-- Versão do servidor: 10.4.32-MariaDB
-- Versão do PHP: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Banco de dados: `db_sorveteria`
--

DELIMITER $$
--
-- Procedimentos
--
CREATE DEFINER=`root`@`localhost` PROCEDURE `AtribuirPedidoEntregador` (IN `p_idPedido` INT, IN `p_idEntregador` INT)   BEGIN
    UPDATE pedidos
    SET idEntregador = p_idEntregador
    WHERE idPedido = p_idPedido;
END$$

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

CREATE DEFINER=`root`@`localhost` PROCEDURE `CriarPedido` (IN `emailIN` VARCHAR(255), IN `dtPedidoIN` DATETIME, IN `tipoFreteIN` INT, IN `valorTotalIN` DECIMAL(10,2), IN `freteIN` DOUBLE, IN `meioPagamentoIN` VARCHAR(20))   BEGIN
    -- Obter o id do cliente com base no email
    SET @id_cliente := (SELECT idCliente FROM clientes WHERE email = emailIN);
    
    -- Verificar se o cliente tem um endereço
    IF NOT EXISTS (SELECT idEndereco FROM clientes WHERE idCliente = @id_cliente) THEN
        SELECT '403' AS 'Status', 
               'ERROR_ENDERECO_NAO_ENCONTRADO' AS 'Error', 
               '' AS 'Message';
    ELSE
        -- Obter o id do endereço
        SET @id_endereco := (SELECT idEndereco FROM clientes WHERE idCliente = @id_cliente);
        
        -- Definir o status do pedido
        SET @status_pedido := 'Aguardando Confirmação';

        -- Verificar o valor do frete
        IF freteIN IS NULL THEN
            SET @frete := 0;
        ELSE
            SET @frete := freteIN;
        END IF;

        -- Inserir o pedido na tabela 'pedidos', incluindo o meio de pagamento
        INSERT INTO pedidos(`idCliente`, `dtPedido`, `tipoFrete`, `idEndereco`, `valorTotal`, `statusPedido`, `frete`, `meioPagamento`)
            VALUES (@id_cliente, dtPedidoIN, tipoFreteIN, @id_endereco, valorTotalIN, @status_pedido, @frete, meioPagamentoIN);
        
        -- Obter o ID do pedido inserido
        SET @id_pedido := LAST_INSERT_ID();
        
        -- Retornar resposta de sucesso
        SELECT 
            '201' AS 'Status',
            '' AS 'Error',
            'SUCCESS_CREATED' AS 'Message',
            @id_pedido AS 'Body';
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

CREATE DEFINER=`root`@`localhost` PROCEDURE `DesativarFornecedorPorEmail` (IN `emailIN` VARCHAR(255))   BEGIN
	IF NOT EXISTS (SELECT email FROM fornecedores WHERE email like emailIN AND desativado != 1)
	THEN
		SELECT '403' AS 'Status', 'ERROR_FUNCIONARIO_NAO_ENCONTRADO' AS 'Error', '' AS 'Message';
	ELSE
        UPDATE fornecedores SET
			desativado = 1
			WHERE email like emailIN;
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

CREATE DEFINER=`root`@`localhost` PROCEDURE `DesativarProdutoPorID` (IN `idProdutoIN` INT)   BEGIN
    -- Verifica se o produto existe e não está desativado
    IF NOT EXISTS (SELECT idProduto FROM produtos WHERE idProduto = idProdutoIN AND desativado != 1) THEN
        SELECT '403' AS 'Status', 'ERROR_PRODUTO_NAO_ENCONTRADO' AS 'Error', '' AS 'Message';
    ELSE
        -- Obtenha o idEstoque correspondente ao idProduto
        SET @id_estoque := (SELECT idEstoque FROM estoque WHERE idProduto = idProdutoIN LIMIT 1);
        
        -- Verifique se o idEstoque foi encontrado antes de tentar atualizar
        IF @id_estoque IS NOT NULL THEN
            UPDATE estoque SET desativado = 1 WHERE idEstoque = @id_estoque;  -- Use a variável
        END IF;

        -- Atualize os produtos e variações para desativá-los
        UPDATE produtos SET desativado = 1 WHERE idProduto = idProdutoIN;
        UPDATE variacaoProduto SET desativado = 1 WHERE idProduto = idProdutoIN;

        -- Retorna mensagem de sucesso
        SELECT
            '204' AS 'Status',
            '' AS 'Error',
            'SUCCESS_DELETED' AS 'Message';
    END IF;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `DesativarVariacaoPorId` (IN `idVariacaoIN` INT)   BEGIN
    -- Verifica se a variação existe e não está desativada
    IF NOT EXISTS (SELECT idVariacao FROM variacaoProduto WHERE idVariacao = idVariacaoIN AND desativado != 1) THEN
        SELECT '403' AS 'Status', 'ERROR_VARIACAO_NAO_ENCONTRADA' AS 'Error', '' AS 'Message';
    ELSE
        -- Atualiza a variação para desativá-la
        UPDATE variacaoProduto SET desativado = 1 WHERE idVariacao = idVariacaoIN;

        -- Retorna mensagem de sucesso
        SELECT
            '204' AS 'Status',
            '' AS 'Error',
            'SUCCESS_DELETED' AS 'Message';
    END IF;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `EditarCliente` (IN `nomeIN` VARCHAR(255), IN `emailIN` VARCHAR(255), IN `telefoneIN` VARCHAR(25), IN `idEnderecoIN` INT, IN `ruaEnd` VARCHAR(255), IN `numeroEnd` INT, IN `complementoEnd` VARCHAR(255), IN `bairroEnd` VARCHAR(255), IN `cepEnd` VARCHAR(20), IN `cidadeEnd` VARCHAR(255), IN `estadoEnd` VARCHAR(255))   BEGIN
        UPDATE enderecos SET
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
            telefone = telefoneIN
            WHERE email = emailIN;
        SELECT 
            '204' AS 'Status',
            '' AS 'Error',
            'SUCCESS_UPDATED' AS 'Message';
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

CREATE DEFINER=`root`@`localhost` PROCEDURE `EditarFornecedorPorEmail` (IN `emailIN` VARCHAR(255), IN `emailNovoIN` VARCHAR(255), IN `nomeIN` VARCHAR(255), IN `telefoneIN` VARCHAR(25))   BEGIN
	IF NOT EXISTS (SELECT email from fornecedores where email like emailIN)
	THEN
		SELECT '403' AS 'Status', 'ERROR_EMAIL_NAO_ENCONTRADO' AS 'Error', '' AS 'Message';
	ELSE
		UPDATE fornecedores SET
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

CREATE DEFINER=`root`@`localhost` PROCEDURE `EditarProdutoPorId` (IN `idProdutoIN` INT, IN `nomeIN` VARCHAR(255), IN `marcaIN` VARCHAR(255), IN `descricaoIN` VARCHAR(255), IN `fotoIN` VARCHAR(50))   BEGIN
    IF NOT EXISTS (SELECT * FROM produtos WHERE idProduto = idProdutoIN) THEN
        SELECT '403' AS 'Status', 'ERROR_PRODUTO_NAO_ENCONTRADO' AS 'Error', '' AS 'Message';
    ELSE
        UPDATE produtos 
        SET nome = nomeIN, 
            marca = marcaIN, 
            descricao = descricaoIN, 
            foto = fotoIN 
        WHERE idProduto = idProdutoIN;
        
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

CREATE DEFINER=`root`@`localhost` PROCEDURE `EditarStatusPedido` (IN `p_idPedido` INT, IN `p_novoStatus` ENUM('Aguardando Pagamento','Aguardando Envio','A Caminho','Entregue','Cancelado','Concluído','Entrega Falhou'))   BEGIN
    IF EXISTS (SELECT 1 FROM pedidos WHERE idPedido = p_idPedido) THEN
        UPDATE pedidos
        SET statusPedido = p_novoStatus
        WHERE idPedido = p_idPedido;
    ELSE
        SIGNAL SQLSTATE '45000'
            SET MESSAGE_TEXT = 'Pedido não encontrado';
    END IF;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `EditarVariacaoPorID` (IN `idVariacaoIN` INT, IN `nomeVariacaoIN` VARCHAR(255), IN `precoVariacaoIN` DECIMAL(10,2), IN `fotoVariacaoIN` VARCHAR(255), IN `idProdutoIN` INT)   BEGIN
    -- Tente atualizar diretamente e depois verifique se houve alguma linha afetada
    UPDATE variacaoProduto 
    SET nomeVariacao = nomeVariacaoIN, 
        precoVariacao = precoVariacaoIN, 
        fotoVariacao = fotoVariacaoIN, 
        idProduto = idProdutoIN 
    WHERE idVariacao = idVariacaoIN AND desativado != 1;

    -- Verifica se alguma linha foi afetada pela atualização
    IF ROW_COUNT() = 0 THEN
        SELECT '403' AS 'Status', 'ERROR_NOME_NAO_ENCONTRADO' AS 'Error', '' AS 'Message';
    ELSE
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

CREATE DEFINER=`root`@`localhost` PROCEDURE `InserirFornecedor` (IN `nomeIN` VARCHAR(255), IN `emailIN` VARCHAR(255), IN `telefoneIN` VARCHAR(20), IN `cnpjIN` VARCHAR(18), IN `ruaIN` VARCHAR(255), IN `numeroIN` INT, IN `complementoIN` VARCHAR(255), IN `bairroIN` VARCHAR(255), IN `cepIN` VARCHAR(10), IN `cidadeIN` VARCHAR(255), IN `estadoIN` VARCHAR(255))   BEGIN
 
    IF NOT EXISTS (SELECT 1 FROM fornecedores WHERE cnpj = cnpj) THEN
        INSERT INTO enderecos (rua, numero, complemento, bairro, cep, cidade, estado) VALUES (ruaIN, numeroIN, complementoIN, bairroIN, cepIN, cidadeIN, estadoIN);
        SET @last_id_in_enderecos = LAST_INSERT_ID();
        INSERT INTO fornecedores (nome, email, telefone, cnpj, idEndereco)
        VALUES (nomeIN, emailIN, telefoneIN, cnpjIN, @last_id_in_enderecos);
        
        SELECT '201' AS Status, 'SUCCESS' AS Message, 'Fornecedor inserido com sucesso.' AS Info;
    ELSE
        SELECT '409' AS Status, 'ERROR_DUPLICATE' AS Message, 'Fornecedor com este email ou CNPJ já existe.' AS Info;
    END IF;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `InserirFuncionario` (IN `nomeIN` VARCHAR(255), IN `emailIN` VARCHAR(255), IN `telefoneIN` VARCHAR(255), IN `senhaIN` VARCHAR(255), IN `admIN` TINYINT)   BEGIN
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

CREATE DEFINER=`root`@`localhost` PROCEDURE `InserirProduto` (IN `nome` VARCHAR(50), IN `marca` VARCHAR(50), IN `descricao` VARCHAR(255), IN `idFornecedor` INT, IN `foto` VARCHAR(255), IN `desativado` INT)   BEGIN

        INSERT INTO produtos (
            `nome`, 
            `marca`, 
            `descricao`, 
            `idFornecedor`, 
            `foto`, 
            `desativado`
        ) VALUES (
            nome, 
            marca, 
            descricao, 
            idFornecedor, 
            foto, 
            desativado
        );
        
        SELECT 
            '201' AS 'Status', 
            '' AS 'Error', 
            'SUCCESS_CREATED' AS 'Message';
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `InserirVariacao` (IN `nomeVariacaoIN` VARCHAR(255), IN `precoVariacaoIN` DECIMAL(10,2), IN `fotoVaricaoIN` VARCHAR(255), IN `idProdutoIN` INT)   BEGIN
    IF EXISTS (SELECT nomeVariacao FROM variacaoProduto WHERE nomeVariacao LIKE nomeVariacaoIN) THEN
        SELECT '403' AS 'Status', 'ERROR_VARIACAO_CADASTRADA' AS 'Error', '' AS 'Message';
    ELSEIF NOT EXISTS (SELECT * FROM produtos WHERE idProduto = idProdutoIN) THEN
        SELECT '403' AS 'Status', 'ERROR_PRODUTO_NAO_CADASTRADO' AS 'Error', '' AS 'Message';
    ELSE
        INSERT INTO variacaoProduto(`nomeVariacao`, `precoVariacao`, `fotoVariacao`, `idProduto`, `desativado`) 
        VALUES (nomeVariacaoIN, precoVariacaoIN, fotoVaricaoIN, idProdutoIN, 0);
        
        SELECT 
            '201' AS 'Status',
            '' AS 'Error',
            'SUCCESS_CREATED' AS 'Message';
    END IF; 

END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `ListarCep` (IN `idEndereco` INT)   BEGIN
    SELECT cep
    FROM enderecos
    WHERE idEndereco = idEndereco;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `ListarClientePorEmail` (IN `email` VARCHAR(60))   BEGIN
    SELECT * 
    FROM clientes
    WHERE clientes.email LIKE email
    LIMIT 1;
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

CREATE DEFINER=`root`@`localhost` PROCEDURE `ListarEnderecoPorID` (IN `idEnderecoIN` INT)   BEGIN
    SELECT *
    FROM
        enderecos
    WHERE
        idEndereco = idEnderecoIN;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `ListarEntregadores` ()   BEGIN
    SELECT *
    FROM entregador;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `ListarEntregadorPorID` (IN `entregadorID` INT)   BEGIN
    SELECT *
    FROM entregador
    WHERE idEntregador = entregadorID;
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

CREATE DEFINER=`root`@`localhost` PROCEDURE `ListarFornecedorPorEmail` (IN `emailIN` VARCHAR(255))   BEGIN
    IF NOT EXISTS (SELECT * FROM fornecedores WHERE email = emailIN) THEN

        SELECT '403' AS Status, 'ERROR_FORNECEDOR_NAO_ENCONTRADO' AS Error, '' AS Message;
    ELSE
        SELECT * FROM fornecedores WHERE email = emailIN LIMIT 1;
        
        SELECT '201' AS Status, '' AS Error, 'SUCCESS_CREATED' AS Message;
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

CREATE DEFINER=`` PROCEDURE `ListarInformacoesPedido` (IN `p_idPedido` INT)   BEGIN
    SELECT 
        ip.idPedido,
        ip.quantidade,
        vp.idVariacao,
        vp.nomeVariacao AS NomeProduto,
        vp.precoVariacao AS Preco,
        vp.fotoVariacao AS Foto,
        vp.desativado AS ProdutoDesativado
    FROM 
        itens_pedido ip
    INNER JOIN 
        variacaoproduto vp
    ON 
        ip.idProduto = vp.idVariacao
    WHERE 
        ip.idPedido = p_idPedido;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `ListarPedidoPorCliente` (IN `emailIN` VARCHAR(255))   BEGIN
	SET @id_cliente := (SELECT idCliente FROM clientes WHERE email like emailIN);
    IF (isnull(@id_cliente))
    THEN
		SELECT '403' AS 'Status', 'ERROR_CLIENTE_NAO_ENCONTRADO' AS 'Error', '' AS 'Message';
	ELSE
		SELECT * FROM pedidos
			WHERE pedidos.idCliente = @id_cliente;
	END IF;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `ListarPedidoPorID` (IN `idPedidoIN` INT)   BEGIN
	SELECT * FROM pedidos
        WHERE pedidos.idPedido = idPedidoIN;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `ListarPedidoPorStatus` (`statusPedidoIN` VARCHAR(20))   BEGIN
	SELECT * 
		FROM pedidos
        LEFT OUTER JOIN pedidoProduto
		ON pedidos.idPedido = pedidoProduto.idPedido
        WHERE pedidos.statusPedido like statusPedidoIN;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `ListarPedidos` ()   BEGIN
    SELECT * FROM pedidos;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `ListarPedidosAtribuidosEntregador` (IN `entregador_id` INT)   BEGIN
    SELECT 
        p.idPedido,
        p.idCliente,
        p.dtPedido,
        p.dtPagamento,
        p.tipoFrete,
        p.rastreioFrete,
        p.idEndereco,
        p.valorTotal,
        p.qtdItems,
        p.dtCancelamento,
        p.motivoCancelamento,
        p.statusPedido
    FROM 
        Pedidos p
    INNER JOIN 
        Entregador e ON JSON_CONTAINS(e.idPedidosAtribuidos, JSON_QUOTE(CAST(p.idPedido AS CHAR)))
    WHERE 
        e.idEntregador = entregador_id;
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

CREATE DEFINER=`root`@`localhost` PROCEDURE `ListarPedidosPorEmailEntregador` (IN `p_email` VARCHAR(255))   BEGIN
    DECLARE v_idEntregador INT;
    
    SELECT idEntregador INTO v_idEntregador
    FROM entregador
    WHERE email = p_email;

    IF v_idEntregador IS NULL THEN
        SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Entregador não encontrado';
    ELSE
        SELECT *
        FROM pedidos p
        WHERE p.idEntregador = v_idEntregador;
    END IF;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `ListarPedidosPorEntregador` (IN `p_idEntregador` INT)   BEGIN
    SELECT *
    FROM 
        Pedidos
    WHERE 
        idEntregador = p_idEntregador
    ORDER BY 
        statusPedido ASC, 
        idPedido ASC;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `ListarProdutoAtivo` (IN `limitF` INT, IN `offsetF` INT)   BEGIN
	SELECT * 
		FROM produtos
        WHERE desativado = 0
		LIMIT limitF 
        OFFSET offsetF;
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

CREATE DEFINER=`root`@`localhost` PROCEDURE `ListarVariacaoAtivaPorId` (IN `id` INT)   BEGIN
    SELECT * 
    FROM variacaoproduto 
    WHERE desativado = 0
    AND idVariacao = id;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `ListarVariacaoPorID` (`idVaricaoIN` INT)   BEGIN
	SELECT * FROM variacaoProduto WHERE desativado != 1 AND idVariacao = idVaricaoIN;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `ListarVariacaoPorTipo` (IN `id` INT)   BEGIN
	SELECT * 
		FROM variacaoProduto 
        WHERE desativado = 0
		AND idProduto = id;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `ListarVaricaoPorCodigoProduto` (`codigoProdutoIN` VARCHAR(255))   BEGIN
	SET @produto_id := (SELECT idProduto FROM produtos WHERE codigoProduto like codigoProdutoIN);
	SELECT * FROM variacaoProduto WHERE desativado != 1 AND idProduto = @produto_id;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `ListarVaricaoPorProduto` (`idProduto` INT)   BEGIN
	SELECT * FROM variacaoProduto WHERE desativado != 1 AND idProduto = idProduto;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `Login` (IN `emailIN` VARCHAR(255))   BEGIN
    IF EXISTS (SELECT email FROM funcionarios WHERE email LIKE CONCAT('%', emailIN, '%'))
    THEN
        SELECT * FROM funcionarios WHERE email LIKE CONCAT('%', emailIN, '%') LIMIT 1;
    
    ELSEIF EXISTS (SELECT email FROM clientes WHERE email LIKE CONCAT('%', emailIN, '%'))
    THEN
        SELECT * 
        FROM clientes 
        INNER JOIN enderecos ON clientes.idEndereco = enderecos.idEndereco 
        WHERE email LIKE CONCAT('%', emailIN, '%') LIMIT 1;
    
    ELSEIF EXISTS (SELECT email FROM entregador WHERE email LIKE CONCAT('%', emailIN, '%'))
    THEN
        SELECT * FROM entregador WHERE email LIKE CONCAT('%', emailIN, '%') LIMIT 1;
    
    ELSE
        SELECT '403' AS 'Status', 'ERROR_EMAIL_NAO_ENCONTRADO' AS 'Error', '' AS 'Message', '' AS 'Body';
    END IF;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `SalvarItensPedido` (IN `idPedido` INT, IN `idProduto` INT, IN `quantidade` INT)   BEGIN
 
    -- Inserir os itens do pedido na tabela 'itens_pedido'
    INSERT INTO itens_pedido(idPedido, idProduto, quantidade)
    VALUES (idPedido, idProduto, quantidade);

    -- Retornar resposta de sucesso
    SELECT '201' AS 'Status', 'SUCCESS' AS 'Message', 'Item do pedido salvo com sucesso' AS 'Body';
END$$

--
-- Funções
--
CREATE DEFINER=`root`@`localhost` FUNCTION `FN_GetClienteId` (`email` VARCHAR(60)) RETURNS INT(11) DETERMINISTIC BEGIN
    DECLARE clienteId INT;

    SELECT idCliente INTO clienteId
    FROM clientes
    WHERE clientes.email LIKE email
    LIMIT 1;

    IF clienteId IS NOT NULL THEN
        RETURN clienteId;
    ELSE
        RETURN NULL;
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
(1, 0, 'joao lucas binario', 'jo@email.com', '$2y$10$VxfyRb4qZtF8nrk/BJs1NuvJy/sG5WxHGJFbyS9gjB7SQ6.lnI1yC', '44564-2135', 'CLIE', 1),
(2, 0, 'Caroliny Rocha Sampaio', 'carol@email.com', '$2y$10$VxfyRb4qZtF8nrk/BJs1NuvJy/sG5WxHGJFbyS9gjB7SQ6.lnI1yC', '44564-2132', 'CLIE', 5),
(3, 0, 'Joelita Rocha', 'joelita@email.com', '$2y$10$hMHoDvGNbpdT9285sSbvVOUD49txnbVnFGdr0aE6pKrYlHnKiFkNW', '(11) 99898-4901', 'CLIE', 6);

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
(1, '90570020', 'Rua Tobias da Silva', 120, '', 'Moinhos de Vento', 'Porto Alegre', 'Rio Grande do Sul'),
(2, '08110520', 'Rua Edson de Carvalho Guimarães', 19, NULL, 'Vila Alabama', 'São Paulo', 'SP'),
(3, '08110492', 'Rua Moisés José Pereira', 50, '', 'Vila Alabama', 'São Paulo', 'SP'),
(4, '08110640', 'Rua Raimundo Mendes Figueiredo', 152, '', 'Vila Alabama', 'São Paulo', 'SP'),
(5, '08110210', 'Rua Enseada das Garoupas', 401, '', 'Vila Silva Teles', 'São Paulo', 'SP'),
(6, '08110600', 'Rua São Sebastião do Tocantins', 123, 'Casa', 'Vila Imac.', 'São Paulo', 'SP');

-- --------------------------------------------------------

--
-- Estrutura para tabela `entregador`
--

CREATE TABLE `entregador` (
  `idEntregador` int(11) NOT NULL,
  `desativado` int(11) DEFAULT 0,
  `perfil` varchar(100) DEFAULT NULL,
  `nome` varchar(100) NOT NULL,
  `telefone` varchar(15) DEFAULT NULL,
  `email` varchar(100) NOT NULL,
  `senha` varchar(255) NOT NULL,
  `cnh` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `entregador`
--

INSERT INTO `entregador` (`idEntregador`, `desativado`, `perfil`, `nome`, `telefone`, `email`, `senha`, `cnh`) VALUES
(1, 0, 'ENTR', 'João Silva', '1234567890', 'joao.silva@example.com', '$2y$10$VxfyRb4qZtF8nrk/BJs1NuvJy/sG5WxHGJFbyS9gjB7SQ6.lnI1yC', '12345678900'),
(2, 0, 'ENTR', 'Maria Oliveira', '0987654321', 'maria.oliveira@example.com', '$2y$10$VxfyRb4qZtF8nrk/BJs1NuvJy/sG5WxHGJFbyS9gjB7SQ6.lnI1yC', '09876543210'),
(3, 0, 'ENTR', 'Carlos Santos', '1122334455', 'carlos.santos@example.com', '$2y$10$VxfyRb4qZtF8nrk/BJs1NuvJy/sG5WxHGJFbyS9gjB7SQ6.lnI1yC', '11223344550'),
(4, 0, 'ENTR', 'Ana Costa', '2233445566', 'ana.costa@example.com', '$2y$10$VxfyRb4qZtF8nrk/BJs1NuvJy/sG5WxHGJFbyS9gjB7SQ6.lnI1yC', '22334455660'),
(5, 0, 'ENTR', 'Pedro Lima', '3344556677', 'pedro.lima@example.com', '$2y$10$VxfyRb4qZtF8nrk/BJs1NuvJy/sG5WxHGJFbyS9gjB7SQ6.lnI1yC', '33445566770');

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

--
-- Despejando dados para a tabela `estoque`
--

INSERT INTO `estoque` (`idEstoque`, `idProduto`, `dtEntrada`, `quantidade`, `dtFabricacao`, `dtVencimento`, `precoCompra`, `qtdMinima`, `qtdVendida`, `qtdOcorrencia`, `ocorrencia`, `desativado`) VALUES
(1, 1, '2024-01-01', 0, '2023-12-01', '2024-06-01', 10.00, 5, 0, 0, 'Pote Kibon', 1),
(2, 2, '2024-01-01', 0, '2023-12-05', '2024-06-05', 5.00, 3, 0, 0, 'Picolé Marca', 1),
(3, 3, '2024-01-01', 0, '2023-12-10', '2024-06-10', 2.50, 2, 0, 0, 'ChupChup Garoto', 1),
(4, 4, '2024-01-01', 0, '2023-12-15', '2024-06-15', 8.00, 4, 0, 0, 'Sundae Nestle', 1);

-- --------------------------------------------------------

--
-- Estrutura para tabela `fornecedores`
--

CREATE TABLE `fornecedores` (
  `idFornecedor` int(11) NOT NULL,
  `nome` varchar(255) NOT NULL,
  `telefone` varchar(20) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `cnpj` varchar(20) NOT NULL,
  `desativado` int(11) DEFAULT NULL,
  `idEndereco` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `fornecedores`
--

INSERT INTO `fornecedores` (`idFornecedor`, `nome`, `telefone`, `email`, `cnpj`, `desativado`, `idEndereco`) VALUES
(1, 'Sorvetes do Sul', '51987654325', 'contato@sorvetesdosul.com.br', '12.345.678/0001-99', 0, 1),
(2, 'Gelados Tropical', '21987654321', 'vendas@geladostropical.com.br', '98.765.432/0001-11', 0, 2),
(3, 'Doces e Sorvetes Ltda', NULL, 'info@docesesorvetes.com.br', '56.789.012/0001-55', 1, 3),
(4, 'IceDream Sorvetes', '31987654321', 'icecream@email.com', '23.456.789/0001-77', 0, 4),
(5, 'Delícias Geladas', NULL, 'delicias_geladas@email.com', '34.567.890/0001-88', 0, 5);

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
(1, 0, 1, 'FUNC', 'Jessica', '96309-85895', 'je@email.com', '$2y$10$VxfyRb4qZtF8nrk/BJs1NuvJy/sG5WxHGJFbyS9gjB7SQ6.lnI1yC', 1),
(3, 0, NULL, 'FUNC', 'Carol', '(11) 99999-9998', 'ca@email.com', '$2y$10$VxfyRb4qZtF8nrk/BJs1NuvJy/sG5WxHGJFbyS9gjB7SQ6.lnI1yC', NULL),
(4, 1, 1, 'FUNC', 'Antonio', '(11) 99999-9998', 'an@email.com', NULL, NULL),
(7, 0, NULL, 'FUNC', 'Juliana', '11998984901', 'ju@email.com', '$2y$10$6OMwNydIdQ0bzq...6E1fOzxf7xQexcCIQLvTyET86aExXaGbyJMC', NULL);

-- --------------------------------------------------------

--
-- Estrutura para tabela `itens_pedido`
--

CREATE TABLE `itens_pedido` (
  `idPedido` int(11) DEFAULT NULL,
  `idProduto` int(11) DEFAULT NULL,
  `quantidade` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `itens_pedido`
--

INSERT INTO `itens_pedido` (`idPedido`, `idProduto`, `quantidade`) VALUES
(187, 1, 1),
(187, 9, 3),
(187, 12, 2),
(188, 4, 1),
(188, 17, 1),
(189, 12, 1),
(190, 1, 1);

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
  `idEndereco` int(11) DEFAULT NULL,
  `valorTotal` decimal(12,2) DEFAULT NULL,
  `qtdItems` int(11) NOT NULL,
  `dtCancelamento` datetime DEFAULT NULL,
  `motivoCancelamento` text DEFAULT NULL,
  `statusPedido` enum('Aguardando Confirmação','Aguardando Envio','A Caminho','Entregue','Cancelado','Concluído','Entrega Falhou') DEFAULT 'Aguardando Confirmação',
  `idEntregador` int(11) DEFAULT NULL,
  `frete` double DEFAULT NULL,
  `meioPagamento` enum('Cartão de Débito','Cartão de Crédito','Dinheiro') NOT NULL DEFAULT 'Dinheiro'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `pedidos`
--

INSERT INTO `pedidos` (`idPedido`, `idCliente`, `dtPedido`, `dtPagamento`, `tipoFrete`, `idEndereco`, `valorTotal`, `qtdItems`, `dtCancelamento`, `motivoCancelamento`, `statusPedido`, `idEntregador`, `frete`, `meioPagamento`) VALUES
(187, 1, '2025-01-04 23:01:35', NULL, 0, 1, 80.94, 0, NULL, NULL, 'Cancelado', NULL, 0, 'Cartão de Débito'),
(188, 1, '2025-01-09 13:21:32', NULL, 1, 1, 73.21, 0, NULL, NULL, 'Aguardando Envio', 2, 22.72, 'Cartão de Crédito'),
(189, 1, '0000-00-00 00:00:00', NULL, 0, 1, 16.99, 0, NULL, NULL, 'Aguardando Confirmação', NULL, 0, 'Cartão de Débito'),
(190, 1, '2025-01-09 09:57:36', NULL, 0, 1, 25.99, 0, NULL, NULL, 'Aguardando Confirmação', NULL, 0, 'Cartão de Crédito');

-- --------------------------------------------------------

--
-- Estrutura para tabela `produtos`
--

CREATE TABLE `produtos` (
  `idProduto` int(11) NOT NULL,
  `idFornecedor` int(11) NOT NULL,
  `nome` varchar(255) NOT NULL,
  `marca` varchar(255) NOT NULL,
  `descricao` text DEFAULT NULL,
  `desativado` int(11) DEFAULT NULL,
  `foto` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `produtos`
--

INSERT INTO `produtos` (`idProduto`, `idFornecedor`, `nome`, `marca`, `descricao`, `desativado`, `foto`) VALUES
(1, 1, 'Pote', 'Kibon', 'Potes de sorvete', 0, '98fb6a95c11ab1b4270121f66ced7c98.png'),
(2, 2, 'Picolé', 'Marca', 'Picolé', 0, 'picoleLogo.png'),
(3, 2, 'ChupChup', 'Garoto', 'ChupChup', 0, 'chupLogo.png'),
(4, 2, 'Sundae', 'Nestle', 'Sundae', 0, 'sundaeLogo.png'),
(5, 1, 'Açaí', 'AcaiGalaxy', 'Açai', 0, 'acaiLogo.png');

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
(2, 0, 'Chocolitano', 22.50, 'chocolitanoPote.png', 1),
(3, 0, 'Milho Verde - Pote 2L', 34.50, 'milho-verdePote.png', 1),
(4, 0, 'ChupChup - Unicórnio', 3.99, 'unicornioChup.png', 3),
(5, 0, 'Chup Chup - Coco', 3.97, 'cocoChup.png', 3),
(6, 1, 'Chup Chup - Morango', 3.99, 'morangoChup.png', 3),
(7, 0, 'ChupChup - Maracujá', 3.99, 'maracujaChup.png', 3),
(8, 0, 'Picolé - Mousse de Doce de Leite', 7.98, 'mousse-doce-leitePicole.png', 2),
(9, 0, ' Picolé - Coraçãozinho', 6.99, '71724a9521477340ecde4400800ba580.png', 2),
(10, 0, 'Picolé - Açaí', 7.99, 'acaiPicole.png', 2),
(11, 0, 'Picolé - Flocos', 7.99, 'flocosPicole.png', 2),
(12, 0, 'Sundae - Morango', 16.99, 'morangoSundae.png', 4),
(13, 0, 'Sundae - Baunilha', 16.99, 'baunilhaSundae.png', 4),
(14, 0, 'Napolitano - Pote 2L', 36.50, 'napolitanoPote.png', 1),
(15, 0, 'Açai com banana', 46.50, 'acai-bananaAcai.png', 5),
(16, 0, 'Açai com morango', 46.50, 'acai-morangoAcai.png', 5),
(17, 0, 'Açai com leite', 46.50, 'acai-leitinhoAcai.png', 5),
(18, 0, 'Açai com iorgute', 37.50, 'acai-iogurteAcai.png', 5),
(19, 0, 'Açai com guarana', 36.50, 'acai-guaranaAcai.png', 5),
(20, 0, 'Açai Original', 40.00, 'acaiLogo.png', 5);

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
-- Índices de tabela `entregador`
--
ALTER TABLE `entregador`
  ADD PRIMARY KEY (`idEntregador`),
  ADD UNIQUE KEY `email` (`email`);

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
-- Índices de tabela `itens_pedido`
--
ALTER TABLE `itens_pedido`
  ADD KEY `idPedido` (`idPedido`),
  ADD KEY `idProduto` (`idProduto`);

--
-- Índices de tabela `pedidos`
--
ALTER TABLE `pedidos`
  ADD PRIMARY KEY (`idPedido`),
  ADD KEY `fk_pedido_endereco` (`idEndereco`),
  ADD KEY `fk_pedido_cliente` (`idCliente`),
  ADD KEY `fk_entregador` (`idEntregador`);

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
  MODIFY `idCliente` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

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
-- AUTO_INCREMENT de tabela `entregador`
--
ALTER TABLE `entregador`
  MODIFY `idEntregador` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de tabela `estoque`
--
ALTER TABLE `estoque`
  MODIFY `idEstoque` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de tabela `fornecedores`
--
ALTER TABLE `fornecedores`
  MODIFY `idFornecedor` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de tabela `funcionarios`
--
ALTER TABLE `funcionarios`
  MODIFY `idFuncionario` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT de tabela `pedidos`
--
ALTER TABLE `pedidos`
  MODIFY `idPedido` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=191;

--
-- AUTO_INCREMENT de tabela `produtos`
--
ALTER TABLE `produtos`
  MODIFY `idProduto` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT de tabela `variacaoproduto`
--
ALTER TABLE `variacaoproduto`
  MODIFY `idVariacao` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

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
-- Restrições para tabelas `itens_pedido`
--
ALTER TABLE `itens_pedido`
  ADD CONSTRAINT `itens_pedido_ibfk_1` FOREIGN KEY (`idPedido`) REFERENCES `pedidos` (`idPedido`),
  ADD CONSTRAINT `itens_pedido_ibfk_2` FOREIGN KEY (`idProduto`) REFERENCES `variacaoproduto` (`idVariacao`);

--
-- Restrições para tabelas `pedidos`
--
ALTER TABLE `pedidos`
  ADD CONSTRAINT `fk_entregador` FOREIGN KEY (`idEntregador`) REFERENCES `entregador` (`idEntregador`) ON DELETE SET NULL,
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
