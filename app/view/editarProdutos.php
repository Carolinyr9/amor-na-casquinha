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
        $imagemProduto = $_POST['imagem'] ?? '';

        $produtoController->adicionarProduto($nomeProduto, $marca, $descricao, $idFornecedor, $imagemProduto);
        header("Location: editarProdutos.php");
    } ?>
    
    <main>
        <h1>Produtos</h1>
        <div class="produtos d-flex flex-column align-items-center justify-content-center">
            <button class="produtos__btn--add border-0 rounded-4 my-3 fw-bold fs-5 px-3">Adicionar Produto</button>
            <div class="formulario container my-5 border w-75 rounded-4 py-3">
                <form enctype="multipart/form-data" action="" method="POST" class="mx-auto d-flex flex-row flex-wrap gap-5 m-auto w-75">
                    <div class="mb-3">
                        <label for="produto" class="form-label">Produto</label>
                        <input type="text" id="produto" name="nomeProAdd" class="form-control"/>
                    </div>
                    
                    <div class="mb-3">
                        <label for="marca" class="form-label">Marca</label>
                        <input type="text" id="marca" name="marcaProAdd" class="form-control"/>
                    </div>
                    
                    <div class="mb-3">
                        <label for="descricaoProduto" class="form-label">Descrição</label>
                        <input type="text" id="descricaoProduto" name="descricaoProAdd" class="form-control"/>
                    </div>
                    
                    <div class="mb-3">
                        <label for="fornecedor" class="form-label">ID do fornecedor</label>
                        <input type="text" id="fornecedor" name="fornecedor" class="form-control"/>
                    </div>
                    
                    <div class="mb-3">
                        <label for="imagem" class="form-label">Imagem</label>
                        <input type="file" id="imagem" name="imagem" class="form-control"/>
                    </div>

                    <img id="preview" src="" alt="Pré-visualização da imagem" class="mx-auto mt-3" style="max-width: 150px; display: none;">

                    <button type="submit" class="formumario__btn--submit m-auto px-4 border-0 rounded-3">Salvar</button>
                </form>
            </div>
            <div class="conteiner d-flex flex-row flex-wrap gap-3 justify-content-center align-items-center">
                <?php
                if ($produtos) {
                    foreach ($produtos as $row) {
                        $redirectToVariacao = 'editarSabores.php?produto=' . $row['idProduto'];
                        $redirectToEditar = 'editProd.php?produto=' . $row['idProduto'];
                        $redirectToExcluir = 'excluirProd.php?produto=' . $row['idProduto'];
                        echo '
                        <div class="produto p-2 rounded-4 my-3 d-flex justify-content-center w-25">
                            <div class="produto__card container d-flex flex-row justify-content-between align-items-center">
                                <picture class="w-50">
                                    <img src="../images/' . htmlspecialchars($row["foto"]) . '" alt="' . htmlspecialchars($row["nome"]) . '">
                                </picture>
                                <div class="card__botao text-center d-flex flex-column justify-content-evenly mt-3">
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
    <script src="script/exibirFormulario.js"></script>
    <script src="script/exibirArquivoImagem.js"></script>
</body>
</html>
