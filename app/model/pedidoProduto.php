<?php 
    require_once '../config/database.php';

    class OrderProduct{
        private $idPedidoProduto;
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