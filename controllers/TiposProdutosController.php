<?php 

class TiposProdutosController extends Controller{

    private $tipoProduto;

    public function __construct() {
        $this->tipoProduto = new TipoProduto();
    }
    
    public function index() {
        $this->render();
    }

    public function listaJson(){
        $lista = $this->tipoProduto->lista();
        $this->json(['tiposProdutos'=> $lista]);
    }

    public function novo(){
        echo "Formulário de novo tipo de produto";
    }
}