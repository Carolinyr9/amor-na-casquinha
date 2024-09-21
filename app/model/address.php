<?php 
    require_once '../config/database.php';

    class Adress{
        private $id;
        private $cep;
        private $rua;
        private $numero;
        private $bairro;
        private $complemento;

        public function __construct(){
            $database = new DataBase;
            $this->conn = $database->getConnection();
        }

    }

?>