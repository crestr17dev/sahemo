<?php
 	//===========================================================================================================
    // CONTROLADOR DE EMPRESAS
    // Este archivo maneja toda la lógica del módulo de empresas
	// Es el intermediario entre las vistas y el modelo
    //===========================================================================================================

	//===================== Verificamos si es una petición AJAX para incluir el archivo correcto ===============
if($peticionAjax){
    require_once "../models/App_empresasModel.php";
}else{
    require_once "./models/App_empresasModel.php";
}

class empresasController extends empresasModel {
    


    
	//===========================================================================================================
    // OBTENER TOKEN CSRF PARA EL FORMULARIO
    // Función para generar el token que va en el formuladi de registro y/o actualizacion
    //===========================================================================================================

    public function obtener_token_csrf($key){
        return $this->generar_token_csrf($key);
    }
	
    
    //===========================================================================================================
    // REGISTRAR NUEVA EMPRESA
    // Función con todos los parametros para registrar una nueva empresa de manera segura en BD
    //===========================================================================================================

    public function registrar_empresa_controlador(){
		
	/*-------------------//-------- PASO 1 LIMPIEZA, VALIDACIONES Y SEGURIDAD --------//-----------------------*/	
    /************ Marcar inicio del tiempo para  funcion normalizar el timepo de respuestas ********************/
        $this->tiempo_inicio = microtime(true);
		
    /************ VALIDACION 1: valida si el metodo es POST de lo contrario bloquea proceso ********************/
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
			/*-*-*-*-*-* registro logs de seguimiento y auditoria *-*-*-*-*-*/
            $this->guardar_log(
				'metodo_http_invalido', [
                	'datos_antes' => [
						'metodo_recibido' => $_SERVER['REQUEST_METHOD'],
						'esperado' => 'POST'
					],
                	'datos_despues' => [
						'accion' => 'rechazado'
					]
            	], 
				'alto', 'bloqueado', 'App_empresa');

            http_response_code(405); // Method Not Allowed
            return json_encode(["error" => "Method not allowed"]);
        }
        
    /************ VALIDACION 2: Detecto hay automatizado de llenado (ataques con bot) **************************/
        $numero_campos = count($_POST); // contar los campos recibidos
        $tiempo_por_campo = 2; // segundos mínimos por campo
        $umbral_minimo = ($numero_campos - 1) * $tiempo_por_campo;
        /*-*-*-*-*-* llamo funcion para detectar bots atacantes *-*-*-*-*-*/
        $analisis_bot = $this->es_bot_sospechoso($umbral_minimo,$numero_campos);
        
        if ($analisis_bot['es_bot']) {
            /*-*-*-*-*-* Normalizamos el tiempo de respuesta *-*-*-*-*-*/           
            $this->normalizar_tiempo_respuesta();
			/*-*-*-*-*-* Termino función para registro de empresa *-*-*-*-*-*/ 
            return  json_encode([
				"Alerta" => "simple",
				"Titulo" => "Actividad Inapropiada",
				"Texto" => "Actividad sospechosa detectada", 
				"Tipo" => "warning"
			], JSON_UNESCAPED_UNICODE);
        }
		
	/************ VALIDACION 3: Verificar si trae el token que se creo previamente para formulado empresa ******/
        if(!isset($_POST['csrf_token']) || !$this->validar_csrf($_POST['csrf_token'],'formNuevaEmpresa')){
			/*-*-*-*-*-* registro logs de seguimiento y auditoria *-*-*-*-*-*/
			$this->guardar_log(
				'csrf_token_invalido', [
					'datos_antes' => [
						'Token_creado'=>'Diferente al recibido'
						],
					'datos_despues' => [
						'formulario' => 'formNuevaEmpresa',
						'token_recibido_hash' => isset($_POST['csrf_token']) ? hash('sha256', $_POST['csrf_token']) : 'no_enviado',
						'session_id' => session_id(),
						'CodigoUsuario' => $_SESSION['CodigoUsuario'] ?? 'no_definido',
						'UsuarioId' => $_SESSION['UsuarioId'] ?? 'no_definido'
					]
				],
				'alto', 'bloqueado', 'App_empresa');
			
            /*-*-*-*-*-* Normalizamos el tiempo de respuesta *-*-*-*-*-*/           
            $this->normalizar_tiempo_respuesta();
			/*-*-*-*-*-* Termino función para registro de empresa *-*-*-*-*-*/
			return  json_encode([
				"Alerta" => "simple",
				"Titulo" => "Token invÃ¡lido",
				"Texto" => "Token de seguridad inválido. Recarga la página e intenta nuevamente", 
				"Tipo" => "warning"
			], JSON_UNESCAPED_UNICODE);
			
        }

	/************ VALIDACION 4: verificar permisos del usuario *************************************************/
        if(!$this->verificar_permisos('empresa_crear')){
			/*-*-*-*-*-* registro logs de seguimiento y auditoria *-*-*-*-*-*/
			$this->guardar_log(
				'Usuario sin permisos para crear empresa', [
					'datos_antes' => [
						'usuario'=>'sin permisos'
					],
					'datos_despues' => [
						'CodigoUsuario' => $_SESSION['CodigoUsuario'],
						'UsuarioId' => $_SESSION['UsuarioId']
					],
				],
				'alto', 'bloqueado', 'App_empresa');
			/*-*-*-*-*-* Normalizamos el tiempo de respuesta *-*-*-*-*-*/           
            $this->normalizar_tiempo_respuesta();
			/*-*-*-*-*-* Termino función para registro de empresa *-*-*-*-*-*/
            
			return  json_encode([
				"Alerta" => "simple",
                "Titulo" => "Sin permisos",
                "Texto" => "No tienes permisos para registrar empresas", 
				"Tipo" => "warning"
			], JSON_UNESCAPED_UNICODE);
        }
        
	/************ VALIDACION 5: Limite de intentos para el envio de informacion, max 3 bloqueo 5 minutos *******/
        if(!$this->verificar_intentos('registro_empresa', 3, 300)){
            /*-*-*-*-*-* registro logs de seguimiento y auditoria *-*-*-*-*-*/
			$this->guardar_log(
				'registro_empresa', [
					'datos_antes' => [
						'intentos'=>'Usuario inicia intento de registro'
					],
					'datos_despues' => [
						'intentos'=>'Usuario alcanzo el maximo de intentos permitos 3',
						'CodigoUsuario' => $_SESSION['CodigoUsuario'],
						'UsuarioId' => $_SESSION['UsuarioId']
					],
				],
				'alto', 'bloqueado', 'App_empresa');
            /*-*-*-*-*-* Normalizamos el tiempo de respuesta *-*-*-*-*-*/           
            $this->normalizar_tiempo_respuesta();
			/*-*-*-*-*-* Termino función para registro de empresa *-*-*-*-*-*/
            
			return  json_encode([
				"Alerta" => "simple",
                "Titulo" => "Demasiados intentos",
                "Texto" => "Has superado el límite de intentos. Espera 5 minutos",
				"Tipo" => "warning"
			], JSON_UNESCAPED_UNICODE);
        }
        
	/************ VALIDACION 6: limpiar los datos recibidos ****************************************************/ 
        $resultados_limpieza = [
            'EmpresaNit' => $this->limpiar_datos($_POST['empresa-nit'], 'numero', 'empresa-nit'),
            'EmpresaNombre' => $this->limpiar_datos($_POST['empresa-nombre'], 'texto', 'empresa-nombre'),
            'EmpresaDireccion' => $this->limpiar_datos($_POST['empresa-direccion'], 'texto','empresa-direccion'),
            'EmpresaTelefono' => $this->limpiar_datos($_POST['empresa-telefono'], 'texto','empresa-telefono'),
            'EmpresaEmail' => $this->limpiar_datos($_POST['empresa-email'], 'email', 'empresa-email'),
            'EmpresaIdRepresentante' => $this->limpiar_datos($_POST['empresa-id-representante'], 'numero', 'empresa-id-representante'),
            'EmpresaNomRepresentante' => $this->limpiar_datos($_POST['empresa-nom-representante'], 'texto', 'empresa-nom-representante')
        ];
        
	/************ VALIDACION 7: Verifico si algun campo tenía ataques, sale de la limpieza realizada ***********/
        $hay_ataques = false;
        $ataques_por_campo = [];
		/*-*-*-*-*-* reviso cada campo *-*-*-*-*-*/
        foreach ($resultados_limpieza as $campo => $resultado) {
            if (!$resultado['es_seguro']) {
                $hay_ataques = true;
                $ataques_por_campo[$campo] = $resultado['ataques_detectados'];
            }  
        }
        /*-*-*-*-*-* si hay ataques RECHAZO completamente *-*-*-*-*-*/
        if ($hay_ataques) {
            /*-*-*-*-*-*calculo el nivel de riesgo *-*-*-*-*-*/
            $nivel_riesgo_maximo = 'bajo';
            foreach ($resultados_limpieza as $resultado) {
                if ($resultado['nivel_riesgo'] === 'alto') {
                    $nivel_riesgo_maximo = 'alto';
                    break;
                } elseif ($resultado['nivel_riesgo'] === 'medio' && $nivel_riesgo_maximo !== 'alto') {
                    $nivel_riesgo_maximo = 'medio';
                }
            }
			/*-*-*-*-*-* registro logs de seguimiento y auditoria *-*-*-*-*-*/
            $this->guardar_log(
				'formulario_rechazado_por_ataques', [
                	'datos_antes' => [
                    	'campos_con_ataques' => array_keys($ataques_por_campo),
                    	'total_campos_afectados' => count($ataques_por_campo),
                    	'resumen_ataques' => $ataques_por_campo
                	],
                	'datos_despues' => [
                    	'accion_tomada' => 'formulario_rechazado_completamente'
                	],
            	],
				$nivel_riesgo_maximo, 'rechazado', 'seguridad'); 

            /*-*-*-*-*-* Normalizamos el tiempo de respuesta *-*-*-*-*-*/           
            $this->normalizar_tiempo_respuesta();
			/*-*-*-*-*-* Termino función para registro de empresa *-*-*-*-*-*/
            return json_encode([
                "Titulo" => "Datos no válidos",
                "Texto" => "Los datos enviados contienen información no permitida.",
                "Tipo" => "warning"
            ], JSON_UNESCAPED_UNICODE);
        }

	/************ VALIDACION 8: Si no hay ataques, extraer datos limpios y continuar ***************************/
        $datos_empresa = [];
        foreach ($resultados_limpieza as $campo => $resultado) {
            $datos_empresa[$campo] = $resultado['dato_limpio'];
        }
        
	/************ VALIDACION 9: verifico los campos obligatorios desde la BD  **********************************/
        /*-*-*-*-*-* excluyo los campos que son automaticos en BD *-*-*-*-*-*/
		$campos_excluir = ['EmpresaId', 'EmpresaCodigo', 'EmpresaFechaRegistro', 'EmpresaFechaActualizacion', 'EmpresaEstado'];

		/*-*-*-*-*-* creo reglas personalizadas que complementas las de base de datos *-*-*-*-*-*/
		$reglas_personalizadas = [
			'EmpresaNit' => [
				'min_caracteres' => 8,  // Mínimo 8 dígitos para NITs
			],
			'EmpresaTelefono' => [
				'min_caracteres' => 7,  // Mínimo 7 dígitos para teléfonos
			],
			'EmpresaNomRepresentante' => [
				'solo_letras' => true   // Solo letras para nombres
			]
		];

		/*-*-*-*-*-* valida todos los campos unificando las reglas *-*-*-*-*-*/
		$errores = $this->validar_completo(
			$datos_empresa, 
			'App_empresa_empresa', 
			$campos_excluir, 
			$reglas_personalizadas
		);

		/*-*-*-*-*-* si hay errores los muestra *-*-*-*-*-*/
		if(!empty($errores)){
			$mensaje_error = "Errores encontrados:\n";
			foreach($errores as $campo => $errores_campo){
				$mensaje_error .= "- " . implode(", ", $errores_campo) . "\n";
			}
            
            /*-*-*-*-*-* Normalizamos el tiempo de respuesta *-*-*-*-*-*/           
            $this->normalizar_tiempo_respuesta();
			/*-*-*-*-*-* Termino función para registro de empresa *-*-*-*-*-*/
			return  json_encode([
				"Alerta" => "simple",
				"Titulo" => "Datos incorrectos",
				"Texto" => $mensaje_error,
				"Tipo" => "warning"
			], JSON_UNESCAPED_UNICODE);
		}

		
    /************ VALIDACION 10: Verifico que no exista el nit en la  BD  **************************************/
        if($this->verificar_nit_duplicado($datos_empresa['EmpresaNit'])){
            /*-*-*-*-*-* Normalizamos el tiempo de respuesta *-*-*-*-*-*/           
            $this->normalizar_tiempo_respuesta();
			/*-*-*-*-*-* Termino función para registro de empresa *-*-*-*-*-*/
			return  json_encode([
				"Alerta" => "simple",
				"Titulo" => "NIT duplicado",
				"Texto" => "Ya existe una empresa registrada con este NIT", 
				"Tipo" => "warning"
			], JSON_UNESCAPED_UNICODE);
        }
        
	/************ VALIDACION 11: Verifico que no exista el email en la  BD *************************************/
        if($this->verificar_email_duplicado($datos_empresa['EmpresaEmail'])){
            /*-*-*-*-*-* Normalizamos el tiempo de respuesta *-*-*-*-*-*/           
            $this->normalizar_tiempo_respuesta();
			/*-*-*-*-*-* Termino función para registro de empresa *-*-*-*-*-*/
           	return  json_encode([
				"Alerta" => "simple",
                "Titulo" => "Email duplicado",
                "Texto" => "Ya existe una empresa registrada con este email",
				"Tipo" => "warning"
			], JSON_UNESCAPED_UNICODE);
        }
        
		
    /************ COMPLEMENTO 1: genero el codigo para la creacion de empresa **********************************/
		/*-*-*-*-*-* Consulto cuantas empresas tengo creadas para darle codigo a nueva empresa *-*-*-*-*-*/
		$sql = "SELECT COUNT(EmpresaId) FROM App_empresa_empresa";
		$totalempresas = $this->ejecutar_consulta_segura($sql, []) ;
		$totalempresas = ($totalempresas->fetchColumn()) + 1 ;
		
		if($totalempresas > 0){
			$longitud = 10 - strlen($totalempresas);
			
		}else{
			$longitud = 10;
			$totalempresas = "_";
		}
		
        $codigo_empresa = $this->generar_codigo_aleatorio('EM', $longitud,'');
        $codigo_empresa = $codigo_empresa.$totalempresas;
		
		
		
    /*-----------------------//-------- PASO 2: REGISTRO DE EMPRESA EN BD --------//---------------------------*/		


		/*-*-*-*-*-* Consolido datos totalmente limpios *-*-*-*-*-*/
        $datos_finales = [
            'codigo' => $codigo_empresa,
            'nit' => $datos_empresa['EmpresaNit'],
            'nombre' => $datos_empresa['EmpresaNombre'],
            'direccion' => $datos_empresa['EmpresaDireccion'],
            'telefono' => $datos_empresa['EmpresaTelefono'],
            'email' => $datos_empresa['EmpresaEmail'],
            'id_representante' => $datos_empresa['EmpresaIdRepresentante'],
            'nom_representante' => $datos_empresa['EmpresaNomRepresentante'],
			'EmpresaFechaRegistro'=>  date("Y-m-d H:i:s"),
			'EmpresaFechaActualizacion'=>  date("Y-m-d H:i:s")
        ];
        
        /*-*-*-*-*-* envio registro de empresa *-*-*-*-*-*/
        $resultado = $this->registrar_empresa_modelo($datos_finales);
		/*-*-*-*-*-* si registro exitoso *-*-*-*-*-*/
        if($resultado){
            /*-*-*-*-*-* registro logs de seguimiento y auditoria *-*-*-*-*-*/
			$this->guardar_log(
				'empresa_registrada',[
					'datos_antes'=>[
						'antes'=>'sin informacion'
							   ],
					'datos_despues'=> [
						'resultado'=> 'empresa creada',
						'codigo_empresa' => $codigo_empresa,
						'nit' => $datos_empresa['EmpresaNit'],
						'nombre' => $datos_empresa['EmpresaNombre']
					],
				], 
				'medio', 'exito', 'App_empresa');
        	/*-*-*-*-*-* elimino el token que le habia creado a l formulario *-*-*-*-*-*/
        	$this->eliminar_token_csrf('formNuevaEmpresa');
            
            /*-*-*-*-*-* Normalizamos el tiempo de respuesta *-*-*-*-*-*/           
            $this->normalizar_tiempo_respuesta();
			/*-*-*-*-*-* Termino función para registro de empresa *-*-*-*-*-*/
			return  json_encode([
				"Alerta" => "recargar",
                "Titulo" => "Empresa registrada",
                "Texto" => "La empresa se ha registrado exitosamente con el código: ".$codigo_empresa,
				"Tipo" => "success"
			], JSON_UNESCAPED_UNICODE);
			/*return  json_encode([
				"Alerta" => "simple",
				"Titulo" => "NIT duplicado",
				"Texto" => "Ya existe una empresa registrada con este NIT: ".$codigo_empresa, 
				"Tipo" => "warning"
			], JSON_UNESCAPED_UNICODE);*/
		
        } else {
			/*-*-*-*-*-* si registro exitoso *-*-*-*-*-*/
			/*-*-*-*-*-* registro logs de seguimiento y auditoria *-*-*-*-*-*/
			$this->guardar_log(
				'empresa_registrada_fallo', [
					'datos_antes'=>[
						'antes'=>'sin informacion'
							   ],
					'datos_despues'=> [
						'resultado'=> 'empresa no creada',
						'codigo_empresa' => $codigo_empresa,
						'nit' => $datos_empresa['empresanit'],
						'nombre' => $datos_empresa['empresanombre']
					],
				], 
				'medio', 'exito', 'App_empresa');
            
            /*-*-*-*-*-* Normalizamos el tiempo de respuesta *-*-*-*-*-*/           
            $this->normalizar_tiempo_respuesta();
			/*-*-*-*-*-* Termino función para registro de empresa *-*-*-*-*-*/
			return  json_encode([
				"Alerta" => "simple",
                "Titulo" => "Error",
                "Texto" => "No se pudo registrar la empresa. Intenta nuevamente",
				"Tipo" => "warning"
			], JSON_UNESCAPED_UNICODE);
        }
    }
	
	
	public function listar_empresa_controlador(){
		/*-------------------//-------- PASO 1 LIMPIEZA, VALIDACIONES Y SEGURIDAD --------//-----------------------*/	
    /************ Marcar inicio del tiempo para  funcion normalizar el timepo de respuestas ********************/
        $this->tiempo_inicio = microtime(true);
		
    /************ VALIDACION 1: valida si el metodo es POST de lo contrario bloquea proceso ********************/
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
			/*-*-*-*-*-* registro logs de seguimiento y auditoria *-*-*-*-*-*/
            $this->guardar_log(
				'metodo_http_invalido', [
                	'datos_antes' => [
						'metodo_recibido' => $_SERVER['REQUEST_METHOD'],
						'esperado' => 'POST'
					],
                	'datos_despues' => [
						'accion' => 'rechazado'
					]
            	], 
				'alto', 'bloqueado', 'App_empresa');

            http_response_code(405); // Method Not Allowed
            return json_encode(["error" => "Method not allowed"]);
        }

		
	/************ VALIDACION 3: Verificar si trae el token que se creo previamente para formulado empresa ******/
        if(!isset($_POST['csrf_token_list']) || !$this->validar_csrf($_POST['csrf_token_list'],'listEmpresas')){
			/*-*-*-*-*-* registro logs de seguimiento y auditoria *-*-*-*-*-*/
			$this->guardar_log(
				'csrf_token_invalido', [
					'datos_antes' => [
						'Token_creado'=>'Diferente al recibido'
						],
					'datos_despues' => [
						'formulario' => 'listEmpresas',
						'token_recibido_hash' => isset($_POST['csrf_token_list']) ? hash('sha256', $_POST['csrf_token_list']) : 'no_enviado',
						'session_id' => session_id(),
						'CodigoUsuario' => $_SESSION['CodigoUsuario'] ?? 'no_definido',
						'UsuarioId' => $_SESSION['UsuarioId'] ?? 'no_definido'
					]
				],
				'alto', 'bloqueado', 'App_empresa');
			
            /*-*-*-*-*-* Normalizamos el tiempo de respuesta *-*-*-*-*-*/           
            $this->normalizar_tiempo_respuesta();
			/*-*-*-*-*-* Termino función para registro de empresa *-*-*-*-*-*/
			return  json_encode([
				"Alerta" => "simple",
				"Titulo" => "Token invÃ¡lido",
				"Texto" => "Token de seguridad inválido. Recarga la página e intenta nuevamente", 
				"Tipo" => "warning"
			], JSON_UNESCAPED_UNICODE);
			
        }

	/************ VALIDACION 4: verificar permisos del usuario *************************************************/
        if(!$this->verificar_permisos('listar_empresas')){
			/*-*-*-*-*-* registro logs de seguimiento y auditoria *-*-*-*-*-*/
			$this->guardar_log(
				'Usuario sin permisos para enlistar empresas', [
					'datos_antes' => [
						'usuario'=>'sin permisos'
					],
					'datos_despues' => [
						'CodigoUsuario' => $_SESSION['CodigoUsuario'],
						'UsuarioId' => $_SESSION['UsuarioId']
					],
				],
				'alto', 'bloqueado', 'App_empresa');
			/*-*-*-*-*-* Normalizamos el tiempo de respuesta *-*-*-*-*-*/           
            $this->normalizar_tiempo_respuesta();
			/*-*-*-*-*-* Termino función para registro de empresa *-*-*-*-*-*/
            
			return  json_encode([
				"Alerta" => "simple",
                "Titulo" => "Sin permisos",
                "Texto" => "No tienes permisos para registrar empresas", 
				"Tipo" => "warning"
			], JSON_UNESCAPED_UNICODE);
        }
		
	/************ VALIDACION 5: limpiar los datos recibidos ****************************************************/ 
        $resultados_limpieza = [
            'shareempresa' => $this->limpiar_datos($_POST['shareempresa'], 'texto', 'shareempresa'),
            'estadoempresa' => $this->limpiar_datos($_POST['estadoempresa'], 'texto', 'estadoempresa'),
			'pagina' => $this->limpiar_datos($_POST['pagina'] ?? '1', 'numero', 'pagina'),
			'vista_tipo' => $this->limpiar_datos($_POST['vista_tipo'] ?? 'list', 'texto', 'vista_tipo')
			
        ];
        
	/************ VALIDACION 6: Verifico si algun campo tenía ataques, sale de la limpieza realizada ***********/
        $hay_ataques = false;
        $ataques_por_campo = [];
		/*-*-*-*-*-* reviso cada campo *-*-*-*-*-*/
        foreach ($resultados_limpieza as $campo => $resultado) {
            if (!$resultado['es_seguro']) {
                $hay_ataques = true;
                $ataques_por_campo[$campo] = $resultado['ataques_detectados'];
            }  
        }
        /*-*-*-*-*-* si hay ataques RECHAZO completamente *-*-*-*-*-*/
        if ($hay_ataques) {
            /*-*-*-*-*-*calculo el nivel de riesgo *-*-*-*-*-*/
            $nivel_riesgo_maximo = 'bajo';
            foreach ($resultados_limpieza as $resultado) {
                if ($resultado['nivel_riesgo'] === 'alto') {
                    $nivel_riesgo_maximo = 'alto';
                    break;
                } elseif ($resultado['nivel_riesgo'] === 'medio' && $nivel_riesgo_maximo !== 'alto') {
                    $nivel_riesgo_maximo = 'medio';
                }
            }
			/*-*-*-*-*-* registro logs de seguimiento y auditoria *-*-*-*-*-*/
            $this->guardar_log(
				'formulario_rechazado_por_ataques', [
                	'datos_antes' => [
                    	'campos_con_ataques' => array_keys($ataques_por_campo),
                    	'total_campos_afectados' => count($ataques_por_campo),
                    	'resumen_ataques' => $ataques_por_campo
                	],
                	'datos_despues' => [
                    	'accion_tomada' => 'formulario_rechazado_completamente'
                	],
            	],
				$nivel_riesgo_maximo, 'rechazado', 'seguridad'); 

            /*-*-*-*-*-* Normalizamos el tiempo de respuesta *-*-*-*-*-*/           
            $this->normalizar_tiempo_respuesta();
			/*-*-*-*-*-* Termino función para registro de empresa *-*-*-*-*-*/
            return json_encode([
                "Titulo" => "Datos no válidos",
                "Texto" => "Los datos enviados contienen información no permitida.",
                "Tipo" => "warning"
            ], JSON_UNESCAPED_UNICODE);
        }

	/************ VALIDACION 7: Si no hay ataques, extraer datos limpios y continuar ***************************/
        $datos_empresa = [];
        foreach ($resultados_limpieza as $campo => $resultado) {
            $datos_empresa[$campo] = $resultado['dato_limpio'];
        }	
		
		/*-------------------//-------- PASO 2 PROCESAMIENTO DE DATOS --------//-----------------------*/
        
        // Validar página
        $pagina = max(1, (int)$datos_empresa['pagina']);
        $registros_por_pagina = 5; // Puedes hacer esto configurable
        
        try {
            // Llamar al modelo para obtener las empresas
            $resultado = $this->listar_empresas_modelo($datos_empresa, $pagina, $registros_por_pagina);
            
            // Obtener estadísticas
            $estadisticas = $this->obtener_estadisticas_empresas_modelo();
            			
			
			$vista_tipo = $datos_empresa['vista_tipo'];

			if ($vista_tipo === 'grid') {
				$html_tabla = $this->generar_html_cards($resultado['empresas'], $resultado['paginacion']);
			} else {
				$html_tabla = $this->generar_html_tabla($resultado['empresas'], $resultado['paginacion']);
			}
            
            // Generar HTML de las tarjetas de estadísticas
            $html_estadisticas = $this->generar_html_estadisticas($estadisticas);
            
            /*-*-*-*-*-* registro logs de seguimiento y auditoria *-*-*-*-*-*/
            $this->guardar_log(
                'empresas_listadas_exitosamente', [
                    'datos_antes' => [
                        'filtros_aplicados' => $datos_empresa,
                        'pagina_solicitada' => $pagina
                    ],
                    'datos_despues' => [
                        'empresas_encontradas' => count($resultado['empresas']),
                        'total_registros' => $resultado['paginacion']['total_registros']
                    ]
                ],
                'bajo', 'exito', 'App_empresa'
            );
            
            /*-*-*-*-*-* Normalizamos el tiempo de respuesta *-*-*-*-*-*/
            $this->normalizar_tiempo_respuesta();
            
            /*-*-*-*-*-* Respuesta exitosa *-*-*-*-*-*/
            return json_encode([
                "status" => "success",
                "html_tabla" => $html_tabla,
                "html_estadisticas" => $html_estadisticas,
                "paginacion" => $resultado['paginacion'],
                "total_empresas" => $resultado['paginacion']['total_registros']
            ], JSON_UNESCAPED_UNICODE);
            
        } catch (Exception $e) {
            /*-*-*-*-*-* registro logs de seguimiento y auditoria *-*-*-*-*-*/
            $this->guardar_log(
                'error_listar_empresas', [
                    'datos_antes' => [
                        'filtros' => $datos_empresa,
                        'pagina' => $pagina
                    ],
                    'datos_despues' => [
                        'error' => $e->getMessage()
                    ]
                ],
                'alto', 'error', 'App_empresa'
            );
            
            /*-*-*-*-*-* Normalizamos el tiempo de respuesta *-*-*-*-*-*/
            $this->normalizar_tiempo_respuesta();
            
            return json_encode([
                "Alerta" => "simple",
                "Titulo" => "Error interno",
                "Texto" => "Ocurrió un error al cargar las empresas. Intenta nuevamente.",
                "Tipo" => "error"
            ], JSON_UNESCAPED_UNICODE);
        }
		
		
		
	}
	
	//===========================================================================================================
    // GENERAR HTML DE LA TABLA DE EMPRESAS
    // Función para generar el HTML de la tabla con los datos en estilo lista
    //===========================================================================================================
    
    private function generar_html_tabla($empresas, $paginacion) {
        $html = '
		<table  class="table empresa-table">
			<thead>
				<tr>
					<th>Código</th>
					<th>Empresa</th>
					<th>Email</th>
					<th>Teléfono</th>
					<th>Representante</th>
					<th>Sucursales</th>
					<th>Estado</th>
					<th>Acciones</th>
				</tr>
			</thead>
		
		<tbody>';
        
        if (empty($empresas)) {
            $html .= '
                <tr>
                    <td colspan="8" class="text-center py-5">
                        <i class="bi bi-building fa-3x text-muted mb-3"></i>
                        <h5 class="text-muted">No hay empresas registradas</h5>
                        <p class="text-muted">Comienza creando tu primera empresa</p>
                        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalNuevaEmpresa">
                            <i class="bi bi-plus me-1"></i>
                            Crear Primera Empresa
                        </button>
                    </td>
                </tr>';
        } else {
			
			
			$separador = $this->encryption_deterministico('1n49n', 'separador_empresa');
			foreach ($empresas as $empresa) {
				
                //$badge_estado = $empresa['EmpresaEstado'] == 'Activo' ? '<span class="badge-custom badge-activo">Activo</span>' : '<span class="badge-custom badge-inactivo">' . htmlspecialchars($empresa['EmpresaEstado']) . '</span>';
				
				$total_sucursales = (int)$empresa['total_sucursales'];
                $texto_sucursales = $total_sucursales == 1 ? 'sucursal' : 'sucursales';
                $codigo_encriptado = $_SESSION['csrf_listEmpresas']. $separador .$this->encryption($empresa['EmpresaId']); 
    			
				// Generar dropdown de estados (en lugar de badge estático)
				$opciones_estado = [
					'Activo' => ['color' => 'success', 'icono' => 'check-circle'],
					'Inactivo' => ['color' => 'warning', 'icono' => 'pause-circle'],
					'Suspendido' => ['color' => 'danger', 'icono' => 'x-circle'],
					'Eliminado' => ['color' => 'secondary', 'icono' => 'trash']
				];

				$dropdown_estados = '<div class="dropdown">
					<button class="btn btn-outline-' . $opciones_estado[$empresa['EmpresaEstado']]['color'] . ' btn-sm dropdown-toggle estado-dropdown" 
							type="button" 
							data-bs-toggle="dropdown" 
							data-empresa-id="' . $codigo_encriptado . '"
							data-estado-actual="' . $empresa['EmpresaEstado'] . '"
							data-empresa-nombre="' . htmlspecialchars($empresa['EmpresaNombre']) . '">
						<i class="bi bi-' . $opciones_estado[$empresa['EmpresaEstado']]['icono'] . ' me-1"></i>
						' . $empresa['EmpresaEstado'] . '
					</button>
					<ul class="dropdown-menu">';

				// Agregar opciones (excepto la actual y "Eliminado")
				foreach ($opciones_estado as $estado => $config) {
					if ($estado !== $empresa['EmpresaEstado'] && $estado !== 'Eliminado') {
						$dropdown_estados .= '
							<li>
								<a class="dropdown-item cambiar-estado" 
								   href="#" 
								   data-nuevo-estado="' . $estado . '"
								   data-empresa-id="' . $codigo_encriptado . '"
								   data-empresa-nombre="' . htmlspecialchars($empresa['EmpresaNombre']) . '">
									<i class="bi bi-' . $config['icono'] . ' me-2 text-' . $config['color'] . '"></i>
									Cambiar a ' . $estado . '
								</a>
							</li>';
					}
				}

				$dropdown_estados .= '
					</ul>
				</div>';

                
                
				
                $html .= '
                    <tr>
                        <td>
                            <span class="badge-custom badge-codigo">' . htmlspecialchars($empresa['EmpresaCodigo']) . '</span>
                        </td>
                        <td>
                            <div>
                                <strong>' . htmlspecialchars($empresa['EmpresaNombre']) . '</strong>
                                <br>
                                <small class="text-muted">' . htmlspecialchars($empresa['EmpresaDireccion']) . '</small>
                            </div>
                        </td>
                        <td>
                            <a href="mailto:' . htmlspecialchars($empresa['EmpresaEmail']) . '" class="text-decoration-none">
                                ' . htmlspecialchars($empresa['EmpresaEmail']) . '
                            </a>
                        </td>
                        <td>' . htmlspecialchars($empresa['EmpresaTelefono']) . '</td>
                        <td>
                            <div>
                                <strong>' . htmlspecialchars($empresa['EmpresaNomRepresentante']) . '</strong>
                                <br>
                                <small class="text-muted">ID: ' . htmlspecialchars($empresa['EmpresaIdRepresentante']) . '</small>
                            </div>
                        </td>
                        <td>
                            <span class="badge-custom badge-sucursales">
                                ' . $total_sucursales . ' ' . $texto_sucursales . '
                            </span>
                        </td>
                        <td>' . $dropdown_estados . '</td>
                        <td>
                            <div class="btn-actions">';
								if($empresa['EmpresaEstado'] !== 'Eliminado'){
								$html .= '
                                <button class="btn btn-outline-primary btn-action" title="Ver / Editar" onclick="verEmpresa(\'' .$codigo_encriptado . '\')">
									<i class="bi bi-pencil-square"></i>
								</button>
                                <button class="btn btn-outline-danger btn-action" title="Eliminar" onclick="eliminarEmpresa(\'' .$codigo_encriptado . '\', \'' . htmlspecialchars($empresa['EmpresaNombre']) . '\',\''.$paginacion['pagina_actual'].'\')">
                                    <i class="bi bi-trash"></i>
                                </button>';
								}
							$html .= '
                            </div>
                        </td>
                    </tr>';
            }
        }
        
        $html .= '</tbody>
		
		</table>';
        
        // Agregar información de paginación
        if ($paginacion['total_paginas'] > 1) {
            $html .= $this->generar_html_paginacion($paginacion);
        }
        
        return $html;
    }
    
	//===========================================================================================================
    // GENERAR HTML DE LA TABLA DE EMPRESAS
    // Función para generar el HTML de la tabla con los datos en estilo grid
    //===========================================================================================================

    private function generar_html_cards($empresas, $paginacion) {
        $html = '<div class="row">';
        
        if (empty($empresas)) {
            $html .= '
                <div class="col-12 text-center py-5">
                    <i class="bi bi-building fa-3x text-muted mb-3"></i>
                    <h5 class="text-muted">No hay empresas registradas</h5>
                    <p class="text-muted">Comienza creando tu primera empresa</p>
                    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalNuevaEmpresa">
                        <i class="bi bi-plus me-1"></i>
                        Crear Primera Empresa
                    </button>
                </div>';
        } else {
			
			$separador = $this->encryption_deterministico('1n49n', 'separador_empresa');
			
            foreach ($empresas as $empresa) {
                //$badge_estado = $empresa['EmpresaEstado'] == 'Activo' ? '<span class="badge bg-success">Activo</span>' : '<span class="badge bg-secondary">' . htmlspecialchars($empresa['EmpresaEstado']) . '</span>';
				                
				$total_sucursales = (int)$empresa['total_sucursales'];
                $texto_sucursales = $total_sucursales == 1 ? 'sucursal' : 'sucursales';
                $codigo_encriptado = $_SESSION['csrf_listEmpresas']. $separador .$this->encryption($empresa['EmpresaId']); 
    			
				// Generar dropdown de estados (en lugar de badge estático)
				$opciones_estado = [
					'Activo' => ['color' => 'success', 'icono' => 'check-circle'],
					'Inactivo' => ['color' => 'warning', 'icono' => 'pause-circle'],
					'Suspendido' => ['color' => 'danger', 'icono' => 'x-circle'],
					'Eliminado' => ['color' => 'secondary', 'icono' => 'trash']
				];

				$dropdown_estados = '<div class="dropdown">
					<button class="btn btn-' . $opciones_estado[$empresa['EmpresaEstado']]['color'] . ' btn-sm dropdown-toggle estado-dropdown" 
							type="button" 
							data-bs-toggle="dropdown" 
							data-empresa-id="' . $codigo_encriptado . '"
							data-estado-actual="' . $empresa['EmpresaEstado'] . '"
							data-empresa-nombre="' . htmlspecialchars($empresa['EmpresaNombre']) . '">
						<i class="bi bi-' . $opciones_estado[$empresa['EmpresaEstado']]['icono'] . ' me-1"></i>
						' . $empresa['EmpresaEstado'] . '
					</button>
					<ul class="dropdown-menu">';

				// Agregar opciones (excepto la actual y "Eliminado")
				foreach ($opciones_estado as $estado => $config) {
					if ($estado !== $empresa['EmpresaEstado'] && $estado !== 'Eliminado') {
						$dropdown_estados .= '
							<li>
								<a class="dropdown-item cambiar-estado" 
								   href="#" 
								   data-nuevo-estado="' . $estado . '"
								   data-empresa-id="' . $codigo_encriptado . '"
								   data-empresa-nombre="' . htmlspecialchars($empresa['EmpresaNombre']) . '">
									<i class="bi bi-' . $config['icono'] . ' me-2 text-' . $config['color'] . '"></i>
									Cambiar a ' . $estado . '
								</a>
							</li>';
					}
				}

				$dropdown_estados .= '
					</ul>
				</div>';

				
				
				
				
				
                $html .= '
                    <div class="col-lg-4 col-md-6 mb-4">
                        <div class="card h-100 shadow-sm empresa-card">
                            <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                                <h6 class="mb-0 text-truncate" title="' . htmlspecialchars($empresa['EmpresaNombre']) . '">
                                    <i class="bi bi-building me-2"></i>
                                    ' . htmlspecialchars($empresa['EmpresaNombre']) . '
                                </h6>
                                ' . $dropdown_estados . '
                            </div>
                            <div class="card-body">
                                <div class="mb-2">
                                    <strong class="text-primary">Código:</strong> 
                                    <span class="badge bg-light text-dark">' . htmlspecialchars($empresa['EmpresaCodigo']) . '</span>
                                </div>
                                <div class="mb-2">
                                    <strong class="text-primary">Email:</strong>
                                    <a href="mailto:' . htmlspecialchars($empresa['EmpresaEmail']) . '" class="text-decoration-none small">
                                        ' . htmlspecialchars($empresa['EmpresaEmail']) . '
                                    </a>
                                </div>
                                <div class="mb-2">
                                    <strong class="text-primary">Teléfono:</strong>
                                    <span class="small">' . htmlspecialchars($empresa['EmpresaTelefono']) . '</span>
                                </div>
                                <div class="mb-2">
                                    <strong class="text-primary">Dirección:</strong>
                                    <span class="small text-muted">' . htmlspecialchars($empresa['EmpresaDireccion']) . '</span>
                                </div>
                                <div class="mb-3">
                                    <strong class="text-primary">Representante:</strong>
                                    <span class="small">' . htmlspecialchars($empresa['EmpresaNomRepresentante']) . '</span><br>
                                    <small class="text-muted">ID: ' . htmlspecialchars($empresa['EmpresaIdRepresentante']) . '</small>
                                </div>
                                <div class="text-center mb-3">
                                    <span class="badge bg-info text-white">
                                        <i class="bi bi-geo-alt me-1"></i>
                                        ' . $total_sucursales . ' ' . $texto_sucursales . '
                                    </span>
                                </div>
                            </div>
                            <div class="card-footer bg-light">
                                <div class="d-grid gap-2 d-md-flex justify-content-md-center">';
									if($empresa['EmpresaEstado'] !== 'Eliminado'){
									$html .= '
										<button class="btn btn-outline-primary btn-sm flex-fill" title="Ver / Editar" onclick="verEmpresa(\'' .$codigo_encriptado . '\')">
											<i class="bi bi-pencil-square"></i> Ver / Editar
										</button>
										<button class="btn btn-outline-danger btn-sm flex-fill" title="Eliminar" onclick="eliminarEmpresa(\'' .$codigo_encriptado . '\', \'' . htmlspecialchars($empresa['EmpresaNombre']) . '\')">
											<i class="bi bi-trash"></i> Eliminar
										</button>';
										}
									$html .= '
									
                                </div>
                            </div>
                        </div>
                    </div>';
					
            }
        }
        
        $html .= '</div>';
        
        // Agregar paginación
        if ($paginacion['total_paginas'] > 1) {
            $html .= $this->generar_html_paginacion($paginacion);
        }
        
        return $html;
    }
	
	
	
	
    //===========================================================================================================
    // GENERAR HTML DE PAGINACIÓN
    // Función para generar los controles de paginación
    //===========================================================================================================
    
    private function generar_html_paginacion($paginacion) {
        $html = '<div class="d-flex justify-content-between align-items-center mt-3 px-3 pb-3">';
        
        // Información de registros
        $html .= '<div class="text-muted small">';
        $html .= 'Mostrando ' . $paginacion['desde'] . ' a ' . $paginacion['hasta'] . ' de ' . $paginacion['total_registros'] . ' registros';
        $html .= '</div>';
        
        // Controles de paginación
        if ($paginacion['total_paginas'] > 1) {
            $html .= '<nav aria-label="Paginación de empresas">';
            $html .= '<ul class="pagination pagination-sm mb-0">';
            
            // Botón anterior
            if ($paginacion['pagina_actual'] > 1) {
                $html .= '<li class="page-item">';
                $html .= '<button class="page-link" onclick="cargarPagina(' . ($paginacion['pagina_actual'] - 1) . ')">';
                $html .= '<i class="bi bi-chevron-left"></i> Anterior</button>';
                $html .= '</li>';
            }
            
            // Páginas
            $inicio = max(1, $paginacion['pagina_actual'] - 2);
            $fin = min($paginacion['total_paginas'], $paginacion['pagina_actual'] + 2);
            
            for ($i = $inicio; $i <= $fin; $i++) {
                $activa = ($i == $paginacion['pagina_actual']) ? 'active' : '';
                $html .= '<li class="page-item ' . $activa . '">';
                $html .= '<button class="page-link" onclick="cargarPagina(' . $i . ')">' . $i . '</button>';
                $html .= '</li>';
            }
            
            // Botón siguiente
            if ($paginacion['pagina_actual'] < $paginacion['total_paginas']) {
                $html .= '<li class="page-item">';
                $html .= '<button class="page-link" onclick="cargarPagina(' . ($paginacion['pagina_actual'] + 1) . ')">';
                $html .= 'Siguiente <i class="bi bi-chevron-right"></i></button>';
                $html .= '</li>';
            }
            
            $html .= '</ul>';
            $html .= '</nav>';
        }
        
        $html .= '</div>';
        return $html;
    }
    
    //===========================================================================================================
    // GENERAR HTML DE ESTADÍSTICAS
    // Función para generar las tarjetas de estadísticas
    //===========================================================================================================
    
	private function generar_html_estadisticas($estadisticas) {
		return '
			<div class="col-lg-2 col-md-6 mb-3">
				<div class="stats-card purple">
					<div class="stats-content">
						<div class="stats-number">' . $estadisticas['total_empresas'] . '</div>
						<div class="stats-label">Total Empresas</div>
					</div>
					<div class="stats-icon">
						<i class="bi bi-building"></i>
					</div>
				</div>
			</div>
			<div class="col-lg-2 col-md-6 mb-3">
				<div class="stats-card pink">
					<div class="stats-content">
						<div class="stats-number">' . $estadisticas['empresas_activas'] . '</div>
						<div class="stats-label">Empresas Activas</div>
					</div>
					<div class="stats-icon">
						<i class="bi bi-check-circle"></i>
					</div>
				</div>
			</div>
			<div class="col-lg-2 col-md-6 mb-3">
				<div class="stats-card cyan">
					<div class="stats-content">
						<div class="stats-number">' . $estadisticas['total_sucursales'] . '</div>
						<div class="stats-label">Total Sucursales</div>
					</div>
					<div class="stats-icon">
						<i class="bi bi-geo-alt"></i>
					</div>
				</div>
			</div>
			<div class="col-lg-2 col-md-6 mb-3">
				<div class="stats-card green">
					<div class="stats-content">
						<div class="stats-number">' . $estadisticas['sucursales_activas'] . '</div>
						<div class="stats-label">Sucursales Activas</div>
					</div>
					<div class="stats-icon">
						<i class="bi bi-graph-up"></i>
					</div>
				</div>
			</div>
			<!-- NUEVAS TARJETAS DE SEDES -->
			<div class="col-lg-2 col-md-6 mb-3">
				<div class="stats-card orange">
					<div class="stats-content">
						<div class="stats-number">' . $estadisticas['total_sedes'] . '</div>
						<div class="stats-label">Total Sedes</div>
					</div>
					<div class="stats-icon">
						<i class="bi bi-buildings"></i>
					</div>
				</div>
			</div>
			<div class="col-lg-2 col-md-6 mb-3">
				<div class="stats-card blue">
					<div class="stats-content">
						<div class="stats-number">' . $estadisticas['sedes_activas'] . '</div>
						<div class="stats-label">Sedes Activas</div>
					</div>
					<div class="stats-icon">
						<i class="bi bi-building-check"></i>
					</div>
				</div>
			</div>';
	}
	//===========================================================================================================
    // ELIMINAR EMPRESA (DESACTIVAR)
    // Función para eliminar empresa, solo le cambie el estado a eliminado pero no la elimina
    //===========================================================================================================
	public function eliminar_empresa_controlador(){
		/*-------------------//-------- PASO 1 LIMPIEZA, VALIDACIONES Y SEGURIDAD --------//-----------------------*/	
	/************ Marcar inicio del tiempo para normalizar respuestas *******************************************/
		$this->tiempo_inicio = microtime(true);

	/************ VALIDACION 1: Método POST *****************************************************************/
		if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
			$this->guardar_log('metodo_http_invalido_eliminar', [
				'datos_antes' => ['metodo_recibido' => $_SERVER['REQUEST_METHOD']],
				'datos_despues' => ['accion' => 'rechazado']
			], 'alto', 'bloqueado', 'App_empresa');

			return json_encode(["error" => "Method not allowed"]);
		}

	/************ VALIDACION 2: Detección de bots ***********************************************************/
		$numero_campos = count($_POST);
		$umbral_minimo = $numero_campos * 1; // 1 segundo por campo para eliminaciones
		$analisis_bot = $this->es_bot_sospechoso($umbral_minimo, $numero_campos);

		if ($analisis_bot['es_bot']) {
			$this->normalizar_tiempo_respuesta();
			return json_encode([
				"Titulo" => "Actividad Sospechosa",
				"Texto" => "Actividad detectada como automatizada", 
				"Tipo" => "warning"
			], JSON_UNESCAPED_UNICODE);
		}

	/************ VALIDACION 3: Verificar permisos **********************************************************/
		if(!$this->verificar_permisos('eliminar_empresas')){
			$this->guardar_log('sin_permisos_eliminar_empresa', [
				'datos_antes' => ['usuario' => $_SESSION['CodigoUsuario']],
				'datos_despues' => ['accion' => 'bloqueado']
			], 'alto', 'bloqueado', 'App_empresa');

			$this->normalizar_tiempo_respuesta();
			return json_encode([
				"Titulo" => "Sin permisos",
				"Texto" => "No tienes permisos para eliminar empresas", 
				"Tipo" => "warning"
			], JSON_UNESCAPED_UNICODE);
		}

	/************ VALIDACION 4: Límite de intentos **********************************************************/
		if(!$this->verificar_intentos('eliminar_empresa', 3, 300)){
			$this->normalizar_tiempo_respuesta();
			return json_encode([
				"Titulo" => "Demasiados intentos",
				"Texto" => "Has superado el límite de intentos. Espera 5 minutos",
				"Tipo" => "warning"
			], JSON_UNESCAPED_UNICODE);
		}

	/************ SEPARAR código y token usando el separador encriptado *************************************/	
		$codigo_encriptado = $_POST['codigo_empresa'] ?? '';

		// Intentar separar primero
		$data_separada = $this->separar_codigo_con_token($codigo_encriptado, 'separador_empresa');

		if (!$data_separada) {
			return json_encode([
				"Titulo" => "Código inválido",
				"Texto" => "El código de empresa no es válido o está corrupto",
				"Tipo" => "warning"
			], JSON_UNESCAPED_UNICODE);
		}

		$codigo_real = $data_separada['codigo'];
		$token_encriptado = $data_separada['token_encriptado'];

		/************ VALIDAR token CSRF ************************************************************************/
		if (!$this->validar_csrf($token_encriptado, 'listEmpresas')) {
			$this->guardar_log('csrf_token_invalido_eliminar', [
				'datos_antes' => ['codigo_empresa' => $codigo_real],
				'datos_despues' => ['accion' => 'bloqueado']
			], 'alto', 'bloqueado', 'App_empresa');

			$this->normalizar_tiempo_respuesta();
			return json_encode([
				"Titulo" => "Token inválido",
				"Texto" => "Token de seguridad inválido o expirado",
				"Tipo" => "warning"
			], JSON_UNESCAPED_UNICODE);
		}

		/*-------------------//-------- PASO 2: ELIMINAR EMPRESA --------//------------------------------------*/

		/************ Verificar que la empresa existe ************************************************************/
		$empresa = $this->obtener_empresa_por_id($codigo_real);
		if (!$empresa) {
			$this->normalizar_tiempo_respuesta();
			return json_encode([
				"Titulo" => "Empresa no encontrada",
				"Texto" => "La empresa no existe o ya fue eliminada",
				"Tipo" => "warning"
			], JSON_UNESCAPED_UNICODE);
		}

		/************ Realizar eliminación (soft delete) ********************************************************/
		$resultado = $this->eliminar_empresa_modelo($empresa['EmpresaId']);
		if ($resultado) {
			/*-*-*-*-*-* Logs de eliminación exitosa *-*-*-*-*-*/
			$this->guardar_log('empresa_eliminada', [
				'datos_antes' => [
					'codigo' => $codigo_real,
					'nombre' => $empresa['EmpresaNombre'],
					'id' => $empresa['EmpresaId']
				],
				'datos_despues' => [
					'resultado' => 'empresa_eliminada',
					'estado_anterior' => $empresa['EmpresaEstado']
				],
			], 'medio', 'exito', 'App_empresa');

			/*-*-*-*-*-* Generar nuevo token para siguientes acciones *-*-*-*-*-*/
    		$nuevo_token = $this->generar_token_csrf('listEmpresas');

			$this->normalizar_tiempo_respuesta();
			return json_encode([
				"Titulo" => "Empresa eliminada",
				"Texto" => "La empresa '" . $empresa['EmpresaNombre'] . "' ha sido eliminada exitosamente",
				"Tipo" => "success",
				"nuevo_token" => $nuevo_token  // Enviar el nuevo token al JavaScript
			], JSON_UNESCAPED_UNICODE);

		} else {
			$this->normalizar_tiempo_respuesta();
			return json_encode([
				"Titulo" => "Error",
				"Texto" => "No se pudo eliminar la empresa. Intenta nuevamente",
				"Tipo" => "error"
			], JSON_UNESCAPED_UNICODE);
		}
	}
	
	
	//===========================================================================================================
    // OBTENER DATOS DE UNA EMPRESA ESPECÍFICA
    // Función para obtener todos los datos de una empresa por su ID
    //===========================================================================================================
	
	public function obtener_empresa_controlador(){
		/*-------------------//-------- PASO 1 LIMPIEZA, VALIDACIONES Y SEGURIDAD --------//-----------------------*/	
	/************ Marcar inicio del tiempo para normalizar respuestas *******************************************/
		$this->tiempo_inicio = microtime(true);

	/************ VALIDACION 1: Método POST *****************************************************************/
		if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
			$this->guardar_log('metodo_http_invalido_obtener', [
				'datos_antes' => ['metodo_recibido' => $_SERVER['REQUEST_METHOD']],
				'datos_despues' => ['accion' => 'rechazado']
			], 'alto', 'bloqueado', 'App_empresa');

			return json_encode(["error" => "Method not allowed"]);
		}

	/************ VALIDACION 2: Verificar permisos **********************************************************/
		if(!$this->verificar_permisos('ver_empresas')){
			$this->guardar_log('sin_permisos_ver_empresa', [
				'datos_antes' => ['usuario' => $_SESSION['CodigoUsuario']],
				'datos_despues' => ['accion' => 'bloqueado']
			], 'alto', 'bloqueado', 'App_empresa');

			$this->normalizar_tiempo_respuesta();
			return json_encode([
				"Titulo" => "Sin permisos",
				"Texto" => "No tienes permisos para ver empresas", 
				"Tipo" => "warning"
			], JSON_UNESCAPED_UNICODE);
		}

	/************ VALIDACION 3: Validar token CSRF **********************************************************/
		if(!isset($_POST['csrf_token_obtener']) || !$this->validar_csrf($_POST['csrf_token_obtener'], 'listEmpresas')){
			$this->guardar_log('csrf_token_invalido_obtener', [
				'datos_antes' => ['empresa_id' => $_POST['empresa_id'] ?? 'no_definido'],
				'datos_despues' => ['accion' => 'bloqueado']
			], 'alto', 'bloqueado', 'App_empresa');

			$this->normalizar_tiempo_respuesta();
			return json_encode([
				"Titulo" => "Token inválido",
				"Texto" => "Token de seguridad inválido o expirado",
				"Tipo" => "warning"
			], JSON_UNESCAPED_UNICODE);
		}
		
	/************ SEPARAR código y token usando el separador encriptado *************************************/	
		$codigo_encriptado = $_POST['empresa_id'] ?? '';

		// Intentar separar primero
		$data_separada = $this->separar_codigo_con_token($codigo_encriptado, 'separador_empresa');

		if (!$data_separada) {
			return json_encode([
				"Titulo" => "Código inválido",
				"Texto" => "El código de empresa no es válido o está corrupto",
				"Tipo" => "warning"
			], JSON_UNESCAPED_UNICODE);
		}

		$codigo_real = $data_separada['codigo'];
		$token_encriptado = $data_separada['token_encriptado'];
		
	/************ VALIDACION 4: Limpiar y validar ID de empresa *********************************************/
		$resultado_limpieza = $this->limpiar_datos($codigo_real, 'numero', 'empresa_id');
		
		if (!$resultado_limpieza['es_seguro']) {
			$this->normalizar_tiempo_respuesta();
			return json_encode([
				"Titulo" => "ID inválido",
				"Texto" => "El ID de empresa no es válido",
				"Tipo" => "warning"
			], JSON_UNESCAPED_UNICODE);
		}

		$empresa_id = $resultado_limpieza['dato_limpio'];

		/*-------------------//-------- PASO 2: OBTENER EMPRESA --------//------------------------------------*/

		try {
	/************ Obtener datos de la empresa ***********************************************************/
			$empresa = $this->obtener_empresa_completa_modelo($empresa_id);
			
			if (!$empresa) {
				$this->normalizar_tiempo_respuesta();
				return json_encode([
					"Titulo" => "Empresa no encontrada",
					"Texto" => "La empresa no existe o no está disponible",
					"Tipo" => "warning"
				], JSON_UNESCAPED_UNICODE);
			}
			
			/*-*-*-*-*-* SEGURIDAD: Generar token específico usando sistema genérico *-*-*-*-*-*/
			$datos_extra = [
				'codigo_empresa' => $empresa['EmpresaCodigo'],
				'nombre_empresa' => $empresa['EmpresaNombre']
			];
			$token_empresa_especifico = $this->generar_token_entidad_especifico('empresa', $empresa_id, 1800, $datos_extra);
			
			/*-*-*-*-*-* SEGURIDAD: Encriptar el ID antes de enviarlo al frontend *-*-*-*-*-*/
			$empresa['EmpresaId'] = $this->encryption($empresa_id);
			
			/*-*-*-*-*-* SEGURIDAD: Agregar el token específico a la respuesta *-*-*-*-*-*/
			$empresa['TokenEmpresaEspecifico'] = $token_empresa_especifico;

			
			/*-*-*-*-*-* Log de consulta exitosa *-*-*-*-*-*/
			$this->guardar_log('empresa_consultada', [
				'datos_antes' => [
					'empresa_id_solicitado' => $empresa_id
				],
				'datos_despues' => [
					'empresa_encontrada' => $empresa['EmpresaCodigo'],
					'nombre' => $empresa['EmpresaNombre'],
					'id_encriptado' => 'SI',
					'token_empresa_generado' => 'SI'					
				],
			], 'bajo', 'exito', 'App_empresa');
						
			$this->normalizar_tiempo_respuesta();
			return json_encode([
				"status" => "success",
				"empresa" => $empresa
			], JSON_UNESCAPED_UNICODE);

		} catch (Exception $e) {
			$this->guardar_log('error_obtener_empresa', [
				'datos_antes' => ['empresa_id' => $empresa_id],
				'datos_despues' => ['error' => $e->getMessage()]
			], 'alto', 'error', 'App_empresa');

			$this->normalizar_tiempo_respuesta();
			return json_encode([
				"Titulo" => "Error interno",
				"Texto" => "Ocurrió un error al obtener la empresa",
				"Tipo" => "error"
			], JSON_UNESCAPED_UNICODE);
		}
	}

	//===========================================================================================================
    // ACTUALIZAR EMPRESA
    // Función para actualizar los datos de una empresa existente
    //===========================================================================================================
	
	public function actualizar_empresa_controlador(){
		/*-------------------//-------- PASO 1 LIMPIEZA, VALIDACIONES Y SEGURIDAD --------//-----------------------*/	
	/************ Marcar inicio del tiempo para normalizar respuestas *******************************************/
		$this->tiempo_inicio = microtime(true);

		/************ VALIDACION 1: Método POST *****************************************************************/
		if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
			$this->guardar_log('metodo_http_invalido_actualizar', [
				'datos_antes' => ['metodo_recibido' => $_SERVER['REQUEST_METHOD']],
				'datos_despues' => ['accion' => 'rechazado']
			], 'alto', 'bloqueado', 'App_empresa');

			return json_encode(["error" => "Method not allowed"]);
		}

	/************ VALIDACION 2: Detección de bots ***********************************************************/
		$numero_campos = count($_POST);
		$umbral_minimo = $numero_campos * 2; // 2 segundos por campo para actualizaciones
		$analisis_bot = $this->es_bot_sospechoso($umbral_minimo, $numero_campos);

		if ($analisis_bot['es_bot']) {
			$this->normalizar_tiempo_respuesta();
			return json_encode([
				"Titulo" => "Actividad Sospechosa",
				"Texto" => "Actividad detectada como automatizada", 
				"Tipo" => "warning"
			], JSON_UNESCAPED_UNICODE);
		}

	/************ VALIDACION 3: Verificar permisos **********************************************************/
		if(!$this->verificar_permisos('editar_empresas')){
			$this->guardar_log('sin_permisos_editar_empresa', [
				'datos_antes' => ['usuario' => $_SESSION['CodigoUsuario']],
				'datos_despues' => ['accion' => 'bloqueado']
			], 'alto', 'bloqueado', 'App_empresa');

			$this->normalizar_tiempo_respuesta();
			return json_encode([
				"Titulo" => "Sin permisos",
				"Texto" => "No tienes permisos para editar empresas", 
				"Tipo" => "warning"
			], JSON_UNESCAPED_UNICODE);
		}

	/************ VALIDACION 4: Validar token CSRF **********************************************************/
		if(!isset($_POST['csrf_token_editar']) || !$this->validar_csrf($_POST['csrf_token_editar'], 'editarEmpresa')){
			$this->guardar_log('csrf_token_invalido_editar', [
				'datos_antes' => ['empresa_id' => $_POST['empresa_id'] ?? 'no_definido'],
				'datos_despues' => ['accion' => 'bloqueado']
			], 'alto', 'bloqueado', 'App_empresa');

			$this->normalizar_tiempo_respuesta();
			return json_encode([
				"Titulo" => "Token inválido",
				"Texto" => "Token de seguridad inválido o expirado",
				"Tipo" => "warning"
			], JSON_UNESCAPED_UNICODE);
		}

	/************ VALIDACION 5: Límite de intentos **********************************************************/
		if(!$this->verificar_intentos('actualizar_empresa', 3, 300)){
			$this->normalizar_tiempo_respuesta();
			return json_encode([
				"Titulo" => "Demasiados intentos",
				"Texto" => "Has superado el límite de intentos. Espera 5 minutos",
				"Tipo" => "warning"
			], JSON_UNESCAPED_UNICODE);
		}
		
		/*-*-*-*-*-* SEGURIDAD: Desencriptar y validar ID de empresa *-*-*-*-*-*/
		$empresa_id_encriptado = $_POST['empresa_id'] ?? '';
		$empresa_id_desencriptado = $this->decryption($empresa_id_encriptado);

		if (!$empresa_id_desencriptado || !is_numeric($empresa_id_desencriptado)) {
			$this->guardar_log('id_empresa_invalido_actualizar', [
				'datos_antes' => ['id_recibido_hash' => hash('sha256', $empresa_id_encriptado)],
				'datos_despues' => ['accion' => 'bloqueado', 'razon' => 'desencriptacion_fallida']
			], 'alto', 'bloqueado', 'App_empresa');
			
			$this->normalizar_tiempo_respuesta();
			return json_encode([
				"Titulo" => "ID inválido",
				"Texto" => "El identificador de empresa no es válido",
				"Tipo" => "warning"
			], JSON_UNESCAPED_UNICODE);
		}
		
		/************ VALIDACION 6: Limpiar los datos recibidos *************************************************/ 
		$resultados_limpieza = [
			'EmpresaId' => $this->limpiar_datos($empresa_id_desencriptado, 'numero', 'empresa_id'),
			'EmpresaNit' => $this->limpiar_datos($_POST['empresa_nit'], 'numero', 'empresa_nit'),
			'EmpresaNombre' => $this->limpiar_datos($_POST['empresa_nombre'], 'texto', 'empresa_nombre'),
			'EmpresaDireccion' => $this->limpiar_datos($_POST['empresa_direccion'], 'texto','empresa_direccion'),
			'EmpresaTelefono' => $this->limpiar_datos($_POST['empresa_telefono'], 'texto','empresa_telefono'),
			'EmpresaEmail' => $this->limpiar_datos($_POST['empresa_email'], 'email', 'empresa_email'),
			'EmpresaIdRepresentante' => $this->limpiar_datos($_POST['empresa_id_representante'], 'numero', 'empresa_id_representante'),
			'EmpresaNomRepresentante' => $this->limpiar_datos($_POST['empresa_nom_representante'], 'texto', 'empresa_nom_representante')
		];
		
		/************ VALIDACION 7: Verificar ataques **********************************************************/
		$hay_ataques = false;
		$ataques_por_campo = [];

		foreach ($resultados_limpieza as $campo => $resultado) {
			if (!$resultado['es_seguro']) {
				$hay_ataques = true;
				$ataques_por_campo[$campo] = $resultado['ataques_detectados'];
			}  
		}

		if ($hay_ataques) {
			$nivel_riesgo_maximo = 'bajo';
			foreach ($resultados_limpieza as $resultado) {
				if ($resultado['nivel_riesgo'] === 'alto') {
					$nivel_riesgo_maximo = 'alto';
					break;
				} elseif ($resultado['nivel_riesgo'] === 'medio' && $nivel_riesgo_maximo !== 'alto') {
					$nivel_riesgo_maximo = 'medio';
				}
			}

			$this->guardar_log('formulario_rechazado_por_ataques_actualizar', [
				'datos_antes' => [
					'campos_con_ataques' => array_keys($ataques_por_campo),
					'resumen_ataques' => $ataques_por_campo
				],
				'datos_despues' => ['accion_tomada' => 'formulario_rechazado_completamente'],
			], $nivel_riesgo_maximo, 'rechazado', 'seguridad'); 

			$this->normalizar_tiempo_respuesta();
			return json_encode([
				"Titulo" => "Datos no válidos",
				"Texto" => "Los datos enviados contienen información no permitida.",
				"Tipo" => "warning"
			], JSON_UNESCAPED_UNICODE);
		}

		/************ VALIDACION 8: Extraer datos limpios ******************************************************/
		$datos_empresa = [];
		foreach ($resultados_limpieza as $campo => $resultado) {
			$datos_empresa[$campo] = $resultado['dato_limpio'];
		}
				
		/*-*-*-*-*-* SEGURIDAD EXTRA: Validar token específico usando sistema genérico *-*-*-*-*-*/
		$token_empresa_recibido = $_POST['token_empresa_especifico'] ?? '';
		
		if (!$this->validar_token_entidad_especifico('empresa', $empresa_id_desencriptado, $token_empresa_recibido)) {
			$this->normalizar_tiempo_respuesta();
			return json_encode([
				"Titulo" => "Token de empresa inválido",
				"Texto" => "El token de autorización para esta empresa no es válido o ha expirado. Vuelve a cargar la empresa.",
				"Tipo" => "warning"
			], JSON_UNESCAPED_UNICODE);
		}

		/************ VALIDACION 9: Validar campos obligatorios ************************************************/
		$campos_excluir = ['EmpresaId', 'EmpresaCodigo', 'EmpresaFechaRegistro', 'EmpresaFechaActualizacion', 'EmpresaEstado'];

		$reglas_personalizadas = [
			'EmpresaNit' => ['min_caracteres' => 8],
			'EmpresaTelefono' => ['min_caracteres' => 7],
			'EmpresaNomRepresentante' => ['solo_letras' => true]
		];

		$errores = $this->validar_completo($datos_empresa, 'App_empresa_empresa', $campos_excluir, $reglas_personalizadas);

		if(!empty($errores)){
			$mensaje_error = "Errores encontrados:\n";
			foreach($errores as $campo => $errores_campo){
				$mensaje_error .= "- " . implode(", ", $errores_campo) . "\n";
			}

			$this->normalizar_tiempo_respuesta();
			return json_encode([
				"Titulo" => "Datos incorrectos",
				"Texto" => $mensaje_error,
				"Tipo" => "warning"
			], JSON_UNESCAPED_UNICODE);
		}

		/*-------------------//-------- PASO 2: ACTUALIZAR EMPRESA --------//----------------------------------*/

		try {
			/************ Verificar que la empresa existe *******************************************************/
			$empresa_actual = $this->obtener_empresa_completa_modelo($datos_empresa['EmpresaId']);
			if (!$empresa_actual) {
				$this->normalizar_tiempo_respuesta();
				return json_encode([
					"Titulo" => "Empresa no encontrada",
					"Texto" => "La empresa no existe o ya fue eliminada",
					"Tipo" => "warning"
				], JSON_UNESCAPED_UNICODE);
			}

			/************ Verificar duplicados (excluyendo la empresa actual) ***********************************/
			if($this->verificar_nit_duplicado_actualizar($datos_empresa['EmpresaNit'], $datos_empresa['EmpresaId'])){
				$this->normalizar_tiempo_respuesta();
				return json_encode([
					"Titulo" => "NIT duplicado",
					"Texto" => "Ya existe otra empresa registrada con este NIT", 
					"Tipo" => "warning"
				], JSON_UNESCAPED_UNICODE);
			}

			if($this->verificar_email_duplicado_actualizar($datos_empresa['EmpresaEmail'], $datos_empresa['EmpresaId'])){
				$this->normalizar_tiempo_respuesta();
				return json_encode([
					"Titulo" => "Email duplicado",
					"Texto" => "Ya existe otra empresa registrada con este email",
					"Tipo" => "warning"
				], JSON_UNESCAPED_UNICODE);
			}

			/************ Preparar datos finales ***************************************************************/
			$datos_finales = [
				'id' => $datos_empresa['EmpresaId'],
				'nit' => $datos_empresa['EmpresaNit'],
				'nombre' => $datos_empresa['EmpresaNombre'],
				'direccion' => $datos_empresa['EmpresaDireccion'],
				'telefono' => $datos_empresa['EmpresaTelefono'],
				'email' => $datos_empresa['EmpresaEmail'],
				'id_representante' => $datos_empresa['EmpresaIdRepresentante'],
				'nom_representante' => $datos_empresa['EmpresaNomRepresentante'],
				'fecha_actualizacion' => date("Y-m-d H:i:s")
			];

			/************ Actualizar empresa ****************************************************************/
			$resultado = $this->actualizar_empresa_modelo($datos_finales);

			if($resultado){
				/*-*-*-*-*-* Log de actualización exitosa *-*-*-*-*-*/
				$this->guardar_log('empresa_actualizada', [
					'datos_antes' => [
						'empresa_id' => $datos_empresa['EmpresaId'],
						'datos_anteriores' => $empresa_actual
					],
					'datos_despues' => [
						'resultado' => 'empresa_actualizada',
						'datos_nuevos' => $datos_finales
					],
				], 'medio', 'exito', 'App_empresa');

				/*-*-*-*-*-* Eliminar token usado *-*-*-*-*-*/
				$this->eliminar_token_csrf('editarEmpresa');

				$this->normalizar_tiempo_respuesta();
				return json_encode([
					"Titulo" => "Empresa actualizada",
					"Texto" => "Los datos de la empresa se han actualizado exitosamente",
					"Tipo" => "success"
				], JSON_UNESCAPED_UNICODE);

			} else {
				$this->normalizar_tiempo_respuesta();
				return json_encode([
					"Titulo" => "Error",
					"Texto" => "No se pudo actualizar la empresa. Intenta nuevamente",
					"Tipo" => "error"
				], JSON_UNESCAPED_UNICODE);
			}

		} catch (Exception $e) {
			$this->guardar_log('error_actualizar_empresa', [
				'datos_antes' => ['empresa_id' => $datos_empresa['EmpresaId']],
				'datos_despues' => ['error' => $e->getMessage()]
			], 'alto', 'error', 'App_empresa');

			$this->normalizar_tiempo_respuesta();
			return json_encode([
				"Titulo" => "Error interno",
				"Texto" => "Ocurrió un error al actualizar la empresa",
				"Tipo" => "error"
			], JSON_UNESCAPED_UNICODE);
		}
	}
	
	//===========================================================================================================
    // CAMBIAR ESTADO DE EMPRESA
    // Función para cambiar el estado de una empresa (Activo/Inactivo/Suspendido)
    //===========================================================================================================
	
	public function cambiar_estado_empresa_controlador(){
		/*-------------------//-------- VALIDACIONES DE SEGURIDAD --------//-----------------------*/	
	/************ Marcar inicio del tiempo para normalizar respuestas *******************************************/
		$this->tiempo_inicio = microtime(true);

	/************ VALIDACION 1: Método POST *****************************************************************/
		if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
			$this->guardar_log('metodo_http_invalido_cambiar_estado_empresa', [
				'datos_antes' => ['metodo_recibido' => $_SERVER['REQUEST_METHOD']],
				'datos_despues' => ['accion' => 'rechazado']
			], 'alto', 'bloqueado', 'App_empresa');

			return json_encode(["error" => "Method not allowed"]);
		}

	/************ VALIDACION 2: Detección de bots ***********************************************************/
		$numero_campos = count($_POST);
		$umbral_minimo = $numero_campos * 2; // 2 segundos por campo para actualizaciones
		$analisis_bot = $this->es_bot_sospechoso($umbral_minimo, $numero_campos);

		if ($analisis_bot['es_bot']) {
			$this->normalizar_tiempo_respuesta();
			return json_encode([
				"Titulo" => "Actividad Sospechosa",
				"Texto" => "Actividad detectada como automatizada", 
				"Tipo" => "warning"
			], JSON_UNESCAPED_UNICODE);
		}
		
	/************ VALIDACION 3: Verificar permisos **********************************************************/
		if(!$this->verificar_permisos('cambiar_estado_empresas')){
			$this->guardar_log('sin_permisos_cambiar_estado_empresas', [
				'datos_antes' => ['usuario' => $_SESSION['CodigoUsuario']],
				'datos_despues' => ['accion' => 'bloqueado']
			], 'alto', 'bloqueado', 'App_empresa');

			$this->normalizar_tiempo_respuesta();
			return json_encode([
				"Titulo" => "Sin permisos",
				"Texto" => "No tienes permisos para cambiar estado de empresas", 
				"Tipo" => "warning"
			], JSON_UNESCAPED_UNICODE);
		}
	/************ VALIDACION 4: Límite de intentos **********************************************************/
		if(!$this->verificar_intentos('cambiar_estado_empresa', 3, 300)){
			$this->normalizar_tiempo_respuesta();
			return json_encode([
				"Titulo" => "Demasiados intentos",
				"Texto" => "Has superado el límite de intentos. Espera 5 minutos",
				"Tipo" => "warning"
			], JSON_UNESCAPED_UNICODE);
		}
	
	/************ SEPARAR código y token usando el separador encriptado *************************************/	
		$codigo_encriptado = $_POST['empresa_id'] ?? '';

		// Intentar separar primero
		$data_separada = $this->separar_codigo_con_token($codigo_encriptado,'separador_empresa');

		if (!$data_separada) {
			return json_encode([
				"Titulo" => "Código inválido",
				"Texto" => "El código de empresa no es válido o está corrupto",
				"Tipo" => "warning"
			], JSON_UNESCAPED_UNICODE);
		}

		$codigo_real = $data_separada['codigo'];
		$token_encriptado = $data_separada['token_encriptado'];
	
	/************ VALIDACION 5: VALIDAR token CSRF del listado ***********************************************/
		if (!$this->validar_csrf($token_encriptado, 'listEmpresas')) {
			$this->guardar_log('csrf_token_invalido_lista_cambio_estado', [
				'datos_antes' => ['codigo_empresa' => $codigo_real],
				'datos_despues' => ['accion' => 'bloqueado']
			], 'alto', 'bloqueado', 'App_empresa');

			$this->normalizar_tiempo_respuesta();
			return json_encode([
				"Titulo" => "Token inválido",
				"Texto" => "Token de seguridad inválido o expirado",
				"Tipo" => "warning"
			], JSON_UNESCAPED_UNICODE);
		}	
		
	/************ VALIDACION 6: Validar token CSRF del cambio de estado ****************************************/
		if(!isset($_POST['csrf_token_estado']) || !$this->validar_csrf($_POST['csrf_token_estado'], 'cambioEstado')){
			$this->guardar_log('csrf_token_invalido_cambiar_estado', [
				'datos_antes' => ['empresa_id' => $codigo_real ?? 'no_definido'],
				'datos_despues' => ['accion' => 'bloqueado']
			], 'alto', 'bloqueado', 'App_empresa');

			$this->normalizar_tiempo_respuesta();
			return json_encode([
				"Titulo" => "Token inválido",
				"Texto" => "Token de seguridad inválido o expirado",
				"Tipo" => "warning"
			], JSON_UNESCAPED_UNICODE);
		}
	
		
		/*-------------------//-------- LIMPIEZA Y VALIDACIÓN DE DATOS --------//---------------------------*/

		$resultados_limpieza = [
			'empresa_id' => $this->limpiar_datos($codigo_real, 'numero', 'empresa_id'),
			'nuevo_estado' => $this->limpiar_datos($_POST['nuevo_estado'], 'texto', 'nuevo_estado'),
			'motivo_cambio' => $this->limpiar_datos($_POST['motivo_cambio'], 'texto', 'motivo_cambio')
		];
		
		
			/************ VALIDACION 7: Verifico si algun campo tenía ataques, sale de la limpieza realizada ***********/
        $hay_ataques = false;
        $ataques_por_campo = [];
		/*-*-*-*-*-* reviso cada campo *-*-*-*-*-*/
        foreach ($resultados_limpieza as $campo => $resultado) {
            if (!$resultado['es_seguro']) {
                $hay_ataques = true;
                $ataques_por_campo[$campo] = $resultado['ataques_detectados'];
            }  
        }
        /*-*-*-*-*-* si hay ataques RECHAZO completamente *-*-*-*-*-*/
        if ($hay_ataques) {
            /*-*-*-*-*-*calculo el nivel de riesgo *-*-*-*-*-*/
            $nivel_riesgo_maximo = 'bajo';
            foreach ($resultados_limpieza as $resultado) {
                if ($resultado['nivel_riesgo'] === 'alto') {
                    $nivel_riesgo_maximo = 'alto';
                    break;
                } elseif ($resultado['nivel_riesgo'] === 'medio' && $nivel_riesgo_maximo !== 'alto') {
                    $nivel_riesgo_maximo = 'medio';
                }
            }
			/*-*-*-*-*-* registro logs de seguimiento y auditoria *-*-*-*-*-*/
            $this->guardar_log(
				'formulario_rechazado_por_ataques', [
                	'datos_antes' => [
                    	'campos_con_ataques' => array_keys($ataques_por_campo),
                    	'total_campos_afectados' => count($ataques_por_campo),
                    	'resumen_ataques' => $ataques_por_campo
                	],
                	'datos_despues' => [
                    	'accion_tomada' => 'formulario_rechazado_completamente'
                	],
            	],
				$nivel_riesgo_maximo, 'rechazado', 'seguridad'); 

            /*-*-*-*-*-* Normalizamos el tiempo de respuesta *-*-*-*-*-*/           
            $this->normalizar_tiempo_respuesta();
			/*-*-*-*-*-* Termino función para registro de empresa *-*-*-*-*-*/
            return json_encode([
                "Titulo" => "Datos no válidos",
                "Texto" => "Los datos enviados contienen información no permitida.",
                "Tipo" => "warning"
            ], JSON_UNESCAPED_UNICODE);
        }
		
		

		// Extraer datos limpios
		$empresa_id = $resultados_limpieza['empresa_id']['dato_limpio'];
		$nuevo_estado = $resultados_limpieza['nuevo_estado']['dato_limpio'];
		$motivo_cambio = $resultados_limpieza['motivo_cambio']['dato_limpio'];
		
		
		

		/*-------------------//-------- VALIDACIONES DE NEGOCIO --------//---------------------------*/

		// Validar que el estado sea válido
		$estados_permitidos = ['Activo', 'Inactivo', 'Suspendido'];
		if (!in_array($nuevo_estado, $estados_permitidos)) {
			/*-*-*-*-*-* registro logs de seguimiento y auditoria *-*-*-*-*-*/
			$this->guardar_log('Estado_empresa_no_permitido', [
				'datos_antes' => [
						'estado_actual'=> $empresa_actual['EmpresaEstado']
					],
				'datos_despues' => [
					'accion' => 'bloqueado',
					'estado_recibido'=> $nuevo_estado,
					'usuario'=>$_SESSION['UsuarioId'] ?? 'anonimo'
				]
			], 'medio', 'bloqueado', 'App_empresa');
			
			
			$this->normalizar_tiempo_respuesta();
			return json_encode([
				"Titulo" => "Estado no válido",
				"Texto" => "El estado seleccionado no es válido",
				"Tipo" => "warning"
			], JSON_UNESCAPED_UNICODE);
		}

		// Validar motivo
		if (strlen($motivo_cambio) < 10) {
			/*-*-*-*-*-* registro logs de seguimiento y auditoria *-*-*-*-*-*/
			$this->guardar_log('Estado_empresa_longitud', [
				'datos_antes' => [
						'estado_actual'=> 'motivo del cambio de estado'
					],
				'datos_despues' => [
					'longitud'=> 'la longitud del motivo de cambio es inferior a 10 caracteres',
					'accion'=>'No se realiza el cambio',
					'estado_nuevo' => $nuevo_estado,
					'motivo' => $motivo_cambio,
					'usuario_cambio' => $_SESSION['UsuarioId'] ?? 'anonimo'
				]
			], 'medio', 'bloqueado', 'App_empresa');
			
			$this->normalizar_tiempo_respuesta();
			return json_encode([
				"Titulo" => "Motivo muy corto",
				"Texto" => "El motivo debe tener al menos 10 caracteres",
				"Tipo" => "warning"
			], JSON_UNESCAPED_UNICODE);
		}

		/*-------------------//-------- PROCESAR CAMBIO DE ESTADO --------//---------------------------*/

		try {
			// Obtener datos actuales de la empresa
			$empresa_actual = $this->obtener_empresa_completa_modelo($empresa_id);
			if (!$empresa_actual) {
				$this->normalizar_tiempo_respuesta();
				return json_encode([
					"Titulo" => "Empresa no encontrada",
					"Texto" => "La empresa no existe o ya fue eliminada",
					"Tipo" => "warning"
				], JSON_UNESCAPED_UNICODE);
			}

			// Verificar que el estado realmente va a cambiar
			if ($empresa_actual['EmpresaEstado'] === $nuevo_estado) {
				$this->normalizar_tiempo_respuesta();
				return json_encode([
					"Titulo" => "Sin cambios",
					"Texto" => "La empresa ya tiene el estado: " . $nuevo_estado,
					"Tipo" => "info"
				], JSON_UNESCAPED_UNICODE);
			}

			// Ejecutar cambio de estado
			$resultado = $this->cambiar_estado_empresa_modelo($empresa_id, $nuevo_estado, $motivo_cambio);

			if ($resultado) {
				// Log del cambio exitoso
				$this->guardar_log('estado_empresa_cambiado', [
					'datos_antes' => [
						'empresa_id' => $empresa_id,
						'codigo_empresa' => $empresa_actual['EmpresaCodigo'],
						'nombre_empresa' => $empresa_actual['EmpresaNombre'],
						'estado_anterior' => $empresa_actual['EmpresaEstado']
					],
					'datos_despues' => [
						'estado_nuevo' => $nuevo_estado,
						'motivo' => $motivo_cambio,
						'usuario_cambio' => $_SESSION['UsuarioId'] ?? 'anonimo'
					],
				], 'medio', 'exito', 'App_empresa');

				$this->normalizar_tiempo_respuesta();
				return json_encode([
					"Titulo" => "Estado actualizado",
					"Texto" => "El estado de la empresa se cambió exitosamente a: " . $nuevo_estado,
					"Tipo" => "success"
				], JSON_UNESCAPED_UNICODE);

			} else {
				$this->normalizar_tiempo_respuesta();
				return json_encode([
					"Titulo" => "Error",
					"Texto" => "No se pudo cambiar el estado de la empresa",
					"Tipo" => "error"
				], JSON_UNESCAPED_UNICODE);
			}

		} catch (Exception $e) {

			$this->guardar_log('error_cambiar_estado_empresa', [
				'datos_antes' => ['empresa_id' => $empresa_id, 'nuevo_estado' => $nuevo_estado],
				'datos_despues' => ['error' => $e->getMessage()]
			], 'alto', 'error', 'App_empresa');

			$this->normalizar_tiempo_respuesta();
			return json_encode([
				"Titulo" => "Error interno",
				"Texto" => "Ocurrió un error al cambiar el estado",
				"Tipo" => "error"
			], JSON_UNESCAPED_UNICODE);
		}
	}
	 	
    
	//===========================================================================================================
    // EXPORTAR EMPRESAS A EXCEL
    // Función para generar archivo Excel con las empresas filtradas
    //===========================================================================================================
	
	public function exportar_empresas_excel_controlador(){
		/*-------------------//-------- VALIDACIONES DE SEGURIDAD --------//-----------------------*/	
		$this->tiempo_inicio = microtime(true);

		if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
			header('Content-Type: application/json; charset=UTF-8');
			echo json_encode(["error" => "Method not allowed"]);
			return;
		}

		if(!$this->verificar_permisos('exportar_empresas')){
			header('Content-Type: application/json; charset=UTF-8');
			echo json_encode([
				"Titulo" => "Sin permisos",
				"Texto" => "No tienes permisos para exportar empresas", 
				"Tipo" => "warning"
			], JSON_UNESCAPED_UNICODE);
			return;
		}

		if(!isset($_POST['csrf_token_export']) || !$this->validar_csrf($_POST['csrf_token_export'], 'listEmpresas')){
			header('Content-Type: application/json; charset=UTF-8');
			echo json_encode([
				"Titulo" => "Token inválido",
				"Texto" => "Token de seguridad inválido o expirado",
				"Tipo" => "warning"
			], JSON_UNESCAPED_UNICODE);
			return;
		}

		if(!$this->verificar_intentos('exportar_empresas', 5, 300)){
			header('Content-Type: application/json; charset=UTF-8');
			echo json_encode([
				"Titulo" => "Demasiados intentos",
				"Texto" => "Has superado el límite de exportaciones. Espera 5 minutos",
				"Tipo" => "warning"
			], JSON_UNESCAPED_UNICODE);
			return;
		}

		/*-------------------//-------- PROCESAR EXPORTACIÓN --------//---------------------------*/

		try {
			// Limpiar filtros recibidos
			$resultados_limpieza = [
				'shareempresa' => $this->limpiar_datos($_POST['shareempresa'] ?? '', 'texto', 'shareempresa'),
				'estadoempresa' => $this->limpiar_datos($_POST['estadoempresa'] ?? '', 'texto', 'estadoempresa')
			];

			// Verificar ataques
			foreach ($resultados_limpieza as $resultado) {
				if (!$resultado['es_seguro']) {
					header('Content-Type: application/json; charset=UTF-8');
					echo json_encode([
						"Titulo" => "Datos no válidos",
						"Texto" => "Los filtros contienen información no permitida",
						"Tipo" => "warning"
					], JSON_UNESCAPED_UNICODE);
					return;
				}
			}

			// Extraer datos limpios
			$filtros = [
				'shareempresa' => $resultados_limpieza['shareempresa']['dato_limpio'],
				'estadoempresa' => $resultados_limpieza['estadoempresa']['dato_limpio']
			];

			// Obtener TODAS las empresas que coincidan con los filtros (sin paginación)
			$empresas_data = $this->obtener_empresas_para_export_modelo($filtros);

			if (empty($empresas_data)) {
				header('Content-Type: application/json; charset=UTF-8');
				echo json_encode([
					"Titulo" => "Sin datos",
					"Texto" => "No hay empresas para exportar con los filtros aplicados",
					"Tipo" => "info"
				], JSON_UNESCAPED_UNICODE);
				return;
			}

			// Generar archivo Excel
			$resultado_excel = $this->generar_excel_empresas($empresas_data, $filtros);

			if ($resultado_excel) {
				// Log de exportación exitosa
				$this->guardar_log('empresas_exportadas_excel', [
					'datos_antes' => [
						'filtros_aplicados' => $filtros,
						'total_empresas' => count($empresas_data)
					],
					'datos_despues' => [
						'archivo_generado' => 'SI',
						'formato' => 'Excel_XLS'
					],
				], 'bajo', 'exito', 'App_empresa');

				$this->normalizar_tiempo_respuesta();
				// El archivo ya se envió en generar_excel_empresas()
				return;

			} else {
				header('Content-Type: application/json; charset=UTF-8');
				echo json_encode([
					"Titulo" => "Error generando Excel",
					"Texto" => "No se pudo crear el archivo Excel",
					"Tipo" => "error"
				], JSON_UNESCAPED_UNICODE);
				return;
			}

		} catch (Exception $e) {
			$this->guardar_log('error_exportar_excel', [
				'datos_antes' => ['accion' => 'exportar_empresas_excel'],
				'datos_despues' => ['error' => $e->getMessage()]
			], 'alto', 'error', 'App_empresa');

			header('Content-Type: application/json; charset=UTF-8');
			echo json_encode([
				"Titulo" => "Error interno",
				"Texto" => "Ocurrió un error al generar la exportación",
				"Tipo" => "error"
			], JSON_UNESCAPED_UNICODE);
			return;
		}
	}

	//===========================================================================================================
	// MÉTODO SIMPLE Y CONFIABLE - SOLO XLS
	//===========================================================================================================

	private function generar_excel_empresas($empresas, $filtros) {
		try {
			// Limpiar cualquier salida previa
			while (ob_get_level()) {
				ob_end_clean();
			}

			// Verificar que no se hayan enviado headers
			if (headers_sent($file, $line)) {
				error_log("Headers ya enviados en {$file}:{$line}");
				throw new Exception("No se puede generar el archivo Excel");
			}

			// BOM para UTF-8 (soporte para caracteres especiales)
			$bom = "\xEF\xBB\xBF";

			// Crear contenido HTML simple para Excel
			$html = $bom . '<!DOCTYPE html>
			<html>
			<head>
				<meta charset="UTF-8">
				<style>
					table { border-collapse: collapse; width: 100%; font-family: Arial, sans-serif; }
					th { background-color: #1B5E20; color: white; padding: 12px 8px; text-align: center; font-weight: bold; border: 1px solid #ddd; }
					td { padding: 8px; border: 1px solid #ddd; text-align: left; }
					tr:nth-child(even) { background-color: #f9f9f9; }
					.texto-centro { text-align: center; }
					.numero { text-align: right; }
					.fecha { text-align: center; }
				</style>
			</head>
			<body>';

			// Título y información del reporte
			$fecha_actual = date('d/m/Y H:i:s');
			$usuario_actual = $_SESSION['UsuarioUsuario'] ?? 'Usuario';

			$html .= '<h2 style="color: #1B5E20; text-align: center;">REPORTE DE EMPRESAS</h2>';
			$html .= '<p style="text-align: center; margin-bottom: 20px;">';
			$html .= '<strong>Fecha de generación:</strong> ' . $fecha_actual . ' | ';
			$html .= '<strong>Generado por:</strong> ' . htmlspecialchars($usuario_actual);
			$html .= '</p>';

			// Mostrar filtros aplicados
			if (!empty($filtros['shareempresa']) || !empty($filtros['estadoempresa'])) {
				$html .= '<p style="background-color: #e3f2fd; padding: 10px; border-radius: 5px;">';
				$html .= '<strong>Filtros aplicados:</strong> ';
				if (!empty($filtros['shareempresa'])) {
					$html .= 'Búsqueda: "' . htmlspecialchars($filtros['shareempresa']) . '" ';
				}
				if (!empty($filtros['estadoempresa'])) {
					$html .= 'Estado: "' . htmlspecialchars($filtros['estadoempresa']) . '" ';
				}
				$html .= '</p>';
			}

			// Tabla de datos
			$html .= '<table>
				<thead>
					<tr>
						<th>Código</th>
						<th>NIT</th>
						<th>Nombre Empresa</th>
						<th>Dirección</th>
						<th>Teléfono</th>
						<th>Email</th>
						<th>Representante Legal</th>
						<th>ID Representante</th>
						<th>Estado</th>
						<th>Sucursales</th>
						<th>Fecha Registro</th>
					</tr>
				</thead>
				<tbody>';

			// Datos de empresas
			foreach ($empresas as $empresa) {
				$fecha_registro = date('d/m/Y', strtotime($empresa['EmpresaFechaRegistro']));

				$html .= '<tr>
					<td class="texto-centro">' . htmlspecialchars($empresa['EmpresaCodigo']) . '</td>
					<td class="numero">' . htmlspecialchars($empresa['EmpresaNit']) . '</td>
					<td>' . htmlspecialchars($empresa['EmpresaNombre']) . '</td>
					<td>' . htmlspecialchars($empresa['EmpresaDireccion']) . '</td>
					<td class="texto-centro">' . htmlspecialchars($empresa['EmpresaTelefono']) . '</td>
					<td>' . htmlspecialchars($empresa['EmpresaEmail']) . '</td>
					<td>' . htmlspecialchars($empresa['EmpresaNomRepresentante']) . '</td>
					<td class="numero">' . htmlspecialchars($empresa['EmpresaIdRepresentante']) . '</td>
					<td class="texto-centro">' . htmlspecialchars($empresa['EmpresaEstado']) . '</td>
					<td class="numero">' . intval($empresa['total_sucursales']) . '</td>
					<td class="fecha">' . $fecha_registro . '</td>
				</tr>';
			}

			$html .= '</tbody></table>';

			// Pie del reporte
			$html .= '<p style="margin-top: 20px; text-align: center; color: #666; font-size: 12px;">';
			$html .= 'Total de empresas exportadas: <strong>' . count($empresas) . '</strong><br>';
			$html .= 'Reporte generado por el Sistema de Gestión Administrativa';
			$html .= '</p>';

			$html .= '</body></html>';

			// Configurar headers para descarga como Excel XLS
			$nombre_archivo = 'Empresas_' . date('Y-m-d_H-i-s');

			// Agregar filtros al nombre del archivo
			if (!empty($filtros['shareempresa'])) {
				$busqueda_limpia = preg_replace('/[^a-zA-Z0-9]/', '', $filtros['shareempresa']);
				$nombre_archivo .= '_' . substr($busqueda_limpia, 0, 15);
			}
			if (!empty($filtros['estadoempresa'])) {
				$nombre_archivo .= '_' . $filtros['estadoempresa'];
			}

			$nombre_archivo .= '.xls';

			// Headers HTTP optimizados
			header('Content-Type: application/vnd.ms-excel; charset=UTF-8');
			header('Content-Disposition: attachment; filename="' . $nombre_archivo . '"');
			header('Cache-Control: max-age=0');
			header('Cache-Control: no-cache, must-revalidate');
			header('Pragma: no-cache');
			header('Expires: 0');

			// Enviar contenido y terminar
			echo $html;
			exit();

		} catch (Exception $e) {
			error_log("Error generando Excel: " . $e->getMessage());

			// Respuesta de error en JSON
			header('Content-Type: application/json');
			http_response_code(500);
			echo json_encode([
				'success' => false,
				'Texto' => 'Error al generar el archivo Excel: ' . $e->getMessage()
			]);
			exit();
		}
	}

	//===========================================================================================================
	// FUNCIÓN AUXILIAR PARA LIMPIAR NOMBRE DE ARCHIVO
	//===========================================================================================================

	private function limpiarNombreArchivo($texto, $maxLength = 20) {
		// Remover caracteres especiales y espacios
		$limpio = preg_replace('/[^a-zA-Z0-9\-_]/', '', $texto);

		// Truncar si es muy largo
		if (strlen($limpio) > $maxLength) {
			$limpio = substr($limpio, 0, $maxLength);
		}

		return $limpio;
	}
	
	//===========================================================================================================
	// LISTAR SUCURSALES DE UNA EMPRESA
	// Función para obtener todas las sucursales de una empresa específica
	//===========================================================================================================

	public function listar_sucursales_empresa_controlador(){
		/*-------------------//-------- VALIDACIONES DE SEGURIDAD --------//-----------------------*/	
		$this->tiempo_inicio = microtime(true);

		/************ VALIDACION 1: Método POST *****************************************************************/
		if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
			$this->guardar_log('metodo_http_invalido_listar_sucursales', [
				'datos_antes' => ['metodo_recibido' => $_SERVER['REQUEST_METHOD']],
				'datos_despues' => ['accion' => 'rechazado']
			], 'alto', 'bloqueado', 'App_empresa');

			return json_encode(["error" => "Method not allowed"]);
		}

		/************ VALIDACION 2: Verificar permisos **********************************************************/
		if(!$this->verificar_permisos('listar_sucursales')){
			$this->guardar_log('sin_permisos_listar_sucursales', [
				'datos_antes' => ['usuario' => $_SESSION['CodigoUsuario']],
				'datos_despues' => ['accion' => 'bloqueado']
			], 'alto', 'bloqueado', 'App_empresa');

			$this->normalizar_tiempo_respuesta();
			return json_encode([
				"Titulo" => "Sin permisos",
				"Texto" => "No tienes permisos para ver sucursales", 
				"Tipo" => "warning"
			], JSON_UNESCAPED_UNICODE);
		}

		/************ VALIDACION 3: Validar token CSRF **********************************************************/
		if(!isset($_POST['csrf_token_sucursales']) || !$this->validar_csrf($_POST['csrf_token_sucursales'], 'listEmpresas')){
			$this->guardar_log('csrf_token_invalido_listar_sucursales', [
				'datos_antes' => ['empresa_id' => $_POST['empresa_id'] ?? 'no_definido'],
				'datos_despues' => ['accion' => 'bloqueado']
			], 'alto', 'bloqueado', 'App_empresa');

			$this->normalizar_tiempo_respuesta();
			return json_encode([
				"Titulo" => "Token inválido",
				"Texto" => "Token de seguridad inválido o expirado",
				"Tipo" => "warning"
			], JSON_UNESCAPED_UNICODE);
		}

		/*-------------------//-------- LIMPIEZA Y VALIDACIÓN DE DATOS --------//---------------------------*/

		/************ SEPARAR código y token usando el separador encriptado *************************************/	
		$codigo_encriptado = $_POST['empresa_id'] ?? '';

		// Intentar separar primero
		$data_separada = $this->separar_codigo_con_token($codigo_encriptado, 'separador_empresa');

		if (!$data_separada) {
			return json_encode([
				"Titulo" => "Código inválido",
				"Texto" => "El código de empresa no es válido o está corrupto",
				"Tipo" => "warning"
			], JSON_UNESCAPED_UNICODE);
		}

		$codigo_real = $data_separada['codigo'];
		$token_encriptado = $data_separada['token_encriptado'];

		/************ VALIDAR token CSRF del listado ***********************************************************/
		if (!$this->validar_csrf($token_encriptado, 'listEmpresas')) {
			$this->guardar_log('csrf_token_invalido_empresa_sucursales', [
				'datos_antes' => ['codigo_empresa' => $codigo_real],
				'datos_despues' => ['accion' => 'bloqueado']
			], 'alto', 'bloqueado', 'App_empresa');

			$this->normalizar_tiempo_respuesta();
			return json_encode([
				"Titulo" => "Token inválido",
				"Texto" => "Token de seguridad inválido o expirado",
				"Tipo" => "warning"
			], JSON_UNESCAPED_UNICODE);
		}

		// Limpiar datos
		$resultados_limpieza = [
			'empresa_id' => $this->limpiar_datos($codigo_real, 'numero', 'empresa_id'),
			'filtro_nombre' => $this->limpiar_datos($_POST['filtro_nombre'] ?? '', 'texto', 'filtro_nombre'),
			'filtro_estado' => $this->limpiar_datos($_POST['filtro_estado'] ?? '', 'texto', 'filtro_estado')
		];

		// Verificar ataques
		foreach ($resultados_limpieza as $resultado) {
			if (!$resultado['es_seguro']) {
				$this->normalizar_tiempo_respuesta();
				return json_encode([
					"Titulo" => "Datos no válidos",
					"Texto" => "Los filtros contienen información no permitida",
					"Tipo" => "warning"
				], JSON_UNESCAPED_UNICODE);
			}
		}

		// Extraer datos limpios
		$empresa_id = $resultados_limpieza['empresa_id']['dato_limpio'];
		$filtro_nombre = $resultados_limpieza['filtro_nombre']['dato_limpio'];
		$filtro_estado = $resultados_limpieza['filtro_estado']['dato_limpio'];

		/*-------------------//-------- PROCESAR SOLICITUD --------//---------------------------*/

		try {
			// Verificar que la empresa existe
			$empresa = $this->obtener_empresa_completa_modelo($empresa_id);
			if (!$empresa) {
				$this->normalizar_tiempo_respuesta();
				return json_encode([
					"Titulo" => "Empresa no encontrada",
					"Texto" => "La empresa no existe o no está disponible",
					"Tipo" => "warning"
				], JSON_UNESCAPED_UNICODE);
			}

			// Obtener sucursales de la empresa
			$sucursales_data = $this->listar_sucursales_empresa_modelo($empresa_id, $filtro_nombre, $filtro_estado);

			// Generar HTML de las sucursales
			$html_sucursales = $this->generar_html_sucursales($sucursales_data);

			// Log de consulta exitosa
			$this->guardar_log('sucursales_listadas_exitosamente', [
				'datos_antes' => [
					'empresa_id' => $empresa_id,
					'filtros_aplicados' => [
						'nombre' => $filtro_nombre,
						'estado' => $filtro_estado
					]
				],
				'datos_despues' => [
					'sucursales_encontradas' => count($sucursales_data),
					'empresa_codigo' => $empresa['EmpresaCodigo']
				]
			], 'bajo', 'exito', 'App_empresa');

			$this->normalizar_tiempo_respuesta();
			return json_encode([
				"status" => "success",
				"html_sucursales" => $html_sucursales,
				"total_sucursales" => count($sucursales_data)
			], JSON_UNESCAPED_UNICODE);

		} catch (Exception $e) {
			$this->guardar_log('error_listar_sucursales', [
				'datos_antes' => ['empresa_id' => $empresa_id],
				'datos_despues' => ['error' => $e->getMessage()]
			], 'alto', 'error', 'App_empresa');

			$this->normalizar_tiempo_respuesta();
			return json_encode([
				"Titulo" => "Error interno",
				"Texto" => "Ocurrió un error al cargar las sucursales",
				"Tipo" => "error"
			], JSON_UNESCAPED_UNICODE);
		}
	}

	//===========================================================================================================
	// GENERAR HTML DE SUCURSALES
	// Función para generar el HTML de la lista de sucursales
	//===========================================================================================================

	private function generar_html_sucursales($sucursales) {
		if (empty($sucursales)) {
			return '
				<div class="text-center py-5">
					<i class="bi bi-geo-alt fa-3x text-muted mb-3"></i>
					<h6 class="text-muted">No hay sucursales registradas</h6>
					<p class="text-muted mb-3">Esta empresa aún no tiene sucursales asociadas</p>
					<button class="btn btn-primary" onclick="mostrarModalNuevaSucursal()">
						<i class="bi bi-plus me-1"></i>
						Crear Primera Sucursal
					</button>
				</div>
			';
		}

		$html = '<div class="list-group list-group-flush">';

		$separador = $this->encryption_deterministico('1n49n', 'separador_sucursal');

		foreach ($sucursales as $sucursal) {
			// Generar código encriptado para la sucursal
			$codigo_encriptado_sucursal = $_SESSION['csrf_listEmpresas'] . $separador . $this->encryption($sucursal['SucursalId']);
			
			
			// Generar dropdown de estados (en lugar de badge estático)
				$opciones_estado = [
					'Activo' => ['color' => 'success', 'icono' => 'check-circle'],
					'Inactivo' => ['color' => 'warning', 'icono' => 'pause-circle'],
					'Suspendido' => ['color' => 'danger', 'icono' => 'x-circle'],
					'Eliminado' => ['color' => 'secondary', 'icono' => 'trash']
				];

				$dropdown_estados = '<div class="dropdown">
					<button class="btn btn-outline-' . $opciones_estado[$sucursal['SucursalEstado']]['color'] . ' btn-sm dropdown-toggle estado-dropdown" 
							type="button" 
							data-bs-toggle="dropdown" 
							data-sucursal-id="' . $codigo_encriptado_sucursal . '"
							data-estado-actual="' . $sucursal['SucursalEstado'] . '"
							data-sucursal-nombre="' . htmlspecialchars($sucursal['SucursalNombre']) . '">
						<i class="bi bi-' . $opciones_estado[$sucursal['SucursalEstado']]['icono'] . ' me-1"></i>
						' . $sucursal['SucursalEstado'] . '
					</button>
					<ul class="dropdown-menu">';

				// Agregar opciones (excepto la actual y "Eliminado")
				foreach ($opciones_estado as $estado => $config) {
					if ($estado !== $sucursal['SucursalEstado'] && $estado !== 'Eliminado') {
						$dropdown_estados .= '
							<li>
								<a class="dropdown-item cambiar-estado-sucursal" 
								   href="#" 
								   data-nuevo-estado="' . $estado . '"
								   data-sucursal-id="' . $codigo_encriptado_sucursal . '"
								   data-sucursal-nombre="' . htmlspecialchars($sucursal['SucursalNombre']) . '">
									<i class="bi bi-' . $config['icono'] . ' me-2 text-' . $config['color'] . '"></i>
									Cambiar a ' . $estado . '
								</a>
							</li>';
					}
				}

				$dropdown_estados .= '
					</ul>
				</div>';

           $texto_sedes = $sucursal['total_sedes'] == 1 ? 'sede' : 'sedes';
                

			$html .= '
				<div class="list-group-item list-group-item-action">
					<div class="d-flex w-100 justify-content-between align-items-start">
						<div class="flex-grow-1">
							<div class="d-flex justify-content-between align-items-center mb-2">
								<h6 class="mb-0">
									<i class="bi bi-geo-alt me-2 text-primary"></i>
									' . htmlspecialchars($sucursal['SucursalNombre']) . '
									<span class="badge bg-info ms-2" title="Sedes activas">
										<i class="bi bi-buildings me-1"></i>
										' . ($sucursal['total_sedes'] ?? 0) . ' '. $texto_sedes. ' 
									</span>
								</h6>
								' . $dropdown_estados . '
								
							</div>

							<div class="row">
								<div class="col-md-6">
									<small class="text-muted d-block">
										<i class="bi bi-hash me-1"></i>
										Código: <span class="text-dark">' . htmlspecialchars($sucursal['SucursalCodigo']) . '</span>
									</small>
									<small class="text-muted d-block">
										<i class="bi bi-card-text me-1"></i>
										NIT: <span class="text-dark">' . htmlspecialchars($sucursal['SucursalNit']) . '</span>
									</small>
									<small class="text-muted d-block">
										<i class="bi bi-telephone me-1"></i>
										Teléfono: <span class="text-dark">' . htmlspecialchars($sucursal['SucursalTelefono']) . '</span>
									</small>
								</div>
								<div class="col-md-6">
									<small class="text-muted d-block">
										<i class="bi bi-envelope me-1"></i>
										Email: <span class="text-dark">' . htmlspecialchars($sucursal['SucursalEmail']) . '</span>
									</small>
									<small class="text-muted d-block">
										<i class="bi bi-geo-alt-fill me-1"></i>
										Dirección: <span class="text-dark">' . htmlspecialchars($sucursal['SucursalDireccion']) . '</span>
									</small>
									<small class="text-muted d-block">
										<i class="bi bi-person me-1"></i>
										Representante: <span class="text-dark">' . htmlspecialchars($sucursal['SucursalNomRepresentante']) . '</span>
									</small>
								</div>
							</div>
						</div>

						<div class="ms-3">
							<div class="btn-group-vertical btn-group-sm" role="group">';
								
								

			if($sucursal['SucursalEstado'] !== 'Eliminado') {
				$html .= '		
								<button class="btn btn-outline-primary" onclick="verSucursal(\'' . $codigo_encriptado_sucursal . '\')" title="Ver/Editar">
									<i class="bi bi-pencil-square"></i>
								</button>
								<button class="btn btn-outline-info" onclick="verSedesSucursal(\'' . $codigo_encriptado_sucursal . '\', \'' . htmlspecialchars($sucursal['SucursalNombre']) . '\')" title="Ver Sedes">
									<i class="bi bi-buildings"></i>
								</button>
								<button class="btn btn-outline-danger" onclick="eliminarSucursal(\'' . $codigo_encriptado_sucursal . '\', \'' . htmlspecialchars($sucursal['SucursalNombre']) . '\')" title="Eliminar">
									<i class="bi bi-trash"></i>
								</button>';
			}

			$html .= '
							</div>
						</div>
					</div>
				</div>
			';
		}

		$html .= '</div>';
		return $html;
	}
	
	//===========================================================================================================
	// CONTAR SUCURSALES DE UNA EMPRESA (SOLO CONTADOR)
	// Función ligera para obtener solo el número de sucursales sin cargar toda la lista
	//===========================================================================================================

	public function contar_sucursales_empresa_controlador(){
		$this->tiempo_inicio = microtime(true);

		if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
			return json_encode(["error" => "Method not allowed"]);
		}

		if(!$this->verificar_permisos('listar_sucursales')){
			$this->normalizar_tiempo_respuesta();
			return json_encode([
				"status" => "error",
				"Titulo" => "Sin permisos",
				"Texto" => "No tienes permisos para ver sucursales", 
				"Tipo" => "warning"
			], JSON_UNESCAPED_UNICODE);
		}

		if(!isset($_POST['csrf_token_contador']) || !$this->validar_csrf($_POST['csrf_token_contador'], 'listEmpresas')){
			$this->normalizar_tiempo_respuesta();
			return json_encode([
				"status" => "error",
				"Titulo" => "Token inválido",
				"Texto" => "Token de seguridad inválido",
				"Tipo" => "warning"
			], JSON_UNESCAPED_UNICODE);
		}

		// Separar código y token
		$codigo_encriptado = $_POST['empresa_id'] ?? '';
		$data_separada = $this->separar_codigo_con_token($codigo_encriptado, 'separador_empresa');

		if (!$data_separada) {
			return json_encode([
				"status" => "error",
				"Titulo" => "Código inválido",
				"Texto" => "El código de empresa no es válido",
				"Tipo" => "warning"
			], JSON_UNESCAPED_UNICODE);
		}

		$codigo_real = $data_separada['codigo'];
		$token_encriptado = $data_separada['token_encriptado'];

		if (!$this->validar_csrf($token_encriptado, 'listEmpresas')) {
			$this->normalizar_tiempo_respuesta();
			return json_encode([
				"status" => "error",
				"Titulo" => "Token inválido",
				"Texto" => "Token de seguridad inválido",
				"Tipo" => "warning"
			], JSON_UNESCAPED_UNICODE);
		}

		// Limpiar empresa ID
		$resultado_limpieza = $this->limpiar_datos($codigo_real, 'numero', 'empresa_id');

		if (!$resultado_limpieza['es_seguro']) {
			$this->normalizar_tiempo_respuesta();
			return json_encode([
				"status" => "error",
				"Titulo" => "ID inválido",
				"Texto" => "El ID de empresa no es válido",
				"Tipo" => "warning"
			], JSON_UNESCAPED_UNICODE);
		}

		$empresa_id = $resultado_limpieza['dato_limpio'];

		try {
			// Obtener solo el contador
			$estadisticas = $this->obtener_estadisticas_sucursales_empresa($empresa_id);

			$this->normalizar_tiempo_respuesta();
			return json_encode([
				"status" => "success",
				"total_sucursales" => $estadisticas['total_sucursales']
			], JSON_UNESCAPED_UNICODE);

		} catch (Exception $e) {
			$this->normalizar_tiempo_respuesta();
			return json_encode([
				"status" => "error",
				"Titulo" => "Error interno",
				"Texto" => "Ocurrió un error al contar las sucursales",
				"Tipo" => "error"
			], JSON_UNESCAPED_UNICODE);
		}
	}

	//===========================================================================================================
	// REGISTRAR NUEVA SUCURSAL
	// Función completa para registrar una nueva sucursal con todas las validaciones
	//===========================================================================================================

	public function registrar_sucursal_controlador(){
		/*-------------------//-------- PASO 1 LIMPIEZA, VALIDACIONES Y SEGURIDAD --------//-----------------------*/	
		/************ Marcar inicio del tiempo para normalizar respuestas *******************************************/
		$this->tiempo_inicio = microtime(true);

		/************ VALIDACION 1: Método POST *****************************************************************/
		if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
			$this->guardar_log('metodo_http_invalido_registrar_sucursal', [
				'datos_antes' => ['metodo_recibido' => $_SERVER['REQUEST_METHOD']],
				'datos_despues' => ['accion' => 'rechazado']
			], 'alto', 'bloqueado', 'App_empresa');

			http_response_code(405);
			return json_encode(["error" => "Method not allowed"]);
		}

		/************ VALIDACION 2: Detección de bots ***********************************************************/
		$numero_campos = count($_POST);
		$tiempo_por_campo = 2; // segundos mínimos por campo
		$umbral_minimo = ($numero_campos - 1) * $tiempo_por_campo;
		$analisis_bot = $this->es_bot_sospechoso($umbral_minimo, $numero_campos);

		if ($analisis_bot['es_bot']) {
			$this->normalizar_tiempo_respuesta();
			return json_encode([
				"Alerta" => "simple",
				"Titulo" => "Actividad Inapropiada",
				"Texto" => "Actividad sospechosa detectada", 
				"Tipo" => "warning"
			], JSON_UNESCAPED_UNICODE);
		}

		/************ VALIDACION 3: Verificar token CSRF ********************************************************/
		if(!isset($_POST['csrf_token']) || !$this->validar_csrf($_POST['csrf_token'],'formNuevaSucursal')){
			$this->guardar_log('csrf_token_invalido_registrar_sucursal', [
				'datos_antes' => ['Token_creado'=>'Diferente al recibido'],
				'datos_despues' => [
					'formulario' => 'formNuevaSucursal',
					'token_recibido_hash' => isset($_POST['csrf_token']) ? hash('sha256', $_POST['csrf_token']) : 'no_enviado',
					'session_id' => session_id(),
					'CodigoUsuario' => $_SESSION['CodigoUsuario'] ?? 'no_definido',
					'UsuarioId' => $_SESSION['UsuarioId'] ?? 'no_definido'
				]
			], 'alto', 'bloqueado', 'App_empresa');

			$this->normalizar_tiempo_respuesta();
			return json_encode([
				"Alerta" => "simple",
				"Titulo" => "Token inválido",
				"Texto" => "Token de seguridad inválido. Recarga la página e intenta nuevamente", 
				"Tipo" => "warning"
			], JSON_UNESCAPED_UNICODE);
		}

		/************ VALIDACION 4: Verificar permisos **********************************************************/
		if(!$this->verificar_permisos('crear_sucursales')){
			$this->guardar_log('sin_permisos_crear_sucursal', [
				'datos_antes' => ['usuario'=>'sin permisos'],
				'datos_despues' => ['CodigoUsuario' => $_SESSION['CodigoUsuario'], 'UsuarioId' => $_SESSION['UsuarioId']]
			], 'alto', 'bloqueado', 'App_empresa');

			$this->normalizar_tiempo_respuesta();
			
			return json_encode([
				"Alerta" => "simple",
				"Titulo" => "Sin permisos",
				"Texto" => "No tienes permisos para crear sucursales", 
				"Tipo" => "warning"
			], JSON_UNESCAPED_UNICODE);

			
		}

		/************ VALIDACION 5: Límite de intentos **********************************************************/
		if(!$this->verificar_intentos('registro_sucursal', 3, 300)){
			$this->guardar_log('registro_sucursal_limite_intentos', [
				'datos_antes' => ['intentos'=>'Usuario alcanzó límite'],
				'datos_despues' => ['intentos'=>'Bloqueado por 5 minutos', 'CodigoUsuario' => $_SESSION['CodigoUsuario']]
			], 'alto', 'bloqueado', 'App_empresa');

			$this->normalizar_tiempo_respuesta();
			return json_encode([
				"Alerta" => "simple",
				"Titulo" => "Demasiados intentos",
				"Texto" => "Has superado el límite de intentos. Espera 5 minutos",
				"Tipo" => "warning"
			], JSON_UNESCAPED_UNICODE);
		}

		/************ VALIDACION 6: Limpiar los datos recibidos *************************************************/ 
		$empresa_id_encriptado = $_POST['empresa-id'] ?? '';
		$empresa_id_desencriptado = $this->decryption($empresa_id_encriptado);
		
		
		$resultados_limpieza = [
			'SucursalNit' => $this->limpiar_datos($_POST['sucursal-nit'], 'numero', 'sucursal-nit'),
			'SucursalNombre' => $this->limpiar_datos($_POST['sucursal-nombre'], 'texto', 'sucursal-nombre'),
			'SucursalDireccion' => $this->limpiar_datos($_POST['sucursal-direccion'], 'texto','sucursal-direccion'),
			'SucursalTelefono' => $this->limpiar_datos($_POST['sucursal-telefono'], 'texto','sucursal-telefono'),
			'SucursalEmail' => $this->limpiar_datos($_POST['sucursal-email'], 'email', 'sucursal-email'),
			'SucursalIdRepresentante' => $this->limpiar_datos($_POST['sucursal-id-representante'], 'numero', 'sucursal-id-representante'),
			'SucursalNomRepresentante' => $this->limpiar_datos($_POST['sucursal-nom-representante'], 'texto', 'sucursal-nom-representante'),
			'SucursalIdEmpresa' => $this->limpiar_datos($empresa_id_desencriptado, 'numero', 'empresa-id')
		];

		/************ VALIDACION 7: Verificar ataques ***********************************************************/
		$hay_ataques = false;
		$ataques_por_campo = [];

		foreach ($resultados_limpieza as $campo => $resultado) {
			if (!$resultado['es_seguro']) {
				$hay_ataques = true;
				$ataques_por_campo[$campo] = $resultado['ataques_detectados'];
			}  
		}

		if ($hay_ataques) {
			$nivel_riesgo_maximo = 'bajo';
			foreach ($resultados_limpieza as $resultado) {
				if ($resultado['nivel_riesgo'] === 'alto') {
					$nivel_riesgo_maximo = 'alto';
					break;
				} elseif ($resultado['nivel_riesgo'] === 'medio' && $nivel_riesgo_maximo !== 'alto') {
					$nivel_riesgo_maximo = 'medio';
				}
			}
			/*-*-*-*-*-* registro logs de seguimiento y auditoria *-*-*-*-*-*/
			$this->guardar_log('formulario_sucursal_rechazado_por_ataques', [
				'datos_antes' => [
					'campos_con_ataques' => array_keys($ataques_por_campo),
					'total_campos_afectados' => count($ataques_por_campo),
					'resumen_ataques' => $ataques_por_campo
				],
				'datos_despues' => ['accion_tomada' => 'formulario_rechazado_completamente']
			], $nivel_riesgo_maximo, 'rechazado', 'seguridad'); 

			/*-*-*-*-*-* Normalizamos el tiempo de respuesta *-*-*-*-*-*/           
            $this->normalizar_tiempo_respuesta();
			/*-*-*-*-*-* Termino función para registro de empresa *-*-*-*-*-*/
			return json_encode([
				"Titulo" => "Datos no válidos",
				"Texto" => "Los datos enviados contienen información no permitida.",
				"Tipo" => "warning"
			], JSON_UNESCAPED_UNICODE);
		}
		
		/************ VALIDACION 8: Extraer datos limpios *******************************************************/
		$datos_sucursal = [];
		foreach ($resultados_limpieza as $campo => $resultado) {
			$datos_sucursal[$campo] = $resultado['dato_limpio'];
		}

		/************ VALIDACION 9: Verificar campos obligatorios ***********************************************/
		$campos_excluir = ['SucursalId', 'SucursalCodigo', 'SucursalFechaRegistro', 'SucursalFechaActualizacion', 'SucursalEstado'];

		$reglas_personalizadas = [
			'SucursalNit' => ['min_caracteres' => 8],
			'SucursalTelefono' => ['min_caracteres' => 7],
			'SucursalNomRepresentante' => ['solo_letras' => true]
		];

		$errores = $this->validar_completo($datos_sucursal, 'App_empresa_sucursal', $campos_excluir, $reglas_personalizadas);

		if(!empty($errores)){
			$mensaje_error = "Errores encontrados:\n";
			foreach($errores as $campo => $errores_campo){
				$mensaje_error .= "- " . implode(", ", $errores_campo) . "\n";
			}

			$this->normalizar_tiempo_respuesta();
			return json_encode([
				"Alerta" => "simple",
				"Titulo" => "Datos incorrectos",
				"Texto" => $mensaje_error,
				"Tipo" => "warning"
			], JSON_UNESCAPED_UNICODE);
		}

		/************ VALIDACION 10: Verificar que la empresa existe y está activa *****************************/
		$empresa = $this->obtener_empresa_completa_modelo($datos_sucursal['SucursalIdEmpresa']);
		if (!$empresa) {
			$this->normalizar_tiempo_respuesta();
			return json_encode([
				"Alerta" => "simple",
				"Titulo" => "Empresa no encontrada",
				"Texto" => "La empresa seleccionada no existe o no está disponible", 
				"Tipo" => "warning"
			], JSON_UNESCAPED_UNICODE);
		}

		if ($empresa['EmpresaEstado'] !== 'Activo') {
			$this->normalizar_tiempo_respuesta();
			return json_encode([
				"Alerta" => "simple",
				"Titulo" => "Empresa no esta activa",
				"Texto" => "No se pueden crear sucursales en empresas inactivas", 
				"Tipo" => "warning"
			], JSON_UNESCAPED_UNICODE);
		}

		/************ VALIDACION 11: Verificar NIT duplicado ****************************************************/
		if($this->verificar_nit_sucursal_duplicado($datos_sucursal['SucursalNit'])){
			$this->normalizar_tiempo_respuesta();
			return json_encode([
				"Alerta" => "simple",
				"Titulo" => "NIT duplicado",
				"Texto" => "Ya existe una sucursal registrada con este NIT", 
				"Tipo" => "warning"
			], JSON_UNESCAPED_UNICODE);
		}

		/************ VALIDACION 12: Verificar email duplicado *************************************************/
		if($this->verificar_email_sucursal_duplicado($datos_sucursal['SucursalEmail'])){
			$this->normalizar_tiempo_respuesta();
			return json_encode([
				"Alerta" => "simple",
				"Titulo" => "Email duplicado",
				"Texto" => "Ya existe una sucursal registrada con este email",
				"Tipo" => "warning"
			], JSON_UNESCAPED_UNICODE);
		}

		/*-------------------//-------- PASO 2: REGISTRO DE SUCURSAL EN BD --------//---------------------------*/		

		/************ COMPLEMENTO 1: Generar código para la sucursal *******************************************/
		$sql = "SELECT COUNT(SucursalId) FROM App_empresa_sucursal";
		$total_sucursales = $this->ejecutar_consulta_segura($sql, []);
		$total_sucursales = ($total_sucursales->fetchColumn()) + 1;
		
		if($total_sucursales > 0){
			$longitud = 10 - strlen($total_sucursales);
		}else{
			$longitud = 10;
			$total_sucursales = "_";
		}

		$codigo_sucursal = $this->generar_codigo_aleatorio('SU', $longitud, '');
		$codigo_sucursal = $codigo_sucursal . $total_sucursales;

		/************ Consolidar datos finales **************************************************************/
		$datos_finales = [
			'codigo' => $codigo_sucursal,
			'nit' => $datos_sucursal['SucursalNit'],
			'nombre' => $datos_sucursal['SucursalNombre'],
			'direccion' => $datos_sucursal['SucursalDireccion'],
			'telefono' => $datos_sucursal['SucursalTelefono'],
			'email' => $datos_sucursal['SucursalEmail'],
			'id_representante' => $datos_sucursal['SucursalIdRepresentante'],
			'nom_representante' => $datos_sucursal['SucursalNomRepresentante'],
			'empresa_id' => $datos_sucursal['SucursalIdEmpresa'],
			'fecha_registro' => date("Y-m-d H:i:s"),
			'fecha_actualizacion' => date("Y-m-d H:i:s")
		];

		/************ Enviar registro de sucursal ***********************************************************/
		$resultado = $this->registrar_sucursal_modelo($datos_finales);

		if($resultado){
			$this->guardar_log('sucursal_registrada', [
				'datos_antes'=>['antes'=>'sin información'],
				'datos_despues'=> [
					'resultado'=> 'sucursal creada',
					'codigo_sucursal' => $codigo_sucursal,
					'nit' => $datos_sucursal['SucursalNit'],
					'nombre' => $datos_sucursal['SucursalNombre'],
					'empresa_id' => $datos_sucursal['SucursalIdEmpresa'],
					'empresa_nombre' => $empresa['EmpresaNombre']
				]
			], 'medio', 'exito', 'App_empresa');

			$this->eliminar_token_csrf('formNuevaSucursal');

			$this->normalizar_tiempo_respuesta();
			return json_encode([
				"Alerta" => "recargar",
				"Titulo" => "Sucursal registrada",
				"Texto" => "La sucursal se ha registrado exitosamente con el código: ".$codigo_sucursal,
				"Tipo" => "success"
			], JSON_UNESCAPED_UNICODE);

		} else {
			$this->guardar_log('sucursal_registrada_fallo', [
				'datos_antes'=>['antes'=>'sin información'],
				'datos_despues'=> [
					'resultado'=> 'sucursal no creada',
					'codigo_sucursal' => $codigo_sucursal,
					'nit' => $datos_sucursal['SucursalNit'],
					'nombre' => $datos_sucursal['SucursalNombre']
				]
			], 'medio', 'error', 'App_empresa');

			$this->normalizar_tiempo_respuesta();
			return json_encode([
				"Alerta" => "simple",
				"Titulo" => "Error",
				"Texto" => "No se pudo registrar la sucursal. Intenta nuevamente",
				"Tipo" => "error"
			], JSON_UNESCAPED_UNICODE);
		}
	}
	
	//===========================================================================================================
	// OBTENER DATOS DE UNA SUCURSAL ESPECÍFICA
	// Función para obtener todos los datos de una sucursal por su ID
	//===========================================================================================================

	public function obtener_sucursal_controlador(){
		/*-------------------//-------- PASO 1 LIMPIEZA, VALIDACIONES Y SEGURIDAD --------//-----------------------*/	
		/************ Marcar inicio del tiempo para normalizar respuestas *******************************************/
		$this->tiempo_inicio = microtime(true);

		/************ VALIDACION 1: Método POST *****************************************************************/
		if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
			$this->guardar_log('metodo_http_invalido_obtener_sucursal', [
				'datos_antes' => ['metodo_recibido' => $_SERVER['REQUEST_METHOD']],
				'datos_despues' => ['accion' => 'rechazado']
			], 'alto', 'bloqueado', 'App_empresa');

			return json_encode(["error" => "Method not allowed"]);
		}

		/************ VALIDACION 2: Verificar permisos **********************************************************/
		if(!$this->verificar_permisos('ver_sucursales')){
			$this->guardar_log('sin_permisos_ver_sucursal', [
				'datos_antes' => ['usuario' => $_SESSION['CodigoUsuario']],
				'datos_despues' => ['accion' => 'bloqueado']
			], 'alto', 'bloqueado', 'App_empresa');

			$this->normalizar_tiempo_respuesta();
			return json_encode([
				"Titulo" => "Sin permisos",
				"Texto" => "No tienes permisos para ver sucursales", 
				"Tipo" => "warning"
			], JSON_UNESCAPED_UNICODE);
		}

		/************ VALIDACION 3: Validar token CSRF **********************************************************/
		if(!isset($_POST['csrf_token_obtener_sucursal']) || !$this->validar_csrf($_POST['csrf_token_obtener_sucursal'], 'listEmpresas')){
			$this->guardar_log('csrf_token_invalido_obtener_sucursal', [
				'datos_antes' => ['sucursal_id' => $_POST['sucursal_id'] ?? 'no_definido'],
				'datos_despues' => ['accion' => 'bloqueado']
			], 'alto', 'bloqueado', 'App_empresa');

			$this->normalizar_tiempo_respuesta();
			return json_encode([
				"Titulo" => "Token inválido",
				"Texto" => "Token de seguridad inválido o expirado",
				"Tipo" => "warning"
			], JSON_UNESCAPED_UNICODE);
		}

		/************ SEPARAR código y token usando el separador encriptado *************************************/	
		$codigo_encriptado = $_POST['sucursal_id'] ?? '';

		// Intentar separar primero
		$data_separada = $this->separar_codigo_con_token($codigo_encriptado, 'separador_sucursal');

		if (!$data_separada) {
			return json_encode([
				"Titulo" => "Código inválido",
				"Texto" => "El código de sucursal no es válido o está corrupto",
				"Tipo" => "warning"
			], JSON_UNESCAPED_UNICODE);
		}

		$codigo_real = $data_separada['codigo'];
		$token_encriptado = $data_separada['token_encriptado'];

		/************ VALIDAR token CSRF del listado ***********************************************************/
		if (!$this->validar_csrf($token_encriptado, 'listEmpresas')) {
			$this->guardar_log('csrf_token_invalido_sucursal_separado', [
				'datos_antes' => ['codigo_sucursal' => $codigo_real],
				'datos_despues' => ['accion' => 'bloqueado']
			], 'alto', 'bloqueado', 'App_empresa');

			$this->normalizar_tiempo_respuesta();
			return json_encode([
				"Titulo" => "Token inválido",
				"Texto" => "Token de seguridad inválido o expirado",
				"Tipo" => "warning"
			], JSON_UNESCAPED_UNICODE);
		}

		/************ VALIDACION 4: Limpiar y validar ID de sucursal *********************************************/
		$resultado_limpieza = $this->limpiar_datos($codigo_real, 'numero', 'sucursal_id');

		if (!$resultado_limpieza['es_seguro']) {
			$this->normalizar_tiempo_respuesta();
			return json_encode([
				"Titulo" => "ID inválido",
				"Texto" => "El ID de sucursal no es válido",
				"Tipo" => "warning"
			], JSON_UNESCAPED_UNICODE);
		}

		$sucursal_id = $resultado_limpieza['dato_limpio'];

		/*-------------------//-------- PASO 2: OBTENER SUCURSAL --------//------------------------------------*/

		try {
			/************ Obtener datos de la sucursal ***********************************************************/
			$sucursal = $this->obtener_sucursal_por_id($sucursal_id);

			if (!$sucursal) {
				$this->normalizar_tiempo_respuesta();
				return json_encode([
					"Titulo" => "Sucursal no encontrada",
					"Texto" => "La sucursal no existe o no está disponible",
					"Tipo" => "warning"
				], JSON_UNESCAPED_UNICODE);
			}

			/*-*-*-*-*-* SEGURIDAD: Generar token específico usando sistema genérico *-*-*-*-*-*/
			$datos_extra = [
				'codigo_sucursal' => $sucursal['SucursalCodigo'],
				'nombre_sucursal' => $sucursal['SucursalNombre'],
				'empresa_id' => $sucursal['SucursalIdEmpresa']
			];
			$token_sucursal_especifico = $this->generar_token_entidad_especifico('sucursal', $sucursal_id, 1800, $datos_extra);

			/*-*-*-*-*-* SEGURIDAD: Encriptar el ID antes de enviarlo al frontend *-*-*-*-*-*/
			$sucursal['SucursalId'] = $this->encryption($sucursal_id);

			/*-*-*-*-*-* SEGURIDAD: Agregar el token específico a la respuesta *-*-*-*-*-*/
			$sucursal['TokenSucursalEspecifico'] = $token_sucursal_especifico;

			/*-*-*-*-*-* Log de consulta exitosa *-*-*-*-*-*/
			$this->guardar_log('sucursal_consultada', [
				'datos_antes' => [
					'sucursal_id_solicitado' => $sucursal_id
				],
				'datos_despues' => [
					'sucursal_encontrada' => $sucursal['SucursalCodigo'],
					'nombre' => $sucursal['SucursalNombre'],
					'empresa' => $sucursal['EmpresaNombre'],
					'id_encriptado' => 'SI',
					'token_sucursal_generado' => 'SI'					
				],
			], 'bajo', 'exito', 'App_empresa');

			$this->normalizar_tiempo_respuesta();
			return json_encode([
				"status" => "success",
				"sucursal" => $sucursal
			], JSON_UNESCAPED_UNICODE);

		} catch (Exception $e) {
			$this->guardar_log('error_obtener_sucursal', [
				'datos_antes' => ['sucursal_id' => $sucursal_id],
				'datos_despues' => ['error' => $e->getMessage()]
			], 'alto', 'error', 'App_empresa');

			$this->normalizar_tiempo_respuesta();
			return json_encode([
				"Titulo" => "Error interno",
				"Texto" => "Ocurrió un error al obtener la sucursal",
				"Tipo" => "error"
			], JSON_UNESCAPED_UNICODE);
		}
	}
	
	//===========================================================================================================
	// ACTUALIZAR SUCURSAL
	// Función para actualizar los datos de una sucursal existente
	//===========================================================================================================

	public function actualizar_sucursal_controlador(){
		/*-------------------//-------- PASO 1 LIMPIEZA, VALIDACIONES Y SEGURIDAD --------//-----------------------*/	
		/************ Marcar inicio del tiempo para normalizar respuestas *******************************************/
		$this->tiempo_inicio = microtime(true);

		/************ VALIDACION 1: Método POST *****************************************************************/
		if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
			$this->guardar_log('metodo_http_invalido_actualizar_sucursal', [
				'datos_antes' => ['metodo_recibido' => $_SERVER['REQUEST_METHOD']],
				'datos_despues' => ['accion' => 'rechazado']
			], 'alto', 'bloqueado', 'App_empresa');

			return json_encode(["error" => "Method not allowed"]);
		}

		/************ VALIDACION 2: Detección de bots ***********************************************************/
		$numero_campos = count($_POST);
		$umbral_minimo = $numero_campos * 2; // 2 segundos por campo para actualizaciones
		$analisis_bot = $this->es_bot_sospechoso($umbral_minimo, $numero_campos);

		if ($analisis_bot['es_bot']) {
			$this->normalizar_tiempo_respuesta();
			return json_encode([
				"Titulo" => "Actividad Sospechosa",
				"Texto" => "Actividad detectada como automatizada", 
				"Tipo" => "warning"
			], JSON_UNESCAPED_UNICODE);
		}

		/************ VALIDACION 3: Verificar permisos **********************************************************/
		if(!$this->verificar_permisos('editar_sucursales')){
			$this->guardar_log('sin_permisos_editar_sucursal', [
				'datos_antes' => ['usuario' => $_SESSION['CodigoUsuario']],
				'datos_despues' => ['accion' => 'bloqueado']
			], 'alto', 'bloqueado', 'App_empresa');

			$this->normalizar_tiempo_respuesta();
			return json_encode([
				"Titulo" => "Sin permisos",
				"Texto" => "No tienes permisos para editar sucursales", 
				"Tipo" => "warning"
			], JSON_UNESCAPED_UNICODE);
		}

		/************ VALIDACION 4: Validar token CSRF **********************************************************/
		if(!isset($_POST['csrf_token_editar_sucursal']) || !$this->validar_csrf($_POST['csrf_token_editar_sucursal'], 'editarSucursal')){
			$this->guardar_log('csrf_token_invalido_editar_sucursal', [
				'datos_antes' => ['sucursal_id' => $_POST['sucursal_id'] ?? 'no_definido'],
				'datos_despues' => ['accion' => 'bloqueado']
			], 'alto', 'bloqueado', 'App_empresa');

			$this->normalizar_tiempo_respuesta();
			return json_encode([
				"Titulo" => "Token inválido",
				"Texto" => "Token de seguridad inválido o expirado",
				"Tipo" => "warning"
			], JSON_UNESCAPED_UNICODE);
		}

		/************ VALIDACION 5: Límite de intentos **********************************************************/
		if(!$this->verificar_intentos('actualizar_sucursal', 3, 300)){
			$this->normalizar_tiempo_respuesta();
			return json_encode([
				"Titulo" => "Demasiados intentos",
				"Texto" => "Has superado el límite de intentos. Espera 5 minutos",
				"Tipo" => "warning"
			], JSON_UNESCAPED_UNICODE);
		}

		/*-*-*-*-*-* SEGURIDAD: Desencriptar y validar ID de sucursal *-*-*-*-*-*/
		$sucursal_id_encriptado = $_POST['sucursal_id'] ?? '';
		$sucursal_id_desencriptado = $this->decryption($sucursal_id_encriptado);

		if (!$sucursal_id_desencriptado || !is_numeric($sucursal_id_desencriptado)) {
			$this->guardar_log('id_sucursal_invalido_actualizar', [
				'datos_antes' => ['id_recibido_hash' => hash('sha256', $sucursal_id_encriptado)],
				'datos_despues' => ['accion' => 'bloqueado', 'razon' => 'desencriptacion_fallida']
			], 'alto', 'bloqueado', 'App_empresa');

			$this->normalizar_tiempo_respuesta();
			return json_encode([
				"Titulo" => "ID inválido",
				"Texto" => "El identificador de sucursal no es válido",
				"Tipo" => "warning"
			], JSON_UNESCAPED_UNICODE);
		}

		/************ VALIDACION 6: Limpiar los datos recibidos *************************************************/ 
		$resultados_limpieza = [
			'SucursalId' => $this->limpiar_datos($sucursal_id_desencriptado, 'numero', 'sucursal_id'),
			'SucursalNit' => $this->limpiar_datos($_POST['sucursal_nit'], 'numero', 'sucursal_nit'),
			'SucursalNombre' => $this->limpiar_datos($_POST['sucursal_nombre'], 'texto', 'sucursal_nombre'),
			'SucursalDireccion' => $this->limpiar_datos($_POST['sucursal_direccion'], 'texto','sucursal_direccion'),
			'SucursalTelefono' => $this->limpiar_datos($_POST['sucursal_telefono'], 'texto','sucursal_telefono'),
			'SucursalEmail' => $this->limpiar_datos($_POST['sucursal_email'], 'email', 'sucursal_email'),
			'SucursalIdRepresentante' => $this->limpiar_datos($_POST['sucursal_id_representante'], 'numero', 'sucursal_id_representante'),
			'SucursalNomRepresentante' => $this->limpiar_datos($_POST['sucursal_nom_representante'], 'texto', 'sucursal_nom_representante')
		];

		/************ VALIDACION 7: Verificar ataques **********************************************************/
		$hay_ataques = false;
		$ataques_por_campo = [];

		foreach ($resultados_limpieza as $campo => $resultado) {
			if (!$resultado['es_seguro']) {
				$hay_ataques = true;
				$ataques_por_campo[$campo] = $resultado['ataques_detectados'];
			}  
		}

		if ($hay_ataques) {
			$nivel_riesgo_maximo = 'bajo';
			foreach ($resultados_limpieza as $resultado) {
				if ($resultado['nivel_riesgo'] === 'alto') {
					$nivel_riesgo_maximo = 'alto';
					break;
				} elseif ($resultado['nivel_riesgo'] === 'medio' && $nivel_riesgo_maximo !== 'alto') {
					$nivel_riesgo_maximo = 'medio';
				}
			}

			$this->guardar_log('formulario_rechazado_por_ataques_actualizar_sucursal', [
				'datos_antes' => [
					'campos_con_ataques' => array_keys($ataques_por_campo),
					'resumen_ataques' => $ataques_por_campo
				],
				'datos_despues' => ['accion_tomada' => 'formulario_rechazado_completamente'],
			], $nivel_riesgo_maximo, 'rechazado', 'seguridad'); 

			$this->normalizar_tiempo_respuesta();
			return json_encode([
				"Titulo" => "Datos no válidos",
				"Texto" => "Los datos enviados contienen información no permitida.",
				"Tipo" => "warning"
			], JSON_UNESCAPED_UNICODE);
		}

		/************ VALIDACION 8: Extraer datos limpios ******************************************************/
		$datos_sucursal = [];
		foreach ($resultados_limpieza as $campo => $resultado) {
			$datos_sucursal[$campo] = $resultado['dato_limpio'];
		}

		/*-*-*-*-*-* SEGURIDAD EXTRA: Validar token específico usando sistema genérico *-*-*-*-*-*/
		$token_sucursal_recibido = $_POST['token_sucursal_especifico'] ?? '';

		if (!$this->validar_token_entidad_especifico('sucursal', $sucursal_id_desencriptado, $token_sucursal_recibido)) {
			$this->normalizar_tiempo_respuesta();
			return json_encode([
				"Titulo" => "Token de sucursal inválido",
				"Texto" => "El token de autorización para esta sucursal no es válido o ha expirado. Vuelve a cargar la sucursal.",
				"Tipo" => "warning"
			], JSON_UNESCAPED_UNICODE);
		}

		/************ VALIDACION 9: Validar campos obligatorios ************************************************/
		$campos_excluir = ['SucursalId', 'SucursalCodigo', 'SucursalFechaRegistro', 'SucursalFechaActualizacion', 'SucursalEstado', 'SucursalIdEmpresa'];

		$reglas_personalizadas = [
			'SucursalNit' => ['min_caracteres' => 8],
			'SucursalTelefono' => ['min_caracteres' => 7],
			'SucursalNomRepresentante' => ['solo_letras' => true]
		];

		$errores = $this->validar_completo($datos_sucursal, 'App_empresa_sucursal', $campos_excluir, $reglas_personalizadas);

		if(!empty($errores)){
			$mensaje_error = "Errores encontrados:\n";
			foreach($errores as $campo => $errores_campo){
				$mensaje_error .= "- " . implode(", ", $errores_campo) . "\n";
			}

			$this->normalizar_tiempo_respuesta();
			return json_encode([
				"Titulo" => "Datos incorrectos",
				"Texto" => $mensaje_error,
				"Tipo" => "warning"
			], JSON_UNESCAPED_UNICODE);
		}


		/*-------------------//-------- PASO 2: ACTUALIZAR SUCURSAL --------//----------------------------------*/

		try {
			/************ Verificar que la sucursal existe *******************************************************/
			$sucursal_actual = $this->obtener_sucursal_por_id($datos_sucursal['SucursalId']);
			if (!$sucursal_actual) {
				$this->normalizar_tiempo_respuesta();
				return json_encode([
					"Titulo" => "Sucursal no encontrada",
					"Texto" => "La sucursal no existe o ya fue eliminada",
					"Tipo" => "warning"
				], JSON_UNESCAPED_UNICODE);
			}

			/************ Verificar duplicados (excluyendo la sucursal actual) ***********************************/
			if($this->verificar_nit_sucursal_duplicado_actualizar($datos_sucursal['SucursalNit'], $datos_sucursal['SucursalId'])){
				$this->normalizar_tiempo_respuesta();
				return json_encode([
					"Titulo" => "NIT duplicado",
					"Texto" => "Ya existe otra sucursal registrada con este NIT", 
					"Tipo" => "warning"
				], JSON_UNESCAPED_UNICODE);
			}

			if($this->verificar_email_sucursal_duplicado_actualizar($datos_sucursal['SucursalEmail'], $datos_sucursal['SucursalId'])){
				$this->normalizar_tiempo_respuesta();
				return json_encode([
					"Titulo" => "Email duplicado",
					"Texto" => "Ya existe otra sucursal registrada con este email",
					"Tipo" => "warning"
				], JSON_UNESCAPED_UNICODE);
			}

			/************ Preparar datos finales ***************************************************************/
			$datos_finales = [
				'id' => $datos_sucursal['SucursalId'],
				'nit' => $datos_sucursal['SucursalNit'],
				'nombre' => $datos_sucursal['SucursalNombre'],
				'direccion' => $datos_sucursal['SucursalDireccion'],
				'telefono' => $datos_sucursal['SucursalTelefono'],
				'email' => $datos_sucursal['SucursalEmail'],
				'id_representante' => $datos_sucursal['SucursalIdRepresentante'],
				'nom_representante' => $datos_sucursal['SucursalNomRepresentante'],
				'fecha_actualizacion' => date("Y-m-d H:i:s")
			];

			/************ Actualizar sucursal ****************************************************************/
			$resultado = $this->actualizar_sucursal_modelo($datos_finales);

			if($resultado){
				/*-*-*-*-*-* Log de actualización exitosa *-*-*-*-*-*/
				$this->guardar_log('sucursal_actualizada', [
					'datos_antes' => [
						'sucursal_id' => $datos_sucursal['SucursalId'],
						'datos_anteriores' => $sucursal_actual
					],
					'datos_despues' => [
						'resultado' => 'sucursal_actualizada',
						'datos_nuevos' => $datos_finales
					],
				], 'medio', 'exito', 'App_empresa');

				/*-*-*-*-*-* Eliminar token usado *-*-*-*-*-*/
				$this->eliminar_token_csrf('editarSucursal');

				$this->normalizar_tiempo_respuesta();
				return json_encode([
					"Titulo" => "Sucursal actualizada",
					"Texto" => "Los datos de la sucursal se han actualizado exitosamente",
					"Tipo" => "success"
				], JSON_UNESCAPED_UNICODE);

			} else {
				$this->normalizar_tiempo_respuesta();
				return json_encode([
					"Titulo" => "Error",
					"Texto" => "No se pudo actualizar la sucursal. Intenta nuevamente",
					"Tipo" => "error"
				], JSON_UNESCAPED_UNICODE);
			}

		} catch (Exception $e) {
			$this->guardar_log('error_actualizar_sucursal', [
				'datos_antes' => ['sucursal_id' => $datos_sucursal['SucursalId']],
				'datos_despues' => ['error' => $e->getMessage()]
			], 'alto', 'error', 'App_empresa');

			$this->normalizar_tiempo_respuesta();
			return json_encode([
				"Titulo" => "Error interno",
				"Texto" => "Ocurrió un error al actualizar la sucursal",
				"Tipo" => "error"
			], JSON_UNESCAPED_UNICODE);
		}
	}
	
	//===========================================================================================================
	// ELIMINAR SUCURSAL (SOFT DELETE)
	// Función para eliminar sucursal, solo le cambia el estado a eliminado pero no la elimina
	//===========================================================================================================

	public function eliminar_sucursal_controlador(){
		/*-------------------//-------- PASO 1 LIMPIEZA, VALIDACIONES Y SEGURIDAD --------//-----------------------*/	
		/************ Marcar inicio del tiempo para normalizar respuestas *******************************************/
		$this->tiempo_inicio = microtime(true);

		/************ VALIDACION 1: Método POST *****************************************************************/
		if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
			$this->guardar_log('metodo_http_invalido_eliminar_sucursal', [
				'datos_antes' => ['metodo_recibido' => $_SERVER['REQUEST_METHOD']],
				'datos_despues' => ['accion' => 'rechazado']
			], 'alto', 'bloqueado', 'App_empresa');

			return json_encode(["error" => "Method not allowed"]);
		}

		/************ VALIDACION 2: Detección de bots ***********************************************************/
		$numero_campos = count($_POST);
		$umbral_minimo = $numero_campos * 1; // 1 segundo por campo para eliminaciones
		$analisis_bot = $this->es_bot_sospechoso($umbral_minimo, $numero_campos);

		if ($analisis_bot['es_bot']) {
			$this->normalizar_tiempo_respuesta();
			return json_encode([
				"Titulo" => "Actividad Sospechosa",
				"Texto" => "Actividad detectada como automatizada", 
				"Tipo" => "warning"
			], JSON_UNESCAPED_UNICODE);
		}

		/************ VALIDACION 3: Verificar permisos **********************************************************/
		if(!$this->verificar_permisos('eliminar_sucursales')){
			$this->guardar_log('sin_permisos_eliminar_sucursal', [
				'datos_antes' => ['usuario' => $_SESSION['CodigoUsuario']],
				'datos_despues' => ['accion' => 'bloqueado']
			], 'alto', 'bloqueado', 'App_empresa');

			$this->normalizar_tiempo_respuesta();
			return json_encode([
				"Titulo" => "Sin permisos",
				"Texto" => "No tienes permisos para eliminar sucursales", 
				"Tipo" => "warning"
			], JSON_UNESCAPED_UNICODE);
		}

		/************ VALIDACION 4: Límite de intentos **********************************************************/
		if(!$this->verificar_intentos('eliminar_sucursal', 3, 300)){
			$this->normalizar_tiempo_respuesta();
			return json_encode([
				"Titulo" => "Demasiados intentos",
				"Texto" => "Has superado el límite de intentos. Espera 5 minutos",
				"Tipo" => "warning"
			], JSON_UNESCAPED_UNICODE);
		}

		/************ SEPARAR código y token usando el separador encriptado *************************************/	
		$codigo_encriptado = $_POST['sucursal_id'] ?? '';

		// Intentar separar primero
		$data_separada = $this->separar_codigo_con_token($codigo_encriptado, 'separador_sucursal');

		if (!$data_separada) {
			return json_encode([
				"Titulo" => "Código inválido",
				"Texto" => "El código de sucursal no es válido o está corrupto",
				"Tipo" => "warning"
			], JSON_UNESCAPED_UNICODE);
		}

		$codigo_real = $data_separada['codigo'];
		$token_encriptado = $data_separada['token_encriptado'];

		/************ VALIDAR token CSRF ************************************************************************/
		if (!$this->validar_csrf($token_encriptado, 'listEmpresas')) {
			$this->guardar_log('csrf_token_invalido_eliminar_sucursal', [
				'datos_antes' => ['codigo_sucursal' => $codigo_real],
				'datos_despues' => ['accion' => 'bloqueado']
			], 'alto', 'bloqueado', 'App_empresa');

			$this->normalizar_tiempo_respuesta();
			return json_encode([
				"Titulo" => "Token inválido",
				"Texto" => "Token de seguridad inválido o expirado",
				"Tipo" => "warning"
			], JSON_UNESCAPED_UNICODE);
		}

		/*-------------------//-------- PASO 2: ELIMINAR SUCURSAL --------//------------------------------------*/

		/************ Verificar que la sucursal existe ************************************************************/
		$sucursal = $this->obtener_sucursal_por_id($codigo_real);
		if (!$sucursal) {
			$this->normalizar_tiempo_respuesta();
			return json_encode([
				"Titulo" => "Sucursal no encontrada",
				"Texto" => "La sucursal no existe o ya fue eliminada",
				"Tipo" => "warning"
			], JSON_UNESCAPED_UNICODE);
		}

		/************ Realizar eliminación (soft delete) ********************************************************/
		$resultado = $this->eliminar_sucursal_modelo($sucursal['SucursalId']);
		if ($resultado) {
			/*-*-*-*-*-* Logs de eliminación exitosa *-*-*-*-*-*/
			$this->guardar_log('sucursal_eliminada', [
				'datos_antes' => [
					'codigo' => $codigo_real,
					'nombre' => $sucursal['SucursalNombre'],
					'id' => $sucursal['SucursalId'],
					'empresa' => $sucursal['EmpresaNombre']
				],
				'datos_despues' => [
					'resultado' => 'sucursal_eliminada',
					'estado_anterior' => $sucursal['SucursalEstado']
				],
			], 'medio', 'exito', 'App_empresa');

			/*-*-*-*-*-* Generar nuevo token para siguientes acciones *-*-*-*-*-*/
			//$nuevo_token = $this->generar_token_csrf('listEmpresas');

			$this->normalizar_tiempo_respuesta();
			return json_encode([
				"Titulo" => "Sucursal eliminada",
				"Texto" => "La sucursal '" . $sucursal['SucursalNombre'] . "' ha sido eliminada exitosamente",
				"Tipo" => "success",
				"nuevo_token" => $nuevo_token  // Enviar el nuevo token al JavaScript
			], JSON_UNESCAPED_UNICODE);

		} else {
			$this->normalizar_tiempo_respuesta();
			return json_encode([
				"Titulo" => "Error",
				"Texto" => "No se pudo eliminar la sucursal. Intenta nuevamente",
				"Tipo" => "error"
			], JSON_UNESCAPED_UNICODE);
		}
	}
	
	//===========================================================================================================
	// CAMBIAR ESTADO DE SUCURSAL
	// Función para cambiar el estado de una sucursal (Activo/Inactivo/Suspendido)
	//===========================================================================================================

	public function cambiar_estado_sucursal_controlador(){
		/*-------------------//-------- VALIDACIONES DE SEGURIDAD --------//-----------------------*/	
		/************ Marcar inicio del tiempo para normalizar respuestas *******************************************/
		$this->tiempo_inicio = microtime(true);

		/************ VALIDACION 1: Método POST *****************************************************************/
		if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
			$this->guardar_log('metodo_http_invalido_cambiar_estado_sucursal', [
				'datos_antes' => ['metodo_recibido' => $_SERVER['REQUEST_METHOD']],
				'datos_despues' => ['accion' => 'rechazado']
			], 'alto', 'bloqueado', 'App_empresa');

			return json_encode(["error" => "Method not allowed"]);
		}

		/************ VALIDACION 2: Detección de bots ***********************************************************/
		$numero_campos = count($_POST);
		$umbral_minimo = $numero_campos * 2; // 2 segundos por campo para actualizaciones
		$analisis_bot = $this->es_bot_sospechoso($umbral_minimo, $numero_campos);

		if ($analisis_bot['es_bot']) {
			$this->normalizar_tiempo_respuesta();
			return json_encode([
				"Titulo" => "Actividad Sospechosa",
				"Texto" => "Actividad detectada como automatizada", 
				"Tipo" => "warning"
			], JSON_UNESCAPED_UNICODE);
		}

		/************ VALIDACION 3: Verificar permisos **********************************************************/
		if(!$this->verificar_permisos('cambiar_estado_sucursales')){
			$this->guardar_log('sin_permisos_cambiar_estado_sucursales', [
				'datos_antes' => ['usuario' => $_SESSION['CodigoUsuario']],
				'datos_despues' => ['accion' => 'bloqueado']
			], 'alto', 'bloqueado', 'App_empresa');

			$this->normalizar_tiempo_respuesta();
			return json_encode([
				"Titulo" => "Sin permisos",
				"Texto" => "No tienes permisos para cambiar estado de sucursales", 
				"Tipo" => "warning"
			], JSON_UNESCAPED_UNICODE);
		}

		/************ VALIDACION 4: Límite de intentos **********************************************************/
		if(!$this->verificar_intentos('cambiar_estado_sucursal', 3, 300)){
			$this->normalizar_tiempo_respuesta();
			return json_encode([
				"Titulo" => "Demasiados intentos",
				"Texto" => "Has superado el límite de intentos. Espera 5 minutos",
				"Tipo" => "warning"
			], JSON_UNESCAPED_UNICODE);
		}

		/************ SEPARAR código y token usando el separador encriptado *************************************/	
		$codigo_encriptado = $_POST['sucursal_id'] ?? '';

		// Intentar separar primero
		$data_separada = $this->separar_codigo_con_token($codigo_encriptado, 'separador_sucursal');

		if (!$data_separada) {
			return json_encode([
				"Titulo" => "Código inválido",
				"Texto" => "El código de sucursal no es válido o está corrupto",
				"Tipo" => "warning"
			], JSON_UNESCAPED_UNICODE);
		}

		$codigo_real = $data_separada['codigo'];
		$token_encriptado = $data_separada['token_encriptado'];

		/************ VALIDACION 5: VALIDAR token CSRF del listado ***********************************************/
		if (!$this->validar_csrf($token_encriptado, 'listEmpresas')) {
			$this->guardar_log('csrf_token_invalido_lista_cambio_estado_sucursal', [
				'datos_antes' => ['codigo_sucursal' => $codigo_real],
				'datos_despues' => ['accion' => 'bloqueado']
			], 'alto', 'bloqueado', 'App_empresa');

			$this->normalizar_tiempo_respuesta();
			return json_encode([
				"Titulo" => "Token inválido",
				"Texto" => "Token de seguridad inválido o expirado",
				"Tipo" => "warning"
			], JSON_UNESCAPED_UNICODE);
		}	

		/************ VALIDACION 6: Validar token CSRF del cambio de estado ****************************************/
		if(!isset($_POST['csrf_token_estado_sucursal']) || !$this->validar_csrf($_POST['csrf_token_estado_sucursal'], 'listEmpresas')){
			$this->guardar_log('csrf_token_invalido_cambiar_estado_sucursal', [
				'datos_antes' => ['sucursal_id' => $codigo_real ?? 'no_definido'],
				'datos_despues' => ['accion' => 'bloqueado']
			], 'alto', 'bloqueado', 'App_empresa');

			$this->normalizar_tiempo_respuesta();
			return json_encode([
				"Titulo" => "Token inválido",
				"Texto" => "Token de seguridad inválido o expirado",
				"Tipo" => "warning"
			], JSON_UNESCAPED_UNICODE);
		}

		/*-------------------//-------- LIMPIEZA Y VALIDACIÓN DE DATOS --------//---------------------------*/

		$resultados_limpieza = [
			'sucursal_id' => $this->limpiar_datos($codigo_real, 'numero', 'sucursal_id'),
			'nuevo_estado' => $this->limpiar_datos($_POST['nuevo_estado'], 'texto', 'nuevo_estado'),
			'motivo_cambio' => $this->limpiar_datos($_POST['motivo_cambio'], 'texto', 'motivo_cambio')
		];

		/************ VALIDACION 7: Verifico si algun campo tenía ataques, sale de la limpieza realizada ***********/
		$hay_ataques = false;
		$ataques_por_campo = [];
		/*-*-*-*-*-* reviso cada campo *-*-*-*-*-*/
		foreach ($resultados_limpieza as $campo => $resultado) {
			if (!$resultado['es_seguro']) {
				$hay_ataques = true;
				$ataques_por_campo[$campo] = $resultado['ataques_detectados'];
			}  
		}
		/*-*-*-*-*-* si hay ataques RECHAZO completamente *-*-*-*-*-*/
		if ($hay_ataques) {
			/*-*-*-*-*-*calculo el nivel de riesgo *-*-*-*-*-*/
			$nivel_riesgo_maximo = 'bajo';
			foreach ($resultados_limpieza as $resultado) {
				if ($resultado['nivel_riesgo'] === 'alto') {
					$nivel_riesgo_maximo = 'alto';
					break;
				} elseif ($resultado['nivel_riesgo'] === 'medio' && $nivel_riesgo_maximo !== 'alto') {
					$nivel_riesgo_maximo = 'medio';
				}
			}
			/*-*-*-*-*-* registro logs de seguimiento y auditoria *-*-*-*-*-*/
			$this->guardar_log(
				'formulario_rechazado_por_ataques_cambio_estado_sucursal', [
					'datos_antes' => [
						'campos_con_ataques' => array_keys($ataques_por_campo),
						'total_campos_afectados' => count($ataques_por_campo),
						'resumen_ataques' => $ataques_por_campo
					],
					'datos_despues' => [
						'accion_tomada' => 'formulario_rechazado_completamente'
					],
				],
				$nivel_riesgo_maximo, 'rechazado', 'seguridad'); 

			/*-*-*-*-*-* Normalizamos el tiempo de respuesta *-*-*-*-*-*/           
			$this->normalizar_tiempo_respuesta();
			/*-*-*-*-*-* Termino función para registro de empresa *-*-*-*-*-*/
			return json_encode([
				"Titulo" => "Datos no válidos",
				"Texto" => "Los datos enviados contienen información no permitida.",
				"Tipo" => "warning"
			], JSON_UNESCAPED_UNICODE);
		}

		// Extraer datos limpios
		$sucursal_id = $resultados_limpieza['sucursal_id']['dato_limpio'];
		$nuevo_estado = $resultados_limpieza['nuevo_estado']['dato_limpio'];
		$motivo_cambio = $resultados_limpieza['motivo_cambio']['dato_limpio'];

		/*-------------------//-------- VALIDACIONES DE NEGOCIO --------//---------------------------*/

		// Validar que el estado sea válido
		$estados_permitidos = ['Activo', 'Inactivo', 'Suspendido'];
		if (!in_array($nuevo_estado, $estados_permitidos)) {
			/*-*-*-*-*-* registro logs de seguimiento y auditoria *-*-*-*-*-*/
			$this->guardar_log('Estado_sucursal_no_permitido', [
				'datos_antes' => [
						'estado_recibido'=> $nuevo_estado
					],
				'datos_despues' => [
					'accion' => 'bloqueado',
					'usuario'=>$_SESSION['UsuarioId'] ?? 'anonimo'
				]
			], 'medio', 'bloqueado', 'App_empresa');

			$this->normalizar_tiempo_respuesta();
			return json_encode([
				"Titulo" => "Estado no válido",
				"Texto" => "El estado seleccionado no es válido",
				"Tipo" => "warning"
			], JSON_UNESCAPED_UNICODE);
		}

		// Validar motivo
		if (strlen($motivo_cambio) < 10) {
			/*-*-*-*-*-* registro logs de seguimiento y auditoria *-*-*-*-*-*/
			$this->guardar_log('Estado_sucursal_longitud', [
				'datos_antes' => [
						'estado_actual'=> 'motivo del cambio de estado'
					],
				'datos_despues' => [
					'longitud'=> 'la longitud del motivo de cambio es inferior a 10 caracteres',
					'accion'=>'No se realiza el cambio',
					'estado_nuevo' => $nuevo_estado,
					'motivo' => $motivo_cambio,
					'usuario_cambio' => $_SESSION['UsuarioId'] ?? 'anonimo'
				]
			], 'medio', 'bloqueado', 'App_empresa');

			$this->normalizar_tiempo_respuesta();
			return json_encode([
				"Titulo" => "Motivo muy corto",
				"Texto" => "El motivo debe tener al menos 10 caracteres",
				"Tipo" => "warning"
			], JSON_UNESCAPED_UNICODE);
		}

		/*-------------------//-------- PROCESAR CAMBIO DE ESTADO --------//---------------------------*/

		try {
			// Obtener datos actuales de la sucursal
			$sucursal_actual = $this->obtener_sucursal_por_id($sucursal_id);
			if (!$sucursal_actual) {
				$this->normalizar_tiempo_respuesta();
				return json_encode([
					"Titulo" => "Sucursal no encontrada",
					"Texto" => "La sucursal no existe o ya fue eliminada",
					"Tipo" => "warning"
				], JSON_UNESCAPED_UNICODE);
			}

			// Verificar que el estado realmente va a cambiar
			if ($sucursal_actual['SucursalEstado'] === $nuevo_estado) {
				$this->normalizar_tiempo_respuesta();
				return json_encode([
					"Titulo" => "Sin cambios",
					"Texto" => "La sucursal ya tiene el estado: " . $nuevo_estado,
					"Tipo" => "info"
				], JSON_UNESCAPED_UNICODE);
			}

			// Ejecutar cambio de estado
			$resultado = $this->cambiar_estado_sucursal_modelo($sucursal_id, $nuevo_estado, $motivo_cambio);

			if ($resultado) {
				// Log del cambio exitoso
				$this->guardar_log('estado_sucursal_cambiado', [
					'datos_antes' => [
						'sucursal_id' => $sucursal_id,
						'codigo_sucursal' => $sucursal_actual['SucursalCodigo'],
						'nombre_sucursal' => $sucursal_actual['SucursalNombre'],
						'empresa_nombre' => $sucursal_actual['EmpresaNombre'],
						'estado_anterior' => $sucursal_actual['SucursalEstado']
					],
					'datos_despues' => [
						'estado_nuevo' => $nuevo_estado,
						'motivo' => $motivo_cambio,
						'usuario_cambio' => $_SESSION['UsuarioId'] ?? 'anonimo'
					],
				], 'medio', 'exito', 'App_empresa');

				$this->normalizar_tiempo_respuesta();
				return json_encode([
					"Titulo" => "Estado actualizado",
					"Texto" => "El estado de la sucursal se cambió exitosamente a: " . $nuevo_estado,
					"Tipo" => "success"
				], JSON_UNESCAPED_UNICODE);

			} else {
				$this->normalizar_tiempo_respuesta();
				return json_encode([
					"Titulo" => "Error",
					"Texto" => "No se pudo cambiar el estado de la sucursal",
					"Tipo" => "error"
				], JSON_UNESCAPED_UNICODE);
			}

		} catch (Exception $e) {
			$this->guardar_log('error_cambiar_estado_sucursal', [
				'datos_antes' => ['sucursal_id' => $sucursal_id, 'nuevo_estado' => $nuevo_estado],
				'datos_despues' => ['error' => $e->getMessage()]
			], 'alto', 'error', 'App_empresa');

			$this->normalizar_tiempo_respuesta();
			return json_encode([
				"Titulo" => "Error interno",
				"Texto" => "Ocurrió un error al cambiar el estado",
				"Tipo" => "error"
			], JSON_UNESCAPED_UNICODE);
		}
	}

	//===========================================================================================================
	// REGISTRAR NUEVA SEDE
	// Función completa para registrar una nueva sede con todas las validaciones de seguridad
	//===========================================================================================================

	public function registrar_sede_controlador(){
		/*-------------------//-------- PASO 1 LIMPIEZA, VALIDACIONES Y SEGURIDAD --------//-----------------------*/	
		/************ Marcar inicio del tiempo para normalizar respuestas *******************************************/
		$this->tiempo_inicio = microtime(true);

		/************ VALIDACION 1: Método POST *****************************************************************/
		if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
			$this->guardar_log('metodo_http_invalido_registrar_sede', [
				'datos_antes' => ['metodo_recibido' => $_SERVER['REQUEST_METHOD']],
				'datos_despues' => ['accion' => 'rechazado']
			], 'alto', 'bloqueado', 'App_empresa');

			http_response_code(405);
			return json_encode(["error" => "Method not allowed"]);
		}

		/************ VALIDACION 2: Detección de bots ***********************************************************/
		$numero_campos = count($_POST);
		$tiempo_por_campo = 2; // segundos mínimos por campo
		$umbral_minimo = ($numero_campos - 1) * $tiempo_por_campo;
		$analisis_bot = $this->es_bot_sospechoso($umbral_minimo, $numero_campos);

		if ($analisis_bot['es_bot']) {
			$this->normalizar_tiempo_respuesta();
			return json_encode([
				"Alerta" => "simple",
				"Titulo" => "Actividad Inapropiada",
				"Texto" => "Actividad sospechosa detectada", 
				"Tipo" => "warning"
			], JSON_UNESCAPED_UNICODE);
		}

		/************ VALIDACION 3: Verificar token CSRF ********************************************************/
		if(!isset($_POST['csrf_token_sede']) || !$this->validar_csrf($_POST['csrf_token_sede'],'formNuevaSede')){
			$this->guardar_log('csrf_token_invalido_registrar_sede', [
				'datos_antes' => ['Token_creado'=>'Diferente al recibido'],
				'datos_despues' => [
					'formulario' => 'formNuevaSede',
					'token_recibido_hash' => isset($_POST['csrf_token_sede']) ? hash('sha256', $_POST['csrf_token_sede']) : 'no_enviado',
					'session_id' => session_id(),
					'CodigoUsuario' => $_SESSION['CodigoUsuario'] ?? 'no_definido',
					'UsuarioId' => $_SESSION['UsuarioId'] ?? 'no_definido'
				]
			], 'alto', 'bloqueado', 'App_empresa');

			$this->normalizar_tiempo_respuesta();
			return json_encode([
				"Alerta" => "simple",
				"Titulo" => "Token inválido",
				"Texto" => "Token de seguridad inválido. Recarga la página e intenta nuevamente", 
				"Tipo" => "warning"
			], JSON_UNESCAPED_UNICODE);
		}

		/************ VALIDACION 4: Verificar permisos **********************************************************/
		if(!$this->verificar_permisos('crear_sedes')){
			$this->guardar_log('sin_permisos_crear_sede', [
				'datos_antes' => ['usuario'=>'sin permisos'],
				'datos_despues' => ['CodigoUsuario' => $_SESSION['CodigoUsuario'], 'UsuarioId' => $_SESSION['UsuarioId']]
			], 'alto', 'bloqueado', 'App_empresa');

			$this->normalizar_tiempo_respuesta();

			return json_encode([
				"Alerta" => "simple",
				"Titulo" => "Sin permisos",
				"Texto" => "No tienes permisos para crear sedes", 
				"Tipo" => "warning"
			], JSON_UNESCAPED_UNICODE);
		}

		/************ VALIDACION 5: Límite de intentos **********************************************************/
		if(!$this->verificar_intentos('registro_sede', 3, 300)){
			$this->guardar_log('registro_sede_limite_intentos', [
				'datos_antes' => ['intentos'=>'Usuario alcanzó límite'],
				'datos_despues' => ['intentos'=>'Bloqueado por 5 minutos', 'CodigoUsuario' => $_SESSION['CodigoUsuario']]
			], 'alto', 'bloqueado', 'App_empresa');

			$this->normalizar_tiempo_respuesta();
			return json_encode([
				"Alerta" => "simple",
				"Titulo" => "Demasiados intentos",
				"Texto" => "Has superado el límite de intentos. Espera 5 minutos",
				"Tipo" => "warning"
			], JSON_UNESCAPED_UNICODE);
		}

		/************ VALIDACION 6: Limpiar los datos recibidos *************************************************/ 
		$sucursal_id_encriptado = $_POST['sucursal-id'] ?? '';
		$sucursal_id_desencriptado = $this->decryption($sucursal_id_encriptado);

		$resultados_limpieza = [
			'SedeNit' => $this->limpiar_datos($_POST['sede-nit'], 'numero', 'sede-nit'),
			'SedeNombre' => $this->limpiar_datos($_POST['sede-nombre'], 'texto', 'sede-nombre'),
			'SedeDireccion' => $this->limpiar_datos($_POST['sede-direccion'], 'texto','sede-direccion'),
			'SedeTelefono' => $this->limpiar_datos($_POST['sede-telefono'], 'texto','sede-telefono'),
			'SedeEmail' => $this->limpiar_datos($_POST['sede-email'], 'email', 'sede-email'),
			'SedeIdRepresentante' => $this->limpiar_datos($_POST['sede-id-representante'], 'numero', 'sede-id-representante'),
			'SedeNomRepresentante' => $this->limpiar_datos($_POST['sede-nom-representante'], 'texto', 'sede-nom-representante'),
			'SedeIdSucursal' => $this->limpiar_datos($sucursal_id_desencriptado, 'numero', 'sucursal-id')
		];

		/************ VALIDACION 7: Verificar ataques ***********************************************************/
		$hay_ataques = false;
		$ataques_por_campo = [];

		foreach ($resultados_limpieza as $campo => $resultado) {
			if (!$resultado['es_seguro']) {
				$hay_ataques = true;
				$ataques_por_campo[$campo] = $resultado['ataques_detectados'];
			}  
		}

		if ($hay_ataques) {
			$nivel_riesgo_maximo = 'bajo';
			foreach ($resultados_limpieza as $resultado) {
				if ($resultado['nivel_riesgo'] === 'alto') {
					$nivel_riesgo_maximo = 'alto';
					break;
				} elseif ($resultado['nivel_riesgo'] === 'medio' && $nivel_riesgo_maximo !== 'alto') {
					$nivel_riesgo_maximo = 'medio';
				}
			}
			/*-*-*-*-*-* registro logs de seguimiento y auditoria *-*-*-*-*-*/
			$this->guardar_log('formulario_sede_rechazado_por_ataques', [
				'datos_antes' => [
					'campos_con_ataques' => array_keys($ataques_por_campo),
					'total_campos_afectados' => count($ataques_por_campo),
					'resumen_ataques' => $ataques_por_campo
				],
				'datos_despues' => ['accion_tomada' => 'formulario_rechazado_completamente']
			], $nivel_riesgo_maximo, 'rechazado', 'seguridad'); 

			/*-*-*-*-*-* Normalizamos el tiempo de respuesta *-*-*-*-*-*/           
			$this->normalizar_tiempo_respuesta();
			return json_encode([
				"Titulo" => "Datos no válidos",
				"Texto" => "Los datos enviados contienen información no permitida.",
				"Tipo" => "warning"
			], JSON_UNESCAPED_UNICODE);
		}

		/************ VALIDACION 8: Extraer datos limpios *******************************************************/
		$datos_sede = [];
		foreach ($resultados_limpieza as $campo => $resultado) {
			$datos_sede[$campo] = $resultado['dato_limpio'];
		}

		/************ VALIDACION 9: Verificar campos obligatorios ***********************************************/
		$campos_excluir = ['SedeId', 'SedeCodigo', 'SedeFechaRegistro', 'SedeFechaActualizacion', 'SedeEstado'];

		$reglas_personalizadas = [
			'SedeNit' => ['min_caracteres' => 8],
			'SedeTelefono' => ['min_caracteres' => 7],
			'SedeNomRepresentante' => ['solo_letras' => true]
		];

		$errores = $this->validar_completo($datos_sede, 'App_empresa_sede', $campos_excluir, $reglas_personalizadas);

		if(!empty($errores)){
			$mensaje_error = "Errores encontrados:\n";
			foreach($errores as $campo => $errores_campo){
				$mensaje_error .= "- " . implode(", ", $errores_campo) . "\n";
			}

			$this->normalizar_tiempo_respuesta();
			return json_encode([
				"Alerta" => "simple",
				"Titulo" => "Datos incorrectos",
				"Texto" => $mensaje_error,
				"Tipo" => "warning"
			], JSON_UNESCAPED_UNICODE);
		}

		/************ VALIDACION 10: Verificar que la sucursal existe y está activa *****************************/
		$sucursal = $this->obtener_sucursal_por_id($datos_sede['SedeIdSucursal']);
		if (!$sucursal) {
			$this->normalizar_tiempo_respuesta();
			return json_encode([
				"Alerta" => "simple",
				"Titulo" => "Sucursal no encontrada",
				"Texto" => "La sucursal seleccionada no existe o no está disponible", 
				"Tipo" => "warning"
			], JSON_UNESCAPED_UNICODE);
		}

		if ($sucursal['SucursalEstado'] !== 'Activo') {
			$this->normalizar_tiempo_respuesta();
			return json_encode([
				"Alerta" => "simple",
				"Titulo" => "Sucursal no activa",
				"Texto" => "No se pueden crear sedes en sucursales inactivas", 
				"Tipo" => "warning"
			], JSON_UNESCAPED_UNICODE);
		}

		/************ VALIDACION 11: Verificar NIT duplicado ****************************************************/
		if($this->verificar_nit_sede_duplicado($datos_sede['SedeNit'])){
			$this->normalizar_tiempo_respuesta();
			return json_encode([
				"Alerta" => "simple",
				"Titulo" => "NIT duplicado",
				"Texto" => "Ya existe una sede registrada con este NIT", 
				"Tipo" => "warning"
			], JSON_UNESCAPED_UNICODE);
		}

		/************ VALIDACION 12: Verificar email duplicado *************************************************/
		if($this->verificar_email_sede_duplicado($datos_sede['SedeEmail'])){
			$this->normalizar_tiempo_respuesta();
			return json_encode([
				"Alerta" => "simple",
				"Titulo" => "Email duplicado",
				"Texto" => "Ya existe una sede registrada con este email",
				"Tipo" => "warning"
			], JSON_UNESCAPED_UNICODE);
		}

		/*-------------------//-------- PASO 2: REGISTRO DE SEDE EN BD --------//---------------------------*/		

		/************ COMPLEMENTO 1: Generar código para la sede *******************************************/
		$sql = "SELECT COUNT(SedeId) FROM App_empresa_sede";
		$total_sedes = $this->ejecutar_consulta_segura($sql, []);
		$total_sedes = ($total_sedes->fetchColumn()) + 1;

		if($total_sedes > 0){
			$longitud = 10 - strlen($total_sedes);
		}else{
			$longitud = 10;
			$total_sedes = "_";
		}

		$codigo_sede = $this->generar_codigo_aleatorio('SE', $longitud, '');
		$codigo_sede = $codigo_sede . $total_sedes;

		/************ Consolidar datos finales **************************************************************/
		$datos_finales = [
			'codigo' => $codigo_sede,
			'nit' => $datos_sede['SedeNit'],
			'nombre' => $datos_sede['SedeNombre'],
			'direccion' => $datos_sede['SedeDireccion'],
			'telefono' => $datos_sede['SedeTelefono'],
			'email' => $datos_sede['SedeEmail'],
			'id_representante' => $datos_sede['SedeIdRepresentante'],
			'nom_representante' => $datos_sede['SedeNomRepresentante'],
			'sucursal_id' => $datos_sede['SedeIdSucursal'],
			'fecha_registro' => date("Y-m-d H:i:s"),
			'fecha_actualizacion' => date("Y-m-d H:i:s")
		];

		/************ Enviar registro de sede ***********************************************************/
		$resultado = $this->registrar_sede_modelo($datos_finales);

		if($resultado){
			$this->guardar_log('sede_registrada', [
				'datos_antes'=>['antes'=>'sin información'],
				'datos_despues'=> [
					'resultado'=> 'sede creada',
					'codigo_sede' => $codigo_sede,
					'nit' => $datos_sede['SedeNit'],
					'nombre' => $datos_sede['SedeNombre'],
					'sucursal_id' => $datos_sede['SedeIdSucursal'],
					'sucursal_nombre' => $sucursal['SucursalNombre']
				]
			], 'medio', 'exito', 'App_empresa');

			$this->eliminar_token_csrf('formNuevaSede');

			$this->normalizar_tiempo_respuesta();
			return json_encode([
				"Alerta" => "recargar",
				"Titulo" => "Sede registrada",
				"Texto" => "La sede se ha registrado exitosamente con el código: ".$codigo_sede,
				"Tipo" => "success"
			], JSON_UNESCAPED_UNICODE);

		} else {
			$this->guardar_log('sede_registrada_fallo', [
				'datos_antes'=>['antes'=>'sin información'],
				'datos_despues'=> [
					'resultado'=> 'sede no creada',
					'codigo_sede' => $codigo_sede,
					'nit' => $datos_sede['SedeNit'],
					'nombre' => $datos_sede['SedeNombre']
				]
			], 'medio', 'error', 'App_empresa');

			$this->normalizar_tiempo_respuesta();
			return json_encode([
				"Alerta" => "simple",
				"Titulo" => "Error",
				"Texto" => "No se pudo registrar la sede. Intenta nuevamente",
				"Tipo" => "error"
			], JSON_UNESCAPED_UNICODE);
		}
	}
	
	//===========================================================================================================
	// LISTAR SEDES DE UNA SUCURSAL
	// Función para obtener todas las sedes de una sucursal específica
	//===========================================================================================================

	public function listar_sedes_sucursal_controlador(){
		/*-------------------//-------- VALIDACIONES DE SEGURIDAD --------//-----------------------*/	
		$this->tiempo_inicio = microtime(true);

		/************ VALIDACION 1: Método POST *****************************************************************/
		if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
			$this->guardar_log('metodo_http_invalido_listar_sedes', [
				'datos_antes' => ['metodo_recibido' => $_SERVER['REQUEST_METHOD']],
				'datos_despues' => ['accion' => 'rechazado']
			], 'alto', 'bloqueado', 'App_empresa');

			return json_encode(["error" => "Method not allowed"]);
		}

		/************ VALIDACION 2: Verificar permisos **********************************************************/
		if(!$this->verificar_permisos('listar_sedes')){
			$this->guardar_log('sin_permisos_listar_sedes', [
				'datos_antes' => ['usuario' => $_SESSION['CodigoUsuario']],
				'datos_despues' => ['accion' => 'bloqueado']
			], 'alto', 'bloqueado', 'App_empresa');

			$this->normalizar_tiempo_respuesta();
			return json_encode([
				"Titulo" => "Sin permisos",
				"Texto" => "No tienes permisos para ver sedes", 
				"Tipo" => "warning"
			], JSON_UNESCAPED_UNICODE);
		}

		/************ VALIDACION 3: Validar token CSRF **********************************************************/
		if(!isset($_POST['csrf_token_sedes']) || !$this->validar_csrf($_POST['csrf_token_sedes'], 'listEmpresas')){
			$this->guardar_log('csrf_token_invalido_listar_sedes', [
				'datos_antes' => ['sucursal_id' => $_POST['sucursal_id'] ?? 'no_definido'],
				'datos_despues' => ['accion' => 'bloqueado']
			], 'alto', 'bloqueado', 'App_empresa');

			$this->normalizar_tiempo_respuesta();
			return json_encode([
				"Titulo" => "Token inválido",
				"Texto" => "Token de seguridad inválido o expirado",
				"Tipo" => "warning"
			], JSON_UNESCAPED_UNICODE);
		}

		/*-------------------//-------- LIMPIEZA Y VALIDACIÓN DE DATOS --------//---------------------------*/

		/************ SEPARAR código y token usando el separador encriptado *************************************/	
		$codigo_encriptado = $_POST['sucursal_id'] ?? '';

		// Intentar separar primero
		$data_separada = $this->separar_codigo_con_token($codigo_encriptado, 'separador_sucursal');

		if (!$data_separada) {
			return json_encode([
				"Titulo" => "Código inválido",
				"Texto" => "El código de sucursal no es válido o está corrupto",
				"Tipo" => "warning"
			], JSON_UNESCAPED_UNICODE);
		}

		$codigo_real = $data_separada['codigo'];
		$token_encriptado = $data_separada['token_encriptado'];

		/************ VALIDAR token CSRF del listado ***********************************************************/
		if (!$this->validar_csrf($token_encriptado, 'listEmpresas')) {
			$this->guardar_log('csrf_token_invalido_sucursal_sedes', [
				'datos_antes' => ['codigo_sucursal' => $codigo_real],
				'datos_despues' => ['accion' => 'bloqueado']
			], 'alto', 'bloqueado', 'App_empresa');

			$this->normalizar_tiempo_respuesta();
			return json_encode([
				"Titulo" => "Token inválido",
				"Texto" => "Token de seguridad inválido o expirado",
				"Tipo" => "warning"
			], JSON_UNESCAPED_UNICODE);
		}

		// Limpiar datos
		$resultados_limpieza = [
			'sucursal_id' => $this->limpiar_datos($codigo_real, 'numero', 'sucursal_id'),
			'filtro_nombre' => $this->limpiar_datos($_POST['filtro_nombre'] ?? '', 'texto', 'filtro_nombre'),
			'filtro_estado' => $this->limpiar_datos($_POST['filtro_estado'] ?? '', 'texto', 'filtro_estado')
		];

		// Verificar ataques
		foreach ($resultados_limpieza as $resultado) {
			if (!$resultado['es_seguro']) {
				$this->normalizar_tiempo_respuesta();
				return json_encode([
					"Titulo" => "Datos no válidos",
					"Texto" => "Los filtros contienen información no permitida",
					"Tipo" => "warning"
				], JSON_UNESCAPED_UNICODE);
			}
		}

		// Extraer datos limpios
		$sucursal_id = $resultados_limpieza['sucursal_id']['dato_limpio'];
		$filtro_nombre = $resultados_limpieza['filtro_nombre']['dato_limpio'];
		$filtro_estado = $resultados_limpieza['filtro_estado']['dato_limpio'];

		/*-------------------//-------- PROCESAR SOLICITUD --------//---------------------------*/

		try {
			// Verificar que la sucursal existe
			$sucursal = $this->obtener_sucursal_por_id($sucursal_id);
			if (!$sucursal) {
				$this->normalizar_tiempo_respuesta();
				return json_encode([
					"Titulo" => "Sucursal no encontrada",
					"Texto" => "La sucursal no existe o no está disponible",
					"Tipo" => "warning"
				], JSON_UNESCAPED_UNICODE);
			}

			// Obtener sedes de la sucursal
			$sedes_data = $this->listar_sedes_sucursal_modelo($sucursal_id, $filtro_nombre, $filtro_estado);

			// Generar HTML de las sedes
			$html_sedes = $this->generar_html_sedes($sedes_data, $sucursal);

			// Log de consulta exitosa
			$this->guardar_log('sedes_listadas_exitosamente', [
				'datos_antes' => [
					'sucursal_id' => $sucursal_id,
					'filtros_aplicados' => [
						'nombre' => $filtro_nombre,
						'estado' => $filtro_estado
					]
				],
				'datos_despues' => [
					'sedes_encontradas' => count($sedes_data),
					'sucursal_codigo' => $sucursal['SucursalCodigo']
				]
			], 'bajo', 'exito', 'App_empresa');
            
            $SucursalId = $this->encryption($sucursal_id);
            
			$this->normalizar_tiempo_respuesta();
			return json_encode([
				"status" => "success",
				"html_sedes" => $html_sedes,
				"total_sedes" => count($sedes_data),
                "SucursalId"=> $SucursalId,
				"sucursal_info" => [
					"nombre" => $sucursal['SucursalNombre'],
					"codigo" => $sucursal['SucursalCodigo'],
					"empresa" => $sucursal['EmpresaNombre'],
					"SucursalTelefono"=> $sucursal['SucursalTelefono'],
					"SucursalEmail"=> $sucursal['SucursalEmail'],
					"SucursalDireccion"=> $sucursal['SucursalDireccion'],
					"SucursalIdRepresentante"=> $sucursal['SucursalIdRepresentante'],
					"SucursalNomRepresentante"=> $sucursal['SucursalNomRepresentante']
				]
			], JSON_UNESCAPED_UNICODE);

		} catch (Exception $e) {
			$this->guardar_log('error_listar_sedes', [
				'datos_antes' => ['sucursal_id' => $sucursal_id],
				'datos_despues' => ['error' => $e->getMessage()]
			], 'alto', 'error', 'App_empresa');

			$this->normalizar_tiempo_respuesta();
			return json_encode([
				"Titulo" => "Error interno",
				"Texto" => "Ocurrió un error al cargar las sedes",
				"Tipo" => "error"
			], JSON_UNESCAPED_UNICODE);
		}
	}
	
	//===========================================================================================================
	// GENERAR HTML DE SEDES
	// Función para generar el HTML de la lista de sedes
	//===========================================================================================================

	private function generar_html_sedes($sedes, $sucursal) {
		if (empty($sedes)) {
			return '
				<div class="text-center py-5">
					<i class="bi bi-buildings fa-3x text-muted mb-3"></i>
					<h6 class="text-muted">No hay sedes registradas</h6>
					<p class="text-muted mb-3">Esta sucursal aún no tiene sedes asociadas</p>
					<button class="btn btn-primary" onclick="mostrarModalNuevaSede()">
						<i class="bi bi-plus me-1"></i>
						Crear Primera Sede
					</button>
				</div>
			';
		}

		$html = '<div class="list-group list-group-flush">';

		$separador = $this->encryption_deterministico('1n49n', 'separador_sede');

		foreach ($sedes as $sede) {
			// Generar código encriptado para la sede
			$codigo_encriptado_sede = $_SESSION['csrf_listEmpresas'] . $separador . $this->encryption($sede['SedeId']);

			// Generar dropdown de estados (en lugar de badge estático)
			$opciones_estado = [
				'Activo' => ['color' => 'success', 'icono' => 'check-circle'],
				'Inactivo' => ['color' => 'warning', 'icono' => 'pause-circle'],
				'Suspendido' => ['color' => 'danger', 'icono' => 'x-circle'],
				'Eliminado' => ['color' => 'secondary', 'icono' => 'trash']
			];

			$dropdown_estados = '<div class="dropdown">
				<button class="btn btn-outline-' . $opciones_estado[$sede['SedeEstado']]['color'] . ' btn-sm dropdown-toggle estado-dropdown" 
						type="button" 
						data-bs-toggle="dropdown" 
						data-sede-id="' . $codigo_encriptado_sede . '"
						data-estado-actual="' . $sede['SedeEstado'] . '"
						data-sede-nombre="' . htmlspecialchars($sede['SedeNombre']) . '">
					<i class="bi bi-' . $opciones_estado[$sede['SedeEstado']]['icono'] . ' me-1"></i>
					' . $sede['SedeEstado'] . '
				</button>
				<ul class="dropdown-menu">';

			// Agregar opciones (excepto la actual y "Eliminado")
			foreach ($opciones_estado as $estado => $config) {
				if ($estado !== $sede['SedeEstado'] && $estado !== 'Eliminado') {
					$dropdown_estados .= '
						<li>
							<a class="dropdown-item cambiar-estado-sede" 
							   href="#" 
							   data-nuevo-estado="' . $estado . '"
							   data-sede-id="' . $codigo_encriptado_sede . '"
							   data-sede-nombre="' . htmlspecialchars($sede['SedeNombre']) . '">
								<i class="bi bi-' . $config['icono'] . ' me-2 text-' . $config['color'] . '"></i>
								Cambiar a ' . $estado . '
							</a>
						</li>';
				}
			}

			$dropdown_estados .= '
				</ul>
			</div>';

			$html .= '
				<div class="list-group-item list-group-item-action">
					<div class="d-flex w-100 justify-content-between align-items-start">
						<div class="flex-grow-1">
							<div class="d-flex justify-content-between align-items-center mb-2">
								<h6 class="mb-0">
									<i class="bi bi-buildings me-2 text-primary"></i>
									' . htmlspecialchars($sede['SedeNombre']) . '
								</h6>
								' . $dropdown_estados . '
							</div>

							<div class="row">
								<div class="col-md-6">
									<small class="text-muted d-block">
										<i class="bi bi-hash me-1"></i>
										Código: <span class="text-dark">' . htmlspecialchars($sede['SedeCodigo']) . '</span>
									</small>
									<small class="text-muted d-block">
										<i class="bi bi-card-text me-1"></i>
										NIT: <span class="text-dark">' . htmlspecialchars($sede['SedeNit']) . '</span>
									</small>
									<small class="text-muted d-block">
										<i class="bi bi-telephone me-1"></i>
										Teléfono: <span class="text-dark">' . htmlspecialchars($sede['SedeTelefono']) . '</span>
									</small>
								</div>
								<div class="col-md-6">
									<small class="text-muted d-block">
										<i class="bi bi-envelope me-1"></i>
										Email: <span class="text-dark">' . htmlspecialchars($sede['SedeEmail']) . '</span>
									</small>
									<small class="text-muted d-block">
										<i class="bi bi-geo-alt-fill me-1"></i>
										Dirección: <span class="text-dark">' . htmlspecialchars($sede['SedeDireccion']) . '</span>
									</small>
									<small class="text-muted d-block">
										<i class="bi bi-person me-1"></i>
										Representante: <span class="text-dark">' . htmlspecialchars($sede['SedeNomRepresentante']) . '</span>
									</small>
								</div>
							</div>
						</div>

						<div class="ms-3">
							<div class="btn-group-vertical btn-group-sm" role="group">';

			if($sede['SedeEstado'] !== 'Eliminado') {
				$html .= '		
								<button class="btn btn-outline-primary" onclick="verSede(\'' . $codigo_encriptado_sede . '\')" title="Ver/Editar">
									<i class="bi bi-pencil-square"></i>
								</button>
								<button class="btn btn-outline-danger" onclick="eliminarSede(\'' . $codigo_encriptado_sede . '\', \'' . htmlspecialchars($sede['SedeNombre']) . '\')" title="Eliminar">
									<i class="bi bi-trash"></i>
								</button>';
			}

			$html .= '
							</div>
						</div>
					</div>
				</div>
			';
		}

		$html .= '</div>';
		return $html;
	}
	
	//===========================================================================================================
	// OBTENER DATOS DE UNA SEDE ESPECÍFICA
	// Función para obtener todos los datos de una sede por su ID
	//===========================================================================================================

	public function obtener_sede_controlador(){
		/*-------------------//-------- PASO 1 LIMPIEZA, VALIDACIONES Y SEGURIDAD --------//-----------------------*/	
		/************ Marcar inicio del tiempo para normalizar respuestas *******************************************/
		$this->tiempo_inicio = microtime(true);

		/************ VALIDACION 1: Método POST *****************************************************************/
		if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
			$this->guardar_log('metodo_http_invalido_obtener_sede', [
				'datos_antes' => ['metodo_recibido' => $_SERVER['REQUEST_METHOD']],
				'datos_despues' => ['accion' => 'rechazado']
			], 'alto', 'bloqueado', 'App_empresa');

			return json_encode(["error" => "Method not allowed"]);
		}

		/************ VALIDACION 2: Verificar permisos **********************************************************/
		if(!$this->verificar_permisos('ver_sedes')){
			$this->guardar_log('sin_permisos_ver_sede', [
				'datos_antes' => ['usuario' => $_SESSION['CodigoUsuario']],
				'datos_despues' => ['accion' => 'bloqueado']
			], 'alto', 'bloqueado', 'App_empresa');

			$this->normalizar_tiempo_respuesta();
			return json_encode([
				"Titulo" => "Sin permisos",
				"Texto" => "No tienes permisos para ver sedes", 
				"Tipo" => "warning"
			], JSON_UNESCAPED_UNICODE);
		}

		/************ VALIDACION 3: Validar token CSRF **********************************************************/
		if(!isset($_POST['csrf_token_obtener_sede']) || !$this->validar_csrf($_POST['csrf_token_obtener_sede'], 'listEmpresas')){
			$this->guardar_log('csrf_token_invalido_obtener_sede', [
				'datos_antes' => ['sede_id' => $_POST['sede_id'] ?? 'no_definido'],
				'datos_despues' => ['accion' => 'bloqueado']
			], 'alto', 'bloqueado', 'App_empresa');

			$this->normalizar_tiempo_respuesta();
			return json_encode([
				"Titulo" => "Token inválido",
				"Texto" => "Token de seguridad inválido o expirado",
				"Tipo" => "warning"
			], JSON_UNESCAPED_UNICODE);
		}

		/************ SEPARAR código y token usando el separador encriptado *************************************/	
		$codigo_encriptado = $_POST['sede_id'] ?? '';

		// Intentar separar primero
		$data_separada = $this->separar_codigo_con_token($codigo_encriptado, 'separador_sede');

		if (!$data_separada) {
			return json_encode([
				"Titulo" => "Código inválido",
				"Texto" => "El código de sede no es válido o está corrupto",
				"Tipo" => "warning"
			], JSON_UNESCAPED_UNICODE);
		}

		$codigo_real = $data_separada['codigo'];
		$token_encriptado = $data_separada['token_encriptado'];

		/************ VALIDAR token CSRF del listado ***********************************************************/
		if (!$this->validar_csrf($token_encriptado, 'listEmpresas')) {
			$this->guardar_log('csrf_token_invalido_sede_separado', [
				'datos_antes' => ['codigo_sede' => $codigo_real],
				'datos_despues' => ['accion' => 'bloqueado']
			], 'alto', 'bloqueado', 'App_empresa');

			$this->normalizar_tiempo_respuesta();
			return json_encode([
				"Titulo" => "Token inválido",
				"Texto" => "Token de seguridad inválido o expirado",
				"Tipo" => "warning"
			], JSON_UNESCAPED_UNICODE);
		}

		/************ VALIDACION 4: Limpiar y validar ID de sede *********************************************/
		$resultado_limpieza = $this->limpiar_datos($codigo_real, 'numero', 'sede_id');

		if (!$resultado_limpieza['es_seguro']) {
			$this->normalizar_tiempo_respuesta();
			return json_encode([
				"Titulo" => "ID inválido",
				"Texto" => "El ID de sede no es válido",
				"Tipo" => "warning"
			], JSON_UNESCAPED_UNICODE);
		}

		$sede_id = $resultado_limpieza['dato_limpio'];

		/*-------------------//-------- PASO 2: OBTENER SEDE --------//------------------------------------*/

		try {
			/************ Obtener datos de la sede ***********************************************************/
			$sede = $this->obtener_sede_por_id($sede_id);

			if (!$sede) {
				$this->normalizar_tiempo_respuesta();
				return json_encode([
					"Titulo" => "Sede no encontrada",
					"Texto" => "La sede no existe o no está disponible",
					"Tipo" => "warning"
				], JSON_UNESCAPED_UNICODE);
			}

			/*-*-*-*-*-* SEGURIDAD: Generar token específico usando sistema genérico *-*-*-*-*-*/
			$datos_extra = [
				'codigo_sede' => $sede['SedeCodigo'],
				'nombre_sede' => $sede['SedeNombre'],
				'sucursal_id' => $sede['SedeIdSucursal']
			];
			$token_sede_especifico = $this->generar_token_entidad_especifico('sede', $sede_id, 1800, $datos_extra);

			/*-*-*-*-*-* SEGURIDAD: Encriptar el ID antes de enviarlo al frontend *-*-*-*-*-*/
			$sede['SedeId'] = $this->encryption($sede_id);

			/*-*-*-*-*-* SEGURIDAD: Agregar el token específico a la respuesta *-*-*-*-*-*/
			$sede['TokenSedeEspecifico'] = $token_sede_especifico;

			/*-*-*-*-*-* Log de consulta exitosa *-*-*-*-*-*/
			$this->guardar_log('sede_consultada', [
				'datos_antes' => [
					'sede_id_solicitado' => $sede_id
				],
				'datos_despues' => [
					'sede_encontrada' => $sede['SedeCodigo'],
					'nombre' => $sede['SedeNombre'],
					'sucursal' => $sede['SucursalNombre'],
					'empresa' => $sede['EmpresaNombre'],
					'id_encriptado' => 'SI',
					'token_sede_generado' => 'SI'					
				],
			], 'bajo', 'exito', 'App_empresa');

			$this->normalizar_tiempo_respuesta();
			return json_encode([
				"status" => "success",
				"sede" => $sede
			], JSON_UNESCAPED_UNICODE);

		} catch (Exception $e) {
			$this->guardar_log('error_obtener_sede', [
				'datos_antes' => ['sede_id' => $sede_id],
				'datos_despues' => ['error' => $e->getMessage()]
			], 'alto', 'error', 'App_empresa');

			$this->normalizar_tiempo_respuesta();
			return json_encode([
				"Titulo" => "Error interno",
				"Texto" => "Ocurrió un error al obtener la sede",
				"Tipo" => "error"
			], JSON_UNESCAPED_UNICODE);
		}
	}

	//===========================================================================================================
	// ACTUALIZAR SEDE
	// Función para actualizar los datos de una sede existente
	//===========================================================================================================

	public function actualizar_sede_controlador(){
		/*-------------------//-------- PASO 1 LIMPIEZA, VALIDACIONES Y SEGURIDAD --------//-----------------------*/	
		/************ Marcar inicio del tiempo para normalizar respuestas *******************************************/
		$this->tiempo_inicio = microtime(true);

		/************ VALIDACION 1: Método POST *****************************************************************/
		if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
			$this->guardar_log('metodo_http_invalido_actualizar_sede', [
				'datos_antes' => ['metodo_recibido' => $_SERVER['REQUEST_METHOD']],
				'datos_despues' => ['accion' => 'rechazado']
			], 'alto', 'bloqueado', 'App_empresa');

			return json_encode(["error" => "Method not allowed"]);
		}

		/************ VALIDACION 2: Detección de bots ***********************************************************/
		$numero_campos = count($_POST);
		$umbral_minimo = $numero_campos * 2; // 2 segundos por campo para actualizaciones
		$analisis_bot = $this->es_bot_sospechoso($umbral_minimo, $numero_campos);

		if ($analisis_bot['es_bot']) {
			$this->normalizar_tiempo_respuesta();
			return json_encode([
				"Titulo" => "Actividad Sospechosa",
				"Texto" => "Actividad detectada como automatizada", 
				"Tipo" => "warning"
			], JSON_UNESCAPED_UNICODE);
		}

		/************ VALIDACION 3: Verificar permisos **********************************************************/
		if(!$this->verificar_permisos('editar_sedes')){
			$this->guardar_log('sin_permisos_editar_sede', [
				'datos_antes' => ['usuario' => $_SESSION['CodigoUsuario']],
				'datos_despues' => ['accion' => 'bloqueado']
			], 'alto', 'bloqueado', 'App_empresa');

			$this->normalizar_tiempo_respuesta();
			return json_encode([
				"Titulo" => "Sin permisos",
				"Texto" => "No tienes permisos para editar sedes", 
				"Tipo" => "warning"
			], JSON_UNESCAPED_UNICODE);
		}

		/************ VALIDACION 4: Validar token CSRF **********************************************************/
		if(!isset($_POST['csrf_token_editar_sede']) || !$this->validar_csrf($_POST['csrf_token_editar_sede'], 'editarSede')){
			$this->guardar_log('csrf_token_invalido_editar_sede', [
				'datos_antes' => ['sede_id' => $_POST['sede_id'] ?? 'no_definido'],
				'datos_despues' => ['accion' => 'bloqueado']
			], 'alto', 'bloqueado', 'App_empresa');

			$this->normalizar_tiempo_respuesta();
			return json_encode([
				"Titulo" => "Token inválido",
				"Texto" => "Token de seguridad inválido o expirado",
				"Tipo" => "warning"
			], JSON_UNESCAPED_UNICODE);
		}

		/************ VALIDACION 5: Límite de intentos **********************************************************/
		if(!$this->verificar_intentos('actualizar_sede', 3, 300)){
			$this->normalizar_tiempo_respuesta();
			return json_encode([
				"Titulo" => "Demasiados intentos",
				"Texto" => "Has superado el límite de intentos. Espera 5 minutos",
				"Tipo" => "warning"
			], JSON_UNESCAPED_UNICODE);
		}

		/*-*-*-*-*-* SEGURIDAD: Desencriptar y validar ID de sede *-*-*-*-*-*/
		$sede_id_encriptado = $_POST['sede_id'] ?? '';
		$sede_id_desencriptado = $this->decryption($sede_id_encriptado);

		if (!$sede_id_desencriptado || !is_numeric($sede_id_desencriptado)) {
			$this->guardar_log('id_sede_invalido_actualizar', [
				'datos_antes' => ['id_recibido_hash' => hash('sha256', $sede_id_encriptado)],
				'datos_despues' => ['accion' => 'bloqueado', 'razon' => 'desencriptacion_fallida']
			], 'alto', 'bloqueado', 'App_empresa');

			$this->normalizar_tiempo_respuesta();
			return json_encode([
				"Titulo" => "ID inválido",
				"Texto" => "El identificador de sede no es válido",
				"Tipo" => "warning"
			], JSON_UNESCAPED_UNICODE);
		}

		/************ VALIDACION 6: Limpiar los datos recibidos *************************************************/ 
		$resultados_limpieza = [
			'SedeId' => $this->limpiar_datos($sede_id_desencriptado, 'numero', 'sede_id'),
			'SedeNit' => $this->limpiar_datos($_POST['sede_nit'], 'numero', 'sede_nit'),
			'SedeNombre' => $this->limpiar_datos($_POST['sede_nombre'], 'texto', 'sede_nombre'),
			'SedeDireccion' => $this->limpiar_datos($_POST['sede_direccion'], 'texto','sede_direccion'),
			'SedeTelefono' => $this->limpiar_datos($_POST['sede_telefono'], 'texto','sede_telefono'),
			'SedeEmail' => $this->limpiar_datos($_POST['sede_email'], 'email', 'sede_email'),
			'SedeIdRepresentante' => $this->limpiar_datos($_POST['sede_id_representante'], 'numero', 'sede_id_representante'),
			'SedeNomRepresentante' => $this->limpiar_datos($_POST['sede_nom_representante'], 'texto', 'sede_nom_representante')
		];

		/************ VALIDACION 7: Verificar ataques **********************************************************/
		$hay_ataques = false;
		$ataques_por_campo = [];

		foreach ($resultados_limpieza as $campo => $resultado) {
			if (!$resultado['es_seguro']) {
				$hay_ataques = true;
				$ataques_por_campo[$campo] = $resultado['ataques_detectados'];
			}  
		}

		if ($hay_ataques) {
			$nivel_riesgo_maximo = 'bajo';
			foreach ($resultados_limpieza as $resultado) {
				if ($resultado['nivel_riesgo'] === 'alto') {
					$nivel_riesgo_maximo = 'alto';
					break;
				} elseif ($resultado['nivel_riesgo'] === 'medio' && $nivel_riesgo_maximo !== 'alto') {
					$nivel_riesgo_maximo = 'medio';
				}
			}

			$this->guardar_log('formulario_rechazado_por_ataques_actualizar_sede', [
				'datos_antes' => [
					'campos_con_ataques' => array_keys($ataques_por_campo),
					'resumen_ataques' => $ataques_por_campo
				],
				'datos_despues' => ['accion_tomada' => 'formulario_rechazado_completamente'],
			], $nivel_riesgo_maximo, 'rechazado', 'seguridad'); 

			$this->normalizar_tiempo_respuesta();
			return json_encode([
				"Titulo" => "Datos no válidos",
				"Texto" => "Los datos enviados contienen información no permitida.",
				"Tipo" => "warning"
			], JSON_UNESCAPED_UNICODE);
		}

		/************ VALIDACION 8: Extraer datos limpios ******************************************************/
		$datos_sede = [];
		foreach ($resultados_limpieza as $campo => $resultado) {
			$datos_sede[$campo] = $resultado['dato_limpio'];
		}

		/*-*-*-*-*-* SEGURIDAD EXTRA: Validar token específico usando sistema genérico *-*-*-*-*-*/
		$token_sede_recibido = $_POST['token_sede_especifico'] ?? '';

		if (!$this->validar_token_entidad_especifico('sede', $sede_id_desencriptado, $token_sede_recibido)) {
			$this->normalizar_tiempo_respuesta();
			return json_encode([
				"Titulo" => "Token de sede inválido",
				"Texto" => "El token de autorización para esta sede no es válido o ha expirado. Vuelve a cargar la sede.",
				"Tipo" => "warning"
			], JSON_UNESCAPED_UNICODE);
		}

		/************ VALIDACION 9: Validar campos obligatorios ************************************************/
		$campos_excluir = ['SedeId', 'SedeCodigo', 'SedeFechaRegistro', 'SedeFechaActualizacion', 'SedeEstado', 'SedeIdSucursal'];

		$reglas_personalizadas = [
			'SedeNit' => ['min_caracteres' => 8],
			'SedeTelefono' => ['min_caracteres' => 7],
			'SedeNomRepresentante' => ['solo_letras' => true]
		];

		$errores = $this->validar_completo($datos_sede, 'App_empresa_sede', $campos_excluir, $reglas_personalizadas);

		if(!empty($errores)){
			$mensaje_error = "Errores encontrados:\n";
			foreach($errores as $campo => $errores_campo){
				$mensaje_error .= "- " . implode(", ", $errores_campo) . "\n";
			}

			$this->normalizar_tiempo_respuesta();
			return json_encode([
				"Titulo" => "Datos incorrectos",
				"Texto" => $mensaje_error,
				"Tipo" => "warning"
			], JSON_UNESCAPED_UNICODE);
		}

		/*-------------------//-------- PASO 2: ACTUALIZAR SEDE --------//----------------------------------*/

		try {
			/************ Verificar que la sede existe *******************************************************/
			$sede_actual = $this->obtener_sede_por_id($datos_sede['SedeId']);
			if (!$sede_actual) {
				$this->normalizar_tiempo_respuesta();
				return json_encode([
					"Titulo" => "Sede no encontrada",
					"Texto" => "La sede no existe o ya fue eliminada",
					"Tipo" => "warning"
				], JSON_UNESCAPED_UNICODE);
			}

			/************ Verificar duplicados (excluyendo la sede actual) ***********************************/
			if($this->verificar_nit_sede_duplicado_actualizar($datos_sede['SedeNit'], $datos_sede['SedeId'])){
				$this->normalizar_tiempo_respuesta();
				return json_encode([
					"Titulo" => "NIT duplicado",
					"Texto" => "Ya existe otra sede registrada con este NIT", 
					"Tipo" => "warning"
				], JSON_UNESCAPED_UNICODE);
			}

			if($this->verificar_email_sede_duplicado_actualizar($datos_sede['SedeEmail'], $datos_sede['SedeId'])){
				$this->normalizar_tiempo_respuesta();
				return json_encode([
					"Titulo" => "Email duplicado",
					"Texto" => "Ya existe otra sede registrada con este email",
					"Tipo" => "warning"
				], JSON_UNESCAPED_UNICODE);
			}

			/************ Preparar datos finales ***************************************************************/
			$datos_finales = [
				'id' => $datos_sede['SedeId'],
				'nit' => $datos_sede['SedeNit'],
				'nombre' => $datos_sede['SedeNombre'],
				'direccion' => $datos_sede['SedeDireccion'],
				'telefono' => $datos_sede['SedeTelefono'],
				'email' => $datos_sede['SedeEmail'],
				'id_representante' => $datos_sede['SedeIdRepresentante'],
				'nom_representante' => $datos_sede['SedeNomRepresentante'],
				'fecha_actualizacion' => date("Y-m-d H:i:s")
			];

			/************ Actualizar sede ****************************************************************/
			$resultado = $this->actualizar_sede_modelo($datos_finales);

			if($resultado){
				/*-*-*-*-*-* Log de actualización exitosa *-*-*-*-*-*/
				$this->guardar_log('sede_actualizada', [
					'datos_antes' => [
						'sede_id' => $datos_sede['SedeId'],
						'datos_anteriores' => $sede_actual
					],
					'datos_despues' => [
						'resultado' => 'sede_actualizada',
						'datos_nuevos' => $datos_finales
					],
				], 'medio', 'exito', 'App_empresa');

				/*-*-*-*-*-* Eliminar token usado *-*-*-*-*-*/
				$this->eliminar_token_csrf('editarSede');

				$this->normalizar_tiempo_respuesta();
				return json_encode([
					"Titulo" => "Sede actualizada",
					"Texto" => "Los datos de la sede se han actualizado exitosamente",
					"Tipo" => "success"
				], JSON_UNESCAPED_UNICODE);

			} else {
				$this->normalizar_tiempo_respuesta();
				return json_encode([
					"Titulo" => "Error",
					"Texto" => "No se pudo actualizar la sede. Intenta nuevamente",
					"Tipo" => "error"
				], JSON_UNESCAPED_UNICODE);
			}

		} catch (Exception $e) {
			$this->guardar_log('error_actualizar_sede', [
				'datos_antes' => ['sede_id' => $datos_sede['SedeId']],
				'datos_despues' => ['error' => $e->getMessage()]
			], 'alto', 'error', 'App_empresa');

			$this->normalizar_tiempo_respuesta();
			return json_encode([
				"Titulo" => "Error interno",
				"Texto" => "Ocurrió un error al actualizar la sede",
				"Tipo" => "error"
			], JSON_UNESCAPED_UNICODE);
		}
	}

	//===========================================================================================================
	// ELIMINAR SEDE (SOFT DELETE)
	// Función para eliminar sede, solo le cambia el estado a eliminado pero no la elimina
	//===========================================================================================================

	public function eliminar_sede_controlador(){
		/*-------------------//-------- PASO 1 LIMPIEZA, VALIDACIONES Y SEGURIDAD --------//-----------------------*/	
		/************ Marcar inicio del tiempo para normalizar respuestas *******************************************/
		$this->tiempo_inicio = microtime(true);

		/************ VALIDACION 1: Método POST *****************************************************************/
		if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
			$this->guardar_log('metodo_http_invalido_eliminar_sede', [
				'datos_antes' => ['metodo_recibido' => $_SERVER['REQUEST_METHOD']],
				'datos_despues' => ['accion' => 'rechazado']
			], 'alto', 'bloqueado', 'App_empresa');

			return json_encode(["error" => "Method not allowed"]);
		}

		/************ VALIDACION 2: Detección de bots ***********************************************************/
		$numero_campos = count($_POST);
		$umbral_minimo = $numero_campos * 1; // 1 segundo por campo para eliminaciones
		$analisis_bot = $this->es_bot_sospechoso($umbral_minimo, $numero_campos);

		if ($analisis_bot['es_bot']) {
			$this->normalizar_tiempo_respuesta();
			return json_encode([
				"Titulo" => "Actividad Sospechosa",
				"Texto" => "Actividad detectada como automatizada", 
				"Tipo" => "warning"
			], JSON_UNESCAPED_UNICODE);
		}

		/************ VALIDACION 3: Verificar permisos **********************************************************/
		if(!$this->verificar_permisos('eliminar_sedes')){
			$this->guardar_log('sin_permisos_eliminar_sede', [
				'datos_antes' => ['usuario' => $_SESSION['CodigoUsuario']],
				'datos_despues' => ['accion' => 'bloqueado']
			], 'alto', 'bloqueado', 'App_empresa');

			$this->normalizar_tiempo_respuesta();
			return json_encode([
				"Titulo" => "Sin permisos",
				"Texto" => "No tienes permisos para eliminar sedes", 
				"Tipo" => "warning"
			], JSON_UNESCAPED_UNICODE);
		}

		/************ VALIDACION 4: Límite de intentos **********************************************************/
		if(!$this->verificar_intentos('eliminar_sede', 3, 300)){
			$this->normalizar_tiempo_respuesta();
			return json_encode([
				"Titulo" => "Demasiados intentos",
				"Texto" => "Has superado el límite de intentos. Espera 5 minutos",
				"Tipo" => "warning"
			], JSON_UNESCAPED_UNICODE);
		}

		/************ SEPARAR código y token usando el separador encriptado *************************************/	
		$codigo_encriptado = $_POST['sede_id'] ?? '';

		// Intentar separar primero
		$data_separada = $this->separar_codigo_con_token($codigo_encriptado, 'separador_sede');

		if (!$data_separada) {
			return json_encode([
				"Titulo" => "Código inválido",
				"Texto" => "El código de sede no es válido o está corrupto",
				"Tipo" => "warning"
			], JSON_UNESCAPED_UNICODE);
		}

		$codigo_real = $data_separada['codigo'];
		$token_encriptado = $data_separada['token_encriptado'];

		/************ VALIDAR token CSRF ************************************************************************/
		if (!$this->validar_csrf($token_encriptado, 'listEmpresas')) {
			$this->guardar_log('csrf_token_invalido_eliminar_sede', [
				'datos_antes' => ['codigo_sede' => $codigo_real],
				'datos_despues' => ['accion' => 'bloqueado']
			], 'alto', 'bloqueado', 'App_empresa');

			$this->normalizar_tiempo_respuesta();
			return json_encode([
				"Titulo" => "Token inválido",
				"Texto" => "Token de seguridad inválido o expirado",
				"Tipo" => "warning"
			], JSON_UNESCAPED_UNICODE);
		}

		/*-------------------//-------- PASO 2: ELIMINAR SEDE --------//------------------------------------*/

		/************ Verificar que la sede existe ************************************************************/
		$sede = $this->obtener_sede_por_id($codigo_real);
		if (!$sede) {
			$this->normalizar_tiempo_respuesta();
			return json_encode([
				"Titulo" => "Sede no encontrada",
				"Texto" => "La sede no existe o ya fue eliminada",
				"Tipo" => "warning"
			], JSON_UNESCAPED_UNICODE);
		}

		/************ Realizar eliminación (soft delete) ********************************************************/
		$resultado = $this->eliminar_sede_modelo($sede['SedeId']);
		if ($resultado) {
			/*-*-*-*-*-* Logs de eliminación exitosa *-*-*-*-*-*/
			$this->guardar_log('sede_eliminada', [
				'datos_antes' => [
					'codigo' => $codigo_real,
					'nombre' => $sede['SedeNombre'],
					'id' => $sede['SedeId'],
					'sucursal' => $sede['SucursalNombre'],
					'empresa' => $sede['EmpresaNombre']
				],
				'datos_despues' => [
					'resultado' => 'sede_eliminada',
					'estado_anterior' => $sede['SedeEstado']
				],
			], 'medio', 'exito', 'App_empresa');

			$this->normalizar_tiempo_respuesta();
			return json_encode([
				"Titulo" => "Sede eliminada",
				"Texto" => "La sede '" . $sede['SedeNombre'] . "' ha sido eliminada exitosamente",
				"Tipo" => "success"
			], JSON_UNESCAPED_UNICODE);

		} else {
			$this->normalizar_tiempo_respuesta();
			return json_encode([
				"Titulo" => "Error",
				"Texto" => "No se pudo eliminar la sede. Intenta nuevamente",
				"Tipo" => "error"
			], JSON_UNESCAPED_UNICODE);
		}
	}

	//===========================================================================================================
	// CAMBIAR ESTADO DE SEDE
	// Función para cambiar el estado de una sede (Activo/Inactivo/Suspendido)
	//===========================================================================================================

	public function cambiar_estado_sede_controlador(){
		/*-------------------//-------- VALIDACIONES DE SEGURIDAD --------//-----------------------*/	
		/************ Marcar inicio del tiempo para normalizar respuestas *******************************************/
		$this->tiempo_inicio = microtime(true);

		/************ VALIDACION 1: Método POST *****************************************************************/
		if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
			$this->guardar_log('metodo_http_invalido_cambiar_estado_sede', [
				'datos_antes' => ['metodo_recibido' => $_SERVER['REQUEST_METHOD']],
				'datos_despues' => ['accion' => 'rechazado']
			], 'alto', 'bloqueado', 'App_empresa');

			return json_encode(["error" => "Method not allowed"]);
		}

		/************ VALIDACION 2: Detección de bots ***********************************************************/
		$numero_campos = count($_POST);
		$umbral_minimo = $numero_campos * 2; // 2 segundos por campo para actualizaciones
		$analisis_bot = $this->es_bot_sospechoso($umbral_minimo, $numero_campos);

		if ($analisis_bot['es_bot']) {
			$this->normalizar_tiempo_respuesta();
			return json_encode([
				"Titulo" => "Actividad Sospechosa",
				"Texto" => "Actividad detectada como automatizada", 
				"Tipo" => "warning"
			], JSON_UNESCAPED_UNICODE);
		}

		/************ VALIDACION 3: Verificar permisos **********************************************************/
		if(!$this->verificar_permisos('cambiar_estado_sedes')){
			$this->guardar_log('sin_permisos_cambiar_estado_sedes', [
				'datos_antes' => ['usuario' => $_SESSION['CodigoUsuario']],
				'datos_despues' => ['accion' => 'bloqueado']
			], 'alto', 'bloqueado', 'App_empresa');

			$this->normalizar_tiempo_respuesta();
			return json_encode([
				"Titulo" => "Sin permisos",
				"Texto" => "No tienes permisos para cambiar estado de sedes", 
				"Tipo" => "warning"
			], JSON_UNESCAPED_UNICODE);
		}

		/************ VALIDACION 4: Límite de intentos **********************************************************/
		if(!$this->verificar_intentos('cambiar_estado_sede', 3, 300)){
			$this->normalizar_tiempo_respuesta();
			return json_encode([
				"Titulo" => "Demasiados intentos",
				"Texto" => "Has superado el límite de intentos. Espera 5 minutos",
				"Tipo" => "warning"
			], JSON_UNESCAPED_UNICODE);
		}

		/************ SEPARAR código y token usando el separador encriptado *************************************/	
		$codigo_encriptado = $_POST['sede_id'] ?? '';

		// Intentar separar primero
		$data_separada = $this->separar_codigo_con_token($codigo_encriptado, 'separador_sede');

		if (!$data_separada) {
			return json_encode([
				"Titulo" => "Código inválido",
				"Texto" => "El código de sede no es válido o está corrupto",
				"Tipo" => "warning"
			], JSON_UNESCAPED_UNICODE);
		}

		$codigo_real = $data_separada['codigo'];
		$token_encriptado = $data_separada['token_encriptado'];

		/************ VALIDACION 5: VALIDAR token CSRF del listado ***********************************************/
		if (!$this->validar_csrf($token_encriptado, 'listEmpresas')) {
			$this->guardar_log('csrf_token_invalido_lista_cambio_estado_sede', [
				'datos_antes' => ['codigo_sede' => $codigo_real],
				'datos_despues' => ['accion' => 'bloqueado']
			], 'alto', 'bloqueado', 'App_empresa');

			$this->normalizar_tiempo_respuesta();
			return json_encode([
				"Titulo" => "Token inválido",
				"Texto" => "Token de seguridad inválido o expirado",
				"Tipo" => "warning"
			], JSON_UNESCAPED_UNICODE);
		}	

		/************ VALIDACION 6: Validar token CSRF del cambio de estado ****************************************/
		if(!isset($_POST['csrf_token_estado_sede']) || !$this->validar_csrf($_POST['csrf_token_estado_sede'], 'listEmpresas')){
			$this->guardar_log('csrf_token_invalido_cambiar_estado_sede', [
				'datos_antes' => ['sede_id' => $codigo_real ?? 'no_definido'],
				'datos_despues' => ['accion' => 'bloqueado']
			], 'alto', 'bloqueado', 'App_empresa');

			$this->normalizar_tiempo_respuesta();
			return json_encode([
				"Titulo" => "Token inválido",
				"Texto" => "Token de seguridad inválido o expirado",
				"Tipo" => "warning"
			], JSON_UNESCAPED_UNICODE);
		}

		/*-------------------//-------- LIMPIEZA Y VALIDACIÓN DE DATOS --------//---------------------------*/

		$resultados_limpieza = [
			'sede_id' => $this->limpiar_datos($codigo_real, 'numero', 'sede_id'),
			'nuevo_estado' => $this->limpiar_datos($_POST['nuevo_estado'], 'texto', 'nuevo_estado'),
			'motivo_cambio' => $this->limpiar_datos($_POST['motivo_cambio'], 'texto', 'motivo_cambio')
		];

		/************ VALIDACION 7: Verifico si algun campo tenía ataques, sale de la limpieza realizada ***********/
		$hay_ataques = false;
		$ataques_por_campo = [];
		/*-*-*-*-*-* reviso cada campo *-*-*-*-*-*/
		foreach ($resultados_limpieza as $campo => $resultado) {
			if (!$resultado['es_seguro']) {
				$hay_ataques = true;
				$ataques_por_campo[$campo] = $resultado['ataques_detectados'];
			}  
		}
		/*-*-*-*-*-* si hay ataques RECHAZO completamente *-*-*-*-*-*/
		if ($hay_ataques) {
			/*-*-*-*-*-*calculo el nivel de riesgo *-*-*-*-*-*/
			$nivel_riesgo_maximo = 'bajo';
			foreach ($resultados_limpieza as $resultado) {
				if ($resultado['nivel_riesgo'] === 'alto') {
					$nivel_riesgo_maximo = 'alto';
					break;
				} elseif ($resultado['nivel_riesgo'] === 'medio' && $nivel_riesgo_maximo !== 'alto') {
					$nivel_riesgo_maximo = 'medio';
				}
			}
			/*-*-*-*-*-* registro logs de seguimiento y auditoria *-*-*-*-*-*/
			$this->guardar_log(
				'formulario_rechazado_por_ataques_cambio_estado_sede', [
					'datos_antes' => [
						'campos_con_ataques' => array_keys($ataques_por_campo),
						'total_campos_afectados' => count($ataques_por_campo),
						'resumen_ataques' => $ataques_por_campo
					],
					'datos_despues' => [
						'accion_tomada' => 'formulario_rechazado_completamente'
					],
				],
				$nivel_riesgo_maximo, 'rechazado', 'seguridad'); 

			/*-*-*-*-*-* Normalizamos el tiempo de respuesta *-*-*-*-*-*/           
			$this->normalizar_tiempo_respuesta();
			/*-*-*-*-*-* Termino función para registro de empresa *-*-*-*-*-*/
			return json_encode([
				"Titulo" => "Datos no válidos",
				"Texto" => "Los datos enviados contienen información no permitida.",
				"Tipo" => "warning"
			], JSON_UNESCAPED_UNICODE);
		}

		// Extraer datos limpios
		$sede_id = $resultados_limpieza['sede_id']['dato_limpio'];
		$nuevo_estado = $resultados_limpieza['nuevo_estado']['dato_limpio'];
		$motivo_cambio = $resultados_limpieza['motivo_cambio']['dato_limpio'];

		/*-------------------//-------- VALIDACIONES DE NEGOCIO --------//---------------------------*/

		// Validar que el estado sea válido
		$estados_permitidos = ['Activo', 'Inactivo', 'Suspendido'];
		if (!in_array($nuevo_estado, $estados_permitidos)) {
			/*-*-*-*-*-* registro logs de seguimiento y auditoria *-*-*-*-*-*/
			$this->guardar_log('Estado_sede_no_permitido', [
				'datos_antes' => [
						'estado_recibido'=> $nuevo_estado
					],
				'datos_despues' => [
					'accion' => 'bloqueado',
					'usuario'=>$_SESSION['UsuarioId'] ?? 'anonimo'
				]
			], 'medio', 'bloqueado', 'App_empresa');

			$this->normalizar_tiempo_respuesta();
			return json_encode([
				"Titulo" => "Estado no válido",
				"Texto" => "El estado seleccionado no es válido",
				"Tipo" => "warning"
			], JSON_UNESCAPED_UNICODE);
		}

		// Validar motivo
		if (strlen($motivo_cambio) < 10) {
			/*-*-*-*-*-* registro logs de seguimiento y auditoria *-*-*-*-*-*/
			$this->guardar_log('Estado_sede_longitud', [
				'datos_antes' => [
						'estado_actual'=> 'motivo del cambio de estado'
					],
				'datos_despues' => [
					'longitud'=> 'la longitud del motivo de cambio es inferior a 10 caracteres',
					'accion'=>'No se realiza el cambio',
					'estado_nuevo' => $nuevo_estado,
					'motivo' => $motivo_cambio,
					'usuario_cambio' => $_SESSION['UsuarioId'] ?? 'anonimo'
				]
			], 'medio', 'bloqueado', 'App_empresa');

			$this->normalizar_tiempo_respuesta();
			return json_encode([
				"Titulo" => "Motivo muy corto",
				"Texto" => "El motivo debe tener al menos 10 caracteres",
				"Tipo" => "warning"
			], JSON_UNESCAPED_UNICODE);
		}

		/*-------------------//-------- PROCESAR CAMBIO DE ESTADO --------//---------------------------*/

		try {
			// Obtener datos actuales de la sede
			$sede_actual = $this->obtener_sede_por_id($sede_id);
			if (!$sede_actual) {
				$this->normalizar_tiempo_respuesta();
				return json_encode([
					"Titulo" => "Sede no encontrada",
					"Texto" => "La sede no existe o ya fue eliminada",
					"Tipo" => "warning"
				], JSON_UNESCAPED_UNICODE);
			}

			// Verificar que el estado realmente va a cambiar
			if ($sede_actual['SedeEstado'] === $nuevo_estado) {
				$this->normalizar_tiempo_respuesta();
				return json_encode([
					"Titulo" => "Sin cambios",
					"Texto" => "La sede ya tiene el estado: " . $nuevo_estado,
					"Tipo" => "info"
				], JSON_UNESCAPED_UNICODE);
			}

			// Ejecutar cambio de estado
			$resultado = $this->cambiar_estado_sede_modelo($sede_id, $nuevo_estado, $motivo_cambio);

			if ($resultado) {
				// Log del cambio exitoso
				$this->guardar_log('estado_sede_cambiado', [
					'datos_antes' => [
						'sede_id' => $sede_id,
						'codigo_sede' => $sede_actual['SedeCodigo'],
						'nombre_sede' => $sede_actual['SedeNombre'],
						'sucursal_nombre' => $sede_actual['SucursalNombre'],
						'empresa_nombre' => $sede_actual['EmpresaNombre'],
						'estado_anterior' => $sede_actual['SedeEstado']
					],
					'datos_despues' => [
						'estado_nuevo' => $nuevo_estado,
						'motivo' => $motivo_cambio,
						'usuario_cambio' => $_SESSION['UsuarioId'] ?? 'anonimo'
					],
				], 'medio', 'exito', 'App_empresa');

				$this->normalizar_tiempo_respuesta();
				return json_encode([
					"Titulo" => "Estado actualizado",
					"Texto" => "El estado de la sede se cambió exitosamente a: " . $nuevo_estado,
					"Tipo" => "success"
				], JSON_UNESCAPED_UNICODE);

			} else {
				$this->normalizar_tiempo_respuesta();
				return json_encode([
					"Titulo" => "Error",
					"Texto" => "No se pudo cambiar el estado de la sede",
					"Tipo" => "error"
				], JSON_UNESCAPED_UNICODE);
			}

		} catch (Exception $e) {
			$this->guardar_log('error_cambiar_estado_sede', [
				'datos_antes' => ['sede_id' => $sede_id, 'nuevo_estado' => $nuevo_estado],
				'datos_despues' => ['error' => $e->getMessage()]
			], 'alto', 'error', 'App_empresa');

			$this->normalizar_tiempo_respuesta();
			return json_encode([
				"Titulo" => "Error interno",
				"Texto" => "Ocurrió un error al cambiar el estado",
				"Tipo" => "error"
			], JSON_UNESCAPED_UNICODE);
		}
	}

}

?>