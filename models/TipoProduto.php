<?php 

class TipoProduto extends Model{

    private $table = "vendas.tipos_produtos";
    private $joins = [];

    public function lista($filtros = []){
        return $this
                    ->select('*')
                    ->from($this->table, 'tp')
                    ->order('descricao')
                    ->fetch();
    }
    
    public function novo($dados){
        return $this->insert($this->table, $dados);
    }
}