<?php 
namespace app\model;

class ItemPedido {
    private $idPedido;
    private $idProduto;
    private $quantidade;

    public function __construct($idPedido, $idProduto, $quantidade) {
        $this->idPedido = $idPedido;
        $this->idProduto = $idProduto;
        $this->quantidade = $quantidade;
    }

    public function getIdPedido() {
        return $this->idPedido;
    }

    public function setIdPedido($idPedido) {
        $this->idPedido = $idPedido;
    }

    public function getIdProduto() {
        return $this->idProduto;
    }

    public function setIdProduto($idProduto) {
        $this->idProduto = $idProduto;
    }

    public function getQuantidade() {
        return $this->quantidade;
    }

    public function setQuantidade($quantidade) {
        $this->quantidade = $quantidade;
    }
}
