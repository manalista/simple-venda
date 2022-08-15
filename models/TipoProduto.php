<?php 

class TipoProduto extends Model{

    private $table = "vendas.tipos_produtos";

    public function lista($filtros = []){
        return $this
                    ->select('*')
                    ->from($this->table, 'tp')
                    ->order('descricao')
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

    public function getTipoProduto($id){
        $tipoProduto = $this->select('*')
                        ->from($this->table, 'tp')
                        ->where('id', '=', $id)
                        ->fetch()[0];
        return $tipoProduto;
    }
}