<?php
define('ACCESS_ALLOWED', true);
header('Content-Type: application/json; charset=utf-8');

require_once __DIR__ . '/../../utils/session.php';
require_once __DIR__ . '/../../config/supabase.php';

if (!verificarAutenticacion()) {
    echo json_encode([
        'status' => 'error',
    ]);
    exit;
}

$token = obtenerAccessToken();
$user = obtenerSesion();
$userId = $user['id'];

$supabase = new SupabaseClient();
$tareas = $supabase->select('tasks', '*', "user_id=eq.{$userId}", $token);

echo json_encode([
    'status' => 'success',
    'data' => $tareas
]);
exit;