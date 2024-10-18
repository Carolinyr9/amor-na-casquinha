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

        public function selecionarVariacaoProdutosFunc($idProduto){
            $this->produtoVariacao->selecionarVariacaoProdutosFunc($idProduto);
        }

        public function adicionarProduto($idProduto, $nomeProduto, $preco, $foto){
            $this->produtoVariacao->adicionarProduto($idProduto, $nomeProduto, $preco, $foto);
        }

        public function selecionarProdutosPorID($idProduto){
            $this->produtoVariacao->selecionarProdutosPorID($idProduto);
        }

        public function editarProduto($idVariacao, $idProduto, $nomeProduto, $preco, $imagemProduto){
            $this->produtoVariacao->editarProduto($idVariacao, $idProduto, $nomeProduto, $preco, $imagemProduto);
        }

        public function removerProduto($idProduto){
            $this->produtoVariacao->removerProduto($idProduto);
        }
    }
?>