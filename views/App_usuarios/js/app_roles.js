// ==========================================================================================================
// APP_ROLES.JS - GESTIÓN COMPLETA DE ROLES SIGUIENDO EL PATRÓN DE USUARIOS
// Replicado exacto del patrón de app_usuario.js
// ==========================================================================================================

// ==========================================================================================================
// INICIALIZACIÓN Y EVENTOS PRINCIPALES
// ==========================================================================================================
document.addEventListener('DOMContentLoaded', function() {
    // Animaciones suaves al cargar
    inicializarAnimacionesRoles();
    
    // Configurar validaciones del modal
    configurarValidacionesModalRol();
    
    // Event listeners para filtros
    configurarFiltrosRoles();
    
    // Cargar datos iniciales
    //cargarRoles();
    
    // Configurar validación en tiempo real del motivo de cambio de estado
    configurarValidacionMotivoRol();
});

// ==========================================================================================================
// FUNCIONES DE INICIALIZACIÓN
// ==========================================================================================================

function inicializarAnimacionesRoles() {
    const statsCards = document.querySelectorAll('.stats-card');
    statsCards.forEach((card, index) => {
        card.style.opacity = '0';
        card.style.transform = 'translateY(30px)';

        setTimeout(() => {
            card.style.transition = 'all 0.5s ease';
            card.style.opacity = '1';
            card.style.transform = 'translateY(0)';
        }, index * 150);
    });
}

function configurarValidacionesModalRol() {
    const form = document.getElementById('formNuevoRol'); 
    if (form) {
        const inputs = form.querySelectorAll('input[required], select[required], textarea[required]');

        inputs.forEach(input => {
            input.addEventListener('blur', function() {
                validateFieldRol(this);
            });

            input.addEventListener('input', function() {
                if (this.classList.contains('is-invalid')) {
                    validateFieldRol(this);
                }
            });
        });

        // Validaciones específicas para campos especiales del rol
        const rolNombreInput = document.getElementById('rolNombre');
        if (rolNombreInput) {
            rolNombreInput.addEventListener('input', function() {
                validateRolNombre(this);
            });
        }

        const rolDescripcionInput = document.getElementById('rolDescripcion');
        if (rolDescripcionInput) {
            rolDescripcionInput.addEventListener('input', function() {
                validateRolDescripcion(this);
            });
        }
    }

    // También configurar para el modal de edición
    const formEdit = document.getElementById('formEditarRolInfo');
    if (formEdit) {
        const inputs = formEdit.querySelectorAll('input[required], select[required], textarea[required]');

        inputs.forEach(input => {
            input.addEventListener('blur', function() {
                validateFieldRol(this);
            });

            input.addEventListener('input', function() {
                if (this.classList.contains('is-invalid')) {
                    validateFieldRol(this);
                }
            });
        });

        // Validaciones específicas para campos especiales del rol en edición
        const editarRolNombreInput = document.getElementById('editarRolNombre');
        if (editarRolNombreInput) {
            editarRolNombreInput.addEventListener('input', function() {
                validateRolNombre(this);
            });
        }

        const editarRolDescripcionInput = document.getElementById('editarRolDescripcion');
        if (editarRolDescripcionInput) {
            editarRolDescripcionInput.addEventListener('input', function() {
                validateRolDescripcion(this);
            });
        }
    }
}

function configurarFiltrosRoles() {
    const shareRol = document.getElementById('sharerol');
    const estadoRol = document.getElementById('estadorol');
    const nivelRol = document.getElementById('nivelrol');
    
    if (shareRol) {
        shareRol.addEventListener('input', filtrarRoles);
    }
    
    if (estadoRol) {
        estadoRol.addEventListener('change', filtrarRoles);
    }

    if (nivelRol) {
        nivelRol.addEventListener('change', filtrarRoles);
    }
}

function configurarValidacionMotivoRol() {
    const motivoInput = document.getElementById('motivoCambioEstadoRol');
    if (motivoInput) {
        motivoInput.addEventListener('input', function() {
            const valor = this.value.trim();

            if (valor.length >= 10) {
                this.classList.remove('is-invalid');
                this.classList.add('is-valid');
            } else if (valor.length > 0) {
                this.classList.remove('is-valid');
                this.classList.add('is-invalid');
            } else {
                this.classList.remove('is-valid', 'is-invalid');
            }
        });
    }
}

// ==========================================================================================================
// FUNCIONES DE VALIDACIÓN
// ==========================================================================================================

function validateFieldRol(field) {
    const value = field.value.trim();

    if (field.hasAttribute('required') && value === '') {
        setFieldErrorRol(field, 'Este campo es obligatorio');
        return false;
    } else {
        setFieldSuccessRol(field);
        return true;
    }
}

function validateRolNombre(field) {
    const value = field.value.trim();

    if (value === '') {
        setFieldErrorRol(field, 'Este campo es obligatorio');
        return false;
    } else if (value.length < 3) {
        setFieldErrorRol(field, 'El nombre debe tener al menos 3 caracteres');
        return false;
    } else if (value.length > 100) {
        setFieldErrorRol(field, 'El nombre no puede exceder 100 caracteres');
        return false;
    } else {
        setFieldSuccessRol(field);
        return true;
    }
}

function validateRolDescripcion(field) {
    const value = field.value.trim();

    // La descripción es opcional, pero si se llena debe cumplir requisitos
    if (value !== '' && value.length < 10) {
        setFieldErrorRol(field, 'La descripción debe tener al menos 10 caracteres');
        return false;
    } else if (value.length > 500) {
        setFieldErrorRol(field, 'La descripción no puede exceder 500 caracteres');
        return false;
    } else {
        setFieldSuccessRol(field);
        return true;
    }
}

// FUNCIÓN setFieldErrorRol
function setFieldErrorRol(field, message) {
    field.classList.remove('is-valid');
    field.classList.add('is-invalid');
    
    // Buscar el contenedor de feedback más cercano
    let feedback = null;
    
    // Si está dentro de un input-group, buscar después del input-group
    const inputGroup = field.closest('.input-group');
    if (inputGroup) {
        feedback = inputGroup.nextElementSibling;
    } else {
        feedback = field.nextElementSibling;
    }
    
    if (feedback && feedback.classList.contains('invalid-feedback')) {
        feedback.textContent = message;
    }
}

// FUNCIÓN setFieldSuccessRol
function setFieldSuccessRol(field) {
    field.classList.remove('is-invalid');
    field.classList.add('is-valid');
    
    // Buscar el contenedor de feedback más cercano
    let feedback = null;
    
    // Si está dentro de un input-group, buscar después del input-group
    const inputGroup = field.closest('.input-group');
    if (inputGroup) {
        feedback = inputGroup.nextElementSibling;
    } else {
        feedback = field.nextElementSibling;
    }
    
    if (feedback && feedback.classList.contains('invalid-feedback')) {
        feedback.textContent = '';
    }
}

// FUNCIÓN validateFormRol completa
function validateFormRol() {
    const form = document.getElementById('formNuevoRol');
    const inputs = form.querySelectorAll('input[required], select[required], textarea[required]');
    let isValid = true;

    inputs.forEach(input => {
        if (input.id === 'rolNombre') {
            if (!validateRolNombre(input)) isValid = false;
        } else if (input.id === 'rolDescripcion') {
            if (!validateRolDescripcion(input)) isValid = false;
        } else {
            if (!validateFieldRol(input)) isValid = false;
        }
    });

    return isValid;
}

// ==========================================================================================================
// FUNCIONES PARA MOSTRAR SWEETALERT2
// ==========================================================================================================

function mostrarSweetAlert(data) {
    // Si data es un string que contiene JSON, parsearlo
    let alertData;
    
    if (typeof data === 'string') {
        try {
            // Buscar el JSON dentro del string HTML
            const jsonMatch = data.match(/\{.*\}/s);
            if (jsonMatch) {
                alertData = JSON.parse(jsonMatch[0]);
            } else {
                // Si no es JSON, mostrar mensaje genérico
                alertData = {
                    Titulo: 'Información',
                    Texto: data,
                    Tipo: 'info'
                };
            }
        } catch (e) {
            alertData = {
                Titulo: 'Información',
                Texto: data,
                Tipo: 'info'
            };
        }
    } else {
        alertData = data;
    }

    // Mapear tipos de alerta
    const tipoIcono = {
        'success': 'success',
        'error': 'error',
        'warning': 'warning',
        'info': 'info'
    };

    // Mostrar SweetAlert normal
    Swal.fire({
        title: alertData.Titulo || 'Información',
        text: alertData.Texto || 'Operación completada',
        icon: tipoIcono[alertData.Tipo] || 'info',
        confirmButtonText: 'Aceptar',
        confirmButtonColor: '#3085d6'
    });
}

// ==========================================================================================================
// FUNCIÓN PRINCIPAL DE GUARDAR ROL
// ==========================================================================================================

function guardarRol() {
    if (!validateFormRol()) {
        Swal.fire({
            title: 'Formulario incompleto',
            text: 'Por favor complete todos los campos correctamente',
            icon: 'warning',
            confirmButtonText: 'Entendido',
            confirmButtonColor: '#f39c12'
        });
        return;
    }

    const formData = new FormData(document.getElementById('formNuevoRol'));

    // Mostrar loading con SweetAlert2
    Swal.fire({
        title: 'Guardando rol...',
        text: 'Por favor espere mientras procesamos la información',
        allowOutsideClick: false,
        allowEscapeKey: false,
        showConfirmButton: false,
        didOpen: () => {
            Swal.showLoading();
        }
    });

    // Enviar datos por AJAX
    fetch('../ajax/App_rolesAjax.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.text())
    .then(data => {
        // Cerrar loading
        Swal.close();

        // Procesar respuesta del PHP
        try {
            // Si la respuesta es JSON directo
            const jsonData = JSON.parse(data);
            mostrarSweetAlert(jsonData);
            
            // Si fue exitoso, cerrar modal y limpiar formulario
            if (jsonData.Tipo === 'success') {
                limpiarModalYCerrarRol();
                regenerarTokenCSRF('input[name="csrf_token"]', 'nuevoRol');
                // Opcional: recargar la página o actualizar la tabla
                setTimeout(() => {
                    location.reload();
                }, 2000);
            }
        } catch (e) {
            // Si no es JSON, buscar el sweet_alert en el HTML
            mostrarSweetAlert(data);
            
            // Si contiene palabras de éxito, cerrar modal
            if (data.includes('success') || data.includes('Rol creado') || data.includes('exitoso')) {
                limpiarModalYCerrarRol();
                regenerarTokenCSRF('input[name="csrf_token"]', 'nuevoRol');
                setTimeout(() => {
                    location.reload();
                }, 1500);
            }
        }
    })
    .catch(error => {
        // Cerrar loading y mostrar error
        Swal.close();
        
        console.error('Error:', error);
        Swal.fire({
            title: 'Error de conexión',
            text: 'No se pudo conectar con el servidor. Verifique su conexión a internet.',
            icon: 'error',
            confirmButtonText: 'Reintentar',
            confirmButtonColor: '#e74c3c'
        });
    });
}

// ==========================================================================================================
// FUNCIÓN PARA LIMPIAR Y CERRAR MODAL
// ==========================================================================================================

function limpiarModalYCerrarRol() {
    const modal = bootstrap.Modal.getInstance(document.getElementById('modalNuevoRol'));
    if (modal) modal.hide();

    document.getElementById('formNuevoRol').reset();
    const inputs = document.querySelectorAll('#formNuevoRol .form-control, #formNuevoRol .form-select');
    inputs.forEach(input => {
        input.classList.remove('is-valid', 'is-invalid');
    });

    const feedbacks = document.querySelectorAll('#formNuevoRol .invalid-feedback');
    feedbacks.forEach(feedback => feedback.textContent = '');

    // Limpiar contenedor de permisos si existe
    const permisosContainer = document.getElementById('permisosContainer');
    if (permisosContainer) {
        permisosContainer.innerHTML = `
            <div class="text-center py-4">
                <i class="bi bi-arrow-clockwise spin fa-2x text-muted mb-2"></i>
                <p class="text-muted">Cargando permisos disponibles...</p>
            </div>
        `;
    }
}

// ==========================================================================================================
// FUNCIÓN REUTILIZABLE PARA REGENERAR TOKENS CSRF
// ==========================================================================================================

function regenerarTokenCSRF(selector, key) {
    // Validación básica de parámetros
    if (!selector || !key) {
        console.error('Faltan parámetros requeridos: selector y key');
        return;
    }

    const accion = 'csrf_regenerar'; 
    const formData = new FormData();
    formData.append('accion', accion);
    formData.append('key', key);

    fetch('../ajax/App_rolesAjax.php', {
        method: 'POST',
        body: formData
    })
    .then(response => {
        if (!response.ok) {
            throw new Error(`HTTP ${response.status}: ${response.statusText}`);
        }
        return response.json();
    })
    .then(data => {
        if(data.status === 'success' && data.token) {
            const elemento = document.querySelector(selector) || document.getElementById(selector);
            if(elemento) {
                elemento.value = data.token;
                console.log(`Token ${key} regenerado exitosamente`);
            } else {
                console.error(`No se encontró el elemento con selector: ${selector}`);
            }
        } else {
            console.error('Respuesta inválida del servidor:', data);
        }
    })
    .catch(error => {
        console.error(`Error regenerando token ${key}:`, error);
    });
}

// Variable global para recordar qué vista está activa para roles
let vistaActualRoles = 'list';

//===========================================================================================================
// FUNCIÓN PRINCIPAL PARA LISTAR ROLES
//===========================================================================================================

function cargarRoles(pagina = 1) {
    const sharerol = document.getElementById('sharerol').value || '';
    const estadorol = document.getElementById('estadorol').value || '';
    const nivelrol = document.getElementById('nivelrol').value || '';
    const csrf_token_list_roles = document.querySelector('input[name="csrf_token_list_roles"]').value;

    // Mostrar loader en la tabla tbody
    const divtable = document.querySelector('.listadoroles');
    if (divtable) {
        divtable.innerHTML = `<div class="row">
            <div class="col-12 text-center py-5">
                <i class="bi bi-arrow-clockwise spin fa-3x text-muted mb-3"></i>
                <h5 class="text-muted">Cargando...</h5>
                <p class="text-muted">Aquí se cargan los roles disponibles</p>
            </div>`;
    }

    const formData = new FormData();
    formData.append('csrf_token_list_roles', csrf_token_list_roles);
    formData.append('sharerol', sharerol);
    formData.append('estadorol', estadorol);
    formData.append('nivelrol', nivelrol);
    formData.append('pagina', pagina);
    formData.append('vista_tipo', vistaActualRoles);

    fetch('../ajax/App_rolesAjax.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        console.log('Respuesta del servidor (roles):', data);

        if (data.status === 'success') {
            // Actualizar tabla
            if (data.html_tabla && divtable) {
                divtable.innerHTML = data.html_tabla;
            }

            // Actualizar estadísticas
            if (data.html_estadisticas) {
                const statsRow = document.querySelector('.stats-row');
                if (statsRow) {
                    statsRow.innerHTML = data.html_estadisticas;
                }
            }

            // Actualizar contador en header de tabla
            const tableTitleBadge = document.querySelector('.table-title .badge');
            if (tableTitleBadge && data.total_roles !== undefined) {
                tableTitleBadge.textContent = data.total_roles;
            }

        } else if (data.Alerta) {
            // Mostrar alerta del sistema
            mostrarSweetAlert(data);

            // Si hay error, mostrar mensaje en tabla
            if (divtable) {
                divtable.innerHTML = `
                    <tr>
                        <td colspan="6" class="text-center py-4 text-danger">
                            <i class="bi bi-exclamation-triangle fa-2x mb-2"></i><br>
                            ${data.Texto || 'Error al cargar roles'}
                        </td>
                    </tr>`;
            }
        }
    })
    .catch(error => {
        console.error('Error:', error);

        if (divtable) {
            divtable.innerHTML = `
                <tr>
                    <td colspan="6" class="text-center py-4 text-danger">
                        <i class="bi bi-wifi-off fa-2x mb-2"></i><br>
                        Error de conexión. Intenta nuevamente.
                    </td>
                </tr>`;
        }

        Swal.fire({
            title: 'Error de conexión',
            text: 'No se pudo conectar con el servidor',
            icon: 'error',
            confirmButtonText: 'Reintentar',
            confirmButtonColor: '#3085d6'
        }).then(() => {
           // cargarRoles(pagina);
        });
    });
}

//===========================================================================================================
// FUNCIÓN PARA FILTRAR ROLES 
//===========================================================================================================
let filtroTimeoutRoles;
function filtrarRoles() {
    clearTimeout(filtroTimeoutRoles);
    filtroTimeoutRoles = setTimeout(() => {
        cargarRoles(1); // Siempre volver a página 1 al filtrar
    }, 500); // Esperar 500ms después de que el usuario deje de escribir
}

//===========================================================================================================
// FUNCIÓN PARA CAMBIAR DE PÁGINA
//===========================================================================================================
function cargarPaginaRoles(pagina) {
    cargarRoles(pagina);
}

//===========================================================================================================
// FUNCIÓN PARA CAMBIAR VISTA
//===========================================================================================================
function toggleViewRoles(viewType) {
    // Cambiar botones
    const buttons = document.querySelectorAll('.view-btn');
    buttons.forEach(btn => btn.classList.remove('active'));

    if (viewType === 'list') {
        buttons[0].classList.add('active');
        vistaActualRoles = 'list';
    } else {
        buttons[1].classList.add('active');
        vistaActualRoles = 'grid';
    }

    // Recargar con la nueva vista
    cargarRoles(1);
}

//===========================================================================================================
// FUNCIÓN PARA LIMPIAR FILTROS
//===========================================================================================================
function limpiarFiltrosRoles() {
    document.getElementById('sharerol').value = '';
    document.getElementById('estadorol').value = '';
    document.getElementById('nivelrol').value = '';
    cargarRoles(1);
}

//===========================================================================================================
// EVENT LISTENERS PARA ROLES
//===========================================================================================================

// Agregar estos event listeners al DOMContentLoaded existente o crear uno nuevo
document.addEventListener('DOMContentLoaded', function() {

    // Event listeners para filtros de roles
    const shareRolInput = document.getElementById('sharerol');
    const estadoRolSelect = document.getElementById('estadorol');
    const nivelRolSelect = document.getElementById('nivelrol');

    if (shareRolInput) {
        shareRolInput.addEventListener('input', filtrarRoles);
    }

    if (estadoRolSelect) {
        estadoRolSelect.addEventListener('change', filtrarRoles);
    }

    if (nivelRolSelect) {
        nivelRolSelect.addEventListener('change', filtrarRoles);
    }

    // Cargar roles al iniciar (si estamos en la página de roles)
    if (document.querySelector('.listadoroles')) {
        cargarRoles();
    }
});

//===========================================================================================================
//FUNCIONES PARA ELIMINAR ROLES
//===========================================================================================================

function eliminarRol(codigoRol, nombreRol, paginaactual) {
    Swal.fire({
        title: '¿Estás seguro?',
        text: `¿Deseas eliminar el rol "${nombreRol}"?`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Sí, eliminar',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            eliminarRolAjax(codigoRol, nombreRol, paginaactual);
        }
    });
}

// FUNCION AJAX PARA ELIMINAR ROL
function eliminarRolAjax(codigoRol, nombreRol, paginaactual) {
    const formData = new FormData();
    formData.append('accion', 'eliminar_rol');
    formData.append('codigo_rol', codigoRol);
    formData.append('csrf_token_eliminar', document.querySelector('input[name="csrf_token_list_roles"]').value);

    Swal.fire({
        title: 'Eliminando...',
        text: 'Por favor espere',
        allowOutsideClick: false,
        showConfirmButton: false,
        didOpen: () => {
            Swal.showLoading();
        }
    });

    fetch('../ajax/App_rolesAjax.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        Swal.close();
        mostrarSweetAlert(data);

        if (data.Tipo === 'success') {
            // Recargar listado después de eliminar
            // Actualizar token si viene en la respuesta
            if (data.nuevo_token) {
                document.querySelector('input[name="csrf_token_list_roles"]').value = data.nuevo_token;
            }
            cargarRoles(paginaactual);
        }
    })
    .catch(error => {
        Swal.close();
        console.error('Error:', error);
        Swal.fire({
            title: 'Error',
            text: 'No se pudo eliminar el rol',
            icon: 'error'
        });
    });
}