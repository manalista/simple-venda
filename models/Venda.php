<?php 

class Venda extends Model{

    private $table = "vendas.vendas";
    private $itens;

    public function lista(){
        $itens = new ItemVenda();
        $vendas = $this->select('*')
            ->from($this->table, 'v')
            ->order('data', 'DESC')
            ->fetch();

        foreach($vendas as $venda){
            $venda->itens = $itens->listaVenda($venda->id);
        }
        return $vendas;
    }

    public function getVenda($id){
        $itens = new ItemVenda();
        $venda = $this->select('*')
            ->from($this->table, 'v')
            ->order('data', 'DESC')
            ->fetch()[0];
        $venda->itens = $itens->listaVenda($id);
        return $venda;
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