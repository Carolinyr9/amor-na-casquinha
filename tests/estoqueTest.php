<?php

require_once __DIR__ . '/../vendor/autoload.php';

use PHPUnit\Framework\TestCase;
use app\model\Estoque;
use app\config\DataBase;

class EstoqueTest extends TestCase {
    private $estoque;
    private $database;
    private $connection;

    protected function setUp(): void {
        parent::setUp();
        $this->estoque = new Estoque();
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
    

    public function testListarEstoque() {
        $resultadoRecebido = $this->estoque->listarEstoque();

        $chavesEsperadas = [
            'idEstoque',
            'idProduto',
            'idVariacao',
            'lote',
            'dtEntrada',
            'quantidade',
            'dtFabricacao',
            'dtVencimento',
            'precoCompra',
            'qtdMinima',
            'qtdVendida',
            'qtdOcorrencia',
            'ocorrencia',
            'desativado'
        ];

        foreach ($resultadoRecebido as $item) {
            foreach ($chavesEsperadas as $chave) {
                $this->assertArrayHasKey($chave, $item);
            }
        }

        $this->assertNotEmpty($resultadoRecebido);
    }

    public function testSelecionarProdutoEstoquePorID() {
        $resultadoRecebido = $this->estoque->selecionarProdutoEstoquePorID(1);

        $chavesEsperadas = [
            'idEstoque',
            'idProduto',
            'idVariacao',
            'lote',
            'dtEntrada',
            'quantidade',
            'dtFabricacao',
            'dtVencimento',
            'precoCompra',
            'qtdMinima',
            'qtdVendida',
            'qtdOcorrencia',
            'ocorrencia',
            'desativado'
        ];
        
        foreach($resultadoRecebido as $item){
            foreach ($chavesEsperadas as $chave) {
                $this->assertArrayHasKey($chave, $item);
            }
        }

        $this->assertNotEmpty($resultadoRecebido);
    }

    public function testEditarProdutoEstoque() {
        $produto = [
            "idEstoque" => 1, 
            "dataEntrada" => '2025-01-01',
            "quantidade" => 11,
            "dataFabricacao" => '2025-01-01',
            "dataVencimento" => '2025-12-31',
            "valor" => 99.98,
            "quantidadeMinima" => 5, 
            "quantidadeOcorrencia" => null, 
            "ocorrencia" => 0
        ];

        $resultadoEsperado = [
            "idEstoque" => 1,
            "idProduto" => 1,
            "idVariacao" => 1,
            "lote" => 1,
            "dtEntrada" => '2025-01-01',
            "quantidade" => 11,
            "dtFabricacao" => '2025-01-01',
            "dtVencimento" => '2025-12-31',
            "precoCompra" => '99.98',
            "qtdMinima" => 5, 
            "qtdVendida" => null,
            "qtdOcorrencia" => null,
            "ocorrencia" => "0",
            "desativado" => 1, 
        ];

        $this->estoque->editarProdutoEstoque($produto);
        $resultadoRecebido = $this->estoque->selecionarProdutoEstoquePorID(1);

        $this->assertEquals($resultadoEsperado, $resultadoRecebido[0]);
    }

    public function testExcluirProdutoEstoque() {
        $this->estoque->excluirProdutoEstoque(1, 1);

        $resultadoRecebido = $this->estoque->selecionarProdutoEstoquePorID(1);

        $this->assertEquals($resultadoRecebido[0]['desativado'], 1);
    }

    public function testVerificarQuantidadeMinima() {
        $resultadoRecebido = $this->estoque->verificarQuantidadeMinima();
    
        $chavesEsperadas = [
            'idProduto',
            'idVariacao',
            'quantidade'
        ];
    
        if (count($resultadoRecebido) > 0) {  
            foreach ($chavesEsperadas as $chave) {
                $this->assertArrayHasKey($chave, $resultadoRecebido[0]);
            }
        } else {
            $this->assertEmpty($resultadoRecebido);
        }
    }
}
?>
