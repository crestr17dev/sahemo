
<?php
global $peticionAjax;
$peticionAjax=true;
require_once "../core/configGeneral.php";
// VERIFICAR SI LA SESIÓN YA ESTÁ ACTIVA
if (session_status() === PHP_SESSION_NONE) {
    session_start(['name' => SESION]);
}

if (isset($_SESSION['sesionactiva']) && $_SESSION['sesionactiva'] == true){
	if(isset($_POST['empresa-nit']) || isset($_POST['accion']) ||  isset($_POST['csrf_token_list'])  || isset($_POST['sucursal-nit']) || isset($_POST['sede-nit'])){
		
		require_once "../controllers/App_empresasController.php";
		$empresasController = new empresasController();
		
		//========================================================================================
		//FUNCIONES PARA ADMINISTRACIÓN DE EMPRESAS
		//========================================================================================
		
		// REGISTRO DE NUEVA EMPRESA
		if(isset($_POST['empresa-nit'])){
			header('Content-Type: application/json; charset=UTF-8');
			echo $empresasController->registrar_empresa_controlador();
		}

		// LISTAR EMPRESAS
		if(isset($_POST['csrf_token_list'])){
			header('Content-Type: application/json; charset=UTF-8');
			echo $empresasController->listar_empresa_controlador();
		}
		
		//regenero token para la funcion que se este trabajando
		if($_POST['accion'] == 'csrf_regenerar'){ 
			$key = $_POST['key'] ?? '';

			// Validar key permitidas (lista blanca)
			$keys_permitidas = [
				'formNuevaEmpresa', 'editarEmpresa', 'formNuevaSucursal',
				'editarSucursal', 'formNuevaSede','editarSede'
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
				'token' => $empresasController->obtener_token_csrf($key),
				'status' => 'success'
			], JSON_UNESCAPED_UNICODE);
			exit;
		}
		
		// ELIMINACIÓN DE EMPRESA
		if(isset($_POST['accion']) && $_POST['accion'] == 'eliminar_empresa'){
			header('Content-Type: application/json; charset=UTF-8');
			echo $empresasController->eliminar_empresa_controlador();
		}
		
		// OBTENER DATOS DE UNA EMPRESA
		if(isset($_POST['accion']) && $_POST['accion'] == 'obtener_empresa'){
			header('Content-Type: application/json; charset=UTF-8');
			echo $empresasController->obtener_empresa_controlador();
		}
		
		// ACTUALIZAR EMPRESA
		if(isset($_POST['accion']) && $_POST['accion'] == 'actualizar_empresa'){
			header('Content-Type: application/json; charset=UTF-8');
			echo $empresasController->actualizar_empresa_controlador();
		}

		// CAMBIAR ESTADO DE EMPRESA
		if(isset($_POST['accion']) && $_POST['accion'] == 'cambiar_estado_empresa'){
			header('Content-Type: application/json; charset=UTF-8');
			echo $empresasController->cambiar_estado_empresa_controlador();
		}
		
		// EXPORTAR EMPRESAS A EXCEL
		if(isset($_POST['accion']) && $_POST['accion'] == 'exportar_empresas_excel'){
			// No header JSON aquí - será un archivo
			echo $empresasController->exportar_empresas_excel_controlador();
		}
		
		//========================================================================================
		//FUNCIONES PARA ADMINISTRACIÓN DE SUCURSALES
		//========================================================================================

		// LISTAR SUCURSALES DE UNA EMPRESA
		if(isset($_POST['accion']) && $_POST['accion'] == 'listar_sucursales_empresa'){
			header('Content-Type: application/json; charset=UTF-8');
			echo $empresasController->listar_sucursales_empresa_controlador();
		}

		// REGISTRAR NUEVA SUCURSAL
		if(isset($_POST['sucursal-nit'])){
			header('Content-Type: application/json; charset=UTF-8');
			echo $empresasController->registrar_sucursal_controlador();
		}

		// OBTENER DATOS DE UNA SUCURSAL
		if(isset($_POST['accion']) && $_POST['accion'] == 'obtener_sucursal'){
			header('Content-Type: application/json; charset=UTF-8');
			echo $empresasController->obtener_sucursal_controlador();
		}

		// ACTUALIZAR SUCURSAL
		if(isset($_POST['accion']) && $_POST['accion'] == 'actualizar_sucursal'){
			header('Content-Type: application/json; charset=UTF-8');
			echo $empresasController->actualizar_sucursal_controlador();
		}

		// ELIMINAR SUCURSAL
		if(isset($_POST['accion']) && $_POST['accion'] == 'eliminar_sucursal'){
			header('Content-Type: application/json; charset=UTF-8');
			echo $empresasController->eliminar_sucursal_controlador();
		}

		// CAMBIAR ESTADO DE SUCURSAL
		if(isset($_POST['accion']) && $_POST['accion'] == 'cambiar_estado_sucursal'){
			header('Content-Type: application/json; charset=UTF-8');
			echo $empresasController->cambiar_estado_sucursal_controlador();
		}
		
		// CONTAR SUCURSALES DE UNA EMPRESA
		if(isset($_POST['accion']) && $_POST['accion'] == 'contar_sucursales_empresa'){
			header('Content-Type: application/json; charset=UTF-8');
			echo $empresasController->contar_sucursales_empresa_controlador();
		}
		
		//========================================================================================
		//FUNCIONES PARA ADMINISTRACIÓN DE SEDES
		//========================================================================================

		// REGISTRAR NUEVA SEDE
		if(isset($_POST['sede-nit'])){
			header('Content-Type: application/json; charset=UTF-8');
			echo $empresasController->registrar_sede_controlador();
		}
		
		// LISTAR SEDES DE UNA SUCURSAL
		if(isset($_POST['accion']) && $_POST['accion'] == 'listar_sedes_sucursal'){
			header('Content-Type: application/json; charset=UTF-8');
			echo $empresasController->listar_sedes_sucursal_controlador();
		}
		
		// OBTENER DATOS DE UNA SEDE
		if(isset($_POST['accion']) && $_POST['accion'] == 'obtener_sede'){
			header('Content-Type: application/json; charset=UTF-8');
			echo $empresasController->obtener_sede_controlador();
		}

		// ACTUALIZAR SEDE
		if(isset($_POST['accion']) && $_POST['accion'] == 'actualizar_sede'){
			header('Content-Type: application/json; charset=UTF-8');
			echo $empresasController->actualizar_sede_controlador();
		}
		
		// ELIMINAR SEDE
		if(isset($_POST['accion']) && $_POST['accion'] == 'eliminar_sede'){
			header('Content-Type: application/json; charset=UTF-8');
			echo $empresasController->eliminar_sede_controlador();
		}

		// CAMBIAR ESTADO DE SEDE
		if(isset($_POST['accion']) && $_POST['accion'] == 'cambiar_estado_sede'){
			header('Content-Type: application/json; charset=UTF-8');
			echo $empresasController->cambiar_estado_sede_controlador();
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