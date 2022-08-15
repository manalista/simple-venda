<?php $titulo = "Tipos de Produtos"?>

#{botoes}
<div class="btn-group me-2">
    <button type="button" class="btn btn-sm btn-outline-secondary" id="bt-novo">Novo Tipo de Produtos</button>
</div>
#{/botoes}

<style>
    #novo-tipo-produto{
        display: none;
    }
</style>

<div class="table-responsive">
    <table class="table table-striped table-sm" id="tb-tipos-produtos">
        <thead>
            <tr>
                <th scope="col">#</th>
                <th scope="col">Descrição</th>
                <th scope="col">Imposto</th>
                <th scope="col" class="acoes">Ações</th>
            </tr>
        </thead>
        <tbody>

        </tbody>
    </table>
</div>

<div id="novo-tipo-produto">
    <form method="post" id="form-tipo-produto">
        <input type="hidden" name="tipoProdutoId" value="">
        <div class="mb-3">
            <label for="descricao" class="form-label">Descriçao</label>
            <input type="text" required class="form-control"
                    name="descricao"
                     id="descricao" placeholder="Descriçao do tipo de produtos">
        </div>
        <div class="mb-3">
            <label for="imposto" class="form-label">Imposto</label>
            <div class="mb-3 input-group">
                <input type="number" required
                    class="form-control" id="imposto"
                    name="imposto" 
                    min="0" step="0.01"
                    placeholder="Alícota de imposto para este tipo de produtos">
                <span class="input-group-text">%</span>
            </div>
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
    const urlLista = '/tipos-produtos/lista';
    const urlNovo = '/tipos-produtos/novo';
    const urlExcluir = '/tipos-produtos/excluir';
    const urlEditar = '/tipos-produtos/editar';

    const btNovo = document.getElementById('bt-novo');
    const divNovo = document.getElementById('novo-tipo-produto');
    const tabelaTipos = document.getElementById('tb-tipos-produtos');
    const tbody = tabelaTipos.querySelector("tbody");
    const btCancelar = document.getElementById('bt-cancelar');
    const btSalvar = document.getElementById('bt-salvar');
    const formulario = document.getElementById('form-tipo-produto');

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

    function criaLinha(tipoProduto, tbody){
        const tr = document.createElement('tr');
        tr.setAttribute("data-id", tipoProduto.id);
        const tdId = document.createElement('td');
        const tdDescricao = document.createElement('td');
        const tdImposto = document.createElement('td');
        templateAcoes = document.getElementById('td-acoes');
        const tdAcoes = document.importNode(templateAcoes.content, true);
        tdId.innerHTML = tipoProduto.id;
        tdDescricao.innerHTML = tipoProduto.descricao;
        valorImposto = (tipoProduto.valor_imposto*100).toFixed(2);
        tdImposto.innerHTML = `${valorImposto}%`;

        tr.appendChild(tdId)
        tr.appendChild(tdDescricao)
        tr.appendChild(tdImposto);
        tr.appendChild(tdAcoes);
        tbody.append(tr);
        addClickEvent(tr, '.bt-editar', function(){
            btNovo.style.display = 'none';
            formulario.action = urlEditar;
            formulario.method = 'PUT';
            tipoProdutoId = this.parentElement.parentElement.getAttribute('data-id');
            descricao = this.parentElement.parentElement.children[1].innerHTML;
            imposto = this.parentElement.parentElement.children[2].innerHTML.replace('%', '');
            formulario.tipoProdutoId.value = tipoProdutoId;
            formulario.descricao.value = descricao;
            formulario.imposto.value = imposto;
            tabelaTipos.style.display = 'none';
            divNovo.style.display = 'block';
            editando = true;
        });
        addClickEvent(tr, '.bt-excluir', function(){
            if(confirm("Deseja realmente excluir este elemento?")){
                formulario.method = 'DELETE';
                tipoProdutoId = this.parentElement.parentElement.getAttribute('data-id');
                sendData(urlExcluir, {id:tipoProdutoId}, function(dados){
                    showToast('Sucesso', 'Tipo de produto excluido com sucesso');
                }, 'DELETE');
                preencheTabela()
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
                id: formulario.tipoProdutoId.value,
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
        tabelaTipos.style.display = 'table';
        formulario.reset(); 
        btNovo.style.display = 'inline-block';
    });

    btNovo.addEventListener('click', () =>{
        formulario.action = urlNovo;
        formulario.tipoProdutoId.value = '';
        formulario.method = 'POST';
        btNovo.style.display = 'none';
        tabelaTipos.style.display = 'none';
        divNovo.style.display = 'block';
    });
    
    document.addEventListener('DOMContentLoaded', () =>{
       preencheTabela();
    });
</script>