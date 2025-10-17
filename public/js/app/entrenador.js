/**
 * JavaScript para gestión de entrenadores
 * Maneja modales y interacciones del usuario
 */

document.addEventListener('DOMContentLoaded', function() {
    // No hay nada que inicializar por ahora
});

function eliminarEntrenador(id, nombre) {
    const modal = new bootstrap.Modal(document.getElementById('eliminarModal'));
    document.getElementById('nombreEntrenadorEliminar').textContent = nombre;
    document.getElementById('confirmarEliminar').href = `../controllers/EntrenadorController.php?accion=eliminar&id=${id}`;
    modal.show();
}
