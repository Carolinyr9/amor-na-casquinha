<?php
namespace app\controller2;

use app\repository\ItemPedidoRepository;
use app\model2\ItemPedido;
use app\utils\Logger;
use Exception;

class ItemPedidoController {
    private ItemPedidoRepository $repository;

    public function __construct(ItemPedidoRepository $repository = null) {
        $this->repository = $repository ?? new ItemPedidoRepository();
    }

    public function criarPedido($dados){
        try {
            if (empty($dados)) {
                return false;
            }
    
            $this->repository->criarPedido(
               $dados['idPedido'],
               $dados['idProduto'],
               $dados['quantidade']
            );
    
            $pedido = new Pedido(
                $dados['idPedido'],
                $dados['idProduto'],
                $dados['quantidade']
            );
    
            Logger::logInfo("Pedido criado com sucesso!");
            return true;
    
        } catch (Exception $e) {
            Logger::logError("Erro ao associar itens ao pedido: " . $e->getMessage());
            return false;
        }
    }

}