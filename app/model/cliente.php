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
        $database = new DataBase();
        $this->conn = $database->getConnection();
    }

    public function getCliente($email) {
        if (isset($email)) {
            $stmt = $this->conn->prepare("CALL SP_GetClienteInfo(?)");
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
    
                // Fecha o cursor para permitir outra execução
                $stmt->closeCursor();
    
                // Chama a função para listar o endereço
                $this->listarEndereco($this->idEndereco);
                
                return $this->idEndereco; 
            } else {
                echo "Cliente não encontrado!";
            }
        } else {
            echo "Email não fornecido!";
        }
    }
    

    public function mostrarDadosCliente() {
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
    }

    public function listarEndereco($idEndereco){
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
    }
}
?>
