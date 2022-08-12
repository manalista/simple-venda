<?php $titulo = "Produtos";
var_dump($search);
?>

<div class="table-responsive">
    <table class="table table-striped table-sm">
        <thead>
            <tr>
                <th scope="col">#</th>
                <th scope="col">Nome</th>
                <th scope="col">Descrição</th>
                <th scope="col">Valor</th>
                <th scope="col">Tipo do produto</th>
            </tr>
        </thead>
        <tbody>

        </tbody>
    </table>
</div>

<script>
    const search = document.querySelector('input');
    document.addEventListener('DOMContentLoaded', function(){
        var table = document.querySelector('table');
        var tbody = table.querySelector('tbody');
        var tr = document.createElement('tr');
        var td = document.createElement('td');
        td.innerHTML = '<a href="/produtos/novo">Novo</a>';
        tr.appendChild(td);
        tbody.appendChild(tr);
    });
</script>