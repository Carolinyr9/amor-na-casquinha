<?php
session_start();
require_once '../config/blockURLAccess.php';
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Sabores</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link rel="stylesheet" href="style/CabecalhoRodape.css">
    <link rel="stylesheet" href="style/editarSaboresS.css">
    <link rel="shortcut icon" href="images/iceCreamIcon.ico" type="image/x-icon">
</head>
<body>

    <?php
    include_once 'components/header.php';
    require_once '../controller/produtoVariacaoController.php';
    $produtoVariacaoController = new ProdutoVariacaoController();
    $variacoes = $produtoVariacaoController->selecionarVariacaoProdutos($_GET["produto"]);

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $idProduto = $_GET["produto"];
        $nomeProduto = isset($_POST['nomeSabAdd']) ? $_POST['nomeSabAdd'] : '';
        $preco = isset($_POST['precoSabAdd']) ? $_POST['precoSabAdd'] : '';
        $foto = isset($_POST['nomeImagemSabAdd']) ? $_POST['nomeImagemSabAdd'] : '';

        $produtoVariacaoController->adicionarProduto($idProduto, $nomeProduto, $preco, $foto);
        header("Location: editarSabores.php?produto=$idProduto");
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
            <div>
                <form action="" method="POST" id="addFormulario">
                    <label for="nome">Nome:</label>
                    <input type="text" id="nome" name="nomeSabAdd" placeholder="Nome do produto">
                    <label for="preco">Preço:</label>
                    <input type="text" id="preco" name="precoSabAdd" placeholder="Preço do produto">
                    <label for="nomeImagem">Nome do arquivo de imagem:</label>
                    <input type="text" id="nomeImagem" name="nomeImagemSabAdd" placeholder="imagem.png">
                    
                    <button name="bntCreatSab" type="submit">Salvar</button>
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
</body>
</html>
