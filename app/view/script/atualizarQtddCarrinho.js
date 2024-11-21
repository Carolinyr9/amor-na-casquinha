function updateProdutoQuantidade(produtoId, quantidade) {
    // Enviar os dados para o servidor
    fetch('carrinho.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({ id: produtoId, quantidade: quantidade })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            console.log('Quantidade atualizada com sucesso!');
            // Opcional: Atualize o total no frontend
            if (data.novoTotal) {
                document.querySelector('.d-flex.flex-row.justify-content-between.w-75.my-3 p').innerText = `R$ ${data.novoTotal}`;
                document.querySelector('#totalValue').innerText = `R$ ${data.novoTotal}`;
            }
        } else {
            console.error('Erro ao atualizar a quantidade:', data.error);
        }
    })
    .catch(error => console.error('Erro na requisição:', error));
}