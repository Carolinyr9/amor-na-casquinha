<?php
// Para rodar, coloque no caminho: C:\xampp\htdocs\amor-na-casquinha
// Para rodar os testes, use o comando: .\vendor\bin\phpunit

require_once __DIR__ . '/../vendor/autoload.php';

use PHPUnit\Framework\TestCase;
use app\model\Produto;
use app\config\DataBase;

class ProdutoTest extends TestCase {
    private $produto;
    private $database;
    private $connection;

    protected function setUp(): void {
        parent::setUp();
        $this->produto = new Produto();
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
    

    public function testAdicionarProduto() {
        $resultadoRecebido = $this->produto->adicionarProduto(
            "Bombom de sorvete",
            "Nestlé",
            "Sobremesa gelada que combina sorvete com uma cobertura de chocolate",
            1,
            "1234.png"
        );

        $resultadoEsperado = "Produto criado com sucesso!";

        $this->assertEquals($resultadoEsperado, $resultadoRecebido);
    }

    public function testSelecionarProdutos() {
        $resultadoRecebido = $this->produto->selecionarProdutos();

        $chavesEsperadas = [
            'idProduto',
            'idFornecedor',
            'nome',
            'marca',
            'descricao',
            'desativado',
            'foto'
        ];

        foreach ($chavesEsperadas as $chave) {
            $this->assertArrayHasKey($chave, $resultadoRecebido[0]);
        }

        $this->assertNotEmpty($resultadoRecebido);
    }

    public function testEditarProduto() {
        $resultadoRecebido = $this->produto->editarProduto(
            1,
            "Pote",
            "Nestlé",
            "Potes de Sorvete",
            "98fb6a95c11ab1b4270121f66ced7c98.png"
        );

        $resultadoEsperado = "Produto atualizado com sucesso!";

        $this->assertEquals($resultadoEsperado, $resultadoRecebido);
    }

    public function testSelecionarProdutosPorId() {
        $resultadoRecebido = $this->produto->selecionarProdutosPorID(3);
        $chavesEsperadas = [
            'idProduto',
            'idFornecedor',
            'nome',
            'marca',
            'descricao',
            'desativado',
            'foto'
        ];

        foreach ($chavesEsperadas as $chave) {
            $this->assertArrayHasKey($chave, $resultadoRecebido);
        }

        $this->assertCount(7, $resultadoRecebido); // ja que sao 7 parametros que ele retorna
    }

    public function testRemoverProduto() {
        $resultadoRecebido = $this->produto->removerProduto(1);
        
        $resultadoEsperado = "Produto excluído com sucesso";

        $this->assertEquals($resultadoEsperado, $resultadoRecebido);
    }
}
?>