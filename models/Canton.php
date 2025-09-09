<?php
require_once 'config/config_munpa_security.php';

class Canton {
    private $conn;
    private $table = "canton";

    public function __construct() {
        $db = new Config_munpa_security();
        $this->conn = $db->getConnection();
    }

    public function getByProvinciaId($id) {
        $stmt = $this->conn->prepare("SELECT * FROM $this->table WHERE id_provincia = ?");
        $stmt->execute([$id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}