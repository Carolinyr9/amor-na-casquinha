<?php
session_start();
require_once '../config/blockURLAccess.php';
require_once '../utils/helpers/faqHandler.php';
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Amor de Casquinha - Perguntas Frequentes</title>
    <?php include_once '../utils/links/styleLinks.php'; ?>
    <link rel="stylesheet" href="style/faq.css">
</head>
<body>
    <?php include_once 'components/header.php'; ?>
    <main>
        <section>
            <div class="container d-flex flex-column justify-content-center align-items-center rounded-4 p-4">
                <h3 class="titulo">Perguntas Frequentes</h3>
                <div class="container-fac d-flex flex-row flex-wrap justify-content-between align-items-start gap-4 my-3">
                    <div class="card-fac d-flex align-items-center flex-column text-center p-3 w-25">
                        <h4 class="subtitulo">Como faço uma compra?</h4>
                        <p>
                            No menu superior, clique em "Início" e escolha o tipo de produto desejado. Em seguida, clique em "Adicionar ao Carrinho". Para finalizar a compra, clique no ícone do carrinho, revise os itens e selecione "Concluir Pedido". Se desejar entrega, marque a opção correspondente para atualizar o valor do frete. Escolha a forma de pagamento e conclua seu pedido.
                        </p>
                    </div>
                    <div class="card-fac d-flex align-items-center flex-column text-center p-3 w-25">
                        <h4 class="subtitulo">Quais são as formas de pagamento?</h4>
                        <p>
                            Atualmente, os pagamentos são feitos no momento da retirada do produto ou na entrega. O preenchimento das informações de pagamento é apenas para organização prévia.
                        </p>
                    </div>
                    <div class="card-fac d-flex align-items-center flex-column text-center p-3 w-25">
                        <h4 class="subtitulo">Como acompanho meus pedidos?</h4>
                        <p>
                            Acesse seu perfil clicando no ícone no menu superior. Role a página para baixo para visualizar seus pedidos e clique em "Ver Informações" para obter detalhes sobre o status da entrega.
                        </p>
                    </div>
                    <div class="card-fac d-flex align-items-center flex-column text-center p-3 w-25">
                        <h4 class="subtitulo">Posso cancelar um pedido?</h4>
                        <p>
                            Sim. Acesse seu perfil e localize o pedido desejado. Se ele ainda não saiu para entrega, a opção de cancelamento estará disponível. Após esse estágio, não é possível cancelar o pedido.
                        </p>
                    </div>
                    <div class="card-fac d-flex align-items-center flex-column text-center p-3 w-25">
                        <h4 class="subtitulo">Como altero meus dados de perfil?</h4>
                        <p>
                            No menu superior, clique em "Perfil" e depois em "Editar". Faça as alterações desejadas e clique em "Salvar" para atualizar suas informações.
                        </p>
                    </div>
                </div>
                
                <div class="d-flex flex-column justify-content-center">
                    Mais dúvidas?
                    Envie uma mensagem diretamente:
                    <form action="../utils/faqHandler.php" method="post" class="d-flex flex-column justify-content-center gap-4">
                        <input type="email" name="emailField" class="form-control" placeholder="Email para contato" required>
                        <textarea id="inputField" name="inputField" cols="30" class="form-control" placeholder="Sua dúvida" required></textarea>
                        <input type="submit" class="botao botao-primary mx-auto" value="Enviar">
                    </form>
                </div>

            </div>
        </section>
    </main>
    <?php include_once 'components/footer.php'; ?>
</body>
</html>