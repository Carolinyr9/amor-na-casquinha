<?php
// para rodar tem que estar no caminho: C:\xampp\htdocs\amor-na-casquinha
// para rodar os testes use o comando: .\vendor\bin\phpunit

require_once __DIR__ . '/../vendor/autoload.php';

use PHPUnit\Framework\TestCase;
use app\model\Login;
use app\config\DataBase;

class LoginTest extends TestCase {

    protected Login $login;
    protected $database;
    protected $connection;

    protected function setUp(): void {
        parent::setUp();
        $this->login = new Login();
        $this->database = new Database();
        $this->connection = $this->database->getConnection();
        $this->connection->beginTransaction();
        
        $senhaHash = password_hash("senha123", PASSWORD_DEFAULT);
        $stmt = $this->connection->prepare("INSERT INTO funcionarios (desativado, adm, perfil, nome, telefone, email, senha, idEndereco) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bindValue(1, 0);
        $stmt->bindValue(2, 1);
        $stmt->bindValue(3, "FUNC");
        $stmt->bindValue(4, "Usuário Teste");
        $stmt->bindValue(5, "11999999999");
        $stmt->bindValue(6, "teste@email.com");
        $stmt->bindParam(7, $senhaHash);
        $stmt->bindValue(8, 1);
        $stmt->execute();
    }

    public function testLoginFuncionario() {
        session_start();

        $this->login->login("teste@email.com", "senha123");

        $this->assertEquals("teste@email.com", $_SESSION["userEmail"]);
        $this->assertEquals("Usuário Teste", $_SESSION["userName"]);
        $this->assertEquals("11999999999", $_SESSION["userTel"]);
        $this->assertEquals("FUNC", $_SESSION["userPerfil"]);
    }

    public function testLoginComSenhaIncorreta() {
        $_POST["email"] = "teste@email.com";
        $_POST["senha"] = "senhaErrada";

        $this->login->login("teste@email.com", "senhaErrada");
    }

    protected function tearDown(): void {
        if ($this->connection->inTransaction()) {
            $this->connection->rollBack();
        }
        
        // Opcional: deletar os dados de teste após o rollback
        $this->connection->beginTransaction();
        $stmt = $this->connection->prepare("DELETE FROM funcionarios WHERE email = ?");
        $stmt->execute(["teste@email.com"]);
        $this->connection->commit();
        
        $this->connection = null;
        $this->database = null;
        $this->login = null;
        
        // Limpar a sessão
        session_unset();
        session_destroy();
    }

    public function testRegistrarCliente() {

    }
}



