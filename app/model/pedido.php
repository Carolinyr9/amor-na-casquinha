<?php 
require_once '../config/database.php';

class Pedido {
    private $conn;

    public function __construct() {
        try {
            $database = new DataBase(); 
            $this->conn = $database->getConnection();
        } catch (PDOException $e) {
            throw new Exception("Erro ao conectar com o banco de dados: " . $e->getMessage());
        }
    }

    public function listarPedidoPorCliente($email) {
        if (isset($email)) {
            try {
                $stmt = $this->conn->prepare("CALL ListarPedidoPorCliente(?)");
                $stmt->bindParam(1, $email);
                $stmt->execute();

                $pedidos = [];
                if ($stmt->rowCount() > 0) {
                    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                        $pedidos[] = $row; // Armazena todos os pedidos em um array
                    }
                    return $pedidos; // Retorna todos os pedidos
                } else {
                    return []; // Retorna um array vazio se não houver pedidos
                }
            } catch (PDOException $e) {
                throw new Exception("Erro ao listar pedidos: " . $e->getMessage());
            }
        } else {
            throw new Exception("Email não fornecido!");
        }
    }

    public function listarPedidoPorId($idPedido) {
        if (isset($idPedido)) {
            try {
                $stmt = $this->conn->prepare("CALL ListarPedidoPorID(?)");
                $stmt->bindParam(1, $idPedido, PDO::PARAM_INT);
                $stmt->execute();

                $pedido = null;
                if ($stmt->rowCount() > 0) {
                    $pedido = $stmt->fetch(PDO::FETCH_ASSOC); // Busca apenas um pedido
                }
                return $pedido; // Retorna o pedido ou null
            } catch (PDOException $e) {
                throw new Exception("Erro ao listar pedido: " . $e->getMessage());
            }
        } else {
            throw new Exception("ID do pedido não fornecido!");
        }
    }

    public function criarPedido($email, $tipoFrete, $valorTotal) {
        try {
            $dataPedido = date('Y-m-d H:i:s');

            $stmt = $this->conn->prepare("CALL InserirPedido(?, ?, ?, ?)");
            $stmt->bindParam(1, $email);
            $stmt->bindParam(2, $dataPedido);
            $stmt->bindParam(3, $tipoFrete);
            $stmt->bindParam(4, $valorTotal);
            $stmt->execute();

            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            throw new Exception("Erro ao criar o pedido: " . $e->getMessage());
        }
    }

    public function listarPedidos() {
        try {
            $stmt = $this->conn->prepare("CALL ListarPedidos()");
            $stmt->execute(); 

            $pedidos = [];
            if ($stmt->rowCount() > 0) {
                $pedidos = $stmt->fetchAll(PDO::FETCH_ASSOC); // Busca todos os pedidos
            }
            return $pedidos; // Retorna todos os pedidos
        } catch (PDOException $e) {
            throw new Exception("Erro ao listar pedidos: " . $e->getMessage());
        }
    }

    public function atribuirEntregador($idPedido, $idEntregador) {
        try {
            $stmt = $this->conn->prepare("CALL AtribuirPedidoEntregador(?, ?)");
            $stmt->bindParam(1, $idPedido, PDO::PARAM_INT);
            $stmt->bindParam(2, $idEntregador, PDO::PARAM_INT);
            $stmt->execute();
            echo "<script>
                    alert('Entregador atribuído com sucesso!');
                    window.location.href = 'pedidos.php';
                </script>";
        } catch (PDOException $e) {
            throw new Exception("Erro ao atualizar entregador: " . $e->getMessage());
        }
    }
}
?>
