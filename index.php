<?php 

$route = $_SERVER['REQUEST_URI'];
if(file_exists('routes.json')){
    $routes = json_decode(file_get_contents('routes.json'), true);
}else{
    die("Rotas não encontradas");
}

[$path, $queryString]  = explode('?', $route);
[$pathController, $nameAction] = explode('/', mb_substr($path, 1));

$action = $nameAction ?: 'index';

$controller = $routes[$pathController]['classController'];

spl_autoload_register(function ($class) {
    $look_in = [
        'controllers'
        , 'models'
        , 'system'
    ];
    foreach($look_in as $folder){
        if(file_exists("{$folder}/{$class}.php")){
            require_once "{$folder}/{$class}.php";
            break;
        }
    }
    if(!class_exists($class)){
        http_response_code(404);
        require_once dirname(__FILE__).'/views/http/404.php';
        exit(404);
    }
});

$controllerClass = $controller . 'Controller';
$controllerFound = new $controllerClass();

call_user_func([$controllerFound, $action]);