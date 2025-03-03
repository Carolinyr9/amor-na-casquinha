<?php
namespace app\controller;

use app\model\Pedido;

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

    public function criarPedido($email, $tipoFrete, $total, $frete, $meioDePagamento, $trocoPara, $itensCarrinho) {
        try {
            $id = $this->pedidoModel->criarPedido($email, $tipoFrete, $total, $frete, $meioDePagamento, $trocoPara);
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

    public function listarResumo($dataInicio, $dataFim) {
        try {
            return $this->pedidoModel->listarResumo($dataInicio, $dataFim);
        } catch (Exception $e) {
            echo "error" . $e->getMessage();
        }
    }

    public function listarTodosItensPedidos($dataInicio, $dataFim){
        try {
            return $this->pedidoModel->listarTodosItensPedidos($dataInicio, $dataFim);
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

    public function mudarStatus($idPedido, $usuario, $motivoCancelamento) {
        try {
            $this->pedidoModel->mudarStatus($idPedido, $usuario, $motivoCancelamento);
        } catch (Exception $e) {
            echo "error" . $e->getMessage();
        }
    }

    public function mudarStatusEntregador($idPedido, $status) {
        try {
            $this->pedidoModel->mudarStatusEntregador($idPedido, $status);
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

