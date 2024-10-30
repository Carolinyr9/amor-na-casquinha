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
        $redirectAtribuirEntregador = 'atribuirEntregador.php?idPedido=' . $row['idPedido'];
        $redirectToInformacao = 'informacoesPedido.php?idPedido=' . $row['idPedido'];
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
                                <p>Status: ' . $row['statusPedido'] . '</p>';
                                
                                if ($row['tipoFrete'] == 1 && is_null($row['idEntregador'])) {
                                    echo '<button id="vari"><a href="' . $redirectAtribuirEntregador . '">Atribuir Entregador</a></button>';
                                } else if ($row['tipoFrete'] == 1) {
                                    echo '<p>Entregador '. $row['idEntregador'] . ' atribuído ao pedido</p>';
                                }

        echo                    '<button id="vari"><a href="' . $redirectToInformacao . '">Ver Informações</a></button>
                            </div>
                        </div>
                    </div>
                </div>        
            </div>';
    }

    public function atribuirEntregador($idPedido, $idEntregador) {
        try {
            $stmt = $this->conn->prepare("CALL AtribuirPedidoEntregador(?, ?)");
            $stmt->bindParam(1, $idPedido, PDO::PARAM_INT);
            $stmt->bindParam(2, $idEntregador, PDO::PARAM_INT);
            $stmt->execute();
            echo "<script>
                alert('Entregador atribuído com sucesso');
                window.location.href = 'pedidos.php';
                </script>";
        } catch (PDOException $e) {
            echo "Erro ao atualizar entregador: " . $e->getMessage();
        }
    }
}
?>
