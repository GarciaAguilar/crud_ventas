<?php
class Database {
    private $host = 'localhost';
    private $db_name = 'crud_ventas';
    private $username = 'root'; 
    private $password = '';    
    private $conn;

    public function connect() {
        $this->conn = null;

        try {
            $this->conn = new PDO(
                'mysql:host=' . $this->host . ';dbname=' . $this->db_name,
                $this->username, 
                $this->password
            );
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->conn->exec("SET NAMES 'utf8'");
        } catch(PDOException $e) {
            error_log('Error de conexión: ' . $e->getMessage());
            die('Error al conectar con la base de datos');
        }

        return $this->conn;
    }
}
?>