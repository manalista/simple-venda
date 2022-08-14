<?php 
error_reporting(E_ALL);
ini_set('display_errors', 1);

$route = $_SERVER['REQUEST_URI'];

define("ROOT_FILES", dirname(__FILE__));

if(file_exists('routes.json')){
    $routes = json_decode(file_get_contents('routes.json'), true);
}else{
    die("Rotas não encontradas");
}

$http_method = $_SERVER['REQUEST_METHOD'];
$pathController = "/";
if($route !== "/"){
    $pos = strpos($route, "/", 1);
    $pathController = $pos ? substr($route, 1, ($pos-1)) : substr($route, 1);
    $nameAction = "/";
    if(strpos($route,"/",1) !== false){
        $nameAction = substr($route, $pos+1);
    }
}

$controller = $routes[$pathController]['classController'];
$rotasController = $routes[$pathController];
if(isset($rotasController[$http_method][$nameAction]) ?? false){
    $action = $rotasController[$http_method][$nameAction];
}else{
    $action = 'error404';
}

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
