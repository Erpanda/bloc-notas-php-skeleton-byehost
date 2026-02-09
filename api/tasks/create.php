<?php
define('ACCESS_ALLOWED', true);
header('Content-Type: application/json; charset=utf-8');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode([
        'status' => 'error',
        'message' => 'Método no permitido.'
    ]);
    exit;
}

require_once __DIR__ . '/../../utils/session.php';
require_once __DIR__ . '/../../config/supabase.php';

$token = obtenerAccessToken();
$user = obtenerSesion();
$userId = $user['id'];

// Leer JSON del cuerpo de la petición
$input = json_decode(file_get_contents('php://input'), true);

$titulo = $input['title'] ?? '';
$descripcion = $input['description'] ?? '';
$fechaLimite = $input['due_date'] ?? '';

if (empty($titulo)) {
    echo json_encode([
        'status' => 'error',
        'message' => 'El título es obligatorio'
    ]);
    exit;
}

$supabase = new SupabaseClient();

// Crear tarea en Supabase
$nuevaTarea = [
    'user_id' => $userId,
    'title' => $titulo,
    'description' => $descripcion,
    'completed' => false,
    'due_date' => !empty($fechaLimite) ? $fechaLimite : null
];

$response = $supabase->insert('tasks', [$nuevaTarea], $token);

if (isset($response['error'])) {
    echo json_encode([
        'status' => 'error',
        'message' => 'Error al crear la tarea: ' . $response['error']
    ]);
    exit;
} else {
    echo json_encode([
        'status' => 'success',
        'message' => 'Tarea creada exitosamente'
    ]);
    exit;
}

