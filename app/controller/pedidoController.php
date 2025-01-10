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

    public function criarPedido($email, $tipoFrete, $total, $frete, $meioDePagamento, $itensCarrinho) {
        try {
            $id = $this->pedidoModel->criarPedido($email, $tipoFrete, $total, $frete, $meioDePagamento);
            $this->pedidoModel->salvarItensPedido($itensCarrinho, $id);
        } catch (Exception $e) {
            return ["error" => $e->getMessage()];
        }
    }
    

    public function listarPedidos() {
        try {
            return $this->pedidoModel->listarPedidos();
        } catch (Exception $e) {
            echo "error" . $e->getMessage();
        }
    }

    public function listarInformacoesPedido($pedidoId) {
        try {
            return $this->pedidoModel->listarInformacoesPedido($pedidoId);
        } catch (Exception $e) {
            echo "error" . $e->getMessage();
        }
    }

    public function listarTodosItensPedidos(){
        try {
            return $this->pedidoModel->listarTodosItensPedidos();
        } catch (Exception $e) {
            echo "error" . $e->getMessage();
        }
    }

    public function listarInformacoesPedido($pedidoId) {
        try {
            return $this->pedidoModel->listarInformacoesPedido($pedidoId);
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

    public function listarPedidosEntregador($emailEntregador) {
        try {
            return $this->pedidoModel->listarPedidosEntregador($emailEntregador);
        } catch (Exception $e) {
            return "error" . $e->getMessage();
        }
    }

    public function mudarStatus($idPedido, $usuario) {
        try {
            $this->pedidoModel->mudarStatus($idPedido, $usuario);
        } catch (Exception $e) {
            echo "error" . $e->getMessage();
        }
    }

    public function calcularFrete($cep) {
        try {
            return $this->pedidoModel->calcularFrete($cep);
        } catch (Exception $e) {
            return "error" . $e->getMessage();
        }
    }
    
}
?>

