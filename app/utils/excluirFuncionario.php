<?php
use app\controller\FuncionarioController;

if(isset($_GET['exclFunc'])) {
    $funcionarioController = new FuncionarioController();
    $funcionarioController->desativarFuncionario($_GET['exclFunc']);
    header("Location: gerenciarFuncionarios.php");
    exit;
}