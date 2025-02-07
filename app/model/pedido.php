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
                echo $e;
                throw new Exception("Erro ao listar pedido: " . $e->getMessage());
            }
        } else {
            throw new Exception("ID do pedido não fornecido!");
        }
    }

    public function listarInformacoesPedido($idPedido) {
        if (isset($idPedido)) {
            try {
                $stmt = $this->conn->prepare("CALL ListarInformacoesPedido(?)");
                $stmt->bindParam(1, $idPedido, PDO::PARAM_INT);
                $stmt->execute();
    
                $produtos = [];
                
                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    $produtos[] = $row;
                }

                return $produtos;
            } catch (PDOException $e) {
                echo $e;
                throw new Exception("Erro ao listar pedido: " . $e->getMessage());
            }
        } else {
            throw new Exception("ID do pedido não fornecido!");
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

    public function salvarItensPedido($itensCarrinho, $id) {

        try {
            foreach ($itensCarrinho as $item) {
    
                $stmt = $this->conn->prepare("CALL SalvarItensPedido(?, ?, ?)");
                $stmt->bindParam(1, $id, PDO::PARAM_INT);
                $stmt->bindParam(2, $item['id'], PDO::PARAM_INT);
                $stmt->bindParam(3, $item['qntd'], PDO::PARAM_INT); 
                $stmt->execute();
                $stmt->closeCursor();
            }
        } catch (PDOException $e) {
            echo "Erro ao salvar os itens do pedido: " . $e->getMessage();
            throw new Exception("Erro ao salvar itens do pedido: " . $e->getMessage());
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

    public function mudarStatus($idPedido, $usuario, $motivoCancelamento) {
        try {
            $pedido = $this->listarPedidoPorId($idPedido);
    
            $novoStatus = $this->determinarNovoStatusPorUsuario($usuario, $pedido);
    
            $stmt = $this->conn->prepare("CALL EditarPedidoStatus(?, ?, ?)");
            $stmt->bindParam(1, $idPedido, PDO::PARAM_INT);
            $stmt->bindParam(2, $novoStatus, PDO::PARAM_STR);
            $stmt->bindParam(3, $motivoCancelamento, PDO::PARAM_STR);
            $stmt->execute();
    
        } catch (Exception $e) {
            throw new Exception("Erro ao atualizar status do pedido: " . $e->getMessage());
        }
    }

    public function listarTodosItensPedidos($dataInicio, $dataFim) {
        try {
            $stmt = $this->conn->prepare("CALL ListarProdutosPedido(?, ?)");
            $stmt->bindParam(1, $dataInicio);
            $stmt->bindParam(2, $dataFim);
            $stmt->execute();
    
            $itensPedidos = [];
            if ($stmt->rowCount() > 0) {
                $itensPedidos = $stmt->fetchAll(PDO::FETCH_ASSOC); 
            }
    
            $result = [];
    
            foreach ($itensPedidos as $row) {
                $idVariacao = $row['idVariacao'];
                $quantidade = $row['quantidade'];
                $nomeProduto = $row['NomeProduto'];
                $preco = $row['Preco'];
                $foto = $row['Foto'];
                $produtoDesativado = $row['ProdutoDesativado'];
    
                if (isset($result[$idVariacao])) {
                    $result[$idVariacao]['quantidade'] += $quantidade;
                } else {
                    $result[$idVariacao] = [
                        'idVariacao' => $idVariacao,
                        'quantidade' => $quantidade,
                        'NomeProduto' => $nomeProduto,
                        'Preco' => $preco,
                        'Foto' => $foto,
                        'ProdutoDesativado' => $produtoDesativado,
                    ];
                }
            }
    
            usort($result, function ($a, $b) {
                return $b['quantidade'] <=> $a['quantidade'];
            });
    
            return $result;
    
        } catch (PDOException $e) {
            throw new Exception("Erro ao recuperar os itens do pedido: " . $e->getMessage());
        }
    }
    
    public function listarResumo($dataInicio, $dataFim) {
        try {
            $stmt = $this->conn->prepare("CALL ListarResumoVendas(?, ?)");
            $stmt->bindParam(1, $dataInicio);
            $stmt->bindParam(2, $dataFim);
            $stmt->execute();
            
            $auxClientes = [];
            $auxProdutos = [];
            $auxPedidos = [];
            $result = [
                'totalVendas' => 0,
                'totalPedidosClientes' => 0,
                'totalProdutos' => 0,
                'pedidosFeitos' => 0
            ];
            
            if ($stmt->rowCount() > 0) {
                $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
                
                foreach ($rows as $row) {
                    $idCliente = $row['idCliente'];
                    $idProduto = $row['idProduto'];
                    $idPedido = $row['pedidoId'];
    
                    if (!isset($auxPedidos[$idPedido])) {
                        $auxPedidos[$idPedido] = true;
                        $result['totalVendas'] += $row['valorTotal'];
                        $result['pedidosFeitos'] += 1;
                    }
                    
                    if (!isset($auxClientes[$idCliente])) {
                        $auxClientes[$idCliente] = true;
                        $result['totalPedidosClientes'] += 1; 
                    }
                    
                    if (!isset($auxProdutos[$idProduto])) {
                        $auxProdutos[$idProduto] = true;
                        $result['totalProdutos'] += 1;
                    }
                }
            }
            
            return $result;
            
        } catch (PDOException $e) {
            throw new Exception("Erro ao recuperar o resumo das vendas: " . $e->getMessage());
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
            'Aguardando Confirmação' => 'Aguardando Envio',
            'Aguardando Envio' => 'A Caminho',
            'Entregue' => $pedido['tipoFrete'] == 0 ? 'Concluído' : 'Entregue'
        ]);
    }
    
    private function determinarNovoStatusCliente($pedido) {
        return $this->determinarNovoStatus($pedido['statusPedido'], [
            'A Caminho' => 'Entregue',
            'Aguardando Confirmação' => 'Cancelado',
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

    public function calcularFrete($cep) {
        $url = "http://localhost:8080/sorveteria/frete?cep=" . urlencode($cep);
        $ch = curl_init();
        $timeout = 5;
    
        // Configura a URL e o método GET
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_TIMEOUT, $timeout);
    
        // Executa a requisição
        $data = curl_exec($ch);
    
        if (curl_errno($ch)) {
            echo 'Error:' . curl_error($ch);
            return 'Erro ao calcular o frete.';
        }
    
        if ($data == 1) {
            return 'Estamos muito distantes do seu endereço para fazer uma entrega!';
        }

        curl_close($ch);
    
        return $data;
    }

    
}
?>