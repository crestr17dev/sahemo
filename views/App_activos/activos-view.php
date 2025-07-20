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

    .stats-card.orange {
        background: linear-gradient(135deg, #f39c12 0%, #e67e22 100%);
        color: white;
    }

    .stats-card.green {
        background: linear-gradient(135deg, #27ae60 0%, #229954 100%);
        color: white;
    }

    .stats-card.red {
        background: linear-gradient(135deg, #e74c3c 0%, #c0392b 100%);
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

    /* Tabla de Activos */
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

    /* Opciones de Vista */
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

    .activo-table {
        margin: 0;
    }

    .activo-table thead th {
        background-color: #f8f9fa;
        border: none;
        padding: 1rem 1.5rem;
        font-weight: 600;
        color: #495057;
        font-size: 0.85rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .activo-table tbody td {
        padding: 1rem 1.5rem;
        vertical-align: middle;
        border: none;
        border-bottom: 1px solid #f1f3f4;
    }

    .activo-table tbody tr:hover {
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

    .badge-formalizado {
        background-color: #17a2b8;
        color: white;
    }

    .badge-abierto {
        background-color: #fd7e14;
        color: white;
    }

    .badge-sin-enviar {
        background-color: #6c757d;
        color: white;
    }

    .badge-codigo {
        background-color: #e9ecef;
        color: #495057;
        font-family: monospace;
    }

    .badge-tipo {
        background-color: #6f42c1;
        color: white;
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

    /* Dropdown de tipos de documento */
    .doc-type-dropdown {
        position: relative;
    }

    .dropdown-menu {
        min-width: 200px;
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

    /* Estado del documento */
    .doc-status {
        display: flex;
        flex-direction: column;
        gap: 0.25rem;
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

        .table-filters .col-md-3,
        .table-filters .col-md-2 {
            margin-bottom: 0.5rem;
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
                <a class="nav-link" href="<?php echo SERVERURL?>usuarios/">
                    <i class="bi bi-people me-1"></i>
                    Usuarios
                </a>
				<a class="nav-link" href="<?php echo SERVERURL?>ccostos/">
                    <i class="bi bi-diagram-3 me-1"></i>
                    Centros de Costos
                </a>
				<a class="nav-link" href="<?php echo SERVERURL?>ubicaciones/">
                    <i class="bi bi-geo-alt me-1"></i>
                    Ubicaciones
                </a>
                <a class="nav-link active" href="<?php echo SERVERURL?>activos/">
                    <i class="bi bi-box-seam me-1"></i>
                    Activos
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
                <div class="sidebar-header">GESTIÓN DE ACTIVOS</div>
                <nav class="nav flex-column">
                    <a class="nav-link active" href="/activos/">
                        <i class="bi bi-list-ul"></i>
                        Movimientos de Activos
                    </a>
                    <a class="nav-link" href="#" onclick="showNewMovementModal()">
                        <i class="bi bi-plus-circle"></i>
                        Nuevo Movimiento
                    </a>
                    <a class="nav-link" href="#" onclick="alert('Próximamente')">
                        <i class="bi bi-box-seam"></i>
                        Inventario General
                    </a>
                    <a class="nav-link" href="#" onclick="alert('Próximamente')">
                        <i class="bi bi-person-workspace"></i>
                        Asignaciones por Usuario
                    </a>
                </nav>

                <hr>

                <div class="sidebar-header">TIPOS DE DOCUMENTO</div>
                <nav class="nav flex-column">
                    <a class="nav-link" href="#" onclick="filterByType('traslado')">
                        <i class="bi bi-arrow-left-right"></i>
                        Traslados
                    </a>
                    <a class="nav-link" href="#" onclick="filterByType('entrega')">
                        <i class="bi bi-hand-thumbs-up"></i>
                        Entregas
                    </a>
                    <a class="nav-link" href="#" onclick="filterByType('revision')">
                        <i class="bi bi-search"></i>
                        Revisiones
                    </a>
                    <a class="nav-link" href="#" onclick="filterByType('devolucion')">
                        <i class="bi bi-arrow-return-left"></i>
                        Devoluciones
                    </a>
                </nav>

                <hr>

                <div class="sidebar-header">REPORTES</div>
                <nav class="nav flex-column">
                    <a class="nav-link" href="#" onclick="alert('Próximamente')">
                        <i class="bi bi-file-earmark-bar-graph"></i>
                        Reporte de Movimientos
                    </a>
                    <a class="nav-link" href="#" onclick="alert('Próximamente')">
                        <i class="bi bi-file-earmark-text"></i>
                        Historial por Activo
                    </a>
                    <a class="nav-link" href="#" onclick="alert('Próximamente')">
                        <i class="bi bi-graph-up"></i>
                        Estadísticas de Uso
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
                    <li class="breadcrumb-item active">Movimientos de Activos</li>
                </ol>
            </nav>

            <!-- Header -->
            <div class="page-header">
                <div>
                    <h2 class="page-title">
                        <i class="bi bi-box-seam me-2"></i>
                        Gestión de Activos
                    </h2>
                    <p class="text-muted mb-0">Administra traslados, entregas y revisiones de activos</p>
                </div>
                <div class="doc-type-dropdown">
                    <button class="btn btn-primary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                        <i class="bi bi-plus me-1"></i>
                        Nuevo Documento
                    </button>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="#" onclick="newDocument('traslado')">
                            <i class="bi bi-arrow-left-right"></i>Traslado de Activos
                        </a></li>
                        <li><a class="dropdown-item" href="#" onclick="newDocument('entrega')">
                            <i class="bi bi-hand-thumbs-up"></i>Entrega de Activos
                        </a></li>
                        <li><a class="dropdown-item" href="#" onclick="newDocument('revision')">
                            <i class="bi bi-search"></i>Revisión de Activos
                        </a></li>
                        <li><a class="dropdown-item" href="#" onclick="newDocument('devolucion')">
                            <i class="bi bi-arrow-return-left"></i>Devolución de Activos
                        </a></li>
                    </ul>
                </div>
            </div>

            <!-- Tarjetas de Estadísticas -->
            <div class="row stats-row">
                <div class="col-lg-3 col-md-6 mb-3">
                    <div class="stats-card blue">
                        <div class="stats-content">
                            <div class="stats-number">213</div>
                            <div class="stats-label">Total Documentos</div>
                        </div>
                        <div class="stats-icon">
                            <i class="bi bi-file-earmark"></i>
                        </div>
                    </div>
                </div>

                <div class="col-lg-3 col-md-6 mb-3">
                    <div class="stats-card green">
                        <div class="stats-content">
                            <div class="stats-number">142</div>
                            <div class="stats-label">Formalizados</div>
                        </div>
                        <div class="stats-icon">
                            <i class="bi bi-check-circle"></i>
                        </div>
                    </div>
                </div>

                <div class="col-lg-3 col-md-6 mb-3">
                    <div class="stats-card orange">
                        <div class="stats-content">
                            <div class="stats-number">45</div>
                            <div class="stats-label">Pendientes</div>
                        </div>
                        <div class="stats-icon">
                            <i class="bi bi-clock"></i>
                        </div>
                    </div>
                </div>

                <div class="col-lg-3 col-md-6 mb-3">
                    <div class="stats-card red">
                        <div class="stats-content">
                            <div class="stats-number">26</div>
                            <div class="stats-label">Sin Enviar</div>
                        </div>
                        <div class="stats-icon">
                            <i class="bi bi-exclamation-triangle"></i>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tabla de Activos -->
            <div class="table-container">
                <!-- Header de la tabla -->
                <div class="table-header">
                    <h5 class="table-title">
                        <i class="bi bi-table"></i>
                        Documentos Creados
                        <span class="badge bg-light text-dark ms-2">213</span>
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
                            <div class="input-group">
                                <span class="input-group-text">
                                    <i class="bi bi-search"></i>
                                </span>
                                <input type="text" class="form-control" placeholder="Buscar documento..." id="searchInput">
                            </div>
                        </div>
                        <div class="col-md-2">
                            <input type="date" class="form-control" placeholder="Fecha inicio">
                        </div>
                        <div class="col-md-2">
                            <input type="date" class="form-control" placeholder="Fecha fin">
                        </div>
                        <div class="col-md-2">
                            <select class="form-select" id="estadoFilter">
                                <option value="">Todos los estados</option>
                                <option value="activo">Activo</option>
                                <option value="formalizado">Formalizado</option>
                                <option value="abierto">Abierto</option>
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

                <!-- Tabla -->
                <div class="table-responsive">
                    <table class="table activo-table">
                        <thead>
                            <tr>
                                <th>Documento</th>
                                <th>Fecha Creación</th>
                                <th>Responsables (Entrada)</th>
                                <th>Responsables (Salida)</th>
                                <th>Estado</th>
                                <th>Aceptación</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody id="activosTableBody">
                            <!-- Los datos se cargarán dinámicamente -->
                        </tbody>
                    </table>
                </div>

                <!-- Vista en Cuadrícula (oculta por defecto) -->
                <div class="grid-view" id="gridView">
                    <div class="row" id="activosGridContainer">
                        <!-- Las tarjetas se cargarán dinámicamente -->
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        // Datos de ejemplo de documentos de activos
        const activosData = [
            {
                id: 'AI1264',
                tipo: 'traslado',
                fechaCreacion: '2025-05-30 21:01:32',
                responsableEntrada: 'Andrea Johana Aguilar Barreto (27601663)',
                responsableSalida: '',
                estado: 'Activo, Formalizado, Sin enviar',
                aceptacion: 'sin aceptar',
                tipoDoc: 'Traslado'
            },
            {
                id: 'AI-C749',
                tipo: 'entrega',
                fechaCreacion: '2025-05-30 18:35:45',
                responsableEntrada: 'Graides Cristina Castaño Cano (43751663)',
                responsableSalida: 'Bertha Cecilia Rosero Melo (27395789)',
                estado: 'Activo, Formalizado, Sin enviar',
                aceptacion: 'sin aceptar, sin aceptar',
                tipoDoc: 'Entrega'
            },
            {
                id: 'TRS-C857',
                tipo: 'traslado',
                fechaCreacion: '2025-05-30 16:42:08',
                responsableEntrada: 'Rodrigo Dejesus Fernandez Villa (98566258)',
                responsableSalida: 'Estiven Caicedo Palacios (11814588)',
                estado: 'Activo, Formalizado, Sin enviar',
                aceptacion: 'sin aceptar, sin aceptar, sin aceptar',
                tipoDoc: 'Traslado'
            },
            {
                id: 'AI1263',
                tipo: 'revision',
                fechaCreacion: '2025-05-30 16:18:27',
                responsableEntrada: 'Bertha Cecilia Rosero Melo (27395789)',
                responsableSalida: '',
                estado: 'Activo, Abierto, Sin enviar',
                aceptacion: 'sin aceptar',
                tipoDoc: 'Revisión'
            },
            {
                id: 'AI1262',
                tipo: 'entrega',
                fechaCreacion: '2025-05-30 16:08:32',
                responsableEntrada: 'Juan Fernando Gomez Paniagua (71777716)',
                responsableSalida: '',
                estado: 'Activo, Formalizado, Sin enviar',
                aceptacion: 'sin aceptar',
                tipoDoc: 'Entrega'
            },
            {
                id: 'RPW275',
                tipo: 'devolucion',
                fechaCreacion: '2025-05-30 15:35:11',
                responsableEntrada: 'Norela Dejesus Rivera Rios (22234499)',
                responsableSalida: 'Antonio Elias Agudelo Vivares (8470414)',
                estado: 'Activo, Abierto, Sin enviar',
                aceptacion: 'sin aceptar, sin aceptar',
                tipoDoc: 'Devolución'
            }
        ];

        // Función para obtener el estado como badges
        function getEstadoBadges(estado) {
            const estados = estado.split(', ');
            let badges = '';
            
            estados.forEach(est => {
                const trimmedEst = est.trim();
                let badgeClass = '';
                
                switch(trimmedEst) {
                    case 'Activo':
                        badgeClass = 'badge-activo';
                        break;
                    case 'Formalizado':
                        badgeClass = 'badge-formalizado';
                        break;
                    case 'Abierto':
                        badgeClass = 'badge-abierto';
                        break;
                    case 'Sin enviar':
                        badgeClass = 'badge-sin-enviar';
                        break;
                    default:
                        badgeClass = 'badge-secondary';
                }
                
                badges += `<span class="badge-custom ${badgeClass} me-1">${trimmedEst}</span>`;
            });
            
            return badges;
        }

        // Función para obtener el icono del tipo de documento
        function getTipoIcon(tipo) {
            switch(tipo) {
                case 'traslado':
                    return 'bi-arrow-left-right';
                case 'entrega':
                    return 'bi-hand-thumbs-up';
                case 'revision':
                    return 'bi-search';
                case 'devolucion':
                    return 'bi-arrow-return-left';
                default:
                    return 'bi-file-earmark';
            }
        }

        // Función para cargar la tabla de activos
        function loadActivosTable() {
            const tbody = document.getElementById('activosTableBody');
            tbody.innerHTML = '';

            activosData.forEach(activo => {
                const row = document.createElement('tr');
                row.innerHTML = `
                    <td>
                        <div class="d-flex align-items-center">
                            <i class="bi ${getTipoIcon(activo.tipo)} me-2 text-primary"></i>
                            <div>
                                <span class="badge-custom badge-codigo">${activo.id}</span>
                                <br>
                                <small class="text-muted">${activo.tipoDoc}</small>
                            </div>
                        </div>
                    </td>
                    <td>
                        <small>${activo.fechaCreacion}</small>
                    </td>
                    <td>
                        <div>
                            <strong>${activo.responsableEntrada.split('(')[0].trim()}</strong>
                            <br>
                            <small class="text-muted">${activo.responsableEntrada.match(/\((\d+)\)/)?.[1] || ''}</small>
                        </div>
                    </td>
                    <td>
                        ${activo.responsableSalida ? `
                            <div>
                                <strong>${activo.responsableSalida.split('(')[0].trim()}</strong>
                                <br>
                                <small class="text-muted">${activo.responsableSalida.match(/\((\d+)\)/)?.[1] || ''}</small>
                            </div>
                        ` : '<span class="text-muted">No aplica</span>'}
                    </td>
                    <td>
                        <div class="doc-status">
                            ${getEstadoBadges(activo.estado)}
                        </div>
                    </td>
                    <td>
                        <div class="doc-status">
                            ${activo.aceptacion.split(', ').map(acep => `
                                <span class="badge-custom badge-sin-enviar">${acep.trim()}</span>
                            `).join('')}
                        </div>
                    </td>
                    <td>
                        <div class="btn-actions">
                            <button class="btn btn-outline-primary btn-action" title="Ver Detalle" onclick="viewDocument('${activo.id}')">
                                <i class="bi bi-eye"></i>
                            </button>
                            <button class="btn btn-outline-secondary btn-action" title="Editar" onclick="editDocument('${activo.id}')">
                                <i class="bi bi-pencil"></i>
                            </button>
                            <button class="btn btn-outline-info btn-action" title="Imprimir" onclick="printDocument('${activo.id}')">
                                <i class="bi bi-printer"></i>
                            </button>
                            <button class="btn btn-outline-success btn-action" title="Enviar" onclick="sendDocument('${activo.id}')">
                                <i class="bi bi-send"></i>
                            </button>
                            <button class="btn btn-outline-danger btn-action" title="Eliminar" onclick="deleteDocument('${activo.id}')">
                                <i class="bi bi-trash"></i>
                            </button>
                        </div>
                    </td>
                `;
                tbody.appendChild(row);
            });
        }

        // Función para cargar la vista en cuadrícula
        function loadActivosGrid() {
            const container = document.getElementById('activosGridContainer');
            container.innerHTML = '';

            activosData.forEach(activo => {
                const card = document.createElement('div');
                card.className = 'col-lg-4 col-md-6 mb-4';
                card.innerHTML = `
                    <div class="card h-100 shadow-sm">
                        <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                            <div class="d-flex align-items-center">
                                <i class="bi ${getTipoIcon(activo.tipo)} me-2"></i>
                                <h6 class="mb-0">${activo.id}</h6>
                            </div>
                            <span class="badge-custom badge-tipo">${activo.tipoDoc}</span>
                        </div>
                        <div class="card-body">
                            <p class="card-text">
                                <strong>Fecha:</strong> ${activo.fechaCreacion}<br>
                                <strong>Responsable Entrada:</strong><br>
                                <small>${activo.responsableEntrada}</small><br>
                                ${activo.responsableSalida ? `
                                    <strong>Responsable Salida:</strong><br>
                                    <small>${activo.responsableSalida}</small>
                                ` : ''}
                            </p>
                            <div class="mb-3">
                                <strong>Estado:</strong><br>
                                ${getEstadoBadges(activo.estado)}
                            </div>
                            <div class="mb-3">
                                <strong>Aceptación:</strong><br>
                                ${activo.aceptacion.split(', ').map(acep => `
                                    <span class="badge-custom badge-sin-enviar">${acep.trim()}</span>
                                `).join('')}
                            </div>
                        </div>
                        <div class="card-footer">
                            <div class="btn-actions justify-content-center">
                                <button class="btn btn-outline-primary btn-action" title="Ver" onclick="viewDocument('${activo.id}')">
                                    <i class="bi bi-eye"></i>
                                </button>
                                <button class="btn btn-outline-secondary btn-action" title="Editar" onclick="editDocument('${activo.id}')">
                                    <i class="bi bi-pencil"></i>
                                </button>
                                <button class="btn btn-outline-info btn-action" title="Imprimir" onclick="printDocument('${activo.id}')">
                                    <i class="bi bi-printer"></i>
                                </button>
                                <button class="btn btn-outline-success btn-action" title="Enviar" onclick="sendDocument('${activo.id}')">
                                    <i class="bi bi-send"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                `;
                container.appendChild(card);
            });

            // Si no hay documentos, mostrar mensaje
            if (activosData.length === 0) {
                container.innerHTML = `
                    <div class="col-12 text-center py-5">
                        <i class="bi bi-file-earmark fa-3x text-muted mb-3"></i>
                        <h5 class="text-muted">No hay documentos registrados</h5>
                        <p class="text-muted">Comienza creando tu primer documento de activos</p>
                        <button class="btn btn-primary" onclick="showNewMovementModal()">
                            <i class="bi bi-plus me-1"></i>
                            Crear Primer Documento
                        </button>
                    </div>
                `;
            }
        }

        // Función para cambiar entre vista de lista y cuadrícula
        function toggleView(viewType) {
            const buttons = document.querySelectorAll('.view-btn');
            const tableContainer = document.querySelector('.table-responsive');
            const gridView = document.getElementById('gridView');

            buttons.forEach(btn => btn.classList.remove('active'));

            if (viewType === 'list') {
                buttons[0].classList.add('active');
                tableContainer.style.display = 'block';
                gridView.style.display = 'none';
            } else {
                buttons[1].classList.add('active');
                tableContainer.style.display = 'none';
                gridView.style.display = 'block';
                loadActivosGrid();
            }
        }

        // Funciones de filtrado
        function filterByType(tipo) {
            console.log('Filtrar por tipo:', tipo);
            // Aquí implementarías la lógica de filtrado
            alert(`Filtrar documentos de tipo: ${tipo}`);
        }

        // Funciones de acción
        function showNewMovementModal() {
            alert('Abrir modal para nuevo movimiento de activos');
        }

        function newDocument(tipo) {
            alert(`Crear nuevo documento de tipo: ${tipo}`);
        }

        function viewDocument(id) {
            alert(`Ver documento: ${id}`);
        }

        function editDocument(id) {
            alert(`Editar documento: ${id}`);
        }

        function printDocument(id) {
            alert(`Imprimir documento: ${id}`);
        }

        function sendDocument(id) {
            if (confirm(`¿Estás seguro de que deseas enviar el documento ${id}?`)) {
                alert(`Documento ${id} enviado correctamente`);
                // Aquí iría la lógica real para enviar
            }
        }

        function deleteDocument(id) {
            if (confirm(`¿Estás seguro de que deseas eliminar el documento ${id}?`)) {
                alert(`Documento ${id} eliminado correctamente`);
                // Aquí iría la lógica real para eliminar
            }
        }

        // Función de búsqueda en tiempo real
        function setupSearch() {
            const searchInput = document.getElementById('searchInput');
            if (searchInput) {
                searchInput.addEventListener('input', function(e) {
                    const searchTerm = e.target.value.toLowerCase();
                    const rows = document.querySelectorAll('#activosTableBody tr');
                    
                    rows.forEach(row => {
                        const text = row.textContent.toLowerCase();
                        row.style.display = text.includes(searchTerm) ? '' : 'none';
                    });
                });
            }
        }

        // Función de filtrado por estado
        function setupFilters() {
            const estadoFilter = document.getElementById('estadoFilter');
            if (estadoFilter) {
                estadoFilter.addEventListener('change', function() {
                    const filterValue = this.value.toLowerCase();
                    const rows = document.querySelectorAll('#activosTableBody tr');
                    
                    rows.forEach(row => {
                        if (filterValue === '') {
                            row.style.display = '';
                        } else {
                            const estadoCell = row.querySelector('.doc-status');
                            const estadoText = estadoCell.textContent.toLowerCase();
                            row.style.display = estadoText.includes(filterValue) ? '' : 'none';
                        }
                    });
                });
            }
        }

        // Inicializar la página
        document.addEventListener('DOMContentLoaded', function() {
            // Cargar la tabla de activos
            loadActivosTable();

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

            // Manejar clics en el sidebar para filtros
            const sidebarLinks = document.querySelectorAll('.sidebar .nav-link');
            sidebarLinks.forEach(link => {
                link.addEventListener('click', function(e) {
                    // Remover active de otros links en la misma sección
                    const section = this.closest('.sidebar-section');
                    if (section) {
                        section.querySelectorAll('.nav-link').forEach(l => l.classList.remove('active'));
                        this.classList.add('active');
                    }
                });
            });
        });
    </script>
</div>

</div>