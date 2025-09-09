<!--HEADER-->
<?php include 'views/layout/header.php'; ?>

<!--METODOS DE VALIDACIÓN INICIALES-->
<?php
$submodulo = "Usuarios";
$permisos = ['READ' => false, 'CREATE' => false, 'UPDATE' => false, 'DELETE' => false, 'ASSIGN' => false, 'CHANGE_PASSWORD' => false];
$permisos = PermisosSubmodulos($submodulo, $permisos);
?>

<!--Cambio de diseño para el Card/ incorporar al inicio de todas las vistas-->
<div class="card">
<div class="card-header">
<!----------------------------------------------------------------->

<!--TITULO DE PÁGINA-->
<div class="row justify-content-md-center mb-3">
    <div class="col-12">
        <h3 class="text-center">Usuarios</h3>
    </div>
</div>

<div class="mb-3">
    <ul class="nav nav-tabs">
        <li class="nav-item">
            <a class="nav-link active" href="#" data-estado="todos">Todos los usuarios</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="#" data-estado="INTERNO">Usuarios Internos</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="#" data-estado="EXTERNO">Usuarios Externos</a>
        </li>
    </ul>

</div>

<!--OPCIONES DE PÁGINA-->
<div class="row justify-content-md-center mb-3">
    <div class="col-4">
        <input type="text" id="buscarTabla" class="form-control" placeholder="Buscar en la tabla...">
    </div>
    <div class="col-2">
        <select id="registrosPorPagina" class="form-select">
            <option value="10" selected>10</option>
            <option value="25">25</option>
            <option value="50">50</option>
            <option value="100">100</option>
            <option value="500">500</option>
        </select>
    </div>
    <div class="col-2 d-grid gap-2">
        <?php if ($permisos['CREATE']) { ?>
            <button class="btn btn-primary" type="button" data-bs-toggle="modal" data-bs-target="#usuario_create">Nuevo
                <i class="align-middle" data-feather="user-plus"></i>
            </button>
        <?php } ?>
    </div>
</div>

<!--TABLA Y OPCIONES DE LA TABLA-->
<div class="table-responsive">
    <table class="table table-hover" id="table_usuarios">
        <thead>
            <tr class="table-primary">
                <th scope="col">#</th>
                <th scope="col">Username</th>
                <th scope="col">Nombres</th>
                <th scope="col">Apellidos</th>
                <th scope="col">Tipo Usuario</th>
                <th scope="col">Rol</th>
                <th scope="col">Estado</th>
                <th scope="col">Opciones</th>
            </tr>
        </thead>
        <tbody>

            <!--LISTAR DATOS-->
            <?php foreach ($usuarios as $index => $usuario) { ?>
                <tr id="usuario_<?php echo $usuario['id']; ?>">
                    <th scope='row'><?php echo $index + 1; ?></th>
                    <td><?php echo $usuario['username']; ?></td>
                    <td><?php echo $usuario['nombres']; ?></td>
                    <td><?php echo $usuario['apellidos']; ?></td>
                    <td><?php echo $usuario['tipo_usuario']; ?></td>
                    <td><?php echo $usuario['rol']; ?></td>
                    <td><?php echo $usuario['estado']; ?></td>

                    <!--OPCIONES X FILA-->
                    <td>
                        <div class="dropdown" data-bs-display="static">
                            <button class="btn btn-info dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                Opciones
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <li>
                                    <?php if ($permisos['READ']) { ?>
                                        <a class="dropdown-item" href="#" onclick="verUsuario(<?= $usuario['id'] ?>)">Ver</a>
                                    <?php } ?>
                                </li>
                                <li>
                                    <?php if ($permisos['UPDATE']) { ?>
                                        <a class="dropdown-item" href="#" onclick="editarUsuario(<?= $usuario['id'] ?>)">Editar</a>
                                    <?php } ?>
                                </li>
                                <li>
                                    <?php if ($permisos['DELETE']) { ?>
                                        <a class="dropdown-item" href="#" onclick="eliminarUsuario(<?= $usuario['id'] ?>)">Eliminar</a>
                                    <?php } ?>
                                </li>
                                <li>
                                    <?php if ($permisos['CHANGE_PASSWORD']) { ?>
                                        <a class="dropdown-item" href="#" onclick="CambiarPasswordUsuario(<?= $usuario['id'] ?>)">Cambiar Contraseña</a>
                                    <?php } ?>
                                </li>
                                <li>
                                    <?php if ($permisos['ASSIGN']) { ?>
                                        <a title="Asignar permisos a Usuario" href="web.php?controller=usuario&action=indexUsuariosPermisos&id=<?php echo $usuario['id']; ?>&username=<?php echo $usuario['username']; ?>"
                                            class="dropdown-item">Asignar Permisos
                                        </a>
                                    <?php } ?>
                                </li>
                            </ul>
                        </div>
                    </td>
                </tr>
            <?php } ?>
        </tbody>
    </table>
</div>

<!--PAGINACION-->
<div class="row">
    <div class="col-12">
        <div id="paginacion" class="mt-3 text-center"></div>
    </div>
</div>

<!--MODAL READ-->
<div class="modal fade" id="usuario_read" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title w-100 text-center">Usuario Seleccionado</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="formUsuarioRead">
                    <div class="row">
                        <div class="col-12 d-flex justify-content-start">
                            <h4 class="mb-0">
                                Datos de la Persona
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
                            <label class="form-label">Parroquia</label>
                            <input type="tel" class="form-control" id="parroquia_read" name="parroquia_read" readonly />
                        </div>

                        <div class="col-12 d-flex justify-content-start">
                            <h4 class="mb-0 mt-4">
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
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>

<!-- MODAL CREATE-->
<div class="modal fade" id="usuario_create" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title w-100 text-center">Nuevo Usuario</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="formUsuarioCreate">
                    <div class="row">
                        <div class="col-12 d-flex justify-content-start">
                            <h4 class="mb-0">
                                Datos de la Persona
                            </h4>
                        </div>
                        <div class="col-6 mt-2">
                            <label class="form-label">Cedula</label>
                            <input type="text" class="form-control" id="cedula_create" name="cedula_create" maxlength="10" pattern="\d{10}" inputmode="numeric"
                            required placeholder=" " title="Debe contener exactamente 10 dígitos numéricos">
                        </div>
                        <div class="col-6 mt-2">
                            <label class="form-label">Nombres</label>
                            <input type="text" class="form-control" id="nombres_create" name="nombres_create" required />
                        </div>
                        <div class="col-6 mt-2">
                            <label class="form-label">Apellidos</label>
                            <input type="text" class="form-control" id="apellidos_create" name="apellidos_create" required />
                        </div>
                        <div class="col-6 mt-2">
                            <label class="form-label">Genero</label>
                            <select class="form-select" id="genero_create" name="genero_create">
                                <option selected>Seleccionar Genero</option>
                                <option value="MASCULINO">MASCULINO</option>
                                <option value="FAMENINO">FEMENINO</option>
                                <option value="OTROS">OTROS</option>
                            </select>
                        </div>
                        <div class="col-6 mt-2">
                            <label class="form-label">Fecha de Nacimiento</label>
                            <input type="date" class="form-control" id="fecha_nacimiento_create"
                                name="fecha_nacimiento_create" require />
                        </div>
                        <div class="col-6 mt-2">
                            <label class="form-label">Teléfono</label>
                            <input type="tel" class="form-control" id="telefono_create" name="telefono_create" require />
                        </div>
                        <div class="col-6 mt-2">
                            <label class="form-label" for="provincia_create">Provincia</label>
                            <select class="form-select" id="provincia_create" name="provincia_create">
                                <option selected>Seleccionar Provincia</option>
                                <?php foreach ($provincias as $index => $provincia) { ?>
                                    <option value="<?php echo $provincia['id']; ?>"><?php echo $provincia['descripcion']; ?></option>
                                <?php } ?>
                            </select>
                        </div>
                        <div class="col-6 mt-2 form-group">
                            <label class="form-label" for="canton_create">Cantón</label>
                            <select class="form-select" name="canton_create" id="canton_create" required>
                            </select>
                        </div>
                        <div class="col-6 mt-2 form-group">
                            <label class="form-label" for="parroquia_create">Parroquia</label>
                            <select class="form-select" name="parroquia_create" id="parroquia_create" required>
                            </select>
                        </div>

                        <div class="col-12 d-flex justify-content-start">
                            <h4 class="mb-0 mt-4">
                                Datos del Usuario
                            </h4>
                        </div>
                        <div class="col-6 mt-2 form-group">
                            <label class="form-label" for="rol_create">Rol</label>
                            <select class="form-select" id="rol_create" name="rol_create">
                                <option selected>Seleccionar Rol</option>
                                <?php foreach ($roles as $index => $rol) { ?>
                                    <option value="<?php echo $rol['id']; ?>"><?php echo $rol['descripcion']; ?></option>
                                <?php } ?>
                            </select>
                        </div>
                        <div class="col-6 mt-2 form-group">
                            <label class="form-label" for="tipo_usuario_create">Tipo de Usuario</label>
                            <select class="form-select" id="tipo_usuario_create" name="tipo_usuario_create">
                                <option selected>Seleccionar Tipo Usuario</option>
                                <?php foreach ($tipos_usuarios as $index => $tipo_usuario) { ?>
                                    <option value="<?php echo $tipo_usuario['id']; ?>"><?php echo $tipo_usuario['descripcion']; ?></option>
                                <?php } ?>
                            </select>
                        </div>
                        <div class="col-6 mt-2">
                            <label class="form-label">Username</label>
                            <input type="text" class="form-control" id="username_create" name="username_create" require />
                        </div>
                        <div class="col-6 mt-2">
                            <label class="form-label">Password</label>
                            <input type="password" class="form-control" id="password_create" name="password_create" require />
                        </div>
                        <div class="col-6 mt-2">
                            <label class="form-label">Correo</label>
                            <input type="email" class="form-control" id="correo_create" name="correo_create" require />
                        </div>
                        <div class="col-6 mt-2">
                            <label class="form-label">Estado</label>
                            <select class="form-select" id="estado_create" name="estado_create">
                                <option selected>Seleccionar Estado</option>
                                <option value="ACTIVO">ACTIVO</option>
                                <option value="INACTIVO">INACTIVO</option>
                                <option value="OTROS">OTROS</option>
                            </select>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-primary btn-usuarioCreate">Guardar</button>
            </div>
        </div>
    </div>
</div>

<!-- MODAL UPDATE-->
<div class="modal fade" id="usuario_update" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title w-100 text-center">Actualizar Usuario</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="formUsuarioUpdate">
                    <div class="row">
                        <div class="col-12" style="display: none;">
                            <label class="form-label">id</label>
                            <input type="text" class="form-control" id="id_update" name="id_update" readonly />
                        </div>
                        <div class="col-12" style="display: none;">
                            <label class="form-label">id Persona</label>
                            <input type="text" class="form-control" id="id_persona_update" name="id_persona_update" readonly />
                        </div>
                        <div class="col-12 d-flex justify-content-start">
                            <h4 class="mb-0">
                                Datos de la Persona
                            </h4>
                        </div>
                        <div class="col-6 mt-2">
                            <label class="form-label">Cedula</label>
                            <input type="text" class="form-control" id="cedula_update" name="cedula_update" require />
                        </div>
                        <div class="col-6 mt-2">
                            <label class="form-label">Nombres</label>
                            <input type="text" class="form-control" id="nombres_update" name="nombres_update" require />
                        </div>
                        <div class="col-6 mt-2">
                            <label class="form-label">Apellidos</label>
                            <input type="text" class="form-control" id="apellidos_update" name="apellidos_update" require />
                        </div>
                        <div class="col-6 mt-2">
                            <label class="form-label">Genero</label>
                            <select class="form-select" id="genero_update" name="genero_update">
                                <option selected>Seleccionar Genero</option>
                                <option value="MASCULINO">MASCULINO</option>
                                <option value="FAMENINO">FEMENINO</option>
                                <option value="OTROS">OTROS</option>
                            </select>
                        </div>
                        <div class="col-6 mt-2">
                            <label class="form-label">Fecha de Nacimiento</label>
                            <input type="date" class="form-control" id="fecha_nacimiento_update"
                                name="fecha_nacimiento_update" require />
                        </div>
                        <div class="col-6 mt-2">
                            <label class="form-label">Teléfono</label>
                            <input type="tel" class="form-control" id="telefono_update" name="telefono_update" require />
                        </div>
                        <div class="col-6 mt-2">
                            <label class="form-label" for="provincia_update">Provincia</label>
                            <select class="form-select" id="provincia_update" name="provincia_update">
                                <option selected>Seleccionar Provincia</option>
                                <?php foreach ($provincias as $index => $provincia) { ?>
                                    <option value="<?php echo $provincia['id']; ?>"><?php echo $provincia['descripcion']; ?></option>
                                <?php } ?>
                            </select>
                        </div>
                        <div class="col-6 mt-2 form-group">
                            <label class="form-label" for="canton_update">Cantón</label>
                            <select class="form-select" name="canton_update" id="canton_update" required>
                            </select>
                        </div>
                        <div class="col-6 mt-2 form-group">
                            <label class="form-label" for="parroquia_update">Parroquia</label>
                            <select class="form-select" name="parroquia_update" id="parroquia_update" required>
                            </select>
                        </div>

                        <div class="col-12 d-flex justify-content-start">
                            <h4 class="mb-0 mt-4">
                                Datos del Usuario
                            </h4>
                        </div>
                        <div class="col-6 mt-2 form-group">
                            <label class="form-label" for="rol_update">Rol</label>
                            <select class="form-select" id="rol_update" name="rol_update">
                                <option selected>Seleccionar Rol</option>
                                <?php foreach ($roles as $index => $rol) { ?>
                                    <option value="<?php echo $rol['id']; ?>"><?php echo $rol['descripcion']; ?></option>
                                <?php } ?>
                            </select>
                        </div>
                        <div class="col-6 mt-2 form-group">
                            <label class="form-label" for="tipo_usuario_update">Tipo de Usuario</label>
                            <select class="form-select" id="tipo_usuario_update" name="tipo_usuario_update">
                                <option selected>Seleccionar Tipo Usuario</option>
                                <?php foreach ($tipos_usuarios as $index => $tipo_usuario) { ?>
                                    <option value="<?php echo $tipo_usuario['id']; ?>"><?php echo $tipo_usuario['descripcion']; ?></option>
                                <?php } ?>
                            </select>
                        </div>
                        <div class="col-6 mt-2">
                            <label class="form-label">Username</label>
                            <input type="text" class="form-control" id="username_update" name="username_update" require />
                        </div>
                        <div class="col-6 mt-2">
                            <label class="form-label">Password</label>
                            <input type="password" class="form-control" id="password_update" value="***" name="password_update" readonly />
                        </div>
                        <div class="col-6 mt-2">
                            <label class="form-label">Correo</label>
                            <input type="mail" class="form-control" id="correo_update" name="correo_update" require />
                        </div>
                        <div class="col-6 mt-2">
                            <label class="form-label">Estado</label>
                            <select class="form-select" id="estado_update" name="estado_update">
                                <option selected>Seleccionar Estado</option>
                                <option value="ACTIVO">ACTIVO</option>
                                <option value="INACTIVO">INACTIVO</option>
                                <option value="OTROS">OTROS</option>
                            </select>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-primary btn-usuarioUpdate">Guardar</button>
            </div>
        </div>
    </div>
</div>

<!-- MODAL UPDATE-->
<div class="modal fade" id="usuario_update_password" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title w-100 text-center">Actualizar Password Usuario</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="formUsuarioUpdatePassword">
                    <div class="row">
                        <div class="col-12" style="display: none;">
                            <label class="form-label">id</label>
                            <input type="text" class="form-control" id="id_updatePassword" name="id_updatePassword" readonly />
                        </div>
                        <div class="col-6 mt-2">
                            <label class="form-label">New Password</label>
                            <input type="password" class="form-control" id="password1_update" value="***" name="password1_update" require />
                        </div>
                        <div class="col-6 mt-2">
                            <label class="form-label">Confirm Password</label>
                            <input type="password" class="form-control" id="password2_update" value="***" name="password2_update" require />
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-primary btn-usuarioUpdatePassword">Guardar</button>
            </div>
        </div>
    </div>
</div>

<!--FUNCIONALIDAD-->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="src/js/Actions/usuario.js"></script>

<!--FOOTER-->
<?php include 'views/layout/footer.php'; ?>