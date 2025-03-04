<?php
session_start();
require_once '../config/blockURLAccess.php';
require_once '../../vendor/autoload.php';
use app\controller\produtoVariacaoController;
use app\controller\FotoController;

$produtoVariacaoController = new produtoVariacaoController();
$produtoVariacaoId = $_GET['idVariacao'] ?? null;
$produtoVariacao = $produtoVariacaoId ? $produtoVariacaoController->selecionarProdutosPorID($produtoVariacaoId) : null;

if (isset($_POST['btnEditar'])) {
    $idProduto = $_POST['produto'] ?? '';
    $idVariacao = $_POST['idVariacao'] ?? '';
    $nomeProduto = $_POST['nomeProdEdt'] ?? '';
    $preco = $_POST['precoSabEdt'] ?? '';
    $imagemProduto = $_POST['imagemSabEdt'] ?? '';
    
    if (isset($_FILES['foto']) && $_FILES['foto']['error'] === UPLOAD_ERR_OK) {
        $image = $_FILES['foto'];
        $pictureController = new Picture($image);
        $name_image = $pictureController->validatePicture();

        if ($name_image) {
            $produtoVariacaoController->editarProduto($idVariacao, $idProduto, $nomeProduto, $preco, $name_image);
        } else {
            $errors = $pictureController->countErrors();
            echo '<script>alert("' . $errors . '")</script>';
        }
    } else {
        $produtoVariacaoController->editarProduto($idVariacao, $idProduto, $nomeProduto, $preco, $imagemProduto);
    }

    header("Location: editaSabor.php?idProduto=$idProduto&idVariacao=$idVariacao");
    exit();
}
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
                    <?php
                    if ($produtoVariacao && count($produtoVariacao) > 0) {
                        $produto = $produtoVariacao[0];
                        $idProduto = htmlspecialchars($produto['idProduto']);
                        $idVariacao = htmlspecialchars($produto['idVariacao']);
                        $nomeVariacao = htmlspecialchars($produto['nomeVariacao']);
                        $precoVariacao = htmlspecialchars($produto['precoVariacao']);
                        $fotoVariacao = htmlspecialchars($produto['fotoVariacao']);

                        echo '
                        <form enctype="multipart/form-data" action="' . htmlspecialchars($_SERVER["PHP_SELF"] . '?idVariacao=' . $idVariacao) . '" method="POST" id="formulario" class="formulario m-auto d-flex flex-column justify-content-center">
                            <input type="hidden" name="produto" value="' . $idProduto . '">
                            <input type="hidden" name="idVariacao" value="' . $idVariacao . '">
                            <label for="nome2">Nome:</label>
                            <input class="rounded-3 border-0 pl-3" type="text" id="nome2" name="nomeProdEdt" placeholder="Nome" value="' . $nomeVariacao . '">

                            <label for="preco2">Preço</label>
                            <input class="rounded-3 border-0 pl-3" type="text" id="preco2" name="precoSabEdt" placeholder="Preço" value="' . $precoVariacao . '">
                            
                            <label for="imagemInput">Foto:</label>
                            <input type="file" id="imagemInput" name="foto" class="w-100">
                            <input class="rounded-3 border-0 pl-4" type="hidden" name="imagemSabEdt" value="' . $fotoVariacao . '">
                            <img id="preview" src="" alt="Pré-visualização da imagem" class="mx-auto mt-3" style="max-width: 150px; display: none;">
                            <input type="submit" name="btnEditar" class="btnEditar mt-3 mx-auto border-0 rounded-4" value="Editar" />
                        </form>';
                    } else {
                        echo '<p>Produto não encontrado.</p>';
                    }
                    ?>
            </div>
        <button class="voltar border-0 rounded-4 fw-bold"><a class="text-decoration-none color-black" href="editarSabores.php?produto=<?php echo $idProduto;?>">Voltar</a></button>
    </div>
</main>
<?php
include_once 'components/footer.php';
?>
<script src="script/header.js"></script>
<script src="script/adicionar.js"></script>
</body>
</html>
