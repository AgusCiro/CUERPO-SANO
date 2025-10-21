<?php
// Iniciar sesión si no está iniciada
if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

$usuario = $_SESSION['USUARIO'];
$filtro = $_GET['filtro'] ?? '';
$mensaje = $_GET['success'] ?? $_GET['error'] ?? '';
$tipoMensaje = isset($_GET['success']) ? 'success' : (isset($_GET['error']) ? 'danger' : '');

// El controlador debe pasar esta variable
$tiposEntrenador = $tiposEntrenador ?? []; 
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Entrenadores - Cuerpo Sano</title>
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
                <li><a href="../templates/dashboard.php">🏠 Inicio</a></li>
                <li><a href="../controllers/ClienteController.php?accion=listar">👥 Clientes</a></li>
                <li><a href="#" class="active">🧑‍🏫 Entrenadores</a></li>
                <li><a href="#">📅 Clases</a></li>
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
                    <h1><i class="fas fa-users"></i> Gestión de Entrenadores</h1>
                    <div class="header-actions">
                        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#agregarEntrenadorModal">
                            <i class="fas fa-plus"></i> Nuevo Entrenador
                        </button>
                    </div>
                </div>
            </header>

            <section class="content">
                <!-- Mensajes -->
                <?php if (!empty($mensaje)): ?>
                    <div class="alert alert-<?php echo $tipoMensaje; ?> alert-dismissible fade show" role="alert">
                        <?php echo htmlspecialchars($mensaje); ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php endif; ?>

                <!-- Barra de búsqueda -->
                <div class="search-container">
                    <form method="GET" action="EntrenadorController.php">
                        <input type="hidden" name="accion" value="listar">
                        <div class="row">
                            <div class="col-md-8">
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fas fa-search"></i></span>
                                    <input type="text" class="form-control" name="filtro" 
                                           placeholder="Buscar por DNI, apellido, nombre, email o n° de entrenador..." 
                                           value="<?php echo htmlspecialchars($filtro); ?>">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <button type="submit" class="btn btn-primary me-2">
                                    <i class="fas fa-search"></i> Buscar
                                </button>
                                <a href="EntrenadorController.php?accion=listar" class="btn btn-outline-secondary">
                                    <i class="fas fa-times"></i> Limpiar
                                </a>
                            </div>
                        </div>
                    </form>
                </div>

                <!-- Lista de entrenadores -->
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th>N°</th>
                                            <th>Nombre</th>
                                            <th>Apellido</th>
                                            <th>DNI</th>
                                            <th>Teléfono</th>
                                            <th>Estado</th>
                                            <th>Acciones</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if (empty($entrenadores)): ?>
                                            <tr>
                                                <td colspan="7" class="text-center">No se encontraron entrenadores.</td>
                                            </tr>
                                        <?php else: ?>
                                            <?php foreach ($entrenadores as $entrenador): ?>
                                                <tr>
                                                    <td><?php echo htmlspecialchars($entrenador['numero_entrenador']); ?></td>
                                                    <td><?php echo htmlspecialchars($entrenador['nombre']); ?></td>
                                                    <td><?php echo htmlspecialchars($entrenador['apellido']); ?></td>
                                                    <td><?php echo htmlspecialchars($entrenador['dni']); ?></td>
                                                    <td><?php echo htmlspecialchars($entrenador['telefono']); ?></td>
                                                    <td>
                                                        <?php 
                                                            $estado = $entrenador['estado'];
                                                            $claseBadge = 'bg-secondary';
                                                            if ($estado == 'activo') $claseBadge = 'bg-success';
                                                            if ($estado == 'suspendido') $claseBadge = 'bg-warning text-dark';
                                                            if ($estado == 'baja') $claseBadge = 'bg-danger';
                                                        ?>
                                                        <span class="badge <?php echo $claseBadge; ?>"><?php echo ucfirst($estado); ?></span>
                                                    </td>
                                                    <td>
                                                        <a href="EntrenadorController.php?accion=ver&id=<?php echo $entrenador['id']; ?>" class="btn btn-sm btn-outline-info"><i class="fas fa-eye"></i></a>
                                                        <a href="EntrenadorController.php?accion=editar&id=<?php echo $entrenador['id']; ?>" class="btn btn-sm btn-outline-warning"><i class="fas fa-edit"></i></a>
                                                        <button type="button" class="btn btn-sm btn-outline-danger" onclick="eliminarEntrenador(<?php echo $entrenador['id']; ?>, '<?php echo htmlspecialchars($entrenador['nombre'] . ' ' . $entrenador['apellido']); ?>')"><i class="fas fa-trash"></i></button>
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
            </section>
        </main>
    </div>

    <!-- Modal para agregar entrenador -->
    <div class="modal fade" id="agregarEntrenadorModal" tabindex="-1">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><i class="fas fa-user-plus"></i> Nuevo Entrenador</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form method="POST" action="EntrenadorController.php" id="formAgregarEntrenador">
                    <div id="agregarEntrenadorErrors" class="alert alert-danger" style="display: none;"></div>
                    <input type="hidden" name="accion" value="agregar">
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="nombre" class="form-label">Nombre *</label>
                                    <input type="text" class="form-control" name="nombre" id="nombre" required>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="apellido" class="form-label">Apellido *</label>
                                    <input type="text" class="form-control" name="apellido" id="apellido" required>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="dni" class="form-label">DNI *</label>
                                    <input type="text" class="form-control" name="dni" id="dni" required>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="fecha_nacimiento" class="form-label">Fecha de Nacimiento</label>
                                    <input type="date" class="form-control" name="fecha_nacimiento" id="fecha_nacimiento">
                                </div>
                            </div>
                            <div class="col-md-8">
                                <div class="mb-3">
                                    <label for="direccion" class="form-label">Dirección</label>
                                    <input type="text" class="form-control" name="direccion" id="direccion">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="telefono" class="form-label">Teléfono</label>
                                    <input type="text" class="form-control" name="telefono" id="telefono">
                                </div>
                            </div>
                            <div class="col-md-8">
                                <div class="mb-3">
                                    <label for="email" class="form-label">Email</label>
                                    <input type="email" class="form-control" name="email" id="email">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                             <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="numero_entrenador" class="form-label">N° Entrenador</label>
                                    <input type="number" class="form-control" name="numero_entrenador" id="numero_entrenador" placeholder="Se genera automáticamente si se deja vacío">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="estado" class="form-label">Estado</label>
                                    <select class="form-select" name="estado" id="estado">
                                        <option value="activo" selected>Activo</option>
                                        <option value="suspendido">Suspendido</option>
                                        <option value="baja">Baja</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="tipos" class="form-label">Especialidades</label>
                            <select class="form-select" name="tipos[]" id="tipos" multiple size="3">
                                <?php foreach ($tiposEntrenador as $tipo): ?>
                                    <option value="<?php echo $tipo['id']; ?>"><?php echo htmlspecialchars($tipo['nombre']); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Guardar Entrenador</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal de confirmación para eliminar -->
    <div class="modal fade" id="eliminarModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><i class="fas fa-exclamation-triangle text-warning"></i> Confirmar Eliminación</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p>¿Está seguro que desea eliminar al entrenador <strong id="nombreEntrenadorEliminar"></strong>?</p>
                    <p class="text-danger"><small>Esta acción no se puede deshacer.</small></p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <a href="#" id="confirmarEliminar" class="btn btn-danger"><i class="fas fa-trash"></i> Eliminar</a>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="../../public/js/app/entrenador.js"></script>
</body>
</html>