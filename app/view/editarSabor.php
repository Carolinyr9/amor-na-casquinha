<?php
session_start();
require_once '../config/blockURLAccess.php';
require_once '../../vendor/autoload.php';
require_once '../utils/editarProdutos.php';
use app\controller2\ProdutoController;

$produtoController = new ProdutoController();
$produtoId = $_GET['idProduto'] ?? null;
$produto = $produtoController->selecionarProdutoPorID($produtoId);
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Sabor</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link rel="stylesheet" href="style/CabecalhoRodape.css">
    <link rel="stylesheet" href="style/editaSaborS.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <link rel="shortcut icon" href="images/iceCreamIcon.ico" type="image/x-icon">
    <script src="script/exibirArquivoImagem.js"></script>
</head>
<body>
<?php include_once 'components/header.php'; ?>
<main>
    <div class="conteiner d-flex flex-column align-items-center justify-content-center flex-column">
        <h2 class="m-auto text-center pt-4 pb-4">Editar Sabor</h2>
        <div class="conteiner-form p-3 rounded-4 d-flex flex-column my-3 w-75">
            <?php if ($produto): ?>
                <form enctype="multipart/form-data" action="<?= htmlspecialchars($_SERVER["PHP_SELF"] . '?idVariacao=' . htmlspecialchars($produto->getCategoria())) ?>" method="POST" id="formulario" class="formulario m-auto d-flex flex-column justify-content-center">
                    <input type="hidden" name="idProduto" value="<?= htmlspecialchars($produto->getId()) ?>">
                    <input type="hidden" name="idCategoria" value="<?= htmlspecialchars($_GET['idCategoria']) ?>">
                    <input type="hidden" name="imagemSabEdt" value="<?= htmlspecialchars($produto->getFoto()) ?>">

                    <div class="mb-3">
                        <label for="produto" class="form-label">Produto</label>
                        <input type="text" class="form-control" name="nomeProdEdt" id="produto" value="<?= htmlspecialchars($produto->getNome()) ?>">
                    </div>

                    <div class="mb-3">
                        <label for="preco" class="form-label">Preço</label>
                        <input type="text" class="form-control" name="precoSabEdt" id="preco" value="<?= htmlspecialchars($produto->getPreco()) ?>">
                    </div>

                    <div class="mb-3">
                        <label for="imagem" class="form-label">Imagem</label>
                        <input type="file" id="imagem" name="imagem" class="form-control">
                    </div>

                    <img id="preview" src="" alt="Pré-visualização da imagem" class="mx-auto mt-3" style="max-width: 150px; display: none;">

                    <input type="submit" name="btnEditar" class="btnEditar mt-3 mx-auto border-0 rounded-3 px-4" value="Editar" />
                </form>
            <?php else: ?>
                <p>Produto não encontrado.</p>
            <?php endif; ?>
        </div>

        <button class="voltar border-0 rounded-3 fw-bold">
            <a class="text-decoration-none color-black" href="gerenciarProdutos.php?categoria=<?= $produto ? htmlspecialchars($produto->getCategoria()) : '' ?>">Voltar</a>
        </button>
    </div>
</main>
<?php include_once 'components/footer.php'; ?>
<script src="script/header.js"></script>
<script src="script/adicionar.js"></script>
</body>
</html>
