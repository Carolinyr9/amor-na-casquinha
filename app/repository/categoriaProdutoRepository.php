<?php 
namespace app\repository;

use app\config\DataBase;
use app\utils\Logger;
use app\model2\CategoriaProduto;
use PDO;
use PDOException;

class CategoriaProdutoRepository {
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

    public function buscarCategoriasAtivas($limit = 100, $offset = 0) {
        $produtos = [];
        try {
            $stmt = $this->conn->prepare("SELECT * FROM categoriaProduto WHERE desativado = 0 LIMIT :limit OFFSET :offset");
            $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
            $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
            $stmt->execute();
    
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $produtos[] = [
                    'id' => $row['id'],  
                    'fornecedor' => $row['idFornecedor'],  
                    'nome' => $row['nome'],  
                    'marca' => $row['marca'],  
                    'descricao' => $row['descricao'],  
                    'desativado' => $row['desativado'] == 0, 
                    'foto' => $row['foto']
                ];
            }
        } catch (PDOException $e) {
            Logger::logError("Erro ao buscar produtos: " . $e->getMessage());
        }
        return $produtos;
    }
    

    public function buscarCategoriaPorID($id) {
        try {
            $stmt = $this->conn->prepare("SELECT * FROM categoriaProduto WHERE id = :id AND desativado = 0 LIMIT 1");
            $stmt->bindParam(":id", $id, PDO::PARAM_INT);
            $stmt->execute();
            $dados = $stmt->fetch(PDO::FETCH_ASSOC);
    
            return $dados ? new CategoriaProduto(
                $dados['id'],
                $dados['fornecedor'],
                $dados['nome'],
                $dados['marca'],
                $dados['descricao'],
                $dados['desativado'],
                $dados['foto'],
                $dados['preco']
            ) : null;
        } catch (PDOException $e) {
            Logger::logError("Erro ao buscar produto por ID: " . $e->getMessage());
        }
    }
    
    public function criarCategoria($nome, $marca, $descricao, $fornecedor, $foto) {
        try {
            $stmt = $this->conn->prepare("INSERT INTO categoriaproduto (nome, marca, descricao, idFornecedor, foto, desativado) VALUES (:nome, :marca, :descricao, :fornecedor, :foto, 0)");
            $stmt->bindParam(':nome', $nome);
            $stmt->bindParam(':marca', $marca);
            $stmt->bindParam(':descricao', $descricao);
            $stmt->bindParam(':fornecedor', $fornecedor);
            $stmt->bindParam(':foto', $foto);
            $stmt->execute();
    
            return $this->conn->lastInsertId();
        } catch (PDOException $e) {
            Logger::logError("Erro ao inserir o produto: " . $e->getMessage());
        }
    }
    
    public function editarCategoria($idProduto, $nomeProduto, $marca, $descricao, $foto) {
        try {
            $stmt = $this->conn->prepare("UPDATE categoriaProduto SET nome = :nome, marca = :marca, descricao = :descricao, foto = :foto WHERE id = :idProduto");
            $stmt->bindParam(':idProduto', $idProduto, PDO::PARAM_INT);
            $stmt->bindParam(':nome', $nomeProduto);
            $stmt->bindParam(':marca', $marca);
            $stmt->bindParam(':descricao', $descricao);
            $stmt->bindParam(':foto', $foto);
            return $stmt->execute() ? true : false;
        } catch (PDOException $e) {
            Logger::logError("Erro ao editar o produto: " . $e->getMessage());
        }
    }

    public function desativarCategoria($idProduto) {
        try {
            $stmt = $this->conn->prepare("UPDATE categoriaProduto SET desativado = 1 WHERE id = :idProduto");
            $stmt->bindParam(':idProduto', $idProduto, PDO::PARAM_INT);
            return $stmt->execute() ? true : false;
        } catch (PDOException $e) {
            Logger::logError("Erro ao desativar o produto: " . $e->getMessage());
        }
    }
    
}