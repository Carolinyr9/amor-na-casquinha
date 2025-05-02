<?php
namespace app\view;
require_once '../../vendor/autoload.php';
require_once '../utils/adicionarProdutos.php';

use app\controller2\ProdutoController;

session_start();
require_once '../utils/sessao.php';

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
    <link rel="stylesheet" href="style/editarSaboresS.css">
    <link rel="shortcut icon" href="images/iceCreamIcon.ico" type="image/x-icon">
</head>
<body>

    <?php include_once 'components/header.php'; ?>

    <main>
        <h1>Sabores</h1>

        <div class="d-flex flex-column align-items-center justify-content-center">
            <button class="add fs-5 fw-bold rounded-4 border-0 my-3">Adicionar Sabor</button>
            <div class="m-4">
                <form action="" method="POST" enctype="multipart/form-data" id="addFormulario" class="flex-row justify-content-between flex-wrap gap-4 w-50 m-auto">
                    <div class="form-group">
                        <label for="nome">Nome:</label>
                        <input type="text" class="form-control"  id="nome" name="nomeSabAdd" placeholder="Picolé de..." required>
                    </div>
                    <div class="form-group">
                        <label for="preco">Preço:</label>
                        <input type="number" step="0.01" class="form-control"  id="preco" name="precoSabAdd" placeholder="00,00" required>
                    </div>
                    <div class="form-group">
                        <label for="lote">Lote</label>
                        <input type="text" class="form-control" id="lote" name="lote" placeholder="000" required>
                    </div>
                    <div class="form-group">
                        <label for="valor">Valor da compra</label>
                        <input type="number" step="0.01" class="form-control" id="valor" name="valor" placeholder="00,00" required>
                    </div>
                    <div class="form-group">
                        <label for="quantidade">Quantidade</label>
                        <input type="number" class="form-control" id="quantidade" name="quantidade" placeholder="000" required>
                    </div>
                    <div class="form-group">
                        <label for="dataEntrada">Entrada</label>
                        <input type="date" class="form-control" id="dataEntrada" name="dataEntrada" required>
                    </div>

                    <div class="form-group">
                        <label for="dataFabricacao">Fabricação</label>
                        <input type="date" class="form-control" id="dataFabricacao" name="dataFabricacao" required>
                    </div>
                    <div class="form-group">
                        <label for="dataVencimento">Vencimento</label>
                        <input type="date" class="form-control" id="dataVencimento" name="dataVencimento" required>
                    </div>
                    <div class="form-group">
                        <label for="quantidadeMinima">Quantidade Mínima</label>
                        <input type="number" class="form-control" id="quantidadeMinima" name="quantidadeMinima" placeholder="000" required>
                    </div>
                    <div class="mb-3">
                        <label for="imagem" class="form-label">Imagem</label>
                        <input type="file" id="imagem" name="imagem" class="form-control"/>
                    </div>

                    <img id="preview" src="" alt="Pré-visualização da imagem" class="mx-auto mt-3" style="max-width: 150px; display: none;">
                    
                    <input type="hidden" name="idProduto" value="<?= $_GET["categoria"]; ?>">
                    <input name="inserirSaborSubmit" type="submit" value="Inserir" class="form__submit px-3 border-0 rounded-3 m-1 text-black" />
                </form>
            </div>

            <div class="d-flex flex-row flex-wrap m-auto w-75 justify-content-center">
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
            <button class="voltar mt-3 fs-5 fw-bold rounded-4 border-0"><a href="gerenciarCategorias.php">Voltar</a></button>
        </div>
    </main>

    <?php include 'components/footer.php'; ?>

    <script src="script/header.js"></script>
    <script src="script/adicionar.js"></script>    
    <script src="script/exibirArquivoImagem.js"></script>
</body>
</html>