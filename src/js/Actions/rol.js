//FUNCIONALIDAD DE ROL
//VISTA QUE PERTENECE -> views/roles/index.php

    //DATATABLE -> se inicia con la carga completa del DOM
    document.addEventListener('DOMContentLoaded', function() {
    //VARIABLES PARA DATATABLE -> CAMBIAR DE ACUERDO AL MODULO Y TABLA
    let tabla = document.querySelector("#table_roles tbody");
    let filas = Array.from(tabla.rows);
    let registrosPorPagina = 10;
    let paginaActual = 1;
    let totalPaginas = Math.ceil(filas.length / registrosPorPagina);

    // Buscar
    document.getElementById("buscarTabla").addEventListener("keyup", () => {
        filtrarYPaginar();
    });

    // Cambiar cantidad de registros
    document.getElementById("registrosPorPagina").addEventListener("change", (e) => {
        registrosPorPagina = parseInt(e.target.value);
        paginaActual = 1;
        filtrarYPaginar();
    });

    function filtrarYPaginar() {
        let filtro = document.getElementById("buscarTabla").value.toLowerCase();
        let filasFiltradas = filas.filter(fila => fila.textContent.toLowerCase().includes(filtro));
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

        // Rango dinámico de páginas (máximo 3 botones visibles)
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
        // Reemplazar íconos después de modificar el DOM
        feather.replace();
    }

    // Inicialización
    filtrarYPaginar();
    
    });

    //METODO VER
    function verRol(id) {
        $.ajax({

            //RUTA DE BUSQUEDA X ID
            url: 'web.php?controller=rol&action=view&id='+id,
            method: 'GET',
            dataType: 'json',
            
            success: function (data) {
                if (data.success) {
                    document.getElementById('descripcion_read').value = data.rol.descripcion;
                    document.getElementById('estado_read').value = data.rol.estado;

                    var modal = new bootstrap.Modal(document.getElementById('rol_read'));
                    modal.show();
                } else {
                    alert('No se encontró el rol.');
                }
            },
            error: function () {
                alert('Ocurrió un error al obtener los datos del rol.');
            }
        });
    }

    
    //METODO PARA CREAR
    document.querySelector('.btn-rolCreate').addEventListener('click', function() {
        const descripcion = document.getElementById('descripcion_create').value.trim();
        const estado = document.getElementById('estado_create').value;

        //VALIDACIÓN DE CAMPOS VACIOS
        if (descripcion === '' || estado === '' || estado === 'Seleccionar Estado') {
            alert('Por favor, complete todos los campos correctamente.');
            return;
        }

        // ENVIAR DATOS A PHP -> BACKEND -> SE CREA UN FORMULARIO Y SE GUARDA LOS DATOS EN ESE FORMULARIO
        const formData = new FormData();
        formData.append('descripcion', descripcion);
        formData.append('estado', estado);

        //RUTA PARA INSERTAR -> GUARDAR EN BD
        fetch('web.php?controller=rol&action=store', {
                method: 'POST',
                body: formData
            })
            .then(response => response.text())
            .then(data => {
                if (data === 'success') {
                    Swal.fire({
                        icon: 'success',
                        title: '¡Exito!',
                        text: 'Nuevo Rol guardado en la BD!',
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
                        text: 'No se pudo Guardar el Rol',//data,
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
    });

    
    //METODO PARA LLAMAR LOS DATOS X ID
    function editarRol(id) {
        $.ajax({

            //RUTA DE BUSQUEDA X ID
            url: 'web.php?controller=rol&action=view&id='+id,
            method: 'GET',
            dataType: 'json',
            
            success: function(data) {
                if (data.success) {
                    document.getElementById('id_update').value = id;
                    document.getElementById('descripcion_update').value = data.rol.descripcion;
                    document.getElementById('estado_update').value = data.rol.estado;

                    var modal = new bootstrap.Modal(document.getElementById('rol_update'));
                    modal.show();
                } else {
                    alert('No se encontró el rol.');
                }
            },
            error: function() {
                alert('Ocurrió un error al obtener los datos del rol.');
            }
        });
    }


    //METODO PARA EDITAR
    document.querySelector('.btn-rolUpdate').addEventListener('click', function() {
        const id = document.getElementById('id_update').value.trim();
        const descripcion = document.getElementById('descripcion_update').value.trim();
        const estado = document.getElementById('estado_update').value;

        if (descripcion === '' || estado === '' || id === '' || estado === 'Seleccionar Estado') {
            alert('Por favor, complete todos los campos.');
            return;
        }

        // ENVIAR DATOS A PHP -> BACKEND -> SE CREA UN FORMULARIO Y SE GUARDA LOS DATOS EN ESE FORMULARIO
        const formData = new FormData();
        formData.append('id', id);
        formData.append('descripcion', descripcion);
        formData.append('estado', estado);

        Swal.fire({
            icon: 'question',
            title: '¿Estás seguro?',
            text: '¿Deseas actualizar este rol?',
            showCancelButton: true,
            confirmButtonText: 'Sí, actualizar',
            cancelButtonText: 'Cancelar',
            confirmButtonColor: '#0072ff',
            cancelButtonColor: '#d33'
        }).then((result) => {
            if (result.isConfirmed) {

                // RUTA DE ACTUALIZACION
                fetch('web.php?controller=rol&action=update', {
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
                                text: 'Rol Actualizado en la BD!',
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
                                text: 'No se pudo Actualizar el Rol',//data,
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

    });


    //METODO PARA ELIMINAR
    function eliminarRol(id) {
        $.ajax({

            //RUTA DE BUSQUEDA X ID
            url: 'web.php?controller=rol&action=view&id='+id,
            method: 'GET',
            dataType: 'json',

            success: function(data) {
                if (data.success) {
                    Swal.fire({
                        icon: 'question',
                        title: '¿Estás seguro?',
                        text: '¿Deseas eliminar este rol?',
                        showCancelButton: true,
                        confirmButtonText: 'Sí, eliminar',
                        cancelButtonText: 'Cancelar',
                        confirmButtonColor: '#0072ff',
                        cancelButtonColor: '#d33'
                    }).then((result) => {
                        if (result.isConfirmed) {

                            // Enviar datos a PHP
                            const formData = new FormData();
                            formData.append('id', data.rol.id);

                            // función para eliminar
                            fetch('web.php?controller=rol&action=delete', {
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
                                            text: 'Rol Eliminado en la BD!',
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
                                            text: 'No se pudo Eliminar el Rol',//data,
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
                    alert('No se encontró el rol.');
                }
            },
            error: function() {
                alert('Ocurrió un error al obtener los datos del rol.');
            }
        });
    }

    





