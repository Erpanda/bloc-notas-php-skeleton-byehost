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
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
        }

        .placeholder {
            border-radius: 10px;
        }
    </style>
    <title>Crear Nota</title>
</head>

<body class="d-flex flex-column min-vh-100" id="body" style="padding-top: 70px;">
    <div class="navbar" id="navbar">
        <?php include __DIR__ . '/../includes/navbar.php'; ?>
    </div>

    <main class="container py-md-5 flex-grow-1" id="formularioTarea">

        <div class="row justify-content-center mx-3 my-5 mx-md-0 my-md-0">

            <div class="card shadow-sm border-1 rounded-4 overflow-hidden px-0">
                <div class="card-header bg-dark text-white py-3 text-center">
                    <h4 class="mb-0">
                        <i class="bi bi-plus-circle me-2"></i>Crear una nueva tarea
                    </h4>
                </div>
                <div class="card-body p-4 p-md-5">

                    <!-- Skeleton formulario -->
                    <?php include __DIR__ . '/../includes/skeleton-form.php'; ?>

                    <!-- Formulario real -->
                    <form id="formNuevaTarea" class="needs-validation" novalidate>
                        <!-- Título -->
                        <div class="mb-4">
                            <label for="tituloTarea" class="form-label fw-bold">
                                Título <span class="text-danger">*</span>
                            </label>
                            <input type="text" class="form-control form-control-lg" id="tituloTarea" name="title"
                                placeholder="Ej: Terminar el proyecto de matemáticas" required autofocus>
                            <div class="invalid-feedback">
                                Por favor ingresa un título para la tarea.
                            </div>
                        </div>

                        <!-- Descripción -->
                        <div class="mb-4">
                            <label for="descripcionTarea" class="form-label fw-bold">
                                Descripción <small class="text-muted">(opcional)</small>
                            </label>
                            <textarea class="form-control" id="descripcionTarea" name="description" rows="5"
                                placeholder="Detalles adicionales sobre la tarea..."></textarea>
                        </div>

                        <!-- Fecha límite -->
                        <div class="mb-4">
                            <label for="fechaLimite" class="form-label fw-bold">
                                Fecha límite <small class="text-muted">(opcional)</small>
                            </label>
                            <input type="date" class="form-control form-control-lg" id="fechaLimite" name="due_date">
                        </div>

                        <!-- Botones -->
                        <div class="d-grid d-md-flex gap-3 justify-content-md-end">
                            <button type="button" class="btn btn-outline-secondary btn-lg px-5 order-md-2"
                                id="btnCancelar">
                                Cancelar
                            </button>
                            <button type="submit" class="btn btn-primary btn-lg px-5 order-md-1" id="btnAction">
                                <i class="bi bi-check2 me-2"></i>
                                Crear Tarea
                            </button>
                        </div>

                        <div id="appMensaje" class="alert border-0 rounded-3 d-none mt-3 mb-0" role="alert"></div>

                    </form>
                </div>
            </div>

        </div>

    </main>

    <div class="mt-auto" id="footer">
        <?php include __DIR__ . '/../includes/footer.php'; ?>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI"
        crossorigin="anonymous"></script>
    <script type="module" src="/bloc-c/assets/js/pages/createNote.js"></script>
    <script type="module" src="/bloc-c/assets/js/utils/logout.js"></script>

</body>

</html>