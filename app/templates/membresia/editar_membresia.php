<?php
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
    <title>Editar Membresía - Cuerpo Sano</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="../../public/css/dashboard.css" rel="stylesheet">
</head>
<body>
    <div class="dashboard-container">
        <aside class="sidebar">
            <h2>CUERPO SANO</h2>
            <ul>
                <li><a href="/CUERPO-SANO/app/templates/dashboard.php">🏠 Inicio</a></li>
                <li><a href="/CUERPO-SANO/app/controllers/ClienteController.php?accion=listar">👥 Clientes</a></li>
                <li><a href="/CUERPO-SANO/app/controllers/EntrenadorController.php?accion=listar">🧑‍🏫 Entrenadores</a></li>
                <li><a href="/CUERPO-SANO/app/controllers/ActividadController.php?accion=listar">🤸 Actividades</a></li>
                <li><a href="/CUERPO-SANO/app/controllers/MembresiaController.php?accion=listar"  class="active">🎟️ Membresías</a></li>
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

        <main class="main-content">
            <header>
                <div class="header-content">
                    <h1><i class="fas fa-edit"></i> Editar Membresía</h1>
                </div>
            </header>

            <section class="content">
                <?php if (!empty($errores)): ?>
                    <div class="alert alert-danger">
                        <ul>
                            <?php foreach ($errores as $error): ?>
                                <li><?php echo htmlspecialchars($error); ?></li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                <?php endif; ?>

                <div class="card">
                    <div class="card-body">
                        <form method="POST" action="MembresiaController.php" id="form-membresia">
                            <input type="hidden" name="accion" value="editar">
                            <input type="hidden" name="id" value="<?php echo $membresiaData['id']; ?>">
                            
                            <div class="mb-3">
                                <label for="cliente_id" class="form-label">Cliente</label>
                                <select class="form-select" name="cliente_id" id="cliente_id" required>
                                    <?php foreach ($clientes as $cli): ?>
                                        <option value="<?php echo $cli['id']; ?>" <?php echo ($cli['id'] == $membresiaData['cliente_id']) ? 'selected' : ''; ?>><?php echo htmlspecialchars($cli['nombre'] . ' ' . $cli['apellido']); ?></option>
                                    <?php endforeach; ?>
                                </select>
                                <div id="descuento-info" class="form-text mt-2" style="display: none;"></div>
                            </div>

                            <div class="mb-3">
                                <label for="tipo_id" class="form-label">Tipo de Membresía</label>
                                <select class="form-select" name="tipo_id" id="tipo_id" required>
                                    <?php foreach ($tipos as $tipo): ?>
                                        <option value="<?php echo $tipo['id']; ?>" <?php echo ($tipo['id'] == $membresiaData['tipo_id']) ? 'selected' : ''; ?> data-precio="<?php echo $tipo['precio']; ?>" data-dias="<?php echo $tipo['duracion_dias']; ?>"><?php echo htmlspecialchars($tipo['nombre']); ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <div id="info_tipo_membresia" style="display: none;" class="mb-3">
                                <h5>Información de la Membresía</h5>
                                <p><strong>Precio:</strong> <span id="precio_tipo"></span></p>
                                <p><strong>Duración:</strong> <span id="duracion_tipo"></span> días</p>
                                <p><strong>Descripción:</strong> <span id="descripcion_tipo"></span></p>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="fecha_inicio" class="form-label">Fecha de Inicio</label>
                                        <input type="date" class="form-control" name="fecha_inicio" id="fecha_inicio" value="<?php echo $membresiaData['fecha_inicio']; ?>" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="fecha_fin" class="form-label">Fecha de Fin</label>
                                        <input type="date" class="form-control" name="fecha_fin" id="fecha_fin" value="<?php echo $membresiaData['fecha_fin']; ?>" readonly>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="precio_final" class="form-label">Precio a Pagar</label>
                                        <input type="text" class="form-control" id="precio_final" readonly>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="mb-3">
                                <button type="button" class="btn btn-success" id="btn-cobrar">Cobrar</button>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="precio_pagado" class="form-label">Precio Pagado</label>
                                        <input type="number" step="0.01" class="form-control" name="precio_pagado" id="precio_pagado" value="<?php echo $membresiaData['precio_pagado']; ?>" readonly required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="numero_comprobante" class="form-label">Número de Comprobante</label>
                                        <input type="text" class="form-control" name="numero_comprobante" id="numero_comprobante" value="<?php echo $membresiaData['numero_comprobante']; ?>" readonly>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="mb-3">
                                <label for="estado" class="form-label">Estado</label>
                                <select class="form-select" name="estado" id="estado">
                                    <option value="vigente" <?php echo ($membresiaData['estado'] == 'vigente') ? 'selected' : ''; ?>>Vigente</option>
                                    <option value="vencida" <?php echo ($membresiaData['estado'] == 'vencida') ? 'selected' : ''; ?>>Vencida</option>
                                    <option value="cancelada" <?php echo ($membresiaData['estado'] == 'cancelada') ? 'selected' : ''; ?>>Cancelada</option>
                                    <option value="suspendida" <?php echo ($membresiaData['estado'] == 'suspendida') ? 'selected' : ''; ?>>Suspendida</option>
                                </select>
                            </div>

                            <button type="submit" class="btn btn-primary" id="btn-guardar" disabled><i class="fas fa-save"></i> Guardar Cambios</button>
                            <a href="MembresiaController.php?accion=listar" class="btn btn-secondary">Cancelar</a>
                        </form>
                    </div>
                </div>
            </section>
            <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        $(document).ready(function() {
            let precioOriginal = 0;
            let descuento = 0;

            function actualizarPrecioFinal() {
                let precioFinal = precioOriginal;
                if (descuento > 0) {
                    precioFinal = precioOriginal * (1 - descuento);
                }
                $('#precio_final').val(precioFinal.toFixed(2));
            }

            function obtenerInfoCliente() {
                const clienteId = $('#cliente_id').val();
                if (clienteId) {
                    $.ajax({
                        url: 'MembresiaController.php?accion=get_cliente_info',
                        type: 'GET',
                        data: { id: clienteId },
                        dataType: 'json',
                        success: function(cliente) {
                            descuento = 0;
                            let infoText = '';
                            if (cliente.es_estudiante == 1) {
                                descuento = 0.15;
                                infoText = 'Posee un 15% de descuento por ser estudiante.';
                            } else if (cliente.es_mayor_60 == 1) {
                                descuento = 0.10;
                                infoText = 'Posee un 10% de descuento por ser mayor de 60 años.';
                            }

                            if (infoText) {
                                $('#descuento-info').text(infoText).show();
                            } else {
                                $('#descuento-info').hide();
                            }
                            actualizarPrecioFinal();
                        }
                    });
                } else {
                    $('#descuento-info').hide();
                    descuento = 0;
                    actualizarPrecioFinal();
                }
            }

            function obtenerInfoTipoMembresia() {
                const tipoId = $('#tipo_id').val();
                if (tipoId) {
                    $.ajax({
                        url: 'MembresiaController.php?accion=get_tipo_membresia',
                        type: 'GET',
                        data: { id: tipoId },
                        dataType: 'json',
                        success: function(tipo) {
                            if(tipo) {
                                precioOriginal = parseFloat(tipo.precio);
                                $('#precio_tipo').text(tipo.precio);
                                $('#duracion_tipo').text(tipo.duracion_dias);
                                $('#descripcion_tipo').text(tipo.descripcion);
                                $('#info_tipo_membresia').show();
                                $('#fecha_inicio').val('');
                                $('#fecha_fin').val('');
                                actualizarPrecioFinal();
                            } else {
                                $('#info_tipo_membresia').hide();
                                precioOriginal = 0;
                                actualizarPrecioFinal();
                            }
                        }
                    });
                } else {
                    $('#info_tipo_membresia').hide();
                    precioOriginal = 0;
                    actualizarPrecioFinal();
                }
            }

            $('#cliente_id').change(function() {
                obtenerInfoCliente();
            });

            $('#tipo_id').change(function() {
                obtenerInfoTipoMembresia();
            });

            $('#fecha_inicio').change(function() {
                const fechaInicio = $(this).val();
                const tipoId = $('#tipo_id').val();
                const dias = $('#tipo_id option:selected').data('dias');

                if (fechaInicio && dias) {
                    const fechaFin = new Date(fechaInicio);
                    fechaFin.setDate(fechaFin.getDate() + parseInt(dias));
                    $('#fecha_fin').val(fechaFin.toISOString().split('T')[0]);
                }
            });

            $('#btn-cobrar').click(function() {
                const precioFinal = $('#precio_final').val();
                if (precioFinal > 0) {
                    // Simulación de cobro
                    const numeroComprobante = 'COMP-' + Math.random().toString(36).substr(2, 9).toUpperCase();
                    $('#precio_pagado').val(precioFinal);
                    $('#numero_comprobante').val(numeroComprobante);
                    $('#btn-guardar').prop('disabled', false);
                    alert('Cobro simulado exitosamente. Comprobante: ' + numeroComprobante);
                } else {
                    alert('No hay un precio a cobrar.');
                }
            });

            // Init
            obtenerInfoCliente();
            obtenerInfoTipoMembresia();
        });
    </script>
        </main>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>