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
    <title>Excluir Sabor</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link rel="stylesheet" href="style/CabecalhoRodape.css">
    <link rel="stylesheet" href="style/excluirSaborS.css">
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

    <main>
        <h1>Desativar Sabor</h1>
    
        <div class="conteiner">
            <h3>Tem certeza que deseja desativar esse Sabor?</h3>
            
            <div class="conteiner1">   
                <div class="c1">
                    <?php if ($produtoVariacao): ?>
                        <div class="categ d-flex align-items-center">
                            <picture>
                                <img src="../images/<?= htmlspecialchars($produtoVariacao["fotoVariacao"]) ?>" alt="<?= htmlspecialchars($produtoVariacao["nomeVariacao"]) ?>" class="imagem">
                            </picture>
                            <div class="d-flex align-items-center flex-column c2">
                                <h4><?= htmlspecialchars($produtoVariacao["nomeVariacao"]) ?></h4>
                                <p>Número de Identificação: <?= htmlspecialchars($produtoVariacao["idVariacao"]) ?></p>
                                <p>Preço: <?= htmlspecialchars($produtoVariacao["precoVariacao"]) ?></p>
                            </div>
                        </div>
                        <form action="" method="POST" id="formulario" class="formulario">
                            <input type="hidden" name="idProdutoExcl" value="<?= htmlspecialchars($produtoVariacao["idVariacao"]) ?>">
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
    
    <?php
        include_once 'components/footer.php';
    ?>
    
    <script src="script/header.js"></script>
</body>
</html>
