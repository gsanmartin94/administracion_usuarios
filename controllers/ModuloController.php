<?php
require_once 'controllers/BaseController.php';
require_once 'models/Modulo.php';

class ModuloController extends BaseController {
    
    //METODO QUE SOLICITA LOS DATOS DE LA TABLA
    public function index() {
        $modulo = new Modulo();
        $modulos = $modulo->getAll();
        include 'views/modulos/index.php';
    }

    //METODO QUE SOLICITA UN REGISTRO EN ESPECIFICO SEGUN ID
    public function view() {
        $modulo = new Modulo();
        $data = $modulo->getById($_GET['id']);
        
        if ($data) {
            echo json_encode([
                'success' => true,
                'modulo' => $data
            ]);
        } else {
            echo json_encode([
                'success' => false,
                'message' => 'Módulo no encontrado'
            ]);
        }
    }

    //METODO QUE SOLICITA GUARDAR EN LA TABLA
    public function store() {
        $modulo = new Modulo();
        $result = $modulo->insert($_POST);
        echo $result;
    }

    //METODO QUE SOLICITA ACTUALIZAR EN LA TABLA
    public function update() {
        $modulo = new Modulo();
        $result = $modulo->update($_POST['id'], $_POST);
        echo $result;
    }

    public function delete() {
        $modulo = new Modulo();
        $result = $modulo->delete($_POST['id']);
        echo $result;
    }
}