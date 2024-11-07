<?php
require_once '../model/Carrinho.php';

class CarrinhoController {
    private $carrinho;

    public function __construct() {
        $this->carrinho = new Carrinho();
    }

    public function adicionarProduto($variacaoId) {
        try {
            $this->carrinho->addProduto($variacaoId);
        } catch (Exception $e) {
            echo $e->getMessage();
        }
    }

    public function removerProduto($id) {
        $this->carrinho->removeProduto($id);
    }

    public function atualizarCarrinho() {
        $this->carrinho->atualizarCarrinho();
    }

    public function listarCarrinho() {
        return $this->carrinho->listarCarrinho();
    }

    public function calcularTotal() {
        return $this->carrinho->getTotal();
    }

    public function getPedidoData() {
        $total = $this->carrinho->getTotal();
        $isUserLoggedIn = isset($_SESSION["userEmail"]) && !empty($_SESSION["userEmail"]);
        return [
            "total" => $total,
            "isUserLoggedIn" => $isUserLoggedIn
        ];
    }

    public function limparCarrinho() {
        $this->carrinho->limparCarrinho();
    }
}
?>
