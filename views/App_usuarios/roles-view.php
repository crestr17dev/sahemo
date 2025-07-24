<?php 

require_once './core/auth_check.php';

require_once './controllers/App_rolesController.php';
$rolesController = new rolesController();

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
	
	body {
		background-color: #f8f9fa;
		font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
	}

	/* Navbar Superior */
	.top-navbar {
		background: var(--primary-color);
		color: white;
		padding: 0.75rem 2rem;
		box-shadow: 0 2px 4px rgba(0,0,0,0.1);
		display: flex;
		justify-content: space-between;
		align-items: center;
	}

	.navbar-left {
		display: flex;
		align-items: center;
		gap: 2rem;
	}

	.navbar-brand {
		color: white !important;
		font-weight: bold;
		text-decoration: none;
	}

	.navbar-links {
		display: flex;
		align-items: center;
		gap: 1.5rem;
	}

	.navbar-links .nav-link {
		color: rgba(255,255,255,0.9) !important;
		margin: 0;
		padding: 0.5rem 1rem !important;
		border-radius: 6px;
		transition: all 0.3s ease;
		text-decoration: none;
		font-size: 0.9rem;
	}

	.navbar-links .nav-link:hover,
	.navbar-links .nav-link.active {
		background: rgba(255,255,255,0.2);
		color: white !important;
	}

	.user-section {
		color: white;
		font-size: 0.9rem;
		display: flex;
		align-items: center;
		gap: 0.5rem;
	}

	.container-fluid-custom {
		display: flex;
		min-height: calc(100vh - 76px);
	}

	/* Sidebar */
	.sidebar {
		width: 260px;
		background: white;
		box-shadow: 2px 0 10px rgba(0,0,0,0.1);
		padding: 0;
		flex-shrink: 0;
	}

	.sidebar-section {
		padding: 1.5rem 0 0.5rem 0;
	}

	.sidebar-header {
		color: #6c757d;
		font-size: 0.75rem;
		font-weight: 600;
		text-transform: uppercase;
		letter-spacing: 0.5px;
		padding: 0 1.5rem 0.75rem 1.5rem;
		margin-bottom: 0.5rem;
	}

	.sidebar .nav-link {
		color: #495057;
		padding: 0.75rem 1.5rem;
		display: flex;
		align-items: center;
		transition: all 0.3s ease;
		border: none;
		border-radius: 0;
	}

	.sidebar .nav-link:hover {
		background-color: #f8f9fa;
		color: var(--primary-color);
	}

	.sidebar .nav-link.active {
		background-color: var(--primary-color);
		color: white;
	}

	.sidebar .nav-link i {
		margin-right: 0.75rem;
		width: 18px;
		text-align: center;
	}

	.sidebar hr {
		margin: 1rem 1.5rem;
		color: #dee2e6;
	}

	/* Contenido Principal */
	.main-content {
		flex: 1;
		padding: 2rem;
		overflow-x: auto;
	}

	/* Breadcrumb */
	.breadcrumb {
		background: none;
		padding: 0;
		margin-bottom: 1.5rem;
	}

	.breadcrumb-item a {
		color: var(--primary-color);
		text-decoration: none;
	}

	/* Header */
	.page-header {
		display: flex;
		justify-content: space-between;
		align-items: center;
		margin-bottom: 2rem;
	}

	.page-title {
		margin: 0;
		color: #343a40;
	}

	.page-title i {
		color: var(--primary-color);
	}

	/* Tarjetas de Estadísticas */
	.stats-row {
		margin-bottom: 2rem;
	}

	.stats-card {
		background: white;
		border-radius: 12px;
		padding: 1.5rem;
		height: 120px;
		display: flex;
		align-items: center;
		box-shadow: 0 2px 10px rgba(0,0,0,0.08);
		transition: transform 0.3s ease;
		position: relative;
		overflow: hidden;
	}

	.stats-card:hover {
		transform: translateY(-2px);
	}

	.stats-card.purple {
		background: linear-gradient(135deg, #6f42c1 0%, #5a2d91 100%);
		color: white;
	}

	.stats-card.pink {
		background: linear-gradient(135deg, #e83e8c 0%, #d91a72 100%);
		color: white;
	}

	.stats-card.cyan {
		background: linear-gradient(135deg, #17a2b8 0%, #138496 100%);
		color: white;
	}

	.stats-card.green {
		background: linear-gradient(135deg, #28a745 0%, #1e7e34 100%);
		color: white;
	}
	.stats-card.orange {
		background: linear-gradient(135deg, #fd7e14 0%, #e8590c 100%);
		color: white;
	}

	.stats-card.blue {
		background: linear-gradient(135deg, #0d6efd 0%, #0b5ed7 100%);
		color: white;
	}

	.stats-card.red {
		background: linear-gradient(135deg, #dc3545 0%, #c82333 100%);
		color: white;
	}

	.stats-content {
		flex: 1;
	}

	.stats-number {
		font-size: 2.5rem;
		font-weight: bold;
		line-height: 1;
		margin-bottom: 0.25rem;
	}

	.stats-label {
		font-size: 0.95rem;
		opacity: 0.9;
	}

	.stats-icon {
		font-size: 2.5rem;
		opacity: 0.8;
		margin-left: 1rem;
	}

	/* Tabla de Roles */
	.table-container {
		background: white;
		border-radius: 12px;
		box-shadow: 0 2px 10px rgba(0,0,0,0.08);
		overflow: hidden;
	}

	.table-header {
		background: linear-gradient(135deg, #495057 0%, #343a40 100%);
		color: white;
		padding: 1.25rem 1.5rem;
		display: flex;
		align-items: center;
		justify-content: space-between;
	}

	.table-title {
		margin: 0;
		font-size: 1.1rem;
		font-weight: 600;
		display: flex;
		align-items: center;
		gap: 0.75rem;
	}

	.view-options {
		display: flex;
		border: 1px solid rgba(255,255,255,0.3);
		border-radius: 6px;
		overflow: hidden;
	}

	.view-btn {
		background: transparent;
		border: none;
		padding: 0.5rem 0.75rem;
		color: rgba(255,255,255,0.8);
		transition: all 0.2s ease;
	}

	.view-btn.active {
		background-color: rgba(255,255,255,0.2);
		color: white;
	}

	.view-btn:hover {
		background-color: rgba(255,255,255,0.1);
		color: white;
	}

	.table-filters {
		background: #f8f9fa;
		padding: 1.25rem 1.5rem;
		border-bottom: 1px solid #dee2e6;
	}

	.roles-table {
		margin: 0;
	}

	.roles-table thead th {
		background-color: #f8f9fa;
		border: none;
		padding: 1rem 1.5rem;
		font-weight: 600;
		color: #495057;
		font-size: 0.85rem;
		text-transform: uppercase;
		letter-spacing: 0.5px;
	}

	.roles-table tbody td {
		padding: 1rem 1.5rem;
		vertical-align: middle;
		border: none;
		border-bottom: 1px solid #f1f3f4;
	}

	.roles-table tbody tr:hover {
		background-color: #f8f9fa;
	}

	/* Badges */
	.badge-custom {
		padding: 0.5rem 0.75rem;
		border-radius: 6px;
		font-size: 0.75rem;
		font-weight: 500;
	}

	.badge-nivel {
		background-color: #6f42c1;
		color: white;
	}

	.badge-activo {
		background-color: #28a745;
		color: white;
	}

	.badge-inactivo {
		background-color: #6c757d;
		color: white;
	}

	.badge-codigo {
		background-color: #e9ecef;
		color: #495057;
		font-family: monospace;
	}

	/* Botones de Acción */
	.btn-actions {
		display: flex;
		gap: 0.25rem;
	}

	.btn-action {
		width: 32px;
		height: 32px;
		border-radius: 6px;
		display: flex;
		align-items: center;
		justify-content: center;
		font-size: 0.8rem;
		border: 1px solid;
		transition: all 0.2s ease;
	}

	.btn-action:hover {
		transform: translateY(-1px);
	}

	/* Botones */
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

	/* Responsive */
	@media (max-width: 768px) {
		.container-fluid-custom {
			flex-direction: column;
		}

		.sidebar {
			width: 100%;
			order: 2;
		}

		.main-content {
			order: 1;
			padding: 1rem;
		}
	}

	/* ESTILOS MODAL */
	.modal-content {
		border: none;
		border-radius: 12px;
		box-shadow: 0 10px 30px rgba(0,0,0,0.15);
	}

	.modal-header {
		background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
		color: white;
		border-radius: 12px 12px 0 0;
		padding: 1.5rem 2rem;
		border-bottom: none;
	}

	.modal-title {
		font-weight: 600;
		display: flex;
		align-items: center;
		gap: 0.75rem;
	}

	.modal-title i {
		font-size: 1.25rem;
	}

	.btn-close {
		filter: brightness(0) invert(1);
		opacity: 0.8;
	}

	.btn-close:hover {
		opacity: 1;
	}

	.modal-body {
		padding: 2rem;
	}

	.form-section {
		margin-bottom: 2rem;
	}

	.form-section-title {
		color: var(--primary-color);
		font-size: 1rem;
		font-weight: 600;
		margin-bottom: 1rem;
		padding-bottom: 0.5rem;
		border-bottom: 2px solid #e9ecef;
		display: flex;
		align-items: center;
		gap: 0.5rem;
	}

	.form-label {
		color: #495057;
		font-weight: 500;
		margin-bottom: 0.5rem;
	}

	.required::after {
		content: " *";
		color: #dc3545;
	}

	.form-control:focus {
		border-color: var(--primary-color);
		box-shadow: 0 0 0 0.2rem rgba(27, 94, 32, 0.25);
	}

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

	.form-control.is-invalid {
		border-color: #dc3545;
	}

	.form-control.is-valid {
		border-color: #28a745;
	}

	.invalid-feedback {
		display: block;
		margin-top: 0.25rem;
		font-size: 0.875rem;
		color: #dc3545;
	}

	/*perfil de usuario*/
	.dropdown-person-perfil{
		background-color: var(--primary-color);
	}
	.dropdown-person-perfil .dropdown-item{
		color: white;
	}
	.dropdown-person-perfil .dropdown-item:hover {
		background-color: #f8f9fa;
		color: black;
		transform: translateX(3px);
	}

	/* Dropdown de Estados */
	.estado-dropdown {
		min-width: 100px;
		font-size: 0.875rem;
		border-radius: 6px;
		transition: all 0.2s ease;
	}

	.estado-dropdown:hover {
		transform: translateY(-1px);
		box-shadow: 0 2px 8px rgba(0,0,0,0.15);
	}

	.dropdown-menu {
		border-radius: 8px;
		box-shadow: 0 4px 15px rgba(0,0,0,0.1);
		border: 1px solid #e9ecef;
		padding: 0.5rem 0;
	}

	.dropdown-menu .dropdown-item {
		padding: 0.6rem 1rem;
		font-size: 0.875rem;
		transition: all 0.2s ease;
		border: none;
		background: none;
	}

	.dropdown-menu .dropdown-item:hover {
		background-color: #f8f9fa;
		transform: translateX(3px);
	}

	.dropdown-menu .dropdown-item:active {
		background-color: #e9ecef;
	}

	.dropdown-menu .dropdown-item i {
		width: 18px;
		text-align: center;
	}

	.dropdown-divider {
		margin: 0.5rem 0;
		border-top: 1px solid #e9ecef;
	}

	.modal-cambio-estado {
		max-width: 600px;
	}

	.modal-cambio-estado .modal-body {
		padding: 2rem;
	}

	.estado-info-card {
		background: #f8f9fa;
		border-radius: 8px;
		padding: 1rem;
		margin: 1rem 0;
		border-left: 4px solid #dee2e6;
	}

	.estado-info-card.success {
		border-left-color: #28a745;
		background: #d4edda;
	}

	.estado-info-card.warning {
		border-left-color: #ffc107;
		background: #fff3cd;
	}

	.estado-info-card.danger {
		border-left-color: #dc3545;
		background: #f8d7da;
	}

	.icono-estado-grande {
		font-size: 3rem;
		margin-bottom: 1rem;
	}

	.spin {
		animation: spin 1s linear infinite;
	}

	@keyframes spin {
		from { transform: rotate(0deg); }
		to { transform: rotate(360deg); }
	}
	
	.name_principal{
		color: var(--primary-color);
	}

	/* Estilos específicos para roles */
	.nivel-badge {
		display: inline-flex;
		align-items: center;
		gap: 0.25rem;
	}

	.nivel-indicator {
		width: 8px;
		height: 8px;
		border-radius: 50%;
		display: inline-block;
	}

	.nivel-0 { background-color: #dc3545; } /* SYSTEM_ADMIN - Rojo */
	.nivel-1 { background-color: #fd7e14; } /* SUPER_ADMIN - Naranja */
	.nivel-2 { background-color: #ffc107; } /* ADMIN - Amarillo */
	.nivel-3 { background-color: #20c997; } /* MANAGER - Verde agua */
	.nivel-4 { background-color: #0dcaf0; } /* USER - Azul claro */
	.nivel-5 { background-color: #6c757d; } /* READONLY - Gris */

	/* Estilos para selector de permisos */
	.permisos-grid {
		display: grid;
		grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
		gap: 1rem;
		max-height: 400px;
		overflow-y: auto;
		border: 1px solid #dee2e6;
		border-radius: 8px;
		padding: 1rem;
		background: #f8f9fa;
	}

	.permiso-group {
		background: white;
		border-radius: 6px;
		padding: 1rem;
		border: 1px solid #e9ecef;
	}

	.permiso-group-title {
		font-weight: 600;
		color: var(--primary-color);
		margin-bottom: 0.75rem;
		font-size: 0.9rem;
		text-transform: uppercase;
		letter-spacing: 0.5px;
	}

	.permiso-item {
		display: flex;
		align-items: center;
		gap: 0.5rem;
		margin-bottom: 0.5rem;
	}

	.permiso-item:last-child {
		margin-bottom: 0;
	}

	.permiso-checkbox {
		width: 1.1em;
		height: 1.1em;
		margin: 0;
	}

	.permiso-label {
		font-size: 0.85rem;
		margin: 0;
		cursor: pointer;
		line-height: 1.3;
	}

	/* Estilos para indicadores de nivel de rol */
	.rol-nivel-info {
		background: linear-gradient(135deg, #e3f2fd 0%, #bbdefb 100%);
		border-left: 4px solid #2196f3;
		padding: 1rem;
		border-radius: 0 8px 8px 0;
		margin: 1rem 0;
	}

	.rol-nivel-info h6 {
		color: #1565c0;
		margin-bottom: 0.5rem;
	}

	.rol-nivel-info small {
		color: #424242;
		line-height: 1.4;
	}

	/* Animación para validación en tiempo real */
	.form-control.validating {
		border-color: #ffc107 !important;
		box-shadow: 0 0 0 0.2rem rgba(255, 193, 7, 0.25) !important;
	}
</style>

<div>
    <!-- Navbar Superior -->
    <nav class="top-navbar">
        <div class="navbar-left">
            <a class="navbar-brand" href="#">
                <i class="bi bi-shield-check me-2"></i>
                <?php echo (!empty(COMPANY)) ? COMPANY : "Sistema de Gestión Administrativa" ?>
            </a>

            <div class="navbar-links">
                <a class="nav-link" href="<?php echo SERVERURL?>dashboard/">
                    <i class="bi bi-speedometer2 me-1"></i>
                    Dashboard
                </a>
                <a class="nav-link" href="<?php echo SERVERURL?>empresa/">
                    <i class="bi bi-building me-1"></i>
                    Empresas
                </a>
                <a class="nav-link" href="<?php echo SERVERURL?>usuarios/">
                    <i class="bi bi-people me-1"></i>
                    Usuarios
                </a>
                <a class="nav-link active" href="<?php echo SERVERURL?>roles/">
                    <i class="bi bi-shield-check me-1"></i>
                    Roles
                </a>
            </div>
        </div>
		<div class="dropdown ">
			<div class="user-section dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false" style="cursor: pointer;">
				<i class="bi bi-person-circle me-1"></i>
				<?php echo $_SESSION['UsuarioUsuario']; ?>
			</div>
			<ul class="dropdown-menu dropdown-menu-end dropdown-person-perfil">
				<li><a class="dropdown-item" href="#"><i class="bi bi-person me-2"></i>Perfil</a></li>
				<li><a class="dropdown-item" href="#"><i class="bi bi-gear me-2"></i>Configuración</a></li>
				<li><hr class="dropdown-divider"></li>
				<li><a class="dropdown-item text-danger" href="#" onclick="logout(); return false;"><i class="bi bi-box-arrow-right me-2"></i>Cerrar Sesión</a></li>
			</ul>
		</div>
    </nav>

    <div class="container-fluid-custom">
        <!-- Sidebar -->
        <div class="sidebar">
            <div class="sidebar-section">
                <div class="sidebar-header">USUARIOS</div>
                <nav class="nav flex-column">
                    <a class="nav-link" href="<?php echo SERVERURL?>usuarios/">
                        <i class="bi bi-people"></i>
                        Lista de Usuarios
                    </a>
                </nav>

                <hr>

                <div class="sidebar-header">ROLES Y PERMISOS</div>
                <nav class="nav flex-column">
                    <a class="nav-link active" href="<?php echo SERVERURL?>roles/">
                        <i class="bi bi-shield-check"></i>
                        Gestión de Roles
                    </a>
                    <a class="nav-link" href="#" data-bs-toggle="modal" data-bs-target="#modalNuevoRol">
                        <i class="bi bi-plus-circle"></i>
                        Nuevo Rol
                    </a>
                    <a class="nav-link" href="<?php echo SERVERURL?>permisos/">
                        <i class="bi bi-key"></i>
                        Gestión de Permisos
                    </a>
                </nav>

                <hr>

                <div class="sidebar-header">REPORTES</div>
                <nav class="nav flex-column">
                    <a class="nav-link" href="#" onclick="exportarRoles()">
                        <i class="bi bi-file-earmark-bar-graph"></i>
                        Reporte de Roles
                    </a>
                    <a class="nav-link" href="#" onclick="alert('Próximamente')">
                        <i class="bi bi-file-earmark-text"></i>
                        Asignaciones de Roles
                    </a>
                </nav>
            </div>
        </div>

        <!-- Contenido Principal -->
        <div class="main-content">
            <!-- Breadcrumb -->
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="/">Inicio</a></li>
                    <li class="breadcrumb-item"><a href="<?php echo SERVERURL?>usuarios/">Usuarios</a></li>
                    <li class="breadcrumb-item active">Gestión de Roles</li>
                </ol>
            </nav>

            <!-- Header -->
            <div class="page-header">
                <div>
                    <h2 class="page-title">
                        <i class="bi bi-shield-check me-2"></i>
                        Gestión de Roles
                    </h2>
                    <p class="text-muted mb-0">Administra los roles y permisos del sistema</p>
                </div>
                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalNuevoRol">
                    <i class="bi bi-plus-circle me-1"></i>
                    Nuevo Rol
                </button>
            </div>

            <!-- Tarjetas de Estadísticas -->
            <div class="row stats-row">
                <div class="col-lg-2 col-md-6 mb-3">
                    <div class="stats-card purple">
                        <div class="stats-content">
                            <div class="stats-number">#</div>
                            <div class="stats-label">Total Roles</div>
                        </div>
                        <div class="stats-icon">
                            <i class="bi bi-shield-check"></i>
                        </div>
                    </div>
                </div>

                <div class="col-lg-2 col-md-6 mb-3">
                    <div class="stats-card green">
                        <div class="stats-content">
                            <div class="stats-number">#</div>
                            <div class="stats-label">Roles Activos</div>
                        </div>
                        <div class="stats-icon">
                            <i class="bi bi-shield-fill-check"></i>
                        </div>
                    </div>
                </div>

                <div class="col-lg-2 col-md-6 mb-3">
                    <div class="stats-card orange">
                        <div class="stats-content">
                            <div class="stats-number">#</div>
                            <div class="stats-label">Roles Inactivos</div>
                        </div>
                        <div class="stats-icon">
                            <i class="bi bi-shield-fill-exclamation"></i>
                        </div>
                    </div>
                </div>

                <div class="col-lg-2 col-md-6 mb-3">
                    <div class="stats-card cyan">
                        <div class="stats-content">
                            <div class="stats-number">#</div>
                            <div class="stats-label">Permisos Disponibles</div>
                        </div>
                        <div class="stats-icon">
                            <i class="bi bi-key"></i>
                        </div>
                    </div>
                </div>

                <div class="col-lg-2 col-md-6 mb-3">
                    <div class="stats-card blue">
                        <div class="stats-content">
                            <div class="stats-number">#</div>
                            <div class="stats-label">Usuarios con Roles</div>
                        </div>
                        <div class="stats-icon">
                            <i class="bi bi-people-fill"></i>
                        </div>
                    </div>
                </div>

                <div class="col-lg-2 col-md-6 mb-3">
                    <div class="stats-card red">
                        <div class="stats-content">
                            <div class="stats-number">#</div>
                            <div class="stats-label">Roles Sistema</div>
                        </div>
                        <div class="stats-icon">
                            <i class="bi bi-gear-fill"></i>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tabla de Roles -->
            <div class="table-container">
                <div class="table-header">
                    <h5 class="table-title">
                        <i class="bi bi-table"></i>
                        Roles del Sistema
                        <span class="badge bg-light text-dark ms-2">#</span>
                    </h5>

                    <div class="view-options">
                        <button class="view-btn active" onclick="toggleViewRoles('list')" title="Vista en Lista">
                            <i class="bi bi-list"></i>
                        </button>
                        <button class="view-btn" onclick="toggleViewRoles('grid')" title="Vista en Cuadrícula">
                            <i class="bi bi-grid"></i>
                        </button>
                    </div>
                </div>

                <!-- Filtros -->
                <div class="table-filters">
                    <div class="row g-3">
						<input type="hidden" name="csrf_token_list_roles" value="<?php echo $rolesController->obtener_token_csrf('listRoles'); ?>">
                        <div class="col-md-4">
                            <div class="input-group">
                                <span class="input-group-text">
                                    <i class="bi bi-search"></i>
                                </span>
                                <input type="text" class="form-control" id="sharerol" name="sharerol" placeholder="Buscar por nombre, código, descripción...">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <select class="form-select" id="estadorol" name="estadorol">
                                <option value="">Todos los estados</option>
                                <option value="Activo">Solo Activos</option>
                                <option value="Inactivo">Solo Inactivos</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <select class="form-select" id="nivelrol" name="nivelrol">
                                <option value="">Todos los niveles</option>
                                <option value="0">Nivel 0 - System Admin</option>
                                <option value="1">Nivel 1 - Super Admin</option>
                                <option value="2">Nivel 2 - Admin</option>
                                <option value="3">Nivel 3 - Manager</option>
                                <option value="4">Nivel 4 - User</option>
                                <option value="5">Nivel 5 - ReadOnly</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <button class="btn btn-outline-primary" onclick="cargarRoles(1)">
                                <i class="bi bi-funnel me-1"></i>
                                Filtrar
                            </button>
                            <button class="btn btn-outline-secondary ms-2" onclick="limpiarFiltrosRoles()">
                                <i class="bi bi-arrow-clockwise me-1"></i>
                                Limpiar
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Tabla -->
                <div class="table-responsive listadoroles">
                    <!-- Aquí se carga la tabla via AJAX -->
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Nuevo Rol -->
    <div class="modal fade" id="modalNuevoRol" tabindex="-1" aria-labelledby="modalNuevoRolLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalNuevoRolLabel">
                        <i class="bi bi-plus-circle"></i>
                        Crear Nuevo Rol
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                </div>
                
                <div class="modal-body">
                    <form id="formNuevoRol" novalidate>
						<input type="hidden" name="csrf_token" value="<?php echo $rolesController->obtener_token_csrf('nuevoRol'); ?>">
                        
                        <!-- Información Básica del Rol -->
                        <div class="form-section">
                            <div class="form-section-title">
                                <i class="bi bi-info-circle"></i>
                                Información Básica del Rol
                            </div>
                            
                            <div class="row">
                               <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="rolNombre" class="form-label required">Nombre del Rol</label>
                                        <div class="input-group">
                                            <span class="input-group-text">
                                                <i class="bi bi-shield-check"></i>
                                            </span>
                                            <input type="text" class="form-control" id="rolNombre" name="rol-nombre" 
                                                   placeholder="Ej: Gerente de Ventas" maxlength="100" required>
                                        </div>
                                        <div class="invalid-feedback"></div>
                                    </div>
                                </div>

                            <div class="row">
                                
                            </div>

                            <div class="row">
                                <div class="col-md-12">
                                    <div class="mb-3">
                                        <label for="rolDescripcion" class="form-label">Descripción del Rol</label>
                                        <div class="input-group">
                                            <span class="input-group-text">
                                                <i class="bi bi-card-text"></i>
                                            </span>
                                            <textarea class="form-control" id="rolDescripcion" name="rol-descripcion" 
                                                      rows="3" maxlength="500" 
                                                      placeholder="Describe las responsabilidades y alcance de este rol..."></textarea>
                                        </div>
                                        <div class="form-text">
                                            <i class="bi bi-info-circle me-1"></i>
                                            Opcional. Máximo 500 caracteres.
                                        </div>
                                        <div class="invalid-feedback"></div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Información del Nivel -->
                        <div class="rol-nivel-info" id="infoNivelRol" style="display: none;">
                            <h6><i class="bi bi-info-circle me-2"></i>Información del Nivel</h6>
                            <small id="descripcionNivelRol">Selecciona un nivel para ver su descripción.</small>
                        </div>

                        <!-- Asignación de Permisos -->
                        <div class="form-section">
                            <div class="form-section-title">
                                <i class="bi bi-key"></i>
                                Asignación de Permisos
                            </div>
                            
                            <div class="alert alert-info">
                                <i class="bi bi-lightbulb me-2"></i>
                                <strong>¿Cómo asignar permisos?</strong>
                                <ul class="mb-0 mt-2">
                                    <li>Selecciona los permisos específicos que tendrá este rol</li>
                                    <li>Los permisos están agrupados por módulos para facilitar su gestión</li>
                                    <li>Puedes seleccionar todos los permisos de un grupo haciendo clic en el título</li>
                                    <li>Los roles de mayor nivel heredan automáticamente algunos permisos básicos</li>
                                </ul>
                            </div>

                            <!-- Contenedor de permisos que se carga via AJAX -->
                            <div id="permisosContainer">
                                <div class="text-center py-4">
                                    <i class="bi bi-arrow-clockwise spin fa-2x text-muted mb-2"></i>
                                    <p class="text-muted">Cargando permisos disponibles...</p>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="bi bi-x-circle me-1"></i>
                        Cancelar
                    </button>
                    <button type="button" class="btn btn-primary" onclick="guardarRol()">
                        <i class="bi bi-check-circle me-1"></i>
                        Crear Rol
                    </button>
                </div>
            </div>
        </div>
    </div>

	<!-- Modal Editar Rol -->
	<div class="modal fade" id="modalEditarRol" tabindex="-1" aria-labelledby="modalEditarRolLabel" aria-hidden="true">
		<div class="modal-dialog modal-xl">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title" id="modalEditarRolLabel">
						<i class="bi bi-pencil-square"></i>
						<span id="nombreRolEditar">Editar Rol:</span>
					</h5>
					<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
				</div>

				<div class="modal-body">
					<!-- Tabs Navigation -->
					<ul class="nav nav-tabs" id="editarRolTabs" role="tablist">
						<li class="nav-item" role="presentation">
							<button class="nav-link active" id="info-rol-tab" data-bs-toggle="tab" data-bs-target="#info-rol-pane" type="button" role="tab">
								<i class="bi bi-info-circle me-2"></i>
								Información del Rol
							</button>
						</li>
						<li class="nav-item" role="presentation">
							<button class="nav-link" id="permisos-rol-tab" data-bs-toggle="tab" data-bs-target="#permisos-rol-pane" type="button" role="tab">
								<i class="bi bi-key me-2"></i>
								Permisos Asignados
							</button>
						</li>
						<li class="nav-item" role="presentation">
							<button class="nav-link" id="usuarios-rol-tab" data-bs-toggle="tab" data-bs-target="#usuarios-rol-pane" type="button" role="tab">
								<i class="bi bi-people me-2"></i>
								Usuarios con este Rol
							</button>
						</li>
					</ul>

					<!-- Tabs Content -->
					<div class="tab-content mt-3" id="editarRolTabsContent">

						<!-- TAB 1: INFORMACIÓN DEL ROL -->
						<div class="tab-pane fade show active" id="info-rol-pane" role="tabpanel">
							<form id="formEditarRolInfo" novalidate>
								<input type="hidden" id="rolIdEditar" name="rol-id-editar">
								<input type="hidden" id="tokenRolEspecifico" name="tokenRolEspecifico">
								<input type="hidden" name="csrf_token_editar" value="<?php echo $rolesController->obtener_token_csrf('editarRol'); ?>">
									
								<!-- Información de Solo Lectura -->
								<div class="form-section">
									<div class="form-section-title">
										<i class="bi bi-info-circle"></i>
										Información del Sistema
									</div>

									<div class="row">
										<div class="col-md-4">
											<div class="mb-3">
												<label for="verRolCodigo" class="form-label">Código</label>
												<div class="input-group">
													<span class="input-group-text">
														<i class="bi bi-hash"></i>
													</span>
													<input type="text" class="form-control bg-light" id="verRolCodigo" readonly>
												</div>
											</div>
										</div>

										<div class="col-md-4">
											<div class="mb-3">
												<label for="verRolEstado" class="form-label">Estado</label>
												<div class="input-group">
													<span class="input-group-text">
														<i class="bi bi-check-circle"></i>
													</span>
													<input type="text" class="form-control bg-light" id="verRolEstado" readonly>
												</div>
											</div>
										</div>

										<div class="col-md-4">
											<div class="mb-3">
												<label for="verRolFechaCreacion" class="form-label">Fecha Creación</label>
												<div class="input-group">
													<span class="input-group-text">
														<i class="bi bi-calendar-plus"></i>
													</span>
													<input type="text" class="form-control bg-light" id="verRolFechaCreacion" readonly>
												</div>
											</div>
										</div>
									</div>
								</div>

								<!-- Información Editable del Rol -->
								<div class="form-section">
									<div class="form-section-title">
										<i class="bi bi-pencil-square"></i>
										Información Editable
									</div>

									<div class="row">
										<div class="col-md-6">
											<div class="mb-3">
												<label for="editarRolNivel" class="form-label required">Nivel del Rol</label>
												<div class="input-group">
													<span class="input-group-text">
														<i class="bi bi-sort-numeric-down"></i>
													</span>
													<select class="form-select" id="editarRolNivel" name="editar-rol-nivel" required>
														<option value="">Seleccionar Nivel</option>
														<option value="1">Nivel 1 - Super Admin</option>
														<option value="2">Nivel 2 - Admin</option>
														<option value="3">Nivel 3 - Manager/Supervisor</option>
														<option value="4">Nivel 4 - Usuario Estándar</option>
														<option value="5">Nivel 5 - Solo Lectura</option>
													</select>
												</div>
												<div class="invalid-feedback"></div>
											</div>
										</div>

										<div class="col-md-6">
											<div class="mb-3">
												<label for="editarRolNombre" class="form-label required">Nombre del Rol</label>
												<div class="input-group">
													<span class="input-group-text">
														<i class="bi bi-shield-check"></i>
													</span>
													<input type="text" class="form-control" id="editarRolNombre" name="editar-rol-nombre" 
														   placeholder="Ej: Gerente de Ventas" maxlength="100" required>
												</div>
												<div class="invalid-feedback"></div>
											</div>
										</div>
									</div>

									<div class="row">
										<div class="col-md-12">
											<div class="mb-3">
												<label for="editarRolDescripcion" class="form-label">Descripción del Rol</label>
												<div class="input-group">
													<span class="input-group-text">
														<i class="bi bi-card-text"></i>
													</span>
													<textarea class="form-control" id="editarRolDescripcion" name="editar-rol-descripcion" 
															  rows="3" maxlength="500" 
															  placeholder="Describe las responsabilidades y alcance de este rol..."></textarea>
												</div>
												<div class="form-text">
													<i class="bi bi-info-circle me-1"></i>
													Opcional. Máximo 500 caracteres.
												</div>
												<div class="invalid-feedback"></div>
											</div>
										</div>
									</div>
								</div>
							</form>
						</div>

						<!-- TAB 2: PERMISOS ASIGNADOS -->
						<div class="tab-pane fade" id="permisos-rol-pane" role="tabpanel">
							<div class="text-center py-5">
								<i class="bi bi-key fa-3x text-muted mb-3"></i>
								<h5 class="text-muted">Gestión de Permisos</h5>
								<p class="text-muted">Administra los permisos asignados a este rol</p>
								
								<!-- Aquí se cargarán los permisos via AJAX -->
								<div id="permisosRolContainer">
									<div class="text-center py-4">
										<i class="bi bi-arrow-clockwise spin fa-2x text-muted mb-2"></i>
										<p class="text-muted">Cargando permisos...</p>
									</div>
								</div>
							</div>
						</div>

						<!-- TAB 3: USUARIOS CON ESTE ROL -->
						<div class="tab-pane fade" id="usuarios-rol-pane" role="tabpanel">
							<div class="text-center py-5">
								<i class="bi bi-people fa-3x text-muted mb-3"></i>
								<h5 class="text-muted">Usuarios Asignados</h5>
								<p class="text-muted">Lista de usuarios que tienen asignado este rol</p>
								
								<!-- Aquí se cargarán los usuarios via AJAX -->
								<div id="usuariosRolContainer">
									<div class="text-center py-4">
										<i class="bi bi-arrow-clockwise spin fa-2x text-muted mb-2"></i>
										<p class="text-muted">Cargando usuarios...</p>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>

				<div class="modal-footer" id="modalFooterRolInfo">
					<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
						<i class="bi bi-x-circle me-1"></i>
						Cancelar
					</button>
					<button type="button" class="btn btn-primary" onclick="guardarCambiosRol()">
						<i class="bi bi-check-circle me-1"></i>
						Guardar Cambios
					</button>
				</div>
			</div>
		</div>
	</div>

    <!-- Modal Cambio de Estado -->
    <div class="modal fade" id="modalCambioEstadoRol" tabindex="-1" aria-labelledby="modalCambioEstadoRolLabel" aria-hidden="true">
        <div class="modal-dialog modal-cambio-estado">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalCambioEstadoRolLabel">
                        <i class="bi bi-arrow-repeat"></i>
                        Cambiar Estado del Rol
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                </div>
                
                <div class="modal-body">
                    <form id="formCambioEstadoRol" novalidate>
                        <input type="hidden" id="rolIdCambioEstado" name="rolIdCambioEstado">
                        <input type="hidden" id="nuevoEstadoCambioRol" name="nuevoEstadoCambioRol">
                        <input type="hidden" name="csrf_token_estado_rol" value="<?php echo $rolesController->obtener_token_csrf('cambioEstadoRol'); ?>">
                        
                        <!-- Información del rol -->
                        <div class="mb-4">
                            <h6 class="text-muted mb-3">Información del Rol</h6>
                            <div class="row">
                                <div class="col-md-8">
                                    <strong id="nombreRolCambio">Nombre del rol</strong>
                                </div>
                                <div class="col-md-4 text-end">
                                    <span class="badge bg-secondary" id="estadoActualCambioRol">Estado Actual</span>
                                </div>
                            </div>
                        </div>

                        <!-- Información del cambio -->
                        <div id="infoCambioEstadoRol" class="estado-info-card">
                            <div class="text-center">
                                <i id="iconoNuevoEstadoRol" class="bi icono-estado-grande"></i>
                                <h5 id="tituloNuevoEstadoRol">Nuevo Estado</h5>
                                <p id="descripcionNuevoEstadoRol" class="text-muted mb-0">Descripción del estado</p>
                            </div>
                        </div>

                        <!-- Motivo del cambio -->
                        <div class="mb-3">
                            <label for="motivoCambioEstadoRol" class="form-label required">
                                <strong>Motivo del cambio</strong>
                            </label>
                            <textarea id="motivoCambioEstadoRol" 
                                      name="motivoCambioEstadoRol" 
                                      class="form-control" 
                                      rows="4" 
                                      placeholder="Describe detalladamente el motivo de este cambio de estado. Este registro quedará en el historial de auditoría."
                                      required></textarea>
                            <div class="form-text">
                                <i class="bi bi-info-circle me-1"></i>
                                Mínimo 10 caracteres. Este motivo quedará registrado permanentemente.
                            </div>
                            <div class="invalid-feedback">
                                El motivo es obligatorio y debe tener al menos 10 caracteres.
                            </div>
                        </div>

                        <!-- Información adicional -->
                        <div class="alert alert-warning">
                            <i class="bi bi-exclamation-triangle me-2"></i>
                            <strong>Advertencia:</strong> Cambiar el estado de un rol afectará a todos los usuarios que lo tengan asignado.
                        </div>

                        <div class="alert alert-info">
                            <i class="bi bi-shield-check me-2"></i>
                            <strong>Auditoría:</strong> Este cambio será registrado con tu usuario, fecha, hora e IP para fines de auditoría.
                        </div>
                    </form>
                </div>
                
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="bi bi-x-circle me-1"></i>
                        Cancelar
                    </button>
                    <button type="button" class="btn" id="btnConfirmarCambioRol" onclick="confirmarCambioEstadoRol()">
                        <i class="bi bi-check-circle me-1"></i>
                        Confirmar Cambio
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script src="<?php echo SERVERURL?>views/App_usuarios/js/app_roles.js"></script>
</div>