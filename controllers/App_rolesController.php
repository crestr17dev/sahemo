<?php
 	//===========================================================================================================
    // CONTROLADOR DE ROLES -
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

	//===========================================================================================================
    // REGISTRAR NUEVO ROL - COPIADO EXACTO DE USUARIOS
    // Función con todos los parámetros para registrar un nuevo rol de manera segura en BD
    //===========================================================================================================

    public function registrar_rol_controlador(){
		
	/*-------------------//-------- PASO 1 LIMPIEZA, VALIDACIONES Y SEGURIDAD --------//-----------------------*/	
    /************ Marcar inicio del tiempo para función normalizar el tiempo de respuestas ********************/
        $this->tiempo_inicio = microtime(true);
		
    /************ VALIDACION 1: valida si el método es POST de lo contrario bloquea proceso ********************/
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
			/*-*-*-*-*-* registro logs de seguimiento y auditoría *-*-*-*-*-*/
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
				'alto', 'bloqueado', 'App_roles');

            http_response_code(405); // Method Not Allowed
            return json_encode(["error" => "Method not allowed"]);
        }
        
    /************ VALIDACION 2: Detectar llenado automatizado (ataques con bot) **************************/
        $numero_campos = count($_POST); // contar los campos recibidos
        $tiempo_por_campo = 2; // segundos mínimos por campo
        $umbral_minimo = ($numero_campos - 1) * $tiempo_por_campo;
        /*-*-*-*-*-* llamo función para detectar bots atacantes *-*-*-*-*-*/
        $analisis_bot = $this->es_bot_sospechoso($umbral_minimo,$numero_campos);
        
        if ($analisis_bot['es_bot']) {
            /*-*-*-*-*-* Normalizamos el tiempo de respuesta *-*-*-*-*-*/           
            $this->normalizar_tiempo_respuesta();
			/*-*-*-*-*-* Termino función para registro de rol *-*-*-*-*-*/ 
            return  json_encode([
				"Alerta" => "simple",
				"Titulo" => "Actividad Inapropiada",
				"Texto" => "Actividad sospechosa detectada", 
				"Tipo" => "warning"
			], JSON_UNESCAPED_UNICODE);
        }
		
	/************ VALIDACION 3: Verificar si trae el token que se creó previamente para formulario rol ******/
        if(!isset($_POST['csrf_token']) || !$this->validar_csrf($_POST['csrf_token'],'nuevoRol')){
			/*-*-*-*-*-* registro logs de seguimiento y auditoría *-*-*-*-*-*/
			$this->guardar_log(
				'csrf_token_invalido', [
					'datos_antes' => [
						'Token_creado'=>'Diferente al recibido'
						],
					'datos_despues' => [
						'formulario' => 'registerRol',
						'token_recibido_hash' => isset($_POST['csrf_token_register_rol']) ? hash('sha256', $_POST['csrf_token_register_rol']) : 'no_enviado',
						'session_id' => session_id(),
						'CodigoUsuario' => $_SESSION['CodigoUsuario'] ?? 'no_definido',
						'UsuarioId' => $_SESSION['UsuarioId'] ?? 'no_definido'
					]
				],
				'alto', 'bloqueado', 'App_roles');
			
            /*-*-*-*-*-* Normalizamos el tiempo de respuesta *-*-*-*-*-*/           
            $this->normalizar_tiempo_respuesta();
			/*-*-*-*-*-* Termino función para registro de rol *-*-*-*-*-*/
			return  json_encode([
				"Alerta" => "simple",
				"Titulo" => "Token inválido",
				"Texto" => "Token de seguridad inválido. Recarga la página e intenta nuevamente", 
				"Tipo" => "warning"
			], JSON_UNESCAPED_UNICODE);
			
        }

	/************ VALIDACION 4: verificar permisos del usuario *************************************************/
        if(!$this->verificar_permisos('rol_crear')){
			/*-*-*-*-*-* registro logs de seguimiento y auditoría *-*-*-*-*-*/
			$this->guardar_log(
				'Rol sin permisos para crear rol', [
					'datos_antes' => [
						'usuario'=>'sin permisos'
					],
					'datos_despues' => [
						'CodigoUsuario' => $_SESSION['CodigoUsuario'],
						'UsuarioId' => $_SESSION['UsuarioId']
					],
				],
				'alto', 'bloqueado', 'App_roles');
			/*-*-*-*-*-* Normalizamos el tiempo de respuesta *-*-*-*-*-*/           
            $this->normalizar_tiempo_respuesta();
			/*-*-*-*-*-* Termino función para registro de rol *-*-*-*-*-*/
            
			return  json_encode([
				"Alerta" => "simple",
                "Titulo" => "Sin permisos",
                "Texto" => "No tienes permisos para registrar roles", 
				"Tipo" => "warning"
			], JSON_UNESCAPED_UNICODE);
        }
        
	/************ VALIDACION 5: Límite de intentos para el envío de información, max 3 bloqueo 5 minutos *******/
        if(!$this->verificar_intentos('registro_rol', 3, 300)){
            /*-*-*-*-*-* registro logs de seguimiento y auditoría *-*-*-*-*-*/
			$this->guardar_log(
				'registro_rol', [
					'datos_antes' => [
						'intentos'=>'Usuario inicia intento de registro'
					],
					'datos_despues' => [
						'intentos'=>'Usuario alcanzó el máximo de intentos permitidos 3',
						'CodigoUsuario' => $_SESSION['CodigoUsuario'],
						'UsuarioId' => $_SESSION['UsuarioId']
					],
				],
				'alto', 'bloqueado', 'App_roles');
            /*-*-*-*-*-* Normalizamos el tiempo de respuesta *-*-*-*-*-*/           
            $this->normalizar_tiempo_respuesta();
			/*-*-*-*-*-* Termino función para registro de rol *-*-*-*-*-*/
            
			return  json_encode([
				"Alerta" => "simple",
                "Titulo" => "Demasiados intentos",
                "Texto" => "Has superado el límite de intentos. Espera 5 minutos", 
				"Tipo" => "warning"
			], JSON_UNESCAPED_UNICODE);
        }
        
	/************ VALIDACION 6: Limpiar y validar datos individualmente ***********************************/
        $resultados_limpieza = [
            'RolNombre' => $this->limpiar_datos($_POST['rol-nombre'], 'texto', 'rol-nombre'),
            'RolDescripcion' => $this->limpiar_datos($_POST['rol-descripcion'], 'texto', 'rol-descripcion')          
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
			/*-*-*-*-*-* Termino función para registro de rol *-*-*-*-*-*/
            return json_encode([
                "Titulo" => "Datos no válidos",
                "Texto" => "Los datos enviados contienen información no permitida.",
                "Tipo" => "warning"
            ], JSON_UNESCAPED_UNICODE);
        }

	/************ VALIDACION 8: Si no hay ataques, extraer datos limpios y continuar ***************************/
        $datos_rol = [];
        foreach ($resultados_limpieza as $campo => $resultado) {
            $datos_rol[$campo] = $resultado['dato_limpio'];
        }
        
	/************ VALIDACION 9: verifico los campos obligatorios desde la BD *********************************/
        /*-*-*-*-*-* excluyo los campos que son automáticos en BD *-*-*-*-*-*/
		$campos_excluir = ['RolId', 'RolCodigo', 'RolNivel', 'RolFechaCreacion', 'RolEstado'];

		/*-*-*-*-*-* creo reglas personalizadas que complementan las de base de datos *-*-*-*-*-*/
		$reglas_personalizadas = [
			'RolNombre' => [
				'min_caracteres' => 5  // Mínimo 3 caracteres para nombre
			],
			'RolDescripcion' => [
				'min_caracteres' => 10  // Mínimo 3 caracteres para nombre
			]
			
		];

		/*-*-*-*-*-* valida todos los campos unificando las reglas *-*-*-*-*-*/
		$errores = $this->validar_completo(
			$datos_rol, 
			'App_usuarios_rol', 
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
			/*-*-*-*-*-* Termino función para registro de usuario *-*-*-*-*-*/
			return json_encode([
				"Alerta" => "simple",
				"Titulo" => "Datos incorrectos",
				"Texto" => $mensaje_error,
				"Tipo" => "warning"
			], JSON_UNESCAPED_UNICODE);
		}
		
		/************ Generar código único para el rol **************************************************/
		$codigo_rol = $this->generar_codigo_rol($datos_rol['RolNombre']);
		
		/*-------------------//-------- PASO 2: REGISTRO DEL ROL --------//----------------------------------*/

		/************ Verificar que no exista un rol con el mismo nombre ***************************************/
		if($this->verificar_nombre_rol_duplicado($codigo_rol)){
		/*-*-*-*-*-* Normalizamos el tiempo de respuesta *-*-*-*-*-*/           
			$this->normalizar_tiempo_respuesta();
		/*-*-*-*-*-* Termino función para registro de usuario *-*-*-*-*-*/
			return json_encode([
				"Titulo" => "Nombre duplicado",
				"Texto" => "Ya existe un rol con este nombre", 
				"Tipo" => "warning"
			], JSON_UNESCAPED_UNICODE);
		}
		/********* consulto el maximo numeor de nivel de rol ******************/
		$sql = "SELECT MAX(RolNivel) AS RolNivel FROM App_usuarios_rol";
		$resultado = $this->ejecutar_consulta_segura($sql, []);
		$fila = $resultado->fetch();

		// Manejo del caso cuando la tabla está vacía (MAX retorna NULL)
		$siguienteRol = ($fila && $fila['RolNivel'] !== null) ? $fila['RolNivel'] + 1 : 1;

		/************ Preparar datos finales para registro **********************************************/
		$datos_finales = [
			'RolCodigo' => $codigo_rol,
			'RolNombre' => $datos_rol['RolNombre'],
			'RolDescripcion' => $datos_rol['RolDescripcion'],
			'RolNivel' => (int)$siguienteRol,
			'RolFechaCreacion' => date("Y-m-d H:i:s")
		];

		/************ Registrar rol en la base de datos ************************************************/
		$resultado = $this->registrar_rol_modelo($datos_finales);

		if ($resultado) {
			/*-*-*-*-*-* Logs de registro exitoso *-*-*-*-*-*/
			$this->guardar_log('rol_registrado', [
				'datos_antes' => [
					'accion' => 'crear_nuevo_rol'
				],
				'datos_despues' => [
					'codigo_rol' => $codigo_rol,
					'nombre_rol' => $datos_rol['RolNombre'],
					'nivel_rol' => $siguienteRol,
					'resultado' => 'rol_creado_exitosamente'
				],
			], 'bajo', 'exito', 'App_roles');

			/*-*-*-*-*-* elimino el token que le había creado al formulario *-*-*-*-*-*/
			$this->eliminar_token_csrf('nuevoRol');

			$this->normalizar_tiempo_respuesta();
			return json_encode([
				"Titulo" => "Rol registrado",
				"Texto" => "El rol '" . $datos_rol['RolNombre'] . "' ha sido registrado correctamente",
				"Tipo" => "success",
				"nuevo_token" => $nuevo_token
			], JSON_UNESCAPED_UNICODE);

		} else {
			/*-*-*-*-*-* si registro falló *-*-*-*-*-*/
			/*-*-*-*-*-* registro logs de seguimiento y auditoría *-*-*-*-*-*/
			$this->guardar_log(
				'rol_registrado_fallo', [
					'datos_antes'=>[
						'antes'=>'sin información'
							   ],
					'datos_despues'=> [
						'resultado'=> 'rol no creado',
						'codigo_usuario' => $codigo_rol,
						'nombre' => $$datos_rol['RolNombre'],
						'descripcion' => $datos_rol['RolDescripcion']
					],
				], 
				'medio', 'error', 'App_usuario');

			/*-*-*-*-*-* Normalizamos el tiempo de respuesta *-*-*-*-*-*/           
			$this->normalizar_tiempo_respuesta();
			/*-*-*-*-*-* Termino función para registro de usuario *-*-*-*-*-*/
			return  json_encode([
				"Alerta" => "simple",
				"Titulo" => "Error",
				"Texto" => "No se pudo registrar el rol. Intenta nuevamente",
				"Tipo" => "warning"
			], JSON_UNESCAPED_UNICODE);
		}
	}
		
	
}

?>