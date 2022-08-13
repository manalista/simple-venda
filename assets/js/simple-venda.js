async function loadData(url, callback){
    fetch(url)
        .then(r => r.json())
        .then(dados => criaLinhas(dados, callback))
}

function criaLinhas(tiposProdutos, callbackLinha){
    const table = document.querySelector('table');
    const tbody = table.querySelector('tbody');
    for(tipo of tiposProdutos.tiposProdutos){
        callbackLinha(tipo, tbody);
    }
}

