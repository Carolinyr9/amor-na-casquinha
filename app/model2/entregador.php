<?php 
namespace app\model;

class Entregador {
    private $id;
    private $desativado;
    private $perfil;
    private $nome;
    private $email;
    private $telefone;
    private $senha;
    private $cnh;

    public function __construct($id, $desativado, $perfil, $nome, $email, $telefone, $senha, $cnh) {
        $this->id = $id;
        $this->desativado = $desativado;
        $this->perfil = $perfil;
        $this->nome = $nome;
        $this->email = $email;
        $this->telefone = $telefone;
        $this->senha = $senha;
        $this->cnh = $cnh;
    }

    public function getId() {
        return $this->id;
    }
 
    public function setId($id) {
        $this->id = $id;
    }
 
    public function getDesativado() {
        return $this->desativado;
    }
 
    public function setDesativado($desativado) {
        $this->desativado = $desativado;
    }
 
    public function getPerfil() {
        return $this->perfil;
    }
 
    public function setPerfil($perfil) {
        $this->perfil = $perfil;
    }
 
    public function getNome() {
        return $this->nome;
    }
 
    public function setNome($nome) {
        $this->nome = $nome;
    }
 
    public function getEmail() {
        return $this->email;
    }
 
    public function setEmail($email) {
        $this->email = $email;
    }
 
    public function getTelefone() {
        return $this->telefone;
    }
 
    public function setTelefone($telefone) {
        $this->telefone = $telefone;
    }
 
    public function getSenha() {
        return $this->senha;
    }
 
    public function setSenha($senha) {
        $this->senha = $senha;
    }
 
    public function getCnh() {
        return $this->cnh;
    }
 
    public function setCnh($cnh) {
        $this->cnh = $cnh;
    }
}