<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Guardar datos del usuario en sesión (después de login)
function iniciarSesionUsuario($userData, $token)
{
    // Guardamos el array completo de usuario y el token
    $_SESSION['user'] = $userData;
    $_SESSION['access_token'] = $token;
}

// Obtener datos del usuario actual
function obtenerSesion()
{
    return $_SESSION['user'] ?? null;
}

// Obtener token de autenticación
function obtenerAccessToken()
{
    return $_SESSION['access_token'] ?? null;
}

// Cerrar sesión (logout)
function cerrarSesionUsuario()
{
    $_SESSION = [];
    session_destroy();
}

// Verificar si el usuario está autenticado
function verificarAutenticacion()
{
    $userId = obtenerSesion();
    $token = obtenerAccessToken();
    return isset($userId) && isset($token);
}

// Proteger páginas (redirigir si no está logueado)
function protegerRuta()
{
    if (!verificarAutenticacion()) {
        header('Location: home');
        exit();
    }
}

// Redirigir si YA está autenticado (para login/register)
function redirigirSiAutenticado()
{
    if (verificarAutenticacion()) {
        header('Location: dashboard');
        exit();
    }
}