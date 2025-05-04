<?php
namespace app\view;
use app\controller2\ProdutoController;

session_start();
include_once 'components/header.php';

$produtoController = new ProdutoController();
$categoriaId = $_GET['categoria'] ?? null;
$produtos = $categoriaId ? $produtoController->selecionarProdutosAtivos($categoriaId) : [];
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>5
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sabores</title>
    <link rel="stylesheet" href="style/CabecalhoRodape.css">
    <link rel="stylesheet" href="style/components/botao.css">
    <link rel="stylesheet" href="style/components/cards.css">
    <link rel="stylesheet" href="style/base/global.css">
    <link rel="stylesheet" href="style/base/variables.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-SgOJa3DmI69IUzQ2PVdRZhwQ+dy64/BUtbMJw1MZ8t5HZApcHrRKUc4W0kG879m7" crossorigin="anonymous">  
</head>
<body>
<main>
    <section class="container-md">
        <h1 class="titulo">Sabores</h1>
        <div class="conteiner container-md d-flex flex-column justify-content-center align-items-center rounded-4 p-4 m-md-0">
                <div class="d-flex flex-md-column flex-row flex-wrap gap-4 justify-content-center align-items-center">
                    <?php
                        if (is_array($produtos) && count($produtos) > 0) {
                            foreach ($produtos as $produto) {
                                include 'components/produtosCards.php';
                            }
                        } else {
                            echo '<p>Nenhum produto encontrado.</p>';
                        }
                    ?>
                </div>
        <a class="botao botao-secondary wx-auto mt-5" href="index.php">Voltar</a>
        </div>
    </section>
</main>
<?php include_once 'components/footer.php'; ?>
</body>
</html>
