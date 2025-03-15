<?php
// Para rodar, coloque no caminho: C:\xampp\htdocs\amor-na-casquinha
// Para rodar os testes, use o comando: .\vendor\bin\phpunit

require_once __DIR__ . '/../vendor/autoload.php';

use PHPUnit\Framework\TestCase;
use app\model\Entregador;
use app\config\DataBase;

class EntregadorTest extends TestCase {
    private $entregador;
    private $database;
    private $connection;

    protected function setUp(): void {
        parent::setUp();
        $this->entregador = new Entregador();
        $this->database = new DataBase();
        $this->connection = $this->database->getConnection();
        $this->connection->beginTransaction();
    }

    protected function tearDown(): void{
        $this->connection->rollback();
        parent::tearDown();
    }

    public function testListarEntregadores(){
        $resultadoRecebido = $this->entregador->listarEntregadores();
        $chavesEsperadas = [
            'idEntregador',
            'desativado',
            'perfil',
            'nome',
            'telefone',
            'email',
            'cnh'
        ];

        foreach($resultadoRecebido as $item){
            foreach ($chavesEsperadas as $chave) {
                $this->assertArrayHasKey($chave, $item);
            }
        }
        $this->assertNotEmpty($resultadoRecebido);
    }

    public function testlistarEntregadorPorId() {
        $idEntregador = 1;
        $resultadoRecebido = $this->entregador->listarEntregadorPorId($idEntregador);
        
        $this->assertIsArray($resultadoRecebido);
        
        $this->assertCount(1, $resultadoRecebido);
    
        $item = $resultadoRecebido[0];
        
        $chavesEsperadas = [
            'idEntregador',
            'desativado',
            'perfil',
            'nome',
            'telefone',
            'email',
            'cnh'
        ];
        
        foreach ($chavesEsperadas as $chave) {
            $this->assertArrayHasKey($chave, $item);
        }
        
        $this->assertEquals($idEntregador, $item['idEntregador']);
        $this->assertNotEmpty($resultadoRecebido);
    }
    
}
?>