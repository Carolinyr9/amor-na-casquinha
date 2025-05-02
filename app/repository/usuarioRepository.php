<?php 
namespace app\repository;
use app\model\Usuario;

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
            $stmt = $this->conn->prepare("SELECT * FROM funcionarios WHERE email LIKE CONCAT('%', ?, '%') LIMIT 1");
            $stmt->execute([$email]);
    
            if ($stmt->rowCount() > 0) {
                return $stmt->fetch(PDO::FETCH_ASSOC);
            }
    
            $stmt = $this->conn->prepare("
                SELECT * FROM clientes 
                INNER JOIN enderecos ON clientes.idEndereco = enderecos.idEndereco 
                WHERE clientes.email LIKE CONCAT('%', ?, '%') 
                LIMIT 1
            ");
            $stmt->execute([$email]);
    
            if ($stmt->rowCount() > 0) {
                return $stmt->fetch(PDO::FETCH_ASSOC);
            }
    
            $stmt = $this->conn->prepare("SELECT * FROM entregador WHERE email LIKE CONCAT('%', ?, '%') LIMIT 1");
            $stmt->execute([$email]);
    
            if ($stmt->rowCount() > 0) {
                return $stmt->fetch(PDO::FETCH_ASSOC);
            }
    
            return [
                'Status' => '403',
                'Error' => 'ERROR_EMAIL_NAO_ENCONTRADO',
                'Message' => '',
                'Body' => ''
            ];
            
        } catch (PDOException $e) {
            throw new Exception("Erro ao verificar usuário: " . $e->getMessage());
        }
    }
    

}