<?php
session_start();
require_once '../config/blockURLAccess.php';
require_once '../../vendor/autoload.php';
require_once '../utils/helpers/csrf.php';
require_once '../utils/categoria/excluirCategorias.php';
use app\controller\CategoriaProdutoController;
$categoriaController = new CategoriaProdutoController();
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Excluir Categoria</title>
    <?php include_once '../utils/links/styleLinks.php'; ?> 
    <link rel="stylesheet" href="style/CabecalhoRodape.css">
    <link rel="stylesheet" href="style/excluirProduto.css">
    
    <link rel="shortcut icon" href="../images/iceCreamIcon.ico" type="image/x-icon">
</head>
<body>
    <?php include_once 'components/header.php'; ?>

    <?php
    $categoriaID = isset($_GET['categoria']) ? (int)$_GET['categoria'] : null;
    $categoria = $categoriaID ? $categoriaController->buscarCategoriaPorID($categoriaID) : null;
    ?>

    <main class="d-flex flex-column justify-content-center align-items-center">
        <h1 class="titulo">Excluir Categoria</h1>
        
        <div class="container-info container d-flex align-items-center flex-column justify-content-center mx-auto my-4 p-4 rounded-4">
        <h4 class="subtitulo text-center">Tem certeza que deseja excluir essa categoria?</h4>
            <?php if ($categoria): ?>
                <div class="d-flex align-items-center flex-column">
                    <picture>
                        <img class="rounded-4" src="../images/<?= htmlspecialchars($categoria->getFoto()) ?>" alt="<?= htmlspecialchars($categoria->getNome()) ?>" class="imagem">
                    </picture>
                    <div class="d-flex align-items-center flex-column">
                        <h4 class="subtitulo"><?= htmlspecialchars($categoria->getNome()) ?></h4>
                        <p>Descrição: <?= htmlspecialchars($categoria->getDescricao()) ?></p>
                        <p>Número de Identificação: <?= htmlspecialchars($categoria->getId()) ?></p>
                    </div>
                </div>
                
                <form action="" method="POST" id="formulario" class="formulario">
                    <input type="hidden" name="idCategoriaExcl" value="<?= htmlspecialchars($categoria->getId()) ?>">
                    <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
                    <button type="submit" class="botao botao-alerta">Excluir</button>
                </form>
            <?php else: ?>
                <p>Categoria não encontrada.</p>
            <?php endif; ?>
        </div>
        
        <a class="botao botao-secondary" href="gerenciarCategorias.php">Voltar</a>
    </main>

    <?php include_once 'components/footer.php'; ?>
</body>
</html>
