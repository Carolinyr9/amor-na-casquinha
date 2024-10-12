<?php 
    require_once '../config/database.php';

    class Pedido{
        private $idPedido;
        private $idCliente
        private $dataPedido;
        private $dataPagamento
        private $tipoFrete;
        private $rastreioFrete;
        private $idEndereco;
        private $valorTotal;
        private $qtdItens;
        private $dataCancelamento;
        private $motivoCancelamento;
        private $statusPedido

        public function __construct(){
            $database = new DataBase;
            $this->conn = $database->getConnection();
        }

    }

?>