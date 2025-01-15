<?php
session_start();
require_once '../config/blockURLAccess.php';
require_once '../controller/estoqueController.php';

if (isset($_GET['idsArray'])) {
    $idsArray = explode(',', $_GET['idsArray']);
} else {
    echo "Nenhum produto foi selecionado.";
}

foreach($idsArray as $id){

}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style/CabecalhoRodape.css">
    <link rel="stylesheet" href="style/editarEstoque-style.css">
    <title>Edição de Estoque</title>
</head>
<body>
<?php include_once 'components/header.php'; ?>

<main>
    <h2 class="text-center mb-3">Edição</h2>
    <form action="" method="post" class="formulario w-50 m-auto p-3 rounded-4 d-flex flex-row flex-wrap gap-4 justify-content-center">
        <div class="form-group w-25">
            <label for="lote">Lote</label>
            <input type="text" class="form-control" id="lote" name="lote" required>
        </div>
        <div class="form-group w-25">
            <label for="valor">Valor</label>
            <input type="text" class="form-control" id="valor" name="valor" required>
        </div>
        <div class="form-group w-25">
            <label for="quantidade">Quantidade</label>
            <input type="number" class="form-control" id="quantidade" name="quantidade" required>
        </div>
        <div class="form-group w-25">
            <label for="dataFabricacao">Fabricação</label>
            <input type="date" class="form-control" id="dataFabricacao" name="dataFabricacao" required>
        </div>
        <div class="form-group w-25">
            <label for="dataVencimento">Vencimento</label>
            <input type="date" class="form-control" id="dataVencimento" name="dataVencimento" required>
        </div>
        <div class="form-group w-25">
            <label for="quantidadeMinima">Quantidade Mínima</label>
            <input type="text" class="form-control" id="quantidadeMinima" name="quantidadeMinima" required>
        </div>
        <div class="form-group w-25">
            <label for="quantidadeOcorrencia">Quantidade ocorrida</label>
            <input type="number" class="form-control" id="quantidadeOcorrencia" name="quantidadeOcorrencia" required>
        </div>
        <div class="form-group w-100">
            <label for="ocorrencia">Ocorrência</label>
            <textarea class="form-control" name="ocorrencia" id="ocorrencia"></textarea>
        </div>
    </form>
</main>

<?php include_once 'components/footer.php'; ?>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script
  src="https://code.jquery.com/jquery-3.7.1.js"
  integrity="sha256-eKhayi8LEQwp4NKxN+CfCh+3qOVUtJn3QNZ0TciWLP4="
  crossorigin="anonymous"></script>
</body>
</html>