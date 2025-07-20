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

        .stats-card.green {
            background: linear-gradient(135deg, #28a745 0%, #1e7e34 100%);
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

        /* Tabla de Movimientos */
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

        .movimientos-table {
            margin: 0;
        }

        .movimientos-table thead th {
            background-color: #f8f9fa;
            border: none;
            padding: 1rem 1.5rem;
            font-weight: 600;
            color: #495057;
            font-size: 0.85rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .movimientos-table tbody td {
            padding: 1rem 1.5rem;
            vertical-align: middle;
            border: none;
            border-bottom: 1px solid #f1f3f4;
        }

        .movimientos-table tbody tr:hover {
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

        .badge-entrada {
            background-color: #28a745;
            color: white;
        }

        .badge-salida {
            background-color: #dc3545;
            color: white;
        }

        .badge-traslado {
            background-color: #6f42c1;
            color: white;
        }

        .badge-activo {
            background-color: #17a2b8;
            color: white;
        }

        .badge-formalizado {
            background-color: #28a745;
            color: white;
        }

        .badge-pendiente {
            background-color: #ffc107;
            color: #212529;
        }

        .badge-quincena {
            background-color: #6c757d;
            color: white;
            font-size: 0.7rem;
        }

        .badge-numero {
            background-color: #6f42c1;
            color: white;
            font-size: 0.7rem;
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
                <a class="nav-link active" href="<?php echo SERVERURL?>inventarios/">
                    <i class="bi bi-calculator me-1"></i>
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
                <div class="sidebar-header">MOVIMIENTOS</div>
                <nav class="nav flex-column">
                    <a class="nav-link active" href="<?php echo SERVERURL?>inventarios/">
                        <i class="bi bi-list-ul"></i>
                        Lista de Movimientos
                    </a>
                    <a class="nav-link" href="#" onclick="alert('Próximamente')">
                        <i class="bi bi-plus-circle"></i>
                        Nuevo Movimiento
                    </a>
                    <a class="nav-link" href="#" onclick="alert('Próximamente')">
                        <i class="bi bi-clock-history"></i>
                        Historial
                    </a>
                </nav>

                <hr>

                <div class="sidebar-header">TIPOS DE MOVIMIENTO</div>
                <nav class="nav flex-column">
                    <a class="nav-link" href="#" onclick="alert('Próximamente')">
                        <i class="bi bi-arrow-down-square"></i>
                        Entradas de Almacén
                    </a>
                    <a class="nav-link" href="#" onclick="alert('Próximamente')">
                        <i class="bi bi-arrow-up-square"></i>
                        Salidas de Almacén
                    </a>
                    <a class="nav-link" href="#" onclick="alert('Próximamente')">
                        <i class="bi bi-arrow-left-right"></i>
                        Traslados entre Bodegas
                    </a>
                </nav>

                <hr>

                <div class="sidebar-header">FACTURACIÓN</div>
                <nav class="nav flex-column">
                    <a class="nav-link" href="#" onclick="alert('Próximamente')">
                        <i class="bi bi-receipt"></i>
                        Consolidar Facturas
                    </a>
                    <a class="nav-link" href="#" onclick="alert('Próximamente')">
                        <i class="bi bi-file-earmark-text"></i>
                        Facturas Registradas
                    </a>
                    <a class="nav-link" href="#" onclick="alert('Próximamente')">
                        <i class="bi bi-currency-dollar"></i>
                        Control de Pagos
                    </a>
                </nav>

                <hr>

                <div class="sidebar-header">REPORTES</div>
                <nav class="nav flex-column">
                    <a class="nav-link" href="#" onclick="alert('Próximamente')">
                        <i class="bi bi-file-earmark-bar-graph"></i>
                        Kardex de Inventario
                    </a>
                    <a class="nav-link" href="#" onclick="alert('Próximamente')">
                        <i class="bi bi-graph-up"></i>
                        Movimientos por Período
                    </a>
                    <a class="nav-link" href="#" onclick="alert('Próximamente')">
                        <i class="bi bi-pie-chart"></i>
                        Análisis de Consumo
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
                    <li class="breadcrumb-item active">Inventario y Facturación</li>
                </ol>
            </nav>

            <!-- Header -->
            <div class="page-header">
                <div>
                    <h2 class="page-title">
                        <i class="bi bi-calculator me-2"></i>
                        Inventario y Facturación
                    </h2>
                    <p class="text-muted mb-0">Gestiona entradas, salidas, traslados y facturación</p>
                </div>
                <button class="btn btn-primary">
                    <i class="bi bi-plus me-1"></i>
                    Nuevo Movimiento
                </button>
            </div>

            <!-- Tarjetas de Estadísticas -->
            <div class="row stats-row">
                <div class="col-lg-3 col-md-6 mb-3">
                    <div class="stats-card blue">
                        <div class="stats-content">
                            <div class="stats-number">1630</div>
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
                            <div class="stats-number">890</div>
                            <div class="stats-label">Entradas</div>
                        </div>
                        <div class="stats-icon">
                            <i class="bi bi-arrow-down-square"></i>
                        </div>
                    </div>
                </div>

                <div class="col-lg-3 col-md-6 mb-3">
                    <div class="stats-card red">
                        <div class="stats-content">
                            <div class="stats-number">540</div>
                            <div class="stats-label">Salidas</div>
                        </div>
                        <div class="stats-icon">
                            <i class="bi bi-arrow-up-square"></i>
                        </div>
                    </div>
                </div>

                <div class="col-lg-3 col-md-6 mb-3">
                    <div class="stats-card orange">
                        <div class="stats-content">
                            <div class="stats-number">200</div>
                            <div class="stats-label">Traslados</div>
                        </div>
                        <div class="stats-icon">
                            <i class="bi bi-arrow-left-right"></i>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tabla de Movimientos -->
            <div class="table-container">
                <!-- Header de la tabla -->
                <div class="table-header">
                    <h5 class="table-title">
                        <i class="bi bi-table"></i>
                        Documentos Creados
                        <span class="badge bg-light text-dark ms-2">1630</span>
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
                                <option value="">Tipo de Documento:</option>
                                <option value="entrada">Entradas (I-SA)</option>
                                <option value="salida">Salidas (I-TR)</option>
                                <option value="traslado">Traslados (I-RE)</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <select class="form-select">
                                <option value="">Estado:</option>
                                <option value="activo">Activo</option>
                                <option value="formalizado">Formalizado</option>
                                <option value="pendiente">Pendiente</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <select class="form-select">
                                <option value="">Proceso:</option>
                                <option value="facturacion">Facturación</option>
                                <option value="consolidacion">Consolidación</option>
                                <option value="traslado">Traslado</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <input type="text" class="form-control" placeholder="Buscar documento, responsable...">
                        </div>
                        <div class="col-md-2">
                            <select class="form-select">
                                <option value="">Centro:</option>
                                <option value="extension">EXTENSIÓN ACADÉMICA</option>
                                <option value="campus">CAMPUS UNIVERSITARIO</option>
                                <option value="acreditacion">ACREDITACIÓN</option>
                                <option value="bienestar">DIRECCIÓN DE BIENESTAR</option>
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
                    <table class="table movimientos-table">
                        <thead>
                            <tr>
                                <th>Documento</th>
                                <th>F. Creación / Quincena</th>
                                <th>Responsables (Recibe)</th>
                                <th>Centro de Costos</th>
                                <th># Factura / Pedido</th>
                                <th>Fecha Fact / Pedido</th>
                                <th>Estado</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>
                                    <div>
                                        <span class="badge-custom badge-documento">I-SA1788-80</span>
                                        <br>
                                        <span class="badge-custom badge-entrada">Entrada</span>
                                    </div>
                                </td>
                                <td>
                                    <div>
                                        <strong>2025-06-04 19:25:05</strong>
                                        <br>
                                        <span class="badge-custom badge-quincena">Quincena: 1</span>
                                    </div>
                                </td>
                                <td>
                                    <div>
                                        <strong>1020446085 MARIA ALEJANDRA MONTOYA CENTENO</strong>
                                        <br>
                                        <small class="text-muted">208001001 EXTENSIÓN ACADÉMICA2</small>
                                    </div>
                                </td>
                                <td>
                                    <div>
                                        <strong>EXTENSIÓN ACADÉMICA</strong>
                                        <br>
                                        <small class="text-muted">208001001</small>
                                    </div>
                                </td>
                                <td>
                                    <div>
                                        <span class="badge-custom badge-numero">3086</span>
                                        <br>
                                        <small class="text-muted">Factura</small>
                                    </div>
                                </td>
                                <td>
                                    <div>
                                        <strong>2025-06-04</strong>
                                    </div>
                                </td>
                                <td>
                                    <div class="d-flex flex-column gap-1">
                                        <span class="badge-custom badge-activo">Activo</span>
                                        <span class="badge-custom badge-formalizado">Formalizado</span>
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
                                        <button class="btn btn-outline-info btn-action" title="Ver Factura">
                                            <i class="bi bi-receipt"></i>
                                        </button>
                                        <button class="btn btn-outline-success btn-action" title="Imprimir">
                                            <i class="bi bi-printer"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <div>
                                        <span class="badge-custom badge-documento">I-SA1787-61</span>
                                        <br>
                                        <span class="badge-custom badge-entrada">Entrada</span>
                                    </div>
                                </td>
                                <td>
                                    <div>
                                        <strong>2025-06-04 18:41:41</strong>
                                        <br>
                                        <span class="badge-custom badge-quincena">Quincena: 1</span>
                                    </div>
                                </td>
                                <td>
                                    <div>
                                        <strong>70110130 CARLOS ALBERTO YEPES DUQUE</strong>
                                        <br>
                                        <small class="text-muted">102001016 CAMPUS UNIVERSITARIO ABURRA SUR-TDEA</small>
                                    </div>
                                </td>
                                <td>
                                    <div>
                                        <strong>CAMPUS UNIVERSITARIO</strong>
                                        <br>
                                        <small class="text-muted">102001016</small>
                                    </div>
                                </td>
                                <td>
                                    <div>
                                        <span class="badge-custom badge-numero">3068</span>
                                        <br>
                                        <small class="text-muted">Factura</small>
                                    </div>
                                </td>
                                <td>
                                    <div>
                                        <strong>2025-06-04</strong>
                                    </div>
                                </td>
                                <td>
                                    <div class="d-flex flex-column gap-1">
                                        <span class="badge-custom badge-activo">Activo</span>
                                        <span class="badge-custom badge-formalizado">Formalizado</span>
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
                                        <button class="btn btn-outline-info btn-action" title="Ver Factura">
                                            <i class="bi bi-receipt"></i>
                                        </button>
                                        <button class="btn btn-outline-success btn-action" title="Imprimir">
                                            <i class="bi bi-printer"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <div>
                                        <span class="badge-custom badge-documento">I-SA1786-6</span>
                                        <br>
                                        <span class="badge-custom badge-entrada">Entrada</span>
                                    </div>
                                </td>
                                <td>
                                    <div>
                                        <strong>2025-06-04 18:39:55</strong>
                                        <br>
                                        <span class="badge-custom badge-quincena">Quincena: 1</span>
                                    </div>
                                </td>
                                <td>
                                    <div>
                                        <strong>43838503 SANDRA YANETH RUEDA VILLA</strong>
                                        <br>
                                        <small class="text-muted">201001007 ACREDITACIÓN</small>
                                    </div>
                                </td>
                                <td>
                                    <div>
                                        <strong>ACREDITACIÓN</strong>
                                        <br>
                                        <small class="text-muted">201001007</small>
                                    </div>
                                </td>
                                <td>
                                    <div>
                                        <span class="badge-custom badge-numero">2949</span>
                                        <br>
                                        <small class="text-muted">Factura</small>
                                    </div>
                                </td>
                                <td>
                                    <div>
                                        <strong>2025-06-04</strong>
                                    </div>
                                </td>
                                <td>
                                    <div class="d-flex flex-column gap-1">
                                        <span class="badge-custom badge-activo">Activo</span>
                                        <span class="badge-custom badge-formalizado">Formalizado</span>
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
                                        <button class="btn btn-outline-info btn-action" title="Ver Factura">
                                            <i class="bi bi-receipt"></i>
                                        </button>
                                        <button class="btn btn-outline-success btn-action" title="Imprimir">
                                            <i class="bi bi-printer"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <div>
                                        <span class="badge-custom badge-documento">I-SA1785-109</span>
                                        <br>
                                        <span class="badge-custom badge-salida">Salida</span>
                                    </div>
                                </td>
                                <td>
                                    <div>
                                        <strong>2025-06-04 18:38:01</strong>
                                        <br>
                                        <span class="badge-custom badge-quincena">Quincena: 1</span>
                                    </div>
                                </td>
                                <td>
                                    <div>
                                        <strong>39272544 YASMINE STELLA MASSIRI ALVAREZ</strong>
                                        <br>
                                        <small class="text-muted">105001001 DIRECCIÓN DE BIENESTAR3</small>
                                    </div>
                                </td>
                                <td>
                                    <div>
                                        <strong>DIRECCIÓN DE BIENESTAR</strong>
                                        <br>
                                        <small class="text-muted">105001001</small>
                                    </div>
                                </td>
                                <td>
                                    <div>
                                        <span class="badge-custom badge-numero">2931</span>
                                        <br>
                                        <small class="text-muted">Pedido</small>
                                    </div>
                                </td>
                                <td>
                                    <div>
                                        <strong>2025-06-04</strong>
                                    </div>
                                </td>
                                <td>
                                    <div class="d-flex flex-column gap-1">
                                        <span class="badge-custom badge-activo">Activo</span>
                                        <span class="badge-custom badge-formalizado">Formalizado</span>
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
                                        <button class="btn btn-outline-warning btn-action" title="Ver Pedido">
                                            <i class="bi bi-clipboard-check"></i>
                                        </button>
                                        <button class="btn btn-outline-success btn-action" title="Imprimir">
                                            <i class="bi bi-printer"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <div>
                                        <span class="badge-custom badge-documento">I-SA1783-63</span>
                                        <br>
                                        <span class="badge-custom badge-entrada">Entrada</span>
                                    </div>
                                </td>
                                <td>
                                    <div>
                                        <strong>2025-06-04 18:10:36</strong>
                                        <br>
                                        <span class="badge-custom badge-quincena">Quincena: 1</span>
                                    </div>
                                </td>
                                <td>
                                    <div>
                                        <strong>1035431878 DANIELA MONTOYA MARIN</strong>
                                        <br>
                                        <small class="text-muted">202001002 FACULTAD DE CIENCIAS ADMINISTRATIVAS Y ECONOMICAS-DOCENTES T</small>
                                    </div>
                                </td>
                                <td>
                                    <div>
                                        <strong>FACULTAD CIENCIAS ADMINISTRATIVAS</strong>
                                        <br>
                                        <small class="text-muted">202001002</small>
                                    </div>
                                </td>
                                <td>
                                    <div>
                                        <span class="badge-custom badge-numero">2897</span>
                                        <br>
                                        <small class="text-muted">Factura</small>
                                    </div>
                                </td>
                                <td>
                                    <div>
                                        <strong>2025-06-04</strong>
                                    </div>
                                </td>
                                <td>
                                    <div class="d-flex flex-column gap-1">
                                        <span class="badge-custom badge-activo">Activo</span>
                                        <span class="badge-custom badge-formalizado">Formalizado</span>
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
                                        <button class="btn btn-outline-info btn-action" title="Ver Factura">
                                            <i class="bi bi-receipt"></i>
                                        </button>
                                        <button class="btn btn-outline-success btn-action" title="Imprimir">
                                            <i class="bi bi-printer"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <div>
                                        <span class="badge-custom badge-documento">I-RE132-91</span>
                                        <br>
                                        <span class="badge-custom badge-traslado">Traslado</span>
                                    </div>
                                </td>
                                <td>
                                    <div>
                                        <strong>2025-06-04 17:01:22</strong>
                                        <br>
                                        <span class="badge-custom badge-quincena">Quincena: 1</span>
                                    </div>
                                </td>
                                <td>
                                    <div>
                                        <strong>901349914 BALDER S.A.S</strong>
                                        <br>
                                        <small class="text-muted">901349914 BALDER</small>
                                    </div>
                                </td>
                                <td>
                                    <div>
                                        <strong>ALMACÉN CENTRAL</strong>

                                        <br>
                                        <small class="text-muted">→ BODEGA SECUNDARIA</small>
                                    </div>
                                </td>
                                <td>
                                    <div>
                                        <span class="badge-custom badge-numero">20</span>
                                        <br>
                                        <small class="text-muted">Traslado</small>
                                    </div>
                                </td>
                                <td>
                                    <div>
                                        <strong>2025-06-04</strong>
                                    </div>
                                </td>
                                <td>
                                    <div class="d-flex flex-column gap-1">
                                        <span class="badge-custom badge-activo">Activo</span>
                                        <span class="badge-custom badge-pendiente">Pendiente</span>
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
                                        <button class="btn btn-outline-warning btn-action" title="Confirmar Traslado">
                                            <i class="bi bi-arrow-left-right"></i>
                                        </button>
                                        <button class="btn btn-outline-success btn-action" title="Imprimir">
                                            <i class="bi bi-printer"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <div>
                                        <span class="badge-custom badge-documento">I-TR8185-210</span>
                                        <br>
                                        <span class="badge-custom badge-salida">Salida</span>
                                    </div>
                                </td>
                                <td>
                                    <div>
                                        <strong>2025-06-04 16:06:15</strong>
                                        <br>
                                        <span class="badge-custom badge-quincena">Quincena: 1</span>
                                    </div>
                                </td>
                                <td>
                                    <div>
                                        <strong>22234499 NORELA DEJESUS RIVERA RIOS</strong>
                                        <br>
                                        <small class="text-muted">106006001 SERVICIOS GENERALES2</small>
                                    </div>
                                </td>
                                <td>
                                    <div>
                                        <strong>SERVICIOS GENERALES</strong>
                                        <br>
                                        <small class="text-muted">106006001</small>
                                    </div>
                                </td>
                                <td>
                                    <div>
                                        <span class="badge-custom badge-numero">2973</span>
                                        <br>
                                        <small class="text-muted">Pedido</small>
                                    </div>
                                </td>
                                <td>
                                    <div>
                                        <strong>2025-06-04</strong>
                                    </div>
                                </td>
                                <td>
                                    <div class="d-flex flex-column gap-1">
                                        <span class="badge-custom badge-activo">Activo</span>
                                        <span class="badge-custom badge-formalizado">Formalizado</span>
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
                                        <button class="btn btn-outline-warning btn-action" title="Ver Pedido">
                                            <i class="bi bi-clipboard-check"></i>
                                        </button>
                                        <button class="btn btn-outline-success btn-action" title="Imprimir">
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
                        <div class="card h-100 shadow-sm">
                            <div class="card-header bg-success text-white d-flex justify-content-between align-items-center">
                                <h6 class="mb-0">I-SA1788-80</h6>
                                <span class="badge bg-light text-success">Entrada</span>
                            </div>
                            <div class="card-body">
                                <p class="card-text">
                                    <strong>Fecha:</strong> 2025-06-04 19:25:05<br>
                                    <strong>Responsable:</strong> MARIA ALEJANDRA MONTOYA<br>
                                    <strong>Centro:</strong> EXTENSIÓN ACADÉMICA<br>
                                    <strong>Factura:</strong> 3086<br>
                                    <strong>Estado:</strong> Activo - Formalizado
                                </p>
                            </div>
                            <div class="card-footer">
                                <div class="btn-actions justify-content-center">
                                    <button class="btn btn-outline-primary btn-action" title="Ver Detalle">
                                        <i class="bi bi-eye"></i>
                                    </button>
                                    <button class="btn btn-outline-info btn-action" title="Ver Factura">
                                        <i class="bi bi-receipt"></i>
                                    </button>
                                    <button class="btn btn-outline-success btn-action" title="Imprimir">
                                        <i class="bi bi-printer"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-6 mb-4">
                        <div class="card h-100 shadow-sm">
                            <div class="card-header bg-success text-white d-flex justify-content-between align-items-center">
                                <h6 class="mb-0">I-SA1787-61</h6>
                                <span class="badge bg-light text-success">Entrada</span>
                            </div>
                            <div class="card-body">
                                <p class="card-text">
                                    <strong>Fecha:</strong> 2025-06-04 18:41:41<br>
                                    <strong>Responsable:</strong> CARLOS ALBERTO YEPES<br>
                                    <strong>Centro:</strong> CAMPUS UNIVERSITARIO<br>
                                    <strong>Factura:</strong> 3068<br>
                                    <strong>Estado:</strong> Activo - Formalizado
                                </p>
                            </div>
                            <div class="card-footer">
                                <div class="btn-actions justify-content-center">
                                    <button class="btn btn-outline-primary btn-action" title="Ver Detalle">
                                        <i class="bi bi-eye"></i>
                                    </button>
                                    <button class="btn btn-outline-info btn-action" title="Ver Factura">
                                        <i class="bi bi-receipt"></i>
                                    </button>
                                    <button class="btn btn-outline-success btn-action" title="Imprimir">
                                        <i class="bi bi-printer"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-6 mb-4">
                        <div class="card h-100 shadow-sm">
                            <div class="card-header bg-danger text-white d-flex justify-content-between align-items-center">
                                <h6 class="mb-0">I-SA1785-109</h6>
                                <span class="badge bg-light text-danger">Salida</span>
                            </div>
                            <div class="card-body">
                                <p class="card-text">
                                    <strong>Fecha:</strong> 2025-06-04 18:38:01<br>
                                    <strong>Responsable:</strong> YASMINE STELLA MASSIRI<br>
                                    <strong>Centro:</strong> DIRECCIÓN DE BIENESTAR<br>
                                    <strong>Pedido:</strong> 2931<br>
                                    <strong>Estado:</strong> Activo - Formalizado
                                </p>
                            </div>
                            <div class="card-footer">
                                <div class="btn-actions justify-content-center">
                                    <button class="btn btn-outline-primary btn-action" title="Ver Detalle">
                                        <i class="bi bi-eye"></i>
                                    </button>
                                    <button class="btn btn-outline-warning btn-action" title="Ver Pedido">
                                        <i class="bi bi-clipboard-check"></i>
                                    </button>
                                    <button class="btn btn-outline-success btn-action" title="Imprimir">
                                        <i class="bi bi-printer"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-6 mb-4">
                        <div class="card h-100 shadow-sm">
                            <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                                <h6 class="mb-0">I-RE132-91</h6>
                                <span class="badge bg-light text-primary">Traslado</span>
                            </div>
                            <div class="card-body">
                                <p class="card-text">
                                    <strong>Fecha:</strong> 2025-06-04 17:01:22<br>
                                    <strong>Responsable:</strong> BALDER S.A.S<br>
                                    <strong>Traslado:</strong> ALMACÉN → BODEGA<br>
                                    <strong>Número:</strong> 20<br>
                                    <strong>Estado:</strong> Activo - Pendiente
                                </p>
                            </div>
                            <div class="card-footer">
                                <div class="btn-actions justify-content-center">
                                    <button class="btn btn-outline-primary btn-action" title="Ver Detalle">
                                        <i class="bi bi-eye"></i>
                                    </button>
                                    <button class="btn btn-outline-warning btn-action" title="Confirmar Traslado">
                                        <i class="bi bi-arrow-left-right"></i>
                                    </button>
                                    <button class="btn btn-outline-success btn-action" title="Imprimir">
                                        <i class="bi bi-printer"></i>
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