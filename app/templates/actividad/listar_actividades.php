<?php
if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}
$usuario = $_SESSION['USUARIO'];
$mensaje = $_GET['success'] ?? $_GET['error'] ?? '';
$tipoMensaje = isset($_GET['success']) ? 'success' : (isset($_GET['error']) ? 'danger' : '');
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Actividades - Cuerpo Sano</title>
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
                <li><a href="/CUERPO-SANO/app/controllers/EntrenadorController.php?accion=listar">🧑‍🏫 Entrenadores</a></li>
                <li><a href="../controllers/ActividadController.php?accion=listar" class="active">🤸 Actividades</a></li>
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
                    <h1><i class="fas fa-dumbbell"></i> Gestión de Actividades</h1>
                    <div class="header-actions">
                        <a href="ActividadController.php?accion=agregar" class="btn btn-info">
                            <i class="fas fa-plus"></i> Nueva Actividad
                        </a>
                    </div>
                </div>
            </header>

            <section class="content">
                <?php if (!empty($mensaje)): ?>
                    <div class="alert alert-<?php echo $tipoMensaje; ?> alert-dismissible fade show" role="alert">
                        <?php echo htmlspecialchars($mensaje); ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                <?php endif; ?>

                <div class="card">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead class="table-light">
                                    <tr>
                                        <th>Nombre</th>
                                        <th>Descripción</th>
                                        <th>Duración (min)</th>
                                        <th>Intensidad</th>
                                        <th>Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (empty($actividades)): ?>
                                        <tr>
                                            <td colspan="5" class="text-center">No hay actividades registradas.</td>
                                        </tr>
                                    <?php else: ?>
                                        <?php foreach ($actividades as $actividad): ?>
                                            <tr>
                                                <td><?php echo htmlspecialchars($actividad['nombre']); ?></td>
                                                <td><?php echo htmlspecialchars($actividad['descripcion']); ?></td>
                                                <td><?php echo htmlspecialchars($actividad['duracion_minutos']); ?></td>
                                                <td><?php echo htmlspecialchars($actividad['intensidad']); ?></td>
                                                <td>
                                                    <a href="ActividadController.php?accion=editar&id=<?php echo $actividad['id']; ?>" class="btn btn-sm btn-warning">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                    <a href="#" class="btn btn-sm btn-danger" onclick="confirmarEliminar(<?php echo $actividad['id']; ?>, '<?php echo htmlspecialchars($actividad['nombre']); ?>')">
                                                        <i class="fas fa-trash"></i>
                                                    </a>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </section>
        </main>
    </div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
function confirmarEliminar(id, nombre) {
    if (confirm(`¿Está seguro que desea dar de baja la actividad "${nombre}"?`)) {
        window.location.href = `ActividadController.php?accion=eliminar&id=${id}`;
    }
}
</script>

</body>
</html>