<?php
require_once '../config/database.php';
require_once '../models/BaseModel.php';

// Autocargador de clases
spl_autoload_register(function($class) {
    if (file_exists("../models/{$class}.php")) {
        require_once "../models/{$class}.php";
    } elseif (file_exists("../controllers/{$class}.php")) {
        require_once "../controllers/{$class}.php";
    }
});

// Obtener el módulo solicitado (por defecto inventario)
$module = $_GET['module'] ?? 'inventario';
$action = $_GET['action'] ?? 'index';

// Validar módulos permitidos
$allowedModules = ['inventario', 'ventas', 'facturas'];
if (!in_array($module, $allowedModules)) {
    die('Módulo no permitido');
}

// Formar nombre del controlador
$controllerClass = ucfirst($module) . 'Controller';
$controllerFile = "../controllers/{$controllerClass}.php";

if (file_exists($controllerFile)) {
    require_once $controllerFile;
    $controller = new $controllerClass();
    
    if (method_exists($controller, $action)) {
        $controller->$action();
    } else {
        die('Acción no encontrada');
    }
} else {
    die('Controlador no encontrado');
}
?>