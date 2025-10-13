/**
 * JavaScript para gestión de clientes
 * Maneja modales, AJAX y interacciones del usuario
 */

// Variables globales
let clienteIdEliminar = null;
let nombreClienteEliminar = '';

// Inicialización cuando el DOM está listo
document.addEventListener('DOMContentLoaded', function() {
    // Cargar estadísticas al cargar la página
    cargarEstadisticas();
    
    // Cargar usuarios disponibles para el modal de agregar
    cargarUsuariosDisponibles();
    
    // Configurar eventos
    configurarEventos();
});

/**
 * Configurar eventos de la página
 */
function configurarEventos() {
    // Evento para el botón de confirmar eliminación
    const confirmarEliminarBtn = document.getElementById('confirmarEliminar');
    if (confirmarEliminarBtn) {
        confirmarEliminarBtn.addEventListener('click', confirmarEliminacion);
    }
    
    // Evento para generar código de barras automáticamente
    const codigoBarcodeInput = document.getElementById('codigo_barcode');
    if (codigoBarcodeInput) {
        codigoBarcodeInput.addEventListener('blur', function() {
            if (this.value === '') {
                generarCodigoBarcode();
            }
        });
    }
    
    // Evento para el modal de agregar cliente
    const agregarModal = document.getElementById('agregarClienteModal');
    if (agregarModal) {
        agregarModal.addEventListener('show.bs.modal', function() {
            cargarUsuariosDisponibles();
        });
    }
}

/**
 * Cargar estadísticas de clientes via AJAX
 */
function cargarEstadisticas() {
    fetch('ClienteController.php?accion=ajax_estadisticas')
        .then(response => response.json())
        .then(data => {
            if (data) {
                document.getElementById('total-clientes').textContent = data.total_clientes || 0;
                document.getElementById('clientes-activos').textContent = data.clientes_activos || 0;
                document.getElementById('estudiantes').textContent = data.estudiantes || 0;
                document.getElementById('mayores').textContent = data.mayores || 0;
            }
        })
        .catch(error => {
            console.error('Error al cargar estadísticas:', error);
        });
}

/**
 * Cargar usuarios disponibles para el select
 */
function cargarUsuariosDisponibles() {
    fetch('ClienteController.php?accion=ajax_usuarios')
        .then(response => response.json())
        .then(data => {
            const select = document.getElementById('usuario_id');
            if (select) {
                // Limpiar opciones existentes (excepto la primera)
                while (select.children.length > 1) {
                    select.removeChild(select.lastChild);
                }
                
                // Agregar usuarios disponibles
                data.forEach(usuario => {
                    const option = document.createElement('option');
                    option.value = usuario.id;
                    option.textContent = `${usuario.nombre} ${usuario.apellido} (DNI: ${usuario.dni})`;
                    select.appendChild(option);
                });
            }
        })
        .catch(error => {
            console.error('Error al cargar usuarios:', error);
        });
}

/**
 * Ver cliente en modal o redirigir
 */
function verCliente(id) {
    window.location.href = `ClienteController.php?accion=ver&id=${id}`;
}

/**
 * Editar cliente
 */
function editarCliente(id) {
    window.location.href = `ClienteController.php?accion=editar&id=${id}`;
}

/**
 * Mostrar modal de confirmación para eliminar cliente
 */
function eliminarCliente(id, nombre) {
    clienteIdEliminar = id;
    nombreClienteEliminar = nombre;
    
    // Actualizar el modal
    document.getElementById('nombreClienteEliminar').textContent = nombre;
    
    // Mostrar el modal
    const modal = new bootstrap.Modal(document.getElementById('eliminarModal'));
    modal.show();
}

/**
 * Confirmar eliminación del cliente
 */
function confirmarEliminacion() {
    if (clienteIdEliminar) {
        // Mostrar loading en el botón
        const btn = document.getElementById('confirmarEliminar');
        const originalText = btn.innerHTML;
        btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Eliminando...';
        btn.disabled = true;
        
        // Redirigir para eliminar
        window.location.href = `ClienteController.php?accion=eliminar&id=${clienteIdEliminar}`;
    }
}

/**
 * Generar código de barras automáticamente
 */
function generarCodigoBarcode() {
    const codigo = 'BAR' + Math.floor(Math.random() * 1000000000).toString().padStart(9, '0');
    const input = document.getElementById('codigo_barcode');
    if (input) {
        input.value = codigo;
    }
}

/**
 * Validar formulario antes de enviar
 */
function validarFormulario(form) {
    const nombre = form.querySelector('input[name="nombre"]');
    const apellido = form.querySelector('input[name="apellido"]');
    
    let errores = [];
    
    if (!nombre.value.trim()) {
        errores.push('El nombre es obligatorio');
        nombre.classList.add('is-invalid');
    } else {
        nombre.classList.remove('is-invalid');
    }
    
    if (!apellido.value.trim()) {
        errores.push('El apellido es obligatorio');
        apellido.classList.add('is-invalid');
    } else {
        apellido.classList.remove('is-invalid');
    }
    
    if (errores.length > 0) {
        mostrarMensaje('Por favor, corrija los siguientes errores:\n' + errores.join('\n'), 'error');
        return false;
    }
    
    return true;
}

/**
 * Mostrar mensaje al usuario
 */
function mostrarMensaje(mensaje, tipo = 'info') {
    const alertClass = tipo === 'error' ? 'alert-danger' : 'alert-info';
    const icon = tipo === 'error' ? 'fas fa-exclamation-triangle' : 'fas fa-info-circle';
    
    const alertDiv = document.createElement('div');
    alertDiv.className = `alert ${alertClass} alert-dismissible fade show`;
    alertDiv.innerHTML = `
        <i class="${icon}"></i> ${mensaje}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    `;
    
    // Insertar al inicio del contenido
    const content = document.querySelector('.content');
    if (content) {
        content.insertBefore(alertDiv, content.firstChild);
        
        // Auto-ocultar después de 5 segundos
        setTimeout(() => {
            if (alertDiv.parentNode) {
                alertDiv.remove();
            }
        }, 5000);
    }
}

/**
 * Filtrar clientes en tiempo real (si se implementa búsqueda AJAX)
 */
function filtrarClientes(filtro) {
    // Esta función se puede implementar para búsqueda en tiempo real
    // Por ahora, la búsqueda se hace con el formulario tradicional
    console.log('Filtrar clientes:', filtro);
}

/**
 * Exportar datos de clientes (funcionalidad futura)
 */
function exportarClientes() {
    // Implementar exportación a Excel/CSV
    console.log('Exportar clientes');
}

/**
 * Imprimir lista de clientes (funcionalidad futura)
 */
function imprimirClientes() {
    window.print();
}

/**
 * Copiar código de barras al portapapeles
 */
function copiarCodigoBarcode(codigo) {
    navigator.clipboard.writeText(codigo).then(() => {
        mostrarMensaje('Código de barras copiado al portapapeles', 'success');
    }).catch(err => {
        console.error('Error al copiar:', err);
        mostrarMensaje('Error al copiar el código', 'error');
    });
}

/**
 * Calcular edad a partir de fecha de nacimiento
 */
function calcularEdad(fechaNacimiento) {
    if (!fechaNacimiento) return null;
    
    const hoy = new Date();
    const nacimiento = new Date(fechaNacimiento);
    let edad = hoy.getFullYear() - nacimiento.getFullYear();
    const mes = hoy.getMonth() - nacimiento.getMonth();
    
    if (mes < 0 || (mes === 0 && hoy.getDate() < nacimiento.getDate())) {
        edad--;
    }
    
    return edad;
}

/**
 * Formatear teléfono argentino
 */
function formatearTelefono(telefono) {
    if (!telefono) return '';
    
    // Remover caracteres no numéricos
    const numeros = telefono.replace(/\D/g, '');
    
    // Formatear según el patrón argentino
    if (numeros.length === 10) {
        return numeros.replace(/(\d{2})(\d{4})(\d{4})/, '$1 $2-$3');
    } else if (numeros.length === 11) {
        return numeros.replace(/(\d{3})(\d{4})(\d{4})/, '$1 $2-$3');
    }
    
    return telefono;
}

/**
 * Validar email
 */
function validarEmail(email) {
    const regex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    return regex.test(email);
}

/**
 * Limpiar formulario
 */
function limpiarFormulario(formId) {
    const form = document.getElementById(formId);
    if (form) {
        form.reset();
        
        // Remover clases de validación
        const inputs = form.querySelectorAll('.is-invalid, .is-valid');
        inputs.forEach(input => {
            input.classList.remove('is-invalid', 'is-valid');
        });
    }
}

// Exportar funciones para uso global
window.verCliente = verCliente;
window.editarCliente = editarCliente;
window.eliminarCliente = eliminarCliente;
window.generarCodigoBarcode = generarCodigoBarcode;
window.copiarCodigoBarcode = copiarCodigoBarcode;
