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

    th.acoes, td.acoes{
        text-align: right;
    }

    tr td.acoes a{
        opacity: 0.25;
        transition: opacity 0.5s;
    }

    tr:hover td.acoes a{
        opacity: 1;
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
    <form method="post" id="form-tipo-produto" action="/tipos-produtos/novo">
        <input type="hidden" name="tipo-produto-id" value="">
        <div class="mb-3">
            <label for="descricao" class="form-label">Descriçao</label>
            <input type="text" required class="form-control" id="descricao" placeholder="Descriçao do tipo de produtos">
        </div>
        <div class="mb-3">
            <label for="imposto" class="form-label">Imposto</label>
            <input type="number" required class="form-control" id="imposto" placeholder="Alícota de imposto para este tipo de produtos">
        </div>

        <div class="mb-3">
            <button type="button" class="btn btn-sm btn-outline-primary" id="bt-salvar">Salvar</button>
            <button type="button" class="btn btn-sm btn-outline-secondary" id="bt-cancelar">Cancelar</button>
        </div>
    </form>
</div>

<template id="td-acoes">
    <td class="acoes">
        <a href="#" class="btn btn-sm btn-outline-secondary" class="bt-editar">Editar</a>
        <a href="#" class="btn btn-sm btn-outline-danger" class="bt-excluir">Excluir</a>
    </td>
</template>

<script>
    const urlLista = '/tipos-produtos/lista';
    const btNovo = document.getElementById('bt-novo');
    const divNovo = document.getElementById('novo-tipo-produto');
    const tabelaTipos = document.getElementById('tb-tipos-produtos');
    const btCancelar = document.getElementById('bt-cancelar');
    const btSalvar = document.getElementById('bt-salvar');
    const formulario = document.getElementById('form-tipo-produto');

    function criaLinha(tipoProduto, tbody){
        const tr = document.createElement('tr');
        const tdId = document.createElement('td');
        const tdDescricao = document.createElement('td');
        const tdImposto = document.createElement('td');
        templateAcoes = document.getElementById('td-acoes');
        const tdAcoes = document.importNode(templateAcoes.content, true);
        tdId.innerHTML = tipo.id;
        tdDescricao.innerHTML = tipo.descricao;
        valorImposto = (tipo.valor_imposto*100).toFixed(2);
        tdImposto.innerHTML = `${valorImposto}%`;

        tr.appendChild(tdId)
        tr.appendChild(tdDescricao)
        tr.appendChild(tdImposto);
        tr.appendChild(tdAcoes);
        tbody.append(tr);
    }

    btSalvar.addEventListener('click', (e)=>{
        e.preventDefault();

    });

    btCancelar.addEventListener('click', () => {
        divNovo.style.display = 'none';
        tabelaTipos.style.display = 'table';
    });

    btNovo.addEventListener('click', () =>{
        tabelaTipos.style.display = 'none';
        divNovo.style.display = 'block';
    });
    
    document.addEventListener('DOMContentLoaded', () =>{
       loadData(urlLista, criaLinha);
    });
</script>