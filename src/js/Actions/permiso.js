//FUNCIONALIDAD DE PERMISO
//VISTA QUE PERTENECE -> views/permisos/index.php

    //DATATABLE -> se inicia con la carga completa del DOM
    document.addEventListener('DOMContentLoaded', function() {
    //VARIABLES PARA DATATABLE -> CAMBIAR DE ACUERDO AL MODULO Y TABLA
    let tabla = document.querySelector("#table_permisos tbody");
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
    function verPermiso(id) {
        $.ajax({

            //RUTA DE BUSQUEDA X ID
            url: 'web.php?controller=permiso&action=view&id='+id,
            method: 'GET',
            dataType: 'json',
            
            success: function (data) {
                if (data.success) {
                    document.getElementById('id_submodulo_read').value = data.permiso.submodulo;
                    document.getElementById('descripcion_read').value = data.permiso.descripcion;
                    document.getElementById('estado_read').value = data.permiso.estado;

                    var modal = new bootstrap.Modal(document.getElementById('permiso_read'));
                    modal.show();
                } else {
                    alert('No se encontró el permiso.');
                }
            },
            error: function () {
                alert('Ocurrió un error al obtener los datos del permiso.');
            }
        });
    }

    //METODO PARA CREAR
    document.querySelector('.btn-permisoCreate').addEventListener('click', function() {
        
        const id_submodulo = document.getElementById('id_submodulo_create').value;
        const descripcion = document.getElementById('descripcion_create').value.trim();
        const estado = document.getElementById('estado_create').value;

        //VALIDACIÓN DE CAMPOS VACIOS
        if (descripcion === '' ||
             estado === '' || 
             estado === 'Seleccionar Estado' || 
             id_submodulo === '' || 
             id_submodulo === 'Seleccionar Submódulo') {
            alert('Por favor, complete todos los campos correctamente.');
            return;
        }

        // ENVIAR DATOS A PHP -> BACKEND -> SE CREA UN FORMULARIO Y SE GUARDA LOS DATOS EN ESE FORMULARIO
        const formData = new FormData();
        formData.append('id_submodulo', id_submodulo);
        formData.append('descripcion', descripcion);
        formData.append('estado', estado);

        //RUTA PARA INSERTAR -> GUARDAR EN BD
        fetch('web.php?controller=permiso&action=store', {
                method: 'POST',
                body: formData
            })
            .then(response => response.text())
            .then(data => {
                if (data === 'success') {
                    Swal.fire({
                        icon: 'success',
                        title: '¡Exito!',
                        text: 'Nuevo Permiso guardado en la BD!',
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
                        text: 'No se pudo Guardar el Permiso',//data,
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
    function editarPermiso(id) {
        $.ajax({

            //RUTA DE BUSQUEDA X ID
            url: 'web.php?controller=permiso&action=view&id='+id,
            method: 'GET',
            dataType: 'json',
            
            success: function(data) {
                if (data.success) {
                    document.getElementById('id_update').value = id;
                    document.getElementById('id_submodulo_update').value = data.permiso.id_submodulo;
                    document.getElementById('descripcion_update').value = data.permiso.descripcion;
                    document.getElementById('estado_update').value = data.permiso.estado;

                    var modal = new bootstrap.Modal(document.getElementById('permiso_update'));
                    modal.show();
                } else {
                    alert('No se encontró el permiso.');
                }
            },
            error: function() {
                alert('Ocurrió un error al obtener los datos del permiso.');
            }
        });
    }

    //METODO PARA EDITAR
    document.querySelector('.btn-permisoUpdate').addEventListener('click', function() {
        const id = document.getElementById('id_update').value.trim();
        const id_submodulo = document.getElementById('id_submodulo_update').value;
        const descripcion = document.getElementById('descripcion_update').value.trim();
        const estado = document.getElementById('estado_update').value;

        //VALIDACIÓN DE CAMPOS VACIOS
        if (descripcion === '' ||
             estado === '' || 
             estado === 'Seleccionar Estado' || 
             id_submodulo === '' || 
             id_submodulo === 'Seleccionar Submódulo') {
            alert('Por favor, complete todos los campos correctamente.');
            return;
        }

        // ENVIAR DATOS A PHP -> BACKEND -> SE CREA UN FORMULARIO Y SE GUARDA LOS DATOS EN ESE FORMULARIO
        const formData = new FormData();
        formData.append('id', id);
        formData.append('id_submodulo', id_submodulo);
        formData.append('descripcion', descripcion);
        formData.append('estado', estado);

        Swal.fire({
            icon: 'question',
            title: '¿Estás seguro?',
            text: '¿Deseas actualizar este Permiso?',
            showCancelButton: true,
            confirmButtonText: 'Sí, actualizar',
            cancelButtonText: 'Cancelar',
            confirmButtonColor: '#0072ff',
            cancelButtonColor: '#d33'
        }).then((result) => {
            if (result.isConfirmed) {

                // RUTA DE ACTUALIZACION
                fetch('web.php?controller=permiso&action=update', {
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
                                text: 'Permiso Actualizado en la BD!',
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
                                text: 'No se pudo Actualizar el Permiso',//data,
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
    function eliminarPermiso(id) {
        $.ajax({

            //RUTA DE BUSQUEDA X ID
            url: 'web.php?controller=permiso&action=view&id='+id,
            method: 'PET',
            dataType: 'json',

            success: function(data) {
                if (data.success) {
                    Swal.fire({
                        icon: 'question',
                        title: '¿Estás seguro?',
                        text: '¿Deseas eliminar este Permiso?',
                        showCancelButton: true,
                        confirmButtonText: 'Sí, eliminar',
                        cancelButtonText: 'Cancelar',
                        confirmButtonColor: '#0072ff',
                        cancelButtonColor: '#d33'
                    }).then((result) => {
                        if (result.isConfirmed) {

                            // Enviar datos a PHP
                            const formData = new FormData();
                            formData.append('id', data.permiso.id);

                            // función para eliminar
                            fetch('web.php?controller=permiso&action=delete', {
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
                                            text: 'Permiso Eliminado en la BD!',
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
                                            text: 'No se pudo Eliminar el Permiso',//data,
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
                    alert('No se encontró el Permiso.');
                }
            },
            error: function() {
                alert('Ocurrió un error al obtener los datos del permiso.');
            }
        });
    }