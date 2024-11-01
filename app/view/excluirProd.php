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
    <title>Excluir Produto</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
    <link rel="stylesheet" href="style/CabecalhoRodape.css">
    <link rel="stylesheet" href="style/excluirProdS.css">
    <link rel="shortcut icon" href="images/iceCreamIcon.ico" type="image/x-icon">
</head>
<body>
    <?php
    include_once 'components/header.php';
    require_once '../controller/produtoController.php';

    $produtoController = new ProdutoController();
    $produtoId = $_GET['produto'] ?? null;
    $produto = $produtoId ? $produtoController->obterProdutoPorID($produtoId) : null;

    if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['idProdutoExcl'])) {
        $idProduto = $_POST['idProdutoExcl'];
        $produtoController->removerProduto($idProduto);
        header("Location: editarProdutos.php");
        exit();
    }
    ?>
    <main>
        <h1 class="m-auto text-center pt-4 pb-4">Excluir Produto</h1>
        <div class="container">
            <h3>Tem certeza que deseja excluir esse produto?</h3>
            <div class="container1 text-center">   
                <div class="c1">
                    <?php if ($produto): ?>
                        <div class="categ d-flex align-items-center">
                            <picture>
                                <img src="../images/<?= htmlspecialchars($produto["foto"]) ?>" alt="<?= htmlspecialchars($produto["nome"]) ?>" class="imagem">
                            </picture>
                            <div class="d-flex align-items-center flex-column c2">
                                <h4><?= htmlspecialchars($produto["nome"]) ?></h4>
                                <p>Descrição: <?= htmlspecialchars($produto["descricao"]) ?></p>
                                <p>Número de Identificação: <?= htmlspecialchars($produto["idProduto"]) ?></p>
                            </div>
                        </div>
                        <form action="" method="POST" id="formulario" class="formulario">
                            <input type="hidden" name="idProdutoExcl" value="<?= htmlspecialchars($produto['idProduto']) ?>">
                            <button type="submit" class="btn btn-danger">Excluir</button>
                        </form>
                    <?php else: ?>
                        <p>Produto não encontrado.</p>
                    <?php endif; ?>
                </div>
            </div>
            <button class="voltar"><a href="editarProdutos.php">Voltar</a></button>
        </div>
    </main>
    <?php include_once 'components/footer.php'; ?>
</body>
</html>
