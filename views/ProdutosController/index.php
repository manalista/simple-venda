#{titulo}Produtos#{/titulo}

#{botoes}
<div class="btn-group me-2">
    <button type="button" class="btn btn-sm btn-outline-secondary" id="bt-novo">Novo Produto</button>
</div>
#{/botoes}

<style>
    #novo-produto{
        display: none;
    }
</style>


<div class="table-responsive">
    <table class="table table-striped table-sm" id="tb-produtos">
        <thead>
            <tr>
                <th scope="col">#</th>
                <th scope="col">Nome</th>
                <th scope="col">Valor</th>
                <th scope="col">Tipo do produto</th>
                <th scope="col" class="acoes">Ações</th>
            </tr>
        </thead>
        <tbody>

        </tbody>
    </table>
</div>

<div id="novo-produto">
    <form method="post" id="form-produto">
        <input type="hidden" name="id" value="">
        <div class="mb-3">
            <label for="nome" class="form-label">Nome</label>
            <input type="text" required class="form-control"
                    name="nome"
                     id="nome" placeholder="Nome do produto">
        </div>

        <div class="mb-3">
            <label for="valor" class="form-label">Valor</label>
            <div class="input-group mb-3">
                <span class="input-group-text">R$</span>
                <input type="number" required
                    class="form-control" id="valor"
                    name="valor" 
                    min="0" step="0.01"
                    placeholder="Valor de venda">
            </div>
        </div>

        <div class="mb-3">
            <label for="tipo-produto">Tipo de produto</label>
            <select name="tipo_id" id="tipo-produto"  class="form-select" >
                <option value="">Selecione um tipo de produto</option>
            </select>
        </div>
        
        <div class="mb-3">
            <button type="button" class="btn btn-sm btn-outline-primary" id="bt-salvar">Salvar</button>
            <button type="button" class="btn btn-sm btn-outline-secondary" id="bt-cancelar">Cancelar</button>
        </div>
    </form>
</div>

<template id="td-acoes">
    <td class="acoes">
        <a href="#" class="btn btn-sm btn-outline-secondary bt-editar">Editar</a>
        <a href="#" class="btn btn-sm btn-outline-danger bt-excluir">Excluir</a>
    </td>
</template>

<script>
    const urlLista = '/produtos/lista';
    const urlListaTipos = '/tipos-produtos/lista';
    const urlNovo = '/produtos/novo';
    const urlExcluir = '/produtos/excluir';
    const urlEditar = '/produtos/editar';

    const btNovo = document.getElementById('bt-novo');
    const divNovo = document.getElementById('novo-produto');
    const tabelaProdutos = document.getElementById('tb-produtos');
    const tbody = tabelaProdutos.querySelector("tbody");
    const btCancelar = document.getElementById('bt-cancelar');
    const btSalvar = document.getElementById('bt-salvar');
    const formulario = document.getElementById('form-produto');
    const selectTipos = document.getElementById('tipo-produto');

    var editando = false;
    
    function addClickEvent(element, selector, callback){
        element.querySelector(selector).addEventListener('click', callback);
    }

    function preencheTabela(){
        loadData(urlLista).then(function(dados){
            tbody.innerHTML = '';
            dados.forEach(tipoProduto => {
                criaLinha(tipoProduto, tbody);
            });
        });
    }

    function criaOption(tipoProduto, select){
        var option = document.createElement('option');
        option.value = tipoProduto.id;
        option.text = tipoProduto.descricao;
        select.appendChild(option);
    }

    function criaLinha(produto, tbody){
        const tr = document.createElement('tr');
        tr.setAttribute("data-id", produto.id);
        tr.setAttribute("data-tipo-id", produto.tipo.id);
        const tdId = document.createElement('td');
        const tdNome = document.createElement('td');
        const tdvalor = document.createElement('td');
        const tdtipo = document.createElement('td');
        templateAcoes = document.getElementById('td-acoes');
        const tdAcoes = document.importNode(templateAcoes.content, true);
        tdId.innerHTML = produto.id;
        tdNome.innerHTML = produto.nome;
        valorvalor = produto.valor;

        tdvalor.innerHTML = `R$ ${valorvalor}`;
        tdtipo.innerHTML = produto.tipo.descricao;
        selectTipos.value = produto.tipo.id;
        tr.appendChild(tdId)
        tr.appendChild(tdNome)
        tr.appendChild(tdvalor);
        tr.appendChild(tdtipo);
        tr.appendChild(tdAcoes);
        tbody.append(tr);
        addClickEvent(tr, '.bt-editar', function(){
            btNovo.style.display = 'none';
            formulario.action = urlEditar;
            formulario.method = 'PUT';
            produtoId = this.parentElement.parentElement.getAttribute('data-id');
            nome = this.parentElement.parentElement.children[1].innerHTML;
            valor = this.parentElement.parentElement.children[2].innerHTML.replace('R$', '');
            console.log(`Valor ${valor}`);
            tipoProdutoId = this.parentElement.getAttribute('data-tipo-produto-id');
            formulario.id.value = produtoId;
            formulario.nome.value = nome;
            formulario.valor.value = Number.parseFloat(valor);
            selectTipos.value = tipoProdutoId;
            option = selectTipos.querySelector("option[value='"+produto.tipo.id+"']");
            option.selected = true;
            tabelaProdutos.style.display = 'none';
            divNovo.style.display = 'block';
            editando = true;
        });
        addClickEvent(tr, '.bt-excluir', function(){
            if(confirm("Deseja realmente excluir este elemento?")){
                formulario.method = 'DELETE';
                produtoId = this.parentElement.parentElement.getAttribute('data-id');
                sendData(urlExcluir, {id:produtoId}, function(dados){
                    showToast('Sucesso', 'Produto excluido com sucesso');
                }, 'DELETE');
                loadData(urlLista, 'produtos', criaLinha);
            }
        });

    }

    btSalvar.addEventListener('click', (e)=>{
        e.preventDefault();
        if(formulario.reportValidity()){
            btSalvar.disabled = true;
            actionForm = formulario.action;
            btSalvar.innerHTML = 'Salvando...';
            btSalvar.classList.add("pulsante");
            btCancelar.style.display = 'none';
            dados = {
                id: formulario.id.value,
                nome: formulario.nome.value,
                valor: formulario.valor.value,
                tipo_id: formulario.tipo_id.value
            };
            sendData(actionForm, dados, function(r){
                if(r.length > 0){
                    showToast('Sucesso', `Produto salvo com sucesso`);
                    btSalvar.disabled = false;
                    btSalvar.innerHTML = 'Salvar';
                    btSalvar.classList.remove("pulsante");
                    btCancelar.style.display = 'inline-block';
                    formulario.reset();
                    if(editando){
                        btCancelar.click();
                        editando = false;
                        preencheTabela();
                    }
                }
            }, formulario.getAttribute('method'));
        }
    });

    btCancelar.addEventListener('click', () => {
        divNovo.style.display = 'none';
        tabelaProdutos.style.display = 'table';
        formulario.reset(); 
        btNovo.style.display = 'inline-block';
    });

    btNovo.addEventListener('click', () =>{
        formulario.action = urlNovo;
        formulario.id.value = '';
        formulario.method = 'POST';
        btNovo.style.display = 'none';
        tabelaProdutos.style.display = 'none';
        divNovo.style.display = 'block';
    });
    
    document.addEventListener('DOMContentLoaded', () =>{
        loadData(urlListaTipos).then(function(dados){
            dados.forEach(tipoProduto => {
                criaOption(tipoProduto, selectTipos);
            });
        });
        preencheTabela();
    });
</script>