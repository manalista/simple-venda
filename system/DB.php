<?php 

class DB{

    private $conexao;

    public function __construct() {
        if(file_exists(dirname(__FILE__).'/../db.json')){
            $config =  json_decode(file_get_contents(dirname(__FILE__).'/../db.json'), true);
            foreach($config as $var => $value){
                $$var = $value;
            }
        }else{
            die("Database config not found.");
        }
        $this->conexao = new PDO("pgsql:host=$host;port=$port;dbname=$database;user=$user;password=$password");
    }
    
    public function consulta($query){
        $query = $this->conexao->query($query);
        return $query->fetchAll(PDO::FETCH_CLASS);
    }

    public function executa(){

    }


}