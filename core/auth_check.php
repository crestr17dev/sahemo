<?php
/**
 * VERIFICACIÓN SIMPLE DE AUTENTICACIÓN
 * Incluir al inicio de páginas que requieren login
 */

// Verificar si la sesión ya está activa
if (session_status() === PHP_SESSION_NONE) {
    session_start(['name' => SESION]);
}

// Verificar si el usuario está logueado
if(!isset($_SESSION['sesionactiva']) || $_SESSION['sesionactiva'] !== true) {
    // Si es petición AJAX
    if(isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] === 'XMLHttpRequest') {
        header('Content-Type: application/json; charset=UTF-8');
        http_response_code(401);
        echo json_encode([
            "success" => false,
            "message" => "Sesión expirada. Recarga la página.",
            "redirect_required" => true
        ], JSON_UNESCAPED_UNICODE);
        exit;
    } else {
        // Redirección normal
        header("Location: " . SERVERURL);
        exit;
    }
}

// Variables del usuario disponibles después de incluir este archivo
$usuario_logueado = [
    'id' => $_SESSION['UsuarioId'],
    'codigo' => $_SESSION['CodigoUsuario'],
    'nombre' => $_SESSION['UsuarioUsuario'],
    'email' => $_SESSION['UsuarioEmail'],
    'cargo' => $_SESSION['UsuarioCargo']
];
?>