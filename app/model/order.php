<?php 
    require_once '../config/database.php';

    class Order{
        private $id;
        private $data;
        private $total;
        private $status;
        private $cliente;

        public function __construct(){
            $database = new DataBase;
            $this->conn = $database->getConnection();
        }

    }

?>