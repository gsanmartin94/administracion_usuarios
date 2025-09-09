<?php
require_once 'config/config_munpa_security.php';

class Persona {
    private $conn;
    private $table = "persona";

    public function __construct() {
        $db = new Config_munpa_security();
        $this->conn = $db->getConnection();
    }

    public function insert($data) {
        try {
            $stmt = $this->conn->prepare("INSERT INTO $this->table (cedula, nombres, apellidos, genero, fecha_nacimiento, telefono, id_parroquia, estado) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
            $success = $stmt->execute([
                $data['cedula'],
                $data['nombres'],
                $data['apellidos'],
                $data['genero'],
                $data['fecha_nacimiento'],
                $data['telefono'],
                $data['id_parroquia'],
                $data['estado']
            ]);
            if ($success) {
                // devolver el id de la persona insertada
                return $this->conn->lastInsertId();
            } else {
                return false;
            }
        } catch (PDOException $e) {
            return 'Error en la base de datos: ' . $e->getMessage();
        }
    }

    public function update($id, $data) {
        try{
            $stmt = $this->conn->prepare("UPDATE $this->table SET cedula=?, nombres=?, apellidos=?, genero=?, fecha_nacimiento=?, telefono=?, id_parroquia=?, estado=? WHERE id=?");
            $success = $stmt->execute([
                $data['cedula'],
                $data['nombres'],
                $data['apellidos'],
                $data['genero'],
                $data['fecha_nacimiento'],
                $data['telefono'],
                $data['id_parroquia'],
                $data['estado'],
                $id
            ]);
            return $success ? 'success' : 'No se pudo actualizar la Persona.';
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