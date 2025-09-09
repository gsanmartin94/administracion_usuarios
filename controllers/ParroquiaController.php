<?php
require_once 'controllers/BaseController.php';
require_once 'models/Parroquia.php';

class ParroquiaController extends BaseController {
    
    //obtiene cantones desde el id Canton
    public function viewByCantonId() {
        $parroquia = new Parroquia();
        $data = $parroquia->getByCantonId($_GET['id']);

        if ($data) {
            echo json_encode([
                'success' => true,
                'parroquias' => $data
            ]);
        } else {
            echo json_encode([
                'success' => false,
                'message' => 'No se encontraron parroquias'
            ]);
        }
    }
}