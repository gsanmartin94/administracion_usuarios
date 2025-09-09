<?php
require_once 'config/config_munpa_security.php';

class Modulo {
    private $conn;
    private $table = "modulo";

    public function __construct() {
        $db = new Config_munpa_security();
        $this->conn = $db->getConnection();
    }

    public function getAll() {
        $stmt = $this->conn->prepare("SELECT * FROM $this->table");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getAllActive() {
        $stmt = $this->conn->prepare("SELECT * FROM $this->table WHERE estado = 'ACTIVO'");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getById($id) {
        $stmt = $this->conn->prepare("SELECT * FROM $this->table WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function insert($data) {
        try {
            $stmt = $this->conn->prepare("INSERT INTO $this->table (descripcion, estado) VALUES (?, ?)");
            $success = $stmt->execute([
                $data['descripcion'],
                $data['estado']
            ]);
            return $success ? 'success' : 'No se pudo guardar el módulo.';
        } catch (PDOException $e) {
            return 'Error en la base de datos: ' . $e->getMessage();
        }
    }

    public function update($id, $data) {
        try{
            $stmt = $this->conn->prepare("UPDATE $this->table SET descripcion=?, estado=? WHERE id=?");
            $success = $stmt->execute([
                $data['descripcion'],
                $data['estado'],
                $id
            ]);
            return $success ? 'success' : 'No se pudo actualizar el módulo.';
        } catch (PDOException $e) {
            return 'Error en la base de datos: ' . $e->getMessage();
        }
    }

    public function delete($id) {
        try{
            $stmt = $this->conn->prepare("UPDATE $this->table SET estado='INACTIVO' WHERE id=?");
            $success = $stmt->execute([
                $id
            ]);
            return $success ? 'success' : 'No se pudo Eliminar el módulo.';
        } catch (PDOException $e) {
            return 'Error en la base de datos: ' . $e->getMessage();
        }
    }
}