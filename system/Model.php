<?php 

class Model{

    private $db;
    private $dmlQueryBuilder;

    public function __construct() {
        $this->db = new DB();
        $this->query = "";
        $this->fields = null;
        $this->fromString = "";
        $this->whereContent = null;
        $this->orderContent = null;
        $this->alias = "";
        $this->dmlQueryBuilder = new dmlQueryBuilder();
   }

    public function select($fields = "*"){
        $this->dmlQueryBuilder->select($fields);
        return $this;
    }

    public function from($tableName, $alias = false){
        $this->dmlQueryBuilder->from($tableName, $alias);
        return $this;
    }

    public function where($field, $operation, $val){
        $this->dmlQueryBuilder->where($field, $operation, $val);
        return $this;
    }

    public function order($order, $direction = "ASC"){
        $this->dmlQueryBuilder->order($order, $direction);
        return $this;
    }

    public function and($field=null, $operation = null, $value=null){
        $this->dmlQueryBuilder->and($field, $operation, $value);
        return $this;
    }

    public function or($field=null, $value=null){
        $this->dmlQueryBuilder->or($field, $value);
        return $this;
    }

    public function like($field, $value){
        $this->dmlQueryBuilder->like($field, $value);
        return $this;
    }

    public function ilike($field, $value){
        $this->dmlQueryBuilder->ilike($field, $value);
        return $this;
    }

    public function in($field, $value){
        $this->dmlQueryBuilder->in($field, $value);
        return $this;
    }

    public function notIn($field, $value){
        $this->dmlQueryBuilder->notIn($field, $value);
        return $this;
    }

    public function between($field, $lower, $higher){
        $this->dmlQueryBuilder->between($field, $lower, $higher);
        return $this;
    }

    public function fetch(){
        $query = $this->dmlQueryBuilder->getSelectQuery();
        $result = $this->db->consulta($query);
        return $result;
    }

    public function insert($tableName, $data){
        $query = $this->dmlQueryBuilder->getInsertQuery($tableName, $data);
        $result = $this->db->executa($query);
        return $result;
    }
}