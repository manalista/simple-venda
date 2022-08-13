<?php 

class Produto extends Model{

    private $table = "vendas.produtos";
    private $joins = [];

    public function lista($filtros = []){
        return $this
                    ->select('*')
                    ->from($this->table, 'p')
                    ->order('nome')
                    ->fetch();
    }
}