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

    public function getPedidosCliente($idCliente) {
        
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
}
?>
