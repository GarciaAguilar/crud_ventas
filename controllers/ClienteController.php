<?php
require_once realpath(dirname(__DIR__)) . DIRECTORY_SEPARATOR . 'config' . DIRECTORY_SEPARATOR . 'path.php';

try {
    requireFrom(MODELS_PATH, 'Cliente.php');

    class ClienteController {
        private $model;

        public function __construct() {
            $this->model = new Cliente();
        }

    public function buscar() {
        header('Content-Type: application/json');
        
        $termino = $_GET['termino'] ?? '';
        
        if(empty($termino)) {
            echo json_encode([]);
            return;
        }
        
        try {
            $clientes = $this->model->buscar($termino);
            echo json_encode($clientes);
        } catch(Exception $e) {
            echo json_encode(['error' => $e->getMessage()]);
        }
    }
    }

    // Manejo de acciones
    if (isset($_GET['action'])) {
        $controller = new ClienteController();
        
        switch ($_GET['action']) {
            case 'buscar':
                $controller->buscar();
                break;
            default:
                echo json_encode(['error' => 'Acción no válida']);
                break;
        }
    } else {
        echo json_encode(['error' => 'No se especificó ninguna acción']);
    }
} catch (Exception $e) {
    echo json_encode(['error' => $e->getMessage()]);
}
?>