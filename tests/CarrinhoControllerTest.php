<?php
use PHPUnit\Framework\TestCase;
use app\controller\CarrinhoController;
use app\model\Carrinho;
use app\model\Produto; 
use app\utils\helpers\Logger;

require_once __DIR__ . '/../app/utils/helpers/Logger.php';

class CarrinhoControllerTest extends TestCase {
    private $carrinhoController;

    protected function setUp(): void {
        $_SESSION = [];
        $this->carrinhoController = new CarrinhoController();
    }

    protected function tearDown(): void {
        unset($_SESSION);
        $_SESSION = []; 
    }

    public function testAdicionarProdutoComProdutoValidoRetornaTrue() {
        $produto = new Produto(1, 0, 'Sorvete de Chocolate', 15.00, 'choco.png', 'picole');
        $resultado = $this->carrinhoController->adicionarProduto($produto);
        $this->assertTrue($resultado);
        $this->assertCount(1, $_SESSION['cartArray']);
        $this->assertEquals(1, $_SESSION['cartArray'][1]['qntd']);
    }

    public function testAdicionarProdutoIncrementaQuantidadeSeProdutoJaExiste() {
        $produto = new Produto(1, 0, 'Sorvete de Chocolate', 15.00, 'choco.png', 'picole');

        $this->carrinhoController->adicionarProduto($produto);
        $resultado = $this->carrinhoController->adicionarProduto($produto);

        $this->assertTrue($resultado);
        $this->assertCount(1, $_SESSION['cartArray']);
        $this->assertEquals(2, $_SESSION['cartArray'][1]['qntd']);
    }

    public function testAdicionarProdutoComProdutoInvalidoRetornaFalse() {
        $produtoInvalido = (object)['id' => 1, 'nome' => 'Invalido']; 
        $resultado = $this->carrinhoController->adicionarProduto($produtoInvalido);
        $this->assertFalse($resultado);
        $this->assertEmpty($_SESSION['cartArray']);
    }

    public function testAdicionarProdutoComProdutoNuloRetornaFalse() {
        $resultado = $this->carrinhoController->adicionarProduto(null);
        $this->assertFalse($resultado);
        $this->assertEmpty($_SESSION['cartArray']);
    }

    // --- Testes para removerProduto ---

    public function testRemoverProdutoRemoveProdutoDoCarrinho() {
        $produto = new Produto(1, 0, 'Sorvete de Chocolate', 15.00, 'choco.png', 'picole');
        $this->carrinhoController->adicionarProduto($produto);
        $this->assertCount(1, $_SESSION['cartArray']);

        $this->carrinhoController->removerProduto(1);
        $this->assertEmpty($_SESSION['cartArray']);
    }

    public function testRemoverProdutoNaoFazNadaSeIdNaoExiste() {
        $produto = new Produto(1, 0, 'Sorvete de Chocolate', 15.00, 'choco.png', 'picole');
        $this->carrinhoController->adicionarProduto($produto);
        $this->assertCount(1, $_SESSION['cartArray']);

        $this->carrinhoController->removerProduto(99); 
        $this->assertCount(1, $_SESSION['cartArray']);
        $this->assertArrayHasKey(1, $_SESSION['cartArray']);
    }

    // --- Testes para atualizarCarrinho (depende de $_POST) ---

    public function testAtualizarCarrinhoAtualizaQuantidadesCorretamente() {
        $produto1 = new Produto(10, 0, 'Produto A', 10.00, 'a.png', 'picole');
        $produto2 = new Produto(20, 0, 'Produto B', 20.00, 'b.png', 'picole');

        $this->carrinhoController->adicionarProduto($produto1); 
        $this->carrinhoController->adicionarProduto($produto2); 

        $_POST['cart'] = 'true';
        $_POST['select10'] = 5; 
        $_POST['select20'] = 2;

        $this->carrinhoController->atualizarCarrinho();

        $this->assertEquals(5, $_SESSION['cartArray'][10]['qntd']);
        $this->assertEquals(2, $_SESSION['cartArray'][20]['qntd']);
    }

    public function testAtualizarCarrinhoIgnoraCamposNaoRelacionados() {
        $produto = new Produto(10, 0, 'Produto A', 10.00, 'a.png', 'picole');
        $this->carrinhoController->adicionarProduto($produto);

        $_POST['cart'] = 'true';
        $_POST['select10'] = 3;
        $_POST['outroCampo'] = 'valor';

        $this->carrinhoController->atualizarCarrinho();
        $this->assertEquals(3, $_SESSION['cartArray'][10]['qntd']);
        $this->assertFalse(isset($_SESSION['cartArray']['outroCampo']));
    }

    // --- Testes para listarCarrinho ---

    public function testListarCarrinhoComProdutosRetornaListaCorreta() {
        $produto1 = new Produto(1, 0, 'Sorvete A', 10.00, 'a.png', 'picole');
        $produto2 = new Produto(2, 0, 'Sorvete B', 20.00, 'b.png', 'picole');

        $this->carrinhoController->adicionarProduto($produto1);
        $this->carrinhoController->adicionarProduto($produto2);

        $carrinhoListado = $this->carrinhoController->listarCarrinho();

        $this->assertIsArray($carrinhoListado);
        $this->assertCount(2, $carrinhoListado);

        $this->assertEquals(1, $carrinhoListado[0]['id']);
        $this->assertEquals('Sorvete A', $carrinhoListado[0]['nome']);
        $this->assertEquals(10.00, $carrinhoListado[0]['preco']);
        $this->assertEquals(1, $carrinhoListado[0]['qntd']);
        $this->assertStringContainsString('<option value="1" selected>1</option>', $carrinhoListado[0]['quantidades']);

        $this->assertEquals(2, $carrinhoListado[1]['id']);
        $this->assertEquals('Sorvete B', $carrinhoListado[1]['nome']);
        $this->assertEquals(20.00, $carrinhoListado[1]['preco']);
        $this->assertEquals(1, $carrinhoListado[1]['qntd']);
    }

    public function testListarCarrinhoVazioRetornaArrayVazio() {
        $carrinhoListado = $this->carrinhoController->listarCarrinho();
        $this->assertIsArray($carrinhoListado);
        $this->assertEmpty($carrinhoListado);
    }

    // --- Testes para calcularTotal ---

    public function testCalcularTotalRetornaSomaCorretaDosProdutos() {
        $produto1 = new Produto(1, 0, 'Sorvete A', 10.00, 'a.png', 'picole');
        $produto2 = new Produto(2, 0, 'Sorvete B', 20.00, 'b.png', 'picole');

        $this->carrinhoController->adicionarProduto($produto1); 
        $this->carrinhoController->adicionarProduto($produto2); 
        $this->carrinhoController->adicionarProduto($produto1); 

        $total = $this->carrinhoController->calcularTotal();
        $this->assertEquals(40.00, $total);
    }

    public function testCalcularTotalDeCarrinhoVazioRetornaZero() {
        $total = $this->carrinhoController->calcularTotal();
        $this->assertEquals(0, $total);
    }

    // --- Testes para getPedidoData ---

    public function testGetPedidoDataComUsuarioLogado() {
        $_SESSION["userEmail"] = "usuario@example.com";

        $produto = new Produto(1, 0, 'Item', 10.00, 'item.png', 'picole');
        $this->carrinhoController->adicionarProduto($produto);

        $pedidoData = $this->carrinhoController->getPedidoData();

        $this->assertIsArray($pedidoData);
        $this->assertEquals(10.00, $pedidoData['total']);
        $this->assertTrue($pedidoData['isUserLoggedIn']);
    }

    public function testGetPedidoDataComUsuarioNaoLogado() {
        unset($_SESSION["userEmail"]);

        $produto = new Produto(1, 0, 'Item', 10.00, 'item.png', 'picole');
        $this->carrinhoController->adicionarProduto($produto);

        $pedidoData = $this->carrinhoController->getPedidoData();

        $this->assertIsArray($pedidoData);
        $this->assertEquals(10.00, $pedidoData['total']);
        $this->assertFalse($pedidoData['isUserLoggedIn']);
    }

    public function testGetPedidoDataComCarrinhoVazio() {
        unset($_SESSION["userEmail"]);
        unset($_SESSION["cartArray"]);

        $pedidoData = $this->carrinhoController->getPedidoData();

        $this->assertIsArray($pedidoData);
        $this->assertEquals(0, $pedidoData['total']);
        $this->assertFalse($pedidoData['isUserLoggedIn']);
    }

    // --- Testes para limparCarrinho ---

    public function testLimparCarrinhoRemoveTodosOsProdutos() {
        $produto1 = new Produto(1, 0, 'Sorvete A', 10.00, 'a.png', 'picole');
        $produto2 = new Produto(2, 0, 'Sorvete B', 20.00, 'b.png', 'picole');
        $this->carrinhoController->adicionarProduto($produto1);
        $this->carrinhoController->adicionarProduto($produto2);
        $this->assertCount(2, $_SESSION['cartArray']);

        $this->carrinhoController->limparCarrinho();
        $this->assertFalse(isset($_SESSION['cartArray']));
        $this->assertEmpty($this->carrinhoController->listarCarrinho()); 
    }

    public function testLimparCarrinhoNaoLancaErroSeJaEstiverVazio() {
        $this->carrinhoController->limparCarrinho();
        $this->assertFalse(isset($_SESSION['cartArray']));
    }

    // --- Testes para atualizarQuantidade ---

    public function testAtualizarQuantidadeAtualizaCorretamente() {
        $produto = new Produto(1, 0, 'Item', 10.00, 'item.png', 'picole');
        $this->carrinhoController->adicionarProduto($produto);
        $this->assertEquals(1, $_SESSION['cartArray'][1]['qntd']);

        $dados = ['id' => 1, 'quantidade' => 5];
        $this->carrinhoController->atualizarQuantidade($dados);
        $this->assertEquals(5, $_SESSION['cartArray'][1]['qntd']);
    }

    public function testAtualizarQuantidadeNaoFazNadaSeIdNaoExiste() {
        $produto = new Produto(1, 0, 'Item', 10.00, 'item.png', 'picole');
        $this->carrinhoController->adicionarProduto($produto);
        
        $dados = ['id' => 99, 'quantidade' => 5]; 
        $this->carrinhoController->atualizarQuantidade($dados);
        $this->assertEquals(1, $_SESSION['cartArray'][1]['qntd']);
        $this->assertFalse(isset($_SESSION['cartArray'][99]));
    }
}