// Função para preencher o formulário de categoria para edição
function editCategoria(id, nome) {
    document.getElementById('id').value = id;
    document.getElementById('nome').value = nome;
}

// Função para preencher o formulário de cliente para edição
function editCliente(id, nome, email, telefone) {
    document.getElementById('id').value = id;
    document.getElementById('nome').value = nome;
    document.getElementById('email').value = email;
    document.getElementById('telefone').value = telefone;
}

// Função para preencher o formulário de pedido para edição
function editPedido(id, cliente_id, produto_id, quantidade) {
    document.getElementById('id').value = id;
    document.getElementById('cliente_id').value = cliente_id;
    document.getElementById('produto_id').value = produto_id;
    document.getElementById('quantidade').value = quantidade;
}

// Função para preencher o formulário de produto para edição
function editProduto(id, nome, descricao, preco, categoria_id, marca_id) {
    document.getElementById('id').value = id;
    document.getElementById('nome').value = nome;
    document.getElementById('descricao').value = descricao;
    document.getElementById('preco').value = preco;
    document.getElementById('categoria_id').value = categoria_id;
    document.getElementById('marca_id').value = marca_id;
}

// Função para preencher o formulário de usuário para edição
function editUsuario(id, nome, email, permissao) {
    document.getElementById('id').value = id;
    document.getElementById('nome').value = nome;
    document.getElementById('email').value = email;
    document.getElementById('senha').value = ''; // Deixe a senha em branco para não alterar
    document.getElementById('permissao').value = permissao;
}

// Função para preencher o formulário de marca para edição
function editMarca(id, nome) {
    document.getElementById('id').value = id;
    document.getElementById('nome').value = nome;
}
