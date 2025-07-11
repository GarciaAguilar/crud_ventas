<?php
require_once __DIR__ . '/../config/paths.php';
require_once MODELS_PATH . '/Venta.php';
require_once MODELS_PATH . '/Inventario.php';

class VentaController {
    private $modelVenta;
    private $modelInventario;

    public function __construct() {
        $this->modelVenta = new Venta();
        $this->modelInventario = new Inventario();
    }

    public function index() {
        $ventas = $this->modelVenta->getAll();
        require_once VIEWS_PATH . '/ventas/index.php';
    }

    public function crear() {
        if($_SERVER['REQUEST_METHOD'] === 'POST') {
            $idCliente = $_POST['id_cliente'] ?? null;
            $productos = json_decode($_POST['productos'], true);
            
            $idVenta = $this->modelVenta->crearVenta($idCliente, $productos);
            
            if($idVenta) {
                $_SESSION['success'] = 'Venta registrada correctamente. ID: ' . $idVenta;
                header('Location: ../ventas/detalle/' . $idVenta);
                exit();
            } else {
                $_SESSION['error'] = 'Error al registrar la venta';
                header('Location: ../ventas/crear');
                exit();
            }
        } else {
            $productosDisponibles = $this->modelInventario->getProductosDisponibles();
            require_once VIEWS_PATH . '/ventas/crear.php';
        }
    }

    public function detalle($idVenta) {
        $venta = $this->modelVenta->obtenerConDetalles($idVenta);
        if(!$venta) {
            $_SESSION['error'] = 'Venta no encontrada';
            header('Location: ../ventas');
            exit();
        }
        require_once VIEWS_PATH . '/ventas/detalle.php';
    }

    public function procesarPago() {
        if($_SERVER['REQUEST_METHOD'] === 'POST') {
            $idVenta = $_POST['id_venta'];
            $montoRecibido = (float)$_POST['monto_recibido'];
            
            $result = $this->modelVenta->registrarPago($idVenta, $montoRecibido);
            
            if($result) {
                $_SESSION['success'] = 'Pago registrado correctamente';
            } else {
                $_SESSION['error'] = 'Error al registrar el pago';
            }
            header('Location: ../ventas/detalle/' . $idVenta);
            exit();
        }
    }

    
}
?>