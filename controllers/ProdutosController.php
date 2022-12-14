<?php 

class ProdutosController extends Controller{

    private $produto;
    private $tipoProduto;
    private $filters;

    public function __construct() {
        $this->produto = new Produto();
        $this->tipoProduto = new TipoProduto();
        $this->filters = [
            'novo' => [
                'nome' => FILTER_SANITIZE_SPECIAL_CHARS
                , 'valor' =>  FILTER_VALIDATE_FLOAT
                , 'tipo_id' =>  FILTER_VALIDATE_INT
            ], 
            'editar' => [
                'id' => FILTER_VALIDATE_INT
                , 'nome' => FILTER_SANITIZE_SPECIAL_CHARS
                , 'valor' =>  FILTER_VALIDATE_FLOAT
                , 'tipo_id' =>  FILTER_VALIDATE_INT
            ], 
            'excluir' => [
                'id' => FILTER_VALIDATE_INT
            ], 
            'tipo_id' => [
                'id' => FILTER_VALIDATE_INT
            ]
        ];
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
        $dados = $this->filterPost($this->filters['novo']);
        $dados['valor'] = $dados['valor'];
        $record = $this->produto->inserir($dados);
        $this->json($record);
    }

    public function editar(){
        $dados = $this->filterPost($this->filters['editar']);
        $dados['valor'] = $dados['valor'];
        $record = $this->produto->atualizar($dados);
        $this->json($record);
    }

    public function excluir(){
        $dados = $this->filterPost($this->filters['excluir']);
        $record = $this->produto->excluir($dados);
        $this->json($record);
    }
}