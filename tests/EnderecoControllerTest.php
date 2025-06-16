<?php

use PHPUnit\Framework\TestCase;
use app\controller\EnderecoController;
use app\model\Endereco;
use app\repository\EnderecoRepository;

require_once __DIR__ . '/../app/utils/helpers/Logger.php';

class EnderecoControllerTest extends TestCase {
    private $enderecoController;
    /** @var PHPUnit\Framework\MockObject\MockObject|EnderecoRepository */
    private $enderecoRepository; 

    protected function setUp(): void {
        $this->enderecoRepository = $this->createMock(EnderecoRepository::class);

        $this->enderecoController = new EnderecoController();

        $reflection = new ReflectionClass(EnderecoController::class);
        $property = $reflection->getProperty('repository');
        $property->setAccessible(true);
        $property->setValue($this->enderecoController, $this->enderecoRepository);
    }

    // --- Testes para criarEndereco ---

    public function testCriarEnderecoComDadosValidosRetornaId() {
        $dados = [
            'rua' => 'Rua Teste',
            'numero' => '123',
            'cep' => '01000-000',
            'bairro' => 'Centro',
            'cidade' => 'São Paulo',
            'estado' => 'SP',
            'complemento' => 'Apto 1'
        ];

        $this->enderecoRepository
             ->expects($this->once()) 
             ->method('criarEndereco')
             ->with('Rua Teste', '123', '01000-000', 'Centro', 'São Paulo', 'SP', 'Apto 1')
             ->willReturn(1); 

        $resultado = $this->enderecoController->criarEndereco($dados);
        $this->assertEquals(1, $resultado);
    }

    public function testCriarEnderecoComNumeroInvalidoRetornaNull() {
        $dados = [
            'rua' => 'Rua Teste',
            'numero' => 'abc',
            'cep' => '01000-000',
            'bairro' => 'Centro',
            'cidade' => 'São Paulo',
            'estado' => 'SP',
            'complemento' => 'Apto 1'
        ];

        $this->enderecoRepository->expects($this->never())->method('criarEndereco');

        $resultado = $this->enderecoController->criarEndereco($dados);
        $this->assertNull($resultado);
    }

    public function testCriarEnderecoComCepInvalidoRetornaNull() {
        $dados = [
            'rua' => 'Rua Teste',
            'numero' => '123',
            'cep' => '123',
            'bairro' => 'Centro',
            'cidade' => 'São Paulo',
            'estado' => 'SP',
            'complemento' => 'Apto 1'
        ];

        $this->enderecoRepository->expects($this->never())->method('criarEndereco');

        $resultado = $this->enderecoController->criarEndereco($dados);
        $this->assertNull($resultado);
    }

    public function testCriarEnderecoQuandoRepositorioFalhaRetornaFalse() {
        $dados = [
            'rua' => 'Rua Teste',
            'numero' => '123',
            'cep' => '01000-000',
            'bairro' => 'Centro',
            'cidade' => 'São Paulo',
            'estado' => 'SP',
            'complemento' => 'Apto 1'
        ];

        $this->enderecoRepository
             ->expects($this->once())
             ->method('criarEndereco')
             ->willReturn(false);

        $resultado = $this->enderecoController->criarEndereco($dados);
        $this->assertFalse($resultado);
    }

    public function testCriarEnderecoLancaExcecaoRetornaFalse() {
        $dados = [
            'rua' => 'Rua Teste',
            'numero' => '123',
            'cep' => '01000-000',
            'bairro' => 'Centro',
            'cidade' => 'São Paulo',
            'estado' => 'SP',
            'complemento' => 'Apto 1'
        ];

        $this->enderecoRepository
             ->expects($this->once())
             ->method('criarEndereco')
             ->willThrowException(new Exception('Erro de banco de dados'));

        $resultado = $this->enderecoController->criarEndereco($dados);
        $this->assertFalse($resultado);
    }

    // --- Testes para listarEnderecoPorId ---

    public function testListarEnderecoPorIdComIdValidoRetornaObjetoEndereco() {
        $idEndereco = 1;
        $dadosRepository = [
            'rua' => 'Rua Existente',
            'numero' => '456',
            'cep' => '02000-000',
            'bairro' => 'Bairro A',
            'cidade' => 'Cidade B',
            'estado' => 'RJ',
            'complemento' => 'Casa'
        ];

        $this->enderecoRepository
             ->expects($this->once())
             ->method('listarEnderecoPorId')
             ->with($idEndereco)
             ->willReturn($dadosRepository);

        $endereco = $this->enderecoController->listarEnderecoPorId($idEndereco);

        $this->assertInstanceOf(Endereco::class, $endereco);
        $this->assertEquals($idEndereco, $endereco->getIdEndereco());
        $this->assertEquals('Rua Existente', $endereco->getRua());
    }

    public function testListarEnderecoPorIdComIdNaoExistenteRetornaNull() {
        $idEndereco = 99;

        $this->enderecoRepository
             ->expects($this->once())
             ->method('listarEnderecoPorId')
             ->with($idEndereco)
             ->willReturn(null);

        $endereco = $this->enderecoController->listarEnderecoPorId($idEndereco);
        $this->assertNull($endereco);
    }

    public function testListarEnderecoPorIdComIdVazioRetornaNull() {
        $this->enderecoRepository->expects($this->never())->method('listarEnderecoPorId');
        $this->assertNull($this->enderecoController->listarEnderecoPorId(null));
        $this->assertNull($this->enderecoController->listarEnderecoPorId(''));
    }

    public function testListarEnderecoPorIdLancaExcecaoRetornaNull() {
        $idEndereco = 1;

        $this->enderecoRepository
             ->expects($this->once())
             ->method('listarEnderecoPorId')
             ->with($idEndereco)
             ->willThrowException(new Exception('Erro de conexão'));

        $endereco = $this->enderecoController->listarEnderecoPorId($idEndereco);
        $this->assertFalse($endereco);
    }

    // --- Testes para editarEndereco ---

    public function testEditarEnderecoComDadosValidosRetornaNull() {
        $dados = [
            'idEndereco' => 1,
            'rua' => 'Rua Editada',
            'numero' => '456',
            'cep' => '01000-000',
            'bairro' => 'Novo Bairro',
            'cidade' => 'Nova Cidade',
            'estado' => 'MG',
            'complemento' => 'Casa 2'
        ];

        $this->enderecoRepository
             ->expects($this->once())
             ->method('listarEnderecoPorId')
             ->with(1)
             ->willReturn([
                'idEndereco' => 1, 'rua' => 'Rua Antiga', 'numero' => '123', 'cep' => '01000-000',
                'bairro' => 'Antigo', 'cidade' => 'Cidade Antiga', 'estado' => 'SP', 'complemento' => 'Apto'
             ]);

        $this->enderecoRepository
             ->expects($this->once())
             ->method('editarEndereco')
             ->with('Rua Editada', '456', 'Casa 2', '01000-000', 'Novo Bairro', 'MG', 'Nova Cidade', 1)
             ->willReturn(true);

        $resultado = $this->enderecoController->editarEndereco($dados);
        $this->assertNull($resultado);
    }

    public function testEditarEnderecoComNumeroInvalidoRetornaFalse() {
        $dados = [
            'idEndereco' => 1,
            'rua' => 'Rua Editada',
            'numero' => 'abc',
            'cep' => '01000-000',
            'bairro' => 'Novo Bairro',
            'cidade' => 'Nova Cidade',
            'estado' => 'MG',
            'complemento' => 'Casa 2'
        ];

        $this->enderecoRepository->expects($this->never())->method('listarEnderecoPorId');
        $this->enderecoRepository->expects($this->never())->method('editarEndereco');

        $resultado = $this->enderecoController->editarEndereco($dados);
        $this->assertFalse($resultado);
    }

    public function testEditarEnderecoComCepInvalidoRetornaFalse() {
        $dados = [
            'idEndereco' => 1,
            'rua' => 'Rua Editada',
            'numero' => '123',
            'cep' => '123',
            'bairro' => 'Novo Bairro',
            'cidade' => 'Nova Cidade',
            'estado' => 'MG',
            'complemento' => 'Casa 2'
        ];

        $this->enderecoRepository->expects($this->never())->method('listarEnderecoPorId');
        $this->enderecoRepository->expects($this->never())->method('editarEndereco');

        $resultado = $this->enderecoController->editarEndereco($dados);
        $this->assertFalse($resultado);
    }

    public function testEditarEnderecoQuandoEnderecoNaoEncontradoRetornaFalse() {
        $dados = [
            'idEndereco' => 99,
            'rua' => 'Rua Editada',
            'numero' => '456',
            'cep' => '01000-000',
            'bairro' => 'Novo Bairro',
            'cidade' => 'Nova Cidade',
            'estado' => 'MG',
            'complemento' => 'Casa 2'
        ];
        
        $this->enderecoRepository
             ->expects($this->once())
             ->method('listarEnderecoPorId')
             ->with(99)
             ->willReturn(false);

        $this->enderecoRepository->expects($this->never())->method('editarEndereco');

        $resultado = $this->enderecoController->editarEndereco($dados);
        $this->assertFalse($resultado);
    }

    public function testEditarEnderecoQuandoRepositorioFalhaRetornaFalse() {
        $dados = [
            'idEndereco' => 1,
            'rua' => 'Rua Editada',
            'numero' => '456',
            'cep' => '01000-000',
            'bairro' => 'Novo Bairro',
            'cidade' => 'Nova Cidade',
            'estado' => 'MG',
            'complemento' => 'Casa 2'
        ];

        $this->enderecoRepository
             ->expects($this->once())
             ->method('listarEnderecoPorId')
             ->willReturn([
                'idEndereco' => 1, 'rua' => 'Rua Antiga', 'numero' => '123', 'cep' => '01000-000',
                'bairro' => 'Antigo', 'cidade' => 'Cidade Antiga', 'estado' => 'SP', 'complemento' => 'Apto'
             ]);

        $this->enderecoRepository
             ->expects($this->once())
             ->method('editarEndereco')
             ->willReturn(false);

        $resultado = $this->enderecoController->editarEndereco($dados);
        $this->assertNull($resultado);
    }

    public function testEditarEnderecoLancaExcecaoRetornaFalse() {
        $dados = [
            'idEndereco' => 1,
            'rua' => 'Rua Editada',
            'numero' => '456',
            'cep' => '01000-000',
            'bairro' => 'Novo Bairro',
            'cidade' => 'Nova Cidade',
            'estado' => 'MG',
            'complemento' => 'Casa 2'
        ];

        $this->enderecoRepository
             ->expects($this->once())
             ->method('listarEnderecoPorId')
             ->willThrowException(new Exception('Erro inesperado'));

        $resultado = $this->enderecoController->editarEndereco($dados);
        $this->assertFalse($resultado);
    }

    // --- Testes para listarEnderecos ---

    public function testListarEnderecosRetornaArrayDeObjetosEndereco() {
        $dadosRepository = [
            [
                'idEndereco' => 1,
                'rua' => 'Rua Um', 'numero' => '10', 'cep' => '01000-000',
                'bairro' => 'Bairro A', 'cidade' => 'Cidade X', 'estado' => 'SP', 'complemento' => 'Apto 1'
            ],
            [
                'idEndereco' => 2,
                'rua' => 'Rua Dois', 'numero' => '20', 'cep' => '02000-000',
                'bairro' => 'Bairro B', 'cidade' => 'Cidade Y', 'estado' => 'MG', 'complemento' => ''
            ]
        ];

        $this->enderecoRepository
             ->expects($this->once())
             ->method('listarEnderecos')
             ->willReturn($dadosRepository);

        $enderecos = $this->enderecoController->listarEnderecos();

        $this->assertIsArray($enderecos);
        $this->assertCount(2, $enderecos);
        $this->assertInstanceOf(Endereco::class, $enderecos[0]);
        $this->assertEquals(1, $enderecos[0]->getIdEndereco());
        $this->assertInstanceOf(Endereco::class, $enderecos[1]);
        $this->assertEquals(2, $enderecos[1]->getIdEndereco());
    }

    public function testListarEnderecosQuandoNenhumEnderecoEncontradoRetornaArrayVazio() {
        $this->enderecoRepository
             ->expects($this->once())
             ->method('listarEnderecos')
             ->willReturn(false);

        $enderecos = $this->enderecoController->listarEnderecos();
        $this->assertFalse($enderecos);
        $this->assertEmpty($enderecos);
    }

    public function testListarEnderecosLancaExcecaoRetornaArrayVazio() {
        $this->enderecoRepository
             ->expects($this->once())
             ->method('listarEnderecos')
             ->willThrowException(new Exception('Falha de conexão'));

        $enderecos = $this->enderecoController->listarEnderecos();
        $this->assertFalse($enderecos);
        $this->assertEmpty($enderecos);
    }
}