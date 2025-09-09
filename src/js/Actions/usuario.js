//FUNCIONALIDAD DE USUARIO
//VISTA QUE PERTENECE -> views/usuarios/index.php

    //DATATABLE -> se inicia con la carga completa del DOM
document.addEventListener("DOMContentLoaded", function () {
    // Variables para la tabla
    let tabla = document.querySelector("#table_usuarios tbody");
    let filas = Array.from(tabla.rows);
    let registrosPorPagina = 10;
    let paginaActual = 1;
    let totalPaginas = Math.ceil(filas.length / registrosPorPagina);
    let estadoActual = "todos"; // por defecto todos

    // Escuchar clicks en el navbar para filtrar por estado
    document.querySelectorAll('.nav-link').forEach(link => {
        link.addEventListener('click', function (e) {
            e.preventDefault();
            // Quitar clase activa de todos y ponerla al actual
            document.querySelectorAll('.nav-link').forEach(l => l.classList.remove('active'));
            this.classList.add('active');

            // Guardar estado actual
            estadoActual = this.dataset.estado;

            // Reiniciar a la página 1
            paginaActual = 1;

            // Aplicar filtros y paginación
            filtrarYPaginar();
        });
    });

    // Buscar
    document.getElementById("buscarTabla").addEventListener("keyup", () => {
        paginaActual = 1;
        filtrarYPaginar();
    });

    // Cambiar cantidad de registros
    document.getElementById("registrosPorPagina").addEventListener("change", (e) => {
        registrosPorPagina = parseInt(e.target.value);
        paginaActual = 1;
        filtrarYPaginar();
    });

    function filtrarYPaginar() {
        let filtroTexto = document.getElementById("buscarTabla").value.toLowerCase();

        // Filtrar por texto y por estado
        let filasFiltradas = filas.filter(fila => {
            let coincideTexto = fila.textContent.toLowerCase().includes(filtroTexto);
            let coincideEstado = (estadoActual === "todos") ||
                (fila.cells[4] && fila.cells[4].textContent.trim().toUpperCase() === estadoActual.toUpperCase());
            return coincideTexto && coincideEstado;
        });

        totalPaginas = Math.ceil(filasFiltradas.length / registrosPorPagina);
        mostrarPagina(filasFiltradas);
        generarPaginacion(filasFiltradas);
    }

    function mostrarPagina(filasFiltradas) {
        tabla.innerHTML = "";
        let inicio = (paginaActual - 1) * registrosPorPagina;
        let fin = inicio + registrosPorPagina;
        filasFiltradas.slice(inicio, fin).forEach(fila => tabla.appendChild(fila));
    }

    function generarPaginacion(filasFiltradas) {
        let paginacion = document.getElementById("paginacion");
        paginacion.innerHTML = "";

        if (totalPaginas <= 1) return;

        let contenedor = document.createElement("div");
        contenedor.classList.add("pagination", "d-flex", "justify-content-center");

        let btnAnterior = document.createElement("button");
        btnAnterior.textContent = "<<";
        btnAnterior.disabled = paginaActual === 1;
        btnAnterior.classList.add("btn", "btn-outline-primary", "me-1");
        btnAnterior.onclick = () => {
            paginaActual--;
            mostrarPagina(filasFiltradas);
            generarPaginacion(filasFiltradas);
        };
        contenedor.appendChild(btnAnterior);

        let inicio = Math.max(1, paginaActual);
        let fin = Math.min(totalPaginas, paginaActual + 2);

        for (let i = inicio; i <= fin; i++) {
            let btn = document.createElement("button");
            btn.textContent = i;
            btn.classList.add("btn", "me-1", paginaActual === i ? "btn-primary" : "btn-outline-primary");
            btn.onclick = () => {
                paginaActual = i;
                mostrarPagina(filasFiltradas);
                generarPaginacion(filasFiltradas);
            };
            contenedor.appendChild(btn);
        }

        let btnSiguiente = document.createElement("button");
        btnSiguiente.textContent = ">>";
        btnSiguiente.disabled = paginaActual === totalPaginas;
        btnSiguiente.classList.add("btn", "btn-outline-primary");
        btnSiguiente.onclick = () => {
            paginaActual++;
            mostrarPagina(filasFiltradas);
            generarPaginacion(filasFiltradas);
        };
        contenedor.appendChild(btnSiguiente);

        paginacion.appendChild(contenedor);
        feather.replace();
    }

    // Inicialización
    filtrarYPaginar();
});

//METODO VER
    function verUsuario(id) {
        $.ajax({

            //RUTA DE BUSQUEDA X ID
            url: 'web.php?controller=usuario&action=view&id='+id,
            method: 'GET',
            dataType: 'json',
            
            success: function (data) {
                if (data.success) {
                    document.getElementById('nombres_read').value = data.usuario.nombres;
                    document.getElementById('apellidos_read').value = data.usuario.apellidos;
                    document.getElementById('cedula_read').value = data.usuario.cedula;
                    document.getElementById('genero_read').value = data.usuario.genero;
                    document.getElementById('fecha_nacimiento_read').value = data.usuario.fecha_nacimiento;
                    document.getElementById('telefono_read').value = data.usuario.telefono;
                    document.getElementById('parroquia_read').value = data.usuario.parroquia;

                    document.getElementById('rol_read').value = data.usuario.rol;
                    document.getElementById('tipo_usuario_read').value = data.usuario.tipo_usuario;
                    document.getElementById('correo_read').value = data.usuario.correo;
                    document.getElementById('username_read').value = data.usuario.username;
                    document.getElementById('estado_read').value = data.usuario.estado;

                    var modal = new bootstrap.Modal(document.getElementById('usuario_read'));
                    modal.show();
                } else {
                    alert('No se encontró el Usuario.');
                }
            },
            error: function () {
                alert('Ocurrió un error al obtener los datos del Usuario.');
            }
        });
    }

    //CARGA AUTOMATICA DE CANTONES X PROVINCIA
    document.getElementById('provincia_create').addEventListener('change', function () {
        const idProvincia = this.value;
        document.getElementById('parroquia_create').innerHTML = ''; // limpiar parroquias
        
        if (idProvincia) {
        fetch('web.php?controller=canton&action=viewByProvinciaId&id='+ idProvincia)
            .then(response => response.json())
            .then(data => {
                let selectCanton = document.getElementById('canton_create');
                selectCanton.innerHTML = '<option value="">Seleccione cantón</option>';

                if (data.success) {
                    data.cantones.forEach(canton => {
                        let option = document.createElement('option');
                        option.value = canton.id;
                        option.textContent = canton.descripcion;
                        selectCanton.appendChild(option);
                    });
                } else {
                    alert(data.message);
                }
            });
        }
    });

    //CARGA AUTOMATICA DE PARROQUIAS X CANTON
    document.getElementById('canton_create').addEventListener('change', function () {
        const idCanton = this.value;

        if (idCanton) {
        fetch('web.php?controller=parroquia&action=viewByCantonId&id='+ idCanton)
            .then(response => response.json())
            .then(data => {
                let selectParroquia = document.getElementById('parroquia_create');
                selectParroquia.innerHTML = '<option value="">Seleccione Parroquia</option>';

                if (data.success) {
                    data.parroquias.forEach(parroquia => {
                        let option = document.createElement('option');
                        option.value = parroquia.id;
                        option.textContent = parroquia.descripcion;
                        selectParroquia.appendChild(option);
                    });
                } else {
                    alert(data.message);
                }
            });
        }
    });



    //METODO PARA CREAR
    document.querySelector('.btn-usuarioCreate').addEventListener('click', function() {
        
        //DATOS DE PERSONA
        const cedula = document.getElementById('cedula_create').value;
        const nombres = document.getElementById('nombres_create').value;
        const apellidos = document.getElementById('apellidos_create').value;
        const genero = document.getElementById('genero_create').value;
        const fecha_nacimiento = document.getElementById('fecha_nacimiento_create').value;
        const telefono = document.getElementById('telefono_create').value;
        const id_parroquia = document.getElementById('parroquia_create').value;
        const estado = document.getElementById('estado_create').value;

        //DATOS DE USUARIO
        const id_rol = document.getElementById('rol_create').value;
        const id_tipo_usuario = document.getElementById('tipo_usuario_create').value;
        const correo = document.getElementById('correo_create').value;
        const username = document.getElementById('username_create').value;
        const password = document.getElementById('password_create').value;
        

        //VALIDACIÓN DE CAMPOS VACIOS
        if (cedula === '' ||
             nombres === '' ||
             apellidos === '' ||
             genero === '' ||
             estado === 'Seleccionar Genero' || 
             fecha_nacimiento === '' ||
             telefono === '' ||
             id_parroquia === '' ||
             id_parroquia === 'Seleccione Parroquia' ||
             estado === '' || 
             estado === 'Seleccionar Estado' || 
             id_rol === '' || 
             id_rol === 'Seleccionar Rol' || 
             id_tipo_usuario === '' || 
             id_tipo_usuario === 'Seleccionar Tipo Usuario' || 
             username === '' || 
             password === '' || 
             correo === '') {
            alert('Por favor, complete todos los campos correctamente.');
            return;
        }

        /**
         * NOTA IMPORTANTE
         * DEV: Galo Sanmartín D.
         * 
         * Alcance de la creación de usuarios
         * 
         * la gestión de usuario permitirá el ingreso/creación de diferentes usuarios y personas al sistema
         * Existiran datos "repetidos" con respecto a la creación de personas si el usuario se crea de manera externa (un ciudadano)
         * Un usuario tipo ciudadano no es igual que un usuario creado por el sistema, al usuario tipo CIUDADANO no se podrá
         * asignar permisos extra, ya que eso ocasionaria fallas en la organizacion del sistema.
         * el contro de usuarios internos dependerá netamente del administrador del sistema.
         * antes de crear un usuario se deberá verificar que la cedula o correo no se encuentre repetida para usuarios internos
         * Como unica validacion de creación de usuarios internos será CEDULA y CORREO ya que son los datos que usa el sistema para recuperación
         * 
         * Se deberá tomar en cuenta la funcionalidad del sistema con respecto a la creación de usuarios internos
         * */
        
        //VALIDACIÓN DE CEDULA Y CORREO -> INTERNO
        $.ajax({
            //RUTA DE BUSQUEDA X CEDULA Y CORREO
            url: 'web.php?controller=usuario&action=validate&cedula=' + cedula + '&correo=' + correo + '&username='+ username,
            method: 'GET',
            dataType: 'json',
            success: function (data) {
                if (!data.success) {
                    //NO EXISTE USUARIO INTERNO - CREAR USUARIO
                    console.log("entro a Crear Persona");
                    //PRIMERO CREA LA PERSONA
                    const formData = new FormData();
                    formData.append('cedula', cedula);
                    formData.append('nombres', nombres);
                    formData.append('apellidos', apellidos);
                    formData.append('genero', genero);
                    formData.append('fecha_nacimiento', fecha_nacimiento);
                    formData.append('telefono', telefono);
                    formData.append('id_parroquia', id_parroquia);
                    formData.append('estado', estado);

                    fetch('web.php?controller=persona&action=store', {
                        method: 'POST',
                        body: formData
                    })
                    .then(response => response.json())
                    .then(result => {
                        if (result.success) {
                            console.log("Se creo la persona con id "+ result.data);
                            //SI INGRESO PERSONA DE MANERA EXITOSA DEBERÁ INGRESAR USUARIO
                            const formDataUsuario = new FormData();
                            formDataUsuario.append('id_persona', result.data);
                            formDataUsuario.append('id_rol', id_rol);
                            formDataUsuario.append('id_tipo_usuario', id_tipo_usuario);
                            formDataUsuario.append('correo', correo);
                            formDataUsuario.append('username', username);
                            formDataUsuario.append('password', password);
                            formDataUsuario.append('estado', estado);

                            console.log("Se guardaron datos en variable usuario"+ formDataUsuario);
                            fetch('web.php?controller=usuario&action=store', {
                                method: 'POST',
                                body: formDataUsuario
                            })
                            .then(response => response.text())
                            .then(data => {
                                if (data === 'success') {
                                    console.log("Se creo el usuario");
                                    Swal.fire({
                                        icon: 'success',
                                        title: '¡Exito!',
                                        text: 'Nuevo Usuario ingresado en la BD!',
                                        confirmButtonColor: '#0072ff'
                                    }).then((result) => {
                                        if (result.isConfirmed) {
                                            location.reload();
                                        }
                                    });
                                } else {
                                    console.log("NO Se creo el usuario" + data);
                                    Swal.fire({
                                        icon: 'error',
                                        title: '¡Error!',
                                        text: 'No se pudo Guardar el Usuario',//data,
                                        confirmButtonColor: '#0072ff'
                                    }).then((result) => {
                                        if (result.isConfirmed) {
                                            location.reload();
                                        }
                                    });
                                }
                            })
                            .catch(error => {
                                alert('Error en la solicitud: ' + error);
                            });

                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: '¡Error!',
                                text: 'No se pudo Guardar la Persona',//data,
                                confirmButtonColor: '#0072ff'
                            }).then((result) => {
                                if (result.isConfirmed) {
                                    location.reload();
                                }
                            });
                        }
                    })
                    .catch(error => {
                        alert('Error en la solicitud: ' + error);
                    });

                } else {
                    Swal.fire({
                                icon: 'error',
                                title: '¡Error!',
                                text: 'Ya existe usuario en el Sistema con la misma cedula o correo.',
                                confirmButtonColor: '#0072ff'
                            });
                }
            },
            error: function () {
                alert('Ocurrió un error al obtener los datos del Usuario.');
            }
        });
        
    });

    //CARGA AUTOMATICA DE CANTONES X PROVINCIA
    document.getElementById('provincia_update').addEventListener('change', function () {
        const idProvincia = this.value;
        document.getElementById('parroquia_update').innerHTML = ''; // limpiar parroquias
        
        if (idProvincia) {
        fetch('web.php?controller=canton&action=viewByProvinciaId&id='+ idProvincia)
            .then(response => response.json())
            .then(data => {
                let selectCanton = document.getElementById('canton_update');
                selectCanton.innerHTML = '<option value="">Seleccione cantón</option>';

                if (data.success) {
                    data.cantones.forEach(canton => {
                        let option = document.createElement('option');
                        option.value = canton.id;
                        option.textContent = canton.descripcion;
                        selectCanton.appendChild(option);
                    });
                } else {
                    alert(data.message);
                }
            });
        }
    });

    //CARGA AUTOMATICA DE PARROQUIAS X CANTON
    document.getElementById('canton_update').addEventListener('change', function () {
        const idCanton = this.value;

        if (idCanton) {
        fetch('web.php?controller=parroquia&action=viewByCantonId&id='+ idCanton)
            .then(response => response.json())
            .then(data => {
                let selectParroquia = document.getElementById('parroquia_update');
                selectParroquia.innerHTML = '<option value="">Seleccione Parroquia</option>';

                if (data.success) {
                    data.parroquias.forEach(parroquia => {
                        let option = document.createElement('option');
                        option.value = parroquia.id;
                        option.textContent = parroquia.descripcion;
                        selectParroquia.appendChild(option);
                    });
                } else {
                    alert(data.message);
                }
            });
        }
    });



    //METODO PARA LLAMAR LOS DATOS X ID
function editarUsuario(id) {
    $.ajax({
        url: 'web.php?controller=usuario&action=view&id=' + id,
        method: 'GET',
        dataType: 'json',
        success: function (data) {
            if (!data.success) {
                alert('No se encontró el Usuario.');
                return;
            }

            const usuario = data.usuario;

            // Llenar campos simples
            document.getElementById('id_update').value = usuario.id;
            document.getElementById('id_persona_update').value = usuario.id_persona;
            document.getElementById('nombres_update').value = usuario.nombres;
            document.getElementById('apellidos_update').value = usuario.apellidos;
            document.getElementById('cedula_update').value = usuario.cedula;
            document.getElementById('genero_update').value = usuario.genero;
            document.getElementById('fecha_nacimiento_update').value = usuario.fecha_nacimiento;
            document.getElementById('telefono_update').value = usuario.telefono;
            document.getElementById('provincia_update').value = usuario.id_provincia;
            document.getElementById('rol_update').value = usuario.id_rol;
            document.getElementById('tipo_usuario_update').value = usuario.id_tipo_usuario;
            document.getElementById('correo_update').value = usuario.correo;
            document.getElementById('username_update').value = usuario.username;
            document.getElementById('estado_update').value = usuario.estado;

            // Limpiar cantón y parroquia
            const selectCanton = document.getElementById('canton_update');
            const selectParroquia = document.getElementById('parroquia_update');
            selectCanton.innerHTML = '<option value="">Seleccione cantón</option>';
            selectParroquia.innerHTML = '<option value="">Seleccione parroquia</option>';

            // Llenar cantones según provincia
            if (usuario.id_provincia) {
                fetch('web.php?controller=canton&action=viewByProvinciaId&id=' + usuario.id_provincia)
                    .then(response => response.json())
                    .then(cantonData => {
                        if (cantonData.success) {
                            cantonData.cantones.forEach(canton => {
                                const option = document.createElement('option');
                                option.value = canton.id;
                                option.textContent = canton.descripcion;
                                selectCanton.appendChild(option);
                            });

                            // Seleccionar el cantón del usuario
                            selectCanton.value = usuario.id_canton;

                            // Ahora cargar parroquias según el cantón seleccionado
                            if (usuario.id_canton) {
                                fetch('web.php?controller=parroquia&action=viewByCantonId&id=' + usuario.id_canton)
                                    .then(resp => resp.json())
                                    .then(parroquiaData => {
                                        if (parroquiaData.success) {
                                            parroquiaData.parroquias.forEach(parroquia => {
                                                const option = document.createElement('option');
                                                option.value = parroquia.id;
                                                option.textContent = parroquia.descripcion;
                                                selectParroquia.appendChild(option);
                                            });

                                            // Seleccionar parroquia del usuario
                                            selectParroquia.value = usuario.id_parroquia;
                                        }
                                    });
                            }
                        } else {
                            alert(cantonData.message);
                        }
                    });
            }

            // Mostrar modal
            const modal = new bootstrap.Modal(document.getElementById('usuario_update'));
            modal.show();
        },
        error: function () {
            alert('Ocurrió un error al obtener los datos del Usuario.');
        }
    });
}

//METODO PARA EDITAR
    document.querySelector('.btn-usuarioUpdate').addEventListener('click', function() {
        const id = document.getElementById('id_update').value.trim();
        //DATOS DE PERSONA
        const id_persona = document.getElementById('id_persona_update').value;
        const cedula = document.getElementById('cedula_update').value;
        const nombres = document.getElementById('nombres_update').value;
        const apellidos = document.getElementById('apellidos_update').value;
        const genero = document.getElementById('genero_update').value;
        const fecha_nacimiento = document.getElementById('fecha_nacimiento_update').value;
        const telefono = document.getElementById('telefono_update').value;
        const id_parroquia = document.getElementById('parroquia_update').value;
        const estado = document.getElementById('estado_update').value;

        //DATOS DE USUARIO
        const id_rol = document.getElementById('rol_update').value;
        const id_tipo_usuario = document.getElementById('tipo_usuario_update').value;
        const correo = document.getElementById('correo_update').value;
        const username = document.getElementById('username_update').value;

        //VALIDACIÓN DE CAMPOS VACIOS
        if (cedula === '' ||
             nombres === '' ||
             apellidos === '' ||
             genero === '' ||
             estado === 'Seleccionar Genero' || 
             fecha_nacimiento === '' ||
             telefono === '' ||
             id_parroquia === '' ||
             id_parroquia === 'Seleccione Parroquia' ||
             estado === '' || 
             estado === 'Seleccionar Estado' || 
             id_rol === '' || 
             id_rol === 'Seleccionar Rol' || 
             id_tipo_usuario === '' || 
             id_tipo_usuario === 'Seleccionar Tipo Usuario' || 
             username === '' || 
             correo === '') {
            alert('Por favor, complete todos los campos correctamente.:)');
            return;
        }

        //VALIDACIÓN DE USERNAME -> INTERNO
        $.ajax({
            //RUTA DE BUSQUEDA X USERNAME
            url: 'web.php?controller=usuario&action=validate&username='+username,
            method: 'GET',
            dataType: 'json',
            success: function (data) {
                //SI EL USUARIO ES ENCONTRADO -> ACTUALIZA SOLO SI ES EL MISMO ID
                if (data.success) {
                    let usuarios = data.usuario; // GUARDA LA DATA
                    // SI ENCONTRO MÁS DE 1 REGISTRO -> ERROR
                    if (usuarios.length > 1) {
                        Swal.fire({
                            icon: 'error',
                            title: '¡Error!',
                            text: 'Existen varios usuarios con el mismo USERNAME. No se puede actualizar.',
                            confirmButtonColor: '#0072ff'
                        });
                        return;
                    }else{
                        let encontrado = usuarios[0]; // el único resultado
                        // SI EL ID ENCONTRADO ES EL MISMO QUE ESTAMOS EDITANDO -> PERMITE ACTUALIZAR
                        if (encontrado.id_usuario == id) {
                           
                            //NOTIFICACIÓN PARA ACTUALIZAR
                            Swal.fire({
                                icon: 'question',
                                title: '¿Estás seguro?',
                                text: '¿Deseas actualizar este usuario?',
                                showCancelButton: true,
                                confirmButtonText: 'Sí, actualizar',
                                cancelButtonText: 'Cancelar',
                                confirmButtonColor: '#0072ff',
                                cancelButtonColor: '#d33'
                            }).then((result) => {

                                if (result.isConfirmed) {

                                    console.log("se confirma actualizacion");
                                    //ACTUALIZACIONES X SEPARADO
                                    let bandera_persona = false;
                                    let bandera_usuario = false;
                                    
                                    //DATOS PARA LOS FORM

                                    // ACTUALIZAR PERSONA
                                    const formDataPersona = new FormData();
                                    formDataPersona.append('id', id_persona);
                                    formDataPersona.append('cedula', cedula);
                                    formDataPersona.append('nombres', nombres);
                                    formDataPersona.append('apellidos', apellidos);
                                    formDataPersona.append('genero', genero);
                                    formDataPersona.append('fecha_nacimiento', fecha_nacimiento);
                                    formDataPersona.append('telefono', telefono);
                                    formDataPersona.append('id_parroquia', id_parroquia);
                                    formDataPersona.append('estado', estado);

                                     // ACTUALIZAR USUARIO
                                    const formDataUsuario = new FormData();
                                    formDataUsuario.append('id', id);
                                    formDataUsuario.append('id_persona', id_persona);
                                    formDataUsuario.append('id_rol', id_rol);
                                    formDataUsuario.append('id_tipo_usuario', id_tipo_usuario);
                                    formDataUsuario.append('correo', correo);
                                    formDataUsuario.append('username', username);
                                    formDataUsuario.append('estado', estado);

                                    //MANEJO DE PROMESAS DE ACTUALIZACION -> CON EL BACKEND
                                    Promise.all([
                                        //ACTUALIZACION DE PERSONA
                                        fetch('web.php?controller=persona&action=update', {
                                            method: 'POST',
                                            body: formDataPersona
                                        }).then(r => r.text()),

                                        //ACTUALIZACION DE USUARIO
                                        fetch('web.php?controller=usuario&action=update', {
                                            method: 'POST',
                                            body: formDataUsuario
                                        }).then(r => r.text())
                                    ])
                                    .then(([resPersona, resUsuario]) => {
                                        //PREGUNTA SI SE REALIZARON LAS ACTUALIZACIONES CON EXITO
                                        if (resPersona === 'success' && resUsuario === 'success') {
                                            Swal.fire({
                                                icon: 'success',
                                                title: '¡Éxito!',
                                                text: 'Usuario Actualizado en la BD!',
                                                confirmButtonColor: '#0072ff'
                                            }).then(() => location.reload());
                                        } else {
                                            Swal.fire({
                                                icon: 'error',
                                                title: '¡Error!',
                                                text: 'No se pudo Actualizar el Usuario',
                                                confirmButtonColor: '#0072ff'
                                            }).then(() => location.reload());
                                        }
                                    })//CONTROL DE ERRORES
                                    .catch(error => {
                                        alert('Error en la solicitud: ' + error);
                                    });
                                }
                            });

                        } else {
                            // EL ID NO COINCIDE -> ERROR
                            Swal.fire({
                                icon: 'error',
                                title: '¡Error!',
                                text: 'Ya existe un usuario en el sistema con la misma cédula, correo o username.',
                                confirmButtonColor: '#0072ff'
                            });
                            return;
                        }
                    }
                } else {
                    //EL BACKEND ME DEVUELVE TRUE Y DATA CUANDO EXISTEN Y FALSE CUANDO NO EXISTE
                    //SI CAMBIA TODOS LOS DATOS PRINCIPALES COMO CORREO / USERNAME / CEDULA ES MEJOR QUE CREE UN USUARIO NUEVO
                    if(id_tipo_usuario == 2){

                        //NOTIFICACIÓN PARA ACTUALIZAR
                            Swal.fire({
                                icon: 'question',
                                title: '¿Estás seguro?',
                                text: '¿Deseas actualizar este usuario?',
                                showCancelButton: true,
                                confirmButtonText: 'Sí, actualizar',
                                cancelButtonText: 'Cancelar',
                                confirmButtonColor: '#0072ff',
                                cancelButtonColor: '#d33'
                            }).then((result) => {

                                if (result.isConfirmed) {

                                    console.log("se confirma actualizacion");
                                    //ACTUALIZACIONES X SEPARADO
                                    let bandera_persona = false;
                                    let bandera_usuario = false;
                                    
                                    //DATOS PARA LOS FORM

                                    // ACTUALIZAR PERSONA
                                    const formDataPersona = new FormData();
                                    formDataPersona.append('id', id_persona);
                                    formDataPersona.append('cedula', cedula);
                                    formDataPersona.append('nombres', nombres);
                                    formDataPersona.append('apellidos', apellidos);
                                    formDataPersona.append('genero', genero);
                                    formDataPersona.append('fecha_nacimiento', fecha_nacimiento);
                                    formDataPersona.append('telefono', telefono);
                                    formDataPersona.append('id_parroquia', id_parroquia);
                                    formDataPersona.append('estado', estado);

                                     // ACTUALIZAR USUARIO
                                    const formDataUsuario = new FormData();
                                    formDataUsuario.append('id', id);
                                    formDataUsuario.append('id_persona', id_persona);
                                    formDataUsuario.append('id_rol', id_rol);
                                    formDataUsuario.append('id_tipo_usuario', id_tipo_usuario);
                                    formDataUsuario.append('correo', correo);
                                    formDataUsuario.append('username', username);
                                    formDataUsuario.append('estado', estado);

                                    //MANEJO DE PROMESAS DE ACTUALIZACION -> CON EL BACKEND
                                    Promise.all([
                                        //ACTUALIZACION DE PERSONA
                                        fetch('web.php?controller=persona&action=update', {
                                            method: 'POST',
                                            body: formDataPersona
                                        }).then(r => r.text()),

                                        //ACTUALIZACION DE USUARIO
                                        fetch('web.php?controller=usuario&action=update', {
                                            method: 'POST',
                                            body: formDataUsuario
                                        }).then(r => r.text())
                                    ])
                                    .then(([resPersona, resUsuario]) => {
                                        //PREGUNTA SI SE REALIZARON LAS ACTUALIZACIONES CON EXITO
                                        if (resPersona === 'success' && resUsuario === 'success') {
                                            Swal.fire({
                                                icon: 'success',
                                                title: '¡Éxito!',
                                                text: 'Usuario Actualizado en la BD!',
                                                confirmButtonColor: '#0072ff'
                                            }).then(() => location.reload());
                                        } else {
                                            Swal.fire({
                                                icon: 'error',
                                                title: '¡Error!',
                                                text: 'No se pudo Actualizar el Usuario',
                                                confirmButtonColor: '#0072ff'
                                            }).then(() => location.reload());
                                        }
                                    })//CONTROL DE ERRORES
                                    .catch(error => {
                                        alert('Error en la solicitud: ' + error);
                                    });
                                }
                            });

                    }else{
                        Swal.fire({
                            icon: 'error',
                            title: '¡Error!',
                            text: 'El usuario ha cambiado sus caracteristicas principales como username, cedula o correo -> CREAR UN NUEVO USUARIO',
                            confirmButtonColor: '#0072ff'
                        });
                        return;
                    }
                }
            },
            error: function () {
                alert('Ocurrió un error al obtener los datos del Usuario.');
            }
        });
        
    });


    //METODO PARA ELIMINAR
    function eliminarUsuario(id) {
        $.ajax({

            //RUTA DE BUSQUEDA X ID
            url: 'web.php?controller=usuario&action=view&id='+id,
            method: 'GET',
            dataType: 'json',

            success: function(data) {
                if (data.success) {
                    Swal.fire({
                        icon: 'question',
                        title: '¿Estás seguro?',
                        text: '¿Deseas eliminar este usuario?',
                        showCancelButton: true,
                        confirmButtonText: 'Sí, eliminar',
                        cancelButtonText: 'Cancelar',
                        confirmButtonColor: '#0072ff',
                        cancelButtonColor: '#d33'
                    }).then((result) => {
                        if (result.isConfirmed) {

                            // Enviar datos a PHP
                            const formData = new FormData();
                            formData.append('id', data.usuario.id);

                            // función para eliminar
                            fetch('web.php?controller=usuario&action=delete', {
                                    method: 'POST',
                                    body: formData
                                })
                                .then(response => response.text())
                                .then(data => {
                                    if (data === 'success') {
                                        console.log(data);
                                        Swal.fire({
                                            icon: 'success',
                                            title: '¡Exito!',
                                            text: 'Usuario Eliminado en la BD!',
                                            confirmButtonColor: '#0072ff'
                                        }).then((result) => {
                                            if (result.isConfirmed) {
                                                location.reload();
                                            }
                                        });
                                    } else {
                                        Swal.fire({
                                            icon: 'error',
                                            title: '¡Error!',
                                            text: 'No se pudo Eliminar el Usuario',//data,
                                            confirmButtonColor: '#0072ff'
                                        }).then((result) => {
                                            if (result.isConfirmed) {
                                                location.reload();
                                            }
                                        });
                                    }
                                })
                                .catch(error => {
                                    alert('Error en la solicitud: ' + error);
                                });

                        }
                    });

                } else {
                    alert('No se encontró el usuario.');
                }
            },
            error: function() {
                alert('Ocurrió un error al obtener los datos del usuario.');
            }
        });
    }


    //METODO PARA SOLICITAR Actualizar Contraseña ->MODAL
    function CambiarPasswordUsuario(id) {
        $.ajax({
            url: 'web.php?controller=usuario&action=view&id=' + id,
            method: 'GET',
            dataType: 'json',
            success: function (data) {
                if (!data.success) {
                    alert('No se encontró el Usuario.');
                    return;
                }
                
                const usuario = data.usuario;
                // Llenar campo Id
                document.getElementById('id_updatePassword').value = usuario.id;
                
                // Mostrar modal
                const modal = new bootstrap.Modal(document.getElementById('usuario_update_password'));
                modal.show();
            },
            error: function () {
                alert('Ocurrió un error al obtener los datos del Usuario.');
            }
        });
    }

    //METODO PARA EDITAR PASSWORD
    document.querySelector('.btn-usuarioUpdatePassword').addEventListener('click', function() {
        const id = document.getElementById('id_updatePassword').value.trim();
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

    document.getElementById('cedula_create').addEventListener('input', function(e) {
        this.value = this.value.replace(/\D/g, ''); // Elimina cualquier cosa que no sea dígito
    });