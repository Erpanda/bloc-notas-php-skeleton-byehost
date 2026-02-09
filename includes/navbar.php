<?php
$paginaActual = trim(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH), '/');

require_once __DIR__ . '/../utils/session.php';
$profile = obtenerSesion();

if (!$profile) {
    $nombreCompleto = 'Invitado'; 
} else {
    $nombreCompleto = htmlspecialchars(
        $profile['nombre'] . ' ' . $profile['apellido'],
        ENT_QUOTES,
        'UTF-8'
    );
}

?>

<nav class="navbar fixed-top bg-dark border-bottom border-body navbar-expand-lg" data-bs-theme="dark">
    <div class="container-fluid">
        <a class="navbar-brand d-flex align-items-center gap-2 text-light" href="dashboard">
            <img src="assets/img/logo.png" alt="logo" width="40" height="40">
            <span class="fs-5 fw-bold d-none d-sm-inline">BlocNotas</span>
        </a>
        <div class="ms-auto text-light fw-medium me-3">Bienvenido, <strong><?php echo $nombreCompleto ?></strong></div>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" 
                data-bs-target="#navbarSupportedContent" 
                aria-controls="navbarSupportedContent" 
                aria-expanded="false" 
                aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
                <li class="nav-item">
                    <a class="nav-link <?php echo $paginaActual === 'bloc-c/dashboard' ? 'active' : ''?>"
                    <?php echo $paginaActual === 'bloc-c/dashboard' ? 'aria-current="page"' : ''?>
                    href="dashboard">
                        Mis notas
                    </a>
                </li>    
                <li class="nav-item">
                    <a class="nav-link <?php echo $paginaActual === 'bloc-c/createNote' ? 'active' : ''?>"
                    <?php echo $paginaActual === 'bloc-c/createNote' ? 'aria-current="page"' : ''?>
                    href="createNote">
                        Crear nota
                    </a>
                </li>
                <li class="nav-item">
                    <button class="nav-link btn btn-link text-danger" id="btnLogout">Cerrar Sesión</button>
                </li>
            </ul>
        </div>
    </div>
</nav>   
