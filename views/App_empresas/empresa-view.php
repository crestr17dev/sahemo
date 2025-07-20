
<?php 
require_once './core/auth_check.php';

require_once './controllers/App_empresasController.php';
$empresasController = new empresasController();

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

	/* Navbar Superior MODIFICADO - links izquierda, usuario derecha */
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

	/* Header MODIFICADO - Nueva Empresa a la derecha */
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

	/* Tabla de Empresas */
	.table-container {
		background: white;
		border-radius: 12px;
		box-shadow: 0 2px 10px rgba(0,0,0,0.08);
		overflow: hidden;
	}

	/* Header de tabla MODIFICADO - con opciones de vista */
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

	/* Opciones de Vista - en header de tabla */
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

	.empresa-table {
		margin: 0;
	}

	.empresa-table thead th {
		background-color: #f8f9fa;
		border: none;
		padding: 1rem 1.5rem;
		font-weight: 600;
		color: #495057;
		font-size: 0.85rem;
		text-transform: uppercase;
		letter-spacing: 0.5px;
	}

	.empresa-table tbody td {
		padding: 1rem 1.5rem;
		vertical-align: middle;
		border: none;
		border-bottom: 1px solid #f1f3f4;
	}

	.empresa-table tbody tr:hover {
		background-color: #f8f9fa;
	}

	/* Badges */
	.badge-custom {
		padding: 0.5rem 0.75rem;
		border-radius: 6px;
		font-size: 0.75rem;
		font-weight: 500;
	}

	.badge-sucursales {
		background-color: #17a2b8;
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

	/* ESTILOS MODAL AGREGADOS */
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

	/* Separador en dropdown */
	.dropdown-divider {
		margin: 0.5rem 0;
		border-top: 1px solid #e9ecef;
	}

	/* Modal de cambio de estado más ancho */
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
	
	/*ESTILOS ADICIONALES PARA LOS TABS */

	.nav-tabs {
		border-bottom: 2px solid #dee2e6;
		background-color: #f8f9fa;
	}

	.nav-tabs .nav-link {
		border: none;
		color: #6c757d;
		padding: 1rem 1.5rem;
		font-weight: 500;
		border-radius: 0;
		transition: all 0.3s ease;
	}

	.nav-tabs .nav-link:hover {
		background-color: #e9ecef;
		color: var(--primary-color);
		border-color: transparent;
	}

	.nav-tabs .nav-link.active {
		background-color: white;
		color: var(--primary-color);
		border-bottom: 3px solid var(--primary-color);
		font-weight: 600;
	}

	.tab-content {
		background-color: white;
		min-height: 500px;
	}

	.modal-xl {
		max-width: 1200px;
	}

	@media (max-width: 768px) {
		.modal-xl {
			max-width: 95%;
			margin: 0.5rem;
		}

		.nav-tabs .nav-link {
			padding: 0.75rem 0.5rem;
			font-size: 0.85rem;
		}
	}
	.spin {
		animation: spin 1s linear infinite;
	}

	@keyframes spin {
		from { transform: rotate(0deg); }
		to { transform: rotate(360deg); }
	}

	.nav-tabs .nav-link .badge {
		font-size: 0.7em;
		padding: 0.25em 0.5em;
	}
	/*estilo div nueva sucursal*/

	.alert-light {
		background-color: #fafafa;
		border-color: #e9ecef;
	}
	
	.name_principal{
		color: var(--primary-color);
	}
	
	
</style>

<div>
    <!-- Navbar Superior MODIFICADO -->
    <nav class="top-navbar">
        <div class="navbar-left">
            <a class="navbar-brand" href="#">
                <i class="bi bi-building-gear me-2"></i>
                <?php echo (!empty(COMPANY)) ? COMPANY : "Sistema de Gestión Administrativa" ?>
            </a>

            <div class="navbar-links">
                <a class="nav-link" href="<?php echo SERVERURL?>dashboard/">
                    <i class="bi bi-speedometer2 me-1"></i>
                    Dashboard
                </a>
                <a class="nav-link active" href="<?php echo SERVERURL?>empresa/">
                    <i class="bi bi-building me-1"></i>
                    Empresas
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
                <div class="sidebar-header">EMPRESAS</div>
                <nav class="nav flex-column">
                    <a class="nav-link active" href="<?php echo SERVERURL?>empresa/">
                        <i class="bi bi-building"></i>
                        Lista de Empresas
                    </a>
                    <a class="nav-link" href="#" data-bs-toggle="modal" data-bs-target="#modalNuevaEmpresa">
                        <i class="bi bi-plus-circle"></i>
                        Nueva Empresa
                    </a>
                </nav>

                <hr>

                <div class="sidebar-header">REPORTES</div>
                <nav class="nav flex-column">
                    <a class="nav-link" href="#" onclick="exportarEmpresas()">
                        <i class="bi bi-file-earmark-bar-graph"></i>
                        Reporte de Empresas
                    </a>
                    <a class="nav-link" href="#" onclick="alert('Próximamente')">
                        <i class="bi bi-file-earmark-text"></i>
                        Reporte de Sucursales
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
                    <li class="breadcrumb-item active">Lista de Empresas</li>
                </ol>
            </nav>

            <!-- Header MODIFICADO - Nueva Empresa a la derecha -->
            <div class="page-header">
                <div>
                    <h2 class="page-title">
                        <i class="bi bi-building me-2"></i>
                        Gestión de Empresas
                    </h2>
                    <p class="text-muted mb-0">Administra las empresas del sistema</p>
                </div>
                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalNuevaEmpresa">
                    <i class="bi bi-plus me-1"></i>
                    Nueva Empresa
                </button>
            </div>

            <!-- Tarjetas de Estadísticas -->
            <div class="row stats-row">
                <div class="col-lg-3 col-md-6 mb-3">
                    <div class="stats-card purple">
                        <div class="stats-content">
                            <div class="stats-number">#</div>
                            <div class="stats-label">Total Empresas</div>
                        </div>
                        <div class="stats-icon">
                            <i class="bi bi-building"></i>
                        </div>
                    </div>
                </div>

                <div class="col-lg-3 col-md-6 mb-3">
                    <div class="stats-card pink">
                        <div class="stats-content">
                            <div class="stats-number">#</div>
                            <div class="stats-label">Empresas Activas</div>
                        </div>
                        <div class="stats-icon">
                            <i class="bi bi-check-circle"></i>
                        </div>
                    </div>
                </div>

                <div class="col-lg-3 col-md-6 mb-3">
                    <div class="stats-card cyan">
                        <div class="stats-content">
                            <div class="stats-number">#</div>
                            <div class="stats-label">Total Sucursales</div>
                        </div>
                        <div class="stats-icon">
                            <i class="bi bi-geo-alt"></i>
                        </div>
                    </div>
                </div>

                <div class="col-lg-3 col-md-6 mb-3">
                    <div class="stats-card green">
                        <div class="stats-content">
                            <div class="stats-number">#</div>
                            <div class="stats-label">Sucursales Activas</div>
                        </div>
                        <div class="stats-icon">
                            <i class="bi bi-graph-up"></i>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tabla de Empresas -->
            <div class="table-container">
                <!-- Header de la tabla MODIFICADO - con opciones de vista -->
                <div class="table-header">
                    <h5 class="table-title">
                        <i class="bi bi-table"></i>
                        Empresas Registradas
                        <span class="badge bg-light text-dark ms-2">#</span>
                    </h5>

                    <!-- Opciones de Vista: Listado/Cuadrícula -->
                    <div class="view-options">
                        <button class="view-btn active" onclick="toggleView('list')" title="Vista en Lista">
                            <i class="bi bi-list"></i>
                        </button>
                        <button class="view-btn" onclick="toggleView('grid')" title="Vista en Cuadrícula">
                            <i class="bi bi-grid"></i>
                        </button>
                    </div>
                </div>

                <!-- Filtros -->
                <div class="table-filters">
                    <div class="row g-3">
						<input type="hidden" name="csrf_token_list" value="<?php echo $empresasController->obtener_token_csrf('listEmpresas'); ?>">
                        <div class="col-md-4">
                            <div class="input-group">
                                <span class="input-group-text">
                                    <i class="bi bi-search"></i>
                                </span>
                                <input type="text" class="form-control" id="shareempresa" name="shareempresa" placeholder="Buscar por nombre, código o email...">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <select class="form-select" id="estadoempresa" name="estadoempresa">
                                <option value="">Todos los estados</option>
                                <option value="Activo">Solo Activas</option>
                                <option value="Inactivo">Solo Inactivas</option>
                                <option value="Eliminado">Solo Eliminado</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <button class="btn btn-outline-primary" onclick="cargarEmpresas(1)">
                                <i class="bi bi-funnel me-1"></i>
                                Filtrar
                            </button>
                            <button class="btn btn-outline-secondary ms-2" onclick="limpiarFiltros()">
                                <i class="bi bi-arrow-clockwise me-1"></i>
                                Limpiar
                            </button>
                        </div>
                        <div class="col-md-2 text-end">
                            <!-- MODIFICADO - Icono de Excel -->
                            <button class="btn btn-outline-success" onclick="exportarEmpresas()" title="Exportar a Excel">
                                <i class="bi bi-file-earmark-excel me-1"></i>
                                Excel
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Tabla -->
                <div class="table-responsive listadoempresas">
                    
                        
                      
                    
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Nueva Empresa -->
    <div class="modal fade" id="modalNuevaEmpresa" tabindex="-1" aria-labelledby="modalNuevaEmpresaLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalNuevaEmpresaLabel">
                        <i class="bi bi-building-add"></i>
                        Registrar Nueva Empresa
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                </div>
                
                <div class="modal-body">
                    <form id="formNuevaEmpresa" novalidate>
						<input type="hidden" name="csrf_token" value="<?php	echo $empresasController->obtener_token_csrf('formNuevaEmpresa'); ?>">
                        <!-- Información General -->
                        <div class="form-section">
                            <div class="form-section-title">
                                <i class="bi bi-building"></i>
                                Información General
                            </div>
                            
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="empresaNit" class="form-label required">NIT</label>
                                        <div class="input-group">
                                            <span class="input-group-text">
                                                <i class="bi bi-card-text"></i>
                                            </span>
                                            <input type="number" class="form-control" id="empresaNit" name="empresaNit" 
                                                   placeholder="Ej: 900123456" required>
                                        </div>
                                        <div class="invalid-feedback"></div>
                                    </div>
                                </div>
                                
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="empresaNombre" class="form-label required">Nombre de la Empresa</label>
                                        <div class="input-group">
                                            <span class="input-group-text">
                                                <i class="bi bi-building"></i>
                                            </span>
                                            <input type="text" class="form-control" id="empresaNombre" name="empresaNombre" 
                                                   placeholder="Nombre completo de la empresa" maxlength="100" required>
                                        </div>
                                        <div class="invalid-feedback"></div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Información de Contacto -->
                        <div class="form-section">
                            <div class="form-section-title">
                                <i class="bi bi-telephone"></i>
                                Información de Contacto
                            </div>
                            
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="empresaTelefono" class="form-label required">Teléfono</label>
                                        <div class="input-group">
                                            <span class="input-group-text">
                                                <i class="bi bi-telephone"></i>
                                            </span>
                                            <input type="tel" class="form-control" id="empresaTelefono" name="empresaTelefono" 
                                                   placeholder="Ej: 3001234567" maxlength="15" required>
                                        </div>
                                        <div class="invalid-feedback"></div>
                                    </div>
                                </div>
                                
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="empresaEmail" class="form-label required">Email</label>
                                        <div class="input-group">
                                            <span class="input-group-text">
                                                <i class="bi bi-envelope"></i>
                                            </span>
                                            <input type="email" class="form-control" id="empresaEmail" name="empresaEmail" 
                                                   placeholder="correo@empresa.com" maxlength="70" required>
                                        </div>
                                        <div class="invalid-feedback"></div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="mb-3">
                                <label for="empresaDireccion" class="form-label required">Dirección</label>
                                <div class="input-group">
                                    <span class="input-group-text">
                                        <i class="bi bi-geo-alt"></i>
                                    </span>
                                    <input type="text" class="form-control" id="empresaDireccion" name="empresaDireccion" 
                                           placeholder="Dirección completa de la empresa" maxlength="100" required>
                                </div>
                                <div class="invalid-feedback"></div>
                            </div>
                        </div>

                        <!-- Información del Representante -->
                        <div class="form-section">
                            <div class="form-section-title">
                                <i class="bi bi-person-badge"></i>
                                Representante Legal
                            </div>
                            
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="empresaIdRepresentante" class="form-label required">Documento de Identidad</label>
                                        <div class="input-group">
                                            <span class="input-group-text">
                                                <i class="bi bi-card-text"></i>
                                            </span>
                                            <input type="number" class="form-control" id="empresaIdRepresentante" name="empresaIdRepresentante" 
                                                   placeholder="Número de documento" required>
                                        </div>
                                        <div class="invalid-feedback"></div>
                                    </div>
                                </div>
                                
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="empresaNomRepresentante" class="form-label required">Nombre Completo</label>
                                        <div class="input-group">
                                            <span class="input-group-text">
                                                <i class="bi bi-person"></i>
                                            </span>
                                            <input type="text" class="form-control" id="empresaNomRepresentante" name="empresaNomRepresentante" 
                                                   placeholder="Nombre completo del representante" maxlength="100" required>
                                        </div>
                                        <div class="invalid-feedback"></div>
                                    </div>
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
                    <button type="button" class="btn btn-primary" onclick="guardarEmpresa()">
                        <i class="bi bi-check-circle me-1"></i>
                        Guardar Empresa
                    </button>
                </div>
            </div>
        </div>
    </div>
	
	<!-- Modal Ver/Editar Empresa CON TABS -->
	<div class="modal fade" id="modalVerEditarEmpresa" tabindex="-1" aria-labelledby="modalVerEditarEmpresaLabel" aria-hidden="true">
		<div class="modal-dialog modal-xl"> <!-- CAMBIADO: de modal-lg a modal-xl para más espacio -->
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title" id="modalVerEditarEmpresaLabel">
						<i class="bi bi-building-gear"></i>
						<span id="tituloEmpresaModal">Gestión de Empresa</span>
					</h5>
					<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
				</div>

				<div class="modal-body p-0"> <!-- CAMBIADO: padding 0 para que los tabs ocupen todo -->

					<!-- NUEVO: Navegación por Tabs -->
					<ul class="nav nav-tabs nav-justified" id="empresaTabs" role="tablist">
						<li class="nav-item" role="presentation">
							<button class="nav-link active" id="tab-informacion" data-bs-toggle="tab" data-bs-target="#tabpane-informacion" type="button" role="tab">
								<i class="bi bi-building me-2"></i>
								Información General
							</button>
						</li>
						<li class="nav-item" role="presentation">
							<button class="nav-link" id="tab-sucursales" data-bs-toggle="tab" data-bs-target="#tabpane-sucursales" type="button" role="tab">
								<i class="bi bi-geo-alt me-2"></i>
								Sucursales
								<span class="badge bg-secondary ms-1" id="contadorSucursales">0</span>
							</button>
						</li>
						<li class="nav-item" role="presentation">
							<button class="nav-link" id="tab-sedes" data-bs-toggle="tab" data-bs-target="#tabpane-sedes" type="button" role="tab">
								<i class="bi bi-buildings me-2"></i>
								Sedes
								<span class="badge bg-info ms-1" id="contadorSedes">0</span>
							</button>
						</li>
					</ul>

					<!-- NUEVO: Contenido de los Tabs -->
					<div class="tab-content" id="empresaTabsContent">

						<!-- TAB 1: Información General (CONTENIDO ACTUAL) -->
						<div class="tab-pane fade show active" id="tabpane-informacion" role="tabpanel">
							<div class="p-4"> <!-- Padding solo al contenido -->
								<form id="formVerEditarEmpresa" novalidate>
									<input type="hidden" id="empresaIdEditar" name="empresaIdEditar">
									<input type="hidden" id="tokenEmpresaEspecifico" name="tokenEmpresaEspecifico">
									<input type="hidden" name="csrf_token_editar" value="<?php echo $empresasController->obtener_token_csrf('editarEmpresa'); ?>">

									<!-- Información de Solo Lectura -->
									<div class="form-section">
										<div class="form-section-title">
											<i class="bi bi-info-circle"></i>
											Información del Sistema
										</div>

										<div class="row">
											<div class="col-md-4">
												<div class="mb-3">
													<label for="verEmpresaCodigo" class="form-label">Código</label>
													<div class="input-group">
														<span class="input-group-text">
															<i class="bi bi-hash"></i>
														</span>
														<input type="text" class="form-control bg-light" id="verEmpresaCodigo" readonly>
													</div>
												</div>
											</div>

											<div class="col-md-4">
												<div class="mb-3">
													<label for="verEmpresaEstado" class="form-label">Estado</label>
													<div class="input-group">
														<span class="input-group-text">
															<i class="bi bi-check-circle"></i>
														</span>
														<input type="text" class="form-control bg-light" id="verEmpresaEstado" readonly>
													</div>
												</div>
											</div>

											<div class="col-md-4">
												<div class="mb-3">
													<label for="verEmpresaFechaRegistro" class="form-label">Fecha Registro</label>
													<div class="input-group">
														<span class="input-group-text">
															<i class="bi bi-calendar-plus"></i>
														</span>
														<input type="text" class="form-control bg-light" id="verEmpresaFechaRegistro" readonly>
													</div>
												</div>
											</div>
										</div>
									</div>

									<!-- Información General EDITABLE -->
									<div class="form-section">
										<div class="form-section-title">
											<i class="bi bi-building"></i>
											Información General
										</div>

										<div class="row">
											<div class="col-md-6">
												<div class="mb-3">
													<label for="editEmpresaNit" class="form-label required">NIT</label>
													<div class="input-group">
														<span class="input-group-text">
															<i class="bi bi-card-text"></i>
														</span>
														<input type="number" class="form-control" id="editEmpresaNit" name="editEmpresaNit" 
															   placeholder="Ej: 900123456" required>
													</div>
													<div class="invalid-feedback"></div>
												</div>
											</div>

											<div class="col-md-6">
												<div class="mb-3">
													<label for="editEmpresaNombre" class="form-label required">Nombre de la Empresa</label>
													<div class="input-group">
														<span class="input-group-text">
															<i class="bi bi-building"></i>
														</span>
														<input type="text" class="form-control" id="editEmpresaNombre" name="editEmpresaNombre" 
															   placeholder="Nombre completo de la empresa" maxlength="100" required>
													</div>
													<div class="invalid-feedback"></div>
												</div>
											</div>
										</div>
									</div>

									<!-- Información de Contacto EDITABLE -->
									<div class="form-section">
										<div class="form-section-title">
											<i class="bi bi-telephone"></i>
											Información de Contacto
										</div>

										<div class="row">
											<div class="col-md-6">
												<div class="mb-3">
													<label for="editEmpresaTelefono" class="form-label required">Teléfono</label>
													<div class="input-group">
														<span class="input-group-text">
															<i class="bi bi-telephone"></i>
														</span>
														<input type="tel" class="form-control" id="editEmpresaTelefono" name="editEmpresaTelefono" 
															   placeholder="Ej: 3001234567" maxlength="15" required>
													</div>
													<div class="invalid-feedback"></div>
												</div>
											</div>

											<div class="col-md-6">
												<div class="mb-3">
													<label for="editEmpresaEmail" class="form-label required">Email</label>
													<div class="input-group">
														<span class="input-group-text">
															<i class="bi bi-envelope"></i>
														</span>
														<input type="email" class="form-control" id="editEmpresaEmail" name="editEmpresaEmail" 
															   placeholder="correo@empresa.com" maxlength="70" required>
													</div>
													<div class="invalid-feedback"></div>
												</div>
											</div>
										</div>

										<div class="mb-3">
											<label for="editEmpresaDireccion" class="form-label required">Dirección</label>
											<div class="input-group">
												<span class="input-group-text">
													<i class="bi bi-geo-alt"></i>
												</span>
												<input type="text" class="form-control" id="editEmpresaDireccion" name="editEmpresaDireccion" 
													   placeholder="Dirección completa de la empresa" maxlength="100" required>
											</div>
											<div class="invalid-feedback"></div>
										</div>
									</div>

									<!-- Información del Representante EDITABLE -->
									<div class="form-section">
										<div class="form-section-title">
											<i class="bi bi-person-badge"></i>
											Representante Legal
										</div>

										<div class="row">
											<div class="col-md-6">
												<div class="mb-3">
													<label for="editEmpresaIdRepresentante" class="form-label required">Documento de Identidad</label>
													<div class="input-group">
														<span class="input-group-text">
															<i class="bi bi-card-text"></i>
														</span>
														<input type="number" class="form-control" id="editEmpresaIdRepresentante" name="editEmpresaIdRepresentante" 
															   placeholder="Número de documento" required>
													</div>
													<div class="invalid-feedback"></div>
												</div>
											</div>

											<div class="col-md-6">
												<div class="mb-3">
													<label for="editEmpresaNomRepresentante" class="form-label required">Nombre Completo</label>
													<div class="input-group">
														<span class="input-group-text">
															<i class="bi bi-person"></i>
														</span>
														<input type="text" class="form-control" id="editEmpresaNomRepresentante" name="editEmpresaNomRepresentante" 
															   placeholder="Nombre completo del representante" maxlength="100" required>
													</div>
													<div class="invalid-feedback"></div>
												</div>
											</div>
										</div>
									</div>
								</form>
							</div>
						</div>

						<!-- TAB 2: Sucursales (NUEVO) -->
						<div class="tab-pane fade" id="tabpane-sucursales" role="tabpanel">
							<div class="p-4">
								<!-- Header de Sucursales -->
								<div class="d-flex justify-content-between align-items-center mb-4">
									<div>
										<h5 class="mb-1">
											<i class="bi bi-geo-alt me-2 text-primary"></i>
											Sucursales de <strong class="name_principal"></st><span id="nombreEmpresaSucursales">la empresa</span></strong>
										</h5>
										<p class="text-muted mb-0">Gestiona las sucursales de esta empresa</p>
									</div>
									<button class="btn btn-primary" onclick="mostrarModalNuevaSucursal()">
										<i class="bi bi-plus me-1"></i>
										Nueva Sucursal
									</button>
								</div>

								<!-- Filtros de Sucursales -->
								<div class="row mb-3">
									<div class="col-md-6">
										<div class="input-group">
											<span class="input-group-text">
												<i class="bi bi-search"></i>
											</span>
											<input type="text" class="form-control" id="filtroSucursales" placeholder="Buscar sucursales...">
										</div>
									</div>
									<div class="col-md-4">
										<select class="form-select" id="filtroEstadoSucursales">
											<option value="">Todos los estados</option>
											<option value="Activo">Solo Activas</option>
											<option value="Inactivo">Solo Inactivas</option>
										</select>
									</div>
									<div class="col-md-2">
										<button class="btn btn-outline-secondary w-100" onclick="limpiarFiltrosSucursales()">
											<i class="bi bi-arrow-clockwise"></i>
										</button>
									</div>
								</div>

								<!-- Lista de Sucursales -->
								<div id="listaSucursales" class="border rounded">
									<!-- Aquí se cargarán las sucursales vía AJAX -->
									<div class="text-center py-5">
										<i class="bi bi-geo-alt fa-3x text-muted mb-3"></i>
										<h6 class="text-muted">Cargando sucursales...</h6>
									</div>
								</div>
							</div>
						</div>

						<!-- TAB 3: Sedes (FUTURO) -->
						<div class="tab-pane fade" id="tabpane-sedes" role="tabpanel">
							<div class="p-4">
								<!-- Header de Sucursales -->
								<div class="d-flex justify-content-between align-items-center mb-4">
									<div>
										<h5 class="mb-1">
											<i class="bi bi-buildings me-2 text-primary"></i>
											Sedes de <strong class="name_principal"><span id="nombreEmpresaSedes">la empresa</span></strong>
										</h5>
										<h6 class="mb-1">
											Gestiona las sedes organizadas por sucursales <strong  class="name_principal"><span id="nombreSucursalSedes">la Sucursal</span></strong>
										</h6>
									</div>
									<button class="btn btn-primary" onclick="mostrarModalNuevaSede()">
										<i class="bi bi-plus me-1"></i>
										Nueva Sede
									</button>
								</div>

								<!-- Filtros de Sucursales -->
								<div class="row mb-3">
									<div class="col-md-6">
										<div class="input-group">
											<span class="input-group-text">
												<i class="bi bi-search"></i>
											</span>
											<input type="text" class="form-control" id="filtroSedes" placeholder="Buscar sucursales...">
										</div>
									</div>
									<div class="col-md-4">
										<select class="form-select" id="filtroEstadoSedes">
											<option value="">Todos los estados</option>
											<option value="Activo">Solo Activas</option>
											<option value="Inactivo">Solo Inactivas</option>
										</select>
									</div>
									<div class="col-md-2">
										<button class="btn btn-outline-secondary w-100" onclick="limpiarFiltrosSedes()">
											<i class="bi bi-arrow-clockwise"></i>
										</button>
									</div>
								</div>

								<!-- Lista de Sucursales -->
								<div id="listaSedes" class="border rounded">
									<!-- Aquí se cargarán las sucursales vía AJAX -->
									<div class="text-center py-5">
										<i class="bi bi-buildings fa-3x text-muted mb-3"></i>
										<h6 class="text-muted">Cargando sedes...</h6>
									</div>
								</div>
							</div>
						</div>

					</div>
				</div>

				<!-- Footer del Modal (solo visible en tab Información) -->
				<div class="modal-footer" id="modalFooterInformacion">
					<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
						<i class="bi bi-x-circle me-1"></i>
						Cerrar
					</button>
					<button type="button" class="btn btn-success" onclick="guardarCambiosEmpresa()">
						<i class="bi bi-floppy me-1"></i>
						Guardar Cambios
					</button>
				</div>
			</div>
		</div>
	</div>
	
	<!-- Modal Cambio de Estado -->
    <div class="modal fade" id="modalCambioEstado" tabindex="-1" aria-labelledby="modalCambioEstadoLabel" aria-hidden="true">
        <div class="modal-dialog modal-cambio-estado">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalCambioEstadoLabel">
                        <i class="bi bi-arrow-repeat"></i>
                        Cambiar Estado de Empresa
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                </div>
                
                <div class="modal-body">
                    <form id="formCambioEstado" novalidate>
                        <input type="hidden" id="empresaIdCambioEstado" name="empresaIdCambioEstado">
                        <input type="hidden" id="nuevoEstadoCambio" name="nuevoEstadoCambio">
                        <input type="hidden" name="csrf_token_estado" value="<?php echo $empresasController->obtener_token_csrf('cambioEstado'); ?>">
                        
                        <!-- Información de la empresa -->
                        <div class="mb-4">
                            <h6 class="text-muted mb-3">Información de la Empresa</h6>
                            <div class="row">
                                <div class="col-md-8">
                                    <strong id="nombreEmpresaCambio">Nombre de la empresa</strong>
                                </div>
                                <div class="col-md-4 text-end">
                                    <span class="badge bg-secondary" id="estadoActualCambio">Estado Actual</span>
                                </div>
                            </div>
                        </div>

                        <!-- Información del cambio -->
                        <div id="infoCambioEstado" class="estado-info-card">
                            <div class="text-center">
                                <i id="iconoNuevoEstado" class="bi icono-estado-grande"></i>
                                <h5 id="tituloNuevoEstado">Nuevo Estado</h5>
                                <p id="descripcionNuevoEstado" class="text-muted mb-0">Descripción del estado</p>
                            </div>
                        </div>

                        <!-- Motivo del cambio -->
                        <div class="mb-3">
                            <label for="motivoCambioEstado" class="form-label required">
                                <strong>Motivo del cambio</strong>
                            </label>
                            <textarea id="motivoCambioEstado" 
                                      name="motivoCambioEstado" 
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
                    <button type="button" class="btn" id="btnConfirmarCambio" onclick="confirmarCambioEstado()">
                        <i class="bi bi-check-circle me-1"></i>
                        Confirmar Cambio
                    </button>
                </div>
            </div>
        </div>
    </div>

	<!-- Modal Nueva Sucursal -->
	<div class="modal fade" id="modalNuevaSucursal" tabindex="-1" aria-labelledby="modalNuevaSucursalLabel" aria-hidden="true">
		<div class="modal-dialog modal-lg">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title" id="modalNuevaSucursalLabel">
						<i class="bi bi-geo-alt-fill"></i>
						Registrar Nueva Sucursal
					</h5>
					<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
				</div>

				<div class="modal-body">
					<form id="formNuevaSucursal" novalidate>
						<input type="hidden" name="csrf_token_sucursal" id="csrf_token_sucursal" value="<?php echo $empresasController->obtener_token_csrf('formNuevaSucursal'); ?>">
						<input type="hidden" name="empresa_id_sucursal" id="empresa_id_sucursal" value="">

						<!-- Información de la Empresa Padre -->
						<div class="alert alert-info">
							<div class="d-flex align-items-center">
								<i class="bi bi-info-circle me-2"></i>
								<div>
									<strong>Empresa:</strong> <span id="nombreEmpresaPadre">-</span><br>
									<small class="text-muted">Esta sucursal pertenecerá a la empresa seleccionada</small>
								</div>
							</div>
						</div>

						<!-- Información General de la Sucursal -->
						<div class="form-section">
							<div class="form-section-title">
								<i class="bi bi-geo-alt"></i>
								Información General
							</div>

							<div class="row">
								<div class="col-md-6">
									<div class="mb-3">
										<label for="sucursalNit" class="form-label required">NIT de la Sucursal</label>
										<div class="input-group">
											<span class="input-group-text">
												<i class="bi bi-card-text"></i>
											</span>
											<input type="number" class="form-control" id="sucursalNit" name="sucursalNit" 
												   placeholder="Ej: 900123456001" required>
										</div>
										<div class="form-text">
											<i class="bi bi-lightbulb me-1"></i>
											Generalmente es el NIT de la empresa + sufijo
										</div>
										<div class="invalid-feedback"></div>
									</div>
								</div>

								<div class="col-md-6">
									<div class="mb-3">
										<label for="sucursalNombre" class="form-label required">Nombre de la Sucursal</label>
										<div class="input-group">
											<span class="input-group-text">
												<i class="bi bi-geo-alt"></i>
											</span>
											<input type="text" class="form-control" id="sucursalNombre" name="sucursalNombre" 
												   placeholder="Ej: Sucursal Centro, Sede Norte..." maxlength="100" required>
										</div>
										<div class="invalid-feedback"></div>
									</div>
								</div>
							</div>
						</div>

						<!-- Información de Contacto -->
						<div class="form-section">
							<div class="form-section-title">
								<i class="bi bi-telephone"></i>
								Información de Contacto
							</div>

							<div class="row">
								<div class="col-md-6">
									<div class="mb-3">
										<label for="sucursalTelefono" class="form-label required">Teléfono</label>
										<div class="input-group">
											<span class="input-group-text">
												<i class="bi bi-telephone"></i>
											</span>
											<input type="tel" class="form-control" id="sucursalTelefono" name="sucursalTelefono" 
												   placeholder="Ej: 3001234567" maxlength="15" required>
										</div>
										<div class="invalid-feedback"></div>
									</div>
								</div>

								<div class="col-md-6">
									<div class="mb-3">
										<label for="sucursalEmail" class="form-label required">Email</label>
										<div class="input-group">
											<span class="input-group-text">
												<i class="bi bi-envelope"></i>
											</span>
											<input type="email" class="form-control" id="sucursalEmail" name="sucursalEmail" 
												   placeholder="sucursal@empresa.com" maxlength="70" required>
										</div>
										<div class="invalid-feedback"></div>
									</div>
								</div>
							</div>

							<div class="mb-3">
								<label for="sucursalDireccion" class="form-label required">Dirección</label>
								<div class="input-group">
									<span class="input-group-text">
										<i class="bi bi-geo-alt-fill"></i>
									</span>
									<input type="text" class="form-control" id="sucursalDireccion" name="sucursalDireccion" 
										   placeholder="Dirección completa de la sucursal" maxlength="100" required>
								</div>
								<div class="invalid-feedback"></div>
							</div>
						</div>

						<!-- Información del Representante Local -->
						<div class="form-section">
							<div class="form-section-title">
								<i class="bi bi-person-badge"></i>
								Representante Local
							</div>

							<div class="row">
								<div class="col-md-6">
									<div class="mb-3">
										<label for="sucursalIdRepresentante" class="form-label required">Documento de Identidad</label>
										<div class="input-group">
											<span class="input-group-text">
												<i class="bi bi-card-text"></i>
											</span>
											<input type="number" class="form-control" id="sucursalIdRepresentante" name="sucursalIdRepresentante" 
												   placeholder="Número de documento" required>
										</div>
										<div class="invalid-feedback"></div>
									</div>
								</div>

								<div class="col-md-6">
									<div class="mb-3">
										<label for="sucursalNomRepresentante" class="form-label required">Nombre Completo</label>
										<div class="input-group">
											<span class="input-group-text">
												<i class="bi bi-person"></i>
											</span>
											<input type="text" class="form-control" id="sucursalNomRepresentante" name="sucursalNomRepresentante" 
												   placeholder="Nombre del representante local" maxlength="100" required>
										</div>
										<div class="form-text">
											<i class="bi bi-info-circle me-1"></i>
											Puede ser diferente al representante legal de la empresa
										</div>
										<div class="invalid-feedback"></div>
									</div>
								</div>
							</div>
						</div>

						<!-- Botón para autocompletar datos de la empresa -->
						<div class="form-section">
							<div class="alert alert-light border">
								<div class="d-flex justify-content-between align-items-center">
									<div>
										<h6 class="mb-1">
											<i class="bi bi-magic me-2"></i>
											Autocompletar desde empresa
										</h6>
										<small class="text-muted">Copia los datos de contacto de la empresa principal</small>
									</div>
									<button type="button" class="btn btn-outline-primary btn-sm" onclick="autocompletarDesdeEmpresa()">
										<i class="bi bi-arrow-down-circle me-1"></i>
										Usar datos de empresa
									</button>
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
					<button type="button" class="btn btn-primary" onclick="guardarNuevaSucursal()">
						<i class="bi bi-check-circle me-1"></i>
						Crear Sucursal
					</button>
				</div>
			</div>
		</div>
	</div>

	<!-- Modal Ver/Editar Sucursal -->
	<div class="modal fade" id="modalVerEditarSucursal" tabindex="-1" aria-labelledby="modalVerEditarSucursalLabel" aria-hidden="true">
		<div class="modal-dialog modal-lg">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title" id="modalVerEditarSucursalLabel">
						<i class="bi bi-geo-alt-fill"></i>
						<span id="tituloSucursalModal">Ver / Editar Sucursal</span>
					</h5>
					<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
				</div>

				<div class="modal-body">
					<form id="formVerEditarSucursal" novalidate>
						<input type="hidden" id="sucursalIdEditar" name="sucursalIdEditar">
						<input type="hidden" id="tokenSucursalEspecifico" name="tokenSucursalEspecifico">
						<input type="hidden" name="csrf_token_editar_sucursal" value="<?php echo $empresasController->obtener_token_csrf('editarSucursal'); ?>">

						<!-- Información del Sistema -->
						<div class="form-section">
							<div class="form-section-title">
								<i class="bi bi-info-circle"></i>
								Información del Sistema
							</div>

							<div class="row">
								<div class="col-md-4">
									<div class="mb-4">
										<label for="verSucursalCodigo" class="form-label">Código</label>
										<div class="input-group">
											<span class="input-group-text">
												<i class="bi bi-hash"></i>
											</span>
											<input type="text" class="form-control bg-light" id="verSucursalCodigo" readonly>
										</div>
									</div>
								</div>

								<div class="col-md-4">
									<div class="mb-4">
										<label for="verSucursalEstado" class="form-label">Estado</label>
										<div class="input-group">
											<span class="input-group-text">
												<i class="bi bi-check-circle"></i>
											</span>
											<input type="text" class="form-control bg-light" id="verSucursalEstado" readonly>
										</div>
									</div>
								</div>

								<div class="col-md-4">
									<div class="mb-4">
										<label for="verSucursalFechaRegistro" class="form-label">Fecha Registro</label>
										<div class="input-group">
											<span class="input-group-text">
												<i class="bi bi-calendar-plus"></i>
											</span>
											<input type="text" class="form-control bg-light" id="verSucursalFechaRegistro" readonly>
										</div>
									</div>
								</div>

								<div class="col-md-12">
									<div class="mb-12">
										<label for="verSucursalEmpresa" class="form-label">Empresa</label>
										<div class="input-group">
											<span class="input-group-text">
												<i class="bi bi-building"></i>
											</span>
											<input type="text" class="form-control bg-light" id="verSucursalEmpresa" readonly>
										</div>
									</div>
								</div>
							</div>
						</div>

						<!-- Información General EDITABLE -->
						<div class="form-section">
							<div class="form-section-title">
								<i class="bi bi-geo-alt"></i>
								Información General
							</div>

							<div class="row">
								<div class="col-md-6">
									<div class="mb-3">
										<label for="editSucursalNit" class="form-label required">NIT de la Sucursal</label>
										<div class="input-group">
											<span class="input-group-text">
												<i class="bi bi-card-text"></i>
											</span>
											<input type="number" class="form-control" id="editSucursalNit" name="editSucursalNit" 
												   placeholder="Ej: 900123456001" required>
										</div>
										<div class="invalid-feedback">El NIT es obligatorio</div>
									</div>
								</div>

								<div class="col-md-6">
									<div class="mb-3">
										<label for="editSucursalNombre" class="form-label required">Nombre de la Sucursal</label>
										<div class="input-group">
											<span class="input-group-text">
												<i class="bi bi-geo-alt"></i>
											</span>
											<input type="text" class="form-control" id="editSucursalNombre" name="editSucursalNombre" 
												   placeholder="Nombre de la sucursal" maxlength="100" required>
										</div>
										<div class="invalid-feedback">El nombre es obligatorio</div>
									</div>
								</div>
							</div>
						</div>

						<!-- Información de Contacto EDITABLE -->
						<div class="form-section">
							<div class="form-section-title">
								<i class="bi bi-telephone"></i>
								Información de Contacto
							</div>

							<div class="row">
								<div class="col-md-6">
									<div class="mb-3">
										<label for="editSucursalTelefono" class="form-label required">Teléfono</label>
										<div class="input-group">
											<span class="input-group-text">
												<i class="bi bi-telephone"></i>
											</span>
											<input type="tel" class="form-control" id="editSucursalTelefono" name="editSucursalTelefono" 
												   placeholder="Ej: 3001234567" maxlength="15" required>
										</div>
										<div class="invalid-feedback">El teléfono es obligatorio</div>
									</div>
								</div>

								<div class="col-md-6">
									<div class="mb-3">
										<label for="editSucursalEmail" class="form-label required">Email</label>
										<div class="input-group">
											<span class="input-group-text">
												<i class="bi bi-envelope"></i>
											</span>
											<input type="email" class="form-control" id="editSucursalEmail" name="editSucursalEmail" 
												   placeholder="sucursal@empresa.com" maxlength="70" required>
										</div>
										<div class="invalid-feedback">El email es obligatorio y debe ser válido</div>
									</div>
								</div>
							</div>

							<div class="mb-3">
								<label for="editSucursalDireccion" class="form-label required">Dirección</label>
								<div class="input-group">
									<span class="input-group-text">
										<i class="bi bi-geo-alt-fill"></i>
									</span>
									<input type="text" class="form-control" id="editSucursalDireccion" name="editSucursalDireccion" 
										   placeholder="Dirección completa de la sucursal" maxlength="100" required>
								</div>
								<div class="invalid-feedback">La dirección es obligatoria</div>
							</div>
						</div>

						<!-- Información del Representante EDITABLE -->
						<div class="form-section">
							<div class="form-section-title">
								<i class="bi bi-person-badge"></i>
								Representante Local
							</div>

							<div class="row">
								<div class="col-md-6">
									<div class="mb-3">
										<label for="editSucursalIdRepresentante" class="form-label required">Documento de Identidad</label>
										<div class="input-group">
											<span class="input-group-text">
												<i class="bi bi-card-text"></i>
											</span>
											<input type="number" class="form-control" id="editSucursalIdRepresentante" name="editSucursalIdRepresentante" 
												   placeholder="Número de documento" required>
										</div>
										<div class="invalid-feedback">El documento es obligatorio</div>
									</div>
								</div>

								<div class="col-md-6">
									<div class="mb-3">
										<label for="editSucursalNomRepresentante" class="form-label required">Nombre Completo</label>
										<div class="input-group">
											<span class="input-group-text">
												<i class="bi bi-person"></i>
											</span>
											<input type="text" class="form-control" id="editSucursalNomRepresentante" name="editSucursalNomRepresentante" 
												   placeholder="Nombre del representante local" maxlength="100" required>
										</div>
										<div class="invalid-feedback">El nombre del representante es obligatorio</div>
									</div>
								</div>
							</div>
						</div>
					</form>
				</div>

				<div class="modal-footer">
					<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
						<i class="bi bi-x-circle me-1"></i>
						Cerrar
					</button>
					<button type="button" class="btn btn-success" onclick="guardarCambiosSucursal()">
						<i class="bi bi-floppy me-1"></i>
						Guardar Cambios
					</button>
				</div>
			</div>
		</div>
	</div>

	<!-- Modal Nueva Sede -->
	<div class="modal fade" id="modalNuevaSede" tabindex="-1" aria-labelledby="modalNuevaSedeLabel" aria-hidden="true">
		<div class="modal-dialog modal-lg">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title" id="modalNuevaSedeLabel">
						<i class="bi bi-buildings"></i>
						Registrar Nueva Sede
					</h5>
					<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
				</div>

				<div class="modal-body">
					<form id="formNuevaSede" novalidate>
						<input type="hidden" name="csrf_token_sede" id="csrf_token_sede" value="<?php echo $empresasController->obtener_token_csrf('formNuevaSede'); ?>">
						<input type="hidden" name="sucursal-id" id="sucursal_id_sede" value="">

						<!-- Información de la Sucursal Padre -->
						<div class="alert alert-info">
							<div class="d-flex align-items-center">
								<i class="bi bi-info-circle me-2"></i>
								<div>
									<strong>Sucursal:</strong> <span id="nombreSucursalPadre">-</span><br>
									<small class="text-muted">Esta sede pertenecerá a la sucursal seleccionada</small>
								</div>
							</div>
						</div>

						<!-- Información General de la Sede -->
						<div class="form-section">
							<div class="form-section-title">
								<i class="bi bi-buildings"></i>
								Información General
							</div>

							<div class="row">
								<div class="col-md-6">
									<div class="mb-3">
										<label for="sedeNit" class="form-label required">NIT de la Sede</label>
										<div class="input-group">
											<span class="input-group-text">
												<i class="bi bi-card-text"></i>
											</span>
											<input type="number" class="form-control" id="sedeNit" name="sede-nit" 
												   placeholder="Ej: 900123456002" required>
										</div>
										<div class="form-text">
											<i class="bi bi-lightbulb me-1"></i>
											Generalmente es el NIT de la sucursal + sufijo
										</div>
										<div class="invalid-feedback"></div>
									</div>
								</div>

								<div class="col-md-6">
									<div class="mb-3">
										<label for="sedeNombre" class="form-label required">Nombre de la Sede</label>
										<div class="input-group">
											<span class="input-group-text">
												<i class="bi bi-buildings"></i>
											</span>
											<input type="text" class="form-control" id="sedeNombre" name="sede-nombre" 
												   placeholder="Ej: Sede Principal, Oficina Norte..." maxlength="100" required>
										</div>
										<div class="invalid-feedback"></div>
									</div>
								</div>
							</div>
						</div>

						<!-- Información de Contacto -->
						<div class="form-section">
							<div class="form-section-title">
								<i class="bi bi-telephone"></i>
								Información de Contacto
							</div>

							<div class="row">
								<div class="col-md-6">
									<div class="mb-3">
										<label for="sedeTelefono" class="form-label required">Teléfono</label>
										<div class="input-group">
											<span class="input-group-text">
												<i class="bi bi-telephone"></i>
											</span>
											<input type="tel" class="form-control" id="sedeTelefono" name="sede-telefono" 
												   placeholder="Ej: 3001234567" maxlength="15" required>
										</div>
										<div class="invalid-feedback"></div>
									</div>
								</div>

								<div class="col-md-6">
									<div class="mb-3">
										<label for="sedeEmail" class="form-label required">Email</label>
										<div class="input-group">
											<span class="input-group-text">
												<i class="bi bi-envelope"></i>
											</span>
											<input type="email" class="form-control" id="sedeEmail" name="sede-email" 
												   placeholder="sede@empresa.com" maxlength="70" required>
										</div>
										<div class="invalid-feedback"></div>
									</div>
								</div>
							</div>

							<div class="mb-3">
								<label for="sedeDireccion" class="form-label required">Dirección</label>
								<div class="input-group">
									<span class="input-group-text">
										<i class="bi bi-geo-alt-fill"></i>
									</span>
									<input type="text" class="form-control" id="sedeDireccion" name="sede-direccion" 
										   placeholder="Dirección completa de la sede" maxlength="200" required>
								</div>
								<div class="invalid-feedback"></div>
							</div>
						</div>

						<!-- Información del Representante Local -->
						<div class="form-section">
							<div class="form-section-title">
								<i class="bi bi-person-badge"></i>
								Representante de Sede
							</div>

							<div class="row">
								<div class="col-md-6">
									<div class="mb-3">
										<label for="sedeIdRepresentante" class="form-label required">Documento de Identidad</label>
										<div class="input-group">
											<span class="input-group-text">
												<i class="bi bi-card-text"></i>
											</span>
											<input type="number" class="form-control" id="sedeIdRepresentante" name="sede-id-representante" 
												   placeholder="Número de documento" required>
										</div>
										<div class="invalid-feedback"></div>
									</div>
								</div>

								<div class="col-md-6">
									<div class="mb-3">
										<label for="sedeNomRepresentante" class="form-label required">Nombre Completo</label>
										<div class="input-group">
											<span class="input-group-text">
												<i class="bi bi-person"></i>
											</span>
											<input type="text" class="form-control" id="sedeNomRepresentante" name="sede-nom-representante" 
												   placeholder="Nombre del representante de sede" maxlength="100" required>
										</div>
										<div class="form-text">
											<i class="bi bi-info-circle me-1"></i>
											Puede ser diferente al representante de la sucursal
										</div>
										<div class="invalid-feedback"></div>
									</div>
								</div>
							</div>
						</div>

						<!-- Botón para autocompletar datos de la sucursal -->
						<div class="form-section">
							<div class="alert alert-light border">
								<div class="d-flex justify-content-between align-items-center">
									<div>
										<h6 class="mb-1">
											<i class="bi bi-magic me-2"></i>
											Autocompletar desde sucursal
										</h6>
										<small class="text-muted">Copia los datos de contacto de la sucursal</small>
									</div>
									<button type="button" class="btn btn-outline-primary btn-sm" onclick="autocompletarDesdeSucursal()">
										<i class="bi bi-arrow-down-circle me-1"></i>
										Usar datos de sucursal
									</button>
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
					<button type="button" class="btn btn-primary" onclick="guardarNuevaSede()">
						<i class="bi bi-check-circle me-1"></i>
						Crear Sede
					</button>
				</div>
			</div>
		</div>
	</div>

	<!-- Modal Ver/Editar Sede -->
	<div class="modal fade" id="modalVerEditarSede" tabindex="-1" aria-labelledby="modalVerEditarSedeLabel" aria-hidden="true">
		<div class="modal-dialog modal-lg">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title" id="modalVerEditarSedeLabel">
						<i class="bi bi-buildings"></i>
						<span id="tituloSedeModal">Ver / Editar Sede</span>
					</h5>
					<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
				</div>

				<div class="modal-body">
					<form id="formVerEditarSede" novalidate>
						<input type="hidden" id="sedeIdEditar" name="sedeIdEditar">
						<input type="hidden" id="tokenSedeEspecifico" name="tokenSedeEspecifico">
						<input type="hidden" name="csrf_token_editar_sede" value="<?php echo $empresasController->obtener_token_csrf('editarSede'); ?>">

						<!-- Información del Sistema -->
						<div class="form-section">
							<div class="form-section-title">
								<i class="bi bi-info-circle"></i>
								Información del Sistema
							</div>

							<div class="row">
								<div class="col-md-4">
									<div class="mb-4">
										<label for="verSedeCodigo" class="form-label">Código</label>
										<div class="input-group">
											<span class="input-group-text">
												<i class="bi bi-hash"></i>
											</span>
											<input type="text" class="form-control bg-light" id="verSedeCodigo" readonly>
										</div>
									</div>
								</div>

								<div class="col-md-4">
									<div class="mb-4">
										<label for="verSedeEstado" class="form-label">Estado</label>
										<div class="input-group">
											<span class="input-group-text">
												<i class="bi bi-check-circle"></i>
											</span>
											<input type="text" class="form-control bg-light" id="verSedeEstado" readonly>
										</div>
									</div>
								</div>

								<div class="col-md-4">
									<div class="mb-4">
										<label for="verSedeFechaRegistro" class="form-label">Fecha Registro</label>
										<div class="input-group">
											<span class="input-group-text">
												<i class="bi bi-calendar-plus"></i>
											</span>
											<input type="text" class="form-control bg-light" id="verSedeFechaRegistro" readonly>
										</div>
									</div>
								</div>

								<div class="col-md-12">
									<div class="mb-12">
										<label for="verSedeSucursal" class="form-label">Sucursal</label>
										<div class="input-group">
											<span class="input-group-text">
												<i class="bi bi-geo-alt"></i>
											</span>
											<input type="text" class="form-control bg-light" id="verSedeSucursal" readonly>
										</div>
									</div>
								</div>
							</div>
						</div>

						<!-- Información General EDITABLE -->
						<div class="form-section">
							<div class="form-section-title">
								<i class="bi bi-buildings"></i>
								Información General
							</div>

							<div class="row">
								<div class="col-md-6">
									<div class="mb-3">
										<label for="editSedeNit" class="form-label required">NIT de la Sede</label>
										<div class="input-group">
											<span class="input-group-text">
												<i class="bi bi-card-text"></i>
											</span>
											<input type="number" class="form-control" id="editSedeNit" name="editSedeNit" 
												   placeholder="Ej: 900123456002" required>
										</div>
										<div class="invalid-feedback">El NIT es obligatorio</div>
									</div>
								</div>

								<div class="col-md-6">
									<div class="mb-3">
										<label for="editSedeNombre" class="form-label required">Nombre de la Sede</label>
										<div class="input-group">
											<span class="input-group-text">
												<i class="bi bi-buildings"></i>
											</span>
											<input type="text" class="form-control" id="editSedeNombre" name="editSedeNombre" 
												   placeholder="Nombre de la sede" maxlength="100" required>
										</div>
										<div class="invalid-feedback">El nombre es obligatorio</div>
									</div>
								</div>
							</div>
						</div>

						<!-- Información de Contacto EDITABLE -->
						<div class="form-section">
							<div class="form-section-title">
								<i class="bi bi-telephone"></i>
								Información de Contacto
							</div>

							<div class="row">
								<div class="col-md-6">
									<div class="mb-3">
										<label for="editSedeTelefono" class="form-label required">Teléfono</label>
										<div class="input-group">
											<span class="input-group-text">
												<i class="bi bi-telephone"></i>
											</span>
											<input type="tel" class="form-control" id="editSedeTelefono" name="editSedeTelefono" 
												   placeholder="Ej: 3001234567" maxlength="15" required>
										</div>
										<div class="invalid-feedback">El teléfono es obligatorio</div>
									</div>
								</div>

								<div class="col-md-6">
									<div class="mb-3">
										<label for="editSedeEmail" class="form-label required">Email</label>
										<div class="input-group">
											<span class="input-group-text">
												<i class="bi bi-envelope"></i>
											</span>
											<input type="email" class="form-control" id="editSedeEmail" name="editSedeEmail" 
												   placeholder="sede@empresa.com" maxlength="70" required>
										</div>
										<div class="invalid-feedback">El email es obligatorio y debe ser válido</div>
									</div>
								</div>
							</div>

							<div class="mb-3">
								<label for="editSedeDireccion" class="form-label required">Dirección</label>
								<div class="input-group">
									<span class="input-group-text">
										<i class="bi bi-geo-alt-fill"></i>
									</span>
									<input type="text" class="form-control" id="editSedeDireccion" name="editSedeDireccion" 
										   placeholder="Dirección completa de la sede" maxlength="200" required>
								</div>
								<div class="invalid-feedback">La dirección es obligatoria</div>
							</div>
						</div>

						<!-- Información del Representante EDITABLE -->
						<div class="form-section">
							<div class="form-section-title">
								<i class="bi bi-person-badge"></i>
								Representante de Sede
							</div>

							<div class="row">
								<div class="col-md-6">
									<div class="mb-3">
										<label for="editSedeIdRepresentante" class="form-label required">Documento de Identidad</label>
										<div class="input-group">
											<span class="input-group-text">
												<i class="bi bi-card-text"></i>
											</span>
											<input type="number" class="form-control" id="editSedeIdRepresentante" name="editSedeIdRepresentante" 
												   placeholder="Número de documento" required>
										</div>
										<div class="invalid-feedback">El documento es obligatorio</div>
									</div>
								</div>

								<div class="col-md-6">
									<div class="mb-3">
										<label for="editSedeNomRepresentante" class="form-label required">Nombre Completo</label>
										<div class="input-group">
											<span class="input-group-text">
												<i class="bi bi-person"></i>
											</span>
											<input type="text" class="form-control" id="editSedeNomRepresentante" name="editSedeNomRepresentante" 
												   placeholder="Nombre del representante de sede" maxlength="100" required>
										</div>
										<div class="invalid-feedback">El nombre del representante es obligatorio</div>
									</div>
								</div>
							</div>
						</div>
					</form>
				</div>

				<div class="modal-footer">
					<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
						<i class="bi bi-x-circle me-1"></i>
						Cerrar
					</button>
					<button type="button" class="btn btn-success" onclick="guardarCambiosSede()">
						<i class="bi bi-floppy me-1"></i>
						Guardar Cambios
					</button>
				</div>
			</div>
		</div>
	</div>
	
    <script src="<?php echo SERVERURL?>views/App_empresas/js/app_empresa.js"></script>
</div>