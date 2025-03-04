<?php
session_start();
require_once '../../vendor/autoload.php';
require_once '../config/blockURLAccess.php';
use app\controller\ProdutoVariacaoController;
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Sabores</title>
    <script
  src="https://code.jquery.com/jquery-3.7.1.js"
  integrity="sha256-eKhayi8LEQwp4NKxN+CfCh+3qOVUtJn3QNZ0TciWLP4="
  crossorigin="anonymous"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link rel="stylesheet" href="style/CabecalhoRodape.css">
    <link rel="stylesheet" href="style/editarSaboresS.css">
    <link rel="shortcut icon" href="images/iceCreamIcon.ico" type="image/x-icon">
</head>
<body>

    <?php
    include_once 'components/header.php';

    $produtoVariacaoController = new ProdutoVariacaoController();
    $variacoes = $produtoVariacaoController->selecionarVariacaoProdutos($_GET["produto"]);

    if ($_SERVER['REQUEST_METHOD'] == 'POST' || isset($_POST['inserirSaborSubmit'])) {
        echo "foi";
        $idProduto = $_POST["idProduto"];
        $nomeProduto = $_POST['nomeSabAdd'];
        $preco = $_POST['precoSabAdd'];
        $lote = $_POST['lote'];
        $valor = $_POST['valor'];
        $quantidade = $_POST['quantidade'];
        $dataEntrada = $_POST['dataEntrada'];
        $dataFabricacao = $_POST['dataFabricacao'];
        $dataVencimento = $_POST['dataVencimento'];
        $quantidadeMinima = $_POST['quantidadeMinima'];
        $imagem = $_FILES['foto'];
        echo "foi2";
        $produtoVariacaoController->adicionarVariacaoProduto($idProduto, $nomeProduto, $preco, $lote, $valor, $quantidade, $dataEntrada, $dataFabricacao, $dataVencimento, $quantidadeMinima, $imagem);

        
    }
    ?>

    <main>
        <h1>Sabores</h1>

        <div class="d-flex flex-column align-items-center justify-content-center">
            <?php  
            $var = isset($_SESSION['var']) ? $_SESSION['var'] : ""; 
            echo $var;
            $_SESSION['var'] = NULL;
            ?>
            <button class="add fs-5 fw-bold rounded-4 border-0 my-3">Adicionar Sabor</button>
            <div class="m-4">
                <form action="" method="POST" enctype="multipart/form-data" id="addFormulario" class="flex-row justify-content-between flex-wrap gap-4 w-50 m-auto">
                    <div class="form-group">
                        <label for="nome">Nome:</label>
                        <input type="text" class="form-control"  id="nome" name="nomeSabAdd" placeholder="Picolé de..." required>
                    </div>
                    <div class="form-group">
                        <label for="preco">Preço:</label>
                        <input type="number" class="form-control"  id="preco" name="precoSabAdd" placeholder="00,00" required>
                    </div>
                    <div class="form-group">
                        <label for="lote">Lote</label>
                        <input type="text" class="form-control" id="lote" name="lote" placeholder="000" required>
                    </div>
                    <div class="form-group">
                        <label for="valor">Valor da compra</label>
                        <input type="number" class="form-control" id="valor" name="valor" placeholder="00,00" required>
                    </div>
                    <div class="form-group">
                        <label for="quantidade">Quantidade</label>
                        <input type="number" class="form-control" id="quantidade" name="quantidade" placeholder="000" required>
                    </div>
                    <div class="form-group">
       s                 <label for="dataFabricacao">Entrada</label>
                        <input type="date" class="form-control" id="dataEntrada" name="dataEntrada" required>
                    </div>
                    <div class="form-group">
                        <label for="dataFabricacao">Fabricação</label>
                        <input type="date" class="form-control" id="dataFabricacao" name="dataFabricacao" required>
                    </div>
                    <div class="form-group">
                        <label for="dataVencimento">Vencimento</label>
                        <input type="date" class="form-control" id="dataVencimento" name="dataVencimento" required>
                    </div>
                    <div class="form-group">
                        <label for="quantidadeMinima">Quantidade Mínima</label>
                        <input type="number" class="form-control" id="quantidadeMinima" name="quantidadeMinima" placeholder="000" required>
                    </div>
                    <div class="form-group">
                        <label for="imagem">Imagem</label>
                        <input class="form-control" id="imagemInput" type="file" name="foto">
                        <img id="preview" src="" alt="Pré-visualização da imagem" class="mx-auto mt-3" style="max-width: 150px; display: none;">
                    </div>
                    <input type="hidden" name="idProduto" value="<?= $_GET["produto"]; ?>">
                    <input name="inserirSaborSubmit" type="submit" value="Inserir" class="form__submit px-3 border-0 rounded-3 m-1 text-black" />
                </form>
            </div>

            <div class="d-flex flex-row flex-wrap m-auto w-75 justify-content-center">
                <?php
                if (is_array($variacoes) && count($variacoes) > 0) {
                    foreach ($variacoes as $produto) {
                        $redirectToExcluir = 'excluirSabor.php?idVariacao=' . htmlspecialchars($produto['idVariacao']);
                        $redirectToEditar = 'editaSabor.php?idProduto=' . htmlspecialchars($produto['idProduto']) . '&idVariacao=' . htmlspecialchars($produto['idVariacao']);
                        
                        echo '
                        <div class="c1 p-3 my-3 d-flex flex-column rounded-4">
                            <div class="head-box d-flex flex-row justify-content-between">
                                <div class="img-box" style="width: 40%; height: auto;">
                                <img class="imagem m-2 rounded-4 w-100 h-auto" src="../images/' . htmlspecialchars($produto["fotoVariacao"]) . '" alt="' . htmlspecialchars($produto["nomeVariacao"]) . '">
                                </div>
                                <div class="d-flex flex-column justify-content-center">
                                    <h3 class="titulo mt-2 fs-6 fw-bold pl-2 px-2 text-wrap">' . htmlspecialchars($produto["nomeVariacao"]) . '</h3>
                                    <div class="preco d-flex justify-content-end px-2 mt-3 pl-2">
                                        <span>R$ ' . htmlspecialchars($produto["precoVariacao"]) . '</span>
                                    </div>
                                </div>
                            </div>
                            <div class="botao text-center d-flex justify-content-evenly mt-3">
                                <button id="excl" class="rounded-3 border-0"><a class="text-decoration-none" href="' . $redirectToExcluir . '">Excluir</a></button>                        
                                <button id="edit" class="rounded-3 border-0"><a class="text-decoration-none" href="' . $redirectToEditar . '">Editar</a></button>
                            </div>
                        </div>';
                    }
                } else {
                    echo '<p>Nenhuma variação de produto encontrada.</p>';
                }
                ?>
            </div>
            <button class="voltar mt-3 fs-5 fw-bold rounded-4 border-0"><a href="editarProdutos.php">Voltar</a></button>
        </div>
    </main>

    <?php
    include_once 'components/footer.php';
    ?>
    
    <script src="script/header.js"></script>
    <script src="script/adicionar.js"></script>    
    <script src="script/exibirArquivoImagem.js"></script>
</body>
</html>
