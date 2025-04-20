<?php 
namespace app\comtroller2;

use app\repository\PedidoRepository;
use app\repository\ClienteController;
use app\model\Pedido;

class PedidoController {
    private $repositorio;

    public function __construct() {
        $this->repostorio = new PedidoRepository();
    }

    public function listarPedidoPorEmailCliente($email) {
        $clienteController = new ClienteController();
        $dados = $clienteController->listarClientePorEmail($email);
        if(isset($dados['error'])) {
            return $dados['error'];
        } else {
            $pedidos = $this->repositorio->listarPedidoPorIdCliente($id);
            return $pedidos ?: ["error" => "Nenhum pedido encontrado para o cliente!"];
        }
    }
    
    public function listarPedidoPorId($idPedido) {
        if(!isset($idPedido) || empty($idPedido)) {
            return ["error" => "ID do pedido não fornecido!"];
        }

        $pedido = $this->repositorio->listarPedidoPorId($idPedido);
        return $pedido ?: ["error" => "Pedido não encontrado!"];
    }

    public function listarInformacoesPedido($idPedido) {
        if(!isset($idPedido) || empty($idPedido)) {
            return ["error" => "ID do pedido não fornecido!"];
        }

        $pedido = $this->repositorio->listarInformacoesPedido($idPedido);
        return $pedido ?: ["error" => "Pedido não encontrado!"];
    }

    public function criarPedido($email, $tipoFrete, $valorTotal, $frete, $meioDePagamento, $trocoPara) {
        $clienteController = new ClienteController();
        $dados = $clienteController->listarClientePorEmail($email);

        if(isset($dados['error'])) {
            return $dados['error'];
        } else {
            $idCliente = $dados['idCliente'];
        }
        
        if(!isset($tipoFrete) || empty($tipoFrete)) {
            $erro = ["error" => "Tipo de frete não fornecido!"];
        }

        if(!isset($valorTotal) || empty($valorTotal)) {
            $erro = ["error" => "Valor total não fornecido!"];
        }

        if(!isset($frete) || empty($frete)) {
            $erro = ["error" => "Frete não fornecido!"];
        }

        if(!isset($meioDePagamento) || empty($meioDePagamento)) {
            $erro = ["error" => "Meio de pagamento não fornecido!"];
        }

        if(!isset($trocoPara) || empty($trocoPara)) {
            $erro = ["error" => "Troco para não fornecido!"];
        }

        $pedido = new Pedido();
        $pedido->setEmail($email);
        $pedido->setTipoFrete($tipoFrete);
        $pedido->setValorTotal($valorTotal);
        $pedido->setFrete($frete);
        $pedido->setMeioDePagamento($meioDePagamento);
        $pedido->setTrocoPara($trocoPara);

        $this->repositorio->criarPedido(
                $pedido->getEmail(),
                $pedido->getTipoFrete(),
                $pedido->getValorTotal(),
                $pedido->getFrete(),
                $pedido->getMeioDePagamento(),
                $pedido->getTrocoPara()
            );
    }
}