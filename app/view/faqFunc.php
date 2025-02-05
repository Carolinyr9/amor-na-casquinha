<?php
session_start();
require_once '../config/blockURLAccess.php';
include_once 'components/header.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (!empty($_POST['inputField'])) {
        $message = htmlspecialchars($_POST['inputField']);

        $to = "carolinyr9@gmail.com"; 
        $subject = "Novas dúvidas";
        
        $headers = "From: no-reply@amor-de-casquinha.com\r\n";
        $headers .= "Reply-To: carolinyr9@gmail.com\r\n";
        $headers .= "Content-Type: text/plain; charset=UTF-8\r\n";

        if (mail($to, $subject, $message, $headers)) {
            echo "✅ E-mail enviado com sucesso!";
        } else {
            $error = error_get_last();
            echo "❌ Erro ao enviar o e-mail: " . $error;
        }
    } else {
        echo "❌ O campo de entrada está vazio!";
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Amor de Casquinha - Perguntas Frequentes</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="style/CabecalhoRodape.css">
    <link rel="shortcut icon" href="images/iceCreamIcon.ico" type="image/x-icon">
    <link rel="stylesheet" href="style/indexS.css">
</head>
<body>
    <main>
        <section>
            <div class="container d-flex flex-column justify-content-center align-items-center rounded-4 p-4">
                <h3 class="text-center pb-1">Perguntas Frequentes</h3>
                <div class="d-flex flex-wrap justify-content-between align-items-center">
                    <div class="card categ d-flex align-items-center rounded-4 h-auto border-0 mt-3">
                        <div class="d-flex align-items-center flex-column text-center p-3">
                            <h4>Como para ver meus pedidos atribuidos?</h4>
                            <p>
                                Lorem ipsum dolor sit amet, consectetur adipiscing elit. Mauris a convallis risus, at accumsan urna. Aliquam sodales porttitor rutrum. Interdum et malesuada fames ac ante ipsum primis in faucibus. Ut lorem lacus, auctor quis fringilla sed, consectetur sagittis lorem. Sed in nunc at nibh fermentum cursus in a velit.
                            </p>
                        </div>
                    </div>
                    <div class="card categ d-flex align-items-center rounded-4 h-auto border-0 mt-3">
                        <div class="d-flex align-items-center flex-column text-center p-3">
                            <h4>Como faço para fazer o gerenciamento dos produtos?</h4>
                            <p>
                                Lorem ipsum dolor sit amet, consectetur adipiscing elit. Mauris a convallis risus, at accumsan urna. Aliquam sodales porttitor rutrum. Interdum et malesuada fames ac ante ipsum primis in faucibus. Ut lorem lacus, auctor quis fringilla sed, consectetur sagittis lorem. Sed in nunc at nibh fermentum cursus in a velit.
                            </p>
                        </div>
                    </div>
                    <div class="card categ d-flex align-items-center rounded-4 h-auto border-0 mt-3">
                        <div class="d-flex align-items-center flex-column text-center p-3">
                            <h4>Como acompanho meus pedidos?</h4>
                            <p>
                                Lorem ipsum dolor sit amet, consectetur adipiscing elit. Mauris a convallis risus, at accumsan urna. Aliquam sodales porttitor rutrum. Interdum et malesuada fames ac ante ipsum primis in faucibus. Ut lorem lacus, auctor quis fringilla sed, consectetur sagittis lorem. Sed in nunc at nibh fermentum cursus in a velit.
                            </p>
                        </div>
                    </div>
                    <div class="card categ d-flex align-items-center rounded-4 h-auto border-0 mt-3">
                        <div class="d-flex align-items-center flex-column text-center p-3">
                            <h4>Como mudo o status de um pedido?</h4>
                            <p>
                                Lorem ipsum dolor sit amet, consectetur adipiscing elit. Mauris a convallis risus, at accumsan urna. Aliquam sodales porttitor rutrum. Interdum et malesuada fames ac ante ipsum primis in faucibus. Ut lorem lacus, auctor quis fringilla sed, consectetur sagittis lorem. Sed in nunc at nibh fermentum cursus in a velit.
                            </p>
                        </div>
                    </div>
                    <div class="card categ d-flex align-items-center rounded-4 h-auto border-0 mt-3">
                        <div class="d-flex align-items-center flex-column text-center p-3">
                            <h4>Como posso fazer a gestão do estoque?</h4>
                            <p>
                                Lorem ipsum dolor sit amet, consectetur adipiscing elit. Mauris a convallis risus, at accumsan urna. Aliquam sodales porttitor rutrum. Interdum et malesuada fames ac ante ipsum primis in faucibus. Ut lorem lacus, auctor quis fringilla sed, consectetur sagittis lorem. Sed in nunc at nibh fermentum cursus in a velit.
                            </p>
                        </div>
                    </div>
                </div>
                
                Mais dúvidas? 
                Envie uma mensagem diretamente:
                <form action="" method="post">
                    <textarea id="inputField" name="inputField" cols="30" required></textarea>
                    <input type="submit" value="Enviar">
                </form>

            </div>
        </section>
    </main>
    <?php include_once 'components/footer.php'; ?>
</body>
</html>
