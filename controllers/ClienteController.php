<?php
require_once __DIR__ . '/../config/path.php';
require_once MODELS_PATH . '/Cliente.php';

class ClienteController {
    private $model;

    public function __construct() {
        $this->model = new Cliente();
    }

    public function buscar() {
        $termino = $_GET['termino'] ?? '';
        $clientes = $this->model->buscar($termino);
        header('Content-Type: application/json');
        echo json_encode($clientes);
    }
}
?>