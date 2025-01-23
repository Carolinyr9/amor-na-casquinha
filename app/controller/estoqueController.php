<?php 
    require_once '../model/estoque.php';

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
        
    } 

?>