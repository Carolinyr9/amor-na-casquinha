<?php
session_start();
require_once '../config/blockURLAccess.php';
use app\controller\ProdutoVariacaoController;
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Excluir Sabor</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link rel="stylesheet" href="style/CabecalhoRodape.css">
    <link rel="stylesheet" href="style/excluirProdS.css">
    <link rel="shortcut icon" href="images/iceCreamIcon.ico" type="image/x-icon">
</head>
<body>
    
    <?php
        include_once 'components/header.php';
        require_once '../controller/produtoVariacaoController.php';
        $produtoVariacaoController = new produtoVariacaoController();
        $produtoVariacaoId = $_GET['idVariacao'] ?? null;
        $produtoVariacao = $produtoVariacaoId ? $produtoVariacaoController->selecionarProdutosPorID($produtoVariacaoId) : null;

        // Acessa o primeiro elemento do array retornado, se existir
        if ($produtoVariacao) {
            $produtoVariacao = $produtoVariacao[0]; // Aqui estamos pegando o primeiro produto da lista
        }

        if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['idProdutoExcl'])) {
            $idProduto = $_POST['idProdutoExcl'];
            $produtoVariacaoController->removerProduto($idProduto);
            header("Location: editarProdutos.php?success=true");
            exit();
        }
    ?>

    <main class="d-flex flex-column justify-content-center align-items-center">
        <h1 class="m-auto text-center pt-4 pb-4">Excluir Sabor</h1>
            <h4 class="text-center">Tem certeza que deseja excluir esse sabor?</h4>
                <div class="c1 mx-auto my-4 p-2 rounded-4 d-flex align-items-center flex-column justify-content-center">
                    <?php if ($produtoVariacao): ?>
                        <div class="d-flex align-items-center flex-column">
                            <picture>
                                <img src="../images/<?= htmlspecialchars($produtoVariacao["fotoVariacao"]) ?>" alt="<?= htmlspecialchars($produtoVariacao["nomeVariacao"]) ?>" class="imagem">
                            </picture>
                            <div class="d-flex align-items-center flex-column">
                                <h4><?= htmlspecialchars($produtoVariacao["nomeVariacao"]) ?></h4>
                                <p>Número de Identificação: <?= htmlspecialchars($produtoVariacao["idVariacao"]) ?></p>
                                <p>Preço: <?= htmlspecialchars($produtoVariacao["precoVariacao"]) ?></p>
                            </div>
                        </div>
                        <form action="" method="POST" id="formulario" class="formulario">
                            <input type="hidden" name="idProdutoExcl" value="<?= htmlspecialchars($produtoVariacao["idVariacao"]) ?>">
                            <button type="submit" class="btnExcluir border-0 rounded-4 px-3 fw-bold">Excluir</button>
                        </form>
                    <?php else: ?>
                        <p>Produto não encontrado.</p>
                    <?php endif; ?>
                </div>
            <button class="voltar m-auto border-0 rounded-4 fw-bold"><a class="text-decoration-none color-black" href="editarProdutos.php">Voltar</a></button>
    </main>
    
    <?php
        include_once 'components/footer.php';
    ?>
    
    <script src="script/header.js"></script>
</body>
</html>
