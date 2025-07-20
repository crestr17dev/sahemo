<style>
	:root {
		--primary-color: #1B5E20;
		--secondary-color: #388E3C;
		--navbar-color: #2E7D32;
		--sidebar-color: #495057;
		--accent-color: #198754;
		--sidebar-width: 280px; 
	}

	.sidebar {
		position: fixed;
		top: 0;
		left: 0;
		height: 100vh;
		width: var(--sidebar-width);
		background: linear-gradient(180deg, var(--sidebar-color) 0%, #495057 100%);
		z-index: 1000;
		transition: all 0.3s ease;
	}

	.sidebar-header {
		padding: 1.5rem;
		border-bottom: 1px solid rgba(255,255,255,0.1);
	}

	.sidebar-brand {
		color: white;
		text-decoration: none;
		font-weight: bold;
		font-size: 1.1rem;
	}

	.sidebar-nav {
		padding: 1rem 0;
	}

	.nav-link {
		color: rgba(255,255,255,0.8);
		padding: 0.75rem 1.5rem;
		transition: all 0.3s ease;
		display: flex;
		align-items: center;
	}

	.nav-link:hover, .nav-link.active {
		color: white;
		background: rgba(255,255,255,0.1);
	}

	.nav-link i {
		margin-right: 0.75rem;
		width: 20px;
	}

	.main-content {
		margin-left: var(--sidebar-width);
		padding: 2rem;
	}

	.top-bar {
		background: var(--navbar-color);
		color: white;
		padding: 1rem 2rem;
		margin: -2rem -2rem 2rem -2rem;
		border-radius: 0 0 10px 10px;
	}

	.color-preview {
		width: 40px;
		height: 40px;
		border-radius: 8px;
		border: 2px solid #dee2e6;
		cursor: pointer;
		transition: all 0.3s ease;
	}

	.color-preview:hover {
		transform: scale(1.1);
		border-color: var(--current-primary);
	}

	.preview-card {
		border: 2px solid #dee2e6;
		border-radius: 15px;
		overflow: hidden;
		transition: all 0.5s ease;
	}

	.preview-navbar {
		background: var(--preview-navbar, var(--current-navbar));
		color: white;
		padding: 1rem;
		transition: all 0.3s ease;
	}

	.preview-sidebar {
		background: var(--preview-sidebar, var(--current-sidebar));
		color: white;
		padding: 1rem;
		min-height: 200px;
		transition: all 0.3s ease;
	}

	.form-control:focus {
		border-color: var(--current-primary);
		box-shadow: 0 0 0 0.2rem rgba(13, 110, 253, 0.25);
	}


	.btn-primary {
		background-color: var(--primary-color);
		border-color: var(--primary-color);
	}

	.color-input-group {
		position: relative;
	}

	.color-input {
		position: absolute;
		width: 50px;
		height: 38px;
		right: 5px;
		top: 50%;
		transform: translateY(-50%);
		border: none;
		border-radius: 6px;
		cursor: pointer;
	}

	.config-section {
		background: white;
		border-radius: 15px;
		padding: 2rem;
		box-shadow: 0 4px 6px rgba(0,0,0,0.1);
		margin-bottom: 2rem;
	}

	@media (max-width: 768px) {
		.sidebar {
			transform: translateX(-100%);
		}

		.main-content {
			margin-left: 0;
		}
	}
</style>
<div>
    <!-- Sidebar -->
    <nav class="sidebar">
        <div class="sidebar-header">
            <a href="/dashboard/" class="sidebar-brand">
                <i class="bi bi-building me-2"></i>
                <?php echo (!empty(COMPANY)) ? COMPANY : "Sistema de Gestión Administrativa" ?>
            </a>
        </div>

        <ul class="sidebar-nav nav flex-column">
            <li class="nav-item">
                <a class="nav-link" href="<?php echo SERVERURL?>dashboard/">
                    <i class="bi bi-speedometer2"></i>
                    Dashboard
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link active" href="<?php echo SERVERURL?>configuracion/">
                    <i class="bi bi-palette"></i>
                    Configuración Temas
                </a>
            </li>
			<li class="nav-item">
                <a class="nav-link" href="<?php echo SERVERURL?>configuracion/">
                    <i class="bi bi-palette"></i>
                    Configuración Slider
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="<?php echo SERVERURL?>ccostos/">
                    <i class="bi bi-diagram-3"></i>
                    Centros de Costos
                </a>
            </li>
			<li class="nav-item">
                <a class="nav-link" href="<?php echo SERVERURL?>ubicaciones/">
                    <i class="bi bi-geo-alt"></i>
                    Ubicaciones
                </a>
            </li>
			<li class="nav-item">
                <a class="nav-link" href="#">
                    <i class="bi bi-shield"></i>
                    Privilegios segun rol
                </a>
            </li>
			<li class="nav-item">
                <a class="nav-link" href="#">
                    <i class="bi bi-send"></i>
                    Correos de Envios por Modulo
                </a>
            </li>
			<li class="nav-item">
                <a class="nav-link" href="<?php echo SERVERURL?>estructuras/">
                    <i class="bi bi-geo-alt"></i>
                    Estructuras TB_BD
                </a>
            </li>

            <hr class="my-3" style="border-color: rgba(255,255,255,0.2);">

            <li class="nav-item">
                <a class="nav-link" href="/logout/">
                    <i class="bi bi-box-arrow-right"></i>
                    Cerrar Sesión
                </a>
            </li>
        </ul>
    </nav>

    <!-- Main Content -->
    <main class="main-content">
        <!-- Top Bar -->
        <div class="top-bar">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h4 class="mb-0">
                        <i class="bi bi-palette me-2"></i>
                        Configuración de Temas
                    </h4>
                    <small>Personaliza la apariencia de tu sistema</small>
                </div>
                <div>
                    <span class="badge bg-light text-dark"><?php echo (!empty(COMPANY)) ? COMPANY : "Codigo Empresa" ?></span>
                </div>
            </div>
        </div>

        <div class="row">
            <!-- Panel de Configuración -->
            <div class="col-md-6">
                <div class="config-section">
                    <h5 class="mb-4">
                        <i class="bi bi-sliders me-2"></i>
                        Personalizar Colores
                    </h5>

                    <form id="temaForm">
                        {% csrf_token %}

                        <!-- Nombre de la Empresa -->
                        <div class="mb-4">
                            <label class="form-label">
                                <i class="bi bi-building me-1"></i>
                                Nombre de la Empresa
                            </label>
                            <input type="text" class="form-control" id="nombre_empresa" name="nombre_empresa"
                                   value="<?php echo (!empty(COMPANY)) ? COMPANY : "Sistema de Gestión Administrativa" ?>" placeholder="Nombre de tu empresa">
                        </div>

                        <!-- Color Primario -->
                        <div class="mb-4">
                            <label class="form-label">
                                <i class="bi bi-circle-fill me-1" style="color: var(--current-primary);"></i>
                                Color Primario
                            </label>
                            <div class="color-input-group">
                                <input type="text" class="form-control" id="color_primario_text"
                                       value="{{ tema_actual.color_primario|default:'#0d6efd' }}" placeholder="#0d6efd">
                                <input type="color" class="color-input" id="color_primario" name="color_primario"
                                       value="{{ tema_actual.color_primario|default:'#0d6efd' }}">
                            </div>
                            <small class="text-muted">Color principal de botones y elementos destacados</small>
                        </div>

                        <!-- Color Navbar -->
                        <div class="mb-4">
                            <label class="form-label">
                                <i class="bi bi-circle-fill me-1" style="color: var(--current-navbar);"></i>
                                Color Barra Superior
                            </label>
                            <div class="color-input-group">
                                <input type="text" class="form-control" id="color_navbar_text"
                                       value="{{ tema_actual.color_navbar|default:'#343a40' }}" placeholder="#343a40">
                                <input type="color" class="color-input" id="color_navbar" name="color_navbar"
                                       value="{{ tema_actual.color_navbar|default:'#343a40' }}">
                            </div>
                            <small class="text-muted">Color de la barra de navegación superior</small>
                        </div>

                        <!-- Color Sidebar -->
                        <div class="mb-4">
                            <label class="form-label">
                                <i class="bi bi-circle-fill me-1" style="color: var(--current-sidebar);"></i>
                                Color Menú Lateral
                            </label>
                            <div class="color-input-group">
                                <input type="text" class="form-control" id="color_sidebar_text"
                                       value="{{ tema_actual.color_sidebar|default:'#495057' }}" placeholder="#495057">
                                <input type="color" class="color-input" id="color_sidebar" name="color_sidebar"
                                       value="{{ tema_actual.color_sidebar|default:'#495057' }}">
                            </div>
                            <small class="text-muted">Color del menú lateral izquierdo</small>
                        </div>

                        <!-- Botones -->
                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary btn-lg">
                                <i class="bi bi-check-circle me-2"></i>
                                Aplicar Cambios
                            </button>
                            <button type="button" class="btn btn-outline-secondary" id="resetBtn">
                                <i class="bi bi-arrow-clockwise me-2"></i>
                                Restablecer por Defecto
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Vista Previa -->
            <div class="col-md-6">
                <div class="config-section">
                    <h5 class="mb-4">
                        <i class="bi bi-eye me-2"></i>
                        Vista Previa
                    </h5>

                    <div class="preview-card" id="previewCard">
                        <div class="preview-navbar" id="previewNavbar">
                            <div class="d-flex justify-content-between align-items-center">
                                <span class="fw-bold" id="previewNombre"><?php echo (!empty(COMPANY)) ? COMPANY : "Sistema de Gestión Administrativa" ?></span>
                                <i class="bi bi-person-circle"></i>
                            </div>
                        </div>
                        <div class="row m-0">
                            <div class="col-4 p-0">
                                <div class="preview-sidebar" id="previewSidebar">
                                    <div class="mb-3">
                                        <i class="bi bi-speedometer2 me-2"></i>
                                        Dashboard
                                    </div>
                                    <div class="mb-3">
                                        <i class="bi bi-people me-2"></i>
                                        Usuarios
                                    </div>
                                    <div class="mb-3">
                                        <i class="bi bi-box me-2"></i>
                                        Inventario
                                    </div>
                                </div>
                            </div>
                            <div class="col-8 p-3">
                                <h6>Contenido Principal</h6>
                                <p class="text-muted mb-3">Así se verá tu dashboard con los nuevos colores.</p>
                                <div class="d-flex gap-2">
                                    <button class="btn btn-sm" id="previewBtnPrimary"
                                            style="background-color: var(--preview-primary, var(--current-primary)); border-color: var(--preview-primary, var(--current-primary)); color: white;">
                                        Botón Primario
                                    </button>
                                    <button class="btn btn-outline-secondary btn-sm">Botón Secundario</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Colores Predefinidos -->
                <div class="config-section">
                    <h6 class="mb-3">
                        <i class="bi bi-palette-fill me-2"></i>
                        Temas Predefinidos
                    </h6>
                    <div class="row">
                        <div class="col-4 text-center mb-3">
                            <div class="color-preview mx-auto" style="background: linear-gradient(45deg, #0d6efd, #1e3a5f);"
                                 data-tema="azul" title="Tema Azul Profesional"></div>
                            <small class="d-block mt-1">Azul</small>
                        </div>
                        <div class="col-4 text-center mb-3">
                            <div class="color-preview mx-auto" style="background: linear-gradient(45deg, #dc3545, #b02a37);"
                                 data-tema="rojo" title="Tema Rojo Corporativo"></div>
                            <small class="d-block mt-1">Rojo</small>
                        </div>
                        <div class="col-4 text-center mb-3">
                            <div class="color-preview mx-auto" style="background: linear-gradient(45deg, #198754, #146c43);"
                                 data-tema="verde" title="Tema Verde Natural"></div>
                            <small class="d-block mt-1">Verde</small>
                        </div>
                        <div class="col-4 text-center mb-3">
                            <div class="color-preview mx-auto" style="background: linear-gradient(45deg, #6f42c1, #5a2d91);"
                                 data-tema="morado" title="Tema Morado Creativo"></div>
                            <small class="d-block mt-1">Morado</small>
                        </div>
                        <div class="col-4 text-center mb-3">
                            <div class="color-preview mx-auto" style="background: linear-gradient(45deg, #fd7e14, #e55a00);"
                                 data-tema="naranja" title="Tema Naranja Dinámico"></div>
                            <small class="d-block mt-1">Naranja</small>
                        </div>
                        <div class="col-4 text-center mb-3">
                            <div class="color-preview mx-auto" style="background: linear-gradient(45deg, #20c997, #17a589);"
                                 data-tema="teal" title="Tema Teal Moderno"></div>
                            <small class="d-block mt-1">Teal</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <!-- Bootstrap 5 JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        const temas = {
            azul: { primario: '#0d6efd', navbar: '#1e3a5f', sidebar: '#2c5282' },
            rojo: { primario: '#dc3545', navbar: '#b02a37', sidebar: '#8b2332' },
            verde: { primario: '#198754', navbar: '#146c43', sidebar: '#0f5132' },
            morado: { primario: '#6f42c1', navbar: '#5a2d91', sidebar: '#4c1d7a' },
            naranja: { primario: '#fd7e14', navbar: '#e55a00', sidebar: '#cc4d00' },
            teal: { primario: '#20c997', navbar: '#17a589', sidebar: '#138668' }
        };

        // Sincronizar inputs de color y texto
        function syncColorInputs(colorId, textId) {
            document.getElementById(colorId).addEventListener('input', function() {
                document.getElementById(textId).value = this.value;
                updatePreview();
            });

            document.getElementById(textId).addEventListener('input', function() {
                if(/^#[0-9A-F]{6}$/i.test(this.value)) {
                    document.getElementById(colorId).value = this.value;
                    updatePreview();
                }
            });
        }

        syncColorInputs('color_primario', 'color_primario_text');
        syncColorInputs('color_navbar', 'color_navbar_text');
        syncColorInputs('color_sidebar', 'color_sidebar_text');

        // Actualizar nombre en preview
        document.getElementById('nombre_empresa').addEventListener('input', function() {
            document.getElementById('previewNombre').textContent = this.value;
        });

        // Actualizar vista previa
        function updatePreview() {
            const primario = document.getElementById('color_primario').value;
            const navbar = document.getElementById('color_navbar').value;
            const sidebar = document.getElementById('color_sidebar').value;

            document.documentElement.style.setProperty('--preview-primary', primario);
            document.documentElement.style.setProperty('--preview-navbar', navbar);
            document.documentElement.style.setProperty('--preview-sidebar', sidebar);
        }

        // Temas predefinidos
        document.querySelectorAll('.color-preview').forEach(preview => {
            preview.addEventListener('click', function() {
                const tema = this.dataset.tema;
                if (temas[tema]) {
                    document.getElementById('color_primario').value = temas[tema].primario;
                    document.getElementById('color_primario_text').value = temas[tema].primario;
                    document.getElementById('color_navbar').value = temas[tema].navbar;
                    document.getElementById('color_navbar_text').value = temas[tema].navbar;
                    document.getElementById('color_sidebar').value = temas[tema].sidebar;
                    document.getElementById('color_sidebar_text').value = temas[tema].sidebar;
                    updatePreview();
                }
            });
        });

        // Reset a valores por defecto
        document.getElementById('resetBtn').addEventListener('click', function() {
            if (confirm('¿Estás seguro de restablecer los colores por defecto?')) {
                document.getElementById('color_primario').value = '#0d6efd';
                document.getElementById('color_primario_text').value = '#0d6efd';
                document.getElementById('color_navbar').value = '#343a40';
                document.getElementById('color_navbar_text').value = '#343a40';
                document.getElementById('color_sidebar').value = '#495057';
                document.getElementById('color_sidebar_text').value = '#495057';
                document.getElementById('nombre_empresa').value = 'Sistema de Gestión';
                document.getElementById('previewNombre').textContent = 'Sistema de Gestión';
                updatePreview();
            }
        });

        // Enviar formulario
        document.getElementById('temaForm').addEventListener('submit', function(e) {
            e.preventDefault();

            const formData = new FormData(this);

            Swal.fire({
                title: 'Aplicando cambios...',
                text: 'Guardando tu nueva configuración',
                icon: 'info',
                showConfirmButton: false
            });

            fetch('/configuracion/actualizar-tema/', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.status === 'success') {
                    Swal.fire({
                        title: '¡Éxito!',
                        text: 'Tema actualizado correctamente',
                        icon: 'success',
                        timer: 2000,
                        showConfirmButton: false
                    }).then(() => {
                        window.location.reload();
                    });
                } else {
                    Swal.fire({
                        title: 'Error',
                        text: data.message,
                        icon: 'error'
                    });
                }
            })
            .catch(error => {
                Swal.fire({
                    title: 'Error',
                    text: 'Error de conexión',
                    icon: 'error'
                });
            });
        });

        // Inicializar preview
        updatePreview();
    </script>
</div>