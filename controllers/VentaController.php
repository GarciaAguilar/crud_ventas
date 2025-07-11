<?php
session_start();
require_once realpath(dirname(__DIR__)) . DIRECTORY_SEPARATOR . 'config' . DIRECTORY_SEPARATOR . 'path.php';

try {
    requireFrom(MODELS_PATH, 'Venta.php');
    requireFrom(MODELS_PATH, 'Inventario.php');

class VentaController {
    private $modelVenta;
    private $modelInventario;

    public function __construct() {
        $this->modelVenta = new Venta();
        $this->modelInventario = new Inventario();
    }

    public function index() {
        $ventas = $this->modelVenta->obtenerTodasConClientes();
        require_once VIEWS_PATH . '/ventas/index.php';
    }

    public function crear() {
        if($_SERVER['REQUEST_METHOD'] === 'POST') {
            $idCliente = $_POST['id_cliente'] ?? null;
            $productos = json_decode($_POST['productos'], true);
            
            $idVenta = $this->modelVenta->crearVenta($idCliente, $productos);
            
            if($idVenta) {
                $_SESSION['success'] = 'Venta registrada correctamente. ID: ' . $idVenta;
                header('Location: /Crud_Ventas/views/ventas/detalle.php?id=' . $idVenta);
                exit();
            } else {
                $_SESSION['error'] = 'Error al registrar la venta';
                header('Location: /Crud_Ventas/views/ventas/crear.php');
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
            header('Location: /Crud_Ventas/public/ventas/');
            exit();
        }
        require_once VIEWS_PATH . '/ventas/detalle.php';
    }

    public function procesarPago() {
        if($_SERVER['REQUEST_METHOD'] === 'POST') {
            $idCliente = $_POST['id_cliente'] ?: null;
            $productos = json_decode($_POST['productos'], true);
            $montoRecibido = (float)$_POST['monto_recibido'];
            $cambio = (float)$_POST['cambio'];
            
            // Calcular totales
            $subtotal = 0;
            foreach($productos as $producto) {
                $inventario = $this->modelInventario->getById($producto['id_producto']);
                $subtotal += $inventario['precio'] * $producto['cantidad'];
            }
            $iva = $subtotal * 0.13;
            $total = $subtotal + $iva;
            
            // Crear la venta y procesar pago
            try {
                // 1. Crear la venta con estado 2 (pagado)
                $idVenta = $this->modelVenta->crearVenta($idCliente, $productos, 2);
                if(!$idVenta) {
                    throw new Exception("Error al crear la venta");
                }
                
                // 2. Registrar el pago con monto recibido y cambio
                $result = $this->modelVenta->registrarPago($idVenta, $montoRecibido, $cambio);
                if(!$result) {
                    throw new Exception("Error al registrar el pago");
                }
                
                $_SESSION['success'] = 'Venta registrada y pago procesado correctamente';
                header('Location: /Crud_Ventas/public/ventas/');
                exit();
                
            } catch(Exception $e) {
                $_SESSION['error'] = $e->getMessage();
                // Obtener productos disponibles para evitar el error
                $productosDisponibles = $this->modelInventario->getProductosDisponibles();
                require_once VIEWS_PATH . '/ventas/crear.php';
            }
        } else {
            // Redirigir si no es POST
            header('Location: /Crud_Ventas/public/ventas/?action=crear');
            exit();
        }
    }
}
} catch (Exception $e) {
    die("Error en VentaController: " . $e->getMessage());
}
?>