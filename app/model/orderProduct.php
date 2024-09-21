<?php 
    require_once '../config/database.php';

    class OrderProduct{
        private $idPedido;
        private $idProduto;
        private $variacao;
        private $quantidade;
        private $preco;
        
        public function __construct(){
            $database = new DataBase;
            $this->conn = $database->getConnection();
        }

    }

?>