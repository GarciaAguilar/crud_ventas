<?php
require_once 'BaseModel.php';

class Inventario extends BaseModel {
    protected $table = 'inventario';

    public function __construct() {
        parent::__construct();
    }

    public function crear($data) {
        $query = "INSERT INTO {$this->table} 
                 (nombre, descripcion, precio, costo, stock, categoria, proveedor, estado) 
                 VALUES (:nombre, :descripcion, :precio, :costo, :stock, :categoria, :proveedor, :estado)";
        
        $stmt = $this->db->prepare($query);
        return $stmt->execute($data);
    }

    public function actualizar($id, $data) {
        $query = "UPDATE {$this->table} 
                  SET nombre = :nombre, 
                      descripcion = :descripcion, 
                      precio = :precio, 
                      costo = :costo, 
                      stock = :stock, 
                      categoria = :categoria, 
                      proveedor = :proveedor, 
                      estado = :estado,
                      fecha_ingreso = CURRENT_TIMESTAMP
                  WHERE id_producto = :id";
        
        $data['id'] = $id;
        $stmt = $this->db->prepare($query);
        return $stmt->execute($data);
    }

    public function buscar($termino) {
        $query = "SELECT * FROM {$this->table} 
                 WHERE nombre LIKE :termino 
                 OR descripcion LIKE :termino 
                 OR categoria LIKE :termino";
        
        $stmt = $this->db->prepare($query);
        $termino = "%$termino%";
        $stmt->bindParam(':termino', $termino);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getById($id) {
        $query = "SELECT * FROM {$this->table} WHERE id_producto = :id LIMIT 1";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function delete($id) {
        $query = "DELETE FROM {$this->table} WHERE id_producto = :id";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':id', $id);
        return $stmt->execute();
    }

      public function getProductosDisponibles() {
        $query = "SELECT * FROM {$this->table} WHERE stock > 0 AND estado = 1";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>