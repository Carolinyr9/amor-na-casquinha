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
                        $pedidos[] = $row; 
                    }
                    return $pedidos;  
                } else {
                    return [];  
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
                    $pedido = $stmt->fetch(PDO::FETCH_ASSOC);  
                }
                return $pedido;  
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
                $pedidos = $stmt->fetchAll(PDO::FETCH_ASSOC); 
            }
            return $pedidos; 
        } catch (PDOException $e) {
            throw new Exception("Erro ao listar pedidos: " . $e->getMessage());
        }
    }

    public function listarPedidosEntregador($emailEntregador) {
        try {
            $stmt = $this->conn->prepare("CALL ListarPedidosPorEmailEntregador(?)");
            $stmt->bindParam(1, $emailEntregador, PDO::PARAM_STR);
            $stmt->execute(); 

            $pedidos = [];
            if ($stmt->rowCount() > 0) {
                $pedidos = $stmt->fetchAll(PDO::FETCH_ASSOC); 
            }
            return $pedidos; 
        } catch (PDOException $e) {
            throw new Exception("Erro ao listar pedidos: " . $e->getMessage());
        }
    }
    public function mudarStatus($idPedido, $usuario) {
        try {
            $pedido = $this->listarPedidoPorId($idPedido);
    
            $novoStatus = $this->determinarNovoStatusPorUsuario($usuario, $pedido);
    
            $stmt = $this->conn->prepare("CALL EditarStatusPedido(?, ?)");
            $stmt->bindParam(1, $idPedido, PDO::PARAM_INT);
            $stmt->bindParam(2, $novoStatus, PDO::PARAM_STR);
            $stmt->execute();
    
        } catch (Exception $e) {
            throw new Exception("Erro ao atualizar status do pedido: " . $e->getMessage());
        }
    }

    private function determinarNovoStatusPorUsuario($usuario, $pedido) {
        switch($usuario) {
            case 'ENTR':
                return $this->determinarNovoStatusEntregador($pedido);
    
            case 'CLIE':
                return $this->determinarNovoStatusCliente($pedido);
    
            case 'FUNC':
                return $this->determinarNovoStatusFuncionario($pedido);
    
            default:
                return $pedido['statusPedido'];
        }
    }

    private function determinarNovoStatusFuncionario($pedido) {
        return $this->determinarNovoStatus($pedido['statusPedido'], [
            'Aguardando Pagamento' => 'Aguardando Envio',
            'Aguardando Envio' => 'A Caminho',
            'Entregue' => $pedido['tipoFrete'] == 0 ? 'Concluído' : 'Entregue'
        ]);
    }
    
    private function determinarNovoStatusCliente($pedido) {
        return $this->determinarNovoStatus($pedido['statusPedido'], [
            'A Caminho' => 'Entregue',
            'Aguardando Pagamento' => 'Cancelado',
            'Aguardando Envio' => 'Cancelado'
        ]);
    }
    
    private function determinarNovoStatusEntregador($pedido) {
        return $this->determinarNovoStatus($pedido['statusPedido'], [
            'A Caminho' => 'Entrega Falhou',
            'Entregue' => 'Concluído'
        ]);
    }
    
    private function determinarNovoStatus($statusAtual, $mudancas) {
        return $mudancas[$statusAtual] ?? $statusAtual;
    }
 
    public function atribuirEntregador($idPedido, $idEntregador) {
        try {
            $stmt = $this->conn->prepare("CALL ListarPedidosAtribuidosEntregador(?)");
            $stmt->bindParam(1, $email, PDO::PARAM_INT);
            $stmt = $this->conn->prepare("CALL AtribuirPedidoEntregador(?, ?)");
            $stmt->bindParam(1, $idPedido, PDO::PARAM_INT);
            $stmt->bindParam(2, $idEntregador, PDO::PARAM_INT);
            $stmt->execute();
            if ($stmt->rowCount() > 0) {
                $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
                
                foreach ($result as $row) {
                    $this->mostrarListarPedidos($row); 
                }
            } else {
                echo '<p>Nenhum pedido encontrado.</p>'; 
            }
            echo "<script>
                    alert('Entregador atribuído com sucesso!');
                    window.location.href = 'pedidos.php';
                </script>";
        } catch (PDOException $e) {
            echo "Erro ao listar pedidos atribuídos: " . $e->getMessage();
            throw new Exception("Erro ao atualizar entregador: " . $e->getMessage());
        }
    }
}
?>
