<?php 
require_once '../config/database.php';

class Produto {
    private $idProduto;
    private $idFornecedor;
    private $nome;
    private $marca;
    private $descricao;
    private $desativado;
    private $foto;
    private $preco;
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

    public function selecionarProdutos() {
        $produtos = [];
        try {
            $recordsLimit = 100;
            $recordsOffset = 0;
            $stmt = $this->conn->prepare("CALL ListarProdutoAtivo(?, ?)");
            $stmt->bindParam(1, $recordsLimit, PDO::PARAM_INT);
            $stmt->bindParam(2, $recordsOffset, PDO::PARAM_INT); 
            $stmt->execute();

            do {
                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    $produtos[] = $row;
                }
            } while ($stmt->nextRowset());
        } catch (PDOException $e) {
            die("Erro ao selecionar produtos: " . $e->getMessage());
        }
        return $produtos;
    }

    public function selecionarProdutosPorID($id) {
        $produto = null;
        try {
            $recordsLimit = 100;
            $recordsOffset = 0;

            $stmt = $this->conn->prepare("CALL ListarProdutoPorId(?, ?, ?)");
            $stmt->bindParam(1, $id, PDO::PARAM_INT);
            $stmt->bindParam(2, $recordsLimit, PDO::PARAM_INT);
            $stmt->bindParam(3, $recordsOffset, PDO::PARAM_INT); 
            $stmt->execute();

            if ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $produto = $row;
            }
        } catch (PDOException $e) {
            die("Erro ao selecionar produtos por ID: " . $e->getMessage());
        }
        return $produto;
    }

    public function selecionarProdutoPorID($id) {
        try {
            $recordsLimit = 100;
            $recordsOffset = 0;
            
            $stmt = $this->conn->prepare("CALL ListarProdutoPorId(?, ?, ?)");
            $stmt->bindParam(1, $id, PDO::PARAM_INT);
            $stmt->bindParam(2, $recordsLimit, PDO::PARAM_INT);
            $stmt->bindParam(3, $recordsOffset, PDO::PARAM_INT); 
            $stmt->execute();

            $produto = $stmt->fetch(PDO::FETCH_ASSOC);
            return $produto;
        } catch (PDOException $e) {
            die("Erro ao selecionar produtos por ID: " . $e->getMessage());
        }
        return $produto;
    }

    public function adicionarProduto($nomeProduto, $marca, $descricao, $idFornecedor, $imagemProduto) {
        try {
            $desativado = 0;
            $stmt = $this->conn->prepare("CALL InserirProduto(?, ?, ?, ?, ?, ?)");
            $stmt->bindParam(1, $nomeProduto);    
            $stmt->bindParam(2, $marca);           
            $stmt->bindParam(3, $descricao);     
            $stmt->bindParam(4, $idFornecedor);   
            $stmt->bindParam(5, $imagemProduto);  
            $stmt->bindParam(6, $desativado);   
            $stmt->execute();

            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return $result['Status'] == '201' ? "Produto criado com sucesso!" : "Erro: " . $result['Error'];
        } catch (PDOException $e) {
            return "Erro ao inserir o produto: " . $e->getMessage();
        }
    }

    public function editarProduto($idProduto, $nomeProduto, $marca, $descricao, $imagemProduto) {
        try {
            $stmt = $this->conn->prepare("CALL EditarProdutoPorId(?, ?, ?, ?, ?)");
            $stmt->bindParam(1, $idProduto);
            $stmt->bindParam(2, $nomeProduto);
            $stmt->bindParam(3, $marca);
            $stmt->bindParam(4, $descricao);
            $stmt->bindParam(5, $imagemProduto);
            $stmt->execute();

            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return $result['Status'] == '204' ? "Produto atualizado com sucesso!" : "Erro: " . $result['Error'];
        } catch (PDOException $e) {
            return "Erro ao editar o produto: " . $e->getMessage();
        }
    }

    public function removerProduto($idProduto) {
        try {
            $stmt = $this->conn->prepare("CALL DesativarProdutoPorID(?)");
            $stmt->bindParam(1, $idProduto);
            $stmt->execute();

            return "Produto excluído com sucesso";
        } catch (PDOException $e) {
            return "Erro ao excluir o produto: " . $e->getMessage();
        }
    }
}
?>