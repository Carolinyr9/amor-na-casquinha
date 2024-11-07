<?php
require_once '../config/blockURLAccess.php';
session_start();
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
    <link rel="stylesheet" href="style/editFuncS.css">
    <link rel="shortcut icon" href="images/iceCreamIcon.ico" type="image/x-icon">
</head>
<body>
<?php
require_once '../controller/produtoVariacaoController.php';
$produtoVariacaoController = new produtoVariacaoController();
include_once 'components/header.php';

$produtoVariacaoId = $_GET['idVariacao'] ?? null;
$produtoVariacao = $produtoVariacaoId ? $produtoVariacaoController->selecionarProdutosPorID($produtoVariacaoId) : null;

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['btnEditar'])) {
    $idProduto = $_POST['produto'] ?? '';
    $idVariacao = $_POST['idVariacao'] ?? '';
    $nomeProduto = $_POST['nomeProdEdt'] ?? '';
    $preco = $_POST['precoSabEdt'] ?? '';
    $imagemProduto = $_POST['imagemSabEdt'] ?? '';

    $produtoVariacaoController->editarProduto($idVariacao, $idProduto, $nomeProduto, $preco, $imagemProduto);
    
    header("Location: editaSabor.php?idProduto=$idProduto&idVariacao=$idVariacao");
    exit();
}
?>
<main>
    <div class="conteiner2">
        <h1 class="m-auto text-center pt-4 pb-4">Editar Sabor</h1>
        <div class="conteiner1">
            <div class="c1">
                <div class="c2">
                    <?php
                    if ($produtoVariacao && count($produtoVariacao) > 0) {
                        $produto = $produtoVariacao[0];
                        $idProduto = htmlspecialchars($produto['idProduto']);
                        $idVariacao = htmlspecialchars($produto['idVariacao']);
                        $nomeVariacao = htmlspecialchars($produto['nomeVariacao']);
                        $precoVariacao = htmlspecialchars($produto['precoVariacao']);
                        $fotoVariacao = htmlspecialchars($produto['fotoVariacao']);

                        echo '
                        <form action="' . htmlspecialchars($_SERVER["PHP_SELF"] . '?idVariacao=' . $idVariacao) . '" method="POST" id="formulario" class="formulario">
                            <input type="hidden" name="produto" value="' . $idProduto . '">
                            <input type="hidden" name="idVariacao" value="' . $idVariacao . '">
                            <label for="nome2">Nome:</label>
                            <input type="text" id="nome2" name="nomeProdEdt" placeholder="Nome" value="' . $nomeVariacao . '">

                            <label for="preco2">Preço</label>
                            <input type="text" id="preco2" name="precoSabEdt" placeholder="Preço" value="' . $precoVariacao . '">
                            
                            <label for="foto2">Foto:</label>
                            <input type="text" id="imagem2" name="imagemSabEdt" placeholder="imagem.png" value="' . $fotoVariacao . '">
                            <button type="submit" name="btnEditar">Salvar</button>
                        </form>';
                    } else {
                        echo '<p>Produto não encontrado.</p>';
                    }
                    ?>
                </div>
            </div>
        </div>
        <button class="voltar"><a href="editarSabores.php?produto=<?php echo $idProduto;?>">Voltar</a></button>
    </div>
</main>
<?php
include_once 'components/footer.php';
?>
<script src="script/header.js"></script>
<script src="script/adicionar.js"></script>
</body>
</html>
