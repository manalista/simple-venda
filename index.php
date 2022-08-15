<?php 
error_reporting(E_ALL);
ini_set('display_errors', 1);

define("ROOT_FILES", dirname(__FILE__));

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
        error_log("Class {$class} not found");
        http_response_code(404);
        require_once ROOT_FILES."/views/http/404.php";
        exit(404);
    }
});

if(file_exists('routes.json')){
    $routes = json_decode(file_get_contents('routes.json'), true);
}else{
    die("Rotas não encontradas");
}

$route = $_SERVER['REQUEST_URI'];
$request_method = $_SERVER['REQUEST_METHOD'];
$http_method = $request_method;

if($request_method == 'POST'){
    if(isset($_POST['_method'])){
        $http_method = strtoupper($_POST['_method']);
    }
}

$pathController = "/";
if($route !== "/"){
    $pos = strpos($route, "/", 1);
    $pathController = $pos ? substr($route, 1, ($pos-1)) : substr($route, 1);
    $nameAction = "/";
    if(strpos($route,"/",1) !== false){
        $nameAction = substr($route, $pos+1);
    }
}
//die($http_method);
$controller = $routes[$pathController]['classController'];
$rotasController = $routes[$pathController];
if(isset($rotasController[$http_method][$nameAction]) ?? false){
    $action = $rotasController[$http_method][$nameAction];
}else{
    $action = 'error404';
}

$controllerClass = $controller . 'Controller';
$controllerFound = new $controllerClass();
$before = [$controllerFound, 'before'];
$method = [$controllerFound, $action];
$after = [$controllerFound, 'after'];
try{
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
} catch (Exception $e) {
    error_log($e->getMessage());
    http_response_code(500);
    require_once ROOT_FILES."/views/http/500.php";
    exit(500);
}
