<?php
require_once 'config/config_munpa_security.php';

class Usuario {
    private $conn;
    private $table = "usuario";

    public function __construct() {
        $db = new Config_munpa_security();
        $this->conn = $db->getConnection();
    }

    public function getAll() {
        $stmt = $this->conn->prepare("SELECT 
        u.id,
        u.id_persona,
        u.id_rol,
        u.id_tipo_usuario,
        u.correo,
        u.username,
        u.estado,
        p.cedula AS cedula,
        p.nombres AS nombres,
        p.apellidos AS apellidos,
        p.genero AS genero,
        p.fecha_nacimiento AS fecha_nacimiento,
        p.telefono AS telefono,
        r.descripcion AS rol,
        t.descripcion AS tipo_usuario
        FROM $this->table u
        INNER JOIN persona p ON p.id = u.id_persona
        INNER JOIN rol r ON r.id = u.id_rol
        INNER JOIN tipo_usuario t ON t.id = u.id_tipo_usuario");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getById($id) {
        $stmt = $this->conn->prepare("SELECT 
        u.id,
        u.id_persona,
        u.id_rol,
        u.id_tipo_usuario,
        u.correo,
        u.username,
        u.estado,
        p.cedula AS cedula,
        p.nombres AS nombres,
        p.apellidos AS apellidos,
        p.genero AS genero,
        p.fecha_nacimiento AS fecha_nacimiento,
        p.telefono AS telefono,
        r.descripcion AS rol,
        t.descripcion AS tipo_usuario,
        pa.descripcion As parroquia,
        pa.id As id_parroquia,
        ca.id As id_canton,
        ca.descripcion As canton,
        pro.id As id_provincia,
        pro.descripcion As provincia
        FROM $this->table u
        INNER JOIN persona p ON p.id = u.id_persona
        INNER JOIN rol r ON r.id = u.id_rol
        INNER JOIN tipo_usuario t ON t.id = u.id_tipo_usuario
        INNER JOIN parroquia pa ON pa.id = p.id_parroquia
        INNER JOIN canton ca ON ca.id = pa.id_canton
        INNER JOIN provincia pro ON pro.id = ca.id_provincia
        WHERE u.id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    //METODO ACTUALIZADO PARA VALIDAR SOLO POR USERNAME
    public function validate($username)
    {
        $stmt = $this->conn->prepare("SELECT 
                p.id AS id_persona,
                u.id AS id_usuario,
                u.id_tipo_usuario
            FROM usuario u
            INNER JOIN persona p ON p.id = u.id_persona
            WHERE u.id_tipo_usuario = 1
              AND u.username = ?
            ");
        $stmt->execute([$username]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function insert($data) {
        try {

            $this->conn->beginTransaction();

            //INSERTAR EL USUARIO
            $stmt = $this->conn->prepare("INSERT INTO $this->table (id_persona, id_rol, id_tipo_usuario, correo, username, password, estado) VALUES (?, ?, ?, ? , ?, ?, ?)");
            //se encripta la contraseña
            $hashedPassword = password_hash($data['password'], PASSWORD_BCRYPT);
            //guardar en la BD -> USUARIO
            $success = $stmt->execute([
                $data['id_persona'],
                $data['id_rol'],
                $data['id_tipo_usuario'],
                $data['correo'],
                $data['username'],
                $hashedPassword,
                $data['estado']
            ]);

            //PREGUNTA SI SE PUDO INGRESAR
            if (!$success) {
                $this->conn->rollBack();
                return 'No se pudo guardar el usuario.';
            }

            // 2. Obtener ID del usuario insertado
            $idUsuario = $this->conn->lastInsertId();

            // 3. Consultar permisos del rol
            $stmtPermisos = $this->conn->prepare("SELECT id_permiso FROM rol_permiso WHERE id_rol = ? AND estado = 'ACTIVO'");
            $stmtPermisos->execute([$data['id_rol']]);
            $permisos = $stmtPermisos->fetchAll(PDO::FETCH_COLUMN);

            // 4. Insertar permisos al usuario
            if (!empty($permisos)) {
                $stmtInsert = $this->conn->prepare("INSERT INTO usuario_permiso (id_usuario, id_permiso, estado) VALUES (?, ?, 'ACTIVO')");
                foreach ($permisos as $permiso) {
                    $stmtInsert->execute([$idUsuario, $permiso]);
                }
            }

            $this->conn->commit();
            return 'success';

        } catch (PDOException $e) {
            $this->conn->rollBack();
            return 'Error en la base de datos: ' . $e->getMessage();
        }
    }

    public function update($id, $data) {
        try {
            $this->conn->beginTransaction();

            // 1. Actualizar datos del usuario
            $stmt = $this->conn->prepare("UPDATE $this->table 
                SET id_persona=?, id_rol=?, id_tipo_usuario=?, correo=?, username=?, estado=? 
                WHERE id=?");
            
            $success = $stmt->execute([
                $data['id_persona'],
                $data['id_rol'],
                $data['id_tipo_usuario'],
                $data['correo'],
                $data['username'],
                $data['estado'],
                $id
            ]);

            if (!$success) {
                $this->conn->rollBack();
                return 'No se pudo actualizar el usuario.';
            }

            // 2. Obtener permisos del nuevo rol
            $stmtPermisos = $this->conn->prepare("SELECT id_permiso FROM rol_permiso WHERE id_rol = ? AND estado = 'ACTIVO'");
            $stmtPermisos->execute([$data['id_rol']]);
            $permisosRol = $stmtPermisos->fetchAll(PDO::FETCH_COLUMN);

            // 3. Desactivar todos los permisos del usuario
            $stmt = $this->conn->prepare("UPDATE usuario_permiso SET estado = 'INACTIVO' WHERE id_usuario = ?");
            $stmt->execute([$id]);

            // Preparar queries
            $stmtSelect = $this->conn->prepare("SELECT id FROM usuario_permiso WHERE id_usuario = ? AND id_permiso = ?");
            $stmtUpdate = $this->conn->prepare("UPDATE usuario_permiso SET estado = 'ACTIVO' WHERE id_usuario = ? AND id_permiso = ?");
            $stmtInsert = $this->conn->prepare("INSERT INTO usuario_permiso (id_usuario, id_permiso, estado) VALUES (?, ?, 'ACTIVO')");

            // 4. Sincronizar permisos
            foreach ($permisosRol as $id_permiso) {
                $stmtSelect->execute([$id, $id_permiso]);
                $existe = $stmtSelect->fetchColumn();

                if ($existe) {
                    $stmtUpdate->execute([$id, $id_permiso]);
                } else {
                    $stmtInsert->execute([$id, $id_permiso]);
                }
            }

            $this->conn->commit();
            return 'success';

        } catch (Exception $e) {
            $this->conn->rollBack();
            return 'Error en la base de datos: ' . $e->getMessage();
        }
    }

    public function delete($id) {
        try{
            $stmt = $this->conn->prepare("UPDATE $this->table SET estado='INACTIVO' WHERE id=?");
            $success = $stmt->execute([
                $id
            ]);
            return $success ? 'success' : 'No se pudo Eliminar el usuario.';
        } catch (PDOException $e) {
            return 'Error en la base de datos: ' . $e->getMessage();
        }
    }

    public function updatePassword($id, $data) {
        try{
            //se encripta la contraseña
            $hashedPassword = password_hash($data['password'], PASSWORD_BCRYPT);

            $stmt = $this->conn->prepare("UPDATE $this->table SET password=? WHERE id=?");
            $success = $stmt->execute([
                $hashedPassword,
                $id
            ]);
            return $success ? 'success' : 'No se pudo actualizar la contraseña.';
        } catch (PDOException $e) {
            return 'Error en la base de datos: ' . $e->getMessage();
        }
    }

    public function getPermisosByUserId($id)
    {
        $stmt = $this->conn->prepare("SELECT id_permiso FROM usuario_permiso WHERE id_usuario = ? AND estado = 'ACTIVO'");
        $stmt->execute([$id]);
        return $stmt->fetchAll(PDO::FETCH_COLUMN, 0);
    }

    public function insertPermisosUsuario($id_usuario, $permisosSeleccionados = [])
    {
        $id_usuario = intval($id_usuario);
        $permisosSeleccionados = array_map('intval', $permisosSeleccionados);

        try {
            $this->conn->beginTransaction();

            // Desactivar todos los permisos
            $stmt = $this->conn->prepare("UPDATE usuario_permiso SET estado = 'INACTIVO' WHERE id_usuario = ?");
            $stmt->execute([$id_usuario]);

            // Preparar queries
            $stmtSelect = $this->conn->prepare("SELECT id FROM usuario_permiso WHERE id_usuario = ? AND id_permiso = ?");
            $stmtUpdate = $this->conn->prepare("UPDATE usuario_permiso SET estado = 'ACTIVO' WHERE id_usuario = ? AND id_permiso = ?");
            $stmtInsert = $this->conn->prepare("INSERT INTO usuario_permiso (id_usuario, id_permiso, estado) VALUES (?, ?, 'ACTIVO')");

            foreach ($permisosSeleccionados as $id_permiso) {
                $stmtSelect->execute([$id_usuario, $id_permiso]);
                $existe = $stmtSelect->fetchColumn();

                if ($existe) {
                    $stmtUpdate->execute([$id_usuario, $id_permiso]);
                } else {
                    $stmtInsert->execute([$id_usuario, $id_permiso]);
                }
            }

            $this->conn->commit();
            return ["success" => true];
        } catch (Exception $e) {
            $this->conn->rollBack();
            return ["success" => false, "message" => $e->getMessage()];
        }
    }

    public function getAllInternos() {
        $stmt = $this->conn->prepare("SELECT 
        u.id,
        u.id_persona,
        u.id_rol,
        u.id_tipo_usuario,
        u.correo,
        u.username,
        u.estado,
        p.cedula AS cedula,
        p.nombres AS nombres,
        p.apellidos AS apellidos,
        p.genero AS genero,
        p.fecha_nacimiento AS fecha_nacimiento,
        p.telefono AS telefono,
        r.descripcion AS rol,
        t.descripcion AS tipo_usuario
        FROM munpa_seguridad.usuario u
        INNER JOIN munpa_seguridad.persona p ON p.id = u.id_persona
        INNER JOIN munpa_seguridad.rol r ON r.id = u.id_rol
        INNER JOIN munpa_seguridad.tipo_usuario t ON t.id = u.id_tipo_usuario
        WHERE u.id_tipo_usuario = 1
        AND u.id NOT IN (
            SELECT f.id_usuario 
            FROM munpa_tramites.funcionario f
        )");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}