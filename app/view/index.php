<?php
namespace app\view;

use app\controller\CategoriaProdutoController;

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
    <?php include_once '../utils/links/styleLinks.php'; ?>
    <link rel="stylesheet" href="style/index.css">
</head>
<body>
    <main>
        <h1 class="titulo">Boas-vindas ao Amor de Casquinha!</h1>
        <section>
            <div class="conteiner d-flex flex-column justify-content-center align-items-center rounded-4 p-4">
                <h3 class="subtitulo text-center pb-1">Explore nossas opções de sorvete</h3>
                <div class="d-flex flex-row flex-wrap gap-4 justify-content-center align-items-center">
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