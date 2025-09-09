<?php
session_start();

// Limpiar todas las variables de sesión
unset($_SESSION['username']);
session_destroy();

// Redirigir al login (index.php)
header("Location: ../../index.php");
exit();
?>