<?php 
// Verificamos si es una petición AJAX para incluir el archivo correcto
if($peticionAjax){
    require_once "../core/SecureModel.php";
}else{
    require_once "./core/SecureModel.php";
}


class vistasModelo extends SecureModel{
    
    protected function obtener_vistas_modelo($vistas){
        $listaBlanca = "";
		$urls = "";
		$url = "";
        // LISTA BLANCA DE PÁGINAS PERMITIDAS (solo las que necesitas)
        $listaBlanca = [
			//app_inicio
			"404",				// Error 404
            "home",				// Página principal
			//App_dashboard
            "dashboard",		// dashboard
            //App_temas
			"configuracion",	// temas y colores
			"estructuras",		// revisar estructura de la tabla en base de datos
			//App-empresas
			"empresa",			// listado de empresas
			//App_usuarios
			"usuarios",			// listado de usuarios
			"roles",			// listado de roles
			//App_activos
			"activos",			// listado de activos
			//App_ccostos
			"ccostos",			// listado de centro de costos
			//App_ubicaciones
			"ubicaciones",		// listado de ubicaciones
			//App_contratacion
			"contratacion",		// listado de contratacion
			//App_pedidos
			"pedidos",			// listado de pedidos
			//App_proveedores
			"proveedores",		// listado de proveedores
            //App_inventarios
			"inventarios",		// listado de inventarios y facturacion
            //App_mesaayudas
			"mesaayudas",		// listado de mesaayudas
            
            "error"
        ];
        
		$urls=[
			//app_inicio
			"404"=>"App_inicio",					// Error 404
			"home"=>"App_inicio",					// Página principal
			//App_dashboard
			"dashboard"=>"App_dashboard",			// dashboard
			//App_temas
			"configuracion"=>"App_configuracion",	// temas y colores
			"estructuras"=>"App_configuracion",		// revisar estructura de la tabla en base de datos
			//App_empresas
			"empresa"=>"App_empresas",				// listado de empresas
			//App_usuarios
			"usuarios"=>"App_usuarios",				// listado de usuarios
			"roles"=>"App_usuarios",				// listado de roles
			//App_activos
			"activos"=>"App_activos",				// listado de activos
			//App_ccostos
			"ccostos"=>"App_ccostos",				// listado de centro de costos
			//App_ubicaciones
			"ubicaciones"=>"App_ubicaciones",		// listado de ubicaciones 
			//App_contratacion
			"contratacion"=>"App_contratacion",		// listado de contratacion
			//App_pedidos
			"pedidos"=>"App_pedidos",				// listado de pedidos 
			//App_proveedores
			"proveedores"=>"App_proveedores",		// listado de proveedores 
            //App_inventarios
			"inventarios"=>"App_inventarios",		// listado de inventarios y facturacion
            //App_mesaayudas
			"mesaayudas"=>"App_mesaayudas",			// listado de mesaayudas
            
            "error"=>"contenidos"
			
		];
		
        // VALIDACIÓN Y SANITIZACIÓN
        $vistas = $this->limpiar_entrada($vistas);
        
        // LÓGICA SIMPLIFICADA DE ROUTING
        if(empty($vistas) || $vistas == "home"){
            // Si no hay vista o es index, mostrar página principal
            $contenido = "./views/App_inicio/home-view.php";
            
        } elseif($vistas == "404"){
			
            $contenido = "./views/App_inicio/404-view.php";
            
        } elseif(in_array($vistas, $listaBlanca)){
			//defino la carpeta donde esta el template
			$url = $urls[$vistas];
            // Verificar si el archivo existe
            $rutaArchivo = "./views/".$url."/".$vistas."-view.php";
            
            if(is_file($rutaArchivo)){
                $contenido = $rutaArchivo;
            } else {
                $this->registrar_intento_acceso($vistas, "archivo_no_existe");
                $contenido = "./views/contenidos/404-view.php";
            }
            
        } else {
            // Página no autorizada
            $this->registrar_intento_acceso($vistas, "pagina_no_autorizada");
            $contenido = "./views/App_inicio/404-view.php";
        }
        
            // Log para auditoría
            $this->guardar_log('visita de pagina', [
                'datos_antes'=>['sin datos'],
                'datos_despues'=> [
                    'pagina_visitada'=>$vistas
                ],
            ], 'bajo', 'exito', $url);
        
        return $contenido;
    }
    
    // Limpiar y validar entrada
    private function limpiar_entrada($entrada){
        if(empty($entrada)) return "home";
        
        // Remover caracteres peligrosos
        $entrada = preg_replace('/[^a-zA-Z0-9_-]/', '', $entrada);
        
        // Limitar longitud
        $entrada = substr($entrada, 0, 30);
        
        // Convertir a minúsculas
        $entrada = strtolower($entrada);
        
        return $entrada;
    }
    
    // Registrar intentos sospechosos
    private function registrar_intento_acceso($pagina, $motivo){
        $ip = $_SERVER['REMOTE_ADDR'] ?? 'desconocida';
        $fecha = date('Y-m-d H:i:s');
        
        $mensaje = "SEGURIDAD: Página='$pagina' Motivo='$motivo' IP='$ip' Fecha='$fecha'";
        error_log($mensaje);
    }
}
?>