<?php 
require_once '../model/produtoVariacao.php';
require_once 'fotoController.php';

class ProdutoVariacaoController {
    private $produtoVariacao;

    public function __construct() {
        $this->produtoVariacao = new ProdutoVariacao();
    }

    public function selecionarVariacaoProdutos($idProduto) {
        return $this->produtoVariacao->selecionarVariacaoProdutos($idProduto);
    }

    public function adicionarVariacaoProduto($idProduto, $nomeProduto, $preco, $lote, $valor, $quantidade, $dataEntrada, $dataFabricacao, $dataVencimento, $quantidadeMinima, $imagem) {
        $pictureController = new Picture($imagem);
        echo "foi3";
        $name_image = $pictureController->validatePicture();
        echo "fo14";
        if ($name_image) {
            echo "foi5";
            $this->produtoVariacao->adicionarVariacaoProduto($idProduto, $nomeProduto, $preco, $lote, $valor, $quantidade, $dataEntrada, $dataFabricacao, $dataVencimento, $quantidadeMinima, $name_image);
            echo "foi6";
            // header("Location: editarSabores.php?produto=$idProduto");
        } else {
            $errors = $pictureController->countErrors();
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
