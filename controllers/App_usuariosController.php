<?php
 	//===========================================================================================================
    // CONTROLADOR DE USUARIOS - ACTUALIZADO CON CONTRASEÑAS TEMPORALES
    // Este archivo maneja toda la lógica del módulo de usuarios
	// Es el intermediario entre las vistas y el modelo
    //===========================================================================================================

	//===================== Verificamos si es una petición AJAX para incluir el archivo correcto ===============
if($peticionAjax){
    require_once "../models/App_usuariosModel.php";
}else{
    require_once "./models/App_usuariosModel.php";
}

class usuariosController extends usuariosModel {
    
	//===========================================================================================================
    // OBTENER TOKEN CSRF PARA EL FORMULARIO
    // Función para generar el token que va en el formulario de registro y/o actualización
    //===========================================================================================================

    public function obtener_token_csrf($key){
        return $this->generar_token_csrf($key);
    }
	
    //===========================================================================================================
    // REGISTRAR NUEVO USUARIO - ACTUALIZADO CON CONTRASEÑAS TEMPORALES
    // Función con todos los parámetros para registrar un nuevo usuario de manera segura en BD
    //===========================================================================================================

    public function registrar_usuario_controlador(){
		
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
				'alto', 'bloqueado', 'App_usuario');

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
			/*-*-*-*-*-* Termino función para registro de usuario *-*-*-*-*-*/ 
            return  json_encode([
				"Alerta" => "simple",
				"Titulo" => "Actividad Inapropiada",
				"Texto" => "Actividad sospechosa detectada", 
				"Tipo" => "warning"
			], JSON_UNESCAPED_UNICODE);
        }
		
	/************ VALIDACION 3: Verificar si trae el token que se creó previamente para formulario usuario ******/
        if(!isset($_POST['csrf_token']) || !$this->validar_csrf($_POST['csrf_token'],'formNuevoUsuario')){
			/*-*-*-*-*-* registro logs de seguimiento y auditoría *-*-*-*-*-*/
			$this->guardar_log(
				'csrf_token_invalido', [
					'datos_antes' => [
						'Token_creado'=>'Diferente al recibido'
						],
					'datos_despues' => [
						'formulario' => 'formNuevoUsuario',
						'token_recibido_hash' => isset($_POST['csrf_token']) ? hash('sha256', $_POST['csrf_token']) : 'no_enviado',
						'session_id' => session_id(),
						'CodigoUsuario' => $_SESSION['CodigoUsuario'] ?? 'no_definido',
						'UsuarioId' => $_SESSION['UsuarioId'] ?? 'no_definido'
					]
				],
				'alto', 'bloqueado', 'App_usuario');
			
            /*-*-*-*-*-* Normalizamos el tiempo de respuesta *-*-*-*-*-*/           
            $this->normalizar_tiempo_respuesta();
			/*-*-*-*-*-* Termino función para registro de usuario *-*-*-*-*-*/
			return  json_encode([
				"Alerta" => "simple",
				"Titulo" => "Token inválido",
				"Texto" => "Token de seguridad inválido. Recarga la página e intenta nuevamente", 
				"Tipo" => "warning"
			], JSON_UNESCAPED_UNICODE);
			
        }

	/************ VALIDACION 4: verificar permisos del usuario *************************************************/
        if(!$this->verificar_permisos('usuario_crear')){
			/*-*-*-*-*-* registro logs de seguimiento y auditoría *-*-*-*-*-*/
			$this->guardar_log(
				'Usuario sin permisos para crear usuario', [
					'datos_antes' => [
						'usuario'=>'sin permisos'
					],
					'datos_despues' => [
						'CodigoUsuario' => $_SESSION['CodigoUsuario'],
						'UsuarioId' => $_SESSION['UsuarioId']
					],
				],
				'alto', 'bloqueado', 'App_usuario');
			/*-*-*-*-*-* Normalizamos el tiempo de respuesta *-*-*-*-*-*/           
            $this->normalizar_tiempo_respuesta();
			/*-*-*-*-*-* Termino función para registro de usuario *-*-*-*-*-*/
            
			return  json_encode([
				"Alerta" => "simple",
                "Titulo" => "Sin permisos",
                "Texto" => "No tienes permisos para registrar usuarios", 
				"Tipo" => "warning"
			], JSON_UNESCAPED_UNICODE);
        }
        
	/************ VALIDACION 5: Límite de intentos para el envío de información, max 3 bloqueo 5 minutos *******/
        if(!$this->verificar_intentos('registro_usuario', 3, 300)){
            /*-*-*-*-*-* registro logs de seguimiento y auditoría *-*-*-*-*-*/
			$this->guardar_log(
				'registro_usuario', [
					'datos_antes' => [
						'intentos'=>'Usuario inicia intento de registro'
					],
					'datos_despues' => [
						'intentos'=>'Usuario alcanzó el máximo de intentos permitidos 3',
						'CodigoUsuario' => $_SESSION['CodigoUsuario'],
						'UsuarioId' => $_SESSION['UsuarioId']
					],
				],
				'alto', 'bloqueado', 'App_usuario');
            /*-*-*-*-*-* Normalizamos el tiempo de respuesta *-*-*-*-*-*/           
            $this->normalizar_tiempo_respuesta();
			/*-*-*-*-*-* Termino función para registro de usuario *-*-*-*-*-*/
            
			return  json_encode([
				"Alerta" => "simple",
                "Titulo" => "Demasiados intentos",
                "Texto" => "Has superado el límite de intentos. Espera 5 minutos",
				"Tipo" => "warning"
			], JSON_UNESCAPED_UNICODE);
        }
        
	/************ VALIDACION 6: limpiar los datos recibidos - SIN CONTRASEÑA ****************************/ 
        $resultados_limpieza = [
            'UsuarioDocumento' => $this->limpiar_datos($_POST['usuario-documento'], 'numero', 'usuario-documento'),
            'UsuarioTipoDocumento' => $this->limpiar_datos($_POST['usuario-tipo-documento'], 'texto', 'usuario-tipo-documento'),
            'UsuarioNombres' => $this->limpiar_datos($_POST['usuario-nombres'], 'texto', 'usuario-nombres'),
            'UsuarioApellidos' => $this->limpiar_datos($_POST['usuario-apellidos'], 'texto','usuario-apellidos'),
            'UsuarioEmail' => $this->limpiar_datos($_POST['usuario-email'], 'email', 'usuario-email'),
            'UsuarioTelefono' => $this->limpiar_datos($_POST['usuario-telefono'], 'texto', 'usuario-telefono'),
            'UsuarioCargo' => $this->limpiar_datos($_POST['usuario-cargo'] ?? '', 'texto', 'usuario-cargo'),
            'UsuarioDepartamento' => $this->limpiar_datos($_POST['usuario-departamento'] ?? '', 'texto', 'usuario-departamento'),
            'UsuarioEmpresaId' => $this->limpiar_datos($_POST['usuario-empresa-id'], 'numero', 'usuario-empresa-id'),
            'UsuarioSucursalId' => $this->limpiar_datos($_POST['usuario-sucursal-id'] ?? '', 'numero', 'usuario-sucursal-id'),
            'UsuarioSedeId' => $this->limpiar_datos($_POST['usuario-sede-id'] ?? '', 'numero', 'usuario-sede-id')
        ];
        
	/************ VALIDACION 7: Verifico si algún campo tenía ataques, sale de la limpieza realizada ***********/
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
			/*-*-*-*-*-* registro logs de seguimiento y auditoría *-*-*-*-*-*/
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
			/*-*-*-*-*-* Termino función para registro de usuario *-*-*-*-*-*/
            return json_encode([
                "Titulo" => "Datos no válidos",
                "Texto" => "Los datos enviados contienen información no permitida.",
                "Tipo" => "warning"
            ], JSON_UNESCAPED_UNICODE);
        }

	/************ VALIDACION 8: Si no hay ataques, extraer datos limpios y continuar ***************************/
        $datos_usuario = [];
        foreach ($resultados_limpieza as $campo => $resultado) {
            $datos_usuario[$campo] = $resultado['dato_limpio'];
        }
        
	/************ VALIDACION 9: verifico los campos obligatorios desde la BD - SIN CONTRASEÑA ****************/
        /*-*-*-*-*-* excluyo los campos que son automáticos en BD *-*-*-*-*-*/
		$campos_excluir = ['UsuarioId', 'UsuarioCodigo', 'UsuarioPassword', 'UsuarioFechaRegistro', 'UsuarioFechaActualizacion', 'UsuarioEstado', 'UsuarioUltimoAcceso', 'UsuarioIntentosLogin', 'UsuarioFechaBloqueo', 'UsuarioPasswordCambio', 'UsuarioPasswordExpira', 'UsuarioFoto'];

		/*-*-*-*-*-* creo reglas personalizadas que complementan las de base de datos *-*-*-*-*-*/
		$reglas_personalizadas = [
			'UsuarioDocumento' => [
				'min_caracteres' => 6,  // Mínimo 6 dígitos para documentos
			],
			'UsuarioTelefono' => [
				'min_caracteres' => 7,  // Mínimo 7 dígitos para teléfonos
			],
			'UsuarioNombres' => [
				'solo_letras' => true   // Solo letras para nombres
			],
			'UsuarioApellidos' => [
				'solo_letras' => true   // Solo letras para apellidos
			]
		];

		/*-*-*-*-*-* valida todos los campos unificando las reglas *-*-*-*-*-*/
		$errores = $this->validar_completo(
			$datos_usuario, 
			'App_usuarios_usuario', 
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
			return  json_encode([
				"Alerta" => "simple",
				"Titulo" => "Datos incorrectos",
				"Texto" => $mensaje_error,
				"Tipo" => "warning"
			], JSON_UNESCAPED_UNICODE);
		}

		
    /************ VALIDACION 10: Verifico que no exista el documento en la BD  **************************************/
        if($this->verificar_documento_duplicado($datos_usuario['UsuarioDocumento'])){
            /*-*-*-*-*-* Normalizamos el tiempo de respuesta *-*-*-*-*-*/           
            $this->normalizar_tiempo_respuesta();
			/*-*-*-*-*-* Termino función para registro de usuario *-*-*-*-*-*/
			return  json_encode([
				"Alerta" => "simple",
				"Titulo" => "Documento duplicado",
				"Texto" => "Ya existe un usuario registrado con este documento", 
				"Tipo" => "warning"
			], JSON_UNESCAPED_UNICODE);
        }
        
	/************ VALIDACION 11: Verifico que no exista el email en la BD *************************************/
        if($this->verificar_email_duplicado($datos_usuario['UsuarioEmail'])){
            /*-*-*-*-*-* Normalizamos el tiempo de respuesta *-*-*-*-*-*/           
            $this->normalizar_tiempo_respuesta();
			/*-*-*-*-*-* Termino función para registro de usuario *-*-*-*-*-*/
           	return  json_encode([
				"Alerta" => "simple",
                "Titulo" => "Email duplicado",
                "Texto" => "Ya existe un usuario registrado con este email",
				"Tipo" => "warning"
			], JSON_UNESCAPED_UNICODE);
        }

    /************ VALIDACION 12: Verifico que la empresa sea válida *******************************************/
        if(!$this->verificar_empresa_valida($datos_usuario['UsuarioEmpresaId'])){
            /*-*-*-*-*-* Normalizamos el tiempo de respuesta *-*-*-*-*-*/           
            $this->normalizar_tiempo_respuesta();
			/*-*-*-*-*-* Termino función para registro de usuario *-*-*-*-*-*/
           	return  json_encode([
				"Alerta" => "simple",
                "Titulo" => "Empresa inválida",
                "Texto" => "La empresa seleccionada no existe o no está activa",
				"Tipo" => "warning"
			], JSON_UNESCAPED_UNICODE);
        }

    /************ VALIDACION 13: Verifico que la sucursal sea válida (si se envió) ****************************/
        if(!empty($datos_usuario['UsuarioSucursalId']) && !$this->verificar_sucursal_valida($datos_usuario['UsuarioSucursalId'], $datos_usuario['UsuarioEmpresaId'])){
            /*-*-*-*-*-* Normalizamos el tiempo de respuesta *-*-*-*-*-*/           
            $this->normalizar_tiempo_respuesta();
			/*-*-*-*-*-* Termino función para registro de usuario *-*-*-*-*-*/
           	return  json_encode([
				"Alerta" => "simple",
                "Titulo" => "Sucursal inválida",
                "Texto" => "La sucursal seleccionada no existe o no pertenece a la empresa",
				"Tipo" => "warning"
			], JSON_UNESCAPED_UNICODE);
        }

    /************ VALIDACION 14: Verifico que la sede sea válida (si se envió) ********************************/
        if(!empty($datos_usuario['UsuarioSedeId']) && !$this->verificar_sede_valida($datos_usuario['UsuarioSedeId'], $datos_usuario['UsuarioSucursalId'])){
            /*-*-*-*-*-* Normalizamos el tiempo de respuesta *-*-*-*-*-*/           
            $this->normalizar_tiempo_respuesta();
			/*-*-*-*-*-* Termino función para registro de usuario *-*-*-*-*-*/
           	return  json_encode([
				"Alerta" => "simple",
                "Titulo" => "Sede inválida",
                "Texto" => "La sede seleccionada no existe o no pertenece a la sucursal",
				"Tipo" => "warning"
			], JSON_UNESCAPED_UNICODE);
        }
        
		
    /************ COMPLEMENTO 1: genero el código para la creación de usuario **********************************/
		/*-*-*-*-*-* Consulto cuántos usuarios tengo creados para darle código a nuevo usuario *-*-*-*-*-*/
		$sql = "SELECT COUNT(UsuarioId) FROM App_usuarios_usuario";
		$totalusuarios = $this->ejecutar_consulta_segura($sql, []) ;
		$totalusuarios = ($totalusuarios->fetchColumn()) + 1 ;
		
		if($totalusuarios > 0){
			$longitud = 10 - strlen($totalusuarios);
			
		}else{
			$longitud = 10;
			$totalusuarios = "_";
		}
		
        $codigo_usuario = $this->generar_codigo_aleatorio('US', $longitud,'');
        $codigo_usuario = $codigo_usuario.$totalusuarios;

    /************ COMPLEMENTO 2: Generar contraseña temporal y segura ***************************************/
        // Generar contraseña temporal en lugar de usar la del formulario
        $password_temporal = $this->generar_password_temporal();
        $password_hash = password_hash($password_temporal, PASSWORD_DEFAULT);
		
		
    /*-----------------------//-------- PASO 2: REGISTRO DE USUARIO EN BD --------//---------------------------*/		

		/*-*-*-*-*-* Consolido datos totalmente limpios *-*-*-*-*-*/
        $datos_finales = [
            'codigo' => $codigo_usuario,
            'documento' => $datos_usuario['UsuarioDocumento'],
            'tipo_documento' => $datos_usuario['UsuarioTipoDocumento'],
            'nombres' => $datos_usuario['UsuarioNombres'],
            'apellidos' => $datos_usuario['UsuarioApellidos'],
            'email' => $datos_usuario['UsuarioEmail'],
            'telefono' => $datos_usuario['UsuarioTelefono'],
            'password_hash' => $password_hash,
            'cargo' => $datos_usuario['UsuarioCargo'],
            'departamento' => $datos_usuario['UsuarioDepartamento'],
            'empresa_id' => $datos_usuario['UsuarioEmpresaId'],
            'sucursal_id' => !empty($datos_usuario['UsuarioSucursalId']) ? $datos_usuario['UsuarioSucursalId'] : null,
            'sede_id' => !empty($datos_usuario['UsuarioSedeId']) ? $datos_usuario['UsuarioSedeId'] : null,
			'UsuarioFechaRegistro'=>  date("Y-m-d H:i:s"),
			'UsuarioFechaActualizacion'=>  date("Y-m-d H:i:s"),
            // NUEVOS CAMPOS PARA CONTRASEÑA TEMPORAL
            'password_temporal' => true,
            'password_expira_fecha' => date("Y-m-d H:i:s", strtotime('+7 days'))
        ];
        
        /*-*-*-*-*-* envío registro de usuario *-*-*-*-*-*/
        $resultado = $this->registrar_usuario_modelo($datos_finales);
		/*-*-*-*-*-* si registro exitoso *-*-*-*-*-*/
        if($resultado){
            
            // Intentar enviar email con la contraseña temporal
            $email_enviado = $this->enviar_credenciales_temporales([
                'email' => $datos_usuario['UsuarioEmail'],
                'nombres' => $datos_usuario['UsuarioNombres'],
                'apellidos' => $datos_usuario['UsuarioApellidos'],
                'codigo_usuario' => $codigo_usuario,
                'password_temporal' => $password_temporal
            ]);
            
            $mensaje_exito = $email_enviado 
                ? "Usuario registrado exitosamente. Se han enviado las credenciales temporales al email: " . $datos_usuario['UsuarioEmail']
                : "Usuario registrado exitosamente.\n\nCredenciales temporales:\nUsuario: " . $codigo_usuario . "\nContraseña: " . $password_temporal . "\n\n⚠️ IMPORTANTE: El usuario debe cambiar esta contraseña en su primer acceso.";
            
            /*-*-*-*-*-* registro logs de seguimiento y auditoría *-*-*-*-*-*/
			$this->guardar_log(
				'usuario_registrado_con_password_temporal',[
					'datos_antes'=>[
						'antes'=>'sin información'
							   ],
					'datos_despues'=> [
						'resultado'=> 'usuario creado con contraseña temporal',
						'codigo_usuario' => $codigo_usuario,
						'documento' => $datos_usuario['UsuarioDocumento'],
						'nombres' => $datos_usuario['UsuarioNombres'],
						'apellidos' => $datos_usuario['UsuarioApellidos'],
						'email' => $datos_usuario['UsuarioEmail'],
                        'email_enviado' => $email_enviado
					],
				], 
				'medio', 'exito', 'App_usuario');
        	/*-*-*-*-*-* elimino el token que le había creado al formulario *-*-*-*-*-*/
        	$this->eliminar_token_csrf('formNuevoUsuario');
            
            /*-*-*-*-*-* Normalizamos el tiempo de respuesta *-*-*-*-*-*/           
            $this->normalizar_tiempo_respuesta();
			/*-*-*-*-*-* Termino función para registro de usuario *-*-*-*-*-*/
			return  json_encode([
				"Alerta" => "recargar",
                "Titulo" => "Usuario registrado",
                "Texto" => $mensaje_exito,
				"Tipo" => "success"
			], JSON_UNESCAPED_UNICODE);
		
        } else {
			/*-*-*-*-*-* si registro falló *-*-*-*-*-*/
			/*-*-*-*-*-* registro logs de seguimiento y auditoría *-*-*-*-*-*/
			$this->guardar_log(
				'usuario_registrado_fallo', [
					'datos_antes'=>[
						'antes'=>'sin información'
							   ],
					'datos_despues'=> [
						'resultado'=> 'usuario no creado',
						'codigo_usuario' => $codigo_usuario,
						'documento' => $datos_usuario['UsuarioDocumento'],
						'nombres' => $datos_usuario['UsuarioNombres'],
						'apellidos' => $datos_usuario['UsuarioApellidos']
					],
				], 
				'medio', 'error', 'App_usuario');
            
            /*-*-*-*-*-* Normalizamos el tiempo de respuesta *-*-*-*-*-*/           
            $this->normalizar_tiempo_respuesta();
			/*-*-*-*-*-* Termino función para registro de usuario *-*-*-*-*-*/
			return  json_encode([
				"Alerta" => "simple",
                "Titulo" => "Error",
                "Texto" => "No se pudo registrar el usuario. Intenta nuevamente",
				"Tipo" => "warning"
			], JSON_UNESCAPED_UNICODE);
        }
    }

    //===========================================================================================================
    // NUEVAS FUNCIONES PARA MANEJO DE CONTRASEÑAS TEMPORALES
    //===========================================================================================================

    /**
     * Generar contraseña temporal segura
     */
    private function generar_password_temporal() {
        // Generar contraseña de 12 caracteres con mayúsculas, minúsculas, números y símbolos seguros
        $caracteres = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789@#$%&*';
        $password = '';
        $longitud = 12;
        
        // Asegurar al menos un carácter de cada tipo
        $password .= chr(rand(65, 90));  // Mayúscula
        $password .= chr(rand(97, 122)); // Minúscula
        $password .= chr(rand(48, 57));  // Número
        $password .= $caracteres[rand(62, 66)]; // Símbolo
        
        // Completar el resto
        for ($i = 4; $i < $longitud; $i++) {
            $password .= $caracteres[rand(0, strlen($caracteres) - 1)];
        }
        
        // Mezclar caracteres
        return str_shuffle($password);
    }

    /**
     * Enviar credenciales temporales por email
     */
    private function enviar_credenciales_temporales($datos) {
        try {
            // Por ahora retornamos false para mostrar credenciales en pantalla
            // En producción, aquí implementarías PHPMailer o el sistema de emails que uses
            
            $asunto = "Credenciales de acceso - " . (defined('COMPANY') ? COMPANY : "Sistema");
            $mensaje = "
            Hola {$datos['nombres']} {$datos['apellidos']},
            
            Se ha creado tu cuenta en nuestro sistema con los siguientes datos:
            
            Usuario: {$datos['codigo_usuario']}
            Contraseña temporal: {$datos['password_temporal']}
            
            IMPORTANTE: 
            - Esta contraseña es temporal y debe ser cambiada en tu primer acceso
            - La contraseña expira en 7 días
            - Por seguridad, no compartas estas credenciales
            
            Accede al sistema en: " . SERVERURL . "
            
            Saludos,
            Equipo de TI
            ";
            
            // Aquí iría la lógica real de envío de email
            // Por ejemplo usando PHPMailer:
            // return $this->enviar_email($datos['email'], $asunto, $mensaje);
            
            return false; // Cambiar a true cuando implementes el envío real
            
        } catch (Exception $e) {
            error_log("Error enviando email: " . $e->getMessage());
            return false;
        }
    }
	
	//===========================================================================================================
    // VERIFICAR SI USUARIO TIENE CONTRASEÑA TEMPORAL
    // Función para verificar en el dashboard si el usuario debe cambiar contraseña
    //===========================================================================================================
    
    public function verificar_password_temporal_controlador(){
        
        /************ VALIDACION 1: Verificar que sea petición POST *******************************************/
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            return json_encode(["error" => "Method not allowed"]);
        }
        
        /************ VALIDACION 2: Verificar que el usuario esté logueado *************************************/
        if(!isset($_SESSION['CodigoUsuario'])) {
            return json_encode([
                "error" => true,
                "mensaje" => "Usuario no autenticado"
            ], JSON_UNESCAPED_UNICODE);
        }
        
        /************ OBTENER INFORMACIÓN DE CONTRASEÑA TEMPORAL **********************************************/
        $codigo_usuario = $_SESSION['CodigoUsuario'];
        $info_password = $this->verificar_password_temporal($codigo_usuario);
        
        if($info_password) {
            return json_encode([
                "tiene_password_temporal" => $info_password['debe_cambiar'],
                "password_expirada" => $info_password['password_expirada'],
                "dias_restantes" => $info_password['dias_restantes'],
                "dias_transcurridos" => $info_password['dias_transcurridos']
            ], JSON_UNESCAPED_UNICODE);
        } else {
            return json_encode([
                "tiene_password_temporal" => false,
                "password_expirada" => false,
                "dias_restantes" => 0
            ], JSON_UNESCAPED_UNICODE);
        }
    }

    //===========================================================================================================
    // CAMBIAR CONTRASEÑA TEMPORAL POR DEFINITIVA
    // Función para cambiar la contraseña temporal en el primer acceso
    //===========================================================================================================
    
    public function cambiar_password_temporal_controlador(){
        
        /*-------------------//-------- PASO 1 LIMPIEZA, VALIDACIONES Y SEGURIDAD --------//-----------------------*/
        
        /************ Marcar inicio del tiempo ********************/
        $this->tiempo_inicio = microtime(true);
        
        /************ VALIDACION 1: Verificar que sea petición POST *******************************************/
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            return json_encode(["error" => "Method not allowed"]);
        }
        
        /************ VALIDACION 2: Verificar token CSRF *****************************************************/
        if(!isset($_POST['csrf_token_cambio']) || !$this->validar_csrf($_POST['csrf_token_cambio'],'cambioPasswordObligatorio')){
            /*-*-*-*-*-* registro logs de seguimiento y auditoría *-*-*-*-*-*/
            $this->guardar_log(
                'csrf_token_invalido_cambio_password', [
                    'datos_antes' => [
                        'Token_creado'=>'Diferente al recibido'
                    ],
                    'datos_despues' => [
                        'formulario' => 'cambioPasswordObligatorio',
                        'CodigoUsuario' => $_SESSION['CodigoUsuario'] ?? 'no_definido'
                    ]
                ],
                'alto', 'bloqueado', 'App_usuario');
            
            /*-*-*-*-*-* Normalizamos el tiempo de respuesta *-*-*-*-*-*/           
            $this->normalizar_tiempo_respuesta();
            
            return json_encode([
                "success" => false,
                "mensaje" => "Token de seguridad inválido"
            ], JSON_UNESCAPED_UNICODE);
        }
        
        /************ VALIDACION 3: Verificar que el usuario esté logueado *************************************/
        if(!isset($_SESSION['CodigoUsuario'])) {
            /*-*-*-*-*-* Normalizamos el tiempo de respuesta *-*-*-*-*-*/           
            $this->normalizar_tiempo_respuesta();
            
            return json_encode([
                "success" => false,
                "mensaje" => "Usuario no autenticado"
            ], JSON_UNESCAPED_UNICODE);
        }
        
        /************ VALIDACION 4: Límite de intentos *******************************************************/
        if(!$this->verificar_intentos('cambio_password_temporal', 5, 900)){ // 5 intentos, 15 minutos
            /*-*-*-*-*-* Normalizamos el tiempo de respuesta *-*-*-*-*-*/           
            $this->normalizar_tiempo_respuesta();
            
            return json_encode([
                "success" => false,
                "mensaje" => "Demasiados intentos. Espera 15 minutos"
            ], JSON_UNESCAPED_UNICODE);
        }
        
        /************ VALIDACION 5: Limpiar datos recibidos **************************************************/		
		$password_actual = $this->limpiar_datos($_POST['password-actual'], 'password', 'password-actual');
		$password_nueva = $this->limpiar_datos($_POST['password-nueva'], 'password', 'password-nueva');
		$password_confirmar = $this->limpiar_datos($_POST['password-confirmar'], 'password', 'password-confirmar');
        
        // Verificar si hay ataques en la limpieza
        $datos_limpios = [$password_actual, $password_nueva, $password_confirmar];
        foreach ($datos_limpios as $dato) {
            if (!$dato['es_seguro']) {
                /*-*-*-*-*-* Normalizamos el tiempo de respuesta *-*-*-*-*-*/           
                $this->normalizar_tiempo_respuesta();
                
                return json_encode([
                    "success" => false,
                    "mensaje" => "Datos no válidos detectados"
                ], JSON_UNESCAPED_UNICODE);
            }
        }
        
        // Extraer datos limpios
        $password_actual_limpia = $password_actual['dato_limpio'];
        $password_nueva_limpia = $password_nueva['dato_limpio'];
        $password_confirmar_limpia = $password_confirmar['dato_limpio'];
        
        /************ VALIDACION 6: Validaciones de contraseñas **********************************************/
        
        // Verificar campos no vacíos
        if(empty($password_actual_limpia) || empty($password_nueva_limpia) || empty($password_confirmar_limpia)) {
            /*-*-*-*-*-* Normalizamos el tiempo de respuesta *-*-*-*-*-*/           
            $this->normalizar_tiempo_respuesta();
            
            return json_encode([
                "success" => false,
                "mensaje" => "Todos los campos son obligatorios"
            ], JSON_UNESCAPED_UNICODE);
        }
        
        // Verificar que las contraseñas nuevas coincidan
        if($password_nueva_limpia !== $password_confirmar_limpia) {
            /*-*-*-*-*-* Normalizamos el tiempo de respuesta *-*-*-*-*-*/           
            $this->normalizar_tiempo_respuesta();
            
            return json_encode([
                "success" => false,
                "mensaje" => "Las contraseñas nuevas no coinciden"
            ], JSON_UNESCAPED_UNICODE);
        }
        
        // Validar requisitos de la nueva contraseña
        if(strlen($password_nueva_limpia) < 8) {
            /*-*-*-*-*-* Normalizamos el tiempo de respuesta *-*-*-*-*-*/           
            $this->normalizar_tiempo_respuesta();
            
            return json_encode([
                "success" => false,
                "mensaje" => "La contraseña debe tener al menos 8 caracteres"
            ], JSON_UNESCAPED_UNICODE);
        }
        
        if(!preg_match('/[A-Z]/', $password_nueva_limpia)) {
            /*-*-*-*-*-* Normalizamos el tiempo de respuesta *-*-*-*-*-*/           
            $this->normalizar_tiempo_respuesta();
            
            return json_encode([
                "success" => false,
                "mensaje" => "La contraseña debe tener al menos una letra mayúscula"
            ], JSON_UNESCAPED_UNICODE);
        }
        
        if(!preg_match('/[a-z]/', $password_nueva_limpia)) {
            /*-*-*-*-*-* Normalizamos el tiempo de respuesta *-*-*-*-*-*/           
            $this->normalizar_tiempo_respuesta();
            
            return json_encode([
                "success" => false,
                "mensaje" => "La contraseña debe tener al menos una letra minúscula"
            ], JSON_UNESCAPED_UNICODE);
        }
        
        if(!preg_match('/[0-9]/', $password_nueva_limpia)) {
            /*-*-*-*-*-* Normalizamos el tiempo de respuesta *-*-*-*-*-*/           
            $this->normalizar_tiempo_respuesta();
            
            return json_encode([
                "success" => false,
                "mensaje" => "La contraseña debe tener al menos un número"
            ], JSON_UNESCAPED_UNICODE);
        }
        
        if(!preg_match('/[@#$%&*]/', $password_nueva_limpia)) {
            /*-*-*-*-*-* Normalizamos el tiempo de respuesta *-*-*-*-*-*/           
            $this->normalizar_tiempo_respuesta();
            
            return json_encode([
                "success" => false,
                "mensaje" => "La contraseña debe tener al menos un símbolo (@#$%&*)"
            ], JSON_UNESCAPED_UNICODE);
        }
        
        /*-----------------------//-------- PASO 2: VERIFICACIONES DE USUARIO --------//---------------------------*/
        
        $codigo_usuario = $_SESSION['CodigoUsuario'];
        
        /************ VERIFICAR 1: Que el usuario tenga contraseña temporal *************************************/
        $info_password = $this->verificar_password_temporal($codigo_usuario);
        
        if(!$info_password || !$info_password['debe_cambiar']) {
            /*-*-*-*-*-* Normalizamos el tiempo de respuesta *-*-*-*-*-*/           
            $this->normalizar_tiempo_respuesta();
            
            return json_encode([
                "success" => false,
                "mensaje" => "No tienes una contraseña temporal que cambiar"
            ], JSON_UNESCAPED_UNICODE);
        }
        
        /************ VERIFICAR 2: Que la contraseña temporal no haya expirado *******************************/
        if($info_password['password_expirada']) {
            /*-*-*-*-*-* Normalizamos el tiempo de respuesta *-*-*-*-*-*/           
            $this->normalizar_tiempo_respuesta();
            
            return json_encode([
                "success" => false,
                "mensaje" => "Tu contraseña temporal ha expirado. Contacta al administrador"
            ], JSON_UNESCAPED_UNICODE);
        }
        
        /************ VERIFICAR 3: Que la contraseña actual sea correcta *************************************/
        $usuario_data = $this->obtener_usuario_para_login($codigo_usuario);
        
        if(!$usuario_data) {
            /*-*-*-*-*-* Normalizamos el tiempo de respuesta *-*-*-*-*-*/           
            $this->normalizar_tiempo_respuesta();
            
            return json_encode([
                "success" => false,
                "mensaje" => "Error al obtener datos del usuario"
            ], JSON_UNESCAPED_UNICODE);
        }
        
        if(!password_verify($password_actual_limpia, $usuario_data['UsuarioPassword'])) {
            /*-*-*-*-*-* registro logs de seguimiento y auditoría *-*-*-*-*-*/
            $this->guardar_log(
                'password_temporal_incorrecta', [
                    'datos_antes' => [
                        'accion' => 'intento_cambio_password_temporal'
                    ],
                    'datos_despues' => [
                        'resultado' => 'password_actual_incorrecta',
                        'CodigoUsuario' => $codigo_usuario
                    ]
                ],
                'medio', 'error', 'App_usuario');
            
            /*-*-*-*-*-* Normalizamos el tiempo de respuesta *-*-*-*-*-*/           
            $this->normalizar_tiempo_respuesta();
            
            return json_encode([
                "success" => false,
                "mensaje" => "La contraseña temporal actual es incorrecta"
            ], JSON_UNESCAPED_UNICODE);
        }
        
        /*-----------------------//-------- PASO 3: CAMBIAR CONTRASEÑA --------//---------------------------*/
        
        $resultado = $this->actualizar_password_primer_acceso($codigo_usuario, $password_nueva_limpia);
        
        if($resultado) {
            /*-*-*-*-*-* registro logs de seguimiento y auditoría *-*-*-*-*-*/
            $this->guardar_log(
                'password_temporal_cambiada_exitosamente', [
                    'datos_antes' => [
                        'password_expiraba' => $info_password['debe_cambiar']
                    ],
                    'datos_despues' => [
                        'resultado' => 'password_cambiada_exitosamente',
                        'CodigoUsuario' => $codigo_usuario,
                        'UsuarioNombres' => $usuario_data['UsuarioNombres'],
                        'UsuarioApellidos' => $usuario_data['UsuarioApellidos']
                    ]
                ],
                'medio', 'exito', 'App_usuario');
            
            /*-*-*-*-*-* elimino el token que le había creado al formulario *-*-*-*-*-*/
            $this->eliminar_token_csrf('cambioPasswordObligatorio');
            
            /*-*-*-*-*-* Normalizamos el tiempo de respuesta *-*-*-*-*-*/           
            $this->normalizar_tiempo_respuesta();
            
            return json_encode([
                "success" => true,
                "mensaje" => "Contraseña cambiada exitosamente. Ya puedes usar el sistema normalmente."
            ], JSON_UNESCAPED_UNICODE);
            
        } else {
            /*-*-*-*-*-* registro logs de seguimiento y auditoría *-*-*-*-*-*/
            $this->guardar_log(
                'error_cambio_password_temporal', [
                    'datos_antes' => [
                        'accion' => 'cambio_password_temporal'
                    ],
                    'datos_despues' => [
                        'resultado' => 'error_actualizacion_bd',
                        'CodigoUsuario' => $codigo_usuario
                    ]
                ],
                'alto', 'error', 'App_usuario');
            
            /*-*-*-*-*-* Normalizamos el tiempo de respuesta *-*-*-*-*-*/           
            $this->normalizar_tiempo_respuesta();
            
            return json_encode([
                "success" => false,
                "mensaje" => "Error al cambiar la contraseña. Intenta nuevamente"
            ], JSON_UNESCAPED_UNICODE);
        }
    }

	//===========================================================================================================
    // FUNCIÓN DE LOGIN - AGREGAR AL FINAL DEL CONTROLADOR EXISTENTE
    //===========================================================================================================
    
    public function login_usuario_controlador(){
        
        /*-------------------//-------- PASO 1 LIMPIEZA, VALIDACIONES Y SEGURIDAD --------//-----------------------*/	
        /************ Marcar inicio del tiempo para función normalizar el tiempo de respuestas ********************/
        $this->tiempo_inicio = microtime(true);
        
        /************ VALIDACION 1: valida si el método es POST de lo contrario bloquea proceso ********************/
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            /*-*-*-*-*-* registro logs de seguimiento y auditoría *-*-*-*-*-*/
            $this->guardar_log(
                'metodo_http_invalido_login', [
                    'datos_antes' => [
                        'metodo_recibido' => $_SERVER['REQUEST_METHOD'],
                        'esperado' => 'POST'
                    ],
                    'datos_despues' => [
                        'accion' => 'rechazado'
                    ]
                ], 
                'alto', 'bloqueado', 'App_login');

            http_response_code(405);
            return json_encode([
                "success" => false,
                "message" => "Método no permitido"
            ], JSON_UNESCAPED_UNICODE);
        }
        
        /************ VALIDACION 2: Detectar llenado automatizado (ataques con bot) **************************/
        $numero_campos = count($_POST);
        $tiempo_por_campo = 3; // Mayor tiempo para login (más crítico)
        $umbral_minimo = ($numero_campos - 1) * $tiempo_por_campo;
        /*-*-*-*-*-* llamo función para detectar bots atacantes *-*-*-*-*-*/
        $analisis_bot = $this->es_bot_sospechoso($umbral_minimo, $numero_campos);
        
        if ($analisis_bot['es_bot']) {
            /*-*-*-*-*-* Normalizamos el tiempo de respuesta *-*-*-*-*-*/           
            $this->normalizar_tiempo_respuesta();
            /*-*-*-*-*-* Termino función de login *-*-*-*-*-*/ 
            return json_encode([
                "success" => false,
                "message" => "Actividad sospechosa detectada. Intenta más despacio."
            ], JSON_UNESCAPED_UNICODE);
        }
        
        /************ VALIDACION 3: Verificar token CSRF ******************************************************/
        if(!isset($_POST['csrf_token_login']) || !$this->validar_csrf($_POST['csrf_token_login'],'loginForm')){
            /*-*-*-*-*-* registro logs de seguimiento y auditoría *-*-*-*-*-*/
            $this->guardar_log(
                'csrf_token_invalido_login', [
                    'datos_antes' => [
                        'Token_creado'=>'Diferente al recibido'
                    ],
                    'datos_despues' => [
                        'formulario' => 'loginForm',
                        'token_recibido_hash' => isset($_POST['csrf_token_login']) ? hash('sha256', $_POST['csrf_token_login']) : 'no_enviado',
                        'session_id' => session_id()
                    ]
                ],
                'alto', 'bloqueado', 'App_login');
            
            /*-*-*-*-*-* Normalizamos el tiempo de respuesta *-*-*-*-*-*/           
            $this->normalizar_tiempo_respuesta();
            /*-*-*-*-*-* Termino función de login *-*-*-*-*-*/
            return json_encode([
                "success" => false,
                "message" => "Token de seguridad inválido. Recarga la página."
            ], JSON_UNESCAPED_UNICODE);
        }
        
        /************ VALIDACION 4: Límite de intentos de login **********************************************/
        if(!$this->verificar_intentos('login_attempts', 5, 900)){ // 5 intentos, 15 minutos
            /*-*-*-*-*-* registro logs de seguimiento y auditoría *-*-*-*-*-*/
            $this->guardar_log(
                'login_attempts_exceeded', [
                    'datos_antes' => [
                        'intentos'=>'Usuario alcanzó máximo de intentos'
                    ],
                    'datos_despues' => [
                        'ip_bloqueada' => $this->obtener_ip(),
                        'tiempo_bloqueo_minutos' => 15
                    ],
                ],
                'critico', 'bloqueado', 'App_login');
            /*-*-*-*-*-* Normalizamos el tiempo de respuesta *-*-*-*-*-*/           
            $this->normalizar_tiempo_respuesta();
            /*-*-*-*-*-* Termino función de login *-*-*-*-*-*/
            
            return json_encode([
                "success" => false,
                "message" => "Demasiados intentos fallidos. Espera 15 minutos antes de intentar de nuevo."
            ], JSON_UNESCAPED_UNICODE);
        }
        
        /************ VALIDACION 5: limpiar los datos recibidos ****************************/ 
        $resultado_usuario = $this->limpiar_datos($_POST['usuario'], 'email', 'campo-usuario');
        $resultado_password = $this->limpiar_datos($_POST['clave'], 'password', 'campo-password');
        
        // Verificar si hay ataques en los datos
        if (!$resultado_usuario['es_seguro'] || !$resultado_password['es_seguro']) {
            /*-*-*-*-*-* registro logs de seguimiento y auditoría *-*-*-*-*-*/
            $this->guardar_log(
                'login_datos_maliciosos', [
                    'datos_antes' => [
                        'usuario_ataques' => $resultado_usuario['ataques_detectados'],
                        'password_ataques' => $resultado_password['ataques_detectados']
                    ],
                    'datos_despues' => [
                        'accion' => 'login_rechazado_por_ataques'
                    ],
                ],
                'alto', 'bloqueado', 'App_login'); 

            /*-*-*-*-*-* Normalizamos el tiempo de respuesta *-*-*-*-*-*/           
            $this->normalizar_tiempo_respuesta();
            /*-*-*-*-*-* Termino función de login *-*-*-*-*-*/
            return json_encode([
                "success" => false,
                "message" => "Datos no válidos detectados."
            ], JSON_UNESCAPED_UNICODE);
        }
        
        // Extraer datos limpios
        $usuario_limpio = $resultado_usuario['dato_limpio'];
        $password_limpio = $resultado_password['dato_limpio'];
        
        /************ VALIDACION 6: verificar campos no vacíos **********************************************/
        if(empty($usuario_limpio) || empty($password_limpio)) {
            /*-*-*-*-*-* Normalizamos el tiempo de respuesta *-*-*-*-*-*/           
            $this->normalizar_tiempo_respuesta();
            /*-*-*-*-*-* Termino función de login *-*-*-*-*-*/
            return json_encode([
                "success" => false,
                "message" => "Usuario y contraseña son obligatorios"
            ], JSON_UNESCAPED_UNICODE);
        }
        
        /*-----------------------//-------- PASO 2: VERIFICACIÓN DE CREDENCIALES --------//---------------------------*/		

        // Obtener datos del usuario
        $datos_usuario = $this->obtener_usuario_para_login($usuario_limpio);
        
        if(!$datos_usuario) {
            /*-*-*-*-*-* registro logs de seguimiento y auditoría *-*-*-*-*-*/
            $this->guardar_log(
                'login_usuario_no_encontrado', [
                    'datos_antes' => [
                        'usuario_intentado' => $usuario_limpio
                    ],
                    'datos_despues' => [
                        'resultado' => 'usuario_inexistente'
                    ],
                ],
                'medio', 'error', 'App_login');
            
            /*-*-*-*-*-* Normalizamos el tiempo de respuesta *-*-*-*-*-*/           
            $this->normalizar_tiempo_respuesta();
            /*-*-*-*-*-* Termino función de login *-*-*-*-*-*/
            return json_encode([
                "success" => false,
                "message" => "Credenciales incorrectas"
            ], JSON_UNESCAPED_UNICODE);
        }
        
        // Verificar estado del usuario
        if($datos_usuario['UsuarioEstado'] !== 'Activo') {
            /*-*-*-*-*-* registro logs de seguimiento y auditoría *-*-*-*-*-*/
            $this->guardar_log(
                'login_usuario_inactivo', [
                    'datos_antes' => [
                        'usuario' => $usuario_limpio,
                        'estado_actual' => $datos_usuario['UsuarioEstado']
                    ],
                    'datos_despues' => [
                        'resultado' => 'acceso_denegado_por_estado'
                    ],
                ],
                'medio', 'bloqueado', 'App_login');
            
            /*-*-*-*-*-* Normalizamos el tiempo de respuesta *-*-*-*-*-*/           
            $this->normalizar_tiempo_respuesta();
            /*-*-*-*-*-* Termino función de login *-*-*-*-*-*/
            return json_encode([
                "success" => false,
                "message" => "Usuario inactivo. Contacta al administrador."
            ], JSON_UNESCAPED_UNICODE);
        }
        
        // Verificar contraseña
        if(!password_verify($password_limpio, $datos_usuario['UsuarioPassword'])) {
            /*-*-*-*-*-* registro logs de seguimiento y auditoría *-*-*-*-*-*/
            $this->guardar_log(
                'login_password_incorrecta', [
                    'datos_antes' => [
                        'usuario' => $usuario_limpio
                    ],
                    'datos_despues' => [
                        'resultado' => 'password_incorrecta'
                    ],
                ],
                'medio', 'error', 'App_login');
            
            /*-*-*-*-*-* Normalizamos el tiempo de respuesta *-*-*-*-*-*/           
            $this->normalizar_tiempo_respuesta();
            /*-*-*-*-*-* Termino función de login *-*-*-*-*-*/
            return json_encode([
                "success" => false,
                "message" => "Credenciales incorrectas"
            ], JSON_UNESCAPED_UNICODE);
        }
        
        /*-----------------------//-------- PASO 3: CREAR SESIÓN SEGURA --------//---------------------------*/		

        // Regenerar session ID por seguridad
        session_regenerate_id(true);
        session_start(['name' => SESION]);
		
        // Crear sesión
        $_SESSION['sesionactiva'] = true;
        $_SESSION['UsuarioId'] = $datos_usuario['UsuarioId'];
        $_SESSION['CodigoUsuario'] = $datos_usuario['UsuarioCodigo'];
        $_SESSION['UsuarioUsuario'] = $datos_usuario['UsuarioNombres'] . ' ' . $datos_usuario['UsuarioApellidos'];
        $_SESSION['UsuarioEmail'] = $datos_usuario['UsuarioEmail'];
        $_SESSION['UsuarioCargo'] = $datos_usuario['UsuarioCargo'];
        $_SESSION['UsuarioDepartamento'] = $datos_usuario['UsuarioDepartamento'];
        $_SESSION['UsuarioEmpresaId'] = $datos_usuario['UsuarioEmpresaId'];
        $_SESSION['EmpresaNombre'] = $datos_usuario['EmpresaNombre'];
        $_SESSION['UsuarioFoto'] = $datos_usuario['UsuarioFoto'];
        
        // Actualizar último acceso
        $this->actualizar_ultimo_acceso($datos_usuario['UsuarioId']);
        
        // Verificar si tiene contraseña temporal
        $info_password_temporal = $this->verificar_password_temporal($datos_usuario['UsuarioCodigo']);
        
        /*-*-*-*-*-* registro logs de seguimiento y auditoría *-*-*-*-*-*/
        $this->guardar_log(
            'login_exitoso', [
                'datos_antes' => [
                    'usuario' => $usuario_limpio
                ],
                'datos_despues' => [
                    'usuario_id' => $datos_usuario['UsuarioId'],
                    'codigo_usuario' => $datos_usuario['UsuarioCodigo'],
                    'nombres' => $datos_usuario['UsuarioNombres'],
                    'apellidos' => $datos_usuario['UsuarioApellidos'],
                    'empresa' => $datos_usuario['EmpresaNombre'],
                    'tiene_password_temporal' => $info_password_temporal ? $info_password_temporal['debe_cambiar'] : false,
                    'session_id' => session_id()
                ],
            ],
            'bajo', 'exito', 'App_login');
        
        /*-*-*-*-*-* elimino el token que le había creado al formulario *-*-*-*-*-*/
        $this->eliminar_token_csrf('loginForm');
        
        /*-*-*-*-*-* Normalizamos el tiempo de respuesta *-*-*-*-*-*/           
        $this->normalizar_tiempo_respuesta();
        /*-*-*-*-*-* Termino función de login *-*-*-*-*-*/
        
        // Respuesta según tipo de contraseña
        if($info_password_temporal && $info_password_temporal['debe_cambiar']) {
            return json_encode([
                "success" => true,
                "message" => "Login exitoso. Debes cambiar tu contraseña temporal.",
                "redirect_url" => SERVERURL . "dashboard/",
                "password_temporal" => true,
                "dias_restantes" => $info_password_temporal['dias_restantes']
            ], JSON_UNESCAPED_UNICODE);
        } else {
            return json_encode([
                "success" => true,
                "message" => "Login exitoso. Bienvenido " . $datos_usuario['UsuarioNombres'],
                "redirect_url" => SERVERURL . "dashboard/"
            ], JSON_UNESCAPED_UNICODE);
        }
    }

    //===========================================================================================================
    // FUNCIÓN PARA LOGOUT SEGURO
    //===========================================================================================================
    
    public function logout_usuario_controlador(){
        
        /*-*-*-*-*-* registro logs de seguimiento y auditoría antes de destruir sesión *-*-*-*-*-*/
        if(isset($_SESSION['CodigoUsuario'])) {
            $this->guardar_log(
                'logout_usuario', [
                    'datos_antes' => [
                        'usuario_codigo' => $_SESSION['CodigoUsuario'],
                        'usuario_nombres' => $_SESSION['UsuarioUsuario'] ?? 'no_definido',
                        'session_id' => session_id()
                    ],
                    'datos_despues' => [
                        'resultado' => 'sesion_cerrada_exitosamente'
                    ],
                ],
                'bajo', 'exito', 'App_login');
        }
        
        // Destruir sesión
        session_unset();
        session_destroy();
        
        // Regenerar sesión limpia
        session_start(['name' => SESION]);
        
        return json_encode([
            "success" => true,
            "message" => "Sesión cerrada exitosamente",
            "redirect_url" => SERVERURL
        ], JSON_UNESCAPED_UNICODE);
    }
	
	//===========================================================================================================
    // LISTAR USUARIOS CON PAGINACIÓN Y FILTROS
    // Función para obtener usuarios con filtros aplicados y paginación
    //===========================================================================================================
	
	public function listar_usuarios_controlador(){
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
				'alto', 'bloqueado', 'App_usuarios');

            http_response_code(405); // Method Not Allowed
            return json_encode(["error" => "Method not allowed"]);
        }

		
	/************ VALIDACION 3: Verificar si trae el token que se creo previamente para formulado usuarios ******/
        if(!isset($_POST['csrf_token_list_usuarios']) || !$this->validar_csrf($_POST['csrf_token_list_usuarios'],'listUsuarios')){
			/*-*-*-*-*-* registro logs de seguimiento y auditoria *-*-*-*-*-*/
			$this->guardar_log(
				'csrf_token_invalido', [
					'datos_antes' => [
						'Token_creado'=>'Diferente al recibido'
						],
					'datos_despues' => [
						'formulario' => 'listUsuarios',
						'token_recibido_hash' => isset($_POST['csrf_token_list_usuarios']) ? hash('sha256', $_POST['csrf_token_list_usuarios']) : 'no_enviado',
						'session_id' => session_id(),
						'CodigoUsuario' => $_SESSION['CodigoUsuario'] ?? 'no_definido',
						'UsuarioId' => $_SESSION['UsuarioId'] ?? 'no_definido'
					]
				],
				'alto', 'bloqueado', 'App_usuarios');
			
            /*-*-*-*-*-* Normalizamos el tiempo de respuesta *-*-*-*-*-*/           
            $this->normalizar_tiempo_respuesta();
			/*-*-*-*-*-* Termino función para listar usuarios *-*-*-*-*-*/
			return  json_encode([
				"Alerta" => "simple",
				"Titulo" => "Token inválido",
				"Texto" => "Token de seguridad inválido. Recarga la página e intenta nuevamente", 
				"Tipo" => "warning"
			], JSON_UNESCAPED_UNICODE);
			
        }

	/************ VALIDACION 4: verificar permisos del usuario *************************************************/
		if(!$this->verificar_permisos('listar_usuarios', null, false)){ // false = no verificar empresa para listar
			$this->guardar_log('Usuario sin permisos para enlistar usuarios', [
				'datos_antes' => ['usuario'=>'sin permisos'],
				'datos_despues' => [
					'CodigoUsuario' => $_SESSION['CodigoUsuario'],
					'UsuarioId' => $_SESSION['UsuarioId']
				],
			], 'alto', 'bloqueado', 'App_usuarios');

			/*-*-*-*-*-* Normalizamos el tiempo de respuesta *-*-*-*-*-*/           
            $this->normalizar_tiempo_respuesta();
			/*-*-*-*-*-* Termino función para listar usuarios *-*-*-*-*-*/
			return json_encode([
				"Alerta" => "simple",
				"Titulo" => "Sin permisos",
				"Texto" => "No tienes permisos para listar usuarios", 
				"Tipo" => "warning"
			], JSON_UNESCAPED_UNICODE);
		}
		
		
	/************ VALIDACION 5: limpiar los datos recibidos ****************************************************/ 
        $resultados_limpieza = [
            'shareusuario' => $this->limpiar_datos($_POST['shareusuario'], 'texto', 'shareusuario'),
            'estadousuario' => $this->limpiar_datos($_POST['estadousuario'], 'texto', 'estadousuario'),
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
			/*-*-*-*-*-* Termino función para listar usuarios *-*-*-*-*-*/
            return json_encode([
                "Titulo" => "Datos no válidos",
                "Texto" => "Los datos enviados contienen información no permitida.",
                "Tipo" => "warning"
            ], JSON_UNESCAPED_UNICODE);
        }

	/************ VALIDACION 7: Si no hay ataques, extraer datos limpios y continuar ***************************/
        $datos_usuario = [];
        foreach ($resultados_limpieza as $campo => $resultado) {
            $datos_usuario[$campo] = $resultado['dato_limpio'];
        }	
		
		/*-------------------//-------- PASO 2 PROCESAMIENTO DE DATOS --------//-----------------------*/
        
        // Validar página
        $pagina = max(1, (int)$datos_usuario['pagina']);
        $registros_por_pagina = 5; // Puedes hacer esto configurable
        
        try {
			
			// NUEVO: Determinar filtro de empresa según tipo de usuario
        	$filtro_empresa = $this->determinar_filtro_empresa();
			
            // Llamar al modelo para obtener los usuarios
            $resultado = $this->listar_usuarios_modelo($datos_usuario, $pagina, $registros_por_pagina, $filtro_empresa);
           
			// Obtener estadísticas con filtro
			$estadisticas = $this->obtener_estadisticas_usuarios_modelo($filtro_empresa);
			
			$vista_tipo = $datos_usuario['vista_tipo'];

			if ($vista_tipo === 'grid') {
				$html_tabla = $this->generar_html_cards_usuarios($resultado['usuarios'], $resultado['paginacion']);
			} else {
				$html_tabla = $this->generar_html_tabla_usuarios($resultado['usuarios'], $resultado['paginacion']);
			}
            
            // Generar HTML de las tarjetas de estadísticas
            $html_estadisticas = $this->generar_html_estadisticas_usuarios($estadisticas);
            
            /*-*-*-*-*-* registro logs de seguimiento y auditoria *-*-*-*-*-*/
            $this->guardar_log(
                'usuarios_listados_exitosamente', [
                    'datos_antes' => [
                        'filtros_aplicados' => $datos_usuario,
                        'pagina_solicitada' => $pagina
                    ],
                    'datos_despues' => [
                        'usuarios_encontrados' => count($resultado['usuarios']),
                        'total_registros' => $resultado['paginacion']['total_registros']
                    ]
                ],
                'bajo', 'exito', 'App_usuarios'
            );
            
            /*-*-*-*-*-* Normalizamos el tiempo de respuesta *-*-*-*-*-*/
            $this->normalizar_tiempo_respuesta();
            
            /*-*-*-*-*-* Respuesta exitosa *-*-*-*-*-*/
            return json_encode([
                "status" => "success",
                "html_tabla" => $html_tabla,
                "html_estadisticas" => $html_estadisticas,
                "paginacion" => $resultado['paginacion'],
                "total_usuarios" => $resultado['paginacion']['total_registros']
            ], JSON_UNESCAPED_UNICODE);
            
        } catch (Exception $e) {
            /*-*-*-*-*-* registro logs de seguimiento y auditoria *-*-*-*-*-*/
            $this->guardar_log(
                'error_listar_usuarios', [
                    'datos_antes' => [
                        'filtros' => $datos_usuario,
                        'pagina' => $pagina
                    ],
                    'datos_despues' => [
                        'error' => $e->getMessage()
                    ]
                ],
                'alto', 'error', 'App_usuarios'
            );
            
            /*-*-*-*-*-* Normalizamos el tiempo de respuesta *-*-*-*-*-*/
            $this->normalizar_tiempo_respuesta();
            
            return json_encode([
                "Alerta" => "simple",
                "Titulo" => "Error interno",
                "Texto" => "Ocurrió un error al cargar los usuarios. Intenta nuevamente.",
                "Tipo" => "error"
            ], JSON_UNESCAPED_UNICODE);
        }
	}
	
	//===========================================================================================================
    // GENERAR HTML DE LA TABLA DE USUARIOS
    // Función para generar el HTML de la tabla con los datos en estilo lista
    //===========================================================================================================
    
    private function generar_html_tabla_usuarios($usuarios, $paginacion) {
        $html = '
		<table  class="table usuario-table">
			<thead>
				<tr>
					<th>Código</th>
					<th>Usuario</th>
					<th>Documento</th>
					<th>Email</th>
					<th>Teléfono</th>
					<th>Cargo</th>
					<th>Empresa</th>
					<th>Estado</th>
					<th>Acciones</th>
				</tr>
			</thead>
		
		<tbody>';
        
        if (empty($usuarios)) {
            $html .= '
                <tr>
                    <td colspan="9" class="text-center py-5">
                        <i class="bi bi-people fa-3x text-muted mb-3"></i>
                        <h5 class="text-muted">No hay usuarios registrados</h5>
                        <p class="text-muted">Comienza creando tu primer usuario</p>
                        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalNuevoUsuario">
                            <i class="bi bi-plus me-1"></i>
                            Crear Primer Usuario
                        </button>
                    </td>
                </tr>';
        } else {
			
			$separador = $this->encryption_deterministico('1n49n', 'separador_usuario');
			foreach ($usuarios as $usuario) {
				
                $codigo_encriptado = $_SESSION['csrf_listUsuarios']. $separador .$this->encryption($usuario['UsuarioId']); 
    			
				// Generar dropdown de estados
				$opciones_estado = [
					'Activo' => ['color' => 'success', 'icono' => 'check-circle'],
					'Inactivo' => ['color' => 'warning', 'icono' => 'pause-circle'],
					'Bloqueado' => ['color' => 'danger', 'icono' => 'lock'],
					'Eliminado' => ['color' => 'secondary', 'icono' => 'trash']
				];

				$dropdown_estados = '<div class="dropdown">
					<button class="btn btn-outline-' . $opciones_estado[$usuario['UsuarioEstado']]['color'] . ' btn-sm dropdown-toggle estado-dropdown" 
							type="button" 
							data-bs-toggle="dropdown" 
							data-usuario-id="' . $codigo_encriptado . '"
							data-estado-actual="' . $usuario['UsuarioEstado'] . '"
							data-usuario-nombre="' . htmlspecialchars($usuario['UsuarioNombres'] . ' ' . $usuario['UsuarioApellidos']) . '">
						<i class="bi bi-' . $opciones_estado[$usuario['UsuarioEstado']]['icono'] . ' me-1"></i>
						' . $usuario['UsuarioEstado'] . '
					</button>
					<ul class="dropdown-menu">';

				// Agregar opciones (excepto la actual y "Eliminado")
				foreach ($opciones_estado as $estado => $config) {
					if ($estado !== $usuario['UsuarioEstado'] && $estado !== 'Eliminado') {
						$dropdown_estados .= '
							<li>
								<a class="dropdown-item cambiar-estado-usuario" 
								   href="#" 
								   data-nuevo-estado="' . $estado . '"
								   data-usuario-id="' . $codigo_encriptado . '"
								   data-usuario-nombre="' . htmlspecialchars($usuario['UsuarioNombres'] . ' ' . $usuario['UsuarioApellidos']) . '">
									<i class="bi bi-' . $config['icono'] . ' me-2 text-' . $config['color'] . '"></i>
									Cambiar a ' . $estado . '
								</a>
							</li>';
					}
				}

				$dropdown_estados .= '
					</ul>
				</div>';

                // Formatear empresa asociada
                $info_empresa = 'Sin asignar';
                if (!empty($usuario['EmpresaNombre'])) {
                    $info_empresa = htmlspecialchars($usuario['EmpresaNombre']);
                    if (!empty($usuario['SucursalNombre'])) {
                        $info_empresa .= '<br><small class="text-muted">Sucursal: ' . htmlspecialchars($usuario['SucursalNombre']) . '</small>';
                    }
                }
				
                $html .= '
                    <tr>
                        <td>
                            <span class="badge-custom badge-codigo">' . htmlspecialchars($usuario['UsuarioCodigo']) . '</span>
                        </td>
                        <td>
                            <div>
                                <strong>' . htmlspecialchars($usuario['UsuarioNombres'] . ' ' . $usuario['UsuarioApellidos']) . '</strong>
                                <br>
                                <small class="text-muted">' . htmlspecialchars($usuario['UsuarioDepartamento']) . '</small>
                            </div>
                        </td>
                        <td>
                            <div>
                                <strong>' . htmlspecialchars($usuario['UsuarioDocumento']) . '</strong>
                                <br>
                                <small class="text-muted">' . htmlspecialchars($usuario['UsuarioTipoDocumento']) . '</small>
                            </div>
                        </td>
                        <td>
                            <a href="mailto:' . htmlspecialchars($usuario['UsuarioEmail']) . '" class="text-decoration-none">
                                ' . htmlspecialchars($usuario['UsuarioEmail']) . '
                            </a>
                        </td>
                        <td>' . htmlspecialchars($usuario['UsuarioTelefono'] ?? 'N/A') . '</td>
                        <td>
                            <strong>' . htmlspecialchars($usuario['UsuarioCargo']) . '</strong>
                        </td>
                        <td>' . $info_empresa . '</td>
                        <td>' . $dropdown_estados . '</td>
                        <td>
                            <div class="btn-actions">';
								if($usuario['UsuarioEstado'] !== 'Eliminado'){
								$html .= '
                                <button class="btn btn-outline-primary btn-action" title="Ver / Editar" onclick="verUsuario(\'' .$codigo_encriptado . '\')">
									<i class="bi bi-pencil-square"></i>
								</button>
                                <button class="btn btn-outline-danger btn-action" title="Eliminar" onclick="eliminarUsuario(\'' .$codigo_encriptado . '\', \'' . htmlspecialchars($usuario['UsuarioNombres'] . ' ' . $usuario['UsuarioApellidos']) . '\',\''.$paginacion['pagina_actual'].'\')">
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
            $html .= $this->generar_html_paginacion_usuarios($paginacion);
        }
        
        return $html;
    }
    
	//===========================================================================================================
    // GENERAR HTML DE CARDS DE USUARIOS
    // Función para generar el HTML de la tabla con los datos en estilo grid
    //===========================================================================================================

    private function generar_html_cards_usuarios($usuarios, $paginacion) {
        $html = '<div class="row">';
        
        if (empty($usuarios)) {
            $html .= '
                <div class="col-12 text-center py-5">
                    <i class="bi bi-people fa-3x text-muted mb-3"></i>
                    <h5 class="text-muted">No hay usuarios registrados</h5>
                    <p class="text-muted">Comienza creando tu primer usuario</p>
                    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalNuevoUsuario">
                        <i class="bi bi-plus me-1"></i>
                        Crear Primer Usuario
                    </button>
                </div>';
        } else {
			
			$separador = $this->encryption_deterministico('1n49n', 'separador_usuario');
			
            foreach ($usuarios as $usuario) {
                              
                $codigo_encriptado = $_SESSION['csrf_listUsuarios']. $separador .$this->encryption($usuario['UsuarioId']); 
    			
				// Generar dropdown de estados
				$opciones_estado = [
					'Activo' => ['color' => 'success', 'icono' => 'check-circle'],
					'Inactivo' => ['color' => 'warning', 'icono' => 'pause-circle'],
					'Bloqueado' => ['color' => 'danger', 'icono' => 'lock'],
					'Eliminado' => ['color' => 'secondary', 'icono' => 'trash']
				];

				$dropdown_estados = '<div class="dropdown">
					<button class="btn btn-' . $opciones_estado[$usuario['UsuarioEstado']]['color'] . ' btn-sm dropdown-toggle estado-dropdown" 
							type="button" 
							data-bs-toggle="dropdown" 
							data-usuario-id="' . $codigo_encriptado . '"
							data-estado-actual="' . $usuario['UsuarioEstado'] . '"
							data-usuario-nombre="' . htmlspecialchars($usuario['UsuarioNombres'] . ' ' . $usuario['UsuarioApellidos']) . '">
						<i class="bi bi-' . $opciones_estado[$usuario['UsuarioEstado']]['icono'] . ' me-1"></i>
						' . $usuario['UsuarioEstado'] . '
					</button>
					<ul class="dropdown-menu">';

				// Agregar opciones (excepto la actual y "Eliminado")
				foreach ($opciones_estado as $estado => $config) {
					if ($estado !== $usuario['UsuarioEstado'] && $estado !== 'Eliminado') {
						$dropdown_estados .= '
							<li>
								<a class="dropdown-item cambiar-estado-usuario" 
								   href="#" 
								   data-nuevo-estado="' . $estado . '"
								   data-usuario-id="' . $codigo_encriptado . '"
								   data-usuario-nombre="' . htmlspecialchars($usuario['UsuarioNombres'] . ' ' . $usuario['UsuarioApellidos']) . '">
									<i class="bi bi-' . $config['icono'] . ' me-2 text-' . $config['color'] . '"></i>
									Cambiar a ' . $estado . '
								</a>
							</li>';
					}
				}

				$dropdown_estados .= '
					</ul>
				</div>';

                // Formatear empresa asociada
                $info_empresa = 'Sin asignar';
                if (!empty($usuario['EmpresaNombre'])) {
                    $info_empresa = $usuario['EmpresaNombre'];
                    if (!empty($usuario['SucursalNombre'])) {
                        $info_empresa .= ' / ' . $usuario['SucursalNombre'];
                    }
                }
				
                $html .= '
                    <div class="col-lg-4 col-md-6 mb-4">
                        <div class="card h-100 shadow-sm usuario-card">
                            <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                                <h6 class="mb-0 text-truncate" title="' . htmlspecialchars($usuario['UsuarioNombres'] . ' ' . $usuario['UsuarioApellidos']) . '">
                                    <i class="bi bi-person me-2"></i>
                                    ' . htmlspecialchars($usuario['UsuarioNombres'] . ' ' . $usuario['UsuarioApellidos']) . '
                                </h6>
                                ' . $dropdown_estados . '
                            </div>
                            <div class="card-body">
                                <div class="mb-2">
                                    <strong class="text-primary">Código:</strong> 
                                    <span class="badge bg-light text-dark">' . htmlspecialchars($usuario['UsuarioCodigo']) . '</span>
                                </div>
                                <div class="mb-2">
                                    <strong class="text-primary">Documento:</strong>
                                    <span class="small">' . htmlspecialchars($usuario['UsuarioDocumento']) . ' (' . htmlspecialchars($usuario['UsuarioTipoDocumento']) . ')</span>
                                </div>
                                <div class="mb-2">
                                    <strong class="text-primary">Email:</strong>
                                    <a href="mailto:' . htmlspecialchars($usuario['UsuarioEmail']) . '" class="text-decoration-none small">
                                        ' . htmlspecialchars($usuario['UsuarioEmail']) . '
                                    </a>
                                </div>
                                <div class="mb-2">
                                    <strong class="text-primary">Teléfono:</strong>
                                    <span class="small">' . htmlspecialchars($usuario['UsuarioTelefono'] ?? 'N/A') . '</span>
                                </div>
                                <div class="mb-2">
                                    <strong class="text-primary">Cargo:</strong>
                                    <span class="small">' . htmlspecialchars($usuario['UsuarioCargo']) . '</span>
                                </div>
                                <div class="mb-2">
                                    <strong class="text-primary">Departamento:</strong>
                                    <span class="small text-muted">' . htmlspecialchars($usuario['UsuarioDepartamento']) . '</span>
                                </div>
                                <div class="text-center mb-3">
                                    <span class="badge bg-secondary text-white">
                                        <i class="bi bi-building me-1"></i>
                                        ' . htmlspecialchars($info_empresa) . '
                                    </span>
                                </div>
                            </div>
                            <div class="card-footer bg-light">
                                <div class="d-grid gap-2 d-md-flex justify-content-md-center">';
									if($usuario['UsuarioEstado'] !== 'Eliminado'){
									$html .= '
										<button class="btn btn-outline-primary btn-sm flex-fill" title="Ver / Editar" onclick="verUsuario(\'' .$codigo_encriptado . '\')">
											<i class="bi bi-pencil-square"></i> Ver / Editar
										</button>
										<button class="btn btn-outline-danger btn-sm flex-fill" title="Eliminar" onclick="eliminarUsuario(\'' .$codigo_encriptado . '\', \'' . htmlspecialchars($usuario['UsuarioNombres'] . ' ' . $usuario['UsuarioApellidos']) . '\')">
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
            $html .= $this->generar_html_paginacion_usuarios($paginacion);
        }
        
        return $html;
    }
	
    //===========================================================================================================
    // GENERAR HTML DE PAGINACIÓN PARA USUARIOS
    // Función para generar los controles de paginación
    //===========================================================================================================
    
    private function generar_html_paginacion_usuarios($paginacion) {
        $html = '<div class="d-flex justify-content-between align-items-center mt-3 px-3 pb-3">';
        
        // Información de registros
        $html .= '<div class="text-muted small">';
        $html .= 'Mostrando ' . $paginacion['desde'] . ' a ' . $paginacion['hasta'] . ' de ' . $paginacion['total_registros'] . ' registros';
        $html .= '</div>';
        
        // Controles de paginación
        if ($paginacion['total_paginas'] > 1) {
            $html .= '<nav aria-label="Paginación de usuarios">';
            $html .= '<ul class="pagination pagination-sm mb-0">';
            
            // Botón anterior
            if ($paginacion['pagina_actual'] > 1) {
                $html .= '<li class="page-item">';
                $html .= '<button class="page-link" onclick="cargarPaginaUsuarios(' . ($paginacion['pagina_actual'] - 1) . ')">';
                $html .= '<i class="bi bi-chevron-left"></i> Anterior</button>';
                $html .= '</li>';
            }
            
            // Páginas
            $inicio = max(1, $paginacion['pagina_actual'] - 2);
            $fin = min($paginacion['total_paginas'], $paginacion['pagina_actual'] + 2);
            
            for ($i = $inicio; $i <= $fin; $i++) {
                $activa = ($i == $paginacion['pagina_actual']) ? 'active' : '';
                $html .= '<li class="page-item ' . $activa . '">';
                $html .= '<button class="page-link" onclick="cargarPaginaUsuarios(' . $i . ')">' . $i . '</button>';
                $html .= '</li>';
            }
            
            // Botón siguiente
            if ($paginacion['pagina_actual'] < $paginacion['total_paginas']) {
                $html .= '<li class="page-item">';
                $html .= '<button class="page-link" onclick="cargarPaginaUsuarios(' . ($paginacion['pagina_actual'] + 1) . ')">';
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
    // GENERAR HTML DE ESTADÍSTICAS PARA USUARIOS
    // Función para generar las tarjetas de estadísticas
    //===========================================================================================================
    
	private function generar_html_estadisticas_usuarios($estadisticas) {
		return '
			<div class="col-lg-2 col-md-6 mb-3">
				<div class="stats-card purple">
					<div class="stats-content">
						<div class="stats-number">' . $estadisticas['total_usuarios'] . '</div>
						<div class="stats-label">Total Usuarios</div>
					</div>
					<div class="stats-icon">
						<i class="bi bi-people"></i>
					</div>
				</div>
			</div>
			<div class="col-lg-2 col-md-6 mb-3">
				<div class="stats-card green">
					<div class="stats-content">
						<div class="stats-number">' . $estadisticas['usuarios_activos'] . '</div>
						<div class="stats-label">Usuarios Activos</div>
					</div>
					<div class="stats-icon">
						<i class="bi bi-person-check"></i>
					</div>
				</div>
			</div>
			<div class="col-lg-2 col-md-6 mb-3">
				<div class="stats-card orange">
					<div class="stats-content">
						<div class="stats-number">' . $estadisticas['usuarios_inactivos'] . '</div>
						<div class="stats-label">Usuarios Inactivos</div>
					</div>
					<div class="stats-icon">
						<i class="bi bi-person-dash"></i>
					</div>
				</div>
			</div>
			<div class="col-lg-2 col-md-6 mb-3">
				<div class="stats-card red">
					<div class="stats-content">
						<div class="stats-number">' . $estadisticas['usuarios_bloqueados'] . '</div>
						<div class="stats-label">Usuarios Bloqueados</div>
					</div>
					<div class="stats-icon">
						<i class="bi bi-person-lock"></i>
					</div>
				</div>
			</div>';
	}
	
	//===========================================================================================================
    // ELIMINAR USUARIO (DESACTIVAR)
    // Función para eliminar usuario, solo le cambie el estado a eliminado pero no la elimina
    //===========================================================================================================
	public function eliminar_usuario_controlador(){
		/*-------------------//-------- PASO 1 LIMPIEZA, VALIDACIONES Y SEGURIDAD --------//-----------------------*/	
	/************ Marcar inicio del tiempo para normalizar respuestas *******************************************/
		$this->tiempo_inicio = microtime(true);

	/************ VALIDACION 1: Método POST *****************************************************************/
		if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
			$this->guardar_log('metodo_http_invalido_eliminar', [
				'datos_antes' => ['metodo_recibido' => $_SERVER['REQUEST_METHOD']],
				'datos_despues' => ['accion' => 'rechazado']
			], 'alto', 'bloqueado', 'App_usuario');

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
		if(!$this->verificar_permisos('eliminar_usuarios')){
			$this->guardar_log('sin_permisos_eliminar_usuario', [
				'datos_antes' => ['usuario' => $_SESSION['CodigoUsuario']],
				'datos_despues' => ['accion' => 'bloqueado']
			], 'alto', 'bloqueado', 'App_usuario');

			$this->normalizar_tiempo_respuesta();
			return json_encode([
				"Titulo" => "Sin permisos",
				"Texto" => "No tienes permisos para eliminar usuarios", 
				"Tipo" => "warning"
			], JSON_UNESCAPED_UNICODE);
		}

	/************ VALIDACION 4: Límite de intentos **********************************************************/
		if(!$this->verificar_intentos('eliminar_usuario', 3, 300)){
			$this->normalizar_tiempo_respuesta();
			return json_encode([
				"Titulo" => "Demasiados intentos",
				"Texto" => "Has superado el límite de intentos. Espera 5 minutos",
				"Tipo" => "warning"
			], JSON_UNESCAPED_UNICODE);
		}

	/************ SEPARAR código y token usando el separador encriptado *************************************/	
		$codigo_encriptado = $_POST['codigo_usuario'] ?? '';

		// Intentar separar primero
		$data_separada = $this->separar_codigo_con_token($codigo_encriptado, 'separador_usuario');

		if (!$data_separada) {
			return json_encode([
				"Titulo" => "Código inválido",
				"Texto" => "El código de usuario no es válido o está corrupto",
				"Tipo" => "warning"
			], JSON_UNESCAPED_UNICODE);
		}

		$codigo_real = $data_separada['codigo'];
		$token_encriptado = $data_separada['token_encriptado'];

		/************ VALIDAR token CSRF ************************************************************************/
		if (!$this->validar_csrf($token_encriptado, 'listUsuarios')) {
			$this->guardar_log('csrf_token_invalido_eliminar', [
				'datos_antes' => ['codigo_usuario' => $codigo_real],
				'datos_despues' => ['accion' => 'bloqueado']
			], 'alto', 'bloqueado', 'App_usuario');

			$this->normalizar_tiempo_respuesta();
			return json_encode([
				"Titulo" => "Token inválido",
				"Texto" => "Token de seguridad inválido o expirado",
				"Tipo" => "warning"
			], JSON_UNESCAPED_UNICODE);
		}

		/*-------------------//-------- PASO 2: ELIMINAR EMPRESA --------//------------------------------------*/

		/************ Verificar que la empresa existe ************************************************************/
		$usuario = $this->obtener_usuario_por_id($codigo_real);
		if (!$usuario) {
			$this->normalizar_tiempo_respuesta();
			return json_encode([
				"Titulo" => "Usuario no encontrada",
				"Texto" => "El usuario no existe o ya fue eliminado",
				"Tipo" => "warning"
			], JSON_UNESCAPED_UNICODE);
		}

		/************ Realizar eliminación (soft delete) ********************************************************/
		$resultado = $this->eliminar_usuario_modelo($usuario['UsuarioId']);
		if ($resultado) {
			/*-*-*-*-*-* Logs de eliminación exitosa *-*-*-*-*-*/
			$this->guardar_log('usuario_eliminado', [
				'datos_antes' => [
					'codigo' => $codigo_real,
					'nombre' => $usuario['UsuarioNombres'].' '.$usuario['UsuarioApellidos'],
					'id' => $usuario['UsuarioId']
				],
				'datos_despues' => [
					'resultado' => 'usuario_eliminado',
					'estado_anterior' => $usuario['UsuarioEstado ']
				],
			], 'medio', 'exito', 'App_usuario');

			/*-*-*-*-*-* Generar nuevo token para siguientes acciones *-*-*-*-*-*/
    		$nuevo_token = $this->generar_token_csrf('listUsuarios');

			$this->normalizar_tiempo_respuesta();
			return json_encode([
				"Titulo" => "Usuario eliminado",
				"Texto" => "El usuario '" . $usuario['UsuarioNombres'] . ' ' .$usuario['UsuarioApellidos']. "' ha sido eliminado exitosamente",
				"Tipo" => "success",
				"nuevo_token" => $nuevo_token  // Enviar el nuevo token al JavaScript
			], JSON_UNESCAPED_UNICODE);

		} else {
			$this->normalizar_tiempo_respuesta();
			return json_encode([
				"Titulo" => "Error",
				"Texto" => "No se pudo eliminar el usuario. Intenta nuevamente",
				"Tipo" => "error"
			], JSON_UNESCAPED_UNICODE);
		}
	}
	
	//===========================================================================================================
    // OBTENER DATOS DE UN USUARIO ESPECÍFICA
    // Función para obtener todos los datos de un usuario por su ID
    //===========================================================================================================
	
	public function obtener_usuario_controlador(){
		/*-------------------//-------- PASO 1 LIMPIEZA, VALIDACIONES Y SEGURIDAD --------//-----------------------*/	
	/************ Marcar inicio del tiempo para normalizar respuestas *******************************************/
		$this->tiempo_inicio = microtime(true);

	/************ VALIDACION 1: Método POST *****************************************************************/
		if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
			$this->guardar_log('metodo_http_invalido_obtener', [
				'datos_antes' => ['metodo_recibido' => $_SERVER['REQUEST_METHOD']],
				'datos_despues' => ['accion' => 'rechazado']
			], 'alto', 'bloqueado', 'App_usuario');

			return json_encode(["error" => "Method not allowed"]);
		}

	/************ VALIDACION 2: Verificar permisos **********************************************************/
		if(!$this->verificar_permisos('ver_usuarios')){
			$this->guardar_log('sin_permisos_ver_usuarios', [
				'datos_antes' => ['usuario' => $_SESSION['CodigoUsuario']],
				'datos_despues' => ['accion' => 'bloqueado']
			], 'alto', 'bloqueado', 'App_usuario');

			$this->normalizar_tiempo_respuesta();
			return json_encode([
				"Titulo" => "Sin permisos",
				"Texto" => "No tienes permisos para ver usuarios", 
				"Tipo" => "warning"
			], JSON_UNESCAPED_UNICODE);
		}

	/************ VALIDACION 3: Validar token CSRF **********************************************************/
		if(!isset($_POST['csrf_token_obtener']) || !$this->validar_csrf($_POST['csrf_token_obtener'], 'listUsuarios')){
			$this->guardar_log('csrf_token_invalido_obtener', [
				'datos_antes' => ['usuario_id' => $_POST['usuario_id'] ?? 'no_definido'],
				'datos_despues' => ['accion' => 'bloqueado']
			], 'alto', 'bloqueado', 'App_usuario');

			$this->normalizar_tiempo_respuesta();
			return json_encode([
				"Titulo" => "Token inválido",
				"Texto" => "Token de seguridad inválido o expirado",
				"Tipo" => "warning"
			], JSON_UNESCAPED_UNICODE);
		}
		
	/************ SEPARAR código y token usando el separador encriptado *************************************/	
		$codigo_encriptado = $_POST['usuario_id'] ?? '';

		// Intentar separar primero
		$data_separada = $this->separar_codigo_con_token($codigo_encriptado, 'separador_usuario');

		if (!$data_separada) {
			return json_encode([
				"Titulo" => "Código inválido",
				"Texto" => "El código de usuario no es válido o está corrupto",
				"Tipo" => "warning"
			], JSON_UNESCAPED_UNICODE);
		}

		$codigo_real = $data_separada['codigo'];
		$token_encriptado = $data_separada['token_encriptado'];
		
	/************ VALIDACION 4: Limpiar y validar ID de usuario *********************************************/
		$resultado_limpieza = $this->limpiar_datos($codigo_real, 'numero', 'usuario_id');
		
		if (!$resultado_limpieza['es_seguro']) {
			$this->normalizar_tiempo_respuesta();
			return json_encode([
				"Titulo" => "ID inválido",
				"Texto" => "El ID de usuario no es válido",
				"Tipo" => "warning"
			], JSON_UNESCAPED_UNICODE);
		}

		$usuario_id = $resultado_limpieza['dato_limpio'];

		/*-------------------//-------- PASO 2: OBTENER EMPRESA --------//------------------------------------*/

		try {
	/************ Obtener datos de la usuario ***********************************************************/
			$usuario = $this->obtener_usuario_completo_modelo($usuario_id);
			
			if (!$usuario) {
				$this->normalizar_tiempo_respuesta();
				return json_encode([
					"Titulo" => "Usuario no encontrada",
					"Texto" => "La usuario no existe o no está disponible",
					"Tipo" => "warning"
				], JSON_UNESCAPED_UNICODE);
			}
			
			/*-*-*-*-*-* SEGURIDAD: Generar token específico usando sistema genérico *-*-*-*-*-*/
			$datos_extra = [
				'codigo_usuario' => $usuario['UsuarioCodigo'],
				'nombre_usuario' => $usuario['UsuarioNombres']
			];
			$token_usuario_especifico = $this->generar_token_entidad_especifico('usuario', $usuario_id, 1800, $datos_extra);
			
			/*-*-*-*-*-* SEGURIDAD: Encriptar el ID antes de enviarlo al frontend *-*-*-*-*-*/
			$usuario['UsuarioId'] = $this->encryption($usuario_id);
			
			/*-*-*-*-*-* SEGURIDAD: Agregar el token específico a la respuesta *-*-*-*-*-*/
			$usuario['TokenUsuarioEspecifico'] = $token_usuario_especifico;

			
			/*-*-*-*-*-* Log de consulta exitosa *-*-*-*-*-*/
			$this->guardar_log('usuario_consultada', [
				'datos_antes' => [
					'usuario_id_solicitado' => $usuario_id
				],
				'datos_despues' => [
					'usuario_encontrado' => $usuario['UsuarioCodigo'],
					'nombre' => $usuario['UsuarioNombres'].' '. $usuario['UsuarioApellidos'],
					'id_encriptado' => 'SI',
					'token_usuario_generado' => 'SI'					
				],
			], 'bajo', 'exito', 'App_usuario');
						
			$this->normalizar_tiempo_respuesta();
			return json_encode([
				"status" => "success",
				"usuario" => $usuario
			], JSON_UNESCAPED_UNICODE);

		} catch (Exception $e) {
			$this->guardar_log('error_obtener_empresa', [
				'datos_antes' => ['empresa_id' => $usuario_id],
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
    // ACTUALIZAR USUARIO
    // Función para actualizar los datos de un usuario existente
    //===========================================================================================================
	
	public function actualizar_usuario_controlador(){
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
		if(!$this->verificar_permisos('editar_usuario')){
			$this->guardar_log('sin_permisos_editar_usuario', [
				'datos_antes' => ['usuario' => $_SESSION['CodigoUsuario']],
				'datos_despues' => ['accion' => 'bloqueado']
			], 'alto', 'bloqueado', 'App_usuario');

			$this->normalizar_tiempo_respuesta();
			return json_encode([
				"Titulo" => "Sin permisos",
				"Texto" => "No tienes permisos para editar usuarios", 
				"Tipo" => "warning"
			], JSON_UNESCAPED_UNICODE);
		}

	/************ VALIDACION 4: Validar token CSRF **********************************************************/
		if(!isset($_POST['csrf_token_editar']) || !$this->validar_csrf($_POST['csrf_token_editar'], 'editarUsuario')){
			$this->guardar_log('csrf_token_invalido_editar', [
				'datos_antes' => ['usuario_id' => $_POST['usuario_id'] ?? 'no_definido'],
				'datos_despues' => ['accion' => 'bloqueado']
			], 'alto', 'bloqueado', 'App_usuario');

			$this->normalizar_tiempo_respuesta();
			return json_encode([
				"Titulo" => "Token inválido",
				"Texto" => "Token de seguridad inválido o expirado",
				"Tipo" => "warning"
			], JSON_UNESCAPED_UNICODE);
		}

	/************ VALIDACION 5: Límite de intentos **********************************************************/
		if(!$this->verificar_intentos('actualizar_usuario', 3, 300)){
			$this->normalizar_tiempo_respuesta();
			return json_encode([
				"Titulo" => "Demasiados intentos",
				"Texto" => "Has superado el límite de intentos. Espera 5 minutos",
				"Tipo" => "warning"
			], JSON_UNESCAPED_UNICODE);
		}
		
		/*-*-*-*-*-* SEGURIDAD: Desencriptar y validar ID de empresa *-*-*-*-*-*/
		$usuario_id_encriptado = $_POST['usuario_id'] ?? '';
		$usuario_id_desencriptado = $this->decryption($usuario_id_encriptado);

		if (!$usuario_id_desencriptado || !is_numeric($usuario_id_desencriptado)) {
			$this->guardar_log('id_usuario_invalido_actualizar', [
				'datos_antes' => ['id_recibido_hash' => hash('sha256', $usuario_id_encriptado)],
				'datos_despues' => ['accion' => 'bloqueado', 'razon' => 'desencriptacion_fallida']
			], 'alto', 'bloqueado', 'App_usuario');
			
			$this->normalizar_tiempo_respuesta();
			return json_encode([
				"Titulo" => "ID inválido",
				"Texto" => "El identificador de empresa no es válido",
				"Tipo" => "warning"
			], JSON_UNESCAPED_UNICODE);
		}
		
		/************ VALIDACION 6: Limpiar los datos recibidos *************************************************/ 
		$resultados_limpieza = [
			'UsuarioId' => $this->limpiar_datos($usuario_id_desencriptado, 'numero', 'usuario_id'),
			'UsuarioDocumento' => $this->limpiar_datos($_POST['usuario_Documento'], 'numero', 'usuario_Documento'),
			'UsuarioTipoDocumento' => $this->limpiar_datos($_POST['usuario_TipoDocumento'], 'texto', 'usuario_TipoDocumento'),
			'UsuarioNombres' => $this->limpiar_datos($_POST['usuario_Nombres'], 'texto','usuario_Nombres'),
			'UsuarioApellidos' => $this->limpiar_datos($_POST['usuario_Apellidos'], 'texto','usuario_Apellidos'),
			'UsuarioEmail' => $this->limpiar_datos($_POST['usuario_email'], 'email', 'usuario_email'),
			'UsuarioTelefono' => $this->limpiar_datos($_POST['usuario_Telefono'], 'texto', 'usuario_Telefono'),
			'UsuarioCargo' => $this->limpiar_datos($_POST['usuario_Cargo'], 'texto', 'usuario_Cargo'),
			'UsuarioDepartamento' => $this->limpiar_datos($_POST['usuario_Departamento'], 'texto', 'usuario_Departamento'),
			'UsuarioEmpresaId' => $this->limpiar_datos($_POST['usuario_EmpresaId'], 'numero', 'usuario_EmpresaId'),
			'UsuarioSucursalId' => $this->limpiar_datos($_POST['usuario_SucursalId'], 'numero', 'usuario_SucursalId'),
			'UsuarioSedeId' => $this->limpiar_datos($_POST['usuario_SedeId'], 'numero', 'usuario_SedeId')
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
			], $nivel_riesgo_maximo, 'rechazado', 'App_usuario_editar'); 

			$this->normalizar_tiempo_respuesta();
			return json_encode([
				"Titulo" => "Datos no válidos",
				"Texto" => "Los datos enviados contienen información no permitida.",
				"Tipo" => "warning"
			], JSON_UNESCAPED_UNICODE);
		}

		/************ VALIDACION 8: Extraer datos limpios ******************************************************/
		$datos_usuario = [];
		foreach ($resultados_limpieza as $campo => $resultado) {
			$datos_usuario[$campo] = $resultado['dato_limpio'];
		}
				
		/*-*-*-*-*-* SEGURIDAD EXTRA: Validar token específico usando sistema genérico *-*-*-*-*-*/
		$token_usuario_especifico = $_POST['token_usuario_especifico'] ?? '';
		
		if (!$this->validar_token_entidad_especifico('usuario', $usuario_id_desencriptado, $token_usuario_especifico)) {
			$this->normalizar_tiempo_respuesta();
			return json_encode([
				"Titulo" => "Token de usuario inválido",
				"Texto" => "El token de autorización para este usuario no es válido o ha expirado. Vuelve a cargar el usuario.",
				"Tipo" => "warning"
			], JSON_UNESCAPED_UNICODE);
		}

		/************ VALIDACION 9: Validar campos obligatorios ************************************************/
		$campos_excluir = ['UsuarioId ', 'UsuarioCodigo', 'UsuarioPassword', 'UsuarioFoto', 'UsuarioFechaRegistro', 'UsuarioFechaActualizacion', 'UsuarioUltimoAcceso ', 'UsuarioIntentosLogin', 'UsuarioFechaBloqueo', 'UsuarioPasswordCambio', 'UsuarioPasswordExpira', 'UsuarioEstado'];

		/*-*-*-*-*-* creo reglas personalizadas que complementan las de base de datos *-*-*-*-*-*/
		$reglas_personalizadas = [
			'UsuarioDocumento' => [
				'min_caracteres' => 6,  // Mínimo 6 dígitos para documentos
			],
			'UsuarioTelefono' => [
				'min_caracteres' => 7,  // Mínimo 7 dígitos para teléfonos
			],
			'UsuarioNombres' => [
				'solo_letras' => true   // Solo letras para nombres
			],
			'UsuarioApellidos' => [
				'solo_letras' => true   // Solo letras para apellidos
			]
		];
		
		$errores = $this->validar_completo($datos_usuario, 'App_usuarios_usuario', $campos_excluir, $reglas_personalizadas);

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
			$usuario_actual = $this->obtener_usuario_completo_modelo($datos_usuario['UsuarioId']);
			if (!$usuario_actual) {
				$this->normalizar_tiempo_respuesta();
				return json_encode([
					"Titulo" => "Empresa no encontrada",
					"Texto" => "La empresa no existe o ya fue eliminada",
					"Tipo" => "warning"
				], JSON_UNESCAPED_UNICODE);
			}

			/************ Verificar duplicados (excluyendo la empresa actual) ***********************************/
			if($this->verificar_documento_duplicado_actualizar($datos_usuario['UsuarioDocumento'], $datos_usuario['UsuarioId'])){
				$this->normalizar_tiempo_respuesta();
				return json_encode([
					"Titulo" => "Documento duplicado",
					"Texto" => "Ya existe otro usuario registrado con este documento", 
					"Tipo" => "warning"
				], JSON_UNESCAPED_UNICODE);
			}

			if($this->verificar_email_duplicado_actualizar($datos_usuario['UsuarioEmail'], $datos_usuario['UsuarioId'])){
				$this->normalizar_tiempo_respuesta();
				return json_encode([
					"Titulo" => "Email duplicado",
					"Texto" => "Ya existe otro usuario registrado con este email",
					"Tipo" => "warning"
				], JSON_UNESCAPED_UNICODE);
			}

			/************ Preparar datos finales ***************************************************************/
			$datos_finales = [
				'UsuarioId' => $datos_usuario['UsuarioId'],
				'UsuarioDocumento' => $datos_usuario['UsuarioDocumento'],
				'UsuarioTipoDocumento' => $datos_usuario['UsuarioTipoDocumento'],
				'UsuarioNombres' => $datos_usuario['UsuarioNombres'],
				'UsuarioApellidos' => $datos_usuario['UsuarioApellidos'],
				'UsuarioEmail' => $datos_usuario['UsuarioEmail'],
				'UsuarioTelefono' => $datos_usuario['UsuarioTelefono'],
				'UsuarioCargo' => $datos_usuario['UsuarioCargo'],
				'UsuarioDepartamento' => $datos_usuario['UsuarioDepartamento'],
				'UsuarioEmpresaId' => $datos_usuario['UsuarioEmpresaId'],
				'UsuarioSucursalId' => $datos_usuario['UsuarioSucursalId'],
				'UsuarioSedeId' => $datos_usuario['UsuarioSedeId'],
				'UsuarioFechaActualizacion' => date("Y-m-d H:i:s")
			];

			/************ Actualizar empresa ****************************************************************/
			$resultado = $this->actualizar_usuario_modelo($datos_finales);

			if($resultado){
				/*-*-*-*-*-* Log de actualización exitosa *-*-*-*-*-*/
				$this->guardar_log('usuario_actualizado', [
					'datos_antes' => [
						'usuario_id' => $datos_usuario['UsuarioId'],
						'datos_anteriores' => $usuario_actual
					],
					'datos_despues' => [
						'resultado' => 'usuario_actualizada',
						'datos_nuevos' => $datos_finales
					],
				], 'medio', 'exito', 'App_usuario');

				/*-*-*-*-*-* Eliminar token usado *-*-*-*-*-*/
				$this->eliminar_token_csrf('editarUsuario');

				$this->normalizar_tiempo_respuesta();
				return json_encode([
					"Titulo" => "Usuario actualizado",
					"Texto" => "Los datos del usuario se han actualizado exitosamente",
					"Tipo" => "success"
				], JSON_UNESCAPED_UNICODE);

			} else {
				$this->normalizar_tiempo_respuesta();
				return json_encode([
					"Titulo" => "Error",
					"Texto" => "No se pudo actualizar el usuario. Intenta nuevamente",
					"Tipo" => "error"
				], JSON_UNESCAPED_UNICODE);
			}

		} catch (Exception $e) {
			$this->guardar_log('error_actualizar_usuario', [
				'datos_antes' => ['usuario_id' => $datos_usuario['EmpresaId']],
				'datos_despues' => ['error' => $e->getMessage()]
			], 'alto', 'error', 'App_usuario');

			$this->normalizar_tiempo_respuesta();
			return json_encode([
				"Titulo" => "Error interno",
				"Texto" => "Ocurrió un error al actualizar el usuario",
				"Tipo" => "error"
			], JSON_UNESCAPED_UNICODE);
		}
	}
	
	//===========================================================================================================
    // CAMBIAR ESTADO DE USUARIO
    // Función para cambiar el estado de una usuario (Activo/Inactivo/Suspendido)
    //===========================================================================================================
	
	public function cambiar_estado_usuario_controlador(){
		/*-------------------//-------- VALIDACIONES DE SEGURIDAD --------//-----------------------*/	
	/************ Marcar inicio del tiempo para normalizar respuestas *******************************************/
		$this->tiempo_inicio = microtime(true);

	/************ VALIDACION 1: Método POST *****************************************************************/
		if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
			$this->guardar_log('metodo_http_invalido_cambiar_estado_usuario', [
				'datos_antes' => ['metodo_recibido' => $_SERVER['REQUEST_METHOD']],
				'datos_despues' => ['accion' => 'rechazado']
			], 'alto', 'bloqueado', 'App_usuario');

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
		if(!$this->verificar_permisos('cambiar_estado_usuarios')){
			$this->guardar_log('sin_permisos_cambiar_estado_usuarios', [
				'datos_antes' => ['usuario' => $_SESSION['CodigoUsuario']],
				'datos_despues' => ['accion' => 'bloqueado']
			], 'alto', 'bloqueado', 'App_usuario');

			$this->normalizar_tiempo_respuesta();
			return json_encode([
				"Titulo" => "Sin permisos",
				"Texto" => "No tienes permisos para cambiar estado de usuario", 
				"Tipo" => "warning"
			], JSON_UNESCAPED_UNICODE);
		}
	/************ VALIDACION 4: Límite de intentos **********************************************************/
		if(!$this->verificar_intentos('cambiar_estado_usuarios', 3, 300)){
			$this->normalizar_tiempo_respuesta();
			return json_encode([
				"Titulo" => "Demasiados intentos",
				"Texto" => "Has superado el límite de intentos. Espera 5 minutos",
				"Tipo" => "warning"
			], JSON_UNESCAPED_UNICODE);
		}
	
	/************ SEPARAR código y token usando el separador encriptado *************************************/	
		$codigo_encriptado = $_POST['usuario_id'] ?? '';

		// Intentar separar primero
		$data_separada = $this->separar_codigo_con_token($codigo_encriptado,'separador_usuario');

		if (!$data_separada) {
			return json_encode([
				"Titulo" => "Código inválido",
				"Texto" => "El código de usuario no es válido o está corrupto",
				"Tipo" => "warning"
			], JSON_UNESCAPED_UNICODE);
		}

		$codigo_real = $data_separada['codigo'];
		$token_encriptado = $data_separada['token_encriptado'];
	
	/************ VALIDACION 5: VALIDAR token CSRF del listado ***********************************************/
		if (!$this->validar_csrf($token_encriptado, 'listUsuarios')) {
			$this->guardar_log('csrf_token_invalido_lista_cambio_estado', [
				'datos_antes' => ['codigo_usuario' => $codigo_real],
				'datos_despues' => ['accion' => 'bloqueado']
			], 'alto', 'bloqueado', 'App_usuario');

			$this->normalizar_tiempo_respuesta();
			return json_encode([
				"Titulo" => "Token inválido",
				"Texto" => "Token de seguridad inválido o expirado",
				"Tipo" => "warning"
			], JSON_UNESCAPED_UNICODE);
		}	
		
	/************ VALIDACION 6: Validar token CSRF del cambio de estado ****************************************/
		if(!isset($_POST['csrf_token_estado']) || !$this->validar_csrf($_POST['csrf_token_estado'], 'cambioEstadoUsuario')){
			$this->guardar_log('csrf_token_invalido_cambiar_estado', [
				'datos_antes' => ['usuario_id' => $codigo_real ?? 'no_definido'],
				'datos_despues' => ['accion' => 'bloqueado']
			], 'alto', 'bloqueado', 'App_usuario');

			$this->normalizar_tiempo_respuesta();
			return json_encode([
				"Titulo" => "Token inválido",
				"Texto" => "Token de seguridad inválido o expirado",
				"Tipo" => "warning"
			], JSON_UNESCAPED_UNICODE);
		}
	
		
		/*-------------------//-------- LIMPIEZA Y VALIDACIÓN DE DATOS --------//---------------------------*/

		$resultados_limpieza = [
			'usuario_id' => $this->limpiar_datos($codigo_real, 'numero', 'usuario_id'),
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
                    	'accion_tomada' => 'formulario_rechazado_completamente',
						'Formulario'=>'Cambio de estado usuarios'
                	],
            	],
				$nivel_riesgo_maximo, 'rechazado', 'seguridad'); 

            /*-*-*-*-*-* Normalizamos el tiempo de respuesta *-*-*-*-*-*/           
            $this->normalizar_tiempo_respuesta();
			/*-*-*-*-*-* Termino función para registro de usuario *-*-*-*-*-*/
            return json_encode([
                "Titulo" => "Datos no válidos",
                "Texto" => "Los datos enviados contienen información no permitida.",
                "Tipo" => "warning"
            ], JSON_UNESCAPED_UNICODE);
        }
		
		

		// Extraer datos limpios
		$usuario_id = $resultados_limpieza['usuario_id']['dato_limpio'];
		$nuevo_estado = $resultados_limpieza['nuevo_estado']['dato_limpio'];
		$motivo_cambio = $resultados_limpieza['motivo_cambio']['dato_limpio'];
		
		
		

		/*-------------------//-------- VALIDACIONES DE NEGOCIO --------//---------------------------*/

		// Validar que el estado sea válido
		$estados_permitidos = ['Activo', 'Inactivo', 'Bloqueado'];
		if (!in_array($nuevo_estado, $estados_permitidos)) {
			/*-*-*-*-*-* registro logs de seguimiento y auditoria *-*-*-*-*-*/
			$this->guardar_log('Estado_usuario_no_permitido', [
				'datos_antes' => [
						'estado_actual'=> $usuario_actual['EmpresaEstado']
					],
				'datos_despues' => [
					'accion' => 'bloqueado',
					'estado_recibido'=> $nuevo_estado,
					'usuario'=>$_SESSION['UsuarioId'] ?? 'anonimo'
				]
			], 'medio', 'bloqueado', 'App_usuario');
			
			
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
			$this->guardar_log('Estado_usuario_longitud', [
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
			], 'medio', 'bloqueado', 'App_usuario');
			
			$this->normalizar_tiempo_respuesta();
			return json_encode([
				"Titulo" => "Motivo muy corto",
				"Texto" => "El motivo debe tener al menos 10 caracteres",
				"Tipo" => "warning"
			], JSON_UNESCAPED_UNICODE);
		}

		/*-------------------//-------- PROCESAR CAMBIO DE ESTADO --------//---------------------------*/

		try {
			// Obtener datos actuales del usuario
			$usuario_actual = $this->obtener_usuario_completo_modelo($usuario_id);
			if (!$usuario_actual) {
				$this->normalizar_tiempo_respuesta();
				return json_encode([
					"Titulo" => "Usuario no encontrado",
					"Texto" => "el Usuario no existe o ya fue eliminado",
					"Tipo" => "warning"
				], JSON_UNESCAPED_UNICODE);
			}

			// Verificar que el estado realmente va a cambiar
			if ($usuario_actual['UsuarioEstado'] === $nuevo_estado) {
				$this->normalizar_tiempo_respuesta();
				return json_encode([
					"Titulo" => "Sin cambios",
					"Texto" => "El usuario ya tiene el estado: " . $nuevo_estado,
					"Tipo" => "info"
				], JSON_UNESCAPED_UNICODE);
			}

			// Ejecutar cambio de estado
			$resultado = $this->cambiar_estado_usuario_modelo($usuario_id, $nuevo_estado, $motivo_cambio);

			if ($resultado) {
				// Log del cambio exitoso
				$this->guardar_log('estado_usuario_cambiado', [
					'datos_antes' => [
						'usuario_id' => $usuario_id,
						'codigo_usuario' => $usuario_actual['UsuarioCodigo'],
						'nombre_usuario' => $usuario_actual['UsuarioNombres'].' '.$usuario_actual['UsuarioApellidos'],
						'estado_anterior' => $usuario_actual['UsuarioEstado']
					],
					'datos_despues' => [
						'estado_nuevo' => $nuevo_estado,
						'motivo' => $motivo_cambio,
						'usuario_cambio' => $_SESSION['UsuarioId'] ?? 'anonimo'
					],
				], 'medio', 'exito', 'App_usuario');

				$this->normalizar_tiempo_respuesta();
				return json_encode([
					"Titulo" => "Estado actualizado",
					"Texto" => "El estado del usuario se cambió exitosamente a: " . $nuevo_estado,
					"Tipo" => "success"
				], JSON_UNESCAPED_UNICODE);

			} else {
				$this->normalizar_tiempo_respuesta();
				return json_encode([
					"Titulo" => "Error",
					"Texto" => "No se pudo cambiar el estado del usuario",
					"Tipo" => "error"
				], JSON_UNESCAPED_UNICODE);
			}

		} catch (Exception $e) {

			$this->guardar_log('error_cambiar_estado_usuario', [
				'datos_antes' => ['usuario_id' => $usuario_id, 'nuevo_estado' => $nuevo_estado],
				'datos_despues' => ['error' => $e->getMessage()]
			], 'alto', 'error', 'App_usuario');

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