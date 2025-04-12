<?php 
namespace app\model2;

use app\config\DataBase;
use PDO;
use PDOException;

// MUDAR NOME DA TABELAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAA
class CategoriaProduto {
    private $id;
    private $fornecedor;
    private $nome;
    private $marca;
    private $descricao;
    private $desativado;
    private $foto;

    public function __construct($id, $fornecedor, $nome, $marca, $descricao, $desativado, $foto) {
        $this->id = $id;
        $this->fornecedor = $fornecedor;
        $this->nome = $nome;
        $this->marca = $marca;
        $this->descricao = $descricao;
        $this->desativado = $desativado;
        $this->foto = $foto;
    }

    public function getId() {
        return $this->id;
    }

    public function getFornecedor() {
        return $this->fornecedor;
    }

    public function getNome() {
        return $this->nome;
    }

    public function getMarca() {
        return $this->marca;
    }

    public function getDescricao() {
        return $this->descricao;
    }

    public function isDesativado() {
        return $this->desativado;
    }

    public function getFoto() {
        return $this->foto;
    }

    public function setFornecedor($fornecedor) {
        $this->fornecedor = $fornecedor;
    }

    public function setNome($nome) {
        $this->nome = $nome;
    }

    public function setMarca($marca) {
        $this->marca = $marca;
    }

    public function setDescricao($descricao) {
        $this->descricao = $descricao;
    }

    public function setDesativado($desativado) {
        $this->desativado = $desativado;
    }

    public function setFoto($foto) {
        $this->foto = $foto;
    }

    public static function criarLista(array $dados) {
        $produtos = [];
        foreach ($dados as $produto) {
            $produtos[] = new self(
                $produto['id'],
                $produto['fornecedor'],
                $produto['nome'],
                $produto['marca'],
                $produto['descricao'],
                $produto['desativado'],
                $produto['foto']
            );
        }
        return $produtos;
    }

    public function editarCategoria($nome, $marca, $descricao, $foto){
        $this->setNome($nome);
        $this->setMarca($marca);
        $this->setDescricao($descricao);
        $this->setFoto($foto);
    }
    
}
