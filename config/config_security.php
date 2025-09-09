<?php
//Conexion Local
$servername = "localhost";
$username = "supervisor";
$password = "root";
$dbname = "prueba_munpa_seguridad";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}
?>