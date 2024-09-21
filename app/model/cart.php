<?php 
    require_once '../config/database.php';

    class Cart{
        private $id;
        private $data;
        private $total;
        private $produtos = new SplFixedArray(3);
        private $cliente;

        public function __construct(){
            $database = new DataBase;
            $this->conn = $database->getConnection();
        }

    }

?>