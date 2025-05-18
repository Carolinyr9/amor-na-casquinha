<?php
namespace app\view;
require_once '../../vendor/autoload.php';
require_once '../utils/produto/adicionarProdutos.php';

use app\controller\ProdutoController;

session_start();
require_once '../utils/autenticacao/sessao.php';

$categoriaId = $_GET['categoria'] ?? null;
$produtoController = new ProdutoController();
$produtos = $categoriaId ? $produtoController->selecionarProdutosAtivos($categoriaId) : [];
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gerenciar Produtos</title>
    <script
  src="https://code.jquery.com/jquery-3.7.1.js"
  integrity="sha256-eKhayi8LEQwp4NKxN+CfCh+3qOVUtJn3QNZ0TciWLP4="
  crossorigin="anonymous"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link rel="stylesheet" href="style/CabecalhoRodape.css">
    <link rel="stylesheet" href="style/components/botao.css">
    <link rel="stylesheet" href="style/components/cards.css">
    <link rel="stylesheet" href="style/base/global.css">
    <link rel="stylesheet" href="style/base/variables.css">
    <link rel="stylesheet" href="style/formulario.css">
    <link rel="shortcut icon" href="../images/iceCreamIcon.ico" type="image/x-icon">
</head>
<body>

    <?php include_once 'components/header.php'; ?>

    <main>
        <h1 class="titulo">Sabores</h1>
        <div class="container d-flex flex-column align-items-center justify-content-center m-auto my-5">
            <button class="botao botao-primary" id="addProduto">Adicionar Sabor</button>

            <div class="container my-5 border w-50 rounded-4 py-3" id="formulario" style="display: none;">
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
                            <input type="date" class="form-control" id="dataEntrada" name="dataEntrada" placeholder="Data de entrada" required>
                        </div>
                        <div class="mb-3">
                            <input type="date" class="form-control" id="dataFabricacao" name="dataFabricacao" placeholder="Data de fabricação" required>
                        </div>
                        <div class="mb-3">
                            <input type="date" class="form-control" id="dataVencimento" name="dataVencimento" placeholder="Data de vencimento" required>
                        </div>
                        <div class="mb-3">
                            <input type="number" class="form-control" id="quantidadeMinima" name="quantidadeMinima" placeholder="Quantidade mínima" required>
                        </div>
                        <div class="mb-3">
                            <input type="file" id="imagem" name="imagem" class="form-control"/>
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

    <script src="script/header.js"></script>
    <script src="script/exibirFormulario.js"></script>    
    <script src="script/exibirArquivoImagem.js"></script>
</body>
</html>