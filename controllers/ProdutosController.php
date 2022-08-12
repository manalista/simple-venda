<?php 

class ProdutosController extends Controller{

    private $produto;

    public function __construct() {
        $this->produto = new Produto();
    }
    
    public function index() {
        $this->render();
    }

    public function listaJson(){
        $lista = $this->produto->lista();
        $this->json(['produtos'=> $lista]);
    }

    public function novo(){
        echo "Formulário de novo produto";
    }
}