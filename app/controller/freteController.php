<?php
namespace app\controller;

use app\utils\helpers\Logger;
use Exception;

class FreteController {
    public function calcularFrete($cep) {
        try {
            if (!preg_match("/^\d{5}-?\d{3}$/", $cep)) {
                Logger::logError("CEP inválido. Use o formato 99999-999");
                return "Erro ao calcular o frete: Formato inválido fornecido";
            }

            $url = "http://localhost:8080/sorveteria/frete?cep=" . urlencode($cep);
            $ch = curl_init();
            $timeout = 5;

            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_TIMEOUT, $timeout);

            $data = curl_exec($ch);

            if (curl_errno($ch)) {
                Logger::logError("Erro ao calcular o frete.");
                return "Erro ao calcular o frete.";
            }

            if ($data == 1) {
                Logger::logError("Estamos muito distantes do seu endereço para fazer uma entrega!");
                return "Estamos muito distantes do seu endereço para fazer uma entrega!";
            }

            curl_close($ch);

            return $data;
        } catch(Exception $e) {
            Logger::logError("Erro ao calcular o frete: " . $e->getMessage());
            return "Erro ao calcular o frete: " . $e->getMessage();
        }
    }
}