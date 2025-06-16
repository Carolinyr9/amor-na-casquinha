<?php

use PHPUnit\Framework\TestCase;
use app\controller\ClienteController;
use app\repository\ClienteRepository;
use app\model\Cliente;
use app\utils\helpers\Logger;

require_once __DIR__ . '/../app/utils/helpers/Logger.php';

class ClienteControllerTest extends TestCase {
    private $clienteRepositoryMock;
    private $clienteController;

    protected function setUp(): void {
        $this->clienteRepositoryMock = $this->createMock(ClienteRepository::class);
        $this->clienteController = new ClienteController($this->clienteRepositoryMock);
    }

    // --- Testes para criarCliente ---

    public function testCriarClienteComDadosValidosRetornaIdDoCliente() {
        $dados = [
            'nome' => 'Novo Cliente',
            'email' => 'novo@example.com',
            'senha' => 'senha123',
            'telefone' => '11999999999',
            'idEndereco' => 1
        ];

        $this->clienteRepositoryMock->expects($this->once())
            ->method('criarCliente')
            ->with(
                $dados['nome'],
                $dados['email'],
                $dados['senha'],
                $dados['telefone'],
                $dados['idEndereco']
            )
            ->willReturn(1);

        $resultado = $this->clienteController->criarCliente($dados);
        $this->assertEquals(1, $resultado);
    }

    public function testCriarClienteComEmailInvalidoRetornaFalse() {
        $dados = [
            'nome' => 'Cliente Inválido',
            'email' => 'email_invalido',
            'senha' => 'senha123',
            'telefone' => '11999999999',
            'idEndereco' => 1
        ];

        $this->clienteRepositoryMock->expects($this->never())
            ->method('criarCliente');

        $resultado = $this->clienteController->criarCliente($dados);
        $this->assertFalse($resultado);
    }

    public function testCriarClienteComDadosFaltandoRetornaFalse() {
        $dados = [
            'nome' => 'Cliente Faltando Dados',
            'email' => 'faltando@example.com',
            'senha' => 'senha123',
            'idEndereco' => 1
        ];

        $this->clienteRepositoryMock->expects($this->never())
            ->method('criarCliente');

        $resultado = $this->clienteController->criarCliente($dados);
        $this->assertFalse($resultado);
    }

    public function testCriarClienteQuandoRepositorioFalhaRetornaFalse() {
        $dados = [
            'nome' => 'Cliente Falha Repositorio',
            'email' => 'falha@example.com',
            'senha' => 'senha123',
            'telefone' => '11999999999',
            'idEndereco' => 1
        ];

        $this->clienteRepositoryMock->expects($this->once())
            ->method('criarCliente')
            ->willReturn(0);

        $resultado = $this->clienteController->criarCliente($dados);
        $this->assertFalse($resultado);
    }

    public function testCriarClienteLancaExcecaoRetornaFalse() {
        $dados = [
            'nome' => 'Cliente Excecao',
            'email' => 'excecao@example.com',
            'senha' => 'senha123',
            'telefone' => '11999999999',
            'idEndereco' => 1
        ];

        $this->clienteRepositoryMock->expects($this->once())
            ->method('criarCliente')
            ->willThrowException(new Exception("Erro de banco de dados"));

        $resultado = $this->clienteController->criarCliente($dados);
        $this->assertFalse($resultado);
    }

    // --- Testes para listarClientePorEmail ---

    public function testListarClientePorEmailComEmailExistenteRetornaCliente() {
        $email = 'cliente_existente@example.com';
        $dadosCliente = [
            'idCliente' => 1,
            'nome' => 'Cliente Teste',
            'email' => $email,
            'telefone' => '11987654321',
            'senha' => 'senhaHash',
            'idEndereco' => 1
        ];

        $this->clienteRepositoryMock->expects($this->once())
            ->method('listarClientePorEmail')
            ->with($email)
            ->willReturn($dadosCliente);

        $cliente = $this->clienteController->listarClientePorEmail($email);

        $this->assertInstanceOf(Cliente::class, $cliente);
        $this->assertEquals($email, $cliente->getEmail());
        $this->assertEquals($dadosCliente['nome'], $cliente->getNome());
    }

    public function testListarClientePorEmailComEmailInexistenteRetornaFalse() {
        $email = 'nao_existe@example.com';

        $this->clienteRepositoryMock->expects($this->once())
            ->method('listarClientePorEmail')
            ->with($email)
            ->willReturn(null);

        $resultado = $this->clienteController->listarClientePorEmail($email);
        $this->assertFalse($resultado);
    }

    public function testListarClientePorEmailComEmailVazioRetornaFalse() {
        $email = '';

        $this->clienteRepositoryMock->expects($this->never())
            ->method('listarClientePorEmail');

        $resultado = $this->clienteController->listarClientePorEmail($email);
        $this->assertFalse($resultado);
    }

    public function testListarClientePorEmailLancaExcecaoRetornaFalse() {
        $email = 'erro@example.com';

        $this->clienteRepositoryMock->expects($this->once())
            ->method('listarClientePorEmail')
            ->with($email)
            ->willThrowException(new Exception("Erro de conexão"));

        $resultado = $this->clienteController->listarClientePorEmail($email);
        $this->assertFalse($resultado);
    }

    // --- Testes para editarCliente ---

    public function testEditarClienteComDadosValidosRetornaTrue() {
        $dados = [
            'nome' => 'Cliente Editado',
            'email' => 'editado@example.com',
            'telefone' => '(11) 98888-7777',
            'emailAntigo' => 'original@example.com'
        ];

        $clienteOriginal = new Cliente(1, 'Original', 'original@example.com', '11999999999', 'senha', 1);

        $this->clienteRepositoryMock->expects($this->once())
            ->method('listarClientePorEmail')
            ->with($dados['emailAntigo'])
            ->willReturn([
                'idCliente' => $clienteOriginal->getId(),
                'nome' => $clienteOriginal->getNome(),
                'email' => $clienteOriginal->getEmail(),
                'telefone' => $clienteOriginal->getTelefone(),
                'senha' => $clienteOriginal->getSenha(),
                'idEndereco' => $clienteOriginal->getIdEndereco()
            ]);

        $this->clienteRepositoryMock->expects($this->once())
            ->method('editarCliente')
            ->with(
                $dados['nome'],
                $dados['telefone'],
                $dados['email'],
                $dados['emailAntigo']
            )
            ->willReturn(true);

        $resultado = $this->clienteController->editarCliente($dados);
        $this->assertTrue($resultado);
    }

    public function testEditarClienteComDadosInvalidosRetornaFalse() {
        $dados = [
            'nome' => 'Cliente Invalido',
            'email' => 'email_invalido',
            'telefone' => '(11) 98888-7777',
            'emailAntigo' => 'original@example.com'
        ];

        $this->clienteRepositoryMock->expects($this->never())
            ->method('listarClientePorEmail');
        $this->clienteRepositoryMock->expects($this->never())
            ->method('editarCliente');

        $resultado = $this->clienteController->editarCliente($dados);
        $this->assertFalse($resultado);
    }

    public function testEditarClienteNaoEncontradoRetornaFalse() {
        $dados = [
            'nome' => 'Cliente Editado',
            'email' => 'editado@example.com',
            'telefone' => '(11) 98888-7777',
            'emailAntigo' => 'nao_existe@example.com'
        ];

        $this->clienteRepositoryMock->expects($this->once())
            ->method('listarClientePorEmail')
            ->with($dados['emailAntigo'])
            ->willReturn(false);

        $this->clienteRepositoryMock->expects($this->never())
            ->method('editarCliente');

        $resultado = $this->clienteController->editarCliente($dados);
        $this->assertFalse($resultado);
    }

    public function testEditarClienteQuandoRepositorioFalhaRetornaFalse() {
        $dados = [
            'nome' => 'Cliente Editado',
            'email' => 'editado@example.com',
            'telefone' => '(11) 98888-7777',
            'emailAntigo' => 'original@example.com'
        ];

        $clienteOriginal = new Cliente(1, 'Original', 'original@example.com', '(11) 98888-7777', 'senha', 1);

        $this->clienteRepositoryMock->expects($this->once())
            ->method('listarClientePorEmail')
            ->with($dados['emailAntigo'])
            ->willReturn([
                'idCliente' => $clienteOriginal->getId(),
                'nome' => $clienteOriginal->getNome(),
                'email' => $clienteOriginal->getEmail(),
                'telefone' => $clienteOriginal->getTelefone(),
                'senha' => $clienteOriginal->getSenha(),
                'idEndereco' => $clienteOriginal->getIdEndereco()
            ]);

        $this->clienteRepositoryMock->expects($this->once())
            ->method('editarCliente')
            ->willReturn(false);

        $resultado = $this->clienteController->editarCliente($dados);
        $this->assertFalse($resultado);
    }

    public function testEditarClienteLancaExcecaoRetornaFalse() {
        $dados = [
            'nome' => 'Cliente Excecao',
            'email' => 'excecao@example.com',
            'telefone' => '(11) 98888-7777',
            'emailAntigo' => 'original@example.com'
        ];

        $this->clienteRepositoryMock->expects($this->once())
            ->method('listarClientePorEmail')
            ->willThrowException(new Exception("Erro inesperado"));

        $resultado = $this->clienteController->editarCliente($dados);
        $this->assertFalse($resultado);
    }

    // --- Testes para alterarSenha ---

    public function testAlterarSenhaComDadosValidosRetornaTrue() {
        $dados = [
            'senhaNova' => 'novaSenha123',
            'senhaAtual' => 'senhaAntiga',
            'idCliente' => 1
        ];

        $this->clienteRepositoryMock->expects($this->once())
            ->method('editarSenha')
            ->with(
                $dados['senhaNova'],
                $dados['senhaAtual'],
                $dados['idCliente']
            )
            ->willReturn(true);

        $resultado = $this->clienteController->alterarSenha($dados);
        $this->assertTrue($resultado);
    }

    public function testAlterarSenhaComDadosInvalidosRetornaFalse() {
        $dados = [
            'senhaNova' => 'novaSenha',
            'idCliente' => 1
        ];

        $this->clienteRepositoryMock->expects($this->never())
            ->method('editarSenha');

        $resultado = $this->clienteController->alterarSenha($dados);
        $this->assertFalse($resultado);
    }

    public function testAlterarSenhaQuandoRepositorioFalhaRetornaFalse() {
        $dados = [
            'senhaNova' => 'novaSenha123',
            'senhaAtual' => 'senhaAntiga',
            'idCliente' => 1
        ];

        $this->clienteRepositoryMock->expects($this->once())
            ->method('editarSenha')
            ->willReturn(false);

        $resultado = $this->clienteController->alterarSenha($dados);
        $this->assertFalse($resultado);
    }

    public function testAlterarSenhaLancaExcecaoRetornaFalse() {
        $dados = [
            'senhaNova' => 'novaSenha123',
            'senhaAtual' => 'senhaAntiga',
            'idCliente' => 1
        ];

        $this->clienteRepositoryMock->expects($this->once())
            ->method('editarSenha')
            ->willThrowException(new Exception("Erro de banco"));

        $resultado = $this->clienteController->alterarSenha($dados);
        $this->assertFalse($resultado);
    }

    // --- Testes para desativarPerfil ---

    public function testDesativarPerfilComEmailExistenteRetornaTrue() {
        $email = 'cliente_para_desativar@example.com';
        $dadosCliente = [
            'idCliente' => 1,
            'nome' => 'Cliente Desativar',
            'email' => $email,
            'telefone' => '11987654321',
            'senha' => 'senhaHash',
            'idEndereco' => 1
        ];

        $this->clienteRepositoryMock->expects($this->once())
            ->method('listarClientePorEmail')
            ->with($email)
            ->willReturn($dadosCliente);

        $this->clienteRepositoryMock->expects($this->once())
            ->method('desativarPerfil')
            ->with($email)
            ->willReturn(true);

        $resultado = $this->clienteController->desativarPerfil($email);
        $this->assertTrue($resultado);
    }

    public function testDesativarPerfilComEmailInexistenteRetornaFalse() {
        $email = 'nao_existe@example.com';

        $this->clienteRepositoryMock->expects($this->once())
            ->method('listarClientePorEmail')
            ->with($email)
            ->willReturn(false);

        $this->clienteRepositoryMock->expects($this->never())
            ->method('desativarPerfil');

        $resultado = $this->clienteController->desativarPerfil($email);
        $this->assertFalse($resultado);
    }

    public function testDesativarPerfilComEmailVazioRetornaFalse() {
        $email = '';

        $this->clienteRepositoryMock->expects($this->never())
            ->method('listarClientePorEmail');
        $this->clienteRepositoryMock->expects($this->never())
            ->method('desativarPerfil');

        $resultado = $this->clienteController->desativarPerfil($email);
        $this->assertFalse($resultado);
    }

    public function testDesativarPerfilQuandoRepositorioFalhaRetornaFalse() {
        $email = 'cliente_falha_desativar@example.com';
        $dadosCliente = [
            'idCliente' => 1,
            'nome' => 'Cliente Falha Desativar',
            'email' => $email,
            'telefone' => '11987654321',
            'senha' => 'senhaHash',
            'idEndereco' => 1
        ];

        $this->clienteRepositoryMock->expects($this->once())
            ->method('listarClientePorEmail')
            ->with($email)
            ->willReturn($dadosCliente);

        $this->clienteRepositoryMock->expects($this->once())
            ->method('desativarPerfil')
            ->with($email)
            ->willReturn(false);

        $resultado = $this->clienteController->desativarPerfil($email);
        $this->assertFalse($resultado);
    }

    public function testDesativarPerfilLancaExcecaoRetornaFalse() {
        $email = 'excecao@example.com';

        $this->clienteRepositoryMock->expects($this->once())
            ->method('listarClientePorEmail')
            ->willThrowException(new Exception("Erro de rede"));

        $resultado = $this->clienteController->desativarPerfil($email);
        $this->assertFalse($resultado);
    }

    // --- Testes para listarClientes ---

    public function testListarClientesComDadosRetornaArrayDeClientes() {
        $dadosRetornoRepository = [
            ['idCliente' => 1, 'nome' => 'Cliente A', 'email' => 'a@example.com', 'telefone' => '111', 'senha' => '123', 'idEndereco' => 1],
            ['idCliente' => 2, 'nome' => 'Cliente B', 'email' => 'b@example.com', 'telefone' => '222', 'senha' => '456', 'idEndereco' => 2],
        ];

        $this->clienteRepositoryMock->expects($this->once())
            ->method('listarClientes')
            ->willReturn($dadosRetornoRepository);

        $clientes = $this->clienteController->listarClientes();

        $this->assertIsArray($clientes);
        $this->assertCount(2, $clientes);
        $this->assertInstanceOf(Cliente::class, $clientes[0]);
        $this->assertEquals('Cliente A', $clientes[0]->getNome());
        $this->assertEquals('Cliente B', $clientes[1]->getNome());
    }

    public function testListarClientesSemDadosRetornaFalse() {
        $this->clienteRepositoryMock->expects($this->once())
            ->method('listarClientes')
            ->willReturn([]); 

        $resultado = $this->clienteController->listarClientes();
        $this->assertFalse($resultado);
    }

    public function testListarClientesLancaExcecaoRetornaFalse() {
        $this->clienteRepositoryMock->expects($this->once())
            ->method('listarClientes')
            ->willThrowException(new Exception("Erro de servidor"));

        $resultado = $this->clienteController->listarClientes();
        $this->assertFalse($resultado);
    }
}