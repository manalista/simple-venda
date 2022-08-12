<?php 

class Controller{

    public function goto($addres){
        header("Location: {$addres}");
        exit(0);
    }

    public function render($dados = [], $view = ''){
        $chamador =  debug_backtrace()[1];
        $controllerName = $chamador['class'];
        if(!$view){
            $view = $chamador['function'];
        }
        extract($dados);
        $viewFIle = "/views/$controllerName/$view.php";
        require_once dirname(dirname(__FILE__)). $viewFIle;
    }

    public function json($dados){
        header('Content-Type: application/json');
        echo json_encode($dados);
        exit(0);
    }
}