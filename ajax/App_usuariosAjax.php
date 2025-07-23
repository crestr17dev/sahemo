<?php
global $peticionAjax;
$peticionAjax=true;
require_once "../core/configGeneral.php";
// VERIFICAR SI LA SESIÓN YA ESTÁ ACTIVA
if (session_status() === PHP_SESSION_NONE) {
    session_start(['name' => SESION]);
}
$especial = false;
if($_POST['key'] == 'loginForm' || isset($_POST['clave'])){
	$especial = true;
}

if ((isset($_SESSION['sesionactiva']) && $_SESSION['sesionactiva'] == true) || $especial == true){
	if(isset($_POST['usuario-documento']) || isset($_POST['accion']) || isset($_POST['password-actual']) || isset($_POST['clave']) || isset($_POST['csrf_token_list_usuarios'])){
		
		require_once "../controllers/App_usuariosController.php";
		$usuariosController = new usuariosController();
		
		//========================================================================================
		//FUNCIONES PARA ADMINISTRACIÓN DE USUARIOS
		//========================================================================================
		
		// REGISTRO DE NUEVO USUARIO
		if(isset($_POST['usuario-documento'])){
			header('Content-Type: application/json; charset=UTF-8');
			echo $usuariosController->registrar_usuario_controlador();
		}
		// VERIFICAR SI USUARIO TIENE CONTRASEÑA TEMPORAL
		if(isset($_POST['accion']) && $_POST['accion'] == 'verificar_password_temporal'){
			header('Content-Type: application/json; charset=UTF-8');
			echo $usuariosController->verificar_password_temporal_controlador();
		}
		
		// CAMBIAR CONTRASEÑA TEMPORAL POR DEFINITIVA
		if(isset($_POST['password-actual'])){
			header('Content-Type: application/json; charset=UTF-8');
			echo $usuariosController->cambiar_password_temporal_controlador();
		}
		// LOGIN DE USUARIO
		if(isset($_POST['usuario']) && isset($_POST['clave'])){
			header('Content-Type: application/json; charset=UTF-8');
			echo $usuariosController->login_usuario_controlador();
		}
		
		// LOGOUT DE USUARIO
		if(isset($_POST['accion']) && $_POST['accion'] == 'logout'){
			header('Content-Type: application/json; charset=UTF-8');
			echo $usuariosController->logout_usuario_controlador();
		}
		// LISTAR USUARIOS
		if(isset($_POST['csrf_token_list_usuarios'])){
			header('Content-Type: application/json; charset=UTF-8');
			echo $usuariosController->listar_usuarios_controlador();
		}
		
		// ELIMINACIÓN DE EMPRESA
		if(isset($_POST['accion']) && $_POST['accion'] == 'eliminar_usuario'){
			header('Content-Type: application/json; charset=UTF-8');
			echo $usuariosController->eliminar_usuario_controlador();
		}
		
		//==============================================================================================
		//FUNCIONES PARA EDITAR USUARIOS
		//==============================================================================================
		
		// OBTENER DATOS DE UN USUARIO
		if(isset($_POST['accion']) && $_POST['accion'] == 'obtener_usuario'){
			header('Content-Type: application/json; charset=UTF-8');
			echo $usuariosController->obtener_usuario_controlador();
		}
		
		// ACTUALIZAR EMPRESA
		if(isset($_POST['accion']) && $_POST['accion'] == 'actualizar_usuario'){
			header('Content-Type: application/json; charset=UTF-8');
			echo $usuariosController->actualizar_usuario_controlador();
		}
		
		// CAMBIAR ESTADO DE EMPRESA
		if(isset($_POST['accion']) && $_POST['accion'] == 'cambiar_estado_usuario'){
			header('Content-Type: application/json; charset=UTF-8');
			echo $usuariosController->cambiar_estado_usuario_controlador();
		}
		
		
		
		//===========================================================================================================
		//FUNCIONES PARA GESTIÓN DE ROLES
		//===========================================================================================================

		// CREAR NUEVO ROL
		if(isset($_POST['accion']) && $_POST['accion'] == 'crear_rol'){
			header('Content-Type: application/json; charset=UTF-8');
			echo $usuariosController->crear_rol_controlador();
		}

		// OBTENER NIVELES DISPONIBLES PARA CREAR ROL
		if(isset($_POST['accion']) && $_POST['accion'] == 'obtener_niveles_disponibles'){
			header('Content-Type: application/json; charset=UTF-8');
			echo $usuariosController->obtener_niveles_disponibles_controlador();
		}

		// LISTAR ROLES
		if(isset($_POST['accion']) && $_POST['accion'] == 'listar_roles'){
			header('Content-Type: application/json; charset=UTF-8');
			echo $usuariosController->listar_roles_controlador();
		}

		// OBTENER ROL ESPECÍFICO
		if(isset($_POST['accion']) && $_POST['accion'] == 'obtener_rol'){
			header('Content-Type: application/json; charset=UTF-8');
			echo $usuariosController->obtener_rol_controlador();
		}

		// ACTUALIZAR ROL
		if(isset($_POST['accion']) && $_POST['accion'] == 'actualizar_rol'){
			header('Content-Type: application/json; charset=UTF-8');
			echo $usuariosController->actualizar_rol_controlador();
		}

		// ELIMINAR ROL
		if(isset($_POST['accion']) && $_POST['accion'] == 'eliminar_rol'){
			header('Content-Type: application/json; charset=UTF-8');
			echo $usuariosController->eliminar_rol_controlador();
		}

		// CAMBIAR ESTADO DE ROL
		if(isset($_POST['accion']) && $_POST['accion'] == 'cambiar_estado_rol'){
			header('Content-Type: application/json; charset=UTF-8');
			echo $usuariosController->cambiar_estado_rol_controlador();
		}

		//===========================================================================================================
		//FUNCIONES PARA GESTIÓN DE PERMISOS DE ROLES
		//===========================================================================================================

		// OBTENER PERMISOS DE UN ROL
		if(isset($_POST['accion']) && $_POST['accion'] == 'obtener_permisos_rol'){
			header('Content-Type: application/json; charset=UTF-8');
			echo $usuariosController->obtener_permisos_rol_controlador();
		}

		// ASIGNAR PERMISOS A ROL
		if(isset($_POST['accion']) && $_POST['accion'] == 'asignar_permisos_rol'){
			header('Content-Type: application/json; charset=UTF-8');
			echo $usuariosController->asignar_permisos_rol_controlador();
		}
		
		
		//regenero token para la función que se esté trabajando
		if(isset($_POST['accion']) && $_POST['accion'] == 'csrf_regenerar'){ 
			$key = $_POST['key'] ?? '';

			// Validar key permitidas (lista blanca)
			$keys_permitidas = [
				'formNuevoUsuario', 'editarUsuario', 'listUsuarios', 'cambioEstadoUsuario','cambioPasswordObligatorio', 'loginForm',
    			'formNuevoRol', 'editarRol', 'listRoles', 'cambioEstadoRol', 'gestionPermisosRol'
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
				'token' => $usuariosController->obtener_token_csrf($key),
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