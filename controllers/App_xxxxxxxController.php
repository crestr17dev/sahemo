<?php
/**
 * CONTROLADOR DE USUARIOS
 * Este archivo maneja toda la lógica de modulo de usuarios
 * Es el intermediario entre las vistas y el modelo
 */

// Verificamos si es una petición AJAX para incluir el archivo correcto
if($peticionAjax){
    require_once "../models/App_xxxxxxModel.php";
}else{
    require_once "./models/App_xxxxxxModel.php";
}

class xxxxxxController extends xxxxxxModel {
	

	
}