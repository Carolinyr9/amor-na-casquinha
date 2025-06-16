<?php

use PHPUnit\Framework\TestCase;
use app\controller\CategoriaProdutoController;
use app\repository\CategoriaProdutoRepository;
use app\model\CategoriaProduto;

class CategoriaProdutoControllerTest extends TestCase
{
    private $categoriaProdutoRepositoryMock;
    private $categoriaProdutoController;

    protected function setUp(): void
    {
        // Cria um mock do CategoriaProdutoRepository para injetar na Controller
        $this->categoriaProdutoRepositoryMock = $this->createMock(CategoriaProdutoRepository::class);
        $this->categoriaProdutoController = new CategoriaProdutoController($this->categoriaProdutoRepositoryMock);
    }

    ## Testes para listarCategorias

    public function testListarCategoriasRetornaArrayDeCategoriasProduto() {
        $dadosRepository = [
            [
                'id' => 1,
                'fornecedor' => 'Sorvetes Gelado+',
                'nome' => 'Sorvetes Cremosos',
                'marca' => 'Delícia Gelada',
                'descricao' => 'Sorvetes de massa com alta cremosidade, diversos sabores.',
                'desativado' => 0,
                'foto' => 'foto_sorvetes_cremosos.jpg'
            ],
            [
                'id' => 2,
                'fornecedor' => 'Picolés Naturais Ltda.',
                'nome' => 'Picolés Frutados',
                'marca' => 'Fruta Pura',
                'descricao' => 'Picolés feitos com frutas frescas e sem adição de conservantes.',
                'desativado' => 0,
                'foto' => 'foto_picoles_frutados.jpg'
            ],
        ];

        $this->categoriaProdutoRepositoryMock->method('buscarCategoriasAtivas')
            ->willReturn($dadosRepository);

        $categorias = $this->categoriaProdutoController->listarCategorias();

        $this->assertIsArray($categorias);
        $this->assertCount(2, $categorias);
        $this->assertInstanceOf(CategoriaProduto::class, $categorias[0]);
        $this->assertEquals('Sorvetes Cremosos', $categorias[0]->getNome());
        $this->assertEquals(0, $categorias[0]->isDesativado()); 
    }

    public function testListarCategoriasRetornaArrayVazioQuandoNaoHaCategoriasAtivas() {
        $this->categoriaProdutoRepositoryMock->method('buscarCategoriasAtivas')
            ->willReturn([]);

        $categorias = $this->categoriaProdutoController->listarCategorias();

        $this->assertIsArray($categorias);
        $this->assertEmpty($categorias);
    }

    public function testListarCategoriasLidaComExcecaoDoRepositorio() {
        $this->categoriaProdutoRepositoryMock->method('buscarCategoriasAtivas')
            ->willThrowException(new Exception("Erro de conexão com o banco de dados"));

        $categorias = $this->categoriaProdutoController->listarCategorias();
        $this->assertFalse($categorias);
    }

    ## Testes para buscarCategorias

    public function testBuscarCategoriasRetornaArrayDeTodasAsCategoriasProduto() {
        $dadosRepository = [
            [
                'id' => 1,
                'fornecedor' => 'Sorvetes Gelado+',
                'nome' => 'Sorvetes Cremosos',
                'marca' => 'Delícia Gelada',
                'descricao' => 'Sorvetes de massa com alta cremosidade, diversos sabores.',
                'desativado' => 0,
                'foto' => 'foto_sorvetes_cremosos.jpg'
            ],
            [
                'id' => 2,
                'fornecedor' => 'Picolés Naturais Ltda.',
                'nome' => 'Picolés Frutados',
                'marca' => 'Fruta Pura',
                'descricao' => 'Picolés feitos com frutas frescas e sem adição de conservantes.',
                'desativado' => 1, // Desativado
                'foto' => 'foto_picoles_frutados.jpg'
            ],
        ];

        $this->categoriaProdutoRepositoryMock->method('buscarCategorias')
            ->willReturn($dadosRepository);

        $categorias = $this->categoriaProdutoController->buscarCategorias();

        $this->assertIsArray($categorias);
        $this->assertCount(2, $categorias);
        $this->assertInstanceOf(CategoriaProduto::class, $categorias[0]);
        $this->assertEquals('Sorvetes Cremosos', $categorias[0]->getNome());
        $this->assertEquals(0, $categorias[0]->isDesativado());
        $this->assertEquals(1, $categorias[1]->isDesativado());
    }

    public function testBuscarCategoriasRetornaArrayVazioQuandoNaoHaCategorias() {
        $this->categoriaProdutoRepositoryMock->method('buscarCategorias')
            ->willReturn([]);

        $categorias = $this->categoriaProdutoController->buscarCategorias();

        $this->assertIsArray($categorias);
        $this->assertEmpty($categorias);
    }

    public function testBuscarCategoriasLidaComExcecaoDoRepositorio() {
        $this->categoriaProdutoRepositoryMock->method('buscarCategorias')
            ->willThrowException(new Exception("Erro de conexão com o banco de dados"));

        $categorias = $this->categoriaProdutoController->buscarCategorias();
        $this->assertFalse($categorias);
    }

    ## Testes para buscarCategoriaPorID

    public function testBuscarCategoriaPorIDRetornaCategoriaValida() {
        $id = 1;
        $categoriaEsperada = new CategoriaProduto(1, 'Sorvetes Gelado+', 'Sorvetes Cremosos', 'Delícia Gelada', 'Sorvetes de massa.', 0, 'foto_sorvete.jpg');

        $this->categoriaProdutoRepositoryMock->method('buscarCategoriaPorID')
            ->with($id)
            ->willReturn($categoriaEsperada);

        $categoriaRetornada = $this->categoriaProdutoController->buscarCategoriaPorID($id);

        $this->assertInstanceOf(CategoriaProduto::class, $categoriaRetornada);
        $this->assertEquals($id, $categoriaRetornada->getId());
    }

    public function testBuscarCategoriaPorIDRetornaFalseParaIDNaoFornecido() {
        $this->assertFalse($this->categoriaProdutoController->buscarCategoriaPorID(null));
        $this->assertFalse($this->categoriaProdutoController->buscarCategoriaPorID(''));
    }

    public function testBuscarCategoriaPorIDRetornaNullQuandoNaoEncontrado() {
        $id = 99;
        $this->categoriaProdutoRepositoryMock->method('buscarCategoriaPorID')
            ->with($id)
            ->willReturn(null);

        $categoriaRetornada = $this->categoriaProdutoController->buscarCategoriaPorID($id);
        $this->assertNull($categoriaRetornada);
    }

    public function testBuscarCategoriaPorIDLidaComExcecaoDoRepositorio() {
        $id = 1;
        $this->categoriaProdutoRepositoryMock->method('buscarCategoriaPorID')
            ->willThrowException(new Exception("Erro de banco de dados"));

        $categoriaRetornada = $this->categoriaProdutoController->buscarCategoriaPorID($id);
        $this->assertFalse($categoriaRetornada);
    }

    ## Testes para criarCategoria

    public function testCriarCategoriaComDadosValidosRetornaTrue() {
        $dados = [
            'nome' => 'Coberturas Especiais',
            'fornecedor' => 'Doces & Cia',
            'marca' => 'Doce Momento',
            'descricao' => 'Variedade de caldas e confeitos para sorvetes.',
            'foto' => 'foto_coberturas.jpg'
        ];
        $idGerado = 3;

        $this->categoriaProdutoRepositoryMock->method('criarCategoria')
            ->with($dados['nome'], $dados['marca'], $dados['descricao'], $dados['fornecedor'], $dados['foto'])
            ->willReturn($idGerado);

        $resultado = $this->categoriaProdutoController->criarCategoria($dados);

        $this->assertTrue($resultado);
    }

    public function testCriarCategoriaComDadosInvalidosRetornaFalse() {
        $dadosNomeVazio = [
            'nome' => '',
            'fornecedor' => 'Doces & Cia',
            'marca' => 'Doce Momento',
            'descricao' => 'Variedade de caldas e confeitos para sorvetes.',
            'foto' => 'foto_coberturas.jpg'
        ];
        $this->assertFalse($this->categoriaProdutoController->criarCategoria($dadosNomeVazio));

        $dadosFornecedorVazio = [
            'nome' => 'Coberturas Especiais',
            'fornecedor' => '',
            'marca' => 'Doce Momento',
            'descricao' => 'Variedade de caldas e confeitos para sorvetes.',
            'foto' => 'foto_coberturas.jpg'
        ];
        $this->assertFalse($this->categoriaProdutoController->criarCategoria($dadosFornecedorVazio));

        $dadosMarcaVazia = [
            'nome' => 'Coberturas Especiais',
            'fornecedor' => 'Doces & Cia',
            'marca' => '',
            'descricao' => 'Variedade de caldas e confeitos para sorvetes.',
            'foto' => 'foto_coberturas.jpg'
        ];
        $this->assertFalse($this->categoriaProdutoController->criarCategoria($dadosMarcaVazia));

        $dadosFotoVazia = [
            'nome' => 'Coberturas Especiais',
            'fornecedor' => 'Doces & Cia',
            'marca' => 'Doce Momento',
            'descricao' => 'Variedade de caldas e confeitos para sorvetes.',
            'foto' => ''
        ];
        $this->assertFalse($this->categoriaProdutoController->criarCategoria($dadosFotoVazia));

        $dadosDescricaoVazia = [
            'nome' => 'Coberturas Especiais',
            'fornecedor' => 'Doces & Cia',
            'marca' => 'Doce Momento',
            'descricao' => '',
            'foto' => 'foto_coberturas.jpg'
        ];
        $this->assertFalse($this->categoriaProdutoController->criarCategoria($dadosDescricaoVazia));
    }

    public function testCriarCategoriaRetornaFalseQuandoRepositorioFalha() {
        $dados = [
            'nome' => 'Coberturas Especiais',
            'fornecedor' => 'Doces & Cia',
            'marca' => 'Doce Momento',
            'descricao' => 'Variedade de caldas e confeitos para sorvetes.',
            'foto' => 'foto_coberturas.jpg'
        ];

        $this->categoriaProdutoRepositoryMock->method('criarCategoria')
            ->willReturn(false);

        $resultado = $this->categoriaProdutoController->criarCategoria($dados);
        $this->assertFalse($resultado);
    }

    public function testCriarCategoriaLidaComExcecaoDoRepositorio() {
        $dados = [
            'nome' => 'Coberturas Especiais',
            'fornecedor' => 'Doces & Cia',
            'marca' => 'Doce Momento',
            'descricao' => 'Variedade de caldas e confeitos para sorvetes.',
            'foto' => 'foto_coberturas.jpg'
        ];

        $this->categoriaProdutoRepositoryMock->method('criarCategoria')
            ->willThrowException(new Exception("Erro ao inserir no banco"));

        $resultado = $this->categoriaProdutoController->criarCategoria($dados);
        $this->assertFalse($resultado);
    }

    ## Testes para editarCategoria

    public function testEditarCategoriaComDadosValidosRetornaTrue() {
        $dados = [
            'id' => 1,
            'nome' => 'Sorvetes Premium Edição',
            'marca' => 'Elite Gelato',
            'descricao' => 'Sorvetes com ingredientes nobres e sabores exclusivos.',
            'foto' => 'foto_sorvetes_premium_editado.jpg'
        ];

        $categoriaExistente = new CategoriaProduto(1, 'Sorvetes Gelado+', 'Sorvetes Cremosos', 'Delícia Gelada', 'Sorvetes de massa.', 0, 'foto_sorvetes_cremosos.jpg');

        $this->categoriaProdutoRepositoryMock->method('buscarCategoriaPorID')
            ->with($dados['id'])
            ->willReturn($categoriaExistente);

        $this->categoriaProdutoRepositoryMock->method('editarCategoria')
            ->with(
                $dados['id'],
                $dados['nome'],
                $dados['marca'],
                $dados['descricao'],
                $dados['foto']
            )
            ->willReturn(true);

        $resultado = $this->categoriaProdutoController->editarCategoria($dados);
        $this->assertTrue($resultado);
        $this->assertEquals($dados['nome'], $categoriaExistente->getNome());
    }

    public function testEditarCategoriaComDadosInvalidosRetornaFalse() {
        $dados = [
            'id' => 1,
            'nome' => '', 
            'marca' => 'NewTech',
            'descricao' => 'Nova descrição para eletrônicos',
            'foto' => 'nova_foto_eletronicos.jpg'
        ];
        $this->assertFalse($this->categoriaProdutoController->editarCategoria($dados));

        $dados['nome'] = 'Sorvetes Premium Edição';
        $dados['marca'] = ''; 
        $this->assertFalse($this->categoriaProdutoController->editarCategoria($dados));

        $dados['marca'] = 'Elite Gelato';
        $dados['foto'] = ''; 
        $this->assertFalse($this->categoriaProdutoController->editarCategoria($dados));

        $dados['foto'] = 'foto_sorvetes_premium_editado.jpg';
        $dados['descricao'] = '';
        $this->assertFalse($this->categoriaProdutoController->editarCategoria($dados));
    }

    public function testEditarCategoriaRetornaFalseQuandoCategoriaNaoEncontrada() {
        $dados = [
            'id' => 99,
            'nome' => 'Sorvetes Premium Edição',
            'marca' => 'Elite Gelato',
            'descricao' => 'Sorvetes com ingredientes nobres e sabores exclusivos.',
            'foto' => 'foto_sorvetes_premium_editado.jpg'
        ];

        $this->categoriaProdutoRepositoryMock->method('buscarCategoriaPorID')
            ->with($dados['id'])
            ->willReturn(null);

        $resultado = $this->categoriaProdutoController->editarCategoria($dados);
        $this->assertFalse($resultado);
    }

    public function testEditarCategoriaRetornaFalseQuandoEdicaoNoRepositorioFalha() {
        $dados = [
            'id' => 1,
            'nome' => 'Sorvetes Premium Edição',
            'marca' => 'Elite Gelato',
            'descricao' => 'Sorvetes com ingredientes nobres e sabores exclusivos.',
            'foto' => 'foto_sorvetes_premium_editado.jpg'
        ];

        $categoriaExistente = new CategoriaProduto(1, 'Sorvetes Gelado+', 'Sorvetes Cremosos', 'Delícia Gelada', 'Sorvetes de massa.', 0, 'foto_sorvetes_cremosos.jpg');

        $this->categoriaProdutoRepositoryMock->method('buscarCategoriaPorID')
            ->with($dados['id'])
            ->willReturn($categoriaExistente);

        $this->categoriaProdutoRepositoryMock->method('editarCategoria')
            ->willReturn(false);

        $resultado = $this->categoriaProdutoController->editarCategoria($dados);
        $this->assertFalse($resultado);
    }

    public function testEditarCategoriaLidaComExcecaoDoRepositorio() {
        $dados = [
            'id' => 1,
            'nome' => 'Sorvetes Premium Edição',
            'marca' => 'Elite Gelato',
            'descricao' => 'Sorvetes com ingredientes nobres e sabores exclusivos.',
            'foto' => 'foto_sorvetes_premium_editado.jpg'
        ];

        $this->categoriaProdutoRepositoryMock->method('buscarCategoriaPorID')
            ->willThrowException(new Exception("Erro de banco de dados"));

        $resultado = $this->categoriaProdutoController->editarCategoria($dados);
        $this->assertFalse($resultado);
    }

    ## Testes para removerCategoria

    public function testRemoverCategoriaComIDValidoRetornaTrue() {
        $idCategoria = 1;
        $categoriaExistente = new CategoriaProduto(1, 'Sorvetes Gelado+', 'Cones Crocantes', 'Casquinha Perfeita', 'Cones para sorvete de alta qualidade.', 0, 'foto_cones.jpg');

        $this->categoriaProdutoRepositoryMock->method('buscarCategoriaPorID')
            ->with($idCategoria)
            ->willReturn($categoriaExistente);

        $this->categoriaProdutoRepositoryMock->method('desativarCategoria')
            ->with($idCategoria)
            ->willReturn(true);

        $resultado = $this->categoriaProdutoController->removerCategoria($idCategoria);
        $this->assertTrue($resultado);
        $this->assertEquals(1, $categoriaExistente->isDesativado());
    }

    public function testRemoverCategoriaComIDNaoFornecidoRetornaFalse() {
        $this->assertFalse($this->categoriaProdutoController->removerCategoria(null));
        $this->assertFalse($this->categoriaProdutoController->removerCategoria(''));
    }

    public function testRemoverCategoriaRetornaFalseQuandoCategoriaNaoEncontrada() {
        $idCategoria = 99;
        $this->categoriaProdutoRepositoryMock->method('buscarCategoriaPorID')
            ->with($idCategoria)
            ->willReturn(null);

        $resultado = $this->categoriaProdutoController->removerCategoria($idCategoria);
        $this->assertFalse($resultado);
    }

    public function testRemoverCategoriaRetornaFalseQuandoDesativacaoNoRepositorioFalha() {
        $idCategoria = 1;
        $categoriaExistente = new CategoriaProduto(1, 'Sorvetes Gelado+', 'Cones Crocantes', 'Casquinha Perfeita', 'Cones para sorvete de alta qualidade.', 0, 'foto_cones.jpg');

        $this->categoriaProdutoRepositoryMock->method('buscarCategoriaPorID')
            ->with($idCategoria)
            ->willReturn($categoriaExistente);

        $this->categoriaProdutoRepositoryMock->method('desativarCategoria')
            ->willReturn(false);

        $resultado = $this->categoriaProdutoController->removerCategoria($idCategoria);
        $this->assertFalse($resultado);
    }

    public function testRemoverCategoriaLidaComExcecaoDoRepositorio()
    {
        $idCategoria = 1;
        $this->categoriaProdutoRepositoryMock->method('buscarCategoriaPorID')
            ->willThrowException(new Exception("Erro de banco de dados na busca"));

        $resultado = $this->categoriaProdutoController->removerCategoria($idCategoria);
        $this->assertFalse($resultado);
    }

    ## Testes para ativarCategoria

    public function testAtivarCategoriaComIDValidoRetornaTrue()
    {
        $idCategoria = 1;
        $categoriaDesativada = new CategoriaProduto(1, 'Sorvetes Gelado+', 'Toppings Doces', 'Confeitaria Feliz', 'Toppings variados para sorvetes.', 1, 'foto_toppings.jpg');

        $this->categoriaProdutoRepositoryMock->method('ativarCategoria')
            ->with($idCategoria)
            ->willReturn(true);

        $this->categoriaProdutoRepositoryMock->method('buscarCategoriaPorID')
            ->with($idCategoria)
            ->willReturn($categoriaDesativada);

        $resultado = $this->categoriaProdutoController->ativarCategoria($idCategoria);
        $this->assertTrue($resultado);
        $this->assertEquals(0, $categoriaDesativada->isDesativado());
    }

    public function testAtivarCategoriaComIDNaoFornecidoRetornaFalse()
    {
        $this->assertFalse($this->categoriaProdutoController->ativarCategoria(null));
        $this->assertFalse($this->categoriaProdutoController->ativarCategoria(''));
    }

    public function testAtivarCategoriaLidaComExcecaoDoRepositorioNaAtivacao()
    {
        $idCategoria = 1;
        $this->categoriaProdutoRepositoryMock->method('ativarCategoria')
            ->willThrowException(new Exception("Erro de banco de dados na ativação"));

        $resultado = $this->categoriaProdutoController->ativarCategoria($idCategoria);
        $this->assertFalse($resultado);
    }

    public function testAtivarCategoriaLidaComExcecaoDoRepositorioNaBuscaAposAtivacao()
    {
        $idCategoria = 1;
        $this->categoriaProdutoRepositoryMock->method('ativarCategoria')
            ->willReturn(true);
        $this->categoriaProdutoRepositoryMock->method('buscarCategoriaPorID')
            ->willThrowException(new Exception("Erro de banco de dados na busca pós-ativação"));

        $resultado = $this->categoriaProdutoController->ativarCategoria($idCategoria);
        $this->assertFalse($resultado);
    }
}