<?php
require_once 'controllers/BaseController.php';
require_once 'models/Usuario.php';
//require_once 'models/Persona.php';
require_once 'models/Rol.php';
require_once 'models/TipoUsuario.php';
require_once 'models/Provincia.php';
require_once 'models/Modulo.php';
require_once 'models/Submodulo.php';
require_once 'models/Permiso.php';


class UsuarioController extends BaseController {
    
    //METODO QUE SOLICITA LOS DATOS DE LA TABLA
    public function index() {
        $usuario = new Usuario();
        $usuarios = $usuario->getAll();

        /**DATOS EXTRA*/
        $tipo_usuario = new TipoUsuario();
        $tipos_usuarios = $tipo_usuario->getAll();
        $provincia = new Provincia();
        $provincias = $provincia->getAll();
        $rol = new Rol();
        $roles = $rol->getAll();

        /**VISTA */
        include 'views/usuarios/index.php';
    }

    //METODO QUE SOLICITA UN REGISTRO EN ESPECIFICO SEGUN ID
    public function view() {
        $usuario = new Usuario();
        $data = $usuario->getById($_GET['id']);
        
        if ($data) {
            echo json_encode([
                'success' => true,
                'usuario' => $data
            ]);
        } else {
            echo json_encode([
                'success' => false,
                'message' => 'Usuario no encontrado'
            ]);
        }
    }

    //verifica si existe cedula y correo con un usuario INTERNO
    public function validate() {
        $usuario = new Usuario();
        $data = $usuario->Validate($_GET['username']);
        
        if ($data) {
            echo json_encode([
                'success' => true,
                'usuario' => $data
            ]);
        } else {
            echo json_encode([
                'success' => false,
                'message' => 'Usuario no encontrado'
            ]);
        }
    }

    //METODO QUE SOLICITA GUARDAR EN LA TABLA
    public function store() {
        $usuario = new Usuario();
        $result = $usuario->insert($_POST);
        echo $result;
    }

    //METODO QUE SOLICITA ACTUALIZAR EN LA TABLA
    public function update() {
        $usuario = new Usuario();
        $result = $usuario->update($_POST['id'], $_POST);
        echo $result;
    }

    public function delete() {
        $usuario = new Usuario();
        $result = $usuario->delete($_POST['id']);
        echo $result;
    }

    public function updatePassword() {
        $usuario = new Usuario();
        $result = $usuario->updatePassword($_POST['id'], $_POST);
        echo $result;
    }

    public function indexUsuariosPermisos() {
        $id_usuario = ($_GET['id']);
        $username = ($_GET['username']);

        $modulo = new Modulo();
        $modulos = $modulo->getAllActive();

        $submodulo = new Submodulo();
        $submodulos = $submodulo->getAllActive();

        $permiso = new Permiso();
        $permisos = $permiso->getAllActive();

        $permisosUsuarioTotales = [];
        $usuario = new Usuario();
        $permisosUsuarioTotales = $usuario->getPermisosByUserId($id_usuario);
        $permisosUsuarioTotales = array_map('intval', $permisosUsuarioTotales);

        // 5. rearmar arrays
        $subPorModulo = [];
        foreach ($submodulos as $s) {
            $subPorModulo[$s['id_modulo']][] = $s;
        }

        $permsPorSub = [];
        foreach ($permisos as $p) {
            $permsPorSub[$p['id_submodulo']][] = $p;
        }

        include 'views/usuarios_permisos/index.php';
    }

    public function storePermisos() {
        $usuario = new Usuario();

        $id_usuario = isset($_POST['id_usuario']) ? intval($_POST['id_usuario']) : 0;
        $permisos = isset($_POST['permisos']) ? $_POST['permisos'] : [];

        $result = $usuario->insertPermisosUsuario($id_usuario, $permisos);

        // Devolver respuesta JSON
        header('Content-Type: application/json');
        echo json_encode($result);
    }
}