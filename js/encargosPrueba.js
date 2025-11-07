$(document).ready(function() {

    $.post("../controlador/permisosCtrl.php", {
        action:'presentarRoles'																		
    }, 
    function(data){
        $('#listaRoles').html(data.listaRoles);
    }, 'json');

    $(document).on("click", "#idBtnRol",  function () {
        var idrol = $(this).data('idrol');   
        $('#idrol').val(idrol);
        var nomrerol = $(this).data('nomrerol');   
        $('#nombrerol').val(nomrerol);	

        // Limpia y carga acordeón de permisos para este rol
        $('#listaAsignarPermisos').html('<div class="text-muted px-2 py-1">Cargando permisos...</div>');
        $.post("../controlador/permisosCtrl.php", {
            action: 'presentarMenu',
            idrol: idrol
            // Aquí podemos agregar más parámetros si es necesario
        }, function(data){
            $('#listaAsignarPermisos').html(data.listaMenu);
            $('#asignarPermisos').modal('show');
        }, 'json');
    });	

    // Manejar cambios en los switches de permisos
    $(document).on('change', '.permiso-switch', function(){
        const $switch = $(this);
        const idRol = $switch.data('id-rol');
        const idMenu = $switch.data('id-menu');
        const idSubmenu = $switch.data('id-submenu');
        const activar = $switch.is(':checked');

        // Deshabilitar el switch mientras se procesa, es decir, mientras se espera la respuesta del servidor
        $switch.prop('disabled', true);

        // Enviar la solicitud para guardar el permiso
        $.post("../controlador/permisosCtrl.php", {
            action: 'guardarPermiso',
            id_rol: idRol,
            id_menu: idMenu,
            id_submenu: idSubmenu,
            activar: activar
        }, function(response){
            if(!response.success){
                // Error: revertir el estado del switch silenciosamente
                $switch.prop('checked', !activar);
            }
            // Rehabilitar el switch
            $switch.prop('disabled', false);
        }, 'json').fail(function(){
            // En caso de error de comunicación, revertir silenciosamente
            $switch.prop('checked', !activar);
            $switch.prop('disabled', false);
        });
    });

    // Manejar el hover sobre los elementos del menú
    $('ul li:has(ul)').hover(function(e) {
        $(this).find('ul').css({display: "block"});
    },
    function(e) {
        $(this).find('ul').css({display: "none"});
    });
    
    // Solo mostrar el modal al hacer clic en el submenú 'Roles'
    $('#verRoles').on('click', function(e) {
        e.preventDefault();
        $('#languageModal').modal('show');
    });	

    $('#buscarUsuario').on('click', function(e) {
        //si el input de busqueda esta vacio
        if($('#buscarUsu').val() === '') {
            mostrarAlerta('warning', 'Por favor ingrese un término de búsqueda');
        } else {
            // Mostrar indicador de carga
            $('#resultadosBusqueda').html('<div class="text-center"><i class="fas fa-spinner fa-spin"></i> Buscando...</div>');
            $('#Alertas').html('');
            
            //si no esta vacio, se envia la peticion a ctrlvalidacion
            $.post("../controlador/ctrlvalidacion.php", {
                action: 'buscarUsuario',
                buscarUsu: $('#buscarUsu').val()
            }, function(data) {
                // Manejar la respuesta del servidor
                if(data.success) {
                    // Mostrar los resultados de la búsqueda
                    $('#resultadosBusqueda').html(data.resultados);
                } else {
                    $('#resultadosBusqueda').html('<div class="alert alert-info">No se encontraron resultados</div>');
                }
            }, 'json').fail(function() {
                $('#resultadosBusqueda').html('');
                mostrarAlerta('danger', 'Error de comunicación con el servidor');
            });
        }
    });

    // Permitir buscar al presionar Enter
    $('#buscarUsu').on('keypress', function(e) {
        if(e.which === 13) {
            $('#buscarUsuario').click();
        }
    });

    // Función para editar usuario - abrir modal con datos
    window.editarUsuario = function(id, nombre, apellido, idValidacion) {
        $('#usuarioId').val(id);
        $('#nombreUsuario').val(nombre + ' ' + apellido);
        $('#estadoValidacion').val(idValidacion);
        $('#modalEditarValidacion').modal('show');
    };

    // Manejar guardar validación
    $('#guardarValidacion').on('click', function() {
        const usuarioId = $('#usuarioId').val();
        const estadoValidacion = $('#estadoValidacion').val();
        
        if(usuarioId && estadoValidacion !== '') {
            // Deshabilitar botón mientras se procesa
            $('#guardarValidacion').prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Guardando...');
            
            $.post("../controlador/ctrlvalidacion.php", {
                action: 'actualizarValidacion',
                usuarioId: usuarioId,
                estadoValidacion: estadoValidacion
            }, function(data) {
                if(data.success) {
                    $('#modalEditarValidacion').modal('hide');
                    mostrarAlerta('success', 'Estado de validación actualizado correctamente');
                    // Recargar resultados de búsqueda si hay alguno
                    if($('#buscarUsu').val() !== '') {
                        $('#buscarUsuario').click();
                    }
                } else {
                    mostrarAlerta('danger', 'Error al actualizar: ' + (data.mensaje || 'Error desconocido'));
                }
            }, 'json').fail(function() {
                mostrarAlerta('danger', 'Error de comunicación con el servidor');
            }).always(function() {
                // Rehabilitar botón
                $('#guardarValidacion').prop('disabled', false).html('Guardar Cambios');
            });
        } else {
            mostrarAlerta('warning', 'Por favor complete todos los campos');
        }
    });

    // Función para mostrar alertas con auto-desvanecimiento
    window.mostrarAlerta = function(tipo, mensaje, contenedor = '#Alertas') {
        // Mapear tipos de alerta
        let alertClass;
        switch(tipo) {
            case 'success':
                alertClass = 'alert-success';
                break;
            case 'error':
            case 'danger':
                alertClass = 'alert-danger';
                break;
            case 'warning':
                alertClass = 'alert-warning';
                break;
            default:
                alertClass = 'alert-info';
        }
        
        const alerta = `<div class="alert ${alertClass} alert-dismissible fade show" role="alert">
                            ${mensaje}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>`;
        
        $(contenedor).html(alerta);
        
        // Auto-cerrar después de 5 segundos
        setTimeout(function() {
            $('.alert').fadeOut(500, function() {
                $(this).alert('close');
            });
        }, 5000);
    };

    // FUNCIONES PARA ADMIN INVENTARIO
    
    // Cargar inventario y distribuidoras al iniciar (solo si estamos en admin_inventario.php)
    if(window.location.pathname.includes('admin_inventario.php')) {
        cargarInventario();
        cargarDistribuidoras();
    }
    
    // EVENTOS PARA PELÍCULAS
    // Solo ejecutar si estamos en la página de películas (verificar si existe el elemento)
    if ($('#listaPeliculas').length) {
        // Cargar películas al iniciar
        cargarPeliculas();
        
        // Buscar películas
        $('#buscarPeliculaBtn').click(function() {
            const busqueda = $('#buscarPelicula').val();
            buscarPeliculas(busqueda);
        });
        
        // Buscar al presionar Enter
        $('#buscarPelicula').keypress(function(e) {
            if(e.which == 13) {
                $('#buscarPeliculaBtn').click();
            }
        });
        
        // Confirmar renta
        $('#confirmarRenta').click(function() {
            const idPelicula = $('#peliculaId').val();
            
            $.post("../controlador/ctrlPeliculas.php", {
                action: 'rentarPelicula',
                idPelicula: idPelicula
            }, function(response) {
                if(response.success) {
                    mostrarAlerta('success', response.mensaje, '#alertasPeliculas');
                    $('#modalRentarPelicula').modal('hide');
                    cargarPeliculas(); // Recargar la lista
                } else {
                    mostrarAlerta('error', response.mensaje, '#alertasPeliculas');
                }
            }, 'json');
        });
    }
    
    // EVENTOS PARA ADMIN INVENTARIO
    
    // Buscar en inventario
    $('#btnBuscarInventario').click(function() {
        const busqueda = $('#buscarInventario').val();
        buscarInventario(busqueda);
    });
    
    // Buscar al presionar Enter
    $('#buscarInventario').keypress(function(e) {
        if(e.which == 13) {
            $('#btnBuscarInventario').click();
        }
    });
    
    // Limpiar formulario al cerrar modal
    $('#modalAgregarPelicula').on('hidden.bs.modal', function() {
        limpiarFormularioPelicula();
    });
    
    // Guardar película
    $('#btnGuardarPelicula').click(function() {
        if(validarFormularioPelicula()) {
            guardarPelicula();
        }
    });
});

// FUNCIONES PARA ADMIN INVENTARIO

// Función para cargar inventario
function cargarInventario() {
    $('#listaInventario').html('<div class="text-center"><i class="fas fa-spinner fa-spin"></i> Cargando inventario...</div>');
    
    $.post("../controlador/ctrlInventario.php", {
        action: 'listarInventario'
    }, function(response) {
        $('#listaInventario').html(response.inventario);
    }, 'json');
}

// Función para buscar en inventario
function buscarInventario(busqueda) {
    $('#listaInventario').html('<div class="text-center"><i class="fas fa-spinner fa-spin"></i> Buscando...</div>');
    
    $.post("../controlador/ctrlInventario.php", {
        action: 'buscarInventario',
        busqueda: busqueda
    }, function(response) {
        $('#listaInventario').html(response.inventario);
    }, 'json');
}

// Función para cargar distribuidoras
function cargarDistribuidoras() {
    $.post("../controlador/ctrlInventario.php", {
        action: 'listarDistribuidoras'
    }, function(response) {
        let opciones = '<option value="">Seleccionar distribuidora</option>';
        if(response.success && response.distribuidoras) {
            response.distribuidoras.forEach(function(dist) {
                opciones += `<option value="${dist.id}">${dist.nombre}</option>`;
            });
        }
        $('#distribuidora').html(opciones);
    }, 'json');
}

// Función para editar película, aqui se cargan los datos en el formulario
function editarPelicula(id, titulo, director, ano, stock, distribuidoraId) {
    $('#peliculaId').val(id);
    $('#titulo').val(titulo);
    $('#director').val(director);
    $('#anoLanzamiento').val(ano);
    $('#stock').val(stock);
    $('#distribuidora').val(distribuidoraId);
    
    $('#modalAgregarPeliculaLabel').text('Editar Película');
    $('#modalAgregarPelicula').modal('show');
}

// Función para eliminar película
function eliminarPelicula(id, titulo) {
    if(confirm(`¿Estás seguro de que deseas eliminar la película "${titulo}"?`)) {
        $.post("../controlador/ctrlInventario.php", {
            action: 'eliminarPelicula',
            id: id
        }, function(response) {
            if(response.success) {
                mostrarAlerta('success', response.mensaje, '#alertasInventario');
                cargarInventario();
            } else {
                mostrarAlerta('error', response.mensaje, '#alertasInventario');
            }
        }, 'json');
    }
}

// Función para validar el formulario de película
function validarFormularioPelicula() {
    const titulo = $('#titulo').val().trim();
    const director = $('#director').val().trim();
    const ano = $('#anoLanzamiento').val();
    const stock = $('#stock').val();
    const distribuidora = $('#distribuidora').val();

    // Validar campos requeridos
    if(!titulo) {
        mostrarAlerta('error', 'El título es requerido', '#alertasInventario');
        return false;
    }
    
    if(!director) {
        mostrarAlerta('error', 'El director es requerido', '#alertasInventario');
        return false;
    }
    
    if(!ano || ano < 1900 || ano > 3000) {
        mostrarAlerta('error', 'El año debe estar entre 1900 y 3000', '#alertasInventario');
        return false;
    }
    
    if(stock === '' || stock < 0) {
        mostrarAlerta('error', 'El stock debe ser un número mayor o igual a 0', '#alertasInventario');
        return false;
    }
    
    if(!distribuidora) {
        mostrarAlerta('error', 'Debe seleccionar una distribuidora', '#alertasInventario');
        return false;
    }
    
    return true;
}

// Función para guardar película
function guardarPelicula() {
    const formData = {
        action: $('#peliculaId').val() ? 'editarPelicula' : 'agregarPelicula',
        id: $('#peliculaId').val(),
        titulo: $('#titulo').val().trim(),
        director: $('#director').val().trim(),
        anoLanzamiento: $('#anoLanzamiento').val(),
        stock: $('#stock').val(),
        distribuidora: $('#distribuidora').val()
    };
    
    $.post("../controlador/ctrlInventario.php", formData, function(response) {
        if(response.success) {
            mostrarAlerta('success', response.mensaje, '#alertasInventario');
            $('#modalAgregarPelicula').modal('hide');
            cargarInventario();
        } else {
            mostrarAlerta('error', response.mensaje, '#alertasInventario');
        }
    }, 'json');
}

// Función para limpiar el formulario de película
function limpiarFormularioPelicula() {
    $('#formPelicula')[0].reset();
    $('#peliculaId').val('');
    $('#modalAgregarPeliculaLabel').text('Agregar Película');
}


// FUNCIONES PARA PELÍCULAS 

// Función para cargar películas
function cargarPeliculas() {
    $('#listaPeliculas').html('<div class="text-center"><i class="fas fa-spinner fa-spin"></i> Cargando películas...</div>');
    
    $.post("../controlador/ctrlPeliculas.php", {
        action: 'listarPeliculas'
    }, function(response) {
        $('#listaPeliculas').html(response.peliculas);
    }, 'json');
}

// Función para buscar películas
function buscarPeliculas(busqueda) {
    $('#listaPeliculas').html('<div class="text-center"><i class="fas fa-spinner fa-spin"></i> Buscando...</div>');
    
    $.post("../controlador/ctrlPeliculas.php", {
        action: 'buscarPeliculas',
        busqueda: busqueda
    }, function(response) {
        $('#listaPeliculas').html(response.peliculas);
    }, 'json');
}

// Función para ver detalle de película
function verDetalle(idPelicula) {
    $.post("../controlador/ctrlPeliculas.php", {
        action: 'detallePelicula',
        idPelicula: idPelicula
    }, function(response) {
        if(response.success) {
            const detalle = response.detalle;
            const contenido = `
                <div class="row">
                    <div class="col-md-6">
                        <p><strong>Título:</strong> ${detalle.titulo}</p>
                        <p><strong>Director:</strong> ${detalle.director}</p>
                        <p><strong>Año:</strong> ${detalle.año}</p>
                    </div>
                    <div class="col-md-6">
                        <p><strong>Stock:</strong> <span class="${detalle.stock > 0 ? 'text-success' : 'text-danger'}">${detalle.stock > 0 ? detalle.stock : 'Agotado'}</span></p>
                        <p><strong>Distribuidora:</strong> ${detalle.distribuidora || 'No especificada'}</p>
                    </div>
                </div>
            `;
            $('#contenidoDetalle').html(contenido);
        }
    }, 'json');
}





