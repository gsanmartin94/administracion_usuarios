<?php
session_start();

// Función para verificar si hay sesión iniciada
function usuarioAutenticado() {
    if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
	    return false;
    }else{
        return true;
    }
}

// Valida si el usuario tiene un permisos
function PermisosModulosSubmodulos() {
    //una vez que se valida el inicio de session
    $idUsuario = $_SESSION['idUsuario'];
    require_once 'config/config_munpa_security.php';
    $db = new Config_munpa_security();
    $conn = $db->getConnection();

    $sql = "SELECT 
                m.descripcion AS modulo,
                sm.descripcion AS submodulo,
                sm.ruta,
                p.descripcion AS permiso
            FROM usuario u
            JOIN usuario_permiso up ON up.id_usuario = u.id AND up.estado = 'ACTIVO'
            JOIN permiso p ON p.id = up.id_permiso AND p.estado = 'ACTIVO'
            JOIN submodulo sm ON sm.id = p.id_submodulo AND sm.estado = 'ACTIVO'
            JOIN modulo m ON m.id = sm.id_modulo AND m.estado = 'ACTIVO'
            WHERE u.id = ?";
            //ORDER BY m.descripcion, sm.descripcion;";
    $stmt = $conn->prepare($sql);
    $stmt->execute([$idUsuario]);

    $permisos = [];
    while ($row = $stmt->fetch()) {
        $modulo = strtoupper($row['modulo']);
        $submodulo = $row['submodulo'];
        $ruta = $row['ruta'];

        // Evita duplicados
        $yaExiste = false;
        if (isset($permisos[$modulo])) {
            foreach ($permisos[$modulo] as $perm) {
                if ($perm['nombre'] === $submodulo && $perm['ruta'] === $ruta) {
                    $yaExiste = true;
                    break;
                }
            }
        }

        if (!$yaExiste) {
            $permisos[$modulo][] = [
                'nombre' => $submodulo,
                'ruta' => $ruta
            ];
        }
    }

    return $permisos;
}

function PermisosSubmodulos($submodulo, $permisos) {
    //una vez que se valida el inicio de session
    $idUsuario = $_SESSION['idUsuario'];

    require_once 'config/config_munpa_security.php';
    $db = new Config_munpa_security();
    $conn = $db->getConnection();

    $sql = "SELECT p.descripcion
            FROM usuario_permiso up
            JOIN permiso p ON p.id = up.id_permiso
            JOIN submodulo s ON s.id = p.id_submodulo
            WHERE up.id_usuario = ?
              AND s.descripcion = ?
              AND up.estado = 'ACTIVO'
              AND p.estado = 'ACTIVO'
              AND s.estado = 'ACTIVO'";

    $stmt = $conn->prepare($sql);
    $stmt->execute([$idUsuario, $submodulo]);

    while ($row = $stmt->fetch()) {
        $permiso = strtoupper($row['descripcion']);
        if (isset($permisos[$permiso])) {
            $permisos[$permiso] = true;
        }
    }

    return $permisos;
}

function datosUser(){
    $idUsuario = $_SESSION['idUsuario'];

    require_once 'config/config_munpa_security.php';
    $db = new Config_munpa_security();
    $conn = $db->getConnection();

    $sql = "SELECT 
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
                pro.id As id_provincia
            FROM usuario u
            INNER JOIN persona p ON p.id = u.id_persona
            INNER JOIN rol r ON r.id = u.id_rol
            INNER JOIN tipo_usuario t ON t.id = u.id_tipo_usuario
            INNER JOIN parroquia pa ON pa.id = p.id_parroquia
            INNER JOIN canton ca ON ca.id = pa.id_canton
            INNER JOIN provincia pro ON pro.id = ca.id_provincia
            WHERE u.id = ?";

    $stmt = $conn->prepare($sql);
    $stmt->execute([$idUsuario]);

    // Devuelve un array asociativo con los datos
    $datosUsuario = $stmt->fetch(PDO::FETCH_ASSOC);

    return $datosUsuario;
}

