<?php
/**
 * MODELO DE EMPRESAS
 * Aquí van todas las funciones para manejar empresas en la base de datos
 * Este modelo hereda de SecureModel para tener todas las funciones de seguridad
 */

// Verificamos si es una petición AJAX para incluir el archivo correcto
if($peticionAjax){
    require_once "../core/SecureModel.php";
}else{
    require_once "./core/SecureModel.php";
}

class empresasModel extends SecureModel {

	//===========================================================================================================
    // REGISTRAR NUEVA EMPRESA E LA BASE DE DATOS
    // Esta función inserta la nueva empresa usando consultas seguras
    //===========================================================================================================
    protected function registrar_empresa_modelo($datos){
        try {
            // Preparar la consulta SQL segura
            $sql = "INSERT INTO App_empresa_empresa 
                    (EmpresaCodigo, EmpresaNit, EmpresaNombre, EmpresaDireccion, 
                     EmpresaTelefono, EmpresaEmail, EmpresaIdRepresentante, EmpresaNomRepresentante, EmpresaFechaRegistro, EmpresaFechaActualizacion) 
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
            
            // Preparar los parámetros en el orden correcto
            $parametros = [
                $datos['codigo'],
                $datos['nit'],
                $datos['nombre'],
                $datos['direccion'],
                $datos['telefono'],
                $datos['email'],
                $datos['id_representante'],
                $datos['nom_representante'],
				$datos['EmpresaFechaRegistro'],
				$datos['EmpresaFechaActualizacion']
            ];
            
            /*-*-*-*-*-* ejecuto consulta segura *-*-*-*-*-*/
            $stmt = $this->ejecutar_consulta_segura($sql, $parametros);
            
			/*-*-*-*-*-* Verificar si se insertó correctamente *-*-*-*-*-*/
            return $stmt->rowCount() > 0;
            
        } catch(Exception $e) {
			/*-*-*-*-*-* Guardar error en log *-*-*-*-*-*/
            error_log("Error registrando empresa: " . $e->getMessage());
            return false;
        }
    }
 
	/************ AUXILIAR 1: VERIFICAR SI EL NIT YA EXISTE ****************************************************/
    protected function verificar_nit_duplicado($nit){
        try {
            $sql = "SELECT COUNT(*) as total FROM App_empresa_empresa WHERE EmpresaNit = ?";
            $stmt = $this->ejecutar_consulta_segura($sql, [$nit]);
            $resultado = $stmt->fetch();
            
            return $resultado['total'] > 0;
            
        } catch(Exception $e) {
            error_log("Error verificando NIT: " . $e->getMessage());
            return false;
        }
    }
 
	/************ AUXILIAR 2: VERIFICAR SI EL EMAIL YA EXISTE **************************************************/
    protected function verificar_email_duplicado($email){
        try {
            $sql = "SELECT COUNT(*) as total FROM App_empresa_empresa WHERE EmpresaEmail = ?";
            $stmt = $this->ejecutar_consulta_segura($sql, [$email]);
            $resultado = $stmt->fetch();
            
            return $resultado['total'] > 0;
            
        } catch(Exception $e) {
            error_log("Error verificando email: " . $e->getMessage());
            return false;
        }
    }
 	
	//===========================================================================================================
    // LISTAR EMPRESAS CON PAGINACIÓN Y FILTROS
    // Función para obtener empresas con filtros aplicados y paginación
    //===========================================================================================================
	protected function listar_empresas_modelo($datos_busqueda, $pagina = 1, $registros_por_pagina = 10) {
		try {
			// Offset para la paginación
			$offset = ($pagina - 1) * $registros_por_pagina;

			// Definir campos donde buscar
			$campos_busqueda = [
				'e.EmpresaNombre',
				'e.EmpresaCodigo', 
				'e.EmpresaEmail',
				'e.EmpresaNomRepresentante',
				'e.EmpresaDireccion'
			];

			// Generar búsqueda inteligente
			$parametros_busqueda = [];
			$where_busqueda = $this->generar_busqueda_inteligente(
				$datos_busqueda['shareempresa'] ?? '', 
				$campos_busqueda, 
				$parametros_busqueda
			);

			//$estado_filtro = !empty($datos_busqueda['estadoempresa']) ? ucfirst($datos_busqueda['estadoempresa']) : '';
			
			$estado_filtro = !empty($datos_busqueda['estadoempresa']) ? '%' . ucfirst($datos_busqueda['estadoempresa']) . '%' : '%';

			// 1. Consulta para contar total
			$sql_count = "SELECT COUNT(*) as total 
						  FROM App_empresa_empresa e 
						  WHERE $where_busqueda
						  AND e.EmpresaEstado LIKE ?";

			$parametos_consulta_count = array_merge($parametros_busqueda, [$estado_filtro]);

			$stmt_count = $this->ejecutar_consulta_segura($sql_count, $parametos_consulta_count);
			$total_registros = $stmt_count->fetch()['total'];

			// 2. Consulta para obtener datos
			$sql_datos = "SELECT 
							e.EmpresaId,
							e.EmpresaCodigo,
							e.EmpresaNit,
							e.EmpresaNombre,
							e.EmpresaDireccion,
							e.EmpresaTelefono,
							e.EmpresaEmail,
							e.EmpresaIdRepresentante,
							e.EmpresaNomRepresentante,
							e.EmpresaFechaRegistro,
							e.EmpresaEstado,
							COALESCE(s.total_sucursales, 0) as total_sucursales
						  FROM App_empresa_empresa e 
							LEFT JOIN (
								SELECT SucursalIdEmpresa, COUNT(*) as total_sucursales 
								FROM App_empresa_sucursal 
								WHERE SucursalEstado = 'Activo' 
								GROUP BY SucursalIdEmpresa
							) s ON e.EmpresaId = s.SucursalIdEmpresa 
						  WHERE $where_busqueda
						  AND e.EmpresaEstado LIKE ?
						  ORDER BY e.EmpresaFechaRegistro DESC 
						  LIMIT ? OFFSET ?";

			$parametos_consulta_datos = array_merge($parametros_busqueda, [$estado_filtro, $registros_por_pagina, $offset]);

			$stmt_datos = $this->ejecutar_consulta_segura($sql_datos, $parametos_consulta_datos);
			$empresas = $stmt_datos->fetchAll();

			// Calcular paginación
			$total_paginas = ceil($total_registros / $registros_por_pagina);

			return [
				'empresas' => $empresas,
				'paginacion' => [
					'pagina_actual' => $pagina,
					'total_paginas' => $total_paginas,
					'total_registros' => $total_registros,
					'registros_por_pagina' => $registros_por_pagina,
					'desde' => $offset + 1,
					'hasta' => min($offset + $registros_por_pagina, $total_registros)
				]
			];

		} catch(Exception $e) {
			error_log("Error en listar_empresas_modelo: " . $e->getMessage());
			return [
				'empresas' => [],
				'paginacion' => [
					'pagina_actual' => 1,
					'total_paginas' => 0,
					'total_registros' => 0,
					'registros_por_pagina' => $registros_por_pagina,
					'desde' => 0,
					'hasta' => 0
				]
			];
		}
	}
    
    //===========================================================================================================
    // OBTENER ESTADÍSTICAS DE EMPRESAS
    // Función para obtener contadores para las tarjetas de estadísticas
    //===========================================================================================================
	protected function obtener_estadisticas_empresas_modelo() {
        try {
            
			
			$parametros =[
				'Activo'
			];
						
			$sql_total_empresas = "SELECT  COUNT(*) as total_empresas FROM App_empresa_empresa ";
			$stmt =  $this->ejecutar_consulta_segura($sql_total_empresas, []);
			$total_empresas = $stmt->fetch();
			
			$sql_empresas_activas = "SELECT  COUNT(*) as empresas_activas FROM App_empresa_empresa WHERE EmpresaEstado = ? ";
			$stmt =  $this->ejecutar_consulta_segura($sql_empresas_activas, $parametros);
			$empresas_activas = $stmt->fetch();
			
			$sql_total_sucursales = "SELECT COUNT(*) as total_sucursales FROM App_empresa_sucursal";
			$stmt =  $this->ejecutar_consulta_segura($sql_total_sucursales, []);
			$total_sucursales = $stmt->fetch();
			
			$sql_sucursales_activas = "SELECT COUNT(*) as sucursales_activas FROM App_empresa_sucursal WHERE SucursalEstado = ? ";
			$stmt =  $this->ejecutar_consulta_segura($sql_sucursales_activas, $parametros);
			$sucursales_activas = $stmt->fetch();
			
			// NUEVO: Estadísticas de sedes
			$sql_total_sedes = "SELECT COUNT(*) as total_sedes FROM App_empresa_sede";
			$stmt =  $this->ejecutar_consulta_segura($sql_total_sedes, []);
			$total_sedes = $stmt->fetch();
			
			$sql_sedes_activas = "SELECT COUNT(*) as sedes_activas FROM App_empresa_sede WHERE SedeEstado = ? ";
			$stmt =  $this->ejecutar_consulta_segura($sql_sedes_activas, $parametros);
			$sedes_activas = $stmt->fetch();
			
			return [
                'total_empresas' => $total_empresas['total_empresas'],
                'empresas_activas' => $empresas_activas['empresas_activas'],
                'total_sucursales' => $total_sucursales['total_sucursales'],
                'sucursales_activas' => $sucursales_activas['sucursales_activas'],
                'total_sedes' => $total_sedes['total_sedes'],
                'sedes_activas' => $sedes_activas['sedes_activas']
            ];
			
            
        } catch(Exception $e) {
            error_log("Error en obtener_estadisticas_empresas_modelo: " . $e->getMessage());
            return [
                'total_empresas' => 0,
                'empresas_activas' => 0,
                'total_sucursales' => 0,
                'sucursales_activas' => 0,
                'total_sedes' => 0,
                'sedes_activas' => 0
            ];
        }
    }
	
	//===========================================================================================================
    // FUNCION PARA ELIMINAR EMPRESA
    // Cambia el estado a 'Eliminado' en lugar de borrar físicamente
    //===========================================================================================================
	
	protected function eliminar_empresa_modelo($empresa_id) {
		try {
			$sql = "UPDATE App_empresa_empresa 
					SET EmpresaEstado = ?, 
						EmpresaFechaActualizacion = ? 
					WHERE EmpresaId = ? 
					AND EmpresaEstado != 'Eliminado'";

			$parametros = [
				'Eliminado',
				date("Y-m-d H:i:s"),
				$empresa_id
			];

			$stmt = $this->ejecutar_consulta_segura($sql, $parametros);

			// Verificar que se actualizó al menos una fila
			return $stmt->rowCount() > 0;

		} catch(Exception $e) {
			error_log("Error eliminando empresa: " . $e->getMessage());
			return false;
		}
	}


	//===========================================================================================================
    // FUNCIÓN AUXILIAR PARA SEPARAR CÓDIGO + TOKEN
    // Función para separar el codigo del token ya que este viene unificado para evitar plagios
    //===========================================================================================================
	protected function separar_codigo_con_token($data_combinada, $key_separacion) {
		try {
						
			// Generar el separador encriptado para buscar
			$separador_encriptado = $this->encryption_deterministico('1n49n', $key_separacion);
			$longitud_separador = strlen($separador_encriptado);
			
			// Buscar posición del separador
			$pos_separador = strpos($data_combinada, $separador_encriptado);
			if ($pos_separador === false) return false;

			// Extraer las partes
			$token_encriptado = substr($data_combinada, 0, $pos_separador);
			$codigo_encriptado = substr($data_combinada, $pos_separador + $longitud_separador);

			// Desencriptar el código
			$codigo_real = $this->decryption($codigo_encriptado);
			if (!$codigo_real) return false;

			return [
				'codigo' => $codigo_real,
				'token_encriptado' => $token_encriptado
			];

		} catch (Exception $e) {
			error_log("Error separando datos: " . $e->getMessage());
			return false;
		}
	}
	
	
	//===========================================================================================================
    // OBTENER EMPRESA POR ID
    // verifica si la empresa si existe
    //===========================================================================================================
	protected function obtener_empresa_por_id($empresa_id){
		try {
			$sql = "SELECT EmpresaId, EmpresaCodigo, EmpresaNit, EmpresaNombre, 
						   EmpresaDireccion, EmpresaTelefono, EmpresaEmail, 
						   EmpresaIdRepresentante, EmpresanomRepresentante,
						   EmpresaEstado, EmpresaFechaRegistro, EmpresaFechaActualizacion
					FROM App_empresa_empresa 
					WHERE EmpresaId = ? 
					AND EmpresaEstado != 'Eliminado'";

			$stmt = $this->ejecutar_consulta_segura($sql, [$empresa_id]);
			return $stmt->fetch();

		} catch(Exception $e) {
			error_log("Error obteniendo empresa por ID: " . $e->getMessage());
			return false;
		}
	}
	
	//===========================================================================================================
    // OBTENER EMPRESA COMPLETA POR ID
    // Función para obtener todos los datos de una empresa específica
    //===========================================================================================================
	
	protected function obtener_empresa_completa_modelo($empresa_id){
		try {
			$sql = "SELECT EmpresaId, EmpresaCodigo, EmpresaNit, EmpresaNombre, 
						   EmpresaDireccion, EmpresaTelefono, EmpresaEmail, 
						   EmpresaIdRepresentante, EmpresaNomRepresentante,
						   EmpresaEstado, EmpresaFechaRegistro, EmpresaFechaActualizacion
					FROM App_empresa_empresa 
					WHERE EmpresaId = ? ";
//AND EmpresaEstado != 'Eliminado'
			$stmt = $this->ejecutar_consulta_segura($sql, [$empresa_id]);
			$empresa = $stmt->fetch();

			return $empresa;

		} catch(Exception $e) {
			error_log("Error obteniendo empresa completa por ID: " . $e->getMessage());
			return false;
		}
	}

	//===========================================================================================================
    // ACTUALIZAR EMPRESA
    // Función para actualizar los datos de una empresa existente
    //===========================================================================================================
	
	protected function actualizar_empresa_modelo($datos_finales){
		try {
			$sql = "UPDATE App_empresa_empresa 
					SET EmpresaNit = ?, 
						EmpresaNombre = ?, 
						EmpresaDireccion = ?, 
						EmpresaTelefono = ?, 
						EmpresaEmail = ?, 
						EmpresaIdRepresentante = ?, 
						EmpresaNomRepresentante = ?, 
						EmpresaFechaActualizacion = ? 
					WHERE EmpresaId = ? 
					AND EmpresaEstado != 'Eliminado'";

			$parametros = [
				$datos_finales['nit'],
				$datos_finales['nombre'],
				$datos_finales['direccion'],
				$datos_finales['telefono'],
				$datos_finales['email'],
				$datos_finales['id_representante'],
				$datos_finales['nom_representante'],
				$datos_finales['fecha_actualizacion'],
				$datos_finales['id']
			];

			$stmt = $this->ejecutar_consulta_segura($sql, $parametros);

			// Verificar que se actualizó al menos una fila
			return $stmt->rowCount() > 0;

		} catch(Exception $e) {
			error_log("Error actualizando empresa: " . $e->getMessage());
			return false;
		}
	}

	//===========================================================================================================
    // VERIFICAR NIT DUPLICADO PARA ACTUALIZACIÓN
    // Excluye la empresa actual de la verificación
    //===========================================================================================================
	
	protected function verificar_nit_duplicado_actualizar($nit, $empresa_id_actual){
		try {
			$sql = "SELECT COUNT(*) as total 
					FROM App_empresa_empresa 
					WHERE EmpresaNit = ? 
					AND EmpresaId != ? 
					AND EmpresaEstado != 'Eliminado'";
			
			$stmt = $this->ejecutar_consulta_segura($sql, [$nit, $empresa_id_actual]);
			$resultado = $stmt->fetch();

			return $resultado['total'] > 0;

		} catch(Exception $e) {
			error_log("Error verificando NIT duplicado para actualización: " . $e->getMessage());
			return false;
		}
	}

	//===========================================================================================================
    // VERIFICAR EMAIL DUPLICADO PARA ACTUALIZACIÓN
    // Excluye la empresa actual de la verificación
    //===========================================================================================================
	
	protected function verificar_email_duplicado_actualizar($email, $empresa_id_actual){
		try {
			$sql = "SELECT COUNT(*) as total 
					FROM App_empresa_empresa 
					WHERE EmpresaEmail = ? 
					AND EmpresaId != ? 
					AND EmpresaEstado != 'Eliminado'";
			
			$stmt = $this->ejecutar_consulta_segura($sql, [$email, $empresa_id_actual]);
			$resultado = $stmt->fetch();

			return $resultado['total'] > 0;

		} catch(Exception $e) {
			error_log("Error verificando email duplicado para actualización: " . $e->getMessage());
			return false;
		}
	}
	
	
	//===========================================================================================================
    // CAMBIAR ESTADO DE EMPRESA
    // Función para actualizar el estado de una empresa (permite reactivar eliminadas)
    //===========================================================================================================
	
	protected function cambiar_estado_empresa_modelo($empresa_id, $nuevo_estado, $motivo_cambio){
		try {
			$sql = "UPDATE App_empresa_empresa 
					SET EmpresaEstado = ?, 
						EmpresaFechaActualizacion = ? 
					WHERE EmpresaId = ?";

			$parametros = [
				$nuevo_estado,
				date("Y-m-d H:i:s"),
				$empresa_id
			];

			$stmt = $this->ejecutar_consulta_segura($sql, $parametros);

			// Verificar que se actualizó al menos una fila
			return $stmt->rowCount() > 0;

		} catch(Exception $e) {
			error_log("Error cambiando estado de empresa: " . $e->getMessage());
			return false;
		}
	}
	
	//===========================================================================================================
    // OBTENER EMPRESAS PARA EXPORTACIÓN
    // Función para obtener TODAS las empresas sin paginación (para Excel)
    //===========================================================================================================
	
	protected function obtener_empresas_para_export_modelo($filtros) {
		try {
			// Definir campos donde buscar
			$campos_busqueda = [
				'e.EmpresaNombre',
				'e.EmpresaCodigo', 
				'e.EmpresaEmail',
				'e.EmpresaNomRepresentante',
				'e.EmpresaDireccion'
			];

			// Generar búsqueda inteligente
			$parametros_busqueda = [];
			$where_busqueda = $this->generar_busqueda_inteligente(
				$filtros['shareempresa'] ?? '', 
				$campos_busqueda, 
				$parametros_busqueda
			);

			// Filtro de estado
			$estado_filtro = !empty($filtros['estadoempresa']) ? '%' . ucfirst($filtros['estadoempresa']) . '%' : '%';

			// Consulta para obtener TODOS los datos (sin LIMIT)
			$sql = "SELECT 
						e.EmpresaId,
						e.EmpresaCodigo,
						e.EmpresaNit,
						e.EmpresaNombre,
						e.EmpresaDireccion,
						e.EmpresaTelefono,
						e.EmpresaEmail,
						e.EmpresaIdRepresentante,
						e.EmpresaNomRepresentante,
						e.EmpresaFechaRegistro,
						e.EmpresaEstado,
						COALESCE(s.total_sucursales, 0) as total_sucursales
					FROM App_empresa_empresa e 
						LEFT JOIN (
							SELECT SucursalIdEmpresa, COUNT(*) as total_sucursales 
							FROM App_empresa_sucursal 
							WHERE SucursalEstado = 'Activo' 
							GROUP BY SucursalIdEmpresa
						) s ON e.EmpresaId = s.SucursalIdEmpresa 
					WHERE $where_busqueda
					AND e.EmpresaEstado LIKE ?
					ORDER BY e.EmpresaNombre ASC";

			$parametros_consulta = array_merge($parametros_busqueda, [$estado_filtro]);

			$stmt = $this->ejecutar_consulta_segura($sql, $parametros_consulta);
			return $stmt->fetchAll();

		} catch(Exception $e) {
			error_log("Error en obtener_empresas_para_export_modelo: " . $e->getMessage());
			return [];
		}
	}
	

	//===========================================================================================================
	// LISTAR SUCURSALES DE UNA EMPRESA
	// Función para obtener todas las sucursales de una empresa específica con filtros
	//===========================================================================================================

	protected function listar_sucursales_empresa_modelo($empresa_id, $filtro_nombre = '', $filtro_estado = '') {
		try {
			// Definir campos donde buscar (búsqueda inteligente)
			$campos_busqueda = [
				's.SucursalNombre',
				's.SucursalCodigo', 
				's.SucursalEmail',
				's.SucursalNomRepresentante',
				's.SucursalDireccion'
			];

			// Generar búsqueda inteligente
			$parametros_busqueda = [];
			$where_busqueda = $this->generar_busqueda_inteligente(
				$filtro_nombre, 
				$campos_busqueda, 
				$parametros_busqueda
			);

			// Preparar condiciones base
			$condiciones = ['s.SucursalIdEmpresa = ?'];
			$parametros = [$empresa_id];

			// Agregar búsqueda inteligente si hay texto
			if (!empty($filtro_nombre)) {
				$condiciones[] = "($where_busqueda)";
				$parametros = array_merge($parametros, $parametros_busqueda);
			}

			// Filtro por estado (usando LIKE como en empresas para flexibilidad)
			$estado_filtro = !empty($filtro_estado) ? $filtro_estado : '%';
			if ($estado_filtro !== '%') {
				$condiciones[] = "s.SucursalEstado LIKE ?";
				$parametros[] = "%{$estado_filtro}%";
			} else {
				$condiciones[] = "s.SucursalEstado LIKE ?";
				$parametros[] = '%';
			}

			$where_clause = implode(' AND ', $condiciones);

			$sql = "SELECT 
						s.SucursalId,
						s.SucursalCodigo,
						s.SucursalNit,
						s.SucursalNombre,
						s.SucursalDireccion,
						s.SucursalTelefono,
						s.SucursalEmail,
						s.SucursalIdRepresentante,
						s.SucursalNomRepresentante,
						s.SucursalFechaRegistro,
						s.SucursalFechaActualizacion,
						s.SucursalEstado,
						e.EmpresaNombre,
						e.EmpresaCodigo,
						COALESCE(se.total_sedes, 0) as total_sedes
					FROM App_empresa_sucursal s
					INNER JOIN App_empresa_empresa e ON s.SucursalIdEmpresa = e.EmpresaId
					LEFT JOIN (
						SELECT SedeIdSucursal, COUNT(*) as total_sedes 
						FROM App_empresa_sede 
						GROUP BY SedeIdSucursal
					) se ON s.SucursalId = se.SedeIdSucursal
					WHERE {$where_clause}
					ORDER BY s.SucursalFechaRegistro DESC";

			$stmt = $this->ejecutar_consulta_segura($sql, $parametros);
			return $stmt->fetchAll();

		} catch(Exception $e) {
			error_log("Error en listar_sucursales_empresa_modelo: " . $e->getMessage());
			return [];
		}
	}

	//===========================================================================================================
	// OBTENER SUCURSAL POR ID
	// Función para obtener una sucursal específica por su ID
	//===========================================================================================================

	protected function obtener_sucursal_por_id($sucursal_id) {
		try {
			$sql = "SELECT 
						s.SucursalId,
						s.SucursalCodigo,
						s.SucursalNit,
						s.SucursalNombre,
						s.SucursalDireccion,
						s.SucursalTelefono,
						s.SucursalEmail,
						s.SucursalIdRepresentante,
						s.SucursalNomRepresentante,
						s.SucursalFechaRegistro,
						s.SucursalFechaActualizacion,
						s.SucursalIdEmpresa,
						s.SucursalEstado,
						e.EmpresaNombre,
						e.EmpresaCodigo
					FROM App_empresa_sucursal s
					INNER JOIN App_empresa_empresa e ON s.SucursalIdEmpresa = e.EmpresaId
					WHERE s.SucursalId = ?";

			$stmt = $this->ejecutar_consulta_segura($sql, [$sucursal_id]);
			return $stmt->fetch();

		} catch(Exception $e) {
			error_log("Error obteniendo sucursal por ID: " . $e->getMessage());
			return false;
		}
	}

	//===========================================================================================================
	// VERIFICAR EMAIL DUPLICADO PARA ACTUALIZACIÓN DE SUCURSAL
	// Excluye la sucursal actual de la verificación
	//===========================================================================================================

	protected function verificar_email_sucursal_duplicado_actualizar($email, $sucursal_id_actual, $empresa_id = null) {
		try {
			$sql = "SELECT COUNT(*) as total 
					FROM App_empresa_sucursal 
					WHERE SucursalEmail = ? 
					AND SucursalId != ? 
					AND SucursalEstado != 'Eliminado'";

			$parametros = [$email, $sucursal_id_actual];

			// Si se especifica empresa, verificar solo dentro de esa empresa
			if ($empresa_id !== null) {
				$sql .= " AND SucursalIdEmpresa = ?";
				$parametros[] = $empresa_id;
			}

			$stmt = $this->ejecutar_consulta_segura($sql, $parametros);
			$resultado = $stmt->fetch();

			return $resultado['total'] > 0;

		} catch(Exception $e) {
			error_log("Error verificando email duplicado para actualización de sucursal: " . $e->getMessage());
			return false;
		}
	}

	

	//===========================================================================================================
	// OBTENER ESTADÍSTICAS DE SUCURSALES POR EMPRESA
	// Función para obtener contadores de sucursales por estado para una empresa
	//===========================================================================================================

	protected function obtener_estadisticas_sucursales_empresa($empresa_id) {
		try {
			$sql_total = "SELECT COUNT(*) as total FROM App_empresa_sucursal WHERE SucursalIdEmpresa = ?";
			$stmt = $this->ejecutar_consulta_segura($sql_total, [$empresa_id]);
			$total_sucursales = $stmt->fetch()['total'];

			$sql_activas = "SELECT COUNT(*) as activas FROM App_empresa_sucursal WHERE SucursalIdEmpresa = ? AND SucursalEstado = 'Activo'";
			$stmt = $this->ejecutar_consulta_segura($sql_activas, [$empresa_id]);
			$sucursales_activas = $stmt->fetch()['activas'];

			$sql_inactivas = "SELECT COUNT(*) as inactivas FROM App_empresa_sucursal WHERE SucursalIdEmpresa = ? AND SucursalEstado = 'Inactivo'";
			$stmt = $this->ejecutar_consulta_segura($sql_inactivas, [$empresa_id]);
			$sucursales_inactivas = $stmt->fetch()['inactivas'];

			return [
				'total_sucursales' => $total_sucursales,
				'sucursales_activas' => $sucursales_activas,
				'sucursales_inactivas' => $sucursales_inactivas,
				'sucursales_eliminadas' => $total_sucursales - $sucursales_activas - $sucursales_inactivas
			];

		} catch(Exception $e) {
			error_log("Error en obtener_estadisticas_sucursales_empresa: " . $e->getMessage());
			return [
				'total_sucursales' => 0,
				'sucursales_activas' => 0,
				'sucursales_inactivas' => 0,
				'sucursales_eliminadas' => 0
			];
		}
	}

	//===========================================================================================================
	// REGISTRAR NUEVA SUCURSAL
	// Función para insertar una nueva sucursal en la base de datos
	//===========================================================================================================

	protected function registrar_sucursal_modelo($datos) {
		try {
			$sql = "INSERT INTO App_empresa_sucursal 
					(SucursalCodigo, SucursalNit, SucursalNombre, SucursalDireccion, 
					 SucursalTelefono, SucursalEmail, SucursalIdRepresentante, 
					 SucursalNomRepresentante, SucursalIdEmpresa, SucursalFechaRegistro, 
					 SucursalFechaActualizacion) 
					VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

			$parametros = [
				$datos['codigo'],
				$datos['nit'],
				$datos['nombre'],
				$datos['direccion'],
				$datos['telefono'],
				$datos['email'],
				$datos['id_representante'],
				$datos['nom_representante'],
				$datos['empresa_id'],
				$datos['fecha_registro'],
				$datos['fecha_actualizacion']
			];

			$stmt = $this->ejecutar_consulta_segura($sql, $parametros);
			return $stmt->rowCount() > 0;

		} catch(Exception $e) {
			error_log("Error registrando sucursal: " . $e->getMessage());
			return false;
		}
	}

	//===========================================================================================================
	// ACTUALIZAR SUCURSAL
	// Función para actualizar los datos de una sucursal existente
	//===========================================================================================================

	protected function actualizar_sucursal_modelo($datos) {
		try {
			$sql = "UPDATE App_empresa_sucursal 
					SET SucursalNit = ?, 
						SucursalNombre = ?, 
						SucursalDireccion = ?, 
						SucursalTelefono = ?, 
						SucursalEmail = ?, 
						SucursalIdRepresentante = ?, 
						SucursalNomRepresentante = ?, 
						SucursalFechaActualizacion = ? 
					WHERE SucursalId = ? 
					AND SucursalEstado != 'Eliminado'";

			$parametros = [
				$datos['nit'],
				$datos['nombre'],
				$datos['direccion'],
				$datos['telefono'],
				$datos['email'],
				$datos['id_representante'],
				$datos['nom_representante'],
				$datos['fecha_actualizacion'],
				$datos['id']
			];

			$stmt = $this->ejecutar_consulta_segura($sql, $parametros);
			return $stmt->rowCount() > 0;

		} catch(Exception $e) {
			error_log("Error actualizando sucursal: " . $e->getMessage());
			return false;
		}
	}

	//===========================================================================================================
	// ELIMINAR SUCURSAL (SOFT DELETE)
	// Función para cambiar el estado de una sucursal a 'Eliminado'
	//===========================================================================================================

	protected function eliminar_sucursal_modelo($sucursal_id) {
		try {
			$sql = "UPDATE App_empresa_sucursal 
					SET SucursalEstado = ?, 
						SucursalFechaActualizacion = ? 
					WHERE SucursalId = ? 
					AND SucursalEstado != 'Eliminado'";

			$parametros = [
				'Eliminado',
				date("Y-m-d H:i:s"),
				$sucursal_id
			];

			$stmt = $this->ejecutar_consulta_segura($sql, $parametros);
			return $stmt->rowCount() > 0;

		} catch(Exception $e) {
			error_log("Error eliminando sucursal: " . $e->getMessage());
			return false;
		}
	}

	//===========================================================================================================
	// CAMBIAR ESTADO DE SUCURSAL
	// Función para cambiar el estado de una sucursal (Activo/Inactivo/Suspendido)
	//===========================================================================================================

	protected function cambiar_estado_sucursal_modelo($sucursal_id, $nuevo_estado, $motivo_cambio) {
		try {
			$sql = "UPDATE App_empresa_sucursal 
					SET SucursalEstado = ?, 
						SucursalFechaActualizacion = ? 
					WHERE SucursalId = ?";

			$parametros = [
				$nuevo_estado,
				date("Y-m-d H:i:s"),
				$sucursal_id
			];

			$stmt = $this->ejecutar_consulta_segura($sql, $parametros);
			return $stmt->rowCount() > 0;

		} catch(Exception $e) {
			error_log("Error cambiando estado de sucursal: " . $e->getMessage());
			return false;
		}
	}

	//===========================================================================================================
	// VERIFICAR NIT DUPLICADO EN SUCURSALES
	// Función para verificar si el NIT de sucursal ya existe
	//===========================================================================================================

	protected function verificar_nit_sucursal_duplicado($nit, $empresa_id = null) {
		try {
			$sql = "SELECT COUNT(*) as total FROM App_empresa_sucursal WHERE SucursalNit = ?";
			$parametros = [$nit];

			// Si se especifica empresa, verificar solo dentro de esa empresa
			if ($empresa_id !== null) {
				$sql .= " AND SucursalIdEmpresa = ?";
				$parametros[] = $empresa_id;
			}

			$stmt = $this->ejecutar_consulta_segura($sql, $parametros);
			$resultado = $stmt->fetch();

			return $resultado['total'] > 0;

		} catch(Exception $e) {
			error_log("Error verificando NIT de sucursal: " . $e->getMessage());
			return false;
		}
	}

	//===========================================================================================================
	// VERIFICAR EMAIL DUPLICADO EN SUCURSALES
	// Función para verificar si el email de sucursal ya existe
	//===========================================================================================================

	protected function verificar_email_sucursal_duplicado($email, $empresa_id = null) {
		try {
			$sql = "SELECT COUNT(*) as total FROM App_empresa_sucursal WHERE SucursalEmail = ?";
			$parametros = [$email];

			// Si se especifica empresa, verificar solo dentro de esa empresa
			if ($empresa_id !== null) {
				$sql .= " AND SucursalIdEmpresa = ?";
				$parametros[] = $empresa_id;
			}

			$stmt = $this->ejecutar_consulta_segura($sql, $parametros);
			$resultado = $stmt->fetch();

			return $resultado['total'] > 0;

		} catch(Exception $e) {
			error_log("Error verificando email de sucursal: " . $e->getMessage());
			return false;
		}
	}

	//===========================================================================================================
	// VERIFICAR NIT DUPLICADO PARA ACTUALIZACIÓN DE SUCURSAL
	// Excluye la sucursal actual de la verificación
	//===========================================================================================================

	protected function verificar_nit_sucursal_duplicado_actualizar($nit, $sucursal_id_actual, $empresa_id = null) {
		try {
			$sql = "SELECT COUNT(*) as total 
					FROM App_empresa_sucursal 
					WHERE SucursalNit = ? 
					AND SucursalId != ? 
					AND SucursalEstado != 'Eliminado'";

			$parametros = [$nit, $sucursal_id_actual];

			// Si se especifica empresa, verificar solo dentro de esa empresa
			if ($empresa_id !== null) {
				$sql .= " AND SucursalIdEmpresa = ?";
				$parametros[] = $empresa_id;
			}

			$stmt = $this->ejecutar_consulta_segura($sql, $parametros);
			$resultado = $stmt->fetch();

			return $resultado['total'] > 0;

		} catch(Exception $e) {
			error_log("Error verificando NIT duplicado para actualización de sucursal: " . $e->getMessage());
			return false;
		}
	}
	
	
	//===========================================================================================================
	// REGISTRAR NUEVA SEDE EN LA BASE DE DATOS
	// Esta función inserta la nueva sede usando consultas seguras (igual que las sucursales)
	//===========================================================================================================
	protected function registrar_sede_modelo($datos){
		try {
			// Preparar la consulta SQL segura
			$sql = "INSERT INTO App_empresa_sede 
					(SedeCodigo, SedeNit, SedeNombre, SedeDireccion, 
					 SedeTelefono, SedeEmail, SedeIdRepresentante, SedeNomRepresentante, 
					 SedeIdSucursal, SedeFechaRegistro, SedeFechaActualizacion) 
					VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

			// Preparar los parámetros en el orden correcto
			$parametros = [
				$datos['codigo'],
				$datos['nit'],
				$datos['nombre'],
				$datos['direccion'],
				$datos['telefono'],
				$datos['email'],
				$datos['id_representante'],
				$datos['nom_representante'],
				$datos['sucursal_id'],
				$datos['fecha_registro'],
				$datos['fecha_actualizacion']
			];

			/*-*-*-*-*-* ejecuto consulta segura *-*-*-*-*-*/
			$stmt = $this->ejecutar_consulta_segura($sql, $parametros);

			/*-*-*-*-*-* Verificar si se insertó correctamente *-*-*-*-*-*/
			return $stmt->rowCount() > 0;

		} catch(Exception $e) {
			/*-*-*-*-*-* Guardar error en log *-*-*-*-*-*/
			error_log("Error registrando sede: " . $e->getMessage());
			return false;
		}
	}
	
	//===========================================================================================================
	// VERIFICAR SI EL NIT DE SEDE YA EXISTE
	// Función para validar que no se dupliquen NITs en sedes
	//===========================================================================================================
	protected function verificar_nit_sede_duplicado($nit){
		try {
			$sql = "SELECT COUNT(*) as total FROM App_empresa_sede WHERE SedeNit = ?";
			$stmt = $this->ejecutar_consulta_segura($sql, [$nit]);
			$resultado = $stmt->fetch();

			return $resultado['total'] > 0;

		} catch(Exception $e) {
			error_log("Error verificando NIT de sede: " . $e->getMessage());
			return false;
		}
	}

	//===========================================================================================================
	// VERIFICAR SI EL EMAIL DE SEDE YA EXISTE
	// Función para validar que no se dupliquen emails en sedes
	//===========================================================================================================
	protected function verificar_email_sede_duplicado($email){
		try {
			$sql = "SELECT COUNT(*) as total FROM App_empresa_sede WHERE SedeEmail = ?";
			$stmt = $this->ejecutar_consulta_segura($sql, [$email]);
			$resultado = $stmt->fetch();

			return $resultado['total'] > 0;

		} catch(Exception $e) {
			error_log("Error verificando email de sede: " . $e->getMessage());
			return false;
		}
	}
	
	//===========================================================================================================
	// LISTAR SEDES DE UNA SUCURSAL
	// Función para obtener todas las sedes de una sucursal específica con filtros
	//===========================================================================================================
	protected function listar_sedes_sucursal_modelo($sucursal_id, $filtro_nombre = '', $filtro_estado = '') {
		try {
			// Construir condiciones WHERE
			$condiciones = ['se.SedeIdSucursal = ?'];
			$parametros = [$sucursal_id];

			// Filtro por nombre
			if (!empty($filtro_nombre)) {
				$condiciones[] = "(se.SedeNombre LIKE ? OR se.SedeCodigo LIKE ? OR se.SedeEmail LIKE ?)";
				$filtro_like = "%{$filtro_nombre}%";
				$parametros[] = $filtro_like;
				$parametros[] = $filtro_like;
				$parametros[] = $filtro_like;
			}

			// Filtro por estado
			if (!empty($filtro_estado)) {
				$condiciones[] = "se.SedeEstado = ?";
				$parametros[] = $filtro_estado;
			}

			$where_clause = implode(' AND ', $condiciones);

			$sql = "SELECT 
						se.SedeId,
						se.SedeCodigo,
						se.SedeNit,
						se.SedeNombre,
						se.SedeDireccion,
						se.SedeTelefono,
						se.SedeEmail,
						se.SedeIdRepresentante,
						se.SedeNomRepresentante,
						se.SedeFechaRegistro,
						se.SedeFechaActualizacion,
						se.SedeEstado,
						s.SucursalNombre,
						s.SucursalCodigo,
						e.EmpresaNombre,
						e.EmpresaCodigo
					FROM App_empresa_sede se
					INNER JOIN App_empresa_sucursal s ON se.SedeIdSucursal = s.SucursalId
					INNER JOIN App_empresa_empresa e ON s.SucursalIdEmpresa = e.EmpresaId
					WHERE {$where_clause}
					ORDER BY se.SedeFechaRegistro DESC";

			$stmt = $this->ejecutar_consulta_segura($sql, $parametros);
			return $stmt->fetchAll();

		} catch(Exception $e) {
			error_log("Error en listar_sedes_sucursal_modelo: " . $e->getMessage());
			return [];
		}
	}
	
	//===========================================================================================================
	// OBTENER SEDE POR ID
	// Función para obtener una sede específica por su ID con información de sucursal y empresa
	//===========================================================================================================

	protected function obtener_sede_por_id($sede_id) {
		try {
			$sql = "SELECT 
						se.SedeId,
						se.SedeCodigo,
						se.SedeNit,
						se.SedeNombre,
						se.SedeDireccion,
						se.SedeTelefono,
						se.SedeEmail,
						se.SedeIdRepresentante,
						se.SedeNomRepresentante,
						se.SedeFechaRegistro,
						se.SedeFechaActualizacion,
						se.SedeIdSucursal,
						se.SedeEstado,
						s.SucursalNombre,
						s.SucursalCodigo,
						e.EmpresaNombre,
						e.EmpresaCodigo
					FROM App_empresa_sede se
					INNER JOIN App_empresa_sucursal s ON se.SedeIdSucursal = s.SucursalId
					INNER JOIN App_empresa_empresa e ON s.SucursalIdEmpresa = e.EmpresaId
					WHERE se.SedeId = ?";

			$stmt = $this->ejecutar_consulta_segura($sql, [$sede_id]);
			return $stmt->fetch();

		} catch(Exception $e) {
			error_log("Error obteniendo sede por ID: " . $e->getMessage());
			return false;
		}
	}

	//===========================================================================================================
	// ACTUALIZAR SEDE
	// Función para actualizar los datos de una sede existente
	//===========================================================================================================

	protected function actualizar_sede_modelo($datos) {
		try {
			$sql = "UPDATE App_empresa_sede 
					SET SedeNit = ?, 
						SedeNombre = ?, 
						SedeDireccion = ?, 
						SedeTelefono = ?, 
						SedeEmail = ?, 
						SedeIdRepresentante = ?, 
						SedeNomRepresentante = ?, 
						SedeFechaActualizacion = ? 
					WHERE SedeId = ? 
					AND SedeEstado != 'Eliminado'";

			$parametros = [
				$datos['nit'],
				$datos['nombre'],
				$datos['direccion'],
				$datos['telefono'],
				$datos['email'],
				$datos['id_representante'],
				$datos['nom_representante'],
				$datos['fecha_actualizacion'],
				$datos['id']
			];

			$stmt = $this->ejecutar_consulta_segura($sql, $parametros);
			return $stmt->rowCount() > 0;

		} catch(Exception $e) {
			error_log("Error actualizando sede: " . $e->getMessage());
			return false;
		}
	}

	//===========================================================================================================
	// VERIFICAR NIT DUPLICADO PARA ACTUALIZACIÓN DE SEDE
	// Excluye la sede actual de la verificación
	//===========================================================================================================

	protected function verificar_nit_sede_duplicado_actualizar($nit, $sede_id_actual, $sucursal_id = null) {
		try {
			$sql = "SELECT COUNT(*) as total 
					FROM App_empresa_sede 
					WHERE SedeNit = ? 
					AND SedeId != ? 
					AND SedeEstado != 'Eliminado'";

			$parametros = [$nit, $sede_id_actual];

			// Si se especifica sucursal, verificar solo dentro de esa sucursal
			if ($sucursal_id !== null) {
				$sql .= " AND SedeIdSucursal = ?";
				$parametros[] = $sucursal_id;
			}

			$stmt = $this->ejecutar_consulta_segura($sql, $parametros);
			$resultado = $stmt->fetch();

			return $resultado['total'] > 0;

		} catch(Exception $e) {
			error_log("Error verificando NIT duplicado para actualización de sede: " . $e->getMessage());
			return false;
		}
	}

	//===========================================================================================================
	// VERIFICAR EMAIL DUPLICADO PARA ACTUALIZACIÓN DE SEDE
	// Excluye la sede actual de la verificación
	//===========================================================================================================

	protected function verificar_email_sede_duplicado_actualizar($email, $sede_id_actual, $sucursal_id = null) {
		try {
			$sql = "SELECT COUNT(*) as total 
					FROM App_empresa_sede 
					WHERE SedeEmail = ? 
					AND SedeId != ? 
					AND SedeEstado != 'Eliminado'";

			$parametros = [$email, $sede_id_actual];

			// Si se especifica sucursal, verificar solo dentro de esa sucursal
			if ($sucursal_id !== null) {
				$sql .= " AND SedeIdSucursal = ?";
				$parametros[] = $sucursal_id;
			}

			$stmt = $this->ejecutar_consulta_segura($sql, $parametros);
			$resultado = $stmt->fetch();

			return $resultado['total'] > 0;

		} catch(Exception $e) {
			error_log("Error verificando email duplicado para actualización de sede: " . $e->getMessage());
			return false;
		}
	}

	//===========================================================================================================
	// ELIMINAR SEDE (SOFT DELETE)
	// Función para cambiar el estado de una sede a 'Eliminado'
	//===========================================================================================================

	protected function eliminar_sede_modelo($sede_id) {
		try {
			$sql = "UPDATE App_empresa_sede 
					SET SedeEstado = ?, 
						SedeFechaActualizacion = ? 
					WHERE SedeId = ? 
					AND SedeEstado != 'Eliminado'";

			$parametros = [
				'Eliminado',
				date("Y-m-d H:i:s"),
				$sede_id
			];

			$stmt = $this->ejecutar_consulta_segura($sql, $parametros);
			return $stmt->rowCount() > 0;

		} catch(Exception $e) {
			error_log("Error eliminando sede: " . $e->getMessage());
			return false;
		}
	}

	//===========================================================================================================
	// CAMBIAR ESTADO DE SEDE
	// Función para cambiar el estado de una sede (Activo/Inactivo/Suspendido)
	//===========================================================================================================

	protected function cambiar_estado_sede_modelo($sede_id, $nuevo_estado, $motivo_cambio) {
		try {
			$sql = "UPDATE App_empresa_sede 
					SET SedeEstado = ?, 
						SedeFechaActualizacion = ? 
					WHERE SedeId = ?";

			$parametros = [
				$nuevo_estado,
				date("Y-m-d H:i:s"),
				$sede_id
			];

			$stmt = $this->ejecutar_consulta_segura($sql, $parametros);
			return $stmt->rowCount() > 0;

		} catch(Exception $e) {
			error_log("Error cambiando estado de sede: " . $e->getMessage());
			return false;
		}
	}

	//===========================================================================================================
	// OBTENER ESTADÍSTICAS DE SEDES POR SUCURSAL
	// Función para obtener contadores de sedes por estado para una sucursal
	//===========================================================================================================

	protected function obtener_estadisticas_sedes_sucursal($sucursal_id) {
		try {
			$sql_total = "SELECT COUNT(*) as total FROM App_empresa_sede WHERE SedeIdSucursal = ?";
			$stmt = $this->ejecutar_consulta_segura($sql_total, [$sucursal_id]);
			$total_sedes = $stmt->fetch()['total'];

			$sql_activas = "SELECT COUNT(*) as activas FROM App_empresa_sede WHERE SedeIdSucursal = ? AND SedeEstado = 'Activo'";
			$stmt = $this->ejecutar_consulta_segura($sql_activas, [$sucursal_id]);
			$sedes_activas = $stmt->fetch()['activas'];

			$sql_inactivas = "SELECT COUNT(*) as inactivas FROM App_empresa_sede WHERE SedeIdSucursal = ? AND SedeEstado = 'Inactivo'";
			$stmt = $this->ejecutar_consulta_segura($sql_inactivas, [$sucursal_id]);
			$sedes_inactivas = $stmt->fetch()['inactivas'];

			return [
				'total_sedes' => $total_sedes,
				'sedes_activas' => $sedes_activas,
				'sedes_inactivas' => $sedes_inactivas,
				'sedes_eliminadas' => $total_sedes - $sedes_activas - $sedes_inactivas
			];

		} catch(Exception $e) {
			error_log("Error en obtener_estadisticas_sedes_sucursal: " . $e->getMessage());
			return [
				'total_sedes' => 0,
				'sedes_activas' => 0,
				'sedes_inactivas' => 0,
				'sedes_eliminadas' => 0
			];
		}
	}

	//===========================================================================================================
	// OBTENER SEDES PARA EXPORTACIÓN
	// Función para obtener TODAS las sedes sin paginación (para futuros reportes)
	//===========================================================================================================

	protected function obtener_sedes_para_export_modelo($filtros) {
		try {
			// Definir campos donde buscar
			$campos_busqueda = [
				'se.SedeNombre',
				'se.SedeCodigo', 
				'se.SedeEmail',
				'se.SedeNomRepresentante',
				'se.SedeDireccion'
			];

			// Generar búsqueda inteligente
			$parametros_busqueda = [];
			$where_busqueda = $this->generar_busqueda_inteligente(
				$filtros['shareseede'] ?? '', 
				$campos_busqueda, 
				$parametros_busqueda
			);

			// Filtro de estado
			$estado_filtro = !empty($filtros['estadosede']) ? '%' . ucfirst($filtros['estadosede']) . '%' : '%';

			// Consulta para obtener TODOS los datos (sin LIMIT)
			$sql = "SELECT 
						se.SedeId,
						se.SedeCodigo,
						se.SedeNit,
						se.SedeNombre,
						se.SedeDireccion,
						se.SedeTelefono,
						se.SedeEmail,
						se.SedeIdRepresentante,
						se.SedeNomRepresentante,
						se.SedeFechaRegistro,
						se.SedeEstado,
						s.SucursalNombre,
						s.SucursalCodigo,
						e.EmpresaNombre,
						e.EmpresaCodigo
					FROM App_empresa_sede se
					INNER JOIN App_empresa_sucursal s ON se.SedeIdSucursal = s.SucursalId
					INNER JOIN App_empresa_empresa e ON s.SucursalIdEmpresa = e.EmpresaId
					WHERE $where_busqueda
					AND se.SedeEstado LIKE ?
					ORDER BY se.SedeNombre ASC";

			$parametros_consulta = array_merge($parametros_busqueda, [$estado_filtro]);

			$stmt = $this->ejecutar_consulta_segura($sql, $parametros_consulta);
			return $stmt->fetchAll();

		} catch(Exception $e) {
			error_log("Error en obtener_sedes_para_export_modelo: " . $e->getMessage());
			return [];
		}
	}
}
?>