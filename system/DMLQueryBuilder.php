<?php 

class DMLQueryBuilder{

    private $query;
    private $alias;
    private $fields;
    private $fromString;
    private $orderContent;
    private $whereContent;
    private $currentOperator;

    public function __construct() {
        $this->query = "";
        $this->fields = [];
        $this->fromString = "";
        $this->whereContent = [];
        $this->orderContent = [];
        $this->currentOperator = [];
        $this->alias = "";
   }

    public function select($fields = "*"){
        if(is_string($fields)){
            $this->fields = explode(",", $fields);
            return $this;
        }
        if(is_array($fields)){
            if(count($fields) == count($fields, COUNT_RECURSIVE)){
                // array is not multidimensional
                $this->fields = $fields;
                return $this;
            }else{
                foreach($fields as $field => $alias){
                    $this->fields[] = "$field as $alias\n";
                }
                return $this;
            }
        }
    }

    public function from($tableName, $alias = false){
        if(!$alias){
            $this->fromString = "\nFROM\n    {$tableName}";
            return $this;
        }
        $this->alias = $alias;
        $this->fromString = "\nFROM\n    {$tableName} as {$alias}";
        return $this;
    }

    public function where($field, $operation, $value){
        $this->whereContent[] = "\n    {$field} {$operation} {$value}\n";
        return $this;
    }

    public function order($order, $direction = "ASC"){
        if(!$order){
            return $this;
        }
        if(is_string($order)){
            $this->orderContent = "ORDER BY $order $direction";
            return $this;
        }
        foreach($order as $key => $value){
            $this->orderContent[] = "{$key} {$value}";
            return $this;
        }
    }

    public function and($field = null, $operation = null, $value = null){
        if($field && $value){
            $this->whereContent[]['and'] = "{$field} $operation '{$value}'";
            return $this;
        }
        $this->currentOperator = "AND";
        return $this;
    }

    public function or($field = null, $value = null){
        if($field && $value){
            $this->whereContent[]['OR'] = "{$field} = '{$value}'";
            return $this;
        }
        $this->currentOperator = "OR";
        return $this;
    }

    public function like($field, $value){
        $operatorOrLike = $this->currentOperator ?? "LIKE";
        $this->whereContent[][$operatorOrLike] = "{$field} like '{$value}'";
        return $this;
    }

    public function ilike($field, $value){
        $operatorOrIlike = $this->currentOperator ?? "ILIKE";
        $this->whereContent[][$operatorOrIlike] = "{$field} = '{$value}'";
        return $this;
    }

    public function inNotIn($inNotIn, $field, $values){
        $valuesArray = [];
        foreach($values as $value){
            if(is_string($value)){
                $valuesArray[] = "'{$value}'";
            }else{
                $valuesArray[] = "{$value}";
            }
        }
        $operator = $this->currentOperator ?? "";
        $this->whereContent[][$operator] = "{$field} $inNotIn (" . implode(",", $valuesArray) . ")";
        return $this;
    }

    public function notIn($field, $value){
        $this->inNotIn("NOT IN", $field, $value);
        return $this;
    }
    
    public function in($field, $value){
        $this->inNotIn("IN", $field, $value);
        return $this;
    }

    public function between($field, $lower, $higher){
        $this->whereContent[]['between'] = "{$field} BETWEEN {$lower} AND {$higher}";
        return $this;
    }

    public function getInsertQuery($table, $data){
        $types = [
            'integer' => ''
            , 'string' => "'"
            , 'boolean' => ''
            , 'double' => ''
        ];
        $fields = [];
        $values = [];
        foreach($data as $field => $value){
            $type = gettype($value);
            $fields[] = $field;
            $values[] = $types[$type] . $value . $types[$type];
        }
        $sFields = implode(",", $fields);
        $sValues = implode(",", $values);
        return "INSERT INTO {$table} ({$sFields}) VALUES ({$sValues}) returning *";
    }

    public function getSelectQuery(){
        $this->currentOperator = '';
        $fromString = $this->fromString;
        $fieldsString = $this->createFieldsString();
        $whereString = "";
        $orderString = "";

        if($this->whereContent){
            $whereString = $this->createWhereString();
        }

        if($this->orderContent){
            $orderString = $this->createOrderString();
        }

        $this->query = "SELECT\n{$fieldsString}{$fromString}{$whereString}{$orderString}";
        return $this->query;
    }

    private function createFieldsString(){
        $fieldsString = "    ";
        if(count($this->fields) == 1){
            $fieldsString = "    {$this->alias}.{$this->fields[0]}";
        }else{
            $separador = "";
            foreach($this->fields as $campo => $alias){
                $fieldsString .= "$separador{$this->alias}.{$campo} as $alias";
                $separador = "\n    , ";
            }
        }
        return $fieldsString;
    }

    private function createWhereString(){
        $whereString = "";
        if(is_string($this->whereContent)){
            $whereString = "{$this->whereContent}";
        }else{
            if(count($this->whereContent) > 0){
                $whereString = "\nWHERE";
                foreach($this->whereContent as $where ){
                    if(is_string($where)){
                        $whereString .= "    {$where}";
                    }else{
                        $operator= key($where);
                        $condition = current($where);
                        $whereString .= "    $operator $condition\n";
                    }
                }
                $whereString = rtrim($whereString, "\n");
            }
        }
        return $whereString;
    }

    private function createOrderString(){
        if(is_string($this->orderContent)){
            $orderString = "\n{$this->orderContent}";
        }else{
            if(count($this->orderContent) > 0){
                $orderString = "\nORDER BY";
                $separador = "";
                foreach($this->orderContent as $order){
                    $orderString .= "\n    $separador $order";
                    $separador = ", ";
                }
            }
        }
        return $orderString;
    }
}