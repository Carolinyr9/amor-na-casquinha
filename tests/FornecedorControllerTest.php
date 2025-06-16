<?php
use PHPUnit\Framework\TestCase;
use app\controller\FornecedorController;
use app\model\Fornecedor;
use app\repository\FornecedorRepository;

require_once __DIR__ . '/../app/utils/helpers/Logger.php';


class FornecedorControllerTest extends TestCase {
    private $fornecedorRepositoryMock;
    private $fornecedorController;

    protected function setUp(): void {
        $this->fornecedorRepositoryMock = $this->createMock(FornecedorRepository::class);
        $this->fornecedorController = new FornecedorController($this->fornecedorRepositoryMock);
    }

    // --- Testes para listarFornecedor ---

    public function testListarFornecedorRetornaArrayDeFornecedores() {
        $dadosRepository = [
            [
                'idFornecedor' => 1,
                'nome' => 'Fornecedor A',
                'telefone' => '11987654321',
                'email' => 'fornecedorA@example.com',
                'cnpj' => '12345678000100',
                'desativado' => 0,
                'idEndereco' => 101
            ],
            [
                'idFornecedor' => 2,
                'nome' => 'Fornecedor B',
                'telefone' => '11912345678',
                'email' => 'fornecedorB@example.com',
                'cnpj' => '87654321000111',
                'desativado' => 1,
                'idEndereco' => 102
            ],
        ];

        $this->fornecedorRepositoryMock->method('listarFornecedor')
            ->willReturn($dadosRepository);

        $fornecedores = $this->fornecedorController->listarFornecedor();

        $this->assertIsArray($fornecedores);
        $this->assertCount(2, $fornecedores);
        $this->assertInstanceOf(Fornecedor::class, $fornecedores[0]);
        $this->assertEquals('Fornecedor A', $fornecedores[0]->getNome());
        $this->assertEquals(true, $fornecedores[0]->getDesativado());
        $this->assertEquals(false, $fornecedores[1]->getDesativado());

    }

    public function testListarFornecedorRetornaArrayVazioQuandoNaoHaFornecedores() {
        $this->fornecedorRepositoryMock->method('listarFornecedor')
            ->willReturn([]);

        $fornecedores = $this->fornecedorController->listarFornecedor();

        $this->assertIsArray($fornecedores);
        $this->assertEmpty($fornecedores);
    }

    public function testListarFornecedorLidaComExcecaoDoRepositorio() {
        $this->fornecedorRepositoryMock->method('listarFornecedor')
            ->willThrowException(new Exception("Erro de conexão com o banco de dados"));

        $fornecedores = $this->fornecedorController->listarFornecedor();
        $this->assertNull($fornecedores);
    }

    // --- Testes para buscarFornecedorPorEmail ---

    public function testBuscarFornecedorPorEmailRetornaFornecedorValido() {
        $email = 'fornecedor@example.com';
        $fornecedorEsperado = new Fornecedor(1, 'Fornecedor Teste', '(11) 99999-9999', $email, '00000000000100', false, 1);

        $this->fornecedorRepositoryMock->method('buscarFornecedorPorEmail')
            ->with($email)
            ->willReturn($fornecedorEsperado);

        $fornecedorRetornado = $this->fornecedorController->buscarFornecedorPorEmail($email);

        $this->assertInstanceOf(Fornecedor::class, $fornecedorRetornado);
        $this->assertEquals($email, $fornecedorRetornado->getEmail());
    }

    public function testBuscarFornecedorPorEmailRetornaNullParaEmailInvalido() {
        $emailInvalido = 'email-invalido';
        $fornecedorRetornado = $this->fornecedorController->buscarFornecedorPorEmail($emailInvalido);
        $this->assertNull($fornecedorRetornado);
    }

    public function testBuscarFornecedorPorEmailRetornaNullQuandoNaoEncontrado() {
        $email = 'naoencontrado@example.com';
        $this->fornecedorRepositoryMock->method('buscarFornecedorPorEmail')
            ->with($email)
            ->willReturn(null);

        $fornecedorRetornado = $this->fornecedorController->buscarFornecedorPorEmail($email);
        $this->assertNull($fornecedorRetornado);
    }

    public function testBuscarFornecedorPorEmailLidaComExcecaoDoRepositorio() {
        $email = 'fornecedor@example.com';
        $this->fornecedorRepositoryMock->method('buscarFornecedorPorEmail')
            ->willThrowException(new Exception("Erro de banco de dados"));

        $fornecedorRetornado = $this->fornecedorController->buscarFornecedorPorEmail($email);
        $this->assertNull($fornecedorRetornado);
    }

    // --- Testes para criarFornecedor ---

    public function testCriarFornecedorComDadosValidosRetornaFornecedor() {
        $dados = [
            'nome' => 'Novo Fornecedor',
            'email' => 'novo@example.com',
            'telefone' => '11988887777',
            'cnpj' => '11223344000155',
            'idEndereco' => 200
        ];
        $idGerado = 3;

        $this->fornecedorRepositoryMock->method('criarFornecedor')
            ->with($dados['nome'], $dados['email'], $dados['telefone'], $dados['cnpj'], $dados['idEndereco'])
            ->willReturn($idGerado);

        $fornecedorCriado = $this->fornecedorController->criarFornecedor($dados);

        $this->assertInstanceOf(Fornecedor::class, $fornecedorCriado);
        $this->assertEquals($idGerado, $fornecedorCriado->getId());
        $this->assertEquals($dados['nome'], $fornecedorCriado->getNome());
        $this->assertEquals($dados['email'], $fornecedorCriado->getEmail());
        $this->assertEquals($dados['telefone'], $fornecedorCriado->getTelefone());
        $this->assertEquals($dados['cnpj'], $fornecedorCriado->getCnpj());
        $this->assertEquals(false, $fornecedorCriado->getDesativado());
        $this->assertEquals($dados['idEndereco'], $fornecedorCriado->getEndereco());
    }

    public function testCriarFornecedorComDadosInvalidosRetornaFalse() {
        $dadosNomeVazio = [
            'nome' => '',
            'email' => 'novo@example.com',
            'telefone' => '11988887777',
            'cnpj' => '11223344000155',
            'idEndereco' => 200
        ];
        $this->assertFalse($this->fornecedorController->criarFornecedor($dadosNomeVazio));

        $dadosEmailInvalido = [
            'nome' => 'Novo Fornecedor',
            'email' => 'email-invalido',
            'telefone' => '11988887777',
            'cnpj' => '11223344000155',
            'idEndereco' => 200
        ];
        $this->assertFalse($this->fornecedorController->criarFornecedor($dadosEmailInvalido));

        $dadosEmailVazio = [
            'nome' => 'Novo Fornecedor',
            'email' => '',
            'telefone' => '11988887777',
            'cnpj' => '11223344000155',
            'idEndereco' => 200
        ];
        $this->assertFalse($this->fornecedorController->criarFornecedor($dadosEmailVazio));

        $dadosTelefoneVazio = [
            'nome' => 'Novo Fornecedor',
            'email' => 'novo@example.com',
            'telefone' => '',
            'cnpj' => '11223344000155',
            'idEndereco' => 200
        ];
        $this->assertFalse($this->fornecedorController->criarFornecedor($dadosTelefoneVazio));

        $dadosCnpjVazio = [
            'nome' => 'Novo Fornecedor',
            'email' => 'novo@example.com',
            'telefone' => '11988887777',
            'cnpj' => '',
            'idEndereco' => 200
        ];
        $this->assertFalse($this->fornecedorController->criarFornecedor($dadosCnpjVazio));

        $dadosIdEnderecoVazio = [
            'nome' => 'Novo Fornecedor',
            'email' => 'novo@example.com',
            'telefone' => '11988887777',
            'cnpj' => '11223344000155',
            'idEndereco' => null
        ];
        $this->assertFalse($this->fornecedorController->criarFornecedor($dadosIdEnderecoVazio));
    }

    public function testCriarFornecedorRetornaNullQuandoRepositorioFalha() {
        $dados = [
            'nome' => 'Novo Fornecedor',
            'email' => 'novo@example.com',
            'telefone' => '11988887777',
            'cnpj' => '11223344000155',
            'idEndereco' => 200
        ];

        $this->fornecedorRepositoryMock->method('criarFornecedor')
            ->willReturn(false);

        $fornecedorCriado = $this->fornecedorController->criarFornecedor($dados);
        $this->assertNull($fornecedorCriado);
    }

    public function testCriarFornecedorLidaComExcecaoDoRepositorio() {
        $dados = [
            'nome' => 'Novo Fornecedor',
            'email' => 'novo@example.com',
            'telefone' => '11988887777',
            'cnpj' => '11223344000155',
            'idEndereco' => 200
        ];

        $this->fornecedorRepositoryMock->method('criarFornecedor')
            ->willThrowException(new Exception("Erro ao inserir no banco"));

        $fornecedorCriado = $this->fornecedorController->criarFornecedor($dados);
        $this->assertNull($fornecedorCriado);
    }

    // --- Testes para editarFornecedor ---

    public function testEditarFornecedorComDadosValidosRetornaTrue() {
        $dados = [
            'emailAntigo' => 'fornecedor@example.com',
            'nome' => 'Fornecedor Editado',
            'email' => 'editado@example.com',
            'telefone' => '11977776666',
            'cnpj' => '00000000000100'
        ];

        $fornecedorExistente = new Fornecedor(1, 'Fornecedor Original', '(11) 99999-9999', 'fornecedor@example.com', '00000000000100', false, 1);

        $this->fornecedorRepositoryMock->method('buscarFornecedorPorEmail')
            ->with($dados['emailAntigo'])
            ->willReturn($fornecedorExistente);

        $this->fornecedorRepositoryMock->method('editarFornecedor')
            ->with(
                $dados['emailAntigo'],
                $dados['nome'],
                $dados['email'],
                $dados['telefone'],
                $dados['cnpj']
            )
            ->willReturn(true);

        $resultado = $this->fornecedorController->editarFornecedor($dados);
        $this->assertTrue($resultado);
    }

    public function testEditarFornecedorComDadosInvalidosRetornaFalse() {
        $dados = [
            'emailAntigo' => 'fornecedor@example.com',
            'nome' => '',
            'email' => 'editado@example.com',
            'telefone' => '11977776666',
            'cnpj' => '00000000000100'
        ];
        $this->assertFalse($this->fornecedorController->editarFornecedor($dados));

        $dados['nome'] = 'Fornecedor Editado';
        $dados['email'] = 'email-invalido'; 
        $this->assertFalse($this->fornecedorController->editarFornecedor($dados));

        $dados['email'] = 'editado@example.com';
        $dados['telefone'] = ''; 
        $this->assertFalse($this->fornecedorController->editarFornecedor($dados));
    }

    public function testEditarFornecedorRetornaNullQuandoFornecedorNaoEncontrado() {
        $dados = [
            'emailAntigo' => 'naoencontrado@example.com',
            'nome' => 'Fornecedor Editado',
            'email' => 'editado@example.com',
            'telefone' => '11977776666',
            'cnpj' => '00000000000100'
        ];

        $this->fornecedorRepositoryMock->method('buscarFornecedorPorEmail')
            ->with($dados['emailAntigo'])
            ->willReturn(null); 

        $resultado = $this->fornecedorController->editarFornecedor($dados);
        $this->assertNull($resultado);
    }

    public function testEditarFornecedorRetornaNullQuandoEdicaoNoRepositorioFalha() {
        $dados = [
            'emailAntigo' => 'fornecedor@example.com',
            'nome' => 'Fornecedor Editado',
            'email' => 'editado@example.com',
            'telefone' => '11977776666',
            'cnpj' => '00000000000100'
        ];

        $fornecedorExistente = new Fornecedor(1, 'Fornecedor Original', '(11) 99999-9999', 'fornecedor@example.com', '00000000000100', false, 1);

        $this->fornecedorRepositoryMock->method('buscarFornecedorPorEmail')
            ->with($dados['emailAntigo'])
            ->willReturn($fornecedorExistente);

        $this->fornecedorRepositoryMock->method('editarFornecedor')
            ->willReturn(false);

        $resultado = $this->fornecedorController->editarFornecedor($dados);
        $this->assertNull($resultado);
    }

    public function testEditarFornecedorLidaComExcecaoDoRepositorioNaBusca() {
        $dados = [
            'emailAntigo' => 'fornecedor@example.com',
            'nome' => 'Fornecedor Editado',
            'email' => 'editado@example.com',
            'telefone' => '11977776666',
            'cnpj' => '00000000000100'
        ];

        $this->fornecedorRepositoryMock->method('buscarFornecedorPorEmail')
            ->willThrowException(new Exception("Erro de banco de dados na busca"));

        $resultado = $this->fornecedorController->editarFornecedor($dados);
        $this->assertNull($resultado);
    }

    public function testEditarFornecedorLidaComExcecaoDoRepositorioNaEdicao() {
        $dados = [
            'emailAntigo' => 'fornecedor@example.com',
            'nome' => 'Fornecedor Editado',
            'email' => 'editado@example.com',
            'telefone' => '11977776666',
            'cnpj' => '00000000000100'
        ];

        $fornecedorExistente = new Fornecedor(1, 'Fornecedor Original', '(11) 99999-9999', 'fornecedor@example.com', '00000000000100', false, 1);

        $this->fornecedorRepositoryMock->method('buscarFornecedorPorEmail')
            ->willReturn($fornecedorExistente);

        $this->fornecedorRepositoryMock->method('editarFornecedor')
            ->willThrowException(new Exception("Erro de banco de dados na edição"));

        $resultado = $this->fornecedorController->editarFornecedor($dados);
        $this->assertNull($resultado);
    }

    // --- Testes para desativarFornecedor ---

    public function testDesativarFornecedorComEmailValidoRetornaTrue() {
        $email = 'desativar@example.com';
        $fornecedorExistente = new Fornecedor(1, 'Fornecedor Ativo', '(11) 99999-9999', $email, '00000000000100', 0, 1);

        $this->fornecedorRepositoryMock->method('buscarFornecedorPorEmail')
            ->with($email)
            ->willReturn($fornecedorExistente);

        $this->fornecedorRepositoryMock->method('desativarFornecedor')
            ->with($email)
            ->willReturn(true);

        $resultado = $this->fornecedorController->desativarFornecedor($email);
        $this->assertTrue($resultado);
        $this->assertEquals(1, $fornecedorExistente->getDesativado());
    }

    public function testDesativarFornecedorComEmailInvalidoRetornaNull() {
        $emailInvalido = 'email-invalido';
        $resultado = $this->fornecedorController->desativarFornecedor($emailInvalido);
        $this->assertNull($resultado);
    }

    public function testDesativarFornecedorRetornaNullQuandoFornecedorNaoEncontrado() {
        $email = 'naoencontrado@example.com';
        $this->fornecedorRepositoryMock->method('buscarFornecedorPorEmail')
            ->with($email)
            ->willReturn(null);

        $resultado = $this->fornecedorController->desativarFornecedor($email);
        $this->assertNull($resultado);
    }

    public function testDesativarFornecedorRetornaNullQuandoDesativacaoNoRepositorioFalha() {
        $email = 'desativar@example.com';
        $fornecedorExistente = new Fornecedor(1, 'Fornecedor Ativo', '(11) 99999-9999', $email, '00000000000100', false, 1);

        $this->fornecedorRepositoryMock->method('buscarFornecedorPorEmail')
            ->with($email)
            ->willReturn($fornecedorExistente);

        $this->fornecedorRepositoryMock->method('desativarFornecedor')
            ->willReturn(false);

        $resultado = $this->fornecedorController->desativarFornecedor($email);
        $this->assertNull($resultado);
    }

    public function testDesativarFornecedorLidaComExcecaoDoRepositorioNaBusca() {
        $email = 'desativar@example.com';
        $this->fornecedorRepositoryMock->method('buscarFornecedorPorEmail')
            ->willThrowException(new Exception("Erro de banco de dados na busca"));

        $resultado = $this->fornecedorController->desativarFornecedor($email);
        $this->assertNull($resultado);
    }

    public function testDesativarFornecedorLidaComExcecaoDoRepositorioNaDesativacao() {
        $email = 'desativar@example.com';
        $fornecedorExistente = new Fornecedor(1, 'Fornecedor Ativo', '(11) 99999-9999', $email, '00000000000100', false, 1);

        $this->fornecedorRepositoryMock->method('buscarFornecedorPorEmail')
            ->willReturn($fornecedorExistente);

        $this->fornecedorRepositoryMock->method('desativarFornecedor')
            ->willThrowException(new Exception("Erro de banco de dados na desativação"));

        $resultado = $this->fornecedorController->desativarFornecedor($email);
        $this->assertNull($resultado);
    }
}