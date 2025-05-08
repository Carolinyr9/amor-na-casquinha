<?php
namespace app\repository;
use app\config\DataBase;
use app\model\Pedido;
use app\utils\helpers\Logger;
use PDO;
use PDOException;
use Exception;

class PedidoRepository {
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

    public function listarPedidoPorIdCliente($idCliente) {
        try {
            $stmt = $this->conn->prepare("SELECT * FROM pedidos WHERE idCliente = ?");
            $stmt->bindParam(1, $idCliente);
            $stmt->execute();
            $dados = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
            if (empty($dados)) {
                return [];
            }
    
            $pedidos = [];
            foreach ($dados as $pedido) {
                $pedidos[] = new Pedido(
                    $pedido['idPedido'],
                    $pedido['idCliente'],
                    $pedido['dtPedido'],
                    $pedido['dtPagamento'],
                    $pedido['tipoFrete'],
                    $pedido['idEndereco'],
                    $pedido['valorTotal'],
                    $pedido['dtCancelamento'],
                    $pedido['motivoCancelamento'],
                    $pedido['statusPedido'],
                    $pedido['idEntregador'],
                    $pedido['frete'],
                    $pedido['meioPagamento'],
                    $pedido['trocoPara']
                );
            }
    
            return $pedidos;
    
        } catch (PDOException $e) {
            Logger::logError("Erro ao listar pedidos por ID do cliente: " . $e->getMessage());
            return []; 
        }
    }

    public function listarPedidoPorId($idPedido) {
        try {
            $stmt = $this->conn->prepare("SELECT * FROM pedidos WHERE idPedido = ?");
            $stmt->bindParam(1, $idPedido);
            $stmt->execute();

            $dados = $stmt->fetch(PDO::FETCH_ASSOC);
            
            return $dados ? new Pedido(
                $dados['idPedido'],
                $dados['idCliente'],
                $dados['dtPedido'],
                $dados['dtPagamento'],
                $dados['tipoFrete'],
                $dados['idEndereco'],
                $dados['valorTotal'],
                $dados['dtCancelamento'],
                $dados['motivoCancelamento'],
                $dados['statusPedido'],
                $dados['idEntregador'],
                $dados['frete'],
                $dados['meioPagamento'],
                $dados['trocoPara']   
            ) : null;

        } catch (PDOException $e) {
            Logger::logError("Erro ao listar pedidos por ID do pedido: " . $e->getMessage());
        }
    }

    public function criarPedido($idCliente, $dataPedido, $tipoFrete, $idEndereco, $valorTotal, $statusPedido, $frete, $meioDePagamento, $trocoPara) {
        try {
            $stmt = $this->conn->prepare("INSERT INTO pedidos(idCliente, dtPedido, tipoFrete, idEndereco, valorTotal, statusPedido, frete, meioPagamento, trocoPara) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
                
            $stmt->bindParam(1, $idCliente);
            $stmt->bindParam(2, $dataPedido);
            $stmt->bindParam(3, $tipoFrete);
            $stmt->bindParam(4, $idEndereco);
            $stmt->bindParam(5, $valorTotal);
            $stmt->bindParam(6, $statusPedido);
            $stmt->bindParam(7, $frete);
            $stmt->bindParam(8, $meioDePagamento);
            $stmt->bindParam(9, $trocoPara);
            $stmt->execute();

            return $this->conn->lastInsertId();
        } catch (PDOException $e) {
            Logger::logError("Erro ao criar o pedido: " . $e->getMessage());
        }
    }

    public function listarPedidos() {
        try {
            $stmt = $this->conn->prepare("SELECT * FROM pedidos");
            $stmt->execute();
            $dados = $stmt->fetchAll(PDO::FETCH_ASSOC);

            $pedidos = [];
    
            foreach ($dados as $pedido) {
                $pedidos[] = new Pedido(
                    $pedido['idPedido'],
                    $pedido['idCliente'],
                    $pedido['dtPedido'],
                    $pedido['dtPagamento'],
                    $pedido['tipoFrete'],
                    $pedido['idEndereco'],
                    $pedido['valorTotal'],
                    $pedido['dtCancelamento'],
                    $pedido['motivoCancelamento'],
                    $pedido['statusPedido'],
                    $pedido['idEntregador'],
                    $pedido['frete'],
                    $pedido['meioPagamento'],
                    $pedido['trocoPara']   
                );
            }
    
            return $pedidos; 
        } catch (PDOException $e) {
            Logger::logError("Erro ao listar pedidos: " . $e->getMessage());
            return false;
        }
    }

    public function listarPedidosPorPeriodo($dataInicio, $dataFim) {
        try {
            $stmt = $this->conn->prepare("SELECT * FROM pedidos WHERE DATE(dtPedido) BETWEEN ? AND ?");
            $stmt->bindParam(1, $dataInicio);
            $stmt->bindParam(2, $dataFim);
            $stmt->execute();
            $dados = $stmt->fetchAll(PDO::FETCH_ASSOC);

            $pedidos = [];
    
            foreach ($dados as $pedido) {
                $pedidos[] = new Pedido(
                    $pedido['idPedido'],
                    $pedido['idCliente'],
                    $pedido['dtPedido'],
                    $pedido['dtPagamento'],
                    $pedido['tipoFrete'],
                    $pedido['idEndereco'],
                    $pedido['valorTotal'],
                    $pedido['dtCancelamento'],
                    $pedido['motivoCancelamento'],
                    $pedido['statusPedido'],
                    $pedido['idEntregador'],
                    $pedido['frete'],
                    $pedido['meioPagamento'],
                    $pedido['trocoPara']   
                );
            }
    
            return $pedidos; 
        } catch (PDOException $e) {
            Logger::logError("Erro ao listar pedidos de $dataInicio a $dataFim: " . $e->getMessage());
            return false;
        }
    }

    public function listarPedidosEntregador($idEntregador) {
        try {
            $stmt = $this->conn->prepare("SELECT * FROM pedidos WHERE idEntregador = ?");
            $stmt->bindParam(1, $idEntregador);
            $stmt->execute();
            $dados = $stmt->fetchAll(PDO::FETCH_ASSOC);

            $pedidos = [];
    
            foreach ($dados as $pedido) {
                $pedidos[] = new Pedido(
                    $pedido['idPedido'],
                    $pedido['idCliente'],
                    $pedido['dtPedido'],
                    $pedido['dtPagamento'],
                    $pedido['tipoFrete'],
                    $pedido['idEndereco'],
                    $pedido['valorTotal'],
                    $pedido['dtCancelamento'],
                    $pedido['motivoCancelamento'],
                    $pedido['statusPedido'],
                    $pedido['idEntregador'],
                    $pedido['frete'],
                    $pedido['meioPagamento'],
                    $pedido['trocoPara']   
                );
            }
    
            return $pedidos; 
        } catch (PDOException $e) {
            Logger::logError("Erro ao listar pedidos por entregador: " . $e->getMessage());
        }
    }

    public function atribuirEntregadorPedido($idPedido, $idEntregador) {
        try {
            $stmt = $this->conn->prepare("UPDATE pedidos SET idEntregador = ? WHERE idPedido = ?");
            $stmt->bindParam(1, $idEntregador);
            $stmt->bindParam(2, $idPedido);
            $stmt->execute();

            return $stmt->rowCount() > 0 ? true : false;
        } catch (PDOException $e) {
            Logger::logError("Erro ao atribuir entregador ao pedido: " . $e->getMessage());
        }
    }

    public function mudarStatus($idPedido, $novoStatus) {
        try {
            $stmt = $this->conn->prepare("UPDATE pedidos SET statusPedido = ? WHERE idPedido = ?");
            $stmt->bindParam(1, $novoStatus);
            $stmt->bindParam(2, $idPedido);
            $stmt->execute();
            
            return $stmt->rowCount() > 0 ? true : false;
        } catch (PDOException $e) {
            Logger::logError("Erro ao mudar o status: " . $e->getMessage());
        }
    }

    public function cancelarPedido($idPedido, $novoStatus, $motivoCancelamento) {
        try {
            $stmt = $this->conn->prepare("UPDATE pedidos SET statusPedido = ?, motivoCancelamento = ? WHERE idPedido = ?");
            $stmt->bindParam(1, $novoStatus);
            $stmt->bindParam(2, $motivoCancelamento);
            $stmt->bindParam(3, $idPedido);
            $stmt->execute();

            return $stmt->rowCount() > 0 ? true : false;
        } catch (PDOException $e) {
            Logger::logError("Erro ao mudar o status: " . $e->getMessage());
        }
    }
}