<?php
require_once 'BaseModel.php';

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
}
?>