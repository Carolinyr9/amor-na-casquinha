<?php
namespace app\view;
use app\controller\ProdutoController;

session_start();
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Amor de Casquinha</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="style/CabecalhoRodape.css">
    <link rel="shortcut icon" href="images/iceCreamIcon.ico" type="image/x-icon">
    <link rel="stylesheet" href="style/indexS.css">
</head>
<body>
    <?php
        include_once 'components/header.php';
        $produtoController = new produtoController();
        $produtos = $produtoController->listarProdutos();
    ?>
    <main>
        <h1 class="m-auto text-center pt-4 pb-4">Boas-vindas ao Amor de Casquinha!</h1>
        <section>
            <div class="conteiner1 d-flex flex-column justify-content-center align-items-center rounded-4 p-4">
                <h3 class="text-center pb-1">Explore nossas opções de sorvete</h3>
                <div class="c1 d-flex flex-wrap flex-row justify-content-between align-items-center">
                    <?php foreach ($produtos as $produto): ?>
                        <div class="card categ d-flex align-items-center rounded-4 h-auto border-0 mt-3">
                            <picture>
                                <img src="../images/<?= htmlspecialchars($produto["foto"]) ?>" alt="<?= htmlspecialchars($produto["nome"]) ?>" class="imagem">
                            </picture>
                            <div class="d-flex align-items-center flex-column c2">
                                <h4 class="text-center m-auto"><?= htmlspecialchars($produto["nome"]) ?></h4>
                                <button class=" border-0 rounded-4 fw-bold m-1"><a class="text-decoration-none text-body" href="sabores.php?produto=<?= htmlspecialchars($produto['idProduto']) ?>">ver</a></button>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </section>
    </main>
    <?php
        include_once 'components/footer.php';
    ?>
</body>
</html>
