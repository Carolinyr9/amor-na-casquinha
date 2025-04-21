<?php
namespace app\repository;
use app\config\DataBase;
use PDO;
use PDOException;
use app\controller\ClienteController;
use app\controller\EnderecoController;

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

    public function listarPedidoPorIdCliente($id) {
        try {
            $stmt = $this->conn->prepare("SELECT * FROM pedidos WHERE idCliente = ?");
            $stmt->bindParam(1, $id);
            $stmt->execute();

            return $stmt->fetch(PDO::FETCH_ASSOC) ?: false;
        } catch (PDOException $e) {
            throw new Exception("Erro ao listar pedidos: " . $e->getMessage());
        }
    }

    public function listarInformacoesPedido($idPedido) {
        try{
            $stmt = $this->conn->prepare("SELECT ip.idPedido, ip.quantidade, vp.idVariacao,
                                            vp.nomeVariacao AS NomeProduto,
                                            vp.precoVariacao AS Preco,
                                            vp.fotoVariacao AS Foto,
                                            vp.desativado AS ProdutoDesativado
                                            FROM itens_pedido ip 
                                            INNER JOIN variacaoproduto vp ON ip.idProduto = vp.idVariacao
                                            WHERE ip.idPedido = ?");
            $stmt->bindParam(1, $idPedido);
            $stmt->execute();

            return $stmt->fetchAll(PDO::FETCH_ASSOC) ?: false;
        } catch(PDOException $e) {
            throw new Exception("Erro ao listar pedidos: " . $e->getMessage());
        }
    }

    public function criarPedido($idCliente, $dataPedido, $tipoFrete, $idEndereco, $valorTotal, $statusPedido, $frete, $meioDePagamento, $trocoPara) {
        try {
            date_default_timezone_set('America/Sao_Paulo');
            $dataPedido = date('Y-m-d H:i:s');
            $stmt = $this->conn->prepare("INSERT INTO pedidos(idCliente, dtPedido, tipoFrete, idEndereco, valorTotal, statusPedido, frete, meioPagamento, trocoPara) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
                
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
            echo "Erro ao criar o pedido: " . $e->getMessage();
            throw new Exception("Erro ao criar o pedido: " . $e->getMessage());
        }
    }

    public function listarPedidos() {
        try {
            $stmt = $this->conn->prepare("SELECT * FROM pedidos");
            $stmt->execute();

            return $stmt->fetchAll(PDO::FETCH_ASSOC) ?: false;
        } catch (PDOException $e) {
            throw new Exception("Erro ao listar pedidos: " . $e->getMessage());
        }
    }

    public function listarPedidosEntregador($idEntregador) {
        try {
            $stmt = $this->conn->prepare("SELECT * FROM pedidos WHERE idEntregador = ?");
            $stmt->bindParam(1, $idEntregador);
            $stmt->execute();

            return $stmt->fetchAll(PDO::FETCH_ASSOC) ?: false;
        } catch (PDOException $e) {
            throw new Exception("Erro ao listar pedidos: " . $e->getMessage());
        }
    }

    public function mudarStatus($idPedido, $novoStatus) {
        try {
            $stmt = $this->conn->prepare("UPDATE pedidos SET statusPedido = ? WHERE idPedido = ?");
            $stmt->bindParam(1, $novoStatus);
            $stmt->bindParam(2, $idPedido);
            
            return $stmt->rowCount() > 0 ? true : false;
        } catch (PDOException $e) {
            throw new Exception("Erro ao mudar o status: " . $e->getMessage());
        }
    }

    public function mudarmudarStatusCancelamento($idPedido, $novoStatus, $motivoCancelamento) {
        try {
            $stmt = $this->conn->prepare("UPDATE pedidos SET statusPedido = ?, motivoCancelamento = ? WHERE idPedido = ?");
            $stmt->bindParam(1, $novoStatus);
            $stmt->bindParam(2, $motivoCancelamento);
            $stmt->bindParam(3, $idPedido);

            return $stmt->rowCount() > 0 ? true : false;
        } catch (PDOException $e) {
            throw new Exception("Erro ao mudar o status: " . $e->getMessage());
        }
    }

    public function listarResumoVendas($dataInicio, $dataFim) {
        try {
            $stmt = $this->conn->prepare("SELECT pedidos.valorTotal, pedidos.idCliente, pedidos.idPedido AS pedidoId, itens_pedido.idProduto FROM pedidos JOIN itens_pedido ON pedidos.idPedido = itens_pedido.idPedido WHERE DATE(pedidos.dtPedido) BETWEEN ? AND ?");
            $stmt->bindParam(1, $dataInicio);
            $stmt->bindParam(2, $dataFim);
            $stmt->execute();

            return $stmt->fetchAll(PDO::FETCH_ASSOC) ?: false;
        } catch (PDOException $e) {
            throw new Exception("Erro ao resumir as vendas: " . $e->getMessage());
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
            throw new Exception("Erro ao atribuir entregador ao pedido: " . $e->getMessage());
        }
    }

}