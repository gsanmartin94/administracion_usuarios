<?php
session_start();

require_once '../../config/config_security.php';

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    $_SESSION['error'] = "Acceso denegado.";
    header("location: index.php");
    exit;
}

$conexion = new mysqli($servername, $username, $password, $dbname);

if ($conexion->connect_error) {
    die("La conexión falló: " . $conexion->connect_error);
}

$username = trim($_POST['username']);
$password = $_POST['password'];

if (empty($username) || empty($password)) {
    $_SESSION['error'] = "Por favor ingrese el nombre de usuario o cédula y la contraseña.";
    header("location: ../../index.php");
    exit;
}

//$sql = "SELECT * FROM usuario WHERE username = ?";
$sql = "SELECT u.*, p.nombres, p.apellidos FROM usuario AS u 
LEFT JOIN persona AS p ON p.id = u.id_persona WHERE username = ?";
$stmt = $conexion->prepare($sql);
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();

    if (password_verify($password, $row['password'])) {
        $_SESSION['loggedin'] = true;
        $_SESSION['username'] = $username;
        $_SESSION['idUsuario'] = strtoupper($row['id']);
        $_SESSION['nombreUsuario'] = strtoupper($row['nombres']);

        /*
         *WEB REALIZA LA VALIDACION DE LOS MODULOS QUE SALGAN EN EL SIDEBAR
         *LOS MODULOS A LOS QUE SE TENDRA ACCESO DEPENDEN DEL PERMISO ASIGNADO
        */

        header("location:../../web.php");
        exit;
    } else {
        $_SESSION['error'] = "Contraseña incorrecta.";
    }
} else {
    $_SESSION['error'] = "Usuario no encontrado.";
}

$stmt->close();
$conexion->close();

// Redirige al login con un mensaje de error
header("location: ../../index.php");
exit;
