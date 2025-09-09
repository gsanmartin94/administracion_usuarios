<?php
require_once 'controllers/BaseController.php';
require_once 'models/Submodulo.php';
require_once 'models/Modulo.php';

class SubmoduloController extends BaseController {
    
    //METODO QUE SOLICITA LOS DATOS DE LA TABLA
    public function index() {
        $submodulo = new Submodulo();
        $submodulos = $submodulo->getAll();
        $modulo = new Modulo();
        $modulos = $modulo->getAll();
        include 'views/submodulos/index.php';
    }

    //METODO QUE SOLICITA UN REGISTRO EN ESPECIFICO SEGUN ID
    public function view() {
        $submodulo = new Submodulo();
        $data = $submodulo->getById($_GET['id']);
        
        if ($data) {
            echo json_encode([
                'success' => true,
                'submodulo' => $data
            ]);
        } else {
            echo json_encode([
                'success' => false,
                'message' => 'Submódulo no encontrado'
            ]);
        }
    }

    //METODO QUE SOLICITA GUARDAR EN LA TABLA
    public function store() {
        $submodulo = new Submodulo();
        $result = $submodulo->insert($_POST);
        echo $result;
    }

    //METODO QUE SOLICITA ACTUALIZAR EN LA TABLA
    public function update() {
        $submodulo = new Submodulo();
        $result = $submodulo->update($_POST['id'], $_POST);
        echo $result;
    }

    public function delete() {
        $submodulo = new Submodulo();
        $result = $submodulo->delete($_POST['id']);
        echo $result;
    }
}