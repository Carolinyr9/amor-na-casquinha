<?php 
    require_once '../config/database.php';

    class DeliveryMan{
        private $id;
        private $nome;
        private $login;
        private $telefone;
        private $senha;
        private $cnh;
        private $conn;

        public function __construct(){
            $database = new DataBase;
            $this->conn = $database->getConnection();
        }

    }

?>