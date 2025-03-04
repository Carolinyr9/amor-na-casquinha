<?php 
namespace app\controller;

use app\model\ProdutoVariacao;
use app\controller\FotoController;

class ProdutoVariacaoController {
    private $produtoVariacao;

    public function __construct() {
        $this->produtoVariacao = new ProdutoVariacao();
    }

    public function selecionarVariacaoProdutos($idProduto) {
        return $this->produtoVariacao->ListarVariacaoPorTipo($idProduto);
    }

    public function adicionarVariacaoProduto($idProduto, $nomeProduto, $preco, $lote, $valor, $quantidade, $dataEntrada, $dataFabricacao, $dataVencimento, $quantidadeMinima, $imagem) {
        $fotoController = new FotoController($imagem);
        echo "foi3";
        $name_image = $fotoController->validatePicture();
        echo "fo14";
        if ($name_image) {
            echo "foi5";
            $this->produtoVariacao->adicionarVariacaoProduto($idProduto, $nomeProduto, $preco, $lote, $valor, $quantidade, $dataEntrada, $dataFabricacao, $dataVencimento, $quantidadeMinima, $name_image);
            echo "foi6";
            // header("Location: editarSabores.php?produto=$idProduto");
        } else {
            $errors = $fotoController->countErrors();
            echo '<script>alert("' . $errors . '")</script>';
        }
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
