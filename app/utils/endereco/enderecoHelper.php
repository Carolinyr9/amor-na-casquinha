<?php
use app\controller\EnderecoController;
use app\utils\helpers\Logger;

function obterEnderecoCompleto($idEndereco)
{
    try {
        $enderecoController = new EnderecoController();
        $endereco = $enderecoController->listarEnderecoPorId($idEndereco);

        if (!$endereco) {
            throw new \Exception("Endereço não encontrado");
        }

        return [
            'rua' => $endereco->getRua(),
            'numero' => $endereco->getNumero(),
            'complemento' => $endereco->getComplemento(),
            'bairro' => $endereco->getBairro(),
            'cidade' => $endereco->getCidade(),
            'estado' => $endereco->getEstado(),
            'cep' => $endereco->getCep(),
            'url' => urlencode(
                $endereco->getRua() . ', ' .
                $endereco->getNumero() . ', ' .
                $endereco->getBairro() . ', ' .
                $endereco->getCidade() . ' - ' .
                $endereco->getEstado() . ', ' .
                $endereco->getCep()
            )
        ];
    } catch (Exception $e) {
        Logger::logError("Erro ao carregar endereço: " . $e->getMessage());
        include_once 'components/footer.php';
        exit;
    }
    
}
