<?php
define('ACCESS_ALLOWED', true);

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../utils/session.php';
protegerRuta();
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <style>
        body {
            font-family: 'Poppins', sans-serif;
        }
    </style>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
    <link rel="stylesheet" href="/bloc-c/assets/css/skeleton.css">
    <title>Dashboard Notes</title>
</head>

<body class="d-flex flex-column min-vh-100" id="body" style="padding-top: 70px;">
    <div class="navbar" id="navbar">
        <?php include __DIR__ . '/../includes/navbar.php'; ?>
    </div>

    <div class="container py-4" id="contenedorMain">
        <div class="mb-4" id="contenedorEstadisticas">
            <div
                class="d-flex justify-content-between align-items-center border-bottom border-3 border-black pb-2 mb-4 w-100">
                <h2 class="display-6 fw-bold mb-0">Estadísticas</h2>
                <button class="btn btn-primary d-flex align-items-center gap-2" onclick="#">
                    <i class="bi bi-file-earmark-pdf-fill"></i>
                    Generar PDF
                </button>
            </div>

            <!-- Skeleton stats -->
            <?php include __DIR__ . '/../includes/skeleton-stats.php'; ?>

            <!-- Starts reales -->
            <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4 d-none" id="stats-real">

                <div class="col">
                    <div class="card text-bg-success h-100 border-0 shadow-sm">
                        <div class="card-header fw-bold">Tareas Completadas</div>
                        <div class="card-body">
                            <h5 class="card-title display-6" id="tareasCompletadas"></h5>
                            <p class="card-text mb-0">Tareas finalizadas con éxito esta semana.</p>
                        </div>
                    </div>
                </div>
                <div class="col">
                    <div class="card text-bg-danger h-100 border-0 shadow-sm">
                        <div class="card-header fw-bold">Tareas Pendientes</div>
                        <div class="card-body">
                            <h5 class="card-title display-6" id="tareasPendientes"></h5>
                            <p class="card-text mb-0">Requieren atención inmediata.</p>
                        </div>
                    </div>
                </div>
                <div class="col">
                    <div class="card text-bg-light h-100 border-0 shadow-sm">
                        <div class="card-header fw-bold text-dark">Total de Tareas</div>
                        <div class="card-body">
                            <h5 class="card-title display-6 text-dark" id="totalTareas"></h5>
                            <p class="card-text mb-0 text-muted">Registradas en el sistema.</p>
                        </div>
                    </div>
                </div>

            </div>
        </div>

        <div class="mb-4" id="contenedorTareas">

            <h2 class="mb-4 display-6 fw-bold border-bottom border-3 border-black pb-2">Mis Tareas</h2>

            <!-- Skeleton tasks -->
            <?php include __DIR__ . '/../includes/skeleton-tasks.php'; ?>

            <!-- Real tasks -->
            <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4 d-none" id="listaTareas">
                <!-- lista de tareas -->
            </div>

        </div>

        <div id="appMensaje" class="alert border-0 rounded-3 d-none mt-3 mb-0" role="alert"></div>

        <?php include __DIR__ . '/../includes/panel.php'; ?>

    </div>

    <div class="mt-auto" id="footer">
        <?php include __DIR__ . '/../includes/footer.php'; ?>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI"
        crossorigin="anonymous"></script>
    <script type="module" src="/bloc-c/assets/js/pages/dashboard.js"></script>
    <script type="module" src="/bloc-c/assets/js/utils/logout.js"></script>

</body>

</html>