<?php
// Para rodar, coloque no caminho: C:\xampp\htdocs\amor-na-casquinha
// Para rodar os testes, use o comando: .\vendor\bin\phpunit

require_once __DIR__ . '/../vendor/autoload.php';

use PHPUnit\Framework\TestCase;
use app\model\Produto;
use app\config\DataBase;
use app\controller\ProdutoController;
use app\repository\ProdutoRepository;

class ProdutoControllerTest extends TestCase {
    private $produtoController;
    private $database;
    private $connection;
    private bool $transactionStarted = false;

    protected function setUp(): void {
        parent::setUp();
        $this->produtoController = new ProdutoController();
        $this->connection = (new DataBase())->getConnection();
    
        if (!$this->transactionStarted) {
            $this->connection->beginTransaction();
            $this->transactionStarted = true;
        }

        $this->connection->exec("SET autocommit=0;");
        $this->connection->exec("SET FOREIGN_KEY_CHECKS=0;");
    }
    
    public function tearDown(): void {
        if ($this->transactionStarted) {
            $this->connection->rollBack();
        }
    
        $this->connection->exec("SET FOREIGN_KEY_CHECKS=1;");
        
        parent::tearDown();
    }

    public function testSelecionarProdutosAtivosComCategoriaValida() {
        $dadosMock = [
            [
                'idProduto' => 1,
                'desativado' => 0,
                'nome' => 'Casquinha de Morango',
                'preco' => 5.50,
                'foto' => 'morango.jpg',
                'categoria' => 2
            ],
            [
                'idProduto' => 2,
                'desativado' => 0,
                'nome' => 'Casquinha de Chocolate',
                'preco' => 6.00,
                'foto' => 'chocolate.jpg',
                'categoria' => 2
            ]
        ];

        $repositoryMock = $this->createMock(ProdutoRepository::class);
        $repositoryMock->method('selecionarProdutosAtivosPorCategoria')
                       ->with(2) 
                       ->willReturn($dadosMock);

        $controller = new ProdutoController($repositoryMock);
        $resultado = $controller->selecionarProdutosAtivos(2);

        $this->assertIsArray($resultado);
        $this->assertCount(2, $resultado);
        $this->assertInstanceOf(Produto::class, $resultado[0]);
        $this->assertEquals('Casquinha de Morango', $resultado[0]->getNome());
    }
    
    public function testSelecionarProdutosAtivos() {
        $dadosMock = [
            [
                'idProduto' => 1,
                'desativado' => 0,
                'nome' => 'Casquinha de Morango',
                'preco' => 5.50,
                'foto' => 'morango.jpg',
                'categoria' => 2
            ],
            [
                'idProduto' => 2,
                'desativado' => 0,
                'nome' => 'Casquinha de Chocolate',
                'preco' => 6.00,
                'foto' => 'chocolate.jpg',
                'categoria' => 2
            ]
        ];

        $repositoryMock = $this->createMock(ProdutoRepository::class);
        $repositoryMock->method('selecionarProdutos')
                       ->willReturn($dadosMock);

        $controller = new ProdutoController($repositoryMock);
        $resultado = $controller->selecionarProdutos();

        $this->assertIsArray($resultado);
        $this->assertCount(2, $resultado);
        $this->assertInstanceOf(Produto::class, $resultado[0]);
        $this->assertEquals('Casquinha de Morango', $resultado[0]->getNome());
        $this->assertEquals(2, $resultado[0]->getCategoria());
        $this->assertEquals(5.50, $resultado[0]->getPreco());
        $this->assertEquals('morango.jpg', $resultado[0]->getFoto());
        $this->assertEquals('Casquinha de Chocolate', $resultado[1]->getNome());
        $this->assertEquals(2, $resultado[1]->getCategoria());
        $this->assertEquals(6.00, $resultado[1]->getPreco());
        $this->assertEquals('chocolate.jpg', $resultado[1]->getFoto());

    }

    public function testSelecionarProdutosCategoriaSemId() {
        $repositoryMock = $this->createMock(ProdutoRepository::class);
        $controller = new ProdutoController($repositoryMock);

        $resultado = $controller->selecionarProdutosCategoria(null);

        $this->assertFalse($resultado);
    }



    public function testCriarProdutoComDadosValidos() {
        $dados = [
            'categoria' => 2,
            'nome' => 'Sorvete de Chocolate',
            'preco' => 6.50,
            'foto' => 'chocolate.png'
        ];

        $repositoryMock = $this->createMock(ProdutoRepository::class);
        $repositoryMock->method('criarProduto')
            ->with(
                $dados['categoria'],
                $dados['nome'],
                $dados['preco'],
                $dados['foto']
            )
            ->willReturn(10);

        $controller = new ProdutoController($repositoryMock);
        $resultado = $controller->criarProduto($dados);

        $this->assertEquals(10, $resultado);
    }

    public function testCriarProdutoComDadosInvalidos() {
        $dados = [
            'categoria' => 2,
            'nome' => '',
            'preco' => 6.50,
            'foto' => 'chocolate.png'
        ];

        $repositoryMock = $this->createMock(ProdutoRepository::class);
        $controller = new ProdutoController($repositoryMock);

        $resultado = $controller->criarProduto($dados);

        $this->assertFalse($resultado);
    }

    public function testSelecionarProdutoPorIDComIDValido() {
        $id = 1;
        $produtoEsperado = new Produto($id, 0, "Sorvete", 9.99, "sorvete.jpg", "Sobremesa");

        $mockRepository = $this->createMock(ProdutoRepository::class);
        $mockRepository->method('selecionarProdutoPorID')
                       ->with($id)
                       ->willReturn($produtoEsperado);

        $controller = new ProdutoController($mockRepository);

        $resultado = $controller->selecionarProdutoPorID($id);
        $this->assertEquals($produtoEsperado, $resultado);
    }

    public function testSelecionarProdutoPorIDSemID() {
        $mockRepository = $this->createMock(ProdutoRepository::class);

        $controller = new ProdutoController($mockRepository);

        $resultado = $controller->selecionarProdutoPorID(null);
        $this->assertFalse($resultado);
    }

    public function testEditarProdutoComDadosValidos() {
        $dados = [
            'id' => 1,
            'nome' => 'Novo Sorvete',
            'preco' => 12.50,
            'foto' => 'sorvete_novo.jpg'
        ];

        $produtoMock = $this->createMock(Produto::class);
        $produtoMock->expects($this->once())
                    ->method('editar')
                    ->with($dados['nome'], $dados['preco'], $dados['foto']);

        $repositoryMock = $this->createMock(ProdutoRepository::class);
        $repositoryMock->method('selecionarProdutoPorID')
                        ->with($dados['id'])
                        ->willReturn($produtoMock);

        $repositoryMock->method('editarProduto')
                        ->with($dados['id'], $dados['nome'], $dados['preco'], $dados['foto'])
                        ->willReturn(true);

        $controller = new ProdutoController($repositoryMock);
        $resultado = $controller->editarProduto($dados);

        $this->assertTrue($resultado);
    }

    public function testEditarProdutoComDadosInvalidos() {
        $dados = [
            'id' => 1,
            'nome' => '', // inválido
            'preco' => 12.50,
            'foto' => 'sorvete.jpg'
        ];

        $repositoryMock = $this->createMock(ProdutoRepository::class);
        $controller = new ProdutoController($repositoryMock);

        $resultado = $controller->editarProduto($dados);

        $this->assertFalse($resultado);
    }

    public function testDesativarProdutoComIdValido() {
        $id = 1;

        $produtoMock = $this->createMock(Produto::class);
        $produtoMock->expects($this->once())
                    ->method('setDesativado')
                    ->with(1);

        $repositoryMock = $this->createMock(ProdutoRepository::class);
        $repositoryMock->method('selecionarProdutoPorID')
                       ->with($id)
                       ->willReturn($produtoMock);

        $repositoryMock->method('desativarProduto')
                       ->with($id)
                       ->willReturn(true);

        $controller = new ProdutoController($repositoryMock);
        $resultado = $controller->desativarProduto($id);

        $this->assertTrue($resultado);
    }

    public function testDesativarProdutoSemId() {
        $repositoryMock = $this->createMock(ProdutoRepository::class);
        $controller = new ProdutoController($repositoryMock);

        $resultado = $controller->desativarProduto(null);

        $this->assertFalse($resultado);
    }

    
    public function testAtivarProdutoComIdValido() {
        $id = 1;

        $repositoryMock = $this->createMock(ProdutoRepository::class);
        $repositoryMock->method('ativarProduto')
                       ->with($id)
                       ->willReturn(true);

        $controller = new ProdutoController($repositoryMock);
        $resultado = $controller->ativarProduto($id);

        $this->assertTrue($resultado);
    }

    public function testAtivarProdutoSemId() {
        $repositoryMock = $this->createMock(ProdutoRepository::class);
        $controller = new ProdutoController($repositoryMock);

        $resultado = $controller->ativarProduto(null);

        $this->assertFalse($resultado);
    }

}
?>