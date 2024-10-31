<?php 
require_once '../config/database.php';

class Pedido {
    private $idPedido;
    private $idCliente;
    private $dataPedido;
    private $dataPagamento;
    private $tipoFrete;
    private $rastreioFrete;
    private $idEndereco;
    private $valorTotal;
    private $qtdItens;
    private $dataCancelamento;
    private $motivoCancelamento;
    private $statusPedido;
    private $idEntregador;
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

    public function listarPedidoPorId($idPedido) {
        if (isset($idPedido)) {
            try {
                $stmt = $this->conn->prepare("CALL ListarPedidoPorID(?)");
                $stmt->bindParam(1, $idPedido, PDO::PARAM_INT);
                $stmt->execute();
    
                if ($stmt->rowCount() > 0) {
                    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                        $this->mostrarListarPedidosPorId($row);
                        $_SESSION['idEntregador'] = $row['idEntregador'];
                    }
                } else {
                    echo "Sem pedidos associados.";
                }
            } catch (PDOException $e) {
                echo "Erro ao listar pedidos: " . $e->getMessage();
            }
        } else {
            echo "ID do pedido não fornecido!";
        }
    }
    

    private function mostrarListarPedidosPorId($row) { 
        $redirectToAlterarStatus = 'atribuirEntregador.php?idPedido=' . $row['idPedido'];
        $redirectToAcompanharEntrega = 'informacoesPedido.php?idPedido=' . $row['idPedido'];
        echo '
            <div>
                <div id="dados">
                    <h3 class="titulo px-3">Número do Pedido: ' . $row["idPedido"] . '</h3>
                    <div class="px-3">
                        <p>Realizado em: ' . $row['dtPedido'] . '</p>
                        <p>Total: R$ ' . number_format($row['valorTotal'], 2, ',', '.') . '</p>
                        <p>' . (($row['tipoFrete'] == 1) ? 'É para entrega!' : 'É para buscar na sorveteria!') . '</p>
                        <p>Status: ' . $row['statusPedido'] . '</p>';
                        
                        if ($row['tipoFrete'] == 1 && isset($row['idEntregador'])) {
                            echo '<p>Entregador '. $row['idEntregador'] . ' atribuído ao pedido</p>
                            <button id="vari"><a href="' . $redirectToAcompanharEntrega. '">Acompanhar Entrega</a></button>';
                                }
        echo                    '<button id="vari"><a href="' . $redirectToAlterarStatus . '">Alterar Status</a></button>
                    </div>
                </div>
            </div>';
    }

    public function criarPedido($email, $tipoFrete, $qtdItens) {
        try {
            $dataPedido = date('Y-m-d H:i:s');

            $stmt = $this->conn->prepare("CALL InserirPedido(?, ?, ?, ?)");
            $stmt->bindParam(1, $email);
            $stmt->bindParam(2, $dataPedido);
            $stmt->bindParam(3, $tipoFrete);
            $stmt->bindParam(4, $qtdItens);
            $stmt->execute();

            return $stmt->fetch(PDO::FETCH_ASSOC); // Retorna o resultado
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
