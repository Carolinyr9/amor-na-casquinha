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
            echo "foi1;";
            if(!is_numeric($produto["valor"])){
                echo "<script>alert('O valor informado não é um número. Insira o valor novamente no formato correto');</script>";
                exit;
            }
echo "foi2;";
            if(!is_numeric($produto["quantidadeMinima"])){
                echo "<script>alert('O valor informado não é um número. Insira novamente no formato correto');</script>";
                exit;
            }
echo "foi3";
            if(!is_numeric($produto["quantidade"])){
                echo "<script>alert('O valor informado não é um número. Insira novamente no formato correto');</script>";
                exit;
            }
            echo "foi4;";
            if(!is_numeric($produto["quantidadeMinima"])){
                echo "<script>alert('O valor informado não é um número. Insira novamente no formato correto');</script>";
                exit;
            }
            echo "foi5;";
            if(!isset($produto["ocorrencia"]) || empty($produto["ocorrencia"])){
                $produto["ocorrencia"] = " ";
            }
            echo "foi6;";
            if(!isset($produto["quantidadeOcorrencia"]) || empty($produto["quantidadeOcorrencia"])){
                $produto["quantidadeOcorrencia"] = 0;
            }
            echo "foi7;";
            $result = $this->estoque->editarProdutoEstoque($produto);

            echo "foi10";
            // echo "<script>window.location.href = 'telaEstoque.php';</script>";
        }
        
    } 

?>