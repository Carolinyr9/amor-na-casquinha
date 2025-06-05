<?php
use app\controller\EstoqueController;

$estoqueController = new EstoqueController();
$produtosBaixoEstoque = $estoqueController->verificarQuantidadeMinima();

if (!empty($produtosBaixoEstoque)) {
    $mensagem = "Atenção! Estoque baixo para os produtos:\n";
    foreach ($produtosBaixoEstoque as $produto) {
        $mensagem .= "- Produto ID: {$produto['idProduto']} (Quantidade: {$produto['quantidade']})\n";
    }
    echo "<script>alert(`" . addslashes($mensagem) . "`);</script>";
}
?>
