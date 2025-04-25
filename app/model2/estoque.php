<?php 
namespace app\model2;

use app\config\DataBase;
use PDO;
use PDOException;

class Estoque {
    private $idEstoque;
    private $idProduto;
    private $idVariacao;
    private $dtEntrada;
    private $quantidade;
    private $dtFabricacao;
    private $dtVencimento;
    private $lote;
    private $precoCompra;
    private $qtdMinima;
    private $qtdVendida;
    private $qtdOcorrencia;
    private $ocorrencia;
    private $desativado;

    public function __construct(
        $idEstoque, $idCategoria, $idProduto, $dtEntrada, $quantidade, 
        $dtFabricacao, $dtVencimento, $lote, $precoCompra, $qtdMinima, 
        $qtdVendida, $qtdOcorrencia, $ocorrencia, $desativado
    ) {
        $this->idEstoque = $idEstoque;
        $this->idCategoria = $idCategoria;
        $this->idProduto = $idProduto;
        $this->dtEntrada = $dtEntrada;
        $this->quantidade = $quantidade;
        $this->dtFabricacao = $dtFabricacao;
        $this->dtVencimento = $dtVencimento;
        $this->lote = $lote;
        $this->precoCompra = $precoCompra;
        $this->qtdMinima = $qtdMinima;
        $this->qtdVendida = $qtdVendida;
        $this->qtdOcorrencia = $qtdOcorrencia;
        $this->ocorrencia = $ocorrencia;
        $this->desativado = $desativado;
    }

    public function getIdEstoque() {
        return $this->idEstoque;
    }

    public function setIdEstoque($idEstoque) {
        $this->idEstoque = $idEstoque;
    }

    public function getIdProduto() {
        return $this->idProduto;
    }

    public function setIdProduto($idProduto) {
        $this->idProduto = $idProduto;
    }

    public function getIdCategoria() {
        return $this->idVariacao;
    }

    public function setIdCategoria($idCategoria) {
        $this->idCategoria = $idCategoria;
    }

    public function getDtEntrada() {
        return $this->dtEntrada;
    }

    public function setDtEntrada($dtEntrada) {
        $this->dtEntrada = $dtEntrada;
    }

    public function getQuantidade() {
        return $this->quantidade;
    }

    public function setQuantidade($quantidade) {
        $this->quantidade = $quantidade;
    }

    public function getDtFabricacao() {
        return $this->dtFabricacao;
    }

    public function setDtFabricacao($dtFabricacao) {
        $this->dtFabricacao = $dtFabricacao;
    }

    public function getDtVencimento() {
        return $this->dtVencimento;
    }

    public function setDtVencimento($dtVencimento) {
        $this->dtVencimento = $dtVencimento;
    }

    public function getLote() {
        return $this->lote;
    }

    public function setLote($lote) {
        $this->lote = $lote;
    }

    public function getPrecoCompra() {
        return $this->precoCompra;
    }

    public function setPrecoCompra($precoCompra) {
        $this->precoCompra = $precoCompra;
    }

    public function getQtdMinima() {
        return $this->qtdMinima;
    }

    public function setQtdMinima($qtdMinima) {
        $this->qtdMinima = $qtdMinima;
    }

    public function getQtdVendida() {
        return $this->qtdVendida;
    }

    public function setQtdVendida($qtdVendida) {
        $this->qtdVendida = $qtdVendida;
    }

    public function getQtdOcorrencia() {
        return $this->qtdOcorrencia;
    }

    public function setQtdOcorrencia($qtdOcorrencia) {
        $this->qtdOcorrencia = $qtdOcorrencia;
    }

    public function getOcorrencia() {
        return $this->ocorrencia;
    }

    public function setOcorrencia($ocorrencia) {
        $this->ocorrencia = $ocorrencia;
    }

    public function getDesativado() {
        return $this->desativado;
    }

    public function setDesativado($desativado) {
        $this->desativado = $desativado;
    }

    public function editarProdutoEstoque(
        $idEstoque, $dtEntrada, $quantidade, $dtFabricacao, $dtVencimento, 
        $precoCompra, $qtdMinima, $qtdOcorrencia, $ocorrencia
    ) {
        $this->setIdEstoque($idEstoque);
        $this->setDtEntrada($dtEntrada);
        $this->setQuantidade($quantidade);
        $this->setDtFabricacao($dtFabricacao);
        $this->setDtVencimento($dtVencimento);
        $this->setPrecoCompra($precoCompra);
        $this->setQtdMinima($qtdMinima);
        $this->setQtdOcorrencia($qtdOcorrencia);
        $this->setOcorrencia($ocorrencia);
    }
}

?>