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

	.stats-card.orange {
		background: linear-gradient(135deg, #fd7e14 0%, #e8590c 100%);
		color: white;
	}

	.stats-card.teal {
		background: linear-gradient(135deg, #20c997 0%, #17a085 100%);
		color: white;
	}

	.stats-card.indigo {
		background: linear-gradient(135deg, #6610f2 0%, #520dc2 100%);
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

	/* Tabla de Ubicaciones */
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

	.ubicacion-table {
		margin: 0;
	}

	.ubicacion-table thead th {
		background-color: #f8f9fa;
		border: none;
		padding: 1rem 1.5rem;
		font-weight: 600;
		color: #495057;
		font-size: 0.85rem;
		text-transform: uppercase;
		letter-spacing: 0.5px;
	}

	.ubicacion-table tbody td {
		padding: 1rem 1.5rem;
		vertical-align: middle;
		border: none;
		border-bottom: 1px solid #f1f3f4;
	}

	.ubicacion-table tbody tr:hover {
		background-color: #f8f9fa;
	}

	/* Badges */
	.badge-custom {
		padding: 0.5rem 0.75rem;
		border-radius: 6px;
		font-size: 0.75rem;
		font-weight: 500;
	}

	.badge-id {
		background-color: #17a2b8;
		color: white;
		font-family: monospace;
	}

	.badge-activo {
		background-color: #28a745;
		color: white;
	}

	.badge-inactivo {
		background-color: #6c757d;
		color: white;
	}

	.badge-puestos {
		background-color: #fd7e14;
		color: white;
	}

	.badge-ubicacion {
		background-color: #e9ecef;
		color: #495057;
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
                <a class="nav-link" href="<?php echo SERVERURL?>configuracion/">
                    <i class="bi bi-palette me-1"></i>
                    Configuración
                </a>
                <a class="nav-link" href="<?php echo SERVERURL?>activos/">
                    <i class="bi bi-box-seam me-1"></i>
                    Activos
                </a>
                <a class="nav-link active" href="<?php echo SERVERURL?>ubicaciones/">
                    <i class="bi bi-geo-alt me-1"></i>
                    Ubicaciones
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
                <div class="sidebar-header">UBICACIONES</div>
                <nav class="nav flex-column">
                    <a class="nav-link active" href="/ubicaciones/">
                        <i class="bi bi-geo-alt"></i>
                        Lista de Ubicaciones
                    </a>
                    <a class="nav-link" href="#" onclick="alert('Próximamente')">
                        <i class="bi bi-plus-circle"></i>
                        Nueva Ubicación
                    </a>
                    <a class="nav-link" href="#" onclick="alert('Próximamente')">
                        <i class="bi bi-diagram-3"></i>
                        Gestión de Bloques
                    </a>
                    <a class="nav-link" href="#" onclick="alert('Próximamente')">
                        <i class="bi bi-grid-3x3"></i>
                        Gestión de Puestos
                    </a>
                </nav>

                <hr>

                <div class="sidebar-header">REPORTES</div>
                <nav class="nav flex-column">
                    <a class="nav-link" href="#" onclick="alert('Próximamente')">
                        <i class="bi bi-file-earmark-bar-graph"></i>
                        Reporte de Ubicaciones
                    </a>
                    <a class="nav-link" href="#" onclick="alert('Próximamente')">
                        <i class="bi bi-file-earmark-text"></i>
                        Ocupación por Sede
                    </a>
                    <a class="nav-link" href="#" onclick="alert('Próximamente')">
                        <i class="bi bi-graph-up"></i>
                        Análisis de Capacidad
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
                    <li class="breadcrumb-item active">Lista de Ubicaciones</li>
                </ol>
            </nav>

            <!-- Header -->
            <div class="page-header">
                <div>
                    <h2 class="page-title">
                        <i class="bi bi-geo-alt me-2"></i>
                        Gestión de Ubicaciones
                    </h2>
                    <p class="text-muted mb-0">Administra las sedes, bloques y puestos del sistema</p>
                </div>
                <button class="btn btn-primary">
                    <i class="bi bi-plus me-1"></i>
                    Nueva Ubicación
                </button>
            </div>

            <!-- Tarjetas de Estadísticas -->
            <div class="row stats-row">
                <div class="col-lg-3 col-md-6 mb-3">
                    <div class="stats-card orange">
                        <div class="stats-content">
                            <div class="stats-number">12</div>
                            <div class="stats-label">Total Sedes</div>
                        </div>
                        <div class="stats-icon">
                            <i class="bi bi-building"></i>
                        </div>
                    </div>
                </div>

                <div class="col-lg-3 col-md-6 mb-3">
                    <div class="stats-card teal">
                        <div class="stats-content">
                            <div class="stats-number">10</div>
                            <div class="stats-label">Sedes Activas</div>
                        </div>
                        <div class="stats-icon">
                            <i class="bi bi-check-circle"></i>
                        </div>
                    </div>
                </div>

                <div class="col-lg-3 col-md-6 mb-3">
                    <div class="stats-card indigo">
                        <div class="stats-content">
                            <div class="stats-number">45</div>
                            <div class="stats-label">Total Bloques</div>
                        </div>
                        <div class="stats-icon">
                            <i class="bi bi-diagram-3"></i>
                        </div>
                    </div>
                </div>

                <div class="col-lg-3 col-md-6 mb-3">
                    <div class="stats-card warning">
                        <div class="stats-content">
                            <div class="stats-number">238</div>
                            <div class="stats-label">Total Puestos</div>
                        </div>
                        <div class="stats-icon">
                            <i class="bi bi-grid-3x3"></i>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tabla de Ubicaciones -->
            <div class="table-container">
                <!-- Header de la tabla -->
                <div class="table-header">
                    <h5 class="table-title">
                        <i class="bi bi-table"></i>
                        Ubicaciones Registradas
                        <span class="badge bg-light text-dark ms-2">{{ total_ubicaciones|default:"12" }}</span>
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
                                <input type="text" class="form-control" placeholder="Buscar por sede, bloque o nomenclatura...">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <select class="form-select">
                                <option value="">Todos los estados</option>
                                <option value="Activo">Solo Activas</option>
                                <option value="Inactivo">Solo Inactivas</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <button class="btn btn-outline-primary">
                                <i class="bi bi-funnel me-1"></i>
                                Filtrar
                            </button>
                            <button class="btn btn-outline-secondary ms-2">
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
                    <table class="table ubicacion-table">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Sede</th>
                                <th>Bloque</th>
                                <th>Localidad</th>
                                <th>Nomenclatura</th>
                                <th>Puestos</th>
                                <th>Estado</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- Datos de ejemplo -->
                            <tr>
                                <td>
                                    <span class="badge-custom badge-id">001</span>
                                </td>
                                <td>
                                    <div>
                                        <strong>Sede Principal</strong>
                                        <br>
                                        <small class="text-muted">Medellín Centro</small>
                                    </div>
                                </td>
                                <td>
                                    <span class="badge-custom badge-ubicacion">Bloque A</span>
                                </td>
                                <td>El Poblado</td>
                                <td>
                                    <code>CLL 10 # 43A-15</code>
                                </td>
                                <td>
                                    <span class="badge-custom badge-puestos">
                                        25 puestos
                                    </span>
                                </td>
                                <td>
                                    <span class="badge-custom badge-activo">Activo</span>
                                </td>
                                <td>
                                    <div class="btn-actions">
                                        <button class="btn btn-outline-primary btn-action" title="Ver Detalle">
                                            <i class="bi bi-eye"></i>
                                        </button>
                                        <button class="btn btn-outline-secondary btn-action" title="Editar">
                                            <i class="bi bi-pencil"></i>
                                        </button>
                                        <button class="btn btn-outline-danger btn-action" title="Eliminar">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <span class="badge-custom badge-id">002</span>
                                </td>
                                <td>
                                    <div>
                                        <strong>Sede Norte</strong>
                                        <br>
                                        <small class="text-muted">Medellín Norte</small>
                                    </div>
                                </td>
                                <td>
                                    <span class="badge-custom badge-ubicacion">Bloque B</span>
                                </td>
                                <td>Bello</td>
                                <td>
                                    <code>CRA 65 # 45-23</code>
                                </td>
                                <td>
                                    <span class="badge-custom badge-puestos">
                                        18 puestos
                                    </span>
                                </td>
                                <td>
                                    <span class="badge-custom badge-activo">Activo</span>
                                </td>
                                <td>
                                    <div class="btn-actions">
                                        <button class="btn btn-outline-primary btn-action" title="Ver Detalle">
                                            <i class="bi bi-eye"></i>
                                        </button>
                                        <button class="btn btn-outline-secondary btn-action" title="Editar">
                                            <i class="bi bi-pencil"></i>
                                        </button>
                                        <button class="btn btn-outline-danger btn-action" title="Eliminar">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <span class="badge-custom badge-id">003</span>
                                </td>
                                <td>
                                    <div>
                                        <strong>Sede Sabaneta</strong>
                                        <br>
                                        <small class="text-muted">Área Metropolitana</small>
                                    </div>
                                </td>
                                <td>
                                    <span class="badge-custom badge-ubicacion">Bloque A</span>
                                </td>
                                <td>Sabaneta</td>
                                <td>
                                    <code>CLL 70 SUR # 45-67</code>
                                </td>
                                <td>
                                    <span class="badge-custom badge-puestos">
                                        32 puestos
                                    </span>
                                </td>
                                <td>
                                    <span class="badge-custom badge-activo">Activo</span>
                                </td>
                                <td>
                                    <div class="btn-actions">
                                        <button class="btn btn-outline-primary btn-action" title="Ver Detalle">
                                            <i class="bi bi-eye"></i>
                                        </button>
                                        <button class="btn btn-outline-secondary btn-action" title="Editar">
                                            <i class="bi bi-pencil"></i>
                                        </button>
                                        <button class="btn btn-outline-danger btn-action" title="Eliminar">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <span class="badge-custom badge-id">004</span>
                                </td>
                                <td>
                                    <div>
                                        <strong>Sede Envigado</strong>
                                        <br>
                                        <small class="text-muted">Zona Sur</small>
                                    </div>
                                </td>
                                <td>
                                    <span class="badge-custom badge-ubicacion">Bloque C</span>
                                </td>
                                <td>Envigado</td>
                                <td>
                                    <code>CRA 43A # 34-12</code>
                                </td>
                                <td>
                                    <span class="badge-custom badge-puestos">
                                        15 puestos
                                    </span>
                                </td>
                                <td>
                                    <span class="badge-custom badge-inactivo">Mantenimiento</span>
                                </td>
                                <td>
                                    <div class="btn-actions">
                                        <button class="btn btn-outline-primary btn-action" title="Ver Detalle">
                                            <i class="bi bi-eye"></i>
                                        </button>
                                        <button class="btn btn-outline-secondary btn-action" title="Editar">
                                            <i class="bi bi-pencil"></i>
                                        </button>
                                        <button class="btn btn-outline-danger btn-action" title="Eliminar">
                                            <i class="bi bi-trash"></i>
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
                <div class="row">
                    <div class="col-lg-4 col-md-6 mb-4">
                        <div class="card h-100 shadow-sm">
                            <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                                <h6 class="mb-0">Sede Principal</h6>
                                <span class="badge bg-success">Activo</span>
                            </div>
                            <div class="card-body">
                                <p class="card-text">
                                    <strong>Bloque:</strong> Bloque A<br>
                                    <strong>Localidad:</strong> El Poblado<br>
                                    <strong>Nomenclatura:</strong> CLL 10 # 43A-15<br>
                                    <strong>Puestos:</strong> 25
                                </p>
                            </div>
                            <div class="card-footer">
                                <div class="btn-actions justify-content-center">
                                    <button class="btn btn-outline-primary btn-action" title="Ver Detalle">
                                        <i class="bi bi-eye"></i>
                                    </button>
                                    <button class="btn btn-outline-secondary btn-action" title="Editar">
                                        <i class="bi bi-pencil"></i>
                                    </button>
                                    <button class="btn btn-outline-danger btn-action" title="Eliminar">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-6 mb-4">
                        <div class="card h-100 shadow-sm">
                            <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                                <h6 class="mb-0">Sede Norte</h6>
                                <span class="badge bg-success">Activo</span>
                            </div>
                            <div class="card-body">
                                <p class="card-text">
                                    <strong>Bloque:</strong> Bloque B<br>
                                    <strong>Localidad:</strong> Bello<br>
                                    <strong>Nomenclatura:</strong> CRA 65 # 45-23<br>
                                    <strong>Puestos:</strong> 18
                                </p>
                            </div>
                            <div class="card-footer">
                                <div class="btn-actions justify-content-center">
                                    <button class="btn btn-outline-primary btn-action" title="Ver Detalle">
                                        <i class="bi bi-eye"></i>
                                    </button>
                                    <button class="btn btn-outline-secondary btn-action" title="Editar">
                                        <i class="bi bi-pencil"></i>
                                    </button>
                                    <button class="btn btn-outline-danger btn-action" title="Eliminar">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-6 mb-4">
                        <div class="card h-100 shadow-sm">
                            <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                                <h6 class="mb-0">Sede Sabaneta</h6>
                                <span class="badge bg-success">Activo</span>
                            </div>
                            <div class="card-body">
                                <p class="card-text">
                                    <strong>Bloque:</strong> Bloque A<br>
                                    <strong>Localidad:</strong> Sabaneta<br>
                                    <strong>Nomenclatura:</strong> CLL 70 SUR # 45-67<br>
                                    <strong>Puestos:</strong> 32
                                </p>
                            </div>
                            <div class="card-footer">
                                <div class="btn-actions justify-content-center">
                                    <button class="btn btn-outline-primary btn-action" title="Ver Detalle">
                                        <i class="bi bi-eye"></i>
                                    </button>
                                    <button class="btn btn-outline-secondary btn-action" title="Editar">
                                        <i class="bi bi-pencil"></i>
                                    </button>
                                    <button class="btn btn-outline-danger btn-action" title="Eliminar">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-6 mb-4">
                        <div class="card h-100 shadow-sm">
                            <div class="card-header bg-warning text-dark d-flex justify-content-between align-items-center">
                                <h6 class="mb-0">Sede Envigado</h6>
                                <span class="badge bg-secondary">Mantenimiento</span>
                            </div>
                            <div class="card-body">
                                <p class="card-text">
                                    <strong>Bloque:</strong> Bloque C<br>
                                    <strong>Localidad:</strong> Envigado<br>
                                    <strong>Nomenclatura:</strong> CRA 43A # 34-12<br>
                                    <strong>Puestos:</strong> 15
                                </p>
                            </div>
                            <div class="card-footer">
                                <div class="btn-actions justify-content-center">
                                    <button class="btn btn-outline-primary btn-action" title="Ver Detalle">
                                        <i class="bi bi-eye"></i>
                                    </button>
                                    <button class="btn btn-outline-secondary btn-action" title="Editar">
                                        <i class="bi bi-pencil"></i>
                                    </button>
                                    <button class="btn btn-outline-danger btn-action" title="Eliminar">
                                        <i class="bi bi-trash"></i>
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

            // Funcionalidad de búsqueda en tiempo real
            const searchInput = document.querySelector('input[placeholder*="Buscar"]');
            if (searchInput) {
                searchInput.addEventListener('input', function() {
                    const searchTerm = this.value.toLowerCase();
                    const tableRows = document.querySelectorAll('.ubicacion-table tbody tr');
                    
                    tableRows.forEach(row => {
                        const text = row.textContent.toLowerCase();
                        if (text.includes(searchTerm)) {
                            row.style.display = '';
                        } else {
                            row.style.display = 'none';
                        }
                    });
                });
            }

            // Funcionalidad de filtro por estado
            const stateFilter = document.querySelector('select.form-select');
            if (stateFilter) {
                stateFilter.addEventListener('change', function() {
                    const selectedState = this.value;
                    const tableRows = document.querySelectorAll('.ubicacion-table tbody tr');
                    
                    tableRows.forEach(row => {
                        if (selectedState === '') {
                            row.style.display = '';
                        } else {
                            const stateBadge = row.querySelector('.badge-activo, .badge-inactivo');
                            if (stateBadge && stateBadge.textContent.trim() === selectedState) {
                                row.style.display = '';
                            } else {
                                row.style.display = 'none';
                            }
                        }
                    });
                });
            }
        });

        // Función para limpiar filtros
        function clearFilters() {
            const searchInput = document.querySelector('input[placeholder*="Buscar"]');
            const stateFilter = document.querySelector('select.form-select');
            const tableRows = document.querySelectorAll('.ubicacion-table tbody tr');
            
            if (searchInput) searchInput.value = '';
            if (stateFilter) stateFilter.value = '';
            
            tableRows.forEach(row => {
                row.style.display = '';
            });
        }

        // Agregar evento al botón limpiar
        document.addEventListener('DOMContentLoaded', function() {
            const clearButton = document.querySelector('.btn-outline-secondary');
            if (clearButton) {
                clearButton.addEventListener('click', clearFilters);
            }
        });
    </script>
</body>