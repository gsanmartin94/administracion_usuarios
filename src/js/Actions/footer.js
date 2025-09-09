//METODO PARA SOLICITAR Actualizar Contraseña ->MODAL
    function CambiarPasswordCliente(id) {
        console.log(id);
        $.ajax({
            url: 'web.php?controller=usuario&action=view&id=' + id,
            method: 'GET',
            dataType: 'json',
            success: function (data) {
                if (!data.success) {
                    alert('No se encontró el Usuario.');
                    return;
                }
                console.log("se ejecuto");
                const usuario = data.usuario;
                // Llenar campo Id
                document.getElementById('id_updatePasswordCliente').value = usuario.id;
                
                // Mostrar modal
                const modal = new bootstrap.Modal(document.getElementById('cliente_update_password'));
                modal.show();
            },
            error: function () {
                alert('Ocurrió un error al obtener los datos del Usuario.');
            }
        });
    }

    //METODO PARA EDITAR PASSWORD
    document.querySelector('.btn-clienteUpdatePassword').addEventListener('click', function() {
        const id = document.getElementById('id_updatePasswordCliente').value.trim();
        console.log("valor de id del usuario ->"+id);
        //DATOS DE CONTRASEÑA
        const password_1 = document.getElementById('password1_update').value;
        const password_2 = document.getElementById('password2_update').value;

        // Validar que no estén vacíos
        if (password_1 === '' || password_2 === '') {
            Swal.fire({
                icon: 'warning',
                title: '¡Atención!',
                text: 'Debes ingresar la contraseña en ambos campos.',
                confirmButtonColor: '#0072ff'
            });
            return;
        }

        // Validar que sean iguales
        if (password_1 !== password_2) {
            Swal.fire({
                icon: 'error',
                title: '¡Error!',
                text: 'Las contraseñas no coinciden.',
                confirmButtonColor: '#0072ff'
            });
            return;
        }

        // Confirmación antes de actualizar
        Swal.fire({
            icon: 'question',
            title: '¿Actualizar contraseña?',
            text: '¿Estás seguro de cambiar la contraseña de este usuario?',
            showCancelButton: true,
            confirmButtonText: 'Sí, actualizar',
            cancelButtonText: 'Cancelar',
            confirmButtonColor: '#0072ff',
            cancelButtonColor: '#d33'
        }).then((result) => {
            if (result.isConfirmed) {
                // Enviar al backend
                const formData = new FormData();
                formData.append('id', id);
                formData.append('password', password_1);

                fetch('web.php?controller=usuario&action=updatePassword', {
                    method: 'POST',
                    body: formData
                })
                .then(r => r.text())
                .then(data => {
                    if (data === 'success') {
                        Swal.fire({
                            icon: 'success',
                            title: '¡Éxito!',
                            text: 'Contraseña actualizada correctamente',
                            confirmButtonColor: '#0072ff'
                        }).then(() => location.reload());
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: '¡Error!',
                            text: 'No se pudo actualizar la contraseña',
                            confirmButtonColor: '#0072ff'
                        });
                    }
                })
                .catch(error => {
                    alert('Error en la solicitud: ' + error);
                });
            }
        });
    });

    
