<?php 

class ProdutosController extends Controller{

    private $produto;

    public function __construct() {
        $this->produto = new Produto();
    }
    
    public function index() {
        $this->produto->lista();
        $this->render(['nome'=> 'Marcos']);
    }

    public function novo(){
        echo "Formulário de novo produto";
    }
}