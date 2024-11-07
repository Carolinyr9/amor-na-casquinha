<?php
require_once '../model/pedido.php';

class PedidoController {
    private $pedidoModel;

    public function __construct() {
        $this->pedidoModel = new Pedido();
    }

    public function listarPedidoPorCliente($email) {
        try {
            return $this->pedidoModel->listarPedidoPorCliente($email);
        } catch (Exception $e) {
            return ["error" => $e->getMessage()];
        }
    }

    public function listarPedidoPorId($idPedido) {
        try {
            return $this->pedidoModel->listarPedidoPorId($idPedido);
        } catch (Exception $e) {
            return ["error" => $e->getMessage()];
        }
    }

    public function criarPedido($email, $tipoFrete, $total) {
        try {
            return $this->pedidoModel->criarPedido($email, $tipoFrete, $total);
        } catch (Exception $e) {
            return ["error" => $e->getMessage()];
        }
    }

    public function listarPedidos() {
        try {
            return $this->pedidoModel->listarPedidos();
        } catch (Exception $e) {
            return ["error" => $e->getMessage()];
        }
    }

    public function atribuirEntregador($idPedido, $idEntregador) {
        try {
            $this->pedidoModel->atribuirEntregador($idPedido, $idEntregador);
        } catch (Exception $e) {
            echo "error" . $e->getMessage();
        }
    }
}
?>
