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

        public function selecionarProdutosPorID($id){
            $this->produto->selecionarProdutosPorID($id);
        }

        public function selecionarProdutosFunc(){
            $this->produto->selecionarProdutosFunc();
        }

        public function adicionarProduto($nomeProduto, $marca, $descricao, $idFornecedor, $imagemProduto){
            $this->produto->adicionarProduto($nomeProduto, $marca, $descricao, $idFornecedor, $imagemProduto);
        }

        public function editarProduto($id, $nomeProduto, $marca, $descricao, $imagemProduto){
            $this->produto->editarProduto($id, $nomeProduto, $marca, $descricao, $imagemProduto);
        }

        function removerProduto($idProduto){
            $this->produto->removerProduto($idProduto);
        }
    }
?>