<?php
/**
 * MODELO DE USUARIOS - VERSIÓN PASO A PASO
 * Empezamos solo con registro de usuarios
 */

// Verificamos si es una petición AJAX para incluir el archivo correcto
if($peticionAjax){
    require_once "../core/SecureModel.php";
}else{
    require_once "./core/SecureModel.php";
}

class usuariosModel extends SecureModel {

	//===========================================================================================================
    // REGISTRAR NUEVO USUARIO EN LA BASE DE DATOS
    // Esta función inserta el nuevo usuario usando consultas seguras
    //===========================================================================================================
    protected function registrar_usuario_modelo($datos){
		try {
			// Preparar la consulta SQL segura ACTUALIZADA
			$sql = "INSERT INTO App_usuarios_usuario 
					(UsuarioCodigo, UsuarioDocumento, UsuarioTipoDocumento, UsuarioNombres, 
					 UsuarioApellidos, UsuarioEmail, UsuarioTelefono, UsuarioPassword, 
					 UsuarioCargo, UsuarioDepartamento, UsuarioEmpresaId, UsuarioSucursalId, 
					 UsuarioSedeId, UsuarioFechaRegistro, UsuarioFechaActualizacion,
					 UsuarioPasswordExpira, UsuarioPasswordCambio) 
					VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

			// Preparar los parámetros en el orden correcto
			$parametros = [
				$datos['codigo'],
				$datos['documento'],
				$datos['tipo_documento'],
				$datos['nombres'],
				$datos['apellidos'],
				$datos['email'],
				$datos['telefono'],
				$datos['password_hash'],
				$datos['cargo'],
				$datos['departamento'],
				$datos['empresa_id'],
				$datos['sucursal_id'],
				$datos['sede_id'],
				$datos['UsuarioFechaRegistro'],
				$datos['UsuarioFechaActualizacion'],
				1, // UsuarioPasswordExpira = 1 (debe cambiar contraseña)
				$datos['password_expira_fecha'] // Fecha límite para cambiar
			];

			/*-*-*-*-*-* ejecuto consulta segura *-*-*-*-*-*/
			$stmt = $this->ejecutar_consulta_segura($sql, $parametros);

			/*-*-*-*-*-* Verificar si se insertó correctamente *-*-*-*-*-*/
			return $stmt->rowCount() > 0;

		} catch(Exception $e) {
			/*-*-*-*-*-* Guardar error en log *-*-*-*-*-*/
			error_log("Error registrando usuario: " . $e->getMessage());
			return false;
		}
	}

	//===========================================================================================================
    // FUNCION PARA VERIFICAR PASSWORD TEMPORAL
    // verificar_password_temporal
    //===========================================================================================================

	protected function verificar_password_temporal($codigo_usuario){
        try {
            $sql = "SELECT UsuarioPasswordExpira, UsuarioPasswordCambio, UsuarioFechaRegistro 
                    FROM App_usuarios_usuario 
                    WHERE UsuarioCodigo = ? AND UsuarioEstado = 'Activo'";
            
            $stmt = $this->ejecutar_consulta_segura($sql, [$codigo_usuario]);
            $resultado = $stmt->fetch();
            
            if($resultado){
                // Si UsuarioPasswordExpira = 1, debe cambiar contraseña
                $debe_cambiar = $resultado['UsuarioPasswordExpira'] == 1;
                
                // Verificar si la contraseña temporal expiró (7 días desde registro)
                $fecha_registro = new DateTime($resultado['UsuarioFechaRegistro']);
                $fecha_actual = new DateTime();
                $diferencia = $fecha_actual->diff($fecha_registro);
                $dias_transcurridos = $diferencia->days;
                
                $password_expirada = $dias_transcurridos > 7;
                
                return [
                    'debe_cambiar' => $debe_cambiar,
                    'password_expirada' => $password_expirada,
                    'dias_transcurridos' => $dias_transcurridos,
                    'dias_restantes' => max(0, 7 - $dias_transcurridos)
                ];
            }
            
            return false;
            
        } catch(Exception $e) {
            error_log("Error verificando password temporal: " . $e->getMessage());
            return false;
        }
    }

/************ FUNCIÓN: actualizar_password_primer_acceso *************************************/
    protected function actualizar_password_primer_acceso($codigo_usuario, $nueva_password){
        try {
            $password_hash = password_hash($nueva_password, PASSWORD_DEFAULT);
			
			$fechaactualizacion = date('Y-m-d H:m:s');
            
            $sql = "UPDATE App_usuarios_usuario 
                    SET UsuarioPassword = ?, 
                        UsuarioPasswordExpira = 0, 
                        UsuarioPasswordCambio = ?,
                        UsuarioFechaActualizacion = ?
                    WHERE UsuarioCodigo = ?";
			
			$parametros = [
				$password_hash,
				$fechaactualizacion,
				$fechaactualizacion,
				$codigo_usuario				
			];
            
            $stmt = $this->ejecutar_consulta_segura($sql, $parametros);
            
            return $stmt->rowCount() > 0;
            
        } catch(Exception $e) {
            error_log("Error actualizando password: " . $e->getMessage());
            return false;
        }
    }

    /************ FUNCIÓN: obtener_usuario_para_login ********************************************/
	 protected function obtener_usuario_para_login($identificador){
        try {
            /*$sql = "SELECT u.*, e.EmpresaNombre, s.SucursalNombre, se.SedeNombre
                    FROM App_usuarios_usuario u
                    LEFT JOIN App_empresa_empresa e ON u.UsuarioEmpresaId = e.EmpresaId
                    LEFT JOIN App_empresa_sucursal s ON u.UsuarioSucursalId = s.SucursalId  
                    LEFT JOIN App_empresa_sede se ON u.UsuarioSedeId = se.SedeId
                    WHERE (u.UsuarioCodigo = ? OR u.UsuarioEmail = ? OR u.UsuarioDocumento = ?)";*/
			
			
			$sql = "SELECT u.*, e.EmpresaNombre, s.SucursalNombre, se.SedeNombre, rol.UsuarioRolIdRol, r.RolNivel, r.RolNombre
					FROM App_usuarios_usuario u
					LEFT JOIN App_empresa_empresa e ON u.UsuarioEmpresaId = e.EmpresaId
					LEFT JOIN App_empresa_sucursal s ON u.UsuarioSucursalId = s.SucursalId  
					LEFT JOIN App_empresa_sede se ON u.UsuarioSedeId = se.SedeId
					LEFT JOIN App_usuarios_usuario_rol rol ON u.UsuarioId = rol.UsuarioRolIdUsuario
					LEFT JOIN App_usuarios_rol r ON rol.UsuarioRolIdRol = r.RolId
					WHERE (u.UsuarioCodigo = ? OR u.UsuarioEmail = ? OR u.UsuarioDocumento = ?)";
			
            
            $stmt = $this->ejecutar_consulta_segura($sql, [$identificador, $identificador, $identificador]);
            $resultado = $stmt->fetch();
            
            return $resultado;
            
        } catch(Exception $e) {
            error_log("Error obteniendo usuario para login: " . $e->getMessage());
            return false;
        }
    }
	
	/************ AUXILIAR 1: VERIFICAR SI EL DOCUMENTO YA EXISTE ****************************************************/
    protected function verificar_documento_duplicado($documento){
        try {
            $sql = "SELECT COUNT(*) as total FROM App_usuarios_usuario WHERE UsuarioDocumento = ?";
            $stmt = $this->ejecutar_consulta_segura($sql, [$documento]);
            $resultado = $stmt->fetch();
            
            return $resultado['total'] > 0;
            
        } catch(Exception $e) {
            error_log("Error verificando documento: " . $e->getMessage());
            return false;
        }
    }
 
	/************ AUXILIAR 2: VERIFICAR SI EL EMAIL YA EXISTE **************************************************/
    protected function verificar_email_duplicado($email){
        try {
            $sql = "SELECT COUNT(*) as total FROM App_usuarios_usuario WHERE UsuarioEmail = ?";
            $stmt = $this->ejecutar_consulta_segura($sql, [$email]);
            $resultado = $stmt->fetch();
            
            return $resultado['total'] > 0;
            
        } catch(Exception $e) {
            error_log("Error verificando email: " . $e->getMessage());
            return false;
        }
    }

	/************ AUXILIAR 3: VERIFICAR QUE LA EMPRESA EXISTE Y ESTÁ ACTIVA *********************************/
    protected function verificar_empresa_valida($empresa_id){
        try {
            if(empty($empresa_id)) return false;
            
            $sql = "SELECT COUNT(*) as total FROM App_empresa_empresa 
                    WHERE EmpresaId = ? AND EmpresaEstado = 'Activo'";
            $stmt = $this->ejecutar_consulta_segura($sql, [$empresa_id]);
            $resultado = $stmt->fetch();
            
            return $resultado['total'] > 0;
            
        } catch(Exception $e) {
            error_log("Error verificando empresa: " . $e->getMessage());
            return false;
        }
    }

	/************ AUXILIAR 4: VERIFICAR QUE LA SUCURSAL EXISTE Y PERTENECE A LA EMPRESA *******************/
    protected function verificar_sucursal_valida($sucursal_id, $empresa_id){
        try {
            if(empty($sucursal_id)) return true; // Es opcional
            
            $sql = "SELECT COUNT(*) as total FROM App_empresa_sucursal 
                    WHERE SucursalId = ? AND SucursalIdEmpresa = ? AND SucursalEstado = 'Activo'";
            $stmt = $this->ejecutar_consulta_segura($sql, [$sucursal_id, $empresa_id]);
            $resultado = $stmt->fetch();
            
            return $resultado['total'] > 0;
            
        } catch(Exception $e) {
            error_log("Error verificando sucursal: " . $e->getMessage());
            return false;
        }
    }

	/************ AUXILIAR 5: VERIFICAR QUE LA SEDE EXISTE Y PERTENECE A LA SUCURSAL *********************/
    protected function verificar_sede_valida($sede_id, $sucursal_id){
        try {
            if(empty($sede_id)) return true; // Es opcional
            
            $sql = "SELECT COUNT(*) as total FROM App_empresa_sede 
                    WHERE SedeId = ? AND SedeIdSucursal = ? AND SedeEstado = 'Activo'";
            $stmt = $this->ejecutar_consulta_segura($sql, [$sede_id, $sucursal_id]);
            $resultado = $stmt->fetch();
            
            return $resultado['total'] > 0;
            
        } catch(Exception $e) {
            error_log("Error verificando sede: " . $e->getMessage());
            return false;
        }
    }
	
	/************ FUNCIÓN: actualizar_ultimo_acceso *********************************************************/
    protected function actualizar_ultimo_acceso($usuario_id){
        try {
            $fecha_actual = date("Y-m-d H:i:s");
            
            $sql = "UPDATE App_usuarios_usuario 
                    SET UsuarioUltimoAcceso = ?, 
                        UsuarioFechaActualizacion = ? 
                    WHERE UsuarioId = ?";
            
            $stmt = $this->ejecutar_consulta_segura($sql, [$fecha_actual, $fecha_actual, $usuario_id]);
            
            return $stmt->rowCount() > 0;
            
        } catch(Exception $e) {
            error_log("Error actualizando último acceso: " . $e->getMessage());
            return false;
        }
    }
	
	//===========================================================================================================
    // LISTAR USUARIOS CON PAGINACIÓN Y FILTROS
    // Función para obtener usuarios con filtros aplicados y paginación
    //===========================================================================================================
	/*protected function listar_usuarios_modelo($datos_busqueda, $pagina = 1, $registros_por_pagina = 10) {
		try {
			// Offset para la paginación
			$offset = ($pagina - 1) * $registros_por_pagina;

			// Definir campos donde buscar
			$campos_busqueda = [
				'u.UsuarioNombres',
				'u.UsuarioApellidos', 
				'u.UsuarioCodigo',
				'u.UsuarioEmail',
				'u.UsuarioDocumento',
				'u.UsuarioCargo',
				'u.UsuarioDepartamento'
			];

			// Generar búsqueda inteligente
			$parametros_busqueda = [];
			$where_busqueda = $this->generar_busqueda_inteligente(
				$datos_busqueda['shareusuario'] ?? '', 
				$campos_busqueda, 
				$parametros_busqueda
			);

			$estado_filtro = !empty($datos_busqueda['estadousuario']) ? '%' . ucfirst($datos_busqueda['estadousuario']) . '%' : '%';

			// 1. Consulta para contar total
			$sql_count = "SELECT COUNT(*) as total 
						  FROM App_usuarios_usuario u 
						  WHERE $where_busqueda
						  AND u.UsuarioEstado LIKE ?";

			$parametos_consulta_count = array_merge($parametros_busqueda, [$estado_filtro]);

			$stmt_count = $this->ejecutar_consulta_segura($sql_count, $parametos_consulta_count);
			$total_registros = $stmt_count->fetch()['total'];

			// 2. Consulta para obtener datos con JOINs a empresa y sucursal
			$sql_datos = "SELECT 
							u.UsuarioId,
							u.UsuarioCodigo,
							u.UsuarioDocumento,
							u.UsuarioTipoDocumento,
							u.UsuarioNombres,
							u.UsuarioApellidos,
							u.UsuarioEmail,
							u.UsuarioTelefono,
							u.UsuarioCargo,
							u.UsuarioDepartamento,
							u.UsuarioFechaRegistro,
							u.UsuarioUltimoAcceso,
							u.UsuarioEstado,
							u.UsuarioEmpresaId,
							u.UsuarioSucursalId,
							u.UsuarioSedeId,
							e.EmpresaNombre,
							e.EmpresaCodigo,
							s.SucursalNombre,
							s.SucursalCodigo,
							se.SedeNombre,
							se.SedeCodigo
						  FROM App_usuarios_usuario u 
							LEFT JOIN App_empresa_empresa e ON u.UsuarioEmpresaId = e.EmpresaId 
							LEFT JOIN App_empresa_sucursal s ON u.UsuarioSucursalId = s.SucursalId
							LEFT JOIN App_empresa_sede se ON u.UsuarioSedeId = se.SedeId
						  WHERE $where_busqueda
						  AND u.UsuarioEstado LIKE ?
						  ORDER BY u.UsuarioFechaRegistro DESC 
						  LIMIT ? OFFSET ?";

			$parametos_consulta_datos = array_merge($parametros_busqueda, [$estado_filtro, $registros_por_pagina, $offset]);

			$stmt_datos = $this->ejecutar_consulta_segura($sql_datos, $parametos_consulta_datos);
			$usuarios = $stmt_datos->fetchAll();

			// Calcular paginación
			$total_paginas = ceil($total_registros / $registros_por_pagina);

			return [
				'usuarios' => $usuarios,
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
			error_log("Error en listar_usuarios_modelo: " . $e->getMessage());
			return [
				'usuarios' => [],
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
	}*/
    
	
	protected function listar_usuarios_modelo($datos_busqueda, $pagina = 1, $registros_por_pagina = 10, $filtro_empresa = null) {
    
    $usuario_id = $_SESSION['UsuarioId'];
    $RolNivel = $this->determinar_rol_usuario();
    
    try {
        // Offset para la paginación
        $offset = ($pagina - 1) * $registros_por_pagina;

        // Definir campos donde buscar
        $campos_busqueda = [
            'u.UsuarioNombres',
            'u.UsuarioApellidos', 
            'u.UsuarioCodigo',
            'u.UsuarioEmail',
            'u.UsuarioDocumento',
            'u.UsuarioCargo',
            'u.UsuarioDepartamento'
        ];

        // Generar búsqueda inteligente
        $parametros_busqueda = [];
        $where_busqueda = $this->generar_busqueda_inteligente(
            $datos_busqueda['shareusuario'] ?? '', 
            $campos_busqueda, 
            $parametros_busqueda
        );

        $estado_filtro = !empty($datos_busqueda['estadousuario']) ? '%' . ucfirst($datos_busqueda['estadousuario']) . '%' : '%';

        // Construir filtro de empresa
        $where_empresa = "";
        $parametros_empresa = [];
        
        if ($filtro_empresa !== null) {
            $where_empresa = " AND u.UsuarioEmpresaId = ?";
            $parametros_empresa[] = $filtro_empresa;
        }

        // 1. Consulta para contar total CON FILTRO DE ROLES
        $sql_count = "SELECT COUNT(DISTINCT u.UsuarioId) as total 
                      FROM App_usuarios_usuario u 
                      LEFT JOIN App_usuarios_usuario_rol ur ON u.UsuarioId = ur.UsuarioRolIdUsuario
                      LEFT JOIN App_usuarios_rol rol ON ur.UsuarioRolIdRol = rol.RolId
                      WHERE $where_busqueda
                      AND u.UsuarioEstado LIKE ?
                      AND (u.UsuarioId = ? OR rol.RolNivel > ?)
                      $where_empresa";

        $parametros_consulta_count = array_merge($parametros_busqueda, [$estado_filtro, $usuario_id, $RolNivel], $parametros_empresa);

        $stmt_count = $this->ejecutar_consulta_segura($sql_count, $parametros_consulta_count);
        $total_registros = $stmt_count->fetch()['total'];

        // 2. Consulta para obtener datos CON FILTRO DE ROLES
        $sql_datos = "SELECT DISTINCT
                        u.UsuarioId,
                        u.UsuarioCodigo,
                        u.UsuarioDocumento,
                        u.UsuarioTipoDocumento,
                        u.UsuarioNombres,
                        u.UsuarioApellidos,
                        u.UsuarioEmail,
                        u.UsuarioTelefono,
                        u.UsuarioCargo,
                        u.UsuarioDepartamento,
                        u.UsuarioFechaRegistro,
                        u.UsuarioUltimoAcceso,
                        u.UsuarioEstado,
                        u.UsuarioEmpresaId,
                        u.UsuarioSucursalId,
                        u.UsuarioSedeId,
                        u.UsuarioIsSuperAdmin,
                        u.UsuarioIsSystemAdmin,
                        e.EmpresaNombre,
                        e.EmpresaCodigo,
                        s.SucursalNombre,
                        s.SucursalCodigo,
                        se.SedeNombre,
                        se.SedeCodigo
                      FROM App_usuarios_usuario u 
                        LEFT JOIN App_empresa_empresa e ON u.UsuarioEmpresaId = e.EmpresaId 
                        LEFT JOIN App_empresa_sucursal s ON u.UsuarioSucursalId = s.SucursalId
                        LEFT JOIN App_empresa_sede se ON u.UsuarioSedeId = se.SedeId
                        LEFT JOIN App_usuarios_usuario_rol ur ON u.UsuarioId = ur.UsuarioRolIdUsuario
                        LEFT JOIN App_usuarios_rol rol ON ur.UsuarioRolIdRol = rol.RolId
                      WHERE $where_busqueda
                      AND u.UsuarioEstado LIKE ?
                      AND (u.UsuarioId = ? OR rol.RolNivel > ?)
                      $where_empresa
                      ORDER BY u.UsuarioFechaRegistro DESC 
                      LIMIT ? OFFSET ?";

        $parametros_consulta_datos = array_merge($parametros_busqueda, [$estado_filtro, $usuario_id, $RolNivel], $parametros_empresa, [$registros_por_pagina, $offset]);

        $stmt_datos = $this->ejecutar_consulta_segura($sql_datos, $parametros_consulta_datos);
        $usuarios = $stmt_datos->fetchAll();

        // Calcular paginación
        $total_paginas = ceil($total_registros / $registros_por_pagina);

        return [
            'usuarios' => $usuarios,
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
        error_log("Error en listar_usuarios_modelo: " . $e->getMessage());
        return [
            'usuarios' => [],
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
    // OBTENER ESTADÍSTICAS DE USUARIOS
    // Función para obtener contadores para las tarjetas de estadísticas
    //===========================================================================================================
	/*protected function obtener_estadisticas_usuarios_modelo() {
        try {
            
			$parametros_activo = ['Activo'];
			$parametros_inactivo = ['Inactivo'];
			$parametros_bloqueado = ['Bloqueado'];
						
			// Total de usuarios
			$sql_total_usuarios = "SELECT COUNT(*) as total_usuarios FROM App_usuarios_usuario ";
			$stmt = $this->ejecutar_consulta_segura($sql_total_usuarios, []);
			$total_usuarios = $stmt->fetch();
			
			// Usuarios activos
			$sql_usuarios_activos = "SELECT COUNT(*) as usuarios_activos FROM App_usuarios_usuario WHERE UsuarioEstado = ? ";
			$stmt = $this->ejecutar_consulta_segura($sql_usuarios_activos, $parametros_activo);
			$usuarios_activos = $stmt->fetch();
			
			// Usuarios inactivos
			$sql_usuarios_inactivos = "SELECT COUNT(*) as usuarios_inactivos FROM App_usuarios_usuario WHERE UsuarioEstado = ? ";
			$stmt = $this->ejecutar_consulta_segura($sql_usuarios_inactivos, $parametros_inactivo);
			$usuarios_inactivos = $stmt->fetch();
			
			// Usuarios bloqueados
			$sql_usuarios_bloqueados = "SELECT COUNT(*) as usuarios_bloqueados FROM App_usuarios_usuario WHERE UsuarioEstado = ? ";
			$stmt = $this->ejecutar_consulta_segura($sql_usuarios_bloqueados, $parametros_bloqueado);
			$usuarios_bloqueados = $stmt->fetch();
			
						
			return [
                'total_usuarios' => $total_usuarios['total_usuarios'],
                'usuarios_activos' => $usuarios_activos['usuarios_activos'],
                'usuarios_inactivos' => $usuarios_inactivos['usuarios_inactivos'],
                'usuarios_bloqueados' => $usuarios_bloqueados['usuarios_bloqueados']
                
            ];
			
        } catch(Exception $e) {
            error_log("Error en obtener_estadisticas_usuarios_modelo: " . $e->getMessage());
            return [
                'total_usuarios' => 0,
                'usuarios_activos' => 0,
                'usuarios_inactivos' => 0,
                'usuarios_bloqueados' => 0
            ];
        }
    }*/
	
	protected function obtener_estadisticas_usuarios_modelo($filtro_empresa = null) {
    try {
        
        // NUEVO: Construir filtro de empresa
        $where_empresa = "";
        $parametros_empresa = [];
        
        if ($filtro_empresa !== null) {
            $where_empresa = " AND UsuarioEmpresaId = ?";
            $parametros_empresa[] = $filtro_empresa;
        }
        
        $parametros_activo = array_merge(['Activo'], $parametros_empresa);
        $parametros_inactivo = array_merge(['Inactivo'], $parametros_empresa);
        $parametros_bloqueado = array_merge(['Bloqueado'], $parametros_empresa);
                    
        // Total de usuarios CON FILTRO
        $sql_total_usuarios = "SELECT COUNT(*) as total_usuarios FROM App_usuarios_usuario WHERE 1=1 $where_empresa";
        $stmt = $this->ejecutar_consulta_segura($sql_total_usuarios, $parametros_empresa);
        $total_usuarios = $stmt->fetch();
        
        // Usuarios activos CON FILTRO
        $sql_usuarios_activos = "SELECT COUNT(*) as usuarios_activos FROM App_usuarios_usuario WHERE UsuarioEstado = ? $where_empresa";
        $stmt = $this->ejecutar_consulta_segura($sql_usuarios_activos, $parametros_activo);
        $usuarios_activos = $stmt->fetch();
        
        // Usuarios inactivos CON FILTRO
        $sql_usuarios_inactivos = "SELECT COUNT(*) as usuarios_inactivos FROM App_usuarios_usuario WHERE UsuarioEstado = ? $where_empresa";
        $stmt = $this->ejecutar_consulta_segura($sql_usuarios_inactivos, $parametros_inactivo);
        $usuarios_inactivos = $stmt->fetch();
        
        // Usuarios bloqueados CON FILTRO
        $sql_usuarios_bloqueados = "SELECT COUNT(*) as usuarios_bloqueados FROM App_usuarios_usuario WHERE UsuarioEstado = ? $where_empresa";
        $stmt = $this->ejecutar_consulta_segura($sql_usuarios_bloqueados, $parametros_bloqueado);
        $usuarios_bloqueados = $stmt->fetch();
        
                    
        return [
            'total_usuarios' => $total_usuarios['total_usuarios'],
            'usuarios_activos' => $usuarios_activos['usuarios_activos'],
            'usuarios_inactivos' => $usuarios_inactivos['usuarios_inactivos'],
            'usuarios_bloqueados' => $usuarios_bloqueados['usuarios_bloqueados']
        ];
        
    } catch(Exception $e) {
        error_log("Error en obtener_estadisticas_usuarios_modelo: " . $e->getMessage());
        return [
            'total_usuarios' => 0,
            'usuarios_activos' => 0,
            'usuarios_inactivos' => 0,
            'usuarios_bloqueados' => 0
        ];
    }
}
	//===========================================================================================================
    // FUNCION PARA ELIMINAR EMPRESA
    // Cambia el estado a 'Eliminado' en lugar de borrar físicamente
    //===========================================================================================================
	
	protected function eliminar_usuario_modelo($usuario_id) {
		try {
			$sql = "UPDATE App_usuarios_usuario 
					SET UsuarioEstado = ?, 
						UsuarioFechaActualizacion = ? 
					WHERE UsuarioId = ? 
					AND UsuarioEstado != 'Eliminado'";

			$parametros = [
				'Eliminado',
				date("Y-m-d H:i:s"),
				$usuario_id
			];

			$stmt = $this->ejecutar_consulta_segura($sql, $parametros);

			// Verificar que se actualizó al menos una fila
			return $stmt->rowCount() > 0;

		} catch(Exception $e) {
			error_log("Error eliminando usuario: " . $e->getMessage());
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
    // OBTENER USUARIO POR ID
    // verifica si la empresa si existe
    //===========================================================================================================
	protected function obtener_usuario_por_id($usuario_id){
		try {
			$sql = "SELECT UsuarioId , UsuarioCodigo , UsuarioDocumento , UsuarioTipoDocumento, 
						   UsuarioNombres, UsuarioApellidos, UsuarioEmail, 
						   UsuarioTelefono, UsuarioFoto, UsuarioCargo, UsuarioDepartamento, UsuarioFechaRegistro, 
						   UsuarioFechaActualizacion, UsuarioUltimoAcceso, UsuarioIntentosLogin, UsuarioFechaBloqueo, UsuarioPasswordCambio, UsuarioPasswordExpira, UsuarioEstado, UsuarioEmpresaId, UsuarioSucursalId, UsuarioSedeId 
					FROM  App_usuarios_usuario 
					WHERE UsuarioId  = ? 
					AND UsuarioEstado != 'Eliminado'";

			$stmt = $this->ejecutar_consulta_segura($sql, [$usuario_id]);
			return $stmt->fetch();

		} catch(Exception $e) {
			error_log("Error obteniendo usuario por ID: " . $e->getMessage());
			return false;
		}
	}
	
/*-**--*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-* funciones para actualizar usuarios*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*-*/
	
	//===========================================================================================================
    // OBTENER USUARIO COMPLETO POR ID
    // Función para obtener todos los datos de un usuario específica
    //===========================================================================================================
	
	protected function obtener_usuario_completo_modelo($usuario_id){
		try {
			$sql = "SELECT UsuarioId , UsuarioCodigo , UsuarioDocumento , UsuarioTipoDocumento, 
						   UsuarioNombres, UsuarioApellidos, UsuarioEmail, 
						   UsuarioTelefono, UsuarioFoto, UsuarioCargo, UsuarioDepartamento, UsuarioFechaRegistro, 
						   UsuarioFechaActualizacion, UsuarioUltimoAcceso, UsuarioIntentosLogin, UsuarioFechaBloqueo, UsuarioPasswordCambio, UsuarioPasswordExpira, UsuarioEstado, UsuarioEmpresaId, UsuarioSucursalId, UsuarioSedeId 
					FROM  App_usuarios_usuario 
					WHERE UsuarioId  = ? ";
			
			$stmt = $this->ejecutar_consulta_segura($sql, [$usuario_id]);
			$usuario = $stmt->fetch();

			return $usuario;

		} catch(Exception $e) {
			error_log("Error obteniendo usuario completo por ID: " . $e->getMessage());
			return false;
		}
	}
	
	//===========================================================================================================
    // VERIFICAR DOCUMENTO DUPLICADO PARA ACTUALIZACIÓN
    // Excluye la usuario actual de la verificación
    //===========================================================================================================
	
	protected function verificar_documento_duplicado_actualizar($documento, $usuario_id_actual){
		try {
			$sql = "SELECT COUNT(*) as total 
					FROM App_usuarios_usuario 
					WHERE UsuarioDocumento = ? 
					AND UsuarioId != ? ";
			
			$stmt = $this->ejecutar_consulta_segura($sql, [$documento, $usuario_id_actual]);
			$resultado = $stmt->fetch();

			return $resultado['total'] > 0;

		} catch(Exception $e) {
			error_log("Error verificando documento duplicado para actualización: " . $e->getMessage());
			return false;
		}
	}

	//===========================================================================================================
    // VERIFICAR EMAIL DUPLICADO PARA ACTUALIZACIÓN
    // Excluye la empresa actual de la verificación
    //===========================================================================================================
	
	protected function verificar_email_duplicado_actualizar($email, $usuario_id_actual){
		try {
			$sql = "SELECT COUNT(*) as total 
					FROM App_usuarios_usuario 
					WHERE UsuarioEmail = ? 
					AND UsuarioId != ? ";
			
			$stmt = $this->ejecutar_consulta_segura($sql, [$email, $usuario_id_actual]);
			$resultado = $stmt->fetch();

			return $resultado['total'] > 0;

		} catch(Exception $e) {
			error_log("Error verificando email duplicado para actualización: " . $e->getMessage());
			return false;
		}
	}
	
	//===========================================================================================================
    // ACTUALIZAR USUARIO
    // Función para actualizar los datos de una empresa existente
    //===========================================================================================================
	
	protected function actualizar_usuario_modelo($datos_finales){	
		try {
			$sql = "UPDATE App_usuarios_usuario 
					SET UsuarioDocumento = ?, 
						UsuarioTipoDocumento = ?, 
						UsuarioNombres = ?, 
						UsuarioApellidos = ?, 
						UsuarioEmail = ?, 
						UsuarioTelefono = ?, 
						UsuarioCargo = ?, 
						UsuarioDepartamento = ?, 
						UsuarioEmpresaId = ?, 
						UsuarioSucursalId = ?, 
						UsuarioSedeId = ?, 
						UsuarioFechaActualizacion = ? 
					WHERE UsuarioId = ? 
					AND UsuarioEstado != 'Eliminado'";

			$parametros = [
				$datos_finales['UsuarioDocumento'],
				$datos_finales['UsuarioTipoDocumento'],
				$datos_finales['UsuarioNombres'],
				$datos_finales['UsuarioApellidos'],
				$datos_finales['UsuarioEmail'],
				$datos_finales['UsuarioTelefono'],
				$datos_finales['UsuarioCargo'],
				$datos_finales['UsuarioDepartamento'],
				$datos_finales['UsuarioEmpresaId'],
				$datos_finales['UsuarioSucursalId'],
				$datos_finales['UsuarioSedeId'],
				$datos_finales['UsuarioFechaActualizacion'],
				$datos_finales['UsuarioId']
			];

			$stmt = $this->ejecutar_consulta_segura($sql, $parametros);

			// Verificar que se actualizó al menos una fila
			return $stmt->rowCount() > 0;

		} catch(Exception $e) {
			error_log("Error actualizando usuario: " . $e->getMessage());
			return false;
		}
	}
	
	
		
	//===========================================================================================================
    // CAMBIAR ESTADO DE USUARIO
    // Función para actualizar el estado de una empresa (permite reactivar eliminadas)
    //===========================================================================================================
	
	protected function cambiar_estado_usuario_modelo($usuario_id, $nuevo_estado, $motivo_cambio){
		try {
			$sql = "UPDATE App_usuarios_usuario
					SET UsuarioEstado = ?, 
						UsuarioFechaActualizacion = ? 
					WHERE UsuarioId = ?";

			$parametros = [
				$nuevo_estado,
				date("Y-m-d H:i:s"),
				$usuario_id
			];

			$stmt = $this->ejecutar_consulta_segura($sql, $parametros);

			// Verificar que se actualizó al menos una fila
			return $stmt->rowCount() > 0;

		} catch(Exception $e) {
			error_log("Error cambiando estado de usuairo: " . $e->getMessage());
			return false;
		}
	}
	
	
	//===========================================================================================================
	// FUNCIONES PARA GESTIÓN DE ROLES
	//===========================================================================================================

	//===========================================================================================================
	// CREAR NUEVO ROL EN LA BASE DE DATOS
	// Función para insertar un nuevo rol usando consultas seguras
	//===========================================================================================================
	protected function crear_rol_modelo($datos_rol){
		try {
			// Preparar la consulta SQL segura
			$sql = "INSERT INTO App_usuarios_rol 
					(RolCodigo, RolNombre, RolDescripcion, RolNivel, RolFechaCreacion, RolEstado) 
					VALUES (?, ?, ?, ?, ?, ?)";

			// Preparar los parámetros en el orden correcto
			$parametros = [
				$datos_rol['RolCodigo'],
				$datos_rol['RolNombre'],
				$datos_rol['RolDescripcion'],
				$datos_rol['RolNivel'],
				$datos_rol['RolFechaCreacion'],
				$datos_rol['RolEstado']
			];

			/*-*-*-*-*-* Ejecutar consulta segura *-*-*-*-*-*/
			$stmt = $this->ejecutar_consulta_segura($sql, $parametros);

			/*-*-*-*-*-* Verificar si se insertó correctamente *-*-*-*-*-*/
			return $stmt->rowCount() > 0;

		} catch(Exception $e) {
			/*-*-*-*-*-* Guardar error en log *-*-*-*-*-*/
			error_log("Error creando rol: " . $e->getMessage());
			return false;
		}
	}

	//===========================================================================================================
	// VERIFICAR NOMBRE DE ROL DUPLICADO
	// Función para verificar si ya existe un rol con el mismo nombre
	//===========================================================================================================
	protected function verificar_nombre_rol_duplicado($nombre_rol){
		try {
			$sql = "SELECT COUNT(*) as total FROM App_usuarios_rol WHERE RolNombre = ?";
			$stmt = $this->ejecutar_consulta_segura($sql, [$nombre_rol]);
			$resultado = $stmt->fetch();

			return $resultado['total'] > 0;

		} catch(Exception $e) {
			error_log("Error verificando nombre de rol: " . $e->getMessage());
			return false;
		}
	}

	//===========================================================================================================
	// VERIFICAR NIVEL DE ROL DUPLICADO
	// Función para verificar si ya existe un rol con el mismo nivel
	//===========================================================================================================
	protected function verificar_nivel_rol_duplicado($nivel_rol){
		try {
			$sql = "SELECT COUNT(*) as total FROM App_usuarios_rol WHERE RolNivel = ?";
			$stmt = $this->ejecutar_consulta_segura($sql, [$nivel_rol]);
			$resultado = $stmt->fetch();

			return $resultado['total'] > 0;

		} catch(Exception $e) {
			error_log("Error verificando nivel de rol: " . $e->getMessage());
			return false;
		}
	}

	//===========================================================================================================
	// VERIFICAR SI CÓDIGO DE ROL EXISTE
	// Función para verificar si ya existe un rol con el mismo código
	//===========================================================================================================
	protected function verificar_codigo_rol_existe($codigo_rol){
		try {
			$sql = "SELECT COUNT(*) as total FROM App_usuarios_rol WHERE RolCodigo = ?";
			$stmt = $this->ejecutar_consulta_segura($sql, [$codigo_rol]);
			$resultado = $stmt->fetch();

			return $resultado['total'] > 0;

		} catch(Exception $e) {
			error_log("Error verificando código de rol: " . $e->getMessage());
			return false;
		}
	}

	//===========================================================================================================
	// OBTENER TODOS LOS ROLES
	// Función para obtener la lista de roles con paginación y filtros
	//===========================================================================================================
	protected function listar_roles_modelo($datos_busqueda = [], $pagina = 1, $registros_por_pagina = 10){
		try {
			// Offset para la paginación
			$offset = ($pagina - 1) * $registros_por_pagina;

			// Definir campos donde buscar
			$campos_busqueda = [
				'RolNombre',
				'RolCodigo', 
				'RolDescripcion'
			];

			// Generar búsqueda inteligente
			$parametros_busqueda = [];
			$where_busqueda = $this->generar_busqueda_inteligente(
				$datos_busqueda['buscarRol'] ?? '', 
				$campos_busqueda, 
				$parametros_busqueda
			);

			$estado_filtro = !empty($datos_busqueda['filtroEstadoRol']) ? $datos_busqueda['filtroEstadoRol'] : '%';
			if ($estado_filtro !== '%') {
				$estado_filtro = '%' . $estado_filtro . '%';
			}

			$nivel_filtro = !empty($datos_busqueda['filtroNivelRol']) ? $datos_busqueda['filtroNivelRol'] : '';

			// Construir filtro de nivel
			$where_nivel = "";
			$parametros_nivel = [];
			if (!empty($nivel_filtro)) {
				$where_nivel = " AND RolNivel = ?";
				$parametros_nivel[] = $nivel_filtro;
			}

			// 1. Consulta para contar total
			$sql_count = "SELECT COUNT(*) as total 
						  FROM App_usuarios_rol 
						  WHERE $where_busqueda
						  AND RolEstado LIKE ?
						  $where_nivel";

			$parametros_consulta_count = array_merge($parametros_busqueda, [$estado_filtro], $parametros_nivel);

			$stmt_count = $this->ejecutar_consulta_segura($sql_count, $parametros_consulta_count);
			$total_registros = $stmt_count->fetch()['total'];

			// 2. Consulta para obtener datos
			$sql_datos = "SELECT RolId, RolCodigo, RolNombre, RolDescripcion, RolNivel, 
								 RolFechaCreacion, RolEstado,
								 (SELECT COUNT(*) FROM App_usuarios_usuario_rol 
								  WHERE UsuarioRolIdRol = App_usuarios_rol.RolId 
								  AND UsuarioRolEstado = 'Activo') as UsuariosAsignados,
								 (SELECT COUNT(*) FROM App_usuarios_rol_permiso 
								  WHERE RolPermisoIdRol = App_usuarios_rol.RolId 
								  AND RolPermisoEstado = 'Activo') as PermisosAsignados
						  FROM App_usuarios_rol 
						  WHERE $where_busqueda
						  AND RolEstado LIKE ?
						  $where_nivel
						  ORDER BY RolNivel ASC, RolFechaCreacion DESC 
						  LIMIT ? OFFSET ?";

			$parametros_consulta_datos = array_merge($parametros_busqueda, [$estado_filtro], $parametros_nivel, [$registros_por_pagina, $offset]);

			$stmt_datos = $this->ejecutar_consulta_segura($sql_datos, $parametros_consulta_datos);
			$roles = $stmt_datos->fetchAll();

			// Calcular paginación
			$total_paginas = ceil($total_registros / $registros_por_pagina);

			return [
				'roles' => $roles,
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
			error_log("Error en listar_roles_modelo: " . $e->getMessage());
			return [
				'roles' => [],
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
	// OBTENER ESTADÍSTICAS DE ROLES
	// Función para obtener contadores para las tarjetas de estadísticas
	//===========================================================================================================
	protected function obtener_estadisticas_roles_modelo() {
		try {
			// Total de roles
			$sql_total_roles = "SELECT COUNT(*) as total_roles FROM App_usuarios_rol";
			$stmt = $this->ejecutar_consulta_segura($sql_total_roles, []);
			$total_roles = $stmt->fetch();

			// Roles activos
			$sql_roles_activos = "SELECT COUNT(*) as roles_activos FROM App_usuarios_rol WHERE RolEstado = 'Activo'";
			$stmt = $this->ejecutar_consulta_segura($sql_roles_activos, []);
			$roles_activos = $stmt->fetch();

			// Usuarios con roles asignados
			$sql_usuarios_con_roles = "SELECT COUNT(DISTINCT UsuarioRolIdUsuario) as usuarios_con_roles 
									   FROM App_usuarios_usuario_rol 
									   WHERE UsuarioRolEstado = 'Activo'";
			$stmt = $this->ejecutar_consulta_segura($sql_usuarios_con_roles, []);
			$usuarios_con_roles = $stmt->fetch();

			// Total de permisos asignados a roles
			$sql_permisos_asignados = "SELECT COUNT(*) as permisos_asignados 
									   FROM App_usuarios_rol_permiso 
									   WHERE RolPermisoEstado = 'Activo'";
			$stmt = $this->ejecutar_consulta_segura($sql_permisos_asignados, []);
			$permisos_asignados = $stmt->fetch();

			return [
				'total_roles' => $total_roles['total_roles'],
				'roles_activos' => $roles_activos['roles_activos'],
				'usuarios_con_roles' => $usuarios_con_roles['usuarios_con_roles'],
				'permisos_asignados' => $permisos_asignados['permisos_asignados']
			];

		} catch(Exception $e) {
			error_log("Error en obtener_estadisticas_roles_modelo: " . $e->getMessage());
			return [
				'total_roles' => 0,
				'roles_activos' => 0,
				'usuarios_con_roles' => 0,
				'permisos_asignados' => 0
			];
		}
	}

	//===========================================================================================================
	// OBTENER ROL POR ID
	// Función para obtener un rol específico por su ID
	//===========================================================================================================
	protected function obtener_rol_por_id($rol_id){
		try {
			$sql = "SELECT RolId, RolCodigo, RolNombre, RolDescripcion, RolNivel, 
						   RolFechaCreacion, RolEstado,
						   (SELECT COUNT(*) FROM App_usuarios_usuario_rol 
							WHERE UsuarioRolIdRol = App_usuarios_rol.RolId 
							AND UsuarioRolEstado = 'Activo') as UsuariosAsignados
					FROM App_usuarios_rol 
					WHERE RolId = ?";

			$stmt = $this->ejecutar_consulta_segura($sql, [$rol_id]);
			return $stmt->fetch();

		} catch(Exception $e) {
			error_log("Error obteniendo rol por ID: " . $e->getMessage());
			return false;
		}
	}

	//===========================================================================================================
	// ACTUALIZAR ROL
	// Función para actualizar los datos de un rol existente
	//===========================================================================================================
	protected function actualizar_rol_modelo($datos_rol){
		try {
			$sql = "UPDATE App_usuarios_rol 
					SET RolNombre = ?, 
						RolDescripcion = ?, 
						RolNivel = ?
					WHERE RolId = ?";

			$parametros = [
				$datos_rol['RolNombre'],
				$datos_rol['RolDescripcion'],
				$datos_rol['RolNivel'],
				$datos_rol['RolId']
			];

			$stmt = $this->ejecutar_consulta_segura($sql, $parametros);

			// Verificar que se actualizó al menos una fila
			return $stmt->rowCount() > 0;

		} catch(Exception $e) {
			error_log("Error actualizando rol: " . $e->getMessage());
			return false;
		}
	}

	//===========================================================================================================
	// ELIMINAR ROL (SOFT DELETE)
	// Función para cambiar el estado del rol a 'Inactivo'
	//===========================================================================================================
	protected function eliminar_rol_modelo($rol_id) {
		try {
			$sql = "UPDATE App_usuarios_rol 
					SET RolEstado = 'Inactivo'
					WHERE RolId = ? 
					AND RolEstado = 'Activo'";

			$stmt = $this->ejecutar_consulta_segura($sql, [$rol_id]);

			// Verificar que se actualizó al menos una fila
			return $stmt->rowCount() > 0;

		} catch(Exception $e) {
			error_log("Error eliminando rol: " . $e->getMessage());
			return false;
		}
	}

	//===========================================================================================================
	// CAMBIAR ESTADO DE ROL
	// Función para cambiar el estado de un rol (Activo/Inactivo)
	//===========================================================================================================
	protected function cambiar_estado_rol_modelo($rol_id, $nuevo_estado){
		try {
			$sql = "UPDATE App_usuarios_rol
					SET RolEstado = ?
					WHERE RolId = ?";

			$parametros = [
				$nuevo_estado,
				$rol_id
			];

			$stmt = $this->ejecutar_consulta_segura($sql, $parametros);

			// Verificar que se actualizó al menos una fila
			return $stmt->rowCount() > 0;

		} catch(Exception $e) {
			error_log("Error cambiando estado de rol: " . $e->getMessage());
			return false;
		}
	}
}
?>