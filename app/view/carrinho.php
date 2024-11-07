<?php
session_start();
require_once '../controller/carrinhoController.php';

$carrinhoController = new CarrinhoController();

if (isset($_GET["add"])) {
    $carrinhoController->adicionarProduto($_GET["add"]);
}
if (isset($_GET["action"]) && $_GET["action"] === 'remove' && isset($_GET["item"])) {
    $carrinhoController->removerProduto($_GET["item"]);
}

$produtos = $carrinhoController->listarCarrinho();
$total = $carrinhoController->calcularTotal();
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Carrinho</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style/CabecalhoRodape.css">
    <link rel="stylesheet" href="style/carrinhoS.css">
</head>
<body>
    <?php include_once 'components/header.php'; ?>
    <main>
        <h1 class="m-auto text-center pt-4 pb-4">Carrinho</h1>
        <div class="container d-flex flex-column align-items-center">
            <form method="post" action="notaFiscal.php" class="container-fluid d-flex flex-column align-items-center conteiner1">
                <input type="hidden" name="cart" value="1">
                <!-- Campo oculto para enviar o total -->
                <input type="hidden" name="total" value="<?= htmlspecialchars($total); ?>">

                <?php if (!empty($produtos)): ?>
                    <?php foreach ($produtos as $produto): ?>
                        <div class="c1">
                            <div class="row">
                                <div class="col col-4 c2">
                                    <img src="../images/<?= $produto['foto'] ?>" alt="<?= $produto['nome'] ?>" class="imagem">
                                </div>
                                <div class="col c3">
                                    <h3><?= $produto['nome'] ?></h3>
                                    <div class="preco d-flex flex-row justify-content-between px-2">
                                        <p>Preço</p>
                                        <span>R$ <?= $produto['preco'] ?></span>
                                    </div>
                                </div>
                            </div>
                            <div class="botao text-center d-flex justify-content-evenly mt-3 flex-row">
                                <div class="col col-3 d-flex align-items-start excl">
                                    <a href="?action=remove&item=<?= $produto['id'] ?>" class="b-excluir">Excluir</a>
                                </div>
                                <div class="col d-flex align-items-start col-7">
                                    <p>Quantid.</p>
                                    <select id="select<?= $produto['id'] ?>" name="select<?= $produto['id'] ?>">
                                        <?= $produto['quantidades'] ?>
                                    </select>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                    <div class="c4">
                        <h4>Total</h4>
                        <p>R$ <?= $total ?></p>
                    </div>
                    <input class="conc" type="submit" value="Concluir"/>
                <?php else: ?>
                    <p>Carrinho está vazio!</p>
                <?php endif; ?>
            </form>
            <button class="voltar"><a href="index.php">Voltar</a></button>
        </div>
    </main>
    <?php include_once 'components/footer.php'; ?>
</body>
</html>
