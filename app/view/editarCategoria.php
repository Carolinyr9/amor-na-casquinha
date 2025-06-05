<?php
session_start();
require_once '../config/blockURLAccess.php';
require_once '../../vendor/autoload.php';
require_once '../utils/categoria/editarCategorias.php';

use app\controller\CategoriaProdutoController;

$categoriaController = new CategoriaProdutoController();
$idCategoria = $_GET['categoria'] ?? null;
$categoria = $categoriaController->buscarCategoriaPorID($idCategoria);
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Categoria</title>
    <?php include_once '../utils/links/styleLinks.php'; ?>
    <link rel="stylesheet" href="style/editarCategoria.css">
    <script src="script/exibirArquivoImagem.js"></script>
</head>
<body>
    <?php include_once 'components/header.php'; ?>
    
    <main>
        <div class="conteiner d-flex flex-column align-items-center justify-content-center">
            <h2 class="titulo">Editar Produto</h2>

            <div class="container-form d-flex flex-column align-items-center justify-content-center rounded-4 p-3 my-3 w-50">
                <?php if ($categoria): ?>
                    <form enctype="multipart/form-data" 
                          action="<?= htmlspecialchars($_SERVER['PHP_SELF']) . '?categoria=' . htmlspecialchars($categoria->getId()) ?>" 
                          method="POST" 
                          class="d-flex flex-column w-75">

                        <input type="hidden" name="categoria" value="<?= htmlspecialchars($categoria->getId()) ?>">
                        <input type="hidden" name="imagemProdEdt" value="<?= htmlspecialchars($categoria->getFoto()) ?>">

                        <div class="mb-3">
                            <label for="produto" class="form-label">Produto</label>
                            <input type="text" class="form-control" id="produto" name="nomeProdEdt" value="<?= htmlspecialchars($categoria->getNome()) ?>" required>
                        </div>

                        <div class="mb-3">
                            <label for="marca" class="form-label">Marca</label>
                            <input type="text" class="form-control" id="marca" name="marcaProdEdt" value="<?= htmlspecialchars($categoria->getMarca()) ?>" required>
                        </div>

                        <div class="mb-3">
                            <label for="descricao" class="form-label">Descrição</label>
                            <input type="text" class="form-control" id="descricao" name="descricaoProdEdt" value="<?= htmlspecialchars($categoria->getDescricao()) ?>" required>
                        </div>

                        <div class="mb-3">
                            <label for="imagem" class="form-label">Imagem</label>
                            <input type="file" id="imagem" name="imagem" class="form-control">
                        </div>

                        <img id="preview" src="" alt="Pré-visualização da imagem" class="mx-auto mt-3" style="max-width: 150px; display: none;">

                        <input type="submit" name="btnEditar" value="Editar" class="botao botao-primary mx-auto mt-4">
                    </form>
                <?php else: ?>
                    <p class="text-danger">Produto não encontrado.</p>
                <?php endif; ?>
            </div>

            <a href="gerenciarCategorias.php" class="botao botao-secondary">Voltar</a>
        </div>
    </main>

    <?php include_once 'components/footer.php'; ?>
</body>
</html>
