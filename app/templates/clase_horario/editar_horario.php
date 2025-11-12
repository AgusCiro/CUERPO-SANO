<?php
if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}
$usuario = $_SESSION['USUARIO'];
$clase = $clase ?? [];
$horario = $horario ?? [];
$errores = $errores ?? [];

// Formatear fechas para el input datetime-local
$fecha_inicio_fmt = !empty($horario['fecha_inicio']) ? date('Y-m-d\TH:i', strtotime($horario['fecha_inicio'])) : '';
$fecha_fin_fmt = !empty($horario['fecha_fin']) ? date('Y-m-d\TH:i', strtotime($horario['fecha_fin'])) : '';
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Horario - LIFTUP</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="../../public/css/dashboard.css" rel="stylesheet">
</head>
<body>
    <div class="dashboard-container">
        <!-- MENU LATERAL -->
        <aside class="sidebar">
            <h2>LIFTUP</h2>
            <ul>
                <li><a href="/CUERPO-SANO/app/templates/dashboard.php">🏠 Inicio</a></li>
                <li><a href="/CUERPO-SANO/app/controllers/ClienteController.php?accion=listar">👥 Clientes</a></li>
                <li><a href="/CUERPO-SANO/app/controllers/EntrenadorController.php?accion=listar">🧑‍🏫 Entrenadores</a></li>
                <li><a href="/CUERPO-SANO/app/controllers/ActividadController.php?accion=listar">🤸 Actividades</a></li>
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
                    <h1><i class="fas fa-edit"></i> Editar Horario para: <?php echo htmlspecialchars($clase['nombre']); ?></h1>
                    <div class="header-actions">
                        <a href="ClaseHorarioController.php?accion=listar&clase_id=<?php echo $clase['id']; ?>" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> Volver a Horarios
                        </a>
                    </div>
                </div>
            </header>

            <section class="content">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Actualiza los datos del horario</h5>
                        
                        <?php if (!empty($errores)): ?>
                            <div class="alert alert-danger">
                                <ul>
                                    <?php foreach ($errores as $error): ?>
                                        <li><?php echo htmlspecialchars($error); ?></li>
                                    <?php endforeach; ?>
                                </ul>
                            </div>
                        <?php endif; ?>

                        <form action="ClaseHorarioController.php" method="POST">
                            <input type="hidden" name="accion" value="editar">
                            <input type="hidden" name="clase_id" value="<?php echo $clase['id']; ?>">
                            <input type="hidden" name="id" value="<?php echo $horario['id']; ?>">
                            
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="fecha_inicio" class="form-label" style="color: white;">Fecha y Hora de Inicio *</label>
                                        <input type="datetime-local" class="form-control" id="fecha_inicio" name="fecha_inicio" required value="<?php echo $fecha_inicio_fmt; ?>">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="fecha_fin" class="form-label" style="color: white; ">Fecha y Hora de Fin *</label>
                                        <input type="datetime-local" class="form-control" id="fecha_fin" name="fecha_fin" required value="<?php echo $fecha_fin_fmt; ?>">
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="ubicacion" style="color: white; "class="form-label">Ubicación</label>
                                        <input type="text" class="form-control" id="ubicacion" name="ubicacion" value="<?php echo htmlspecialchars($horario['ubicacion'] ?? ''); ?>">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="cupo" class="form-label" style="color: white; " >Cupo Total *</label>
                                        <input type="number" class="form-control" id="cupo" name="cupo" required min="1" value="<?php echo htmlspecialchars($horario['cupo'] ?? ''); ?>">
                                    </div>
                                </div>
                            </div>

                            <button type="submit" class="btn" style="background-color: #0dcaf0; color: white;"><i class="fas fa-save"></i> Guardar Cambios</button>
                            <a href="ClaseHorarioController.php?accion=listar&clase_id=<?php echo $clase['id']; ?>" class="btn btn-secondary">Cancelar</a>
                        </form>
                    </div>
                </div>
            </section>
        </main>
    </div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
