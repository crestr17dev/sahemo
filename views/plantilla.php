<?php
	// Iniciar sesión
	session_start(['name' => SESION]);


	$peticionAjax = false;
	require_once "./controllers/vistasControlador.php";
	$vt = new vistasControlador();

	// Obtener la vista solicitada
	$vistasR = $vt->obtener_vistas_controlador();

	$_SESSION['token_total_sesion'] = $vt->generar_token_visita();

	// Variables para JavaScript - información del usuario logueado
	$nombreCompleto = ($_SESSION['UsuarioNombres'] ?? '') . ' ' . ($_SESSION['UsuarioApellidos'] ?? '');
	$codigoUsuario = $_SESSION['CodigoUsuario'] ?? '';
	$usuarioId = $_SESSION['UsuarioId'] ?? '';

?>
 
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo (!empty(COMPANY)) ? COMPANY : "Sistema de Gestión" ?></title>
	<link rel="shortcut icon" href="<?php echo SERVERURL; ?>assets/img/6989.png" />
    <!-- Bootstrap 5 CSS -->
    <link href="<?php echo SERVERURL; ?>assets/libs/bootstrap/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="<?php echo SERVERURL; ?>assets/libs/bootstrap/bootstrap-icons.css" rel="stylesheet">
    <!-- SweetAlert2 -->
    <script src="<?php echo SERVERURL; ?>assets/libs/sweetalert2/sweetalert2.min.js"></script>
	<!-- Bootstrap 5 JS -->
	<script src="<?php echo SERVERURL; ?>assets/libs/bootstrap/bootstrap.bundle.min.js"></script>
</head>

<body>
	
	
	
	
    <?php  
    
    
    // Páginas públicas (sin login)
    $paginasPublicas = ["404", "home"];
    
    if(in_array($vistasR, $paginasPublicas)){
        
        if($vistasR == "home"){
            require_once "./views/App_inicio/home-view.php";
        }
        else{
            require_once "./views/App_inicio/404-view.php";
        }
        
    } else {
        // Páginas que requieren login
        /*if (isset($_SESSION['sesionactiva']) && $_SESSION['sesionactiva']){
            ?>
            <!-- Contenido para usuarios logueados -->
           <!-- <section class="full-box dashboard-contentPage">
               
                <?php //include "./vistas/generales/modulos/navbar.php"; ?>
                
                <div class="container">
                    <h1>Sistema en construcción</h1>
                    <p>Módulo: <?php //echo htmlspecialchars($vistasR); ?></p>
                </div>
            </section>-->
            
            <?php //include "./vistas/generales/modulos/logoutScript.php"; ?>
            <?php
        } else {
            // Redirigir a login si no está autenticado
            header("Location: " . SERVERURL . "");
            exit();
        }	*/
		require_once $vistasR; 
    }
	if (!in_array($vistasR, $paginasPublicas)) {
    ?>
	<!-- ==========================================================================================================
     MODAL DE CAMBIO DE CONTRASEÑA OBLIGATORIO - PARA DASHBOARD
     Este modal debe agregarse al archivo dashboard.php o dashboard-view.php
     ========================================================================================================== -->

	<!-- Modal de Cambio de Contraseña Obligatorio -->
	<div class="modal fade" id="modalCambioObligatorio" tabindex="-1" aria-labelledby="modalCambioObligatorioLabel" 
		 aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
		<div class="modal-dialog modal-lg">
			<div class="modal-content">
				<div class="modal-header bg-warning text-dark">
					<h5 class="modal-title" id="modalCambioObligatorioLabel">
						<i class="bi bi-shield-exclamation me-2"></i>
						Cambio de Contraseña Obligatorio
					</h5>
					<!-- Sin botón de cerrar para forzar el cambio -->
				</div>

				<div class="modal-body">
					<div class="alert alert-warning">
						<i class="bi bi-exclamation-triangle me-2"></i>
						<strong>¡Contraseña Temporal Detectada!</strong>
						<p class="mb-0 mt-2">
							Tu cuenta tiene una contraseña temporal. Por tu seguridad, debes cambiarla antes de continuar usando el sistema.
						</p>
					</div>

					<form id="formCambioObligatorio" novalidate>
						<input type="hidden" name="csrf_token_cambio" value="<?php echo $vt->obtener_token_csrf('cambioPasswordObligatorio'); ?>">

						<!-- Información del usuario -->
						<div class="user-info-card mb-4">
							<div class="row">
								<div class="col-md-8">
									<h6 class="mb-1">
										<i class="bi bi-person-circle me-2"></i>
										<span id="nombreUsuarioObligatorio">Nombre del Usuario</span>
									</h6>
									<small class="text-muted">Código: <span id="codigoUsuarioObligatorio">USxxxxxxx</span></small>
								</div>
								<div class="col-md-4 text-end">
									<span class="badge bg-warning">Contraseña Temporal</span>
								</div>
							</div>
						</div>

						<!-- Contraseña actual -->
						<div class="mb-3">
							<label for="passwordActualObligatorio" class="form-label required">
								<i class="bi bi-lock me-1"></i>
								Contraseña Temporal Actual
							</label>
							<div class="input-group">
								<span class="input-group-text">
									<i class="bi bi-key"></i>
								</span>
								<input type="password" class="form-control" id="passwordActualObligatorio" 
									   name="password-actual" placeholder="Ingresa tu contraseña temporal" required>
								<button class="btn btn-outline-secondary" type="button" onclick="togglePasswordObligatorio('passwordActualObligatorio', 'eyeActual')">
									<i class="bi bi-eye" id="eyeActual"></i>
								</button>
							</div>
							<div class="invalid-feedback"></div>
						</div>

						<!-- Nueva contraseña -->
						<div class="mb-3">
							<label for="passwordNuevaObligatoria" class="form-label required">
								<i class="bi bi-shield-lock me-1"></i>
								Nueva Contraseña
							</label>
							<div class="input-group">
								<span class="input-group-text">
									<i class="bi bi-lock-fill"></i>
								</span>
								<input type="password" class="form-control" id="passwordNuevaObligatoria" 
									   name="password-nueva" placeholder="Crea tu nueva contraseña" required>
								<button class="btn btn-outline-secondary" type="button" onclick="togglePasswordObligatorio('passwordNuevaObligatoria', 'eyeNueva')">
									<i class="bi bi-eye" id="eyeNueva"></i>
								</button>
							</div>
							<div class="invalid-feedback"></div>
						</div>

						<!-- Medidor de fuerza de contraseña -->
						<div class="password-strength mb-3">
							<div class="d-flex justify-content-between align-items-center mb-1">
								<small class="text-muted">Seguridad de la contraseña:</small>
								<small id="strengthTextObligatorio" class="fw-bold text-danger">Débil</small>
							</div>
							<div class="progress" style="height: 6px;">
								<div id="strengthBarObligatorio" class="progress-bar bg-danger" 
									 role="progressbar" style="width: 25%"></div>
							</div>
						</div>

						<!-- Confirmar contraseña -->
						<div class="mb-3">
							<label for="passwordConfirmarObligatoria" class="form-label required">
								<i class="bi bi-check-circle me-1"></i>
								Confirmar Nueva Contraseña
							</label>
							<div class="input-group">
								<span class="input-group-text">
									<i class="bi bi-check-square"></i>
								</span>
								<input type="password" class="form-control" id="passwordConfirmarObligatoria" 
									   name="password-confirmar" placeholder="Confirma tu nueva contraseña" required>
								<button class="btn btn-outline-secondary" type="button" onclick="togglePasswordObligatorio('passwordConfirmarObligatoria', 'eyeConfirmar')">
									<i class="bi bi-eye" id="eyeConfirmar"></i>
								</button>
							</div>
							<div class="invalid-feedback"></div>
						</div>

						<!-- Requisitos de contraseña -->
						<div class="requisitos-container">
							<h6 class="text-muted mb-2">
								<i class="bi bi-list-check me-1"></i>
								Requisitos de Contraseña:
							</h6>
							<div class="row">
								<div class="col-md-6">
									<div class="requisito-item" id="req-length-obligatorio">
										<i class="bi bi-x-circle text-danger me-1"></i>
										<small>Al menos 8 caracteres</small>
									</div>
									<div class="requisito-item" id="req-upper-obligatorio">
										<i class="bi bi-x-circle text-danger me-1"></i>
										<small>Una letra mayúscula</small>
									</div>
									<div class="requisito-item" id="req-lower-obligatorio">
										<i class="bi bi-x-circle text-danger me-1"></i>
										<small>Una letra minúscula</small>
									</div>
								</div>
								<div class="col-md-6">
									<div class="requisito-item" id="req-number-obligatorio">
										<i class="bi bi-x-circle text-danger me-1"></i>
										<small>Un número</small>
									</div>
									<div class="requisito-item" id="req-special-obligatorio">
										<i class="bi bi-x-circle text-danger me-1"></i>
										<small>Un símbolo (@#$%&*)</small>
									</div>
									<div class="requisito-item" id="req-match-obligatorio">
										<i class="bi bi-x-circle text-danger me-1"></i>
										<small>Las contraseñas coinciden</small>
									</div>
								</div>
							</div>
						</div>

						<!-- Información de expiración -->
						<div class="alert alert-info mt-3">
							<i class="bi bi-clock me-2"></i>
							<strong>Recordatorio:</strong> Tu contraseña temporal expira en 
							<span id="diasRestantesObligatorio" class="fw-bold">X días</span>.
							Después de eso no podrás acceder al sistema.
						</div>
					</form>
				</div>

				<div class="modal-footer">
					<button type="button" class="btn btn-success" id="btnCambiarObligatorio" 
							onclick="cambiarPasswordObligatorio()" disabled>
						<i class="bi bi-shield-check me-1"></i>
						Cambiar Contraseña y Continuar
					</button>
				</div>
			</div>
		</div>
	</div>
	<script>
		// Variables globales para JavaScript
		window.sessionUserName = '<?php echo htmlspecialchars($nombreCompleto, ENT_QUOTES, 'UTF-8'); ?>';
		window.sessionUserCode = '<?php echo htmlspecialchars($codigoUsuario, ENT_QUOTES, 'UTF-8'); ?>';
		window.sessionUserId = '<?php echo htmlspecialchars($usuarioId, ENT_QUOTES, 'UTF-8'); ?>';
	</script>
	
	<script src="<?php echo SERVERURL?>views/generales/js/app_plantilla.js"></script>	
	
	<?php
	}
	?>

</body>
</html>