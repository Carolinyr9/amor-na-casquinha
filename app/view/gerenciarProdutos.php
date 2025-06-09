<?php
namespace app\view;
require_once '../../vendor/autoload.php';
require_once '../utils/produto/adicionarProdutos.php';
require_once '../utils/produto/reativarProduto.php';

use app\controller\ProdutoController;

session_start();
require_once '../utils/autenticacao/sessao.php';

$categoriaId = $_GET['categoria'] ?? null;
$produtoController = new ProdutoController();
$produtos = $categoriaId ? $produtoController->selecionarProdutosCategoria($categoriaId) : [];
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gerenciar Produtos</title>
    <?php include_once '../utils/links/styleLinks.php'; ?>
    <link rel="stylesheet" href="style/formulario.css">
</head>
<body>

    <?php include_once 'components/header.php'; ?>

    <main>
        <h1 class="titulo">Sabores</h1>
        <div class="container d-flex flex-column align-items-center justify-content-center m-auto my-5">
            <button class="botao botao-primary" id="addProduto">Adicionar Sabor</button>

            <div class="formulario container my-5 border rounded-4 py-3" id="formulario" style="display: none;">
                <form enctype="multipart/form-data" action="" method="POST" class="d-flex flex-column align-items-center gap-5">
                    <div class="d-flex flex-column ">
                        <div class="mb-3">
                            <input type="text" class="form-control"  id="nome" name="nomeSabAdd" placeholder="Produto" required>
                        </div>
                        <div class="mb-3">
                            <input type="number" step="0.01" class="form-control"  id="preco" name="precoSabAdd" placeholder="Preço de venda" required>
                        </div>
                        <div class="mb-3">
                            <input type="text" class="form-control" id="lote" name="lote" placeholder="Lote" required>
                        </div>
                        <div class="mb-3">
                            <input type="number" step="0.01" class="form-control" id="valor" name="valor" placeholder="Valor da compra" required>
                        </div>
                        <div class="mb-3">
                            <input type="number" class="form-control" id="quantidade" name="quantidade" placeholder="Quantidade" required>
                        </div>
                        <div class="mb-3">
                            <label for="dataEntrada" class="form-label">Data de entrada</label>
                            <input type="date" class="form-control" id="dataEntrada" name="dataEntrada" placeholder="Data de entrada" required>
                        </div>
                        <div class="mb-3">
                            <label for="dataFabricacao" class="form-label">Data de fabricação</label>
                            <input type="date" class="form-control" id="dataFabricacao" name="dataFabricacao" placeholder="Data de fabricação" required>
                        </div>
                        <div class="mb-3">
                            <label for="dataVencimento" class="form-label">Data de vencimento</label>
                            <input type="date" class="form-control" id="dataVencimento" name="dataVencimento" placeholder="Data de vencimento" required>
                        </div>
                        <div class="mb-3">
                            <input type="number" class="form-control" id="quantidadeMinima" name="quantidadeMinima" placeholder="Quantidade mínima" required>
                        </div>
                        <div class="mb-3">
                            <input type="file" id="imagem" name="imagem" class="form-control" require/>
                        </div>
                            <img id="preview" src="" alt="Pré-visualização da imagem" class="mx-auto mt-3 align-self-center" style="max-width: 170px; display: none;">
                        
                        <input type="hidden" name="idProduto" value="<?= $_GET["categoria"]; ?>">
                    </div>
                    
                    <input name="inserirSaborSubmit" type="submit" value="Inserir" class="botao botao-primary" />
                </form>
            </div>

            <div class="container d-flex flex-row flex-wrap justify-content-center gap-4 mt-4">
                <?php 
                if ($produtos) {
                    foreach ($produtos as $produto) {
                        include 'components/produtosCards.php';
                    }
                } else {
                    echo '<p>Nenhuma variação de produto encontrada.</p>';
                }
                ?>
            </div>
            <a class="botao botao-secondary mt-4" href="gerenciarCategorias.php">Voltar</a>
        </div>
    </main>

    <?php include 'components/footer.php'; ?>

    <script src="script/exibirFormulario.js"></script>    
    <script src="script/exibirArquivoImagem.js"></script>
</body>
</html>