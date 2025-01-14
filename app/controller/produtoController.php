<?php
require_once '../model/Produto.php';

class ProdutoController {
    private $produtoModel;

    public function __construct() {
        $this->produtoModel = new Produto();
    }

    public function listarProdutos() {
        try {
            return $this->produtoModel->selecionarProdutos();
        } catch (Exception $e) {
            echo "Erro ao listar produtos: " . $e->getMessage();
        }
    }

    public function obterProdutoPorID($idProduto) {
        try {
            return $this->produtoModel->selecionarProdutosPorID($idProduto);
        } catch (Exception $e) {
            echo "Erro ao obter produto: " . $e->getMessage();
        }
    }

    public function selecionarProdutoPorID($idProduto) {
        try {
            return $this->produtoModel->selecionarProdutoPorID($idProduto);
        } catch (Exception $e) {
            echo "Erro ao obter produto: " . $e->getMessage();
        }
    }

    public function adicionarProduto($nomeProduto, $marca, $descricao, $idFornecedor, $imagemProduto) {
        try {
            return $this->produtoModel->adicionarProduto($nomeProduto, $marca, $descricao, $idFornecedor, $imagemProduto);
        } catch (Exception $e) {
            echo "Erro ao adicionar produto: " . $e->getMessage();
        }
    }

    public function editarProduto($idProduto, $nomeProduto, $marca, $descricao, $imagemProduto) {
        try {
            return $this->produtoModel->editarProduto($idProduto, $nomeProduto, $marca, $descricao, $imagemProduto);
        } catch (Exception $e) {
            echo "Erro ao editar produto: " . $e->getMessage();
        }
    }

    public function removerProduto($idProduto) {
        try {
            return $this->produtoModel->removerProduto($idProduto);
        } catch (Exception $e) {
            echo "Erro ao remover produto: " . $e->getMessage();
        }
    }
}
?>
