<?php 
namespace app\model;

use app\config\DataBase;
use PDO;
use PDOException;

class Fornecedor {
    private $id;
    private $nome;
    private $telefone;
    private $email;
    private $cpnj;
    private $desativado;
    private $endereco;

    public function __construct($id, $nome, $telefone, $email, $cpnj, $desativado, $endereco) {
        $this->id = $id;
        $this->nome = $nome;
        $this->telefone = $telefone;
        $this->email = $email;
        $this->cpnj = $cpnj;
        $this->desativado = $desativado;
        $this->endereco = $endereco;
    }
    

    public function getId() {
        return $this->id;
    }

    public function getNome() {
        return $this->nome;
    }

    public function getTelefone() {
        return $this->telefone;
    }

    public function getEmail() {
        return $this->email;
    }

    public function getCpnj() {
        return $this->cpnj;
    }

    public function getDesativado() {
        return $this->desativado;
    }

    public function getEndereco() {
        return $this->endereco;
    }

    public function setId($id) {
        $this->id = $id;
    }

    public function setNome($nome) {
        $this->nome = $nome;
    }

    public function setTelefone($telefone) {
        $this->telefone = $telefone;
    }

    public function setEmail($email) {
        $this->email = $email;
    }

    public function setCpnj($cpnj) {
        $this->cpnj = $cpnj;
    }

    public function setDesativado($desativado) {
        $this->desativado = $desativado;
    }

    public function setEndereco($endereco) {
        $this->endereco = $endereco;
    }


    public function editarFornecedor($nome, $email, $telefone){
        $this->setEmail($email);
        $this->setNome($nome);
        $this->setTelefone($telefone);
    }
}
?>
