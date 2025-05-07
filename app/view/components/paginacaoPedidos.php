<?php if ($totalPaginas > 1): ?>
<nav aria-label="Navegação de página">
    <ul class="pagination justify-content-center mt-4">
        <li class="page-item <?= $paginaAtual == 1 ? 'disabled' : ''; ?>">
            <a class="page-link" href="?pagina=<?= $paginaAtual - 1; ?>">Anterior</a>
        </li>
        <?php for ($i = 1; $i <= $totalPaginas; $i++): ?>
            <li class="page-item <?= $i == $paginaAtual ? 'active' : ''; ?>">
                <a class="page-link" href="?pagina=<?= $i; ?>"><?= $i; ?></a>
            </li>
        <?php endfor; ?>
        <li class="page-item <?= $paginaAtual == $totalPaginas ? 'disabled' : ''; ?>">
            <a class="page-link" href="?pagina=<?= $paginaAtual + 1; ?>">Próxima</a>
        </li>
    </ul>
</nav>
<?php endif; ?>
