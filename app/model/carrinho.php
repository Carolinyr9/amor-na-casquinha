<?php 
require_once '../config/database.php';

class Carrinho {
    private $id;
    private $data;
    private $total;
    private $produtos;
    private $cliente;
    private $conn;

    public function __construct(){
        $this->produtos = array();  
        $this->getConnection();
    }

    private function getConnection() {
        $database = new DataBase();
        $this->conn = $database->getConnection();
    }

    public function addProduto($variacaoId){
        // Consulta para pegar os detalhes da variação do produto
        $stmt = $this->conn->prepare("CALL SP_VariacaoLerProdutoIdVariacao(?)");
        $stmt->bindParam(1, $variacaoId);
        $stmt->execute();
    
        if ($stmt->rowCount() > 0) {
            $row = $stmt->fetch();
            $id = $row["idVariacao"];
            $nome = $row["nomeVariacao"];
            $preco = $row["precoVariacao"];
            $foto = $row["fotoVariacao"];
    
            // Se não existir um carrinho na sessão, inicializa-o
            if(!isset($_SESSION["cartArray"])) {
                $_SESSION["cartArray"] = array();
            }
    
            // Adiciona ou atualiza o produto no carrinho
            if(!isset($_SESSION["cartArray"][$id])) {
                $_SESSION["cartArray"][$id] = array (
                    "nome" => $nome,
                    "preco" => $preco,
                    "foto" => $foto,
                    "qntd" => 1
                );
            } else {
                $_SESSION["cartArray"][$id]["qntd"] += 1;
            }
        } else {
            echo "Produto não encontrado!";
        }
    }

    public function listarCarrinho() {
        if(isset($_SESSION["cartArray"])) {
            foreach ($_SESSION["cartArray"] as $variacaoId => $cartItem) {
                $id = $variacaoId;
                $nome = $cartItem["nome"];
                $preco = $cartItem["preco"];
                $foto = $cartItem["foto"];
                $qntd = $cartItem["qntd"];

                echo '<div class="c1">
                <div class="row">
                    <div class="col col-4 c2">
                        <img src="../images/'.$foto.'" alt="'.$nome.'" class="imagem">
                    </div>
                    <div class="col c3">
                        <h3 class="">'.$nome.'</h3>
                        <div class="preco d-flex flex-row justify-content-between px-2">
                            <p>Preço</p>
                            <span>R$ '.$preco.'</span>
                        </div>
                    </div>
                </div>
                <div class="botao text-center d-flex justify-content-evenly mt-3 flex-row row">
                    <div class="col col-3 d-flex align-items-start excl">
                        <a href="?action=remove&item='.$id.'" class="b-excluir">Excluir</a>
                    </div>
                    <div class="col d-flex align-items-start col-7">
                        <p>Quantid.</p>
                        <select id="select'.$id.'" name="select'.$id.'">
                            '.$this->gerarOpcoesQuantidade($qntd).'
                        </select>
                    </div>
                </div>
                </div>';
            }
            echo '<input class="conc" type="submit" value="Concluir"/>';
        } else {
            echo 'Carrinho está vazio!';
        }
    }

    private function gerarOpcoesQuantidade($qntd) {
        $html = '';
        for($i = 1; $i <= 10; $i++) {
            $selected = ($qntd == $i) ? 'selected' : '';
            $html .= '<option value="'.$i.'" '.$selected.'>'.$i.'</option>';
        }
        return $html;
    }

    public function removeProduto($id) {
        if(isset($_SESSION["cartArray"][$id])) {
            unset($_SESSION["cartArray"][$id]);
        }
    }
}
