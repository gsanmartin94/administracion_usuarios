<!--HEADER-->
<?php include 'views/layout/header.php';?>

<!--METODOS DE VALIDACIÓN INICIALES-->
<?php
    $submodulo = "Submódulos";
    $permisos = ['READ' => false, 'CREATE' => false, 'UPDATE' => false, 'DELETE' => false];
    $permisos = PermisosSubmodulos($submodulo, $permisos);
?>

<!--Cambio de diseño para el Card/ incorporar al inicio de todas las vistas-->
<div class="card">
<div class="card-header">
<!----------------------------------------------------------------->

<!--TITULO DE PÁGINA-->
<div class="row justify-content-md-center mb-3">
    <div class="col-12">
        <h3 class="text-center">Submódulos</h3>
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
        <button class="btn btn-primary" type="button" data-bs-toggle="modal" data-bs-target="#submodulo_create">Nuevo
            <i class="align-middle" data-feather="user-plus"></i>
        </button>
        <?php } ?>
    </div>
    <!--div class="col-2 d-grid gap-2">
        <button type="button" class="btn btn-success">Exportar
            <i class="align-middle" data-feather="download"></i>
        </button>
    </div>
    <div class="col-2 d-grid gap-2">
        <button type="button" class="btn btn-success">Importar
            <i class="align-middle" data-feather="upload"></i>
        </button>
    </div-->
</div>

<!--TABLA Y OPCIONES DE LA TABLA-->
<div class="table-responsive">
    <table class="table table-hover" id="table_submodulos">
        <thead>
            <tr class="table-primary">
                <th scope="col">#</th>
                <th scope="col">Módulos</th>
                <th scope="col">Submódulos</th>
                <th scope="col">Ruta</th>
                <th scope="col">Estados</th>
                <th scope="col">Opciones</th>
            </tr>
        </thead>
        <tbody>

            <!--LISTAR DATOS-->
            <?php foreach ($submodulos as $index => $submodulo) { ?>
                <tr id="submodulo_<?php echo $submodulo['id']; ?>">
                    <th scope='row'><?php echo $index + 1; ?></th>
                    <td><?php echo $submodulo['modulo']; ?></td>
                    <td><?php echo $submodulo['descripcion']; ?></td>
                    <td><?php echo $submodulo['ruta']; ?></td>
                    <td><?php echo $submodulo['estado']; ?></td>

            <!--OPCIONES X FILA-->
                    <td>
                        <?php if($permisos['READ']){ ?>
                        <a title="Ver detalles del usuario" href="#"
                            onclick="verSubmodulo(<?php echo $submodulo['id']; ?>)"
                            class="btn btn-info">Ver
                            <i data-feather="search"></i>
                        </a>
                        <?php } ?>

                        <?php if($permisos['UPDATE']){ ?>
                        <a title="Editar datos del usuario" href="#" 
                            onclick="editarSubmodulo(<?php echo $submodulo['id']; ?>)"
                            class="btn btn-info">Editar
                            <i data-feather="edit"></i>
                        </a>
                        <?php } ?>

                        <?php if($permisos['DELETE']){ ?>
                        <a title="Eliminar datos del usuario" href="#"
                            onclick="eliminarSubmodulo(<?php echo $submodulo['id']; ?>)"
                            class="btn btn-info">Eliminar
                            <i data-feather="trash-2"></i>
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
<div class="modal fade" id="submodulo_read" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title w-100 text-center">Submódulo Seleccionado</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="formSubmoduloRead">
                    <div class="row">
                        <div class="col-6 mt-2">
                            <label class="form-label">Módulo</label>
                            <input type="text" class="form-control" id="id_modulo_read" name="id_modulo_read" readonly />
                        </div>
                        <div class="col-6 mt-2">
                            <label class="form-label">Submódulo</label>
                            <input type="text" class="form-control" id="descripcion_read" name="descripcion_read" readonly />
                        </div>
                        <div class="col-6 mt-2">
                            <label class="form-label">Ruta</label>
                            <input type="text" class="form-control" id="ruta_read" name="ruta_read" readonly />
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
<div class="modal fade" id="submodulo_create" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title w-100 text-center">Nuevo Submódulo</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="formSubmoduloCreate">
                    <div class="row">
                        <div class="col-6 mt-2">
                            <label class="form-label">Módulo</label>
                            <select class="form-select" id="id_modulo_create" name="id_modulo_create">
                                <option selected>Seleccionar Modulo</option>
                                <?php foreach ($modulos as $index => $modulo) { ?>
                                    <option value="<?php echo $modulo['id']; ?>"><?php echo $modulo['descripcion'];?></option>
                                <?php } ?>
                            </select>
                        </div>
                        <div class="col-6 mt-2">
                            <label class="form-label">Submódulo</label>
                            <input type="text" class="form-control" id="descripcion_create" name="descripcion_create" required />
                        </div>
                        <div class="col-6 mt-2">
                            <label class="form-label">Ruta</label>
                            <input type="text" class="form-control" id="ruta_create" name="ruta_create" required />
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
                <button type="button" class="btn btn-primary btn-submoduloCreate">Guardar</button>
            </div>
        </div>
    </div>
</div>

<!-- MODAL UPDATE-->
<div class="modal fade" id="submodulo_update" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title w-100 text-center">Actualizar Submódulo</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="formSubmoduloUpdate">
                    <div class="row">
                        <div class="col-12" style="display: none;">
                            <label class="form-label">id</label>
                            <input type="text" class="form-control" id="id_update" name="id_update" readonly />
                        </div>
                        <div class="col-6 mt-2">
                            <label class="form-label">Módulo</label>
                            <select class="form-select" id="id_modulo_update" name="id_modulo_update">
                                <option selected>Seleccionar Modulo</option>
                                <?php foreach ($modulos as $index => $modulo) { ?>
                                    <option value="<?php echo $modulo['id']; ?>"><?php echo $modulo['descripcion'];?></option>
                                <?php } ?>
                            </select>
                        </div>
                        <div class="col-6 mt-2">
                            <label class="form-label">Submódulo</label>
                            <input type="text" class="form-control" id="descripcion_update" name="descripcion_update" required />
                        </div>
                        <div class="col-6 mt-2">
                            <label class="form-label">Ruta</label>
                            <input type="text" class="form-control" id="ruta_update" name="ruta_update" required />
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
                <button type="button" class="btn btn-primary btn-submoduloUpdate">Guardar</button>
            </div>
        </div>
    </div>
</div>

<!--FUNCIONALIDAD-->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="src/js/Actions/submodulo.js"></script>

<!--FOOTER-->
<?php include 'views/layout/footer.php'; ?>

