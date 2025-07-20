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
        text-decoration: none;
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

    .stats-card.blue {
        background: linear-gradient(135deg, #3498db 0%, #2980b9 100%);
        color: white;
    }

    .stats-card.purple {
        background: linear-gradient(135deg, #9b59b6 0%, #8e44ad 100%);
        color: white;
    }

    .stats-card.green {
        background: linear-gradient(135deg, #27ae60 0%, #229954 100%);
        color: white;
    }

    .stats-card.orange {
        background: linear-gradient(135deg, #f39c12 0%, #e67e22 100%);
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

    /* Tabla de Centros de Costos */
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

    /* Tabs para alternar entre principales y secundarios */
    .view-tabs {
        display: flex;
        border: 1px solid rgba(255,255,255,0.3);
        border-radius: 6px;
        overflow: hidden;
    }

    .tab-btn {
        background: transparent;
        border: none;
        padding: 0.5rem 1rem;
        color: rgba(255,255,255,0.8);
        transition: all 0.2s ease;
        font-size: 0.85rem;
    }

    .tab-btn.active {
        background-color: rgba(255,255,255,0.2);
        color: white;
    }

    .tab-btn:hover {
        background-color: rgba(255,255,255,0.1);
        color: white;
    }

    .table-filters {
        background: #f8f9fa;
        padding: 1.25rem 1.5rem;
        border-bottom: 1px solid #dee2e6;
    }

    .centro-table {
        margin: 0;
    }

    .centro-table thead th {
        background-color: #f8f9fa;
        border: none;
        padding: 1rem 1.5rem;
        font-weight: 600;
        color: #495057;
        font-size: 0.85rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .centro-table tbody td {
        padding: 1rem 1.5rem;
        vertical-align: middle;
        border: none;
        border-bottom: 1px solid #f1f3f4;
    }

    .centro-table tbody tr:hover {
        background-color: #f8f9fa;
    }

    /* Badges */
    .badge-custom {
        padding: 0.5rem 0.75rem;
        border-radius: 6px;
        font-size: 0.75rem;
        font-weight: 500;
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
        font-weight: 600;
    }

    .badge-principal {
        background-color: #6f42c1;
        color: white;
    }

    .badge-secundario {
        background-color: #17a2b8;
        color: white;
    }

    .badge-count {
        background-color: #fd7e14;
        color: white;
    }

    /* Indicador de jerarquía */
    .hierarchy-indicator {
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .hierarchy-line {
        width: 20px;
        height: 1px;
        background-color: #dee2e6;
    }

    .hierarchy-icon {
        color: #6c757d;
        font-size: 0.8rem;
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

    /* Dropdown de nuevos centros */
    .dropdown-menu {
        min-width: 220px;
    }

    .dropdown-item i {
        margin-right: 0.5rem;
        width: 16px;
    }

    /* Vista en cuadrícula */
    .grid-view {
        padding: 1.5rem;
        display: none;
    }

    .centro-card {
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }

    .centro-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 25px rgba(0,0,0,0.15);
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

        .page-header {
            flex-direction: column;
            gap: 1rem;
            align-items: stretch;
        }

        .stats-row .col-lg-3 {
            margin-bottom: 1rem;
        }

        .table-filters .row {
            gap: 0.5rem;
        }
    }
</style>

<div>
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
                <a class="nav-link active" href="<?php echo SERVERURL?>ccostos/">
                    <i class="bi bi-diagram-3 me-1"></i>
                    Centros de Costos
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
                <div class="sidebar-header">CENTROS DE COSTOS</div>
                <nav class="nav flex-column">
                    <a class="nav-link active" href="/centros-costos/" id="linkPrincipales">
                        <i class="bi bi-building"></i>
                        Centros Principales
                    </a>
                    <a class="nav-link" href="#" onclick="showSecundarios()" id="linkSecundarios">
                        <i class="bi bi-diagram-2"></i>
                        Centros Secundarios
                    </a>
                    <a class="nav-link" href="#" onclick="showJerarquia()">
                        <i class="bi bi-diagram-3"></i>
                        Vista Jerárquica
                    </a>
                </nav>

                <hr>

                <div class="sidebar-header">GESTIÓN</div>
                <nav class="nav flex-column">
                    <a class="nav-link" href="#" onclick="openNewCentroModal('principal')">
                        <i class="bi bi-plus-circle"></i>
                        Nuevo Centro Principal
                    </a>
                    <a class="nav-link" href="#" onclick="openNewCentroModal('secundario')">
                        <i class="bi bi-plus-square"></i>
                        Nuevo Centro Secundario
                    </a>
                    <a class="nav-link" href="#" onclick="alert('Próximamente')">
                        <i class="bi bi-person-workspace"></i>
                        Asignar Encargados
                    </a>
                </nav>

                <hr>

                <div class="sidebar-header">REPORTES</div>
                <nav class="nav flex-column">
                    <a class="nav-link" href="#" onclick="alert('Próximamente')">
                        <i class="bi bi-file-earmark-bar-graph"></i>
                        Reporte por Centro
                    </a>
                    <a class="nav-link" href="#" onclick="alert('Próximamente')">
                        <i class="bi bi-file-earmark-text"></i>
                        Estructura Organizacional
                    </a>
                    <a class="nav-link" href="#" onclick="alert('Próximamente')">
                        <i class="bi bi-graph-up"></i>
                        Análisis de Costos
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
                    <li class="breadcrumb-item active" id="breadcrumbText">Centros de Costos Principales</li>
                </ol>
            </nav>

            <!-- Header -->
            <div class="page-header">
                <div>
                    <h2 class="page-title">
                        <i class="bi bi-diagram-3 me-2"></i>
                        <span id="pageTitle">Gestión de Centros de Costos</span>
                    </h2>
                    <p class="text-muted mb-0" id="pageSubtitle">Administra la estructura de centros de costos</p>
                </div>
                <div class="dropdown">
                    <button class="btn btn-primary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                        <i class="bi bi-plus me-1"></i>
                        Nuevo Centro
                    </button>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="#" onclick="openNewCentroModal('principal')">
                            <i class="bi bi-building"></i>Centro Principal
                        </a></li>
                        <li><a class="dropdown-item" href="#" onclick="openNewCentroModal('secundario')">
                            <i class="bi bi-diagram-2"></i>Centro Secundario
                        </a></li>
                    </ul>
                </div>
            </div>

            <!-- Tarjetas de Estadísticas -->
            <div class="row stats-row">
                <div class="col-lg-3 col-md-6 mb-3">
                    <div class="stats-card blue">
                        <div class="stats-content">
                            <div class="stats-number">8</div>
                            <div class="stats-label">Centros Principales</div>
                        </div>
                        <div class="stats-icon">
                            <i class="bi bi-building"></i>
                        </div>
                    </div>
                </div>

                <div class="col-lg-3 col-md-6 mb-3">
                    <div class="stats-card purple">
                        <div class="stats-content">
                            <div class="stats-number">24</div>
                            <div class="stats-label">Centros Secundarios</div>
                        </div>
                        <div class="stats-icon">
                            <i class="bi bi-diagram-2"></i>
                        </div>
                    </div>
                </div>

                <div class="col-lg-3 col-md-6 mb-3">
                    <div class="stats-card green">
                        <div class="stats-content">
                            <div class="stats-number">29</div>
                            <div class="stats-label">Centros Activos</div>
                        </div>
                        <div class="stats-icon">
                            <i class="bi bi-check-circle"></i>
                        </div>
                    </div>
                </div>

                <div class="col-lg-3 col-md-6 mb-3">
                    <div class="stats-card orange">
                        <div class="stats-content">
                            <div class="stats-number">7</div>
                            <div class="stats-label">Encargados Únicos</div>
                        </div>
                        <div class="stats-icon">
                            <i class="bi bi-person-badge"></i>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tabla de Centros de Costos -->
            <div class="table-container">
                <!-- Header de la tabla -->
                <div class="table-header">
                    <h5 class="table-title">
                        <i class="bi bi-table"></i>
                        <span id="tableTitle">Centros de Costos Principales</span>
                        <span class="badge bg-light text-dark ms-2" id="tableCount">8</span>
                    </h5>

                    <!-- Tabs para alternar entre principales y secundarios -->
                    <div class="view-tabs">
                        <button class="tab-btn active" onclick="showTab('principales')" id="tabPrincipales">
                            <i class="bi bi-building me-1"></i>Principales
                        </button>
                        <button class="tab-btn" onclick="showTab('secundarios')" id="tabSecundarios">
                            <i class="bi bi-diagram-2 me-1"></i>Secundarios
                        </button>
                        <button class="tab-btn" onclick="showTab('jerarquia')" id="tabJerarquia">
                            <i class="bi bi-diagram-3 me-1"></i>Jerarquía
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
                                <input type="text" class="form-control" placeholder="Buscar centro de costos..." id="searchInput">
                            </div>
                        </div>
                        <div class="col-md-2">
                            <select class="form-select" id="estadoFilter">
                                <option value="">Todos los estados</option>
                                <option value="activo">Solo Activos</option>
                                <option value="inactivo">Solo Inactivos</option>
                            </select>
                        </div>
                        <div class="col-md-3" id="principalFilter" style="display: none;">
                            <select class="form-select" id="principalSelect">
                                <option value="">Todos los principales</option>
                                <!-- Se cargarán dinámicamente -->
                            </select>
                        </div>
                        <div class="col-md-2">
                            <button class="btn btn-outline-primary me-1">
                                <i class="bi bi-funnel"></i>
                            </button>
                            <button class="btn btn-outline-secondary">
                                <i class="bi bi-arrow-clockwise"></i>
                            </button>
                        </div>
                        <div class="col-md-1 text-end">
                            <button class="btn btn-outline-success">
                                <i class="bi bi-file-earmark-excel"></i>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Tabla Principales -->
                <div class="table-responsive" id="tablaPrincipales">
                    <table class="table centro-table">
                        <thead>
                            <tr>
                                <th>Código</th>
                                <th>Nombre Centro</th>
                                <th>Encargado</th>
                                <th>Centros Secundarios</th>
                                <th>Estado</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody id="principalesTableBody">
                            <!-- Los datos se cargarán dinámicamente -->
                        </tbody>
                    </table>
                </div>

                <!-- Tabla Secundarios -->
                <div class="table-responsive" id="tablaSecundarios" style="display: none;">
                    <table class="table centro-table">
                        <thead>
                            <tr>
                                <th>Código</th>
                                <th>Centro Principal</th>
                                <th>Nombre Centro Secundario</th>
                                <th>Estado</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody id="secundariosTableBody">
                            <!-- Los datos se cargarán dinámicamente -->
                        </tbody>
                    </table>
                </div>

                <!-- Vista Jerárquica -->
                <div class="table-responsive" id="tablaJerarquia" style="display: none;">
                    <table class="table centro-table">
                        <thead>
                            <tr>
                                <th>Estructura</th>
                                <th>Código</th>
                                <th>Nombre</th>
                                <th>Encargado</th>
                                <th>Estado</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody id="jerarquiaTableBody">
                            <!-- Los datos se cargarán dinámicamente -->
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        // Datos de ejemplo de centros de costos principales
        const centrosPrincipales = [
            {
                id: 1,
                codigo: 'CC001',
                nombre: 'Administración General',
                cedulaEncargado: '12345678',
                nombreEncargado: 'María González Pérez',
                estado: 'Activo',
                secundarios: 3
            },
            {
                id: 2,
                codigo: 'CC002',
                nombre: 'Recursos Humanos',
                cedulaEncargado: '87654321',
                nombreEncargado: 'Carlos Rodríguez López',
                estado: 'Activo',
                secundarios: 4
            },
            {
                id: 3,
                codigo: 'CC003',
                nombre: 'Sistemas y Tecnología',
                cedulaEncargado: '11223344',
                nombreEncargado: 'Ana Martínez Silva',
                estado: 'Activo',
                secundarios: 2
            },
            {
                id: 4,
                codigo: 'CC004',
                nombre: 'Contabilidad y Finanzas',
                cedulaEncargado: '44332211',
                nombreEncargado: 'Pedro García Morales',
                estado: 'Activo',
                secundarios: 5
            },
            {
                id: 5,
                codigo: 'CC005',
                nombre: 'Operaciones',
                cedulaEncargado: '55667788',
                nombreEncargado: 'Luisa Fernández Castro',
                estado: 'Inactivo',
                secundarios: 0
            }
        ];

        // Datos de ejemplo de centros de costos secundarios
        const centrosSecundarios = [
            {
                id: 1,
                codigoPrincipal: 'CC001',
                nombrePrincipal: 'Administración General',
                codigo: 'CC001-01',
                nombre: 'Secretaría General',
                estado: 'Activo'
            },
            {
                id: 2,
                codigoPrincipal: 'CC001',
                nombrePrincipal: 'Administración General',
                codigo: 'CC001-02',
                nombre: 'Archivo y Correspondencia',
                estado: 'Activo'
            },
            {
                id: 3,
                codigoPrincipal: 'CC002',
                nombrePrincipal: 'Recursos Humanos',
                codigo: 'CC002-01',
                nombre: 'Selección y Contratación',
                estado: 'Activo'
            },
            {
                id: 4,
                codigoPrincipal: 'CC002',
                nombrePrincipal: 'Recursos Humanos',
                codigo: 'CC002-02',
                nombre: 'Nómina y Beneficios',
                estado: 'Activo'
            },
            {
                id: 5,
                codigoPrincipal: 'CC003',
                nombrePrincipal: 'Sistemas y Tecnología',
                codigo: 'CC003-01',
                nombre: 'Desarrollo de Software',
                estado: 'Activo'
            },
            {
                id: 6,
                codigoPrincipal: 'CC003',
                nombrePrincipal: 'Sistemas y Tecnología',
                codigo: 'CC003-02',
                nombre: 'Soporte Técnico',
                estado: 'Activo'
            }
        ];

        // Función para cargar la tabla de centros principales
        function loadPrincipalesTable() {
            const tbody = document.getElementById('principalesTableBody');
            tbody.innerHTML = '';

            centrosPrincipales.forEach(centro => {
                const row = document.createElement('tr');
                row.innerHTML = `
                    <td>
                        <span class="badge-custom badge-codigo">${centro.codigo}</span>
                    </td>
                    <td>
                        <div>
                            <strong>${centro.nombre}</strong>
                            <br>
                            <span class="badge-custom badge-principal">Principal</span>
                        </div>
                    </td>
                    <td>
                        <div>
                            <strong>${centro.nombreEncargado}</strong>
                            <br>
                            <small class="text-muted">CC: ${centro.cedulaEncargado}</small>
                        </div>
                    </td>
                    <td>
                        <span class="badge-custom badge-count">
                            ${centro.secundarios} secundario${centro.secundarios !== 1 ? 's' : ''}
                        </span>
                    </td>
                    <td>
                        <span class="badge-custom ${centro.estado === 'Activo' ? 'badge-activo' : 'badge-inactivo'}">
                            ${centro.estado}
                        </span>
                    </td>
                    <td>
                        <div class="btn-actions">
                            <button class="btn btn-outline-primary btn-action" title="Ver Detalle" onclick="viewCentro('principal', ${centro.id})">
                                <i class="bi bi-eye"></i>
                            </button>
                            <button class="btn btn-outline-secondary btn-action" title="Editar" onclick="editCentro('principal', ${centro.id})">
                                <i class="bi bi-pencil"></i>
                            </button>
                            <button class="btn btn-outline-info btn-action" title="Ver Secundarios" onclick="viewSecundarios('${centro.codigo}')">
                                <i class="bi bi-diagram-2"></i>
                            </button>
                            <button class="btn btn-outline-danger btn-action" title="Eliminar" onclick="deleteCentro('principal', ${centro.id})">
                                <i class="bi bi-trash"></i>
                            </button>
                        </div>
                    </td>
                `;
                tbody.appendChild(row);
            });
        }

        // Función para cargar la tabla de centros secundarios
        function loadSecundariosTable() {
            const tbody = document.getElementById('secundariosTableBody');
            tbody.innerHTML = '';

            centrosSecundarios.forEach(centro => {
                const row = document.createElement('tr');
                row.innerHTML = `
                    <td>
                        <span class="badge-custom badge-codigo">${centro.codigo}</span>
                    </td>
                    <td>
                        <div>
                            <strong>${centro.nombrePrincipal}</strong>
                            <br>
                            <small class="text-muted">${centro.codigoPrincipal}</small>
                        </div>
                    </td>
                    <td>
                        <div>
                            <strong>${centro.nombre}</strong>
                            <br>
                            <span class="badge-custom badge-secundario">Secundario</span>
                        </div>
                    </td>
                    <td>
                        <span class="badge-custom ${centro.estado === 'Activo' ? 'badge-activo' : 'badge-inactivo'}">
                            ${centro.estado}
                        </span>
                    </td>
                    <td>
                        <div class="btn-actions">
                            <button class="btn btn-outline-primary btn-action" title="Ver Detalle" onclick="viewCentro('secundario', ${centro.id})">
                                <i class="bi bi-eye"></i>
                            </button>
                            <button class="btn btn-outline-secondary btn-action" title="Editar" onclick="editCentro('secundario', ${centro.id})">
                                <i class="bi bi-pencil"></i>
                            </button>
                            <button class="btn btn-outline-danger btn-action" title="Eliminar" onclick="deleteCentro('secundario', ${centro.id})">
                                <i class="bi bi-trash"></i>
                            </button>
                        </div>
                    </td>
                `;
                tbody.appendChild(row);
            });
        }

        // Función para cargar la vista jerárquica
        function loadJerarquiaTable() {
            const tbody = document.getElementById('jerarquiaTableBody');
            tbody.innerHTML = '';

            centrosPrincipales.forEach(principal => {
                // Agregar centro principal
                const principalRow = document.createElement('tr');
                principalRow.innerHTML = `
                    <td>
                        <div class="hierarchy-indicator">
                            <i class="bi bi-building text-primary"></i>
                            <strong>Principal</strong>
                        </div>
                    </td>
                    <td>
                        <span class="badge-custom badge-codigo">${principal.codigo}</span>
                    </td>
                    <td>
                        <strong>${principal.nombre}</strong>
                    </td>
                    <td>
                        <div>
                            <strong>${principal.nombreEncargado}</strong>
                            <br>
                            <small class="text-muted">CC: ${principal.cedulaEncargado}</small>
                        </div>
                    </td>
                    <td>
                        <span class="badge-custom ${principal.estado === 'Activo' ? 'badge-activo' : 'badge-inactivo'}">
                            ${principal.estado}
                        </span>
                    </td>
                    <td>
                        <div class="btn-actions">
                            <button class="btn btn-outline-primary btn-action" title="Ver Detalle" onclick="viewCentro('principal', ${principal.id})">
                                <i class="bi bi-eye"></i>
                            </button>
                            <button class="btn btn-outline-secondary btn-action" title="Editar" onclick="editCentro('principal', ${principal.id})">
                                <i class="bi bi-pencil"></i>
                            </button>
                        </div>
                    </td>
                `;
                tbody.appendChild(principalRow);

                // Agregar centros secundarios
                const secundarios = centrosSecundarios.filter(sec => sec.codigoPrincipal === principal.codigo);
                secundarios.forEach(secundario => {
                    const secundarioRow = document.createElement('tr');
                    secundarioRow.innerHTML = `
                        <td>
                            <div class="hierarchy-indicator">
                                <div class="hierarchy-line"></div>
                                <i class="bi bi-diagram-2 hierarchy-icon"></i>
                                <small>Secundario</small>
                            </div>
                        </td>
                        <td>
                            <span class="badge-custom badge-codigo">${secundario.codigo}</span>
                        </td>
                        <td>
                            ${secundario.nombre}
                        </td>
                        <td>
                            <small class="text-muted">Hereda del principal</small>
                        </td>
                        <td>
                            <span class="badge-custom ${secundario.estado === 'Activo' ? 'badge-activo' : 'badge-inactivo'}">
                                ${secundario.estado}
                            </span>
                        </td>
                        <td>
                            <div class="btn-actions">
                                <button class="btn btn-outline-primary btn-action" title="Ver Detalle" onclick="viewCentro('secundario', ${secundario.id})">
                                    <i class="bi bi-eye"></i>
                                </button>
                                <button class="btn btn-outline-secondary btn-action" title="Editar" onclick="editCentro('secundario', ${secundario.id})">
                                    <i class="bi bi-pencil"></i>
                                </button>
                            </div>
                        </td>
                    `;
                    tbody.appendChild(secundarioRow);
                });
            });
        }

        // Función para mostrar las diferentes pestañas
        function showTab(tab) {
            // Actualizar botones de pestañas
            document.querySelectorAll('.tab-btn').forEach(btn => btn.classList.remove('active'));
            document.getElementById(`tab${tab.charAt(0).toUpperCase() + tab.slice(1)}`).classList.add('active');

            // Ocultar todas las tablas
            document.getElementById('tablaPrincipales').style.display = 'none';
            document.getElementById('tablaSecundarios').style.display = 'none';
            document.getElementById('tablaJerarquia').style.display = 'none';

            // Actualizar filtros
            const principalFilter = document.getElementById('principalFilter');
            
            // Mostrar tabla correspondiente y actualizar UI
            switch(tab) {
                case 'principales':
                    document.getElementById('tablaPrincipales').style.display = 'block';
                    document.getElementById('tableTitle').textContent = 'Centros de Costos Principales';
                    document.getElementById('tableCount').textContent = centrosPrincipales.length;
                    document.getElementById('pageTitle').textContent = 'Centros de Costos Principales';
                    document.getElementById('pageSubtitle').textContent = 'Administra los centros de costos principales';
                    document.getElementById('breadcrumbText').textContent = 'Centros de Costos Principales';
                    principalFilter.style.display = 'none';
                    loadPrincipalesTable();
                    break;
                    
                case 'secundarios':
                    document.getElementById('tablaSecundarios').style.display = 'block';
                    document.getElementById('tableTitle').textContent = 'Centros de Costos Secundarios';
                    document.getElementById('tableCount').textContent = centrosSecundarios.length;
                    document.getElementById('pageTitle').textContent = 'Centros de Costos Secundarios';
                    document.getElementById('pageSubtitle').textContent = 'Administra los centros de costos secundarios';
                    document.getElementById('breadcrumbText').textContent = 'Centros de Costos Secundarios';
                    principalFilter.style.display = 'block';
                    loadSecundariosTable();
                    loadPrincipalFilter();
                    break;
                    
                case 'jerarquia':
                    document.getElementById('tablaJerarquia').style.display = 'block';
                    document.getElementById('tableTitle').textContent = 'Vista Jerárquica de Centros';
                    document.getElementById('tableCount').textContent = centrosPrincipales.length + centrosSecundarios.length;
                    document.getElementById('pageTitle').textContent = 'Vista Jerárquica';
                    document.getElementById('pageSubtitle').textContent = 'Estructura completa de centros de costos';
                    document.getElementById('breadcrumbText').textContent = 'Vista Jerárquica';
                    principalFilter.style.display = 'none';
                    loadJerarquiaTable();
                    break;
            }

            // Actualizar sidebar
            document.querySelectorAll('.sidebar .nav-link').forEach(link => link.classList.remove('active'));
            if (tab === 'principales') {
                document.getElementById('linkPrincipales').classList.add('active');
            } else if (tab === 'secundarios') {
                document.getElementById('linkSecundarios').classList.add('active');
            }
        }

        // Función para cargar el filtro de centros principales
        function loadPrincipalFilter() {
            const select = document.getElementById('principalSelect');
            select.innerHTML = '<option value="">Todos los principales</option>';
            
            centrosPrincipales.forEach(principal => {
                const option = document.createElement('option');
                option.value = principal.codigo;
                option.textContent = `${principal.codigo} - ${principal.nombre}`;
                select.appendChild(option);
            });
        }

        // Funciones de sidebar
        function showSecundarios() {
            showTab('secundarios');
        }

        function showJerarquia() {
            showTab('jerarquia');
        }

        // Funciones de acción
        function openNewCentroModal(tipo) {
            alert(`Abrir modal para crear nuevo centro ${tipo}`);
        }

        function viewCentro(tipo, id) {
            alert(`Ver detalle del centro ${tipo} con ID: ${id}`);
        }

        function editCentro(tipo, id) {
            alert(`Editar centro ${tipo} con ID: ${id}`);
        }

        function deleteCentro(tipo, id) {
            if (confirm(`¿Estás seguro de que deseas eliminar este centro ${tipo}?`)) {
                alert(`Centro ${tipo} eliminado correctamente`);
            }
        }

        function viewSecundarios(codigo) {
            showTab('secundarios');
            // Filtrar por el centro principal seleccionado
            document.getElementById('principalSelect').value = codigo;
            alert(`Mostrando centros secundarios del centro principal: ${codigo}`);
        }

        // Función de búsqueda en tiempo real
        function setupSearch() {
            const searchInput = document.getElementById('searchInput');
            if (searchInput) {
                searchInput.addEventListener('input', function(e) {
                    const searchTerm = e.target.value.toLowerCase();
                    const activeTable = document.querySelector('.table-responsive[style="display: block;"], .table-responsive:not([style*="none"])');
                    
                    if (activeTable) {
                        const rows = activeTable.querySelectorAll('tbody tr');
                        rows.forEach(row => {
                            const text = row.textContent.toLowerCase();
                            row.style.display = text.includes(searchTerm) ? '' : 'none';
                        });
                    }
                });
            }
        }

        // Función de filtrado por estado
        function setupFilters() {
            const estadoFilter = document.getElementById('estadoFilter');
            const principalFilter = document.getElementById('principalSelect');
            
            if (estadoFilter) {
                estadoFilter.addEventListener('change', function() {
                    filterByEstado(this.value);
                });
            }

            if (principalFilter) {
                principalFilter.addEventListener('change', function() {
                    filterByPrincipal(this.value);
                });
            }
        }

        function filterByEstado(estado) {
            const activeTable = document.querySelector('.table-responsive[style="display: block;"], .table-responsive:not([style*="none"])');
            
            if (activeTable) {
                const rows = activeTable.querySelectorAll('tbody tr');
                rows.forEach(row => {
                    if (estado === '') {
                        row.style.display = '';
                    } else {
                        const estadoCell = row.querySelector('.badge-activo, .badge-inactivo');
                        if (estadoCell) {
                            const estadoText = estadoCell.textContent.toLowerCase();
                            row.style.display = estadoText.includes(estado) ? '' : 'none';
                        }
                    }
                });
            }
        }

        function filterByPrincipal(codigo) {
            const tbody = document.getElementById('secundariosTableBody');
            const rows = tbody.querySelectorAll('tr');
            
            rows.forEach(row => {
                if (codigo === '') {
                    row.style.display = '';
                } else {
                    const codigoCell = row.querySelector('td:nth-child(2) small');
                    if (codigoCell && codigoCell.textContent === codigo) {
                        row.style.display = '';
                    } else {
                        row.style.display = 'none';
                    }
                }
            });
        }

        // Inicializar la página
        document.addEventListener('DOMContentLoaded', function() {
            // Cargar la vista inicial (principales)
            showTab('principales');

            // Configurar búsqueda y filtros
            setupSearch();
            setupFilters();

            // Animaciones de las tarjetas de estadísticas
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

            // Configurar navegación del sidebar
            document.getElementById('linkPrincipales').addEventListener('click', function(e) {
                e.preventDefault();
                showTab('principales');
            });

            document.getElementById('linkSecundarios').addEventListener('click', function(e) {
                e.preventDefault();
                showTab('secundarios');
            });
        });
    </script>
</div>