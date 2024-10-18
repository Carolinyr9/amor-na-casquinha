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
                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    if ($row['desativado'] == 0) {
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

    public function selecionarVariacaoProdutosFunc($idProduto) {
        if (isset($idProduto)) {
            $stmt = $this->conn->prepare("CALL SP_VariacaoLerProdutoId(?)");
            $stmt->bindParam(1, $idProduto, PDO::PARAM_INT);  
            $stmt->execute();

            do {
                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    if ($row['desativado'] == 0) {
                        $redirectToExcluir = 'excluirSabor.php?idVariacao=' . $row['idVariacao'];
                        $redirectToEditar = 'editaSabor.php?idProduto=' . $row['idProduto'] . '&idVariacao=' . $row['idVariacao'];
                        
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
                                <button id="excl"><a href="' . $redirectToExcluir . '">Excluir</a></button>                        
                                <button id="edit"><a href="' . $redirectToEditar . '">Editar</a></button>
                            </div>
                        </div>';
                    }
                }
            } while ($stmt->nextRowset());
        } else {
            echo 'Não há produtos!';
        }
    }

    public function adicionarProduto($idProduto, $nomeProduto, $preco, $foto) {
        try {
            $desativado = 0;
            $stmt = $this->conn->prepare("CALL InserirVariacao(?, ?, ?, ?)");
            $stmt->bindParam(1, $nomeProduto);    
            $stmt->bindParam(2, $preco);           
            $stmt->bindParam(3, $foto);     
            $stmt->bindParam(4, $idProduto);    
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if ($result !== false && isset($result['Status'])) {
                if ($result['Status'] == '201') {
                    echo "Produto criado com sucesso!";
                } else {
                    echo "Erro: " . $result['Error'];
                }
            } else {
                echo "Nenhum resultado retornado da procedure.";
            }
        } catch (PDOException $e) {
            echo "Erro ao inserir o produto: " . $e->getMessage();
        }
    }

    public function selecionarProdutosPorID($idProduto) {
        $stmt = $this->conn->prepare("CALL ListarVariacaoPorID(?)");
        $stmt->bindParam(1, $idProduto, PDO::PARAM_INT);
        $stmt->execute();

        do {
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                echo '
                <form action="' . htmlspecialchars($_SERVER["PHP_SELF"]) . '" method="GET" id="formulario" class="formulario">
                    <input type="hidden" name="idProduto" value="' . htmlspecialchars($row['idProduto']) . '">
                    <input type="hidden" name="idVariacao" value="' . htmlspecialchars($row['idVariacao']) . '">
                    <label for="nome">Nome:</label>
                    <input type="text" id="nome" name="nomeSabEdt" value="' . htmlspecialchars($row['nomeVariacao']) . '">
                    <label for="preco">Preço:</label>
                    <input type="text" id="preco" name="precoSabEdt" value="' . htmlspecialchars($row['precoVariacao']) . '">
                    <label for="imagem">Imagem:</label>
                    <input type="text" id="imagem" name="imagemSabEdt" value="' . htmlspecialchars($row['fotoVariacao']) . '">
                    <button type="submit" name="btnEditar">Salvar</button>
                </form>';
            }
        } while ($stmt->nextRowset());
    }

    public function editarProduto($idVariacao, $idProduto, $nomeProduto, $preco, $imagemProduto) {
        try {
            $stmt = $this->conn->prepare("CALL EditarVariacaoPorID(?, ?, ?, ?, ?)");
            $stmt->bindParam(1, $idVariacao, PDO::PARAM_INT);
            $stmt->bindParam(2, $nomeProduto, PDO::PARAM_STR);    
            $stmt->bindParam(3, $preco, PDO::PARAM_STR);           
            $stmt->bindParam(4, $imagemProduto, PDO::PARAM_STR);    
            $stmt->bindParam(5, $idProduto, PDO::PARAM_INT);
            $stmt->execute();
            header("Location: " . $_SERVER['PHP_SELF'] . 
                   "?idProduto=" . $idProduto . 
                   "&idVariacao=" . $idVariacao . 
                   "&nomeProduto=" . urlencode($nomeProduto) . 
                   "&preco=" . urlencode($preco) . 
                   "&imagemProduto=" . urlencode($imagemProduto));
            exit;
        } catch (PDOException $e) {
            echo "Erro ao editar o produto: " . $e->getMessage();
        }
    }

    function removerProduto($idProduto) {
        try {
            $stmt = $this->conn->prepare("CALL DesativarVariacaoPorID(?)");
            $stmt->bindParam(1, $idProduto);
            $stmt->execute();
    
            echo '<script>
                alert("Produto excluído com sucesso");
                window.location.href = "/amor-na-casquinha/app/view/editarProdutos.php";
              </script>';
        } catch (PDOException $e) {
            echo "Erro no banco de dados: " . $e->getMessage();
        }
    }
}
?>
