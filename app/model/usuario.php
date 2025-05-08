<?php
namespace app\model;

class Usuario {
    private $nome;
    private $email;
    private $perfil;

    public function __construct($nome, $email, $perfil) {
        $this->nome = $nome;
        $this->email = $email;
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

    public function getPerfil() {
        return $this->perfil;
    }

    public function setPerfil($perfil) {
        $this->perfil = $perfil;
    }
}
