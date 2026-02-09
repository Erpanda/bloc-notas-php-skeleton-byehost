<?php
// Archivo index.php - Redirección principal
session_start();

// Si ya está logueado, redirigir al dashboard unificado
if (!isset($_SESSION['user_id'])) {
    header('Location: home');
    exit();
}

// Si no está logueado, redirigir a la home
header('Location: dashboard');
exit();