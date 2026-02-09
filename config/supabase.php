<?php
// PROTECCIÓN: Bloquear acceso directo vía HTTP
if (!defined('ACCESS_ALLOWED')) {
    http_response_code(403);
    die('Acceso denegado');
}

// Cargar desde variables de entorno si existen (más seguro)
if (file_exists(__DIR__ . '/../.env')) {
    $env = parse_ini_file(__DIR__ . '/../.env');
    define('SUPABASE_URL', $env['SUPABASE_URL'] ?? '');
    define('SUPABASE_ANON_KEY', $env['SUPABASE_ANON_KEY'] ?? '');
} else {

}

require_once __DIR__ . '/../utils/session.php';

class SupabaseClient
{
    private $url;
    private $key;

    public function __construct()
    {
        $this->url = SUPABASE_URL;
        $this->key = SUPABASE_ANON_KEY;
    }

    // Función para hacer peticiones a Supabase
    private function request($method, $endpoint, $data = null, $tokenValue = null)
    {

        $token = $tokenValue ? $tokenValue : $this->key;

        $ch = curl_init();
        $url = $this->url . $endpoint;

        $headers = [
            'apikey: ' . $this->key,
            'Authorization: Bearer ' . $token,
            'Content-Type: application/json',
            'Prefer: return=representation'
        ];

        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);

        if ($data) {
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        }

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        // Depuración
        if ($httpCode >= 400) {
            file_put_contents(__DIR__ . '/debug_response.txt', $response);
            return ['error' => 'HTTP Error ' . $httpCode, 'response' => $response];
        }

        return json_decode($response, true);
    }

    // AUTH - Login con email y password
    public function signInWithPassword(string $email, string $password): array
    {
        $endpoint = '/auth/v1/token?grant_type=password';

        $payload = [
            'email' => $email,
            'password' => $password
        ];

        $ch = curl_init($this->url . $endpoint);

        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST => true,
            CURLOPT_HTTPHEADER => [
                'apikey: ' . $this->key,
                'Content-Type: application/json'
            ],
            CURLOPT_POSTFIELDS => json_encode($payload),
            CURLOPT_SSL_VERIFYPEER => false
        ]);

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        $data = json_decode($response, true);

        if ($httpCode !== 200) {
            return [
                'error' => $data['error_description'] ?? 'Credenciales inválidas'
            ];
        }

        return $data;
    }

    // AUTH - Registrar nuevo usuario
    public function signUpUser(string $email, string $password, string $nombre, string $apellido): array
    {
        // Crear usuario en auth
        $endpoint = '/auth/v1/signup';
        $payload = ['email' => $email, 'password' => $password];

        $response = $this->request('POST', $endpoint, $payload, $this->key); // usando anon key
        if (isset($response['error'])) {
            return $response;
        }

        $userId = $response['user']['id'] ?? null;
        if (!$userId) {
            return ['error' => 'No se pudo crear el usuario'];
        }

        // Crear perfil en tabla profiles
        $accessToken = $response['access_token'] ?? null;
        if (!$accessToken) {
            return ['error' => 'No se pudo obtener el access token'];
        }

        $this->insert('profiles', [
            'id' => $userId,
            'nombre' => $nombre,
            'apellido' => $apellido
        ], $accessToken);

        return $response;
    }

    // SELECT
    public function select($table, $columns = '*', $filters = '', $tokenValue = null)
    {
        $endpoint = "/rest/v1/{$table}?select={$columns}";
        if ($filters) {
            $endpoint .= "&{$filters}";
        }
        return $this->request('GET', $endpoint, null, $tokenValue);
    }

    // Los select devuelven un json tipo => casi todos en general
    // [
    //     "data" => [...],
    //     "error" => null
    // ]
    // Estado útil: saber si error es null → insertó correctamente.

    // INSERT
    // "data" => []
    public function insert($table, $data, $accessToken)
    {
        $endpoint = "/rest/v1/{$table}";
        return $this->request('POST', $endpoint, $data, $accessToken);
    }

    // UPDATE
    // "data" => []
    public function update($table, $data, $filters, $accessToken)
    {
        $endpoint = "/rest/v1/{$table}?{$filters}";
        return $this->request('PATCH', $endpoint, $data, $accessToken);
    }

    // DELETE
    // "data" => []
    public function delete($table, $filters, $accessToken)
    {
        $endpoint = "/rest/v1/{$table}?{$filters}";
        return $this->request('DELETE', $endpoint, null, $accessToken);
    }
}
