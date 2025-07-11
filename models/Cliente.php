<?php
require_once realpath(dirname(__DIR__)) . DIRECTORY_SEPARATOR . 'config' . DIRECTORY_SEPARATOR . 'path.php';
requireFrom(MODELS_PATH, 'BaseModel.php');

class Cliente extends BaseModel {
    protected $table = 'clientes';

    public function buscar($termino) {
        $query = "SELECT * FROM {$this->table} 
                 WHERE (nombre LIKE :termino OR email LIKE :termino OR telefono LIKE :termino)
                 AND estado = 1
                 LIMIT 10";
        
        $stmt = $this->db->prepare($query);
        $termino = "%$termino%";
        $stmt->bindParam(':termino', $termino);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function crear($nombre, $email, $telefono, $direccion) {
        $query = "INSERT INTO {$this->table} (nombre, email, telefono, direccion, estado) VALUES (?, ?, ?, ?, 1)";
        $stmt = $this->db->prepare($query);
        return $stmt->execute([$nombre, $email, $telefono, $direccion]);
    }
}
?>