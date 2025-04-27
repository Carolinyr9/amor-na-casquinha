<?php 

namespace app\repository;

use app\config\DataBase;
use app\model2\Estoque;
use app\utils\Logger;
use PDO;
use PDOException;
use Exception;

class EstoqueRepository {
    private $conn;

    public function __construct() {
        $this->conn = (new DataBase())->getConnection();
    }

    public function criarProdutoEstoque($idCategoria, $idProduto, $lote, $dataEntrada, $dataFabricacao, $dataVencimento, $quantidadeMinima, $quantidade, $precoCompra): int {
        try {
            $stmt = $this->conn->prepare("INSERT INTO estoque (idCategoria, idProduto, lote, dtEntrada, quantidade, dtFabricacao, dtVencimento, precoCompra, qtdMinima) VALUES (:idCategoria, :idProduto, :lote, :dtEntrada, :quantidade, :dtFabricacao, :dtVencimento, :precoCompra, :qtdMinima)");
            
            $stmt->bindParam(':idCategoria', $idCategoria);
            $stmt->bindParam(':idProduto', $idProduto);
            $stmt->bindParam(':lote', $lote);
            $stmt->bindParam(':dtEntrada', $dataEntrada);
            $stmt->bindParam(':quantidade', $quantidade);
            $stmt->bindParam(':dtFabricacao', $dataFabricacao);
            $stmt->bindParam(':dtVencimento', $dataVencimento);
            $stmt->bindParam(':precoCompra', $precoCompra);
            $stmt->bindParam(':qtdMinima', $quantidadeMinima);
    
            if ($stmt->execute()) {
                return (int) $this->conn->lastInsertId();
            }
        } catch (PDOException $e) {
            Logger::logError("Erro ao criar produto no estoque: " . $e->getMessage());
        }
    }
    

    public function listarEstoque(): array {
        try {
            $stmt = $this->conn->prepare("SELECT * FROM estoque WHERE desativado = 0");
            $stmt->execute();
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            $estoques = [];
            foreach ($result as $row) {
                $estoques[] = $this->mapEstoque($row);
            }
            return $estoques;
        } catch (PDOException $e) {
            Logger::logError("Erro ao listar o estoque: " . $e->getMessage());
        }
    }

    public function selecionarProdutoEstoquePorID(int $idProduto): ?Estoque {
        try {
            $stmt = $this->conn->prepare("SELECT * FROM estoque WHERE idProduto = :idProduto AND desativado = 0");
            $stmt->bindParam(':idProduto', $idProduto, PDO::PARAM_INT);
            $stmt->execute();
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            
            return $row ? $this->mapEstoque($row) : null;
        } catch (PDOException $e) {
            Logger::logError("Erro ao selecionar o produto: " . $e->getMessage());
        }
    }

    public function editarProdutoEstoque($idEstoque, $lote, $dtEntrada, $quantidade, $dtFabricacao, $dtVencimento, $precoCompra, $qtdMinima, $qtdOcorrencia, $ocorrencia){
        try {
            $stmt = $this->conn->prepare("UPDATE estoque SET lote = :lote, dtEntrada = :dtEntrada, quantidade = :quantidade, dtFabricacao = :dtFabricacao, dtVencimento = :dtVencimento, precoCompra = :precoCompra, qtdMinima = :qtdMinima, qtdOcorrencia = :qtdOcorrencia, ocorrencia = :ocorrencia WHERE idEstoque = :idEstoque");
            
            $stmt->bindParam(':lote', $lote);
            $stmt->bindParam(':dtEntrada', $dtEntrada);
            $stmt->bindParam(':quantidade', $quantidade);
            $stmt->bindParam(':dtFabricacao', $dtFabricacao);
            $stmt->bindParam(':dtVencimento', $dtVencimento);
            $stmt->bindParam(':precoCompra', $precoCompra);
            $stmt->bindParam(':qtdMinima', $qtdMinima);
            $stmt->bindParam(':qtdOcorrencia', $qtdOcorrencia);
            $stmt->bindParam(':ocorrencia', $ocorrencia);
            $stmt->bindParam(':idEstoque', $idEstoque, PDO::PARAM_INT);
            $stmt->execute();

            $stmt->execute();
            if ($stmt->rowCount() > 0) {
                return true;
            } else {
                return false;
            }

        } catch (PDOException $e) {
            Logger::logError("Erro ao editar o produto: " . $e->getMessage());
            return false;
        }
    }

    public function desativarProdutoEstoque(int $idEstoque): bool {
        try {
            $stmt = $this->conn->prepare("UPDATE estoque SET desativado = 1 WHERE idEstoque = :idEstoque");
            $stmt->bindParam(':idEstoque', $idEstoque, PDO::PARAM_INT);
            return $stmt->execute() ? true : false;
        } catch (PDOException $e) {
            Logger::logError("Erro ao desativar o produto: " . $e->getMessage());
        }
    }

    public function verificarQuantidadeMinima() {
        try {
            $stmt = $this->conn->prepare("SELECT idProduto, idCategoria, quantidade FROM estoque WHERE quantidade <= qtdMinima");
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            Logger::logError("Erro ao verificar quantidade mínima: " . $e->getMessage());
        }
    }
    
    private function mapEstoque(array $row): Estoque {
        return new Estoque(
            $row['idEstoque'], 
            $row['idCategoria'], 
            $row['idProduto'], 
            $row['dtEntrada'], 
            $row['quantidade'], 
            $row['dtFabricacao'], 
            $row['dtVencimento'],
            $row['lote'], 
            $row['precoCompra'], 
            $row['qtdMinima'], 
            $row['qtdVendida'], 
            $row['qtdOcorrencia'], 
            $row['ocorrencia'], 
            $row['desativado']
        );
    }
    
}
