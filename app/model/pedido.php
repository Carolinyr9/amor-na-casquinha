<?php 
    require_once '../config/database.php';

    class Pedido{
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

        public function __construct(){
            $database = new DataBase;
            $this->conn = $database->getConnection();
        }

        public function getPedidosCliente($idCliente) {
            
        }

        public function listarPedidoPorCliente($email){
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
            
                        $this->mostrarDadosPedidos(); 
                    }
                } else {
                    echo "Sem pedidos associados.";
                }
            }
        }

        public function mostrarDadosPedido() {
            echo '
                <p>Número do Pedido: '.$this->id.'</p>
                <p>Realizado em: '.$this->dtPedido.'</p>
                <p>Total: R$'.$this->valorTotal.'</p>
                <p>'.(($this->tipoFrete==0)?'É para entrega!':'É para buscar na sorveteria!').'</p>
                <p>Status: '.$row['statusPedido'].'</p>
                <hr/>
                ';
        }

        public function criarPedido($email, $tipoFrete, $qtdItems) {
            try {
                // Obter conexão com o banco de dados
                $database = new Database();
                $conn = $database->getConnection();
    
                // Pegar a data e hora atual
                $dataPedido = date('Y-m-d H:i:s'); // Formato DATETIME para MySQL
    
                // Preparar a chamada para a procedure `InserirPedido`
                $stmt = $conn->prepare("CALL InserirPedido(?, ?, ?, ?)");
    
                // Bind dos parâmetros necessários
                $stmt->bindParam(1, $email, PDO::PARAM_STR);
                $stmt->bindParam(2, $dataPedido, PDO::PARAM_STR); // Enviar a data atual
                $stmt->bindParam(3, $tipoFrete, PDO::PARAM_INT);
                $stmt->bindParam(4, $qtdItems, PDO::PARAM_INT);
    
                // Executar a procedure
                $stmt->execute();
    
                // Buscar o resultado da procedure
                $result = $stmt->fetch(PDO::FETCH_ASSOC);
    
                // Verificar o status da operação
                if ($result['Status'] == '201') {
                    // Pedido foi criado com sucesso
                    echo "Pedido criado com sucesso! ID do Pedido: " . $result['Body'];
                } else {
                    // Exibir erro em caso de falha
                    echo "Erro ao criar o pedido: " . $result['Error'];
                }
    
            } catch (PDOException $e) {
                echo "Erro: " . $e->getMessage();
            }
        }
    }

?>