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
                ], 
                'novo-item' =>[
                    'venda_id' => FILTER_VALIDATE_INT
                    , 'quantidade' => FILTER_VALIDATE_INT
                    , 'produto_id' => FILTER_VALIDATE_INT
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

    public function novoItem(){
        $dados = $this->filterPost($this->filters['novo-item']);
        
        $produto = new Produto();
        $venda = new Venda();
        $item = new ItemVenda();

        $oProduto = $produto->getProduto($dados['produto_id']);
        
        $oVenda = $venda->getVenda($dados['venda_id']);
        $totalVenda = $oVenda->valor_total;
        $totalImpostos = $oVenda->valor_total_impostos;

        $valorImposto = $oProduto->valor * $oProduto->tipo->valor_imposto;
        $venda->atualizar([
            'valor_total_impostos' => $totalImpostos + $valorImposto
            , 'valor_total' => $totalVenda + $oProduto->valor
            , 'id' => $dados['venda_id']
        ]);

        $dados['valor_unitario'] = $oProduto->valor;
        $dados['total'] = $oProduto->valor * $dados['quantidade'];
        $dados['total_imposto'] = $dados['total'] * $oProduto->tipo->valor_imposto;
        $record = $item->inserir($dados);
        $this->json($record);
    }

    public function listaJsonItens(){
        $dados = $this->filterGet(['venda_id' => FILTER_VALIDATE_INT]);
        $item = new ItemVenda();
        $lista = $item->listaVenda($dados['venda_id']);
        $produto = new Produto();
        foreach($lista as $item){
            $oProduto = $produto->getProduto($item->produto_id);
            $item->produto = $oProduto;
        }
        $this->json($lista);
    }

    public function show(){
        $dados = $this->filterGet(['venda_id' => FILTER_VALIDATE_INT]);
        $venda = $this->vendas->getVenda($dados['venda_id']);
        $this->json($venda);
    }

    public function editar(){
        $dados = $this->filterPost($this->filters['editar']);
        $dados['valor_imposto'] = $dados['valor_imposto'];
        $record = $this->vendas->atualizar($dados);
        $this->json($record);
    }

    public function excluir(){
        $dados = $this->filterPost($this->filters['excluir']);
        $record = $this->vendas->excluir($dados);
        $this->json($record);
    }
}