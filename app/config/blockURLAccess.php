<?php
// Este código protege o site do usuário tentar acessar as páginas trocando a URL
if (!defined('STDIN')) {
    if (!isset($_SERVER['HTTP_REFERER']) || strpos($_SERVER['HTTP_REFERER'], $_SERVER['HTTP_HOST']) === false) {
        
        session_start();
        if(isset($_SESSION['userPerfil']) && ($_SESSION['userPerfil'] == 'FUNC' || $_SESSION['userPerfil'] == 'FADM')) 
        {
            echo '<script>window.location.href = "../view/produtos.php";</script>';
        }
        else
        {
            echo '<script>window.location.href = "../view/index.php";</script>';
        }
    }
}
?>