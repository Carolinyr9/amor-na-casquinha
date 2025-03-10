<?php
session_start();
require_once '../config/blockURLAccess.php';
require_once '../../vendor/autoload.php';
use app\controller\ProdutoController;

$produtoController = new ProdutoController();
$produtos = $produtoController->listarProdutos();
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Produtos</title>
    <script src="https://code.jquery.com/jquery-3.7.1.js" integrity="sha256-eKhayi8LEQwp4NKxN+CfCh+3qOVUtJn3QNZ0TciWLP4=" crossorigin="anonymous"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="style/CabecalhoRodape.css">
    <link rel="stylesheet" href="style/editarProdutosS.css">
    <link rel="shortcut icon" href="images/iceCreamIcon.ico" type="image/x-icon">
</head>
<body>
    <?php include_once 'components/header.php'; 
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $nomeProduto = $_POST['nomeProAdd'] ?? '';
        $marca = $_POST['marcaProAdd'] ?? '';
        $descricao = $_POST['descricaoProAdd'] ?? '';
        $idFornecedor = $_POST['fornecedor'] ?? '';
        $imagemProduto = $_POST['imagemProAdd'] ?? '';

        $produtoController->adicionarProduto($nomeProduto, $marca, $descricao, $idFornecedor, $imagemProduto);
        header("Location: editarProdutos.php");
    } ?>
    
    <main>
        <h1>Produtos</h1>
        <div class="d-flex flex-column align-items-center justify-content-center">
            <button class="add border-0 rounded-4 my-3 fw-bold fs-5 px-3">Adicionar Produto</button>
            <div>
                <form action="" method="POST" id="addFormulario">
                    <label for="nome1">Nome:</label>
                    <input type="text" id="nomeProduto" name="nomeProAdd" placeholder="Nome do produto">
                    <label for="marca">Marca:</label>
                    <input type="text" id="marcaProAdd" name="marcaProAdd" placeholder="Marca do produto">
                    <label for="descricaoProduto">Descrição:</label>
                    <input type="text" id="descricaoProduto" name="descricaoProAdd" placeholder="Descrição do produto">
                    <label for="fornecedor">ID do fornecedor:</label>
                    <input type="text" id="fornecedor" name="fornecedor" placeholder="1234">
                    <label for="nomeImagem">Nome do arquivo de imagem:</label>
                    <input type="text" id="imagemProduto" name="imagemProAdd" placeholder="imagem.png">
                    <button type="submit">Salvar</button>
                </form>
            </div>
            <div class="conteiner1 d-flex flex-wrap gap-3 justify-content-center align-items-center">
                <?php
                if ($produtos) {
                    foreach ($produtos as $row) {
                        $redirectToVariacao = 'editarSabores.php?produto=' . $row['idProduto'];
                        $redirectToEditar = 'editProd.php?produto=' . $row['idProduto'];
                        $redirectToExcluir = 'excluirProd.php?produto=' . $row['idProduto'];
                        echo '
                        <div class="c1 p-2 rounded-4 my-3 d-flex justify-content-center">
                            <div class="card categ d-flex justify-content-center align-items-center flex-column">
                                <picture>
                                    <img src="../images/' . htmlspecialchars($row["foto"]) . '" alt="' . htmlspecialchars($row["nome"]) . '">
                                </picture>
                                <div class="botao text-center d-flex justify-content-evenly mt-3">
                                    <a class="botao__link--verSabores text-decoration-none border-0 rounded-3 m-1 text-black" href="' . htmlspecialchars($redirectToVariacao) . '">Ver Sabores</a>     
                                    <a class="botao__link--editar text-decoration-none border-0 rounded-3 m-1 text-black" href="' . htmlspecialchars($redirectToEditar) . '">Editar</a>      
                                    <a class="botao__link--excluir text-decoration-none border-0 rounded-3 m-1 text-black" href="' . htmlspecialchars($redirectToExcluir) . '">Excluir</a>      
                                </div>
                            </div>
                        </div>';
                    }
                } else {
                    echo '<p>Nenhum produto encontrado.</p>';
                }
                ?>
            </div>
        </div>
    </main>
    <?php include_once 'components/footer.php'; ?>
    <script src="script/adicionar.js"></script>
    <script src="script/editar.js"></script>
</body>
</html>
