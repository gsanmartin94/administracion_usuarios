<?php
require_once 'config/config_munpa_security.php';

class Parroquia {
    private $conn;
    private $table = "parroquia";

    public function __construct() {
        $db = new Config_munpa_security();
        $this->conn = $db->getConnection();
    }

    public function getByCantonId($id) {
        $stmt = $this->conn->prepare("SELECT * FROM $this->table WHERE id_canton = ?");
        $stmt->execute([$id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}