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

        .stats-card.blue {
            background: linear-gradient(135deg, #17a2b8 0%, #138496 100%);
            color: white;
        }

        .stats-card.orange {
            background: linear-gradient(135deg, #fd7e14 0%, #e8590c 100%);
            color: white;
        }

        .stats-card.red {
            background: linear-gradient(135deg, #dc3545 0%, #c82333 100%);
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

        /* Tabla de Tickets */
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

        .tickets-table {
            margin: 0;
        }

        .tickets-table thead th {
            background-color: #f8f9fa;
            border: none;
            padding: 1rem 1.5rem;
            font-weight: 600;
            color: #495057;
            font-size: 0.85rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .tickets-table tbody td {
            padding: 1rem 1.5rem;
            vertical-align: middle;
            border: none;
            border-bottom: 1px solid #f1f3f4;
        }

        .tickets-table tbody tr:hover {
            background-color: #f8f9fa;
        }

        /* Badges */
        .badge-custom {
            padding: 0.5rem 0.75rem;
            border-radius: 6px;
            font-size: 0.75rem;
            font-weight: 500;
        }

        .badge-ticket {
            background-color: #e9ecef;
            color: #495057;
            font-family: monospace;
            font-weight: 600;
        }

        /* Categorías */
        .badge-soporte {
            background-color: #17a2b8;
            color: white;
        }

        .badge-servicios {
            background-color: #6f42c1;
            color: white;
        }

        .badge-educativas {
            background-color: #fd7e14;
            color: white;
        }

        .badge-infraestructura {
            background-color: #20c997;
            color: white;
        }

        /* Estados */
        .badge-nuevo {
            background-color: #6c757d;
            color: white;
        }

        .badge-asignado {
            background-color: #17a2b8;
            color: white;
        }

        .badge-proceso {
            background-color: #ffc107;
            color: #212529;
        }

        .badge-resuelto {
            background-color: #28a745;
            color: white;
        }

        .badge-escalado {
            background-color: #dc3545;
            color: white;
        }

        .badge-cerrado {
            background-color: #343a40;
            color: white;
        }

        /* Prioridades */
        .badge-critica {
            background-color: #dc3545;
            color: white;
            animation: pulse 2s infinite;
        }

        .badge-alta {
            background-color: #fd7e14;
            color: white;
        }

        .badge-media {
            background-color: #ffc107;
            color: #212529;
        }

        .badge-baja {
            background-color: #6c757d;
            color: white;
        }

        /* Niveles */
        .badge-nivel1 {
            background-color: #28a745;
            color: white;
        }

        .badge-nivel2 {
            background-color: #fd7e14;
            color: white;
        }

        .badge-nivel3 {
            background-color: #dc3545;
            color: white;
        }

        /* SLA */
        .sla-indicator {
            font-size: 0.7rem;
            padding: 0.25rem 0.5rem;
            border-radius: 4px;
        }

        .sla-ok {
            background-color: #d4edda;
            color: #155724;
        }

        .sla-warning {
            background-color: #fff3cd;
            color: #856404;
        }

        .sla-danger {
            background-color: #f8d7da;
            color: #721c24;
        }

        /* Animación para prioridad crítica */
        @keyframes pulse {
            0% { opacity: 1; }
            50% { opacity: 0.7; }
            100% { opacity: 1; }
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
                <a class="nav-link" href="<?php echo SERVERURL?>proveedores/">
                    <i class="bi bi-cart-check me-1"></i>
                    Órdenes de Compra
                </a>
                <a class="nav-link" href="<?php echo SERVERURL?>inventarios/">
                    <i class="bi bi-calculator me-1"></i>
                    Inventario y Facturación
                </a>
                <a class="nav-link active" href="<?php echo SERVERURL?>mesaayudas/">
                    <i class="bi bi-headset me-1"></i>
                    Mesa de Ayuda
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
                <div class="sidebar-header">MESA DE AYUDA</div>
                <nav class="nav flex-column">
                    <a class="nav-link active" href="<?php echo SERVERURL?>helpdesk/">
                        <i class="bi bi-list-ul"></i>
                        Todos los Tickets
                    </a>
                    <a class="nav-link" href="#" onclick="alert('Próximamente')">
                        <i class="bi bi-plus-circle"></i>
                        Nuevo Ticket
                    </a>
                    <a class="nav-link" href="#" onclick="alert('Próximamente')">
                        <i class="bi bi-person-check"></i>
                        Mis Tickets
                    </a>
                </nav>

                <hr>

                <div class="sidebar-header">POR CATEGORÍA</div>
                <nav class="nav flex-column">
                    <a class="nav-link" href="#" onclick="alert('Próximamente')">
                        <i class="bi bi-laptop"></i>
                        Soporte Técnico
                    </a>
                    <a class="nav-link" href="#" onclick="alert('Próximamente')">
                        <i class="bi bi-tools"></i>
                        Servicios Generales
                    </a>
                    <a class="nav-link" href="#" onclick="alert('Próximamente')">
                        <i class="bi bi-projector"></i>
                        Ayudas Educativas
                    </a>
                    <a class="nav-link" href="#" onclick="alert('Próximamente')">
                        <i class="bi bi-building"></i>
                        Infraestructura
                    </a>
                </nav>

                <hr>

                <div class="sidebar-header">POR ESTADO</div>
                <nav class="nav flex-column">
                    <a class="nav-link" href="#" onclick="alert('Próximamente')">
                        <i class="bi bi-circle"></i>
                        Nuevos
                    </a>
                    <a class="nav-link" href="#" onclick="alert('Próximamente')">
                        <i class="bi bi-person-plus"></i>
                        Asignados
                    </a>
                    <a class="nav-link" href="#" onclick="alert('Próximamente')">
                        <i class="bi bi-gear"></i>
                        En Proceso
                    </a>
                    <a class="nav-link" href="#" onclick="alert('Próximamente')">
                        <i class="bi bi-arrow-up-circle"></i>
                        Escalados
                    </a>
                    <a class="nav-link" href="#" onclick="alert('Próximamente')">
                        <i class="bi bi-check-circle"></i>
                        Resueltos
                    </a>
                </nav>

                <hr>

                <div class="sidebar-header">POR NIVEL</div>
                <nav class="nav flex-column">
                    <a class="nav-link" href="#" onclick="alert('Próximamente')">
                        <i class="bi bi-1-circle"></i>
                        Nivel 1 - Técnicos
                    </a>
                    <a class="nav-link" href="#" onclick="alert('Próximamente')">
                        <i class="bi bi-2-circle"></i>
                        Nivel 2 - Supervisores
                    </a>
                    <a class="nav-link" href="#" onclick="alert('Próximamente')">
                        <i class="bi bi-3-circle"></i>
                        Nivel 3 - Gerencia
                    </a>
                </nav>

                <hr>

                <div class="sidebar-header">REPORTES</div>
                <nav class="nav flex-column">
                    <a class="nav-link" href="#" onclick="alert('Próximamente')">
                        <i class="bi bi-graph-up"></i>
                        Indicadores SLA
                    </a>
                    <a class="nav-link" href="#" onclick="alert('Próximamente')">
                        <i class="bi bi-pie-chart"></i>
                        Por Categoría
                    </a>
                    <a class="nav-link" href="#" onclick="alert('Próximamente')">
                        <i class="bi bi-bar-chart"></i>
                        Por Técnico
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
                    <li class="breadcrumb-item active">Mesa de Ayuda</li>
                </ol>
            </nav>

            <!-- Header -->
            <div class="page-header">
                <div>
                    <h2 class="page-title">
                        <i class="bi bi-headset me-2"></i>
                        Mesa de Ayuda - Sistema de Tickets
                    </h2>
                    <p class="text-muted mb-0">Gestión centralizada de solicitudes con escalamiento por niveles</p>
                </div>
                <button class="btn btn-primary">
                    <i class="bi bi-plus me-1"></i>
                    Nuevo Ticket
                </button>
            </div>

            <!-- Tarjetas de Estadísticas -->
            <div class="row stats-row">
                <div class="col-lg-3 col-md-6 mb-3">
                    <div class="stats-card blue">
                        <div class="stats-content">
                            <div class="stats-number">47</div>
                            <div class="stats-label">Total Tickets</div>
                        </div>
                        <div class="stats-icon">
                            <i class="bi bi-ticket-perforated"></i>
                        </div>
                    </div>
                </div>

                <div class="col-lg-3 col-md-6 mb-3">
                    <div class="stats-card orange">
                        <div class="stats-content">
                            <div class="stats-number">23</div>
                            <div class="stats-label">En Proceso</div>
                        </div>
                        <div class="stats-icon">
                            <i class="bi bi-gear"></i>
                        </div>
                    </div>
                </div>

                <div class="col-lg-3 col-md-6 mb-3">
                    <div class="stats-card red">
                        <div class="stats-content">
                            <div class="stats-number">8</div>
                            <div class="stats-label">Escalados</div>
                        </div>
                        <div class="stats-icon">
                            <i class="bi bi-arrow-up-circle"></i>
                        </div>
                    </div>
                </div>

                <div class="col-lg-3 col-md-6 mb-3">
                    <div class="stats-card green">
                        <div class="stats-content">
                            <div class="stats-number">16</div>
                            <div class="stats-label">Resueltos Hoy</div>
                        </div>
                        <div class="stats-icon">
                            <i class="bi bi-check-circle"></i>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tabla de Tickets -->
            <div class="table-container">
                <!-- Header de la tabla -->
                <div class="table-header">
                    <h5 class="table-title">
                        <i class="bi bi-table"></i>
                        Tickets Activos
                        <span class="badge bg-light text-dark ms-2">47</span>
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
                        <div class="col-md-2">
                            <select class="form-select">
                                <option value="">Categoría:</option>
                                <option value="soporte">Soporte Técnico</option>
                                <option value="servicios">Servicios Generales</option>
                                <option value="educativas">Ayudas Educativas</option>
                                <option value="infraestructura">Infraestructura</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <select class="form-select">
                                <option value="">Estado:</option>
                                <option value="nuevo">Nuevo</option>
                                <option value="asignado">Asignado</option>
                                <option value="proceso">En Proceso</option>
                                <option value="escalado">Escalado</option>
                                <option value="resuelto">Resuelto</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <select class="form-select">
                                <option value="">Prioridad:</option>
                                <option value="critica">Crítica</option>
                                <option value="alta">Alta</option>
                                <option value="media">Media</option>

                                <option value="baja">Baja</option>
                            </select>
                        </div>
                        <div class="col-md-1">
                            <select class="form-select">
                                <option value="">Nivel:</option>
                                <option value="1">N1</option>
                                <option value="2">N2</option>
                                <option value="3">N3</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <input type="text" class="form-control" placeholder="Buscar ticket, solicitante, técnico...">
                        </div>
                        <div class="col-md-2">
                            <select class="form-select">
                                <option value="">Área responsable:</option>
                                <option value="soporte">Soporte Técnico</option>
                                <option value="servicios">Servicios Generales</option>
                                <option value="educativas">Ayudas Educativas</option>
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
                    <table class="table tickets-table">
                        <thead>
                            <tr>
                                <th>Ticket #</th>
                                <th>Solicitante / Asignado</th>
                                <th>Categoría / Subcategoría</th>
                                <th>Prioridad / SLA</th>
                                <th>Estado / Nivel</th>
                                <th>Área Responsable</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>
                                    <div>
                                        <span class="badge-custom badge-ticket">TK-2025-001</span>
                                        <br>
                                        <small class="text-muted">2025-06-05 08:30</small>
                                    </div>
                                </td>
                                <td>
                                    <div>
                                        <strong>CARLOS ZAPATA JIMENEZ</strong>
                                        <br>
                                        <small class="text-muted">Asignado: JUAN PÉREZ</small>
                                    </div>
                                </td>
                                <td>
                                    <div>
                                        <span class="badge-custom badge-soporte">Soporte Técnico</span>
                                        <br>
                                        <small class="text-muted">Impresora no funciona</small>
                                    </div>
                                </td>
                                <td>
                                    <div>
                                        <span class="badge-custom badge-alta">Alta</span>
                                        <br>
                                        <span class="sla-indicator sla-ok">SLA: 2h restantes</span>
                                    </div>
                                </td>
                                <td>
                                    <div>
                                        <span class="badge-custom badge-proceso">En Proceso</span>
                                        <br>
                                        <span class="badge-custom badge-nivel1">Nivel 1</span>
                                    </div>
                                </td>
                                <td>
                                    <div>
                                        <strong>Soporte Técnico</strong>
                                        <br>
                                        <small class="text-muted">Impresoras y equipos</small>
                                    </div>
                                </td>
                                <td>
                                    <div class="btn-actions">
                                        <button class="btn btn-outline-primary btn-action" title="Ver Detalle">
                                            <i class="bi bi-eye"></i>
                                        </button>
                                        <button class="btn btn-outline-secondary btn-action" title="Editar">
                                            <i class="bi bi-pencil"></i>
                                        </button>
                                        <button class="btn btn-outline-success btn-action" title="Resolver">
                                            <i class="bi bi-check"></i>
                                        </button>
                                        <button class="btn btn-outline-warning btn-action" title="Escalar">
                                            <i class="bi bi-arrow-up"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <div>
                                        <span class="badge-custom badge-ticket">TK-2025-002</span>
                                        <br>
                                        <small class="text-muted">2025-06-05 09:15</small>
                                    </div>
                                </td>
                                <td>
                                    <div>
                                        <strong>DANIEL NISPERUZA TOLEDO</strong>
                                        <br>
                                        <small class="text-muted">Asignado: MARÍA GONZÁLEZ</small>
                                    </div>
                                </td>
                                <td>
                                    <div>
                                        <span class="badge-custom badge-servicios">Servicios Generales</span>
                                        <br>
                                        <small class="text-muted">Mantenimiento aire acondicionado</small>
                                    </div>
                                </td>
                                <td>
                                    <div>
                                        <span class="badge-custom badge-critica">Crítica</span>
                                        <br>
                                        <span class="sla-indicator sla-danger">SLA: Vencido</span>
                                    </div>
                                </td>
                                <td>
                                    <div>
                                        <span class="badge-custom badge-escalado">Escalado</span>
                                        <br>
                                        <span class="badge-custom badge-nivel2">Nivel 2</span>
                                    </div>
                                </td>
                                <td>
                                    <div>
                                        <strong>Servicios Generales</strong>
                                        <br>
                                        <small class="text-muted">Infraestructura física</small>
                                    </div>
                                </td>
                                <td>
                                    <div class="btn-actions">
                                        <button class="btn btn-outline-primary btn-action" title="Ver Detalle">
                                            <i class="bi bi-eye"></i>
                                        </button>
                                        <button class="btn btn-outline-secondary btn-action" title="Editar">
                                            <i class="bi bi-pencil"></i>
                                        </button>
                                        <button class="btn btn-outline-success btn-action" title="Resolver">
                                            <i class="bi bi-check"></i>
                                        </button>
                                        <button class="btn btn-outline-danger btn-action" title="Escalar N3">
                                            <i class="bi bi-arrow-up-circle"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <div>
                                        <span class="badge-custom badge-ticket">TK-2025-003</span>
                                        <br>
                                        <small class="text-muted">2025-06-05 10:45</small>
                                    </div>
                                </td>
                                <td>
                                    <div>
                                        <strong>SERGIO VALENCIA HURTADO</strong>
                                        <br>
                                        <small class="text-muted">Asignado: LUIS MARTÍNEZ</small>
                                    </div>
                                </td>
                                <td>
                                    <div>
                                        <span class="badge-custom badge-educativas">Ayudas Educativas</span>
                                        <br>
                                        <small class="text-muted">Proyector Aula 205 no enciende</small>
                                    </div>
                                </td>
                                <td>
                                    <div>
                                        <span class="badge-custom badge-media">Media</span>
                                        <br>
                                        <span class="sla-indicator sla-warning">SLA: 4h restantes</span>
                                    </div>
                                </td>
                                <td>
                                    <div>
                                        <span class="badge-custom badge-asignado">Asignado</span>
                                        <br>
                                        <span class="badge-custom badge-nivel1">Nivel 1</span>
                                    </div>
                                </td>
                                <td>
                                    <div>
                                        <strong>Ayudas Educativas</strong>
                                        <br>
                                        <small class="text-muted">Equipos audiovisuales</small>
                                    </div>
                                </td>
                                <td>
                                    <div class="btn-actions">
                                        <button class="btn btn-outline-primary btn-action" title="Ver Detalle">
                                            <i class="bi bi-eye"></i>
                                        </button>
                                        <button class="btn btn-outline-secondary btn-action" title="Editar">
                                            <i class="bi bi-pencil"></i>
                                        </button>
                                        <button class="btn btn-outline-info btn-action" title="Iniciar">
                                            <i class="bi bi-play"></i>
                                        </button>
                                        <button class="btn btn-outline-warning btn-action" title="Escalar">
                                            <i class="bi bi-arrow-up"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <div>
                                        <span class="badge-custom badge-ticket">TK-2025-004</span>
                                        <br>
                                        <small class="text-muted">2025-06-05 11:20</small>
                                    </div>
                                </td>
                                <td>
                                    <div>
                                        <strong>MANUELA JIMENEZ MUNOZ</strong>
                                        <br>
                                        <small class="text-muted">Sin asignar</small>
                                    </div>
                                </td>
                                <td>
                                    <div>
                                        <span class="badge-custom badge-soporte">Soporte Técnico</span>
                                        <br>
                                        <small class="text-muted">PC lenta, posible virus</small>
                                    </div>
                                </td>
                                <td>
                                    <div>
                                        <span class="badge-custom badge-baja">Baja</span>
                                        <br>
                                        <span class="sla-indicator sla-ok">SLA: 22h restantes</span>
                                    </div>
                                </td>
                                <td>
                                    <div>
                                        <span class="badge-custom badge-nuevo">Nuevo</span>
                                        <br>
                                        <span class="badge-custom badge-nivel1">Nivel 1</span>
                                    </div>
                                </td>
                                <td>
                                    <div>
                                        <strong>Soporte Técnico</strong>
                                        <br>
                                        <small class="text-muted">Equipos de cómputo</small>
                                    </div>
                                </td>
                                <td>
                                    <div class="btn-actions">
                                        <button class="btn btn-outline-primary btn-action" title="Ver Detalle">
                                            <i class="bi bi-eye"></i>
                                        </button>
                                        <button class="btn btn-outline-secondary btn-action" title="Editar">
                                            <i class="bi bi-pencil"></i>
                                        </button>
                                        <button class="btn btn-outline-success btn-action" title="Asignar">
                                            <i class="bi bi-person-plus"></i>
                                        </button>
                                        <button class="btn btn-outline-info btn-action" title="Cambiar Prioridad">
                                            <i class="bi bi-exclamation"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <div>
                                        <span class="badge-custom badge-ticket">TK-2025-005</span>
                                        <br>
                                        <small class="text-muted">2025-06-05 14:30</small>
                                    </div>
                                </td>
                                <td>
                                    <div>
                                        <strong>ANA GARCÍA LÓPEZ</strong>
                                        <br>
                                        <small class="text-muted">Asignado: PEDRO RAMÍREZ</small>
                                    </div>
                                </td>
                                <td>
                                    <div>
                                        <span class="badge-custom badge-infraestructura">Infraestructura</span>
                                        <br>
                                        <small class="text-muted">Traslado mobiliario Oficina 302</small>
                                    </div>
                                </td>
                                <td>
                                    <div>
                                        <span class="badge-custom badge-alta">Alta</span>
                                        <br>
                                        <span class="sla-indicator sla-ok">SLA: 6h restantes</span>
                                    </div>
                                </td>
                                <td>
                                    <div>
                                        <span class="badge-custom badge-resuelto">Resuelto</span>
                                        <br>
                                        <span class="badge-custom badge-nivel1">Nivel 1</span>
                                    </div>
                                </td>
                                <td>
                                    <div>
                                        <strong>Servicios Generales</strong>
                                        <br>
                                        <small class="text-muted">Traslados y mobiliario</small>
                                    </div>
                                </td>
                                <td>
                                    <div class="btn-actions">
                                        <button class="btn btn-outline-primary btn-action" title="Ver Detalle">
                                            <i class="bi bi-eye"></i>
                                        </button>
                                        <button class="btn btn-outline-success btn-action" title="Cerrar Ticket">
                                            <i class="bi bi-check-circle"></i>
                                        </button>
                                        <button class="btn btn-outline-info btn-action" title="Encuesta">
                                            <i class="bi bi-star"></i>
                                        </button>
                                        <button class="btn btn-outline-secondary btn-action" title="Imprimir">
                                            <i class="bi bi-printer"></i>
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
                        <div class="card h-100 shadow-sm border-warning">
                            <div class="card-header bg-warning text-dark d-flex justify-content-between align-items-center">
                                <h6 class="mb-0">TK-2025-001</h6>
                                <span class="badge bg-dark">En Proceso</span>
                            </div>
                            <div class="card-body">
                                <div class="mb-2">
                                    <span class="badge badge-custom badge-soporte">Soporte Técnico</span>
                                    <span class="badge badge-custom badge-alta ms-1">Alta</span>
                                </div>
                                <p class="card-text">
                                    <strong>Solicitante:</strong> CARLOS ZAPATA<br>
                                    <strong>Asignado:</strong> JUAN PÉREZ<br>
                                    <strong>Problema:</strong> Impresora no funciona<br>
                                    <strong>SLA:</strong> <span class="sla-indicator sla-ok">2h restantes</span><br>
                                    <strong>Nivel:</strong> 1
                                </p>
                            </div>
                            <div class="card-footer">
                                <div class="btn-actions justify-content-center">
                                    <button class="btn btn-outline-primary btn-action" title="Ver Detalle">
                                        <i class="bi bi-eye"></i>
                                    </button>
                                    <button class="btn btn-outline-success btn-action" title="Resolver">
                                        <i class="bi bi-check"></i>
                                    </button>
                                    <button class="btn btn-outline-warning btn-action" title="Escalar">
                                        <i class="bi bi-arrow-up"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-6 mb-4">
                        <div class="card h-100 shadow-sm border-danger">
                            <div class="card-header bg-danger text-white d-flex justify-content-between align-items-center">
                                <h6 class="mb-0">TK-2025-002</h6>
                                <span class="badge bg-light text-danger">Escalado</span>
                            </div>
                            <div class="card-body">
                                <div class="mb-2">
                                    <span class="badge badge-custom badge-servicios">Servicios Generales</span>
                                    <span class="badge badge-custom badge-critica ms-1">Crítica</span>
                                </div>
                                <p class="card-text">
                                    <strong>Solicitante:</strong> DANIEL NISPERUZA<br>
                                    <strong>Asignado:</strong> MARÍA GONZÁLEZ<br>
                                    <strong>Problema:</strong> Mantenimiento AC<br>
                                    <strong>SLA:</strong> <span class="sla-indicator sla-danger">Vencido</span><br>
                                    <strong>Nivel:</strong> 2
                                </p>
                            </div>
                            <div class="card-footer">
                                <div class="btn-actions justify-content-center">
                                    <button class="btn btn-outline-primary btn-action" title="Ver Detalle">
                                        <i class="bi bi-eye"></i>
                                    </button>
                                    <button class="btn btn-outline-success btn-action" title="Resolver">
                                        <i class="bi bi-check"></i>
                                    </button>
                                    <button class="btn btn-outline-danger btn-action" title="Escalar N3">
                                        <i class="bi bi-arrow-up-circle"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-6 mb-4">
                        <div class="card h-100 shadow-sm border-info">
                            <div class="card-header bg-info text-white d-flex justify-content-between align-items-center">
                                <h6 class="mb-0">TK-2025-003</h6>
                                <span class="badge bg-light text-info">Asignado</span>
                            </div>
                            <div class="card-body">
                                <div class="mb-2">
                                    <span class="badge badge-custom badge-educativas">Ayudas Educativas</span>
                                    <span class="badge badge-custom badge-media ms-1">Media</span>
                                </div>
                                <p class="card-text">
                                    <strong>Solicitante:</strong> SERGIO VALENCIA<br>
                                    <strong>Asignado:</strong> LUIS MARTÍNEZ<br>
                                    <strong>Problema:</strong> Proyector Aula 205<br>
                                    <strong>SLA:</strong> <span class="sla-indicator sla-warning">4h restantes</span><br>
                                    <strong>Nivel:</strong> 1
                                </p>
                            </div>
                            <div class="card-footer">
                                <div class="btn-actions justify-content-center">
                                    <button class="btn btn-outline-primary btn-action" title="Ver Detalle">
                                        <i class="bi bi-eye"></i>
                                    </button>
                                    <button class="btn btn-outline-info btn-action" title="Iniciar">
                                        <i class="bi bi-play"></i>
                                    </button>
                                    <button class="btn btn-outline-warning btn-action" title="Escalar">
                                        <i class="bi bi-arrow-up"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-6 mb-4">
                        <div class="card h-100 shadow-sm border-secondary">
                            <div class="card-header bg-secondary text-white d-flex justify-content-between align-items-center">
                                <h6 class="mb-0">TK-2025-004</h6>
                                <span class="badge bg-light text-secondary">Nuevo</span>
                            </div>
                            <div class="card-body">
                                <div class="mb-2">
                                    <span class="badge badge-custom badge-soporte">Soporte Técnico</span>
                                    <span class="badge badge-custom badge-baja ms-1">Baja</span>
                                </div>
                                <p class="card-text">
                                    <strong>Solicitante:</strong> MANUELA JIMENEZ<br>
                                    <strong>Asignado:</strong> Sin asignar<br>
                                    <strong>Problema:</strong> PC lenta, virus<br>
                                    <strong>SLA:</strong> <span class="sla-indicator sla-ok">22h restantes</span><br>
                                    <strong>Nivel:</strong> 1
                                </p>
                            </div>
                            <div class="card-footer">
                                <div class="btn-actions justify-content-center">
                                    <button class="btn btn-outline-primary btn-action" title="Ver Detalle">
                                        <i class="bi bi-eye"></i>
                                    </button>
                                    <button class="btn btn-outline-success btn-action" title="Asignar">
                                        <i class="bi bi-person-plus"></i>
                                    </button>
                                    <button class="btn btn-outline-info btn-action" title="Prioridad">
                                        <i class="bi bi-exclamation"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-6 mb-4">
                        <div class="card h-100 shadow-sm border-success">
                            <div class="card-header bg-success text-white d-flex justify-content-between align-items-center">
                                <h6 class="mb-0">TK-2025-005</h6>
                                <span class="badge bg-light text-success">Resuelto</span>
                            </div>
                            <div class="card-body">
                                <div class="mb-2">
                                    <span class="badge badge-custom badge-infraestructura">Infraestructura</span>
                                    <span class="badge badge-custom badge-alta ms-1">Alta</span>
                                </div>
                                <p class="card-text">
                                    <strong>Solicitante:</strong> ANA GARCÍA<br>
                                    <strong>Asignado:</strong> PEDRO RAMÍREZ<br>
                                    <strong>Problema:</strong> Traslado mobiliario<br>
                                    <strong>SLA:</strong> <span class="sla-indicator sla-ok">Cumplido</span><br>
                                    <strong>Nivel:</strong> 1
                                </p>
                            </div>
                            <div class="card-footer">
                                <div class="btn-actions justify-content-center">
                                    <button class="btn btn-outline-primary btn-action" title="Ver Detalle">
                                        <i class="bi bi-eye"></i>
                                    </button>
                                    <button class="btn btn-outline-success btn-action" title="Cerrar">
                                        <i class="bi bi-check-circle"></i>
                                    </button>
                                    <button class="btn btn-outline-info btn-action" title="Encuesta">
                                        <i class="bi bi-star"></i>
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