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

// Leer el cuerpo JSON de la petición
$input = json_decode(file_get_contents('php://input'), true);

// Validar ID
if (empty($input['id'])) {
    echo json_encode([
        'status' => 'error',
        'message' => 'ID de tarea requerido'
    ]);
    exit;
}

$taskId = htmlspecialchars(trim($input['id']), ENT_QUOTES, 'UTF-8');

$supabase = new SupabaseClient();
$tarea = $supabase->select('tasks', 'id,title,description', "id=eq.{$taskId}&user_id=eq.{$userId}", $token);

echo json_encode([
    'status' => 'success',
    'data' => $tarea[0]
]);
exit;