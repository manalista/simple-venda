<?php 

class Model{

    private $db;
    private $ddlQueryBuilder;

    public function __construct() {
        $this->db = new DB();
        $this->query = "";
        $this->fields = null;
        $this->fromString = "";
        $this->whereContent = null;
        $this->orderContent = null;
        $this->alias = "";
        $this->ddlQueryBuilder = new DDLQueryBuilder();
   }

    public function select($fields = "*"){
        $this->ddlQueryBuilder->select($fields);
        return $this;
    }

    public function from($tableName, $alias = false){
        $this->ddlQueryBuilder->from($tableName, $alias);
        return $this;
    }

    public function where($field, $operation, $val){
        $this->ddlQueryBuilder->where($field, $operation, $val);
        return $this;
    }

    public function order($order, $direction = "ASC"){
        $this->ddlQueryBuilder->order($order, $direction);
        return $this;
    }

    public function and($field=null, $operation = null, $value=null){
        $this->ddlQueryBuilder->and($field, $operation, $value);
        return $this;
    }

    public function or($field=null, $value=null){
        $this->ddlQueryBuilder->or($field, $value);
        return $this;
    }

    public function like($field, $value){
        $this->ddlQueryBuilder->like($field, $value);
        return $this;
    }

    public function ilike($field, $value){
        $this->ddlQueryBuilder->ilike($field, $value);
        return $this;
    }

    public function in($field, $value){
        $this->ddlQueryBuilder->in($field, $value);
        return $this;
    }

    public function notIn($field, $value){
        $this->ddlQueryBuilder->notIn($field, $value);
        return $this;
    }

    public function between($field, $lower, $higher){
        $this->ddlQueryBuilder->between($field, $lower, $higher);
        return $this;
    }

    public function fetch(){
        $query = $this->ddlQueryBuilder->getSelectQuery();
        $result = $this->db->consulta($query);
        return $result;
    }
}