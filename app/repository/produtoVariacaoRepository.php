<?php 
namespace app\repository;

use app\config\DataBase;
use app\model2\Produto;
use PDO;
use PDOException;
use Exception;

class ProdutoVariacaoRepository {
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

    public function selecionarVariacaoAtiva($idProduto) {
        try {
            $stmt = $this->conn->prepare("SELECT * FROM variacaoProduto WHERE desativado = 0 AND idProduto = :idProduto");
            $stmt->bindParam(":idProduto", $idProduto, PDO::PARAM_INT);
            $stmt->execute();
            $dados = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            $variacoes = [];
    
            foreach ($dados as $variacao) {
                $variacoes[] = new Produto(
                    $variacao['idVariacao'],
                    $variacao['desativado'],
                    $variacao['nomeVariacao'],
                    $variacao['precoVariacao'],
                    $variacao['fotoVariacao'],
                    $variacao['idProduto']
                );
            }
    
            return $variacoes; 
        } catch (PDOException $e) {
            throw new Exception("Erro ao buscar produtos por variação: " . $e->getMessage());
        }
    }

    public function criarVariacao($idProduto, $nomeProduto, $preco, $imagem) {
        try {
            $stmt = $this->conn->prepare("INSERT INTO variacaoProduto (nomeVariacao, precoVariacao, fotoVariacao, idProduto, desativado) VALUES (:nomeVariacao, :precoVariacao, :fotoVariacao, :idProduto, 0)");
            $stmt->bindParam(":nomeVariacao", $nomeProduto);
            $stmt->bindParam(":precoVariacao", $preco);
            $stmt->bindParam(":fotoVariacao", $imagem);
            $stmt->bindParam(":idProduto", $idProduto, PDO::PARAM_INT);
            
            return $stmt->execute() ? "Produto criado com sucesso!" : "Erro ao inserir o produto.";
        } catch (PDOException $e) {
            return "Erro ao inserir o produto: " . $e->getMessage();
        }
    }

    public function selecionarVariacaoPorID($id) {
        try {
            $stmt = $this->conn->prepare("SELECT * FROM variacaoProduto WHERE idVariacao = :id");
            $stmt->bindParam(":id", $id, PDO::PARAM_INT);
            $stmt->execute();
            $dados = $stmt->fetch(PDO::FETCH_ASSOC);
            
            return $dados ? new Produto(
                $dados['idVariacao'],
                $dados['desativado'],
                $dados['nomeVariacao'],
                $dados['precoVariacao'],
                $dados['fotoVariacao'],
                $dados['idProduto']
            ) : null;
        } catch (PDOException $e) {
            throw new Exception("Erro ao buscar variação por ID: " . $e->getMessage());
        }
    }

    public function editarVariacao($idVariacao, $idProduto, $nomeProduto, $preco, $imagemProduto) {
        try {
            $stmt = $this->conn->prepare("UPDATE variacaoProduto SET nomeVariacao = :nomeVariacao, precoVariacao = :precoVariacao, fotoVariacao = :fotoVariacao, idProduto = :idProduto WHERE idVariacao = :idVariacao AND desativado != 1");
            $stmt->bindParam(":nomeVariacao", $nomeProduto);
            $stmt->bindParam(":precoVariacao", $preco);
            $stmt->bindParam(":fotoVariacao", $imagemProduto);
            $stmt->bindParam(":idProduto", $idProduto, PDO::PARAM_INT);
            $stmt->bindParam(":idVariacao", $idVariacao, PDO::PARAM_INT);
            
            return $stmt->execute();
        } catch (PDOException $e) {
            throw new Exception("Erro ao editar o produto: " . $e->getMessage());
        }
    }

    public function desativarVariacao($idVariacao) {
        try {
            $stmt = $this->conn->prepare("UPDATE variacaoProduto SET desativado = 1 WHERE idVariacao = :idVariacao");
            $stmt->bindParam(":idVariacao", $idVariacao, PDO::PARAM_INT);
            
            return $stmt->execute();
        } catch (PDOException $e) {
            throw new Exception("Erro ao desativar o produto: " . $e->getMessage());
        }
    }
}
