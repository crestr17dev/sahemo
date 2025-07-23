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

class rolesModel extends SecureModel {

}
	
?>