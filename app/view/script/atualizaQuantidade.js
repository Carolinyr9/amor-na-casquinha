document.querySelectorAll('.carrinho-select').forEach(container => {
    const mais = container.querySelector('.mais-quantidade');
    const menos = container.querySelector('.menos-quantidade');
    const quantidade = container.querySelector('.quantidade');
    const form = container.closest('form');
    const inputQuantidade = form.querySelector('input[name="quantidade"]');

    mais.addEventListener('click', () => {
        let valor = parseInt(quantidade.innerHTML);
        valor++;
        quantidade.innerHTML = valor;
        inputQuantidade.value = valor;
        form.submit(); // ⬅ ENVIA O FORMULÁRIO
    });

    menos.addEventListener('click', () => {
        let valor = parseInt(quantidade.innerHTML);
        if (valor > 1) { // evitar zero
            valor--;
            quantidade.innerHTML = valor;
            inputQuantidade.value = valor;
            form.submit(); // ⬅ ENVIA O FORMULÁRIO
        }
    });
});
