<?php 

class TiposProdutosController extends Controller{

    private $tipoProduto;
    private $filters;

    public function __construct() {
        $this->tipoProduto = new TipoProduto();
        $this->filters = [
                'novo' => [
                    'descricao' => FILTER_SANITIZE_FULL_SPECIAL_CHARS
                    , 'valor_imposto' =>  FILTER_VALIDATE_FLOAT
                ], 
                'editar' => [
                    'id' => FILTER_VALIDATE_INT
                    , 'descricao' => FILTER_SANITIZE_FULL_SPECIAL_CHARS
                    , 'valor_imposto' =>  FILTER_VALIDATE_FLOAT
                ], 
                'excluir' => [
                    'id' => FILTER_VALIDATE_INT
                ]
            ];
        
    }
    
    public function index() {
        $this->render();
    }

    public function listaJson(){
        $lista = $this->tipoProduto->lista();
        $this->json($lista);
    }

    public function novo(){
        $dados = $this->filterPost($this->filters['novo']);
        $dados['valor_imposto'] = $dados['valor_imposto'] / 100;
        $record = $this->tipoProduto->inserir($dados);
        $this->json($record);
    }

    public function editar(){
        $dados = $this->filterPost($this->filters['editar']);
        $dados['valor_imposto'] = $dados['valor_imposto'] / 100;
        $record = $this->tipoProduto->atualizar($dados);
        $this->json($record);
    }

    public function excluir(){
        $dados = $this->filterPost($this->filters['excluir']);
        $record = $this->tipoProduto->excluir($dados);
        $this->json($record);
    }
}