<?php
require_once 'config/config_munpa_security.php';

class Submodulo {
    private $conn;
    private $table = "submodulo";

    public function __construct() {
        $db = new Config_munpa_security();
        $this->conn = $db->getConnection();
    }

    public function getAll() {
        $stmt = $this->conn->prepare("SELECT 
        s.id,
        s.descripcion,
        s.ruta,
        s.estado,
        m.descripcion AS modulo
        FROM $this->table s
        INNER JOIN modulo m ON m.id = s.id_modulo
        WHERE m.estado = 'ACTIVO'");
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
        s.id,
        s.descripcion,
        s.ruta,
        s.estado,
        s.id_modulo,
        m.descripcion AS modulo
        FROM $this->table s
        INNER JOIN modulo m ON m.id = s.id_modulo
        WHERE s.id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function insert($data) {
        try {
            $stmt = $this->conn->prepare("INSERT INTO $this->table (id_modulo, descripcion, ruta, estado) VALUES (?, ?, ?, ?)");
            $success = $stmt->execute([
                $data['id_modulo'],
                $data['descripcion'],
                $data['ruta'],
                $data['estado']
            ]);
            return $success ? 'success' : 'No se pudo guardar el submódulo.';
        } catch (PDOException $e) {
            return 'Error en la base de datos: ' . $e->getMessage();
        }
    }

    public function update($id, $data) {
        try{
            $stmt = $this->conn->prepare("UPDATE $this->table SET id_modulo=?, descripcion=?, ruta=?, estado=? WHERE id=?");
            $success = $stmt->execute([
                $data['id_modulo'],
                $data['descripcion'],
                $data['ruta'],
                $data['estado'],
                $id
            ]);
            return $success ? 'success' : 'No se pudo actualizar el submódulo.';
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
            return $success ? 'success' : 'No se pudo Eliminar el submódulo.';
        } catch (PDOException $e) {
            return 'Error en la base de datos: ' . $e->getMessage();
        }
    }
}