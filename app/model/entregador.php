<?php 
    require_once '../config/database.php';

    class Entregador{
        private $id;
        private $nome;
        private $email;
        private $telefone;
        private $senha;
        private $cnh;
        private $pedidos;
        private $conn;

        public function __construct(){
            $database = new DataBase;
            $this->conn = $database->getConnection();
        }
    }
?>