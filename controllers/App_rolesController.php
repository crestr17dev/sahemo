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
	
	
	//===========================================================================================================
    // LISTAR ROLES CON PAGINACIÓN Y FILTROS - COPIADO EXACTO DE USUARIOS
    // Función para obtener roles con filtros aplicados y paginación
    //===========================================================================================================
	
	public function listar_roles_controlador(){
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
				'alto', 'bloqueado', 'App_roles');

            http_response_code(405); // Method Not Allowed
            return json_encode(["error" => "Method not allowed"]);
        }

		
	/************ VALIDACION 3: Verificar si trae el token que se creo previamente para formulado roles ******/
        if(!isset($_POST['csrf_token_list_roles']) || !$this->validar_csrf($_POST['csrf_token_list_roles'],'listRoles')){
			/*-*-*-*-*-* registro logs de seguimiento y auditoria *-*-*-*-*-*/
			$this->guardar_log(
				'csrf_token_invalido', [
					'datos_antes' => [
						'Token_creado'=>'Diferente al recibido'
						],
					'datos_despues' => [
						'formulario' => 'listRoles',
						'token_recibido_hash' => isset($_POST['csrf_token_list_roles']) ? hash('sha256', $_POST['csrf_token_list_roles']) : 'no_enviado',
						'session_id' => session_id(),
						'CodigoUsuario' => $_SESSION['CodigoUsuario'] ?? 'no_definido',
						'UsuarioId' => $_SESSION['UsuarioId'] ?? 'no_definido',
					],
				], 
				'alto', 'bloqueado', 'App_roles');

            $this->normalizar_tiempo_respuesta();
            return json_encode([
                "Alerta" => "simple",
                "Titulo" => "Acceso no autorizado",
                "Texto" => "No tiene permisos suficientes para realizar esta acción",
                "Tipo" => "warning"
            ], JSON_UNESCAPED_UNICODE);
        }

	/************ VALIDACION 4: Verificar que los campos existan y que no sea un envío masivo *****************/
        $campos_requeridos = ['sharerol', 'estadorol', 'nivelrol', 'pagina', 'vista_tipo'];
        $parametros_limpieza = [];

        foreach($campos_requeridos as $campo){
            $parametros_limpieza[$campo] = [
                'valor' => $_POST[$campo] ?? '',
                'tipo' => 'textarea'
            ];
        }

        /*-*-*-*-*-* executo el limpiador de datos *-*-*-*-*-*/
        $resultados_limpieza = $this->limpiar_datos($parametros_limpieza);

	/************ VALIDACION 5: verificar si hubo ataques entre los datos que me llegaron *******************/
        $ataques_detectados = 0;
        foreach($resultados_limpieza as $campo => $resultado){
            if($resultado['ataques_detectados'] > 0){
                $ataques_detectados += $resultado['ataques_detectados'];
            }
        }

        /*-*-*-*-*-* si existe algun ataque aborto la conexión *-*-*-*-*-*/
        if($ataques_detectados > 0){
			/*-*-*-*-*-* registro logs de seguimiento y auditoria *-*-*-*-*-*/
            $this->guardar_log(
				'ataques_detectados_listar_roles', [
					'datos_antes' => [
						'campos_evaluados' => $campos_requeridos,
						'ataques_por_campo' => array_map(function($r) {
							return $r['ataques_detectados'];
						}, $resultados_limpieza)
					],
					'datos_despues' => [
						'total_ataques' => $ataques_detectados,
						'session_id' => session_id(),
						'CodigoUsuario' => $_SESSION['CodigoUsuario'] ?? 'no_definido',
						'UsuarioId' => $_SESSION['UsuarioId'] ?? 'no_definido',
					]
				], 
				'critico', 'bloqueado', 'App_roles'
			);

            $this->normalizar_tiempo_respuesta();
            return json_encode([
                "Alerta" => "simple",
                "Titulo" => "Contenido malicioso",
                "Texto" => "Se detectó contenido potencialmente malicioso en los datos",
                "Tipo" => "warning"
            ], JSON_UNESCAPED_UNICODE);
        }

	/************ VALIDACION 7: Si no hay ataques, extraer datos limpios y continuar ***************************/
        $datos_role = [];
        foreach ($resultados_limpieza as $campo => $resultado) {
            $datos_role[$campo] = $resultado['dato_limpio'];
        }	
		
		/*-------------------//-------- PASO 2 PROCESAMIENTO DE DATOS --------//-----------------------*/
        
        // Validar página
        $pagina = max(1, (int)$datos_role['pagina']);
        $registros_por_pagina = 5; // Puedes hacer esto configurable
        
        try {
			
            // Llamar al modelo para obtener los roles
            $resultado = $this->listar_roles_modelo($datos_role, $pagina, $registros_por_pagina);
           
			// Obtener estadísticas
			$estadisticas = $this->obtener_estadisticas_roles_modelo();
			
			$vista_tipo = $datos_role['vista_tipo'];

			if ($vista_tipo === 'grid') {
				$html_tabla = $this->generar_html_cards_roles($resultado['roles'], $resultado['paginacion']);
			} else {
				$html_tabla = $this->generar_html_tabla_roles($resultado['roles'], $resultado['paginacion']);
			}
            
            // Generar HTML de las tarjetas de estadísticas
            $html_estadisticas = $this->generar_html_estadisticas_roles($estadisticas);
            
            /*-*-*-*-*-* registro logs de seguimiento y auditoria *-*-*-*-*-*/
            $this->guardar_log(
                'roles_listados_exitosamente', [
                    'datos_antes' => [
                        'filtros_aplicados' => $datos_role,
                        'pagina_solicitada' => $pagina
                    ],
                    'datos_despues' => [
                        'roles_encontrados' => count($resultado['roles']),
                        'total_registros' => $resultado['paginacion']['total_registros']
                    ]
                ],
                'bajo', 'exito', 'App_roles'
            );
            
            /*-*-*-*-*-* Normalizamos el tiempo de respuesta *-*-*-*-*-*/
            $this->normalizar_tiempo_respuesta();
            
            /*-*-*-*-*-* Respuesta exitosa *-*-*-*-*-*/
            return json_encode([
                'status' => 'success',
                'html_tabla' => $html_tabla,
                'html_estadisticas' => $html_estadisticas,
                'paginacion' => $resultado['paginacion'],
                'total_roles' => $resultado['paginacion']['total_registros']
            ], JSON_UNESCAPED_UNICODE);
            
        } catch(Exception $e) {
			/*-*-*-*-*-* Guardar error en log *-*-*-*-*-*/
			error_log("Error en listar_roles_controlador: " . $e->getMessage());
			
			/*-*-*-*-*-* registro logs de seguimiento y auditoria *-*-*-*-*-*/
			$this->guardar_log(
				'error_listar_roles', [
					'datos_antes' => [
						'filtros_aplicados' => $datos_role ?? [],
						'pagina_solicitada' => $pagina ?? 1
					],
					'datos_despues' => [
						'error_message' => $e->getMessage(),
						'error_line' => $e->getLine(),
						'error_file' => $e->getFile()
					]
				],
				'critico', 'error', 'App_roles'
			);

            $this->normalizar_tiempo_respuesta();
            return json_encode([
                "Alerta" => "simple",
                "Titulo" => "Error del sistema",
                "Texto" => "Error interno del sistema. Contacte al administrador.",
                "Tipo" => "error"
            ], JSON_UNESCAPED_UNICODE);
        }
	}

	//===========================================================================================================
    // ELIMINAR ROL - COPIADO EXACTO DE USUARIOS
    // Función para eliminar un rol de manera segura
    //===========================================================================================================
	
	public function eliminar_rol_controlador(){
		/*-------------------//-------- PASO 1 LIMPIEZA, VALIDACIONES Y SEGURIDAD --------//-----------------------*/	
    /************ Marcar inicio del tiempo para  funcion normalizar el timepo de respuestas ********************/
        $this->tiempo_inicio = microtime(true);
		
    /************ VALIDACION 1: valida si el metodo es POST de lo contrario bloquea proceso ********************/
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            return json_encode(["error" => "Method not allowed"]);
        }

	/************ VALIDACION 3: Verificar si trae el token que se creo previamente ******/
        if(!isset($_POST['csrf_token_eliminar']) || !$this->validar_csrf($_POST['csrf_token_eliminar'],'listRoles')){
            $this->normalizar_tiempo_respuesta();
            return json_encode([
                "Titulo" => "Acceso no autorizado",
                "Texto" => "No tiene permisos suficientes para realizar esta acción",
                "Tipo" => "warning"
            ], JSON_UNESCAPED_UNICODE);
        }

        // Validar que exista el código del rol
        if(!isset($_POST['codigo_rol']) || empty(trim($_POST['codigo_rol']))){
            $this->normalizar_tiempo_respuesta();
            return json_encode([
                "Titulo" => "Datos incompletos",
                "Texto" => "Código de rol requerido",
                "Tipo" => "warning"
            ], JSON_UNESCAPED_UNICODE);
        }

        $codigo_rol = trim($_POST['codigo_rol']);

        try {
            // Verificar si el rol existe
            $rol_existe = $this->verificar_rol_existe_modelo($codigo_rol);
            if(!$rol_existe) {
                $this->normalizar_tiempo_respuesta();
                return json_encode([
                    "Titulo" => "Rol no encontrado",
                    "Texto" => "El rol especificado no existe en el sistema",
                    "Tipo" => "warning"
                ], JSON_UNESCAPED_UNICODE);
            }

            // Verificar si el rol tiene usuarios asociados
            $tiene_usuarios = $this->verificar_rol_tiene_usuarios_modelo($codigo_rol);
            if($tiene_usuarios) {
                $this->normalizar_tiempo_respuesta();
                return json_encode([
                    "Titulo" => "No se puede eliminar",
                    "Texto" => "Este rol tiene usuarios asociados. No se puede eliminar.",
                    "Tipo" => "warning"
                ], JSON_UNESCAPED_UNICODE);
            }

            // Eliminar el rol
            $resultado = $this->eliminar_rol_modelo($codigo_rol);

            if($resultado) {
                /*-*-*-*-*-* Logs de eliminación exitosa *-*-*-*-*-*/
                $this->guardar_log('rol_eliminado', [
                    'datos_antes' => [
                        'codigo_rol' => $codigo_rol
                    ],
                    'datos_despues' => [
                        'resultado' => 'rol_eliminado_exitosamente'
                    ],
                ], 'medio', 'exito', 'App_roles');

                // Generar nuevo token para próximas operaciones
                $nuevo_token = $this->generar_token_csrf('listRoles');
                
                $this->normalizar_tiempo_respuesta();
                return json_encode([
                    "Titulo" => "Rol eliminado",
                    "Texto" => "El rol ha sido eliminado correctamente",
                    "Tipo" => "success",
                    "nuevo_token" => $nuevo_token
                ], JSON_UNESCAPED_UNICODE);
                
            } else {
                $this->normalizar_tiempo_respuesta();
                return json_encode([
                    "Titulo" => "Error",
                    "Texto" => "No se pudo eliminar el rol. Intenta nuevamente",
                    "Tipo" => "error"
                ], JSON_UNESCAPED_UNICODE);
            }

        } catch(Exception $e) {
            error_log("Error eliminando rol: " . $e->getMessage());
            
            $this->normalizar_tiempo_respuesta();
            return json_encode([
                "Titulo" => "Error del sistema",
                "Texto" => "Error interno del sistema. Contacte al administrador.",
                "Tipo" => "error"
            ], JSON_UNESCAPED_UNICODE);
        }
	}
	
}

?>