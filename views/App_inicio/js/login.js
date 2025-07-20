// ==========================================================================================================
// SISTEMA DE LOGIN SIMPLE - JavaScript
// ==========================================================================================================

document.addEventListener('DOMContentLoaded', function() {
    
    console.log('Sistema de login cargado');
    
    // Generar token CSRF para login al cargar
    generarTokenLogin();
    
    // Configurar eventos
    configurarLogin();
    
    // ========================= FUNCIÓN PRINCIPAL DE LOGIN =========================
    function configurarLogin() {
        // Toggle entre login y recuperación
        const recordarBtn = document.getElementById('recordarBtn');
        const volverLoginBtn = document.getElementById('volverLoginBtn');
        const loginForm = document.getElementById('loginForm');
        const recuperarForm = document.getElementById('recuperarForm');
        
        if(recordarBtn) {
            recordarBtn.addEventListener('click', function(e) {
                e.preventDefault();
                if(loginForm) loginForm.style.display = 'none';
                if(recuperarForm) recuperarForm.style.display = 'block';
            });
        }

        if(volverLoginBtn) {
            volverLoginBtn.addEventListener('click', function(e) {
                e.preventDefault();
                if(loginForm) loginForm.style.display = 'block';
                if(recuperarForm) recuperarForm.style.display = 'none';
            });
        }

        // Evento del formulario de login
        const loginFormElement = document.getElementById('loginFormElement');
        if(loginFormElement) {
            loginFormElement.addEventListener('submit', function(e) {
                e.preventDefault();
                procesarLogin();
            });
        }
    }
    
    // ========================= GENERAR TOKEN CSRF =========================
    function generarTokenLogin() {
        const formData = new FormData();
        formData.append('accion', 'csrf_regenerar');
        formData.append('key', 'loginForm');
        
        fetch('./ajax/App_usuariosAjax.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if(data.status === 'success' && data.token) {
                // Crear o actualizar input token
                let tokenInput = document.getElementById('csrf_token_login');
                if(!tokenInput) {
                    tokenInput = document.createElement('input');
                    tokenInput.type = 'hidden';
                    tokenInput.id = 'csrf_token_login';
                    tokenInput.name = 'csrf_token_login';
                    
                    const form = document.getElementById('loginFormElement');
                    if(form) {
                        form.appendChild(tokenInput);
                    }
                }
                tokenInput.value = data.token;
                console.log('Token CSRF para login generado');
            }
        })
        .catch(error => {
            console.error('Error generando token:', error);
        });
    }
    
    // ========================= PROCESAR LOGIN =========================
    function procesarLogin() {
        const usuario = document.getElementById('usuario');
        const clave = document.getElementById('clave');
        const token = document.getElementById('csrf_token_login');
        
        // Validaciones básicas
        if(!usuario || !usuario.value.trim()) {
            Swal.fire({
                title: 'Usuario requerido',
                text: 'Por favor ingresa tu usuario',
                icon: 'warning'
            });
            return;
        }
        
        if(!clave || !clave.value) {
            Swal.fire({
                title: 'Contraseña requerida',
                text: 'Por favor ingresa tu contraseña',
                icon: 'warning'
            });
            return;
        }
        
        if(!token || !token.value) {
            Swal.fire({
                title: 'Error de seguridad',
                text: 'Token no válido. Recarga la página.',
                icon: 'error'
            }).then(() => {
                location.reload();
            });
            return;
        }
        
        // Mostrar loading
        Swal.fire({
            title: 'Iniciando sesión...',
            text: 'Validando credenciales',
            allowOutsideClick: false,
            allowEscapeKey: false,
            showConfirmButton: false,
            didOpen: () => {
                Swal.showLoading();
            }
        });
        
        // Preparar datos
        const formData = new FormData();
        formData.append('usuario', usuario.value.trim());
        formData.append('clave', clave.value);
        formData.append('csrf_token_login', token.value);
        
        // Enviar petición
        fetch('./ajax/App_usuariosAjax.php', {
            method: 'POST',
            body: formData
        })
        .then(response => {
            if (!response.ok) {
                throw new Error(`Error HTTP: ${response.status}`);
            }
            return response.json();
        })
        .then(data => {
            Swal.close();
            
            if (data.success) {
                // Login exitoso
                let mensaje = data.message;
                let icono = 'success';
                
                if(data.password_temporal) {
                    mensaje += `\n\nTienes ${data.dias_restantes} días para cambiar tu contraseña temporal.`;
                    icono = 'warning';
                }
                
                Swal.fire({
                    title: '¡Bienvenido!',
                    text: mensaje,
                    icon: icono,
                    timer: 2000,
                    showConfirmButton: false
                }).then(() => {
                    window.location.href = data.redirect_url;
                });
                
            } else {
                // Error en login
                Swal.fire({
                    title: 'Error',
                    text: data.message || 'Error en las credenciales',
                    icon: 'error'
                });
                
                // Regenerar token
                generarTokenLogin();
            }
        })
        .catch(error => {
            Swal.close();
            console.error('Error:', error);
            
            Swal.fire({
                title: 'Error de conexión',
                text: 'No se pudo conectar con el servidor',
                icon: 'error'
            });
            
            generarTokenLogin();
        });
    }
    
    // ========================= FUNCIÓN LOGOUT GLOBAL =========================
    window.logout = function() {
        Swal.fire({
            title: '¿Cerrar sesión?',
            text: '¿Estás seguro que quieres salir?',
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'Sí, salir',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                const formData = new FormData();
                formData.append('accion', 'logout');
                
                fetch('./ajax/App_usuariosAjax.php', {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if(data.success) {
                        Swal.fire({
                            title: 'Sesión cerrada',
                            text: data.message,
                            icon: 'success',
                            timer: 1500,
                            showConfirmButton: false
                        }).then(() => {
                            window.location.href = data.redirect_url;
                        });
                    }
                })
                .catch(error => {
                    console.error('Error logout:', error);
                    window.location.href = './';
                });
            }
        });
    };
});

console.log('Login.js cargado correctamente');