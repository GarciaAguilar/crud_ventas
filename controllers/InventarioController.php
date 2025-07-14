
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
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                // Validar datos requeridos
                if (empty($_POST['nombre']) || empty($_POST['precio']) || empty($_POST['stock'])) {
                    echo json_encode([
                        'success' => false,
                        'message' => 'Faltan campos obligatorios: nombre, precio y stock'
                    ]);
                    return;
                }

                $data = [
                    'nombre' => $_POST['nombre'],
                    'descripcion' => $_POST['descripcion'] ?? '',
                    'precio' => $_POST['precio'],
                    'costo' => $_POST['costo'] ?? 0,
                    'stock' => $_POST['stock'],
                    'categoria' => $_POST['categoria'] ?? '',
                    'proveedor' => $_POST['proveedor'] ?? '',
                    'estado' => $_POST['estado'] ?? 1
                ];

                $result = $this->model->crear($data);
                echo json_encode([
                    'success' => $result,
                    'message' => $result ? 'Producto creado correctamente' : 'Error al crear el producto'
                ]);
            } else {
                echo json_encode(['error' => 'Método no permitido']);
            }
        }

        public function editar()
        {
            if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['id'])) {
                // Obtener producto para editar
                $producto = $this->model->getById($_GET['id']);
                if ($producto) {
                    echo json_encode($producto);
                } else {
                    echo json_encode(['error' => 'Producto no encontrado']);
                }
            } else {
                echo json_encode(['error' => 'Método no permitido o ID faltante']);
            }
        }

        public function actualizar()
        {
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $data = [
                    'nombre' => $_POST['nombre'] ?? '',
                    'descripcion' => $_POST['descripcion'] ?? '',
                    'precio' => $_POST['precio'] ?? 0,
                    'costo' => $_POST['costo'] ?? 0,
                    'stock' => $_POST['stock'] ?? 0,
                    'categoria' => $_POST['categoria'] ?? '',
                    'proveedor' => $_POST['proveedor'] ?? '',
                    'estado' => $_POST['estado'] ?? 1
                ];

                $result = $this->model->actualizar($_POST['id'], $data);
                echo json_encode([
                    'success' => $result,
                    'message' => $result ? 'Producto actualizado correctamente' : 'Error al actualizar el producto'
                ]);
            } else {
                echo json_encode(['error' => 'Método no permitido']);
            }
        }

        public function eliminar()
        {
            if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'])) {
                $result = $this->model->delete($_POST['id']);
                echo json_encode([
                    'success' => $result,
                    'message' => $result ? 'Producto eliminado correctamente' : 'Error al eliminar el producto'
                ]);
            } else {
                echo json_encode(['error' => 'Método no permitido o ID faltante']);
            }
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
            case 'actualizar':
                $controller->actualizar();
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

