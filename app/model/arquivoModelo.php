<?php
class ArquivoModelo {
    public function colocarNomeAcaoExemploModel(){
        try {
            $stmt = $this->conn->prepare("CALL Exemplo(?)");
            $stmt->bindParam(1, $idProduto);
            $stmt->execute();

            //se for uma de consulta
            //return objeto -> sempre colocar em formato de classe e retornar a instancia da classe ou o atributo desejado
            // se for uma de alteracao
           // return boleano true
        } catch (PDOException $e) {
            error_log("Erro ao editar produto: " . $e->getMessage());
            return false;
        }
    }

    public function colocarNomeAcaoExemploController($exemplo1, $exemplo2){
        try {
            if (!is_numeric($exemplo1) || !is_array($exemplo2)) { // fazer a verificação das variaveis e só a partir disso chamar a função
                return false;
            }

            // Aqui chamaria a função desejada, ex:
            // return $this->editarProduto($id, ...$dados);

            return true; // Exemplo de retorno para alteração
        } catch (Exception $e) {
            error_log("Erro ao validar e executar ação: " . $e->getMessage());
            return false;
        }
    }

    /*
    Padrões de nomenclatura para métodos:
    
    - editar + NomeClasse      → Para atualizações de registros (ex: editarProduto)
    - criar + NomeClasse       → Para inserções de novos registros (ex: criarProduto)
    - listar + NomeClasse      → Para consultas e listagens (ex: listarVariacoesProduto)
    - testar + NomeClasse      → Para verificações e validações (ex: testarConexaoBanco)
    - remover + NomeClasse     → Para exclusões de registros (ex: removerProduto)
    */

    /*
    Nomes refatorados procedures:
    AtribuirPedidoEntregador -> Atribuir_Pedido_Entregador  
    AtualizarCliente -> Atualizar_Cliente  
    CancelarPedido -> Cancelar_Pedido  
    CriarPedido -> Criar_Pedido  
    CriarCliente -> Criar_Cliente  
    CriarEstoque -> Criar_Estoque  
    CriarFornecedor -> Criar_Fornecedor  
    CriarFuncionario -> Criar_Funcionario  
    CriarOcorrenciaEstoque -> Criar_Ocorrencia_Estoque  
    CriarPedidoDtPagamento -> Criar_Pedido_DataPagamento  
    CriarPedidoProduto -> Criar_Produto_Pedido  
    CriarProduto -> Criar_Produto  
    CriarVariacao -> Criar_Variacao  
    DeletarClientePorEmail -> Deletar_Cliente_PorEmail  
    DesativarCliente -> Desativar_Cliente  
    DesativarEstoqueProdutoPorId -> Desativar_Estoque_Produto_PorId  
    DesativarFornecedorPorEmail -> Desativar_Fornecedor_PorEmail  
    DesativarFuncionarioPorEmail -> Desativar_Funcionario_PorEmail  
    DesativarProdutoPorID -> Desativar_Produto_PorId  
    DesativarVariacaoPorId -> Desativar_Variacao_PorId  
    EditarCliente -> Editar_Cliente  
    EditarEmpresa -> Editar_Empresa  
    EditarFornecedorPorEmail -> Editar_Fornecedor_PorEmail  
    EditarFuncionarioPorEmail -> Editar_Funcionario_PorEmail  
    EditarPedidoStatus -> Editar_Status_Pedido  
    EditarProdutoEstoque -> Editar_Estoque_Produto  
    EditarProdutoPorId -> Editar_Produto_PorId  
    EditarSenha -> Editar_Senha  
    EditarVariacaoPorID -> Editar_Variacao_PorId  
    FN_GetClienteId -> Selecionar_Cliente_PorId  
    ListarCep -> Listar_CEPs  
    ListarClientePorEmail -> Listar_Cliente_PorEmail  
    ListarEmpresa -> Listar_Empresas  
    ListarEnderecoPorID -> Listar_Endereco_PorId  
    ListarEntregadorPorID -> Listar_Entregador_PorId  
    ListarEntregadores -> Listar_Entregadores  
    ListarEstoque -> Listar_Estoque  
    ListarEstoquePorProduto -> Listar_Estoque_PorProduto  
    ListarFornecedorPorEmail -> Listar_Fornecedor_PorEmail  
    ListarFornecedorPorID -> Listar_Fornecedor_PorId  
    ListarFornecedores -> Listar_Fornecedores  
    ListarFuncionarioPorEmail -> Listar_Funcionario_PorEmail  
    ListarFuncionarios -> Listar_Funcionarios  
    ListarInformacoesPedido -> Listar_Informacoes_Pedido  
    ListarPedidoPorCliente -> Listar_Pedido_PorCliente  
    ListarPedidoPorID -> Listar_Pedido_PorId  
    ListarPedidoPorStatus -> Listar_Pedido_PorStatus  
    ListarPedidos -> Listar_Pedidos  
    ListarPedidosAtribuidosEntregador -> Listar_Pedidos_Atribuidos_Entregador  
    ListarPedidosEmAndamento -> Listar_Pedidos_EmAndamento  
    ListarPedidosPorEmailEntregador -> Listar_Pedidos_PorEmail_Entregador  
    ListarPedidosPorEntregador -> Listar_Pedidos_PorEntregador  
    ListarProdutoAtivo -> Listar_Produtos_Ativos  
    ListarProdutoPorID -> Listar_Produto_PorId  
    ListarProdutos -> Listar_Produtos  
    ListarProdutosPedido -> Listar_Produtos_Pedido  
    ListarResumoVendas -> Listar_Resumo_Vendas  
    ListarVariacao -> Listar_Variacoes  
    ListarVariacaoAtivaPorId -> Listar_Variacao_Ativa_PorId  
    ListarVariacaoPorID -> Listar_Variacao_PorId  
    ListarVariacaoPorTipo -> Listar_Variacao_PorTipo  
    ListarVariacaoPorCodigoProduto -> Listar_Variacao_PorCodigoProduto  
    ListarVariacaoPorProduto -> Listar_Variacao_PorProduto  
    Login -> Realizar_Login  
    SalvarItensPedido -> Salvar_Itens_Pedido  
    SelecionarProdutoEstoquePorID -> Selecionar_Produto_Estoque_PorId  
    SelecionarProdutoPorID -> Selecionar_Produto_PorId  
    VerificarQuantidadeMinima -> Verificar_Quantidade_Minima  

    */
}
?>