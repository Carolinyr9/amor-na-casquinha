<?php
session_start();
require_once '../config/blockURLAccess.php';
require_once '../../vendor/autoload.php';
require_once '../utils/categoria/adicionarCategorias.php';

use app\controller\CategoriaProdutoController;
$categoriasController = new CategoriaProdutoController();
$categorias = $categoriasController->listarCategorias();

?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gerenciar Categorias</title>
    <script src="https://code.jquery.com/jquery-3.7.1.js" integrity="sha256-eKhayi8LEQwp4NKxN+CfCh+3qOVUtJn3QNZ0TciWLP4=" crossorigin="anonymous"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="style/CabecalhoRodape.css">
    <link rel="stylesheet" href="style/gerenciarCategorias.css">
    <link rel="stylesheet" href="style/components/cards.css">
    <link rel="stylesheet" href="style/components/botao.css">
    <link rel="stylesheet" href="style/base/variables.css">
    <link rel="stylesheet" href="style/base/global.css">
    <link rel="stylesheet" href="style/formulario.css">
    <link rel="shortcut icon" href="../images/iceCreamIcon.ico" type="image/x-icon">
</head>
<body>
    <main>
        <?php include_once 'components/header.php'; ?>
        <section>
            <h1 class="titulo">Categorias</h1>
            <div class="container d-flex flex-column align-items-center justify-content-center m-auto my-5">
                <button class="botao botao-primary" id="addCategoria">Adicionar Categoria</button>
            
                <div class="container my-5 border w-50 rounded-4 py-3" id="formulario" style="display: none;">
                    <form enctype="multipart/form-data" action="gerenciarCategorias.php" method="POST" class="d-flex flex-column align-items-center gap-5">
                        <div class="d-flex flex-column">
                            <div class="mb-3">
                                <input type="text" id="produto" name="nomeProAdd" class="form-control" placeholder="Produto" required />
                            </div>
                            <div class="mb-3">
                                <input type="text" id="marca" name="marcaProAdd" class="form-control" placeholder="Marca" required />
                            </div>
                            <div class="mb-3">
                                <input type="text" id="descricaoProduto" name="descricaoProAdd" class="form-control" placeholder="Descrição" required />
                            </div>
                            <div class="mb-3">
                                <input type="text" id="fornecedor" name="fornecedor" class="form-control" placeholder="Fornecedor" required />
                            </div>
                            <div class="mb-3">
                                <input type="file" id="imagem" name="imagem" class="form-control" required />
                            </div>
                            <img id="preview" src="" alt="Pré-visualização da imagem" class="mx-auto mt-3 align-self-center" style="max-width: 170px; display: none;">
                        </div>
                        <button type="submit" class="botao botao-primary">Salvar</button>
                    </form>
                </div>
                <div class="conteiner d-flex flex-row flex-wrap gap-4 justify-content-center align-items-center mt-3">
                    <?php foreach ($categorias as $categoria): ?>
                        <?php include 'components/categoriasCards.php'; ?>
                    <?php endforeach; ?>
                </div>
            </div>
        </section>
    </main>

    <?php include_once 'components/footer.php'; ?>
    
    <script src="script/exibirFormulario.js"></script>
    <script src="script/exibirArquivoImagem.js"></script>
</body>
</html>
