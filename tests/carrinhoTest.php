<?php
// Para rodar, coloque no caminho: C:\xampp\htdocs\amor-na-casquinha
// Para rodar os testes, use o comando: .\vendor\bin\phpunit

require_once __DIR__ . '/../vendor/autoload.php';

use PHPUnit\Framework\TestCase;
use app\model\Carrinho;
use app\config\DataBase;

class CarrinhoTest extends TestCase {
    private $carrinho;
    private $database;
    private $connection;

    protected function setUp(): void {
        parent::setUp();
        $this->carrinho = new Carrinho();
        $this->connection = (new DataBase())->getConnection();
    
        // Inicia a transação explicitamente apenas se não estiver em uma transação já iniciada
        if (!$this->transactionStarted) {
            $this->connection->beginTransaction();
            $this->transactionStarted = true;
        }
    
        // Desabilita o autocommit para garantir controle total sobre o commit
        $this->connection->exec("SET autocommit=0;");
        
        // Desabilita as chaves estrangeiras temporariamente, se necessário
        $this->connection->exec("SET FOREIGN_KEY_CHECKS=0;");
    }
    
    public function tearDown(): void {
        // Reverte qualquer alteração no banco
        if ($this->transactionStarted) {
            $this->connection->rollBack();
        }
    
        // Reabilita as chaves estrangeiras
        $this->connection->exec("SET FOREIGN_KEY_CHECKS=1;");
        
        parent::tearDown();
    }
    

    public function testListarCarrinho(){
        $resultadoRecebido = $this->carrinho->listarCarrinho();

        $resultadoEsperadoCasoCarrinhoVazio = [];

        if (empty($resultadoRecebido)) {
            $this->assertEquals($resultadoEsperadoCasoCarrinhoVazio, $resultadoRecebido);
        } else {
            $chavesEsperadas = ['nome', 'preco', 'foto', 'qntd'];

            foreach ($resultadoRecebido as $item) {
                foreach ($chavesEsperadas as $chave) {
                    $this->assertArrayHasKey($chave, $item);
                }
            }

            $this->assertNotEmpty($resultadoRecebido);
        }
    }

    public function testAdicionarProduto(){
        $variacaoId = 4;
        $this->carrinho->addProduto($variacaoId);
        $resultadoRecebido = $this->carrinho->listarCarrinho();

        $idEncontrado = false;
        foreach ($resultadoRecebido as $item) {
            if ($item['id'] == $variacaoId) {
                $idEncontrado = true;
                break;
            }
        }

        $this->assertTrue($idEncontrado);
    }

    public function testAtualizarQtdd(){
        $variacaoId = 4;
        $quantidade = 2;
        $this->carrinho->atualizarQtdd($variacaoId, $quantidade);
        $resultadoRecebido = $this->carrinho->listarCarrinho();

        $idEncontrado = false;
        $quantidadeEncontrada = null;
        foreach ($resultadoRecebido as $item) {
            if ($item['id'] == $variacaoId) {
                $idEncontrado = true;
                $quantidadeEncontrada = $item['qntd'];
                break;
            }
        }

        $this->assertTrue($idEncontrado);
        $this->assertEquals($quantidade, $quantidadeEncontrada);
    }

    public function testGetTotal(){
        $resultadoEsperado = 7.98;
        $resultadoRecebido = $this->carrinho->getTotal();
        $this->assertEquals($resultadoEsperado, $resultadoRecebido);
    }

    public function testRemoveProduto(){
        $variacaoId = 4;
        $this->carrinho->removeProduto($variacaoId);
        $resultadoRecebido = $this->carrinho->listarCarrinho();

        $idEncontrado = false;
        foreach ($resultadoRecebido as $item) {
            if ($item['id'] == $variacaoId) {
                $idEncontrado = true;
                break;
            }
        }

        $this->assertFalse($idEncontrado);
    }

    
    public function testLimparCarrinho(){
        $this->carrinho->limparCarrinho();
        $resultadoRecebido = $this->carrinho->listarCarrinho();
        $this->assertEmpty($resultadoRecebido);
    }

}
