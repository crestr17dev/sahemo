// ==========================================================================================================
// APP_ROLES.JS - GESTIÓN DE ROLES - COPIADO EXACTO DEL PATRÓN DE USUARIOS
// ==========================================================================================================
// ==========================================================================================================
// EVENTOS AL CARGAR LA PÁGINA
// ==========================================================================================================
document.addEventListener('DOMContentLoaded', function() {
    // Configurar validaciones del modal
    configurarValidacionesModalRol();
});


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
                // Cerrar modal
                const modal = bootstrap.Modal.getInstance(document.getElementById('modalNuevoRol'));
                if (modal) {
                    modal.hide();
                }
                
                // Limpiar formulario
                limpiarFormularioRol();
                
                // Recargar lista de roles si existe función
                if (typeof cargarRoles === 'function') {
                    cargarRoles();
                }
            }
            
        } catch (e) {
            // Si no es JSON, buscar JSON dentro de la respuesta HTML
            try {
                const jsonMatch = data.match(/\{.*\}/);
                if (jsonMatch) {
                    const jsonData = JSON.parse(jsonMatch[0]);
                    mostrarSweetAlert(jsonData);
                    
                    // Si fue exitoso, cerrar modal y limpiar formulario
                    if (jsonData.Tipo === 'success') {
                        // Cerrar modal
                        const modal = bootstrap.Modal.getInstance(document.getElementById('modalNuevoRol'));
                        if (modal) {
                            modal.hide();
                        }
                        
                        // Limpiar formulario
                        limpiarFormularioRol();
                        
                        // Recargar lista de roles si existe función
                        if (typeof cargarRoles === 'function') {
                            cargarRoles();
                        }
                    }
                } else {
                    throw new Error('No se encontró JSON en la respuesta');
                }
            } catch (parseError) {
                console.error('Error al procesar respuesta:', parseError);
                console.log('Respuesta del servidor:', data);
                
                Swal.fire({
                    title: 'Error inesperado',
                    text: 'La respuesta del servidor no tiene el formato esperado',
                    icon: 'error',
                    confirmButtonText: 'Entendido',
                    confirmButtonColor: '#e74c3c'
                });
            }
        }
    })
    .catch(error => {
        Swal.close();
        console.error('Error en la petición:', error);
        
        Swal.fire({
            title: 'Error de conexión',
            text: 'No se pudo conectar con el servidor. Verifique su conexión a internet.',
            icon: 'error',
            confirmButtonText: 'Entendido',
            confirmButtonColor: '#e74c3c'
        });
    });
}

// ==========================================================================================================
// FUNCIONES DE VALIDACIÓN DEL FORMULARIO
// ==========================================================================================================

// FUNCIÓN validateFormRol sin validación de contraseña
function validateFormRol() {
    const form = document.getElementById('formNuevoRol');
    const inputs = form.querySelectorAll('input[required], select[required]');
    let isValid = true;

    inputs.forEach(input => {
        if (!validateFieldRol(input)) isValid = false;
    });

    return isValid;
}

function validateFieldRol(input) {
    const value = input.value.trim();
    
    if (value === '') {
        showErrorRol(input, 'Este campo es obligatorio');
        return false;
    }
    
    // Validación específica para nombre del rol
    if (input.id === 'rolNombre') {
        if (value.length < 5) {
            showErrorRol(input, 'El nombre debe tener al menos 5 caracteres');
            return false;
        }
    }
    
    // Validación específica para descripción del rol
    if (input.id === 'rolDescripcion') {
        if (value.length < 10) {
            showErrorRol(input, 'La descripción debe tener al menos 10 caracteres');
            return false;
        }
    }
    
    showSuccessRol(input);
    return true;
}

function showErrorRol(input, message) {
    input.classList.remove('is-valid');
    input.classList.add('is-invalid');
    
    const feedback = input.parentNode.querySelector('.invalid-feedback') || 
                    input.nextElementSibling;
    if (feedback && feedback.classList.contains('invalid-feedback')) {
        feedback.textContent = message;
    }
}

function showSuccessRol(input) {
    input.classList.remove('is-invalid');
    input.classList.add('is-valid');
    
    const feedback = input.parentNode.querySelector('.invalid-feedback') || 
                    input.nextElementSibling;
    if (feedback && feedback.classList.contains('invalid-feedback')) {
        feedback.textContent = '';
    }
}

// ==========================================================================================================
// FUNCIÓN PARA LIMPIAR FORMULARIO
// ==========================================================================================================

function limpiarFormularioRol() {
    const form = document.getElementById('formNuevoRol');
    if (form) {
        form.reset();
        
        // Limpiar clases de validación
        const inputs = form.querySelectorAll('input, select');
        inputs.forEach(input => {
            input.classList.remove('is-valid', 'is-invalid');
        });
        
        // Limpiar mensajes de error
        const feedbacks = form.querySelectorAll('.invalid-feedback');
        feedbacks.forEach(feedback => {
            feedback.textContent = '';
        });
    }
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
            const jsonMatch = data.match(/\{.*\}/);
            if (jsonMatch) {
                alertData = JSON.parse(jsonMatch[0]);
            } else {
                alertData = JSON.parse(data);
            }
        } catch (e) {
            console.error('Error parseando JSON:', e);
            console.log('Data recibida:', data);
            return;
        }
    } else {
        alertData = data;
    }

    // Configurar icono basado en el tipo
    let icon = 'info';
    let confirmButtonColor = '#3085d6';
    
    switch(alertData.Tipo) {
        case 'success':
            icon = 'success';
            confirmButtonColor = '#28a745';
            break;
        case 'error':
            icon = 'error';
            confirmButtonColor = '#e74c3c';
            break;
        case 'warning':
            icon = 'warning';
            confirmButtonColor = '#f39c12';
            break;
    }

    // Configurar el contenido del alert
    let config = {
        title: alertData.Titulo || 'Información',
        text: alertData.Texto || '',
        icon: icon,
        confirmButtonText: 'Entendido',
        confirmButtonColor: confirmButtonColor,
        allowOutsideClick: false
    };

    // Mostrar el alert
    Swal.fire(config);
}



function configurarValidacionesModalRol() {
    const form = document.getElementById('formNuevoRol');
    if (form) {
        const inputs = form.querySelectorAll('input[required], select[required]');

        inputs.forEach(input => {
            input.addEventListener('blur', function() {
                validateFieldRol(this);
            });
        });
    }
}