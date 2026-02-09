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

require_once __DIR__ . '/../../config/supabase.php';

$nombre = $_POST['nombre'] ?? '';
$apellido = $_POST['apellido'] ?? '';
$email = $_POST['email'] ?? '';
$password = $_POST['password'] ?? '';
$confirmPassword = $_POST['confirmPassword'] ?? '';

if (!$nombre || !$apellido || !$email || !$password || !$confirmPassword) {
    echo json_encode([
        'status' => 'error',
        'message' => 'Todos los campos son obligatorios.'
    ]);
    exit;
}

if ($password !== $confirmPassword) {
    echo json_encode([
        'status' => 'error',
        'message' => 'Las contraseñas no coinciden.'
    ]);
    exit;
}

$supabase = new SupabaseClient();
$auth = $supabase->signUpUser($email, $password, $nombre, $apellido);

if (isset($auth['error'])) {
    echo json_encode([
        'status' => 'error',
        'message' => $auth['error']['message']
    ]);
    exit;
}

echo json_encode([
    'status' => 'success',
    'message' => 'Registro exitoso.',
    'redirect' => '/bloc-c/login'
]);
exit;
