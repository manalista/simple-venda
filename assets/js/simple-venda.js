async function loadData(url){
    r = await fetch(url);
    dados = await r.json();
    return dados; 
}

function criaElementos(dados, parent, callbackElemento){
    parent.innerHTML = '';
    for(objeto of dados){
        callbackElemento(objeto, parent);
    }
}

async function sendData(url, data, callback, method = 'POST'){
    const formData  = new FormData();
    console.log(method)
    for(const name in data) {
      formData.append(name, data[name]);
    }
    
    formData.append('_method', method);
    options = {
      method: 'POST'
      , body: formData
    }

    const response = await fetch(url, options);
    const result = await response.json();
    callback(result);
  }

  function showToast(titulo, conteudo){
    const templateToast = document.getElementById('template-toast');
    const toast = document.importNode(templateToast.content, true);
    const toastTitulo = toast.querySelector('.toast-header strong');
    const toastConteudo = toast.querySelector('.toast-body');
    const toastContainer = document.querySelector('.toast-container');

    toastTitulo.innerHTML = titulo;
    toastConteudo.innerHTML = conteudo;

    toastContainer.appendChild(toast);
    const toastFinal = toastContainer.querySelector('.toast');
    new bootstrap.Toast(toastFinal).show();
}