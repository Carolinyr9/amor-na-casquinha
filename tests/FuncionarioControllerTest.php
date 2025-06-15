<?php

use PHPUnit\Framework\TestCase;
use app\controller\FuncionarioController;
use app\repository\FuncionarioRepository;
use app\model\Funcionario;
use app\utils\helpers\Logger;

require_once __DIR__ . '/../app/utils/helpers/Logger.php';

class FuncionarioControllerTest extends TestCase {
    private $funcionarioRepositoryMock;
    private $funcionarioController;

    protected function setUp(): void {
        $this->funcionarioRepositoryMock = $this->createMock(FuncionarioRepository::class);
        $this->funcionarioController = new FuncionarioController($this->funcionarioRepositoryMock);
    }

    // --- Testes para listarFuncionario ---

    public function testListarFuncionarioRetornaArrayDeObjetosFuncionario() {
        $funcionario1 = new Funcionario(1, 0, 0, 'Func 1', '123456789', 'func1@email.com', 'Senha@123', 100);
        $funcionario2 = new Funcionario(2, 1, 0, 'Func 2', '987654321', 'func2@email.com', 'Senha@456', 101);

        $this->funcionarioRepositoryMock->expects($this->once())
            ->method('buscarFuncionarios')
            ->willReturn([$funcionario1, $funcionario2]);

        $funcionarios = $this->funcionarioController->listarFuncionario();

        $this->assertIsArray($funcionarios);
        $this->assertCount(2, $funcionarios);
        $this->assertInstanceOf(Funcionario::class, $funcionarios[0]);
        $this->assertEquals('Func 1', $funcionarios[0]->getNome());
        $this->assertEquals('func2@email.com', $funcionarios[1]->getEmail());$this->assertEquals(0, $funcionarios[0]->getDesativado());
        $this->assertEquals(1, $funcionarios[1]->getDesativado());
    }

    public function testListarFuncionarioComNenhumDadoRetornaArrayVazio() {
        $this->funcionarioRepositoryMock->expects($this->once())
            ->method('buscarFuncionarios')
            ->willReturn([]);

        $funcionarios = $this->funcionarioController->listarFuncionario();

        $this->assertIsArray($funcionarios);
        $this->assertEmpty($funcionarios);
    }

    public function testListarFuncionarioLancaExcecaoRetornaNull() {
        $this->funcionarioRepositoryMock->expects($this->once())
            ->method('buscarFuncionarios')
            ->willThrowException(new Exception("Erro no banco de dados"));

        $resultado = $this->funcionarioController->listarFuncionario();
        $this->assertNull($resultado);
    }

    // --- Testes para buscarFuncionarioPorEmail ---

    public function testBuscarFuncionarioPorEmailComEmailValidoRetornaFuncionario() {
        $email = 'valido@example.com';
        $funcionarioMock = new Funcionario(1, 0, 0, 'Funcionario', '(11) 98888-7777', $email, 'Senha@123', 100);

        $this->funcionarioRepositoryMock->expects($this->once())
            ->method('buscarFuncionarioPorEmail')
            ->with($email)
            ->willReturn($funcionarioMock);

        $resultado = $this->funcionarioController->buscarFuncionarioPorEmail($email);
        $this->assertInstanceOf(Funcionario::class, $resultado);
        $this->assertEquals($email, $resultado->getEmail());
    }

    public function testBuscarFuncionarioPorEmailComEmailInvalidoRetornaNull() {
        $email = 'email_invalido'; 

        $this->funcionarioRepositoryMock->expects($this->never()) 
            ->method('buscarFuncionarioPorEmail');

        $resultado = $this->funcionarioController->buscarFuncionarioPorEmail($email);
        $this->assertNull($resultado); 
    }

    public function testBuscarFuncionarioPorEmailComFuncionarioNaoEncontradoRetornaNull() {
        $email = 'naoexiste@example.com';

        $this->funcionarioRepositoryMock->expects($this->once())
            ->method('buscarFuncionarioPorEmail')
            ->with($email)
            ->willReturn(null);

        $resultado = $this->funcionarioController->buscarFuncionarioPorEmail($email);
        $this->assertNull($resultado);
    }

    public function testBuscarFuncionarioPorEmailLancaExcecaoRetornaNull() {
        $email = 'erro@example.com';

        $this->funcionarioRepositoryMock->expects($this->once())
            ->method('buscarFuncionarioPorEmail')
            ->with($email)
            ->willThrowException(new Exception("Erro de conexão"));

        $resultado = $this->funcionarioController->buscarFuncionarioPorEmail($email);
        $this->assertNull($resultado);
    }

    // --- Testes para criarFuncionario ---

    public function testCriarFuncionarioComDadosValidosRetornaObjetoFuncionario() {
        $dados = [
            'nome' => 'Novo Func',
            'email' => 'novo@example.com',
            'telefone' => '999999999',
            'senha' => 'senha123',
            'adm' => 0,
            'idEndereco' => 200
        ];

        $this->funcionarioRepositoryMock->expects($this->once())
            ->method('criarFuncionario')
            ->with(
                $dados['nome'],
                $dados['email'],
                $dados['telefone'],
                $dados['senha'],
                $dados['adm'],
                $dados['idEndereco']
            )
            ->willReturn(5); 

        $resultado = $this->funcionarioController->criarFuncionario($dados);
        $this->assertInstanceOf(Funcionario::class, $resultado);
        $this->assertEquals(5, $resultado->getId());
        $this->assertEquals($dados['email'], $resultado->getEmail());
    }

    public function testCriarFuncionarioComDadosInvalidosRetornaFalse() {
        $dados = [
            'nome' => 'Func Inválido',
            'email' => 'email_invalido',
            'telefone' => '999',
            'senha' => '123',
            'adm' => 0,
            'idEndereco' => 200
        ];

        $this->funcionarioRepositoryMock->expects($this->never())
            ->method('criarFuncionario');

        $resultado = $this->funcionarioController->criarFuncionario($dados);
        $this->assertFalse($resultado);
    }

    public function testCriarFuncionarioComDadosFaltandoRetornaFalse() {
        $dados = [
            'nome' => 'Func Faltando',
            'email' => 'faltando@example.com',
            'senha' => '123',
            'adm' => 0,
            'idEndereco' => 200
        ];

        $this->funcionarioRepositoryMock->expects($this->never())
            ->method('criarFuncionario');

        $resultado = $this->funcionarioController->criarFuncionario($dados);
        $this->assertFalse($resultado);
    }
    
    public function testCriarFuncionarioQuandoRepositorioFalhaRetornaFalse() {
        $dados = [
            'nome' => 'Func Falha Repositorio',
            'email' => 'falha@example.com',
            'telefone' => '999',
            'senha' => '123',
            'adm' => 0,
            'idEndereco' => 200
        ];

        $this->funcionarioRepositoryMock->expects($this->once())
            ->method('criarFuncionario')
            ->willReturn(0);

        $resultado = $this->funcionarioController->criarFuncionario($dados);
        $this->assertFalse($resultado);
    }

    public function testCriarFuncionarioLancaExcecaoRetornaFalse() {
        $dados = [
            'nome' => 'Func Excecao',
            'email' => 'excecao@example.com',
            'telefone' => '999',
            'senha' => '123',
            'adm' => 0,
            'idEndereco' => 200
        ];

        $this->funcionarioRepositoryMock->expects($this->once())
            ->method('criarFuncionario')
            ->willThrowException(new Exception("Erro de gravação"));

        $resultado = $this->funcionarioController->criarFuncionario($dados);
        $this->assertFalse($resultado);
    }

    // --- Testes para editarFuncionario ---

    public function testEditarFuncionarioComDadosValidosRetornaTrue() {
        $dados = [
            'nome' => 'Func Editado',
            'email' => 'novoemail@example.com',
            'telefone' => '555555555',
            'emailAntigo' => 'antigo@example.com'
        ];

        $funcionarioOriginal = new Funcionario(1, 'Original', '111', 'antigo@example.com', 'senha', 0, 0, 100);

        $this->funcionarioRepositoryMock->expects($this->once())
            ->method('buscarFuncionarioPorEmail')
            ->with($dados['emailAntigo'])
            ->willReturn($funcionarioOriginal);

        $this->funcionarioRepositoryMock->expects($this->once())
            ->method('editarFuncionario')
            ->with(
                $dados['emailAntigo'],
                $dados['nome'],
                $dados['email'],
                $dados['telefone']
            )
            ->willReturn(true);

        $resultado = $this->funcionarioController->editarFuncionario($dados);
        $this->assertTrue($resultado);
    }

    public function testEditarFuncionarioComDadosInvalidosRetornaFalse() {
        $dados = [
            'nome' => 'Func Invalido',
            'email' => 'email_invalido',
            'telefone' => '555',
            'emailAntigo' => 'antigo@example.com'
        ];

        $this->funcionarioRepositoryMock->expects($this->never())
            ->method('buscarFuncionarioPorEmail');
        $this->funcionarioRepositoryMock->expects($this->never())
            ->method('editarFuncionario');

        $resultado = $this->funcionarioController->editarFuncionario($dados);
        $this->assertFalse($resultado);
    }

    public function testEditarFuncionarioNaoEncontradoRetornaNull() {
        $dados = [
            'nome' => 'Func Editado',
            'email' => 'novoemail@example.com',
            'telefone' => '555555555',
            'emailAntigo' => 'naoexiste@example.com'
        ];

        $this->funcionarioRepositoryMock->expects($this->once())
            ->method('buscarFuncionarioPorEmail')
            ->with($dados['emailAntigo'])
            ->willReturn(null);

        $this->funcionarioRepositoryMock->expects($this->never())
            ->method('editarFuncionario');

        $resultado = $this->funcionarioController->editarFuncionario($dados);
        $this->assertNull($resultado);
    }

    public function testEditarFuncionarioQuandoRepositorioFalhaRetornaNull() {
        $dados = [
            'nome' => 'Func Editado',
            'email' => 'novoemail@example.com',
            'telefone' => '555555555',
            'emailAntigo' => 'antigo@example.com'
        ];

        $funcionarioOriginal = new Funcionario(1, 'Original', '111', 'antigo@example.com', 'senha', 0, 0, 100);

        $this->funcionarioRepositoryMock->expects($this->once())
            ->method('buscarFuncionarioPorEmail')
            ->with($dados['emailAntigo'])
            ->willReturn($funcionarioOriginal);

        $this->funcionarioRepositoryMock->expects($this->once())
            ->method('editarFuncionario')
            ->willReturn(false);

        $resultado = $this->funcionarioController->editarFuncionario($dados);
        $this->assertNull($resultado);
    }

    public function testEditarFuncionarioLancaExcecaoRetornaNull() {
        $dados = [
            'nome' => 'Func Excecao',
            'email' => 'excecao@example.com',
            'telefone' => '555555555',
            'emailAntigo' => 'antigo@example.com'
        ];

        $this->funcionarioRepositoryMock->expects($this->once())
            ->method('buscarFuncionarioPorEmail')
            ->willThrowException(new Exception("Erro de rede"));

        $resultado = $this->funcionarioController->editarFuncionario($dados);
        $this->assertNull($resultado);
    }

    // --- Testes para desativarFuncionario ---

    public function testDesativarFuncionarioComEmailValidoRetornaTrue() {
        $email = 'desativar@example.com';
        $funcionarioAtivo = new Funcionario(1, 'Ativo', '123', $email, 'senha', 0, 0, 100);

        $this->funcionarioRepositoryMock->expects($this->once())
            ->method('buscarFuncionarioPorEmail')
            ->with($email)
            ->willReturn($funcionarioAtivo);

        $this->funcionarioRepositoryMock->expects($this->once())
            ->method('desativarFuncionario')
            ->with($email)
            ->willReturn(true);

        $resultado = $this->funcionarioController->desativarFuncionario($email);
        $this->assertTrue($resultado);
    }

    public function testDesativarFuncionarioComEmailInvalidoRetornaNull() {
        $email = 'email_invalido';

        $this->funcionarioRepositoryMock->expects($this->never())
            ->method('buscarFuncionarioPorEmail');
        $this->funcionarioRepositoryMock->expects($this->never())
            ->method('desativarFuncionario');

        $resultado = $this->funcionarioController->desativarFuncionario($email);
        $this->assertNull($resultado); 
    }

    public function testDesativarFuncionarioNaoEncontradoRetornaNull() {
        $email = 'naoexiste@example.com';

        $this->funcionarioRepositoryMock->expects($this->once())
            ->method('buscarFuncionarioPorEmail')
            ->with($email)
            ->willReturn(null);

        $this->funcionarioRepositoryMock->expects($this->never())
            ->method('desativarFuncionario');

        $resultado = $this->funcionarioController->desativarFuncionario($email);
        $this->assertNull($resultado); 
    }

    public function testDesativarFuncionarioQuandoRepositorioFalhaRetornaNull() {
        $email = 'falha@example.com';
        $funcionario = new Funcionario(1, 'Funcionario', '123', $email, 'senha', 0, 0, 100);

        $this->funcionarioRepositoryMock->expects($this->once())
            ->method('buscarFuncionarioPorEmail')
            ->with($email)
            ->willReturn($funcionario);

        $this->funcionarioRepositoryMock->expects($this->once())
            ->method('desativarFuncionario')
            ->willReturn(false); 

        $resultado = $this->funcionarioController->desativarFuncionario($email);
        $this->assertNull($resultado);     
    }

    public function testDesativarFuncionarioLancaExcecaoRetornaNull() {
        $email = 'excecao@example.com';

        $this->funcionarioRepositoryMock->expects($this->once())
            ->method('buscarFuncionarioPorEmail')
            ->willThrowException(new Exception("Erro de segurança"));

        $resultado = $this->funcionarioController->desativarFuncionario($email);
        $this->assertNull($resultado);
    }
}