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

	.stats-card.blue {
		background: linear-gradient(135deg, #17a2b8 0%, #138496 100%);
		color: white;
	}

	.stats-card.orange {
		background: linear-gradient(135deg, #fd7e14 0%, #e8590c 100%);
		color: white;
	}

	.stats-card.green {
		background: linear-gradient(135deg, #28a745 0%, #1e7e34 100%);
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

	/* Tabla de Órdenes */
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

	.ordenes-table {
		margin: 0;
	}

	.ordenes-table thead th {
		background-color: #f8f9fa;
		border: none;
		padding: 1rem 1.5rem;
		font-weight: 600;
		color: #495057;
		font-size: 0.85rem;
		text-transform: uppercase;
		letter-spacing: 0.5px;
	}

	.ordenes-table tbody td {
		padding: 1rem 1.5rem;
		vertical-align: middle;
		border: none;
		border-bottom: 1px solid #f1f3f4;
	}

	.ordenes-table tbody tr:hover {
		background-color: #f8f9fa;
	}

	/* Badges */
	.badge-custom {
		padding: 0.5rem 0.75rem;
		border-radius: 6px;
		font-size: 0.75rem;
		font-weight: 500;
	}

	.badge-documento {
		background-color: #e9ecef;
		color: #495057;
		font-family: monospace;
		font-weight: 600;
	}

	.badge-pedido {
		background-color: #17a2b8;
		color: white;
	}

	.badge-recibir {
		background-color: #fd7e14;
		color: white;
	}

	.badge-recibido {
		background-color: #28a745;
		color: white;
	}

	.badge-quincena {
		background-color: #6c757d;
		color: white;
		font-size: 0.7rem;
	}

	.badge-remision {
		background-color: #6f42c1;
		color: white;
		font-size: 0.7rem;
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
</style>

<body>
    <!-- Navbar Superior -->
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
                <a class="nav-link" href="<?php echo SERVERURL?>pedidos/">
                    <i class="bi bi-clipboard-check me-1"></i>
                    Pedido Suministros
                </a>
                <a class="nav-link active" href="<?php echo SERVERURL?>proveedores/">
                    <i class="bi bi-cart-check me-1"></i>
                    Órdenes de Compra
                </a>
                <a class="nav-link" href="<?php echo SERVERURL?>inventarios/">
                    <i class="bi bi-calculator"></i>
                    Inventario y Facturación
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
                <div class="sidebar-header">ÓRDENES DE COMPRA</div>
                <nav class="nav flex-column">
                    <a class="nav-link active" href="<?php echo SERVERURL?>proveedores/">
                        <i class="bi bi-cart-check"></i>
                        Lista de Órdenes
                    </a>
                    <a class="nav-link" href="#" onclick="alert('Próximamente')">
                        <i class="bi bi-plus-circle"></i>
                        Consolidar Pedidos
                    </a>
                    <a class="nav-link" href="#" onclick="alert('Próximamente')">
                        <i class="bi bi-clock-history"></i>
                        Historial de Órdenes
                    </a>
                </nav>

                <hr>

                <div class="sidebar-header">SEGUIMIENTO</div>
                <nav class="nav flex-column">
                    <a class="nav-link" href="#" onclick="alert('Próximamente')">
                        <i class="bi bi-send"></i>
                        Estado: Pedido
                    </a>
                    <a class="nav-link" href="#" onclick="alert('Próximamente')">
                        <i class="bi bi-hourglass-split"></i>
                        Estado: Recibir
                    </a>
                    <a class="nav-link" href="#" onclick="alert('Próximamente')">
                        <i class="bi bi-check-circle"></i>
                        Estado: Recibido
                    </a>
                </nav>

                <hr>

                <div class="sidebar-header">GESTIÓN</div>
                <nav class="nav flex-column">
                    <a class="nav-link" href="#" onclick="alert('Próximamente')">
                        <i class="bi bi-inbox"></i>
                        Registrar Remisiones
                    </a>
                    <a class="nav-link" href="#" onclick="alert('Próximamente')">
                        <i class="bi bi-truck"></i>
                        Seguimiento Entregas
                    </a>
                </nav>

                <hr>

                <div class="sidebar-header">REPORTES</div>
                <nav class="nav flex-column">
                    <a class="nav-link" href="#" onclick="alert('Próximamente')">
                        <i class="bi bi-file-earmark-bar-graph"></i>
                        Reporte de Órdenes
                    </a>
                    <a class="nav-link" href="#" onclick="alert('Próximamente')">
                        <i class="bi bi-file-earmark-text"></i>
                        Reporte por Proveedor
                    </a>
                    <a class="nav-link" href="#" onclick="alert('Próximamente')">
                        <i class="bi bi-calendar2-week"></i>
                        Reporte de Entregas
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
                    <li class="breadcrumb-item active">Gestión de Órdenes de Compra</li>
                </ol>
            </nav>

            <!-- Header -->
            <div class="page-header">
                <div>
                    <h2 class="page-title">
                        <i class="bi bi-cart-check me-2"></i>
                        Gestión de Órdenes de Compra
                    </h2>
                    <p class="text-muted mb-0">Administra las órdenes de compra consolidadas por proveedor</p>
                </div>
                <button class="btn btn-primary">
                    <i class="bi bi-plus me-1"></i>
                    Consolidar Pedidos
                </button>
            </div>

            <!-- Tarjetas de Estadísticas -->
            <div class="row stats-row">
                <div class="col-lg-3 col-md-6 mb-3">
                    <div class="stats-card purple">
                        <div class="stats-content">
                            <div class="stats-number">12</div>
                            <div class="stats-label">Total Órdenes</div>
                        </div>
                        <div class="stats-icon">
                            <i class="bi bi-cart3"></i>
                        </div>
                    </div>
                </div>

                <div class="col-lg-3 col-md-6 mb-3">
                    <div class="stats-card blue">
                        <div class="stats-content">
                            <div class="stats-number">4</div>
                            <div class="stats-label">Estado: Pedido</div>
                        </div>
                        <div class="stats-icon">
                            <i class="bi bi-send"></i>
                        </div>
                    </div>
                </div>

                <div class="col-lg-3 col-md-6 mb-3">
                    <div class="stats-card orange">
                        <div class="stats-content">
                            <div class="stats-number">5</div>
                            <div class="stats-label">Estado: Recibir</div>
                        </div>
                        <div class="stats-icon">
                            <i class="bi bi-hourglass-split"></i>
                        </div>
                    </div>
                </div>

                <div class="col-lg-3 col-md-6 mb-3">
                    <div class="stats-card green">
                        <div class="stats-content">
                            <div class="stats-number">3</div>
                            <div class="stats-label">Estado: Recibido</div>
                        </div>
                        <div class="stats-icon">
                            <i class="bi bi-check-circle"></i>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tabla de Órdenes -->
            <div class="table-container">
                <!-- Header de la tabla -->
                <div class="table-header">
                    <h5 class="table-title">
                        <i class="bi bi-table"></i>
                        Órdenes de Compra Creadas
                        <span class="badge bg-light text-dark ms-2">12</span>
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
                        <div class="col-md-3">
                            <select class="form-select">
                                <option value="">Proveedor:</option>
                                <option value="balder">BALDER</option>
                                <option value="union-temporal">UNION TEMPORAL CONTIMODULAR</option>
                                <option value="inversiones">INVERSIONES Y SUMINISTROS RL</option>
                                <option value="tu-importaciones">T&J IMPORTACIONES S.A.S</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <select class="form-select">
                                <option value="">Estado:</option>
                                <option value="pedido">Pedido</option>
                                <option value="recibir">Recibir</option>
                                <option value="recibido">Recibido</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <select class="form-select">
                                <option value="">Proceso:</option>
                                <option value="consolidacion">Consolidación</option>
                                <option value="envio">Envío</option>
                                <option value="recepcion">Recepción</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <input type="text" class="form-control" placeholder="Buscar orden, proveedor...">
                        </div>
                        <div class="col-md-2">
                            <select class="form-select">
                                <option value="">Centro:</option>
                                <option value="compras">COMPRAS E INVENTARIOS</option>
                                <option value="bienestar">OFICINA DE BIENESTAR</option>
                                <option value="educativas">AYUDAS EDUCATIVAS</option>
                            </select>
                        </div>
                    </div>
                    <div class="row g-3 mt-2">
                        <div class="col-md-6">
                            <div class="row g-2">
                                <div class="col-md-6">
                                    <label for="fecha-inicio" class="form-label small">Fecha inicio</label>
                                    <input type="date" class="form-control form-control-sm" id="fecha-inicio">
                                </div>
                                <div class="col-md-6">
                                    <label for="fecha-fin" class="form-label small">Fecha fin</label>
                                    <input type="date" class="form-control form-control-sm" id="fecha-fin">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 text-end d-flex align-items-end gap-2">
                            <button class="btn btn-outline-primary">
                                <i class="bi bi-funnel me-1"></i>
                                Filtrar
                            </button>
                            <button class="btn btn-outline-secondary">
                                <i class="bi bi-arrow-clockwise me-1"></i>
                                Limpiar
                            </button>
                            <button class="btn btn-outline-success">
                                <i class="bi bi-file-earmark-excel me-1"></i>
                                Excel
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Tabla -->
                <div class="table-responsive">
                    <table class="table ordenes-table">
                        <thead>
                            <tr>
                                <th>Orden</th>
                                <th>F. Creación / Quincena</th>
                                <th>Proveedor</th>
                                <th>Centro de Costos</th>
                                <th>Remisiones</th>
                                <th>Estado</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>
                                    <span class="badge-custom badge-documento">OC-2025-001</span>
                                </td>
                                <td>
                                    <div>
                                        <strong>2025-06-04 15:30:00</strong>
                                        <br>
                                        <span class="badge-custom badge-quincena">Quincena: 1</span>
                                    </div>
                                </td>
                                <td>
                                    <div>
                                        <strong>901349914 BALDER</strong>
                                        <br>
                                        <small class="text-muted">NIT: 901349914-5</small>
                                    </div>
                                </td>
                                <td>
                                    <div>
                                        <strong>COMPRAS E INVENTARIOS</strong>
                                        <br>
                                        <small class="text-muted">S BL 9 101 MEDELLIN</small>
                                    </div>
                                </td>
                                <td>
                                    <div>
                                        <small class="text-muted">Sin remisiones</small>
                                    </div>
                                </td>
                                <td>
                                    <span class="badge-custom badge-pedido">Pedido</span>
                                </td>
                                <td>
                                    <div class="btn-actions">
                                        <button class="btn btn-outline-primary btn-action" title="Ver Detalle">
                                            <i class="bi bi-eye"></i>
                                        </button>
                                        <button class="btn btn-outline-secondary btn-action" title="Editar">
                                            <i class="bi bi-pencil"></i>
                                        </button>
                                        <button class="btn btn-outline-info btn-action" title="Enviar a Proveedor">
                                            <i class="bi bi-send"></i>
                                        </button>
                                        <button class="btn btn-outline-success btn-action" title="Registrar Remisión">
                                            <i class="bi bi-inbox"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <span class="badge-custom badge-documento">OC-2025-002</span>
                                </td>
                                <td>
                                    <div>
                                        <strong>2025-05-30 10:15:22</strong>
                                        <br>
                                        <span class="badge-custom badge-quincena">Quincena: 2</span>
                                    </div>
                                </td>
                                <td>
                                    <div>
                                        <strong>901846601 UNION TEMPORAL</strong>
                                        <br>
                                        <small class="text-muted">CONTIMODULAR ANTIOQUIA</small>
                                    </div>
                                </td>
                                <td>
                                    <div>
                                        <strong>OFICINA DE BIENESTAR INSTITUCIONAL</strong>
                                        <br>
                                        <small class="text-muted">1 BL 10 105 MEDELLIN</small>
                                    </div>
                                </td>
                                <td>
                                    <div>
                                        <span class="badge-custom badge-remision">REM-001</span>
                                        <br>
                                        <small class="text-muted">Parcial</small>
                                    </div>
                                </td>
                                <td>
                                    <span class="badge-custom badge-recibir">Recibir</span>
                                </td>
                                <td>
                                    <div class="btn-actions">
                                        <button class="btn btn-outline-primary btn-action" title="Ver Detalle">
                                            <i class="bi bi-eye"></i>
                                        </button>
                                        <button class="btn btn-outline-secondary btn-action" title="Editar">
                                            <i class="bi bi-pencil"></i>
                                        </button>
                                        <button class="btn btn-outline-warning btn-action" title="Ver Remisiones">
                                            <i class="bi bi-list-ul"></i>
                                        </button>
                                        <button class="btn btn-outline-success btn-action" title="Confirmar Recepción">
                                            <i class="bi bi-check-circle"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <span class="badge-custom badge-documento">OC-2025-003</span>
                                </td>
                                <td>
                                    <div>
                                        <strong>2025-05-28 14:20:18</strong>
                                        <br>
                                        <span class="badge-custom badge-quincena">Quincena: 2</span>
                                    </div>
                                </td>
                                <td>
                                    <div>
                                        <strong>901311809 INVERSIONES Y SUMINISTROS</strong>
                                        <br>
                                        <small class="text-muted">RL S.A.S</small>
                                    </div>
                                </td>
                                <td>
                                    <div>
                                        <strong>COMPRAS E INVENTARIOS</strong>
                                        <br>
                                        <small class="text-muted">S BL 9 101 MEDELLIN</small>
                                    </div>
                                </td>
                                <td>
                                    <div>
                                        <span class="badge-custom badge-remision">REM-002</span>
                                        <span class="badge-custom badge-remision">REM-003</span>
                                        <br>
                                        <small class="text-muted">Completo</small>
                                    </div>
                                </td>
                                <td>
                                    <span class="badge-custom badge-recibido">Recibido</span>
                                </td>
                                <td>
                                    <div class="btn-actions">
                                        <button class="btn btn-outline-primary btn-action" title="Ver Detalle">
                                            <i class="bi bi-eye"></i>
                                        </button>
                                        <button class="btn btn-outline-info btn-action" title="Ver Remisiones">
                                            <i class="bi bi-list-ul"></i>
                                        </button>
                                        <button class="btn btn-outline-success btn-action" title="Ver Recepción">
                                            <i class="bi bi-clipboard-check"></i>
                                        </button>
                                        <button class="btn btn-outline-secondary btn-action" title="Imprimir">
                                            <i class="bi bi-printer"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <span class="badge-custom badge-documento">OC-2025-004</span>
                                </td>
                                <td>
                                    <div>
                                        <strong>2025-05-27 16:45:33</strong>
                                        <br>
                                        <span class="badge-custom badge-quincena">Quincena: 2</span>
                                    </div>
                                </td>
                                <td>
                                    <div>
                                        <strong>900181068 T&J IMPORTACIONES</strong>
                                        <br>
                                        <small class="text-muted">S.A.S</small>
                                    </div>
                                </td>
                                <td>
                                    <div>
                                        <strong>AYUDAS EDUCATIVAS</strong>
                                        <br>
                                        <small class="text-muted">1 BL 3 104 MEDELLIN</small>
                                    </div>
                                </td>
                                <td>
                                    <div>
                                        <span class="badge-custom badge-remision">REM-004</span>
                                        <br>
                                        <small class="text-muted">Parcial</small>
                                    </div>
                                </td>
                                <td>
                                    <span class="badge-custom badge-recibir">Recibir</span>
                                </td>
                                <td>
                                    <div class="btn-actions">
                                        <button class="btn btn-outline-primary btn-action" title="Ver Detalle">
                                            <i class="bi bi-eye"></i>
                                        </button>
                                        <button class="btn btn-outline-secondary btn-action" title="Editar">
                                            <i class="bi bi-pencil"></i>
                                        </button>
                                        <button class="btn btn-outline-warning btn-action" title="Ver Remisiones">
                                            <i class="bi bi-list-ul"></i>
                                        </button>
                                        <button class="btn btn-outline-success btn-action" title="Confirmar Recepción">
                                            <i class="bi bi-check-circle"></i>
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


    <script>
        // Función para cambiar vista
        function toggleView(viewType) {
            const buttons = document.querySelectorAll('.view-btn');
            buttons.forEach(btn => btn.classList.remove('active'));

            const tableContainer = document.querySelector('.table-responsive');

            if (viewType === 'list') {
                buttons[0].classList.add('active');
                tableContainer.style.display = 'block';
                // Ocultar vista cuadrícula si existe
                const gridView = document.querySelector('.grid-view');
                if (gridView) gridView.style.display = 'none';
            } else {
                buttons[1].classList.add('active');
                tableContainer.style.display = 'none';

                // Crear vista en cuadrícula si no existe
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
                <div class="row">
                    <div class="col-lg-4 col-md-6 mb-4">
                        <div class="card h-100 shadow-sm">
                            <div class="card-header bg-info text-white d-flex justify-content-between align-items-center">
                                <h6 class="mb-0">OC-2025-001</h6>
                                <span class="badge bg-light text-info">Pedido</span>
                            </div>
                            <div class="card-body">
                                <p class="card-text">
                                    <strong>Fecha:</strong> 2025-06-04 15:30:00<br>
                                    <strong>Proveedor:</strong> BALDER<br>
                                    <strong>Centro:</strong> COMPRAS E INVENTARIOS<br>
                                    <strong>Remisiones:</strong> Sin remisiones<br>
                                    <strong>Quincena:</strong> 1
                                </p>
                            </div>
                            <div class="card-footer">
                                <div class="btn-actions justify-content-center">
                                    <button class="btn btn-outline-primary btn-action" title="Ver Detalle">
                                        <i class="bi bi-eye"></i>
                                    </button>
                                    <button class="btn btn-outline-info btn-action" title="Enviar">
                                        <i class="bi bi-send"></i>
                                    </button>
                                    <button class="btn btn-outline-success btn-action" title="Registrar Remisión">
                                        <i class="bi bi-inbox"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-6 mb-4">
                        <div class="card h-100 shadow-sm">
                            <div class="card-header bg-warning text-dark d-flex justify-content-between align-items-center">
                                <h6 class="mb-0">OC-2025-002</h6>
                                <span class="badge bg-warning text-dark">Recibir</span>
                            </div>
                            <div class="card-body">
                                <p class="card-text">
                                    <strong>Fecha:</strong> 2025-05-30 10:15:22<br>
                                    <strong>Proveedor:</strong> UNION TEMPORAL<br>
                                    <strong>Centro:</strong> OFICINA DE BIENESTAR<br>
                                    <strong>Remisiones:</strong> REM-001 (Parcial)<br>
                                    <strong>Quincena:</strong> 2
                                </p>
                            </div>
                            <div class="card-footer">
                                <div class="btn-actions justify-content-center">
                                    <button class="btn btn-outline-primary btn-action" title="Ver Detalle">
                                        <i class="bi bi-eye"></i>
                                    </button>
                                    <button class="btn btn-outline-warning btn-action" title="Ver Remisiones">
                                        <i class="bi bi-list-ul"></i>
                                    </button>
                                    <button class="btn btn-outline-success btn-action" title="Confirmar Recepción">
                                        <i class="bi bi-check-circle"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-6 mb-4">
                        <div class="card h-100 shadow-sm">
                            <div class="card-header bg-success text-white d-flex justify-content-between align-items-center">
                                <h6 class="mb-0">OC-2025-003</h6>
                                <span class="badge bg-light text-success">Recibido</span>
                            </div>
                            <div class="card-body">
                                <p class="card-text">
                                    <strong>Fecha:</strong> 2025-05-28 14:20:18<br>
                                    <strong>Proveedor:</strong> INVERSIONES Y SUMINISTROS<br>
                                    <strong>Centro:</strong> COMPRAS E INVENTARIOS<br>
                                    <strong>Remisiones:</strong> REM-002, REM-003 (Completo)<br>
                                    <strong>Quincena:</strong> 2
                                </p>
                            </div>
                            <div class="card-footer">
                                <div class="btn-actions justify-content-center">
                                    <button class="btn btn-outline-primary btn-action" title="Ver Detalle">
                                        <i class="bi bi-eye"></i>
                                    </button>
                                    <button class="btn btn-outline-info btn-action" title="Ver Remisiones">
                                        <i class="bi bi-list-ul"></i>
                                    </button>
                                    <button class="btn btn-outline-success btn-action" title="Ver Recepción">
                                        <i class="bi bi-clipboard-check"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-6 mb-4">
                        <div class="card h-100 shadow-sm">
                            <div class="card-header bg-warning text-dark d-flex justify-content-between align-items-center">
                                <h6 class="mb-0">OC-2025-004</h6>
                                <span class="badge bg-warning text-dark">Recibir</span>
                            </div>
                            <div class="card-body">
                                <p class="card-text">
                                    <strong>Fecha:</strong> 2025-05-27 16:45:33<br>
                                    <strong>Proveedor:</strong> T&J IMPORTACIONES<br>
                                    <strong>Centro:</strong> AYUDAS EDUCATIVAS<br>
                                    <strong>Remisiones:</strong> REM-004 (Parcial)<br>
                                    <strong>Quincena:</strong> 2
                                </p>
                            </div>
                            <div class="card-footer">
                                <div class="btn-actions justify-content-center">
                                    <button class="btn btn-outline-primary btn-action" title="Ver Detalle">
                                        <i class="bi bi-eye"></i>
                                    </button>
                                    <button class="btn btn-outline-warning btn-action" title="Ver Remisiones">
                                        <i class="bi bi-list-ul"></i>
                                    </button>
                                    <button class="btn btn-outline-success btn-action" title="Confirmar Recepción">
                                        <i class="bi bi-check-circle"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            `;

            return gridContainer;
        }

        // Animaciones suaves al cargar
        document.addEventListener('DOMContentLoaded', function() {
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
        });
    </script>
</body>