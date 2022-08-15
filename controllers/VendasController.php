<?php 

class VendasController extends Controller{

    private $vendas;
    private $filters;

    public function __construct() {
        $this->vendas = new Venda();
        $this->filters = [
                'editar' => [
                    'id' => FILTER_VALIDATE_INT
                    //, 'descricao' => FILTER_SANITIZE_FULL_SPECIAL_CHARS
                    , 'descricao' => FILTER_SANITIZE_SPECIAL_CHARS
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
        $lista = $this->vendas->lista();
        $this->json($lista);
    }

    public function nova(){
        $record = $this->vendas->inserir(['valor_total' => 0, 'valor_total_impostos' => 0]);
        $this->json($record);
    }

    public function editar(){
        $dados = $this->filterPost($this->filters['editar']);
        $dados['valor_imposto'] = $dados['valor_imposto'] / 100;
        $record = $this->vendas->atualizar($dados);
        $this->json($record);
    }

    public function excluir(){
        $dados = $this->filterPost($this->filters['excluir']);
        $record = $this->vendas->excluir($dados);
        $this->json($record);
    }
}