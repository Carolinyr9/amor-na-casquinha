<?php 
namespace app\model;

use app\config\DataBase;
use PDO;
use PDOException;

class Funcionario {
    private $id;
    private $desativado;
    private $adm;
    private $nome;
    private $telefone;
    private $email;
    private $senha;
    private $endereco;
    private $perfil = 'FUNC';

    public function __construct($id, $desativado, $adm, $nome, $telefone, $email, $senha, $endereco) {
        $this->id = $id;
        $this->desativado = $desativado;
        $this->adm = $adm;
        $this->nome = $nome;
        $this->telefone = $telefone;
        $this->email = $email;
        $this->senha = $senha;
        $this->endereco = $endereco;
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

    public function getAdm() {
        return $this->adm;
    }

    public function setAdm($adm) {
        $this->adm = $adm;
    }

    public function getNome() {
        return $this->nome;
    }

    public function setNome($nome) {
        $this->nome = $nome;
    }

    public function getTelefone() {
        return $this->telefone;
    }

    public function setTelefone($telefone) {
        $this->telefone = $telefone;
    }

    public function getEmail() {
        return $this->email;
    }

    public function setEmail($email) {
        $this->email = $email;
    }

    public function getSenha() {
        return $this->senha;
    }

    public function setSenha($senha) {
        $this->senha = $senha;
    }

    public function getEndereco() {
        return $this->endereco;
    }

    public function setEndereco($endereco) {
        $this->endereco = $endereco;
    }

    public function getPerfil() {
        return $this->perfil;
    }

    public function editarFuncionario($nome, $email, $telefone){
        $this->setNome($nome);
        $this->setEmail($email);
        $this->setTelefone($telefone);
    }

}
