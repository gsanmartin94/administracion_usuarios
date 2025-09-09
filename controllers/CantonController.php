<?php
require_once 'controllers/BaseController.php';
require_once 'models/Canton.php';

class CantonController extends BaseController {
    
    //obtiene cantones desde el id provincia
    public function viewByProvinciaId() {
        $canton = new Canton();
        $data = $canton->getByProvinciaId($_GET['id']);

        if ($data) {
            echo json_encode([
                'success' => true,
                'cantones' => $data
            ]);
        } else {
            echo json_encode([
                'success' => false,
                'message' => 'No se encontraron cantones'
            ]);
        }
    }
}