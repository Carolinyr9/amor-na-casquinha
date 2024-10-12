<?php 
    require_once '../model/carrinho.php';

    class CarrinhoController {
        private $carrinho;

        public function __construct() {
            $this->carrinho = new Carrinho();
        }

        public function addProduto($variacaoId){
            $this->carrinho->addProduto($variacaoId);
        }

        public function listarCarrinho() {
            $this->carrinho->listarCarrinho();
        }

        public function removeProduto($id) {
            $this->carrinho->removeProduto($id);
        }
    }
?>
