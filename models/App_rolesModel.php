<?php
/**
 * MODELO DE ROLES 
 * Implementación paso a paso empezando ÚNICAMENTE con registro de roles
 */

// Verificamos si es una petición AJAX para incluir el archivo correcto
if($peticionAjax){
    require_once "../core/SecureModel.php";
}else{
    require_once "./core/SecureModel.php";
}

class rolesModel extends SecureModel {

	//===========================================================================================================
    // REGISTRAR NUEVO ROL EN LA BASE DE DATOS
    // Esta función inserta el nuevo rol usando consultas seguras
    //===========================================================================================================
    protected function registrar_rol_modelo($datos){
		try {
			// Preparar la consulta SQL segura
			$sql = "INSERT INTO App_usuarios_rol 
					(RolCodigo, RolNombre, RolDescripcion, RolNivel, RolFechaCreacion, RolEstado) 
					VALUES (?, ?, ?, ?, ?, ?)";

			// Preparar los parámetros en el orden correcto
			$parametros = [
				$datos['RolCodigo'],
				$datos['RolNombre'],
				$datos['RolDescripcion'],
				$datos['RolNivel'],
				$datos['RolFechaCreacion'],
				'Activo' // Estado por defecto
			];
			
			/*-*-*-*-*-* ejecuto consulta segura *-*-*-*-*-*/
			$stmt = $this->ejecutar_consulta_segura($sql, $parametros);

			/*-*-*-*-*-* Verificar si se insertó correctamente *-*-*-*-*-*/
			return $stmt->rowCount() > 0;

		} catch(Exception $e) {
			/*-*-*-*-*-* Guardar error en log *-*-*-*-*-*/
			error_log("Error registrando rol: " . $e->getMessage());
			return false;
		}
	}

	//===========================================================================================================
    // VERIFICAR NOMBRE DE ROL DUPLICADO
    // Función para verificar si ya existe un rol con el mismo nombre
    //===========================================================================================================
    protected function verificar_nombre_rol_duplicado($nombre_rol){
        try {
            $sql = "SELECT COUNT(*) as total 
                    FROM App_usuarios_rol 
                    WHERE RolCodigo = ?";
            
            $stmt = $this->ejecutar_consulta_segura($sql, [$nombre_rol]);
            $resultado = $stmt->fetch();
            
            return $resultado['total'] > 0;
            
        } catch(Exception $e) {
            error_log("Error verificando nombre de rol duplicado: " . $e->getMessage());
            return false;
        }
    }

	//===========================================================================================================
    // GENERAR CÓDIGO ÚNICO PARA ROL
    // Función para generar un código único basado en el nombre del rol
    //===========================================================================================================
    protected function generar_codigo_rol($nombre_rol) {
        try {
            // Limpiar y convertir nombre a código
            $codigo_base = strtoupper(preg_replace('/[^a-zA-Z0-9]/', '_', $nombre_rol));
            $codigo_base = preg_replace('/_+/', '_', $codigo_base); // Eliminar guiones múltiples
            $codigo_base = trim($codigo_base, '_'); // Eliminar guiones al inicio y final
            
            // Limitar longitud máxima
            if (strlen($codigo_base) > 45) {
                $codigo_base = substr($codigo_base, 0, 45);
            }
            
            return $codigo_base; 
            
        } catch(Exception $e) {
            error_log("Error generando código de rol: " . $e->getMessage());
            return 'ROL_' . time(); // Código fallback
        }
    }
}

?>