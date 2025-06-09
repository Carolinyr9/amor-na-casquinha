<?php
namespace app\controller;

use app\utils\helpers\Logger;
use Exception;

class FreteController {
    public function calcularFrete($cep) {
        try {
            if (!preg_match("/^\d{5}-?\d{3}$/", $cep)) {
                Logger::logError("CEP inválido. Use o formato 99999-999");
                return ['valor' => null, 'descricao' => 'Erro: Formato de CEP inválido.'];
            }

            $cepLimpo = preg_replace('/[^0-9]/', '', $cep);
            $cepPrefixo = substr($cepLimpo, 0, 5);

            $cepBase = '07115'; // Guarulhos - base
            $bairrosProximos = ['07110', '07111', '07112', '07113', '07114', '07116', '07117', '07118'];
            $bairrosAfastados = ['07120', '07130', '07090', '07210', '07040'];

            if ($cepPrefixo === $cepBase) {
                return ['valor' => 0.00, 'descricao' => 'Entrega gratuita no bairro'];
            } elseif (in_array($cepPrefixo, $bairrosProximos)) {
                return ['valor' => 5.00, 'descricao' => 'Bairro próximo - taxa de entrega: R$5,00'];
            } elseif (in_array($cepPrefixo, $bairrosAfastados)) {
                return ['valor' => 10.00, 'descricao' => 'Bairro mais afastado - taxa de R$10,00'];
            } else {
                return ['valor' => null, 'descricao' => 'Entrega não disponível para este CEP'];
            }
        } catch (Exception $e) {
            Logger::logError("Erro no cálculo de frete: " . $e->getMessage());
            return ['valor' => null, 'descricao' => 'Erro interno ao calcular o frete.'];
        }
    }
}
