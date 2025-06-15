<?php

use PHPUnit\Framework\TestCase;
use app\controller\EntregadorController;
use app\repository\EntregadorRepository;
use app\model\Entregador;
use app\utils\helpers\Logger;

require_once __DIR__ . '/../app/utils/helpers/Logger.php';

class EntregadorControllerTest extends TestCase {
    private $entregadorRepositoryMock;
    private $entregadorController;

    protected function setUp(): void {
        $this->entregadorRepositoryMock = $this->createMock(EntregadorRepository::class);
        $this->entregadorController = new EntregadorController($this->entregadorRepositoryMock);
    }

    // --- Testes para criarEntregador ---

    public function testCriarEntregadorComDadosValidosRetornaObjetoEntregador() {
        $dados = [
            'nome' => 'Novo Entregador',
            'email' => 'novo@example.com',
            'telefone' => '11987654321',
            'cnh' => '12345678901',
            'senha' => 'Senha@123'
        ];

        $this->entregadorRepositoryMock->expects($this->once())
            ->method('criarEntregador')
            ->with(
                $dados['nome'],
                $dados['email'],
                $dados['telefone'],
                $dados['cnh'],
                $dados['senha']
            )
            ->willReturn(1);

        $entregador = $this->entregadorController->criarEntregador($dados);

        $this->assertInstanceOf(Entregador::class, $entregador);
        $this->assertEquals(1, $entregador->getId());
        $this->assertEquals('novo@example.com', $entregador->getEmail());
        $this->assertEquals(0, $entregador->getDesativado());
    }

    public function testCriarEntregadorComDadosInvalidosRetornaFalse() {
        $dados = [
            'nome' => 'Entregador Inválido',
            'email' => 'email_invalido',
            'telefone' => '11987654321',
            'cnh' => '12345678901',
            'senha' => 'Senha@123'
        ];

        $this->entregadorRepositoryMock->expects($this->never())
            ->method('criarEntregador');

        $resultado = $this->entregadorController->criarEntregador($dados);
        $this->assertFalse($resultado);
    }

    public function testCriarEntregadorComDadosFaltandoRetornaFalse() {
        $dados = [
            'nome' => 'Entregador Faltando',
            'email' => 'faltando@example.com',
            'telefone' => '11987654321',
            'senha' => 'Senha@123'
        ];

        $this->entregadorRepositoryMock->expects($this->never())
            ->method('criarEntregador');

        $resultado = $this->entregadorController->criarEntregador($dados);
        $this->assertFalse($resultado);
    }

    public function testCriarEntregadorQuandoRepositorioFalhaRetornaNull() {
        $dados = [
            'nome' => 'Entregador Falha Repo',
            'email' => 'falha@example.com',
            'telefone' => '11987654321',
            'cnh' => '12345678901',
            'senha' => 'Senha@123'
        ];

        $this->entregadorRepositoryMock->expects($this->once())
            ->method('criarEntregador')
            ->willReturn(0); 

        $resultado = $this->entregadorController->criarEntregador($dados);
        $this->assertNull($resultado); 
    }

    public function testCriarEntregadorLancaExcecaoRetornaNull() {
        $dados = [
            'nome' => 'Entregador Excecao',
            'email' => 'excecao@example.com',
            'telefone' => '11987654321',
            'cnh' => '12345678901',
            'senha' => 'Senha@123'
        ];

        $this->entregadorRepositoryMock->expects($this->once())
            ->method('criarEntregador')
            ->willThrowException(new Exception("Erro de conexão com BD"));

        $resultado = $this->entregadorController->criarEntregador($dados);
        $this->assertNull($resultado);
    }

    // --- Testes para listarEntregadores ---

    public function testListarEntregadoresRetornaArrayDeObjetosEntregador() {
        $dadosRepository = [
            ['idEntregador' => 1, 'desativado' => 0, 'nome' => 'Entregador A', 'email' => 'a@example.com', 'telefone' => '111', 'senha' => 'passA', 'cnh' => '123', 'perfil' => 'motoboy'],
            ['idEntregador' => 2, 'desativado' => 1, 'nome' => 'Entregador B', 'email' => 'b@example.com', 'telefone' => '222', 'senha' => 'passB', 'cnh' => '456', 'perfil' => 'ciclista'],
        ];

        $this->entregadorRepositoryMock->expects($this->once())
            ->method('listarEntregadores')
            ->willReturn($dadosRepository);

        $entregadores = $this->entregadorController->listarEntregadores();

        $this->assertIsArray($entregadores);
        $this->assertCount(2, $entregadores);
        $this->assertInstanceOf(Entregador::class, $entregadores[0]);
        $this->assertEquals('Entregador A', $entregadores[0]->getNome());
        $this->assertEquals('b@example.com', $entregadores[1]->getEmail());
        $this->assertEquals('motoboy', $entregadores[0]->getPerfil());
    }

    public function testListarEntregadoresQuandoNenhumEncontradoRetornaNull() {
        $this->entregadorRepositoryMock->expects($this->once())
            ->method('listarEntregadores')
            ->willReturn(false); 

        $resultado = $this->entregadorController->listarEntregadores();
        $this->assertNull($resultado); 
    }

    // --- Testes para listarEntregadorPorId ---

    public function testListarEntregadorPorIdComIdValidoRetornaObjetoEntregador() {
        $id = 1;
        $dadosRepository = ['idEntregador' => 1, 'desativado' => 0, 'nome' => 'Entregador Teste', 'email' => 'teste@example.com', 'telefone' => '333', 'senha' => 'passTeste', 'cnh' => '12345', 'perfil' => 'motoboy'];

        $this->entregadorRepositoryMock->expects($this->once())
            ->method('listarEntregadorPorId')
            ->with($id)
            ->willReturn($dadosRepository);

        $entregador = $this->entregadorController->listarEntregadorPorId($id);

        $this->assertInstanceOf(Entregador::class, $entregador);
        $this->assertEquals($id, $entregador->getId());
        $this->assertEquals('Entregador Teste', $entregador->getNome());
    }

    public function testListarEntregadorPorIdComIdInvalidoRetornaNull() {
        $id = 'abc'; 

        $this->entregadorRepositoryMock->expects($this->never())
            ->method('listarEntregadorPorId');

        $resultado = $this->entregadorController->listarEntregadorPorId($id);
        $this->assertNull($resultado);
    }

    public function testListarEntregadorPorIdComIdNaoEncontradoRetornaNull() {
        $id = 99;

        $this->entregadorRepositoryMock->expects($this->once())
            ->method('listarEntregadorPorId')
            ->with($id)
            ->willReturn(false);

        $resultado = $this->entregadorController->listarEntregadorPorId($id);
        $this->assertNull($resultado);
    }

    // --- Testes para listarEntregadorPorEmail ---

    public function testListarEntregadorPorEmailComEmailValidoRetornaObjetoEntregador() {
        $email = 'existente@example.com';
        $dadosRepository = ['idEntregador' => 1, 'desativado' => 0, 'nome' => 'Entregador Existente', 'email' => $email, 'telefone' => '444', 'senha' => 'passExistente', 'cnh' => '54321', 'perfil' => 'motoboy'];

        $this->entregadorRepositoryMock->expects($this->once())
            ->method('listarEntregadorPorEmail')
            ->with($email)
            ->willReturn($dadosRepository);

        $entregador = $this->entregadorController->listarEntregadorPorEmail($email);

        $this->assertInstanceOf(Entregador::class, $entregador);
        $this->assertEquals($email, $entregador->getEmail());
        $this->assertEquals('Entregador Existente', $entregador->getNome());
    }

    public function testListarEntregadorPorEmailComEmailInvalidoRetornaNull() {
        $email = 'email_invalido';

        $this->entregadorRepositoryMock->expects($this->never())
            ->method('listarEntregadorPorEmail');

        $resultado = $this->entregadorController->listarEntregadorPorEmail($email);
        $this->assertNull($resultado);
    }

    public function testListarEntregadorPorEmailComEmailNaoEncontradoRetornaNull() {
        $email = 'naoexiste@example.com';

        $this->entregadorRepositoryMock->expects($this->once())
            ->method('listarEntregadorPorEmail')
            ->with($email)
            ->willReturn(false);

        $resultado = $this->entregadorController->listarEntregadorPorEmail($email);
        $this->assertNull($resultado);
    }

    // --- Testes para editarEntregador ---

    public function testEditarEntregadorComDadosValidosRetornaTrue() {
        $dados = [
            'nome' => 'Entregador Editado',
            'email' => 'novo.email@example.com',
            'telefone' => '(11) 98888-7777',
            'cnh' => '09876543210',
            'senha' => 'Senha@123',
            'emailAntigo' => 'antigo@example.com'
        ];

        $entregadorArray = [
            'idEntregador' => 1,
            'desativado' => 0,
            'nome' => 'Nome Antigo',
            'email' => 'antigo@example.com',
            'telefone' => '(11) 98888-7776',
            'senha' => 'Senha@123',
            'cnh' => '12345678910',
            'perfil' => 'ENTR'
        ];

        $this->entregadorRepositoryMock->method('listarEntregadorPorEmail')
            ->with($dados['emailAntigo'])
            ->willReturn($entregadorArray);

        $this->entregadorRepositoryMock->expects($this->once())
            ->method('editarEntregador')
            ->with(
                $dados['emailAntigo'],
                $dados['nome'],
                $dados['email'],
                $dados['telefone'],
                $dados['cnh']
            )
            ->willReturn(true);

        $resultado = $this->entregadorController->editarEntregador($dados);
        $this->assertTrue($resultado);
    }

    public function testEditarEntregadorComDadosInvalidosRetornaFalse() {
        $dados = [
            'nome' => 'Entregador Invalido',
            'email' => 'email_invalido',
            'telefone' => '11988887777',
            'cnh' => '09876543210',
            'emailAntigo' => 'antigo@example.com'
        ];

        $this->entregadorRepositoryMock->expects($this->never())
            ->method('listarEntregadorPorEmail');
        $this->entregadorRepositoryMock->expects($this->never())
            ->method('editarEntregador');

        $resultado = $this->entregadorController->editarEntregador($dados);
        $this->assertFalse($resultado);
    }

    public function testEditarEntregadorNaoEncontradoRetornaFalse() {
        $dados = [
            'nome' => 'Entregador Editado',
            'email' => 'novo.email@example.com',
            'telefone' => '11988887777',
            'cnh' => '09876543210',
            'senha' => 'Senha@123',
            'emailAntigo' => 'naoexiste@example.com'
        ];

        $controllerMock = $this->getMockBuilder(EntregadorController::class)
            ->setConstructorArgs([$this->entregadorRepositoryMock])
            ->onlyMethods(['listarEntregadorPorEmail'])
            ->getMock();

        $controllerMock->expects($this->once())
            ->method('listarEntregadorPorEmail')
            ->with($dados['emailAntigo'])
            ->willReturn(null);

        $this->entregadorRepositoryMock->expects($this->never())
            ->method('editarEntregador');

        $resultado = $controllerMock->editarEntregador($dados);
        $this->assertFalse($resultado);
    }

    public function testEditarEntregadorQuandoRepositorioFalhaRetornaFalse() {
        $dados = [
            'nome' => 'Entregador Editado',
            'email' => 'novo.email@example.com',
            'telefone' => '11988887777',
            'cnh' => '09876543210',
            'senha' => 'Senha@123',
            'emailAntigo' => 'antigo@example.com'
        ];

        $entregadorArray = [
            'idEntregador' => 1,
            'desativado' => 0,
            'nome' => 'Nome Antigo',
            'email' => 'antigo@example.com',
            'telefone' => '11988887776',
            'senha' => 'Senha@123',
            'cnh' => '12345678910',
            'perfil' => 'ENTR'
        ];

        $this->entregadorRepositoryMock->expects($this->once())
            ->method('listarEntregadorPorEmail')
            ->with($dados['emailAntigo'])
            ->willReturn($entregadorArray);

        $this->entregadorRepositoryMock->expects($this->once())
            ->method('editarEntregador')
            ->willReturn(false);

        $resultado = $this->entregadorController->editarEntregador($dados);
        $this->assertFalse($resultado);
    }


    public function testEditarEntregadorLancaExcecaoRetornaFalse() {
        $dados = [
            'nome'        => 'Entregador Excecao',
            'email'       => 'excecao@example.com',
            'telefone'    => '11988887777',
            'cnh'         => '09876543210',
            'senha'       => 'Senha@123',     
            'emailAntigo' => 'antigo@example.com'
        ];

        $this->entregadorRepositoryMock->expects($this->once())
            ->method('listarEntregadorPorEmail')
            ->with($dados['emailAntigo'])
            ->willThrowException(new Exception('Erro inesperado'));

        $resultado = $this->entregadorController->editarEntregador($dados);
        $this->assertFalse($resultado);
    }

    // --- Testes para desativarEntregador ---

    public function testDesativarEntregadorComEmailValidoRetornaTrue() {
        $email = 'desativar@example.com';

        $entregadorAtivo = [
            'idEntregador' => 1,
            'desativado' => 0,
            'nome' => 'Entregador Ativo',
            'email' => $email,
            'telefone' => '(11) 98888-7776',
            'senha' => 'Senha@123',
            'cnh' => '12345678910',
            'perfil' => 'ENTR'
        ];

        $this->entregadorRepositoryMock->expects($this->once())
            ->method('listarEntregadorPorEmail')
            ->with($email)
            ->willReturn($entregadorAtivo);

        $this->entregadorRepositoryMock->expects($this->once())
            ->method('desativarEntregador')
            ->with($email)
            ->willReturn(true);

        $resultado = $this->entregadorController->desativarEntregador($email);
        $this->assertTrue($resultado);
    }

    public function testDesativarEntregadorComEmailInvalidoRetornaFalse() {
        $email = 'email_invalido';

        $this->entregadorRepositoryMock->expects($this->never())
            ->method('listarEntregadorPorEmail');
        $this->entregadorRepositoryMock->expects($this->never())
            ->method('desativarEntregador');

        $resultado = $this->entregadorController->desativarEntregador($email);
        $this->assertNull($resultado);
    }

    public function testDesativarEntregadorNaoEncontradoRetornaFalse() {
        $email = 'naoexiste@example.com';

        $this->entregadorRepositoryMock->expects($this->once())
            ->method('listarEntregadorPorEmail')
            ->with($email)
            ->willReturn(null);

        $this->entregadorRepositoryMock->expects($this->never())
            ->method('desativarEntregador');

        $resultado = $this->entregadorController->desativarEntregador($email);
        $this->assertFalse($resultado);
    }

    public function testDesativarEntregadorQuandoRepositorioFalhaRetornaFalse() {
        $email = 'falha@example.com';
        $entregadorArray = [
            'idEntregador' => 1,
            'desativado' => 0,
            'nome' => 'Entregador Falha',
            'email' => $email,
            'telefone' => '(11) 98888-7776',
            'senha' => 'Senha@123',
            'cnh' => '12345678910',
            'perfil' => 'ENTR'
        ];

        $this->entregadorRepositoryMock->method('listarEntregadorPorEmail')
            ->with($email)
            ->willReturn($entregadorArray);

        $this->entregadorRepositoryMock->expects($this->once())
            ->method('desativarEntregador')
            ->with($email)
            ->willReturn(false); 

        $resultado = $this->entregadorController->desativarEntregador($email);
        $this->assertFalse($resultado);
    }

}