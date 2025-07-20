<?php
/**
 * CONTROLADOR DE USUARIOS
 * Este archivo maneja toda la lógica de modulo de usuarios
 * Es el intermediario entre las vistas y el modelo
 */

// Verificamos si es una petición AJAX para incluir el archivo correcto
if($peticionAjax){
    require_once "../models/App_configuracionModel.php";
}else{
    require_once "./models/App_configuracionModel.php";
}
//
class configuracionController extends configuracionModel{
	
	/**
     * FUNCIÓN PARA VER LA ESTRUCTURA DE CUALQUIER TABLA (Para debug)
     */
    public function mostrar_estructura_tabla($nombre_tabla = 'App_empresa_empresa'){
        $estructura = SecureModel::obtener_estructura_tabla($nombre_tabla);
        
        echo "<h3>Estructura de la tabla: $nombre_tabla</h3>";
        echo "<table border='1' style='border-collapse: collapse; width: 100%;'>";
        echo "<tr>
                <th>Campo</th>
                <th>Tipo</th>
                <th>Max Caracteres</th>
                <th>Permite NULL</th>
                <th>Valor por Defecto</th>
                <th>Clave</th>
              </tr>";
        
        foreach($estructura as $campo){
		 	$es_requerido = ($campo['IS_NULLABLE'] == 'NO' && $campo['COLUMN_DEFAULT'] === null);
			$clase = $es_requerido ? 'requerido' : 'opcional';
            echo "<tr class=".$clase.">";
            echo "<td>" . $campo['COLUMN_NAME'] . "</td>";
            echo "<td>" . $campo['DATA_TYPE'] . "</td>";
            echo "<td>" . ($campo['CHARACTER_MAXIMUM_LENGTH'] ?: 'N/A') . "</td>";
            echo "<td>" . $campo['IS_NULLABLE'] . "</td>";
            echo "<td>" . ($campo['COLUMN_DEFAULT'] ?: 'NULL') . "</td>";
            echo "<td>" . $campo['COLUMN_KEY'] . "</td>";
            echo "</tr>";
        }
        echo "</table>";
        
        // Mostrar reglas generadas automáticamente
        $reglas = SecureModel::generar_reglas_validacion($nombre_tabla, ['empresaid', 'empresacodigo']);
        echo "<h4>Reglas de validación generadas automáticamente:</h4>";
        echo "<pre>" . print_r($reglas, true) . "</pre>";
    }
	
}