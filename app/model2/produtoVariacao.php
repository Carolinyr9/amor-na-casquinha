<?php 
namespace app\model2;

class ProdutoVariacao {
    private $id;
    private $desativado;
    private $nome;
    private $preco;
    private $foto;
    private $produto;

    public function __construct($id, $desativado, $nome, $preco, $foto, $produto) {
        $this->id = $id;
        $this->desativado = $desativado;
        $this->nome = $nome;
        $this->preco = $preco;
        $this->foto = $foto;
        $this->produto = $produto;
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

    public function getProduto() {
        return $this->produto;
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

    public function setProduto($produto) {
        $this->produto = $produto;
    }


    public function editarVariacao($idProduto, $nomeProduto, $preco, $imagemProduto){
        setProduto($idProduto);
        setNome($nome);
        setPreco($preco);
        setFoto($foto);
    }
}
