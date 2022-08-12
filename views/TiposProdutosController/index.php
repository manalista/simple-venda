<?php $titulo = "Tipos de Produtos"?>
<div class="table-responsive">
    <table class="table table-striped table-sm">
        <thead>
            <tr>
                <th scope="col">#</th>
                <th scope="col">Descrição</th>
                <th scope="col">Imposto</th>
            </tr>
        </thead>
        <tbody>

        </tbody>
    </table>
</div>

<script>
    const search = document.querySelector('input');
    const urlLista = '/tipos-produtos/lista';

    async function loadData(){
        fetch(urlLista)
            .then(r => r.json())
            .then(dados => criaLinhas(dados))
    }

    function criaLinhas(tiposProdutos){
        const table = document.querySelector('table');
        const tbody = table.querySelector('tbody');
        for(tipo of tiposProdutos.tiposProdutos){
            const tr = document.createElement('tr');
            const tdId = document.createElement('td');
            const tdDescricao = document.createElement('td');
            const tdImposto = document.createElement('td');
            tdId.innerHTML = tipo.id;
            tdDescricao.innerHTML = tipo.descricao;
            tdImposto.innerHTML = `${tipo.valor_imposto*100}%`;
            tr.appendChild(tdId)
            tr.appendChild(tdDescricao)
            tr.appendChild(tdImposto);
            tbody.append(tr);
        }
    }

    document.addEventListener('DOMContentLoaded', function(){
       loadData();
    });
</script>