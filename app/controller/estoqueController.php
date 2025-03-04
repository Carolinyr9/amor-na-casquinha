<?php 
namespace app\controller;

use app\model\Estoque;
use app\model\Alertas;
use app\controller\ProdutoVariacaoController;

class EstoqueController{
    private $estoque;

    function __construct(){
        $this->estoque = new Estoque();
    }

    function listarEstoque(){
        return $this->estoque->listarEstoque();
    }

    function selecionarProdutoEstoquePorID($id){
        return $this->estoque->selecionarProdutoEstoquePorID($id);
    }

    function editarProdutoEstoque($produto){
        if(!is_numeric($produto["valor"])){
            echo "<script>alert('O valor informado não é um número. Insira o valor novamente no formato correto');</script>";
            exit;
        }

        if(!is_numeric($produto["quantidadeMinima"])){
            echo "<script>alert('O valor informado não é um número. Insira novamente no formato correto');</script>";
            exit;
        }

        if(!is_numeric($produto["quantidade"])){
            echo "<script>alert('O valor informado não é um número. Insira novamente no formato correto');</script>";
            exit;
        }

        if(!is_numeric($produto["quantidadeMinima"])){
            echo "<script>alert('O valor informado não é um número. Insira novamente no formato correto');</script>";
            exit;
        }

        if(!isset($produto["ocorrencia"]) || empty($produto["ocorrencia"])){
            $produto["ocorrencia"] = " ";
        }

        if(!isset($produto["quantidadeOcorrencia"]) || empty($produto["quantidadeOcorrencia"])){
            $produto["quantidadeOcorrencia"] = 0;
        }

        $result = $this->estoque->editarProdutoEstoque($produto);
        echo "<script>window.location.href = 'telaEstoque.php';</script>";
    }

    function excluirProdutoEstoque($idEstoque, $idVariacao){
        $this->estoque->excluirProdutoEstoque($idEstoque, $idVariacao);
        echo "<script>window.location.href = 'telaEstoque.php';</script>";
    }

    function verificarQuantidadeMinima(){
        $resultado = $this->estoque->verificarQuantidadeMinima();
        
        if ($resultado != NULL) {
            $variacoes = [];
            $variacaoController = new ProdutoVariacaoController();
            
            foreach ($resultado as $estoque) {
                $variacao = $variacaoController->selecionarProdutoPorID($estoque["idVariacao"]); 

                if ($variacao) {
                    $variacoes[] = [
                        'nome' => $variacao["nomeVariacao"], 
                        'quantidade' => $estoque["quantidade"]
                    ];
                }
            }
            if (!empty($variacoes)) {
                $alerta = new Alertas();
                $alerta->alertarQuantidadeMinimaEstoque($variacoes); 
            }
        }      
    }
    
} 

?>