<?php 
namespace app\model;

use app\config\DataBase;
use PDO;
use PDOException;
use app\utils\helpers\Logger;

class Carrinho {
    private $total;
    private $produtos;

    public function __construct($total = 0, $produtos = []) {
        $this->total = $total;
        $this->produtos = $produtos;
    }

    public function getTotal() {
        try {
            if (!isset($_SESSION["cartArray"])) {
                return 0;
            }

            $total = 0;
            foreach ($_SESSION["cartArray"] as $cartItem) {
                $total += $cartItem["preco"] * $cartItem["qntd"];
            }

            return $total;
        } catch (\Throwable $e) {
            return 0;
        }
    }

    public function setTotal($total) {
        $this->total = $total;
    }

    public function getProdutos() {
        return $this->produtos;
    }

    public function setProdutos($produtos) {
        $this->produtos = $produtos;
    }

    public function adicionarProduto($produto) {
        try {
            if (!isset($_SESSION["cartArray"])) {
                $_SESSION["cartArray"] = [];
            }
    
            if (
                $produto && 
                method_exists($produto, 'getId') &&
                method_exists($produto, 'getNome') &&
                method_exists($produto, 'getPreco') &&
                method_exists($produto, 'getFoto')
            ) {
                $id = $produto->getId();
    
                if (isset($_SESSION["cartArray"][$id])) {
                    $_SESSION["cartArray"][$id]["qntd"] += 1;
                } else {
                    $_SESSION["cartArray"][$id] = [
                        "nome" => $produto->getNome(),
                        "preco" => $produto->getPreco(),
                        "foto"  => $produto->getFoto(),
                        "qntd"  => 1
                    ];
                }             
                return true;
            } else {
                Logger::logError("Erro ao adicionar produto ao carrinho: produto inválido ou não existente ");
            }
    
            return false;
        } catch (\Throwable $e) {
            Logger::logError("Erro ao adicionar produto ao carrinho: " . $e->getMessage());
            return false;
        }
    }
    

    public function listarCarrinho() {
        try {
            if (!isset($_SESSION["cartArray"])) {
                return [];
            }

            $produtosCarrinho = [];
            foreach ($_SESSION["cartArray"] as $variacaoId => $cartItem) {
                $cartItem["id"] = $variacaoId;
                $cartItem["quantidades"] = $this->gerarOpcoesQuantidade($cartItem["qntd"]);
                $produtosCarrinho[] = $cartItem;
            }

            return $produtosCarrinho;
        } catch (\Throwable $e) {
            return [];
        }
    }

    public function atualizarCarrinho() {
        try {
            if (isset($_POST["cart"]) && isset($_SESSION["cartArray"])) {
                foreach ($_SESSION["cartArray"] as $variacaoId => $cartItem) {
                    $campoQuantidade = "select" . $variacaoId;
                    if (isset($_POST[$campoQuantidade])) {
                        $qntd = $_POST[$campoQuantidade];
                        $_SESSION["cartArray"][$variacaoId]["qntd"] = $qntd;
                    }
                }
            }
        } catch (\Throwable $e) {
            Logger::logError("Erro ao atualizar carrinho: " . $e->getMessage());
        }
    }

    private function gerarOpcoesQuantidade($qntd) {
        $html = '';
        for ($i = 1; $i <= 10; $i++) {
            $selected = ($qntd == $i) ? 'selected' : '';
            $html .= "<option value=\"$i\" $selected>$i</option>";
        }
        return $html;
    }

    public function removeProduto($id) {
        try {
            if (isset($_SESSION["cartArray"][$id])) {
                unset($_SESSION["cartArray"][$id]);
            }
        } catch (\Throwable $e) {
            Logger::logError("Erro ao remover produto do carrinho: " . $e->getMessage());

        }
    }

    public function limparCarrinho() {
        try {
            if (isset($_SESSION["cartArray"])) {
                unset($_SESSION["cartArray"]);
            }
        } catch (\Throwable $e) {
            Logger::logError("Erro ao limpar carrinho: " . $e->getMessage());
        }
    }

    public function atualizarQuantidade($id, $quantidade) {
        try {
            if (isset($_SESSION["cartArray"][$id])) {
                $_SESSION["cartArray"][$id]["qntd"] = $quantidade;
            }
        } catch (\Throwable $e) {
            Logger::logError("Erro ao atualizar quantidades no carrinho: " . $e->getMessage());
        }
    }
}
?>
