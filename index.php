<?php 


$route = $_SERVER['REQUEST_URI'];

define("ROOT_FILES", dirname(__FILE__));

if(file_exists('routes.json')){
    $routes = json_decode(file_get_contents('routes.json'), true);
}else{
    die("Rotas não encontradas");
}

if($route == "/"){
    $pathController = $route;
}else{
    [$path, $queryString]  = explode('?', $route);
    [$pathController, $nameAction] = explode('/', mb_substr($path, 1));
}

if(!$nameAction){
    $nameAction = '/';
}

$action = $routes[$pathController]['path'][$nameAction];
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
        require_once ROOT_FILES."/views/http/404.php";
        exit(404);
    }
});

$controllerClass = $controller . 'Controller';
$controllerFound = new $controllerClass();
$before = [$controllerFound, 'before'];
$method = [$controllerFound, $action];
$after = [$controllerFound, 'after'];
if(is_callable($before)){
    call_user_func($before);
}
if(is_callable($method)){
    call_user_func($method);
}else{
    if(!$controller){
        die("No controller was found!");
    }
    die("$controllerClass->$action is not a callable!");
}
if(is_callable($after)){
    call_user_func($after);
}
