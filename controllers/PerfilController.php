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

class PerfilController extends BaseController {
    
    //METODO QUE SOLICITA LOS DATOS DE LA TABLA
    public function index() {
        $usuario = new Usuario();
        $perfilUsuario = $usuario->getById($_GET['id']);
        $id = ($_GET['id']);

        if (!$perfilUsuario) {
        // Manejo de error
        $error = "El usuario no existe o el ID es inválido.";
        include 'views/perfil/error.php'; // podrías hacer una vista de error
        return;
    }
        /**DATOS EXTRA*/
        $tipo_usuario = new TipoUsuario();
        $tipos_usuarios = $tipo_usuario->getAll();
        $provincia = new Provincia();
        $provincias = $provincia->getAll();
        $rol = new Rol();
        $roles = $rol->getAll();

        include 'views/perfil/index.php';
    }
}