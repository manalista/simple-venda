<?php $titulo = "Vendas"?>

#{botoes}
<div class="btn-group me-2">
    <button type="button" class="btn btn-sm btn-outline-secondary" id="bt-novo">Nova venda</button>
</div>
#{/botoes}

<style>
    #nova-venda{
        display: none;
    }
</style>

<div class="table-responsive">
    <table class="table table-striped table-sm" id="tb-vendas">
        <thead>
            <tr>
                <th scope="col">#</th>
                <th scope="col">Data</th>
                <th scope="col">Total</th>
                <th scope="col">Impostos</th>
                <th scope="col" class="acoes">Ações</th>
            </tr>
        </thead>
        <tbody>

        </tbody>
    </table>
</div>

<div id="nova-venda">
    <form method="post" id="form-venda">
        <input type="hidden" name="vendaId" value="">
        <div class="mb-3">
            <label for="descricao" class="form-label">Data de Inicio</label>
            <input type="text" required class="form-control"
                    name="data"
                    disabled
                    id="descricao" placeholder="Data da venda">
        </div>
        <div class="mb-3">
            <label for="imposto" class="form-label">Total</label>
            <div class="mb-3 input-group">
            <span class="input-group-text">R$</span>
                <input type="number" required
                    disabled
                    class="form-control" id="imposto"
                    name="valor" placeholder="Valor total"
                    placeholder="Total de impostos">
            </div>
        </div>
        <div class="mb-3">
            <label for="imposto" class="form-label">Imposto</label>
            <div class="mb-3 input-group">
            <span class="input-group-text">R$</span>
                <input type="number" required
                    disabled
                    class="form-control" id="imposto"
                    name="imposto" 
                    placeholder="Total de impostos">
            </div>
        </div>

        <div class="mb-3" id="texto-salvar-venda">
            <div class="alert alert-primary" role="alert">
            Salve a venda para poder adicionar produtos.
            </div>
        </div>

        <div class="mb-3">
            <button type="button" class="btn btn-sm btn-outline-primary" id="bt-salvar">Salvar</button>
            <button type="button" class="btn btn-sm btn-outline-secondary" id="bt-cancelar">Cancelar</button>
        </div>

        <div id="add-item-venda mb-3" class="d-none">
            <div class="row">
                <div class="col-md-4">
                    <label for="descricao" class="form-label">Produto</label>
                    <select class="form-control" name="produto" id="produtos">
                        <option value="">Selecione um produto</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <label for="quantidade" class="form-label">Quantidade</label>
                    <input type="number" required
                        class="form-control" id="quantidade"
                        disabled
                        min="1"
                        name="quantidade" placeholder="Quantidade">
                </div>
                <div class="col-md-3">
                    <label for="valor" class="form-label">Valor Unitário</label>
                    <div class="input-group">
                        <span class="input-group-text">R$</span>
                        <input type="number" required
                            class="form-control" id="valor" disabled
                            name="valor" placeholder="Valor">
                    </div>
                </div>

                <div class="col-md-2">
                    <label for="valor" class="form-label">Valor</label>
                    <div class="input-group">
                        <span class="input-group-text">R$</span>
                        <input type="number" required
                            class="form-control" id="valorTotal" disabled
                            name="valor" placeholder="Valor">
                    </div>
                </div>
                
                <div class="col-md-1">
                    <label for="bt-add-item" class="form-label">&nbsp;</label>
                    <button type="button" id="bt-add-item" disabled
                            class="form-control btn btn-outline-primary">+</button>
                </div>

            </div>
        </div>

        <div id="itens-venda" class="mb-3">
            <table class="table table-striped table-sm d-none"id="tb-itens-venda">
                <thead>
                    <tr>
                        <th>Quantidade</th>
                        <th>Produto</th>
                        <th>Valor unitário</th>
                        <th>Total item</th>
                        <th>Imposto</th>
                        <th>Valor Final</th>
                        <th class="acoes">Ações</th>
                    </tr>
                </thead>

                <tbody>

                </tbody>
            </table>
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
    const urlLista = '/vendas/lista';
    const urlNovo = '/vendas/novo';
    const urlExcluir = '/vendas/excluir';
    const urlEditar = '/vendas/editar';
    const urlListaProdutos = '/produtos/lista';

    const btNovo = document.getElementById('bt-novo');
    const divNovo = document.getElementById('nova-venda');
    const tabelaVendas = document.getElementById('tb-vendas');
    const tbody = tabelaVendas.querySelector("tbody");
    const btCancelar = document.getElementById('bt-cancelar');
    const btSalvar = document.getElementById('bt-salvar');
    const formulario = document.getElementById('form-venda');
    const selectProdutos = document.getElementById('produtos');
    const inputQuantidade = document.getElementById('quantidade');

    var editando = false;
    
    function addClickEvent(element, selector, callback){
        element.querySelector(selector).addEventListener('click', callback);
    }

    function preencheTabela(){
        loadData(urlLista).then(function(dados){
            tbody.innerHTML = '';
            dados.forEach(produto => {
                criaLinha(produto, tbody);
            });
        });
    }

    function criaOption(produto, select){
        var option = document.createElement('option');
        option.value = produto.id;
        option.text = produto.nome;
        option.setAttribute('data-valor', produto.valor);
        select.appendChild(option);
    }

    function criaLinha(produto, tbody){
        const tr = document.createElement('tr');
        tr.setAttribute("data-id", produto.id);
        const tdId = document.createElement('td');
        const tdData = document.createElement('td');
        const tdValorTotal = document.createElement('td');
        const tdValorImpostos = document.createElement('td');
        
        templateAcoes = document.getElementById('td-acoes');
        const tdAcoes = document.importNode(templateAcoes.content, true);
        valorImposto = (produto.valor_total_impostos*100).toFixed(2);
        valorTotal = (produto.valor_total*100).toFixed(2);

        tdId.innerHTML = produto.id;
        tdData.innerHTML = produto.data;
        tdValorTotal.innerHTML = `R$ ${valorTotal}`;
        tdValorImpostos.innerHTML = `R$ ${valorImposto}`;

        tr.appendChild(tdId);
        tr.appendChild(tdData);
        tr.appendChild(tdValorTotal);
        tr.appendChild(tdValorImpostos);
        tr.appendChild(tdAcoes);
        tbody.append(tr);
        addClickEvent(tr, '.bt-editar', function(){
            btNovo.style.display = 'none';
            formulario.action = urlEditar;
            formulario.method = 'PUT';
            vendaId = this.parentElement.parentElement.getAttribute('data-id');
            data = this.parentElement.parentElement.children[1].innerHTML;
            total = this.parentElement.parentElement.children[2].innerHTML.replace('R$ ', '');
            impostos = this.parentElement.parentElement.children[3].innerHTML.replace('R$ ', '');

            formulario.data.value = data;
            formulario.total.value = Number.parseFloat(total);
            formulario.imposto.value = Number.parseFloat(impostos);
            tabelaVendas.style.display = 'none';
            divNovo.style.display = 'block';
            editando = true;
        });
        addClickEvent(tr, '.bt-excluir', function(){
            if(confirm("Deseja realmente excluir este elemento?")){
                formulario.method = 'DELETE';
                vendaId = this.parentElement.parentElement.getAttribute('data-id');
                sendData(urlExcluir, {id:vendaId}, function(dados){
                    showToast('Sucesso', 'Tipo de produto excluido com sucesso');
                }, 'DELETE');
                preencheTabela()
            }
        });

    }

    selectProdutos.addEventListener('change', function(){
        const produtoId = this.value;
        console.log(produtoId);
        const selected = this.querySelector('option[value="'+produtoId+'"]');
        const valor = selected.getAttribute('data-valor');
        const inputValor = document.getElementById('valor');
        const inputQuantidade = document.getElementById('quantidade');        
        const inputTotal = document.getElementById('valorTotal');
        const btAddItem = document.getElementById('bt-add-item');
        if(produtoId){
            inputQuantidade.disabled = false;
            btAddItem.disabled = false;            
            inputQuantidade.value = 1;
            inputValor.value = valor;
            inputTotal.value = valor;
        }else{
            inputQuantidade.disabled = true;
            btAddItem.disabled = true;
            inputQuantidade.value = 0;
            inputValor.value = 0;
            inputTotal.value = 0;
        }
    });

    inputQuantidade.addEventListener('change', (e)=>{
        const valor = document.getElementById('valor').value;
        const quantidade = document.getElementById('quantidade').value;
        const valorTotal = document.getElementById('valorTotal');
        valorTotal.value = (valor*quantidade).toFixed(2);
    });

    btSalvar.addEventListener('click', (e)=>{
        e.preventDefault();
        if(formulario.reportValidity()){
            btSalvar.disabled = true;
            actionForm = formulario.action;
            btSalvar.innerHTML = 'Salvando...';
            btSalvar.classList.add("pulsante");
            btCancelar.style.display = 'none';
            dados = {
                id: formulario.vendaId.value,
                descricao: formulario.descricao.value,
                valor_imposto: formulario.imposto.value
            };
            sendData(actionForm, dados, function(r){
                if(r.length > 0){
                    showToast('Sucesso', `Tipo de produto salvo com sucesso`);
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
        tabelaVendas.style.display = 'table';
        formulario.reset(); 
        btNovo.style.display = 'inline-block';
    });

    btNovo.addEventListener('click', () =>{
        formulario.action = urlNovo;
        formulario.vendaId.value = '';
        formulario.method = 'POST';
        btNovo.style.display = 'none';
        tabelaVendas.style.display = 'none';
        divNovo.style.display = 'block';
    });
    
    document.addEventListener('DOMContentLoaded', () =>{
        loadData(urlListaProdutos).then(function(dados){
            dados.forEach(produto => {
                criaOption(produto, selectProdutos);
            });
        });
        preencheTabela();
    });
</script>