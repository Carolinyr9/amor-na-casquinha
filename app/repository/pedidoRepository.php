<?php
namespace app\repository;
use app\config\DataBase;
use PDO;
use PDOException;

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

    public function criarPedido($email, $tipoFrete, $valorTotal, $frete, $meioDePagamento, $trocoPara) {
        try {
            date_default_timezone_set('America/Sao_Paulo');
            $dataPedido = date('Y-m-d H:i:s');
    
            $stmt = $this->conn->prepare("CALL CriarPedido(?, ?, ?, ?, ?, ?, ?)");
            
            $stmt->bindParam(1, $email);
            $stmt->bindParam(2, $dataPedido);
            $stmt->bindParam(3, $tipoFrete);
            $stmt->bindParam(4, $valorTotal);
            $stmt->bindParam(5, $frete,);
            $stmt->bindParam(6, $meioDePagamento,);
            $stmt->bindParam(7, $trocoPara,);
            $stmt->execute();

            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return ($result['Body']);

        } catch (PDOException $e) {
            echo "Erro ao criar o pedido: " . $e->getMessage();
            throw new Exception("Erro ao criar o pedido: " . $e->getMessage());
        }
    }

}