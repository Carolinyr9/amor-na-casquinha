<?php 
require_once '../config/database.php';

class Cliente {
    private $id;
    private $nome;
    private $email;
    private $telefone;
    private $senha;
    private $idEndereco; 
    private $conn;

    public function __construct(){
        try {
            $database = new DataBase();
            $this->conn = $database->getConnection();
        } catch (PDOException $e) {
            echo "Erro de conexão com o banco de dados: " . $e->getMessage();
        }
    }

    public function getCliente($email) {
        if (isset($email)) {
            try {
                $stmt = $this->conn->prepare("CALL ListarClientePorEmail(?)");
                $stmt->bindParam(1, $email);
                $stmt->execute();
    
            if ($stmt->rowCount() > 0) {
                $row = $stmt->fetch();
                $this->id = $row["idCliente"];                 
                $this->nome = $row["nome"];             
                $this->email = $row["email"];           
                $this->telefone = $row["telefone"];    
                $this->senha = $row["senha"];
                $this->idEndereco = $row["idEndereco"]; 
    
                    $this->mostrarDadosCliente();
                    $stmt->closeCursor();
                    $this->listarEndereco($this->idEndereco);
                    
                    return $this->idEndereco; 
                } else {
                    echo "Cliente não encontrado!";
                }
            } catch (PDOException $e) {
                echo "Erro ao obter dados do cliente: " . $e->getMessage();
            }
        } else {
            echo "Email não fornecido!";
        }
    }
    

    public function mostrarDadosCliente() {
        try {
            if (!empty($this->nome)) {
                echo '
                <div id="dados">
                    <p>Nome: '.$this->nome.'</p>
                    <p>Email: '.$this->email.'</p>
                    <p>Telefone: '.$this->telefone.'</p>
                    <p>Endereço: </p> 
                </div>
                ';
            } else {
                echo "Dados do cliente não estão disponíveis!";
            }
        } catch (Exception $e) {
            echo "Erro ao mostrar dados do cliente: " . $e->getMessage();
        }
    }

    public function listarEndereco($idEndereco){
        try {
            $stmt = $this->conn->prepare("CALL ListarEnderecoPorID(?)");
            $stmt->bindParam(1, $idEndereco);
            $stmt->execute();
    
            if ($stmt->rowCount() > 0) {
                $row = $stmt->fetch();
                echo '
                    <div id="endereco">
                        <p>'. $row["rua"] . ', ' . $row["numero"] . ', ' . 
                        ($row["complemento"] ? $row["complemento"] . ', ' : '') . 
                        $row["bairro"] . ', ' . $row["cidade"] . ', ' . $row["estado"] . ', ' . $row["cep"] .'</p>
                    </div>
                ';
    
            } else {
                echo "Endereço não encontrado!";
            }
        } catch (PDOException $e) {
            echo "Erro ao listar o endereço: " . $e->getMessage();
        }
    }
}
?>
