<?php 
namespace app\repository;

use app\config\DataBase;
use PDO;
use PDOException;

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

    public function buscarProdutosAtivos($limit = 100, $offset = 0) {
        $produtos = [];
        try {
            $stmt = $this->conn->prepare("SELECT * FROM produtos WHERE ativo = 1 LIMIT :limit OFFSET :offset");
            $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
            $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
            $stmt->execute();
    
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $produtos[] = [
                    'id' => $row['idProduto'],  
                    'fornecedor' => $row['idFornecedor'],  
                    'nome' => $row['nome'],  
                    'marca' => $row['marca'],  
                    'descricao' => $row['descricao'],  
                    'desativado' => $row['desativado'] == 0, 
                    'foto' => $row['foto'],  
                    'produtosVariacao' => [] 
                ];
            }
        } catch (PDOException $e) {
            throw new Exception("Erro ao buscar produtos: " . $e->getMessage());
        }
        return $produtos;
    }
    

    public function buscarProdutoPorID($id) {
        try {
            $stmt = $this->conn->prepare("SELECT * FROM produtos WHERE id = :id AND desativado = 0 LIMIT 1");
            $stmt->bindParam(":id", $id, PDO::PARAM_INT);
            $stmt->execute();
            $dados = $stmt->fetch(PDO::FETCH_ASSOC);
    
            return $dados ? new Produto(
                $dados['id'],
                $dados['fornecedor'],
                $dados['nome'],
                $dados['marca'],
                $dados['descricao'],
                $dados['desativado'],
                $dados['foto'],
                $dados['preco'],
                $dados['produtosVariacao'] ?? []
            ) : null;
        } catch (PDOException $e) {
            throw new Exception("Erro ao buscar produto por ID: " . $e->getMessage());
        }
    }
    
    public function criarProduto($nome, $marca, $descricao, $fornecedor, $foto) {
        try {
            $stmt = $this->conn->prepare("INSERT INTO produtos (nome, marca, descricao, idFornecedor, foto, desativado) VALUES (:nome, :marca, :descricao, :fornecedor, :foto, 0)");
            $stmt->bindParam(':nome', $nome);
            $stmt->bindParam(':marca', $marca);
            $stmt->bindParam(':descricao', $descricao);
            $stmt->bindParam(':fornecedor', $fornecedor);
            $stmt->bindParam(':foto', $foto);
            $stmt->execute();
    
            return $this->conn->lastInsertId();
        } catch (PDOException $e) {
            throw new Exception("Erro ao inserir o produto: " . $e->getMessage());
        }
    }
    
    public function editarProduto($idProduto, $nomeProduto, $marca, $descricao, $foto) {
        try {
            $stmt = $this->conn->prepare("UPDATE produtos SET nome = :nome, marca = :marca, descricao = :descricao, foto = :foto WHERE id = :idProduto");
            $stmt->bindParam(':idProduto', $idProduto, PDO::PARAM_INT);
            $stmt->bindParam(':nome', $nomeProduto);
            $stmt->bindParam(':marca', $marca);
            $stmt->bindParam(':descricao', $descricao);
            $stmt->bindParam(':foto', $foto);
            return $stmt->execute() ? true : false;
        } catch (PDOException $e) {
            throw new Exception("Erro ao editar o produto: " . $e->getMessage());
        }
    }

    public function desativarProduto($idProduto) {
        try {
            $stmt = $this->conn->prepare("UPDATE produtos SET desativado = 1 WHERE id = :idProduto");
            $stmt->bindParam(':idProduto', $idProduto, PDO::PARAM_INT);
            return $stmt->execute() ? true : false;
        } catch (PDOException $e) {
            throw new Exception("Erro ao desativar o produto: " . $e->getMessage());
        }
    }
    
}