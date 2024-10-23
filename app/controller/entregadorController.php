<?php 
    require_once '../model/entregador.php';

    class EntregadorController {
        private $entregador;

        public function __construct() {
            $this->entregador = new Entregador();
        }

        
    }
?>