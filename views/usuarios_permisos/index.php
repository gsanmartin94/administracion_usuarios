<!--HEADER-->
<?php include 'views/layout/header.php'; ?>

<!--METODOS DE VALIDACIÓN INICIALES-->
<?php
$submodulo = "Usuarios";
$permisos = ['READ' => false, 'CREATE' => false, 'UPDATE' => false, 'DELETE' => false, 'ASSIGN' => false];
$permisos = PermisosSubmodulos($submodulo, $permisos);
?>

<!--Cambio de diseño para el Card/ incorporar al inicio de todas las vistas-->
<div class="card">
<div class="card-header">
<!----------------------------------------------------------------->

<!--TITULO DE PÁGINA-->
<div class="row justify-content-md-center mt-1">
    <div class="col-12">
        <h4 class="text-center" style="font-weight: bold;">Gestión de Permisos para el Usuario</h4>
        <h3 class="text-center pb-3" style="color: #0d3b66; font-weight: bold; border-bottom: 1px solid #ccc;"><?= $username ?></h3>
    </div>
</div>
<form id="formPermisos">
    <input type="hidden" name="id_usuario" value="<?= $id_usuario ?>">

    <div class="row">
        <!-- Columna izquierda (Módulos) -->
        <div class="col-4">
            <h6 class="fw-bold mb-3">Módulos</h6>
            <ul class="list-group">
                <?php foreach ($modulos as $m): ?>
                    <li class="list-group-item list-group-item-action modulo-item"
                        onclick="showSubmodules('mod<?= $m['id'] ?>', event)">
                        <?= htmlspecialchars($m['descripcion']) ?>
                    </li>
                <?php endforeach; ?>
            </ul>
        </div>

        <!-- Columna central (Submódulos) -->
        <div class="col-4">
            <h6 class="fw-bold mb-3">Submódulos</h6>
            <?php foreach ($modulos as $m): ?>
                <div id="submodules-mod<?= $m['id'] ?>" class="d-none">
                    <ul class="list-group">
                        <?php if (!empty($subPorModulo[$m['id']])): ?>
                            <?php foreach ($subPorModulo[$m['id']] as $s): ?>
                                <li class="list-group-item list-group-item-action submodulo-item"
                                    onclick="showPermissions('subm<?= $s['id'] ?>-md<?= $s['id_modulo'] ?>', event)">
                                    <?= htmlspecialchars($s['descripcion']) ?>
                                </li>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <li class="list-group-item text-muted">Sin submódulos</li>
                        <?php endif; ?>
                    </ul>
                </div>
            <?php endforeach; ?>
        </div>

        <!-- Columna derecha (Permisos) -->
        <div class="col-4">
            <h6 class="fw-bold mb-3">Permisos</h6>
            <?php foreach ($submodulos as $s): ?>
                <div id="perms-subm<?= $s['id'] ?>-md<?= $s['id_modulo'] ?>" class="d-none">
                    <ul class="list-group">
                        <?php if (!empty($permsPorSub[$s['id']])): ?>
                            <?php foreach ($permsPorSub[$s['id']] as $p): ?>
                                <li class="list-group-item list-group-item-action">
                                    <input class="form-check-input"
                                           type="checkbox"
                                           id="perm<?= $p['id'] ?>"
                                           name="permisos[]"
                                           value="<?= $p['id'] ?>"
                                           <?= in_array(intval($p['id']), $permisosUsuarioTotales) ? 'checked' : '' ?>>
                                    <label class="form-check-label px-2" for="perm<?= $p['id'] ?>">
                                        <?= htmlspecialchars($p['descripcion']) ?>
                                    </label>
                                </li>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <li class="list-group-item text-muted">Sin permisos</li>
                        <?php endif; ?>
                    </ul>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

    <div class="row mt-3">
        <div class="col-4">
            <a href="web.php?controller=usuario&action=index" class="btn btn-danger">Cancelar</a>
            <button type="submit" class="btn btn-primary">Guardar</button>
        </div>
    </div>
</form>

<!--FUNCIONALIDAD-->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="src/js/Actions/usuarioPermisos.js"></script>

<!--FOOTER-->
<?php include 'views/layout/footer.php'; ?>