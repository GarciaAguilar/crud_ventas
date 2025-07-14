<?php
require_once realpath(dirname(__DIR__)) . DIRECTORY_SEPARATOR . 'config' . DIRECTORY_SEPARATOR . 'path.php';
requireFrom(MODELS_PATH, 'BaseModel.php');

class Factura extends BaseModel {
    protected $table = 'facturacion';

    public function obtenerTodasConDetalles() {
        $query = "SELECT f.*, v.total, v.fecha_venta, c.nombre as nombre_cliente
                  FROM facturacion f
                  JOIN ventas v ON f.id_venta = v.id_venta
                  LEFT JOIN clientes c ON v.id_cliente = c.id_cliente
                  ORDER BY f.id_factura DESC";
        
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function obtenerPorVenta($idVenta) {
        $query = "SELECT * FROM facturacion WHERE id_venta = ? LIMIT 1";
        $stmt = $this->db->prepare($query);
        $stmt->execute([$idVenta]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function anular($idFactura) {
        $query = "UPDATE facturacion SET estado = 0 WHERE id_factura = ?";
        $stmt = $this->db->prepare($query);
        return $stmt->execute([$idFactura]);
    }
    
    public function crear($idVenta) {
        try {
            // Obtener datos de la venta para calcular totales
            $queryVenta = "SELECT v.*, 
                          SUM(dv.precio_unitario * dv.cantidad) as subtotal
                          FROM ventas v
                          LEFT JOIN detalles_venta dv ON v.id_venta = dv.id_venta
                          WHERE v.id_venta = ?
                          GROUP BY v.id_venta";
            $stmtVenta = $this->db->prepare($queryVenta);
            $stmtVenta->execute([$idVenta]);
            $venta = $stmtVenta->fetch(PDO::FETCH_ASSOC);
            
            if (!$venta) {
                throw new Exception("Venta no encontrada para generar factura");
            }
            
            // Calcular totales
            $subtotal = floatval($venta['subtotal'] ?? 0);
            $impuestos = $subtotal * 0.13; // 13% IVA
            $total = $subtotal + $impuestos;
            
            // Generar número de factura único
            $numeroFactura = $this->generarNumeroFactura();
            
            $query = "INSERT INTO facturacion (id_venta, numero_factura, fecha_emision, subtotal, impuestos, total, estado) 
                      VALUES (?, ?, NOW(), ?, ?, ?, 1)";
            $stmt = $this->db->prepare($query);
            $resultado = $stmt->execute([$idVenta, $numeroFactura, $subtotal, $impuestos, $total]);
            
            if($resultado) {
                return $this->db->lastInsertId();
            }
            return false;
            
        } catch(Exception $e) {
            error_log("Error creando factura: " . $e->getMessage());
            return false;
        }
    }
    
    private function generarNumeroFactura() {
        $anioActual = date('Y');
        
        // Obtener el último número de factura del año actual
        $query = "SELECT numero_factura FROM facturacion 
                  WHERE numero_factura LIKE :patron 
                  ORDER BY id_factura DESC LIMIT 1";
        $stmt = $this->db->prepare($query);
        $patron = "FAC-%-$anioActual";
        $stmt->bindParam(':patron', $patron);
        $stmt->execute();
        $ultimo = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if($ultimo && $ultimo['numero_factura']) {
            // Extraer el número del formato FAC-001-2025
            $partes = explode('-', $ultimo['numero_factura']);
            if(count($partes) >= 2) {
                $numero = intval($partes[1]) + 1;
            } else {
                $numero = 1;
            }
        } else {
            // Primera factura del año
            $numero = 1;
        }
        
        // Formato: FAC-001-2025
        return 'FAC-' . str_pad($numero, 3, '0', STR_PAD_LEFT) . '-' . $anioActual;
    }
}
?>