<?php 
	require_once './core/auth_check.php';

	require_once './controllers/App_usuariosController.php';
	$usuariosController = new usuariosController();




?>

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

	.nav-item {
		/*margin: 0.25rem 0;*/
	}

	.nav-link {
		color: rgba(255,255,255,0.8);
		/*padding: 0.75rem 1.5rem;*/
		border-radius: 0;
		transition: all 0.3s ease;
		display: flex;
		align-items: center;
	}

	.nav-link:hover {
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
		background: white;
		padding: 1rem 2rem;
		margin: -2rem -2rem 2rem -2rem;
		border-bottom: 1px solid #dee2e6;
		display: flex;
		justify-content: space-between;
		align-items: center;
	}

	.user-info {
		margin-left: auto;
	}

	.stats-card {
		background: white;
		border-radius: 10px;
		padding: 1.5rem;
		box-shadow: 0 2px 10px rgba(0,0,0,0.1);
		transition: transform 0.3s ease;
	}

	.stats-card:hover {
		transform: translateY(-2px);
	}

	.stats-icon {
		width: 60px;
		height: 60px;
		border-radius: 50%;
		display: flex;
		align-items: center;
		justify-content: center;
		font-size: 1.5rem;
		color: white;
	}

	.welcome-card {
		background: linear-gradient(135deg, var(--primary-color), var(--accent-color));
		color: white;
		border-radius: 15px;
		padding: 2rem;
		margin-bottom: 2rem;
	}

	.btn-primary {
		background-color: var(--primary-color);
		border-color: var(--primary-color);
	}

	.btn-primary:hover {
		background-color: var(--primary-color);
		border-color: var(--primary-color);
		filter: brightness(90%);
	}

	.btn-outline-primary {
		color: var(--primary-color);
		border-color: var(--primary-color);
	}

	.btn-outline-primary:hover {
		background-color: var(--primary-color);
		border-color: var(--primary-color);
	}

	.bg-primary {
		background-color: var(--primary-color) !important;
	}

	@media (max-width: 768px) {
		.sidebar {
			transform: translateX(-100%);
		}

		.main-content {
			margin-left: 0;
		}
	}
	
	/* Estilos específicos para el modal de cambio obligatorio */
	.user-info-card {
		background: #f8f9fa;
		border: 1px solid #dee2e6;
		border-radius: 8px;
		padding: 1rem;
	}

	.requisito-item {
		margin-bottom: 0.25rem;
		transition: all 0.3s ease;
		display: flex;
		align-items: center;
	}

	.requisito-item.text-success i {
		color: #28a745 !important;
	}

	.requisito-item.text-danger i {
		color: #dc3545 !important;
	}

	.password-strength .progress {
		border-radius: 3px;
	}

	.requisitos-container {
		background: #f8f9fa;
		border-radius: 8px;
		padding: 1rem;
		border-left: 4px solid #6c757d;
	}

	#modalCambioObligatorio .modal-content {
		border: none;
		box-shadow: 0 20px 40px rgba(0,0,0,0.15);
	}

	#modalCambioObligatorio .modal-header {
		border-bottom: 2px solid #ffc107;
	}

	/* Estilos para campos requeridos */
	.required::after {
		content: " *";
		color: #dc3545;
	}

	/* Estilos para validación de formulario */
	.form-control:focus {
		border-color: var(--primary-color);
		box-shadow: 0 0 0 0.2rem rgba(27, 94, 32, 0.25);
	}

	.form-control.is-invalid {
		border-color: #dc3545;
		box-shadow: 0 0 0 0.2rem rgba(220, 53, 69, 0.25);
	}

	.form-control.is-valid {
		border-color: #28a745;
		box-shadow: 0 0 0 0.2rem rgba(40, 167, 69, 0.25);
	}

	/* Estilos para input groups */
	.input-group-text {
		background-color: #f8f9fa;
		border-right: none;
		color: #6c757d;
	}

	.input-group .form-control {
		border-left: none;
	}

	.input-group:focus-within .input-group-text {
		border-color: var(--primary-color);
		background-color: rgba(27, 94, 32, 0.1);
	}

	/* Responsive para el modal */
	@media (max-width: 768px) {
		#modalCambioObligatorio .modal-dialog {
			margin: 1rem;
		}

		.requisitos-container .row {
			flex-direction: column;
		}
	}
</style>
<div>
    <!-- Sidebar -->
    <nav class="sidebar">
        <div class="sidebar-header">
            <a href="/dashboard/" class="sidebar-brand">
                <i class="bi bi-building me-2"></i>
                <?php echo (!empty(COMPANY)) ? COMPANY : "Sistema de Gestión" ?>
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
                <a class="nav-link" href="<?php echo SERVERURL?>empresa/">
                    <i class="bi bi-speedometer2"></i>
                    Empresa
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="<?php echo SERVERURL?>usuarios/">
                    <i class="bi bi-people"></i>
                    Usuarios
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="<?php echo SERVERURL?>activos/">
                    <i class="bi bi-box"></i>
                    Activos Fijos
                </a>
            </li>
            <!--<li class="nav-item">
                <a class="nav-link" href="<?php //echo SERVERURL?>configuracion/">
                    <i class="bi bi-cart"></i>
                    Plan de Compras
                </a>
            </li>-->
            <li class="nav-item">
                <a class="nav-link" href="<?php echo SERVERURL?>contratacion/">
                    <i class="bi bi-briefcase"></i>
                    Contratación
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="<?php echo SERVERURL?>pedidos/">
                    <i class="bi bi-clipboard-check"></i>
                    Pedidos Suministros
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="<?php echo SERVERURL?>proveedores/">
                    <i class="bi bi-truck"></i>
                    Proveedores
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="<?php //echo SERVERURL?>solicitudes/">
                    <i class="bi bi-briefcase"></i>
                    Solicitudes Servicios
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="<?php echo SERVERURL?>inventarios/">
                    <i class="bi bi-calculator"></i>
                    Inventario y Facturación
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="<?php echo SERVERURL?>mesaayudas/">
                    <i class="bi bi-headset"></i>
                    Mesa de Ayudas
                </a>
            </li>

            <hr class="my-3" style="border-color: rgba(255,255,255,0.2);">

            <li class="nav-item">
                <a class="nav-link" href="<?php echo SERVERURL?>configuracion/">
                    <i class="bi bi-palette"></i>
                    Configuración Empresa
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link" href="#" onclick="logout(); return false;">
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
            <h4 class="mb-0">Dashboard</h4>
            <div class="user-info dropdown ">
				<div class="user-section dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false" style="cursor: pointer;">
					<span class="text-muted">Bienvenido, </span>
					<strong><?php echo $_SESSION['UsuarioUsuario']; ?></strong>
				</div>
				<ul class="dropdown-menu dropdown-menu-end dropdown-person-perfil">
					<li><a class="dropdown-item" href="#"><i class="bi bi-person me-2"></i>Perfil</a></li>
					<li><a class="dropdown-item" href="#"><i class="bi bi-gear me-2"></i>Configuración</a></li>
					<li><hr class="dropdown-divider"></li>
					<li><a class="dropdown-item text-danger" href="#" onclick="logout(); return false;"><i class="bi bi-box-arrow-right me-2"></i>Cerrar Sesión</a></li>

				</ul>
			</div>
			
        </div>

        <!-- Welcome Card -->
        <div class="welcome-card">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <h2>¡Bienvenido al Sistema de Gestión!</h2>
                    <p class="mb-0">Accede a todos los módulos desde el menú lateral. Tu sesión está activa y segura.</p>
                </div>
                <div class="col-md-4 text-center">
                    <i class="bi bi-person-circle" style="font-size: 4rem;"></i>
                </div>
            </div>
        </div>

        <!-- Stats Cards -->
        <div class="row">
            <div class="col-md-3 col-sm-6 mb-4">
                <div class="stats-card">
                    <div class="d-flex align-items-center">
                        <div class="stats-icon bg-primary">
                            <i class="bi bi-people"></i>
                        </div>
                        <div class="ms-3">
                            <h5 class="mb-0">Usuarios</h5>
                            <p class="text-muted mb-0">Gestión completa</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-3 col-sm-6 mb-4">
                <div class="stats-card">
                    <div class="d-flex align-items-center">
                        <div class="stats-icon" style="background-color: var(--accent-color);">
                            <i class="bi bi-box"></i>
                        </div>
                        <div class="ms-3">
                            <h5 class="mb-0">Activos</h5>
                            <p class="text-muted mb-0">Control total</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-3 col-sm-6 mb-4">
                <div class="stats-card">
                    <div class="d-flex align-items-center">
                        <div class="stats-icon" style="background-color: #ffc107;">
                            <i class="bi bi-list-task"></i>
                        </div>
                        <div class="ms-3">
                            <h5 class="mb-0">Tareas</h5>
                            <p class="text-muted mb-0">Pendientes</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-3 col-sm-6 mb-4">
                <div class="stats-card">
                    <div class="d-flex align-items-center">
                        <div class="stats-icon" style="background-color: #17a2b8;">
                            <i class="bi bi-graph-up"></i>
                        </div>
                        <div class="ms-3">
                            <h5 class="mb-0">Reportes</h5>
                            <p class="text-muted mb-0">Análisis</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">Acciones Rápidas</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <a href="#" class="btn btn-outline-primary w-100">
                                    <i class="bi bi-plus-circle me-2"></i>
                                    Nueva Tarea
                                </a>
                            </div>
                            <div class="col-md-4 mb-3">
                                <a href="#" class="btn btn-outline-success w-100">
                                    <i class="bi bi-clipboard-plus me-2"></i>
                                    Registrar Activo
                                </a>
                            </div>
                            <div class="col-md-4 mb-3">
                                <a href="#" class="btn btn-outline-info w-100">
                                    <i class="bi bi-file-earmark-text me-2"></i>
                                    Generar Reporte
                                </a>
                            </div>
							
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

</div>