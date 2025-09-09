<!--HEADER-->
<?php include 'views/layout/header.php'; ?>

<!--METODOS DE VALIDACIÓN INICIALES-->
<?php
$submodulo = "";
$permisos = ['READ' => false, 'CREATE' => false, 'UPDATE' => false, 'DELETE' => false, 'ASSIGN' => false, 'CHANGE_PASSWORD' => false];
$permisos = PermisosSubmodulos($submodulo, $permisos);
?>

<div class="row">
    <div class="col-3">
        <div class="card mb-3">
            <div class="card-header">
                <h5 class="card-title">Detalles del Perfil</h5>
            </div>
            <div class="card-body text-center">
                <img src="src/img/avatars/avatar.jpg" class="img-fluid rounded-circle mb-2" width="128" height="128">
                <h5 class="card-title mb-0"><?php echo $perfilUsuario['nombres']; ?></h5>
                <h5 class="card-title mb-0"><?php echo $perfilUsuario['apellidos']; ?></h5>
            </div>
            <hr class="my-0">
            <div class="card-body">
                <h5 class="h6 card-title">Información</h5>
                <ul class="list-unstyled mb-0">
                    <li class="mb-1" style="display: flex; align-items: center;">
                        <i class="align-middle me-1" data-feather="map-pin"></i>
                        <span><?php echo $perfilUsuario['canton']; ?></span>
                    </li>

                    <li class="mb-1" style="display: flex; align-items: center;">
                        <i class="align-middle me-1" data-feather="phone"></i>
                        <span><?php echo $perfilUsuario['telefono']; ?></span>
                    </li>

                    <li class="mb-1" style="display: flex; align-items: center; max-width: 100%;">
                        <i class="align-middle me-1" data-feather="mail"></i>
                        <span style="white-space: nowrap; overflow: hidden; text-overflow: ellipsis; display: inline-block; max-width: 100%;">
                            <?php echo $perfilUsuario['correo']; ?>
                        </span>
                    </li>
                </ul>
            </div>
        </div>
    </div>

    <div class="col-9">
        <div class="card">
            <div class="card-body h-100">
                <div class="modal-body">
                    <form id="formUsuarioRead">
                        <div class="row">
                            <div class="col-12 d-flex justify-content-start">
                                <h4 class="card-title mb-0">
                                    Datos del Ciudadano
                                </h4>
                            </div>
                            <div class="col-6 mt-2">
                                <label class="form-label">Nombres</label>
                                <input type="text" class="form-control" id="nombres_read" name="nombres_read" readonly />
                            </div>
                            <div class="col-6 mt-2">
                                <label class="form-label">Apellidos</label>
                                <input type="text" class="form-control" id="apellidos_read" name="apellidos_read" readonly />
                            </div>
                            <div class="col-6 mt-2">
                                <label class="form-label">Cedula</label>
                                <input type="text" class="form-control" id="cedula_read" name="cedula_read" readonly />
                            </div>
                            <div class="col-6 mt-2">
                                <label class="form-label">Genero</label>
                                <select class="form-select" id="genero_read" name="genero_read" disabled>
                                    <option selected>Seleccionar Genero</option>
                                    <option value="MASCULINO">MASCULINO</option>
                                    <option value="FAMENINO">FEMENINO</option>
                                    <option value="OTROS">OTROS</option>
                                </select>
                            </div>
                            <div class="col-6 mt-2">
                                <label class="form-label">Fecha de Nacimiento</label>
                                <input type="date" class="form-control" id="fecha_nacimiento_read"
                                    name="fecha_nacimiento_read" readonly />
                            </div>
                            <div class="col-6 mt-2">
                                <label class="form-label">Teléfono</label>
                                <input type="tel" class="form-control" id="telefono_read" name="telefono_read" readonly />
                            </div>
                            <div class="col-6 mt-2">
                                <label class="form-label">Provincia</label>
                                <input type="tel" class="form-control" id="provincia_read" name="provincia_read" readonly />
                            </div>
                            <div class="col-6 mt-2">
                                <label class="form-label">canton</label>
                                <input type="tel" class="form-control" id="canton_read" name="canton_read" readonly />
                            </div>
                            <div class="col-6 mt-2">
                                <label class="form-label">Parroquia</label>
                                <input type="tel" class="form-control" id="parroquia_read" name="parroquia_read" readonly />
                            </div>
                            <div class="col-6 mt-2">
                                <br>
                                <button type="button" class="btn btn-primary btn-perfilUpdate mt-2">Editar Perfil</button>
                            </div>
                            <hr class="mt-4">
                            <div class="col-12 d-flex justify-content-start">
                                <h4 class="card-title mb-0">
                                    Datos del Usuario
                                </h4>
                            </div>
                            <div class="col-6 mt-2">
                                <label class="form-label">Rol</label>
                                <input type="text" class="form-control" id="rol_read" name="rol_read" readonly />
                            </div>
                            <div class="col-6 mt-2">
                                <label class="form-label">Tipo Usuario</label>
                                <input type="text" class="form-control" id="tipo_usuario_read" name="tipo_usuario_read" readonly />
                            </div>
                            <div class="col-6 mt-2">
                                <label class="form-label">Username</label>
                                <input type="text" class="form-control" id="username_read" name="username_read" readonly />
                            </div>
                            <div class="col-6 mt-2">
                                <label class="form-label">Correo</label>
                                <input type="text" class="form-control" id="correo_read" name="correo_read" readonly />
                            </div>
                            <div class="col-6 mt-2">
                                <label class="form-label">Estado</label>
                                <select class="form-select" id="estado_read" name="estado_read" disabled>
                                    <option selected>Seleccionar Estado</option>
                                    <option value="ACTIVO">ACTIVO</option>
                                    <option value="INACTIVO">INACTIVO</option>
                                    <option value="OTROS">OTROS</option>
                                </select>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<!--Cambio de diseño para el Card/ incorporar al inicio de todas las vistas-->
<div>
<div>
<!----------------------------------------------------------------->

<!--FUNCIONALIDAD-->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="src/js/Actions/perfil.js"></script>

<!-- Llamar función JS para cargar datos -->
<?php if (!empty($perfilUsuario['id'])): ?>
<script>
    verPerfil(<?php echo (int)$perfilUsuario['id']; ?>);
</script>
<?php endif; ?>

<!--FOOTER-->
<?php include 'views/layout/footer.php'; ?>