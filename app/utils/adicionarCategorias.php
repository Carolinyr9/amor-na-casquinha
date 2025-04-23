<?php
use app\controller2\CategoriaProdutoController;
use app\utils\Logger;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_FILES['imagem']) && $_FILES['imagem']['error'] === UPLOAD_ERR_OK) {
        $nomeImagem = $_FILES['imagem']['name'];

        $nomeImagemSeguro = str_replace(' ', '_', $nomeImagem); 
        $nomeImagemUnico = uniqid() . '_' . $nomeImagemSeguro; 

        $diretorioDestino = $_SERVER['DOCUMENT_ROOT'] . '/amor-na-casquinha/app/images/';

        if (!is_dir($diretorioDestino)) {
            if (!mkdir($diretorioDestino, 0777, true)) {
                Logger::logError("Erro ao criar o diretório de upload.");
                $foto = null;
            }
        }
        if (move_uploaded_file($_FILES['imagem']['tmp_name'], $diretorioDestino . $nomeImagemUnico)) {
            $foto = $nomeImagemUnico; 
        } else {
            $foto = null;
            Logger::logError("Erro ao salvar a imagem.");
        }
    } else {
        $foto = null; 
    }

    $dados = [
        'nome'      => $_POST['nomeProAdd'] ?? '',
        'marca'     => $_POST['marcaProAdd'] ?? '',
        'descricao' => $_POST['descricaoProAdd'] ?? '',
        'fornecedor'=> $_POST['fornecedor'] ?? '',
        'foto'      => $foto,
    ];

    $categoriaController = new CategoriaProdutoController();
    $categoriaController->criarCategoria($dados);

    header("Location: editarCategorias.php");
    exit;

}
