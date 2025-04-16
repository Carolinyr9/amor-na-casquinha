<?php

?>
<div class="card categ d-flex align-items-center rounded-4 h-auto border-0 mt-3">
    <picture>
        <img src="../images/<?= htmlspecialchars($categoria->getFoto()) ?>" alt="<?= htmlspecialchars($categoria->getNome()) ?>" class="imagem">
    </picture>
    <div class="d-flex align-items-center flex-column c2">
        <h4 class="text-center m-auto"><?= htmlspecialchars($categoria->getNome()) ?></h4>
        <button class="border-0 rounded-4 fw-bold m-1">
            <a class="text-decoration-none text-body" href="sabores.php?categoria=<?= htmlspecialchars($categoria->getId()) ?>">ver</a>
        </button>
    </div>
</div>
