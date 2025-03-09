<?php
namespace app\model;

use app\config\DataBase; 
use PDO;
use PDOException;

class ProdutoVariacao {
    private $conn;  

    public function __construct() {
        $this->getConnectionDataBase();
    }

    private function getConnectionDataBase() {
        $database = new DataBase();
        $this->conn = $database->getConnection();  
    }

    public function ListarVariacaoPorTipo($idProduto) {
        $variacoes = [];
        try {
            if (isset($idProduto)) {
                $stmt = $this->conn->prepare("CALL ListarVariacaoPorTipo(?)");
                $stmt->bindParam(1, $idProduto, PDO::PARAM_INT);  
                $stmt->execute();

                do {
                    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                        if ($row['desativado'] == 0) {
                            $variacoes[] = $row; 
                        }
                    }
                } while ($stmt->nextRowset());
            } else {
                return 'Não há produtos!';
            }
            return $variacoes; // Retornar as variações
        } catch (PDOException $e) {
            return "Erro ao selecionar variações de produtos: " . $e->getMessage();
        }
    }

    public function adicionarVariacaoProduto($idProduto, $nomeProduto, $preco, $lote, $valor, $quantidade, $dataEntrada, $dataFabricacao, $dataVencimento, $quantidadeMinima, $imagem) {
        try {
            $stmt = $this->conn->prepare("CALL InserirVariacao(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
            $stmt->bindParam(1, $nomeProduto);    
            $stmt->bindParam(2, $preco);           
            $stmt->bindParam(3, $lote);     
            $stmt->bindParam(4, $valor);
            $stmt->bindParam(5, $quantidade); 
            $stmt->bindParam(6, $dataEntrada);
            $stmt->bindParam(7, $dataFabricacao);
            $stmt->bindParam(8, $dataVencimento);
            $stmt->bindParam(9, $quantidadeMinima);
            $stmt->bindParam(10, $imagem);
            $stmt->bindParam(11, $idProduto);
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            echo "foi8";
            if ($result !== false && isset($result['Status'])) {
                return $result['Status'] == '201' ? "Produto criado com sucesso!" : "Erro: " . $result['Error'];
            } else {
                return "Nenhum resultado retornado da procedure.";
            }
        } catch (PDOException $e) {
            return "Erro ao inserir o produto: " . $e->getMessage();
        }
    }

    public function selecionarProdutosPorID($idProduto) {
        $produtos = [];
        try {
            $stmt = $this->conn->prepare("CALL ListarVariacaoPorID(?)");
            $stmt->bindParam(1, $idProduto, PDO::PARAM_INT);
            $stmt->execute();

            do {
                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    $produtos[] = $row; // Armazenar os produtos
                }
            } while ($stmt->nextRowset());
            return $produtos; // Retornar os produtos
        } catch (PDOException $e) {
            return "Erro ao selecionar produto por ID: " . $e->getMessage();
        }
    }

    public function editarProduto($idVariacao, $idProduto, $nomeProduto, $preco, $imagemProduto) {
        try {
            $stmt = $this->conn->prepare("CALL EditarVariacaoPorID(?, ?, ?, ?, ?)");
            $stmt->bindParam(1, $idVariacao, PDO::PARAM_INT);
            $stmt->bindParam(2, $nomeProduto, PDO::PARAM_STR);    
            $stmt->bindParam(3, $preco, PDO::PARAM_STR);           
            $stmt->bindParam(4, $imagemProduto, PDO::PARAM_STR);    
            $stmt->bindParam(5, $idProduto, PDO::PARAM_INT);
            $stmt->execute();
            return "Produto editado com sucesso!";
        } catch (PDOException $e) {
            return "Erro ao editar o produto: " . $e->getMessage();
        }
    }

    public function removerProduto($idProduto) {
        try {
            $stmt = $this->conn->prepare("CALL DesativarVariacaoPorID(?)");
            $stmt->bindParam(1, $idProduto);
            $stmt->execute();
            return "Produto excluído com sucesso!";
        } catch (PDOException $e) {
            return "Erro no banco de dados: " . $e->getMessage();
        }
    }
}
?>
