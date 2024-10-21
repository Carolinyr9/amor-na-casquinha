<?php 
    require_once '../model/pedido.php';

    class PedidoController {
        private $pedido;

        public function __construct() {
            $this->pedido = new Pedido();
        }

        public function listarPedidoPorCliente($email){
            $this->pedido->listarPedidoPorCliente($email);
        }

        public function criarPedido($email, $tipoFrete, $qtdItems){
            $this->pedido->criarPedido($email, $tipoFrete, $qtdItems);
        }

        public function listarPedidos(){
            $this->pedido->listarPedidos();
        }
    }
?>