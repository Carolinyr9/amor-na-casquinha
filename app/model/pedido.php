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
    private $conn;

    public function __construct() {
        try {
            $database = new DataBase(); 
            $this->conn = $database->getConnection();
        } catch (PDOException $e) {
            echo "Erro ao conectar com o banco de dados: " . $e->getMessage();
        }
    }

    public function listarPedidoPorCliente($email) {
        if (isset($email)) {
            try {
                $stmt = $this->conn->prepare("CALL ListarPedidoPorCliente(?)");
                $stmt->bindParam(1, $email);
                $stmt->execute();

                if ($stmt->rowCount() > 0) {
                    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                        $this->idPedido = $row["idPedido"];
                        $this->idCliente = $row["idCliente"];
                        $this->dataPedido = $row["dtPedido"];
                        $this->dataPagamento = $row["dtPagamento"];
                        $this->tipoFrete = $row["tipoFrete"];
                        $this->rastreioFrete = $row["rastreioFrete"];
                        $this->idEndereco = $row["idEndereco"];
                        $this->valorTotal = $row["valorTotal"];
                        $this->qtdItens = $row["qtdItems"];
                        $this->dataCancelamento = $row["dtCancelamento"];
                        $this->motivoCancelamento = $row["motivoCancelamento"];
                        $this->statusPedido = $row["statusPedido"];
                        $this->mostrarListarPedidosPorCliente(); 
                    }
                } else {
                    echo "Sem pedidos associados.";
                }
            } catch (PDOException $e) {
                echo "Erro ao listar pedidos: " . $e->getMessage();
            }
        } else {
            echo "Email não fornecido!";
        }
    }

    public function mostrarListarPedidosPorCliente() {
        echo '
            <p>Número do Pedido: '.$this->idPedido.'</p>
            <p>Realizado em: '.$this->dataPedido.'</p>
            <p>Total: R$'.$this->valorTotal.'</p>
            <p>'.(($this->tipoFrete == 0) ? 'É para entrega!' : 'É para buscar na sorveteria!').'</p>
            <p>Status: '.$this->statusPedido.'</p>
            <hr/>
        ';
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

            $result = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($result['Status'] == '201') {
                echo "Pedido criado com sucesso! ID do Pedido: " . $result['Body'];
            } else {
                echo "Erro ao criar o pedido: " . $result['Error'];
            }
        } catch (PDOException $e) {
            echo "Erro ao criar o pedido: " . $e->getMessage();
        }
    }

    public function listarPedidos() {
        try {
            $stmt = $this->conn->prepare("CALL ListarPedidos()");
            $stmt->execute(); 

            if ($stmt->rowCount() > 0) {
                $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
                
                foreach ($result as $row) {
                    $this->mostrarListarPedidos($row); 
                }
            } else {
                echo '<p>Nenhum pedido encontrado.</p>'; 
            }
        } catch (PDOException $e) {
            echo "Erro ao listar pedidos: " . $e->getMessage();
        }
    }

    private function mostrarListarPedidos($row) { 
        echo '<div class="c1">
                <div class="c2">
                    <div class="c3">
                        <picture>
                            <img src="../images/FUNC.png" alt="Pedido ' . $row["idPedido"] . '">
                        </picture>
                    </div>
                    <div>
                        <div id="dados">
                            <h3 class="titulo px-3">Número do Pedido: ' . $row["idPedido"] . '</h3>
                            <div class="px-3">
                                <p>Realizado em: ' . $row['dtPedido'] . '</p>
                                <p>Total: R$ ' . number_format($row['valorTotal'], 2, ',', '.') . '</p>
                                <p>' . (($row['tipoFrete'] == 1) ? 'É para entrega!' : 'É para buscar na sorveteria!') . '</p>
                                <p>Status: ' . $row['statusPedido'] . '</p>
                            </div>
                        </div>
                    </div>
                </div>        
            </div>';
    }

    public function listarPedidosAtribuidos($email) {
        try {
            $stmt = $this->conn->prepare("CALL ListarPedidosAtribuidosEntregador(?)");
            $stmt->bindParam(1, $email, PDO::PARAM_INT);
            $stmt->execute();

            if ($stmt->rowCount() > 0) {
                $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
                
                foreach ($result as $row) {
                    $this->mostrarListarPedidos($row); 
                }
            } else {
                echo '<p>Nenhum pedido encontrado.</p>'; 
            }
        } catch (PDOException $e) {
            echo "Erro ao listar pedidos atribuídos: " . $e->getMessage();
        }
    }
}
?>
