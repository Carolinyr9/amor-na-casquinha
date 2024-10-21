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
        try {
            $database = new DataBase();
            $this->conn = $database->getConnection();  
        } catch (PDOException $e) {
            echo "Erro ao conectar com o banco de dados: " . $e->getMessage();
        }
    }

    public function selecionarProdutos() {
        try {
            $recordsLimit = 100;
            $recordsOffset = 0;

            $stmt = $this->conn->prepare("CALL ListarProdutoAtivo(?, ?)");
            $stmt->bindParam(1, $recordsLimit, PDO::PARAM_INT);
            $stmt->bindParam(2, $recordsOffset, PDO::PARAM_INT); 
            $stmt->execute();

            do {
                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    $this->mostrarSelecionarProdutos($row);
                }
            } while ($stmt->nextRowset());
        } catch (PDOException $e) {
            echo "Erro ao selecionar produtos: " . $e->getMessage();
        }
    }

    private function mostrarSelecionarProdutos($row){
        $redirectTo = 'sabores.php?produto=' . $row['idProduto'];
        echo '
            <div class="card categ d-flex align-items-center">
                <picture>
                    <img src="../images/' . $row["foto"] . '" alt="' . $row["nome"] . '" class="imagem">
                </picture>
                <div class="d-flex align-items-center flex-column c2">
                    <h4>' . $row["nome"] . '</h4>
                    <button><a href="' . $redirectTo . '">ver</a></button>
                </div>
            </div>';
    }

    public function selecionarProdutosPorID($id) {
        try {
            $recordsLimit = 100;
            $recordsOffset = 0;

            $stmt = $this->conn->prepare("CALL ListarProdutoPorId(?, ?, ?)");
            $stmt->bindParam(1, $id, PDO::PARAM_INT);
            $stmt->bindParam(2, $recordsLimit, PDO::PARAM_INT);
            $stmt->bindParam(3, $recordsOffset, PDO::PARAM_INT); 
            $stmt->execute();

            do {
                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    $this->mostrarSelecionarProdutosPorID($row);
                }
            } while ($stmt->nextRowset());
        } catch (PDOException $e) {
            echo "Erro ao selecionar produtos por ID: " . $e->getMessage();
        }
    }

    private function mostrarSelecionarProdutosPorID($row){
        echo '
            <form action="' . htmlspecialchars($_SERVER["PHP_SELF"] . '?produto=' . $row['idProduto']) . '" method="GET" id="formulario" class="formulario">
                <input type="hidden" name="produto" value="' . $row['idProduto'] . '">
                <input type="hidden" name="nomeProdAtual" value="' . $row['nome'] . '">
                <label for="nome2">Nome:</label>
                <input type="text" id="nome2" name="nomeProdEdt" placeholder="Nome" value="' . $row['nome'] . '">
                <label for="marca2">Marca:</label>
                <input type="text" id="marca2" name="marcaProdEdt" placeholder="Marca" value="' . $row['marca'] . '">
                <label for="descricao2">Descrição:</label>
                <input type="text" id="descricao2" name="descricaoProdEdt" placeholder="Descrição" value="' . $row['descricao'] . '">
                <label for="foto2">Nome do arquivo de imagem:</label>
                <input type="text" id="imagem2" name="imagemProdEdt" placeholder="imagem.png" value="' . $row['foto'] . '">
                <button type="submit">Salvar</button>
            </form>';
    }

    public function selecionarProdutosFunc() {
        try {
            $recordsLimit = 100;
            $recordsOffset = 0;

            $stmt = $this->conn->prepare("CALL ListarProdutoAtivo(?, ?)");
            $stmt->bindParam(1, $recordsLimit, PDO::PARAM_INT);
            $stmt->bindParam(2, $recordsOffset, PDO::PARAM_INT); 
            $stmt->execute();

            do {
                while ($row = $stmt->fetch()) {
                    $this->mostrarSelecionarProdutosFunc($row);
                }
            } while ($stmt->nextRowset());
        } catch (PDOException $e) {
            echo "Erro ao selecionar produtos para a função: " . $e->getMessage();
        }
    }

    private function mostrarSelecionarProdutosFunc($row){
        $redirectToVariacao = 'editarSabores.php?produto=' . $row['idProduto'];
        $redirectToEditar = 'editProd.php?produto=' . $row['idProduto'];
        $redirectToExcluir = 'excluirProd.php?produto=' . $row['idProduto'];
        echo '
            <div class="c1">
                <div class="card categ d-flex align-items-center">
                    <picture>
                        <img src="../images/' . $row["foto"] . '" alt="' . $row["nome"] . '">
                    </picture>
                    <div class="botao text-center d-flex justify-content-evenly mt-3">
                        <button id="vari"><a href="' . $redirectToVariacao . '">Ver Sabores</a></button>        
                        <button id="edit"><a href="' . $redirectToEditar . '">Editar</a></button>        
                        <button id="excl"><a href="' . $redirectToExcluir . '">Excluir</a></button>        
                    </div>
                </div>
            </div>';
    }

    public function adicionarProduto($nomeProduto, $marca, $descricao, $idFornecedor, $imagemProduto) {
        try {
            $desativado = 0;
            $stmt = $this->conn->prepare("CALL InserirProduto(?, ?, ?, ?, ?, ?)");
    
            $stmt->bindParam(1, $nomeProduto);    
            $stmt->bindParam(2, $marca);           
            $stmt->bindParam(3, $descricao);     
            $stmt->bindParam(4, $idFornecedor);   
            $stmt->bindParam(5, $imagemProduto);  
            $stmt->bindParam(6, $desativado);   

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

    public function editarProduto($idProduto, $nomeProduto, $marca, $descricao, $imagemProduto) {
        try {
            $stmt = $this->conn->prepare("CALL EditarProdutoPorId(?, ?, ?, ?, ?)");
    
            $stmt->bindParam(1, $idProduto);
            $stmt->bindParam(2, $nomeProduto);
            $stmt->bindParam(3, $marca);
            $stmt->bindParam(4, $descricao);
            $stmt->bindParam(5, $imagemProduto);
    
            $stmt->execute();
    
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            if ($result['Status'] == '204') {
                echo "Produto atualizado com sucesso!";
            } else {
                echo "Erro: " . $result['Error'];
            }
    
        } catch (PDOException $e) {
            echo "Erro ao editar o produto: " . $e->getMessage();
        }
    }
    
    public function removerProduto($idProduto) {
        try {
            $stmt = $this->conn->prepare("CALL DesativarProdutoPorID(?)");
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
