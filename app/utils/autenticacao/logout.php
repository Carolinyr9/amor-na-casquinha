<?php
require_once __DIR__ . '/../../../vendor/autoload.php';

use app\controller\UsuarioController;

$usuarioController = new UsuarioController();
$usuarioController->logout();
