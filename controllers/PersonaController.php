<?php
require_once 'controllers/BaseController.php';
require_once 'models/Persona.php';

class PersonaController extends BaseController {

    //METODO QUE SOLICITA GUARDAR EN LA TABLA
    public function store() {
        $persona = new Persona();
        $result = $persona->insert($_POST);
        if ($result && is_numeric($result)) {
            // Insert fue exitoso y devolvió el id
            echo json_encode([
                "success" => true,
                "data" => $result // id_persona insertado
            ]);
        } else {
            // Error en la inserción
            echo json_encode([
                "success" => false,
                "data" => $result // mensaje de error
            ]);
        }
    }

    //METODO QUE SOLICITA ACTUALIZAR EN LA TABLA
    public function update() {
        $persona = new Persona();
        $result = $persona->update($_POST['id'], $_POST);
        echo $result;
    }

    public function delete() {
        $persona = new Persona();
        $result = $persona->update($_POST['id'], $_POST);
        echo $result;
    }
}