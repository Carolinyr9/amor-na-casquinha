<?php
session_start();
require_once '../config/blockURLAccess.php';
require_once '../../vendor/autoload.php';
use app\controller\ProdutoController;
use app\controller\FotoController;

$produtoController = new ProdutoController();
$produtoId = $_GET['produto'] ?? null;
$produto = $produtoId ? $produtoController->obterProdutoPorID($produtoId) : null;

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['btnEditar'])) {
    $id = $_POST['produto'];
    $nomeProduto = $_POST['nomeProdEdt'] ?? '';
    $marca = $_POST['marcaProdEdt'] ?? '';
    $descricao = $_POST['descricaoProdEdt'] ?? '';
    $imagemProduto = $_POST['imagemProdEdt'] ?? '';

    if (isset($_FILES['foto']) && $_FILES['foto']['error'] === UPLOAD_ERR_OK) {
        $image = $_FILES['foto'];
        $fotoController = new FotoController($image);
        $name_image = $fotoController->validatePicture();

        if ($name_image) {
            $produtoController->editarProduto($id, $nomeProduto, $marca, $descricao, $name_image);
        } else {
            $errors = $fotoController->countErrors();
            echo '<script>alert("' . $errors . '")</script>';
        }
    } else {
        $produtoController->editarProduto($id, $nomeProduto, $marca, $descricao, $imagemProduto);
    }

    header("Location: editProd.php?produto=$id");
    exit();
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Produto</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
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
            <h2 class="m-auto text-center pt-4 pb-4">Editar Produto</h2>
            <div class="conteiner-form p-3 rounded-4 d-flex flex-column my-3 w-75">
                <?php
                if ($produto && count($produto) > 0) {
                    $dado = $produto;
                    $idProduto = htmlspecialchars($dado['idProduto']);
                    $nome = htmlspecialchars($dado['nome']);
                    $marca = htmlspecialchars($dado['marca']);
                    $descricao = htmlspecialchars($dado['descricao']);
                    $foto = htmlspecialchars($dado['foto']);

                    echo '
                    <form enctype="multipart/form-data" action="' . htmlspecialchars($_SERVER["PHP_SELF"] . '?produto=' . $idProduto) . '" method="POST" id="formulario" class="formulario m-auto d-flex flex-column justify-content-center">
                        <input type="hidden" name="produto" value="' . $idProduto . '">
                        <input class="rounded-3 border-0 pl-4" type="hidden" name="imagemProdEdt" value="' . $foto . '">
                            
                        <div class="mb-3">
                            <label for="produto" class="form-label">Produto</label>
                            <input type="text" class="form-control" name="nomeProdEdt" id="produto" value="' . $nome . '">
                        </div>
                            
                        <div class="mb-3">
                            <label for="marca" class="form-label">Marca</label>
                            <input type="text" class="form-control" name="marcaProdEdt" id="marca" value="' . $marca . '">
                        </div>
                            
                        <div class="mb-3">
                            <label for="descricao" class="form-label">Descrição</label>
                            <input type="text" class="form-control" name="descricaoProdEdt" id="descricao" value="' . $descricao . '">
                        </div>

                        <div class="mb-3">
                            <label for="imagem" class="form-label">Foto</label>
                            <input class="form-control" type="file" id="imagem" name="foto">
                        </div>

                        
                            
                            <img id="preview" src="" alt="Pré-visualização da imagem" class="mx-auto mt-3" style="max-width: 150px; display: none;">
                        <input type="submit" name="btnEditar" class="btnEditar mt-3 mx-auto border-0 rounded-3 px-4" value="Editar" />
                    </form>';
                } else {
                    echo '<p>Produto não encontrado.</p>';
                }
                ?>
            </div>
            <button class="voltar border-0 rounded-3 fw-bold"><a class="text-decoration-none color-black" href="editarProdutos.php">Voltar</a></button>
        </div>
    </main>
    <?php include_once 'components/footer.php'; ?>
</body>
</html>
