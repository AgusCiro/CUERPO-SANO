<?php
// Iniciar sesión si no está iniciada
if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

$usuario = $_SESSION['USUARIO'];
$errores = $errores ?? [];
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Cliente - Cuerpo Sano</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="../../public/css/dashboard.css" rel="stylesheet">
    <style>
        .form-container {
            background: #f8f9fa;
            padding: 2rem;
            border-radius: 15px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        .section-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 1rem;
            border-radius: 10px;
            margin-bottom: 1.5rem;
        }
        .required-field::after {
            content: " *";
            color: red;
        }
        .client-info {
            background: #e3f2fd;
            padding: 1rem;
            border-radius: 10px;
            margin-bottom: 1.5rem;
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
                    <h1><i class="fas fa-user-edit"></i> Editar Cliente</h1>
                    <div class="header-actions">
                        <a href="ClienteController.php?accion=ver&id=<?php echo $clienteData['id']; ?>" class="btn btn-outline-info">
                            <i class="fas fa-eye"></i> Ver Cliente
                        </a>
                        <a href="ClienteController.php?accion=listar" class="btn btn-outline-secondary">
                            <i class="fas fa-arrow-left"></i> Volver
                        </a>
                    </div>
                </div>
            </header>

            <section class="content">
                <!-- Información del cliente -->
                <div class="client-info">
                    <h5><i class="fas fa-user"></i> Editando: <?php echo htmlspecialchars($clienteData['nombre'] . ' ' . $clienteData['apellido']); ?></h5>
                    <p class="mb-0">
                        <strong>Número de Cliente:</strong> <?php echo htmlspecialchars($clienteData['numero_cliente']); ?> | 
                        <strong>Estado:</strong> 
                        <span class="badge bg-<?php echo $clienteData['estado'] === 'activo' ? 'success' : ($clienteData['estado'] === 'suspendido' ? 'warning' : 'danger'); ?>">
                            <?php echo ucfirst($clienteData['estado']); ?>
                        </span>
                    </p>
                </div>

                <!-- Mensajes de error -->
                <?php if (!empty($errores)): ?>
                    <div class="alert alert-danger" role="alert">
                        <h6><i class="fas fa-exclamation-triangle"></i> Por favor, corrija los siguientes errores:</h6>
                        <ul class="mb-0">
                            <?php foreach ($errores as $error): ?>
                                <li><?php echo htmlspecialchars($error); ?></li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                <?php endif; ?>

                <div class="form-container">
                    <form method="POST" action="ClienteController.php">
                        <input type="hidden" name="accion" value="editar">
                        <input type="hidden" name="id" value="<?php echo $clienteData['id']; ?>">
                        
                        <!-- Información Personal -->
                        <div class="section-header">
                            <h4 class="mb-0"><i class="fas fa-id-card"></i> Información Personal</h4>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="nombre" class="form-label required-field">Nombre</label>
                                    <input type="text" class="form-control" name="nombre" id="nombre" 
                                           value="<?php echo htmlspecialchars($_POST['nombre'] ?? $clienteData['nombre']); ?>" required>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="apellido" class="form-label required-field">Apellido</label>
                                    <input type="text" class="form-control" name="apellido" id="apellido" 
                                           value="<?php echo htmlspecialchars($_POST['apellido'] ?? $clienteData['apellido']); ?>" required>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="dni" class="form-label required-field">DNI</label>
                                    <input type="number" class="form-control" name="dni" id="dni"
                                           value="<?php echo htmlspecialchars($_POST['dni'] ?? $clienteData['dni']); ?>" required>
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="fecha_nacimiento" class="form-label">Fecha de Nacimiento</label>
                                    <input type="date" class="form-control" name="fecha_nacimiento" id="fecha_nacimiento" 
                                           value="<?php echo htmlspecialchars($_POST['fecha_nacimiento'] ?? $clienteData['fecha_nacimiento']); ?>">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="tipo_descuento" class="form-label">Tipo de Descuento</label>
                                    <select class="form-select" name="tipo_descuento" id="tipo_descuento">
                                        <option value="none" <?php echo ($_POST['tipo_descuento'] ?? $clienteData['tipo_descuento']) === 'none' ? 'selected' : ''; ?>>Sin descuento</option>
                                        <option value="estudiante" <?php echo ($_POST['tipo_descuento'] ?? $clienteData['tipo_descuento']) === 'estudiante' ? 'selected' : ''; ?>>Estudiante</option>
                                        <option value="mayor" <?php echo ($_POST['tipo_descuento'] ?? $clienteData['tipo_descuento']) === 'mayor' ? 'selected' : ''; ?>>Adulto mayor</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <!-- Información de Contacto -->
                        <div class="section-header">
                            <h4 class="mb-0"><i class="fas fa-address-book"></i> Información de Contacto</h4>
                        </div>
                        
                        <div class="mb-3">
                            <label for="direccion" class="form-label">Dirección</label>
                            <input type="text" class="form-control" name="direccion" id="direccion" 
                                   value="<?php echo htmlspecialchars($_POST['direccion'] ?? $clienteData['direccion']); ?>">
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="telefono" class="form-label">Teléfono</label>
                                    <input type="text" class="form-control" name="telefono" id="telefono" 
                                           value="<?php echo htmlspecialchars($_POST['telefono'] ?? $clienteData['telefono']); ?>">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="email" class="form-label">Email</label>
                                    <input type="email" class="form-control" name="email" id="email" 
                                           value="<?php echo htmlspecialchars($_POST['email'] ?? $clienteData['email']); ?>">
                                </div>
                            </div>
                        </div>

                        <!-- Información del Sistema -->
                        <div class="section-header">
                            <h4 class="mb-0"><i class="fas fa-cog"></i> Información del Sistema</h4>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="codigo_barcode" class="form-label">Código de Barras</label>
                                                                         <input type="text" class="form-control" name="codigo_barcode" id="codigo_barcode" 
                                                                               value="<?php echo htmlspecialchars($_POST['codigo_barcode'] ?? $clienteData['codigo_barcode']); ?>" readonly>                                    <div class="form-text">Código único para identificación</div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label for="estado" class="form-label">Estado</label>
                            <select class="form-select" name="estado" id="estado">
                                <option value="activo" <?php echo ($_POST['estado'] ?? $clienteData['estado']) === 'activo' ? 'selected' : ''; ?>>Activo</option>
                                <option value="suspendido" <?php echo ($_POST['estado'] ?? $clienteData['estado']) === 'suspendido' ? 'selected' : ''; ?>>Suspendido</option>
                                <option value="baja" <?php echo ($_POST['estado'] ?? $clienteData['estado']) === 'baja' ? 'selected' : ''; ?>>Baja</option>
                            </select>
                        </div>

                        <!-- Botones -->
                        <div class="d-flex justify-content-between">
                            <div>
                                <a href="ClienteController.php?accion=ver&id=<?php echo $clienteData['id']; ?>" class="btn btn-outline-info">
                                    <i class="fas fa-eye"></i> Ver Cliente
                                </a>
                                <a href="ClienteController.php?accion=listar" class="btn btn-secondary">
                                    <i class="fas fa-times"></i> Cancelar
                                </a>
                            </div>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Actualizar Cliente
                            </button>
                        </div>
                    </form>
                </div>
            </section>
        </main>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>