<?php 

class ProdutosController extends Controller{

    private $produto;
    private $tipoProduto;

    public function __construct() {
        $this->produto = new Produto();
        $this->tipoProduto = new TipoProduto();
    }
    
    public function index() {
        $this->render();
    }

    public function listaJson(){
        $lista = $this->produto->lista();
        $listaTipos = $this->tipoProduto->lista();
        
        foreach($lista as $produto){
            $produto->tipo = array_values(array_filter($listaTipos, function($tipo) use ($produto){
                return $tipo->id == $produto->tipo_id;
            }))[0];
        }
        $this->json($lista);
    }

    public function novo(){
        echo "Formulário de novo produto";
    }
}