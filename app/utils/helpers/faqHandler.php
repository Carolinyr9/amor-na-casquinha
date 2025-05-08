<?php
session_start();
use app\utils\helpers\Logger;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (!empty($_POST['inputField'])) {
        $message = htmlspecialchars($_POST['inputField']);
        $email = htmlspecialchars($_POST['emailField']);

        $to = "carolinyr9@gmail.com"; 
        $subject = "Novas dúvidas";
        
        $headers = "From: no-reply@amor-de-casquinha.com\r\n";
        $headers .= "Reply-To: " . $email . "\r\n";
        $headers .= "Content-Type: text/plain; charset=UTF-8\r\n";

        if (mail($to, $subject, $message, $headers)) {
            $redirect = $_SERVER['HTTP_REFERER'] ?? '../view/index.php';
            header("Location: $redirect");
            exit();
        } else {
            $error = error_get_last();
            Logger::logError("❌ Erro ao enviar o e-mail: " . $error);
        }
    } else {
        Logger::logError("O campo de entrada está vazio!");
    }
}
?>