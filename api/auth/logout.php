<?php
define('ACCESS_ALLOWED', true);
header('Content-Type: application/json; charset=utf-8');

require_once __DIR__ . '/../../utils/session.php';

cerrarSesionUsuario();

echo json_encode([
    'status' => 'success',
    'message' => 'Sesión cerrada correctamente.',
    'redirect' => '/bloc-c'
]);
exit;
