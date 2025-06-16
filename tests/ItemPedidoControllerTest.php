<?php

use PHPUnit\Framework\TestCase;
use app\controller\ItemPedidoController;
use app\repository\ItemPedidoRepository;
use app\model\ItemPedido;

class ItemPedidoControllerTest extends TestCase {
    private ItemPedidoController $itemPedidoController;
    /** @var PHPUnit\Framework\MockObject\MockObject|ItemPedidoRepository */
    private $itemPedidoRepository;

    protected function setUp(): void {
        $this->itemPedidoRepository = $this->createMock(ItemPedidoRepository::class);

        $this->itemPedidoController = new ItemPedidoController($this->itemPedidoRepository);
    }

    // --- Testes para criarPedido ---

    public function testCriarPedidoComDadosValidosRetornaTrue() {
        $dados = [
            'idPedido' => 1,
            'idProduto' => 101,
            'quantidade' => 2
        ];

        $this->itemPedidoRepository->expects($this->once())
            ->method('criarPedido')
            ->with(
                $dados['idPedido'],
                $dados['idProduto'],
                $dados['quantidade']
            )
            ->willReturn(true);

        $resultado = $this->itemPedidoController->criarPedido($dados);
        $this->assertTrue($resultado);
    }

    public function testCriarPedidoComIdPedidoAusenteRetornaFalse() {
        $dados = [
            'idPedido' => null,
            'idProduto' => 101,
            'quantidade' => 2
        ];

        $this->itemPedidoRepository->expects($this->never())->method('criarPedido');

        $resultado = $this->itemPedidoController->criarPedido($dados);
        $this->assertFalse($resultado);
    }

    public function testCriarPedidoComIdProdutoAusenteRetornaFalse() {
        $dados = [
            'idPedido' => 1,
            'idProduto' => null,
            'quantidade' => 2
        ];

        $this->itemPedidoRepository->expects($this->never())->method('criarPedido');

        $resultado = $this->itemPedidoController->criarPedido($dados);
        $this->assertFalse($resultado);
    }

    public function testCriarPedidoComQuantidadeAusenteRetornaFalse() {
        $dados = [
            'idPedido' => 1,
            'idProduto' => 101,
            'quantidade' => null
        ];

        $this->itemPedidoRepository->expects($this->never())->method('criarPedido');

        $resultado = $this->itemPedidoController->criarPedido($dados);
        $this->assertFalse($resultado);
    }

    public function testCriarPedidoQuandoRepositorioFalhaRetornaFalse() {
        $dados = [
            'idPedido' => 1,
            'idProduto' => 101,
            'quantidade' => 2
        ];

        $this->itemPedidoRepository->expects($this->once())
            ->method('criarPedido')
            ->willReturn(false); 

        $resultado = $this->itemPedidoController->criarPedido($dados);
        $this->assertFalse($resultado);
    }

    public function testCriarPedidoLancaExcecaoRetornaFalse() {
        $dados = [
            'idPedido' => 1,
            'idProduto' => 101,
            'quantidade' => 2
        ];

        $this->itemPedidoRepository->expects($this->once())
            ->method('criarPedido')
            ->willThrowException(new Exception("Erro de banco de dados"));

        $resultado = $this->itemPedidoController->criarPedido($dados);
        $this->assertFalse($resultado);
    }

    // --- Testes para listarInformacoesPedido ---

    public function testListarInformacoesPedidoComIdValidoRetornaArrayDeDados() {
        $idPedido = 1;
        $dadosRetornados = [
            ['idItemPedido' => 10, 'idPedido' => 1, 'idProduto' => 101, 'quantidade' => 2, 'nomeProduto' => 'Item A'],
            ['idItemPedido' => 11, 'idPedido' => 1, 'idProduto' => 102, 'quantidade' => 1, 'nomeProduto' => 'Item B']
        ];

        $this->itemPedidoRepository->expects($this->once())
            ->method('listarInformacoesPedido')
            ->with($idPedido)
            ->willReturn($dadosRetornados);

        $resultado = $this->itemPedidoController->listarInformacoesPedido($idPedido);
        $this->assertEquals($dadosRetornados, $resultado);
    }

    public function testListarInformacoesPedidoComIdNuloRetornaFalse() {
        $idPedido = null;

        $this->itemPedidoRepository->expects($this->never())->method('listarInformacoesPedido');

        $resultado = $this->itemPedidoController->listarInformacoesPedido($idPedido);
        $this->assertFalse($resultado); 
    }

    public function testListarInformacoesPedidoComIdVazioRetornaNull() {
        $idPedido = '';

        $this->itemPedidoRepository->expects($this->never())->method('listarInformacoesPedido');

        $resultado = $this->itemPedidoController->listarInformacoesPedido($idPedido);
        $this->assertFalse($resultado);
    }

    public function testListarInformacoesPedidoComIdNaoNumericoRetornaFalse() {
        $idPedido = 'abc';

        $this->itemPedidoRepository->expects($this->never())->method('listarInformacoesPedido');

        $resultado = $this->itemPedidoController->listarInformacoesPedido($idPedido);
        $this->assertFalse($resultado);
    }

    public function testListarInformacoesPedidoQuandoNaoEncontradoRetornaFalse() {
        $idPedido = 99; 

        $this->itemPedidoRepository->expects($this->once())
            ->method('listarInformacoesPedido')
            ->with($idPedido)
            ->willReturn([]);

        $resultado = $this->itemPedidoController->listarInformacoesPedido($idPedido);
        $this->assertFalse($resultado);
    }

    public function testListarInformacoesPedidoLancaExcecaoRetornaFalse() {
        $idPedido = 1;

        $this->itemPedidoRepository->expects($this->once())
            ->method('listarInformacoesPedido')
            ->willThrowException(new Exception("Erro de conexão com o DB"));

        $resultado = $this->itemPedidoController->listarInformacoesPedido($idPedido);
        $this->assertFalse($resultado);
    }
}