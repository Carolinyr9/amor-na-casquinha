<?php 
    require_once '../model/cliente.php';

    class ClienteController {
        private $cliente;

        public function __construct() {
            $this->cliente = new Cliente();
        }

        public function getCliente($email){
            $this->cliente->getCliente($email);
        }

    }
?>