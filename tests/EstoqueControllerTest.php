<?php

use PHPUnit\Framework\TestCase;
use app\controller\EstoqueController;
use app\model\Estoque;
use app\repository\EstoqueRepository;

require_once __DIR__ . '/../app/utils/helpers/Logger.php';

class EstoqueControllerTest extends TestCase {
    private EstoqueController $estoqueController;
    /** @var PHPUnit\Framework\MockObject\MockObject|EstoqueRepository */
    private $estoqueRepository;

    protected function setUp(): void {
        $this->estoqueRepository = $this->createMock(EstoqueRepository::class);

        $this->estoqueController = new EstoqueController();

        $reflection = new ReflectionClass(EstoqueController::class);
        $property = $reflection->getProperty('repository');
        $property->setAccessible(true);
        $property->setValue($this->estoqueController, $this->estoqueRepository);
    }

    // --- Testes para criarProdutoEstoque ---

    public function testCriarProdutoEstoqueComDadosValidosRetornaId() {
        $dados = [
            'idCategoria' => 1, 'idProduto' => 10, 'lote' => 'ABC123',
            'dtEntrada' => '2023-01-01', 'dtFabricacao' => '2022-12-01', 'dtVencimento' => '2024-12-31',
            'qtdMinima' => 5, 'quantidade' => 100, 'precoCompra' => 50.00
        ];

        $resultado = $this->estoqueRepository->expects($this->once())
            ->method('criarProdutoEstoque')
            ->with(
                $dados['idCategoria'], $dados['idProduto'], $dados['lote'],
                $dados['dtEntrada'], $dados['dtFabricacao'], $dados['dtVencimento'],
                $dados['qtdMinima'], $dados['quantidade'], $dados['precoCompra']
            )
            ->willReturn(1);

        $resultado = $this->estoqueController->criarProdutoEstoque($dados);
        $this->assertEquals(1, $resultado); 
    }

    public function testCriarProdutoEstoqueComIdCategoriaAusenteRetornaFalse() {
        $dados = [
            'idCategoria' => null, 'idProduto' => 10, 'lote' => 'ABC123',
            'dtEntrada' => '2023-01-01', 'dtFabricacao' => '2022-12-01', 'dtVencimento' => '2024-12-31',
            'qtdMinima' => 5, 'quantidade' => 100, 'precoCompra' => 50.00
        ];
        $this->estoqueRepository->expects($this->never())->method('criarProdutoEstoque');
        $this->assertFalse($this->estoqueController->criarProdutoEstoque($dados));
    }

    public function testCriarProdutoEstoqueComIdProdutoAusenteRetornaFalse() {
        $dados = [
            'idCategoria' => 1, 'idProduto' => null, 'lote' => 'ABC123',
            'dtEntrada' => '2023-01-01', 'dtFabricacao' => '2022-12-01', 'dtVencimento' => '2024-12-31',
            'qtdMinima' => 5, 'quantidade' => 100, 'precoCompra' => 50.00
        ];
        $this->estoqueRepository->expects($this->never())->method('criarProdutoEstoque');
        $this->assertFalse($this->estoqueController->criarProdutoEstoque($dados));
    }

    public function testCriarProdutoEstoqueComLoteVazioRetornaFalse() {
        $dados = [
            'idCategoria' => 1, 'idProduto' => 10, 'lote' => '',
            'dtEntrada' => '2023-01-01', 'dtFabricacao' => '2022-12-01', 'dtVencimento' => '2024-12-31',
            'qtdMinima' => 5, 'quantidade' => 100, 'precoCompra' => 50.00
        ];
        $this->estoqueRepository->expects($this->never())->method('criarProdutoEstoque');
        $this->assertFalse($this->estoqueController->criarProdutoEstoque($dados));
    }

    public function testCriarProdutoEstoqueComDtEntradaVaziaRetornaFalse() {
        $dados = [
            'idCategoria' => 1, 'idProduto' => 10, 'lote' => 'ABC123',
            'dtEntrada' => '', 'dtFabricacao' => '2022-12-01', 'dtVencimento' => '2024-12-31',
            'qtdMinima' => 5, 'quantidade' => 100, 'precoCompra' => 50.00
        ];
        $this->estoqueRepository->expects($this->never())->method('criarProdutoEstoque');
        $this->assertFalse($this->estoqueController->criarProdutoEstoque($dados));
    }

    public function testCriarProdutoEstoqueComDtFabricacaoVaziaRetornaFalse() {
        $dados = [
            'idCategoria' => 1, 'idProduto' => 10, 'lote' => 'ABC123',
            'dtEntrada' => '2023-01-01', 'dtFabricacao' => '', 'dtVencimento' => '2024-12-31',
            'qtdMinima' => 5, 'quantidade' => 100, 'precoCompra' => 50.00
        ];
        $this->estoqueRepository->expects($this->never())->method('criarProdutoEstoque');
        $this->assertFalse($this->estoqueController->criarProdutoEstoque($dados));
    }

    public function testCriarProdutoEstoqueComDtVencimentoVaziaRetornaFalse() {
        $dados = [
            'idCategoria' => 1, 'idProduto' => 10, 'lote' => 'ABC123',
            'dtEntrada' => '2023-01-01', 'dtFabricacao' => '2022-12-01', 'dtVencimento' => '',
            'qtdMinima' => 5, 'quantidade' => 100, 'precoCompra' => 50.00
        ];
        $this->estoqueRepository->expects($this->never())->method('criarProdutoEstoque');
        $this->assertFalse($this->estoqueController->criarProdutoEstoque($dados));
    }

    public function testCriarProdutoEstoqueComQtdMinimaAusenteRetornaFalse() {
        $dados = [
            'idCategoria' => 1, 'idProduto' => 10, 'lote' => 'ABC123',
            'dtEntrada' => '2023-01-01', 'dtFabricacao' => '2022-12-01', 'dtVencimento' => '2024-12-31',
            'qtdMinima' => null, 'quantidade' => 100, 'precoCompra' => 50.00
        ];
        $this->estoqueRepository->expects($this->never())->method('criarProdutoEstoque');
        $this->assertFalse($this->estoqueController->criarProdutoEstoque($dados));
    }

    public function testCriarProdutoEstoqueComQuantidadeAusenteRetornaFalse() {
        $dados = [
            'idCategoria' => 1, 'idProduto' => 10, 'lote' => 'ABC123',
            'dtEntrada' => '2023-01-01', 'dtFabricacao' => '2022-12-01', 'dtVencimento' => '2024-12-31',
            'qtdMinima' => 5, 'quantidade' => null, 'precoCompra' => 50.00
        ];
        $this->estoqueRepository->expects($this->never())->method('criarProdutoEstoque');
        $this->assertFalse($this->estoqueController->criarProdutoEstoque($dados));
    }

    public function testCriarProdutoEstoqueComPrecoCompraAusenteRetornaFalse() {
        $dados = [
            'idCategoria' => 1, 'idProduto' => 10, 'lote' => 'ABC123',
            'dtEntrada' => '2023-01-01', 'dtFabricacao' => '2022-12-01', 'dtVencimento' => '2024-12-31',
            'qtdMinima' => 5, 'quantidade' => 100, 'precoCompra' => null
        ];
        $this->estoqueRepository->expects($this->never())->method('criarProdutoEstoque');
        $this->assertFalse($this->estoqueController->criarProdutoEstoque($dados));
    }

    public function testCriarProdutoEstoqueQuandoRepositorioFalhaRetornaNull() {
        $dados = [
            'idCategoria' => 1, 'idProduto' => 10, 'lote' => 'ABC123',
            'dtEntrada' => '2023-01-01', 'dtFabricacao' => '2022-12-01', 'dtVencimento' => '2024-12-31',
            'qtdMinima' => 5, 'quantidade' => 100, 'precoCompra' => 50.00
        ];

        $this->estoqueRepository->expects($this->once())
            ->method('criarProdutoEstoque')
            ->willReturn(0);

        $resultado = $this->estoqueController->criarProdutoEstoque($dados);
        $this->assertNull($resultado);
    }

    // --- Testes para listarEstoque ---

    public function testListarEstoqueRetornaApenasProdutosNaoVencidos() {
        $produtoVencido = $this->createMock(Estoque::class);
        $produtoVencido->method('getDtVencimento')->willReturn('2020-01-01');
        $produtoVencido->method('getIdEstoque')->willReturn(1);

        $produtoValido = $this->createMock(Estoque::class);
        $produtoValido->method('getDtVencimento')->willReturn('2025-12-31'); 
        $produtoValido->method('getIdEstoque')->willReturn(2);

        $this->estoqueRepository->expects($this->once())
            ->method('listarEstoque')
            ->willReturn([$produtoVencido, $produtoValido]);

        $this->estoqueRepository->expects($this->once())
            ->method('decrementarProduto')
            ->with(0, 1); // Quantidade 0, ID 1

        $estoqueRetorno = $this->estoqueController->listarEstoque();

        $this->assertIsArray($estoqueRetorno);
        $this->assertCount(1, $estoqueRetorno); 
        $this->assertSame($produtoValido, $estoqueRetorno[0]);
    }

    public function testListarEstoqueComTodosProdutosValidosRetornaTodos() {
        $produtoValido1 = $this->createMock(Estoque::class);
        $produtoValido1->method('getDtVencimento')->willReturn('2025-12-31');
        $produtoValido1->method('getIdEstoque')->willReturn(1);

        $produtoValido2 = $this->createMock(Estoque::class);
        $produtoValido2->method('getDtVencimento')->willReturn('2026-01-15');
        $produtoValido2->method('getIdEstoque')->willReturn(2);

        $this->estoqueRepository->expects($this->once())
            ->method('listarEstoque')
            ->willReturn([$produtoValido1, $produtoValido2]);

        $this->estoqueRepository->expects($this->never())->method('decrementarProduto');

        $estoqueRetorno = $this->estoqueController->listarEstoque();

        $this->assertIsArray($estoqueRetorno);
        $this->assertCount(2, $estoqueRetorno);
        $this->assertSame($produtoValido1, $estoqueRetorno[0]);
        $this->assertSame($produtoValido2, $estoqueRetorno[1]);
    }

    public function testListarEstoqueComEstoqueVazioRetornaArrayVazio() {
        $this->estoqueRepository->expects($this->once())
            ->method('listarEstoque')
            ->willReturn([]);

        $estoqueRetorno = $this->estoqueController->listarEstoque();
        $this->assertIsArray($estoqueRetorno);
        $this->assertEmpty($estoqueRetorno);
    }

    // --- Testes para selecionarProdutoEstoquePorID ---

    public function testSelecionarProdutoEstoquePorIDComIdValidoRetornaProduto() {
        $idEstoque = 1;
        $produtoMock = $this->createMock(Estoque::class);
        $produtoMock->method('getIdEstoque')->willReturn($idEstoque);

        $this->estoqueRepository->expects($this->once())
            ->method('selecionarProdutoEstoquePorID')
            ->with($idEstoque)
            ->willReturn($produtoMock);

        $resultado = $this->estoqueController->selecionarProdutoEstoquePorID($idEstoque);
        $this->assertSame($produtoMock, $resultado);
    }

    public function testSelecionarProdutoEstoquePorIDComIdNuloRetornaNull() {
        $id = null;
        $this->estoqueRepository->expects($this->never())->method('selecionarProdutoEstoquePorID');
        $this->assertNull($this->estoqueController->selecionarProdutoEstoquePorID($id));
    }

    public function testSelecionarProdutoEstoquePorIDComIdVazioRetornaNull() {
        $id = '';
        $this->estoqueRepository->expects($this->never())->method('selecionarProdutoEstoquePorID');
        $this->assertNull($this->estoqueController->selecionarProdutoEstoquePorID($id));
    }

    public function testSelecionarProdutoEstoquePorIDComIdNaoNumericoRetornaNull() {
        $id = 'abc';
        $this->estoqueRepository->expects($this->never())->method('selecionarProdutoEstoquePorID');
        $this->assertNull($this->estoqueController->selecionarProdutoEstoquePorID($id));
    }

    public function testSelecionarProdutoEstoquePorIDQuandoNaoEncontradoRetornaNull() {
        $idEstoque = 99;
        $this->estoqueRepository->expects($this->once())
            ->method('selecionarProdutoEstoquePorID')
            ->with($idEstoque)
            ->willReturn(null);

        $resultado = $this->estoqueController->selecionarProdutoEstoquePorID($idEstoque);
        $this->assertNull($resultado);
    }

    public function testSelecionarProdutoEstoquePorIDLancaExcecaoRetornaNull() {
        $idEstoque = 1;
        $this->estoqueRepository->expects($this->once())
            ->method('selecionarProdutoEstoquePorID')
            ->willThrowException(new Exception("Erro de rede"));

        $resultado = $this->estoqueController->selecionarProdutoEstoquePorID($idEstoque);
        $this->assertNull($resultado);
    }

    // --- Testes para editarProdutoEstoque ---

    public function testEditarProdutoEstoqueComDadosValidosRetornaTrue() {
        $dados = [
            'idEstoque' => 1, 'ocorrencia' => 'ajuste', 'qtdOcorrencia' => 2,
            'lote' => 'LOTE-EDITADO', 'dtEntrada' => '2023-02-01', 'dtFabricacao' => '2022-12-15',
            'dtVencimento' => '2025-01-31', 'qtdMinima' => 10, 'quantidade' => 150,
            'precoCompra' => 60.00
        ];

        $produtoMock = $this->createMock(Estoque::class);
        $produtoMock->expects($this->once())
                    ->method('editarProdutoEstoque')
                    ->with(
                        $dados['lote'], $dados['dtEntrada'], $dados['quantidade'],
                        $dados['dtFabricacao'], $dados['dtVencimento'], $dados['precoCompra'],
                        $dados['qtdMinima'], $dados['qtdOcorrencia'], $dados['ocorrencia']
                    );

        $this->estoqueRepository->expects($this->once())
            ->method('selecionarProdutoEstoquePorID')
            ->with($dados['idEstoque'])
            ->willReturn($produtoMock); 

        $this->estoqueRepository->expects($this->once())
            ->method('editarProdutoEstoque')
            ->with(
                $dados['idEstoque'], $dados['lote'], $dados['dtEntrada'],
                $dados['quantidade'], $dados['dtFabricacao'], $dados['dtVencimento'],
                $dados['precoCompra'], $dados['qtdMinima'], $dados['qtdOcorrencia'],
                $dados['ocorrencia']
            )
            ->willReturn(true);

        $resultado = $this->estoqueController->editarProdutoEstoque($dados);
        $this->assertTrue($resultado);
    }

    public function testEditarProdutoEstoqueComOcorrenciaAusenteRetornaFalse() {
        $dados = [
            'idEstoque' => 1, 'ocorrencia' => '', 'qtdOcorrencia' => 2,
            'lote' => 'LOTE-EDITADO', 'dtEntrada' => '2023-02-01', 'dtFabricacao' => '2022-12-15',
            'dtVencimento' => '2025-01-31', 'qtdMinima' => 10, 'quantidade' => 150,
            'precoCompra' => 60.00
        ];
        $this->estoqueRepository->expects($this->never())->method('selecionarProdutoEstoquePorID');
        $this->assertFalse($this->estoqueController->editarProdutoEstoque($dados));
    }

    public function testEditarProdutoEstoqueComQtdOcorrenciaAusenteRetornaFalse() {
        $dados = [
            'idEstoque' => 1, 'ocorrencia' => 'ajuste', 'qtdOcorrencia' => null,
            'lote' => 'LOTE-EDITADO', 'dtEntrada' => '2023-02-01', 'dtFabricacao' => '2022-12-15',
            'dtVencimento' => '2025-01-31', 'qtdMinima' => 10, 'quantidade' => 150,
            'precoCompra' => 60.00
        ];
        $this->estoqueRepository->expects($this->never())->method('selecionarProdutoEstoquePorID');
        $this->assertFalse($this->estoqueController->editarProdutoEstoque($dados));
    }

    public function testEditarProdutoEstoqueComLoteAusenteRetornaFalse() {
        $dados = [
            'idEstoque' => 1, 'ocorrencia' => 'ajuste', 'qtdOcorrencia' => 2,
            'lote' => '', 'dtEntrada' => '2023-02-01', 'dtFabricacao' => '2022-12-15',
            'dtVencimento' => '2025-01-31', 'qtdMinima' => 10, 'quantidade' => 150,
            'precoCompra' => 60.00
        ];
        $this->estoqueRepository->expects($this->never())->method('selecionarProdutoEstoquePorID');
        $this->assertFalse($this->estoqueController->editarProdutoEstoque($dados));
    }

    public function testEditarProdutoEstoqueComDtEntradaAusenteRetornaFalse() {
        $dados = [
            'idEstoque' => 1, 'ocorrencia' => 'ajuste', 'qtdOcorrencia' => 2,
            'lote' => 'LOTE-EDITADO', 'dtEntrada' => '', 'dtFabricacao' => '2022-12-15',
            'dtVencimento' => '2025-01-31', 'qtdMinima' => 10, 'quantidade' => 150,
            'precoCompra' => 60.00
        ];
        $this->estoqueRepository->expects($this->never())->method('selecionarProdutoEstoquePorID');
        $this->assertFalse($this->estoqueController->editarProdutoEstoque($dados));
    }

    public function testEditarProdutoEstoqueComDtFabricacaoAusenteRetornaFalse() {
        $dados = [
            'idEstoque' => 1, 'ocorrencia' => 'ajuste', 'qtdOcorrencia' => 2,
            'lote' => 'LOTE-EDITADO', 'dtEntrada' => '2023-02-01', 'dtFabricacao' => '',
            'dtVencimento' => '2025-01-31', 'qtdMinima' => 10, 'quantidade' => 150,
            'precoCompra' => 60.00
        ];
        $this->estoqueRepository->expects($this->never())->method('selecionarProdutoEstoquePorID');
        $this->assertFalse($this->estoqueController->editarProdutoEstoque($dados));
    }

    public function testEditarProdutoEstoqueComDtVencimentoAusenteRetornaFalse() {
        $dados = [
            'idEstoque' => 1, 'ocorrencia' => 'ajuste', 'qtdOcorrencia' => 2,
            'lote' => 'LOTE-EDITADO', 'dtEntrada' => '2023-02-01', 'dtFabricacao' => '2022-12-15',
            'dtVencimento' => '', 'qtdMinima' => 10, 'quantidade' => 150,
            'precoCompra' => 60.00
        ];
        $this->estoqueRepository->expects($this->never())->method('selecionarProdutoEstoquePorID');
        $this->assertFalse($this->estoqueController->editarProdutoEstoque($dados));
    }

    public function testEditarProdutoEstoqueComQtdMinimaAusenteRetornaFalse() {
        $dados = [
            'idEstoque' => 1, 'ocorrencia' => 'ajuste', 'qtdOcorrencia' => 2,
            'lote' => 'LOTE-EDITADO', 'dtEntrada' => '2023-02-01', 'dtFabricacao' => '2022-12-15',
            'dtVencimento' => '2025-01-31', 'qtdMinima' => null, 'quantidade' => 150,
            'precoCompra' => 60.00
        ];
        $this->estoqueRepository->expects($this->never())->method('selecionarProdutoEstoquePorID');
        $this->assertFalse($this->estoqueController->editarProdutoEstoque($dados));
    }

    public function testEditarProdutoEstoqueComQuantidadeAusenteRetornaFalse() {
        $dados = [
            'idEstoque' => 1, 'ocorrencia' => 'ajuste', 'qtdOcorrencia' => 2,
            'lote' => 'LOTE-EDITADO', 'dtEntrada' => '2023-02-01', 'dtFabricacao' => '2022-12-15',
            'dtVencimento' => '2025-01-31', 'qtdMinima' => 10, 'quantidade' => null,
            'precoCompra' => 60.00
        ];
        $this->estoqueRepository->expects($this->never())->method('selecionarProdutoEstoquePorID');
        $this->assertFalse($this->estoqueController->editarProdutoEstoque($dados));
    }

    public function testEditarProdutoEstoqueComPrecoCompraAusenteRetornaFalse() {
        $dados = [
            'idEstoque' => 1, 'ocorrencia' => 'ajuste', 'qtdOcorrencia' => 2,
            'lote' => 'LOTE-EDITADO', 'dtEntrada' => '2023-02-01', 'dtFabricacao' => '2022-12-15',
            'dtVencimento' => '2025-01-31', 'qtdMinima' => 10, 'quantidade' => 150,
            'precoCompra' => null
        ];
        $this->estoqueRepository->expects($this->never())->method('selecionarProdutoEstoquePorID');
        $this->assertFalse($this->estoqueController->editarProdutoEstoque($dados));
    }

    public function testEditarProdutoEstoqueComIdEstoqueAusenteRetornaFalse() {
        $dados = [
            'idEstoque' => null, 'ocorrencia' => 'ajuste', 'qtdOcorrencia' => 2,
            'lote' => 'LOTE-EDITADO', 'dtEntrada' => '2023-02-01', 'dtFabricacao' => '2022-12-15',
            'dtVencimento' => '2025-01-31', 'qtdMinima' => 10, 'quantidade' => 150,
            'precoCompra' => 60.00
        ];
        $this->estoqueRepository->expects($this->never())->method('selecionarProdutoEstoquePorID');
        $this->assertFalse($this->estoqueController->editarProdutoEstoque($dados));
    }


    public function testEditarProdutoEstoqueQuandoProdutoNaoEncontradoRetornaNull() {
        $dados = [
            'idEstoque' => 99, 'ocorrencia' => 'ajuste', 'qtdOcorrencia' => 2,
            'lote' => 'LOTE-EDITADO', 'dtEntrada' => '2023-02-01', 'dtFabricacao' => '2022-12-15',
            'dtVencimento' => '2025-01-31', 'qtdMinima' => 10, 'quantidade' => 150,
            'precoCompra' => 60.00
        ];

        $this->estoqueRepository->expects($this->once())
            ->method('selecionarProdutoEstoquePorID')
            ->with($dados['idEstoque'])
            ->willReturn(null); 

        $this->estoqueRepository->expects($this->never())->method('editarProdutoEstoque');

        $resultado = $this->estoqueController->editarProdutoEstoque($dados);
        $this->assertNull($resultado);
    }

    public function testEditarProdutoEstoqueQuandoRepositorioFalhaRetornaNull() {
        $dados = [
            'idEstoque' => 1, 'ocorrencia' => 'ajuste', 'qtdOcorrencia' => 2,
            'lote' => 'LOTE-EDITADO', 'dtEntrada' => '2023-02-01', 'dtFabricacao' => '2022-12-15',
            'dtVencimento' => '2025-01-31', 'qtdMinima' => 10, 'quantidade' => 150,
            'precoCompra' => 60.00
        ];

        $produtoMock = $this->createMock(Estoque::class);
        $this->estoqueRepository->expects($this->once())
            ->method('selecionarProdutoEstoquePorID')
            ->willReturn($produtoMock);

        $this->estoqueRepository->expects($this->once())
            ->method('editarProdutoEstoque')
            ->willReturn(false);

        $resultado = $this->estoqueController->editarProdutoEstoque($dados);
        $this->assertNull($resultado);
    }

    public function testEditarProdutoEstoqueLancaExcecaoRetornaNull() {
        $dados = [
            'idEstoque' => 1, 'ocorrencia' => 'ajuste', 'qtdOcorrencia' => 2,
            'lote' => 'LOTE-EDITADO', 'dtEntrada' => '2023-02-01', 'dtFabricacao' => '2022-12-15',
            'dtVencimento' => '2025-01-31', 'qtdMinima' => 10, 'quantidade' => 150,
            'precoCompra' => 60.00
        ];

        $this->estoqueRepository->expects($this->once())
            ->method('selecionarProdutoEstoquePorID')
            ->willThrowException(new Exception("Erro de rede"));

        $resultado = $this->estoqueController->editarProdutoEstoque($dados);
        $this->assertNull($resultado);
    }

    // --- Testes para desativarProdutoEstoque ---

    public function testDesativarProdutoEstoqueComIdValidoRetornaTrue() {
        $idEstoque = 1;
        $produtoMock = $this->createMock(Estoque::class);
        $produtoMock->expects($this->once())->method('setDesativado')->with(1); 

        $this->estoqueRepository->expects($this->once())
            ->method('selecionarProdutoEstoquePorID')
            ->with($idEstoque)
            ->willReturn($produtoMock);

        $this->estoqueRepository->expects($this->once())
            ->method('desativarProdutoEstoque')
            ->with($idEstoque)
            ->willReturn(true);

        $resultado = $this->estoqueController->desativarProdutoEstoque($idEstoque);
        $this->assertTrue($resultado);
    }

    public function testDesativarProdutoEstoqueComIdNuloRetornaNull() {
        $id = null;
        $this->estoqueRepository->expects($this->never())->method('selecionarProdutoEstoquePorID');
        $this->assertNull($this->estoqueController->desativarProdutoEstoque($id));
    }

    public function testDesativarProdutoEstoqueComIdVazioRetornaNull() {
        $id = '';
        $this->estoqueRepository->expects($this->never())->method('selecionarProdutoEstoquePorID');
        $this->assertNull($this->estoqueController->desativarProdutoEstoque($id));
    }

    public function testDesativarProdutoEstoqueComIdNaoNumericoRetornaNull() {
        $id = 'abc';
        $this->estoqueRepository->expects($this->never())->method('selecionarProdutoEstoquePorID');
        $this->assertNull($this->estoqueController->desativarProdutoEstoque($id));
    }

    public function testDesativarProdutoEstoqueQuandoProdutoNaoEncontradoRetornaNull() {
        $idEstoque = 99;
        $this->estoqueRepository->expects($this->once())
            ->method('selecionarProdutoEstoquePorID')
            ->with($idEstoque)
            ->willReturn(null);

        $this->estoqueRepository->expects($this->never())->method('desativarProdutoEstoque');

        $resultado = $this->estoqueController->desativarProdutoEstoque($idEstoque);
        $this->assertNull($resultado);
    }

    public function testDesativarProdutoEstoqueQuandoRepositorioFalhaRetornaNull() {
        $idEstoque = 1;
        $produtoMock = $this->createMock(Estoque::class);
        $produtoMock->method('setDesativado');

        $this->estoqueRepository->expects($this->once())
            ->method('selecionarProdutoEstoquePorID')
            ->willReturn($produtoMock);

        $this->estoqueRepository->expects($this->once())
            ->method('desativarProdutoEstoque')
            ->willReturn(false);

        $resultado = $this->estoqueController->desativarProdutoEstoque($idEstoque);
        $this->assertNull($resultado);
    }

    public function testDesativarProdutoEstoqueLancaExcecaoRetornaNull() {
        $idEstoque = 1;
        $this->estoqueRepository->expects($this->once())
            ->method('selecionarProdutoEstoquePorID')
            ->willThrowException(new Exception("Erro de segurança"));

        $resultado = $this->estoqueController->desativarProdutoEstoque($idEstoque);
        $this->assertNull($resultado);
    }

    // --- Testes para decrementarQuantidade ---

    public function testDecrementarQuantidadeComDadosValidosRetornaTrue() {
        $dados = ['idProduto' => 1, 'quantidade' => 5];
        $produtoMock = $this->createMock(Estoque::class);
        $produtoMock->method('getQuantidade')->willReturn(10); 
        $produtoMock->method('getDtVencimento')->willReturn('2025-12-31'); 
        $produtoMock->method('getIdEstoque')->willReturn(1);

        $this->estoqueRepository->expects($this->once())
            ->method('selecionarProdutoEstoquePorID')
            ->with($dados['idProduto'])
            ->willReturn($produtoMock);

        $this->estoqueRepository->expects($this->once())
            ->method('decrementarProduto')
            ->with(5, $dados['idProduto'])
            ->willReturn(true);

        $resultado = $this->estoqueController->decrementarQuantidade($dados);
        $this->assertTrue($resultado);
    }

    public function testDecrementarQuantidadeComIdProdutoAusenteRetornaFalse() {
        $dados = ['quantidade' => 5];
        $this->estoqueRepository->expects($this->never())->method('selecionarProdutoEstoquePorID');
        $this->assertFalse($this->estoqueController->decrementarQuantidade($dados));
    }

    public function testDecrementarQuantidadeComQuantidadeAusenteRetornaFalse() {
        $dados = ['idProduto' => 1];
        $this->estoqueRepository->expects($this->never())->method('selecionarProdutoEstoquePorID');
        $this->assertFalse($this->estoqueController->decrementarQuantidade($dados));
    }

    public function testDecrementarQuantidadeQuandoProdutoNaoEncontradoRetornaFalse() {
        $dados = ['idProduto' => 99, 'quantidade' => 5];
        $this->estoqueRepository->expects($this->once())
            ->method('selecionarProdutoEstoquePorID')
            ->with($dados['idProduto'])
            ->willReturn(null);

        $this->estoqueRepository->expects($this->never())->method('decrementarProduto');

        $resultado = $this->estoqueController->decrementarQuantidade($dados);
        $this->assertFalse($resultado);
    }

    public function testDecrementarQuantidadeDeProdutoVencidoRetornaFalse() {
        $dados = ['idProduto' => 1, 'quantidade' => 5];
        $produtoMock = $this->createMock(Estoque::class);
        $produtoMock->method('getQuantidade')->willReturn(10);
        $produtoMock->method('getDtVencimento')->willReturn('2020-01-01'); 
        $produtoMock->method('getIdEstoque')->willReturn(1);

        $this->estoqueRepository->expects($this->once())
            ->method('selecionarProdutoEstoquePorID')
            ->with($dados['idProduto'])
            ->willReturn($produtoMock);

        $this->estoqueRepository->expects($this->once())
            ->method('decrementarProduto')
            ->with(0, $dados['idProduto'])
            ->willReturn(true);

        $resultado = $this->estoqueController->decrementarQuantidade($dados);
        $this->assertFalse($resultado);
    }

    public function testDecrementarQuantidadeInsuficienteRetornaFalse() {
        $dados = ['idProduto' => 1, 'quantidade' => 15]; 
        $produtoMock = $this->createMock(Estoque::class);
        $produtoMock->method('getQuantidade')->willReturn(10); 
        $produtoMock->method('getDtVencimento')->willReturn('2025-12-31');

        $this->estoqueRepository->expects($this->once())
            ->method('selecionarProdutoEstoquePorID')
            ->with($dados['idProduto'])
            ->willReturn($produtoMock);

        $this->estoqueRepository->expects($this->never())->method('decrementarProduto'); 

        $resultado = $this->estoqueController->decrementarQuantidade($dados);
        $this->assertFalse($resultado);
    }

    public function testDecrementarQuantidadeQuandoRepositorioFalhaRetornaFalse() {
        $dados = ['idProduto' => 1, 'quantidade' => 5];
        $produtoMock = $this->createMock(Estoque::class);
        $produtoMock->method('getQuantidade')->willReturn(10);
        $produtoMock->method('getDtVencimento')->willReturn('2025-12-31');

        $this->estoqueRepository->expects($this->once())
            ->method('selecionarProdutoEstoquePorID')
            ->willReturn($produtoMock);

        $this->estoqueRepository->expects($this->once())
            ->method('decrementarProduto')
            ->willReturn(false); 
                        
        $resultado = $this->estoqueController->decrementarQuantidade($dados);
        $this->assertFalse($resultado);
    }

    public function testDecrementarQuantidadeLancaExcecaoRetornaFalse() {
        $dados = ['idProduto' => 1, 'quantidade' => 5];
        $this->estoqueRepository->expects($this->once())
            ->method('selecionarProdutoEstoquePorID')
            ->willThrowException(new Exception("Erro de comunicação"));

        $resultado = $this->estoqueController->decrementarQuantidade($dados);
        $this->assertFalse($resultado);
    }

    // --- Testes para verificarQuantidadeMinima ---

    public function testVerificarQuantidadeMinimaRetornaDadosDoRepositorio() {
        $dadosMinima = [
            ['idProduto' => 1, 'nome' => 'Produto X', 'quantidadeAtual' => 3, 'qtdMinima' => 5],
            ['idProduto' => 2, 'nome' => 'Produto Y', 'quantidadeAtual' => 10, 'qtdMinima' => 5]
        ];
        $this->estoqueRepository->expects($this->once())
            ->method('verificarQuantidadeMinima')
            ->willReturn($dadosMinima);

        $resultado = $this->estoqueController->verificarQuantidadeMinima();
        $this->assertEquals($dadosMinima, $resultado);
    }

    public function testVerificarQuantidadeMinimaLancaExcecaoRetornaNull() {
        $this->estoqueRepository->expects($this->once())
            ->method('verificarQuantidadeMinima')
            ->willThrowException(new Exception("Erro ao verificar estoque mínimo"));

        $resultado = $this->estoqueController->verificarQuantidadeMinima();
        $this->assertNull($resultado);
    }
}