<?php 
    require_once '../model/entregador.php';

    class EntregadorController {
        private $entregador;

        public function __construct() {
            $this->entregador = new Entregador();
        }

        public function listarEntregadores($idPedido) {
            $this->entregador->listarEntregadores($idPedido);
        }

        public function listarEntregadorPorId($idEntregador) {
            $this->entregador->listarEntregadorPorId($idEntregador);
        }
        
    }
?>