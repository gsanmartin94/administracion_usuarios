//ASIGNACION DE PERMISOS A USUARIOS
    /**
     * SE IMPLEMENTA LAS FUNCIONES Y LA LÓGICA DE LOS PERMISOS QUE SERÁN ASIGNADOS A LOS ROLES
     * ESTA VISTA NO ES UN SUBMODULO APARTE
     * FORMARÁ PARTE DEL SUBMODULO ->ROL-> PERO SERÁ
     */

    //CARGA LOS SUBMODULOS DEPENDIENDO DEL MODULO SELECCIONADO
    function showSubmodules(moduleId) {
        // Oculta todos los bloques de submódulos
        document.querySelectorAll('[id^="submodules-"]').forEach(div => div.classList.add('d-none'));
        // Muestra el del módulo seleccionado
        document.getElementById('submodules-' + moduleId).classList.remove('d-none');

        // Limpia los permisos visibles
        document.querySelectorAll('[id^="perms-"]').forEach(div => div.classList.add('d-none'));

        // ---- PINTADO DE MÓDULOS ----
        document.querySelectorAll('.modulo-item').forEach(li => li.classList.remove('active'));
        event.currentTarget.classList.add('active');
    }

    //CARGA LOS PERMISOS DEPENDIENDO DEL MODULO SELECCIONADO
    function showPermissions(submoduleId) {
        // Oculta todos los bloques de permisos
        document.querySelectorAll('[id^="perms-"]').forEach(div => div.classList.add('d-none'));
        // Muestra el del submódulo seleccionado
        document.getElementById('perms-' + submoduleId).classList.remove('d-none');
        // ---- PINTADO DE SUBMÓDULOS ----
        document.querySelectorAll('.submodulo-item').forEach(li => li.classList.remove('active'));
        event.currentTarget.classList.add('active');
    }

    //GUARDA LOS PERMISOS SELECCIONADOS EN LA BD
    document.getElementById("formPermisos").addEventListener("submit", function(e) {
        e.preventDefault();
        const formData = new FormData(this);

        fetch("web.php?controller=usuario&action=storePermisos", {
            method: "POST",
            body: formData
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                alert("✅ Permisos guardados correctamente");
            } else {
                alert("⚠️ Error: " + data.message);
            }
        })
        .catch(err => {
            console.error(err);
            alert("Error al guardar permisos.");
        });
    });