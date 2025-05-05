<?php
session_start();
require_once '../config/blockURLAccess.php';
require_once '../config/config.php';
require_once '../../vendor/autoload.php';
use app\controller\ClienteController;
use app\controller\PedidoController;
use app\model\Carrinho;

$pedidoController = new PedidoController();
$clienteController = new ClienteController();
$carrinho = new Carrinho();

$clienteData = $clienteController->getClienteData($_SESSION["userEmail"]);
$pedidos = $pedidoController->listarPedidoPorCliente($_SESSION["userEmail"]);
$itensCarrinho = $carrinho->listarCarrinho();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST["btnSubmit"])) {
        $frete = isset($_POST["frete"]) && is_numeric($_POST["frete"]) ? $_POST["frete"] : NULL;
        $isDelivery = (is_null($frete) || !is_numeric($frete)) ? 0 : (isset($_POST["ckbIsDelivery"]) ? 1 : 0);
        $trocoPara = isset($_POST["trocoPara"]) && is_numeric($_POST["trocoPara"]) ? (float) $_POST["trocoPara"] : NULL;

        $pedidoController->criarPedido(
            $_SESSION["userEmail"],
            $isDelivery,
            $_POST["totalComFrete"],
            $frete,
            isset($_POST["meioDePagamento"]) ? $_POST["meioDePagamento"] : NULL,
            $trocoPara,
            $itensCarrinho
        );
        unset($_POST);

        $carrinho->limparCarrinho();
        header("Location: sobre.php");       
    }

    if (isset($_POST["btnAlterarCliente"])) {
        $clienteController->editarCliente(
            $_SESSION["userEmail"],
            $_POST["idEndereco"],
            $_POST["nome"],
            $_POST["telefone"],
            $_POST["rua"],
            $_POST["cep"],
            $_POST["numero"],
            $_POST["bairro"],
            $_POST["cidade"],
            $_POST["estado"],
            $_POST["complemento"]
        );
        header("Location: sobre.php");
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Perfil</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-SgOJa3DmI69IUzQ2PVdRZhwQ+dy64/BUtbMJw1MZ8t5HZApcHrRKUc4W0kG879m7" crossorigin="anonymous">  
    <link rel="stylesheet" href="style/CabecalhoRodape.css">
    <link rel="stylesheet" href="style/sobreS.css">
    <link rel="shortcut icon" href="images/iceCreamIcon.ico" type="image/x-icon">
    <link rel="stylesheet" href="style/components/botao.css">
    <link rel="stylesheet" href="style/components/cards.css">
    <link rel="stylesheet" href="style/base/global.css">
    <link rel="stylesheet" href="style/base/variables.css">
</head>
<body>
    <?php include_once 'components/header.php'; ?>
    <main>
        <section>
            <h1 class="titulo">Perfil</h1>
            <div class="container-section container d-flex align-items-center flex-column text-center rounded-4 w-75 p-4 my-3">
                <div class="w-100">
                    <?php if (isset($clienteData["error"])): ?>
                        <div class="alert alert-danger">
                            <?php echo htmlspecialchars($clienteData["error"]); ?>
                        </div>
                    <?php else: ?>
                        <div id="dados">
                            <p class="fs-5">Nome: <?= htmlspecialchars($clienteData['nome']); ?></p>
                            <p class="fs-5">Email: <?= htmlspecialchars($clienteData['email']); ?></p>
                            <p class="fs-5">Telefone: <?= htmlspecialchars($clienteData['telefone']); ?></p>
                            <p class="fs-5">Endereço: </p>
            
                            <?php if (isset($clienteData['endereco']['rua'])): ?>
                                <div id="endereco">
                                    <p class="fs-5">
                                        <?= htmlspecialchars($clienteData['endereco']['rua']) . ', ' .
                                           htmlspecialchars($clienteData['endereco']['numero']) . ', ' .
                                           (isset($clienteData['endereco']['complemento']) ? htmlspecialchars($clienteData['endereco']['complemento']) . ', ' : '') .
                                           htmlspecialchars($clienteData['endereco']['bairro']) . ', ' .
                                           htmlspecialchars($clienteData['endereco']['cidade']) . ', ' .
                                           htmlspecialchars($clienteData['endereco']['estado']) . ', ' .
                                           htmlspecialchars($clienteData['endereco']['cep']); ?>
                                    </p>
                                </div>
                            <?php else: ?>
                                <p>Endereço não encontrado!</p>
                            <?php endif; ?>
                        </div>
                    <?php endif; ?>
                    <form action="" class="formEditar flex-column justify-content-center w-auto " method="POST" id="formulario">
                        <p class="subtitulo">Usuário</p>
                        <div class="d-flex flex-row flex-wrap justify-content-center gap-4">
                            <div class="form-group">
                                <input type="text" name="nome" class="form-control" placeholder="Nome" value="<?= htmlspecialchars($clienteData['nome'] ?? ''); ?>" readonly>
                            </div>
                            <div class="form-group">
                                <input type="email" class="form-control" name="email" placeholder="Email" value="<?= htmlspecialchars($clienteData['email'] ?? ''); ?>" readonly>
                            </div>
                            <div class="form-group">
                                <input type="text" class="form-control" name="senha" placeholder="Senha" readonly>
                            </div>
                            <div class="form-group">
                                <input type="text" class="form-control" name="telefone" placeholder="Telefone" pattern="^\(\d{2}\) \d{4,5}-\d{4}$" title="O telefone deve seguir o formato (XX) XXXX-XXXX ou (XX) XXXXX-XXXX" maxlength="15" required>
                            </div>
                        </div>

                        <span class="subtitulo">Endereço</span>
                        <div class="d-flex flex-row flex-wrap justify-content-center gap-4">
                            <div class="form-group">
                                <input type="text" class="form-control" name="rua" placeholder="Rua" pattern="^[A-Za-zÀ-ÿ\s]+$" title="A rua deve conter apenas letras e espaços" maxlength="50" required>
                            </div>
                            <div class="form-group">
                                <input type="number" class="form-control" name="numero" placeholder="Número" min="1" required>
                            </div>
                            <div class="form-group">
                                <input type="text" class="form-control" name="complemento" placeholder="Complemento" maxlength="50">
                            </div>
                            <div class="form-group">
                                <input type="text" class="form-control" name="cep" placeholder="CEP" pattern="^\d{5}-\d{3}$" title="O CEP deve seguir o formato XXXXX-XXX" required>
                            </div>
                            
                            <div class="form-group">
                                <input type="text" class="form-control" name="bairro" placeholder="Bairro" pattern="^[A-Za-zÀ-ÿ\s]+$" title="O bairro deve conter apenas letras e espaços" maxlength="50" required>
                            </div>
                            <div class="form-group">
                                <input type="text" class="form-control" name="cidade" placeholder="Cidade" pattern="^[A-Za-zÀ-ÿ\s]+$" title="A cidade deve conter apenas letras e espaços" maxlength="50" required>
                            </div>
                            <div class="form-group">
                                <input type="text" class="form-control" name="estado" placeholder="Estado" pattern="^[A-Za-zÀ-ÿ\s]+$" title="O estado deve conter apenas letras e espaços" maxlength="50" required>
                            </div>
                        </div>

                        <input type="hidden" name="idEndereco" value="<?= htmlspecialchars($clienteData['endereco']['idEndereco'] ?? ''); ?>">
                        <button type="submit" name="btnAlterarCliente" class="botao botao-primary mt-4" style="width: 100px;">Salvar</button>
                    </form>
                </div>
                <button id="edit" class="botao botao-secondary">Editar</button>
            </div>
        </section>

        <section>
            <h1 class="titulo">Meus pedidos</h1>
            <div class="d-flex flex-row gap-5 mt-5"><?php include 'components/pedidosCards.php'; ?></div>
        </section>
    </main>
    <?php include_once 'components/footer.php'; ?>
    <script src="script/editar.js"></script>
</body>
</html>