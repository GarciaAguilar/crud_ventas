
<?php
require_once realpath(dirname(__DIR__)) . DIRECTORY_SEPARATOR . 'config' . DIRECTORY_SEPARATOR . 'path.php';

try {
    requireFrom(MODELS_PATH, 'Inventario.php');

    class InventarioController
    {
        private $model;

        public function __construct()
        {
            $this->model = new Inventario();
        }

        public function index()
        {
            $productos = $this->model->getAll();
            echo json_encode($productos->fetchAll(PDO::FETCH_ASSOC));
        }

        public function crear()
        {
            $data = [
                'nombre' => $_POST['nombre'],
                'descripcion' => $_POST['descripcion'],
                'precio' => $_POST['precio'],
                'costo' => $_POST['costo'],
                'stock' => $_POST['stock'],
                'categoria' => $_POST['categoria'],
                'proveedor' => $_POST['proveedor'],
                'estado' => $_POST['estado']
            ];

            $result = $this->model->crear($data);
            echo json_encode(['success' => $result]);
        }

        public function editar()
        {
            if (isset($_GET['id'])) {
                // Obtener producto para editar
                $producto = $this->model->getById($_GET['id']);
                echo json_encode($producto);
            } else {
                // Actualizar producto
                $data = [
                    'nombre' => $_POST['nombre'],
                    'descripcion' => $_POST['descripcion'],
                    'precio' => $_POST['precio'],
                    'costo' => $_POST['costo'],
                    'stock' => $_POST['stock'],
                    'categoria' => $_POST['categoria'],
                    'proveedor' => $_POST['proveedor'],
                    'estado' => $_POST['estado']
                ];

                $result = $this->model->actualizar($_POST['id'], $data);
                echo json_encode(['success' => $result]);
            }
        }

        public function eliminar()
        {
            $result = $this->model->delete($_POST['id']);
            echo json_encode(['success' => $result]);
        }

        public function buscar()
        {
            $resultados = $this->model->buscar($_GET['termino']);
            echo json_encode($resultados);
        }
    }

    // Manejo de acciones
    if (isset($_GET['action'])) {
        $controller = new InventarioController();
        
        switch ($_GET['action']) {
            case 'index':
                $controller->index();
                break;
            case 'crear':
                $controller->crear();
                break;
            case 'editar':
                $controller->editar();
                break;
            case 'eliminar':
                $controller->eliminar();
                break;
            case 'buscar':
                $controller->buscar();
                break;
            default:
                echo json_encode(['error' => 'Acción no válida']);
                break;
        }
    }
} catch (Exception $e) {
    die("Error en InventarioController: " . $e->getMessage());
}
?>

