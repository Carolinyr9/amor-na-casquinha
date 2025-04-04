<?php
// para rodar tem que estar no caminho: C:\xampp\htdocs\amor-na-casquinha
// para rodar os testes use o comando: .\vendor\bin\phpunit
require_once __DIR__ . '/../vendor/autoload.php';

use PHPUnit\Framework\TestCase;
use app\model\Funcionarios; // quero refatorar para deixar todas as clases no singular
use app\config\DataBase;

class FuncionariosTest extends TestCase {
    protected Funcionarios $funcionarios;
    protected $database;
    protected $connection;

    protected function setUp(): void {
        parent::setUp();
        $this->funcionarios = new Funcionarios();
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

    public function testListarFuncionarios(){
        $resultadoRecebido = $this->funcionarios->listarFunc();

        $chavesEsperadas = [
            'idFuncionario',
            'desativado', 
            'adm', 
            'perfil', 
            'nome', 
            'telefone', 
            'email', 
            'idEndereco'
        ];

        foreach($resultadoRecebido as $item){
            foreach ($chavesEsperadas as $chave) {
                $this->assertArrayHasKey($chave, $item);
            }
        }
        $this->assertNotEmpty($resultadoRecebido);
    }

    public function testListarFuncionarioPorEmail(){
        $email = 'je@email.com';
        $resultadoRecebido = $this->funcionarios->listarFuncionarioEmail($email);
        
        $chavesEsperadas = [
            'idFuncionario',
            'desativado', 
            'adm', 
            'perfil', 
            'nome', 
            'telefone', 
            'email', 
            'idEndereco'
        ];

        foreach($resultadoRecebido as $item){
            foreach ($chavesEsperadas as $chave) {
                $this->assertArrayHasKey($chave, $item);
                
            }
        }

        $this->assertEquals($email, $resultadoRecebido[0]['email']);
        $this->assertNotEmpty($resultadoRecebido);
    }

    public function testInserirFuncionario(){
        
        $this->funcionarios->inserirFunc('mariana', 'mariana@email.com', '11 998984900', '1234', 1);
        $email = 'mariana@email.com';
        $resultadoRecebido = $this->funcionarios->listarFuncionarioEmail($email);

        $chavesEsperadas = [
            'idFuncionario',
            'desativado', 
            'adm', 
            'perfil', 
            'nome', 
            'telefone', 
            'email', 
            'idEndereco'
        ];

        foreach($resultadoRecebido as $item){
            foreach ($chavesEsperadas as $chave) {
                $this->assertArrayHasKey($chave, $item);
                
            }
        }

        $this->assertEquals($email, $resultadoRecebido[0]['email']);
        $this->assertNotEmpty($resultadoRecebido);
    }

    public function testAtualizarFuncionario(){
        
        $this->funcionarios->atualizarFunc('mariana@email.com', 'marianna', 'marianna@email.com', '11 998987654');
        $email = 'marianna@email.com';
        $resultadoRecebido = $this->funcionarios->listarFuncionarioEmail($email);

        $chavesEsperadas = [
            'idFuncionario',
            'desativado', 
            'adm', 
            'perfil', 
            'nome', 
            'telefone', 
            'email', 
            'idEndereco'
        ];

        foreach($resultadoRecebido as $item){
            foreach ($chavesEsperadas as $chave) {
                $this->assertArrayHasKey($chave, $item);
                
            }
        }

        $this->assertEquals($email, $resultadoRecebido[0]['email']);
        $this->assertNotEmpty($resultadoRecebido);
    }

    public function testDeletarFuncionario(){
        
        $this->funcionarios->deletarFunc('marianna@email.com');
        $email = 'marianna@email.com';
        $resultadoRecebido = $this->funcionarios->listarFuncionarioEmail($email);

        $chavesEsperadas = [
            'idFuncionario',
            'desativado', 
            'adm', 
            'perfil', 
            'nome', 
            'telefone', 
            'email', 
            'idEndereco'
        ];

        foreach($resultadoRecebido as $item){
            foreach ($chavesEsperadas as $chave) {
                $this->assertArrayHasKey($chave, $item);
                
            }
        }

        $this->assertEquals($email, $resultadoRecebido[0]['email']);
        $this->assertEquals(1, $resultadoRecebido[0]['desativado']);
        $this->assertNotEmpty($resultadoRecebido);
    }


}