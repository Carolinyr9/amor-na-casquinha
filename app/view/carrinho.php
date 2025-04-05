<?php
session_start();
require_once '../config/blockURLAccess.php';
require_once '../../vendor/autoload.php';
use app\controller\CarrinhoController;

$carrinhoController = new CarrinhoController();

if (isset($_GET["add"])) {
    $carrinhoController->adicionarProduto($_GET["add"]);
}
if (isset($_GET["action"]) && $_GET["action"] === 'remove' && isset($_GET["item"])) {
    $carrinhoController->removerProduto($_GET["item"]);
}
$produtos = $carrinhoController->listarCarrinho();
$total = $carrinhoController->calcularTotal();

$data = json_decode(file_get_contents('php://input'), true);

if (isset($data['id']) && isset($data['quantidade'])) {
    $id = $data['id'];
    $quantidade = (int)$data['quantidade'];

    if (isset($_SESSION["cartArray"][$id])) {
        $carrinhoController->atualizarQtdd($id, $quantidade);

        $novoTotal = $carrinhoController->calcularTotal();

        echo json_encode(['success' => true, 'novoTotal' => $novoTotal]);
    } else {
        echo json_encode(['success' => false, 'error' => 'Produto não encontrado']);
    }
    exit;
}


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
            <form method="post" action="notaFiscal.php" class="container-fluid d-flex flex-column align-items-center conteiner1 rounded-4 mt-4 py-3 px-2">
                <input type="hidden" name="cart" value="1">
                <input type="hidden" name="total" value="<?= htmlspecialchars($total); ?>">

                <?php if (!empty($produtos)): ?>
                    <?php foreach ($produtos as $produto): ?>
                        <div class="borders my-3 py-3 card-width">
                            <div class="row">
                                <div class="col col-4 c2 d-flex align-items-center justify-content-center">
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
                                <div class="col col-3 d-flex align-items-start">
                                    <a href="?action=remove&item=<?= $produto['id'] ?>" class="btn-excluir rounded-3 text-decoration-none">Excluir</a>
                                </div>
                                <div class="col d-flex align-items-start col-7" style="margin-left: 13px;">
                                    <p>Quantid.</p>
                                    <select 
                                        class="ms-2 border-0" 
                                        id="select<?= $produto['id'] ?>" 
                                        name="select<?= $produto['id'] ?>" 
                                        onchange="updateProdutoQuantidade(<?= $produto['id'] ?>, this.value)">
                                        <?= $produto['quantidades'] ?>
                                    </select>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                    <div class="d-flex flex-row justify-content-between w-75 my-3">
                        <h4>Total</h4>
                        <p id="totalValue">R$ <?= $total ?></p>
                    </div>
                    <input class="btn-concluir fs-5 rounded-4" type="submit" value="Concluir"/>
                <?php else: ?>
                    <p>Carrinho está vazio!</p>
                <?php endif; ?>
            </form>
            <button class="voltar fs-5 fw-bold mt-5 border-0 rounded-4"><a class="text-decoration-none" href="index.php">Voltar</a></button>
        </div>
    </main>
    <?php include_once 'components/footer.php'; ?>
    <script src="script/atualizarQtddCarrinho.js"></script>
</body>
</html>
