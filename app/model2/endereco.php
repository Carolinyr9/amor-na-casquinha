<?php
namespace app\model2;

class Endereco {
    private $idEndereco;
    private $rua;
    private $numero;
    private $cep;
    private $bairro;
    private $cidade;
    private $estado;
    private $complemento;

    public function __construct($idEndereco, $rua, $numero, $cep, $bairro, $cidade, $estado, $complemento) {
        $this->idEndereco = $idEndereco;
        $this->rua = $rua;
        $this->numero = $numero;
        $this->cep = $cep;
        $this->bairro = $bairro;
        $this->cidade = $cidade;
        $this->estado = $estado;
        $this->complemento = $complemento;
    }

    public function getIdEndereco() {
        return $this->idEndereco;
    }

    public function setIdEndereco($idEndereco) {
        $this->idEndereco = $idEndereco;
    }
    
    public function getRua() {
        return $this->rua;
    }

    public function setRua($rua) {
        $this->rua = $rua;
    }
    
    public function getNumero() {
        return $this->numero;
    }

    public function setNumero($numero) {
        $this->numero = $numero;
    }
    
    public function getCep() {
        return $this->cep;
    }

    public function setCep($cep) {
        $this->cep = $cep;
    }

    public function getBairro() {
        return $this->bairro;
    }

    public function setBairro($bairro) {
        $this->bairro = $bairro;
    }
    
    public function getCidade() {
        return $this->cidade;
    }

    public function setCidade($cidade) {
        $this->cidade = $cidade;
    }

    public function getEstado() {
        return $this->estado;
    }

    public function setEstado($estado) {
        $this->estado = $estado;
    }

    public function getComplemento() {
        return $this->complemento;
    }

    public function setComplemento($complemento) {
        $this->complemento = $complemento;
    }
}