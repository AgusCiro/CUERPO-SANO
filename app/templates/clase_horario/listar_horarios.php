<?php
if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}
$usuario = $_SESSION['USUARIO'];
$clase = $clase ?? [];
$horarios = $horarios ?? [];
$mensaje = $_GET['success'] ?? $_GET['error'] ?? '';
$tipoMensaje = isset($_GET['success']) ? 'success' : (isset($_GET['error']) ? 'danger' : '');
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Horarios de Clase - Cuerpo Sano</title>
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
                <li><a href="../dashboard.php">🏠 Inicio</a></li>
                <li><a href="../controllers/ClienteController.php">👥 Clientes</a></li>
                <li><a href="../controllers/EntrenadorController.php?accion=listar">🧑‍🏫 Entrenadores</a></li>
                <li><a href="../controllers/ActividadController.php?accion=listar">🤸 Actividades</a></li>
                <li><a href="../controllers/ClaseController.php?accion=listar" class="active">📅 Clases</a></li>
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
                    <h1><i class="fas fa-clock"></i> Horarios para: <?php echo htmlspecialchars($clase['nombre']); ?></h1>
                    <div class="header-actions">
                        <a href="ClaseHorarioController.php?accion=agregar&clase_id=<?php echo $clase['id']; ?>" class="btn btn-primary">
                            <i class="fas fa-plus"></i> Nuevo Horario
                        </a>
                        <a href="ClaseController.php?accion=listar" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> Volver a Clases
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
                                        <th>Fecha Inicio</th>
                                        <th>Fecha Fin</th>
                                        <th>Ubicación</th>
                                        <th>Cupo Total</th>
                                        <th>Cupo Restante</th>
                                        <th>Estado</th>
                                        <th>Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (empty($horarios)): ?>
                                        <tr>
                                            <td colspan="7" class="text-center">No hay horarios registrados para esta clase.</td>
                                        </tr>
                                    <?php else: ?>
                                        <?php foreach ($horarios as $horario): ?>
                                            <tr>
                                                <td><?php echo htmlspecialchars(date('d/m/Y H:i', strtotime($horario['fecha_inicio']))); ?></td>
                                                <td><?php echo htmlspecialchars(date('d/m/Y H:i', strtotime($horario['fecha_fin']))); ?></td>
                                                <td><?php echo htmlspecialchars($horario['ubicacion']); ?></td>
                                                <td><?php echo htmlspecialchars($horario['cupo']); ?></td>
                                                <td><?php echo htmlspecialchars($horario['cupo_restante']); ?></td>
                                                <td>
                                                    <?php if ($horario['cupo_restante'] > 0): ?>
                                                        <span class="badge bg-success">Disponible</span>
                                                    <?php else: ?>
                                                        <span class="badge bg-danger">Completo</span>
                                                    <?php endif; ?>
                                                </td>
                                                <td>
                                                    <a href="InscripcionController.php?accion=gestionar&clase_horario_id=<?php echo $horario['id']; ?>" class="btn btn-sm btn-primary" title="Gestionar Inscripciones">
                                                        <i class="fas fa-user-check"></i>
                                                    </a>
                                                    <a href="ClaseHorarioController.php?accion=editar&clase_id=<?php echo $clase['id']; ?>&id=<?php echo $horario['id']; ?>" class="btn btn-sm btn-warning" title="Editar">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                    <a href="#" class="btn btn-sm btn-danger" onclick="confirmarEliminar(<?php echo $horario['id']; ?>, <?php echo $clase['id']; ?>)" title="Eliminar">
                                                        <i class="fas fa-trash"></i>
                                                    </a>
                                                    <a href="#" class="btn btn-sm btn-info" onclick="confirmarRestablecer(<?php echo $horario['id']; ?>, <?php echo $clase['id']; ?>)" title="Restablecer Cupo">
                                                        <i class="fas fa-sync"></i>
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
function confirmarEliminar(id, clase_id) {
    if (confirm(`¿Está seguro que desea eliminar este horario?`)) {
        window.location.href = `ClaseHorarioController.php?accion=eliminar&clase_id=${clase_id}&id=${id}`;
    }
}
function confirmarRestablecer(id, clase_id) {
    if (confirm(`¿Está seguro que desea restablecer el cupo para este horario? El cupo restante volverá a ser igual al cupo total.`)) {
        window.location.href = `ClaseHorarioController.php?accion=restablecer_cupo&clase_id=${clase_id}&id=${id}`;
    }
}
</script>

</body>
</html>