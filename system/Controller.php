<?php 

class Controller{

    protected $injectedData;

    public function __construct() {
        $this->injectedData = [];
    }

    public function injectData($data){
        $this->injectedData[] = $data;
    }

    public function before(){
        $this->injectData('search', true);
    }
    
    public function goto($addres){
        header("Location: {$addres}");
        exit(0);
    }
    
    public function render($dados = [], $view = ''){
        $viewHelper = new ViewHelper();
        $chamador =  debug_backtrace()[1];
        $controllerName = $chamador['class'];
        if(!$view){
            $view = $chamador['function'];
        }

        extract($this->injectedData);
        extract($dados);
        
        $viewFIle = "/views/$controllerName/$view.php";
        ob_start();
        require_once dirname(dirname(__FILE__)). $viewFIle;
        $contentView = ob_get_contents();
        ob_get_clean();
        $tags = $viewHelper->getTags($contentView);
        foreach($tags as $tag){
            $bloco = $viewHelper->getBloco($tag, $contentView);
            $contentView = $viewHelper->removeBloco($tag, $bloco, $contentView);
            $$tag = $bloco;
        }
        require_once dirname(dirname(__FILE__)). "/views/template.php";
    }

    public function json($dados){
        header('Content-Type: application/json');
        echo json_encode($dados);
    }

    public function filterPost($filters){
        return filter_var_array($_POST, $filters);
    }

    public function error404(){
        die("404");
    }
}