<?php
// pagina para verificar seguridad de mi aplicacion web
// https://kiggu.io/
// XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX//

 	//===========================================================================================================
    // MODELO BASE SEGURO
    // Este archivo contiene todas las funciones de seguridad necesarias
	// los otros modelos heredán de esta clase
    //===========================================================================================================
if($peticionAjax){
    require_once "../core/configAPP.php";
}else{
    require_once "./core/configAPP.php";
}

class SecureModel {
    //===========================================================================================================
    // CONSTRUCTOR DEL DESARROLLADOR
    // se insertan todos los encabezados de seguridad
    //===========================================================================================================
    protected function inicializar_seguridad() {
        /****************************** Iniciar sesión si no está iniciada *************************************/
        if (session_status() === PHP_SESSION_NONE) {
            session_start(['name' => SESION]);
        }
        /************************* solo cuando no es ajax ******************************************************/
        if (!$peticionAjax) {
            header("Content-Type: text/html; charset=UTF-8");
        }
        // Headers de seguridad obligatorios
        if (!headers_sent()) {
            /************************* CSP SOLO para POST (formularios AJAX) ***********************************/
           /* if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                header("Content-Security-Policy: default-src 'self'; script-src 'self' 'unsafe-inline'; style-src 'self' 'unsafe-inline'; img-src 'self' data:; connect-src 'self'");
                // Verificar que esta línea esté en tu método
                header("Content-Security-Policy: default-src 'self'; script-src 'self' 'unsafe-inline'; style-src 'self' 'unsafe-inline'; img-src 'self' data:;");
            }*/
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                header("Content-Security-Policy: default-src 'self'; script-src 'self' 'unsafe-inline'; style-src 'self' 'unsafe-inline'; img-src 'self' data:; connect-src 'self'");
            } else {
                header("Content-Security-Policy: default-src 'self'; script-src 'self' 'unsafe-inline'; style-src 'self' 'unsafe-inline'; img-src 'self' data:;");
            }
            /************************** Evita que tu sitio se abra en frames (clickjacking) ********************/
            header("X-Frame-Options: DENY");
            /************************** Evita que el navegador "adivine" el tipo de archivo ********************/
            header("X-Content-Type-Options: nosniff");
            /************************** Fuerza HTTPS en navegadores ********************************************/
            header("Strict-Transport-Security: max-age=31536000; includeSubDomains");
            
            header("Referrer-Policy: strict-origin-when-cross-origin");
            /************************** Oculta informaciÃ³n del servidor ***************************************/
            header_remove("X-Powered-By");
            header_remove("Server");
        }


        /****************************** Verificar que la conexión sea HTTPS (en producción) ********************/
        $this->validar_conexion_segura();
    }
    
    //===========================================================================================================
    // VALIDAR TIPO DE CONEXION
    // Función para verificar si la conexion es HTTP/HTTPS segura/insegura
    //===========================================================================================================
    private function validar_conexion_segura() {   
        /************************ Solo verificar HTTPS si NO estamos en desarrollo local ***********************/
        if (!isset($_SERVER['HTTPS']) || $_SERVER['HTTPS'] !== 'on') {
            /************************* Permitir HTTP solo en desarrollo (localhost) ****************************/
            /************************* si la conexion no es HTTPS entonces elimino la sesion *******************/
            if ($_SERVER['HTTP_HOST'] !== 'localhost' && 
                $_SERVER['HTTP_HOST'] !== '127.0.0.1' && 
                strpos($_SERVER['HTTP_HOST'], '192.168.') !== 0) {

                /*************registro logs de conexion no segura y cierro la secion****************************/
                $this->guardar_log(
                    'conexion_insegura_detectada', 
                    [
                        'datos_antes' => [
                            'protocolo' => 'HTTP',
                            'host' => $_SERVER['HTTP_HOST']
                        ],
                        'datos_despues' => [
                            'accion' => 'bloqueado'
                        ]
                    ], 
                    'critico', 'bloqueado', 'seguridad'
                );

                http_response_code(426); // Upgrade Required
                die(json_encode(["error" => "HTTPS required for security"]));
            }
        }else{
            /*************registro logs de conexion  segura ****************************************************/
             $this->guardar_log(
                'verificacion_https', 
                 [
                    'datos_antes' => [
                        'https_isset' => isset($_SERVER['HTTPS']),
                        'https_value' => $_SERVER['HTTPS'] ?? 'no_definido',
                        'host' => $_SERVER['HTTP_HOST']
                    ],
                    'datos_despues' => [
                        'resultado' => 'conexion_verificada'
                    ],
                ], 
                'bajo', 'info', 'seguridad');
        }
    }
    
    //===========================================================================================================
    // Servicios de geolocalizacion gratuitos
    //===========================================================================================================
    private $servicios_geo_gratuitos = ['ip-api.com', 'ipinfo.io', 'ipapi.co'];

    //===========================================================================================================
    // PASO 1: CONEXION SEGURA A LA BASE DE DATOS
    // Esta es la conexion mas segura posible que investigue
    //===========================================================================================================
    
    protected function conectar(){
        try {
            $enlace = new PDO(SGBD, USER, PASS, [
                // Estas opciones hacen la conexión más segura
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,  // Muestra errores si algo falla
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC, // Devuelve arrays asociativos
                PDO::ATTR_EMULATE_PREPARES => false, //  MUY IMPORTANTE: Evita inyección SQL
                PDO::ATTR_STRINGIFY_FETCHES => false, // Mantiene tipos de datos correctos
                PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8mb4" // Soporte para emojis
            ]);
            return $enlace;
        } catch(PDOException $e) {
            // No mostrar el error al usuario, solo guardarlo en logs
            error_log("Error de conexión: " . $e->getMessage());
            die("Error de conexión a la base de datos");
        }
    }

    //===========================================================================================================
    // PASO 2: LIMPIEZA DE DATOS AVANZADA
    // Esta funcion limpia CUALQUIER dato que recibas del usuario
    //===========================================================================================================
    
    protected function limpiar_datos($dato, $tipo = 'texto', $nombre_campo = 'campo_desconocido') {
        $dato_completamente_original = $dato; // Guardar el dato original SIN NINGÚN cambio
        $ataques_detectados = []; // Array para acumular todos los ataques
        $nivel_riesgo = 'bajo'; // Nivel inicial

        // Primero, limpieza básica
        $dato = trim($dato); // Quita espacios al inicio y final
        $dato_paso_anterior = $dato;
        $dato = stripslashes($dato); // Quita barras invertidas

        // Verificar barras invertidas maliciosas
        if($dato_paso_anterior !== $dato) {
            $ataques_detectados['barras_invertidas'] = 'eliminadas';
            $nivel_riesgo = 'medio';
        }

        switch($tipo) {
            case 'email':
                $dato_paso_anterior = $dato;
                $dato = filter_var($dato, FILTER_SANITIZE_EMAIL);
                if($dato_paso_anterior !== $dato) {
                    $ataques_detectados['email_malformado'] = 'sanitizado';
                }
                break;

            case 'numero':
                $dato_paso_anterior = $dato;
                $dato = filter_var($dato, FILTER_SANITIZE_NUMBER_INT);
                if($dato_paso_anterior !== $dato) {
                    $ataques_detectados['caracteres_no_numericos'] = 'eliminados';
                    $nivel_riesgo = 'medio';
                }
                $dato = (int)$dato;
                break;
				
			case 'password':
				// ====================================================================
				// LIMPIEZA ESPECIAL PARA CONTRASEÑAS
				// Mantiene caracteres especiales requeridos: @#$%&*
				// ====================================================================

				// Paso 1: Detectar y neutralizar XSS sin eliminar caracteres de contraseña
				$dato_paso_anterior = $dato;
				// Solo detectamos scripts maliciosos específicos, no todos los caracteres
				if(preg_match('/<script|javascript:|on\w+\s*=|<iframe|<object|<embed/i', $dato)){
					$ataques_detectados['xss_script_detectado'] = 'contenido_rechazado';
					$nivel_riesgo = 'alto';

					// Log del ataque crítico
					$this->guardar_log('ataque_critico_password_xss', [
						'datos_antes'=>[
							'campo_afectado' => $nombre_campo,
							'tipo_campo' => $tipo,
							'dato_original_hash' => hash('sha256', $dato_completamente_original),
							'dato_original_length' => strlen($dato_completamente_original),
						],
						'datos_despues'=> [
							'ataques_detectados' => $ataques_detectados,
							'razon_rechazo' => 'xss_detectado_en_password',
							'timestamp_rechazo' => microtime(true)
						],
					], $nivel_riesgo, 'rechazado', 'seguridad');

					// Retorno inmediato para ataque crítico
					return [
						'dato_limpio' => '',
						'ataques_detectados' => $ataques_detectados,
						'es_seguro' => false,
						'nivel_riesgo' => $nivel_riesgo,
						'campo_afectado' => $nombre_campo
					];
				}

				// Paso 2: SQL injection (más específico para contraseñas)
				$dato_paso_anterior = $dato;
				// Solo detectamos comandos SQL específicos, no eliminamos caracteres válidos
				if(preg_match('/\b(union\s+select|drop\s+table|create\s+table|alter\s+table|exec\s+|execute\s+)/i', $dato)) {
					$ataques_detectados['sql_injection_detectado'] = 'contenido_rechazado';
					$nivel_riesgo = 'alto';

					// Log del ataque crítico
					$this->guardar_log('ataque_critico_password_sql', [
						'datos_antes'=>[
							'campo_afectado' => $nombre_campo,
							'tipo_campo' => $tipo,
							'dato_original_hash' => hash('sha256', $dato_completamente_original),
							'dato_original_length' => strlen($dato_completamente_original),
						],
						'datos_despues'=> [
							'ataques_detectados' => $ataques_detectados,
							'razon_rechazo' => 'sql_injection_detectado_en_password',
							'timestamp_rechazo' => microtime(true)
						],
					], $nivel_riesgo, 'rechazado', 'seguridad');

					// Retorno inmediato para ataque crítico
					return [
						'dato_limpio' => '',
						'ataques_detectados' => $ataques_detectados,
						'es_seguro' => false,
						'nivel_riesgo' => $nivel_riesgo,
						'campo_afectado' => $nombre_campo
					];
				}

				// Paso 3: Eliminar emojis (estos sí no son necesarios en contraseñas)
				$dato_paso_anterior = $dato;
				$dato = preg_replace('/[\x{1F600}-\x{1F64F}]/u', '', $dato); // Emociones
				$dato = preg_replace('/[\x{1F300}-\x{1F5FF}]/u', '', $dato); // Símbolos diversos
				$dato = preg_replace('/[\x{1F680}-\x{1F6FF}]/u', '', $dato); // Transporte
				$dato = preg_replace('/[\x{1F1E0}-\x{1F1FF}]/u', '', $dato); // Banderas
				$dato = preg_replace('/[\x{2600}-\x{26FF}]/u', '', $dato);   // Símbolos diversos
				$dato = preg_replace('/[\x{2700}-\x{27BF}]/u', '', $dato);   // Dingbats
				if($dato_paso_anterior !== $dato) {
					$ataques_detectados['emojis_simbolos'] = 'eliminados';
					$nivel_riesgo = ($nivel_riesgo === 'alto') ? 'alto' : 'medio';
				}

				// Paso 4: ASCII no estándar (mantener algunos caracteres especiales)
				$dato_paso_anterior = $dato;
				$dato = preg_replace('/[^\x{0020}-\x{007E}\x{00A0}-\x{00FF}\x{0100}-\x{017F}]/u', '', $dato);
				if($dato_paso_anterior !== $dato) {
					$ataques_detectados['caracteres_no_ascii'] = 'eliminados';
				}

				// Paso 5: CARACTERES SEGUROS PARA CONTRASEÑAS (LA DIFERENCIA CLAVE)
				// Permitimos: letras, números, espacios, y símbolos específicos para contraseñas
				$dato_paso_anterior = $dato;
				// INCLUIMOS @$%&* explícitamente para contraseñas seguras (agregamos @$%&* a tu lista original)
				$dato = preg_replace('/[^a-zA-Z0-9\s\.\,\-_#@$%&*áéíóúÁÉÍÓÚñÑüÜ¿¡\?\!\(\)\[\]:;]/u', '', $dato);
				if($dato_paso_anterior !== $dato) {
					$ataques_detectados['caracteres_no_seguros'] = 'eliminados';
				}

				// Paso 6: Solo eliminar caracteres HTML maliciosos específicos
				// NO eliminamos & porque es válido para contraseñas
				$dato_paso_anterior = $dato;
				$dato = preg_replace('/[<>"\']/', '', $dato); // Eliminamos < > " ' pero mantenemos &
				if($dato_paso_anterior !== $dato) {
					$ataques_detectados['html_malicioso'] = 'eliminado';
					$nivel_riesgo = 'alto';
				}

				// Validación adicional específica para contraseñas
				if(strlen($dato) < 8) {
					$ataques_detectados['longitud_insuficiente'] = 'contraseña_muy_corta';
					$nivel_riesgo = 'medio';
				}

				break;
				
            case 'texto':
            default:
                // Paso 1: htmlspecialchars  Para texto normal
                $dato_paso_anterior = $dato;
                $dato = htmlspecialchars($dato, ENT_QUOTES, 'UTF-8');
                if($dato_paso_anterior !== $dato) {
                    $ataques_detectados['html_xss'] = 'neutralizado';
                    $nivel_riesgo = 'alto';
                }

                // Paso 2: SQL injection
                $dato_paso_anterior = $dato;
                $dato = preg_replace('/\b(union|select|insert|update|delete|drop|create|alter|exec|execute|script|javascript|on\w+)\b/i', '', $dato);
                if($dato_paso_anterior !== $dato) {
                    $ataques_detectados['sql_injection'] = 'comandos_eliminados';
                    $nivel_riesgo = 'alto';
                }

                // Paso 3: Emojis
                $dato_paso_anterior = $dato;
                $dato = preg_replace('/[\x{1F600}-\x{1F64F}]/u', '', $dato); // Emociones
                $dato = preg_replace('/[\x{1F300}-\x{1F5FF}]/u', '', $dato); // Símbolos diversos
                $dato = preg_replace('/[\x{1F680}-\x{1F6FF}]/u', '', $dato); // Transporte
                $dato = preg_replace('/[\x{1F1E0}-\x{1F1FF}]/u', '', $dato); // Banderas
                $dato = preg_replace('/[\x{2600}-\x{26FF}]/u', '', $dato);   // Símbolos diversos
                $dato = preg_replace('/[\x{2700}-\x{27BF}]/u', '', $dato);   // Dingbats
                if($dato_paso_anterior !== $dato) {
                    $ataques_detectados['emojis_simbolos'] = 'eliminados';
                    $nivel_riesgo = ($nivel_riesgo === 'alto') ? 'alto' : 'medio';
                }

                // Paso 4: ASCII no estÃ¡ndar
                $dato_paso_anterior = $dato;
                $dato = preg_replace('/[^\x{0020}-\x{007E}\x{00A0}-\x{00FF}\x{0100}-\x{017F}]/u', '', $dato);
                if($dato_paso_anterior !== $dato) {
                    $ataques_detectados['caracteres_no_ascii'] = 'eliminados';
                }

                // Paso 5: Solo caracteres seguros
                $dato_paso_anterior = $dato;
                $dato = preg_replace('/[^a-zA-Z0-9\s\.\,\-_#áéíóúÁÉÍÓÚñÑüÜ¿¡\?\!\(\)\[\]:;]/u', '', $dato);
                if($dato_paso_anterior !== $dato) {
                    $ataques_detectados['caracteres_no_seguros'] = 'eliminados';
                }

                // Paso 6: CÃ³digo malicioso restante
                $dato_paso_anterior = $dato;
                $dato = preg_replace('/[<>&"\']/', '', $dato);
                if($dato_paso_anterior !== $dato) {
                    $ataques_detectados['codigo_malicioso_restante'] = 'eliminado';
                    $nivel_riesgo = 'alto';
                }

                break;
        }

        // Solo hacer log si hubo cambios (ataques detectados)
        if($dato_completamente_original !== $dato && !empty($ataques_detectados)) {
            
            $this->guardar_log('ataque_detectado_y_neutralizado', [
                'datos_antes'=>[
                        'campo_afectado' => $nombre_campo,
                        'tipo_campo' => $tipo,
                        'dato_original_hash' => hash('sha256', $dato_completamente_original), // âœ… Solo hash
                        'dato_original_length' => strlen($dato_completamente_original), // âœ… Solo longitud
                    ],
                'datos_despues'=> [
                        'dato_final' => $dato, // âœ… Ya estÃ¡ limpio
                        'ataques_detectados' => $ataques_detectados, // âœ… Solo tipos detectados
                        'diferencia_bytes' => strlen($dato_completamente_original) - strlen($dato),
                        'timestamp_limpieza' => microtime(true),
                        'patron_ataque' => $this->identificar_patron_ataque($ataques_detectados), // âœ… PatrÃ³n general
                        'severidad_calculada' => $this->calcular_severidad($ataques_detectados) // âœ… MÃ©trica de riesgo
                ],

            ], $nivel_riesgo, 'neutralizado', 'seguridad');
        }
        
         // Al final, retornar array con datos Y estado de seguridad
        return [
            'dato_limpio' => $dato,
            'ataques_detectados' => $ataques_detectados,
            'es_seguro' => empty($ataques_detectados),
            'nivel_riesgo' => $nivel_riesgo,
            'campo_afectado' => $nombre_campo
        ];

        //return $dato;
    }
	//==================== Funcion auxiliar para limpiar datos ======================
    private function identificar_patron_ataque($ataques_detectados) {
        $patrones = [];

        if (isset($ataques_detectados['html_xss'])) $patrones[] = 'XSS';
        if (isset($ataques_detectados['sql_injection'])) $patrones[] = 'SQLi';
        if (isset($ataques_detectados['codigo_malicioso_restante'])) $patrones[] = 'Code_Injection';
        if (isset($ataques_detectados['emojis_simbolos'])) $patrones[] = 'Unicode_Abuse';

        return !empty($patrones) ? implode('|', $patrones) : 'Unknown';
    }
	//==================== Funcion auxiliar para limpiar datos ==================================================
    private function calcular_severidad($ataques_detectados) {
        $score = 0;

        if (isset($ataques_detectados['sql_injection'])) $score += 10;
        if (isset($ataques_detectados['html_xss'])) $score += 8;
        if (isset($ataques_detectados['codigo_malicioso_restante'])) $score += 6;
        if (isset($ataques_detectados['emojis_simbolos'])) $score += 2;

        if ($score >= 10) return 'critica';
        if ($score >= 6) return 'alta';
        if ($score >= 3) return 'media';
        return 'baja';
    }
	
	//===========================================================================================================
    // PASO 3: VALIDACION DE DATOS
    // Esta funciÃ³n verifica que los datos sean correctos ANTES de usarlos
    //===========================================================================================================
    
	//=========== GENERAR REGLAS DE VALIDACION AUTOMATICAS DESDE LA BASE DE DATOS ===============================
	//=========== Esta funcion + validar_datos() es todo lo que se necesita para validar completo ===============
	protected function generar_reglas_desde_bd($nombre_tabla, $campos_excluir = []) {
        try {
            $sql = "SELECT COLUMN_NAME, DATA_TYPE, CHARACTER_MAXIMUM_LENGTH, IS_NULLABLE, EXTRA
                    FROM INFORMATION_SCHEMA.COLUMNS 
                    WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = ?
                    ORDER BY ORDINAL_POSITION";
            
            $stmt = $this->ejecutar_consulta_segura($sql, [$nombre_tabla]);
            $estructura = $stmt->fetchAll();
            
            $reglas = [];
            foreach($estructura as $campo) {
                $nombre_campo = $campo['COLUMN_NAME'];
                
                // Saltar campos excluidos
                if(in_array($nombre_campo, $campos_excluir)) continue;
                
                $reglas_campo = [];
                
                // Es requerido si no permite NULL y no es auto-increment
                if($campo['IS_NULLABLE'] == 'NO' && $campo['EXTRA'] != 'auto_increment') {
                    $reglas_campo['requerido'] = true;
                }
                
                // Longitud máxima
                if($campo['CHARACTER_MAXIMUM_LENGTH']) {
                    $reglas_campo['max_caracteres'] = (int)$campo['CHARACTER_MAXIMUM_LENGTH'];
                }
                
                // Tipo específico
                if(in_array($campo['DATA_TYPE'], ['int', 'bigint', 'smallint'])) {
                    $reglas_campo['solo_numeros'] = true;
                }
                
                // Email
                if(strpos($nombre_campo, 'email') !== false) {
                    $reglas_campo['email'] = true;
                }
                
                $reglas[$nombre_campo] = $reglas_campo;
            }
            
            return $reglas;
            
        } catch(Exception $e) {
            error_log("Error generando reglas: " . $e->getMessage());
            return [];
        }
    }
	
    protected function validar_datos($datos, $reglas) {
        $errores = [];
        
        foreach($reglas as $campo => $validaciones) {
            $valor = isset($datos[$campo]) ? $datos[$campo] : '';
            
            foreach($validaciones as $regla => $parametro) {
                switch($regla) {
                    case 'requerido':
                        if($parametro && empty($valor)) {
                            $errores[$campo][] = "El campo $campo es obligatorio";
                        }
                        break;
                        
                    case 'email':
                        if(!empty($valor) && !filter_var($valor, FILTER_VALIDATE_EMAIL)) {
                            $errores[$campo][] = "El email no es vÃ¡lido";
                        }
                        break;
                        
                    case 'min_caracteres':
                        if(!empty($valor) && strlen($valor) < $parametro) {
                            $errores[$campo][] = "MÃ­nimo $parametro caracteres";
                        }
                        break;
                        
                    case 'max_caracteres':
                        if(!empty($valor) && strlen($valor) > $parametro) {
                            $errores[$campo][] = "MÃ¡ximo $parametro caracteres";
                        }
                        break;
                        
                    case 'solo_numeros':
                        if(!empty($valor) && !is_numeric($valor)) {
                            $errores[$campo][] = "Solo se permiten nÃºmeros";
                        }
                        break;
                        
                    case 'solo_letras':
                        if(!empty($valor) && !preg_match('/^[a-zA-ZÃ¡Ã©Ã­Ã³ÃºÃÃ‰ÃÃ“ÃšÃ±Ã‘\s]+$/', $valor)) {
                            $errores[$campo][] = "Solo se permiten letras";
                        }
                        break;
                }
            }
        }
        
        return $errores;
    }
	//==================== Funcion para validar datos completos =================================================
	protected function validar_completo($datos, $nombre_tabla, $campos_excluir = [], $reglas_extra = []) {
        // Reglas automÃ¡ticas de la BD
        $reglas_auto = $this->generar_reglas_desde_bd($nombre_tabla, $campos_excluir);
        
        // Combinar con reglas extra
        $reglas_finales = array_merge_recursive($reglas_auto, $reglas_extra);
        
        // Validar
        return $this->validar_datos($datos, $reglas_finales);
    }

	//===========================================================================================================
    // PASO 4: PROTECCIÓN CSRF
    // Esta funcion evita que otros sitios web hagan acciones a mi nombre
    //===========================================================================================================
	
	// ========================== genera un token para ser utilizado en formulario en el from ===================
    protected function generar_token_csrf($key) {
		$token_key = 'csrf_'.$key;
        $_SESSION[$token_key] =  $this->encryption(bin2hex(random_bytes(32)));
        
		// Log para auditoria
		$this->guardar_log('csrf_token_generado', [
			'datos_antes'=>['sin datos'],
			'datos_despues'=> [
				'token_hash' => hash('sha256', $_SESSION[$token_key]), 'formulario' => $token_key,
        		'timestamp' => date('Y-m-d H:i:s')
			],
		], 'bajo', 'exito', 'seguridad');
		
        return $_SESSION[$token_key];
		
    }
	// ========================== verifica el token generado en el formular =====================================
    protected function validar_csrf($token,$key) {
		$token_key = 'csrf_'.$key;
        return isset($_SESSION[$token_key]) && hash_equals($_SESSION[$token_key], $token);
		
    }
	// ========================== elimina el token generado despues de ser utilizado el formular ================
	protected function eliminar_token_csrf($key) {
		$token_key = 'csrf_' . $key;
		$token_eliminado = $_SESSION[$token_key];
		if (isset($_SESSION[$token_key])) {
			unset($_SESSION[$token_key]);
		}
		
		// Log para auditorÃ­a
		$this->guardar_log('csrf_token_eliminado', [
			'datos_antes'=>['token_eliminado'=>$token_key, 'token'=>$token_eliminado],
			'datos_despues'=> ['resultado'=>$token_key.' queda limpio'],
		], 'bajo', 'exito', 'seguridad');
	}
   
	//===========================================================================================================
    // PASO 5: CONTROL DE INTENTOS (Rate Limiting)
    // Evita que alguien haga demasiadas acciones muy rÃ¡pido
    //===========================================================================================================
    
    protected function verificar_intentos($accion, $limite = 5, $tiempo_espera = 300) {
        $clave = 'intentos_' . $accion . '_' . $this->obtener_ip();
        
        if(!isset($_SESSION[$clave])) {
            $_SESSION[$clave] = ['cantidad' => 0, 'primer_intento' => time()];
						
			$this->guardar_log(
				$accion, [
					'datos_antes'=>['numero_intentos'=>$accion],
					'datos_despues'=> ['cantidad' => $_SESSION[$clave]],
				], 
				'bajo', 'exito', $accion);
        }
        
        $datos = $_SESSION[$clave];
        
        // Si ha pasado el tiempo, resetear contador
        if(time() - $datos['primer_intento'] > $tiempo_espera) {
            $_SESSION[$clave] = ['cantidad' => 1, 'primer_intento' => time()];
            return true;
        }
        
        // Si ya supera el limite
        if($datos['cantidad'] >= $limite) {
			
			$this->guardar_log(
				$accion, [
					'datos_antes'=>['numero_intentos'=>$accion],
					'datos_despues'=> ['cantidad' => $limite],
				], 
				'critico', 'bloqueado', $accion);
			
            return false;
        }
        
        // Incrementar contador
        $_SESSION[$clave]['cantidad']++;
        return true;
    }

	//===========================================================================================================
    // PASO 6: REGISTRO DE ACTIVIDADES (Logs)
    // Guarda un registro de todo lo que pasa en tu sistema
    //===========================================================================================================
   
    protected function guardar_log($accion, $detalles = [], $nivel_riesgo = 'bajo', $resultado = 'exito', $modulo = null) {
        try {
            // Obtener datos antes y despuÃ©s si estÃ¡n en detalles
            $datos_antes = isset($detalles['datos_antes']) ? json_encode($detalles['datos_antes']) : null;
            $datos_despues = isset($detalles['datos_despues']) ? json_encode($detalles['datos_despues']) : null;
            
            $ip = $this->obtener_ip();
            $geolocalizacion = $this->obtener_geolocalizacion($ip);
            
            $log_data = [
                'Log_usuario_id' => isset($_SESSION['UsuarioId']) ? $_SESSION['UsuarioId'] : null,
                'Log_usuario'=> $_SESSION['UsuarioUsuario'],
                'Log_accion' => $accion,
                'Log_modulo' => $modulo ?: $this->obtener_modulo_actual(),
                'Log_ip_address' => $ip,
                'Log_geolocalizacion' => $geolocalizacion,
                'Log_user_agent' => isset($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : 'desconocido',
                'Log_datos_antes' => $datos_antes,
                'Log_datos_despues' => $datos_despues,
                'Log_resultado' => $resultado,
                'Log_nivel_riesgo' => $nivel_riesgo,
                'Log_timestamp' => date("Y-m-d H:i:s")
            ];
            
            $sql = "INSERT INTO App_sistema_logseguridad (
                        Log_usuario_id, Log_usuario, Log_accion, Log_modulo, Log_ip_address, 
                        Log_geolocalizacion, Log_user_agent, Log_datos_antes, Log_datos_despues, 
                        Log_resultado, Log_nivel_riesgo, Log_timestamp
                    ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
                    
            $stmt = $this->conectar()->prepare($sql);
            $stmt->execute(array_values($log_data));
            
        } catch(Exception $e) {
            error_log("Error guardando log de seguridad: " . $e->getMessage());
        }
    }
	//======== Funcion auxiliar del logs para capturar el nombre del script que esta enviando informacion =======
	private function obtener_modulo_actual() {
		$script = basename($_SERVER['SCRIPT_NAME'], '.php');
		return $script;
	}
    //======== Funcion auxiliar del logs para obtener la ip publica de donde acceden ============================
	private function obtener_ip() {
        if(!empty($_SERVER['HTTP_CLIENT_IP'])) {
            return $_SERVER['HTTP_CLIENT_IP'];
        } elseif(!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            return $_SERVER['HTTP_X_FORWARDED_FOR'];
        } else {
            return $_SERVER['REMOTE_ADDR'] ?? 'desconocida';
        }
    }
	//======== Funcion auxiliar del logs para obtener la geolocalizacion de donde visitan la pagina==============	
    // Obtener geolocalizaciÃ³n con multiples servicios y cache mejorado
    private function obtener_geolocalizacion($ip) {
        $cache_key = 'geo_' . md5($ip);
        
        // Buscar en cache primero (dura 24 horas)
        if (isset($_SESSION[$cache_key])) {
            $cache_data = $_SESSION[$cache_key];
            // Verificar si el cache no ha expirado (24 horas)
            if (time() - $cache_data['timestamp'] < 86400) {
                return $cache_data['data'];
            }
        }
        
        // Si es IP local, no consultar servicios externos
        if($ip == 'desconocida' || 
           filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE) === false) {
            $geo_data = json_encode([
                'pais' => 'Local',
                'region' => 'Local',
                'ciudad' => 'Local',
                'isp' => 'Red Local',
                'servicio_usado' => 'local',
                'precision' => 'red_local',
                'tipo_conexion' => 'local',
                'confiabilidad' => 'muy_alta'
            ], JSON_UNESCAPED_UNICODE);
            
            // Guardar en cache
            $_SESSION[$cache_key] = [
                'data' => $geo_data,
                'timestamp' => time()
            ];
            
            return $geo_data;
        }
        
        // Intentar con múlltiples servicios gratuitos
        $geo_final = null;
        
        // Servicio 1: ip-api.com (tu favorito) con anÃ¡lisis avanzado
        $geo_final = $this->consultar_ip_api_avanzado($ip);
        
        // Si falla, intentar con ipinfo.io
        if (!$geo_final) {
            $geo_final = $this->consultar_ipinfo($ip);
        }
        
        // Si falla, intentar con ipapi.co
        if (!$geo_final) {
            $geo_final = $this->consultar_ipapi_co($ip);
        }
        
        // Si todos fallan, usar datos por defecto
        if (!$geo_final) {
            $geo_final = [
                'pais' => 'No disponible',
                'region' => 'No disponible',
                'ciudad' => 'No disponible',
                'isp' => 'No disponible',
                'servicio_usado' => 'fallback',
                'precision' => 'no_disponible',
                'tipo_conexion' => 'desconocido',
                'confiabilidad' => 'muy_baja'
            ];
        }
        
        $geo_json = json_encode($geo_final, JSON_UNESCAPED_UNICODE);
        
        // Guardar en cache por 24 horas
        $_SESSION[$cache_key] = [
            'data' => $geo_json,
            'timestamp' => time()
        ];
        
        return $geo_json;
    }
    // SERVICIO 1: ip-api.com con analisis avanzado de precision
    private function consultar_ip_api_avanzado($ip) {
        try {
            $url = "https://ip-api.com/json/{$ip}?lang=es&fields=status,country,regionName,city,isp,timezone,query,lat,lon,org,as";
            $context = stream_context_create([
                'https' => [
                    'timeout' => 5,
                    'method' => 'GET',
                    'user_agent' => 'Sistema-Logs/1.0'
                ]
            ]);
            
            $response = file_get_contents($url, false, $context);
            
            if($response !== false) {
                $data = json_decode($response, true);
                
                if($data && $data['status'] == 'success') {
                    // CALCULO AVANZADO DE PRECISIÃ“N
                    $precision_geografica = $this->determinar_precision_ip_api($data);
                    $precision_isp = $this->determinar_precision_por_isp($data['isp'] ?? '');
                    $confiabilidad = $this->calcular_confiabilidad($data);
                    
                    return [
                        'pais' => $data['country'] ?? 'Desconocido',
                        'region' => $data['regionName'] ?? 'Desconocido',
                        'ciudad' => $data['city'] ?? 'Desconocido',
                        'isp' => $data['isp'] ?? 'Desconocido',
                        'timezone' => $data['timezone'] ?? 'Desconocido',
                        'latitud' => $data['lat'] ?? null,
                        'longitud' => $data['lon'] ?? null,
                        'organizacion' => $data['org'] ?? $data['as'] ?? 'Desconocido',
                        'servicio_usado' => 'ip-api.com',
                        'precision' => $precision_geografica,
                        'tipo_conexion' => $precision_isp,
                        'confiabilidad' => $confiabilidad
                    ];
                }
            }
        } catch (Exception $e) {
            error_log("Error ip-api.com avanzado: " . $e->getMessage());
        }
        
        return null;
    }
    //SERVICIO 1: Determinar precisiÃ³n basada en datos disponibles
    private function determinar_precision_ip_api($data) {
        // Si tenemos coordenadas especificas
        if (isset($data['lat']) && isset($data['lon']) && $data['lat'] != 0 && $data['lon'] != 0) {
            return 'coordenadas_disponibles';
        }
        
        // Si tenemos ciudad especifica
        if (isset($data['city']) && !empty($data['city']) && $data['city'] !== 'Unknown') {
            return 'ciudad_identificada';
        }
        
        // Si solo tenemos region
        if (isset($data['regionName']) && !empty($data['regionName'])) {
            return 'region_aproximada';
        }
        
        // Si solo tenemos pais
        if (isset($data['country']) && !empty($data['country'])) {
            return 'pais_solamente';
        }
        
        return 'datos_limitados';
    }
    // SERVICIO 1: Determinar precisiÃ³n por tipo de ISP
    private function determinar_precision_por_isp($isp) {
        $isp_lower = strtolower($isp);
        
        // ISPs que suelen dar ubicacion mas precisa
        $isps_precisos = ['google', 'cloudflare', 'amazon', 'microsoft'];
        foreach ($isps_precisos as $isp_preciso) {
            if (strpos($isp_lower, $isp_preciso) !== false) {
                return 'datacenter_preciso';
            }
        }
        
        // ISPs residenciales colombianos
        $isps_residenciales = ['claro', 'movistar', 'tigo', 'une', 'etb', 'colombia mÃ³vil'];
        foreach ($isps_residenciales as $isp_residencial) {
            if (strpos($isp_lower, $isp_residencial) !== false) {
                return 'residencial_colombia';
            }
        }
        
        // ISPs corporativos
        if (strpos($isp_lower, 'corporate') !== false || 
            strpos($isp_lower, 'enterprise') !== false ||
            strpos($isp_lower, 'business') !== false) {
            return 'corporativo';
        }
        
        return 'isp_general';
    }
    // SERVICIO 1: Calcular confiabilidad del resultado
    private function calcular_confiabilidad($data) {
        $score = 0;
        
        // +30 puntos si tiene coordenadas
        if (isset($data['lat']) && isset($data['lon']) && $data['lat'] != 0) {
            $score += 30;
        }
        
        // +25 puntos si tiene ciudad especÃ­fica
        if (isset($data['city']) && !empty($data['city']) && $data['city'] !== 'Unknown') {
            $score += 25;
        }
        
        // +20 puntos si tiene ISP identificado
        if (isset($data['isp']) && !empty($data['isp'])) {
            $score += 20;
        }
        
        // +15 puntos si tiene timezone
        if (isset($data['timezone']) && !empty($data['timezone'])) {
            $score += 15;
        }
        
        // +10 puntos si tiene regiÃ³n
        if (isset($data['regionName']) && !empty($data['regionName'])) {
            $score += 10;
        }
        
        // Convertir a porcentaje y clasificar
        if ($score >= 80) return 'muy_alta';
        if ($score >= 60) return 'alta';
        if ($score >= 40) return 'media';
        if ($score >= 20) return 'baja';
        return 'muy_baja';
    }
    // SERVICIO 2: ipinfo.io (50,000/mes gratis)
    private function consultar_ipinfo($ip) {
        try {
            $url = "https://ipinfo.io/{$ip}/json";
            $context = stream_context_create([
                'https' => [
                    'timeout' => 5,
                    'method' => 'GET',
                    'user_agent' => 'Sistema-Logs/1.0'
                ]
            ]);
            
            $response = file_get_contents($url, false, $context);
            
            if($response !== false) {
                $data = json_decode($response, true);
                
                if($data && !isset($data['error'])) {
                    return [
                        'pais' => $data['country'] ?? 'Desconocido',
                        'region' => $data['region'] ?? 'Desconocido',
                        'ciudad' => $data['city'] ?? 'Desconocido',
						'postal' => $data['postal'] ?? 'Desconocido',
                        'isp' => $data['org'] ?? 'Desconocido',
						'compania'=>$data['company'] ?? 'Desconocido',
						'coordenas'=>$data['loc'] ?? 'Desconocida',
                        'timezone' => $data['timezone'] ?? 'Desconocido',
						'asn' => $data['asn'] ?? 'Desconocido',
                        'servicio_usado' => 'ipinfo.io',
                        'precision' => 'ciudad_aproximada',
                        'tipo_conexion' => 'fallback_service',
                        'confiabilidad' => 'media'
                    ];
                }
            }
        } catch (Exception $e) {
            error_log("Error ipinfo.io: " . $e->getMessage());
        }
        
        return null;
    }
    // SERVICIO 3: ipapi.co (30,000/mes gratis)
    private function consultar_ipapi_co($ip) {
        try {
            $url = "https://ipapi.co/{$ip}/json/";
            $context = stream_context_create([
                'https' => [
                    'timeout' => 5,
                    'method' => 'GET',
                    'user_agent' => 'Sistema-Logs/1.0'
                ]
            ]);
            
            $response = file_get_contents($url, false, $context);
            
            if($response !== false) {
                $data = json_decode($response, true);
                
                if($data && !isset($data['error'])) {
                    return [
                        'pais' => $data['country_name'] ?? 'Desconocido',
                        'region' => $data['region'] ?? 'Desconocido',
                        'ciudad' => $data['city'] ?? 'Desconocido',
						'postal' => $data['postal'] ?? 'Desconocido',
                        'isp' => $data['org'] ?? 'Desconocido',
						'latitud'=>$data['latitude'] ?? null,
						'longitud'=>$data['longitude'] ?? null,
                        'timezone' => $data['timezone'] ?? 'Desconocido',
						'asn' => $data['asn'] ?? 'Desconocido',
                        'servicio_usado' => 'ipapi.co',
                        'precision' => 'ciudad_aproximada',
                        'tipo_conexion' => 'fallback_service',
                        'confiabilidad' => 'media'
                    ];
                }
            }
        } catch (Exception $e) {
            error_log("Error ipapi.co: " . $e->getMessage());
        }
        
        return null;
    }

	//===========================================================================================================
    // RESULTADO EXTRA DE LOS (logs)
    // Funcion para visualizar las geolocalizaciones guardadas en el sistema
    //===========================================================================================================
	
    // FUNCIÃ“N: Para mostrar geolocalizaciÃ³n legible con precisiÃ³n
    public function mostrar_geolocalizacion($geo_json) {
        $geo = json_decode($geo_json, true);
        
        if (!$geo) {
            return 'InformaciÃ³n no disponible';
        }
        
        $resultado = "{$geo['ciudad']}, {$geo['region']}, {$geo['pais']}";
        
        if (isset($geo['isp']) && $geo['isp'] !== 'Desconocido') {
            $resultado .= " ({$geo['isp']})";
        }
        
        if (isset($geo['servicio_usado'])) {
            $resultado .= " [via {$geo['servicio_usado']}]";
        }
        
        // Agregar informaciÃ³n de precisiÃ³n
        if (isset($geo['precision']) && isset($geo['confiabilidad'])) {
            $precision_texto = $this->traducir_precision($geo['precision']);
            $confiabilidad_texto = $this->traducir_confiabilidad($geo['confiabilidad']);
            $resultado .= " - {$precision_texto} ({$confiabilidad_texto})";
        }
        
        return $resultado;
    }
    //FUNCIÃ“N: Traducir precisiÃ³n a texto legible
    private function traducir_precision($precision) {
        $traducciones = [
            'coordenadas_disponibles' => 'Coordenadas exactas',
            'ciudad_identificada' => 'Ciudad confirmada',
            'region_aproximada' => 'Solo regiÃ³n',
            'pais_solamente' => 'Solo paÃ­s',
            'residencial_colombia' => 'ISP residencial CO',
            'datacenter_preciso' => 'Datacenter',
            'corporativo' => 'Red corporativa',
            'ciudad_aproximada' => 'Ciudad aprox.',
            'red_local' => 'Red local',
            'no_disponible' => 'No disponible'
        ];
        
        return $traducciones[$precision] ?? $precision;
    }
    // NUEVA FUNCIÃ“N: Traducir confiabilidad a texto legible
    private function traducir_confiabilidad($confiabilidad) {
        $traducciones = [
            'muy_alta' => 'Muy confiable',
            'alta' => 'Confiable',
            'media' => 'Parcial',
            'baja' => 'Limitada',
            'muy_baja' => 'Incierta'
        ];
        
        return $traducciones[$confiabilidad] ?? $confiabilidad;
    }
    // FUNCIÃ“N AUXILIAR: Para consultas de logs con geolocalizaciÃ³n
    public function obtener_logs_con_geo($filtros = []) {
        $sql = "SELECT *, Log_geolocalizacion FROM App_sistema_logseguridad ORDER BY Log_timestamp DESC";
        $stmt = $this->conectar()->prepare($sql);
        $stmt->execute();
        
        $logs = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        // Procesar geolocalizaciÃ³n para mostrar
        foreach ($logs as &$log) {
            $log['geolocalizacion_legible'] = $this->mostrar_geolocalizacion($log['Log_geolocalizacion']);
        }
        
        return $logs;
    }
    // FUNCIÃ“N MEJORADA: EstadÍsticas de servicios usados
    public function estadisticas_geolocalizacion() {
        return [
            'servicios_configurados' => count($this->servicios_geo_gratuitos),
            'cache_activo' => isset($_SESSION) ? 'SÃ­' : 'No',
            'costo_total' => '$0.00 USD (Siempre gratis)',
            'precision_mejorada' => 'AnÃ¡lisis avanzado de precisiÃ³n activado',
            'servicios_disponibles' => 'ip-api.com (avanzado), ipinfo.io, ipapi.co',
            'nuevas_metricas' => 'PrecisiÃ³n, tipo conexiÃ³n, confiabilidad'
        ];
    }
    
	//===========================================================================================================
    // PASO 7: EJECUCIÃ“N SEGURA DE CONSULTAS
    // Esta es  muy importante - realiza consultas de manera segura a la base de datos
    //===========================================================================================================    
    
    protected function ejecutar_consulta_segura($sql, $parametros = []) {
        try {
            // Verificar que no haya SQL directo (sin parÃ¡metros)
            if(empty($parametros) && preg_match('/\$|\'|"/', $sql)) {
				// Log para auditorÃ­a
				$this->guardar_log('ejecutar_consulta_segura', [
					'datos_antes'=>['sql_detectado' => substr($sql, 0, 100), // Solo primeros 100 caracteres por seguridad
						'ip_origen' => $this->obtener_ip()
								   ],
					'datos_despues'=> [],
				], 'alto', 'bloqueado', 'seguridad');
				
                throw new Exception("Consulta potencialmente insegura detectada");
            }
            
            $stmt = $this->conectar()->prepare($sql);
            $resultado = $stmt->execute($parametros);
            
            // Determinar nivel de riesgo segÃºn tipo de operaciÃ³n
			$tipo_operacion = $this->determinar_tipo_sql($sql);
			$nivel_riesgo = $this->calcular_nivel_riesgo($tipo_operacion);
            
			// Log para auditorÃ­a
			$this->guardar_log('ejecutar_consulta_segura', [
				'datos_antes'=>['tipo_operacion' => $tipo_operacion,
					'parametros_count' => count($parametros),
					'tabla_afectada' => $this->extraer_tabla_principal($sql)
							   ],
				'datos_despues'=> [$sql],
			], $nivel_riesgo, 'exito', 'seguridad');
			
            return $stmt;
            
        } catch(PDOException $e) {
			
			// Log para auditorÃ­a
			$this->guardar_log('ejecutar_consulta_segura', [
				'datos_antes'=>['tipo_operacion' => $this->determinar_tipo_sql($sql),
				'error_code' => $e->getCode(),
				'parametros_count' => count($parametros)
							   ],
				'datos_despues'=> [],
			], $nivel_riesgo, 'fallo', 'seguridad');
			
			
            error_log("Error en consulta: " . $e->getMessage());
            throw new Exception("Error en la consulta a la base de datos");
        }
    }
	// ============= Funcion auxiliar de consulta segura para clasificar las consultas ==========================
	private function determinar_tipo_sql($sql) {
		$sql_upper = strtoupper(trim($sql));
		if (strpos($sql_upper, 'SELECT') === 0) return 'SELECT';
		if (strpos($sql_upper, 'INSERT') === 0) return 'INSERT';
		if (strpos($sql_upper, 'UPDATE') === 0) return 'UPDATE';
		if (strpos($sql_upper, 'DELETE') === 0) return 'DELETE';
		if (strpos($sql_upper, 'CREATE') === 0) return 'CREATE';
		if (strpos($sql_upper, 'DROP') === 0) return 'DROP';
		if (strpos($sql_upper, 'ALTER') === 0) return 'ALTER';
		return 'UNKNOWN';
	}
	// ============= Funcion auxiliar de consulta segura para calcular nivel de riesgo ==========================
	private function calcular_nivel_riesgo($tipo_operacion) {
		switch($tipo_operacion) {
			case 'SELECT':
				return 'bajo';
			case 'INSERT':
			case 'UPDATE':
				return 'medio';
			case 'DELETE':
				return 'alto';
			case 'DROP':
			case 'ALTER':
			case 'CREATE':
				return 'critico';
			default:
				return 'medio';
		}
	}
	// ============= Funcion auxiliar de consulta segura para conocer la tabla que se va a interactuar ==========
	private function extraer_tabla_principal($sql) {
		// Extraer nombre de tabla principal de la consulta
		if (preg_match('/(?:FROM|INTO|UPDATE|TABLE)\s+`?([a-zA-Z_][a-zA-Z0-9_]*)`?/i', $sql, $matches)) {
			return $matches[1];
		}
		return 'desconocida';
	}
	
	//===========================================================================================================
    // PASO 8: NORMALIZAR LOS TIEMPOS DE RESPUESTA
    // Esta funcion hace que los tiempos de respuesta salgan todos iguales para evitar tiempos diferentes de respuesta
    //===========================================================================================================    
       
    private $tiempo_inicio;

    protected function normalizar_tiempo_respuesta() {
        // Tiempo base mÃ­nimo para todas las respuestas (100ms)
        $tiempo_minimo = 100;

        if (!isset($this->tiempo_inicio)) {
            return; // Si no hay tiempo de inicio, no hacer nada
        }

        // Calcular tiempo transcurrido en milisegundos
        $tiempo_transcurrido = (microtime(true) - $this->tiempo_inicio) * 1000;

        // Si la respuesta fue muy rÃ¡pida, agregar delay
        if ($tiempo_transcurrido < $tiempo_minimo) {
            $delay = ($tiempo_minimo - $tiempo_transcurrido) * 1000; // Convertir a microsegundos
            usleep((int)$delay);
        }
    }
    
	//===========================================================================================================
    // PASO 9: ENCRIPTAR Y DESENCRIPTAR INFORMACION (una sola via)
    // Funcion para encriptar datos sencibles y datos de inicion de sesion de usuario
    //===========================================================================================================
	
	public function encryption($string) {
		$key = hash('sha256', SECRET_KEY, true); // true = binario

		// IV aleatorio para cada encriptaciÃ³n (CRÃTICO)
		$iv = random_bytes(12); // GCM usa 12 bytes
		$tag = '';

		$encrypted = openssl_encrypt($string, METHOD, $key, OPENSSL_RAW_DATA, $iv, $tag);

		if ($encrypted === false) {
			throw new Exception('Error en encriptaciÃ³n');
		}

		// Combinar: IV + tag + datos encriptados
		$result = $iv . $tag . $encrypted;
		return base64_encode($result);
	}
	
	// ================= Funcion para desenciptar datos recibidos si es neceario ================================
	public function decryption($string) {
		$key = hash('sha256', SECRET_KEY, true);
		$data = base64_decode($string);

		// Validar longitud mÃ­nima
		if (strlen($data) < 28) { // 12 (IV) + 16 (tag) + mÃ­nimo datos
			return false;
		}

		// Extraer componentes
		$iv = substr($data, 0, 12);
		$tag = substr($data, 12, 16);
		$encrypted = substr($data, 28);

		$decrypted = openssl_decrypt($encrypted, METHOD, $key, OPENSSL_RAW_DATA, $iv, $tag);

		return $decrypted;
	}

	//===========================================================================================================
    // PASO 9.1: ENCRIPTAR Y DESENCRIPTAR INFORMACION (doble via)
    // Menos segura pero determinística
    //===========================================================================================================
	public function encryption_deterministico($string, $salt_key = 'default') {
		$key = hash('sha256', SECRET_KEY);
		$iv = substr(hash('sha256', SECRET_KEY . $salt_key), 0, 16);

		// Usar CBC en lugar de GCM para casos determinísticos
		$output = openssl_encrypt($string, 'AES-256-CBC', $key, 0, $iv);

		if ($output === false) {
			error_log("Error OpenSSL: " . openssl_error_string());
			return false;
		}

		return base64_encode($output);
	}

	public function decryption_deterministico($string, $salt_key = 'default') {
		$key = hash('sha256', SECRET_KEY);
		$iv = substr(hash('sha256', SECRET_KEY . $salt_key), 0, 16);

		$data = base64_decode($string);
		$decrypted = openssl_decrypt($data, 'AES-256-CBC', $key, 0, $iv);

		return $decrypted;
	}
		
	// ============================= INICIO RETIRAR CUANDO YA SE HAGA TODA LA MIGRACION =========================
	// MÃ‰TODO LEGACY: Para desencriptar datos antiguos (CBC)
	// Usar solo para migrar datos existentes
	public function decryptionLegacy($string) {
		$key = hash('sha256', SECRET_KEY); // Sin true para compatibilidad con mÃ©todo anterior
		$iv = substr(hash('sha256', '37269S7ND25S25ST25P98905Z'), 0, 16); // Tu IV anterior

		return openssl_decrypt(base64_decode($string), 'AES-256-CBC', $key, 0, $iv);
	}
	// Migrar datos del mÃ©todo antiguo al nuevo
	public function migrarDato($datosAntiguos) {
		try {
			// Intentar desencriptar con mÃ©todo nuevo primero
			$resultado = $this->decryption($datosAntiguos);
			if ($resultado !== false) {
				return $resultado; // Ya estÃ¡ en formato nuevo
			}

			// Si falla, intentar con mÃ©todo legacy
			$decrypted = $this->decryptionLegacy($datosAntiguos);
			if ($decrypted !== false) {
				// Reencriptar con mÃ©todo seguro
				return $this->encryption($decrypted);
			}

			return false;
		} catch (Exception $e) {
			error_log("Error migrando datos: " . $e->getMessage());
			return false;
		}
	}
	// ============================= FIN RETIRAR CUANDO YA SE HAGA TODA LA MIGRACION ============================
    
	//===========================================================================================================
    // PASO 10: COMPROBACION DE PERMISOS
    // Funcion para verificar en tiempo real si el usuario si puede realizar la accion que esta ejecutando
    //===========================================================================================================
	
    /*protected function verificar_permisos($accion, $recurso_id = null) {
        if(!isset($_SESSION['CodigoUsuario'])) {
            return false;
        }
        
		
		
        $usuario_id = $_SESSION['CodigoUsuario'];
        
        // Consulta segura para verificar permisos
        $sql = "SELECT COUNT(*) as tiene_permiso FROM App_usuarios_privilegios
				RIGHT JOIN App_usuarios_usuario ON App_usuarios_usuario.UsuarioCodigo = App_usuarios_privilegios.CuentaCodigo 
				WHERE App_usuarios_privilegios.CuentaCodigo = ? 
				AND App_usuarios_privilegios.PrivilegioPrivilegio = ? 
				AND App_usuarios_privilegios.PrivilegioValor = '1' 
				AND App_usuarios_usuario.UsuarioEstado = 'Activo'";
        
        $stmt = $this->ejecutar_consulta_segura($sql, [$usuario_id, $accion]);
        $resultado = $stmt->fetch();
        
        return $resultado['tiene_permiso'] > 0;
    }*/
	
	protected function verificar_permisos($accion, $recurso_id = null, $verificar_empresa = true) {
		if(!isset($_SESSION['CodigoUsuario'])) {
			return false;
		}

		$usuario_id = $_SESSION['UsuarioId'];
		$codigo_usuario = $_SESSION['CodigoUsuario'];

		// PASO 1: Verificar si es SYSTEM_ADMIN (proveedor - acceso total)
		if($this->es_system_admin($usuario_id)) {
			$this->guardar_log('acceso_system_admin', [
				'datos_antes' => ['accion_solicitada' => $accion, 'recurso_id' => $recurso_id],
				'datos_despues' => ['resultado' => 'acceso_total_concedido', 'tipo_usuario' => 'SYSTEM_ADMIN']
			], 'bajo', 'exito', 'permisos');

			return true; // SYSTEM_ADMIN tiene acceso a todo
		}

		// PASO 2: Verificar si es SUPER_ADMIN (administrador de empresa)
		if($this->es_super_admin($usuario_id)) {
			// Super admin puede hacer todo dentro de su empresa
			if(!$verificar_empresa || $this->verificar_recurso_misma_empresa($recurso_id, $_SESSION['UsuarioEmpresaId'])) {
				$this->guardar_log('acceso_super_admin', [
					'datos_antes' => ['accion_solicitada' => $accion, 'recurso_id' => $recurso_id],
					'datos_despues' => ['resultado' => 'acceso_empresa_concedido', 'tipo_usuario' => 'SUPER_ADMIN']
				], 'bajo', 'exito', 'permisos');

				return true;
			}
		}

		// PASO 3: Verificación normal de permisos por roles
		$tiene_permiso_rol = $this->verificar_permiso_por_roles($usuario_id, $accion);

		if(!$tiene_permiso_rol) {
			$this->guardar_log('permiso_denegado_rol', [
				'datos_antes' => ['accion_solicitada' => $accion, 'usuario_id' => $usuario_id],
				'datos_despues' => ['resultado' => 'sin_permiso_en_roles']
			], 'medio', 'bloqueado', 'permisos');

			return false;
		}

		// PASO 4: Verificar separación por empresa (si aplica)
		if($verificar_empresa && $recurso_id && !$this->verificar_recurso_misma_empresa($recurso_id, $_SESSION['UsuarioEmpresaId'])) {
			$this->guardar_log('permiso_denegado_empresa', [
				'datos_antes' => ['accion_solicitada' => $accion, 'recurso_id' => $recurso_id],
				'datos_despues' => ['resultado' => 'recurso_empresa_diferente', 'empresa_usuario' => $_SESSION['UsuarioEmpresaId']]
			], 'alto', 'bloqueado', 'permisos');

			return false;
		}

		// PASO 5: Log de acceso exitoso
		$this->guardar_log('permiso_concedido', [
			'datos_antes' => ['accion_solicitada' => $accion, 'usuario_id' => $usuario_id],
			'datos_despues' => ['resultado' => 'acceso_concedido', 'metodo' => 'rol_y_empresa']
		], 'bajo', 'exito', 'permisos');

		return true;
	}
	
	
	//===========================================================================================================
	// FUNCIONES AUXILIARES PARA VERIFICACIÓN DE PERMISOS
	//===========================================================================================================

	/**
	 * Verificar si el usuario es SYSTEM_ADMIN (proveedor)
	 */
	protected function es_system_admin($usuario_id) {
		try {
			$sql = "SELECT UsuarioIsSystemAdmin FROM App_usuarios_usuario 
					WHERE UsuarioId = ? AND UsuarioEstado = 'Activo'";
			$stmt = $this->ejecutar_consulta_segura($sql, [$usuario_id]);
			$resultado = $stmt->fetch();

			return $resultado && $resultado['UsuarioIsSystemAdmin'] == 1;

		} catch(Exception $e) {
			error_log("Error verificando system admin: " . $e->getMessage());
			return false;
		}
	}

	/**
	 * Verificar si el usuario es SUPER_ADMIN (administrador de empresa)
	 */
	protected function es_super_admin($usuario_id) {
		try {
			$sql = "SELECT UsuarioIsSuperAdmin FROM App_usuarios_usuario 
					WHERE UsuarioId = ? AND UsuarioEstado = 'Activo'";
			$stmt = $this->ejecutar_consulta_segura($sql, [$usuario_id]);
			$resultado = $stmt->fetch();

			return $resultado && $resultado['UsuarioIsSuperAdmin'] == 1;

		} catch(Exception $e) {
			error_log("Error verificando super admin: " . $e->getMessage());
			return false;
		}
	}

	/**
	 * Verificar permisos a través del sistema de roles
	 */
	protected function verificar_permiso_por_roles($usuario_id, $accion) {
		try {
			$sql = "SELECT COUNT(*) as tiene_permiso 
					FROM App_usuarios_usuario_rol ur
					INNER JOIN App_usuarios_rol_permiso rp ON ur.UsuarioRolIdRol = rp.RolPermisoIdRol
					INNER JOIN App_usuarios_permiso p ON rp.RolPermisoIdPermiso = p.PermisoId
					WHERE ur.UsuarioRolIdUsuario = ? 
					AND p.PermisoCodigo = ?
					AND ur.UsuarioRolEstado = 'Activo'
					AND rp.RolPermisoEstado = 'Activo'
					AND p.PermisoEstado = 'Activo'";

			$stmt = $this->ejecutar_consulta_segura($sql, [$usuario_id, $accion]);
			$resultado = $stmt->fetch();

			return $resultado['tiene_permiso'] > 0;

		} catch(Exception $e) {
			error_log("Error verificando permisos por roles: " . $e->getMessage());
			return false;
		}
	}

	/**
	 * Verificar que un recurso pertenece a la misma empresa del usuario
	 */
	protected function verificar_recurso_misma_empresa($recurso_id, $empresa_usuario) {
		if(!$recurso_id || !$empresa_usuario) {
			return true; // Si no hay restricción, permitir
		}

		try {
			// Para usuarios, verificar que pertenezcan a la misma empresa
			$sql = "SELECT UsuarioEmpresaId FROM App_usuarios_usuario WHERE UsuarioId = ?";
			$stmt = $this->ejecutar_consulta_segura($sql, [$recurso_id]);
			$resultado = $stmt->fetch();

			if($resultado) {
				return $resultado['UsuarioEmpresaId'] == $empresa_usuario;
			}

			return false;

		} catch(Exception $e) {
			error_log("Error verificando empresa del recurso: " . $e->getMessage());
			return false;
		}
	}

	/**
	 * Verificar jerarquía de usuarios (para evitar que usuarios gestionen superiores)
	 */
	protected function verificar_jerarquia_usuarios($usuario_gestor_id, $usuario_objetivo_id) {
		try {
			// Obtener información de ambos usuarios
			$sql = "SELECT UsuarioId, UsuarioIsSuperAdmin, UsuarioIsSystemAdmin, UsuarioEmpresaId 
					FROM App_usuarios_usuario 
					WHERE UsuarioId IN (?, ?) AND UsuarioEstado != 'Eliminado'";

			$stmt = $this->ejecutar_consulta_segura($sql, [$usuario_gestor_id, $usuario_objetivo_id]);
			$usuarios = $stmt->fetchAll();

			if(count($usuarios) != 2) {
				return false; // Uno de los usuarios no existe
			}

			$gestor = null;
			$objetivo = null;

			foreach($usuarios as $usuario) {
				if($usuario['UsuarioId'] == $usuario_gestor_id) {
					$gestor = $usuario;
				} else {
					$objetivo = $usuario;
				}
			}

			// SYSTEM_ADMIN puede gestionar a todos
			if($gestor['UsuarioIsSystemAdmin'] == 1) {
				return true;
			}

			// SUPER_ADMIN puede gestionar usuarios de su empresa (excepto otros SYSTEM_ADMIN)
			if($gestor['UsuarioIsSuperAdmin'] == 1) {
				// No puede gestionar SYSTEM_ADMIN
				if($objetivo['UsuarioIsSystemAdmin'] == 1) {
					return false;
				}

				// Debe ser de la misma empresa
				return $gestor['UsuarioEmpresaId'] == $objetivo['UsuarioEmpresaId'];
			}

			// Usuario normal no puede gestionar SUPER_ADMIN ni SYSTEM_ADMIN
			if($objetivo['UsuarioIsSuperAdmin'] == 1 || $objetivo['UsuarioIsSystemAdmin'] == 1) {
				return false;
			}

			// Debe ser de la misma empresa
			return $gestor['UsuarioEmpresaId'] == $objetivo['UsuarioEmpresaId'];

		} catch(Exception $e) {
			error_log("Error verificando jerarquía: " . $e->getMessage());
			return false;
		}
	}
	
	//======== funcion para verificar si el filtro se hace automatico por empresa a la que pertenece el usuario =============//
	protected function determinar_filtro_empresa() {
		$usuario_id = $_SESSION['UsuarioId'];

		// SYSTEM_ADMIN ve todos los usuarios
		if($this->es_system_admin($usuario_id)) {
			return null; // Sin filtro
		}

		// Los demás usuarios solo ven de su empresa
		return $_SESSION['UsuarioEmpresaId'] ?? null;
	}
	
    //==================== funciones para verificar el rol del usuario actual =============================================//	
	
	protected function determinar_rol_usuario(){
		try {
			if (!isset($_SESSION['UsuarioId'])) {
				return null;
			}

			$usuario_id = $_SESSION['UsuarioId'];

			// Consulta más directa desde usuarios hacia roles
			$sql = "SELECT MAX(rol.RolNivel) as RolNivel 
					FROM App_usuarios_usuario u
					INNER JOIN App_usuarios_usuario_rol ur ON u.UsuarioId = ur.UsuarioRolIdUsuario
					INNER JOIN App_usuarios_rol rol ON ur.UsuarioRolIdRol = rol.RolId
					WHERE u.UsuarioId = ?";

			$stmt = $this->ejecutar_consulta_segura($sql, [$usuario_id]);
			$resultado = $stmt->fetch();

			$rol_nivel = $resultado['RolNivel'] ?? null;
			$_SESSION['RolNivel'] = $rol_nivel;

			return $rol_nivel;

		} catch(Exception $e) {
			error_log("Error determinando rol de usuario: " . $e->getMessage());
			return null;
		}
	}
	
	//===========================================================================================================
    // PASO 11: GENERACION DE CODIGOS ALEATORIOS
    // funcion para generar codigo de identificacion secundadia en la base de datos para todo lo que se registre
	// que sea nuevo (empresa nueva, sucursal nueva, nuevo usuario, nuevo activo etc)
    //===========================================================================================================
   	
    protected function generar_codigo_aleatorio($prefijo, $longitud, $sufijo = ''){
        $numeros = '';
        for($i = 1; $i <= $longitud; $i++){
            $numeros .= rand(0, 9);
        }
        return $prefijo . $numeros . $sufijo;
    }
    
    //===========================================================================================================
    // PASO 12: IDENTIFICACION DE POSIBLE BOT ATACANTE DETECCIÃ“N AVANZADA DE BOTS Y COMPORTAMIENTO SOSPECHOSO
    // funcion para verificar que la velocidad de llenado sea de un humano promedio
    //===========================================================================================================

    protected function es_bot_sospechoso($umbral_minimo,$numero_campos) {
        $indicadores_bot = 0;
        $detalles = [];

        // 1. Velocidad entre POSTs
		$clave_tiempo = 'uniq_post_' . $this->obtener_ip() . '_' . ($_SESSION['token_total_sesion'] ?? 'notoken');
		
        if (isset($_SESSION[$clave_tiempo])) {
            $tiempo_desde_ultimo_post = microtime(true) - $_SESSION[$clave_tiempo];

            if ($tiempo_desde_ultimo_post < $umbral_minimo) {
                $indicadores_bot += 4;
                $detalles['posts_muy_rapidos'] = round($tiempo_desde_ultimo_post, 4);
                $posts_rapidos = round($tiempo_desde_ultimo_post, 4) ;
                 $this->guardar_log('post_registrado_sospechoso'.$_SESSION['secuenciabot'], [
                    'datos_antes' => ['ip' => $this->obtener_ip(), 'Secuencia_bot'=>$_SESSION['secuenciabot'], 'clave_tiempo'=>$clave_tiempo, 'tiempo_desde_ultimo_post'=>$posts_rapidos, 'umbral_minimo'=>$umbral_minimo, 'campos_recibidos'=>$numero_campos], 
                    'datos_despues' => ['timestamp_inicial' => date('Y-m-d H:i:s')]
                ], 'alto', 'bloqueado', 'seguridad');
            }else{
                 $this->guardar_log('post_registrado_normal'.$_SESSION['secuenciabot'], [
                    'datos_antes' => ['ip' => $this->obtener_ip(), 'Secuencia_bot'=>$_SESSION['secuenciabot'], 'clave_tiempo'=>$clave_tiempo, 'tiempo_transcurrido' => round($tiempo_desde_ultimo_post, 4) . 's'],
                    'datos_despues' => ['timestamp_inicial' => date('Y-m-d H:i:s')]
                ], 'bajo', 'exito', 'seguridad');
            }
            $_SESSION[$clave_tiempo] = microtime(true);			
			$_SESSION['secuenciabot'] += 1;
           
			
        } else {
			$_SESSION['secuenciabot'] = 1;
            $_SESSION[$clave_tiempo] = microtime(true);
            $this->guardar_log('primer_post_registrado', [
                'datos_antes' => ['ip' => $this->obtener_ip(), 'Secuencia_bot'=>$_SESSION['secuenciabot'], 'clave_tiempo'=>$clave_tiempo],
                'datos_despues' => ['timestamp_inicial' => date('Y-m-d H:i:s')]
            ], 'bajo', 'exito', 'seguridad');
        }

        // 2. ðŸ†• DETECCIÃ“N DE BURST SPAM
        if ($this->detectar_burst_spam('empresa_registrada')) {
            $indicadores_bot += 6;
            $detalles['burst_spam'] = 'multiples_envios_detectados';
        }

        // 3. User Agent sospechoso
        $user_agent = $_SERVER['HTTP_USER_AGENT'] ?? '';
        $bots_conocidos = ['curl', 'wget', 'python', 'bot', 'crawler', 'spider', 'scraper', 'automated'];
        foreach ($bots_conocidos as $bot) {
            if (stripos($user_agent, $bot) !== false) {
                $indicadores_bot += 2;
                $detalles['user_agent_bot'] = $bot;
                break;
            }
        }

        // 4. Headers faltantes
        $headers_esperados = ['HTTP_ACCEPT', 'HTTP_ACCEPT_LANGUAGE', 'HTTP_ACCEPT_ENCODING'];
        $headers_faltantes = [];
        foreach ($headers_esperados as $header) {
            if (!isset($_SERVER[$header])) {
                $indicadores_bot += 1;
                $headers_faltantes[] = $header;
            }
        }
        if (!empty($headers_faltantes)) {
            $detalles['headers_faltantes'] = $headers_faltantes;
        }

        // 5. ðŸ†• DATOS SIMILARES EN HISTORIAL
        if ($this->detectar_patron_similar_en_historial()) {
            $indicadores_bot += 3;
            $detalles['patron_datos_similares'] = true;
        }

        // 6. PatrÃ³n de datos automÃ¡ticos
        if ($this->detectar_patron_datos_automaticos($_POST)) {
            $indicadores_bot += 3;
            $detalles['patron_datos_automatico'] = true;
        }

        // 7. User Agent vacÃ­o o muy corto
        if (empty($user_agent) || strlen($user_agent) < 10) {
            $indicadores_bot += 2;
            $detalles['user_agent_sospechoso'] = 'vacio_o_muy_corto';
        }

        // 8. Referrer sospechoso o ausente en POST
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $referrer = $_SERVER['HTTP_REFERER'] ?? '';
            if (empty($referrer)) {
                $indicadores_bot += 1;
                $detalles['referrer_ausente'] = true;
            }
        }

        // EvaluaciÃ³n final - UMBRAL MÃS BAJO
        if ($indicadores_bot >= 4) { // CambiÃ© de 5 a 4
            $this->guardar_log('bot_detectado', [
                'datos_antes' => [
                    'puntuacion_bot' => $indicadores_bot,
                    'detalles' => $detalles,
                    'user_agent' => substr($user_agent, 0, 100)
                ],
                'datos_despues' => ['accion' => 'bloqueado']
            ], 'alto', 'bloqueado', 'seguridad');

            return [
                'es_bot' => true,
                'puntuacion' => $indicadores_bot,
                'detalles' => $detalles
            ];
        }

        if ($indicadores_bot > 0) {
            $this->guardar_log('analisis_bot_realizado', [
                'datos_antes' => ['puntuacion_bot' => $indicadores_bot],
                'datos_despues' => ['resultado' => 'usuario_normal', 'detalles' => $detalles]
            ], 'bajo', 'exito', 'seguridad');
        }

        return [
            'es_bot' => false,
            'puntuacion' => $indicadores_bot,
            'detalles' => $detalles
        ];
    }
    // ======================== Funcion auxiliar para detectar patrones automaticos =============================
    private function detectar_patron_datos_automaticos($datos) {
        if (!is_array($datos)) return false;

        foreach ($datos as $campo => $valor) {
            if (!is_string($valor)) continue;

            // Patrones tÃ­picos de bots/scripts
            if (preg_match('/^(test|prueba|admin|user|demo)\d*$/i', $valor)) {
                return true;
            }

            // Caracteres repetidos (aaaa, 1111, etc.)
            if (preg_match('/^(.)\1{4,}$/', $valor)) {
                return true;
            }

            // Secuencias obvias (123456, abcdef)
            if (preg_match('/^(123456|abcdef|qwerty|password)$/i', $valor)) {
                return true;
            }

            // Datos demasiado similares entre campos
            $valores_unicos = array_unique(array_values($datos));
            if (count($valores_unicos) < 3 && count($datos) > 4) {
                return true; // Muchos campos con valores iguales
            }
        }

        return false;
    }
    // ======================== Funcion auxiliar para detectar spam =============================================
    protected function detectar_burst_spam($accion = 'empresa_registrada') {
        $ip = $this->obtener_ip();
        $ventana_tiempo = 60; // 60 segundos
        $limite_burst = 2; // Maximo 2 envios en 60 segundos

        try {
            $sql = "SELECT COUNT(*) as envios_recientes 
                    FROM App_sistema_logseguridad 
                    WHERE Log_ip_address = ? 
                    AND Log_accion = ? 
                    AND Log_timestamp > DATE_SUB(NOW(), INTERVAL ? SECOND)
                    AND Log_resultado IN ('exito', 'bloqueado')";

            $stmt = $this->ejecutar_consulta_segura($sql, [$ip, $accion, $ventana_tiempo]);
            $envios_recientes = $stmt->fetch()['envios_recientes'];

            if ($envios_recientes >= $limite_burst) {
                $this->guardar_log('burst_spam_detectado', [
                    'datos_antes' => [
                        'envios_en_ventana' => $envios_recientes,
                        'limite_permitido' => $limite_burst,
                        'ventana_segundos' => $ventana_tiempo
                    ],
                    'datos_despues' => ['accion' => 'bloqueado_por_burst']
                ], 'critico', 'bloqueado', 'seguridad');

                return true;
            }

        } catch (Exception $e) {
            error_log("Error detectando burst: " . $e->getMessage());
        }

        return false;
    }
    // ======================== Funcion auxiliar para detectar patrones similares ===============================
    private function detectar_patron_similar_en_historial() {
        $ip = $this->obtener_ip();

        try {
            $sql = "SELECT Log_datos_despues 
                    FROM App_sistema_logseguridad 
                    WHERE Log_ip_address = ? 
                    AND Log_accion = 'empresa_registrada'
                    AND Log_timestamp > DATE_SUB(NOW(), INTERVAL 10 MINUTE)
                    ORDER BY Log_timestamp DESC 
                    LIMIT 5";

            $stmt = $this->ejecutar_consulta_segura($sql, [$ip]);
            $registros_previos = $stmt->fetchAll();

            if (count($registros_previos) >= 2) {
                $datos_actuales = $_POST;
                foreach ($registros_previos as $registro) {
                    $datos_previos = json_decode($registro['Log_datos_despues'], true);

                    if ($this->calcular_similitud($datos_actuales, $datos_previos) > 0.7) {
                        return true;
                    }
                }
            }

        } catch (Exception $e) {
            error_log("Error detectando patrones similares: " . $e->getMessage());
        }

        return false;
    }
    // ======================== Funcion auxiliar para calcular las similitudes ==================================
    private function calcular_similitud($datos1, $datos2) {
        if (!is_array($datos1) || !is_array($datos2)) return 0;

        $campos_comunes = array_intersect_key($datos1, $datos2);
        if (empty($campos_comunes)) return 0;

        $similitudes = 0;
        $total_campos = count($campos_comunes);

        foreach ($campos_comunes as $campo => $valor1) {
            $valor2 = $datos2[$campo] ?? '';

            $distancia = levenshtein($valor1, $valor2);
            $max_longitud = max(strlen($valor1), strlen($valor2));

            if ($max_longitud > 0) {
                $similitud_campo = 1 - ($distancia / $max_longitud);
                $similitudes += $similitud_campo;
            }
        }

        return $total_campos > 0 ? $similitudes / $total_campos : 0;
    }
    
	//===========================================================================================================
    // PASO 13: GENERAR TOKEN ÚNICO POR VISITA (LLAMAR AL CARGAR LA PÁGINA) este se llama desde la plantilla.php
    // funcion para generar token unico de ingreso a la pagina y asi desde una misma ip publica poder darle un
	// ID unico a cada pc para que una tacante especifico no bloque a los demas usuarios
    //===========================================================================================================

	protected function generar_token_visita() {
		// Solo generar si no existe
		if (!isset($_SESSION['token_total_sesion'])) {
			// Token simple: TS + 6 dígitos aleatorios
			$_SESSION['token_total_sesion'] = 'TS' . str_pad(rand(0, 999999), 6, '0', STR_PAD_LEFT);

			// Log del token generado
			$this->guardar_log('token_total_sesion', [
				'datos_antes' => ['ip' => $this->obtener_ip()],
				'datos_despues' => [
					'token' => $_SESSION['token_total_sesion'],
					'timestamp' => date('Y-m-d H:i:s')
				]
			], 'bajo', 'exito', 'seguridad');
		}
		return $_SESSION['token_total_sesion'];
	}
	
	
	//===========================================================================================================
	// PASO 14: VALIDACIÓN DE PARÁMETROS GET - AGREGAR AL FINAL DE SECUREMODEL.PHP
	// funcion para validar seguridad vie GET
	//===========================================================================================================

	protected function validar_seguridad_url() {
		// Si no hay parámetros GET, todo bien
		if (empty($_GET)) {
			return ['es_seguro' => true, 'razon' => 'sin_parametros'];
		}

		foreach ($_GET as $parametro => $valor) {
			// Limpiar cada parámetro GET como si fuera POST
			$resultado = $this->limpiar_datos($valor, 'texto', "GET_{$parametro}");

			if (!$resultado['es_seguro']) {
				$this->guardar_log('parametro_get_malicioso', [
					'datos_antes' => ['parametro' => $parametro, 'valor_original' => $valor],
					'datos_despues' => ['ataques_detectados' => $resultado['ataques_detectados']]
				], 'alto', 'bloqueado', 'seguridad');

				return [
					'es_seguro' => false,
					'razon' => 'parametro_malicioso',
					'parametro' => $parametro
				];
			}
		}

		return ['es_seguro' => true, 'razon' => 'parametros_validos'];
	}
	
	//===========================================================================================================
    // PASO 15: SISTEMA DE TOKENS ESPECÍFICOS POR ENTIDAD (GENÉRICO Y REUTILIZABLE)
    // Funciones para crear tokens únicos que solo sirven para una entidad específica
    // Usar para: empresas, usuarios, sucursales, sedes, productos, etc.
    //===========================================================================================================
    
	// ========================== Generar token específico para cualquier entidad =============================//
	/* 
	* @param string $tipo_entidad Tipo de entidad (empresa, usuario, sucursal, sede, etc.)
	* @param mixed $entidad_id ID de la entidad específica
	* @param int $tiempo_expiracion Tiempo de expiración en segundos (default: 1800 = 30 min)
	* @param array $datos_extra Datos adicionales para incluir en el token (opcional)
	* @return string Token generado
	*/
	protected function generar_token_entidad_especifico($tipo_entidad, $entidad_id, $tiempo_expiracion = 1800, $datos_extra = []) {
        // Validar parámetros de entrada
        if (empty($tipo_entidad) || empty($entidad_id)) {
            throw new InvalidArgumentException("Tipo de entidad e ID son obligatorios");
        }
        
        // MEJORADO: Usar token_total_sesion en lugar de IP para mayor precisión
        $session_token_actual = $_SESSION['token_total_sesion'] ?? $this->generar_token_visita();
        
        // Crear datos únicos para el token
        $datos_token = [
            'tipo_entidad' => strtolower($tipo_entidad),
            'entidad_id' => $entidad_id,
            'usuario_id' => $_SESSION['UsuarioId'] ?? 'anonimo',
            'timestamp' => time(),
            'session_token' => $session_token_actual, // CLAVE: Usar token de sesión específico
            'user_agent_hash' => hash('sha256', $_SERVER['HTTP_USER_AGENT'] ?? 'unknown'),
            'random' => bin2hex(random_bytes(16)), // 32 caracteres aleatorios
            'datos_extra' => $datos_extra
        ];
        
        // Crear hash único e irrepetible
        $token_raw = json_encode($datos_token, JSON_UNESCAPED_UNICODE);
        $token_hash = hash('sha256', $token_raw . SECRET_KEY . $tipo_entidad . $entidad_id);
        
        // Clave única para la sesión
        $clave_sesion = 'token_' . strtolower($tipo_entidad) . '_' . $entidad_id;
        
        // Guardar en sesión con expiración
        $_SESSION[$clave_sesion] = [
            'token' => $token_hash,
            'tipo_entidad' => strtolower($tipo_entidad),
            'entidad_id' => $entidad_id,
            'usuario_id' => $_SESSION['UsuarioId'] ?? 'anonimo',
            'timestamp_creacion' => time(),
            'expira_en' => time() + $tiempo_expiracion,
            'session_token_creacion' => $session_token_actual, // NUEVO: Guardar token de sesión
            'user_agent_hash' => hash('sha256', $_SERVER['HTTP_USER_AGENT'] ?? 'unknown'),
            'datos_extra' => $datos_extra
        ];
        
        // Log de generación mejorado
        $this->guardar_log('token_entidad_especifico_generado_v2', [
            'datos_antes' => [
                'tipo_entidad' => $tipo_entidad,
                'entidad_id' => $entidad_id,
                'usuario_generador' => $_SESSION['UsuarioId'] ?? 'anonimo',
                'expiracion_minutos' => round($tiempo_expiracion / 60, 1)
            ],
            'datos_despues' => [
                'token_hash' => substr($token_hash, 0, 8) . '...',
                'clave_sesion' => $clave_sesion,
                'session_token_hash' => substr(hash('sha256', $session_token_actual), 0, 8) . '...',
                'metodo_validacion' => 'session_token_en_lugar_de_ip',
                'datos_extra_incluidos' => !empty($datos_extra)
            ],
        ], 'bajo', 'exito', 'seguridad');
        
        return $token_hash;
    }
    
    // ========================== Validar token específico para cualquier entidad =============================//
	/* 
	* @param string $tipo_entidad Tipo de entidad (empresa, usuario, sucursal, etc.)
	* @param mixed $entidad_id ID de la entidad que se quiere verificar
	* @param string $token_recibido Token recibido del frontend
	* @param bool $eliminar_despues_validar Si eliminar el token después de validar (default: false)
	* @return bool True si el token es válido, false en caso contrario
	*/
	protected function validar_token_entidad_especifico($tipo_entidad, $entidad_id, $token_recibido, $eliminar_despues_validar = false) {
        $clave_sesion = 'token_' . strtolower($tipo_entidad) . '_' . $entidad_id;
        $usuario_actual = $_SESSION['UsuarioId'] ?? 'anonimo';
        $session_token_actual = $_SESSION['token_total_sesion'] ?? '';
        
        // 1. Verificar que existe el token en sesión
        if (!isset($_SESSION[$clave_sesion])) {
            $this->guardar_log('token_entidad_no_encontrado_v2', [
                'datos_antes' => [
                    'tipo_entidad' => $tipo_entidad,
                    'entidad_id' => $entidad_id,
                    'token_recibido_hash' => substr(hash('sha256', $token_recibido), 0, 8) . '...',
                    'clave_sesion_buscada' => $clave_sesion
                ],
                'datos_despues' => ['resultado' => 'token_no_existe_en_sesion'],
            ], 'medio', 'bloqueado', 'seguridad');
            
            return false;
        }
        
        $datos_sesion = $_SESSION[$clave_sesion];
        
        // 2. Verificar que no ha expirado
        if (time() > $datos_sesion['expira_en']) {
            unset($_SESSION[$clave_sesion]);
            
            $this->guardar_log('token_entidad_expirado_v2', [
                'datos_antes' => [
                    'tipo_entidad' => $tipo_entidad,
                    'entidad_id' => $entidad_id,
                    'tiempo_expiracion' => $datos_sesion['expira_en'],
                    'tiempo_actual' => time(),
                    'minutos_expirado' => round((time() - $datos_sesion['expira_en']) / 60, 1)
                ],
                'datos_despues' => ['resultado' => 'token_expirado_y_eliminado'],
            ], 'medio', 'bloqueado', 'seguridad');
            
            return false;
        }
        
        // 3. Verificar que el token coincide exactamente
        if (!hash_equals($datos_sesion['token'], $token_recibido)) {
            $this->guardar_log('token_entidad_no_coincide_v2', [
                'datos_antes' => [
                    'tipo_entidad' => $tipo_entidad,
                    'entidad_id' => $entidad_id,
                    'token_esperado_hash' => substr($datos_sesion['token'], 0, 8) . '...',
                    'token_recibido_hash' => substr(hash('sha256', $token_recibido), 0, 8) . '...'
                ],
                'datos_despues' => ['resultado' => 'intento_uso_token_invalido'],
            ], 'alto', 'bloqueado', 'seguridad');
            
            return false;
        }
        
        // 4. Verificar que es el mismo usuario que lo generó
        if ($datos_sesion['usuario_id'] !== $usuario_actual) {
            $this->guardar_log('token_entidad_usuario_diferente_v2', [
                'datos_antes' => [
                    'tipo_entidad' => $tipo_entidad,
                    'entidad_id' => $entidad_id,
                    'usuario_token' => $datos_sesion['usuario_id'],
                    'usuario_actual' => $usuario_actual
                ],
                'datos_despues' => ['resultado' => 'intento_uso_token_otro_usuario'],
            ], 'critico', 'bloqueado', 'seguridad');
            
            return false;
        }
        
        // 5. NUEVO: Verificar session_token en lugar de IP (MÁS PRECISO Y SEGURO)
        if (isset($datos_sesion['session_token_creacion']) && $datos_sesion['session_token_creacion'] !== $session_token_actual) {
            $this->guardar_log('token_entidad_session_diferente', [
                'datos_antes' => [
                    'tipo_entidad' => $tipo_entidad,
                    'entidad_id' => $entidad_id,
                    'session_token_creacion_hash' => substr(hash('sha256', $datos_sesion['session_token_creacion']), 0, 8) . '...',
                    'session_token_actual_hash' => substr(hash('sha256', $session_token_actual), 0, 8) . '...'
                ],
                'datos_despues' => [
                    'resultado' => 'posible_session_hijacking_o_nueva_ventana',
                    'metodo_validacion' => 'session_token_vs_ip'
                ],
            ], 'alto', 'bloqueado', 'seguridad');
            
            return false;
        }
        
        // 6. OPCIONAL: Verificar User Agent (detecta cambios de navegador)
        $user_agent_actual = hash('sha256', $_SERVER['HTTP_USER_AGENT'] ?? 'unknown');
        if (isset($datos_sesion['user_agent_hash']) && $datos_sesion['user_agent_hash'] !== $user_agent_actual) {
            $this->guardar_log('token_entidad_user_agent_diferente', [
                'datos_antes' => [
                    'tipo_entidad' => $tipo_entidad,
                    'entidad_id' => $entidad_id,
                    'user_agent_token' => substr($datos_sesion['user_agent_hash'], 0, 8) . '...',
                    'user_agent_actual' => substr($user_agent_actual, 0, 8) . '...'
                ],
                'datos_despues' => ['resultado' => 'posible_cambio_navegador'],
            ], 'medio', 'bloqueado', 'seguridad');
            
            return false;
        }
        
        // 7. Verificar que corresponde al tipo de entidad correcto
        if ($datos_sesion['tipo_entidad'] !== strtolower($tipo_entidad)) {
            $this->guardar_log('token_entidad_tipo_incorrecto_v2', [
                'datos_antes' => [
                    'tipo_esperado' => strtolower($tipo_entidad),
                    'tipo_en_token' => $datos_sesion['tipo_entidad'],
                    'entidad_id' => $entidad_id
                ],
                'datos_despues' => ['resultado' => 'intento_usar_token_tipo_incorrecto'],
            ], 'alto', 'bloqueado', 'seguridad');
            
            return false;
        }
        
        // 8. TODO VÁLIDO - Log de éxito
        $this->guardar_log('token_entidad_validado_exitosamente_v2', [
            'datos_antes' => [
                'tipo_entidad' => $tipo_entidad,
                'entidad_id' => $entidad_id,
                'usuario_id' => $usuario_actual,
                'tiempo_vida_minutos' => round((time() - $datos_sesion['timestamp_creacion']) / 60, 1)
            ],
            'datos_despues' => [
                'resultado' => 'token_valido',
                'metodo_validacion' => 'session_token_user_agent',
                'eliminar_despues' => $eliminar_despues_validar
            ],
        ], 'bajo', 'exito', 'seguridad');
        
        // 9. Eliminar token si se solicita (útil para tokens de un solo uso)
        if ($eliminar_despues_validar) {
            unset($_SESSION[$clave_sesion]);
        }
        
        return true;
    }
    
    // ==========================  Limpiar tokens expirados de la sesión (función de mantenimiento) Llamar periódicamente para mantener la sesión limpia //
    protected function limpiar_tokens_expirados() {
        $tokens_eliminados = 0;
        $tiempo_actual = time();
        
        foreach ($_SESSION as $clave => $valor) {
            // Solo procesar claves que son tokens de entidades
            if (strpos($clave, 'token_') === 0 && is_array($valor) && isset($valor['expira_en'])) {
                if ($tiempo_actual > $valor['expira_en']) {
                    unset($_SESSION[$clave]);
                    $tokens_eliminados++;
                }
            }
        }
        
        if ($tokens_eliminados > 0) {
            $this->guardar_log('tokens_expirados_limpiados', [
                'datos_antes' => ['tokens_a_revisar' => 'sesion_completa'],
                'datos_despues' => [
                    'tokens_eliminados' => $tokens_eliminados,
                    'tiempo_limpieza' => date('Y-m-d H:i:s')
                ],
            ], 'bajo', 'exito', 'seguridad');
        }
        
        return $tokens_eliminados;
    }
    
    // ========================== Obtener información de un token específico (para debugging) =================//
 
    protected function obtener_info_token_entidad($tipo_entidad, $entidad_id) {
        $clave_sesion = 'token_' . strtolower($tipo_entidad) . '_' . $entidad_id;
        
        if (!isset($_SESSION[$clave_sesion])) {
            return null;
        }
        
        $datos = $_SESSION[$clave_sesion];
        
        return [
            'existe' => true,
            'tipo_entidad' => $datos['tipo_entidad'],
            'entidad_id' => $datos['entidad_id'],
            'usuario_id' => $datos['usuario_id'],
            'timestamp_creacion' => $datos['timestamp_creacion'],
            'expira_en' => $datos['expira_en'],
            'minutos_restantes' => round(($datos['expira_en'] - time()) / 60, 1),
            'esta_expirado' => time() > $datos['expira_en'],
            'datos_extra' => $datos['datos_extra'] ?? []
        ];
    }
	
		
	//============================================= FUNCIONES VARIAS ============================================ 
	//=============================================== ADICIONALES =============================================== 
	
	//===========================================================================================================
    // FUNCIÓN PARA GENERAR BÚSQUEDA INTELIGENTE
    // Función reutilizable para construir condiciones de búsqueda inteligente
    //===========================================================================================================
    
    protected function generar_busqueda_inteligente($termino_busqueda, $campos_busqueda, &$parametros) {
        // Si no hay término, devolver condición neutral
        if (empty($termino_busqueda)) {
            return "1=1";
        }
        
        // Limpiar y preparar el término
        $termino_limpio = preg_replace('/\s+/', ' ', trim($termino_busqueda));
        
        // Dividir en palabras
        $palabras = explode(' ', $termino_limpio);
        
        // Filtrar palabras muy cortas (opcional)
        $palabras = array_filter($palabras, function($palabra) {
            return strlen($palabra) >= 2; // Solo palabras de 2+ caracteres
        });
        
        // Si no quedan palabras válidas, devolver condición neutral
        if (empty($palabras)) {
            return "1=1";
        }
        
        $condiciones_palabras = [];
        
        foreach ($palabras as $palabra) {
            $palabra_con_wildcards = "%" . $palabra . "%";
            
            // Crear condición OR para todos los campos
            $condiciones_campos = [];
            foreach ($campos_busqueda as $campo) {
                $condiciones_campos[] = "$campo LIKE ?";
                $parametros[] = $palabra_con_wildcards;
            }
            
            // Cada palabra debe encontrarse en al menos uno de los campos
            $condiciones_palabras[] = "(" . implode(" OR ", $condiciones_campos) . ")";
        }
        
        // Todas las palabras deben encontrarse (AND)
        return "(" . implode(" OR ", $condiciones_palabras) . ")";
    }
	
	//===========================================================================================================
    // OBTENER ESTRUCTURA COMPLETA DE UNA TABLA
    // Devuelve un array con toda la informaciÃ³n de los campos
    //===========================================================================================================
	
	protected function obtener_estructura_tabla($nombre_tabla) {
        try {
            $sql = "SELECT 
                        COLUMN_NAME,
                        DATA_TYPE,
                        CHARACTER_MAXIMUM_LENGTH,
                        NUMERIC_PRECISION,
                        NUMERIC_SCALE,
                        IS_NULLABLE,
                        COLUMN_DEFAULT,
                        COLUMN_TYPE,
                        COLUMN_KEY,
                        EXTRA
                    FROM INFORMATION_SCHEMA.COLUMNS 
                    WHERE TABLE_SCHEMA = DATABASE() 
                    AND TABLE_NAME = ?
                    ORDER BY ORDINAL_POSITION";
            
            $stmt = $this->ejecutar_consulta_segura($sql, [$nombre_tabla]);
            return $stmt->fetchAll();
            
        } catch(Exception $e) {
            error_log("Error obteniendo estructura: " . $e->getMessage());
            return [];
        }
    }
    
	//===========================================================================================================
    // GENERAR REGLAS DE VALIDACIÃ“N AUTOMÃTICAS
    // A partir de la estructura de la tabla
    //===========================================================================================================

    protected function generar_reglas_validacion($nombre_tabla, $campos_excluir = []) {
        $estructura = $this->obtener_estructura_tabla($nombre_tabla);
        $reglas = [];
        
        foreach($estructura as $campo) {
            $nombre_campo = strtolower($campo['COLUMN_NAME']);
            
            // Saltar campos excluidos (como IDs auto-incrementales)
            if(in_array($nombre_campo, $campos_excluir)) {
                continue;
            }
            
            $reglas_campo = [];
            
            // Es requerido si no permite NULL y no tiene valor por defecto
            if($campo['IS_NULLABLE'] == 'NO' && $campo['COLUMN_DEFAULT'] === null) {
                $reglas_campo['requerido'] = true;
            }
            
            // Longitud mÃ¡xima para campos de texto
            if($campo['CHARACTER_MAXIMUM_LENGTH']) {
                $reglas_campo['max_caracteres'] = (int)$campo['CHARACTER_MAXIMUM_LENGTH'];
            }
            
            // Validaciones especÃ­ficas por tipo de dato
            switch($campo['DATA_TYPE']) {
                case 'varchar':
                case 'text':
                    if($nombre_campo == 'email' || strpos($nombre_campo, 'email') !== false) {
                        $reglas_campo['email'] = true;
                    }
                    break;
                    
                case 'int':
                case 'bigint':
                case 'smallint':
                    $reglas_campo['solo_numeros'] = true;
                    break;
                    
                case 'decimal':
                case 'float':
                case 'double':
                    // Para nÃºmeros decimales se podrÃ­a agregar validaciÃ³n especÃ­fica
                    break;
            }
            
            $reglas[$nombre_campo] = $reglas_campo;
        }
        
        return $reglas;
    }
	
    //===========================================================================================================
    // SWEET ALERTAS
    // fucnion para generar mensajes de informacion hacia el usuario
    //===========================================================================================================
    protected function sweet_alert($datos){
        if($datos['Alerta'] == "simple"){
            $alerta = "
                <script>
                    swal(
                          '".$datos['Titulo']."',
                          '".$datos['Texto']."',
                          '".$datos['Tipo']."'
                        )
                </script>
            ";
        } elseif($datos['Alerta'] == "recargar"){
            $alerta = "
                <script>
                    swal({
                      title: '".$datos['Titulo']."',
                      text: '".$datos['Texto']."',
                      type: '".$datos['Tipo']."',
                      confirmButtonText: 'Aceptar'
                    }).then(function () {
                         location.reload();
                    });
                </script>
            ";
        } elseif($datos['Alerta'] == "limpiar"){
            $alerta = "
                <script>
                    swal({
                      title: '".$datos['Titulo']."',
                      text: '".$datos['Texto']."',
                      type: '".$datos['Tipo']."',
                      confirmButtonText: 'Aceptar'
                    }).then(function(){
                         $('.FormularioAjax')[0].reset();
                    });
                </script>
            ";
        }
        return $alerta;        
    }
}
?>