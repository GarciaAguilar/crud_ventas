<?php
require_once realpath(dirname(__DIR__)) . DIRECTORY_SEPARATOR . 'config' . DIRECTORY_SEPARATOR . 'path.php';

try {
    requireFrom(MODELS_PATH, 'Venta.php');
    requireFrom(MODELS_PATH, 'Factura.php');

class FacturaController {
    private $modelVenta;
    private $modelFactura;

    public function __construct() {
        $this->modelVenta = new Venta();
        $this->modelFactura = new Factura();
    }

    public function index() {
        $facturas = $this->modelFactura->obtenerTodasConDetalles();
        require_once VIEWS_PATH . '/facturas/index.php';
    }

    public function generarPdf($idVenta) {
        $venta = $this->modelVenta->obtenerConDetalles($idVenta);
        $factura = $this->modelFactura->obtenerPorVenta($idVenta);
        
        // Validar que se obtuvieron los datos
        if (!$venta) {
            die('Error: Venta no encontrada para ID: ' . $idVenta);
        }
        
        if (!$factura) {
            die('Error: Factura no encontrada para esta venta ID: ' . $idVenta);
        }
        
        require_once VIEWS_PATH . '/facturas/generar_pdf.php';
    }

    public function anular($idFactura) {
        $result = $this->modelFactura->anular($idFactura);
        
        if($result) {
            $_SESSION['success'] = 'Factura anulada correctamente';
        } else {
            $_SESSION['error'] = 'Error al anular la factura';
        }
        
        header('Location: ../facturas');
        exit();
    }
}
} catch (Exception $e) {
    die("Error en FacturaController: " . $e->getMessage());
}
?>