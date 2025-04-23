<?php
session_start();
require_once '../config/blockURLAccess.php';
require_once '../../vendor/autoload.php';
require_once '../utils/adicionarCategorias.php';

use app\controller2\CategoriaProdutoController;
$categoriasController = new CategoriaProdutoController();
$categorias = $categoriasController->listarCategorias();

?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Categorias</title>
    <script src="https://code.jquery.com/jquery-3.7.1.js" integrity="sha256-eKhayi8LEQwp4NKxN+CfCh+3qOVUtJn3QNZ0TciWLP4=" crossorigin="anonymous"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="style/CabecalhoRodape.css">
    <link rel="stylesheet" href="style/editarProdutosS.css">
    <link rel="shortcut icon" href="images/iceCreamIcon.ico" type="image/x-icon">
</head>
<body>
    <main>
        <?php include_once 'components/header.php'; ?>
        <h1>Produtos</h1>
        <div class="produtos d-flex flex-column align-items-center justify-content-center">
            <button class="produtos__btn--add border-0 rounded-4 my-3 fw-bold fs-5 px-3">Adicionar Categoria</button>
            
            <div class="formulario container my-5 border w-75 rounded-4 py-3">
                <form enctype="multipart/form-data" action="" method="POST" class="mx-auto d-flex flex-row flex-wrap gap-5 m-auto w-75">
                    <div class="mb-3">
                        <label for="produto" class="form-label">Categoria</label>
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
                <?php foreach ($categorias as $categoria): ?>
                    <?php include 'components/categoriasCards.php'; ?>
                <?php endforeach; ?>
            </div>
        </div>
    </main>

    <?php include_once 'components/footer.php'; ?>
    
    <script src="script/exibirFormulario.js"></script>
    <script src="script/exibirArquivoImagem.js"></script>
</body>
</html>
