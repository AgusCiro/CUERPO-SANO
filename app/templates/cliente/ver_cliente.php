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
    <title>Ver Cliente - Cuerpo Sano</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="../../public/css/dashboard.css" rel="stylesheet">
    <style>
        .client-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 2rem;
            border-radius: 15px;
            margin-bottom: 2rem;
        }
        .info-card {
            border: none;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            border-radius: 10px;
        }
        .info-item {
            padding: 0.75rem 0;
            border-bottom: 1px solid #f0f0f0;
        }
        .info-item:last-child {
            border-bottom: none;
        }
        .info-label {
            font-weight: 600;
            color: #6c757d;
            min-width: 120px;
        }
        .status-badge {
            font-size: 0.9em;
        }
        .discount-badge {
            font-size: 0.8em;
        }
    </style>
</head>
<body>
    <div class="dashboard-container">
        <!-- MENU LATERAL -->
        <aside class="sidebar">
            <h2>CUERPO SANO</h2>
            <ul>
                <li><a href="../dashboard.php">🏠 Inicio</a></li>
                <li><a href="ClienteController.php?accion=listar" class="active">👥 Clientes</a></li>
                <li><a href="#">🧑‍🏫 Entrenadores</a></li>
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
                    <h1><i class="fas fa-user"></i> Información del Cliente</h1>
                    <div class="header-actions">
                        <a href="ClienteController.php?accion=listar" class="btn btn-outline-secondary">
                            <i class="fas fa-arrow-left"></i> Volver
                        </a>
                        <a href="ClienteController.php?accion=editar&id=<?php echo $clienteData['id']; ?>" class="btn btn-warning">
                            <i class="fas fa-edit"></i> Editar
                        </a>
                    </div>
                </div>
            </header>

            <section class="content">
                <!-- Header del cliente -->
                <div class="client-header">
                    <div class="row align-items-center">
                        <div class="col-md-8">
                            <h2 class="mb-2">
                                <i class="fas fa-user-circle fa-2x me-3"></i>
                                <?php echo htmlspecialchars($clienteData['nombre'] . ' ' . $clienteData['apellido']); ?>
                            </h2>
                            <div class="d-flex align-items-center gap-3">
                                <span class="badge bg-<?php echo $clienteData['estado'] === 'activo' ? 'success' : ($clienteData['estado'] === 'suspendido' ? 'warning' : 'danger'); ?> status-badge">
                                    <i class="fas fa-circle"></i> <?php echo ucfirst($clienteData['estado']); ?>
                                </span>
                                <?php if ($clienteData['tipo_descuento'] !== 'none'): ?>
                                    <span class="badge bg-info discount-badge">
                                        <i class="fas fa-percent"></i> Descuento: <?php echo ucfirst($clienteData['tipo_descuento']); ?>
                                    </span>
                                <?php endif; ?>
                            </div>
                        </div>
                        <div class="col-md-4 text-end">
                            <div class="h4 mb-0"><?php echo htmlspecialchars($clienteData['numero_cliente']); ?></div>
                            <small>Número de Cliente</small>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <!-- Información Personal -->
                    <div class="col-md-6 mb-4">
                        <div class="card info-card h-100">
                            <div class="card-header bg-primary text-white">
                                <h5 class="mb-0"><i class="fas fa-id-card"></i> Información Personal</h5>
                            </div>
                            <div class="card-body">
                                <div class="info-item d-flex">
                                    <span class="info-label">Nombre:</span>
                                    <span><?php echo htmlspecialchars($clienteData['nombre']); ?></span>
                                </div>
                                <div class="info-item d-flex">
                                    <span class="info-label">Apellido:</span>
                                    <span><?php echo htmlspecialchars($clienteData['apellido']); ?></span>
                                </div>
                                <?php if (!empty($clienteData['dni'])): ?>
                                    <div class="info-item d-flex">
                                        <span class="info-label">DNI:</span>
                                        <span><?php echo htmlspecialchars($clienteData['dni']); ?></span>
                                    </div>
                                <?php endif; ?>
                                <?php if (!empty($clienteData['fecha_nacimiento'])): ?>
                                    <div class="info-item d-flex">
                                        <span class="info-label">Fecha Nacimiento:</span>
                                        <span><?php echo date('d/m/Y', strtotime($clienteData['fecha_nacimiento'])); ?></span>
                                    </div>
                                <?php endif; ?>
                                <div class="info-item d-flex">
                                    <span class="info-label">Estado:</span>
                                    <span class="badge bg-<?php echo $clienteData['estado'] === 'activo' ? 'success' : ($clienteData['estado'] === 'suspendido' ? 'warning' : 'danger'); ?>">
                                        <?php echo ucfirst($clienteData['estado']); ?>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Información de Contacto -->
                    <div class="col-md-6 mb-4">
                        <div class="card info-card h-100">
                            <div class="card-header bg-success text-white">
                                <h5 class="mb-0"><i class="fas fa-address-book"></i> Información de Contacto</h5>
                            </div>
                            <div class="card-body">
                                <?php if (!empty($clienteData['telefono'])): ?>
                                    <div class="info-item d-flex">
                                        <span class="info-label">Teléfono:</span>
                                        <span>
                                            <i class="fas fa-phone text-success me-1"></i>
                                            <?php echo htmlspecialchars($clienteData['telefono']); ?>
                                        </span>
                                    </div>
                                <?php endif; ?>
                                <?php if (!empty($clienteData['email'])): ?>
                                    <div class="info-item d-flex">
                                        <span class="info-label">Email:</span>
                                        <span>
                                            <i class="fas fa-envelope text-primary me-1"></i>
                                            <a href="mailto:<?php echo htmlspecialchars($clienteData['email']); ?>">
                                                <?php echo htmlspecialchars($clienteData['email']); ?>
                                            </a>
                                        </span>
                                    </div>
                                <?php endif; ?>
                                <?php if (!empty($clienteData['direccion'])): ?>
                                    <div class="info-item d-flex">
                                        <span class="info-label">Dirección:</span>
                                        <span>
                                            <i class="fas fa-map-marker-alt text-danger me-1"></i>
                                            <?php echo htmlspecialchars($clienteData['direccion']); ?>
                                        </span>
                                    </div>
                                <?php endif; ?>
                                <?php if (!empty($clienteData['codigo_barcode'])): ?>
                                    <div class="info-item d-flex">
                                        <span class="info-label">Código Barras:</span>
                                        <span>
                                            <i class="fas fa-barcode text-info me-1"></i>
                                            <?php echo htmlspecialchars($clienteData['codigo_barcode']); ?>
                                        </span>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>

                    <!-- Información de Membresía -->
                    <div class="col-md-6 mb-4">
                        <div class="card info-card h-100">
                            <div class="card-header bg-info text-white">
                                <h5 class="mb-0"><i class="fas fa-id-badge"></i> Membresía</h5>
                            </div>
                            <div class="card-body">
                                <div class="info-item d-flex">
                                    <span class="info-label">Tipo Descuento:</span>
                                    <span class="badge bg-<?php echo $clienteData['tipo_descuento'] === 'none' ? 'secondary' : 'info'; ?>">
                                        <?php echo $clienteData['tipo_descuento'] === 'none' ? 'Sin descuento' : ucfirst($clienteData['tipo_descuento']); ?>
                                    </span>
                                </div>
                                <?php if (!empty($clienteData['usuario_id'])): ?>
                                    <div class="info-item d-flex">
                                        <span class="info-label">Usuario Asociado:</span>
                                        <span class="badge bg-success">
                                            <i class="fas fa-check"></i> Sí
                                        </span>
                                    </div>
                                    <?php if (!empty($clienteData['rol'])): ?>
                                        <div class="info-item d-flex">
                                            <span class="info-label">Rol:</span>
                                            <span class="badge bg-primary"><?php echo ucfirst($clienteData['rol']); ?></span>
                                        </div>
                                    <?php endif; ?>
                                <?php else: ?>
                                    <div class="info-item d-flex">
                                        <span class="info-label">Usuario Asociado:</span>
                                        <span class="badge bg-warning">
                                            <i class="fas fa-times"></i> No
                                        </span>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>

                    <!-- Información del Sistema -->
                    <div class="col-md-6 mb-4">
                        <div class="card info-card h-100">
                            <div class="card-header bg-secondary text-white">
                                <h5 class="mb-0"><i class="fas fa-cog"></i> Información del Sistema</h5>
                            </div>
                            <div class="card-body">
                                <div class="info-item d-flex">
                                    <span class="info-label">ID Cliente:</span>
                                    <span><?php echo $clienteData['id']; ?></span>
                                </div>
                                <div class="info-item d-flex">
                                    <span class="info-label">Fecha Creación:</span>
                                    <span><?php echo date('d/m/Y H:i', strtotime($clienteData['creado_at'])); ?></span>
                                </div>
                                <div class="info-item d-flex">
                                    <span class="info-label">Última Actualización:</span>
                                    <span><?php echo date('d/m/Y H:i', strtotime($clienteData['actualizado_at'])); ?></span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Acciones -->
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body text-center">
                                <h5 class="card-title">Acciones Disponibles</h5>
                                <div class="btn-group" role="group">
                                    <a href="ClienteController.php?accion=editar&id=<?php echo $clienteData['id']; ?>" class="btn btn-warning">
                                        <i class="fas fa-edit"></i> Editar Cliente
                                    </a>
                                    <button type="button" class="btn btn-danger" onclick="eliminarCliente(<?php echo $clienteData['id']; ?>, '<?php echo htmlspecialchars($clienteData['nombre'] . ' ' . $clienteData['apellido']); ?>')">
                                        <i class="fas fa-trash"></i> Eliminar Cliente
                                    </button>
                                    <a href="ClienteController.php?accion=listar" class="btn btn-secondary">
                                        <i class="fas fa-list"></i> Ver Todos los Clientes
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </main>
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
