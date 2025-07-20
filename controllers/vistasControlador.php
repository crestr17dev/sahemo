<?php
	require_once "./models/vistasModelo.php";
	
	
	class vistasControlador extends vistasModelo{
        
         public function __construct() {
            // Ejecutar seguridad UNA SOLA VEZ para toda la aplicación
            $this->inicializar_seguridad();
			$this->limpieza_inteligente_tokens();
        }
        
		public function obtener_plantilla_controlador(){
			return require_once "./views/plantilla.php";
		}

		public function obtener_vistas_controlador(){
			if(isset($_GET['views'])){
				$ruta=explode("/", $_GET['views']);
				$respuesta=vistasModelo::obtener_vistas_modelo($ruta[0]);
			}else{
				$respuesta="home";
                
                // Log para auditoría
            $this->guardar_log('visita de pagina', [
                'datos_antes'=>['sin datos'],
                'datos_despues'=> [
                    'pagina_visitada'=>'home'
                ],
            ], 'bajo', 'exito', 'App_inicio');
                
			}
            
			return $respuesta;
			//echo $ruta;
		}
		

		/**
		 * Limpieza inteligente de tokens expirados
		 * Se ejecuta automáticamente pero con control de frecuencia
		 */
		private function limpieza_inteligente_tokens() {
			try {
				// Control de tiempo: máximo una limpieza cada 10 minutos por sesión
				$ultima_limpieza = $_SESSION['ultima_limpieza_tokens'] ?? 0;
				$tiempo_actual = time();
				$intervalo_minimo = 600; // 10 minutos en segundos

				// Si han pasado al menos 10 minutos desde la última limpieza
				if (($tiempo_actual - $ultima_limpieza) > $intervalo_minimo) {

					// 15% de probabilidad de ejecutar limpieza (balanceado)
					if (rand(1, 100) <= 15) {

						// Ejecutar limpieza
						$tokens_eliminados = $this->limpiar_tokens_expirados();

						// Actualizar timestamp de última limpieza
						$_SESSION['ultima_limpieza_tokens'] = $tiempo_actual;

						// Log para monitoreo (opcional, solo si se eliminaron tokens)
						if ($tokens_eliminados > 0) {
							$this->guardar_log('limpieza_automatica_tokens', [
								'datos_antes' => [
									'tokens_en_sesion_antes' => $this->contar_tokens_en_sesion(),
									'ultima_limpieza_hace_minutos' => round(($tiempo_actual - $ultima_limpieza) / 60, 1)
								],
								'datos_despues' => [
									'tokens_eliminados' => $tokens_eliminados,
									'metodo' => 'limpieza_automatica_constructor'
								],
							], 'bajo', 'exito', 'mantenimiento');
						}
					}
				}

			} catch (Exception $e) {
				// Error en limpieza no debe afectar la funcionalidad principal
				error_log("Error en limpieza automática de tokens: " . $e->getMessage());
			}
		}

		/**
		 * Función auxiliar para contar tokens en la sesión actual
		 * Útil para estadísticas y monitoreo
		 */
		private function contar_tokens_en_sesion() {
			$contador = 0;
			foreach ($_SESSION as $clave => $valor) {
				if (strpos($clave, 'token_') === 0 && is_array($valor) && isset($valor['expira_en'])) {
					$contador++;
				}
			}
			return $contador;
		}

		// === OPCIONAL: Función para limpieza manual ===
		/**
		 * Forzar limpieza de tokens (para uso administrativo)
		 * Puedes llamarla desde una página de administración
		 */
		public function forzar_limpieza_tokens() {
			$tokens_eliminados = $this->limpiar_tokens_expirados();

			$this->guardar_log('limpieza_manual_tokens', [
				'datos_antes' => ['accion' => 'limpieza_forzada_manual'],
				'datos_despues' => [
					'tokens_eliminados' => $tokens_eliminados,
					'usuario_executor' => $_SESSION['UsuarioId'] ?? 'anonimo'
				],
			], 'bajo', 'exito', 'administracion');

			return $tokens_eliminados;
		}

        
		//===========================================================================================================
		// OBTENER TOKEN CSRF PARA EL FORMULARIO
		// Función para generar el token que va en el formulario de registro y/o actualización
		//===========================================================================================================

		public function obtener_token_csrf($key){
			return $this->generar_token_csrf($key);
		}
		
	}