<?php
 	//===========================================================================================================
    // CONTROLADOR DE ROLES
    // Este archivo maneja toda la lógica del módulo de roles
	// Es el intermediario entre las vistas y el modelo
    //===========================================================================================================

	//===================== Verificamos si es una petición AJAX para incluir el archivo correcto ===============
if($peticionAjax){
    require_once "../models/App_rolesModel.php";
}else{
    require_once "./models/App_rolesModel.php";
}

class rolesController extends rolesModel {
    
	//===========================================================================================================
    // OBTENER TOKEN CSRF PARA EL FORMULARIO
    // Función para generar el token que va en el formulario de registro y/o actualización
    //===========================================================================================================

    public function obtener_token_csrf($key){
        return $this->generar_token_csrf($key);
    }
		
	
}

?>