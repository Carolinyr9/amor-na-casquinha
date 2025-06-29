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
                        <h4 class="subtitulo">Como para ver meus pedidos atribuidos?</h4>
                        <p>
                            Se você é funcionário, poderá ver todos os pedidos realizados, quem os fez, o endereço de entrega e também atribuir entregadores a cada pedido.
                        </p>
                        <p>
                            Se você é entregador, verá apenas os pedidos atribuídos a você, com os detalhes do cliente, endereço e um link direto para o Google Maps com a rota da entrega.
                        </p>
                    </div>
                    
                    <div class="card-fac d-flex align-items-center flex-column text-center p-3 w-25">
                        <h4 class="subtitulo">Como faço para fazer o gerenciamento dos produtos?</h4>
                        <p>
                        Na página inicial do painel de funcionários, você verá o gerenciamento de categorias. Clicando em cada categoria, é possível: acessar os produtos relacionados, editar, excluir ou visualizar detalhes de cada item e cadastrar novos produtos ou criar novas categorias.
                        </p>
                    </div>
                    
                    <div class="card-fac d-flex align-items-center flex-column text-center p-3 w-25">
                        <h4 class="subtitulo">Como acompanho meus pedidos?</h4>
                        <p>
                            Na aba “Pedidos”, você tem uma visão geral de todos os pedidos já realizados. Para mais detalhes, clique em “Ver mais informações” em cada pedido. Assim, você poderá acompanhar os dados completos e tomar as ações necessárias.
                        </p>
                    </div>
                    
                    <div class="card-fac d-flex align-items-center flex-column text-center p-3 w-25">
                        <h4 class="subtitulo">Como mudo o status de um pedido?</h4>
                        <p>
                            Ainda na aba “Pedidos”, ao clicar em “Ver mais informações”, você terá acesso às opções de atualização de status do pedido (ex: Em preparação, A caminho, Entregue).
                        </p>
                    </div>
                    
                    <div class="card-fac d-flex align-items-center flex-column text-center p-3 w-25">
                        <h4 class="subtitulo">Como posso fazer a gestão do estoque?</h4>
                        <p>
                            Na aba “Estoque”, você pode: ver todas as categorias e os produtos disponíveis, editar, excluir ou adicionar novos estoques de produtos e criar novas categorias conforme necessário
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
