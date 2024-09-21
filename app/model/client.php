<?php 
    require_once '../config/database.php';

    class Client{
        private $id;
        private $nome;
        private $email;
        private $telefone;
        private $senha;
        private $endereco;
        private $conn;

        public function __construct(){
            $database = new DataBase;
            $this->conn = $database->getConnection();
        }

    }

?>