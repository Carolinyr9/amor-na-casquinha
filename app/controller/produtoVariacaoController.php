<?php 
    require_once '../model/produtoVariacao.php';

    class ProdutoVariacaoController {
        private $produtoVariacao;

        public function __construct() {
            $this->produtoVariacao = new ProdutoVariacao();
        }

        public function selecionarVariacaoProdutos($idProduto){
            $this->produtoVariacao->selecionarVariacaoProdutos($idProduto);
        }
    }
?>