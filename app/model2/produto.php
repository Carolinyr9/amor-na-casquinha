<?php 
namespace app\model2;

class Produto {
    private $id;
    private $desativado;
    private $nome;
    private $preco;
    private $foto;
    private $categoria;

    public function __construct($id, $desativado, $nome, $preco, $foto, $categoria) {
        $this->id = $id;
        $this->desativado = $desativado;
        $this->nome = $nome;
        $this->preco = $preco;
        $this->foto = $foto;
        $this->categoria = $categoria;
    }

    public function getId() {
        return $this->id;
    }

    public function getDesativado() {
        return $this->desativado;
    }

    public function getNome() {
        return $this->nome;
    }

    public function getPreco() {
        return $this->preco;
    }

    public function getFoto() {
        return $this->foto;
    }

    public function getCategoria() {
        return $this->categoria;
    }

    public function setId($id) {
        $this->id = $id;
    }

    public function setDesativado($desativado) {
        $this->desativado = $desativado;
    }

    public function setNome($nome) {
        $this->nome = $nome;
    }

    public function setPreco($preco) {
        $this->preco = $preco;
    }

    public function setFoto($foto) {
        $this->foto = $foto;
    }

    public function setCategoria($categoria) {
        $this->categoria = $categoria;
    }


    public function editar($nome, $preco, $foto){
        $this->setNome($nome);
        $this->setPreco($preco);
        $this->setFoto($foto);
    }
}
