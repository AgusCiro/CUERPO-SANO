<?php
if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}
$usuario = $_SESSION['USUARIO'];
$clase = $clase ?? [];
$horario = $horario ?? [];
$inscripciones = $inscripciones ?? [];
$clientes = $clientes ?? [];
$mensaje = $_GET['success'] ?? $_GET['error'] ?? '';
$tipoMensaje = isset($_GET['success']) ? 'success' : (isset($_GET['error']) ? 'danger' : '');
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inscripciones - Cuerpo Sano</title>
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
                    <div>
                        <h1><i class="fas fa-user-check"></i> Gestión de Inscripciones</h1>
                        <p class="text-muted fs-5">
                            Clase: <strong><?php echo htmlspecialchars($clase['nombre']); ?></strong> <br>
                            Horario: <strong><?php echo htmlspecialchars(date('d/m/Y H:i', strtotime($horario['fecha_inicio']))); ?> - <?php echo htmlspecialchars(date('H:i', strtotime($horario['fecha_fin']))); ?></strong>
                        </p>
                    </div>
                    <div class="header-actions">
                        <a href="ClaseHorarioController.php?accion=listar&clase_id=<?php echo $clase['id']; ?>" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> Volver a Horarios
                        </a>
                    </div>
                </div>
            </header>

            <section class="content">
                <?php if (!empty($mensaje)): ?>
                    <div class="alert alert-<?php echo $tipoMensaje; ?> alert-dismissible fade show" role="alert">
                        <?php echo htmlspecialchars(urldecode($mensaje)); ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                <?php endif; ?>

                <div class="row">
                    <!-- Columna para inscribir cliente -->
                    <div class="col-md-4">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title">Inscribir Cliente</h5>
                                <div class="mb-3">
                                    <span class="fs-4 fw-bold">Cupos: <?php echo htmlspecialchars($horario['cupo_restante']); ?> / <?php echo htmlspecialchars($horario['cupo']); ?></span>
                                </div>

                                <?php if ($horario['cupo_restante'] > 0): ?>
                                    <form action="InscripcionController.php" method="POST">
                                        <input type="hidden" name="accion" value="inscribir">
                                        <input type="hidden" name="clase_horario_id" value="<?php echo $horario['id']; ?>">
                                        
                                        <div class="mb-3">
                                            <label for="cliente_id" class="form-label">Seleccionar Cliente</label>
                                            <select class="form-select" name="cliente_id" id="cliente_id" required>
                                                <option value="">-- Clientes --</option>
                                                <?php foreach ($clientes as $cliente): ?>
                                                    <option value="<?php echo $cliente['id']; ?>"><?php echo htmlspecialchars($cliente['apellido'] . ', ' . $cliente['nombre']); ?></option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>
                                        <button type="submit" class="btn btn-primary w-100"><i class="fas fa-plus"></i> Inscribir</button>
                                    </form>
                                <?php else: ?>
                                    <div class="alert alert-warning">No hay cupos disponibles.</div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>

                    <!-- Columna para listar inscritos -->
                    <div class="col-md-8">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title">Clientes Inscritos</h5>
                                <div class="table-responsive">
                                    <table class="table table-sm">
                                        <thead>
                                            <tr>
                                                <th>Cliente</th>
                                                <th>DNI</th>
                                                <th>Fecha Inscripción</th>
                                                <th>Acción</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php if (empty($inscripciones)): ?>
                                                <tr><td colspan="4" class="text-center">No hay clientes inscritos.</td></tr>
                                            <?php else: ?>
                                                <?php foreach ($inscripciones as $inscripcion): ?>
                                                    <tr>
                                                        <td><?php echo htmlspecialchars($inscripcion['apellido'] . ', ' . $inscripcion['nombre']); ?></td>
                                                        <td><?php echo htmlspecialchars($inscripcion['dni']); ?></td>
                                                        <td><?php echo htmlspecialchars(date('d/m/Y H:i', strtotime($inscripcion['fecha_inscripcion']))); ?></td>
                                                        <td>
                                                            <a href="#" class="btn btn-sm btn-danger" onclick="confirmarCancelar(<?php echo $inscripcion['id']; ?>, <?php echo $horario['id']; ?>)" title="Cancelar Inscripción">
                                                                <i class="fas fa-user-minus"></i>
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
                    </div>
                </div>
            </section>
        </main>
    </div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
function confirmarCancelar(inscripcion_id, clase_horario_id) {
    if (confirm(`¿Está seguro que desea cancelar esta inscripción?`)) {
        window.location.href = `InscripcionController.php?accion=cancelar&clase_horario_id=${clase_horario_id}&inscripcion_id=${inscripcion_id}`;
    }
}
</script>
</body>
</html>
