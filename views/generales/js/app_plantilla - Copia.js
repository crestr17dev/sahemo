// ==========================================================================================================
// CAMBIO DE CONTRASEÑA OBLIGATORIO
// ==========================================================================================================

document.addEventListener('DOMContentLoaded', function() {
    // Verificar inmediatamente si el usuario tiene contraseña temporal
    verificarPasswordTemporal();
    
    // Configurar el modal de cambio obligatorio
    configurarModalCambioObligatorio();
});

// ==========================================================================================================
// FUNCIÓN PARA VERIFICAR SI EL USUARIO TIENE CONTRASEÑA TEMPORAL
// ==========================================================================================================

function verificarPasswordTemporal() {
    const formData = new FormData();
    formData.append('accion', 'verificar_password_temporal');
    
    fetch('../ajax/App_usuariosAjax.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.tiene_password_temporal) {
            mostrarModalCambioObligatorio(data);
        }
    })
    .catch(error => {
        console.error('Error verificando contraseña temporal:', error);
    });
}

// ==========================================================================================================
// FUNCIÓN PARA MOSTRAR EL MODAL DE CAMBIO OBLIGATORIO
// ==========================================================================================================

function mostrarModalCambioObligatorio(datosUsuario) {
    // Llenar información del usuario
    const nombreElement = document.getElementById('nombreUsuarioObligatorio');
    const codigoElement = document.getElementById('codigoUsuarioObligatorio');
    const diasElement = document.getElementById('diasRestantesObligatorio');
    
    if (nombreElement) {
        // Obtener nombre del usuario de la sesión (debes pasarlo desde PHP)
        nombreElement.textContent = window.sessionUserName || 'Usuario';
    }
    
    if (codigoElement) {
        // Obtener código del usuario de la sesión
        codigoElement.textContent = window.sessionUserCode || 'USxxxxxx';
    }
    
    if (diasElement) {
        if (datosUsuario.password_expirada) {
            diasElement.textContent = '¡EXPIRADA!';
            diasElement.className = 'fw-bold text-danger';
        } else {
            diasElement.textContent = datosUsuario.dias_restantes + ' días';
            
            if (datosUsuario.dias_restantes <= 1) {
                diasElement.className = 'fw-bold text-danger';
            } else if (datosUsuario.dias_restantes <= 3) {
                diasElement.className = 'fw-bold text-warning';
            } else {
                diasElement.className = 'fw-bold text-primary';
            }
        }
    }
    
    // Mostrar modal sin permitir cerrarlo
    const modal = new bootstrap.Modal(document.getElementById('modalCambioObligatorio'), {
        backdrop: 'static',
        keyboard: false
    });
    modal.show();
    
    // Bloquear navegación
    bloquearNavegacion();
}

// ==========================================================================================================
// FUNCIÓN PARA CONFIGURAR EL MODAL DE CAMBIO OBLIGATORIO
// ==========================================================================================================

function configurarModalCambioObligatorio() {
    const passwordNueva = document.getElementById('passwordNuevaObligatoria');
    const passwordConfirmar = document.getElementById('passwordConfirmarObligatoria');
    const btnCambiar = document.getElementById('btnCambiarObligatorio');

    if (passwordNueva) {
        passwordNueva.addEventListener('input', function() {
            validarPasswordObligatoria();
            validarCoincidenciaObligatoria();
        });
    }

    if (passwordConfirmar) {
        passwordConfirmar.addEventListener('input', validarCoincidenciaObligatoria);
    }

    function validarPasswordObligatoria() {
        const password = passwordNueva.value;
        
        // Requisitos
        const requisitos = {
            'req-length-obligatorio': password.length >= 8,
            'req-upper-obligatorio': /[A-Z]/.test(password),
            'req-lower-obligatorio': /[a-z]/.test(password),
            'req-number-obligatorio': /[0-9]/.test(password),
            'req-special-obligatorio': /[@#$%&*]/.test(password)
        };

        // Actualizar indicadores visuales
        for (const [id, cumple] of Object.entries(requisitos)) {
            const elemento = document.getElementById(id);
            if (elemento) {
                if (cumple) {
                    elemento.classList.add('text-success');
                    elemento.classList.remove('text-danger');
                    const icono = elemento.querySelector('i');
                    if (icono) icono.className = 'bi bi-check-circle text-success me-1';
                } else {
                    elemento.classList.add('text-danger');
                    elemento.classList.remove('text-success');
                    const icono = elemento.querySelector('i');
                    if (icono) icono.className = 'bi bi-x-circle text-danger me-1';
                }
            }
        }

        // Calcular fuerza de contraseña
        const cumplidos = Object.values(requisitos).filter(Boolean).length;
        actualizarBarraFuerzaObligatoria(cumplidos);

        return Object.values(requisitos).every(Boolean);
    }

    function validarCoincidenciaObligatoria() {
        const nueva = passwordNueva.value;
        const confirmar = passwordConfirmar.value;
        const elemento = document.getElementById('req-match-obligatorio');

        if (elemento) {
            if (confirmar === '' || nueva === confirmar) {
                elemento.classList.add('text-success');
                elemento.classList.remove('text-danger');
                const icono = elemento.querySelector('i');
                if (icono) icono.className = 'bi bi-check-circle text-success me-1';
                return confirmar !== '';
            } else {
                elemento.classList.add('text-danger');
                elemento.classList.remove('text-success');
                const icono = elemento.querySelector('i');
                if (icono) icono.className = 'bi bi-x-circle text-danger me-1';
                return false;
            }
        }
        return false;
    }

    function actualizarBarraFuerzaObligatoria(cumplidos) {
        const strengthBar = document.getElementById('strengthBarObligatorio');
        const strengthText = document.getElementById('strengthTextObligatorio');

        if (strengthBar && strengthText) {
            if (cumplidos <= 2) {
                strengthBar.className = 'progress-bar bg-danger';
                strengthBar.style.width = '25%';
                strengthText.textContent = 'Débil';
                strengthText.className = 'fw-bold text-danger';
            } else if (cumplidos <= 3) {
                strengthBar.className = 'progress-bar bg-warning';
                strengthBar.style.width = '50%';
                strengthText.textContent = 'Regular';
                strengthText.className = 'fw-bold text-warning';
            } else if (cumplidos <= 4) {
                strengthBar.className = 'progress-bar bg-info';
                strengthBar.style.width = '75%';
                strengthText.textContent = 'Buena';
                strengthText.className = 'fw-bold text-info';
            } else {
                strengthBar.className = 'progress-bar bg-success';
                strengthBar.style.width = '100%';
                strengthText.textContent = 'Excelente';
                strengthText.className = 'fw-bold text-success';
            }
        }

        // Habilitar/deshabilitar botón
        const passwordValida = validarPasswordObligatoria();
        const coinciden = validarCoincidenciaObligatoria();
        if (btnCambiar) {
            btnCambiar.disabled = !(passwordValida && coinciden);
        }
    }
}

// ==========================================================================================================
// FUNCIÓN PARA ALTERNAR VISIBILIDAD DE CONTRASEÑAS
// ==========================================================================================================

function togglePasswordObligatorio(inputId, iconId) {
    const passwordInput = document.getElementById(inputId);
    const eyeIcon = document.getElementById(iconId);
    
    if (passwordInput && eyeIcon) {
        if (passwordInput.type === 'password') {
            passwordInput.type = 'text';
            eyeIcon.className = 'bi bi-eye-slash';
        } else {
            passwordInput.type = 'password';
            eyeIcon.className = 'bi bi-eye';
        }
    }
}

// ==========================================================================================================
// FUNCIÓN PRINCIPAL PARA CAMBIAR CONTRASEÑA
// ==========================================================================================================

function cambiarPasswordObligatorio() {
    const form = document.getElementById('formCambioObligatorio');
    
    if (!form) {
        console.error('Formulario no encontrado');
        return;
    }
    
    const passwordActual = document.getElementById('passwordActualObligatorio').value;
    const passwordNueva = document.getElementById('passwordNuevaObligatoria').value;
    const passwordConfirmar = document.getElementById('passwordConfirmarObligatoria').value;

    // Validaciones básicas
    if (!passwordActual) {
        Swal.fire({
            title: 'Campo requerido',
            text: 'Debes ingresar tu contraseña temporal actual',
            icon: 'warning'
        });
        return;
    }

    if (!passwordNueva || !passwordConfirmar) {
        Swal.fire({
            title: 'Campos incompletos',
            text: 'Debes completar todos los campos de contraseña',
            icon: 'warning'
        });
        return;
    }

    if (passwordNueva !== passwordConfirmar) {
        Swal.fire({
            title: 'Contraseñas no coinciden',
            text: 'La nueva contraseña y su confirmación deben ser iguales',
            icon: 'warning'
        });
        return;
    }

    // Crear FormData del formulario
    const formData = new FormData(form);
    
    // Mostrar loading
    Swal.fire({
        title: 'Cambiando contraseña...',
        text: 'Por favor espera mientras procesamos tu solicitud',
        allowOutsideClick: false,
        allowEscapeKey: false,
        showConfirmButton: false,
        didOpen: () => {
            Swal.showLoading();
        }
    });

    // Llamada AJAX
    fetch('../ajax/App_usuariosAjax.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        Swal.close();
        
        if (data.success) {
            Swal.fire({
                title: '¡Contraseña cambiada exitosamente!',
                text: data.mensaje,
                icon: 'success',
                confirmButtonText: 'Continuar',
                allowOutsideClick: false,
                allowEscapeKey: false
            }).then(() => {
                // Cerrar modal y desbloquear navegación
                const modal = bootstrap.Modal.getInstance(document.getElementById('modalCambioObligatorio'));
                if (modal) modal.hide();
                
                desbloquearNavegacion();
                
                // Regenerar token para próximas operaciones
                regenerarTokenCSRF('input[name="csrf_token_cambio"]', 'cambioPasswordObligatorio');
                
                // Opcional: mostrar mensaje de bienvenida
                setTimeout(() => {
                    Swal.fire({
                        title: '¡Bienvenido al sistema!',
                        text: 'Tu contraseña ha sido actualizada. Ahora puedes usar todas las funciones del sistema.',
                        icon: 'info',
                        timer: 3000,
                        showConfirmButton: false
                    });
                }, 1000);
            });
        } else {
            Swal.fire({
                title: 'Error',
                text: data.mensaje || 'Ocurrió un error al cambiar la contraseña',
                icon: 'error',
                confirmButtonText: 'Reintentar'
            });
        }
    })
    .catch(error => {
        Swal.close();
        console.error('Error:', error);
        Swal.fire({
            title: 'Error de conexión',
            text: 'No se pudo conectar con el servidor. Verifique su conexión a internet.',
            icon: 'error',
            confirmButtonText: 'Reintentar'
        });
    });
}

// ==========================================================================================================
// FUNCIONES AUXILIARES
// ==========================================================================================================

function bloquearNavegacion() {
    // Bloquear enlaces de navegación
    const enlaces = document.querySelectorAll('a:not([data-bs-toggle="modal"])');
    enlaces.forEach(enlace => {
        enlace.style.pointerEvents = 'none';
        enlace.style.opacity = '0.5';
    });
    
    // Bloquear botones
    const botones = document.querySelectorAll('button:not([onclick*="cambiarPasswordObligatorio"]):not([onclick*="togglePasswordObligatorio"])');
    botones.forEach(boton => {
        boton.disabled = true;
        boton.style.opacity = '0.5';
    });
}

function desbloquearNavegacion() {
    // Desbloquear enlaces de navegación
    const enlaces = document.querySelectorAll('a');
    enlaces.forEach(enlace => {
        enlace.style.pointerEvents = '';
        enlace.style.opacity = '';
    });
    
    // Desbloquear botones
    const botones = document.querySelectorAll('button');
    botones.forEach(boton => {
        boton.disabled = false;
        boton.style.opacity = '';
    });
}

function regenerarTokenCSRF(selector, key) {
    const formData = new FormData();
    formData.append('accion', 'csrf_regenerar');
    formData.append('key', key);

    fetch('../ajax/App_usuariosAjax.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if(data.status === 'success' && data.token) {
            const elemento = document.querySelector(selector);
            if(elemento) {
                elemento.value = data.token;
                console.log(`Token ${key} regenerado exitosamente`);
            }
        }
    })
    .catch(error => {
        console.error(`Error regenerando token ${key}:`, error);
    });
}