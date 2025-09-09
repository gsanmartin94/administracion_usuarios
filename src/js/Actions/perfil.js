function verPerfil(id) {
    $.ajax({
        // RUTA DE BUSQUEDA X ID
        url: 'web.php?controller=usuario&action=view&id=' + id,
        method: 'GET',
        dataType: 'json',

        success: function (data) {
            if (data.success) {
                // Datos del ciudadano
                $("#nombres_read").val(data.usuario.nombres || "");
                $("#apellidos_read").val(data.usuario.apellidos || "");
                $("#cedula_read").val(data.usuario.cedula || "");
                $("#genero_read").val(data.usuario.genero || "");
                $("#fecha_nacimiento_read").val(data.usuario.fecha_nacimiento || "");
                $("#telefono_read").val(data.usuario.telefono || "");
                $("#provincia_read").val(data.usuario.provincia || "");
                $("#canton_read").val(data.usuario.canton || "");
                $("#parroquia_read").val(data.usuario.parroquia || "");

                // Datos del usuario
                $("#rol_read").val(data.usuario.rol || "");
                $("#tipo_usuario_read").val(data.usuario.tipo_usuario || "");
                $("#correo_read").val(data.usuario.correo || "");
                $("#username_read").val(data.usuario.username || "");
                $("#estado_read").val(data.usuario.estado || "");

            } else {
                Swal.fire({
                    icon: 'warning',
                    title: 'Usuario no encontrado',
                    text: 'No se encontraron datos para el ID: ' + id,
                    confirmButtonText: 'Entendido'
                });
            }
        },

        error: function (xhr, status, error) {
            console.error("Error AJAX:", status, error);

            Swal.fire({
                icon: 'error',
                title: 'Error en la petición',
                text: 'Ocurrió un problema al obtener los datos del usuario.',
                footer: '<small>Revisa la consola del navegador para más detalles</small>'
            });
        }
    });
}