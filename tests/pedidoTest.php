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
    
        // Conexão com o banco de dados principal (por enquanto)
        $this->connection = (new DataBase())->getConnection();
    
        // Criar banco de dados de teste
        $this->connection->exec("CREATE DATABASE IF NOT EXISTS db_test");
        $this->connection->exec("USE db_test");
    
        // Criar tabelas e dados necessários para o teste
        // Você pode criar as tabelas aqui ou usar migrações do seu banco
    
        // Iniciar transação para isolar as alterações
        $this->connection->beginTransaction();
    
        // Inicializar os objetos necessários para os testes
        $this->pedido = new Pedido();
    }
    
    public function tearDown(): void {
        // Finalizar qualquer transação e fazer rollback
        if ($this->connection) {
            $this->connection->rollBack();
        }
    
        // Excluir o banco de dados temporário
        $this->connection->exec("DROP DATABASE IF EXISTS db_test");
    
        // Fechar a conexão
        (new DataBase())->closeConnection();
    
        parent::tearDown();
    }    

    public function testListarPedidoPorCliente(){
        $resultadoRecebido = $this->pedido->listarPedidoPorCliente('jo@email.com');

        $chavesEsperadas = [
            'idPedido',
            'idCliente',
            'dtPedido',
            'dtPagamento', // depois refatorar o sistema pro entregador ou funcionario poder colocar essa data
            'tipoFrete',
            'idEndereco',
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
            'dtPedido',
            'dtPagamento', // depois refatorar o sistema pro entregador ou funcionario poder colocar essa data
            'tipoFrete',
            'idEndereco',
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

    public function testCriarPedido() {
        $email = 'jo@email.com';
        $tipoFrete = 1;
        $valorTotal = 28.00;
        $frete = 2.01;
        $meioDePagamento = 'Dinheiro';
        $trocoPara = 50;
    
        $this->pedido->criarPedido($email, $tipoFrete, $valorTotal, $frete, $meioDePagamento, $trocoPara);
    
        $resultadoRecebido = $this->pedido->listarPedidoPorCliente($email);
    
        $this->assertNotEmpty($resultadoRecebido, 'Nenhum pedido encontrado para o cliente.');
    
        $valoresEsperados = [
            'tipoFrete' => $tipoFrete,
            'valorTotal' => $valorTotal,
            'frete' => $frete,
            'meioPagamento' => $meioDePagamento,
            'trocoPara' => $trocoPara
        ];
    
        $valoresEncontrados = [
            'tipoFrete' => false,
            'valorTotal' => false,
            'frete' => false,
            'meioPagamento' => false,
            'trocoPara' => false
        ];
    
        foreach ($resultadoRecebido as $item) {
            foreach ($valoresEsperados as $chave => $valorEsperado) {
                if (isset($item[$chave]) && $item[$chave] == $valorEsperado) {
                    $valoresEncontrados[$chave] = true;
                }
            }
        }
    
        foreach ($valoresEncontrados as $chave => $encontrado) {
            $this->assertTrue($encontrado);
        }
    }
    
    public function testSalvarItensPedido() {
        $itensCarrinho = [
            [
                'id' => 1, 
                'qntd' => 2  
            ],
            [
                'id' => 2,
                'qntd' => 1
            ]
        ];
    
        $this->pedido->salvarItensPedido($itensCarrinho, 189);
    
        $resultadoRecebido = $this->pedido->listarInformacoesPedido(189);
    
        $valoresEsperados = [
            1 => 2, 
            2 => 1
        ];
    
    
        $valoresEncontrados = array_fill_keys(array_keys($valoresEsperados), false);
    
        foreach ($resultadoRecebido as $item) {
            $idVariacao = $item['idVariacao'] ?? null;
            $quantidade = $item['quantidade'] ?? null;
    
            if (isset($valoresEsperados[$idVariacao]) && $valoresEsperados[$idVariacao] == $quantidade) {
                $valoresEncontrados[$idVariacao] = true;
            }
        }

        foreach ($valoresEncontrados as $id => $encontrado) {
            $this->assertTrue(
                $encontrado, 
                "O item com idVariacao {$id} e quantidade {$valoresEsperados[$id]} não foi encontrado corretamente."
            );
        }
    }

    public function testListarPedidos(){
        $resultadoRecebido = $this->pedido->listarPedidos();

        $chavesEsperadas = [
            'idPedido',
            'idCliente',
            'dtPedido',
            'dtPagamento', // depois refatorar o sistema pro entregador ou funcionario poder colocar essa data
            'tipoFrete',
            'idEndereco',
            'valorTotal',
            'dtCancelamento',
            'motivoCancelamento',
            'statusPedido',
            'idEntregador',
            'frete',
            'meioPagamento',
            'trocoPara'
        ];


        foreach($resultadoRecebido as $item){
            foreach ($chavesEsperadas as $chave) {
                $this->assertArrayHasKey($chave, $item);
            }
        }

        $this->assertNotEmpty($resultadoRecebido);
    }
    
    public function testListarPedidosEntregador(){
        $emailEntregador = 'joao.silva@example.com';
        $resultadoRecebido = $this->pedido->listarPedidosEntregador($emailEntregador);

        $chavesEsperadas = [
            'idPedido',
            'idCliente',
            'dtPedido',
            'dtPagamento', // depois refatorar o sistema pro entregador ou funcionario poder colocar essa data
            'tipoFrete',
            'idEndereco',
            'valorTotal',
            'dtCancelamento',
            'motivoCancelamento',
            'statusPedido',
            'idEntregador',
            'frete',
            'meioPagamento',
            'trocoPara'
        ];


        foreach($resultadoRecebido as $item){
            foreach ($chavesEsperadas as $chave) {
                $this->assertArrayHasKey($chave, $item);
            }
        }

        $this->assertNotEmpty($resultadoRecebido);
    }

    public function testMudarStatus(){
        $this->pedido->mudarStatus(190, "CLIE", "Não tenho como retirar o produto");

        $resultadoRecebido = $this->pedido->listarPedidoPorId(190);

        $chavesEsperadas = [
            'idPedido',
            'dtCancelamento',
            'motivoCancelamento',
            'statusPedido',
        ];

        foreach ($chavesEsperadas as $chave) {
            $this->assertArrayHasKey($chave, $resultadoRecebido);
        }
        
        $this->assertNotEmpty($resultadoRecebido);
    }

    public function testMudarStatusEntregador(){
        $this->pedido->mudarStatusEntregador(191, 'Preparando pedido') ;

        $resultadoRecebido = $this->pedido->listarPedidoPorId(191);

        $chavesEsperadas = [
            'idPedido',
            'dtCancelamento',
            'motivoCancelamento',
            'statusPedido',
        ];

        foreach ($chavesEsperadas as $chave) {
            $this->assertArrayHasKey($chave, $resultadoRecebido);
        }
        

        $this->assertNotEmpty($resultadoRecebido);
    }

    public function testListarTodosItensPedidos(){
        $resultadoRecebido = $this->pedido->listarTodosItensPedidos('2024-01-01', '2025-02-01');

        $chavesEsperadas = [
            'idVariacao',
            'quantidade',
            'NomeProduto',
            'Preco',
            'Foto',
            'ProdutoDesativado'
        ];

        foreach($resultadoRecebido as $item){
            foreach ($chavesEsperadas as $chave) {
                $this->assertArrayHasKey($chave, $item);
            }
        }

        $this->assertNotEmpty($resultadoRecebido);
    }

    public function testListarResumo(){
        $resultadoRecebido = $this->pedido->listarResumo('2024-01-01', '2025-02-01');

        $chavesEsperadas = [
            'totalVendas',
            'totalPedidosClientes',
            'totalProdutos',
            'pedidosFeitos'
        ];

        foreach ($chavesEsperadas as $chave) {
            $this->assertArrayHasKey($chave, $resultadoRecebido);
        }

        $this->assertNotEmpty($resultadoRecebido);
    }

    public function testCalcularFrete(){
        $resultadoRecebido = $this->pedido->calcularFrete('08110600');

        $this->assertIsNumeric($resultadoRecebido, "O valor retornado não é numérico.");
        $this->assertGreaterThan(0, (float) $resultadoRecebido, "O valor retornado não é um número válido.");


    }
}