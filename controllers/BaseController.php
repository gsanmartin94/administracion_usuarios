<?php
require_once 'helpers/auth.php';

class BaseController
{
    public function __construct()
    {
        if (!usuarioAutenticado()) {
            $_SESSION['error'] = "Acceso denegado.";
            header("Location: index.php");
            exit;
        }
    }
}