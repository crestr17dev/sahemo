<style>
	:root {
		--primary-color: #1B5E20;
		--secondary-color: #388E3C;
		--navbar-color: #2E7D32;
		--sidebar-color: #495057;
		--accent-color: #198754;
		--teal-color: #20c997;
		--success-color: #198754;
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

	.stats-card.teal {
		background: linear-gradient(135deg, var(--teal-color) 0%, #17a085 100%);
		color: white;
	}

	.stats-card.green {
		background: linear-gradient(135deg, var(--success-color) 0%, #155724 100%);
		color: white;
	}

	.stats-card.blue {
		background: linear-gradient(135deg, var(--primary-color) 0%, #0056b3 100%);
		color: white;
	}

	.stats-card.orange {
		background: linear-gradient(135deg, #fd7e14 0%, #e8590c 100%);
		color: white;
	}

	.stats-card.warning {
		background: linear-gradient(135deg, #ffc107 0%, #e0a800 100%);
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

	/* Tabla de Contratos */
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

	.contrato-table {
		margin: 0;
	}

	.contrato-table thead th {
		background-color: #f8f9fa;
		border: none;
		padding: 1rem 1.5rem;
		font-weight: 600;
		color: #495057;
		font-size: 0.85rem;
		text-transform: uppercase;
		letter-spacing: 0.5px;
	}

	.contrato-table tbody td {
		padding: 1rem 1.5rem;
		vertical-align: middle;
		border: none;
		border-bottom: 1px solid #f1f3f4;
	}

	.contrato-table tbody tr:hover {
		background-color: #f8f9fa;
	}

	/* Badges */
	.badge-custom {
		padding: 0.5rem 0.75rem;
		border-radius: 6px;
		font-size: 0.75rem;
		font-weight: 500;
	}

	.badge-proceso {
		background-color: var(--teal-color);
		color: white;
		font-family: monospace;
	}

	.badge-pendiente {
		background-color: #ffc107;
		color: #212529;
	}

	.badge-adjudicado {
		background-color: var(--success-color);
		color: white;
	}

	.badge-desierto {
		background-color: #dc3545;
		color: white;
	}

	.badge-modalidad {
		background-color: #e9ecef;
		color: #495057;
	}

	.badge-tipo {
		background-color: #17a2b8;
		color: white;
	}

	/* Botones de Acción */
	.btn-actions {
		display: flex;
		gap: 0.25rem;
		flex-wrap: wrap;
	}

	.btn-action {
		padding: 0.375rem 0.75rem;
		border-radius: 6px;
		font-size: 0.75rem;
		border: 1px solid;
		transition: all 0.2s ease;
		white-space: nowrap;
	}

	.btn-action:hover {
		transform: translateY(-1px);
	}

	.btn-action.btn-edit {
		color: var(--primary-color);
		border-color: var(--primary-color);
	}

	.btn-action.btn-edit:hover {
		background-color: var(--primary-color);
		color: white;
	}

	.btn-action.btn-adjudicar {
		color: var(--success-color);
		border-color: var(--success-color);
	}

	.btn-action.btn-adjudicar:hover {
		background-color: var(--success-color);
		color: white;
	}

	.btn-action.btn-polizas {
		color: var(--teal-color);
		border-color: var(--teal-color);
	}

	.btn-action.btn-polizas:hover {
		background-color: var(--teal-color);
		color: white;
	}

	.btn-action.btn-supervisores {
		color: #fd7e14;
		border-color: #fd7e14;
	}

	.btn-action.btn-supervisores:hover {
		background-color: #fd7e14;
		color: white;
	}

	.btn-action.btn-facturacion {
		color: #6610f2;
		border-color: #6610f2;
	}

	.btn-action.btn-facturacion:hover {
		background-color: #6610f2;
		color: white;
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

	/* Vista en Cuadrícula */
	.grid-view {
		padding: 1.5rem;
	}

	.contract-card {
		transition: transform 0.3s ease, box-shadow 0.3s ease;
		border: none;
		border-radius: 12px;
		overflow: hidden;
	}

	.contract-card:hover {
		transform: translateY(-5px);
		box-shadow: 0 8px 25px rgba(0,0,0,0.15);
	}

	.contract-card .card-header {
		border-bottom: none;
		padding: 1rem 1.25rem;
	}

	.contract-card .card-body {
		padding: 1.25rem;
	}

	.contract-card .card-footer {
		border-top: 1px solid #dee2e6;
		padding: 1rem 1.25rem;
	}

	.contract-details {
		font-size: 0.9rem;
	}

	.detail-row {
		margin-bottom: 0.5rem;
		display: flex;
		justify-content: space-between;
		align-items: center;
		flex-wrap: wrap;
	}

	.detail-row:last-child {
		margin-bottom: 0;
		justify-content: flex-start;
	}

	.contract-card .btn-actions {
		flex-wrap: wrap;
		gap: 0.5rem;
	}

	.contract-card .btn-action {
		font-size: 0.75rem;
		padding: 0.4rem 0.7rem;
		flex: 1;
		min-width: 120px;
	}

	/* Estilos para Modal de Pólizas */
	.polizas-grid {
		margin-top: 1rem;
	}

	.poliza-card {
		background: var(--success-color);
		border-radius: 8px;
		margin-bottom: 1rem;
		overflow: hidden;
		transition: all 0.3s ease;
	}

	.poliza-card:hover {
		transform: translateY(-2px);
		box-shadow: 0 4px 15px rgba(0,0,0,0.15);
	}

	.poliza-header {
		background: rgba(255,255,255,0.2);
		padding: 0.75rem 1rem;
		display: flex;
		justify-content: space-between;
		align-items: center;
	}

	.poliza-title {
		color: white;
		font-weight: 600;
		font-size: 0.9rem;
		margin: 0;
	}

	.poliza-actions {
		display: flex;
		gap: 0.25rem;
	}

	.poliza-actions .btn {
		padding: 0.25rem 0.5rem;
		border: none;
	}

	.poliza-content {
		background: rgba(255,255,255,0.1);
		padding: 1rem;
	}

	.poliza-content .form-label {
		color: rgba(255,255,255,0.9);
		font-size: 0.85rem;
		margin-bottom: 0.25rem;
		font-weight: 500;
	}

	.poliza-content .form-control {
		background: rgba(255,255,255,0.9);
		border: none;
		border-radius: 4px;
		font-size: 0.9rem;
	}

	.poliza-content .input-group-text {
		background: rgba(255,255,255,0.8);
		border: none;
		font-weight: 500;
	}

	/* Estados de toggle */
	.toggle-active {
		color: #28a745 !important;
	}

	.toggle-inactive {
		color: #6c757d !important;
	}

	/* Estilos para Modal de Supervisores */
	.supervisor-card {
		border: 1px solid #dee2e6;
		transition: all 0.3s ease;
	}

	.supervisor-card:hover {
		border-color: #fd7e14;
		box-shadow: 0 2px 8px rgba(253, 126, 20, 0.15);
	}

	.supervisor-card .card-header {
		background: linear-gradient(135deg, #fd7e14 0%, #e8590c 100%);
		color: white;
		border-bottom: none;
	}

	.supervisor-card .card-header h6 {
		color: white;
	}

	.supervisor-card .btn-outline-danger {
		color: white;
		border-color: rgba(255,255,255,0.5);
	}

	.supervisor-card .btn-outline-danger:hover {
		background-color: rgba(255,255,255,0.2);
		border-color: white;
		color: white;
	}

	.supervisor-title {
		font-weight: 600;
	}

	.supervisor-number {
		background: rgba(255,255,255,0.2);
		padding: 0.25rem 0.5rem;
		border-radius: 4px;
		font-weight: bold;
	}

	/* Tabs personalizadas */
	.nav-tabs .nav-link {
		border: 1px solid transparent;
		border-radius: 0.375rem 0.375rem 0 0;
		color: #495057;
		font-weight: 500;
	}

	.nav-tabs .nav-link.active {
		background-color: #fff;
		border-color: #dee2e6 #dee2e6 #fff;
		color: #fd7e14;
		font-weight: 600;
	}

	.nav-tabs .nav-link:hover {
		border-color: #e9ecef #e9ecef #dee2e6;
		color: #fd7e14;
	}

	/* Badges en tabs */
	.nav-tabs .badge {
		font-size: 0.7rem;
	}

	/* Estados de validación */
	.supervisor-card.valid {
		border-color: #28a745;
	}

	.supervisor-card.invalid {
		border-color: #dc3545;
	}

	.supervisor-card.valid .card-header {
		background: linear-gradient(135deg, #28a745 0%, #1e7e34 100%);
	}

	.supervisor-card.invalid .card-header {
		background: linear-gradient(135deg, #dc3545 0%, #c82333 100%);
	}

	/* Animaciones para agregar/remover */
	.supervisor-card {
		animation: slideInDown 0.3s ease-out;
	}

	@keyframes slideInDown {
		from {
			opacity: 0;
			transform: translateY(-20px);
		}
		to {
			opacity: 1;
			transform: translateY(0);
		}
	}

	.removing {
		animation: slideOutUp 0.3s ease-in forwards;
	}

	@keyframes slideOutUp {
		from {
			opacity: 1;
			transform: translateY(0);
		}
		to {
			opacity: 0;
			transform: translateY(-20px);
		}
	}

	/* Responsive para supervisores */
	@media (max-width: 768px) {
		.supervisor-card .card-header {
			flex-direction: column;
			gap: 0.5rem;
			text-align: center;
		}

		.supervisor-card .row > div {
			margin-bottom: 1rem;
		}
	}
</style>

<body>
    <!-- Navbar Superior -->
    <nav class="top-navbar">
        <div class="navbar-left">
            <a class="navbar-brand" href="#">
                <i class="bi bi-building-gear me-2"></i>
                {{ tema.nombre_empresa|default:"Sistema de Gestión" }}
            </a>

            <div class="navbar-links">
                <a class="nav-link" href="/dashboard/">
                    <i class="bi bi-speedometer2 me-1"></i>
                    Dashboard
                </a>
                <a class="nav-link" href="/empresas/">
                    <i class="bi bi-building me-1"></i>
                    Empresas
                </a>
                <a class="nav-link active" href="/contratacion/">
                    <i class="bi bi-file-earmark-text me-1"></i>
                    Contratación
                </a>
            </div>
        </div>

        <div class="user-section">
            <i class="bi bi-person-circle me-1"></i>
            Carlos Andres Restrepo Gomez
        </div>
    </nav>

    <div class="container-fluid-custom">
        <!-- Sidebar -->
        <div class="sidebar">
            <div class="sidebar-section">
                <div class="sidebar-header">CONTRATACIÓN</div>
                <nav class="nav flex-column">
                    <a class="nav-link active" href="/contratacion/">
                        <i class="bi bi-file-earmark-text"></i>
                        Gestión de Contratos
                    </a>
                    <a class="nav-link" href="#" onclick="showNewContractModal()">
                        <i class="bi bi-plus-circle"></i>
                        Nuevo Proceso
                    </a>
                    <a class="nav-link" href="/contratacion/config-polizas/">
                        <i class="bi bi-gear"></i>
                        Config. Pólizas
                    </a>
                </nav>

                <hr>

                <div class="sidebar-header">SEGUIMIENTO</div>
                <nav class="nav flex-column">
                    <a class="nav-link" href="#" onclick="filterByStatus('pendiente')">
                        <i class="bi bi-clock"></i>
                        Procesos Pendientes
                    </a>
                    <a class="nav-link" href="#" onclick="filterByStatus('adjudicado')">
                        <i class="bi bi-check-circle"></i>
                        Adjudicados
                    </a>
                    <a class="nav-link" href="#" onclick="filterByStatus('desierto')">
                        <i class="bi bi-x-circle"></i>
                        Declarados Desiertos
                    </a>
                </nav>

                <hr>

                <div class="sidebar-header">REPORTES</div>
                <nav class="nav flex-column">
                    <a class="nav-link" href="#" onclick="alert('Próximamente')">
                        <i class="bi bi-file-earmark-bar-graph"></i>
                        Reporte de Contratos
                    </a>
                    <a class="nav-link" href="#" onclick="alert('Próximamente')">
                        <i class="bi bi-graph-up"></i>
                        Análisis de Procesos
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
                    <li class="breadcrumb-item active">Gestión de Contratación</li>
                </ol>
            </nav>

            <!-- Header -->
            <div class="page-header">
                <div>
                    <h2 class="page-title">
                        <i class="bi bi-file-earmark-text me-2"></i>
                        Gestión de Contratación
                    </h2>
                    <p class="text-muted mb-0">Administra los procesos de contratación de proveedores y contratistas</p>
                </div>
                <button class="btn btn-primary" onclick="showNewContractModal()">
                    <i class="bi bi-plus me-1"></i>
                    Nuevo Proceso
                </button>
            </div>

            <!-- Tarjetas de Estadísticas -->
            <div class="row stats-row">
                <div class="col-lg-3 col-md-6 mb-3">
                    <div class="stats-card teal">
                        <div class="stats-content">
                            <div class="stats-number">{{ total_procesos|default:"18" }}</div>
                            <div class="stats-label">Total Procesos</div>
                        </div>
                        <div class="stats-icon">
                            <i class="bi bi-file-earmark-text"></i>
                        </div>
                    </div>
                </div>

                <div class="col-lg-3 col-md-6 mb-3">
                    <div class="stats-card green">
                        <div class="stats-content">
                            <div class="stats-number">{{ procesos_adjudicados|default:"12" }}</div>
                            <div class="stats-label">Adjudicados</div>
                        </div>
                        <div class="stats-icon">
                            <i class="bi bi-check-circle"></i>
                        </div>
                    </div>
                </div>

                <div class="col-lg-3 col-md-6 mb-3">
                    <div class="stats-card warning">
                        <div class="stats-content">
                            <div class="stats-number">{{ procesos_pendientes|default:"4" }}</div>
                            <div class="stats-label">Pendientes</div>
                        </div>
                        <div class="stats-icon">
                            <i class="bi bi-clock"></i>
                        </div>
                    </div>
                </div>

                <div class="col-lg-3 col-md-6 mb-3">
                    <div class="stats-card orange">
                        <div class="stats-content">
                            <div class="stats-number">{{ procesos_desiertos|default:"2" }}</div>
                            <div class="stats-label">Desiertos</div>
                        </div>
                        <div class="stats-icon">
                            <i class="bi bi-x-circle"></i>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tabla de Contratos -->
            <div class="table-container">
                <!-- Header de la tabla -->
                <div class="table-header">
                    <h5 class="table-title">
                        <i class="bi bi-table"></i>
                        Procesos de Contratación
                        <span class="badge bg-light text-dark ms-2">{{ total_procesos|default:"18" }}</span>
                    </h5>

                    <!-- Opciones de Vista -->
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
                        <div class="col-md-4">
                            <div class="input-group">
                                <span class="input-group-text">
                                    <i class="bi bi-search"></i>
                                </span>
                                <input type="text" class="form-control" placeholder="Buscar por nombre, objeto o proceso..." id="searchInput">
                            </div>
                        </div>
                        <div class="col-md-2">
                            <select class="form-select" id="statusFilter">
                                <option value="">Todos los estados</option>
                                <option value="pendiente">Pendiente</option>
                                <option value="adjudicado">Adjudicado</option>
                                <option value="desierto">Desierto</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <select class="form-select" id="typeFilter">
                                <option value="">Todos los tipos</option>
                                <option value="servicios">Servicios</option>
                                <option value="suministros">Suministros</option>
                                <option value="obras">Obras</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <button class="btn btn-outline-primary" onclick="clearFilters()">
                                <i class="bi bi-arrow-clockwise me-1"></i>
                                Limpiar
                            </button>
                        </div>
                        <div class="col-md-2 text-end">
                            <button class="btn btn-outline-success">
                                <i class="bi bi-file-earmark-excel me-1"></i>
                                Excel
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Tabla -->
                <div class="table-responsive">
                    <table class="table contrato-table">
                        <thead>
                            <tr>
                                <th>Proceso</th>
                                <th>Nombre Contrato</th>
                                <th>Objeto a Contratar</th>
                                <th>Modalidad</th>
                                <th>Tipo</th>
                                <th>Área</th>
                                <th>Presupuesto</th>
                                <th>Estado</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- Datos de ejemplo -->
                            <tr data-status="pendiente" data-type="obras">
                                <td>
                                    <span class="badge-custom badge-proceso">PROC-2025-001</span>
                                </td>
                                <td>
                                    <div>
                                        <strong>Construcción Edificio Administrativo</strong>
                                        <br>
                                        <small class="text-muted">Vigencia: 2025</small>
                                    </div>
                                </td>
                                <td>Construcción de edificio de 3 pisos para uso administrativo</td>
                                <td>
                                    <span class="badge-custom badge-modalidad">Licitación Pública</span>
                                </td>
                                <td>
                                    <span class="badge-custom badge-tipo">Obras</span>
                                </td>
                                <td>Infraestructura</td>
                                <td>$850,000,000</td>
                                <td>
                                    <span class="badge-custom badge-pendiente">Pendiente</span>
                                </td>
                                <td>
                                    <div class="btn-actions">
                                        <button class="btn btn-action btn-edit" onclick="editContract('PROC-2025-001')" title="Editar">
                                            <i class="bi bi-pencil me-1"></i>Editar
                                        </button>
                                        <button class="btn btn-action btn-adjudicar" onclick="adjudicarContract('PROC-2025-001')" title="Adjudicar">
                                            <i class="bi bi-gavel me-1"></i>Adjudicar
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            <tr data-status="adjudicado" data-type="servicios">
                                <td>
                                    <span class="badge-custom badge-proceso">PROC-2025-002</span>
                                </td>
                                <td>
                                    <div>
                                        <strong>Servicios de Vigilancia</strong>
                                        <br>
                                        <small class="text-muted">Vigencia: 2025</small>
                                    </div>
                                </td>
                                <td>Prestación de servicios de vigilancia y seguridad física</td>
                                <td>
                                    <span class="badge-custom badge-modalidad">Contratación Directa</span>
                                </td>
                                <td>
                                    <span class="badge-custom badge-tipo">Servicios</span>
                                </td>
                                <td>Seguridad</td>
                                <td>$120,000,000</td>
                                <td>
                                    <span class="badge-custom badge-adjudicado">Adjudicado</span>
                                </td>
                                <td>
                                    <div class="btn-actions">
                                        <button class="btn btn-action btn-edit" onclick="editContract('PROC-2025-002')" title="Editar">
                                            <i class="bi bi-pencil me-1"></i>Editar
                                        </button>
                                        <button class="btn btn-action btn-polizas" onclick="managePolicies('PROC-2025-002')" title="Gestionar Pólizas">
                                            <i class="bi bi-shield-check me-1"></i>Pólizas
                                        </button>
                                        <button class="btn btn-action btn-supervisores" onclick="manageSupervisors('PROC-2025-002')" title="Asignar Supervisores">
                                            <i class="bi bi-people me-1"></i>Supervisores
                                        </button>
                                        <button class="btn btn-action btn-facturacion" onclick="viewInvoicing('PROC-2025-002')" title="Ver Facturación">
                                            <i class="bi bi-receipt me-1"></i>Facturación
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            <tr data-status="adjudicado" data-type="suministros">
                                <td>
                                    <span class="badge-custom badge-proceso">PROC-2025-003</span>
                                </td>
                                <td>
                                    <div>
                                        <strong>Suministro de Equipos de Cómputo</strong>
                                        <br>
                                        <small class="text-muted">Vigencia: 2025</small>
                                    </div>
                                </td>
                                <td>Adquisición de computadores y equipos periféricos</td>
                                <td>
                                    <span class="badge-custom badge-modalidad">Selección Abreviada</span>
                                </td>
                                <td>
                                    <span class="badge-custom badge-tipo">Suministros</span>
                                </td>
                                <td>Sistemas</td>
                                <td>$45,000,000</td>
                                <td>
                                    <span class="badge-custom badge-adjudicado">Adjudicado</span>
                                </td>
                                <td>
                                    <div class="btn-actions">
                                        <button class="btn btn-action btn-edit" onclick="editContract('PROC-2025-003')" title="Editar">
                                            <i class="bi bi-pencil me-1"></i>Editar
                                        </button>
                                        <button class="btn btn-action btn-polizas" onclick="managePolicies('PROC-2025-003')" title="Gestionar Pólizas">
                                            <i class="bi bi-shield-check me-1"></i>Pólizas
                                        </button>
                                        <button class="btn btn-action btn-supervisores" onclick="manageSupervisors('PROC-2025-003')" title="Asignar Supervisores">
                                            <i class="bi bi-people me-1"></i>Supervisores
                                        </button>
                                        <button class="btn btn-action btn-facturacion" onclick="viewInvoicing('PROC-2025-003')" title="Ver Facturación">
                                            <i class="bi bi-receipt me-1"></i>Facturación
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            <tr data-status="desierto" data-type="servicios">
                                <td>
                                    <span class="badge-custom badge-proceso">PROC-2025-004</span>
                                </td>
                                <td>
                                    <div>
                                        <strong>Mantenimiento de Vehículos</strong>
                                        <br>
                                        <small class="text-muted">Vigencia: 2025</small>
                                    </div>
                                </td>
                                <td>Servicios de mantenimiento preventivo y correctivo de flota vehicular</td>
                                <td>
                                    <span class="badge-custom badge-modalidad">Invitación Pública</span>
                                </td>
                                <td>
                                    <span class="badge-custom badge-tipo">Servicios</span>
                                </td>
                                <td>Logística</td>
                                <td>$28,000,000</td>
                                <td>
                                    <span class="badge-custom badge-desierto">Desierto</span>
                                </td>
                                <td>
                                    <div class="btn-actions">
                                        <button class="btn btn-action btn-edit" onclick="editContract('PROC-2025-004')" title="Editar">
                                            <i class="bi bi-pencil me-1"></i>Editar
                                        </button>
                                        <button class="btn btn-action btn-adjudicar" onclick="adjudicarContract('PROC-2025-004')" title="Reabrir Proceso">
                                            <i class="bi bi-arrow-repeat me-1"></i>Reabrir
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            <tr data-status="pendiente" data-type="servicios">
                                <td>
                                    <span class="badge-custom badge-proceso">PROC-2025-005</span>
                                </td>
                                <td>
                                    <div>
                                        <strong>Servicios de Aseo y Cafetería</strong>
                                        <br>
                                        <small class="text-muted">Vigencia: 2025</small>
                                    </div>
                                </td>
                                <td>Prestación de servicios generales de aseo y cafetería</td>
                                <td>
                                    <span class="badge-custom badge-modalidad">Mínima Cuantía</span>
                                </td>
                                <td>
                                    <span class="badge-custom badge-tipo">Servicios</span>
                                </td>
                                <td>Servicios Generales</td>
                                <td>$15,500,000</td>
                                <td>
                                    <span class="badge-custom badge-pendiente">Pendiente</span>
                                </td>
                                <td>
                                    <div class="btn-actions">
                                        <button class="btn btn-action btn-edit" onclick="editContract('PROC-2025-005')" title="Editar">
                                            <i class="bi bi-pencil me-1"></i>Editar
                                        </button>
                                        <button class="btn btn-action btn-adjudicar" onclick="adjudicarContract('PROC-2025-005')" title="Adjudicar">
                                            <i class="bi bi-gavel me-1"></i>Adjudicar
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal para Asignación de Supervisores -->
    <div class="modal fade" id="supervisoresModal" tabindex="-1" aria-labelledby="supervisoresModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header bg-warning text-dark">
                    <h5 class="modal-title" id="supervisoresModalLabel">
                        <i class="bi bi-people me-2"></i>
                        Asignación de Supervisores y Apoyo a la Supervisión
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <!-- Información del proceso -->
                    <div class="alert alert-info d-flex align-items-center mb-4">
                        <i class="bi bi-info-circle me-2"></i>
                        <div>
                            <strong>Proceso:</strong> <span id="supervisores-proceso"></span> | 
                            <strong>Proveedor:</strong> <span id="supervisores-proveedor"></span> |
                            <strong>Valor Contrato:</strong> <span id="supervisores-valor-contrato"></span>
                        </div>
                    </div>

                    <!-- Pestañas para Supervisores y Apoyo -->
                    <ul class="nav nav-tabs mb-4" id="supervisoresTabs" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" id="supervisores-tab" data-bs-toggle="tab" data-bs-target="#supervisores-panel" type="button" role="tab">
                                <i class="bi bi-person-badge me-2"></i>
                                Supervisores
                                <span class="badge bg-primary ms-2" id="supervisores-count">0</span>
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="apoyo-tab" data-bs-toggle="tab" data-bs-target="#apoyo-panel" type="button" role="tab">
                                <i class="bi bi-person-plus me-2"></i>
                                Apoyo a la Supervisión
                                <span class="badge bg-secondary ms-2" id="apoyo-count">0</span>
                            </button>
                        </li>
                    </ul>

                    <div class="tab-content" id="supervisoresTabContent">
                        <!-- Panel de Supervisores -->
                        <div class="tab-pane fade show active" id="supervisores-panel" role="tabpanel">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <h6 class="mb-0">
                                    <i class="bi bi-person-badge me-2"></i>
                                    Gestión de Supervisores
                                </h6>
                                <button class="btn btn-primary btn-sm" onclick="addSupervisor()">
                                    <i class="bi bi-plus me-1"></i>
                                    Agregar Supervisor
                                </button>
                            </div>

                            <!-- Lista de Supervisores -->
                            <div id="supervisores-list">
                                <div class="alert alert-light text-center" id="no-supervisores">
                                    <i class="bi bi-person-x fa-2x text-muted mb-2"></i>
                                    <p class="mb-0">No hay supervisores asignados</p>
                                    <small class="text-muted">Haga clic en "Agregar Supervisor" para comenzar</small>
                                </div>
                            </div>
                        </div>

                        <!-- Panel de Apoyo a la Supervisión -->
                        <div class="tab-pane fade" id="apoyo-panel" role="tabpanel">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <h6 class="mb-0">
                                    <i class="bi bi-person-plus me-2"></i>
                                    Gestión de Apoyo a la Supervisión
                                </h6>
                                <button class="btn btn-secondary btn-sm" onclick="addApoyo()">
                                    <i class="bi bi-plus me-1"></i>
                                    Agregar Apoyo
                                </button>
                            </div>

                            <!-- Lista de Apoyo -->
                            <div id="apoyo-list">
                                <div class="alert alert-light text-center" id="no-apoyo">
                                    <i class="bi bi-person-dash fa-2x text-muted mb-2"></i>
                                    <p class="mb-0">No hay personal de apoyo asignado</p>
                                    <small class="text-muted">Haga clic en "Agregar Apoyo" para comenzar</small>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Template para Supervisor/Apoyo -->
                    <div id="supervisor-template" style="display: none;">
                        <div class="card mb-3 supervisor-card">
                            <div class="card-header d-flex justify-content-between align-items-center">
                                <h6 class="mb-0">
                                    <i class="bi bi-person-badge me-2"></i>
                                    <span class="supervisor-title">Supervisor</span> #<span class="supervisor-number">1</span>
                                </h6>
                                <button class="btn btn-outline-danger btn-sm" onclick="removeSupervisor(this)">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </div>
                            <div class="card-body">
                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <label class="form-label">
                                            <i class="bi bi-card-text me-1"></i>
                                            Número de Identidad <span class="text-danger">*</span>
                                        </label>
                                        <div class="input-group">
                                            <input type="text" class="form-control supervisor-cedula" 
                                                   placeholder="Cédula o documento" onblur="buscarSupervisor(this)">
                                            <button class="btn btn-outline-secondary" type="button" onclick="buscarSupervisor(this.previousElementSibling)">
                                                <i class="bi bi-search"></i>
                                            </button>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">
                                            <i class="bi bi-person me-1"></i>
                                            Nombre Completo <span class="text-danger">*</span>
                                        </label>
                                        <input type="text" class="form-control supervisor-nombre" 
                                               placeholder="Nombre completo del supervisor">
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <label class="form-label">
                                            <i class="bi bi-person-lines-fill me-1"></i>
                                            Apellido <span class="text-danger">*</span>
                                        </label>
                                        <input type="text" class="form-control supervisor-apellido" 
                                               placeholder="Apellidos del supervisor">
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">
                                            <i class="bi bi-envelope me-1"></i>
                                            Correo Electrónico <span class="text-danger">*</span>
                                        </label>
                                        <input type="email" class="form-control supervisor-correo" 
                                               placeholder="correo@empresa.com">
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <label class="form-label">
                                            <i class="bi bi-shield-check me-1"></i>
                                            Rol
                                        </label>
                                        <select class="form-select supervisor-rol">
                                            <option value="supervisor">Supervisor</option>
                                            <option value="apoyo_supervision">Apoyo Supervisión</option>
                                        </select>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">
                                            <i class="bi bi-building me-1"></i>
                                            Centro de Costos <span class="text-danger">*</span>
                                        </label>
                                        <select class="form-control supervisor-centro-costos" onchange="updateNombreCentro(this)">
                                            <option value="">Seleccionar centro de costos</option>
                                            <option value="001">001 - Administración General</option>
                                            <option value="002">002 - Operaciones</option>
                                            <option value="003">003 - Mantenimiento</option>
                                            <option value="004">004 - Sistemas</option>
                                            <option value="005">005 - Seguridad</option>
                                            <option value="006">006 - Servicios Generales</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-12">
                                        <label class="form-label">
                                            <i class="bi bi-tag me-1"></i>
                                            Nombre Centro de Costos
                                        </label>
                                        <input type="text" class="form-control supervisor-nombre-centro" 
                                               placeholder="Se completará automáticamente" readonly>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="bi bi-x me-1"></i>
                        Cancelar
                    </button>
                    <button type="button" class="btn btn-outline-primary" onclick="validateSupervisores()">
                        <i class="bi bi-check-circle me-1"></i>
                        Validar Datos
                    </button>
                    <button type="button" class="btn btn-warning" onclick="saveSupervisores()">
                        <i class="bi bi-people me-1"></i>
                        Guardar Supervisores
                    </button>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="polizasModal" tabindex="-1" aria-labelledby="polizasModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header bg-success text-white">
                    <h5 class="modal-title" id="polizasModalLabel">
                        <i class="bi bi-shield-check me-2"></i>
                        Gestión de Pólizas y Garantías
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <!-- Información del proceso -->
                    <div class="alert alert-info d-flex align-items-center mb-4">
                        <i class="bi bi-info-circle me-2"></i>
                        <div>
                            <strong>Proceso:</strong> <span id="polizas-proceso"></span> | 
                            <strong>Proveedor:</strong> <span id="polizas-proveedor"></span> |
                            <strong>Valor Contrato:</strong> <span id="polizas-valor-contrato"></span>
                        </div>
                    </div>

                    <!-- Tipo de póliza (Inicial, Adición, Prórroga) -->
                    <div class="row mb-4">
                        <div class="col-md-4">
                            <label for="tipoPoliza" class="form-label">
                                <i class="bi bi-bookmark-check me-1"></i>
                                Tipo de Póliza <span class="text-danger">*</span>
                            </label>
                            <select class="form-select" id="tipoPoliza" required onchange="updatePolizaContext()">
                                <option value="">Seleccionar tipo</option>
                                <option value="inicial">Pólizas Iniciales (Adjudicación)</option>
                                <option value="adicion">Pólizas por Adición</option>
                                <option value="prorroga">Pólizas por Prórroga</option>
                            </select>
                        </div>
                        <div class="col-md-4" id="valorReferenciaContainer" style="display: none;">
                            <label for="valorReferencia" class="form-label">Valor de Referencia</label>
                            <div class="input-group">
                                <span class="input-group-text">$</span>
                                <input type="text" class="form-control" id="valorReferencia" readonly>
                            </div>
                            <small class="text-muted">Base para calcular porcentajes</small>
                        </div>
                        <div class="col-md-4">
                            <label for="fechaVigencia" class="form-label">
                                <i class="bi bi-calendar me-1"></i>
                                Fecha de Vigencia <span class="text-danger">*</span>
                            </label>
                            <input type="date" class="form-control" id="fechaVigencia" required>
                        </div>
                    </div>

                    <!-- Grid de Pólizas y Garantías -->
                    <div class="polizas-grid">
                        <div class="row">
                            <!-- P&G Cumplimiento de contrato -->
                            <div class="col-lg-6 mb-3">
                                <div class="poliza-card" data-poliza="cumplimiento">
                                    <div class="poliza-header">
                                        <div class="poliza-title">
                                            <i class="bi bi-check-circle me-2"></i>
                                            P&G Cumplimiento de contrato
                                        </div>
                                        <div class="poliza-actions">
                                            <button class="btn btn-sm btn-light" type="button" onclick="togglePoliza('cumplimiento')" title="Activar/Desactivar">
                                                <i class="bi bi-toggle-off" id="toggle-cumplimiento"></i>
                                            </button>
                                            <button class="btn btn-sm btn-light" type="button" title="Información">
                                                <i class="bi bi-question-circle"></i>
                                            </button>
                                        </div>
                                    </div>
                                    <div class="poliza-content" id="content-cumplimiento" style="display: none;">
                                        <div class="row">
                                            <div class="col-md-4">
                                                <label class="form-label">Número de Póliza</label>
                                                <input type="text" class="form-control" id="poliza-cumplimiento" placeholder="Número de póliza">
                                            </div>
                                            <div class="col-md-4">
                                                <label class="form-label">Porcentaje</label>
                                                <div class="input-group">
                                                    <input type="number" class="form-control" id="porcentaje-cumplimiento" 
                                                           placeholder="%" onchange="calculateValue('cumplimiento')" step="0.01" min="0" max="100">
                                                    <span class="input-group-text">%</span>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <label class="form-label">Valor</label>
                                                <div class="input-group">
                                                    <span class="input-group-text">$</span>
                                                    <input type="text" class="form-control" id="valor-cumplimiento" placeholder="Valor calculado" readonly>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- P&G Calidad del servicio -->
                            <div class="col-lg-6 mb-3">
                                <div class="poliza-card" data-poliza="calidad-servicio">
                                    <div class="poliza-header">
                                        <div class="poliza-title">
                                            <i class="bi bi-check-circle me-2"></i>
                                            P&G Calidad del servicio
                                        </div>
                                        <div class="poliza-actions">
                                            <button class="btn btn-sm btn-light" type="button" onclick="togglePoliza('calidad-servicio')" title="Activar/Desactivar">
                                                <i class="bi bi-toggle-off" id="toggle-calidad-servicio"></i>
                                            </button>
                                            <button class="btn btn-sm btn-light" type="button" title="Información">
                                                <i class="bi bi-question-circle"></i>
                                            </button>
                                        </div>
                                    </div>
                                    <div class="poliza-content" id="content-calidad-servicio" style="display: none;">
                                        <div class="row">
                                            <div class="col-md-4">
                                                <label class="form-label">Número de Póliza</label>
                                                <input type="text" class="form-control" id="poliza-calidad-servicio" placeholder="Número de póliza">
                                            </div>
                                            <div class="col-md-4">
                                                <label class="form-label">Porcentaje</label>
                                                <div class="input-group">
                                                    <input type="number" class="form-control" id="porcentaje-calidad-servicio" 
                                                           placeholder="%" onchange="calculateValue('calidad-servicio')" step="0.01" min="0" max="100">
                                                    <span class="input-group-text">%</span>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <label class="form-label">Valor</label>
                                                <div class="input-group">
                                                    <span class="input-group-text">$</span>
                                                    <input type="text" class="form-control" id="valor-calidad-servicio" placeholder="Valor calculado" readonly>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- P&G Calidad y correcto funcionamiento de bienes -->
                            <div class="col-lg-6 mb-3">
                                <div class="poliza-card" data-poliza="calidad-bienes">
                                    <div class="poliza-header">
                                        <div class="poliza-title">
                                            <i class="bi bi-check-circle me-2"></i>
                                            P&G Calidad y correcto funcionamiento de bienes
                                        </div>
                                        <div class="poliza-actions">
                                            <button class="btn btn-sm btn-light" type="button" onclick="togglePoliza('calidad-bienes')" title="Activar/Desactivar">
                                                <i class="bi bi-toggle-off" id="toggle-calidad-bienes"></i>
                                            </button>
                                            <button class="btn btn-sm btn-light" type="button" title="Información">
                                                <i class="bi bi-question-circle"></i>
                                            </button>
                                        </div>
                                    </div>
                                    <div class="poliza-content" id="content-calidad-bienes" style="display: none;">
                                        <div class="row">
                                            <div class="col-md-4">
                                                <label class="form-label">Número de Póliza</label>
                                                <input type="text" class="form-control" id="poliza-calidad-bienes" placeholder="Número de póliza">
                                            </div>
                                            <div class="col-md-4">
                                                <label class="form-label">Porcentaje</label>
                                                <div class="input-group">
                                                    <input type="number" class="form-control" id="porcentaje-calidad-bienes" 
                                                           placeholder="%" onchange="calculateValue('calidad-bienes')" step="0.01" min="0" max="100">
                                                    <span class="input-group-text">%</span>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <label class="form-label">Valor</label>
                                                <div class="input-group">
                                                    <span class="input-group-text">$</span>
                                                    <input type="text" class="form-control" id="valor-calidad-bienes" placeholder="Valor calculado" readonly>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- P&G Pago salarios y prestaciones sociales -->
                            <div class="col-lg-6 mb-3">
                                <div class="poliza-card" data-poliza="salarios-prestaciones">
                                    <div class="poliza-header">
                                        <div class="poliza-title">
                                            <i class="bi bi-check-circle me-2"></i>
                                            P&G Pago salarios y prestaciones sociales
                                        </div>
                                        <div class="poliza-actions">
                                            <button class="btn btn-sm btn-light" type="button" onclick="togglePoliza('salarios-prestaciones')" title="Activar/Desactivar">
                                                <i class="bi bi-toggle-off" id="toggle-salarios-prestaciones"></i>
                                            </button>
                                            <button class="btn btn-sm btn-light" type="button" title="Información">
                                                <i class="bi bi-question-circle"></i>
                                            </button>
                                        </div>
                                    </div>
                                    <div class="poliza-content" id="content-salarios-prestaciones" style="display: none;">
                                        <div class="row">
                                            <div class="col-md-4">
                                                <label class="form-label">Número de Póliza</label>
                                                <input type="text" class="form-control" id="poliza-salarios-prestaciones" placeholder="Número de póliza">
                                            </div>
                                            <div class="col-md-4">
                                                <label class="form-label">Porcentaje</label>
                                                <div class="input-group">
                                                    <input type="number" class="form-control" id="porcentaje-salarios-prestaciones" 
                                                           placeholder="%" onchange="calculateValue('salarios-prestaciones')" step="0.01" min="0" max="100">
                                                    <span class="input-group-text">%</span>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <label class="form-label">Valor</label>
                                                <div class="input-group">
                                                    <span class="input-group-text">$</span>
                                                    <input type="text" class="form-control" id="valor-salarios-prestaciones" placeholder="Valor calculado" readonly>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- P&G Responsabilidad civil extra contractual -->
                            <div class="col-lg-6 mb-3">
                                <div class="poliza-card" data-poliza="responsabilidad-civil">
                                    <div class="poliza-header">
                                        <div class="poliza-title">
                                            <i class="bi bi-check-circle me-2"></i>
                                            P&G Responsabilidad civil extra contractual
                                        </div>
                                        <div class="poliza-actions">
                                            <button class="btn btn-sm btn-light" type="button" onclick="togglePoliza('responsabilidad-civil')" title="Activar/Desactivar">
                                                <i class="bi bi-toggle-off" id="toggle-responsabilidad-civil"></i>
                                            </button>
                                            <button class="btn btn-sm btn-light" type="button" title="Información">
                                                <i class="bi bi-question-circle"></i>
                                            </button>
                                        </div>
                                    </div>
                                    <div class="poliza-content" id="content-responsabilidad-civil" style="display: none;">
                                        <div class="row">
                                            <div class="col-md-4">
                                                <label class="form-label">Número de Póliza</label>
                                                <input type="text" class="form-control" id="poliza-responsabilidad-civil" placeholder="Número de póliza">
                                            </div>
                                            <div class="col-md-4">
                                                <label class="form-label">Porcentaje</label>
                                                <div class="input-group">
                                                    <input type="number" class="form-control" id="porcentaje-responsabilidad-civil" 
                                                           placeholder="%" onchange="calculateValue('responsabilidad-civil')" step="0.01" min="0" max="100">
                                                    <span class="input-group-text">%</span>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <label class="form-label">Valor</label>
                                                <div class="input-group">
                                                    <span class="input-group-text">$</span>
                                                    <input type="text" class="form-control" id="valor-responsabilidad-civil" placeholder="Valor calculado" readonly>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- P&G Estabilidad obra -->
                            <div class="col-lg-6 mb-3">
                                <div class="poliza-card" data-poliza="estabilidad-obra">
                                    <div class="poliza-header">
                                        <div class="poliza-title">
                                            <i class="bi bi-check-circle me-2"></i>
                                            P&G Estabilidad obra
                                        </div>
                                        <div class="poliza-actions">
                                            <button class="btn btn-sm btn-light" type="button" onclick="togglePoliza('estabilidad-obra')" title="Activar/Desactivar">
                                                <i class="bi bi-toggle-off" id="toggle-estabilidad-obra"></i>
                                            </button>
                                            <button class="btn btn-sm btn-light" type="button" title="Información">
                                                <i class="bi bi-question-circle"></i>
                                            </button>
                                        </div>
                                    </div>
                                    <div class="poliza-content" id="content-estabilidad-obra" style="display: none;">
                                        <div class="row">
                                            <div class="col-md-4">
                                                <label class="form-label">Número de Póliza</label>
                                                <input type="text" class="form-control" id="poliza-estabilidad-obra" placeholder="Número de póliza">
                                            </div>
                                            <div class="col-md-4">
                                                <label class="form-label">Porcentaje</label>
                                                <div class="input-group">
                                                    <input type="number" class="form-control" id="porcentaje-estabilidad-obra" 
                                                           placeholder="%" onchange="calculateValue('estabilidad-obra')" step="0.01" min="0" max="100">
                                                    <span class="input-group-text">%</span>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <label class="form-label">Valor</label>
                                                <div class="input-group">
                                                    <span class="input-group-text">$</span>
                                                    <input type="text" class="form-control" id="valor-estabilidad-obra" placeholder="Valor calculado" readonly>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- P&G Manejo y correcta inversión anticipo -->
                            <div class="col-lg-6 mb-3">
                                <div class="poliza-card" data-poliza="manejo-anticipo">
                                    <div class="poliza-header">
                                        <div class="poliza-title">
                                            <i class="bi bi-check-circle me-2"></i>
                                            P&G Manejo y correcta inversión anticipo
                                        </div>
                                        <div class="poliza-actions">
                                            <button class="btn btn-sm btn-light" type="button" onclick="togglePoliza('manejo-anticipo')" title="Activar/Desactivar">
                                                <i class="bi bi-toggle-off" id="toggle-manejo-anticipo"></i>
                                            </button>
                                            <button class="btn btn-sm btn-light" type="button" title="Información">
                                                <i class="bi bi-question-circle"></i>
                                            </button>
                                        </div>
                                    </div>
                                    <div class="poliza-content" id="content-manejo-anticipo" style="display: none;">
                                        <div class="row">
                                            <div class="col-md-4">
                                                <label class="form-label">Número de Póliza</label>
                                                <input type="text" class="form-control" id="poliza-manejo-anticipo" placeholder="Número de póliza">
                                            </div>
                                            <div class="col-md-4">
                                                <label class="form-label">Porcentaje</label>
                                                <div class="input-group">
                                                    <input type="number" class="form-control" id="porcentaje-manejo-anticipo" 
                                                           placeholder="%" onchange="calculateValue('manejo-anticipo')" step="0.01" min="0" max="100">
                                                    <span class="input-group-text">%</span>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <label class="form-label">Valor</label>
                                                <div class="input-group">
                                                    <span class="input-group-text">$</span>
                                                    <input type="text" class="form-control" id="valor-manejo-anticipo" placeholder="Valor calculado" readonly>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- P&G Devolución pago anticipado -->
                            <div class="col-lg-6 mb-3">
                                <div class="poliza-card" data-poliza="devolucion-pago">
                                    <div class="poliza-header">
                                        <div class="poliza-title">
                                            <i class="bi bi-check-circle me-2"></i>
                                            P&G Devolución pago anticipado
                                        </div>
                                        <div class="poliza-actions">
                                            <button class="btn btn-sm btn-light" type="button" onclick="togglePoliza('devolucion-pago')" title="Activar/Desactivar">
                                                <i class="bi bi-toggle-off" id="toggle-devolucion-pago"></i>
                                            </button>
                                            <button class="btn btn-sm btn-light" type="button" title="Información">
                                                <i class="bi bi-question-circle"></i>
                                            </button>
                                        </div>
                                    </div>
                                    <div class="poliza-content" id="content-devolucion-pago" style="display: none;">
                                        <div class="row">
                                            <div class="col-md-4">
                                                <label class="form-label">Número de Póliza</label>
                                                <input type="text" class="form-control" id="poliza-devolucion-pago" placeholder="Número de póliza">
                                            </div>
                                            <div class="col-md-4">
                                                <label class="form-label">Porcentaje</label>
                                                <div class="input-group">
                                                    <input type="number" class="form-control" id="porcentaje-devolucion-pago" 
                                                           placeholder="%" onchange="calculateValue('devolucion-pago')" step="0.01" min="0" max="100">
                                                    <span class="input-group-text">%</span>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <label class="form-label">Valor</label>
                                                <div class="input-group">
                                                    <span class="input-group-text">$</span>
                                                    <input type="text" class="form-control" id="valor-devolucion-pago" placeholder="Valor calculado" readonly>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Resumen de Pólizas Activas -->
                    <div class="alert alert-light border mt-4" id="polizasResumen" style="display: none;">
                        <h6 class="mb-2"><i class="bi bi-calculator me-2"></i>Resumen de Pólizas Activas</h6>
                        <div id="resumenContent"></div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="bi bi-x me-1"></i>
                        Cancelar
                    </button>
                    <button type="button" class="btn btn-outline-primary" onclick="loadPolizasDefaults()">
                        <i class="bi bi-arrow-clockwise me-1"></i>
                        Cargar Valores por Defecto
                    </button>
                    <button type="button" class="btn btn-success" onclick="savePolizas()">
                        <i class="bi bi-shield-check me-1"></i>
                        Guardar Pólizas
                    </button>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="adjudicacionModal" tabindex="-1" aria-labelledby="adjudicacionModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-success text-white">
                    <h5 class="modal-title" id="adjudicacionModalLabel">
                        <i class="bi bi-gavel me-2"></i>
                        Etapa de Adjudicación
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="alert alert-info d-flex align-items-center">
                        <i class="bi bi-info-circle me-2"></i>
                        <div>
                            <strong>Proceso:</strong> <span id="adjudicacion-proceso"></span> | 
                            <strong>Presupuesto Inicial:</strong> <span id="adjudicacion-presupuesto"></span>
                        </div>
                    </div>

                    <form id="adjudicacionForm">
                        <!-- Tipo de Decisión -->
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="tipoDecision" class="form-label">
                                    <i class="bi bi-check-circle me-1"></i>
                                    Tipo de Decisión <span class="text-danger">*</span>
                                </label>
                                <select class="form-select" id="tipoDecision" required onchange="toggleAdjudicacionFields()">
                                    <option value="">Seleccionar decisión</option>
                                    <option value="adjudicado">Adjudicado</option>
                                    <option value="desierto">Declarar Desierto</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label for="fechaAdjudicacion" class="form-label">
                                    <i class="bi bi-calendar me-1"></i>
                                    Fecha de Adjudicación o Desierto <span class="text-danger">*</span>
                                </label>
                                <input type="date" class="form-control" id="fechaAdjudicacion" required>
                            </div>
                        </div>

                        <!-- Campos de Adjudicación (se ocultan si es desierto) -->
                        <div id="adjudicacionFields">
                            <!-- Datos del Proveedor -->
                            <div class="card mb-3">
                                <div class="card-header bg-light">
                                    <h6 class="mb-0">
                                        <i class="bi bi-building me-2"></i>
                                        Datos del Proveedor
                                    </h6>
                                </div>
                                <div class="card-body">
                                    <div class="row mb-3">
                                        <div class="col-md-6">
                                            <label for="numeroIdentidadProveedor" class="form-label">
                                                Número Identidad Proveedor <span class="text-danger">*</span>
                                            </label>
                                            <div class="input-group">
                                                <input type="text" class="form-control" id="numeroIdentidadProveedor" 
                                                       placeholder="NIT o Cédula" onblur="buscarProveedor()">
                                                <button class="btn btn-outline-secondary" type="button" onclick="buscarProveedor()">
                                                    <i class="bi bi-search"></i>
                                                </button>
                                            </div>
                                            <small class="text-muted">El sistema buscará automáticamente el proveedor</small>
                                        </div>
                                        <div class="col-md-6">
                                            <label for="nombreProveedor" class="form-label">
                                                Nombre Proveedor <span class="text-danger">*</span>
                                            </label>
                                            <input type="text" class="form-control" id="nombreProveedor" 
                                                   placeholder="Se completará automáticamente o ingrese manualmente">
                                        </div>
                                    </div>
                                    
                                    <div class="row mb-3">
                                        <div class="col-md-6">
                                            <label for="telefonoProveedor" class="form-label">
                                                <i class="bi bi-telephone me-1"></i>
                                                Teléfono Proveedor <span class="text-danger">*</span>
                                            </label>
                                            <input type="tel" class="form-control" id="telefonoProveedor" 
                                                   placeholder="Ej: +57 300 123 4567">
                                        </div>
                                        <div class="col-md-6">
                                            <label for="correoProveedor" class="form-label">
                                                <i class="bi bi-envelope me-1"></i>
                                                Correo Electrónico Proveedor <span class="text-danger">*</span>
                                            </label>
                                            <input type="email" class="form-control" id="correoProveedor" 
                                                   placeholder="ejemplo@empresa.com">
                                        </div>
                                    </div>
                                    
                                    <div class="row">
                                        <div class="col-md-12">
                                            <label for="direccionProveedor" class="form-label">
                                                <i class="bi bi-geo-alt me-1"></i>
                                                Dirección Proveedor
                                            </label>
                                            <input type="text" class="form-control" id="direccionProveedor" 
                                                   placeholder="Dirección completa del proveedor">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Datos del Contrato -->
                            <div class="card mb-3">
                                <div class="card-header bg-light">
                                    <h6 class="mb-0">
                                        <i class="bi bi-file-earmark-text me-2"></i>
                                        Datos del Contrato
                                    </h6>
                                </div>
                                <div class="card-body">
                                    <div class="row mb-3">
                                        <div class="col-md-6">
                                            <label for="numeroContrato" class="form-label">
                                                Número Contrato <span class="text-danger">*</span>
                                            </label>
                                            <input type="text" class="form-control" id="numeroContrato" 
                                                   placeholder="Ej: CONT-2025-001" style="text-transform: uppercase;">
                                        </div>
                                        <div class="col-md-6">
                                            <label for="valorAdjudicado" class="form-label">
                                                Valor Adjudicado <span class="text-danger">*</span>
                                            </label>
                                            <div class="input-group">
                                                <span class="input-group-text">$</span>
                                                <input type="text" class="form-control" id="valorAdjudicado" 
                                                       placeholder="0" onblur="validateBudget()">
                                            </div>
                                            <small class="text-muted" id="budgetValidation"></small>
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <div class="col-md-6">
                                            <label for="valorAdjudicadoUnitario" class="form-label">
                                                Valor Adjudicado Unitario
                                            </label>
                                            <div class="input-group">
                                                <span class="input-group-text">$</span>
                                                <input type="text" class="form-control" id="valorAdjudicadoUnitario" 
                                                       placeholder="0">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Fechas del Contrato -->
                            <div class="card mb-3">
                                <div class="card-header bg-light">
                                    <h6 class="mb-0">
                                        <i class="bi bi-calendar-range me-2"></i>
                                        Periodo de Ejecución
                                    </h6>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <label for="fechaInicio" class="form-label">
                                                Fecha de Inicio <span class="text-danger">*</span>
                                            </label>
                                            <input type="date" class="form-control" id="fechaInicio" onchange="calculateDuration()">
                                        </div>
                                        <div class="col-md-6">
                                            <label for="fechaFin" class="form-label">
                                                Fecha Fin <span class="text-danger">*</span>
                                            </label>
                                            <input type="date" class="form-control" id="fechaFin" onchange="calculateDuration()">
                                        </div>
                                    </div>
                                    <div class="row mt-2">
                                        <div class="col-12">
                                            <small class="text-info" id="duracionContrato"></small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Campos para Desierto (se muestran solo si es desierto) -->
                        <div id="desiertoFields" style="display: none;">
                            <div class="card mb-3">
                                <div class="card-header bg-warning text-dark">
                                    <h6 class="mb-0">
                                        <i class="bi bi-exclamation-triangle me-2"></i>
                                        Declaración de Desierto
                                    </h6>
                                </div>
                                <div class="card-body">
                                    <div class="alert alert-warning">
                                        <i class="bi bi-info-circle me-2"></i>
                                        Al declarar desierto este proceso, no se asignarán valores ni fechas de ejecución.
                                    </div>
                                    <div class="mb-3">
                                        <label for="motivoDesierto" class="form-label">
                                            Motivo de Declaración Desierta
                                        </label>
                                        <textarea class="form-control" id="motivoDesierto" rows="3" 
                                                  placeholder="Describa las razones por las cuales se declara desierto el proceso..."></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="bi bi-x me-1"></i>
                        Cancelar
                    </button>
                    <button type="button" class="btn btn-success" onclick="saveAdjudicacion()">
                        <i class="bi bi-check-circle me-1"></i>
                        <span id="btnSaveText">Guardar Adjudicación</span>
                    </button>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="newContractModal" tabindex="-1" aria-labelledby="newContractModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="newContractModalLabel">
                        <i class="bi bi-plus-circle me-2"></i>
                        Nuevo Proceso de Contratación
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="newContractForm">
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="nombreContrato" class="form-label">Nombre Contrato</label>
                                <input type="text" class="form-control" id="nombreContrato" required>
                            </div>
                            <div class="col-md-6">
                                <label for="objetoContratar" class="form-label">Objeto a Contratar</label>
                                <input type="text" class="form-control" id="objetoContratar" required>
                            </div>
                        </div>
                        
                        <div class="row mb-3">
                            <div class="col-md-4">
                                <label for="modalidadSeleccion" class="form-label">Modalidad Selección</label>
                                <select class="form-select" id="modalidadSeleccion" required>
                                    <option value="">Seleccionar modalidad</option>
                                    <option value="licitacion_publica">Licitación Pública</option>
                                    <option value="seleccion_abreviada">Selección Abreviada</option>
                                    <option value="concurso_meritos">Concurso de Méritos</option>
                                    <option value="contratacion_directa">Contratación Directa</option>
                                    <option value="minima_cuantia">Mínima Cuantía</option>
                                    <option value="invitacion_publica">Invitación Pública</option>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label for="causal" class="form-label">Causal</label>
                                <input type="text" class="form-control" id="causal">
                            </div>
                            <div class="col-md-4">
                                <label for="presupuesto" class="form-label">Presupuesto</label>
                                <input type="number" class="form-control" id="presupuesto" required>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-3">
                                <label for="vigencia" class="form-label">Vigencia</label>
                                <select class="form-select" id="vigencia" required>
                                    <option value="">Seleccionar</option>
                                    <option value="2025" selected>2025</option>
                                    <option value="2026">2026</option>
                                    <option value="2027">2027</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label for="area" class="form-label">Área</label>
                                <select class="form-select" id="area" required>
                                    <option value="">Seleccionar área</option>
                                    <option value="infraestructura">Infraestructura</option>
                                    <option value="seguridad">Seguridad</option>
                                    <option value="sistemas">Sistemas</option>
                                    <option value="logistica">Logística</option>
                                    <option value="servicios_generales">Servicios Generales</option>
                                    <option value="administracion">Administración</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label for="tipoContrato" class="form-label">Tipo Contrato</label>
                                <select class="form-select" id="tipoContrato" required>
                                    <option value="">Seleccionar tipo</option>
                                    <option value="servicios">Servicios</option>
                                    <option value="suministros">Suministros</option>
                                    <option value="obras">Obras</option>
                                    <option value="consultoria">Consultoría</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label for="proceso" class="form-label">Proceso</label>
                                <input type="text" class="form-control" id="proceso" placeholder="Auto-generado" readonly>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="bi bi-x me-1"></i>
                        Cancelar
                    </button>
                    <button type="button" class="btn btn-primary" onclick="saveNewContract()">
                        <i class="bi bi-floppy me-1"></i>
                        Guardar Proceso
                    </button>
                </div>
            </div>
        </div>
    </div>


    <script>
        // Variables globales
        let contractData = [];

        // Función para mostrar modal de nuevo contrato
        function showNewContractModal() {
            const modal = new bootstrap.Modal(document.getElementById('newContractModal'));
            generateProcessNumber();
            modal.show();
        }

        // Generar número de proceso automático
        function generateProcessNumber() {
            const year = new Date().getFullYear();
            const randomNum = Math.floor(Math.random() * 900) + 100;
            document.getElementById('proceso').value = `PROC-${year}-${randomNum}`;
        }

        // Funciones de gestión de contratos
        function editContract(processId) {
            alert(`Editar proceso: ${processId}`);
            // Aquí iría la lógica para abrir el modal de edición
        }

        function adjudicarContract(processId) {
            // Obtener datos del proceso para mostrar en el modal
            const row = document.querySelector(`[onclick="editContract('${processId}')"]`).closest('tr');
            const contractName = row.querySelector('strong').textContent;
            const presupuesto = row.cells[6].textContent;
            
            // Llenar información del proceso
            document.getElementById('adjudicacion-proceso').textContent = processId + ' - ' + contractName;
            document.getElementById('adjudicacion-presupuesto').textContent = presupuesto;
            
            // Limpiar formulario
            document.getElementById('adjudicacionForm').reset();
            document.getElementById('adjudicacionFields').style.display = 'block';
            document.getElementById('desiertoFields').style.display = 'none';
            document.getElementById('btnSaveText').textContent = 'Guardar Adjudicación';
            
            // Mostrar modal
            const modal = new bootstrap.Modal(document.getElementById('adjudicacionModal'));
            modal.show();
        }

        // Función para alternar campos según tipo de decisión
        function toggleAdjudicacionFields() {
            const tipoDecision = document.getElementById('tipoDecision').value;
            const adjudicacionFields = document.getElementById('adjudicacionFields');
            const desiertoFields = document.getElementById('desiertoFields');
            const btnSaveText = document.getElementById('btnSaveText');
            
            if (tipoDecision === 'desierto') {
                adjudicacionFields.style.display = 'none';
                desiertoFields.style.display = 'block';
                btnSaveText.textContent = 'Declarar Desierto';
            } else if (tipoDecision === 'adjudicado') {
                adjudicacionFields.style.display = 'block';
                desiertoFields.style.display = 'none';
                btnSaveText.textContent = 'Guardar Adjudicación';
            } else {
                adjudicacionFields.style.display = 'none';
                desiertoFields.style.display = 'none';
                btnSaveText.textContent = 'Guardar';
            }
        }

        // Función para buscar proveedor
        function buscarProveedor() {
            const numeroIdentidad = document.getElementById('numeroIdentidadProveedor').value;
            
            if (!numeroIdentidad) {
                clearProveedorFields();
                return;
            }
            
            // Simular búsqueda en base de datos
            // En la implementación real, esto sería una llamada AJAX
            const proveedoresSimulados = {
                '900123456': {
                    nombre: 'CONSTRUCTORA ABC S.A.S',
                    telefono: '+57 300 123 4567',
                    correo: 'contacto@constructoraabc.com',
                    direccion: 'Calle 10 # 43A-15, Medellín, Antioquia'
                },
                '800654321': {
                    nombre: 'SUMINISTROS XYZ LTDA',
                    telefono: '+57 312 987 6543',
                    correo: 'ventas@suministrosxyz.com',
                    direccion: 'Carrera 65 # 45-23, Bello, Antioquia'
                },
                '76543210': {
                    nombre: 'SERVICIOS TÉCNICOS DEF',
                    telefono: '+57 301 456 7890',
                    correo: 'info@serviciosdef.com',
                    direccion: 'Calle 70 Sur # 45-67, Sabaneta, Antioquia'
                },
                '901234567': {
                    nombre: 'VIGILANCIA Y SEGURIDAD GHI S.A.S',
                    telefono: '+57 315 234 5678',
                    correo: 'seguridad@vigilanciaghi.com',
                    direccion: 'Carrera 43A # 34-12, Envigado, Antioquia'
                }
            };
            
            if (proveedoresSimulados[numeroIdentidad]) {
                const proveedor = proveedoresSimulados[numeroIdentidad];
                
                // Llenar todos los campos del proveedor
                document.getElementById('nombreProveedor').value = proveedor.nombre;
                document.getElementById('telefonoProveedor').value = proveedor.telefono;
                document.getElementById('correoProveedor').value = proveedor.correo;
                document.getElementById('direccionProveedor').value = proveedor.direccion;
                
                // Marcar campos como completados automáticamente
                const proveedorFields = ['nombreProveedor', 'telefonoProveedor', 'correoProveedor', 'direccionProveedor'];
                proveedorFields.forEach(fieldId => {
                    const field = document.getElementById(fieldId);
                    field.classList.add('is-valid');
                    setTimeout(() => field.classList.remove('is-valid'), 2000);
                });
                
                showAlert('✅ Proveedor encontrado y datos completados automáticamente', 'success');
            } else {
                clearProveedorFields();
                document.getElementById('nombreProveedor').focus();
                showAlert('⚠️ Proveedor no encontrado. Complete los datos manualmente.', 'warning');
            }
        }

        // Función para limpiar campos del proveedor
        function clearProveedorFields() {
            const proveedorFields = ['nombreProveedor', 'telefonoProveedor', 'correoProveedor', 'direccionProveedor'];
            proveedorFields.forEach(fieldId => {
                document.getElementById(fieldId).value = '';
                document.getElementById(fieldId).classList.remove('is-valid', 'is-invalid');
            });
        }

        // Función para validar presupuesto
        function validateBudget() {
            const valorAdjudicado = parseFloat(document.getElementById('valorAdjudicado').value.replace(/,/g, '')) || 0;
            const presupuestoText = document.getElementById('adjudicacion-presupuesto').textContent;
            const presupuesto = parseFloat(presupuestoText.replace(/[$,]/g, '')) || 0;
            const validation = document.getElementById('budgetValidation');
            
            if (valorAdjudicado > presupuesto) {
                validation.innerHTML = '<span class="text-danger"><i class="bi bi-exclamation-triangle me-1"></i>El valor excede el presupuesto inicial</span>';
                document.getElementById('valorAdjudicado').classList.add('is-invalid');
                return false;
            } else if (valorAdjudicado > 0) {
                const diferencia = presupuesto - valorAdjudicado;
                validation.innerHTML = `<span class="text-success"><i class="bi bi-check-circle me-1"></i>Diferencia disponible: ${diferencia.toLocaleString('es-CO')}</span>`;
                document.getElementById('valorAdjudicado').classList.remove('is-invalid');
                return true;
            } else {
                validation.innerHTML = '';
                document.getElementById('valorAdjudicado').classList.remove('is-invalid');
                return true;
            }
        }

        // Función para calcular duración del contrato
        function calculateDuration() {
            const fechaInicio = document.getElementById('fechaInicio').value;
            const fechaFin = document.getElementById('fechaFin').value;
            const duracionElement = document.getElementById('duracionContrato');
            
            if (fechaInicio && fechaFin) {
                const inicio = new Date(fechaInicio);
                const fin = new Date(fechaFin);
                const diferencia = fin - inicio;
                
                if (diferencia > 0) {
                    const dias = Math.ceil(diferencia / (1000 * 60 * 60 * 24));
                    const meses = Math.floor(dias / 30);
                    const diasRestantes = dias % 30;
                    
                    let duracionTexto = `<i class="bi bi-calendar-check me-1"></i>Duración: ${dias} días`;
                    if (meses > 0) {
                        duracionTexto += ` (${meses} mes${meses > 1 ? 'es' : ''}${diasRestantes > 0 ? ' y ' + diasRestantes + ' días' : ''})`;
                    }
                    
                    duracionElement.innerHTML = duracionTexto;
                } else {
                    duracionElement.innerHTML = '<span class="text-danger"><i class="bi bi-exclamation-triangle me-1"></i>La fecha de fin debe ser posterior a la fecha de inicio</span>';
                }
            } else {
                duracionElement.innerHTML = '';
            }
        }

        // Función para guardar adjudicación
        function saveAdjudicacion() {
            const form = document.getElementById('adjudicacionForm');
            const tipoDecision = document.getElementById('tipoDecision').value;
            
            // Validar campos requeridos básicos
            if (!tipoDecision || !document.getElementById('fechaAdjudicacion').value) {
                showAlert('Por favor complete los campos requeridos', 'error');
                return;
            }
            
            // Validar campos específicos según tipo de decisión
            if (tipoDecision === 'adjudicado') {
                if (!validateBudget()) {
                    showAlert('El valor adjudicado excede el presupuesto inicial', 'error');
                    return;
                }
                
                const requiredFields = [
                    'numeroIdentidadProveedor', 
                    'nombreProveedor', 
                    'telefonoProveedor', 
                    'correoProveedor',
                    'numeroContrato', 
                    'valorAdjudicado', 
                    'fechaInicio', 
                    'fechaFin'
                ];
                
                const missingFields = requiredFields.filter(field => !document.getElementById(field).value);
                
                if (missingFields.length > 0) {
                    // Marcar campos faltantes
                    requiredFields.forEach(field => {
                        const element = document.getElementById(field);
                        if (!element.value) {
                            element.classList.add('is-invalid');
                        } else {
                            element.classList.remove('is-invalid');
                        }
                    });
                    
                    showAlert('Por favor complete todos los campos requeridos marcados en rojo', 'error');
                    return;
                }
                
                // Validar formato de email
                const correo = document.getElementById('correoProveedor').value;
                const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                if (!emailRegex.test(correo)) {
                    document.getElementById('correoProveedor').classList.add('is-invalid');
                    showAlert('Por favor ingrese un correo electrónico válido', 'error');
                    return;
                }
            }
            
            // Simular guardado exitoso
            showAlert(`Proceso ${tipoDecision === 'desierto' ? 'declarado desierto' : 'adjudicado'} correctamente`, 'success');
            
            // Cerrar modal
            bootstrap.Modal.getInstance(document.getElementById('adjudicacionModal')).hide();
            
            // Actualizar estado en la tabla (simulado)
            updateProcessStatus();
        }

        // Función para actualizar estado del proceso en la tabla
        function updateProcessStatus() {
            // Aquí se actualizaría la tabla con el nuevo estado
            // Por ahora solo mostramos un mensaje
            setTimeout(() => {
                showAlert('La tabla se ha actualizado con los nuevos datos', 'info');
            }, 500);
        }

        // Función para mostrar alertas mejorada
        function showAlert(message, type = 'info') {
            const alertClass = type === 'success' ? 'alert-success' : 
                             type === 'error' ? 'alert-danger' : 
                             type === 'warning' ? 'alert-warning' : 'alert-info';
            
            const icon = type === 'success' ? 'check-circle' : 
                        type === 'error' ? 'exclamation-triangle' : 
                        type === 'warning' ? 'exclamation-triangle' : 'info-circle';
            
            const alert = document.createElement('div');
            alert.className = `alert ${alertClass} alert-dismissible fade show position-fixed`;
            alert.style.top = '20px';
            alert.style.right = '20px';
            alert.style.zIndex = '9999';
            alert.style.minWidth = '300px';
            alert.innerHTML = `
                <i class="bi bi-${icon} me-2"></i>
                ${message}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            `;
            document.body.appendChild(alert);

            setTimeout(() => {
                alert.remove();
            }, 4000);
        }

        function managePolicies(processId) {
            // Obtener datos del proceso para mostrar en el modal
            const row = document.querySelector(`[onclick="editContract('${processId}')"]`).closest('tr');
            const contractName = row.querySelector('strong').textContent;
            const presupuesto = row.cells[6].textContent;
            
            // Llenar información del proceso
            document.getElementById('polizas-proceso').textContent = processId + ' - ' + contractName;
            document.getElementById('polizas-proveedor').textContent = 'CONSTRUCTORA ABC S.A.S'; // Simular proveedor
            document.getElementById('polizas-valor-contrato').textContent = presupuesto;
            
            // Limpiar formulario
            resetPolizasForm();
            
            // Mostrar modal
            const modal = new bootstrap.Modal(document.getElementById('polizasModal'));
            modal.show();
        }

        // Función para resetear el formulario de pólizas
        function resetPolizasForm() {
            document.getElementById('tipoPoliza').value = '';
            document.getElementById('fechaVigencia').value = '';
            document.getElementById('valorReferencia').value = '';
            document.getElementById('valorReferenciaContainer').style.display = 'none';
            
            // Resetear todas las pólizas
            const polizas = [
                'cumplimiento', 'calidad-servicio', 'calidad-bienes', 'salarios-prestaciones',
                'responsabilidad-civil', 'estabilidad-obra', 'manejo-anticipo', 'devolucion-pago'
            ];
            
            polizas.forEach(poliza => {
                // Desactivar toggle
                const toggle = document.getElementById(`toggle-${poliza}`);
                const content = document.getElementById(`content-${poliza}`);
                
                toggle.className = 'bi bi-toggle-off toggle-inactive';
                content.style.display = 'none';
                
                // Limpiar campos
                document.getElementById(`poliza-${poliza}`).value = '';
                document.getElementById(`porcentaje-${poliza}`).value = '';
                document.getElementById(`valor-${poliza}`).value = '';
            });
            
            updatePolizasResumen();
        }

        // Función para actualizar contexto según tipo de póliza
        function updatePolizaContext() {
            const tipoPoliza = document.getElementById('tipoPoliza').value;
            const valorReferenciaContainer = document.getElementById('valorReferenciaContainer');
            const valorReferencia = document.getElementById('valorReferencia');
            
            if (tipoPoliza) {
                valorReferenciaContainer.style.display = 'block';
                
                // Simular valores según el tipo
                const valores = {
                    'inicial': '$850,000,000',
                    'adicion': '$127,500,000',
                    'prorroga': '$425,000,000'
                };
                
                valorReferencia.value = valores[tipoPoliza] || '$0';
            } else {
                valorReferenciaContainer.style.display = 'none';
            }
        }

        // Función para alternar pólizas activas/inactivas
        function togglePoliza(polizaType) {
            const toggle = document.getElementById(`toggle-${polizaType}`);
            const content = document.getElementById(`content-${polizaType}`);
            
            if (toggle.classList.contains('bi-toggle-off')) {
                // Activar
                toggle.className = 'bi bi-toggle-on toggle-active';
                content.style.display = 'block';
                
                // Animar la aparición
                content.style.opacity = '0';
                content.style.transform = 'translateY(-10px)';
                setTimeout(() => {
                    content.style.transition = 'all 0.3s ease';
                    content.style.opacity = '1';
                    content.style.transform = 'translateY(0)';
                }, 10);
            } else {
                // Desactivar
                toggle.className = 'bi bi-toggle-off toggle-inactive';
                content.style.display = 'none';
                
                // Limpiar campos
                document.getElementById(`poliza-${polizaType}`).value = '';
                document.getElementById(`porcentaje-${polizaType}`).value = '';
                document.getElementById(`valor-${polizaType}`).value = '';
            }
            
            updatePolizasResumen();
        }

        // Función para calcular valor basado en porcentaje
        function calculateValue(polizaType) {
            const porcentaje = parseFloat(document.getElementById(`porcentaje-${polizaType}`).value) || 0;
            const valorReferenciaText = document.getElementById('valorReferencia').value;
            const valorReferencia = parseFloat(valorReferenciaText.replace(/[$,]/g, '')) || 0;
            
            if (porcentaje > 0 && valorReferencia > 0) {
                const valorCalculado = (valorReferencia * porcentaje) / 100;
                document.getElementById(`valor-${polizaType}`).value = valorCalculado.toLocaleString('es-CO');
            } else {
                document.getElementById(`valor-${polizaType}`).value = '';
            }
            
            updatePolizasResumen();
        }

        // Función para actualizar resumen de pólizas
        function updatePolizasResumen() {
            const polizas = [
                'cumplimiento', 'calidad-servicio', 'calidad-bienes', 'salarios-prestaciones',
                'responsabilidad-civil', 'estabilidad-obra', 'manejo-anticipo', 'devolucion-pago'
            ];
            
            const polizasActivas = [];
            let totalValor = 0;
            
            polizas.forEach(poliza => {
                const toggle = document.getElementById(`toggle-${poliza}`);
                if (toggle.classList.contains('bi-toggle-on')) {
                    const nombre = document.getElementById(`toggle-${poliza}`).closest('.poliza-card').querySelector('.poliza-title').textContent.trim();
                    const porcentaje = document.getElementById(`porcentaje-${poliza}`).value || '0';
                    const valor = document.getElementById(`valor-${poliza}`).value || '0';
                    const valorNumerico = parseFloat(valor.replace(/,/g, '')) || 0;
                    
                    polizasActivas.push({
                        nombre: nombre,
                        porcentaje: porcentaje,
                        valor: valor,
                        valorNumerico: valorNumerico
                    });
                    
                    totalValor += valorNumerico;
                }
            });
            
            const resumenDiv = document.getElementById('polizasResumen');
            const resumenContent = document.getElementById('resumenContent');
            
            if (polizasActivas.length > 0) {
                let html = '<div class="row">';
                
                polizasActivas.forEach(poliza => {
                    html += `
                        <div class="col-md-6 mb-2">
                            <strong>${poliza.nombre.replace('P&G ', '')}:</strong> 
                            ${poliza.porcentaje}% - ${poliza.valor}
                        </div>
                    `;
                });
                
                html += `</div>
                <div class="mt-2 pt-2 border-top">
                    <strong>Total Garantías: ${totalValor.toLocaleString('es-CO')}</strong>
                </div>`;
                
                resumenContent.innerHTML = html;
                resumenDiv.style.display = 'block';
            } else {
                resumenDiv.style.display = 'none';
            }
        }

        // Función para cargar valores por defecto
        function loadPolizasDefaults() {
            const tipoPoliza = document.getElementById('tipoPoliza').value;
            
            if (!tipoPoliza) {
                showAlert('Primero seleccione el tipo de póliza', 'warning');
                return;
            }
            
            // Valores por defecto según el tipo de contrato
            const defaults = {
                'cumplimiento': 10,
                'calidad-servicio': 5,
                'calidad-bienes': 20,
                'salarios-prestaciones': 5,
                'responsabilidad-civil': 10,
                'estabilidad-obra': 20,
                'manejo-anticipo': 100,
                'devolucion-pago': 100
            };
            
            // Activar y configurar pólizas comunes
            const polizasComunes = ['cumplimiento', 'responsabilidad-civil'];
            
            if (tipoPoliza === 'inicial') {
                // Para adjudicación inicial, activar las más comunes
                polizasComunes.forEach(poliza => {
                    if (document.getElementById(`toggle-${poliza}`).classList.contains('bi-toggle-off')) {
                        togglePoliza(poliza);
                    }
                    document.getElementById(`porcentaje-${poliza}`).value = defaults[poliza];
                    calculateValue(poliza);
                });
            }
            
            showAlert('Valores por defecto cargados correctamente', 'success');
        }

        // Función para guardar pólizas
        function savePolizas() {
            const tipoPoliza = document.getElementById('tipoPoliza').value;
            const fechaVigencia = document.getElementById('fechaVigencia').value;
            
            if (!tipoPoliza || !fechaVigencia) {
                showAlert('Complete el tipo de póliza y fecha de vigencia', 'error');
                return;
            }
            
            // Verificar que al menos una póliza esté activa
            const polizas = [
                'cumplimiento', 'calidad-servicio', 'calidad-bienes', 'salarios-prestaciones',
                'responsabilidad-civil', 'estabilidad-obra', 'manejo-anticipo', 'devolucion-pago'
            ];
            
            const polizasActivas = polizas.filter(poliza => 
                document.getElementById(`toggle-${poliza}`).classList.contains('bi-toggle-on')
            );
            
            if (polizasActivas.length === 0) {
                showAlert('Debe activar al menos una póliza', 'error');
                return;
            }
            
            // Validar que las pólizas activas tengan datos completos
            let polizasIncompletas = [];
            
            polizasActivas.forEach(poliza => {
                const numeroPoliza = document.getElementById(`poliza-${poliza}`).value;
                const porcentaje = document.getElementById(`porcentaje-${poliza}`).value;
                
                if (!numeroPoliza || !porcentaje) {
                    polizasIncompletas.push(poliza);
                }
            });
            
            if (polizasIncompletas.length > 0) {
                showAlert('Complete el número de póliza y porcentaje en todas las pólizas activas', 'error');
                return;
            }
            
            // Simular guardado exitoso
            showAlert(`Pólizas ${tipoPoliza === 'inicial' ? 'iniciales' : 'de ' + tipoPoliza} guardadas correctamente`, 'success');
            
            // Cerrar modal
            bootstrap.Modal.getInstance(document.getElementById('polizasModal')).hide();
        }

        function manageSupervisors(processId) {
            // Obtener datos del proceso para mostrar en el modal
            const row = document.querySelector(`[onclick="editContract('${processId}')"]`).closest('tr');
            const contractName = row.querySelector('strong').textContent;
            const presupuesto = row.cells[6].textContent;
            
            // Llenar información del proceso
            document.getElementById('supervisores-proceso').textContent = processId + ' - ' + contractName;
            document.getElementById('supervisores-proveedor').textContent = 'CONSTRUCTORA ABC S.A.S'; // Simular proveedor
            document.getElementById('supervisores-valor-contrato').textContent = presupuesto;
            
            // Limpiar formulario
            resetSupervisoresForm();
            
            // Mostrar modal
            const modal = new bootstrap.Modal(document.getElementById('supervisoresModal'));
            modal.show();
        }

        // Variables globales para contadores
        let supervisorCounter = 0;
        let apoyoCounter = 0;

        // Función para resetear el formulario de supervisores
        function resetSupervisoresForm() {
            document.getElementById('supervisores-list').innerHTML = `
                <div class="alert alert-light text-center" id="no-supervisores">
                    <i class="bi bi-person-x fa-2x text-muted mb-2"></i>
                    <p class="mb-0">No hay supervisores asignados</p>
                    <small class="text-muted">Haga clic en "Agregar Supervisor" para comenzar</small>
                </div>
            `;
            
            document.getElementById('apoyo-list').innerHTML = `
                <div class="alert alert-light text-center" id="no-apoyo">
                    <i class="bi bi-person-dash fa-2x text-muted mb-2"></i>
                    <p class="mb-0">No hay personal de apoyo asignado</p>
                    <small class="text-muted">Haga clic en "Agregar Apoyo" para comenzar</small>
                </div>
            `;
            
            supervisorCounter = 0;
            apoyoCounter = 0;
            updateCounters();
        }

        // Función para agregar supervisor
        function addSupervisor() {
            supervisorCounter++;
            addPersona('supervisores', 'Supervisor', supervisorCounter, 'supervisor');
        }

        // Función para agregar apoyo
        function addApoyo() {
            apoyoCounter++;
            addPersona('apoyo', 'Apoyo a la Supervisión', apoyoCounter, 'apoyo_supervision');
        }

        // Función genérica para agregar persona
        function addPersona(type, title, number, rol) {
            const listId = type + '-list';
            const noDataId = 'no-' + type;
            
            // Ocultar mensaje de "no hay datos"
            const noDataElement = document.getElementById(noDataId);
            if (noDataElement) {
                noDataElement.style.display = 'none';
            }
            
            // Clonar template
            const template = document.getElementById('supervisor-template');
            const clone = template.cloneNode(true);
            clone.id = `${type}-${number}`;
            clone.style.display = 'block';
            
            // Personalizar el clone
            clone.querySelector('.supervisor-title').textContent = title;
            clone.querySelector('.supervisor-number').textContent = number;
            clone.querySelector('.supervisor-rol').value = rol;
            
            // Agregar IDs únicos a los campos
            const inputs = clone.querySelectorAll('input, select');
            inputs.forEach(input => {
                const baseClass = input.className.split(' ').find(cls => cls.startsWith('supervisor-'));
                if (baseClass) {
                    input.id = `${type}-${number}-${baseClass.replace('supervisor-', '')}`;
                }
            });
            
            // Agregar al contenedor
            document.getElementById(listId).appendChild(clone);
            
            // Actualizar contadores
            updateCounters();
            
            // Animar la entrada
            setTimeout(() => {
                clone.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
            }, 100);
        }

        // Función para remover supervisor/apoyo
        function removeSupervisor(button) {
            const card = button.closest('.supervisor-card');
            const listContainer = card.parentElement;
            
            // Animar salida
            card.classList.add('removing');
            
            setTimeout(() => {
                card.remove();
                
                // Verificar si queda alguna tarjeta
                const remainingCards = listContainer.querySelectorAll('.supervisor-card:not(.removing)');
                if (remainingCards.length === 0) {
                    // Mostrar mensaje de "no hay datos"
                    const listId = listContainer.id;
                    if (listId === 'supervisores-list') {
                        listContainer.innerHTML = `
                            <div class="alert alert-light text-center" id="no-supervisores">
                                <i class="bi bi-person-x fa-2x text-muted mb-2"></i>
                                <p class="mb-0">No hay supervisores asignados</p>
                                <small class="text-muted">Haga clic en "Agregar Supervisor" para comenzar</small>
                            </div>
                        `;
                    } else {
                        listContainer.innerHTML = `
                            <div class="alert alert-light text-center" id="no-apoyo">
                                <i class="bi bi-person-dash fa-2x text-muted mb-2"></i>
                                <p class="mb-0">No hay personal de apoyo asignado</p>
                                <small class="text-muted">Haga clic en "Agregar Apoyo" para comenzar</small>
                            </div>
                        `;
                    }
                }
                
                updateCounters();
            }, 300);
        }

        // Función para buscar supervisor en base de datos
        function buscarSupervisor(input) {
            const cedula = input.value;
            const card = input.closest('.supervisor-card');
            
            if (!cedula) {
                clearSupervisorFields(card);
                return;
            }
            
            // Base de datos simulada de supervisores
            const supervisores = {
                '12345678': {
                    nombre: 'Juan Carlos',
                    apellido: 'Pérez Martínez',
                    correo: 'juan.perez@empresa.com'
                },
                '87654321': {
                    nombre: 'María Elena',
                    apellido: 'González López',
                    correo: 'maria.gonzalez@empresa.com'
                },
                '11223344': {
                    nombre: 'Carlos Alberto',
                    apellido: 'Rodríguez Silva',
                    correo: 'carlos.rodriguez@empresa.com'
                },
                '44332211': {
                    nombre: 'Ana Patricia',
                    apellido: 'Jiménez Torres',
                    correo: 'ana.jimenez@empresa.com'
                }
            };
            
            if (supervisores[cedula]) {
                const supervisor = supervisores[cedula];
                
                // Llenar campos automáticamente
                card.querySelector('.supervisor-nombre').value = supervisor.nombre;
                card.querySelector('.supervisor-apellido').value = supervisor.apellido;
                card.querySelector('.supervisor-correo').value = supervisor.correo;
                
                // Marcar campos como válidos
                const fields = ['.supervisor-nombre', '.supervisor-apellido', '.supervisor-correo'];
                fields.forEach(selector => {
                    const field = card.querySelector(selector);
                    field.classList.add('is-valid');
                    setTimeout(() => field.classList.remove('is-valid'), 2000);
                });
                
                showAlert('✅ Supervisor encontrado y datos completados automáticamente', 'success');
            } else {
                clearSupervisorFields(card);
                card.querySelector('.supervisor-nombre').focus();
                showAlert('⚠️ Supervisor no encontrado. Complete los datos manualmente.', 'warning');
            }
        }

        // Función para limpiar campos del supervisor
        function clearSupervisorFields(card) {
            const fields = ['.supervisor-nombre', '.supervisor-apellido', '.supervisor-correo'];
            fields.forEach(selector => {
                const field = card.querySelector(selector);
                field.value = '';
                field.classList.remove('is-valid', 'is-invalid');
            });
        }

        // Función para actualizar nombre del centro de costos
        function updateNombreCentro(select) {
            const card = select.closest('.supervisor-card');
            const nombreCentro = card.querySelector('.supervisor-nombre-centro');
            
            const centros = {
                '001': 'Administración General',
                '002': 'Operaciones',
                '003': 'Mantenimiento', 
                '004': 'Sistemas',
                '005': 'Seguridad',
                '006': 'Servicios Generales'
            };
            
            nombreCentro.value = centros[select.value] || '';
        }

        // Función para actualizar contadores
        function updateCounters() {
            const supervisoresCount = document.querySelectorAll('#supervisores-list .supervisor-card:not(.removing)').length;
            const apoyoCount = document.querySelectorAll('#apoyo-list .supervisor-card:not(.removing)').length;
            
            document.getElementById('supervisores-count').textContent = supervisoresCount;
            document.getElementById('apoyo-count').textContent = apoyoCount;
        }

        // Función para validar supervisores
        function validateSupervisores() {
            const allCards = document.querySelectorAll('.supervisor-card:not(.removing)');
            let validCount = 0;
            let invalidCount = 0;
            
            allCards.forEach(card => {
                const requiredFields = [
                    '.supervisor-cedula',
                    '.supervisor-nombre', 
                    '.supervisor-apellido',
                    '.supervisor-correo',
                    '.supervisor-centro-costos'
                ];
                
                let cardValid = true;
                
                requiredFields.forEach(selector => {
                    const field = card.querySelector(selector);
                    if (!field.value.trim()) {
                        field.classList.add('is-invalid');
                        cardValid = false;
                    } else {
                        field.classList.remove('is-invalid');
                        field.classList.add('is-valid');
                    }
                });
                
                // Validar email
                const email = card.querySelector('.supervisor-correo');
                if (email.value && !/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email.value)) {
                    email.classList.add('is-invalid');
                    cardValid = false;
                }
                
                // Marcar tarjeta como válida/inválida
                card.classList.remove('valid', 'invalid');
                if (cardValid) {
                    card.classList.add('valid');
                    validCount++;
                } else {
                    card.classList.add('invalid');
                    invalidCount++;
                }
            });
            
            if (invalidCount > 0) {
                showAlert(`❌ ${invalidCount} tarjeta(s) con errores. Revise los campos marcados en rojo.`, 'error');
            } else if (validCount > 0) {
                showAlert(`✅ Todos los ${validCount} supervisor(es) tienen datos válidos.`, 'success');
            } else {
                showAlert('⚠️ No hay supervisores para validar.', 'warning');
            }
        }

        // Función para guardar supervisores
        function saveSupervisores() {
            const allCards = document.querySelectorAll('.supervisor-card:not(.removing)');
            
            if (allCards.length === 0) {
                showAlert('Debe agregar al menos un supervisor o apoyo a la supervisión', 'error');
                return;
            }
            
            // Validar antes de guardar
            validateSupervisores();
            
            // Verificar que no hay tarjetas inválidas
            const invalidCards = document.querySelectorAll('.supervisor-card.invalid');
            if (invalidCards.length > 0) {
                showAlert('No se puede guardar. Hay tarjetas con errores. Use "Validar Datos" para revisar.', 'error');
                return;
            }
            
            // Simular guardado exitoso
            const supervisoresCount = document.querySelectorAll('#supervisores-list .supervisor-card:not(.removing)').length;
            const apoyoCount = document.querySelectorAll('#apoyo-list .supervisor-card:not(.removing)').length;
            
            showAlert(`✅ Guardado exitoso: ${supervisoresCount} supervisor(es) y ${apoyoCount} apoyo(s) asignados`, 'success');
            
            // Cerrar modal
            bootstrap.Modal.getInstance(document.getElementById('supervisoresModal')).hide();
        }

        function viewInvoicing(processId) {
            alert(`Ver facturación para proceso: ${processId}`);
            // Aquí iría la lógica para mostrar la facturación
        }

        // Función para guardar nuevo contrato
        function saveNewContract() {
            const form = document.getElementById('newContractForm');
            const formData = new FormData(form);
            
            // Validar campos requeridos
            const requiredFields = form.querySelectorAll('[required]');
            let isValid = true;
            
            requiredFields.forEach(field => {
                if (!field.value.trim()) {
                    field.classList.add('is-invalid');
                    isValid = false;
                } else {
                    field.classList.remove('is-invalid');
                }
            });
            
            if (isValid) {
                // Aquí iría la lógica para guardar en el backend
                showSaveAlert('Proceso creado correctamente');
                bootstrap.Modal.getInstance(document.getElementById('newContractModal')).hide();
                form.reset();
                generateProcessNumber();
            } else {
                alert('Por favor, complete todos los campos requeridos.');
            }
        }

        // Funciones de filtrado
        function filterByStatus(status) {
            const statusFilter = document.getElementById('statusFilter');
            statusFilter.value = status;
            applyFilters();
        }

        function applyFilters() {
            const searchTerm = document.getElementById('searchInput').value.toLowerCase();
            const statusFilter = document.getElementById('statusFilter').value;
            const typeFilter = document.getElementById('typeFilter').value;
            const rows = document.querySelectorAll('.contrato-table tbody tr');

            rows.forEach(row => {
                const text = row.textContent.toLowerCase();
                const status = row.getAttribute('data-status');
                const type = row.getAttribute('data-type');

                const matchesSearch = text.includes(searchTerm);
                const matchesStatus = !statusFilter || status === statusFilter;
                const matchesType = !typeFilter || type === typeFilter;

                if (matchesSearch && matchesStatus && matchesType) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });

            // Actualizar vista en cuadrícula si está activa
            const gridView = document.querySelector('.grid-view');
            if (gridView && gridView.style.display !== 'none') {
                updateGridView();
            }
        }

        function clearFilters() {
            document.getElementById('searchInput').value = '';
            document.getElementById('statusFilter').value = '';
            document.getElementById('typeFilter').value = '';
            applyFilters();
        }

        // Función para cambiar vista
        function toggleView(viewType) {
            const buttons = document.querySelectorAll('.view-btn');
            buttons.forEach(btn => btn.classList.remove('active'));

            const tableContainer = document.querySelector('.table-responsive');

            if (viewType === 'list') {
                buttons[0].classList.add('active');
                tableContainer.style.display = 'block';
                const gridView = document.querySelector('.grid-view');
                if (gridView) gridView.style.display = 'none';
            } else {
                buttons[1].classList.add('active');
                tableContainer.style.display = 'none';

                let gridView = document.querySelector('.grid-view');
                if (!gridView) {
                    gridView = createGridView();
                    tableContainer.parentNode.appendChild(gridView);
                } else {
                    gridView.style.display = 'block';
                }
            }
        }

        // Crear vista en cuadrícula
        function createGridView() {
            const gridContainer = document.createElement('div');
            gridContainer.className = 'grid-view';
            gridContainer.innerHTML = `
                <div class="row" id="contractsGrid">
                    <!-- Las tarjetas se generan dinámicamente -->
                </div>
            `;

            updateGridView(gridContainer);
            return gridContainer;
        }

        // Actualizar vista en cuadrícula
        function updateGridView(gridContainer = null) {
            if (!gridContainer) {
                gridContainer = document.querySelector('.grid-view');
            }
            
            if (!gridContainer) return;

            const gridRow = gridContainer.querySelector('#contractsGrid');
            const tableRows = document.querySelectorAll('.contrato-table tbody tr');
            
            let gridHTML = '';
            
            tableRows.forEach(row => {
                if (row.style.display !== 'none') {
                    const processCode = row.querySelector('.badge-proceso').textContent;
                    const contractName = row.querySelector('strong').textContent;
                    const vigencia = row.querySelector('small').textContent;
                    const objeto = row.cells[2].textContent;
                    const modalidad = row.querySelector('.badge-modalidad').textContent;
                    const tipo = row.querySelector('.badge-tipo').textContent;
                    const area = row.cells[5].textContent;
                    const presupuesto = row.cells[6].textContent;
                    const statusBadge = row.querySelector('td:nth-child(8) .badge-custom');
                    const status = statusBadge.textContent;
                    const statusClass = statusBadge.className.includes('badge-pendiente') ? 'warning' :
                                      statusBadge.className.includes('badge-adjudicado') ? 'success' : 'danger';
                    
                    const actions = getActionsForCard(processCode, status);
                    
                    gridHTML += `
                        <div class="col-lg-4 col-md-6 mb-4">
                            <div class="card h-100 shadow-sm contract-card">
                                <div class="card-header bg-${statusClass === 'warning' ? 'warning' : statusClass === 'success' ? 'primary' : 'danger'} text-white d-flex justify-content-between align-items-center">
                                    <h6 class="mb-0">${contractName}</h6>
                                    <span class="badge bg-${statusClass === 'warning' ? 'dark' : 'light'} ${statusClass === 'warning' ? 'text-dark' : 'text-dark'}">${status}</span>
                                </div>
                                <div class="card-body">
                                    <div class="mb-2">
                                        <span class="badge badge-custom badge-proceso mb-2">${processCode}</span>
                                    </div>
                                    <p class="card-text mb-3">
                                        <strong>Objeto:</strong> ${objeto.length > 80 ? objeto.substring(0, 80) + '...' : objeto}
                                    </p>
                                    <div class="contract-details">
                                        <div class="detail-row">
                                            <strong>Modalidad:</strong> 
                                            <span class="badge badge-custom badge-modalidad ms-1">${modalidad}</span>
                                        </div>
                                        <div class="detail-row">
                                            <strong>Tipo:</strong> 
                                            <span class="badge badge-custom badge-tipo ms-1">${tipo}</span>
                                        </div>
                                        <div class="detail-row">
                                            <strong>Área:</strong> ${area}
                                        </div>
                                        <div class="detail-row">
                                            <strong>Presupuesto:</strong> ${presupuesto}
                                        </div>
                                        <div class="detail-row">
                                            <small class="text-muted">${vigencia}</small>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-footer bg-light">
                                    <div class="btn-actions justify-content-center">
                                        ${actions}
                                    </div>
                                </div>
                            </div>
                        </div>
                    `;
                }
            });

            if (gridHTML === '') {
                gridHTML = `
                    <div class="col-12 text-center py-5">
                        <i class="bi bi-file-earmark-text fa-3x text-muted mb-3" style="font-size: 3rem;"></i>
                        <h5 class="text-muted">No hay procesos que coincidan con los filtros</h5>
                        <p class="text-muted">Ajusta los filtros o crea un nuevo proceso</p>
                    </div>
                `;
            }

            gridRow.innerHTML = gridHTML;
        }

        // Obtener acciones para las tarjetas
        function getActionsForCard(processCode, status) {
            let actions = `
                <button class="btn btn-action btn-edit" onclick="editContract('${processCode}')" title="Editar">
                    <i class="bi bi-pencil me-1"></i>Editar
                </button>
            `;

            if (status === 'Pendiente') {
                actions += `
                    <button class="btn btn-action btn-adjudicar" onclick="adjudicarContract('${processCode}')" title="Adjudicar">
                        <i class="bi bi-gavel me-1"></i>Adjudicar
                    </button>
                `;
            } else if (status === 'Adjudicado') {
                actions += `
                    <button class="btn btn-action btn-polizas" onclick="managePolicies('${processCode}')" title="Gestionar Pólizas">
                        <i class="bi bi-shield-check me-1"></i>Pólizas
                    </button>
                    <button class="btn btn-action btn-supervisores" onclick="manageSupervisors('${processCode}')" title="Asignar Supervisores">
                        <i class="bi bi-people me-1"></i>Supervisores
                    </button>
                    <button class="btn btn-action btn-facturacion" onclick="viewInvoicing('${processCode}')" title="Ver Facturación">
                        <i class="bi bi-receipt me-1"></i>Facturación
                    </button>
                `;
            } else if (status === 'Desierto') {
                actions += `
                    <button class="btn btn-action btn-adjudicar" onclick="adjudicarContract('${processCode}')" title="Reabrir Proceso">
                        <i class="bi bi-arrow-repeat me-1"></i>Reabrir
                    </button>
                `;
            }

            return actions;
        }

        // Función para mostrar alertas
        function showSaveAlert(message) {
            const alert = document.createElement('div');
            alert.className = 'alert alert-success alert-dismissible fade show position-fixed';
            alert.style.top = '20px';
            alert.style.right = '20px';
            alert.style.zIndex = '9999';
            alert.innerHTML = `
                <i class="bi bi-check-circle me-2"></i>
                ${message}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            `;
            document.body.appendChild(alert);

            setTimeout(() => {
                alert.remove();
            }, 3000);
        }

        // Event listeners
        document.addEventListener('DOMContentLoaded', function() {
            // Animaciones suaves al cargar
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

            // Event listeners para filtros
            document.getElementById('searchInput').addEventListener('input', applyFilters);
            document.getElementById('statusFilter').addEventListener('change', applyFilters);
            document.getElementById('typeFilter').addEventListener('change', applyFilters);

            // Formatear números en campos de valor
            const valorFields = ['valorAdjudicado', 'valorAdjudicadoUnitario'];
            valorFields.forEach(fieldId => {
                const field = document.getElementById(fieldId);
                if (field) {
                    field.addEventListener('input', function(e) {
                        let value = e.target.value.replace(/\D/g, '');
                        if (value) {
                            e.target.value = parseInt(value).toLocaleString('es-CO');
                        }
                    });
                }
            });

            // Validación en tiempo real para email
            const correoProveedor = document.getElementById('correoProveedor');
            if (correoProveedor) {
                correoProveedor.addEventListener('blur', function(e) {
                    const email = e.target.value;
                    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                    
                    if (email && !emailRegex.test(email)) {
                        e.target.classList.add('is-invalid');
                        e.target.classList.remove('is-valid');
                    } else if (email) {
                        e.target.classList.remove('is-invalid');
                        e.target.classList.add('is-valid');
                        setTimeout(() => e.target.classList.remove('is-valid'), 2000);
                    } else {
                        e.target.classList.remove('is-invalid', 'is-valid');
                    }
                });
            }

            // Formateo automático para teléfono
            const telefonoProveedor = document.getElementById('telefonoProveedor');
            if (telefonoProveedor) {
                telefonoProveedor.addEventListener('input', function(e) {
                    // Permitir solo números, espacios, + y -
                    let value = e.target.value.replace(/[^\d\s\+\-]/g, '');
                    e.target.value = value;
                });
            }
        });

        // Función para formatear números como moneda
        function formatCurrency(amount) {
            return new Intl.NumberFormat('es-CO', {
                style: 'currency',
                currency: 'COP',
                minimumFractionDigits: 0
            }).format(amount);
        }

        // Función para exportar a Excel
        function exportToExcel() {
            alert('Exportando a Excel... Función por implementar');
            // Aquí iría la lógica para exportar los datos filtrados a Excel
        }

        // Agregar event listener al botón de Excel
        document.addEventListener('DOMContentLoaded', function() {
            const excelButton = document.querySelector('.btn-outline-success');
            if (excelButton) {
                excelButton.addEventListener('click', exportToExcel);
            }
        });
    </script>
</body>