<?php
require_once 'controllers/BaseController.php';
require_once 'models/Rol.php';
require_once 'models/Modulo.php';
require_once 'models/Submodulo.php';
require_once 'models/Permiso.php';

class RolController extends BaseController {
    
    //METODO QUE SOLICITA LOS DATOS DE LA TABLA
    public function index() {
        $rol = new Rol();
        $roles = $rol->getAll();
        include 'views/roles/index.php';
    }

    //METODO QUE SOLICITA UN REGISTRO EN ESPECIFICO SEGUN ID
    public function view() {
        $rol = new Rol();
        $data = $rol->getById($_GET['id']);
        
        if ($data) {
            echo json_encode([
                'success' => true,
                'rol' => $data
            ]);
        } else {
            echo json_encode([
                'success' => false,
                'message' => 'Rol no encontrado'
            ]);
        }
    }

    //METODO QUE SOLICITA GUARDAR EN LA TABLA
    public function store() {
        $rol = new Rol();
        $result = $rol->insert($_POST);
        echo $result;
    }

    //METODO QUE SOLICITA ACTUALIZAR EN LA TABLA
    public function update() {
        $rol = new Rol();
        $result = $rol->update($_POST['id'], $_POST);
        echo $result;
    }

    public function delete() {
        $rol = new Rol();
        $result = $rol->delete($_POST['id']);
        echo $result;
    }

    public function indexRolesPermisos() {
        $id_rol = ($_GET['id']);
        $rol_descripcion = ($_GET['descripcion']);

        $modulo = new Modulo();
        $modulos = $modulo->getAllActive();

        $submodulo = new Submodulo();
        $submodulos = $submodulo->getAllActive();

        $permiso = new Permiso();
        $permisos = $permiso->getAllActive();

        $rol = new Rol();
        $permisosRol = $rol->getPermisosById($_GET['id']);
        $permisosRol = array_map('intval', $permisosRol);

        // 5. rearmar arrays
        $subPorModulo = [];
        foreach ($submodulos as $s) {
            $subPorModulo[$s['id_modulo']][] = $s;
        }

        $permsPorSub = [];
        foreach ($permisos as $p) {
            $permsPorSub[$p['id_submodulo']][] = $p;
        }

        include 'views/roles_permisos/index.php';
    }

    public function storePermisos() {
        $rol = new Rol();

        $id_rol = isset($_POST['id_rol']) ? intval($_POST['id_rol']) : 0;
        $permisos = isset($_POST['permisos']) ? $_POST['permisos'] : [];

        $result = $rol->insertPermisosRol($id_rol, $permisos);

        // Devolver respuesta JSON
        header('Content-Type: application/json');
        echo json_encode($result);
    }
}