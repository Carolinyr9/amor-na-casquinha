<?php 
require_once '../model/produtoVariacao.php';

class ProdutoVariacaoController {
    private $produtoVariacao;

    public function __construct() {
        $this->produtoVariacao = new ProdutoVariacao();
    }

    public function selecionarVariacaoProdutos($idProduto) {
        return $this->produtoVariacao->selecionarVariacaoProdutos($idProduto);
    }

    public function adicionarProduto($idProduto, $nomeProduto, $preco, $foto) {
        return $this->produtoVariacao->adicionarProduto($idProduto, $nomeProduto, $preco, $foto);
    }

    public function selecionarProdutosPorID($idProduto) {
        return $this->produtoVariacao->selecionarProdutosPorID($idProduto);
    }

    public function selecionarProdutoPorID($idProduto) {
        try {
            return $this->produtoVariacao->selecionarProdutoPorID($idProduto);
        } catch (Exception $e) {
            echo "Erro ao obter produto: " . $e->getMessage();
        }
    }

    public function editarProduto($idVariacao, $idProduto, $nomeProduto, $preco, $imagemProduto) {
        return $this->produtoVariacao->editarProduto($idVariacao, $idProduto, $nomeProduto, $preco, $imagemProduto);
    }

    public function removerProduto($idProduto) {
        return $this->produtoVariacao->removerProduto($idProduto);
    }
}
?>
