<?php
session_start();
require_once '../config/blockURLAccess.php';
require_once '../controller/estoqueController.php';
require_once '../controller/produtoController.php';
// require_once '../controller/variacaoController.php';

$estoque = new EstoqueController();
$dados = $estoque->listarEstoque();
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
<meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Meus Pedidos</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style/CabecalhoRodape.css">
    <link rel="stylesheet" href="style/pedidosS.css">
    <title>Estoque</title>
</head>
<body>
<?php include_once 'components/header.php'; ?>

<main>
<table>
    <thead>
        <tr>
            <th>Produto</th>
            <th>Variação</th>
            <th>Data de Entrada</th>
            <th>Quantidade</th>
            <th>Data de Fabricação</th>
            <th>Data de Vencimento</th>
            <th>Lote</th>
            <th>Preço de compra</th>
            <th>Quantidade Mínima</th>
            <th>Quantidade Vendida</th>
            <th>Ocorrencia</th>
            <th>Quantidade Ocorrida</th>
            <th></th>
            <th></th>
        </tr>
    </thead>
    <tbody>
        <?php 
        if($dados){
            foreach($dados as $row){
                echo '<tr>
                        <td>'.$row['idProduto'].'</td>
                        <td>'.$row['idVariacao'].'</td>
                        <td>'.$row['dtEntrada'].'</td>
                        <td>'.$row['quantidade'].'</td>
                        <td>'.$row['dtFabricacao'].'</td>
                        <td>'.$row['dtVencimento'].'</td>
                        <td>'.$row['lote'].'</td>
                        <td>'.$row['precoCompra'].'</td>
                        <td>'.$row['qtdMinima'].'</td>
                        <td>'.$row['qtdVendida'].'</td>
                        <td>'.$row['ocorrencia'].'</td>
                        <td>'.$row['qtdOcorrencia'].'</td>
                        <td><a href="editarEstoque.php?id='.$row['idEstoque'].'">Editar</a></td>
                        <td><a href="../controller/estoqueController.php?acao=excluir&id='.$row['idEstoque'].'">Excluir</a></td>

                    </tr>';
            }
        }
        ?>
    </tbody>
</table>
</main>
    
<?php include_once 'components/footer.php'; ?>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>