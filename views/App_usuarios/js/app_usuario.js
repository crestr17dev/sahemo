// ==========================================================================================================
// APP_USUARIO.JS - GESTIÓN COMPLETA DE USUARIOS CON CONTRASEÑAS TEMPORALES
// Versión actualizada sin dependencias de contraseñas en formulario de registro
// ==========================================================================================================

// ==========================================================================================================
// INICIALIZACIÓN Y EVENTOS PRINCIPALES
// ==========================================================================================================
document.addEventListener('DOMContentLoaded', function() {
    // Animaciones suaves al cargar
    inicializarAnimaciones();
    
    // Configurar validaciones del modal
    configurarValidacionesModal();
    
    // Event listeners para filtros
    configurarFiltros();
    
    // Cargar datos iniciales
    cargarEmpresas();
	
    cargarUsuarios();
    
    // Configurar dropdowns en cascada
    configurarDropdownsCascada();
    
    // Configurar validación en tiempo real del motivo de cambio de estado
    configurarValidacionMotivo();
});

// ==========================================================================================================
// FUNCIONES DE INICIALIZACIÓN
// ==========================================================================================================

function inicializarAnimaciones() {
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

function configurarValidacionesModal() {
    const form = document.getElementById('formNuevoUsuario');
    if (form) {
        const inputs = form.querySelectorAll('input[required], select[required]');

        inputs.forEach(input => {
            input.addEventListener('blur', function() {
                if (this.type === 'checkbox') {
                    validateCheckbox(this);
                } else {
                    validateField(this);
                }
            });

            input.addEventListener('input', function() {
                if (this.classList.contains('is-invalid')) {
                    if (this.type === 'checkbox') {
                        validateCheckbox(this);
                    } else {
                        validateField(this);
                    }
                }
            });

            // Listener especial para el checkbox de confirmación de seguridad
            if (input.type === 'checkbox') {
                input.addEventListener('change', function() {
                    validateCheckbox(this);
                });
            }
        });

        // Validaciones específicas para campos especiales
        const emailInput = document.getElementById('usuarioEmail');
        if (emailInput) {
            emailInput.addEventListener('input', function() {
                validateEmail(this);
            });
        }

        const telefonoInput = document.getElementById('usuarioTelefono');
        if (telefonoInput) {
            telefonoInput.addEventListener('input', function() {
                validatePhone(this);
            });
        }

        const documentoInput = document.getElementById('usuarioDocumento');
        if (documentoInput) {
            documentoInput.addEventListener('input', function() {
                validateDocumento(this);
            });
        }
    }
	const formEdi = document.getElementById('formEditarUsuarioInfo');
    if (formEdi) {
        const inputs = formEdi.querySelectorAll('input[required], select[required]');

        inputs.forEach(input => {
            input.addEventListener('blur', function() {
                if (this.type === 'checkbox') {
                    validateCheckbox(this);
                } else {
                    validateField(this);
                }
            });

            input.addEventListener('input', function() {
                if (this.classList.contains('is-invalid')) {
                    if (this.type === 'checkbox') {
                        validateCheckbox(this);
                    } else {
                        validateField(this);
                    }
                }
            });

            // Listener especial para el checkbox de confirmación de seguridad
            if (input.type === 'checkbox') {
                input.addEventListener('change', function() {
                    validateCheckbox(this);
                });
            }
        });

        // Validaciones específicas para campos especiales
        const emailInput = document.getElementById('editarUsuarioEmail');
        if (emailInput) {
            emailInput.addEventListener('input', function() {
                validateEmail(this);
            });
        }

        const telefonoInput = document.getElementById('editarUsuarioTelefono');
        if (telefonoInput) {
            telefonoInput.addEventListener('input', function() {
                validatePhone(this);
            });
        }

        const documentoInput = document.getElementById('editarUsuarioDocumento');
        if (documentoInput) {
            documentoInput.addEventListener('input', function() {
                validateDocumento(this);
            });
        }
    }
	
}

function configurarFiltros() {
    const shareUsuario = document.getElementById('shareusuario');
    const estadoUsuario = document.getElementById('estadousuario');
    
    if (shareUsuario) {
        shareUsuario.addEventListener('input', filtrarUsuarios);
    }
    
    if (estadoUsuario) {
        estadoUsuario.addEventListener('change', filtrarUsuarios);
    }
}

function configurarDropdownsCascada() {
    const empresaSelect = document.getElementById('usuarioEmpresaId');
    const sucursalSelect = document.getElementById('usuarioSucursalId');
    const empresaEditarSelect = document.getElementById('editarUsuarioEmpresaId');
    const sucursalEditarSelect = document.getElementById('editarUsuarioSucursalId');
	
    if (empresaSelect) {
        empresaSelect.addEventListener('change', function() {
            cargarSucursalesPorEmpresa(this.value, 'usuarioSucursalId');
            // Limpiar sede cuando cambie empresa
            const sedeSelect = document.getElementById('usuarioSedeId');
            if (sedeSelect) {
                sedeSelect.innerHTML = '<option value="">Seleccionar Sede</option>';
            }
        });
    }
	if (empresaEditarSelect) {
        empresaEditarSelect.addEventListener('change', function() {
            cargarSucursalesPorEmpresa(this.value, 'editarUsuarioSucursalId');
            // Limpiar sede cuando cambie empresa
            const sedeSelect = document.getElementById('editarUsuarioSedeId');
            if (sedeSelect) {
                sedeSelect.innerHTML = '<option value="">Seleccionar Sede</option>';
            }
        });
    }
	
    if (sucursalSelect) {
        sucursalSelect.addEventListener('change', function() {
            cargarSedesPorSucursal(this.value, 'usuarioSedeId');
        });
    }
	if (sucursalEditarSelect) {
        sucursalEditarSelect.addEventListener('change', function() {
            cargarSedesPorSucursal(this.value, 'editarUsuarioSedeId');
        });
    }
}

function configurarValidacionMotivo() {
    const motivoInput = document.getElementById('motivoCambioEstado');
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
// FUNCIONES DE VALIDACIÓN  ==================================================

function validateField(field) {
    const value = field.value.trim();

    if (field.hasAttribute('required') && value === '') {
        setFieldError(field, 'Este campo es obligatorio');
        return false;
    } else {
        setFieldSuccess(field);
        return true;
    }
}

function validateEmail(field) {
    const value = field.value.trim();
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

    if (value === '') {
        setFieldError(field, 'Este campo es obligatorio');
        return false;
    } else if (!emailRegex.test(value)) {
        setFieldError(field, 'Ingrese un email válido');
        return false;
    } else {
        setFieldSuccess(field);
        return true;
    }
}

function validatePhone(field) {
    const value = field.value.trim();
    const phoneRegex = /^[0-9]{7,15}$/;

    if (value === '') {
        setFieldError(field, 'Este campo es obligatorio');
        return false;
    } else if (!phoneRegex.test(value)) {
        setFieldError(field, 'Ingrese un teléfono válido (7-15 dígitos)');
        return false;
    } else {
        setFieldSuccess(field);
        return true;
    }
}

function validateDocumento(field) {
    const value = field.value.trim();

    if (value === '') {
        setFieldError(field, 'Este campo es obligatorio');
        return false;
    } else if (value.length < 6 || value.length > 20) {
        setFieldError(field, 'El documento debe tener entre 6 y 20 dígitos');
        return false;
    } else {
        setFieldSuccess(field);
        return true;
    }
}

// NUEVA FUNCIÓN: Validar checkbox de confirmación de seguridad
function validateCheckbox(field) {
    if (!field.checked) {
        setFieldError(field, 'Debes confirmar esta opción');
        return false;
    } else {
        setFieldSuccess(field);
        return true;
    }
}

// FUNCIÓN setFieldError para manejar checkboxes
function setFieldError(field, message) {
    if (field.type === 'checkbox') {
        field.classList.add('is-invalid');
        field.classList.remove('is-valid');
        const feedback = field.parentElement.nextElementSibling;
        if (feedback && feedback.classList.contains('invalid-feedback')) {
            feedback.textContent = message;
        }
    } else {
        field.classList.remove('is-valid');
        field.classList.add('is-invalid');
        const feedback = field.parentElement.nextElementSibling;
        if (feedback && feedback.classList.contains('invalid-feedback')) {
            feedback.textContent = message;
        }
    }
}

// FUNCIÓN  setFieldSuccess para manejar checkboxes
function setFieldSuccess(field) {
    if (field.type === 'checkbox') {
        field.classList.remove('is-invalid');
        field.classList.add('is-valid');
        const feedback = field.parentElement.nextElementSibling;
        if (feedback && feedback.classList.contains('invalid-feedback')) {
            feedback.textContent = '';
        }
    } else {
        field.classList.remove('is-invalid');
        field.classList.add('is-valid');
        const feedback = field.parentElement.nextElementSibling;
        if (feedback && feedback.classList.contains('invalid-feedback')) {
            feedback.textContent = '';
        }
    }
}

// FUNCIÓN validateForm sin validación de contraseña
function validateForm() {
    const form = document.getElementById('formNuevoUsuario');
    const inputs = form.querySelectorAll('input[required], select[required]');
    let isValid = true;

    inputs.forEach(input => {
        if (input.type === 'email') {
            if (!validateEmail(input)) isValid = false;
        } else if (input.id === 'usuarioTelefono') {
            if (!validatePhone(input)) isValid = false;
        } else if (input.id === 'usuarioDocumento') {
            if (!validateDocumento(input)) isValid = false;
        } else if (input.type === 'checkbox') {
            if (!validateCheckbox(input)) isValid = false;
        } else {
            if (!validateField(input)) isValid = false;
        }
    });

    return isValid;
}

// ==========================================================================================================
// FUNCIONES PARA MOSTRAR SWEETALERT2 CON CREDENCIALES TEMPORALES
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

    // Detectar si el mensaje contiene credenciales temporales
    const contieneCredenciales = alertData.Texto && 
        (alertData.Texto.includes('Contraseña:') || 
         alertData.Texto.includes('password_temporal') ||
         alertData.Texto.includes('Credenciales temporales'));

    if (contieneCredenciales && alertData.Tipo === 'success') {
        // Mostrar modal especial para credenciales
        mostrarCredencialesTemporales(alertData);
    } else {
        // Mostrar SweetAlert normal
        Swal.fire({
            title: alertData.Titulo || 'Información',
            text: alertData.Texto || 'Operación completada',
            icon: tipoIcono[alertData.Tipo] || 'info',
            confirmButtonText: 'Aceptar',
            confirmButtonColor: '#3085d6'
        });
    }
}

function mostrarCredencialesTemporales(alertData) {
    // Extraer credenciales del texto
    let usuario = '';
    let password = '';
    
    const textoCompleto = alertData.Texto;
    
    // Buscar patrones de credenciales
    const usuarioMatch = textoCompleto.match(/Usuario:\s*([^\n\r|]+)/);
    const passwordMatch = textoCompleto.match(/Contraseña:\s*([^\n\r|]+)/);
    
    if (usuarioMatch) usuario = usuarioMatch[1].trim();
    if (passwordMatch) password = passwordMatch[1].trim();

    // Crear HTML personalizado para las credenciales
    const htmlCredenciales = `
        <div class="credenciales-container">
            <div class="alert alert-warning mb-4">
                <i class="bi bi-exclamation-triangle me-2"></i>
                <strong>¡IMPORTANTE!</strong> Estas credenciales son temporales y deben cambiarse en el primer acceso.
            </div>
            
            <div class="credenciales-display">
                <h6><i class="bi bi-key me-2"></i>Credenciales de Acceso</h6>
                
                <div class="credencial-item">
                    <div class="credencial-label">Usuario:</div>
                    <div class="credencial-valor" id="credencial-usuario">${usuario}</div>
                </div>
                
                <div class="credencial-item">
                    <div class="credencial-label">Contraseña Temporal:</div>
                    <div class="credencial-valor" id="credencial-password">${password}</div>
                </div>
            </div>
            
            <div class="mt-3">
                <button type="button" class="btn btn-outline-primary btn-sm" onclick="copiarCredenciales(this)">
                    <i class="bi bi-clipboard me-1"></i>
                    Copiar Credenciales
                </button>
            </div>
            
            <div class="alert alert-info mt-3 mb-0">
                <small>
                    <i class="bi bi-info-circle me-1"></i>
                    <strong>Recordatorio:</strong> El usuario debe cambiar esta contraseña en su primer inicio de sesión. La contraseña temporal expira en 7 días.
                </small>
            </div>
        </div>
    `;

    Swal.fire({
        title: '<i class="bi bi-person-check me-2"></i>' + (alertData.Titulo || 'Usuario Registrado'),
        html: htmlCredenciales,
        icon: 'success',
        confirmButtonText: 'Entendido',
        confirmButtonColor: '#28a745',
        width: '600px',
        showClass: {
            popup: 'animate__animated animate__fadeInDown'
        },
        hideClass: {
            popup: 'animate__animated animate__fadeOutUp'
        }
    });
}

function copiarCredenciales(botonElemento) {
    const usuario = document.getElementById('credencial-usuario').textContent;
    const password = document.getElementById('credencial-password').textContent;
    
    const credencialesTexto = `Usuario: ${usuario}\nContraseña: ${password}`;
    
    // Usar siempre el fallback en modales de SweetAlert
    const textArea = document.createElement('textarea');
    textArea.value = credencialesTexto;
    textArea.style.position = 'fixed';
    textArea.style.left = '-999999px';
    textArea.style.top = '-999999px';
    textArea.style.opacity = '0';
    textArea.style.pointerEvents = 'none';
    
    document.body.appendChild(textArea);
    textArea.focus();
    textArea.select();
    textArea.setSelectionRange(0, 99999); // Para móviles
    
    try {
        const successful = document.execCommand('copy');
        document.body.removeChild(textArea);
        
        if (successful) {
            mostrarConfirmacionCopia(botonElemento);
        } else {
            mostrarErrorCopia(botonElemento);
        }
    } catch (err) {
        document.body.removeChild(textArea);
        console.error('Error al copiar:', err);
        mostrarErrorCopia(botonElemento);
    }
}

function mostrarConfirmacionCopia(boton) {
    const textoOriginal = boton.innerHTML;
    
    boton.innerHTML = '<i class="bi bi-check me-1"></i>¡Copiado!';
    boton.classList.remove('btn-outline-primary');
    boton.classList.add('btn-success');
    
    setTimeout(() => {
        boton.innerHTML = textoOriginal;
        boton.classList.remove('btn-success');
        boton.classList.add('btn-outline-primary');
    }, 2000);
}

function mostrarErrorCopia(boton) {
    const textoOriginal = boton.innerHTML;
    
    boton.innerHTML = '<i class="bi bi-x me-1"></i>Error';
    boton.classList.remove('btn-outline-primary');
    boton.classList.add('btn-danger');
    
    setTimeout(() => {
        boton.innerHTML = textoOriginal;
        boton.classList.remove('btn-danger');
        boton.classList.add('btn-outline-primary');
    }, 2000);
}

// ==========================================================================================================
// FUNCIÓN PRINCIPAL DE GUARDAR USUARIO
// ==========================================================================================================

function guardarUsuario() {
    if (!validateForm()) {
        Swal.fire({
            title: 'Formulario incompleto',
            text: 'Por favor complete todos los campos correctamente',
            icon: 'warning',
            confirmButtonText: 'Entendido',
            confirmButtonColor: '#f39c12'
        });
        return;
    }

    const formData = new FormData(document.getElementById('formNuevoUsuario'));

    // Mostrar loading con SweetAlert2
    Swal.fire({
        title: 'Guardando usuario...',
        text: 'Por favor espere mientras procesamos la información',
        allowOutsideClick: false,
        allowEscapeKey: false,
        showConfirmButton: false,
        didOpen: () => {
            Swal.showLoading();
        }
    });

    // Enviar datos por AJAX
    fetch('../ajax/App_usuariosAjax.php', {
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
                limpiarModalYCerrar();
                regenerarTokenCSRF('input[name="csrf_token"]', 'formNuevoUsuario');
                // Opcional: recargar la página o actualizar la tabla
                setTimeout(() => {
                    location.reload();
                }, 5000);
            }
        } catch (e) {
            // Si no es JSON, buscar el sweet_alert en el HTML
            mostrarSweetAlert(data);
            
            // Si contiene palabras de éxito, cerrar modal
            if (data.includes('success') || data.includes('Usuario registrado') || data.includes('exitoso')) {
                limpiarModalYCerrar();
                regenerarTokenCSRF('input[name="csrf_token"]', 'formNuevoUsuario');
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
// FUNCIÓN PARA LIMPIAR Y CERRAR MODAL - ACTUALIZADA
// ==========================================================================================================

function limpiarModalYCerrar() {
    const modal = bootstrap.Modal.getInstance(document.getElementById('modalNuevoUsuario'));
    if (modal) modal.hide();

    document.getElementById('formNuevoUsuario').reset();
    const inputs = document.querySelectorAll('.form-control, .form-select, .form-check-input');
    inputs.forEach(input => {
        input.classList.remove('is-valid', 'is-invalid');
    });

    const feedbacks = document.querySelectorAll('.invalid-feedback');
    feedbacks.forEach(feedback => feedback.textContent = '');

    // Limpiar dropdowns
    const sucursalSelect = document.getElementById('usuarioSucursalId');
    const sedeSelect = document.getElementById('usuarioSedeId');
    
    if (sucursalSelect) {
        sucursalSelect.innerHTML = '<option value="">Seleccionar Sucursal</option>';
    }
    
    if (sedeSelect) {
        sedeSelect.innerHTML = '<option value="">Seleccionar Sede</option>';
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

    fetch('../ajax/App_usuariosAjax.php', {
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

// ==========================================================================================================
// FUNCIONES PARA CARGAR DROPDOWNS EN CASCADA
// ==========================================================================================================

// Cargar empresas al iniciar
function cargarEmpresas() {
    const empresaSelect = document.getElementById('usuarioEmpresaId');
    const empresaEditarSelect = document.getElementById('editarUsuarioEmpresaId');
	
    if (empresaSelect) {
        // Aquí deberías hacer una llamada AJAX para obtener las empresas reales
        // Por ahora simulo algunas empresas
        empresaSelect.innerHTML =  `
			<option value="">Seleccionar Empresa</option>
			<option value="16">Empresa Ejemplo 16</option>
			<option value="2">Empresa Ejemplo 2</option>
			<option value="3">Empresa Ejemplo 3</option>
        `;
    }
	
	 if (empresaEditarSelect) {
        // Aquí deberías hacer una llamada AJAX para obtener las empresas reales
        // Por ahora simulo algunas empresas
        empresaEditarSelect.innerHTML =  `
			<option value="">Seleccionar Empresa</option>
			<option value="16">Empresa Ejemplo 16</option>
			<option value="2">Empresa Ejemplo 2</option>
			<option value="3">Empresa Ejemplo 3</option>
        `;
    }
}

// Cargar sucursales por empresa
function cargarSucursalesPorEmpresa(empresaId,objet) {
    const sucursalSelect = document.getElementById(objet);
    
    if (!sucursalSelect) return;
    
    if (!empresaId) {
        sucursalSelect.innerHTML = '<option value="">Seleccionar Sucursal</option>';
        return;
    }

    // Aquí deberías hacer una llamada AJAX real
    // Por ahora simulo algunas sucursales
    sucursalSelect.innerHTML = `
		<option value="">Seleccionar Sucursal</option>
		<option value="12">Sucursal Centro - Empresa ${empresaId}</option>
		<option value="2">Sucursal Norte - Empresa ${empresaId}</option>
	`;
}

// Cargar sedes por sucursal
function cargarSedesPorSucursal(sucursalId, objet) {
    const sedeSelect = document.getElementById(objet);
    
    if (!sedeSelect) return;
    
    if (!sucursalId) {
        sedeSelect.innerHTML = '<option value="">Seleccionar Sede</option>';
        return;
    }

    // Aquí deberías hacer una llamada AJAX real
    // Por ahora simulo algunas sedes
    sedeSelect.innerHTML = `
		<option value="">Seleccionar Sede</option> 
		<option value="1">Sede Principal - Sucursal ${sucursalId}</option> 
		<option value="2">Sede Administrativa - Sucursal ${sucursalId}</option>
    `;
}

// Variable global para recordar qué vista está activa para usuarios
let vistaActualUsuarios = 'list';

//===========================================================================================================
// FUNCIÓN PRINCIPAL PARA LISTAR USUARIOS
//===========================================================================================================

function cargarUsuarios(pagina = 1) {
	const shareusuario = document.getElementById('shareusuario').value || '';
	const estadousuario = document.getElementById('estadousuario').value || '';
	const csrf_token_list_usuarios = document.querySelector('input[name="csrf_token_list_usuarios"]').value;

	// Mostrar loader en la tabla tbody
	const divtable = document.querySelector('.listadousuarios');
	if (divtable) {
		divtable.innerHTML = `<div class="row">
			<div class="col-12 text-center py-5">
				<i class="bi bi-arrow-clockwise spin fa-3x text-muted mb-3"></i>
				<h5 class="text-muted">Cargando...</h5>
				<p class="text-muted">Aquí se cargan los usuarios disponibles</p>
			</div>`;
	}

	const formData = new FormData();
	formData.append('csrf_token_list_usuarios', csrf_token_list_usuarios);
	formData.append('shareusuario', shareusuario);
	formData.append('estadousuario', estadousuario);
	formData.append('pagina', pagina);
	formData.append('vista_tipo', vistaActualUsuarios);

	fetch('../ajax/App_usuariosAjax.php', {
		method: 'POST',
		body: formData
	})
	.then(response => response.json())
	.then(data => {
		console.log('Respuesta del servidor (usuarios):', data);

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
			if (tableTitleBadge && data.total_usuarios !== undefined) {
				tableTitleBadge.textContent = data.total_usuarios;
			}

		} else if (data.Alerta) {
			// Mostrar alerta del sistema
			mostrarSweetAlert(data);

			// Si hay error, mostrar mensaje en tabla
			if (divtable) {
				divtable.innerHTML = `
					<tr>
						<td colspan="9" class="text-center py-4 text-danger">
							<i class="bi bi-exclamation-triangle fa-2x mb-2"></i><br>
							${data.Texto || 'Error al cargar usuarios'}
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
					<td colspan="9" class="text-center py-4 text-danger">
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
			cargarUsuarios(pagina);
		});
	});
}

//===========================================================================================================
// FUNCIÓN PARA FILTRAR USUARIOS 
//===========================================================================================================
let filtroTimeoutUsuarios;
function filtrarUsuarios() {
	clearTimeout(filtroTimeoutUsuarios);
	filtroTimeoutUsuarios = setTimeout(() => {
		cargarUsuarios(1); // Siempre volver a página 1 al filtrar
	}, 500); // Esperar 500ms después de que el usuario deje de escribir
}

//===========================================================================================================
// FUNCIÓN PARA CAMBIAR DE PÁGINA
//===========================================================================================================
function cargarPaginaUsuarios(pagina) {
	cargarUsuarios(pagina);
}

//===========================================================================================================
// FUNCIÓN PARA CAMBIAR VISTA
//===========================================================================================================
function toggleViewUsuarios(viewType) {
	// Cambiar botones
	const buttons = document.querySelectorAll('.view-btn');
	buttons.forEach(btn => btn.classList.remove('active'));

	if (viewType === 'list') {
		buttons[0].classList.add('active');
		vistaActualUsuarios = 'list';
	} else {
		buttons[1].classList.add('active');
		vistaActualUsuarios = 'grid';
	}

	// Recargar con la nueva vista
	cargarUsuarios(1);
}

//===========================================================================================================
// FUNCIÓN PARA LIMPIAR FILTROS
//===========================================================================================================
function limpiarFiltrosUsuarios() {
	document.getElementById('shareusuario').value = '';
	document.getElementById('estadousuario').value = '';
	cargarUsuarios(1);
}

//===========================================================================================================
// EVENT LISTENERS PARA USUARIOS
//===========================================================================================================

// Agregar estos event listeners al DOMContentLoaded existente o crear uno nuevo
document.addEventListener('DOMContentLoaded', function() {

	// Event listeners para filtros de usuarios
	const shareUsuarioInput = document.getElementById('shareusuario');
	const estadoUsuarioSelect = document.getElementById('estadousuario');

	if (shareUsuarioInput) {
		shareUsuarioInput.addEventListener('input', filtrarUsuarios);
	}

	if (estadoUsuarioSelect) {
		estadoUsuarioSelect.addEventListener('change', filtrarUsuarios);
	}

	// Cargar usuarios al iniciar (si estamos en la página de usuarios)
	if (document.querySelector('.listadousuarios')) {
		cargarUsuarios();
	}
});

//===========================================================================================================
//FUNCIONES PARA ELIMINAR USUARIOS
//===========================================================================================================

function eliminarUsuario(codigoUsuario, nombreUsuario, paginaactual) {
	Swal.fire({
		title: '¿Estás seguro?',
		text: `¿Deseas eliminar el usuario "${nombreUsuario}"?`,
		icon: 'warning',
		showCancelButton: true,
		confirmButtonColor: '#d33',
		cancelButtonColor: '#3085d6',
		confirmButtonText: 'Sí, eliminar',
		cancelButtonText: 'Cancelar'
	}).then((result) => {
		if (result.isConfirmed) {
			eliminarUsuarioAjax(codigoUsuario, nombreUsuario, paginaactual);
		}
	});
}

// FUNCION AJAX PARA ELIMINAR EMPRESA
function eliminarUsuarioAjax(codigoUsuario, nombreUsuario, paginaactual) {
	const formData = new FormData();
	formData.append('accion', 'eliminar_usuario');
	formData.append('codigo_usuario', codigoUsuario);
	formData.append('csrf_token_eliminar', document.querySelector('input[name="csrf_token_list_usuarios"]').value);

	Swal.fire({
		title: 'Eliminando...',
		text: 'Por favor espere',
		allowOutsideClick: false,
		showConfirmButton: false,
		didOpen: () => {
			Swal.showLoading();
		}
	});

	fetch('../ajax/App_usuariosAjax.php', {
		method: 'POST',
		body: formData
	})

	.then(response => response.json())
	.then(data => {
		Swal.close();
		mostrarSweetAlert(data);

		if (data.Tipo === 'success') {
			// Recargar listado despuÃ©s de eliminar
			// Actualizar token si viene en la respuesta
			if (data.nuevo_token) {
				document.querySelector('input[name="csrf_token_list_usuarios"]').value = data.nuevo_token;
			}
			cargarUsuarios(paginaactual);
		}
	})
	.catch(error => {
		Swal.close();
		console.error('Error:', error);
		Swal.fire({
			title: 'Error',
			text: 'No se pudo eliminar el usuario',
			icon: 'error'
		});
	});
}

//===========================================================================================================
//FUNCIONES PARA VER USUARIO Y EDITAR
//===========================================================================================================

// Variables globales para los tabs
let usuarioActualId = null;
let usuarioActualData = null;

// Event listeners para los tabs
document.addEventListener('DOMContentLoaded', function() {
	// Manejar cambio de tabs
	const tabButtons = document.querySelectorAll('[data-bs-toggle="tab"]');
	tabButtons.forEach(button => {
		button.addEventListener('shown.bs.tab', function(event) {
			const tabId = event.target.getAttribute('data-bs-target');
			manejarCambioTab(tabId);
		});
	});
});

// FUNCIÃ“N para manejar el cambio de tabs
function manejarCambioTab(tabId) {
	const modalFooter = document.getElementById('modalFooterInformacion');

	switch(tabId) {
		case '#info-personal-pane':
			// Mostrar footer con botones de guardar
			if (modalFooter) modalFooter.style.display = 'flex';
			break;

		case '#roles-permisos-pane':
			// Ocultar footer y cargar sucursales
			if (modalFooter) modalFooter.style.display = 'none';
			if (usuarioActualId) {
				//cargarSucursalesEmpresa(usuarioActualId);
			}
			break;

		case '#configuracion-pane':
			// Ocultar footer y cargar sedes organizadas por sucursal
			if (modalFooter) modalFooter.style.display = 'none';
			if (usuarioActualId) {
				//recargarSedesSucursal();
			}
			break;
		case '#auditoria-pane':
			// Ocultar footer y cargar sedes organizadas por sucursal
			if (modalFooter) modalFooter.style.display = 'none';
			if (usuarioActualId) {
				//recargarSedesSucursal();
			}
			break;
	}
}

function verUsuario(usuarioId) {
	verUsuarioConTabs(usuarioId);
}

/********************************* funcion para editar usuario *********************************/

function verUsuarioConTabs(usuarioId) {
	// Mostrar loading
	Swal.fire({
		title: 'Cargando...',
		text: 'Obteniendo datos del usuario',
		allowOutsideClick: false,
		showConfirmButton: false,
		didOpen: () => {
			Swal.showLoading();
		}
	});

	// Preparar datos
	const formData = new FormData();
	formData.append('accion', 'obtener_usuario');
	formData.append('usuario_id', usuarioId);
	formData.append('csrf_token_obtener', document.querySelector('input[name="csrf_token_list_usuarios"]').value);

	// Enviar peticiÃ³n
	fetch('../ajax/App_usuariosAjax.php', {
		method: 'POST',
		body: formData
	})
	.then(response => response.json())
	.then(data => {
		Swal.close();

		if (data.status === 'success') {
			// Guardar datos globales
			usuarioActualId = usuarioId;
			usuarioActualData = data.usuario;
			
			cargarSucursalesPorEmpresa(data.usuario.UsuarioEmpresaId,'editarUsuarioSucursalId');
			cargarSedesPorSucursal(data.usuario.UsuarioSucursalId, 'editarUsuarioSedeId');
			
			// Llenar el modal con los datos
			llenarModalusuariosConTabs(data.usuario);
			// Resetear tabs al tab de informaciÃ³n
			resetearTabs();

			// Mostrar el modal
			const modal = new bootstrap.Modal(document.getElementById('modalEditarUsuario'));
			modal.show();
		} else {
			mostrarSweetAlert(data);
		}
	})
	.catch(error => {
		Swal.close();
		console.error('Error:', error);
		Swal.fire({
			title: 'Error',
			text: 'No se pudo cargar la información',
			icon: 'error'
		});
	});
}

// FUNCIÃ“N para llenar el modal con datos (actualizada para tabs)
function llenarModalusuariosConTabs(usuario) {
	// Campos de solo lectura
	document.getElementById('verUsuarioCodigo').value = usuario.UsuarioCodigo  || '';
	document.getElementById('verUsuarioEstado').value = usuario.UsuarioEstado  || '';
	document.getElementById('verUsuarioFechaRegistro').value = usuario.UsuarioFechaRegistro  || '';

	// Campos editables
	document.getElementById('editarUsuarioTipoDocumento').value = usuario.UsuarioTipoDocumento || '';
	document.getElementById('editarUsuarioDocumento').value = usuario.UsuarioDocumento  || '';
	document.getElementById('editarUsuarioNombres').value = usuario.UsuarioNombres || '';
	document.getElementById('editarUsuarioApellidos').value = usuario.UsuarioApellidos || '';
	document.getElementById('editarUsuarioEmail').value = usuario.UsuarioEmail || '';
	document.getElementById('editarUsuarioTelefono').value = usuario.UsuarioTelefono || '';
	document.getElementById('editarUsuarioCargo').value = usuario.UsuarioCargo || '';
	document.getElementById('editarUsuarioDepartamento').value = usuario.UsuarioDepartamento || '';
	document.getElementById('editarUsuarioEmpresaId').value = usuario.UsuarioEmpresaId || '';
	document.getElementById('editarUsuarioSucursalId').value = usuario.UsuarioSucursalId || '';
	document.getElementById('editarUsuarioSedeId').value = usuario.UsuarioSedeId || '';
	document.getElementById('usuarioIdEditar').value = usuario.UsuarioId;
	document.getElementById('tokenUsuarioEspecifico').value = usuario.TokenUsuarioEspecifico;

	// Actualizar título del modal y nombres en tabs
	document.getElementById('nombreUsuarioEditar').textContent = `GestiÃ³n de Usuario: ${usuario.UsuarioNombres}`;

	// Limpiar validaciones previas
	const campos = document.querySelectorAll('#formEditarUsuarioInfo .form-control');
	campos.forEach(campo => {
		campo.classList.remove('is-valid', 'is-invalid');
	}); 
	
	console.log('Usuario cargado con tabs:', {
		codigo: usuario.UsuarioCodigo,
		nombre: usuario.UsuarioNombres,
		tieneToken: usuario.tokenUsuarioEspecifico ? 'SI' : 'NO'
	});
}

// FUNCIÃ“N para resetear tabs al tab principal
function resetearTabs() {
	// Activar el primer tab (InformaciÃ³n)
	const firstTab = document.getElementById('info-personal-tab');
	const firstTabPane = document.getElementById('info-personal-pane');

	// Remover active de todos los tabs
	document.querySelectorAll('.nav-link').forEach(link => link.classList.remove('active'));
	document.querySelectorAll('.tab-pane').forEach(pane => {
		pane.classList.remove('show', 'active');
	});

	// Activar el primer tab
	firstTab.classList.add('active');
	firstTabPane.classList.add('show', 'active');

	// Mostrar footer
	const modalFooter = document.getElementById('modalFooterInformacion');
	if (modalFooter) modalFooter.style.display = 'flex';
}

// FUNCIÃ“N PARA GUARDAR CAMBIOS
function guardarCambiosUsuario() {
	// Validar formulario
	if (!validarFormularioEdicion()) {
		Swal.fire({
			title: 'Formulario incompleto',
			text: 'Por favor complete todos los campos correctamente',
			icon: 'warning'
		});
		return;
	}

	// Verificar que tenemos el token especÃ­fico
	const tokenEspecifico = document.getElementById('tokenUsuarioEspecifico').value;
	if (!tokenEspecifico) {
		Swal.fire({
			title: 'Error de seguridad',
			text: 'Token de usuario no encontrado. Vuelve a cargar el usuario.',
			icon: 'error'
		});
		return;
	}

	// Preparar datos
	const formData = new FormData();
	formData.append('accion', 'actualizar_usuario');
	formData.append('usuario_id', document.getElementById('usuarioIdEditar').value);
	formData.append('usuario_TipoDocumento', document.getElementById('editarUsuarioTipoDocumento').value);
	formData.append('usuario_Documento', document.getElementById('editarUsuarioDocumento').value);
	formData.append('usuario_Nombres', document.getElementById('editarUsuarioNombres').value);
	formData.append('usuario_Apellidos', document.getElementById('editarUsuarioApellidos').value);
	formData.append('usuario_email', document.getElementById('editarUsuarioEmail').value);
	formData.append('usuario_Telefono', document.getElementById('editarUsuarioTelefono').value);
	formData.append('usuario_Cargo', document.getElementById('editarUsuarioCargo').value);
	formData.append('usuario_Departamento', document.getElementById('editarUsuarioDepartamento').value);
	formData.append('usuario_EmpresaId', document.getElementById('editarUsuarioEmpresaId').value);
	formData.append('usuario_SucursalId', document.getElementById('editarUsuarioSucursalId').value);
	formData.append('usuario_SedeId', document.getElementById('editarUsuarioSedeId').value);
	formData.append('csrf_token_editar', document.querySelector('input[name="csrf_token_editar"]').value);
	formData.append('token_usuario_especifico', document.querySelector('input[name="tokenUsuarioEspecifico"]').value);

	// Mostrar loading
	Swal.fire({
		title: 'Guardando...',
		text: 'Actualizando información de usuario',
		allowOutsideClick: false,
		showConfirmButton: false,
		didOpen: () => {
			Swal.showLoading();
		}
	});

	// Enviar datos
	fetch('../ajax/App_usuariosAjax.php', {
		method: 'POST',
		body: formData
	})
	.then(response => response.json())
	.then(data => {
		Swal.close();
		mostrarSweetAlert(data);

		if (data.Tipo === 'success') {
			// Cerrar modal
			const modal = bootstrap.Modal.getInstance(document.getElementById('modalEditarUsuario'));
			modal.hide();

			// Limpiar token específico de la sesiÃ³n
			document.getElementById('tokenUsuarioEspecifico').value = '';

			// Regenerar token
			regenerarTokenCSRF('input[name="csrf_token_editar"]', 'editarUsuario');

			// Recargar tabla
			cargarUsuarios();
		}
	})
	.catch(error => {
		Swal.close();
		console.error('Error:', error);
		Swal.fire({
			title: 'Error',
			text: 'No se pudieron guardar los cambios',
			icon: 'error'
		});
	});
}

// FUNCIÃ“N PARA VALIDAR FORMULARIO
function validarFormularioEdicion() {
	const campos = [
		{ id: 'editarUsuarioTipoDocumento', tipo: 'texto' },
		{ id: 'editarUsuarioDocumento', tipo: 'numero' },
		{ id: 'editarUsuarioNombres', tipo: 'texto' },
		{ id: 'editarUsuarioApellidos', tipo: 'texto' },
		{ id: 'editarUsuarioEmail', tipo: 'email' },
		{ id: 'editarUsuarioTelefono', tipo: 'telefono' },
		{ id: 'editarUsuarioCargo', tipo: 'texto' },
		{ id: 'editarUsuarioDepartamento', tipo: 'texto' },
		{ id: 'editarUsuarioEmpresaId', tipo: 'numero' },
		{ id: 'editarUsuarioSucursalId', tipo: 'numero' },
		{ id: 'editarUsuarioSedeId', tipo: 'numero' }
	];

	let esValido = true;

	campos.forEach(campo => {
		const elemento = document.getElementById(campo.id);
		const valor = elemento.value.trim();

		// Limpiar estilos previos
		elemento.classList.remove('is-invalid', 'is-valid');

		if (valor === '') {
			elemento.classList.add('is-invalid');
			esValido = false;
		} else if (campo.tipo === 'email') {
			const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
			if (!emailRegex.test(valor)) {
				elemento.classList.add('is-invalid');
				esValido = false;
			} else {
				elemento.classList.add('is-valid');
			}
		} else if (campo.tipo === 'telefono') {
			const telefonoRegex = /^[0-9]{7,15}$/;
			if (!telefonoRegex.test(valor)) {
				elemento.classList.add('is-invalid');
				esValido = false;
			} else {
				elemento.classList.add('is-valid');
			}
		} else {
			elemento.classList.add('is-valid');
		}
	});

	return esValido;
}

	
//===========================================================================================================
// GESTIÓN DE ESTADOS CON DROPDOWN 
//===========================================================================================================

// Event listener para cambios de estado (usar delegación de eventos)
document.addEventListener('click', function(e) {
	if (e.target.classList.contains('cambiar-estado-usuario')) {
		e.preventDefault();

		const usuarioId = e.target.getAttribute('data-usuario-id');
		const usuarioNombre = e.target.getAttribute('data-usuario-nombre');
		const nuevoEstado = e.target.getAttribute('data-nuevo-estado');
		const estadoActual = e.target.closest('.dropdown').querySelector('.estado-dropdown').getAttribute('data-estado-actual');

		mostrarModalCambioEstado(usuarioId, usuarioNombre, estadoActual, nuevoEstado);
	}
});

// FUNCIÃ“N PARA MOSTRAR MODAL DE CAMBIO DE ESTADO
function mostrarModalCambioEstado(usuarioId, usuarioNombre, estadoActual, nuevoEstado) {

	// ConfiguraciÃ³n de estados
	const configuracionEstados = {
		'Activo': { 
			color: 'success', 
			icono: 'bi-check-circle-fill', 
			descripcion: 'El usuario estará completamente operativo y podrá realizar todas sus actividades normalmente.',
			colorBtn: '#28a745'
		},
		'Inactivo': { 
			color: 'warning', 
			icono: 'bi-pause-circle-fill', 
			descripcion: 'El usuario estará temporalmente desactivado. Sus operaciones quedarán suspendidas.',
			colorBtn: '#ffc107'
		},
		'Suspendido': { 
			color: 'danger', 
			icono: 'bi-x-circle-fill', 
			descripcion: 'El usuario estará suspendido por completo. No podrá realizar ninguna actividad.',
			colorBtn: '#dc3545'
		}
	};

	const config = configuracionEstados[nuevoEstado];

	// Llenar datos en el modal
	document.getElementById('usuarioIdCambioEstado').value = usuarioId;
	document.getElementById('nuevoEstadoCambio').value = nuevoEstado;
	document.getElementById('nombreUsuarioCambio').textContent = usuarioNombre;
	document.getElementById('estadoActualCambio').textContent = estadoActual;
	document.getElementById('motivoCambioEstado').value = '';

	// Configurar informaciÃ³n del nuevo estado
	const infoCard = document.getElementById('infoCambioEstado');
	const icono = document.getElementById('iconoNuevoEstado');
	const titulo = document.getElementById('tituloNuevoEstado');
	const descripcion = document.getElementById('descripcionNuevoEstado');
	const btnConfirmar = document.getElementById('btnConfirmarCambio');

	// Aplicar configuraciÃ³n visual
	infoCard.className = `estado-info-card ${config.color}`;
	icono.className = `bi ${config.icono} icono-estado-grande text-${config.color}`;
	titulo.textContent = `Cambiar a ${nuevoEstado}`;
	descripcion.textContent = config.descripcion;
	btnConfirmar.className = `btn btn-${config.color}`;
	btnConfirmar.style.backgroundColor = config.colorBtn;
	btnConfirmar.innerHTML = `<i class="bi bi-check-circle me-1"></i>Cambiar a ${nuevoEstado}`;

	// Limpiar validaciones previas
	const motivo = document.getElementById('motivoCambioEstado');
	motivo.classList.remove('is-valid', 'is-invalid');

	// Mostrar modal
	const modal = new bootstrap.Modal(document.getElementById('modalCambioEstado'));
	modal.show();

	// Enfocar en el textarea
	setTimeout(() => {
		motivo.focus();
	}, 500);
}

// FUNCIÓN PARA CONFIRMAR CAMBIO DE ESTADO (EMPRESAS, SUCURSALES Y SEDES)
function confirmarCambioEstado() {
	const motivo = document.getElementById('motivoCambioEstado');
	const motivoTexto = motivo.value.trim();

	// Validar motivo
	if (!motivoTexto) {
		motivo.classList.add('is-invalid');
		motivo.focus();
		return;
	}

	if (motivoTexto.length < 10) {
		motivo.classList.add('is-invalid');
		motivo.focus();
		return;
	}

	motivo.classList.remove('is-invalid');
	motivo.classList.add('is-valid');

	const entidadId = document.getElementById('usuarioIdCambioEstado').value;
	const nuevoEstado = document.getElementById('nuevoEstadoCambio').value;

	
	ejecutarCambioEstado(entidadId, nuevoEstado, motivoTexto);
	
}

// FUNCIÓN PARA EJECUTAR EL CAMBIO DE ESTADO
function ejecutarCambioEstado(usuarioId, nuevoEstado, motivo) {
	// Cerrar modal primero
	const modal = bootstrap.Modal.getInstance(document.getElementById('modalCambioEstado'));
	modal.hide();

	// Mostrar loading
	Swal.fire({
		title: `Cambiando a ${nuevoEstado}...`,
		text: 'Por favor espere mientras se actualiza el estado del usuario',
		allowOutsideClick: false,
		allowEscapeKey: false,
		showConfirmButton: false,
		didOpen: () => {
			Swal.showLoading();
		}
	});

	// Preparar datos
	const formData = new FormData();
	formData.append('accion', 'cambiar_estado_usuario');
	formData.append('usuario_id', usuarioId);
	formData.append('nuevo_estado', nuevoEstado);
	formData.append('motivo_cambio', motivo);
	formData.append('csrf_token_estado', document.querySelector('input[name="csrf_token_estado"]').value);

	// Enviar peticiÃ³n
	fetch('../ajax/App_usuariosAjax.php', {
		method: 'POST',
		body: formData
	})
	.then(response => response.json())
	.then(data => {
		Swal.close();

		// Mostrar resultado
		mostrarSweetAlert(data);

		if (data.Tipo === 'success') {
			// Regenerar token
			regenerarTokenCSRF('input[name="csrf_token_estado"]', 'cambioEstado');
			// Recargar tabla
			cargarEmpresas();
		}
	})
	.catch(error => {
		Swal.close();
		console.error('Error:', error);
		Swal.fire({
			title: 'Error de conexiÃ³n',
			text: 'No se pudo cambiar el estado de la empresa',
			icon: 'error'
		});
	});
}

// VALIDACIÓN EN TIEMPO REAL DEL MOTIVO
document.addEventListener('DOMContentLoaded', function() {
	const motivoInput = document.getElementById('motivoCambioEstado');
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
});