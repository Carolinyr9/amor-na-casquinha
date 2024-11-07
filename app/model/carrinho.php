<?php 
require_once '../config/database.php';

class Carrinho {
    private $id;
    private $data;
    private $total;
    private $produtos;
    private $cliente;
    private $conn;

    public function __construct() {
        $this->produtos = array();  
        $this->getConnection();
    }

    private function getConnection() {
        $database = new DataBase();
        $this->conn = $database->getConnection();
    }

    public function addProduto($variacaoId) {
        $stmt = $this->conn->prepare("CALL ListarVariacaoAtivaPorId(?)");
        $stmt->bindParam(1, $variacaoId);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            $row = $stmt->fetch();
            $id = $row["idVariacao"];
            $nome = $row["nomeVariacao"];
            $preco = $row["precoVariacao"];
            $foto = $row["fotoVariacao"];

            if (!isset($_SESSION["cartArray"])) {
                $_SESSION["cartArray"] = array();
            }

            if (!isset($_SESSION["cartArray"][$id])) {
                $_SESSION["cartArray"][$id] = array(
                    "nome" => $nome,
                    "preco" => $preco,
                    "foto" => $foto,
                    "qntd" => 1
                );
            } else {
                $_SESSION["cartArray"][$id]["qntd"] += 1;
            }
        } else {
            throw new Exception("Produto não encontrado!");
        }
    }

    public function listarCarrinho() {
        if (!isset($_SESSION["cartArray"])) {
            return [];
        }
        
        $produtosCarrinho = [];
        foreach ($_SESSION["cartArray"] as $variacaoId => $cartItem) {
            $cartItem["id"] = $variacaoId;
            $cartItem["quantidades"] = $this->gerarOpcoesQuantidade($cartItem["qntd"]);
            $produtosCarrinho[] = $cartItem;
        }
        
        return $produtosCarrinho;
    }

    public function atualizarCarrinho() {
        if (isset($_POST["cart"]) && isset($_SESSION["cartArray"])) {
            foreach ($_SESSION["cartArray"] as $variacaoId => $cartItem) {
                if (isset($_POST["select".$variacaoId])) {
                    $qntd = $_POST["select".$variacaoId];
                    $_SESSION["cartArray"][$variacaoId]["qntd"] = $qntd;
                }
            }
        }
    }

    public function getTotal() {
        if (!isset($_SESSION["cartArray"])) {
            return 0;
        }

        $total = 0;
        foreach ($_SESSION["cartArray"] as $cartItem) {
            $total += $cartItem["preco"] * $cartItem["qntd"];
        }

        return $total;
    }

    private function gerarOpcoesQuantidade($qntd) {
        $html = '';
        for ($i = 1; $i <= 10; $i++) {
            $selected = ($qntd == $i) ? 'selected' : '';
            $html .= '<option value="'.$i.'" '.$selected.'>'.$i.'</option>';
        }
        return $html;
    }

    public function removeProduto($id) {
        if (isset($_SESSION["cartArray"][$id])) {
            unset($_SESSION["cartArray"][$id]);
        }
    }

    public function limparCarrinho() {
        if (isset($_SESSION["cartArray"])) {
            unset($_SESSION["cartArray"]); 
        }
    }
}
?>
