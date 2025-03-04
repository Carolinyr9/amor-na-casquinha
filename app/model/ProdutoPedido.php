<?php 
namespace app\model;

use app\config\DataBase;
use PDO;
use PDOException;

class ProdutoPedido{
    private $idProdutoPedido;
    private $idProduto;
    private $idPedido;
    private $variacao;
    private $quantidade;
    private $total;
    private $desativado;
    
    public function __construct(){
        $database = new DataBase;
        $this->conn = $database->getConnection();
    }

}

?>