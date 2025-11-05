<?php
if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}
$usuario = $_SESSION['USUARIO'];
$errores = $errores ?? [];
$clase = $clase ?? [];
$actividades = $actividades ?? [];
$entrenadores = $entrenadores ?? [];
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Clase - Cuerpo Sano</title>
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
                    <h1><i class="fas fa-edit"></i> Editar Clase</h1>
                    <div class="header-actions">
                        <a href="ClaseController.php?accion=listar" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> Volver al Listado
                        </a>
                    </div>
                </div>
            </header>

            <section class="content">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Actualiza los datos de la clase</h5>
                        
                        <?php if (!empty($errores)): ?>
                            <div class="alert alert-danger">
                                <ul>
                                    <?php foreach ($errores as $error): ?>
                                        <li><?php echo htmlspecialchars($error); ?></li>
                                    <?php endforeach; ?>
                                </ul>
                            </div>
                        <?php endif; ?>

                        <form action="ClaseController.php" method="POST">
                            <input type="hidden" name="accion" value="editar">
                            <input type="hidden" name="id" value="<?php echo htmlspecialchars($clase['id']); ?>">
                            
                            <div class="mb-3">
                                <label for="nombre" class="form-label">Nombre de la Clase *</label>
                                <input type="text" class="form-control" id="nombre" name="nombre" required value="<?php echo htmlspecialchars($clase['nombre'] ?? ''); ?>">
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="actividad_id" class="form-label">Actividad *</label>
                                        <select class="form-select" id="actividad_id" name="actividad_id" required>
                                            <option value="">Seleccione una actividad</option>
                                            <?php foreach ($actividades as $actividad): ?>
                                                <option value="<?php echo $actividad['id']; ?>" <?php echo ($clase['actividad_id'] ?? '') == $actividad['id'] ? 'selected' : ''; ?>><?php echo htmlspecialchars($actividad['nombre']); ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="entrenador_id" class="form-label">Entrenador *</label>
                                        <select class="form-select" id="entrenador_id" name="entrenador_id" required>
                                            <option value="">Seleccione un entrenador</option>
                                            <?php foreach ($entrenadores as $entrenador): ?>
                                                <option value="<?php echo $entrenador['id']; ?>" <?php echo ($clase['entrenador_id'] ?? '') == $entrenador['id'] ? 'selected' : ''; ?>><?php echo htmlspecialchars($entrenador['nombre'] . ' ' . $entrenador['apellido']); ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="capacidad" class="form-label">Capacidad *</label>
                                <input type="number" class="form-control" id="capacidad" name="capacidad" required min="1" value="<?php echo htmlspecialchars($clase['capacidad'] ?? ''); ?>">
                            </div>

                            <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Guardar Cambios</button>
                            <a href="ClaseController.php?accion=listar" class="btn btn-secondary">Cancelar</a>
                        </form>
                    </div>
                </div>
            </section>
        </main>
    </div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
