<?php
session_start();
require_once '../../vendor/autoload.php';
use app\controller\EstoqueController;
use app\controller\ProdutoController;
use app\controller\ProdutoVariacaoController;
require_once '../config/blockURLAccess.php';

$produto = new ProdutoController();
$variacao = new ProdutoVariacaoController();
$estoque = new EstoqueController();
$dados = $estoque->listarEstoque();
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
<meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style/CabecalhoRodape.css">
    <link rel="stylesheet" href="style/estoque-style.css">
    <title>Estoque</title>
</head>
<body>
<?php include_once 'components/header.php'; ?>

<main>
    <div class="d-flex justify-content-between m-auto w-25">
        <a href="" id="editarProdutoEstoque" class="rounded-3 px-3 pb-1 text-decoration-none text-black">Editar</a>
        <a href="" id="excluirProdutoEstoque" class="rounded-3 px-3 pb-1 text-decoration-none text-black">Excluir</a>
    </div>
    <div class="lista m-auto p-3">
        <table class="table table-striped table-hover text-nowrap">
            <thead>
                <tr>
                    <th scope="col"></th>
                    <th scope="col">Produto</th>
                    <th scope="col">Variação</th>
                    <th scope="col">Data de Entrada</th>
                    <th scope="col">Quantidade</th>
                    <th scope="col">Data de Fabricação</th>
                    <th scope="col">Data de Vencimento</th>
                    <th scope="col">Lote</th>
                    <th scope="col">Preço de compra</th>
                    <th scope="col">Quantidade Mínima</th>
                    <th scope="col">Quantidade Vendida</th>
                    <th scope="col">Ocorrencia</th>
                    <th scope="col">Quantidade Ocorrida</th>
                    <th scope="col"></th>
                    <th scope="col"></th>
                </tr>
            </thead>
            <tbody>
                <?php 
                if($dados){
                    foreach($dados as $row){
                        $dadosVariacao = $variacao->selecionarProdutoPorID(intval($row['idVariacao']));
                        if ($dadosVariacao) {
                            $dadosProdutos = $produto->selecionarProdutoPorID(intval($dadosVariacao['idProduto']));
                        } else {
                            $dadosProdutos = false;
                        }

                        echo '<tr>
                                <td scope="row"><input type="checkbox" class="produtoCheck" name="produtoCheck" id="'.$row['idEstoque'].'"></td>
                                <td>'.($dadosProdutos ? $dadosProdutos['nome'] : 'Produto não encontrado').'</td>
                                <td>'.($dadosVariacao ? $dadosVariacao['nomeVariacao'] : 'Variação não encontrada').'</td>
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
                                <td><a class="table-linkEditar text-decoration-none text-black px-3 rounded-4" href="editarEstoque.php?idsArray='.$row['idEstoque'].'">Editar</a></td>
                                <td><a class="table-linkExcluir text-decoration-none text-black px-3 rounded-4" href="excluirEstoque.php?idsArray='.$row['idEstoque'].'">Excluir</a></td>
                            </tr>';
                    }
                }
                ?>
            </tbody>
        </table>
    </div>
</main>
    
<?php include_once 'components/footer.php'; ?>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script
  src="https://code.jquery.com/jquery-3.7.1.js"
  integrity="sha256-eKhayi8LEQwp4NKxN+CfCh+3qOVUtJn3QNZ0TciWLP4="
  crossorigin="anonymous"></script>
  <script src="script/telaEstoqueScript.js"></script>
</body>
</html>