<?php
// Iniciar sesión si no está iniciada
if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

$usuario = $_SESSION['USUARIO'];
$filtro = $_GET['filtro'] ?? '';
$mensaje = $_GET['success'] ?? $_GET['error'] ?? '';
$tipoMensaje = isset($_GET['success']) ? 'success' : (isset($_GET['error']) ? 'danger' : '');
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Clientes - Cuerpo Sano</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="../../public/css/dashboard.css" rel="stylesheet">
    <style>
        .search-container {
            background: #212529;
            padding: 20px;
            border-radius: 10px;
            margin-bottom: 20px;
            border: 1px solid rgba(255, 255, 255, 0.1);
        }
        .client-card {
            background-color: #fff; /* White background for client cards */
            color: #212529; /* Dark text for readability */
            transition: transform 0.2s;
            border: none;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        .client-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0,0,0,0.15);
        }
        .client-card .card-title {
            color: #000 !important; /* Black color for the name */
            font-weight: 700; /* Bolder font for emphasis */
        }
        .client-card .text-muted, .client-card small {
            color: #212529 !important; /* Ensure all text inside is dark */
        }
        .client-card .membership-info h6 {
             color: #6c757d !important; /* A slightly lighter grey for subheadings */
        }
        .status-badge {
            font-size: 0.8em;
        }
        .discount-badge {
            font-size: 0.75em;
            background-color: transparent !important;
            border: 1px solid #17a2b8;
            color: #000 !important;
            font-weight: bold;
        }
        .action-buttons {
            gap: 5px;
        }
        .stats-card {
            background-color: #17a2b8; /* Standard light blue accent color */
            color: white;
            border-radius: 15px;
        }
        .stats-card .card-body {
            padding: 1.5rem;
        }
        .stats-number {
            font-size: 2rem;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="dashboard-container">
        <!-- MENU LATERAL -->
        <aside class="sidebar">
            <h2>CUERPO SANO</h2>
            <ul>
                <li><a href="/CUERPO-SANO/app/templates/dashboard.php">🏠 Inicio</a></li>
                <li><a href="#" class="active">👥 Clientes</a></li>
                <li><a href="/CUERPO-SANO/app/controllers/EntrenadorController.php?accion=listar">🧑‍🏫 Entrenadores</a></li>
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
                    <h1><i class="fas fa-users"></i> Gestión de Clientes</h1>
                    <div class="header-actions">
                        <button type="button" class="btn btn-info" data-bs-toggle="modal" data-bs-target="#agregarClienteModal">
                            <i class="fas fa-plus"></i> Nuevo Cliente
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

                <!-- Estadísticas -->
                <div class="row mb-4" id="estadisticas">
                    <div class="col-md-3">
                        <div class="card stats-card">
                            <div class="card-body text-center">
                                <i class="fas fa-users fa-2x mb-2"></i>
                                <div class="stats-number" id="total-clientes">-</div>
                                <div>Total Clientes</div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card stats-card">
                            <div class="card-body text-center">
                                <i class="fas fa-check-circle fa-2x mb-2"></i>
                                <div class="stats-number" id="clientes-activos">-</div>
                                <div>Activos</div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card stats-card">
                            <div class="card-body text-center">
                                <i class="fas fa-graduation-cap fa-2x mb-2"></i>
                                <div class="stats-number" id="estudiantes">-</div>
                                <div>Estudiantes</div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card stats-card">
                            <div class="card-body text-center">
                                <i class="fas fa-user-clock fa-2x mb-2"></i>
                                <div class="stats-number" id="mayores">-</div>
                                <div>Mayores</div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Barra de búsqueda -->
                <div class="search-container">
                    <form method="GET" action="ClienteController.php">
                        <input type="hidden" name="accion" value="listar">
                        <div class="row">
                            <div class="col-md-8">
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fas fa-search"></i></span>
                                    <input type="text" class="form-control" name="filtro" 
                                           placeholder="Buscar por DNI, apellido, nombre o número de cliente..." 
                                           value="<?php echo htmlspecialchars($filtro); ?>">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <button type="submit" class="btn btn-info me-2">
                                    <i class="fas fa-search"></i> Buscar
                                </button>
                                <a href="ClienteController.php?accion=listar" class="btn btn-info">
                                    <i class="fas fa-times"></i> Limpiar
                                </a>
                            </div>
                        </div>
                    </form>
                </div>

                <!-- Lista de clientes -->
                <div class="row" id="clientes-container">
                    <?php if (empty($clientes)): ?>
                        <div class="col-12">
                            <div class="alert alert-info text-center">
                                <i class="fas fa-info-circle fa-2x mb-3"></i>
                                <h4>No se encontraron clientes</h4>
                                <p><?php echo empty($filtro) ? 'No hay clientes registrados.' : 'No se encontraron clientes con el filtro aplicado.'; ?></p>
                                <?php if (empty($filtro)): ?>
                                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#agregarClienteModal">
                                        <i class="fas fa-plus"></i> Agregar Primer Cliente
                                    </button>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php else: ?>
                        <?php foreach ($clientes as $cliente): ?>
                            <div class="col-md-6 col-lg-4 mb-4">
                                <div class="card client-card h-100">
                                    <div class="card-body">
                                        <div class="d-flex justify-content-between align-items-start mb-3">
                                            <h5 class="card-title mb-0">
                                                <?php echo htmlspecialchars($cliente['nombre'] . ' ' . $cliente['apellido']); ?>
                                            </h5>
                                            <span class="badge bg-<?php echo $cliente['estado'] === 'activo' ? 'success' : ($cliente['estado'] === 'suspendido' ? 'warning' : 'danger'); ?> <?php echo ($cliente['estado'] === 'suspendido' ? 'text-dark' : ''); ?> status-badge">
                                                <?php echo ucfirst($cliente['estado']); ?>
                                            </span>
                                        </div>
                                        
                                        <div class="mb-2">
                                            <small class="text-muted">
                                                <i class="fas fa-id-card"></i> 
                                                <?php echo htmlspecialchars($cliente['numero_cliente']); ?>
                                            </small>
                                        </div>
                                        
                                        <?php if (!empty($cliente['dni'])): ?>
                                            <div class="mb-2">
                                                <small class="text-muted">
                                                    <i class="fas fa-fingerprint"></i> 
                                                    DNI: <?php echo htmlspecialchars($cliente['dni']); ?>
                                                </small>
                                            </div>
                                        <?php endif; ?>
                                        
                                        <?php if (!empty($cliente['telefono'])): ?>
                                            <div class="mb-2">
                                                <small class="text-muted">
                                                    <i class="fas fa-phone"></i> 
                                                    <?php echo htmlspecialchars($cliente['telefono']); ?>
                                                </small>
                                            </div>
                                        <?php endif; ?>
                                        
                                        <?php if ($cliente['tipo_descuento'] !== 'none'): ?>
                                            <div class="mb-3">
                                                <span class="badge bg-info discount-badge">
                                                    <i class="fas fa-percent"></i> 
                                                    <?php echo ucfirst($cliente['tipo_descuento']); ?>
                                                </span>
                                            </div>
                                        <?php endif; ?>

                                        <hr>
                                        <div class="membership-info">
                                            <h6 class="text-muted">Membresía</h6>
                                            <?php if (!empty($cliente['fecha_inicio'])): ?>
                                                <?php 
                                                    $fecha_fin = new DateTime($cliente['fecha_fin']);
                                                    $hoy = new DateTime();
                                                    $estado_membresia = ($fecha_fin < $hoy) ? 'vencida' : 'activa';
                                                    $estado_clase = ($estado_membresia == 'activa') ? 'success' : 'danger';
                                                ?>
                                                <div><small><strong>Inicio:</strong> <?php echo htmlspecialchars(date('d/m/Y', strtotime($cliente['fecha_inicio']))); ?></small></div>
                                                <div><small><strong>Fin:</strong> <?php echo htmlspecialchars(date('d/m/Y', strtotime($cliente['fecha_fin']))); ?></small></div>
                                                <div><small><strong>Estado:</strong> <span class="badge bg-<?php echo $estado_clase; ?>"><?php echo ucfirst($estado_membresia); ?></span></small></div>
                                            <?php else: ?>
                                                <small class="text-muted">No posee membresía</small>
                                            <?php endif; ?>
                                        </div>
                                        
                                        <div class="d-flex action-buttons mt-3">
                                            <button type="button" class="btn btn-sm btn-outline-info" 
                                                    onclick="verCliente(<?php echo $cliente['id']; ?>)">
                                                <i class="fas fa-eye"></i> Ver
                                            </button>
                                            <button type="button" class="btn btn-sm btn-outline-warning" 
                                                    onclick="editarCliente(<?php echo $cliente['id']; ?>)">
                                                <i class="fas fa-edit"></i> Editar
                                            </button>
                                            <button type="button" class="btn btn-sm btn-outline-danger" 
                                                    onclick="eliminarCliente(<?php echo $cliente['id']; ?>, '<?php echo htmlspecialchars($cliente['nombre'] . ' ' . $cliente['apellido']); ?>')">
                                                <i class="fas fa-trash"></i> Eliminar
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </section>
        </main>
    </div>

    <!-- Modal para agregar cliente -->
    <div class="modal fade" id="agregarClienteModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content modal-dark">
                <div class="modal-header">
                    <h5 class="modal-title"><i class="fas fa-user-plus"></i> Nuevo Cliente</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form method="POST" action="ClienteController.php">
                    <input type="hidden" name="accion" value="agregar">
                    <div class="modal-body">
                        <div class="row">
                                <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="codigo_barcode" class="form-label">Código de Barras</label>
                                    <input type="text" class="form-control" name="codigo_barcode" id="codigo_barcode">
                                    <div class="form-text">Dejar vacío para generar automáticamente</div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="nombre" class="form-label">Nombre *</label>
                                    <input type="text" class="form-control" name="nombre" id="nombre" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="apellido" class="form-label">Apellido *</label>
                                    <input type="text" class="form-control" name="apellido" id="apellido" required>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="dni" class="form-label">DNI *</label>
                                    <input type="number" class="form-control" name="dni" id="dni" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="direccion" class="form-label">Dirección</label>
                                     <input type="text" class="form-control" name="direccion" id="direccion">
                                </div>
                           </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="telefono" class="form-label">Teléfono</label>
                                    <input type="text" class="form-control" name="telefono" id="telefono">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="email" class="form-label">Email</label>
                                    <input type="email" class="form-control" name="email" id="email">
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="fecha_nacimiento" class="form-label">Fecha de Nacimiento</label>
                                    <input type="date" class="form-control" name="fecha_nacimiento" id="fecha_nacimiento">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="tipo_descuento" class="form-label">Tipo de Descuento</label>
                                    <select class="form-select" name="tipo_descuento" id="tipo_descuento">
                                        <option value="none">Sin descuento</option>
                                        <option value="estudiante">Estudiante</option>
                                        <option value="mayor">Adulto mayor</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label for="estado" class="form-label">Estado</label>
                            <select class="form-select" name="estado" id="estado">
                                <option value="activo">Activo</option>
                                <option value="suspendido">Suspendido</option>
                                <option value="baja">Baja</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-info">
                            <i class="fas fa-save"></i> Guardar Cliente
                        </button>
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
                    <p>¿Está seguro que desea eliminar al cliente <strong id="nombreClienteEliminar"></strong>?</p>
                    <p class="text-danger"><small>Esta acción no se puede deshacer.</small></p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="button" class="btn btn-danger" id="confirmarEliminar">
                        <i class="fas fa-trash"></i> Eliminar
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="../../public/js/app/cliente.js"></script>
</body>
</html>
