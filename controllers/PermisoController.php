<?php
require_once 'controllers/BaseController.php';
require_once 'models/Submodulo.php';
require_once 'models/Permiso.php';

class PermisoController extends BaseController {
    
    //METODO QUE SOLICITA LOS DATOS DE LA TABLA
    public function index() {
        $permiso = new Permiso();
        $permisos = $permiso->getAll();
        $submodulo = new Submodulo();
        $submodulos = $submodulo->getAll();
        include 'views/permisos/index.php';
    }

    //METODO QUE SOLICITA UN REGISTRO EN ESPECIFICO SEGUN ID
    public function view() {
        $permiso = new Permiso();
        $data = $permiso->getById($_GET['id']);
        
        if ($data) {
            echo json_encode([
                'success' => true,
                'permiso' => $data
            ]);
        } else {
            echo json_encode([
                'success' => false,
                'message' => 'Permiso no encontrado'
            ]);
        }
    }

    //METODO QUE SOLICITA GUARDAR EN LA TABLA
    public function store() {
        $permiso = new Permiso();
        $result = $permiso->insert($_POST);
        echo $result;
    }

    //METODO QUE SOLICITA ACTUALIZAR EN LA TABLA
    public function update() {
        $permiso = new Permiso();
        $result = $permiso->update($_POST['id'], $_POST);
        echo $result;
    }

    public function delete() {
        $permiso = new Permiso();
        $result = $permiso->delete($_POST['id'], $_POST);
        echo $result;
    }
}