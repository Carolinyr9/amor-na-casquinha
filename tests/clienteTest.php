<?php 
use PHPUnit\Framework\TestCase;
use app\model\Cliente;

class clienteTest extends TestCase{

    public function testExibirCliente(){
        $cliente = new Cliente();
        $resultado = $cliente->getCliente("jo@email.com");

        $this->assertIsArray($resultado); 
        $this->assertEquals("joao lucas binario", $resultado["nome"]);
        $this->assertEquals("jo@email.com", $resultado["email"]);
        $this->assertEquals("44564-2132", $resultado["telefone"]);
        $this->assertEquals(1, $resultado["endereco"]["idEndereco"]);
        $this->assertEquals("Rua das Flores", $resultado["endereco"]["rua"]);
        $this->assertEquals("123", $resultado["endereco"]["numero"]);
        $this->assertEquals("Apto 101", $resultado["endereco"]["complemento"]);
        $this->assertEquals("Centro", $resultado["endereco"]["bairro"]);
        $this->assertEquals("Porto Alegre", $resultado["endereco"]["cidade"]);
        $this->assertEquals("RS", $resultado["endereco"]["estado"]);
        $this->assertEquals("90050-340", $resultado["endereco"]["cep"]);
    }

    public function testListarEndereco()
}
?>