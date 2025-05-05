document.querySelectorAll('.carrinho-select').forEach(container => {
    const mais = container.querySelector('.mais-quantidade');
    const menos = container.querySelector('.menos-quantidade');
    const quantidade = container.querySelector('.quantidade');
    const inputQuantidade = container.closest('form').querySelector('input[name="quantidade"]');

    mais.addEventListener('click', () => {
        let valor = parseInt(quantidade.innerHTML);
        valor++;
        quantidade.innerHTML = valor;
        inputQuantidade.value = valor;
    });

    menos.addEventListener('click', () => {
        let valor = parseInt(quantidade.innerHTML);
        if (valor > 0) {
            valor--;
            quantidade.innerHTML = valor;
            inputQuantidade.value = valor;
        }
    });
});
