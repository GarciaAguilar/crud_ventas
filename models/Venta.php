<?php
require_once realpath(dirname(__DIR__)) . DIRECTORY_SEPARATOR . 'config' . DIRECTORY_SEPARATOR . 'path.php';
requireFrom(MODELS_PATH, 'BaseModel.php');

class Venta extends BaseModel {
    protected $table = 'ventas';

    public function __construct() {
        parent::__construct();
    }

    public function crearVenta($idCliente, $productos) {
        try {
            $this->db->beginTransaction();

            // 1. Insertar la venta principal
            $query = "INSERT INTO ventas (id_cliente, subtotal, impuestos, total, estado) 
                      VALUES (:id_cliente, 0, 0, 0, 1)";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':id_cliente', $idCliente);
            $stmt->execute();
            $idVenta = $this->db->lastInsertId();

            // 2. Insertar detalles y calcular totales
            $subtotal = 0;
            foreach($productos as $producto) {
                // Verificar stock
                $stockActual = $this->verificarStock($producto['id_producto'], $producto['cantidad']);
                if($stockActual === false) {
                    throw new Exception("Stock insuficiente para el producto ID: ".$producto['id_producto']);
                }

                // Insertar detalle
                $precioUnitario = $this->obtenerPrecioProducto($producto['id_producto']);
                $subtotalProducto = $precioUnitario * $producto['cantidad'];
                $subtotal += $subtotalProducto;

                $query = "INSERT INTO detalles_venta 
                          (id_venta, id_producto, cantidad, precio_unitario, subtotal)
                          VALUES (:id_venta, :id_producto, :cantidad, :precio_unitario, :subtotal)";
                $stmt = $this->db->prepare($query);
                $stmt->execute([
                    ':id_venta' => $idVenta,
                    ':id_producto' => $producto['id_producto'],
                    ':cantidad' => $producto['cantidad'],
                    ':precio_unitario' => $precioUnitario,
                    ':subtotal' => $subtotalProducto
                ]);

                // Actualizar stock
                $this->actualizarStock($producto['id_producto'], $producto['cantidad']);
            }

            // 3. Calcular impuestos y total (10% de IVA)
            $impuestos = $subtotal * 0.10;
            $total = $subtotal + $impuestos;

            // 4. Actualizar venta con totales
            $query = "UPDATE ventas 
                      SET subtotal = :subtotal, impuestos = :impuestos, total = :total
                      WHERE id_venta = :id_venta";
            $stmt = $this->db->prepare($query);
            $stmt->execute([
                ':subtotal' => $subtotal,
                ':impuestos' => $impuestos,
                ':total' => $total,
                ':id_venta' => $idVenta
            ]);

            $this->db->commit();
            return $idVenta;

        } catch(Exception $e) {
            $this->db->rollBack();
            error_log("Error en Venta::crearVenta: " . $e->getMessage());
            return false;
        }
    }

    public function registrarPago($idVenta, $montoRecibido) {
        try {
            // Obtener total de la venta
            $venta = $this->getById($idVenta);
            if(!$venta) {
                throw new Exception("Venta no encontrada");
            }

            // Validar monto recibido
            if($montoRecibido < $venta['total']) {
                throw new Exception("Monto recibido es menor al total");
            }

            $cambio = $montoRecibido - $venta['total'];

            // Actualizar venta
            $query = "UPDATE ventas 
                      SET monto_recibido = :monto_recibido, cambio = :cambio, estado = 2
                      WHERE id_venta = :id_venta";
            $stmt = $this->db->prepare($query);
            $stmt->execute([
                ':monto_recibido' => $montoRecibido,
                ':cambio' => $cambio,
                ':id_venta' => $idVenta
            ]);

            return true;

        } catch(Exception $e) {
            error_log("Error en Venta::registrarPago: " . $e->getMessage());
            return false;
        }
    }

    private function verificarStock($idProducto, $cantidad) {
        $query = "SELECT stock FROM inventario WHERE id_producto = ? AND estado = 1";
        $stmt = $this->db->prepare($query);
        $stmt->execute([$idProducto]);
        $producto = $stmt->fetch(PDO::FETCH_ASSOC);

        return ($producto && $producto['stock'] >= $cantidad) ? $producto['stock'] : false;
    }

    private function obtenerPrecioProducto($idProducto) {
        $query = "SELECT precio FROM inventario WHERE id_producto = ?";
        $stmt = $this->db->prepare($query);
        $stmt->execute([$idProducto]);
        $producto = $stmt->fetch(PDO::FETCH_ASSOC);
        return $producto['precio'];
    }

    private function actualizarStock($idProducto, $cantidad) {
        $query = "UPDATE inventario SET stock = stock - ? WHERE id_producto = ?";
        $stmt = $this->db->prepare($query);
        return $stmt->execute([$cantidad, $idProducto]);
    }

    
    /**
     * Obtiene una venta con sus detalles y datos del cliente
     */
    public function obtenerConDetalles($idVenta) {
        try {
            // Obtener datos básicos de la venta
            $query = "SELECT v.*, c.nombre as nombre_cliente
                      FROM {$this->table} v
                      LEFT JOIN clientes c ON v.id_cliente = c.id_cliente
                      WHERE v.id_venta = :id";
            
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':id', $idVenta);
            $stmt->execute();
            
            $venta = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if(!$venta) {
                return null;
            }
            
            // Obtener productos de la venta
            $query = "SELECT dv.*, i.nombre 
                      FROM detalles_venta dv
                      JOIN inventario i ON dv.id_producto = i.id_producto
                      WHERE dv.id_venta = :id";
            
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':id', $idVenta);
            $stmt->execute();
            
            $venta['productos'] = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            return $venta;
            
        } catch(PDOException $e) {
            error_log("Error en Venta::obtenerConDetalles: " . $e->getMessage());
            return null;
        }
    }

    /**
     * Obtiene todas las ventas que tienen factura asociada
     */
    public function obtenerVentasFacturadas() {
        try {
            $query = "SELECT v.*, f.numero_factura, f.fecha_emision
                      FROM {$this->table} v
                      JOIN facturacion f ON v.id_venta = f.id_venta
                      ORDER BY v.fecha_venta DESC";
            
            $stmt = $this->db->prepare($query);
            $stmt->execute();
            
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
            
        } catch(PDOException $e) {
            error_log("Error en Venta::obtenerVentasFacturadas: " . $e->getMessage());
            return [];
        }
    }
}
?>