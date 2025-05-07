<?php

function paginarArray(array $dados, int $itensPorPagina, int $paginaAtual = 1): array {
    $offset = ($paginaAtual - 1) * $itensPorPagina;
    $totalItens = count($dados);
    $totalPaginas = (int) ceil($totalItens / $itensPorPagina);
    $dadosPaginados = array_slice($dados, $offset, $itensPorPagina);

    return [
        'dados' => $dadosPaginados,
        'total_paginas' => $totalPaginas,
        'pagina_atual' => $paginaAtual
    ];
}
