<?php
namespace app\controller;

use app\repository\ItemPedidoRepository;
use app\model\ItemPedido;
use app\utils\helpers\Logger;
use Exception;

class ItemPedidoController {
    private ItemPedidoRepository $repository;

    public function __construct(ItemPedidoRepository $repository = null) {
        $this->repository = $repository ?? new ItemPedidoRepository();
    }

    public function criarPedido($dados){
        try {
            if (empty($dados['idPedido']) || empty($dados['idProduto']) || 
                empty($dados['quantidade'])) {
                Logger::logError("Dados inválidos para criação de itens do pedido.");
                return false;
            }
    
            $this->repository->criarPedido(
               $dados['idPedido'],
               $dados['idProduto'],
               $dados['quantidade']
            );
    
            $pedido = new ItemPedido(
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

    public function listarInformacoesPedido($idPedido){
        try{
            if (!is_numeric($idPedido) || !isset($idPedido) || empty($idPedido)) {
                return Logger::logError("Erro ao listar itens do pedido: ID inválido.");
            }

            $dados = $this->repository->listarInformacoesPedido($idPedido);
        
            if (empty($dados)) {
                Logger::logError("Erro ao listar informações do pedido: Nenhum pedido com o id {$idPedido} encontrado!");
                return false;
            }

            return $dados;

        } catch (Exception $e) {
            Logger::logError("Erro ao listar informações do pedido: " . $e->getMessage());
            return false;
        }
    }
        
    

}