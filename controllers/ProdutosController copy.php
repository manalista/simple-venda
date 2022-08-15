<?php 

class VendasController extends Controller{

    private $venda;
    private $produto;
    private $filters;

    public function __construct() {
        $this->produto = new Produto();
        $this->venda = new Venda();
        $this->filters = [
            'novo' => [
                'data_hora_inicio' => FILTER_SANITIZE_SPECIAL_CHARS
            ], 
            'editar' => [
                'id' => FILTER_VALIDATE_INT
                , 'data_hora_inicio' => FILTER_SANITIZE_SPECIAL_CHARS
                , 'data_hora_fim' =>  FILTER_VALIDATE_FLOAT
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
        $lista = $this->venda->lista();
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
        $record = $this->venda->inserir($dados);
        $this->json($record);
    }

    public function editar(){
        $dados = $this->filterPost($this->filters['editar']);
        $dados['valor'] = $dados['valor'];
        $record = $this->venda->atualizar($dados);
        $this->json($record);
    }

    public function excluir(){
        $dados = $this->filterPost($this->filters['excluir']);
        $record = $this->venda->excluir($dados);
        $this->json($record);
    }
}