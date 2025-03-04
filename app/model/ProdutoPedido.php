<?php 
    require_once '../config/DataBase.php';

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