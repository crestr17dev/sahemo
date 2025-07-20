<?php
/**
 * MODELO DE USUARIOS
 * Aquí van todas las funciones para manejar usuarios en la base de datos
 * Este modelo hereda de SecureModel para tener todas las funciones de seguridad
 */

// Verificamos si es una petición AJAX para incluir el archivo correcto
if($peticionAjax){
    require_once "../core/SecureModel.php";
}else{
    require_once "./core/SecureModel.php";
}

class configuracionModel extends SecureModel {
  
}