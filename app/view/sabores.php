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
    <title>Sabores</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link rel="stylesheet" href="style/CabecalhoRodape.css">
    <link rel="stylesheet" href="style/saboresS.css">
    <link rel="shortcut icon" href="images/iceCreamIcon.ico" type="image/x-icon">
</head>
<body>
    <?php
        include_once 'components/header.php';
        require_once '../controller/produtoVariacaoController.php';
        $produtoVariacaoController = new produtoVariacaoController();
    ?>
   <main>
        <h1 class="m-auto text-center pt-4 pb-4">Variações</h1>
        <div class="container d-flex flex-column align-items-center justify-content-center">
            <div class="container1 d-flex flex-row flex-wrap justify-content-center">
                <?php
                    if (isset($_GET["produto"])) {
                        $variacoes = $produtoVariacaoController->selecionarVariacaoProdutos($_GET["produto"]);
                        
                        foreach ($variacoes as $variacao) {
                            $redirectTo = 'carrinho.php?add=' . htmlspecialchars($variacao['idVariacao']);
                            echo '
                            <div class="c1 d-flex flex-column rounded-4">
                                <div class="c2">
                                    <div><img src="../images/' . htmlspecialchars($variacao["fotoVariacao"]) . '" alt="' . htmlspecialchars($variacao["nomeVariacao"]) . '" class="imagem"></div>
                                    <div class="c3">
                                        <h3 class="titulo px-2">' . htmlspecialchars($variacao["nomeVariacao"]) . '</h3>
                                        <div class="preco d-flex flex-row justify-content-between px-2">
                                            <p>Preço</p>
                                            <span>R$ ' . htmlspecialchars($variacao["precoVariacao"]) . '</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="botao text-center d-flex justify-content-evenly mt-3">
                                    <button class="add border-0 rounded-4"><a class="text-decoration-none" href="' . $redirectTo . '">Adicionar ao Carrinho</a></button>
                                </div>
                            </div>';
                        }
                    } else {
                        echo '<p>Produto não especificado.</p>';
                    }
                ?>      
            </div>
            <button class="voltar fs-4 fw-bold roudend-3 mt-5 border-0"><a class="text-decoration-none" href="index.php">Voltar</a></button>
        </div>
    </main>
    <?php
        include_once 'components/footer.php';
    ?>
</body>
</html>
