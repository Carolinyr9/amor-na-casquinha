<?php
require_once '../../vendor/autoload.php';

use app\controller\UsuarioController;

$usuarioController = new UsuarioController();
$usuarioController->logout();
