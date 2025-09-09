//FUNCIONALIDAD DE SUBMODULO
//VISTA QUE PERTENECE -> views/submodulos/index.php

    //DATATABLE -> se inicia con la carga completa del DOM
    document.addEventListener('DOMContentLoaded', function() {
    //VARIABLES PARA DATATABLE -> CAMBIAR DE ACUERDO AL MODULO Y TABLA
    let tabla = document.querySelector("#table_submodulos tbody");
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
    function verSubmodulo(id) {
        $.ajax({

            //RUTA DE BUSQUEDA X ID
            url: 'web.php?controller=submodulo&action=view&id='+id,
            method: 'GET',
            dataType: 'json',
            
            success: function (data) {
                if (data.success) {
                    document.getElementById('id_modulo_read').value = data.submodulo.modulo;
                    document.getElementById('descripcion_read').value = data.submodulo.descripcion;
                    document.getElementById('ruta_read').value = data.submodulo.ruta;
                    document.getElementById('estado_read').value = data.submodulo.estado;

                    var modal = new bootstrap.Modal(document.getElementById('submodulo_read'));
                    modal.show();
                } else {
                    alert('No se encontró el submódulo.');
                }
            },
            error: function () {
                alert('Ocurrió un error al obtener los datos del submódulo.');
            }
        });
    }


    //METODO PARA CREAR
    document.querySelector('.btn-submoduloCreate').addEventListener('click', function() {
        const id_modulo = document.getElementById('id_modulo_create').value;
        const descripcion = document.getElementById('descripcion_create').value.trim();
        const ruta = document.getElementById('ruta_create').value;
        const estado = document.getElementById('estado_create').value;

        //VALIDACIÓN DE CAMPOS VACIOS
        if (descripcion === '' ||
             ruta === '' || 
             estado === '' || 
             estado === 'Seleccionar Estado' || 
             id_modulo === '' || 
             id_modulo === 'Seleccionar Modulo') {
            alert('Por favor, complete todos los campos correctamente.');
            return;
        }

        // ENVIAR DATOS A PHP -> BACKEND -> SE CREA UN FORMULARIO Y SE GUARDA LOS DATOS EN ESE FORMULARIO
        const formData = new FormData();
        formData.append('id_modulo', id_modulo);
        formData.append('descripcion', descripcion);
        formData.append('ruta', ruta);
        formData.append('estado', estado);

        //RUTA PARA INSERTAR -> GUARDAR EN BD
        fetch('web.php?controller=submodulo&action=store', {
                method: 'POST',
                body: formData
            })
            .then(response => response.text())
            .then(data => {
                if (data === 'success') {
                    Swal.fire({
                        icon: 'success',
                        title: '¡Exito!',
                        text: 'Nuevo Submodulo guardado en la BD!',
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
                        text: 'No se pudo Guardar el Submódulo',//data,
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
    function editarSubmodulo(id) {
        $.ajax({

            //RUTA DE BUSQUEDA X ID
            url: 'web.php?controller=submodulo&action=view&id='+id,
            method: 'GET',
            dataType: 'json',
            
            success: function(data) {
                if (data.success) {
                    document.getElementById('id_update').value = id;
                    document.getElementById('id_modulo_update').value = data.submodulo.id_modulo;
                    document.getElementById('descripcion_update').value = data.submodulo.descripcion;
                    document.getElementById('ruta_update').value = data.submodulo.ruta;
                    document.getElementById('estado_update').value = data.submodulo.estado;

                    var modal = new bootstrap.Modal(document.getElementById('submodulo_update'));
                    modal.show();
                } else {
                    alert('No se encontró el submódulo.');
                }
            },
            error: function() {
                alert('Ocurrió un error al obtener los datos del submódulo.');
            }
        });
    }


    //METODO PARA EDITAR
    document.querySelector('.btn-submoduloUpdate').addEventListener('click', function() {
        const id = document.getElementById('id_update').value.trim();
        const id_modulo = document.getElementById('id_modulo_update').value;
        const descripcion = document.getElementById('descripcion_update').value.trim();
        const ruta = document.getElementById('ruta_update').value;
        const estado = document.getElementById('estado_update').value;

        //VALIDACIÓN DE CAMPOS VACIOS
        if (descripcion === '' ||
             ruta === '' || 
             estado === '' || 
             estado === 'Seleccionar Estado' || 
             id_modulo === '' || 
             id_modulo === 'Seleccionar Modulo') {
            alert('Por favor, complete todos los campos correctamente.');
            return;
        }

        // ENVIAR DATOS A PHP -> BACKEND -> SE CREA UN FORMULARIO Y SE GUARDA LOS DATOS EN ESE FORMULARIO
        const formData = new FormData();
        formData.append('id', id);
        formData.append('id_modulo', id_modulo);
        formData.append('descripcion', descripcion);
        formData.append('ruta', ruta);
        formData.append('estado', estado);

        Swal.fire({
            icon: 'question',
            title: '¿Estás seguro?',
            text: '¿Deseas actualizar este Submódulo?',
            showCancelButton: true,
            confirmButtonText: 'Sí, actualizar',
            cancelButtonText: 'Cancelar',
            confirmButtonColor: '#0072ff',
            cancelButtonColor: '#d33'
        }).then((result) => {
            if (result.isConfirmed) {

                // RUTA DE ACTUALIZACION
                fetch('web.php?controller=submodulo&action=update', {
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
                                text: 'Modulo Actualizado en la BD!',
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
                                text: 'No se pudo Actualizar el Módulo',//data,
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
    function eliminarSubmodulo(id) {
        $.ajax({

            //RUTA DE BUSQUEDA X ID
            url: 'web.php?controller=submodulo&action=view&id='+id,
            method: 'PET',
            dataType: 'json',

            success: function(data) {
                if (data.success) {
                    Swal.fire({
                        icon: 'question',
                        title: '¿Estás seguro?',
                        text: '¿Deseas eliminar este Submódulo?',
                        showCancelButton: true,
                        confirmButtonText: 'Sí, eliminar',
                        cancelButtonText: 'Cancelar',
                        confirmButtonColor: '#0072ff',
                        cancelButtonColor: '#d33'
                    }).then((result) => {
                        if (result.isConfirmed) {

                            // Enviar datos a PHP
                            const formData = new FormData();
                            formData.append('id', data.submodulo.id);

                            // función para eliminar
                            fetch('web.php?controller=submodulo&action=delete', {
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
                                            text: 'Submodulo Eliminado en la BD!',
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
                                            text: 'No se pudo Eliminar el Submódulo',//data,
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
                    alert('No se encontró el módulo.');
                }
            },
            error: function() {
                alert('Ocurrió un error al obtener los datos del módulo.');
            }
        });
    }