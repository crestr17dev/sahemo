<?php
global $peticionAjax;
$peticionAjax=true;
require_once "../core/configGeneral.php";
// VERIFICAR SI LA SESIÓN YA ESTÁ ACTIVA
if (session_status() === PHP_SESSION_NONE) {
    session_start(['name' => SESION]);
}

if ((isset($_SESSION['sesionactiva']) && $_SESSION['sesionactiva'] == true)){
	if(isset($_POST['accion'])){
		
		require_once "../controllers/App_rolesController.php";
		$rolesController = new rolesController();
		
		//========================================================================================
		//FUNCIONES PARA ADMINISTRACIÓN DE ROLES
		//========================================================================================

		
		
		//regenero token para la función que se esté trabajando
		if(isset($_POST['accion']) && $_POST['accion'] == 'csrf_regenerar'){ 
			$key = $_POST['key'] ?? '';

			// Validar key permitidas (lista blanca)
			$keys_permitidas = [
				'modalNuevoRol'
			];

			if (!in_array($key, $keys_permitidas)) {
				header('Content-Type: application/json; charset=UTF-8');
				echo json_encode([
					'status' => 'error',
					'message' => 'Key no válida'
				], JSON_UNESCAPED_UNICODE);
				exit;
			}

			header('Content-Type: application/json; charset=UTF-8');
			echo json_encode([
				'token' => $rolesController->obtener_token_csrf($key),
				'status' => 'success'
			], JSON_UNESCAPED_UNICODE);
			exit;
		}
		
	}else{
		echo '<script>window.location.href="'.SERVERURL.'home/" </script>';
	}
}else{
	// CERRAR SESIÓN INVÁLIDA
    if (session_status() === PHP_SESSION_ACTIVE) {
        session_destroy();
    }
	echo '<script>window.location.href="'.SERVERURL.'" </script>';
}
?>