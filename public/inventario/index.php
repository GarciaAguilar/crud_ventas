<?php
// Usamos realpath() para asegurar la ruta correcta
require_once realpath(dirname(__DIR__, 2)) . DIRECTORY_SEPARATOR . 'config' . DIRECTORY_SEPARATOR . 'path.php';

try {
    requireFrom(CONTROLLERS_PATH, 'InventarioController.php');
    
    $controller = new InventarioController();

    if (isset($_GET['action'])) {
        $action = $_GET['action'];
        
        switch($action) {
            case 'crear':
                if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                    $controller->crear();
                } else {
                    requireFrom(VIEWS_PATH, 'inventario/crear.php');
                }
                break;
            case 'editar':
                if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                    $controller->editar();
                } else {
                    requireFrom(VIEWS_PATH, 'inventario/editar.php');
                }
                break;
            case 'eliminar':
                if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                    $controller->eliminar();
                }
                break;
            case 'buscar':
                $controller->buscar();
                break;
            default:
                $controller->index();
                break;
        }
    } else {
        requireFrom(VIEWS_PATH, 'inventario/index.php');
    }
} catch (Exception $e) {
    die("Error: " . $e->getMessage());
}
?>