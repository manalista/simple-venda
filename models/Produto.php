<?php 

class Produto extends Model{

    private $table = "vendas.produtos";

    public function lista($filtros = []){
        
        return $this
                    ->select('*')
                    ->from($this->table, 'p')
                    ->order('nome')
                    ->fetch();
    }

    public function inserir($dados){
        $dadosSalvos = $this->insert($this->table, $dados);
        return $dadosSalvos;
    }

    public function atualizar($dados){
        $dadosSalvos = $this->update($this->table, $dados);
        return $dadosSalvos;
    }

    public function excluir($dados){
        $dadosExlcuidos = $this->delete($this->table, $dados);
        return $dadosExlcuidos;
    }

    public function getProduto($id){
        $tipoProduto = new TipoProduto();
        $produto = $this->select('*')
                        ->from($this->table, 'p')
                        ->where('id', '=', $id)
                        ->fetch()[0];
        $oTipoProduto = $tipoProduto->getTipoProduto($produto->id);
        $produto->tipo = $oTipoProduto;
        return $produto;
    }
}