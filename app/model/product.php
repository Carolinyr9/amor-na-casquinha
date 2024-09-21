<?php 
    require_once '../config/database.php';

    class Order{
        private $idProduto;
        private $nome;
        private $tipo;
        private $foto;
        private $preco;

        public function __construct(){
            $database = new DataBase;
            $this->conn = $database->getConnection();
        }

    }

?>