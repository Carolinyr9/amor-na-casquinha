<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

function exibirMensagem($chave) {
    if (isset($_SESSION[$chave])) {
        echo '<div class="alert alert-info text-center">' . htmlspecialchars($_SESSION[$chave]) . '</div>';
        unset($_SESSION[$chave]);
    }
}
