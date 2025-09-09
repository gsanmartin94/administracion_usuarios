<?php
require_once 'config/config_munpa_security.php';

class Rol
{
    private $conn;
    private $table = "rol";

    public function __construct()
    {
        $db = new Config_munpa_security();
        $this->conn = $db->getConnection();
    }

    public function getAll()
    {
        $stmt = $this->conn->prepare("SELECT * FROM $this->table");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getById($id)
    {
        $stmt = $this->conn->prepare("SELECT * FROM $this->table WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function insert($data)
    {
        try {
            $stmt = $this->conn->prepare("INSERT INTO $this->table (descripcion, estado) VALUES (?, ?)");
            $success = $stmt->execute([
                $data['descripcion'],
                $data['estado']
            ]);
            return $success ? 'success' : 'No se pudo guardar el rol.';
        } catch (PDOException $e) {
            return 'Error en la base de datos: ' . $e->getMessage();
        }
    }

    public function update($id, $data)
    {
        try {
            $stmt = $this->conn->prepare("UPDATE $this->table SET descripcion=?, estado=? WHERE id=?");
            $success = $stmt->execute([
                $data['descripcion'],
                $data['estado'],
                $id
            ]);
            return $success ? 'success' : 'No se pudo actualizar el rol.';
        } catch (PDOException $e) {
            return 'Error en la base de datos: ' . $e->getMessage();
        }
    }

    public function delete($id)
    {
        try {
            $stmt = $this->conn->prepare("UPDATE $this->table SET estado='INACTIVO' WHERE id=?");
            $success = $stmt->execute([
                $id
            ]);
            return $success ? 'success' : 'No se pudo Eliminar el rol.';
        } catch (PDOException $e) {
            return 'Error en la base de datos: ' . $e->getMessage();
        }
    }

    public function getPermisosById($id)
    {
        $stmt = $this->conn->prepare("SELECT id_permiso FROM rol_permiso WHERE id_rol = ? AND estado = 'ACTIVO'");
        $stmt->execute([$id]);
        return $stmt->fetchAll(PDO::FETCH_COLUMN, 0);
    }

    public function insertPermisosRol($id_rol, $permisosSeleccionados = [])
    {
        $id_rol = intval($id_rol);
        $permisosSeleccionados = array_map('intval', $permisosSeleccionados);

        try {
            $this->conn->beginTransaction();

            // Desactivar todos los permisos
            $stmt = $this->conn->prepare("UPDATE rol_permiso SET estado = 'INACTIVO' WHERE id_rol = ?");
            $stmt->execute([$id_rol]);

            // Preparar queries
            $stmtSelect = $this->conn->prepare("SELECT id FROM rol_permiso WHERE id_rol = ? AND id_permiso = ?");
            $stmtUpdate = $this->conn->prepare("UPDATE rol_permiso SET estado = 'ACTIVO' WHERE id_rol = ? AND id_permiso = ?");
            $stmtInsert = $this->conn->prepare("INSERT INTO rol_permiso (id_rol, id_permiso, estado) VALUES (?, ?, 'ACTIVO')");

            foreach ($permisosSeleccionados as $id_permiso) {
                $stmtSelect->execute([$id_rol, $id_permiso]);
                $existe = $stmtSelect->fetchColumn();

                if ($existe) {
                    $stmtUpdate->execute([$id_rol, $id_permiso]);
                } else {
                    $stmtInsert->execute([$id_rol, $id_permiso]);
                }
            }

            $this->conn->commit();
            return ["success" => true];
        } catch (Exception $e) {
            $this->conn->rollBack();
            return ["success" => false, "message" => $e->getMessage()];
        }
    }
}