<?php
// Usamos realpath() para asegurar la ruta correcta
require_once realpath(dirname(__DIR__, 2)) . DIRECTORY_SEPARATOR . 'config' . DIRECTORY_SEPARATOR . 'path.php';

try {
    requireFrom(CONTROLLERS_PATH, 'FacturaController.php');
    
    $controller = new FacturaController();

    if (isset($_GET['action'])) {
        $action = $_GET['action'];
        
        switch($action) {
            case 'generar_pdf':
                if (isset($_GET['id'])) {
                    $controller->generarPdf($_GET['id']);
                }
                break;
            case 'anular':
                if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'])) {
                    $controller->anular($_POST['id']);
                }
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
