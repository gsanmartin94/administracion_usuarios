<?php
require_once 'helpers/auth.php';
$permisosUsuario = PermisosModulosSubmodulos();
$datosUsuario = datosUser();
?>
<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<meta name="description" content="GAD Municipal de Pasaje">
	<meta name="author" content="TICS-GADM-PASAJE">


	<!--link rel="shortcut icon" href="src/img/icons/icon-48x48.png" /-->

	<title>GAD Municipal de Pasaje</title>

	<!--ESTILOS-->
	<link href="src/css/app/app.css" rel="stylesheet">
	<link href="src/css/app/light.css" rel="stylesheet">

	<!--FUENTES-->
	<link rel="preconnect" href="https://fonts.gstatic.com">
	<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600&display=swap" rel="stylesheet">
	


</head>

<body>
	<div class="wrapper">
		<nav id="sidebar" class="sidebar js-sidebar">
			<div class="sidebar-content js-simplebar">

				<!--LOGO CENTRAL-->
				<div class="text-center p-4">
					<a class="brand-link" href="web.php?controller=inicio&action=index">
						<img width="200" height="80" src="src/img/LogoGAD.png">
					</a>
				</div>

				<!--SIDEBAR-->
				<ul class="sidebar-nav">

					<?php if (isset($permisosUsuario['ADMINISTRACION'])): ?>
						<li class="sidebar-item">
							<a data-bs-target="#administracion" data-bs-toggle="collapse" class="sidebar-link collapsed"
								aria-expanded="false">
								<i class="align-middle" data-feather="database"></i><span
									class="align-middle">Administración</span>
							</a>
							<ul id="administracion" class="sidebar-dropdown list-unstyled collapse "
								data-bs-parent="#sidebar">
								<?php foreach ($permisosUsuario['ADMINISTRACION'] as $permiso): ?>
									<li class="sidebar-item">
										<a class="sidebar-link"
											href="web.php?controller=<?= htmlspecialchars($permiso['ruta']) ?>&action=index">
											<?= htmlspecialchars($permiso['nombre']) ?>
										</a>
									</li>
								<?php endforeach; ?>
							</ul>
						</li>
					<?php endif; ?>

				</ul>
				<div class="text-center mt-auto p-3">
					<img width="150" height="100" src="src/img/logoJovannyPrimero.png">
				</div>
			</div>
		</nav>


		<!--NAVBAR-->

		<div class="main">
			<nav class="navbar navbar-expand navbar-light navbar-bg">
				<a class="sidebar-toggle js-sidebar-toggle">
					<i class="hamburger align-self-center"></i>
				</a>

				<div class="navbar-collapse collapse">
					<ul class="navbar-nav navbar-align">
						
						<!--USER INFO-->

						<li class="nav-item dropdown">
							
							<a class="nav-link dropdown-toggle d-none d-sm-inline-block" href="#"
								data-bs-toggle="dropdown">
								<span class=" card-title m-2" >
									<!--poner el nombre-->
										<?php echo $datosUsuario['username']; ?> - 
										<?php echo $datosUsuario['nombres']; ?> <?php echo $datosUsuario['apellidos']; ?>
								</span>
								<img src="src/img/avatars/avatar.jpg" class="avatar img-fluid rounded me-1"
									alt="usuario" />
								
							</a>

							<div class="dropdown-menu dropdown-menu-end">
								
								<a class="dropdown-item" title="Mi Perfil" href="web.php?controller=perfil&action=index&id=<?php echo $datosUsuario['id']; ?>">
									<i class="align-middle me-1"data-feather="user"></i>
									Mi perfil
                                </a>

								<!--a class="dropdown-item" href="#"><i class="align-middle me-1"
										data-feather="pie-chart"></i> Analytics</a-->
								<div class="dropdown-divider"></div>
								<a class="dropdown-item" href="#" 
									onclick="CambiarPasswordCliente(<?= $datosUsuario['id'] ?>)"><i class="align-middle me-1"
									data-feather="settings"></i> Cambiar contraseña</a>
								<a class="dropdown-item" href="#"><i class="align-middle me-1"
										data-feather="help-circle"></i> Centro de ayuda</a>
								<div class="dropdown-divider"></div>
								<a class="dropdown-item" href="app/auth/logout.php">Cerrar Sesión</a>
							</div>
						</li>
					</ul>
				</div>
			</nav>

			<main class="content">
				<div class="container-fluid p-0">
					<div class="row">
						<div class="col-12">
							
						<!--CONEXION A VISTAS-->
					