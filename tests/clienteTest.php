<?php 
// para rodar tem que estar no caminho: C:\xampp\htdocs\amor-na-casquinha
// para rodar os testes use o comando: .\vendor\bin\phpunit

require_once __DIR__ . '/../vendor/autoload.php';

use PHPUnit\Framework\TestCase;
use app\model\Cliente;
use app\config\DataBase;

class ClienteTest extends TestCase {

    protected Cliente $cliente;
    protected $database;
    protected $connection;

    protected function setUp(): void {
        parent::setUp();
        $this->cliente = new Cliente();
        $this->database = new DataBase();
        $this->connection = $this->database->getConnection();
        $this->connection->beginTransaction();
    }

    protected function tearDown(): void {
        // Reverte todas as mudanças feitas no banco de dados
        $this->connection->rollBack();
        parent::tearDown();
    }

    public function testEditarCliente() {
        $email = "jo@email.com";
        $idEndereco = 1;
        $nome = "joao Lucas";
        $telefone = "44564-2135";
        $rua = "Rua Edson de Carvalho";
        $cep = "08110520";
        $numero = "150";
        $bairro = "Vila Alabama";
        $cidade = "São Paulo";
        $estado = "SP";
        $complemento = "";
    
        $this->cliente->editarCliente($email, $idEndereco, $nome, $telefone, $rua, $cep, $numero, $bairro, $cidade, $estado, $complemento);
        $clienteAtualizado = $this->cliente->getCliente($email);
        
        $conteudoEsperado = [
            "nome" => "joao Lucas",
            "email" => "jo@email.com",
            "telefone" => "44564-2135",
            "endereco" => [
                "idEndereco" => 1,
                "rua" => "Rua Edson de Carvalho",
                "numero" => "150",
                "complemento" => "",
                "bairro" => "Vila Alabama",
                "cidade" => "São Paulo",
                "estado" => "SP",
                "cep" => "08110520"
            ]
        ];
        
        $this->assertEquals($conteudoEsperado, $clienteAtualizado);
    }

    public function testExibirCliente() {
        $conteudoRecebido = $this->cliente->getCliente("jo@email.com");
    
        $conteudoEsperado = [
            "nome" => "joao Lucas",
            "email" => "jo@email.com",
            "telefone" => "44564-2135",
            "endereco" => [
                "idEndereco" => 1,
                "rua" => "Rua Edson de Carvalho",
                "numero" => "150",
                "complemento" => "",
                "bairro" => "Vila Alabama",
                "cidade" => "São Paulo",
                "estado" => "SP",
                "cep" => "08110520"
            ]
        ];
    
        $this->assertEquals($conteudoEsperado, $conteudoRecebido);
    }

    public function testExibirEndereco() {
        $idEndereco = 1;
        $conteudoRecebido = $this->cliente->listarEndereco($idEndereco);

        $conteudoEsperado = [
            "rua" => "Rua Edson de Carvalho",
            "numero" => 150,
            "complemento" => "",
            "bairro" => "Vila Alabama",
            "cidade" => "São Paulo",
            "estado" => "SP",
            "cep" => "08110520",
            "idEndereco" => 1
        ];
        
        $this->assertEqualsCanonicalizing($conteudoEsperado, $conteudoRecebido);
    }

    public function testExibirCep() {
        $conteudoRecebido = $this->cliente->getCep(1);
    
        $conteudoEsperado = '08110520';
    
        $this->assertEquals($conteudoEsperado, $conteudoRecebido);
    }
}
?>