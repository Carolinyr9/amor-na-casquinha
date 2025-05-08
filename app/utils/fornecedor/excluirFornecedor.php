<?php
use app\controller\FornecedorController;

if(isset($_GET['exclForn'])) {
    $fornecedorController = new FornecedorController();
    $fornecedorController->desativarFornecedor($_GET['exclForn']);
    header("Location: gerenciarFornecedores.php");
    exit;
}