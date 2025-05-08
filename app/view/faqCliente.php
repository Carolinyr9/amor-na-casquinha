<?php
session_start();
require_once '../config/blockURLAccess.php';
include_once 'components/header.php';
require_once '../utils/helpers/faqHandler.php';
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
                            <h4>Como faço uma compra?</h4>
                            <p>
                                No menu superior, clique em "Início" e escolha o tipo de produto desejado. Em seguida, clique em "Adicionar ao Carrinho". Para finalizar a compra, clique no ícone do carrinho, revise os itens e selecione "Concluir Pedido". Se desejar entrega, marque a opção correspondente para atualizar o valor do frete. Escolha a forma de pagamento e conclua seu pedido.
                            </p>
                        </div>
                    </div>
                    <div class="card categ d-flex align-items-center rounded-4 h-auto border-0 mt-3">
                        <div class="d-flex align-items-center flex-column text-center p-3">
                            <h4>Quais são as formas de pagamento?</h4>
                            <p>
                                Atualmente, os pagamentos são feitos no momento da retirada do produto ou na entrega. O preenchimento das informações de pagamento é apenas para organização prévia.
                            </p>
                        </div>
                    </div>
                    <div class="card categ d-flex align-items-center rounded-4 h-auto border-0 mt-3">
                        <div class="d-flex align-items-center flex-column text-center p-3">
                            <h4>Como acompanho meus pedidos?</h4>
                            <p>
                                Acesse seu perfil clicando no ícone no menu superior. Role a página para baixo para visualizar seus pedidos e clique em "Ver Informações" para obter detalhes sobre o status da entrega.
                            </p>
                        </div>
                    </div>
                    <div class="card categ d-flex align-items-center rounded-4 h-auto border-0 mt-3">
                        <div class="d-flex align-items-center flex-column text-center p-3">
                            <h4>Posso cancelar um pedido?</h4>
                            <p>
                                Sim. Acesse seu perfil e localize o pedido desejado. Se ele ainda não saiu para entrega, a opção de cancelamento estará disponível. Após esse estágio, não é possível cancelar o pedido.
                            </p>
                        </div>
                    </div>
                    <div class="card categ d-flex align-items-center rounded-4 h-auto border-0 mt-3">
                        <div class="d-flex align-items-center flex-column text-center p-3">
                            <h4>Como altero meus dados de perfil?</h4>
                            <p>
                                No menu superior, clique em "Perfil" e depois em "Editar". Faça as alterações desejadas e clique em "Salvar" para atualizar suas informações.
                            </p>
                        </div>
                    </div>
                </div>
                
                Mais dúvidas? 
                Envie uma mensagem diretamente:
                <form action="../utils/faqHandler.php" method="post">
                    <input type="email" name="emailField" required placeholder="Email para contato">
                    <textarea id="inputField" name="inputField" cols="30" required placeholder="Sua dúvida"></textarea>
                    <input type="submit" value="Enviar">
                </form>

            </div>
        </section>
    </main>
    <?php include_once 'components/footer.php'; ?>
</body>
</html>