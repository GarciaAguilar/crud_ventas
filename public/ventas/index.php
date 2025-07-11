<?php
// Usamos realpath() para asegurar la ruta correcta
require_once realpath(dirname(__DIR__, 2)) . DIRECTORY_SEPARATOR . 'config' . DIRECTORY_SEPARATOR . 'path.php';

try {
    requireFrom(CONTROLLERS_PATH, 'VentaController.php');
    
    $controller = new VentaController();

    if (isset($_GET['action'])) {
        $action = $_GET['action'];
        
        switch($action) {
            case 'crear':
                $controller->crear();
                break;
            case 'detalle':
                if (isset($_GET['id'])) {
                    $controller->detalle($_GET['id']);
                }
                break;
            case 'procesar_pago':
                $controller->procesarPago();
                break;
            default:
                $controller->index();
                break;
        }
    } else {
        $controller->index();
    }
} catch (Exception $e) {
    die("Error: " . $e->getMessage());
}
?>
