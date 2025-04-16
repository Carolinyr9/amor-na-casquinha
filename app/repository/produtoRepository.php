<?php 
namespace app\repository;

use app\config\DataBase;
use app\model2\Produto;
use PDO;
use PDOException;
use Exception;

class ProdutoRepository {
    private $conn;  

    public function __construct() {
        $this->getConnectionDataBase();
    }

    private function getConnectionDataBase() {
        try {
            $database = new DataBase();
            $this->conn = $database->getConnection();  
        } catch (PDOException $e) {
            die("Erro ao conectar com o banco de dados: " . $e->getMessage());
        }
    }

    public function selecionarProdutosAtivosPorCategoria($idCategoria) {
        try {
            $stmt = $this->conn->prepare("SELECT * FROM produto WHERE desativado = 0 AND categoria = :idCategoria");
            $stmt->bindParam(":idCategoria", $idCategoria, PDO::PARAM_INT);
            $stmt->execute();
            $dados = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            $produtos = [];
    
            foreach ($dados as $produto) {
                $produtos[] = new Produto(
                    $produto['id'],
                    $produto['desativado'],
                    $produto['nome'],
                    $produto['preco'],
                    $produto['foto'],
                    $produto['categoria']
                );
            }
    
            return $produtos; 
        } catch (PDOException $e) {
            throw new Exception("Erro ao buscar produtos: " . $e->getMessage());
        }
    }

    public function criarProduto($categoria, $nomeProduto, $preco, $imagem) {
        try {
            $stmt = $this->conn->prepare("INSERT INTO produto (nomeProduto, precoProduto, fotoProduto, idCategoria, desativado) VALUES (:nomeProduto, :precoProduto, :fotoProduto, :categoria, 0)");
            $stmt->bindParam(":nomeProduto", $nomeProduto);
            $stmt->bindParam(":precoProduto", $preco);
            $stmt->bindParam(":fotoProduto", $imagem);
            $stmt->bindParam(":idCategoria", $categoria, PDO::PARAM_INT);
            
            return $stmt->execute() ? $this->conn->lastInsertId() : false;
        } catch (PDOException $e) {
            throw new Exception("Erro ao inserir o produto: " . $e->getMessage());
        }
    }

    public function selecionarProdutoPorID($id) {
        try {
            $stmt = $this->conn->prepare("SELECT * FROM produto WHERE idProduto = :id");
            $stmt->bindParam(":id", $id, PDO::PARAM_INT);
            $stmt->execute();
            $dados = $stmt->fetch(PDO::FETCH_ASSOC);
            
            return $dados ? new Produto(
                $dados['idProduto'],
                $dados['desativado'],
                $dados['nomeProduto'],
                $dados['precoProduto'],
                $dados['fotoProduto'],
                $dados['idCategoria']
            ) : null;
        } catch (PDOException $e) {
            throw new Exception("Erro ao buscar produto por ID: " . $e->getMessage());
        }
    }

    public function editarProduto($idProduto, $idCategoria, $nomeProduto, $preco, $imagemProduto) {
        try {
            $stmt = $this->conn->prepare("UPDATE produto SET nomeProduto = :nomeProduto, precoProduto = :precoProduto, fotoProduto = :fotoProduto, idCategoria = :idCategoria WHERE idProduto = :idProduto AND desativado != 1");
            $stmt->bindParam(":nomeProduto", $nomeProduto);
            $stmt->bindParam(":precoProduto", $preco);
            $stmt->bindParam(":fotoProduto", $imagemProduto);
            $stmt->bindParam(":idCategoria", $idCategoria, PDO::PARAM_INT);
            $stmt->bindParam(":idProduto", $idProduto, PDO::PARAM_INT);
            
            return $stmt->execute();
        } catch (PDOException $e) {
            throw new Exception("Erro ao editar o produto: " . $e->getMessage());
        }
    }

    public function desativarProduto($idProduto) {
        try {
            $stmt = $this->conn->prepare("UPDATE produto SET desativado = 1 WHERE idProduto = :idProduto");
            $stmt->bindParam(":idProduto", $idProduto, PDO::PARAM_INT);
            
            return $stmt->execute();
        } catch (PDOException $e) {
            throw new Exception("Erro ao desativar o produto: " . $e->getMessage());
        }
    }
}
