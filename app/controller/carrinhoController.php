<?php
namespace app\controller;

use app\model\Carrinho;
use app\utils\helpers\Logger;
use Exception;

class CarrinhoController {
    private $carrinho;

    public function __construct() {
        $this->carrinho = new Carrinho();
    }

    public function adicionarProduto($produto) {
        try {
            return $this->carrinho->adicionarProduto($produto);
        } catch (Exception $e) {
            Logger::logError("Erro ao adicionar produto ao carrinho: " . $e->getMessage());
            return false;
        }
    }

    public function removerProduto($id) {
        try {
            $this->carrinho->removeProduto($id);
        } catch (Exception $e) {
            Logger::logError("Erro ao remover produto do carrinho: " . $e->getMessage());
        }
    }

    public function atualizarCarrinho() {
        try {
            $this->carrinho->atualizarCarrinho();
        } catch (Exception $e) {
            Logger::logError("Erro ao atualizar carrinho: " . $e->getMessage());
        }
    }

    public function listarCarrinho() {
        try {
            return $this->carrinho->listarCarrinho();
        } catch (Exception $e) {
            Logger::logError("Erro ao listar carrinho: " . $e->getMessage());
            return [];
        }
    }

    public function calcularTotal() {
        try {
            return $this->carrinho->getTotal();
        } catch (Exception $e) {
            Logger::logError("Erro ao calcular total do carrinho: " . $e->getMessage());
            return 0;
        }
    }

    public function getPedidoData() {
        try {
            $total = $this->carrinho->getTotal();
            $isUserLoggedIn = isset($_SESSION["userEmail"]) && !empty($_SESSION["userEmail"]);
            return [
                "total" => $total,
                "isUserLoggedIn" => $isUserLoggedIn
            ];
        } catch (Exception $e) {
            Logger::logError("Erro ao obter dados do pedido: " . $e->getMessage());
            return [
                "total" => 0,
                "isUserLoggedIn" => false
            ];
        }
    }

    public function limparCarrinho() {
        try {
            $this->carrinho->limparCarrinho();
        } catch (Exception $e) {
            Logger::logError("Erro ao limpar carrinho: " . $e->getMessage());
        }
    }

    public function atualizarQuantidade($dados) {
        try {
            $this->carrinho->atualizarQuantidade($dados['id'], $dados['quantidade']);
        } catch (Exception $e) {
            Logger::logError("Erro ao atualizar quantidades no carrinho: " . $e->getMessage());
        }
    }
}
