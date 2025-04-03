<?php 
namespace app\model;

use app\config\DataBase;
use PDO;
use PDOException;

class Estoque{
    private $idEstoque;
    private $idProduto;
    private $idVariacao;
    private $dtEntrada;
    private $quantidade;
    private $dtFabricação;
    private $dtVencimento;
    private $lote;
    private $precoCompra;
    private $qtdMinima;
    private $qtdVendida;
    private $qtdOcorrencia;
    private $ocorrencia;
    private $desativado;
    private $conn;

    public function __construct(){
        $database = new DataBase;
        $this->conn = $database->getConnection();
    }

    public function listarEstoque(){
        try{
            $cmd = $this->conn->prepare("CALL ListarEstoque()");
            $cmd->execute();
            $estoque = $cmd->fetchAll(PDO::FETCH_ASSOC);
            return $estoque;
        } catch (PDOException $e) {
            echo "Erro no banco de dados: " . $e->getMessage();
        }
    }

    public function selecionarProdutoEstoquePorID($idEstoque){
        try{
            $cmd = $this->conn->prepare("CALL selecionarProdutoEstoquePorID(?)");
            $cmd->bindParam(1,$idEstoque);
            $cmd->execute();
            $estoque = $cmd->fetchAll(PDO::FETCH_ASSOC);
            return $estoque;
        } catch (PDOException $e){
            echo "Erro no banco de dados: " . $e->getMessage();
        }
    }

    public function editarProdutoEstoque($produto){
        try{
            $cmd = $this->conn->prepare("CALL EditarProdutoEstoque(?,?,?,?,?,?,?,?,?)");
            $cmd->bindParam(1, $produto["idEstoque"]);
            $cmd->bindParam(2, $produto["dataEntrada"]);
            $cmd->bindParam(3, $produto["quantidade"]);
            $cmd->bindParam(4, $produto["dataFabricacao"]);
            $cmd->bindParam(5, $produto["dataVencimento"]);
            $cmd->bindParam(6, $produto["valor"]);
            $cmd->bindParam(7, $produto["quantidadeMinima"]);
            $cmd->bindParam(8, $produto["quantidadeOcorrencia"]);
            $cmd->bindParam(9, $produto["ocorrencia"]);
            if (!$cmd->execute()) {
                $errorInfo = $cmd->errorInfo();
                echo "Erro ao executar procedimento: " . $errorInfo[2];
            }
            
        } catch (PDOException $e){
            echo "Erro no banco de dados: " . $e->getMessage();
        }
    }

    function excluirProdutoEstoque($idEstoque, $idVariacao){ // qual a privacidade???
        try{
            $cmd = $this->conn->prepare("CALL DesativarEstoqueProdutoPorId(?, ?)");
            $cmd->bindParam(1, $idEstoque);
            $cmd->bindParam(2, $idVariacao);
            // $cmd->execute();
            if (!$cmd->execute()) {
                $errorInfo = $cmd->errorInfo();
                echo "Erro ao executar procedimento: " . $errorInfo[2];
            } else {
                echo "<script>alert('Produto excluído com sucesso!');</script>"; // vamos mudar isso, agora o retorno n vai mais ser assim ok?
            }
        } catch (PDOException $e){
            echo "Erro no banco de dados: " . $e->getMessage();
        }
    }

    function verificarQuantidadeMinima() { // privacidadeeee?????
        try{
            $cmd = $this->conn->prepare("CALL VerificarQuantidadeMinima()");
            $cmd->execute();
            $resultado = $cmd->fetchAll(PDO::FETCH_ASSOC);
            
            if(count($resultado) > 0){
                return $resultado;
            }else{
                return NULL;
            }
        } catch (PDOException $e){
            echo "Erro ao executar o procedimento: " . $e->getMessage();
        }
    }

} 

?>