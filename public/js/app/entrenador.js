/**
 * JavaScript para gestión de entrenadores
 * Maneja modales y interacciones del usuario
 */

document.addEventListener('DOMContentLoaded', function() {
    const agregarModal = document.getElementById('agregarEntrenadorModal');
    if (agregarModal) {
        const form = agregarModal.querySelector('form');
        if (form) {
            form.addEventListener('submit', function(e) {
                e.preventDefault();

                const formData = new FormData(form);
                formData.append('is_ajax', '1');

                const errorDiv = document.getElementById('agregarEntrenadorErrors');
                errorDiv.style.display = 'none';
                errorDiv.innerHTML = '';

                fetch(form.action, {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        window.location.reload();
                    } else {
                        errorDiv.innerHTML = data.errors.join('<br>');
                        errorDiv.style.display = 'block';
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    errorDiv.innerHTML = 'Ocurrió un error inesperado. Por favor, intente de nuevo.';
                    errorDiv.style.display = 'block';
                });
            });
        }
    }
});

function eliminarEntrenador(id, nombre) {
    const modal = new bootstrap.Modal(document.getElementById('eliminarModal'));
    document.getElementById('nombreEntrenadorEliminar').textContent = nombre;
    document.getElementById('confirmarEliminar').href = `../controllers/EntrenadorController.php?accion=eliminar&id=${id}`;
    modal.show();
}
