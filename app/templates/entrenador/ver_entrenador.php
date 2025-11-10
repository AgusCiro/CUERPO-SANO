<?php
// Iniciar sesión si no está iniciada
if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

$usuario = $_SESSION['USUARIO'];
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ver Entrenador - Cuerpo Sano</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="../../public/css/dashboard.css" rel="stylesheet">
</head>
<body>
    <div class="dashboard-container">
        <!-- MENU LATERAL -->
        <aside class="sidebar">
            <h2>CUERPO SANO</h2>
            <ul>
                <li><a href="/CUERPO-SANO/app/templates/dashboard.php">🏠 Inicio</a></li>
                <li><a href="/CUERPO-SANO/app/controllers/ClienteController.php?accion=listar">👥 Clientes</a></li>
                <li><a href="../controllers/EntrenadorController.php?accion=listar" class="active">🧑‍🏫 Entrenadores</a></li>
                <li><a href="/CUERPO-SANO/app/controllers/ActividadController.php?accion=listar">🤸 Actividades</a></li>
                <li><a href="/CUERPO-SANO/app/controllers/ClaseController.php?accion=listar">📅 Clases</a></li>
                <li><a href="#">🕓 Asistencias</a></li>
                <li><a href="#">📘 Instructivo</a></li>
            </ul>
            
            <div class="user-sidebar">
                <div class="user-info-sidebar">
                    <span class="user-name-sidebar"><?php echo htmlspecialchars($usuario['nombre'] . " " . $usuario['apellido']); ?></span>
                    <span class="user-dni-sidebar">DNI: <?php echo htmlspecialchars($usuario['dni']); ?></span>
                    <span class="user-role-sidebar"><?php echo htmlspecialchars($usuario['rol']); ?></span>
                </div>
                <a href="../controllers/UsuarioController.php?accion=logout" class="btn-logout-sidebar">🚪 Cerrar Sesión</a>
            </div>
        </aside>

        <!-- CONTENIDO PRINCIPAL -->
        <main class="main-content">
            <header>
                <div class="header-content">
                    <h1><i class="fas fa-user"></i> Ver Entrenador</h1>
                    <div class="header-actions">
                        <a href="../controllers/EntrenadorController.php?accion=listar" class="btn btn-secondary"><i class="fas fa-arrow-left"></i> Volver al listado</a>
                    </div>
                </div>
            </header>

            <section class="content">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <p><strong>Nombre:</strong> <?php echo htmlspecialchars($entrenadorData['nombre']); ?></p>
                                <p><strong>Apellido:</strong> <?php echo htmlspecialchars($entrenadorData['apellido']); ?></p>
                                <p><strong>DNI:</strong> <?php echo htmlspecialchars($entrenadorData['dni']); ?></p>
                                <p><strong>Teléfono:</strong> <?php echo htmlspecialchars($entrenadorData['telefono']); ?></p>
                                <p><strong>Email:</strong> <?php echo htmlspecialchars($entrenadorData['email']); ?></p>
                                <p><strong>Especialidades:</strong> 
                                    <?php 
                                        if (!empty($entrenadorData['tipos'])) {
                                            $tiposNombres = array_column($entrenadorData['tipos'], 'nombre');
                                            echo htmlspecialchars(implode(', ', $tiposNombres));
                                        } else {
                                            echo 'No asignadas';
                                        }
                                    ?>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </main>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
