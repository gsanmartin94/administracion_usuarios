//ASIGNACION DE PERMISOS A ROLES
    /**
     * SE IMPLEMENTA LAS FUNCIONES Y LA LÓGICA DE LOS PERMISOS QUE SERÁN ASIGNADOS A LOS ROLES
     * ESTA VISTA NO ES UN SUBMODULO APARTE
     * FORMARÁ PARTE DEL SUBMODULO ->ROL-> PERO SERÁ
     */

    function showSubmodules(moduleId) {
        // Oculta todos los bloques de submódulos
        document.querySelectorAll('[id^="submodules-"]').forEach(div => div.classList.add('d-none'));
        // Muestra el del módulo seleccionado
        document.getElementById('submodules-' + moduleId).classList.remove('d-none');

        // Limpia los permisos visibles
        document.querySelectorAll('[id^="perms-"]').forEach(div => div.classList.add('d-none'));
    }

    function showPermissions(submoduleId) {
        // Oculta todos los bloques de permisos
        document.querySelectorAll('[id^="perms-"]').forEach(div => div.classList.add('d-none'));
        // Muestra el del submódulo seleccionado
        document.getElementById('perms-' + submoduleId).classList.remove('d-none');
    }

    document.getElementById("formPermisos").addEventListener("submit", function(e) {
        e.preventDefault();
        const formData = new FormData(this);

        fetch("web.php?controller=rol&action=storePermisos", {
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