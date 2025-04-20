<?php
namespace app\view;

use app\controller2\CategoriaProdutoController;

session_start();

include_once 'components/header.php';
$categoriaController = new CategoriaProdutoController();
$categorias = $categoriaController->listarCategorias();

?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Amor de Casquinha</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style/CabecalhoRodape.css">
    <link rel="shortcut icon" href="images/iceCreamIcon.ico" type="image/x-icon">
    <link rel="stylesheet" href="style/indexS.css">
</head>
<body>
    <main>
        <h1 class="m-auto text-center pt-4 pb-4">Boas-vindas ao Amor de Casquinha!</h1>
        <section>
            <div class="conteiner1 d-flex flex-column justify-content-center align-items-center rounded-4 p-4">
                <h3 class="text-center pb-1">Explore nossas opções de sorvete</h3>
                <div class="c1 d-flex flex-wrap flex-row justify-content-between align-items-center">
                    <?php foreach ($categorias as $categoria): ?>
                        <?php include 'components/categoriasCards.php'; ?>
                    <?php endforeach; ?>
                </div>
            </div>
        </section>
    </main>

    <?php include_once 'components/footer.php'; ?>
</body>
</html>