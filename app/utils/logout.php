<?php
require_once '../../vendor/autoload.php';

use app\controller2\UsuarioController;

$usuarioController = new UsuarioController();
$usuarioController->logout();
