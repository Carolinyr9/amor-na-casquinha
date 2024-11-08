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
                    
                    $stmt->closeCursor(); 
    
                    $endereco = $this->listarEndereco($this->idEndereco);
    
                    return [
                        "nome" => $this->nome,
                        "email" => $this->email,
                        "telefone" => $this->telefone,
                        "endereco" => $endereco
                    ];
                } else {
                    return ["error" => "Cliente não encontrado!"];
                }
            } catch (PDOException $e) {
                return ["error" => "Erro ao obter dados do cliente: " . $e->getMessage()];
            }
        } else {
            return ["error" => "Email não fornecido!"];
        }
    }
    
    private function listarEndereco($idEndereco){
        echo $idEndereco;
        try {
            $stmt = $this->conn->prepare("CALL ListarEnderecoPorID(?)");
            $stmt->bindParam(1, $idEndereco);
            $stmt->execute();
            
            if ($stmt->rowCount() > 0) {
                $row = $stmt->fetch();
                $stmt->closeCursor(); 
    
                return [
                    "idEndereco" => $row["idEndereco"],
                    "rua" => $row["rua"],
                    "numero" => $row["numero"],
                    "complemento" => $row["complemento"],
                    "bairro" => $row["bairro"],
                    "cidade" => $row["cidade"],
                    "estado" => $row["estado"],
                    "cep" => $row["cep"]
                ];
            } else {
                return [
                    "error" => "Endereço não encontrado!",
                    "rua" => null,
                    "numero" => null,
                    "bairro" => null,
                    "cidade" => null,
                    "estado" => null,
                    "cep" => null
                ];
            }
        } catch (PDOException $e) {
            return [
                "error" => "Erro ao listar o endereço: " . $e->getMessage(),
                "rua" => null,
                "numero" => null,
                "bairro" => null,
                "cidade" => null,
                "estado" => null,
                "cep" => null
            ];
        }
    }
    
    public function editarCliente($email, $idEndereco, $nome, $telefone, $rua, $cep, $numero, $bairro, $cidade, $estado, $complemento) {
        try {
            $stmt = $this->conn->prepare("CALL EditarCliente(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
            $stmt->bindParam(1, $nome);
            $stmt->bindParam(2, $email);           
            $stmt->bindParam(3, $telefone);
            $stmt->bindParam(4, $idEndereco);
            $stmt->bindParam(5, $rua);
            $stmt->bindParam(6, $numero);    
            $stmt->bindParam(7, $complemento);
            $stmt->bindParam(8, $bairro);
            $stmt->bindParam(9, $cep);           
            $stmt->bindParam(10, $cidade);
            $stmt->bindParam(11, $estado);
            $stmt->execute();

        } catch (PDOException $e) {
            echo "Erro ao editar as informações do cliente: " . $e->getMessage();
        }
    }
}    