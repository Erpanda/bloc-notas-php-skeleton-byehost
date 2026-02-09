<!-- ESTRUCTURA DEL PROYECTO (VERSION PREELIMINAR) -->

bloc-notas-php/
│
├── .env ← Credenciales Supabase
├── .htaccess ← URLs limpias + protección completa
├── .gitignore ← Ignorar .env, vendor/
├── index.php ← Router: verifica sesión → dashboard o home
├── composer.json ← Dependencias (phpdotenv)
├── vendor/ ← Librerías de Composer
│
├── config/
│ └── supabase.php ← Configuración + función cURL
│
├── api/  
│ ├── auth/
│ │ ├── login.php ← POST: autenticar usuario
│ │ ├── register.php ← POST: registrar usuario
│ │ └── logout.php ← Cerrar sesión
│ └── tasks/
│ ├── create.php ← POST: crear tarea
│ ├── list.php ← GET: listar tareas
│ ├── update.php ← PUT: actualizar tarea
│ ├── delete.php ← DELETE: eliminar tarea
│ └── stats.php ← GET: estadísticas
│
├── views/  
│ ├── home.php ← Página de bienvenida (sin sesión)
│ ├── login.php ← Formulario login
│ ├── register.php ← Formulario registro
│ ├── dashboard.php ← Lista de tareas (con sesión)
│ └── crearNota.php ← Crear tarea (con sesión)
│
├── includes/  
│ ├── header.php ← Navbar dinámico según sesión
│ └── footer.php ← Footer
│
├── utils/  
│ ├── session.php ← Manejo de $\_SESSION
│ └── helpers.php ← Validaciones, sanitización
│
└── assets/  
 ├── css/
│ └── styles.css
├── js/
│ └── app.js
└── img/
└── log.png
