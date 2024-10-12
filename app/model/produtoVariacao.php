<?php
require_once '../config/database.php';

class ProdutoVariacao {
    private $idVariacao;
    private $desativado;
    private $nomeVariacao;
    private $precoVariacao;
    private $fotoVariacao;
    private $idProduto;
    private $conn;  

    public function __construct() {
        $this->getConnectionDataBase();
    }

    private function getConnectionDataBase() {
        $database = new DataBase();
        $this->conn = $database->getConnection();  
    }

    public function selecionarVariacaoProdutos($idProduto) {
        if (isset($idProduto)) {
            
            $stmt = $this->conn->prepare("CALL SP_VariacaoLerProdutoId(?)");
            $stmt->bindParam(1, $idProduto, PDO::PARAM_INT);  
            $stmt->execute();

            do {
                while ($row = $stmt->fetch(PDO::FETCH_ASSOC))  
                {
                    if ($row['desativado'] == 0) 
                    {
                        $redirectTo = 'carrinho.php?add=' . $row['idVariacao'];
                        echo '
                        <div class="c1">
                        <div class="c2">
                            <div><img src="../images/' . $row["fotoVariacao"] . '" alt="' . $row["nomeVariacao"] . '" class="imagem"></div>
                            <div class="c3">
                                <h3 class="titulo px-2">' . $row["nomeVariacao"] . '</h3>
                                <div class="preco d-flex flex-row justify-content-between px-2">
                                    <p>Preço</p>
                                    <span>R$ ' . $row["precoVariacao"] . '</span>
                                </div>
                            </div>
                        </div>
                        <div class="botao text-center d-flex justify-content-evenly mt-3">
                        <button class="add"><a href="' . $redirectTo . '">Adicionar ao Carrinho</a></button>
                            </div>
                        </div>';
                    }
                }
            } while ($stmt->nextRowset());
        } else {
            echo 'Não há produtos!';
        }
    }
}
?>
