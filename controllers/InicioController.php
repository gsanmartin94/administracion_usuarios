<?php
require_once 'controllers/BaseController.php';

class InicioController extends BaseController {
    public function index() {        
        include 'views/inicio/index.php';
    }
}