<?php 
    require_once '../model/produto.php';

    class ProdutoController {
        private $produto;

        public function __construct() {
            $this->produto = new Produto();
        }

        public function selecionarProdutos(){
            $this->produto->selecionarProdutos();
        }

        public function selecionarVariacaoProdutos(){
            $this->produto->selecionarVariacaoProdutos();
        }
    }
?>