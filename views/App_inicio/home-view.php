
<style>
	:root {
		--primary-color: #0d6efd;/*#1B5E20;*/
		--secondary-color: #6c757d;/*#388E3C;*/
		--navbar-color: #343a40;/*#2E7D32;*/
		--sidebar-color: #495057; /*#495057;*/
		--accent-color: #198754;/*#198754;*/
	}

	.navbar-custom {
		background: linear-gradient(135deg, var(--primary-color), var(--navbar-color));
		box-shadow: 0 4px 6px rgba(0,0,0,0.1);
	}

	.card-service {
		transition: all 0.3s ease;
		border: none;
		box-shadow: 0 4px 6px rgba(0,0,0,0.1);
	}

	.card-service:hover {
		transform: translateY(-5px);
		box-shadow: 0 8px 15px rgba(0,0,0,0.2);
	}

	.carousel-custom {
		margin-top: 5rem;
	}

	.footer-custom {
		background: linear-gradient(135deg, var(--sidebar-color), var(--navbar-color));
		color: white;
		margin-top: 4rem;
	}

	.btn-raised {
		box-shadow: 0 2px 4px rgba(0,0,0,0.2);
		transition: all 0.3s ease;
	}

	.btn-raised:hover {
		box-shadow: 0 4px 8px rgba(0,0,0,0.3);
		transform: translateY(-1px);
	}

	.service-icon {
		font-size: 3rem;
		color: var(--primary-color);
		margin-bottom: 1rem;
	}

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

	.btn-outline-light {
		border-color: rgba(255,255,255,0.8);
	}

	.btn-outline-light:hover {
		background-color: rgba(255,255,255,0.2);
		border-color: white;
	}

	.text-primary {
		color: var(--primary-color) !important;
	}

	.bg-primary {
		background-color: var(--primary-color) !important;
	}

	.modal-header {
		background: linear-gradient(135deg, var(--primary-color), var(--navbar-color));
		color: white;
	}

	.modal-header .btn-close {
		filter: invert(1);
	}

	/* Efectos adicionales con los colores del tema */
	.carousel-caption {
		background: linear-gradient(135deg, rgba(0,0,0,0.5), rgba(var(--primary-color-rgb, 13, 110, 253), 0.3));
		border-radius: 10px;
		padding: 20px;
	}

	.display-5 {
		color: var(--navbar-color);
	}

	.lead {
		color: var(--secondary-color);
	}

	/* Animaciones con colores del tema */
	@keyframes pulse-primary {
		0% { box-shadow: 0 0 0 0 rgba(var(--primary-color-rgb, 13, 110, 253), 0.7); }
		70% { box-shadow: 0 0 0 10px rgba(var(--primary-color-rgb, 13, 110, 253), 0); }
		100% { box-shadow: 0 0 0 0 rgba(var(--primary-color-rgb, 13, 110, 253), 0); }
	}

	.btn-primary:focus {
		animation: pulse-primary 1.5s infinite;
	}

	/* Gradientes personalizados con colores del tema */
	.navbar-brand {
		background: linear-gradient(45deg, white, rgba(255,255,255,0.8));
		-webkit-background-clip: text;
		-webkit-text-fill-color: transparent;
		background-clip: text;
		font-weight: bold;
	}

	/* Hover effects con colores del tema */
	.card-service:hover .service-icon {
		color: var(--accent-color);
		transform: scale(1.1);
		transition: all 0.3s ease;
	}

	/* Indicadores de carousel con colores del tema */
	.carousel-indicators button {
		background-color: var(--primary-color);
	}

	.carousel-indicators button.active {
		background-color: var(--accent-color);
	}
	.bodyfull{
		margin-top: 5.4rem;
	}
	
	.carousel-item img {
		/*height: 400px;
		object-fit: cover;
		object-position: center;*/
		
		height: 600px;
		/*object-fit: contain; /* Muestra toda la imagen completa */
		object-position: center;
		background-color: #f5f5f5; /* Para llenar espacios vacíos */
	}
</style>
<div>
	<div class="bodyfull">
		<!-- Navbar -->
		<nav class="navbar navbar-expand-lg navbar-dark navbar-custom fixed-top">
			<div class="container">
				<a class="navbar-brand fw-bold" href="#">
					<i class="bi bi-building me-2"></i>
					<?php echo (!empty(COMPANY)) ? COMPANY : "Sistema de Gestión"; ?>
				</a>
				<button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
					<span class="navbar-toggler-icon"></span>
				</button>
				<div class="collapse navbar-collapse" id="navbarNav">
					<ul class="navbar-nav ms-auto">
						<li class="nav-item">
							<a class="nav-link" href="#servicios">Servicios</a>
						</li>
						<li class="nav-item">
							<a class="nav-link" href="#contacto">Contacto</a>
						</li>
						<li class="nav-item">
							<button class="btn btn-outline-light btn-sm" data-bs-toggle="modal" data-bs-target="#loginModal">
								<i class="bi bi-person-fill me-1"></i>
								Iniciar Sesión
							</button>
						</li>
					</ul>
				</div>
			</div>
		</nav>

		<!-- Carousel -->
		<?php 
		$Ver_carrusel = 1;
		if($Ver_carrusel == 1){ ?>
		<div id="mainCarousel" class="carousel slide carousel-custom" data-bs-ride="carousel">
			<div class="carousel-indicators">
				<button type="button" data-bs-target="#mainCarousel" data-bs-slide-to="0" class="active"></button>
				<button type="button" data-bs-target="#mainCarousel" data-bs-slide-to="1"></button>
				<button type="button" data-bs-target="#mainCarousel" data-bs-slide-to="2"></button>
				<button type="button" data-bs-target="#mainCarousel" data-bs-slide-to="3"></button>
				<button type="button" data-bs-target="#mainCarousel" data-bs-slide-to="4"></button>
			</div>
			<div class="carousel-inner">
				<div class="carousel-item active">
					<img src="<?php echo SERVERURL?>assets/slide/slide1.jpg" class="d-block w-100" alt="Slide 1" >
					<div class="carousel-caption d-md-block">
						<h5>Gestión Eficiente</h5>
						<p>Sistema integral para la administración de recursos y servicios.</p>
						<a href="#" class="btn btn-light btn-raised">Descargar Formato</a>
					</div>
				</div>
				<div class="carousel-item">
					<img src="<?php echo SERVERURL?>assets/slide/slide2.jpg" class="d-block w-100" alt="Slide 2" >
					<div class="carousel-caption d-md-block">
						<h5>Inventario Digital</h5>
						<p>Control total de activos y bienes institucionales.</p>
					</div>
				</div>
				<div class="carousel-item">
					<img src="<?php echo SERVERURL?>assets/slide/slide3.jpg" class="d-block w-100" alt="Slide 3">
					<div class="carousel-caption d-md-block">
						<h5>Servicios Integrados</h5>
						<p>Plataforma unificada para todas tus necesidades administrativas.</p>
					</div>
				</div>
				<div class="carousel-item">
					<img src="<?php echo SERVERURL?>assets/slide/slide4.jpg" class="d-block w-100" alt="Slide 4">
					<div class="carousel-caption d-md-block">
						<h5>Servicios Integrados</h5>
						<p>Plataforma unificada para todas tus necesidades administrativas.</p>
					</div>
				</div>
				<div class="carousel-item">
					<img src="<?php echo SERVERURL?>assets/slide/slide5.jpg" class="d-block w-100" alt="Slide 5">
					<div class="carousel-caption d-md-block">
						<h5>Servicios Integrados</h5>
						<p>Plataforma unificada para todas tus necesidades administrativas.</p>
					</div>
				</div>
			</div>
			<button class="carousel-control-prev" type="button" data-bs-target="#mainCarousel" data-bs-slide="prev">
				<span class="carousel-control-prev-icon"></span>
				<span class="visually-hidden">Anterior</span>
			</button>
			<button class="carousel-control-next" type="button" data-bs-target="#mainCarousel" data-bs-slide="next">
				<span class="carousel-control-next-icon"></span>
				<span class="visually-hidden">Siguiente</span>
			</button>
		</div>
		<?php } ?>
		<!-- Servicios -->
		<div class="container my-5" id="servicios">
			<div class="row text-center mb-5">
				<div class="col">
					<h2 class="display-5 fw-bold">Nuestros Servicios</h2>
					<p class="lead">Accede a todos los servicios administrativos desde una sola plataforma</p>
				</div>
			</div>

			<div class="row row-cols-1 row-cols-md-2 row-cols-lg-4 g-4">
				<!-- Servicio 1: Fotocopias -->
				<div class="col">
					<div class="card card-service h-100 text-center">
						<div class="card-body">
							<i class="bi bi-file-text service-icon"></i>
							<h5 class="card-title">Fotocopias</h5>
							<p class="card-text">Verifica tu autorización para fotocopias y realízalas con el proveedor autorizado.</p>
							<a href="/copys/" class="btn btn-primary btn-raised">Ver detalles</a>
						</div>
					</div>
				</div>

				<!-- Servicio 2: Autoinventario -->
				<div class="col">
					<div class="card card-service h-100 text-center">
						<div class="card-body">
							<i class="bi bi-clipboard-check service-icon"></i>
							<h5 class="card-title">Autoinventario</h5>
							<p class="card-text">Lleva un control más apropiado sobre los diferentes bienes que se tienen bajo custodia.</p>
							<button class="btn btn-primary btn-raised" data-bs-toggle="modal" data-bs-target="#loginModal">Ver detalles</button>
						</div>
					</div>
				</div>

				<!-- Servicio 3: Autorización salida de bienes -->
				<div class="col">
					<div class="card card-service h-100 text-center">
						<div class="card-body">
							<i class="bi bi-box-arrow-right service-icon"></i>
							<h5 class="card-title">Autorización Salida</h5>
							<p class="card-text">Gestiona el préstamo de equipos para labores fuera de la Institución.</p>
							<div class="d-grid gap-2">
								<button class="btn btn-primary btn-raised btn-sm" data-bs-toggle="modal" data-bs-target="#loginModal">Ver detalles</button>
								<button class="btn btn-outline-primary btn-raised btn-sm" data-bs-toggle="modal" data-bs-target="#consultaModal">Verificar</button>
							</div>
						</div>
					</div>
				</div>

				<!-- Servicio 4: Activos bajo custodia -->
				<div class="col">
					<div class="card card-service h-100 text-center">
						<div class="card-body">
							<i class="bi bi-shield-check service-icon"></i>
							<h5 class="card-title">Activos Custodia</h5>
							<p class="card-text">Descarga informe con los bienes que tienes bajo tu custodia.</p>
							<button class="btn btn-primary btn-raised" data-bs-toggle="modal" data-bs-target="#loginModal">Ver detalles</button>
						</div>
					</div>
				</div>

				<!-- Servicio 5: Actualización de carteras -->
				<div class="col">
					<div class="card card-service h-100 text-center">
						<div class="card-body">
							<i class="bi bi-arrow-clockwise service-icon"></i>
							<h5 class="card-title">Actualización Carteras</h5>
							<p class="card-text">Mantente al día sobre las novedades de los bienes bajo custodia.</p>
							<button class="btn btn-primary btn-raised" data-bs-toggle="modal" data-bs-target="#loginModal">Ver detalles</button>
						</div>
					</div>
				</div>

				<!-- Servicio 6: Mantenimiento -->
				<div class="col">
					<div class="card card-service h-100 text-center">
						<div class="card-body">
							<i class="bi bi-tools service-icon"></i>
							<h5 class="card-title">Mantenimiento</h5>
							<p class="card-text">Reparación y mantenimiento de equipos e instalaciones. Servicios de traslado.</p>
							<button class="btn btn-primary btn-raised" data-bs-toggle="modal" data-bs-target="#mantenimientoModal">Ver detalles</button>
						</div>
					</div>
				</div>

				<!-- Servicio 7: Pedido de suministros -->
				<div class="col">
					<div class="card card-service h-100 text-center">
						<div class="card-body">
							<i class="bi bi-box service-icon"></i>
							<h5 class="card-title">Suministros</h5>
							<p class="card-text">Solicite insumos que necesita para el desarrollo de las labores.</p>
							<button class="btn btn-primary btn-raised" data-bs-toggle="modal" data-bs-target="#loginModal">Ver detalles</button>
						</div>
					</div>
				</div>

				<!-- Servicio 8: Solicitud de servicios -->
				<div class="col">
					<div class="card card-service h-100 text-center">
						<div class="card-body">
							<i class="bi bi-gear service-icon"></i>
							<h5 class="card-title">Servicios</h5>
							<p class="card-text">Alimentación, Hospedaje y transporte.</p>
							<button class="btn btn-primary btn-raised" data-bs-toggle="modal" data-bs-target="#loginModal">Ver detalles</button>
						</div>
					</div>
				</div>
			</div>
		</div>

		<!-- Modal de Login -->
		<div class="modal fade" id="loginModal" tabindex="-1">
			<div class="modal-dialog modal-dialog-centered">
				<div class="modal-content">
					<div class="modal-header">
						<h5 class="modal-title">
							<i class="bi bi-person-circle me-2"></i>
							Iniciar Sesión
						</h5>
						<button type="button" class="btn-close" data-bs-dismiss="modal"></button>
					</div>
					<div class="modal-body">
						<div id="loginForm">
							<form id="loginFormElement" method="post" action="#">

								<div class="form-floating mb-3">
									<input type="text" class="form-control" id="usuario" name="usuario" placeholder="Usuario" required >
									<input type="hidden" id="csrf_token_login" name="csrf_token_login">
									<label for="usuario">
										<i class="bi bi-person me-2"></i>Usuario
									</label>
								</div>
								<div class="form-floating mb-3">
									<input type="password" class="form-control" id="clave" name="clave" placeholder="Contraseña" required>
									<label for="clave">
										<i class="bi bi-lock me-2"></i>Contraseña
									</label>
								</div>
								<button type="submit" class="btn btn-primary w-100 btn-raised" >
									<i class="bi bi-box-arrow-in-right me-2"></i>
									Iniciar Sesión
								</button>
							</form>
							<div class="text-center mt-3">
								<a href="#" id="recordarBtn" class="text-decoration-none">
									¿Olvidaste tu usuario y/o contraseña?
								</a>
							</div>
						</div>

						<!-- Formulario de recuperación -->
						<div id="recuperarForm" style="display: none;">
							<h6>Recuperar Cuenta</h6>
							<div class="form-floating mb-3">
								<input type="number" class="form-control" id="cc_usuario" placeholder="123456789" required>
								<label for="cc_usuario">
									<i class="bi bi-card-text me-2"></i>Número de identidad
								</label>
							</div>
							<div id="notiResult"></div>
							<button type="button" id="recuperarBtn" class="btn btn-primary w-100 btn-raised">
								Recordar usuario / contraseña
							</button>
							<div class="text-center mt-2">
								<a href="#" id="volverLoginBtn" class="text-decoration-none">
									Ya recordé mi usuario
								</a>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>

		<!-- Modal de Consulta -->
		<div class="modal fade" id="consultaModal" tabindex="-1">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-header">
						<h5 class="modal-title">
							<i class="bi bi-search me-2"></i>
							Consultar Autorización
						</h5>
						<button type="button" class="btn-close" data-bs-dismiss="modal"></button>
					</div>
					<div class="modal-body">
						<form>
							<div class="mb-3">
								<label class="form-label">Tipo de Documento</label>
								<select class="form-select" required>
									<option value="">Seleccionar...</option>
									<option value="salida">Autorización de Salida</option>
									<option value="prestamo">Préstamo de Equipos</option>
								</select>
							</div>
							<div class="mb-3">
								<label class="form-label">Número de Documento</label>
								<input type="number" class="form-control" placeholder="Ingrese el número" required>
							</div>
							<button type="submit" class="btn btn-primary btn-raised w-100">
								<i class="bi bi-eye me-2"></i>
								Visualizar
							</button>
						</form>
					</div>
				</div>
			</div>
		</div>

		<!-- Footer -->
		<footer class="footer-custom py-5" id="contacto">
			<div class="container">
				<div class="row">
					<div class="col-md-4 text-center mb-4">
						<i class="bi bi-headset display-4 mb-3"></i>
						<h4>Atención en?</h4>
						<hr class="w-50 mx-auto">
						<p class="fw-bold">Nombre completo de agente</p>
						<p><i class="bi bi-envelope me-2"></i>Email</p>
						<p><i class="bi bi-geo-alt me-2"></i>Ubicación</p>
						<p><i class="bi bi-telephone me-2"></i>Telefono - extensión</p>
					</div>
					<div class="col-md-4 text-center mb-4">
						<i class="bi bi-headset display-4 mb-3"></i>
						<h4>Atención en?</h4>
						<hr class="w-50 mx-auto">
						<p class="fw-bold">Nombre completo de agente</p>
						<p><i class="bi bi-envelope me-2"></i>Email</p>
						<p><i class="bi bi-geo-alt me-2"></i>Ubicación</p>
						<p><i class="bi bi-telephone me-2"></i>Telefono - extensión</p>
					</div>
					<div class="col-md-4 text-center mb-4">
						<i class="bi bi-headset display-4 mb-3"></i>
						<h4>Atención en?</h4>
						<hr class="w-50 mx-auto">
						<p class="fw-bold">Nombre completo de agente</p>
						<p><i class="bi bi-envelope me-2"></i>Email</p>
						<p><i class="bi bi-geo-alt me-2"></i>Ubicación</p>
						<p><i class="bi bi-telephone me-2"></i>Telefono - extensión</p>
					</div>
				</div>
				<hr class="my-4">
				<div class="row text-center">
					<div class="col">
						<p>&copy;<?php echo (!empty(COMPANY)) ? COMPANY : "Sistema de Gestión" ?>  Todos los derechos reservados.</p>
						<a href="#" class="text-white-50 text-decoration-none">Volver arriba</a>
					</div>
				</div>
			</div>
		</footer>

		<script src="<?php echo SERVERURL?>views/App_inicio/js/login.js"></script>

		<script>
			// Smooth scroll para navegación
			document.querySelectorAll('a[href^="#"]').forEach(anchor => {
				anchor.addEventListener('click', function (e) {
					e.preventDefault();
					const target = document.querySelector(this.getAttribute('href'));
					if (target) {
						target.scrollIntoView({
							behavior: 'smooth'
						});
					}
				});
			});
		</script>
	</div>
</div>