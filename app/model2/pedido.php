<?php
namespace app\model2;

class Pedido {
    private $idPedido;
    private $idCliente;
    private $idProduto;
    private $dtPedido;
    private $dtPagamento;
    private $tipoFrete;
    private $idEndereco;
    private $valorTotal;
    private $dataCancelamento;
    private $motivoCancelamento;
    private $statusPedido;
    private $idEntregador;
    private $frete;
    private $meioPagamento;
    private $trocoPara;
    

    public function getIdPedido()
    {
        return $this->idPedido;
    }

    public function setIdPedido($idPedido)
    {
        $this->idPedido = $idPedido;
    }

    public function getIdCliente()
    {
        return $this->idCliente;
    }

    public function setIdCliente($idCliente)
    {
        $this->idCliente = $idCliente;
    }

    public function getIdProduto()
    {
        return $this->idProduto;
    }

    public function setIdProduto($idProduto)
    {
        $this->idProduto = $idProduto;
    }

    public function getDtPedido()
    {
        return $this->dtPedido;
    }

    public function setDtPedido($dtPedido)
    {
        $this->dtPedido = $dtPedido;
    }

    public function getDtPagamento()
    {
        return $this->dtPagamento;
    }

    public function setDtPagamento($dtPagamento)
    {
        $this->dtPagamento = $dtPagamento;
    }

    public function getTipoFrete()
    {
        return $this->tipoFrete;
    }

    public function setTipoFrete($tipoFrete)
    {
        $this->tipoFrete = $tipoFrete;
    }

    public function getIdEndereco()
    {
        return $this->idEndereco;
    }

    public function setIdEndereco($idEndereco)
    {
        $this->idEndereco = $idEndereco;
    }

    public function getValorTotal()
    {
        return $this->valorTotal;
    }

    public function setValorTotal($valorTotal)
    {
        $this->valorTotal = $valorTotal;
    }

    public function getDataCancelamento()
    {
        return $this->dataCancelamento;
    }

    public function setDataCancelamento($dataCancelamento)
    {
        $this->dataCancelamento = $dataCancelamento;
    }

    public function getMotivoCancelamento()
    {
        return $this->motivoCancelamento;
    }

    public function setMotivoCancelamento($motivoCancelamento)
    {
        $this->motivoCancelamento = $motivoCancelamento;
    }

    public function getStatusPedido()
    {
        return $this->statusPedido;
    }

    public function setStatusPedido($statusPedido)
    {
        $this->statusPedido = $statusPedido;
    }

    public function getIdEntregador()
    {
        return $this->idEntregador;
    }

    public function setIdEntregador($idEntregador)
    {
        $this->idEntregador = $idEntregador;
    }

    public function getFrete()
    {
        return $this->frete;
    }

    public function setFrete($frete)
    {
        $this->frete = $frete;
    }

    public function getMeioPagamento()
    {
        return $this->meioPagamento;
    }

    public function setMeioPagamento($meioPagamento)
    {
        $this->meioPagamento = $meioPagamento;
    }

    public function getTrocoPara()
    {
        return $this->trocoPara;
    }

    public function setTrocoPara($trocoPara)
    {
        $this->trocoPara = $trocoPara;
    }
}