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
            $stmt = $this->conn->prepare("CALL Login(?)");
            $stmt->bindParam(1, $email);
            $stmt->execute();

            if ($stmt->rowCount() > 0) {
                return $stmt->fetch(PDO::FETCH_ASSOC);
            } else {
                return false;
            }
        } catch (PDOException $e) {
            throw new Exception("Erro ao verificar usuário: " . $e->getMessage());
        }
    }

}