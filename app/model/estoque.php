<?php 
require_once '../config/database.php';

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
            $estoque = $cmd->fetchAll();
            return $estoque;
        } catch (PDOException $e) {
            echo "Erro no banco de dados: " . $e->getMessage();
        }
    }

} 

?>