<?php
$controllerName = $_GET['controller'] ?? 'inicio';
$action = $_GET['action'] ?? 'index';

$controllerClass = ucfirst($controllerName) . 'Controller';
$controllerFile = "controllers/$controllerClass.php";

if (file_exists($controllerFile)) {
    require_once $controllerFile;
    $controller = new $controllerClass();

    if (method_exists($controller, $action)) {
        $controller->$action();
    } else {
        echo "Acción '$action' no encontrada.";
    }
} else {
    echo "Controlador '$controllerName' no encontrado.";
}