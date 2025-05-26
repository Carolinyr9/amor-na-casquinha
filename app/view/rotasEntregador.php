<?php 
session_start();
require_once '../config/blockURLAccess.php';
require_once '../../vendor/autoload.php';
require_once '../utils/endereco/enderecoHelper.php';
use utils\obterEnderecoCompleto;
$dadosEndereco = obterEnderecoCompleto($_GET['idEndereco']);
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rotas</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
    <link rel="stylesheet" href="style/CabecalhoRodape.css">
    <link rel="stylesheet" href="style/rotasEntregador.css">
    <link rel="shortcut icon" href="../images/iceCreamIcon.ico" type="image/x-icon">
    <link rel="stylesheet" href="style/components/botao.css">
    <link rel="stylesheet" href="style/components/cards.css">
    <link rel="stylesheet" href="style/base/global.css">
    <link rel="stylesheet" href="style/base/variables.css">
</head>
<body>
    <?php include_once 'components/header.php'; ?>

    <main class="d-flex justify-content-center align-items-center flex-column">
        <h1 class="titulo m-auto text-center py-4">Rotas</h1>
        <div class="container-mapa d-flex flex-column justify-content-center align-items-center w-100 p-3 m-auto">
            <h4 class="subtitulo">Endereço do Destinatário</h4>
            <div>
                <p>
                    Rua: <?= $dadosEndereco['rua']; ?>, Nº <?= $dadosEndereco['numero']; ?><br>
                    <?php if (!empty($dadosEndereco['complemento'])): ?>
                        Complemento: <?= $dadosEndereco['complemento']; ?><br>
                    <?php endif; ?>
                    Bairro: <?= $dadosEndereco['bairro']; ?><br>
                    Cidade: <?= $dadosEndereco['cidade']; ?><br>
                    Estado: <?= $dadosEndereco['estado']; ?><br>
                    CEP: <?= $dadosEndereco['cep']; ?>
                </p>
            </div>

            <iframe class="mapa"
                id="mapa" 
                src="https://www.google.com/maps?q=<?= $dadosEndereco['url']; ?>&output=embed" 
                style="border:0;" 
                allowfullscreen 
                loading="lazy" 
                referrerpolicy="no-referrer-when-downgrade">
            </iframe>
        </div>

        <a class="botao botao-secondary" href="pedidosEntregador.php">Voltar</a>
    </main>

    <?php include_once 'components/footer.php'; ?>
</body>
</html>