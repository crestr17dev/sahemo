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

	/* Tabla de Usuarios */
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

	.usuario-table {
		margin: 0;
	}

	.usuario-table thead th {
		background-color: #f8f9fa;
		border: none;
		padding: 1rem 1.5rem;
		font-weight: 600;
		color: #495057;
		font-size: 0.85rem;
		text-transform: uppercase;
		letter-spacing: 0.5px;
	}

	.usuario-table tbody td {
		padding: 1rem 1.5rem;
		vertical-align: middle;
		border: none;
		border-bottom: 1px solid #f1f3f4;
	}

	.usuario-table tbody tr:hover {
		background-color: #f8f9fa;
	}

	/* Badges */
	.badge-custom {
		padding: 0.5rem 0.75rem;
		border-radius: 6px;
		font-size: 0.75rem;
		font-weight: 500;
	}

	.badge-roles {
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

	.badge-bloqueado {
		background-color: #dc3545;
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
	/* Estilos para checkbox de seguridad */
	.form-check-input.is-invalid {
		border-color: #dc3545;
		box-shadow: 0 0 0 0.2rem rgba(220, 53, 69, 0.25);
	}

	.form-check-input.is-valid {
		border-color: #28a745;
		box-shadow: 0 0 0 0.2rem rgba(40, 167, 69, 0.25);
	}

	.form-check-label {
		font-size: 0.9rem;
		line-height: 1.4;
	}

	/* Estilos para las alertas de información */
	.alert ul {
		padding-left: 1.2rem;
		margin-bottom: 0;
	}

	.alert li {
		margin-bottom: 0.25rem;
	}

	/* Estilos para la card de advertencia */
	.card.border-warning {
		border-width: 2px;
	}

	.card-title.text-warning {
		color: #f0ad4e !important;
	}

	.card-text small {
		line-height: 1.6;
	}

	/* Animación para feedback de validación */
	.invalid-feedback {
		display: block;
		transition: all 0.3s ease;
	}

	.form-check {
		padding: 0.75rem;
		border-radius: 8px;
		transition: all 0.3s ease;
	}

	.form-check:hover {
		background-color: #f8f9fa;
	}

	/* Mejoras visuales para el modal */
	.form-section .alert {
		border-radius: 10px;
		border: none;
		background: linear-gradient(135deg, #d1ecf1 0%, #bee5eb 100%);
		color: #0c5460;
	}

	.form-section .alert i {
		color: #17a2b8;
	}

	/* Estilo para mensajes de éxito con credenciales */
	.credenciales-display {
		background: linear-gradient(135deg, #d4edda 0%, #c3e6cb 100%);
		border: 2px solid #28a745;
		border-radius: 12px;
		padding: 1.5rem;
		margin: 1rem 0;
		font-family: 'Courier New', monospace;
	}

	.credenciales-display h6 {
		color: #155724;
		margin-bottom: 1rem;
	}

	.credenciales-display .credencial-item {
		background: white;
		padding: 0.75rem;
		border-radius: 6px;
		margin-bottom: 0.5rem;
		border-left: 4px solid #28a745;
	}

	.credenciales-display .credencial-label {
		font-weight: bold;
		color: #495057;
	}

	.credenciales-display .credencial-valor {
		color: #28a745;
		font-weight: bold;
		font-size: 1.1rem;
	}

	/* Responsive para dispositivos móviles */
	@media (max-width: 768px) {
		.form-check-label {
			font-size: 0.85rem;
		}

		.card-text small {
			font-size: 0.75rem;
		}
	}
	
	/* Estilos específicos para items de credenciales (detalle mejorado) */
	.credencial-item {
		display: flex;
		justify-content: space-between;
		align-items: center;
	}

	.credencial-valor {
		background: #f8f9fa;
		padding: 0.25rem 0.5rem;
		border-radius: 4px;
		border: 1px solid #dee2e6;
		flex: 1;
		margin-left: 1rem;
		text-align: center;
		font-family: 'Courier New', monospace;
	}

	/* Animación de fade in para credenciales */
	@keyframes fadeInCredenciales {
		from {
			opacity: 0;
			transform: translateY(20px);
		}
		to {
			opacity: 1;
			transform: translateY(0);
		}
	}

	.credenciales-display {
		animation: fadeInCredenciales 0.5s ease-out;
	}

	/* Animación para el botón de copiar */
	@keyframes pulseSuccess {
		0% {
			box-shadow: 0 0 0 0 rgba(40, 167, 69, 0.7);
		}
		70% {
			box-shadow: 0 0 0 10px rgba(40, 167, 69, 0);
		}
		100% {
			box-shadow: 0 0 0 0 rgba(40, 167, 69, 0);
		}
	}

	.btn-success.pulse {
		animation: pulseSuccess 0.6s;
	}

	/* Mejorar apariencia de los checkboxes (solo lo que falta) */
	.form-check-input {
		width: 1.2em;
		height: 1.2em;
		margin-top: 0.1em;
		cursor: pointer;
	}

	.form-check-input:checked {
		background-color: var(--primary-color);
		border-color: var(--primary-color);
	}

	.form-check-label {
		cursor: pointer;
	}

	/* Estilos para mensajes de información importantes */
	.info-destacada {
		background: linear-gradient(135deg, #fff3cd 0%, #ffeaa7 100%);
		border-left: 4px solid #ffc107;
		padding: 1rem;
		border-radius: 0 8px 8px 0;
		margin: 1rem 0;
	}

	.info-destacada i {
		color: #856404;
		font-size: 1.2rem;
	}

	/* Utilidad para texto seleccionable */
	.text-selectable {
		user-select: text;
		-webkit-user-select: text;
		-moz-user-select: text;
		-ms-user-select: text;
	}

	/* Responsive adicional para credenciales */
	@media (max-width: 576px) {
		.credencial-item {
			flex-direction: column;
			text-align: center;
		}

		.credencial-label {
			margin-bottom: 0.5rem;
		}

		.credencial-valor {
			margin-left: 0;
			margin-top: 0.5rem;
			font-size: 1rem;
		}
	}
</style>

<div>
    <!-- Navbar Superior -->
    <nav class="top-navbar">
        <div class="navbar-left">
            <a class="navbar-brand" href="#">
                <i class="bi bi-people-gear me-2"></i>
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
                <a class="nav-link active" href="<?php echo SERVERURL?>usuario/">
                    <i class="bi bi-people me-1"></i>
                    Usuarios
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
                    <a class="nav-link active" href="<?php echo SERVERURL?>usuario/">
                        <i class="bi bi-people"></i>
                        Lista de Usuarios
                    </a>
                    <a class="nav-link" href="#" data-bs-toggle="modal" data-bs-target="#modalNuevoUsuario">
                        <i class="bi bi-person-plus"></i>
                        Nuevo Usuario
                    </a>
                </nav>

                <hr>

                <div class="sidebar-header">ROLES Y PERMISOS</div>
                <nav class="nav flex-column">
                    <a class="nav-link" href="#" onclick="alert('Próximamente - Gestión de Roles')">
                        <i class="bi bi-shield-check"></i>
                        Gestión de Roles
                    </a>
                    <a class="nav-link" href="#" onclick="alert('Próximamente - Permisos')">
                        <i class="bi bi-key"></i>
                        Permisos
                    </a>
                </nav>

                <hr>

                <div class="sidebar-header">REPORTES</div>
                <nav class="nav flex-column">
                    <a class="nav-link" href="#" onclick="exportarUsuarios()">
                        <i class="bi bi-file-earmark-bar-graph"></i>
                        Reporte de Usuarios
                    </a>
                    <a class="nav-link" href="#" onclick="alert('Próximamente')">
                        <i class="bi bi-file-earmark-text"></i>
                        Reporte de Accesos
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
                    <li class="breadcrumb-item active">Lista de Usuarios</li>
                </ol>
            </nav>

            <!-- Header -->
            <div class="page-header">
                <div>
                    <h2 class="page-title">
                        <i class="bi bi-people me-2"></i>
                        Gestión de Usuarios
                    </h2>
                    <p class="text-muted mb-0">Administra los usuarios del sistema</p>
                </div>
                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalNuevoUsuario">
                    <i class="bi bi-person-plus me-1"></i>
                    Nuevo Usuario
                </button>
            </div>

            <!-- Tarjetas de Estadísticas -->
            <div class="row stats-row">
                <div class="col-lg-2 col-md-6 mb-3">
                    <div class="stats-card purple">
                        <div class="stats-content">
                            <div class="stats-number">#</div>
                            <div class="stats-label">Total Usuarios</div>
                        </div>
                        <div class="stats-icon">
                            <i class="bi bi-people"></i>
                        </div>
                    </div>
                </div>

                <div class="col-lg-2 col-md-6 mb-3">
                    <div class="stats-card pink">
                        <div class="stats-content">
                            <div class="stats-number">#</div>
                            <div class="stats-label">Usuarios Activos</div>
                        </div>
                        <div class="stats-icon">
                            <i class="bi bi-person-check"></i>
                        </div>
                    </div>
                </div>

                <div class="col-lg-2 col-md-6 mb-3">
                    <div class="stats-card cyan">
                        <div class="stats-content">
                            <div class="stats-number">#</div>
                            <div class="stats-label">Usuarios Online</div>
                        </div>
                        <div class="stats-icon">
                            <i class="bi bi-person-lines-fill"></i>
                        </div>
                    </div>
                </div>

                <div class="col-lg-2 col-md-6 mb-3">
                    <div class="stats-card green">
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
                    <div class="stats-card orange">
                        <div class="stats-content">
                            <div class="stats-number">#</div>
                            <div class="stats-label">Total Permisos</div>
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
                            <div class="stats-label">Sesiones Activas</div>
                        </div>
                        <div class="stats-icon">
                            <i class="bi bi-activity"></i>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tabla de Usuarios -->
            <div class="table-container">
                <div class="table-header">
                    <h5 class="table-title">
                        <i class="bi bi-table"></i>
                        Usuarios Registrados
                        <span class="badge bg-light text-dark ms-2">#</span>
                    </h5>

                    <div class="view-options">
                        <button class="view-btn active" onclick="toggleViewUsuarios('list')" title="Vista en Lista">
                            <i class="bi bi-list"></i>
                        </button>
                        <button class="view-btn" onclick="toggleViewUsuarios('grid')" title="Vista en Cuadrícula">
                            <i class="bi bi-grid"></i>
                        </button>
                    </div>
                </div>

                <!-- Filtros -->
                <div class="table-filters">
                    <div class="row g-3">
						<input type="hidden" name="csrf_token_list_usuarios" value="<?php echo $usuariosController->obtener_token_csrf('listUsuarios'); ?>">
                        <div class="col-md-4">
                            <div class="input-group">
                                <span class="input-group-text">
                                    <i class="bi bi-search"></i>
                                </span>
                                <input type="text" class="form-control" id="shareusuario" name="shareusuario" placeholder="Buscar por nombre, email, documento...">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <select class="form-select" id="estadousuario" name="estadousuario">
                                <option value="">Todos los estados</option>
                                <option value="Activo">Solo Activos</option>
                                <option value="Inactivo">Solo Inactivos</option>
                                <option value="Bloqueado">Solo Bloqueados</option>
                                <option value="Eliminado">Solo Eliminados</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <button class="btn btn-outline-primary" onclick="cargarUsuarios(1)">
                                <i class="bi bi-funnel me-1"></i>
                                Filtrar
                            </button>
                            <button class="btn btn-outline-secondary ms-2" onclick="limpiarFiltrosUsuarios()">
                                <i class="bi bi-arrow-clockwise me-1"></i>
                                Limpiar
                            </button>
                        </div>
                        <div class="col-md-2 text-end">
                            <button class="btn btn-outline-success" onclick="exportarUsuarios()" title="Exportar a Excel">
                                <i class="bi bi-file-earmark-excel me-1"></i>
                                Excel
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Tabla -->
                <div class="table-responsive listadousuarios">
                    <!-- Aquí se carga la tabla via AJAX -->
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Nuevo Usuario -->
    <div class="modal fade" id="modalNuevoUsuario" tabindex="-1" aria-labelledby="modalNuevoUsuarioLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalNuevoUsuarioLabel">
                        <i class="bi bi-person-plus"></i>
                        Registrar Nuevo Usuario
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                </div>
                
                <div class="modal-body">
                    <form id="formNuevoUsuario" novalidate>
						<input type="hidden" name="csrf_token" value="<?php echo $usuariosController->obtener_token_csrf('formNuevoUsuario'); ?>">
                        
                        <!-- Información Personal -->
                        <div class="form-section">
                            <div class="form-section-title">
                                <i class="bi bi-person"></i>
                                Información Personal
                            </div>
                            
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label for="usuarioTipoDocumento" class="form-label required">Tipo Documento</label>
                                        <div class="input-group">
                                            <span class="input-group-text">
                                                <i class="bi bi-card-text"></i>
                                            </span>
                                            <select class="form-select" id="usuarioTipoDocumento" name="usuario-tipo-documento" required>
                                                <option value="">Seleccionar</option>
                                                <option value="CC">Cédula de Ciudadanía</option>
                                                <option value="CE">Cédula de Extranjería</option>
                                                <option value="TI">Tarjeta de Identidad</option>
                                                <option value="PP">Pasaporte</option>
                                                <option value="NIT">NIT</option>
                                            </select>
                                        </div>
                                        <div class="invalid-feedback"></div>
                                    </div>
                                </div>
                                
                                <div class="col-md-8">
                                    <div class="mb-3">
                                        <label for="usuarioDocumento" class="form-label required">Número de Documento</label>
                                        <div class="input-group">
                                            <span class="input-group-text">
                                                <i class="bi bi-card-text"></i>
                                            </span>
                                            <input type="number" class="form-control" id="usuarioDocumento" name="usuario-documento" 
                                                   placeholder="Ej: 1234567890" required>
                                        </div>
                                        <div class="invalid-feedback"></div>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="usuarioNombres" class="form-label required">Nombres</label>
                                        <div class="input-group">
                                            <span class="input-group-text">
                                                <i class="bi bi-person"></i>
                                            </span>
                                            <input type="text" class="form-control" id="usuarioNombres" name="usuario-nombres" 
                                                   placeholder="Nombres completos" maxlength="50" required>
                                        </div>
                                        <div class="invalid-feedback"></div>
                                    </div>
                                </div>
                                
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="usuarioApellidos" class="form-label required">Apellidos</label>
                                        <div class="input-group">
                                            <span class="input-group-text">
                                                <i class="bi bi-person"></i>
                                            </span>
                                            <input type="text" class="form-control" id="usuarioApellidos" name="usuario-apellidos" 
                                                   placeholder="Apellidos completos" maxlength="50" required>
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
                                        <label for="usuarioEmail" class="form-label required">Email</label>
                                        <div class="input-group">
                                            <span class="input-group-text">
                                                <i class="bi bi-envelope"></i>
                                            </span>
                                            <input type="email" class="form-control" id="usuarioEmail" name="usuario-email" 
                                                   placeholder="correo@ejemplo.com" maxlength="100" required>
                                        </div>
                                        <div class="invalid-feedback"></div>
                                    </div>
                                </div>
                                
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="usuarioTelefono" class="form-label required">Teléfono</label>
                                        <div class="input-group">
                                            <span class="input-group-text">
                                                <i class="bi bi-telephone"></i>
                                            </span>
                                            <input type="tel" class="form-control" id="usuarioTelefono" name="usuario-telefono" 
                                                   placeholder="Ej: 3001234567" maxlength="15" required>
                                        </div>
                                        <div class="invalid-feedback"></div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Información Laboral -->
                        <div class="form-section">
                            <div class="form-section-title">
                                <i class="bi bi-briefcase"></i>
                                Información Laboral
                            </div>
                            
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="usuarioCargo" class="form-label">Cargo</label>
                                        <div class="input-group">
                                            <span class="input-group-text">
                                                <i class="bi bi-briefcase"></i>
                                            </span>
                                            <input type="text" class="form-control" id="usuarioCargo" name="usuario-cargo" 
                                                   placeholder="Ej: Desarrollador, Gerente..." maxlength="100">
                                        </div>
                                        <div class="invalid-feedback"></div>
                                    </div>
                                </div>
                                
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="usuarioDepartamento" class="form-label">Departamento</label>
                                        <div class="input-group">
                                            <span class="input-group-text">
                                                <i class="bi bi-building"></i>
                                            </span>
                                            <input type="text" class="form-control" id="usuarioDepartamento" name="usuario-departamento" 
                                                   placeholder="Ej: IT, Ventas, RRHH..." maxlength="100">
                                        </div>
                                        <div class="invalid-feedback"></div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Asignación Organizacional -->
                        <div class="form-section">
                            <div class="form-section-title">
                                <i class="bi bi-diagram-3"></i>
                                Asignación Organizacional
                            </div>
                            
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="mb-3">
                                        <label for="usuarioEmpresaId" class="form-label required">Empresa</label>
                                        <div class="input-group">
                                            <span class="input-group-text">
                                                <i class="bi bi-building"></i>
                                            </span>
                                            <select class="form-select" id="usuarioEmpresaId" name="usuario-empresa-id" required>
                                                <option value="">Seleccionar Empresa</option>
                                                <!-- Se cargan via AJAX -->
                                            </select>
                                        </div>
                                        <div class="invalid-feedback"></div>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="usuarioSucursalId" class="form-label">Sucursal (Opcional)</label>
                                        <div class="input-group">
                                            <span class="input-group-text">
                                                <i class="bi bi-geo-alt"></i>
                                            </span>
                                            <select class="form-select" id="usuarioSucursalId" name="usuario-sucursal-id">
                                                <option value="">Seleccionar Sucursal</option>
                                            </select>
                                        </div>
                                        <div class="form-text">
                                            <i class="bi bi-info-circle me-1"></i>
                                            Si no seleccionas, tendrá acceso a toda la empresa
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="usuarioSedeId" class="form-label">Sede (Opcional)</label>
                                        <div class="input-group">
                                            <span class="input-group-text">
                                                <i class="bi bi-buildings"></i>
                                            </span>
                                            <select class="form-select" id="usuarioSedeId" name="usuario-sede-id">
                                                <option value="">Seleccionar Sede</option>
                                            </select>
                                        </div>
                                        <div class="form-text">
                                            <i class="bi bi-info-circle me-1"></i>
                                            Solo si seleccionaste una sucursal
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Credenciales de Acceso -->
                        <div class="form-section">
                            <div class="form-section-title">
                                <i class="bi bi-shield-lock"></i>
                                Información de Seguridad
                            </div>
                            
                            <div class="alert alert-info">
                                <i class="bi bi-info-circle me-2"></i>
                                <strong>Sistema de Contraseñas Temporales:</strong>
                                <ul class="mb-0 mt-2">
                                    <li>Se generará automáticamente una contraseña temporal segura</li>
                                    <li>Las credenciales se mostrarán en pantalla después del registro</li>
                                    <li>El usuario deberá cambiar la contraseña en su primer acceso</li>
                                    <li>La contraseña temporal expira en 7 días</li>
                                </ul>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="confirmarSeguridad" name="confirmar-seguridad" required>
                                        <label class="form-check-label" for="confirmarSeguridad">
                                            <strong>Confirmo que entiendo el proceso de contraseñas temporales y que el usuario deberá cambiarla en el primer acceso</strong>
                                        </label>
                                        <div class="invalid-feedback">
                                            Debes confirmar que entiendes el proceso de seguridad
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="mt-3">
                                <div class="card border-warning">
                                    <div class="card-body">
                                        <h6 class="card-title text-warning">
                                            <i class="bi bi-exclamation-triangle me-2"></i>
                                            Importante - Seguridad
                                        </h6>
                                        <p class="card-text mb-0">
                                            <small>
                                                • Entrega las credenciales de manera segura al usuario<br>
                                                • Asegúrate de que el usuario cambie la contraseña en su primer acceso<br>
                                                • Las credenciales temporales no deben compartirse por medios inseguros
                                            </small>
                                        </p>
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
                    <button type="button" class="btn btn-primary" onclick="guardarUsuario()">
                        <i class="bi bi-check-circle me-1"></i>
                        Guardar Usuario
                    </button>
                </div>
            </div>
        </div>
    </div>
	<!-- Modal Editar Usuario -->
	<div class="modal fade" id="modalEditarUsuario" tabindex="-1" aria-labelledby="modalEditarUsuarioLabel" aria-hidden="true">
		<div class="modal-dialog modal-xl">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title" id="modalEditarUsuarioLabel">
						<i class="bi bi-person-gear"></i>
						<span id="nombreUsuarioEditar" >Editar Usuario:</span>
					</h5>
					<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
				</div>

				<div class="modal-body">
					<!-- Tabs Navigation -->
					<ul class="nav nav-tabs" id="editarUsuarioTabs" role="tablist">
						<li class="nav-item" role="presentation">
							<button class="nav-link active" id="info-personal-tab" data-bs-toggle="tab" data-bs-target="#info-personal-pane" type="button" role="tab">
								<i class="bi bi-person me-2"></i>
								Información Personal
							</button>
						</li>
						<li class="nav-item" role="presentation">
							<button class="nav-link" id="roles-permisos-tab" data-bs-toggle="tab" data-bs-target="#roles-permisos-pane" type="button" role="tab">
								<i class="bi bi-shield-check me-2"></i>
								Roles y Permisos
							</button>
						</li>
						<li class="nav-item" role="presentation">
							<button class="nav-link" id="configuracion-tab" data-bs-toggle="tab" data-bs-target="#configuracion-pane" type="button" role="tab">
								<i class="bi bi-gear me-2"></i>
								Configuración
							</button>
						</li>
						<li class="nav-item" role="presentation">
							<button class="nav-link" id="auditoria-tab" data-bs-toggle="tab" data-bs-target="#auditoria-pane" type="button" role="tab">
								<i class="bi bi-clock-history me-2"></i>
								Auditoría
							</button>
						</li>
					</ul>

					<!-- Tabs Content -->
					<div class="tab-content mt-3" id="editarUsuarioTabsContent">

						<!-- TAB 1: INFORMACIÓN PERSONAL -->
						<div class="tab-pane fade show active" id="info-personal-pane" role="tabpanel">
							<form id="formEditarUsuarioInfo" novalidate>
								<input type="hidden" id="usuarioIdEditar" name="usuario-id-editar">
								<input type="hidden" id="tokenUsuarioEspecifico" name="tokenUsuarioEspecifico">
								<input type="hidden" name="csrf_token_editar" value="<?php echo $usuariosController->obtener_token_csrf('editarUsuario'); ?>">
									
								<!-- Información de Solo Lectura -->
								<div class="form-section">
									<div class="form-section-title">
										<i class="bi bi-info-circle"></i>
										Información del Sistema
									</div>

									<div class="row">
										<div class="col-md-4">
											<div class="mb-3">
												<label for="verUsuarioCodigo" class="form-label">Código</label>
												<div class="input-group">
													<span class="input-group-text">
														<i class="bi bi-hash"></i>
													</span>
													<input type="text" class="form-control bg-light" id="verUsuarioCodigo" readonly>
												</div>
											</div>
										</div>

										<div class="col-md-4">
											<div class="mb-3">
												<label for="verUsuarioEstado" class="form-label">Estado</label>
												<div class="input-group">
													<span class="input-group-text">
														<i class="bi bi-check-circle"></i>
													</span>
													<input type="text" class="form-control bg-light" id="verUsuarioEstado" readonly>
												</div>
											</div>
										</div>

										<div class="col-md-4">
											<div class="mb-3">
												<label for="verUsuarioFechaRegistro" class="form-label">Fecha Registro</label>
												<div class="input-group">
													<span class="input-group-text">
														<i class="bi bi-calendar-plus"></i>
													</span>
													<input type="text" class="form-control bg-light" id="verUsuarioFechaRegistro" readonly>
												</div>
											</div>
										</div>
									</div>
								</div>
								<!-- Información Personal -->
								<div class="form-section">
									<div class="form-section-title">
										<i class="bi bi-person"></i>
										Información Personal
									</div>

									<div class="row">
										<div class="col-md-4">
											<div class="mb-3">
												<label for="editarUsuarioTipoDocumento" class="form-label required">Tipo Documento</label>
												<div class="input-group">
													<span class="input-group-text">
														<i class="bi bi-card-text"></i>
													</span>
													<select class="form-select" id="editarUsuarioTipoDocumento" name="editar-usuario-tipo-documento" required>
														<option value="">Seleccionar</option>
														<option value="CC">Cédula de Ciudadanía</option>
														<option value="CE">Cédula de Extranjería</option>
														<option value="TI">Tarjeta de Identidad</option>
														<option value="PP">Pasaporte</option>
														<option value="NIT">NIT</option>
													</select>
												</div>
												<div class="invalid-feedback"></div>
											</div>
										</div>

										<div class="col-md-8">
											<div class="mb-3">
												<label for="editarUsuarioDocumento" class="form-label required">Número de Documento</label>
												<div class="input-group">
													<span class="input-group-text">
														<i class="bi bi-card-text"></i>
													</span>
													<input type="number" class="form-control" id="editarUsuarioDocumento" name="editar-usuario-documento" 
														   placeholder="Ej: 1234567890" required>
												</div>
												<div class="invalid-feedback"></div>
											</div>
										</div>
									</div>

									<div class="row">
										<div class="col-md-6">
											<div class="mb-3">
												<label for="editarUsuarioNombres" class="form-label required">Nombres</label>
												<div class="input-group">
													<span class="input-group-text">
														<i class="bi bi-person"></i>
													</span>
													<input type="text" class="form-control" id="editarUsuarioNombres" name="editar-usuario-nombres" 
														   placeholder="Nombres completos" maxlength="50" required>
												</div>
												<div class="invalid-feedback"></div>
											</div>
										</div>

										<div class="col-md-6">
											<div class="mb-3">
												<label for="editarUsuarioApellidos" class="form-label required">Apellidos</label>
												<div class="input-group">
													<span class="input-group-text">
														<i class="bi bi-person"></i>
													</span>
													<input type="text" class="form-control" id="editarUsuarioApellidos" name="editar-usuario-apellidos" 
														   placeholder="Apellidos completos" maxlength="50" required>
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
												<label for="editarUsuarioEmail" class="form-label required">Email</label>
												<div class="input-group">
													<span class="input-group-text">
														<i class="bi bi-envelope"></i>
													</span>
													<input type="email" class="form-control" id="editarUsuarioEmail" name="editar-usuario-email" 
														   placeholder="correo@ejemplo.com" maxlength="100" required>
												</div>
												<div class="invalid-feedback"></div>
											</div>
										</div>

										<div class="col-md-6">
											<div class="mb-3">
												<label for="editarUsuarioTelefono" class="form-label required">Teléfono</label>
												<div class="input-group">
													<span class="input-group-text">
														<i class="bi bi-telephone"></i>
													</span>
													<input type="tel" class="form-control" id="editarUsuarioTelefono" name="editar-usuario-telefono" 
														   placeholder="Ej: 3001234567" maxlength="15" required>
												</div>
												<div class="invalid-feedback"></div>
											</div>
										</div>
									</div>
								</div>

								<!-- Información Laboral -->
								<div class="form-section">
									<div class="form-section-title">
										<i class="bi bi-briefcase"></i>
										Información Laboral
									</div>

									<div class="row">
										<div class="col-md-6">
											<div class="mb-3">
												<label for="editarUsuarioCargo" class="form-label">Cargo</label>
												<div class="input-group">
													<span class="input-group-text">
														<i class="bi bi-briefcase"></i>
													</span>
													<input type="text" class="form-control" id="editarUsuarioCargo" name="editar-usuario-cargo" 
														   placeholder="Ej: Desarrollador, Gerente..." maxlength="100" required>
												</div>
												<div class="invalid-feedback"></div>
											</div>
										</div>

										<div class="col-md-6">
											<div class="mb-3">
												<label for="editarUsuarioDepartamento" class="form-label">Departamento</label>
												<div class="input-group">
													<span class="input-group-text">
														<i class="bi bi-building"></i>
													</span>
													<input type="text" class="form-control" id="editarUsuarioDepartamento" name="editar-usuario-departamento" 
														   placeholder="Ej: IT, Ventas, RRHH..." maxlength="100" required>
												</div>
												<div class="invalid-feedback"></div>
											</div>
										</div>
									</div>
								</div>

								<!-- Asignación Organizacional -->
								<div class="form-section">
									<div class="form-section-title">
										<i class="bi bi-diagram-3"></i>
										Asignación Organizacional
									</div>

									<div class="row">
										<div class="col-md-12">
											<div class="mb-3">
												<label for="editarUsuarioEmpresaId" class="form-label required">Empresa</label>
												<div class="input-group">
													<span class="input-group-text">
														<i class="bi bi-building"></i>
													</span>
													<select class="form-select" id="editarUsuarioEmpresaId" name="editar-usuario-empresa-id" required>
														<option value="">Seleccionar Empresa</option>
														<!-- Se cargan via AJAX -->
													</select>
												</div>
												<div class="invalid-feedback"></div>
											</div>
										</div>
									</div>

									<div class="row">
										<div class="col-md-6">
											<div class="mb-3">
												<label for="editarUsuarioSucursalId" class="form-label">Sucursal (Opcional)</label>
												<div class="input-group">
													<span class="input-group-text">
														<i class="bi bi-geo-alt"></i>
													</span>
													<select class="form-select" id="editarUsuarioSucursalId" name="editar-usuario-sucursal-id">
														<option value="">Seleccionar Sucursal</option>
													</select>
												</div>
												<div class="form-text">
													<i class="bi bi-info-circle me-1"></i>
													Si no seleccionas, tendrá acceso a toda la empresa
												</div>
											</div>
										</div>

										<div class="col-md-6">
											<div class="mb-3">
												<label for="editarUsuarioSedeId" class="form-label">Sede (Opcional)</label>
												<div class="input-group">
													<span class="input-group-text">
														<i class="bi bi-buildings"></i>
													</span>
													<select class="form-select" id="editarUsuarioSedeId" name="editar-usuario-sede-id">
														<option value="">Seleccionar Sede</option>
													</select>
												</div>
												<div class="form-text">
													<i class="bi bi-info-circle me-1"></i>
													Solo si seleccionaste una sucursal
												</div>
											</div>
										</div>
									</div>
								</div>
							</form>
						</div>

						<!-- TAB 2: ROLES Y PERMISOS (Placeholder) -->
						<div class="tab-pane fade" id="roles-permisos-pane" role="tabpanel">
							<div class="text-center py-5">
								<i class="bi bi-shield-check fa-3x text-muted mb-3"></i>
								<h5 class="text-muted">Roles y Permisos</h5>
								<p class="text-muted">Esta sección se implementará en el siguiente paso</p>
							</div>
						</div>

						<!-- TAB 3: CONFIGURACIÓN (Placeholder) -->
						<div class="tab-pane fade" id="configuracion-pane" role="tabpanel">
							<div class="text-center py-5">
								<i class="bi bi-gear fa-3x text-muted mb-3"></i>
								<h5 class="text-muted">Configuración de Cuenta</h5>
								<p class="text-muted">Resetear contraseña temporal, configuraciones de sesión</p>
							</div>
						</div>

						<!-- TAB 4: AUDITORÍA (Placeholder) -->
						<div class="tab-pane fade" id="auditoria-pane" role="tabpanel">
							<div class="text-center py-5">
								<i class="bi bi-clock-history fa-3x text-muted mb-3"></i>
								<h5 class="text-muted">Auditoría</h5>
								<p class="text-muted">Historial de cambios y accesos del usuario</p>
							</div>
						</div>
					</div>
				</div>

				<div class="modal-footer" id="modalFooterInformacion">
					<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
						<i class="bi bi-x-circle me-1"></i>
						Cancelar
					</button>
					<button type="button" class="btn btn-primary" onclick="guardarCambiosUsuario()">
						<i class="bi bi-check-circle me-1"></i>
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
                        Cambiar Estado de Usuario
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                </div>
                
                <div class="modal-body">
                    <form id="formCambioEstado" novalidate>
                        <input type="hidden" id="usuarioIdCambioEstado" name="usuarioIdCambioEstado">
                        <input type="hidden" id="nuevoEstadoCambio" name="nuevoEstadoCambio">
                        <input type="hidden" name="csrf_token_estado" value="<?php echo $usuariosController->obtener_token_csrf('cambioEstadoUsuario'); ?>">
                        
                        <!-- Información del usuario -->
                        <div class="mb-4">
                            <h6 class="text-muted mb-3">Información del Usuario</h6>
                            <div class="row">
                                <div class="col-md-8">
                                    <strong id="nombreUsuarioCambio">Nombre del usuario</strong>
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
	

	
    <script src="<?php echo SERVERURL?>views/App_usuarios/js/app_usuario.js"></script>
</div>