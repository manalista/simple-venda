<?php $titulo = "Tipos de Produtos"?>
<div class="table-responsive">
    <table class="table table-striped table-sm" id="tb-tipos-produtos">
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

<div id="form-novo-tipo-produto">
    <div class="mb-3">
        <label for="descricao" class="form-label">Descriçao</label>
        <input type="text" required class="form-control" id="descricao" placeholder="Descriçao do tipo de produtos">
    </div>
    <div class="mb-3">
        <label for="imposto" class="form-label">Imposto</label>
        <input type="text" required class="form-control" id="imposto" placeholder="Alícota de imposto para este tipo de produtos">
    </div>

</div>

<script>
    const urlLista = '/tipos-produtos/lista';

    function criaLinha(tipoProduto, tbody){
        const tr = document.createElement('tr');
        const tdId = document.createElement('td');
        const tdDescricao = document.createElement('td');
        const tdImposto = document.createElement('td');
        tdId.innerHTML = tipo.id;
        tdDescricao.innerHTML = tipo.descricao;
        valorImposto = (tipo.valor_imposto*100).toFixed(2);
        tdImposto.innerHTML = `${valorImposto}%`;
        tr.appendChild(tdId)
        tr.appendChild(tdDescricao)
        tr.appendChild(tdImposto);
        tbody.append(tr);
    }

    document.addEventListener('DOMContentLoaded', function(){
       loadData(urlLista, criaLinha);
    });
</script>