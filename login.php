<?php
session_start(); // Debe ser lo primero en el archivo

$error_message = null;
if (isset($_SESSION['error'])) {
    $error_message = $_SESSION['error'];
    unset($_SESSION['error']); // Limpia el mensaje de error de la sesión
}
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Iniciar Sesión</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;600&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <link href="src/css/login.css" rel="stylesheet">
    
</head>

<body>

    <div class="container">
        <div class="icon-container">
            <!-- Ícono de usuario -->
            <img src="src/img/NuevoMP.png" width="300" alt="GAD Municipal Pasaje"
                class=" text-center rounded-3">
        </div>

        <h2>Iniciar Sesión</h2>

        <form action="app/auth/checklogin.php" method="POST">
            <div class="form-group">
                <input type="text" name="username" id="username" style="text-transform:uppercase" required
                    placeholder=" " />
                <label>Usuario o Cédula de Identidad</label>
            </div>

            <div class="form-group">
                <input type="password" name="password" id="password" required placeholder=" " />
                <label>Contraseña</label>
                <i class="bi bi-eye-slash toggle-password" onclick="togglePassword(this)"></i>
            </div>

            <button type="submit">Ingresar</button>
        </form>
    </div>
    <?php if ($error_message): ?>
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <script>
            Swal.fire({
                icon: 'error',
                title: 'Error al iniciar sesión',
                text: '<?= $error_message ?>',
                confirmButtonColor: '#0072ff'
            });
        </script>
    <?php endif; ?>
    <script>
        function togglePassword(icon) {
            const input = document.getElementById("password");

            if (input.type === "password") {
                input.type = "text";
                icon.classList.remove("bi-eye-slash");
                icon.classList.add("bi-eye");
            } else {
                input.type = "password";
                icon.classList.remove("bi-eye");
                icon.classList.add("bi-eye-slash");
            }
        }
    </script>

</body>

</html>