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
    <title>Agregar Membresía - LIFTUP</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="../../public/css/dashboard.css" rel="stylesheet">
</head>
<body>
    <div class="dashboard-container">
        <aside class="sidebar">
            <h2>LIFTUP</h2>
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
                    <h1><i class="fas fa-plus"></i> Agregar Membresía</h1>
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

                <div class="card bg-dark p-4">
                    <div class="card-body text-white">
                        <form method="POST" action="MembresiaController.php">
                            <input type="hidden" name="accion" value="agregar">
                            
                            <div class="mb-3">
                                <label for="cliente_id" class="form-label text-white">Cliente</label>
                                <select class="form-select" name="cliente_id" id="cliente_id" required>
                                    <option value="">Seleccione un cliente</option>
                                    <?php foreach ($clientes as $cli): ?>
                                        <option value="<?php echo $cli['id']; ?>"><?php echo htmlspecialchars($cli['nombre'] . ' ' . $cli['apellido']); ?></option>
                                    <?php endforeach; ?>
                                </select>
                                <div id="info_descuento" class="alert alert-info mt-2" style="display: none;"></div>
                            </div>

                            <div class="mb-3">
                                <label for="tipo_id" class="form-label text-white">Tipo de Membresía</label>
                                <select class="form-select" name="tipo_id" id="tipo_id" required>
                                    <option value="">Seleccione un tipo</option>
                                    <?php foreach ($tipos as $tipo): ?>
                                        <option value="<?php echo $tipo['id']; ?>"><?php echo htmlspecialchars($tipo['nombre']); ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <div class="row">
                                <div class="col-md-8">
                                    <div class="mb-3">
                                        <label for="membresia_descripcion" class="form-label text-white">Descripción</label>
                                        <textarea class="form-control" id="membresia_descripcion" rows="2" readonly></textarea>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label for="membresia_precio" class="form-label text-white">Precio Base</label>
                                        <input type="text" class="form-control" id="membresia_precio" readonly>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="fecha_inicio" class="form-label text-white">Fecha de Inicio</label>
                                        <input type="date" class="form-control" name="fecha_inicio" id="fecha_inicio" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="fecha_fin" class="form-label text-white">Fecha de Fin</label>
                                        <input type="date" class="form-control" name="fecha_fin" id="fecha_fin" readonly required>
                                    </div>
                                </div>
                            </div>

                            <div class="mb-3 text-end">
                                <button type="button" class="btn btn-info" id="btnCobrar"><i class="fas fa-dollar-sign"></i> Cobrar</button>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="precio_pagado" class="form-label text-white">Precio Pagado</label>
                                        <input type="number" step="0.01" class="form-control" name="precio_pagado" id="precio_pagado" readonly required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                     <div class="mb-3">
                                        <label for="numero_comprobante" class="form-label text-white">Número de Comprobante</label>
                                        <input type="text" class="form-control" name="numero_comprobante" id="numero_comprobante" readonly>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="mb-3">
                                 <label for="estado" class="form-label text-white">Estado</label>
                                 <input type="text" class="form-control" name="estado" id="estado" value="vigente" readonly>
                            </div>

                            <button type="submit" class="btn btn-info" id="btnGuardar" disabled><i class="fas fa-save"></i> Guardar Membresía</button>
                            <a href="MembresiaController.php?accion=listar" class="btn btn-secondary">Cancelar</a>
                        </form>
                    </div>
                </div>
            </section>
        </main>
    </div>

    <!-- Modal de Pago -->
    <div class="modal fade" id="pagoModal" tabindex="-1" aria-labelledby="pagoModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="pagoModalLabel">Procesar Pago</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p><strong>Cliente:</strong> <span id="modal_cliente_nombre"></span></p>
                    <p><strong>Membresía:</strong> <span id="modal_membresia_nombre"></span></p>
                    <hr>
                    <p>Precio Original: <span id="modal_precio_original"></span></p>
                    <p>Descuento (<span id="modal_descuento_tipo"></span>): <span id="modal_descuento_monto"></span></p>
                    <h4 class="text-end">Total a Pagar: <span id="modal_total_pagar"></span></h4>
                    <hr>
                    <div class="mb-3">
                        <label for="metodo_pago" class="form-label text-white">Método de Pago</label>
                        <select class="form-select" id="metodo_pago">
                            <option value="debito">Tarjeta de Débito</option>
                            <option value="credito">Tarjeta de Crédito</option>
                            <option value="qr">Pago con QR</option>
                            <option value="efectivo">Efectivo</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="button" class="btn btn-primary" id="btnConfirmarPago">Confirmar Pago</button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const tipoMembresiaSelect = document.getElementById('tipo_id');
            const clienteSelect = document.getElementById('cliente_id');
            const fechaInicioInput = document.getElementById('fecha_inicio');
            const fechaFinInput = document.getElementById('fecha_fin');
            const descripcionTextarea = document.getElementById('membresia_descripcion');
            const precioInput = document.getElementById('membresia_precio');
            const precioPagadoInput = document.getElementById('precio_pagado');
            const nroComprobanteInput = document.getElementById('numero_comprobante');
            const infoDescuentoDiv = document.getElementById('info_descuento');

            const btnCobrar = document.getElementById('btnCobrar');
            const btnGuardar = document.getElementById('btnGuardar');
            
            let membresiaData = null;
            let clienteData = null;
            let pagoRealizado = false;

            // --- MODAL ---
            const pagoModal = new bootstrap.Modal(document.getElementById('pagoModal'));
            const modalClienteNombre = document.getElementById('modal_cliente_nombre');
            const modalMembresiaNombre = document.getElementById('modal_membresia_nombre');
            const modalPrecioOriginal = document.getElementById('modal_precio_original');
            const modalDescuentoTipo = document.getElementById('modal_descuento_tipo');
            const modalDescuentoMonto = document.getElementById('modal_descuento_monto');
            const modalTotalPagar = document.getElementById('modal_total_pagar');
            const btnConfirmarPago = document.getElementById('btnConfirmarPago');


            function calcularFechaFin() {
                if (fechaInicioInput.value && membresiaData && membresiaData.duracion_dias) {
                    const fechaInicio = new Date(fechaInicioInput.value + 'T00:00:00');
                    const duracion = parseInt(membresiaData.duracion_dias, 10);
                    fechaInicio.setDate(fechaInicio.getDate() + duracion);
                    fechaFinInput.value = fechaInicio.toISOString().split('T')[0];
                }
            }

            tipoMembresiaSelect.addEventListener('change', function () {
                fechaInicioInput.value = '';
                fechaFinInput.value = '';

                const tipoId = this.value;
                if (tipoId) {
                    fetch(`MembresiaController.php?accion=get_tipo_membresia&id=${tipoId}`)
                        .then(response => response.json())
                        .then(data => {
                            membresiaData = data;
                            descripcionTextarea.value = data.descripcion || '';
                            precioInput.value = data.precio ? parseFloat(data.precio).toFixed(2) : '';
                            calcularFechaFin();
                        })
                        .catch(error => console.error('Error fetching membresia tipo:', error));
                } else {
                    membresiaData = null;
                    descripcionTextarea.value = '';
                    precioInput.value = '';
                }
            });

            clienteSelect.addEventListener('change', function() {
                const clienteId = this.value;
                if (clienteId) {
                    fetch(`MembresiaController.php?accion=get_cliente_info&id=${clienteId}`)
                        .then(response => response.json())
                        .then(data => {
                            clienteData = data;
                            if (data.tipo_descuento === 'estudiante') {
                                infoDescuentoDiv.innerHTML = 'Posee un 15% de descuento por ser estudiante.';
                                infoDescuentoDiv.style.display = 'block';
                            } else if (data.tipo_descuento === 'mayor') {
                                infoDescuentoDiv.innerHTML = 'Posee un 10% de descuento por ser mayor.';
                                infoDescuentoDiv.style.display = 'block';
                            } else {
                                infoDescuentoDiv.style.display = 'none';
                            }
                        })
                        .catch(error => {
                            console.error('Error fetching cliente info:', error);
                            clienteData = null;
                            infoDescuentoDiv.style.display = 'none';
                        });
                } else {
                    clienteData = null;
                    infoDescuentoDiv.style.display = 'none';
                }
            });

            fechaInicioInput.addEventListener('change', calcularFechaFin);

            btnCobrar.addEventListener('click', function() {
                if (!clienteData || !membresiaData) {
                    alert('Por favor, seleccione un cliente y un tipo de membresía.');
                    return;
                }

                const precioOriginal = parseFloat(membresiaData.precio);
                let descuento = 0;
                let tipoDescuento = "Ninguno";

                if (clienteData.tipo_descuento === 'estudiante') {
                    descuento = precioOriginal * 0.15;
                    tipoDescuento = "Estudiante (15%)";
                } else if (clienteData.tipo_descuento === 'mayor') {
                    descuento = precioOriginal * 0.10;
                    tipoDescuento = "Mayor (10%)";
                }

                const totalAPagar = precioOriginal - descuento;

                modalClienteNombre.textContent = `${clienteData.nombre} ${clienteData.apellido}`;
                modalMembresiaNombre.textContent = membresiaData.nombre;
                modalPrecioOriginal.textContent = `$${precioOriginal.toFixed(2)}`;
                modalDescuentoTipo.textContent = tipoDescuento;
                modalDescuentoMonto.textContent = `-$${descuento.toFixed(2)}`;
                modalTotalPagar.textContent = `$${totalAPagar.toFixed(2)}`;

                pagoModal.show();
            });

            btnConfirmarPago.addEventListener('click', function() {
                const totalAPagar = parseFloat(modalTotalPagar.textContent.replace('$',''));
                
                // Simulación de pago
                console.log("Procesando pago...");
                
                // Generar número de comprobante aleatorio
                const nroComprobante = 'COMP-' + Math.random().toString(36).substr(2, 9).toUpperCase();

                precioPagadoInput.value = totalAPagar.toFixed(2);
                nroComprobanteInput.value = nroComprobante;
                
                pagoRealizado = true;
                btnGuardar.disabled = false;
                btnCobrar.disabled = true;

                pagoModal.hide();
                
                alert('Pago realizado con éxito. Número de comprobante: ' + nroComprobante);
            });

        });
    </script>
</body>
</html>