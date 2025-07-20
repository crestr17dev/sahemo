<?php
//global $peticionAjax;
// LISTA BLANCA DE DOMINIOS PERMITIDOS (seguridad)
$dominiosPermitidos = [
    'vipvetpro.com',
    'www.vipvetpro.com',
    'inventario.tdea.edu.co',
    'www.inventario.tdea.edu.co',
	'sahemo.com',
	'www.sahemo.com',
    'localhost' // Para desarrollo local
];

// Obtener dominio actual de forma segura
$url = isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : '';

// VALIDACIÓN CRÍTICA: Solo dominios autorizados
if (!in_array($url, $dominiosPermitidos)) {
    // Registrar intento de acceso no autorizado
    error_log("Acceso denegado desde dominio no autorizado: " . $url);
    die('Acceso denegado');
}

// Cargar configuración específica del dominio

if($peticionAjax){
    if($url == "www.vipvetpro.com" || $url == "vipvetpro.com"){
        require_once "../core/confiDominios/confiVipVetPro.php";
    }
    
    if($url == "www.inventario.tdea.edu.co" || $url == "inventario.tdea.edu.co"){
        require_once "../core/confiDominios/confiInventarioTdea.php";
    }
    if($url == "www.sahemo.com" || $url == "sahemo.com"){
        require_once "../core/confiDominios/confisahemo.php";
    }
    if($url == "localhost"){
        require_once "../core/confiDominios/confiVipVetPro.php";
    }
} else {
    if($url == "www.vipvetpro.com" || $url == "vipvetpro.com"){
        require_once "./core/confiDominios/confiVipVetPro.php";
    }
    
    if($url == "www.inventario.tdea.edu.co" || $url == "inventario.tdea.edu.co"){
        require_once "./core/confiDominios/confiInventarioTdea.php";
    }
	if($url == "www.sahemo.com" || $url == "sahemo.com"){
        require_once "./core/confiDominios/confisahemo.php";
    }
    
    if($url == "localhost"){
        require_once "./core/confiDominios/confiVipVetPro.php";
    }
}

?>