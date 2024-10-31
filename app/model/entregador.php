<?php 
require_once '../config/database.php';

    class Entregador {
        private $id;
        private $nome;
        private $email;
        private $telefone;
        private $senha;
        private $cnh;
        private $conn;

        public function __construct() {
            $database = new DataBase;
            $this->conn = $database->getConnection();
        }

        public function listarEntregadores($idPedido) {
            try {
                $stmt = $this->conn->prepare("CALL ListarEntregadores()");
                $stmt->execute(); 
    
                if ($stmt->rowCount() > 0) {
                    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
                    
                    foreach ($result as $row) {
                        $this->mostrarListarEntregadores($row, $idPedido); 
                    }
                } else {
                    echo '<p>Nenhum entregador encontrado.</p>'; 
                }
            } catch (PDOException $e) {
                echo "Erro ao listar entregadores: " . $e->getMessage();
            }
        }

        private function mostrarListarEntregadores($row, $idPedido) {
            $redirectAtribuirEntregador = 'atribuirEntregador.php?idEntregador=' . $row['idEntregador'] . '&idPedido=' . $idPedido;
            echo '
                <div class="card categ d-flex align-items-center">
                    <div class="d-flex align-items-center flex-column c2">
                        <h3 class="titulo px-3">'.$row["nome"].'</h3>
                        <div class="px-3">
                            <p>Email: '.$row["email"].'</p>
                            <p>Celular: '.$row["telefone"].'</p>
                            <p>CNH: '.$row["cnh"].'</p>
                        </div>
                        <button><a href="' . $redirectAtribuirEntregador . '">Atribuir entregador</a></button>
                    </div>
                </div>';
        }

        public function listarEntregadorPorId($idEntregador) {
            try {
                $stmt = $this->conn->prepare("CALL ListarEntregadorPorID(?)");
                $stmt->bindParam(1, $idEntregador);
                $stmt->execute(); 
    
                if ($stmt->rowCount() > 0) {
                    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
                    
                    foreach ($result as $row) {
                        $this->mostrarListarEntregadorPorID($row); 
                    }
                } else {
                    echo '<p>Nenhum entregador encontrado.</p>'; 
                }
            } catch (PDOException $e) {
                echo "Erro ao listar entregadores: " . $e->getMessage();
            }
        }

        private function mostrarListarEntregadorPorId($row) {
            echo '
                <h3 class="titulo px-3"> Entregador Responsável: '.$row["nome"].'</h3>
                <div class="px-3">
                    <p>Email: '.$row["email"].'</p>
                    <p>Celular: '.$row["telefone"].'</p>
                    <p>CNH: '.$row["cnh"].'</p>
                </div>';
        }
    }

    public function listarEntregadorPorId($idEntregador) {
        try {
            $stmt = $this->conn->prepare("CALL ListarEntregadorPorID(?)");
            $stmt->bindParam(1, $idEntregador);
            $stmt->execute(); 
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            throw new Exception("Erro ao listar entregador: " . $e->getMessage());
        }
    }
}
?>
