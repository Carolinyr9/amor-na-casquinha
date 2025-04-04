<?php 
session_start();
require_once '../config/blockURLAccess.php';
require_once '../../vendor/autoload.php';
use app\controller\ClienteController;
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
</head>
<body>
    <?php
        include_once 'components/header.php';
        
        $clienteController = new ClienteController();
        
        $endereco = $clienteController->listarEndereco($_GET['idEndereco']);
        
        // Recuperando as informações do endereço
        $rua = $endereco['rua'];
        $numero = $endereco['numero'];
        $complemento = $endereco['complemento'];
        $bairro = $endereco['bairro'];
        $cidade = $endereco['cidade'];
        $estado = $endereco['estado'];
        $cep = $endereco['cep'];

        // Concatenando o endereço completo para a busca no Google Maps
        $enderecoCompleto = urlencode($rua . ', ' . $numero . ', ' . $bairro . ', ' . $cidade . ' - ' . $estado . ', ' . $cep);
    ?>
    <main class="d-flex justify-content-center align-items-center flex-column">
        <h1 class="m-auto text-center pt-4 pb-4">Rotas</h1>
            <div class="container-mapa w-100 p-3 m-auto">
                    <h4>Endereço do Destinatário</h4>
                    <p>
                        Rua: <?php echo $rua; ?>, Nº <?php echo $numero; ?><br>
                        Complemento: <?php echo $complemento; ?><br>
                        Bairro: <?php echo $bairro; ?><br>
                        Cidade: <?php echo $cidade; ?><br>
                        Estado: <?php echo $estado; ?><br>
                        CEP: <?php echo $cep; ?>
                    </p>

                    <!-- Exibindo o mapa com o endereço dinâmico -->
                    <iframe class="mapa"
                        id="mapa" 
                        src="https://www.google.com/maps?q=<?php echo $enderecoCompleto; ?>&output=embed" 
                        style="border:0;" 
                        allowfullscreen="" 
                        loading="lazy" 
                        referrerpolicy="no-referrer-when-downgrade">
                    </iframe>
                </div>
                <button class="voltar mt-5 fs-5 fw-bold rounded-4 border-0"><a class="text-decoration-none fs-5" href="pedidosEntregador.php">Voltar</a></button>
    </main>
    
    <?php
        include_once 'components/footer.php';
    ?>
</body>
</html>
