# Bloc de Notas PHP

Una aplicación web de gestión de tareas y notas personales desarrollada con **PHP** y **Supabase** como backend.

![PHP](https://img.shields.io/badge/PHP-777BB4?style=for-the-badge&logo=php&logoColor=white)
![Supabase](https://img.shields.io/badge/Supabase-3ECF8E?style=for-the-badge&logo=supabase&logoColor=white)
![JavaScript](https://img.shields.io/badge/JavaScript-F7DF1E?style=for-the-badge&logo=javascript&logoColor=black)

---

## Descripción

**Bloc de Notas PHP** es una aplicación web que permite a los usuarios:

- ✅ Registrarse e iniciar sesión de forma segura
- ✅ Crear, editar y eliminar tareas personales
- ✅ Marcar tareas como completadas
- ✅ Ver estadísticas de sus tareas
- ✅ Panel de control (dashboard) personalizado

La aplicación utiliza una arquitectura **frontend + backend separados**, donde PHP actúa como API REST y JavaScript consume estas APIs para una experiencia de usuario fluida.

---

## Tecnologías Utilizadas

| Tecnología            | Uso                                       |
| --------------------- | ----------------------------------------- |
| **PHP 7.4+**          | Backend, lógica de servidor y APIs        |
| **Supabase**          | Base de datos PostgreSQL y autenticación  |
| **JavaScript (ES6+)** | Interactividad frontend y consumo de APIs |
| **HTML5 / CSS3**      | Estructura y estilos                      |
| **cURL**              | Comunicación con Supabase desde PHP       |

---

## Estructura del Proyecto

````
bloc-notas-php/
│
├── .env                 ← Credenciales Supabase (no subir a Git)
├── .htaccess            ← URLs limpias + protección
├── .gitignore           ← Ignorar .env, vendor/
├── index.php            ← Router: verifica sesión → dashboard o home
├── composer.json        ← Dependencias
├── databse.sql          ← Script SQL para crear tablas en Supabase
│
├── config/
│   └── supabase.php     ← Cliente Supabase + funciones cURL
│
├── api/
│   ├── auth/
│   │   ├── login.php    ← POST: autenticar usuario
│   │   ├── register.php ← POST: registrar usuario
│   │   └── logout.php   ← Cerrar sesión
│   └── tasks/
│       ├── create.php   ← POST: crear tarea
│       ├── list.php     ← GET: listar tareas del usuario
│       ├── update.php   ← PUT/PATCH: actualizar tarea
│       ├── delete.php   ← DELETE: eliminar tarea
│       └── stats.php    ← GET: estadísticas de tareas
│
├── views/
│   ├── home.php         ← Página de bienvenida (sin sesión)
│   ├── login.php        ← Formulario de login
│   ├── register.php     ← Formulario de registro
│   ├── dashboard.php    ← Lista de tareas (con sesión)
│   └── crearNota.php    ← Crear nueva tarea
│
├── includes/
│   ├── header.php       ← Navbar dinámico según sesión
│   └── footer.php       ← Footer común
│
├── utils/
│   ├── session.php      ← Manejo de $_SESSION
│   └── helpers.php      ← Validaciones y sanitización
│
└── assets/
    ├── css/
    │   └── styles.css   ← Estilos de la aplicación
    ├── js/
    │   └── app.js       ← Scripts principales
    └── img/

---

## Instalación

### Prerrequisitos

- **XAMPP** o cualquier servidor local con PHP 7.4+
- Cuenta en **[Supabase](https://supabase.com)**
- **Git** (opcional)

### Pasos

1. **Clonar o descargar el proyecto**

   ```bash
   git clone https://github.com/tu-usuario/bloc-notas-php.git
   cd bloc-notas-php


   ```bash
   # Windows
   mv bloc-notas-php C:/xampp/htdocs/

   - Ejecutar el script `databse.sql` en el SQL Editor de Supabase
   SUPABASE_URL=https://tu-proyecto.supabase.co
   SUPABASE_ANON_KEY=tu-anon-key-aqui

   - Acceder a `http://localhost/bloc-notas-php`

---

## Base de Datos

El proyecto utiliza **dos tablas principales** en Supabase:

### Tabla `profiles`

```sql
CREATE TABLE profiles (
  id UUID REFERENCES auth.users(id) ON DELETE CASCADE PRIMARY KEY,
  nombre TEXT NOT NULL,
  apellido TEXT NOT NULL,
  fecha_registro TIMESTAMP WITH TIME ZONE DEFAULT NOW(),
  created_at TIMESTAMP WITH TIME ZONE DEFAULT NOW()
);
````

### Tabla `tasks`

````sql
CREATE TABLE tasks (
  id UUID DEFAULT gen_random_uuid() PRIMARY KEY,
  user_id UUID REFERENCES auth.users(id) ON DELETE CASCADE,
  title TEXT NOT NULL,
  description TEXT,
  completed BOOLEAN DEFAULT false,
  due_date TIMESTAMP WITH TIME ZONE,
  created_at TIMESTAMP WITH TIME ZONE DEFAULT NOW()
);

> **Row Level Security (RLS)** está habilitado para que cada usuario solo pueda ver y modificar sus propios datos.

---

## API Endpoints

### Autenticación

| Método | Endpoint                 | Descripción             |
| ------ | ------------------------ | ----------------------- |
| `POST` | `/api/auth/login.php`    | Iniciar sesión          |
| `POST` | `/api/auth/register.php` | Registrar nuevo usuario |
| `GET`  | `/api/auth/logout.php`   | Cerrar sesión           |

### Tareas

| Método      | Endpoint                | Descripción               |
| ----------- | ----------------------- | ------------------------- |
| `GET`       | `/api/tasks/list.php`   | Listar tareas del usuario |
| `POST`      | `/api/tasks/create.php` | Crear nueva tarea         |
| `PUT/PATCH` | `/api/tasks/update.php` | Actualizar tarea          |
| `DELETE`    | `/api/tasks/delete.php` | Eliminar tarea            |
| `GET`       | `/api/tasks/stats.php`  | Estadísticas de tareas    |

### Formato de Respuesta JSON

Todas las APIs responden en formato JSON estandarizado:

**Éxito:**

```json
{
  "success": true,
  "message": "Operación exitosa",
  "data": { ... }
}
````

**Error:**

```json
{
  "success": false,
  "error": "Mensaje de error"
}
```

---

## Seguridad

- ✅ Autenticación manejada por **Supabase Auth**
- ✅ Sesiones PHP para mantener el estado del usuario
- ✅ **Row Level Security (RLS)** en la base de datos
- ✅ Validación y sanitización de datos en servidor
- ✅ Protección contra acceso directo a archivos de configuración
- ✅ Variables sensibles en archivo `.env` (no versionado)

---

## Arquitectura

El proyecto sigue una arquitectura de **separación frontend/backend**:

```
┌─────────────────┐     ┌─────────────────┐     ┌─────────────────┐
│    Frontend     │────▶│   PHP Backend   │────▶│    Supabase     │
│   (JS + HTML)   │◀────│   (API REST)    │◀────│   (PostgreSQL)  │
└─────────────────┘     └─────────────────┘     └─────────────────┘
       │                        │
       ▼                        ▼
   Formularios             Validación
   Fetch/AJAX              Autenticación
   UI/UX                   Respuestas JSON


---

## Flujo de la Aplicación

1. **Usuario accede** → `index.php` verifica sesión
2. **Sin sesión** → Redirige a `home.php`
3. **Con sesión** → Redirige a `dashboard.php`
4. **Login/Registro** → JS envía datos a API PHP
5. **PHP valida** → Consulta Supabase
6. **Respuesta JSON** → JS actualiza la UI

---
```
