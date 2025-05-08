<?php
namespace app\repository;
use app\model\ItemPedido;
use app\config\DataBase;
use app\utils\Logger;
use PDO;
use PDOException;
use Exception;

class ItemPedidoRepository {
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

    public function criarPedido($idPedido, $idProduto, $quantidade) {
        try {
            $stmt = $this->conn->prepare("INSERT INTO itens_pedido(idPedido, idProduto, quantidade) VALUES (?, ?, ?)");
                
            $stmt->bindParam(1, $idPedido);
            $stmt->bindParam(2, $idProduto);
            $stmt->bindParam(3, $quantidade);
            $stmt->execute();

            return $this->conn->lastInsertId();
        } catch (PDOException $e) {
            Logger::logError("Erro ao associar itens ao pedido o pedido: " . $e->getMessage());
        }
    }

    public function selecionarItensPorID($idPedido) {
        try {
            $stmt = $this->conn->prepare("SELECT * FROM itens_pedidos WHERE idPedido = ?");
                
            $stmt->bindParam(1, $idPedido);
            $dados = $stmt->fetchAll(PDO::FETCH_ASSOC);

            $itensPedido = [];

            foreach ($dados as $item) {
                $itensPedidos[] = new Pedido(
                    $item['idPedido'],
                    $item['idProduto'],
                    $item['quantidade'],
                );
            }
    
            return $itensPedidos; 


        } catch (PDOException $e) {
            Logger::logError("Erro ao associar itens ao pedido o pedido: " . $e->getMessage());
        }
    }


    public function listarInformacoesPedido($idPedido) {
        try {
            $stmt = $this->conn->prepare("SELECT * FROM itens_pedido WHERE idPedido = ?");
            $stmt->bindParam(1, $idPedido);
            $stmt->execute();
            $dados = $stmt->fetchAll(PDO::FETCH_ASSOC);

            $itens = [];

            foreach ($dados as $item) {
                $itens[] = new ItemPedido(
                    $item['idPedido'],
                    $item['idProduto'],
                    $item['quantidade']  
                );
            }

            return $itens;
        } catch (PDOException $e) {
            Logger::logError("Erro ao listar informações do pedido: " . $e->getMessage());
            return false;
        }
    }

}