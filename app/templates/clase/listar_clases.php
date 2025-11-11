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
    <title>Gestión de Clases - Cuerpo Sano</title>
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
                <li><a href="/CUERPO-SANO/app/controllers/ActividadController.php?accion=listar">🤸 Actividades</a></li>
                <li><a href="/CUERPO-SANO/app/controllers/MembresiaController.php?accion=listar">🎟️ Membresías</a></li>
                <li><a href="/CUERPO-SANO/app/controllers/ClaseController.php?accion=listar" class="active">📅 Clases</a></li>
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
                    <h1><i class="fas fa-calendar-alt"></i> Gestión de Clases</h1>
                    <div class="header-actions">
                        <a href="ClaseController.php?accion=agregar" class="btn btn-info">
                            <i class="fas fa-plus"></i> Nueva Clase
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
                                        <th>Actividad</th>
                                        <th>Entrenador</th>
                                        <th>Capacidad</th>
                                        <th>Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (empty($clases)): ?>
                                        <tr>
                                            <td colspan="5" class="text-center">No hay clases registradas.</td>
                                        </tr>
                                    <?php else: ?>
                                        <?php foreach ($clases as $clase): ?>
                                            <tr>
                                                <td><?php echo htmlspecialchars($clase['nombre']); ?></td>
                                                <td><?php echo htmlspecialchars($clase['nombre_actividad']); ?></td>
                                                <td><?php echo htmlspecialchars($clase['nombre_entrenador'] . ' ' . $clase['apellido_entrenador']); ?></td>
                                                <td><?php echo htmlspecialchars($clase['capacidad']); ?></td>
                                                <td>
                                                    <a href="ClaseHorarioController.php?accion=listar&clase_id=<?php echo $clase['id']; ?>" class="btn btn-sm btn-secondary" title="Ver Horarios">
                                                        <i class="fas fa-clock"></i>
                                                    </a>
                                                    <a href="ClaseController.php?accion=editar&id=<?php echo $clase['id']; ?>" class="btn btn-sm btn-warning" title="Editar">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                    <a href="#" class="btn btn-sm btn-danger" onclick="confirmarEliminar(<?php echo $clase['id']; ?>, '<?php echo htmlspecialchars($clase['nombre']); ?>')" title="Eliminar">
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
    if (confirm(`¿Está seguro que desea dar de baja la clase "${nombre}"?`)) {
        window.location.href = `ClaseController.php?accion=eliminar&id=${id}`;
    }
}
</script>

</body>
</html>