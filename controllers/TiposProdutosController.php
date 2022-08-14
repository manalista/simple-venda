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
        $filters = ['descricao' => FILTER_SANITIZE_ENCODED
        , 'valor_imposto' =>  FILTER_VALIDATE_FLOAT];
        $dados = $this->filterPost($filters);
        $record = $this->tipoProduto->inserir($dados);
        $this->json($record);
    }
}