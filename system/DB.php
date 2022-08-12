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
            die("Configurações de banco de dados não encontradas.");
        }
        $this->conexao = new PDO("pgsql:host=$host;port=$port;dbname=$database;user=$user;password=$password");
    }
    public function consulta(){

    }

    public function executa(){

    }


}