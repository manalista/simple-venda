<?php 

class ItemVenda extends Model{

    private $table = "vendas.itens_vendas";
    private $joins = [];

    public function listaVenda($vendaId){
        return $this
                    ->select('*')
                    ->from($this->table, 'iv')
                    ->where('venda_id', '=', $vendaId)
                    //->order('nome')
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
}