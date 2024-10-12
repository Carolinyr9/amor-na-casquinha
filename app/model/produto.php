<?php 
require_once '../config/database.php';

class Produto {
    private $idProduto;
    private $idFornecedor;
    private $nome;
    private $marca;
    private $descricao;
    private $desativado;
    private $foto;
    private $preco;
    private $conn;  

    public function __construct() {
        $this->getConnectionDataBase();
    }

    private function getConnectionDataBase() {
        $database = new DataBase();
        $this->conn = $database->getConnection();  
    }

    public function selecionarProdutos() {
        $recordsLimit = 100;
        $recordsOffset = 0;

        $stmt = $this->conn->prepare("CALL SP_ProdutoLerAtivo(?, ?)");
        $stmt->bindParam(1, $recordsLimit, PDO::PARAM_INT);
        $stmt->bindParam(2, $recordsOffset, PDO::PARAM_INT); 
        $stmt->execute();

        do {
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC))
            {
                $redirectTo = 'sabores.php?produto=' . $row['idProduto'];
                echo '<div class="card categ d-flex align-items-center">
                    <picture>
                        <img src="../images/' . $row["foto"] . '" alt="' . $row["nome"] . '" class="imagem">
                    </picture>
                    <div class="d-flex align-items-center flex-column c2">
                        <h4>' . $row["nome"] . '</h4>
                        <button><a href="' . $redirectTo . '">ver</a></button>
                    </div>
                </div>';
            }
        } while ($stmt->nextRowset());
    }
}
?>
