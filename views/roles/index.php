<!--HEADER-->
<?php include 'views/layout/header.php';?>

<!--METODOS DE VALIDACIÓN INICIALES-->
<?php
    $submodulo = "Roles";
    $permisos = ['READ' => false, 'CREATE' => false, 'UPDATE' => false, 'DELETE' => false, 'ASSIGN' => false];
    $permisos = PermisosSubmodulos($submodulo, $permisos);
?>

<!--Cambio de diseño para el Card/ incorporar al inicio de todas las vistas-->
<div class="card">
<div class="card-header">
<!----------------------------------------------------------------->

<!--TITULO DE PÁGINA-->
<div class="row justify-content-md-center mb-3">
    <div class="col-12">
        <h3 class="text-center">Roles</h3>
    </div>
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
        <?php if($permisos['CREATE']){ ?>
        <button class="btn btn-primary" type="button" data-bs-toggle="modal" data-bs-target="#rol_create">Nuevo
            <i class="align-middle" data-feather="user-plus"></i>
        </button>
        <?php } ?>
    </div>
</div>

<!--TABLA Y OPCIONES DE LA TABLA-->
<div class="table-responsive">
    <table class="table table-hover" id="table_roles">
        <thead>
            <tr class="table-primary">
                <th scope="col">#</th>
                <th scope="col">Roles</th>
                <th scope="col">Estados</th>
                <th scope="col">Opciones</th>
            </tr>
        </thead>
        <tbody>

            <!--LISTAR DATOS-->
            <?php foreach ($roles as $index => $rol) { ?>
                <tr id="modulo_<?php echo $rol['id']; ?>">
                    <th scope='row'><?php echo $index + 1; ?></th>
                    <td><?php echo $rol['descripcion']; ?></td>
                    <td><?php echo $rol['estado']; ?></td>

            <!--OPCIONES X FILA-->
                    <td>
                        <?php if($permisos['READ']){ ?>
                        <a title="Ver detalles del rol" href="#"
                            onclick="verRol(<?php echo $rol['id']; ?>)"
                            class="btn btn-info">Ver
                            <i data-feather="search"></i>
                        </a>
                        <?php } ?>

                        <?php if($permisos['UPDATE']){ ?>
                        <a title="Editar datos del rol" href="#" 
                            onclick="editarRol(<?php echo $rol['id']; ?>)"
                            class="btn btn-info">Editar
                            <i data-feather="edit"></i>
                        </a>
                        <?php } ?>

                        <?php if($permisos['DELETE']){ ?>
                        <a title="Eliminar datos del rol" href="#"
                            onclick="eliminarRol(<?php echo $rol['id']; ?>)"
                            class="btn btn-info">Eliminar
                            <i data-feather="trash-2"></i>
                        </a>
                        <?php } ?>

                        <?php if($permisos['ASSIGN']){ ?>
                        <a title="Asignar permisos a Rol" href="web.php?controller=rol&action=indexRolesPermisos&id=<?php echo $rol['id']; ?>&descripcion=<?php echo $rol['descripcion']; ?>"
                            class="btn btn-info">Asignar Permisos
                            <i data-feather="check-square"></i>
                        </a>
                        <?php } ?>
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
<div class="modal fade" id="rol_read" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title w-100 text-center">Rol Seleccionado</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="formRolRead">
                    <div class="row">
                        <div class="col-6">
                            <label class="form-label">Rol</label>
                            <input type="text" class="form-control" id="descripcion_read" name="descripcion_read" readonly />
                        </div>
                        <div class="col-6">
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
<div class="modal fade" id="rol_create" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title w-100 text-center">Nuevo Rol</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="formRolCreate">
                    <div class="row">
                        <div class="col-6">
                            <label class="form-label">Rol</label>
                            <input type="text" class="form-control" id="descripcion_create" name="descripcion_create" required />
                        </div>
                        <div class="col-6">
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
                <button type="button" class="btn btn-primary btn-rolCreate">Guardar</button>
            </div>
        </div>
    </div>
</div>

<!-- MODAL UPDATE-->
<div class="modal fade" id="rol_update" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title w-100 text-center">Actualizar Rol</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="formRolUpdate">
                    <div class="row">
                        <div class="col-12" style="display: none;">
                            <label class="form-label">id</label>
                            <input type="text" class="form-control" id="id_update" name="id_update" readonly />
                        </div>
                        <div class="col-6">
                            <label class="form-label">Rol</label>
                            <input type="text" class="form-control" id="descripcion_update" name="descripcion_update" required />
                        </div>
                        <div class="col-6">
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
                <button type="button" class="btn btn-primary btn-rolUpdate">Guardar</button>
            </div>
        </div>
    </div>
</div>

<!--FUNCIONALIDAD-->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="src/js/Actions/rol.js"></script>

<!--FOOTER-->
<?php include 'views/layout/footer.php'; ?>

