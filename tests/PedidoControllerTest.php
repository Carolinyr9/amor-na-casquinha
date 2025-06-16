<?php

use PHPUnit\Framework\TestCase;
use app\controller\PedidoController;
use app\repository\PedidoRepository;
use app\model\Pedido;

class PedidoControllerTest extends TestCase
{
    private $pedidoRepositoryMock;
    private $pedidoController;

    protected function setUp(): void {
        $this->pedidoRepositoryMock = $this->createMock(PedidoRepository::class);
        $this->pedidoController = new PedidoController($this->pedidoRepositoryMock);
    }


    ## Testes para listarPedidoPorIdCliente

    public function testListarPedidoPorIdClienteRetornaPedidosValidos() {
        $idCliente = 101;
        $pedidosEsperados = [
            new Pedido(1, $idCliente, '2024-06-15 10:00:00', null, 'Delivery', 1, 35.50, null, null, 'Aguardando Confirmação', null, 5.00, 'Cartão de Crédito', 0),
            new Pedido(2, $idCliente, '2024-06-15 11:30:00', null, 'Retirada na Loja', 2, 20.00, null, null, 'Preparando pedido', null, 0.00, 'Dinheiro', 25.00),
        ];

        $this->pedidoRepositoryMock->method('listarPedidoPorIdCliente')
            ->with($idCliente)
            ->willReturn($pedidosEsperados);

        $pedidosRetornados = $this->pedidoController->listarPedidoPorIdCliente($idCliente);

        $this->assertIsArray($pedidosRetornados);
        $this->assertCount(2, $pedidosRetornados);
        $this->assertInstanceOf(Pedido::class, $pedidosRetornados[0]);
        $this->assertEquals($idCliente, $pedidosRetornados[0]->getIdCliente());
    }

    public function testListarPedidoPorIdClienteRetornaFalseParaIDClienteInvalido() {
        $this->assertFalse($this->pedidoController->listarPedidoPorIdCliente(null));
        $this->assertFalse($this->pedidoController->listarPedidoPorIdCliente(''));
        $this->assertFalse($this->pedidoController->listarPedidoPorIdCliente('abc'));
    }

    public function testListarPedidoPorIdClienteRetornaFalseQuandoNaoEncontraPedidos() {
        $idCliente = 999;
        $this->pedidoRepositoryMock->method('listarPedidoPorIdCliente')
            ->with($idCliente)
            ->willReturn(false);

        $pedidosRetornados = $this->pedidoController->listarPedidoPorIdCliente($idCliente);
        $this->assertFalse($pedidosRetornados);
    }

    public function testListarPedidoPorIdClienteLidaComExcecaoDoRepositorio() {
        $idCliente = 101;
        $this->pedidoRepositoryMock->method('listarPedidoPorIdCliente')
            ->willThrowException(new Exception("Erro de conexão com o banco de dados"));

        $pedidosRetornados = $this->pedidoController->listarPedidoPorIdCliente($idCliente);
        $this->assertFalse($pedidosRetornados);
    }

    ## Testes para listarPedidoPorId

    public function testListarPedidoPorIdRetornaPedidoValido() {
        $idPedido = 1;
        $pedidoEsperado = new Pedido(1, 101, '2024-06-15 10:00:00', null, 'Delivery', 1, 35.50, null, null, 'Aguardando Confirmação', null, 5.00, 'Cartão de Crédito', 0);

        $this->pedidoRepositoryMock->method('listarPedidoPorId')
            ->with($idPedido)
            ->willReturn($pedidoEsperado);

        $pedidoRetornado = $this->pedidoController->listarPedidoPorId($idPedido);

        $this->assertInstanceOf(Pedido::class, $pedidoRetornado);
        $this->assertEquals($idPedido, $pedidoRetornado->getIdPedido());
    }

    public function testListarPedidoPorIdRetornaFalseParaIDPedidoInvalido() {
        $this->assertFalse($this->pedidoController->listarPedidoPorId(null));
        $this->assertFalse($this->pedidoController->listarPedidoPorId(''));
        $this->assertFalse($this->pedidoController->listarPedidoPorId('abc'));
    }

    public function testListarPedidoPorIdRetornaFalseQuandoNaoEncontraPedido() {
        $idPedido = 999;
        $this->pedidoRepositoryMock->method('listarPedidoPorId')
            ->with($idPedido)
            ->willReturn(false);

        $pedidoRetornado = $this->pedidoController->listarPedidoPorId($idPedido);
        $this->assertFalse($pedidoRetornado);
    }

    public function testListarPedidoPorIdLidaComExcecaoDoRepositorio() {
        $idPedido = 1;
        $this->pedidoRepositoryMock->method('listarPedidoPorId')
            ->willThrowException(new Exception("Erro de banco de dados"));

        $pedidoRetornado = $this->pedidoController->listarPedidoPorId($idPedido);
        $this->assertFalse($pedidoRetornado);
    }

    ## Testes para criarPedido

    public function testCriarPedidoComDadosValidosRetornaIdDoNovoPedido() {
        $dadosPedido = [
            'idCliente' => 101,
            'idEndereco' => 1,
            'tipoFrete' => 'Delivery',
            'valorTotal' => 45.00,
            'frete' => 5.00,
            'meioDePagamento' => 'Pix',
            'trocoPara' => 0,
        ];
        $idGerado = 3;

        $this->pedidoRepositoryMock->method('criarPedido')
            ->willReturn($idGerado);

        $resultado = $this->pedidoController->criarPedido($dadosPedido);
        $this->assertEquals($idGerado, $resultado);
    }

    public function testCriarPedidoRetornaFalseParaDadosInvalidos() {
        $dadosInvalidos = [
            'idEndereco' => 1,
            'tipoFrete' => 'Delivery',
            'valorTotal' => 45.00,
            'frete' => 5.00,
            'meioDePagamento' => 'Pix',
            'trocoPara' => 0,
        ];
        $this->assertFalse($this->pedidoController->criarPedido($dadosInvalidos));

        $dadosInvalidos = [
            'idCliente' => 101,
            'idEndereco' => 1,
            'tipoFrete' => 'Delivery',
            'valorTotal' => '',
            'frete' => 5.00,
            'meioDePagamento' => 'Pix',
            'trocoPara' => 0,
        ];
        $this->assertFalse($this->pedidoController->criarPedido($dadosInvalidos));
    }

    public function testCriarPedidoRetornaFalseQuandoRepositorioFalha() {
        $dadosPedido = [
            'idCliente' => 101,
            'idEndereco' => 1,
            'tipoFrete' => 'Delivery',
            'valorTotal' => 45.00,
            'frete' => 5.00,
            'meioDePagamento' => 'Pix',
            'trocoPara' => 0,
        ];

        $this->pedidoRepositoryMock->method('criarPedido')
            ->willReturn(false);

        $resultado = $this->pedidoController->criarPedido($dadosPedido);
        $this->assertFalse($resultado);
    }

    public function testCriarPedidoLidaComExcecaoDoRepositorio() {
        $dadosPedido = [
            'idCliente' => 101,
            'idEndereco' => 1,
            'tipoFrete' => 'Delivery',
            'valorTotal' => 45.00,
            'frete' => 5.00,
            'meioDePagamento' => 'Pix',
            'trocoPara' => 0,
        ];

        $this->pedidoRepositoryMock->method('criarPedido')
            ->willThrowException(new Exception("Erro ao inserir pedido no banco"));

        $resultado = $this->pedidoController->criarPedido($dadosPedido);
        $this->assertFalse($resultado);
    }

    ## Testes para listarPedidos

    public function testListarPedidosRetornaArrayDePedidosOrdenados() {
        $dadosRepository = [
            new Pedido(1, 101, '2024-06-15 10:00:00', null, 'Delivery', 1, 35.50, null, null, 'Entregue', null, 5.00, 'Cartão de Crédito', 0),
            new Pedido(2, 102, '2024-06-15 11:30:00', null, 'Retirada na Loja', 2, 20.00, null, null, 'Aguardando Confirmação', null, 0.00, 'Dinheiro', 25.00),
            new Pedido(3, 103, '2024-06-15 12:00:00', null, 'Delivery', 3, 50.00, null, null, 'A Caminho', null, 7.00, 'Pix', 0),
        ];

        $this->pedidoRepositoryMock->method('listarPedidos')
            ->willReturn($dadosRepository);

        $pedidosRetornados = $this->pedidoController->listarPedidos();

        $this->assertIsArray($pedidosRetornados);
        $this->assertCount(3, $pedidosRetornados);
        $this->assertInstanceOf(Pedido::class, $pedidosRetornados[0]);

        $this->assertEquals('Aguardando Confirmação', $pedidosRetornados[0]->getStatusPedido());
        $this->assertEquals('A Caminho', $pedidosRetornados[1]->getStatusPedido());
        $this->assertEquals('Entregue', $pedidosRetornados[2]->getStatusPedido());
    }

    public function testListarPedidosRetornaFalseQuandoNaoHaPedidos() {
        $this->pedidoRepositoryMock->method('listarPedidos')
            ->willReturn([]);

        $pedidosRetornados = $this->pedidoController->listarPedidos();
        $this->assertFalse($pedidosRetornados);
    }

    public function testListarPedidosLidaComExcecaoDoRepositorio() {
        $this->pedidoRepositoryMock->method('listarPedidos')
            ->willThrowException(new Exception("Erro de banco de dados na listagem"));

        $pedidosRetornados = $this->pedidoController->listarPedidos();
        $this->assertFalse($pedidosRetornados);
    }

    ## Testes para listarPedidoPorIdEntregador

    public function testListarPedidoPorIdEntregadorRetornaPedidosValidos() {
        $idEntregador = 201;
        $pedidosEsperados = [
            new Pedido(187, 1, '2025-01-04 23:01:35', NULL, 0, 1, 80.94, NULL, NULL, 'Em Andamento', 201, 0, 'Cartão de Débito', NULL),
            new Pedido(188, 1, '2025-01-04 00:01:35', NULL, 0, 1, 50.00, NULL, NULL, 'Em Andamento', 201, 0, 'Cartão de Débito', NULL),
        ];

        $this->pedidoRepositoryMock->method('listarPedidosEntregador')
            ->with($idEntregador)
            ->willReturn($pedidosEsperados);

        $pedidosRetornados = $this->pedidoController->listarPedidoPorIdEntregador($idEntregador);

        $this->assertIsArray($pedidosRetornados);
        $this->assertCount(2, $pedidosRetornados);
        $this->assertInstanceOf(Pedido::class, $pedidosRetornados[0]);
        $this->assertEquals($idEntregador, $pedidosRetornados[0]->getIdEntregador());
    }

    public function testListarPedidoPorIdEntregadorRetornaFalseParaIDEntregadorInvalido() {
        $this->assertFalse($this->pedidoController->listarPedidoPorIdEntregador(null));
        $this->assertFalse($this->pedidoController->listarPedidoPorIdEntregador(''));
        $this->assertFalse($this->pedidoController->listarPedidoPorIdEntregador('xyz'));
    }

    public function testListarPedidoPorIdEntregadorRetornaFalseQuandoNaoEncontraPedidos() {
        $idEntregador = 999;
        $this->pedidoRepositoryMock->method('listarPedidosEntregador')
            ->with($idEntregador)
            ->willReturn(false);

        $pedidosRetornados = $this->pedidoController->listarPedidoPorIdEntregador($idEntregador);
        $this->assertFalse($pedidosRetornados);
    }

    public function testListarPedidoPorIdEntregadorLidaComExcecaoDoRepositorio() {
        $idEntregador = 201;
        $this->pedidoRepositoryMock->method('listarPedidosEntregador')
            ->willThrowException(new Exception("Erro de banco de dados ao listar por entregador"));

        $pedidosRetornados = $this->pedidoController->listarPedidoPorIdEntregador($idEntregador);
        $this->assertFalse($pedidosRetornados);
    }

    ## Testes para listarPedidosPorPeriodo

    public function testListarPedidosPorPeriodoRetornaPedidosValidos() {
        $dados = [
            'dataInicio' => '2024-06-01',
            'dataFim' => '2024-06-30'
        ];
        $pedidosEsperados = [
            new Pedido(6, 106, '2024-06-10 14:00:00', null, 'Delivery', 6, 70.00, null, null, 'Entregue', null, 10.00, 'Cartão de Crédito', 0),
            new Pedido(7, 107, '2024-06-20 16:30:00', null, 'Retirada na Loja', 7, 25.00, null, null, 'Concluído', null, 0.00, 'Pix', 0),
        ];

        $this->pedidoRepositoryMock->method('listarPedidosPorPeriodo')
            ->with($dados['dataInicio'], $dados['dataFim'])
            ->willReturn($pedidosEsperados);

        $pedidosRetornados = $this->pedidoController->listarPedidosPorPeriodo($dados);

        $this->assertIsArray($pedidosRetornados);
        $this->assertCount(2, $pedidosRetornados);
        $this->assertInstanceOf(Pedido::class, $pedidosRetornados[0]);
    }

    public function testListarPedidosPorPeriodoRetornaFalseParaDadosInvalidos() {
        $dadosInvalidos = ['dataFim' => '2024-06-30'];
        $this->assertFalse($this->pedidoController->listarPedidosPorPeriodo($dadosInvalidos));

        $dadosInvalidos = ['dataInicio' => '2024-06-01'];
        $this->assertFalse($this->pedidoController->listarPedidosPorPeriodo($dadosInvalidos));

        $dadosInvalidos = ['dataInicio' => '', 'dataFim' => ''];
        $this->assertFalse($this->pedidoController->listarPedidosPorPeriodo($dadosInvalidos));
    }

    public function testListarPedidosPorPeriodoRetornaFalseQuandoNaoEncontraPedidos() {
        $dados = [
            'dataInicio' => '2025-01-01',
            'dataFim' => '2025-01-31'
        ];
        $this->pedidoRepositoryMock->method('listarPedidosPorPeriodo')
            ->with($dados['dataInicio'], $dados['dataFim'])
            ->willReturn(false);

        $pedidosRetornados = $this->pedidoController->listarPedidosPorPeriodo($dados);
        $this->assertFalse($pedidosRetornados);
    }

    public function testListarPedidosPorPeriodoLidaComExcecaoDoRepositorio() {
        $dados = [
            'dataInicio' => '2024-06-01',
            'dataFim' => '2024-06-30'
        ];
        $this->pedidoRepositoryMock->method('listarPedidosPorPeriodo')
            ->willThrowException(new Exception("Erro de banco de dados ao listar por período"));

        $pedidosRetornados = $this->pedidoController->listarPedidosPorPeriodo($dados);
        $this->assertFalse($pedidosRetornados);
    }

    ## Testes para atribuirEntregadorPedido

    public function testAtribuirEntregadorPedidoRetornaTrueQuandoAtribuicaoEhBemSucedida() {
        $dados = [
            'idPedido' => 1,
            'idEntregador' => 201
        ];
        $pedidoExistente = new Pedido(1, 101, '2024-06-15 10:00:00', null, 'Delivery', 1, 35.50, null, null, 'Aguardando Confirmação', null, 5.00, 'Cartão de Crédito', 0);

        $this->pedidoRepositoryMock->method('listarPedidoPorId')
            ->with($dados['idPedido'])
            ->willReturn($pedidoExistente);

        $this->pedidoRepositoryMock->method('atribuirEntregadorPedido')
            ->with($dados['idPedido'], $dados['idEntregador'])
            ->willReturn(true);

        $resultado = $this->pedidoController->atribuirEntregadorPedido($dados);
        $this->assertTrue($resultado);
        $this->assertEquals($dados['idEntregador'], $pedidoExistente->getIdEntregador());
    }

    public function testAtribuirEntregadorPedidoRetornaFalseParaDadosInvalidos() {
        $dadosInvalidos = ['idEntregador' => 201];
        $this->assertFalse($this->pedidoController->atribuirEntregadorPedido($dadosInvalidos));

        $dadosInvalidos = ['idPedido' => 1];
        $this->assertFalse($this->pedidoController->atribuirEntregadorPedido($dadosInvalidos));
    }

    public function testAtribuirEntregadorPedidoRetornaFalseQuandoPedidoNaoEncontrado() {
        $dados = [
            'idPedido' => 999,
            'idEntregador' => 201
        ];

        $this->pedidoRepositoryMock->method('listarPedidoPorId')
            ->with($dados['idPedido'])
            ->willReturn(false);

        $resultado = $this->pedidoController->atribuirEntregadorPedido($dados);
        $this->assertFalse($resultado);
    }

    public function testAtribuirEntregadorPedidoRetornaFalseQuandoRepositorioFalha() {
        $dados = [
            'idPedido' => 1,
            'idEntregador' => 201
        ];
        $pedidoExistente = new Pedido(1, 101, '2024-06-15 10:00:00', null, 'Delivery', 1, 35.50, null, null, 'Aguardando Confirmação', null, 5.00, 'Cartão de Crédito', 0);

        $this->pedidoRepositoryMock->method('listarPedidoPorId')
            ->with($dados['idPedido'])
            ->willReturn($pedidoExistente);

        $this->pedidoRepositoryMock->method('atribuirEntregadorPedido')
            ->willReturn(false); 

        $resultado = $this->pedidoController->atribuirEntregadorPedido($dados);
        $this->assertFalse($resultado);
    }

    public function testAtribuirEntregadorPedidoLidaComExcecaoDoRepositorio() {
        $dados = [
            'idPedido' => 1,
            'idEntregador' => 201
        ];
        $this->pedidoRepositoryMock->method('listarPedidoPorId')
            ->willThrowException(new Exception("Erro de banco de dados na busca do pedido"));

        $resultado = $this->pedidoController->atribuirEntregadorPedido($dados);
        $this->assertFalse($resultado);
    }

    ## Testes para mudarStatus

    public function testMudarStatusRetornaTrueQuandoMudancaEhBemSucedida() {
        $dados = [
            'idPedido' => 1,
            'statusPedido' => 'Preparando pedido'
        ];
        $pedidoExistente = new Pedido(1, 101, '2024-06-15 10:00:00', null, 'Delivery', 1, 35.50, null, null, 'Aguardando Confirmação', null, 5.00, 'Cartão de Crédito', 0);

        $this->pedidoRepositoryMock->method('listarPedidoPorId')
            ->with($dados['idPedido'])
            ->willReturn($pedidoExistente);

        $this->pedidoRepositoryMock->method('mudarStatus')
            ->with($dados['idPedido'], $dados['statusPedido'])
            ->willReturn(true);

        $resultado = $this->pedidoController->mudarStatus($dados);
        $this->assertTrue($resultado);
        $this->assertEquals($dados['statusPedido'], $pedidoExistente->getStatusPedido()); 
    }

    public function testMudarStatusRetornaFalseParaDadosInvalidos() {
        $dadosInvalidos = ['statusPedido' => 'Preparando pedido'];
        $this->assertFalse($this->pedidoController->mudarStatus($dadosInvalidos));

        $dadosInvalidos = ['idPedido' => 1];
        $this->assertFalse($this->pedidoController->mudarStatus($dadosInvalidos));
    }

    public function testMudarStatusRetornaFalseQuandoPedidoNaoEncontrado() {
        $dados = [
            'idPedido' => 999,
            'statusPedido' => 'Preparando pedido'
        ];

        $this->pedidoRepositoryMock->method('listarPedidoPorId')
            ->with($dados['idPedido'])
            ->willReturn(false);

        $resultado = $this->pedidoController->mudarStatus($dados);
        $this->assertFalse($resultado);
    }

    public function testMudarStatusRetornaFalseQuandoRepositorioFalha() {
        $dados = [
            'idPedido' => 1,
            'statusPedido' => 'Preparando pedido'
        ];
        $pedidoExistente = new Pedido(1, 101, '2024-06-15 10:00:00', null, 'Delivery', 1, 35.50, null, null, 'Aguardando Confirmação', null, 5.00, 'Cartão de Crédito', 0);

        $this->pedidoRepositoryMock->method('listarPedidoPorId')
            ->with($dados['idPedido'])
            ->willReturn($pedidoExistente);

        $this->pedidoRepositoryMock->method('mudarStatus')
            ->willReturn(false);

        $resultado = $this->pedidoController->mudarStatus($dados);
        $this->assertNull($resultado);
    }

    public function testMudarStatusLidaComExcecaoDoRepositorio() {
        $dados = [
            'idPedido' => 1,
            'statusPedido' => 'Preparando pedido'
        ];
        $this->pedidoRepositoryMock->method('listarPedidoPorId')
            ->willThrowException(new Exception("Erro de banco de dados na busca do pedido"));

        $resultado = $this->pedidoController->mudarStatus($dados);
        $this->assertFalse($resultado);
    }

    ## Testes para cancelarPedido

    public function testCancelarPedidoRetornaTrueQuandoCancelamentoEhBemSucedido() {
        $dados = [
            'idPedido' => 1,
            'motivoCancelamento' => 'Cliente desistiu'
        ];
        $pedidoExistente = new Pedido(1, 101, '2024-06-15 10:00:00', null, 'Delivery', 1, 35.50, null, null, 'Aguardando Confirmação', null, 5.00, 'Cartão de Crédito', 0);

        $this->pedidoRepositoryMock->method('listarPedidoPorId')
            ->with($dados['idPedido'])
            ->willReturn($pedidoExistente);

        $this->pedidoRepositoryMock->method('cancelarPedido')
            ->with($dados['idPedido'], 'Cancelado', $dados['motivoCancelamento'])
            ->willReturn(true);

        $resultado = $this->pedidoController->cancelarPedido($dados);
        $this->assertTrue($resultado);
        $this->assertEquals('Cancelado', $pedidoExistente->getStatusPedido()); 
        $this->assertEquals($dados['motivoCancelamento'], $pedidoExistente->getMotivoCancelamento()); 
    }

    public function testCancelarPedidoRetornaFalseParaDadosInvalidos() {
        $dadosInvalidos = ['motivoCancelamento' => 'Erro na cozinha'];
        $this->assertFalse($this->pedidoController->cancelarPedido($dadosInvalidos));

        $dadosInvalidos = ['idPedido' => 1];
        $this->assertFalse($this->pedidoController->cancelarPedido($dadosInvalidos));
    }

    public function testCancelarPedidoRetornaFalseQuandoPedidoNaoEncontrado() {
        $dados = [
            'idPedido' => 999,
            'motivoCancelamento' => 'Produto fora de estoque'
        ];

        $this->pedidoRepositoryMock->method('listarPedidoPorId')
            ->with($dados['idPedido'])
            ->willReturn(false);

        $resultado = $this->pedidoController->cancelarPedido($dados);
        $this->assertFalse($resultado);
    }

    public function testCancelarPedidoRetornaFalseQuandoRepositorioFalha() {
        $dados = [
            'idPedido' => 1,
            'motivoCancelamento' => 'Problema interno'
        ];
        $pedidoExistente = new Pedido(1, 101, '2024-06-15 10:00:00', null, 'Delivery', 1, 35.50, null, null, 'Aguardando Confirmação', null, 5.00, 'Cartão de Crédito', 0);

        $this->pedidoRepositoryMock->method('listarPedidoPorId')
            ->with($dados['idPedido'])
            ->willReturn($pedidoExistente);

        $this->pedidoRepositoryMock->method('cancelarPedido')
            ->willReturn(false);

        $resultado = $this->pedidoController->cancelarPedido($dados);
        $this->assertNull($resultado);
    }

    public function testCancelarPedidoLidaComExcecaoDoRepositorio() {
        $dados = [
            'idPedido' => 1,
            'motivoCancelamento' => 'Exceção de teste'
        ];
        $this->pedidoRepositoryMock->method('listarPedidoPorId')
            ->willThrowException(new Exception("Erro de banco de dados no cancelamento"));

        $resultado = $this->pedidoController->cancelarPedido($dados);
        $this->assertFalse($resultado);
    }
}