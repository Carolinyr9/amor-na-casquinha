<?php
// para rodar tem que estar no caminho: C:\xampp\htdocs\amor-na-casquinha
// para rodar os testes use o comando: .\vendor\bin\phpunit
require_once __DIR__ . '/../vendor/autoload.php';

use PHPUnit\Framework\TestCase;
use app\model\Fornecedores; // quero refatorar para deixar todas as clases no singular
use app\config\DataBase;

class FornecedoresTest extends TestCase {
    protected Fornecedores $fornecedores;
    protected $database;
    protected $connection;

    protected function setUp(): void {
        parent::setUp();
        $this->fornecedores = new Fornecedores();
        $this->database = new DataBase();
        $this->connection = $this->database->getConnection();
        $this->connection->beginTransaction();
    }

    protected function tearDown(): void {
        $this->connection->rollBack();
        parent::tearDown();
    }

    public function testListarFornecedores() {
        $resultadoRecebido = $this->fornecedores->listarForn();

        $chavesEsperadas = [
            'idFornecedor',
            'nome',
            'telefone',
            'email',
            'cnpj',
            'desativado',
            'idEndereco'
        ];

        foreach ($resultadoRecebido as $item) {
            foreach ($chavesEsperadas as $chave) {
                $this->assertArrayHasKey($chave, $item);
            }
        }
        $this->assertNotEmpty($resultadoRecebido);
    }

    public function testListarFornecedorPorEmail() {
        $email = 'contato@sorvetesdosul.com.br';
        $resultadoRecebido = $this->fornecedores->listarFornecedorEmail($email);

        $chavesEsperadas = [
            'idFornecedor',
            'nome',
            'telefone',
            'email',
            'cnpj',
            'desativado',
            'idEndereco'
        ];

        foreach ($chavesEsperadas as $chave) {
            $this->assertArrayHasKey($chave, $resultadoRecebido);
        }

        $this->assertEquals($email, $resultadoRecebido['email']);
        $this->assertNotEmpty($resultadoRecebido);
    }

    // NÃO ESTÁ FUNCIONANDO, VERIFICAR DEPOIS
    /*public function testInserirFornecedor() {
        $this->fornecedores->inserirForn('Nestlé', 'nestleSorvetes@email.com', '11 998986754', '12.345.678/0041-99', 'Rua Padre Antonio', 445, 'Vila Julia', 'teste', '08220800', 'São Paulo', 'São Paulo');
        $email = 'nestleSorvetes@email.com';
        $resultadoRecebido = $this->fornecedores->listarFornecedorEmail($email);
        var_dump($resultadoRecebido);

        $chavesEsperadas = [
            'idFornecedor',
            'nome',
            'telefone',
            'email',
            'cnpj',
            'desativado',
            'idEndereco'
        ];

        foreach ($chavesEsperadas as $chave) {
            $this->assertArrayHasKey($chave, $resultadoRecebido);
        }

        $this->assertEquals($email, $resultadoRecebido['email']);
        $this->assertNotEmpty($resultadoRecebido);
    }*/

    public function testAtualizarFornecedor() {
        $this->fornecedores->atualizarForn('contato@sorvetesdosul.com.br', 'Sorvetes do Sull', 'contato@sorvetesdosul.com.br', '11 998986754');
        $email = 'contato@sorvetesdosul.com.br';
        $resultadoRecebido = $this->fornecedores->listarFornecedorEmail($email);

        $chavesEsperadas = [
            'idFornecedor',
            'nome',
            'telefone',
            'email',
            'cnpj',
            'desativado',
            'idEndereco'
        ];

        foreach ($chavesEsperadas as $chave) {
            $this->assertArrayHasKey($chave, $resultadoRecebido);
        }

        $this->assertEquals($email, $resultadoRecebido['email']);
        $this->assertNotEmpty($resultadoRecebido);
    }

    public function testDeletarFornecedor() {
        $email = 'contato@sorvetesdosul.com.br';
        $this->fornecedores->deletarForn($email);
        $resultadoRecebido = $this->fornecedores->listarFornecedorEmail($email);

        $chavesEsperadas = [
            'idFornecedor',
            'nome',
            'telefone',
            'email',
            'cnpj',
            'desativado',
            'idEndereco'
        ];

        foreach ($chavesEsperadas as $chave) {
            $this->assertArrayHasKey($chave, $resultadoRecebido);
        }

        $this->assertEquals($email, $resultadoRecebido['email']);
        $this->assertEquals(1, $resultadoRecebido['desativado']);
        $this->assertNotEmpty($resultadoRecebido);
    }
}
