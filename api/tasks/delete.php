<?php
define('ACCESS_ALLOWED', true);
header('Content-Type: application/json; charset=utf-8');

require_once __DIR__ . '/../../utils/session.php';
require_once __DIR__ . '/../../config/supabase.php';

// Verificar autenticación
if (!verificarAutenticacion()) {
    echo json_encode([
        'status' => 'error',
        'message' => 'No autenticado'
    ]);
    exit;
}

// Solo permitir POST/DELETE
if ($_SERVER['REQUEST_METHOD'] !== 'POST' && $_SERVER['REQUEST_METHOD'] !== 'DELETE') {
    echo json_encode([
        'status' => 'error',
        'message' => 'Método no permitido'
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

try {
    $supabase = new SupabaseClient();
    $resultado = $supabase->delete('tasks', "id=eq.{$taskId}&user_id=eq.{$userId}", $token);

    // Verificar si se eliminó
    if ($resultado === false || $resultado === null) {
        echo json_encode([
            'status' => 'error',
            'message' => 'No se pudo eliminar la tarea. Verifica que existe y te pertenece.'
        ]);
        exit;
    }

    echo json_encode([
        'status' => 'success',
        'message' => 'Tarea eliminada exitosamente'
    ]);
    exit;

} catch (Exception $e) {
    error_log("Error al eliminar tarea: " . $e->getMessage());
    echo json_encode([
        'status' => 'error',
        'message' => 'Error al eliminar la tarea'
    ]);
    exit;
}