<?php
require_once 'config/config_munpa_security.php';

class Permiso {
    private $conn;
    private $table = "permiso";

    public function __construct() {
        $db = new Config_munpa_security();
        $this->conn = $db->getConnection();
    }

    public function getAll() {
        $stmt = $this->conn->prepare("SELECT 
        p.id,
        p.descripcion,
        p.estado,
        s.descripcion AS submodulo
        FROM $this->table p
        INNER JOIN submodulo s ON s.id = p.id_submodulo
        WHERE s.estado = 'ACTIVO'");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getAllActive() {
        $stmt = $this->conn->prepare("SELECT * FROM $this->table WHERE estado = 'ACTIVO'");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getById($id) {
        $stmt = $this->conn->prepare("SELECT 
        p.id,
        p.descripcion,
        p.estado,
        p.id_submodulo,
        s.descripcion AS submodulo
        FROM $this->table p
        INNER JOIN submodulo s ON s.id = p.id_submodulo
        WHERE p.id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function insert($data) {
        try {
            $stmt = $this->conn->prepare("INSERT INTO $this->table (id_submodulo, descripcion, estado) VALUES (?, ?, ?)");
            $success = $stmt->execute([
                $data['id_submodulo'],
                $data['descripcion'],
                $data['estado']
            ]);
            return $success ? 'success' : 'No se pudo guardar el permiso.';
        } catch (PDOException $e) {
            return 'Error en la base de datos: ' . $e->getMessage();
        }
    }

    public function update($id, $data) {
        try{
            $stmt = $this->conn->prepare("UPDATE $this->table SET id_submodulo=?, descripcion=?, estado=? WHERE id=?");
            $success = $stmt->execute([
                $data['id_submodulo'],
                $data['descripcion'],
                $data['estado'],
                $id
            ]);
            return $success ? 'success' : 'No se pudo actualizar el permiso.';
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
            return $success ? 'success' : 'No se pudo Eliminar el permiso.';
        } catch (PDOException $e) {
            return 'Error en la base de datos: ' . $e->getMessage();
        }
    }
}