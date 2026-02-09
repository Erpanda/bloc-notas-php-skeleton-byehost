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

$email = $_POST['email'] ?? '';
$password = $_POST['password'] ?? '';

if (!$email || !$password) {
    echo json_encode([
        'status' => 'error',
        'message' => 'Email y contraseña son obligatorios.'
    ]);
    exit;
}

$supabase = new SupabaseClient();
$auth = $supabase->signInWithPassword($email, $password);

if (isset($auth['error']) || !isset($auth['user']['id'])) {
    echo json_encode([
        'status' => 'error',
        'message' => 'Credenciales incorrectas.'
    ]);
    exit;
}

$accessToken = $auth['access_token'];

// Obtener nombre y apellido de la tabla profiles
$userId = $auth['user']['id'];
$profile = $supabase->select('profiles', 'nombre,apellido', "id=eq.{$userId}", $accessToken);

$nombre = '';
$apellido = '';

// Verificar si se obtuvo el perfil correctamente
if (!isset($profile['error']) && is_array($profile) && count($profile) > 0) {
    $nombre = $profile[0]['nombre'] ?? '';
    $apellido = $profile[0]['apellido'] ?? '';
}

iniciarSesionUsuario([
    'id' => $userId,
    'email' => $email,
    'nombre' => $nombre,
    'apellido' => $apellido
], $auth['access_token']);

echo json_encode([
    'status' => 'success',
    'message' => 'Inicio de sesión exitoso.',
    'redirect' => '/bloc-c/dashboard'
]);
exit;
