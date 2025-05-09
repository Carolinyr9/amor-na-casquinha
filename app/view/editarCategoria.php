<?php
session_start();
require_once '../config/blockURLAccess.php';
require_once '../../vendor/autoload.php';
require_once '../utils/categorias/editarCategorias.php';

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
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-SgOJa3DmI69IUzQ2PVdRZhwQ+dy64/BUtbMJw1MZ8t5HZApcHrRKUc4W0kG879m7" crossorigin="anonymous">  
    <link rel="stylesheet" href="style/CabecalhoRodape.css">
    <link rel="stylesheet" href="style/editaSaborS.css">
    <link rel="shortcut icon" href="images/iceCreamIcon.ico" type="image/x-icon">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="script/exibirArquivoImagem.js"></script>
</head>
<body>
    <?php include_once 'components/header.php'; ?>
    
    <main>
        <div class="conteiner d-flex flex-column align-items-center justify-content-center">
            <h2 class="text-center pt-4 pb-4">Editar Produto</h2>

            <div class="conteiner-form p-3 rounded-4 my-3 w-75">
                <?php if ($categoria): ?>
                    <form enctype="multipart/form-data" 
                          action="<?= htmlspecialchars($_SERVER['PHP_SELF']) . '?categoria=' . htmlspecialchars($categoria->getId()) ?>" 
                          method="POST" 
                          class="formulario d-flex flex-column">

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

                        <input type="submit" name="btnEditar" value="Editar" class="btnEditar mt-3 mx-auto border-0 rounded-3 px-4">
                    </form>
                <?php else: ?>
                    <p class="text-danger">Produto não encontrado.</p>
                <?php endif; ?>
            </div>

            <a href="gerenciarCategorias.php" class="btn voltar border-0 rounded-3 fw-bold">Voltar</a>
        </div>
    </main>

    <?php include_once 'components/footer.php'; ?>
</body>
</html>
