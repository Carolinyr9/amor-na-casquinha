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

    public function __construct() {
        $database = new DataBase;
        $this->conn = $database->getConnection();
    }

    public function listarPedidoPorCliente($email) {
        if (isset($email)) {
            $stmt = $this->conn->prepare("CALL ListarPedidoPorCliente(?)");
            $stmt->bindParam(1, $email);
            $stmt->execute();

            if ($stmt->rowCount() > 0) {
                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    $this->idPedido = $row["idPedido"];
                    $this->idCliente = $row["idCliente"];
                    $this->dtPedido = $row["dtPedido"];
                    $this->dtPagamento = $row["dtPagamento"];
                    $this->tipoFrete = $row["tipoFrete"];
                    $this->rastreioFrete = $row["rastreioFrete"];
                    $this->idEndereco = $row["idEndereco"];
                    $this->valorTotal = $row["valorTotal"];
                    $this->qtdItems = $row["qtdItems"];
                    $this->dtCancelamento = $row["dtCancelamento"];
                    $this->motivoCancelamento = $row["motivoCancelamento"];
                    $this->statusPedido = $row["statusPedido"];
                    $this->mostrarDadosPedido(); 
                }
            } else {
                echo "Sem pedidos associados.";
            }
        }
    }

    public function mostrarDadosPedido() {
        echo '
            <p>Número do Pedido: '.$this->idPedido.'</p>
            <p>Realizado em: '.$this->dtPedido.'</p>
            <p>Total: R$'.$this->valorTotal.'</p>
            <p>'.(($this->tipoFrete==0)?'É para entrega!':'É para buscar na sorveteria!').'</p>
            <p>Status: '.$this->statusPedido.'</p>
            <hr/>
        ';
    }

    public function criarPedido($email, $tipoFrete, $qtdItems) {
        try {
            $database = new Database();
            $conn = $database->getConnection();
            $dataPedido = date('Y-m-d H:i:s');

            $stmt = $conn->prepare("CALL InserirPedido(?, ?, ?, ?)");
            $stmt->bindParam(1, $email);
            $stmt->bindParam(2, $dataPedido);
            $stmt->bindParam(3, $tipoFrete);
            $stmt->bindParam(4, $qtdItems);
            $stmt->execute();

            $result = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($result['Status'] == '201') {
                echo "Pedido criado com sucesso! ID do Pedido: " . $result['Body'];
            } else {
                echo "Erro ao criar o pedido: " . $result['Error'];
            }
        } catch (PDOException $e) {
            echo "Erro: " . $e->getMessage();
        }
    }

    public function listarPedidos() {
        $stmt = $this->conn->prepare("CALL ListarPedidos()");
        $stmt->execute(); 
    
        if ($stmt->rowCount() > 0) {
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            foreach ($result as $row) {
                $lastPedidoId = $row["idPedido"];
                
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
        } else {
            echo '<p>Nenhum pedido encontrado.</p>'; 
        }
    
        $stmt = null;
    }
    
}
?>
