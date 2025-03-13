<?php

require_once __DIR__ . '/../vendor/autoload.php';

use PHPUnit\Framework\TestCase;
use app\model\Pedido;
use app\config\DataBase;

class PedidoTest extends TestCase {
    private $pedido;
    private $database;
    private $connection;

    protected function setUp(): void {
        parent::setUp();
        $this->pedido = new Pedido();
        $this->database = new DataBase();
        $this->connection = $this->database->getConnection();
        $this->connection->beginTransaction();
    }

    public function tearDown(): void {
        $this->connection->rollBack();
        parent::tearDown();
    }

    public function testListarPedidoPorCliente(){
        $resultadoRecebido = $this->pedido->listarPedidoPorCliente('jo@email.com');

        $chavesEsperadas = [
            'idPedido',
            'idCliente',
            'dtPagamento',
            'dtPagamento', // depois refatorar o sistema pro entregador ou funcionario poder colocar essa data
            'tipoFrete',
            'valorTotal',
            'dtCancelamento',
            'motivoCancelamento',
            'statusPedido',
            'idEntregador',
            'frete',
            'meioPagamento',
            'trocoPara'
        ];

        foreach ($resultadoRecebido as $item) {
            foreach ($chavesEsperadas as $chave) {
                $this->assertArrayHasKey($chave, $item);
            }
        }

        $this->assertNotEmpty($resultadoRecebido);
    }

    public function testListarPedidoPorId(){
        $resultadoRecebido = $this->pedido->listarPedidoPorId(187);

        $chavesEsperadas = [
            'idPedido',
            'idCliente',
            'dtPagamento',
            'dtPagamento', // depois refatorar o sistema pro entregador ou funcionario poder colocar essa data
            'tipoFrete',
            'valorTotal',
            'dtCancelamento',
            'motivoCancelamento',
            'statusPedido',
            'idEntregador',
            'frete',
            'meioPagamento',
            'trocoPara'
        ];

        foreach ($chavesEsperadas as $chave) {
            $this->assertArrayHasKey($chave, $resultadoRecebido);
        }
        

        $this->assertNotEmpty($resultadoRecebido);
    }

    public function testListarInformacoesPedido(){
        $resultadoRecebido = $this->pedido->listarInformacoesPedido(187);

        $chavesEsperadas = [
            'idPedido',
            'quantidade',
            'idVariacao',
            'NomeProduto',
            'Preco',
            'Foto',
            'ProdutoDesativado',
        ];

        foreach($resultadoRecebido as $item){
            foreach ($chavesEsperadas as $chave) {
                $this->assertArrayHasKey($chave, $item);
            }
        }

        $this->assertNotEmpty($resultadoRecebido);
    }

    public function testCriarPedido(){ // terminar depois
        $this->$pedido->criarPedido('jo@email.com', 1, 28.00, 'Dinheiro', 2.01, 50);
        

    }


}