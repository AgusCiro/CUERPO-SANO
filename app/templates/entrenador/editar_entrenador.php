<?php
// Iniciar sesión si no está iniciada
if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

$usuario = $_SESSION['USUARIO'];
$errores = $_GET['errores'] ?? [];
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Entrenador - Cuerpo Sano</title>
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
                    <h1><i class="fas fa-user-edit"></i> Editar Entrenador</h1>
                    <div class="header-actions">
                        <a href="../controllers/EntrenadorController.php?accion=listar" class="btn btn-secondary"><i class="fas fa-arrow-left"></i> Volver al listado</a>
                    </div>
                </div>
            </header>

            <section class="content">
                <div class="card">
                    <div class="card-body">
                        <?php if (!empty($errores)): ?>
                            <div class="alert alert-danger">
                                <ul>
                                    <?php foreach ($errores as $error): ?>
                                        <li><?php echo htmlspecialchars($error); ?></li>
                                    <?php endforeach; ?>
                                </ul>
                            </div>
                        <?php endif; ?>
                        <form method="POST" action="../controllers/EntrenadorController.php">
                            <input type="hidden" name="accion" value="editar">
                            <input type="hidden" name="id" value="<?php echo htmlspecialchars($entrenadorData['id']); ?>">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="nombre" class="form-label">Nombre *</label>
                                        <input type="text" class="form-control" name="nombre" id="nombre" value="<?php echo htmlspecialchars($entrenadorData['nombre']); ?>" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="apellido" class="form-label">Apellido *</label>
                                        <input type="text" class="form-control" name="apellido" id="apellido" value="<?php echo htmlspecialchars($entrenadorData['apellido']); ?>" required>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="dni" class="form-label">DNI</label>
                                        <input type="text" class="form-control" name="dni" id="dni" value="<?php echo htmlspecialchars($entrenadorData['dni']); ?>">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="telefono" class="form-label">Teléfono</label>
                                        <input type="text" class="form-control" name="telefono" id="telefono" value="<?php echo htmlspecialchars($entrenadorData['telefono']); ?>">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="email" class="form-label">Email</label>
                                        <input type="email" class="form-control" name="email" id="email" value="<?php echo htmlspecialchars($entrenadorData['email']); ?>">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="direccion" class="form-label">Dirección</label>
                                        <input type="text" class="form-control" name="direccion" id="direccion" value="<?php echo htmlspecialchars($entrenadorData['direccion'] ?? ''); ?>">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="fecha_nacimiento" class="form-label">Fecha de Nacimiento</label>
                                        <input type="date" class="form-control" name="fecha_nacimiento" id="fecha_nacimiento" value="<?php echo htmlspecialchars($entrenadorData['fecha_nacimiento'] ?? ''); ?>">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="estado" class="form-label">Estado</label>
                                        <select class="form-control" name="estado" id="estado">
                                            <option value="1" <?php echo ($entrenadorData['estado'] ?? 1) == 1 ? 'selected' : ''; ?>>Activo</option>
                                            <option value="0" <?php echo ($entrenadorData['estado'] ?? 1) == 0 ? 'selected' : ''; ?>>Inactivo</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label for="tipos" class="form-label">Especialidades</label>
                                <select multiple class="form-control" id="tipos" name="tipos[]" style="min-height: 150px;">
                                    <?php 
                                        $tiposSeleccionados = array_column($entrenadorData['tipos'] ?? [], 'id');
                                        if (!empty($tiposEntrenador)) {
                                            foreach ($tiposEntrenador as $tipo) {
                                                $selected = in_array($tipo['id'], $tiposSeleccionados) ? 'selected' : '';
                                                echo "<option value=\"{$tipo['id']}\" $selected>" . htmlspecialchars($tipo['nombre']) . "</option>";
                                            }
                                        }
                                    ?>
                                </select>
                            </div>
                            <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Guardar Cambios</button>
                        </form>
                    </div>
                </div>
            </section>
        </main>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
