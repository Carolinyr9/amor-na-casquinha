<?php 
namespace app\model;

require_once __DIR__ . '../../config/DataBase.php';
use app\config\DataBase;
use PDO;
use PDOException;

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
            $this->conn = $database->getConnection(); // Garante que a conexão PDO seja criada
        } catch (PDOException $e) {
            echo "Erro de conexão com o banco de dados: " . $e->getMessage();
        }
    }

    public function getCliente($email) {
        if (!isset($email)) {
            return ["error" => "Email não fornecido!"];
        }

        try {
            $stmt = $this->conn->prepare("CALL ListarClientePorEmail(?)");
            $stmt->bindParam(1, $email);
            $stmt->execute();

            if ($stmt->rowCount() > 0) {
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                $this->id = $row["idCliente"];
                $this->nome = $row["nome"];
                $this->email = $row["email"];
                $this->telefone = $row["telefone"];
                $this->senha = $row["senha"];
                $this->idEndereco = $row["idEndereco"];

                $stmt->closeCursor();

                // Chama a função listarEndereco para obter o endereço completo
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
    }

    public function listarEndereco($idEndereco) {
        try {
            $stmt = $this->conn->prepare("CALL ListarEnderecoPorID(?)");
            $stmt->bindParam(1, $idEndereco);
            $stmt->execute();

            if ($stmt->rowCount() > 0) {
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
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

    public function getCep($idEndereco) {
        try {
            $stmt = $this->conn->prepare("CALL ListarCep(?)");
            $stmt->bindParam(1, $idEndereco);
            $stmt->execute();
            $row = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($row) {
                return $row['cep'];
            } else {
                return null;
            }

        } catch (PDOException $e) {
            error_log("Erro ao pegar o cep do cliente: " . $e->getMessage());
            return null;
        }
    }
}
