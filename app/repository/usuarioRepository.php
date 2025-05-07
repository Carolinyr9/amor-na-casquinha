<?php 
namespace app\repository;

use PDO;
use PDOException;
use app\config\DataBase;
use app\controller2\ClienteController;

class UsuarioRepository {
    private $conn;  

    public function __construct() {
        $this->getConnectionDataBase();
    }

    private function getConnectionDataBase() {
        try {
            $database = new DataBase();
            $this->conn = $database->getConnection();  
        } catch (PDOException $e) {
            die("Erro ao conectar com o banco de dados: " . $e->getMessage());
        }
    }

    public function verificarUsuarioPorEmail($email) {
        try {
            $stmt = $this->conn->prepare("SELECT * FROM funcionario WHERE email = ? LIMIT 1");
            $stmt->execute([$email]);
            if ($stmt->rowCount() > 0) return $stmt->fetch(PDO::FETCH_ASSOC);

            $stmt = $this->conn->prepare("
                SELECT cliente.*, enderecos.* FROM cliente
                INNER JOIN enderecos ON cliente.idEndereco = enderecos.idEndereco 
                WHERE cliente.email = ? 
                LIMIT 1
            ");
            $stmt->execute([$email]);
            if ($stmt->rowCount() > 0) return $stmt->fetch(PDO::FETCH_ASSOC);

            $stmt = $this->conn->prepare("SELECT * FROM entregador WHERE email = ? LIMIT 1");
            $stmt->execute([$email]);
            if ($stmt->rowCount() > 0) return $stmt->fetch(PDO::FETCH_ASSOC);

            return [
                'Status' => '403',
                'Error' => 'ERROR_EMAIL_NAO_ENCONTRADO'
            ];
            
        } catch (PDOException $e) {
            throw new \Exception("Erro ao verificar usuário: " . $e->getMessage());
        }
    }

}
