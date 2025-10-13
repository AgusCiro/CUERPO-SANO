<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nuevo Usuario</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../../../bts/public/css/style.css">
</head>
<body>
    <div class="container d-flex justify-content-center align-items-center vh-100">
        <div class="card p-4 shadow-lg" style="width: 400px;">
            <h3 class="text-center mb-4">Crear nuevo usuario</h3>

            <!-- Mensaje de error -->
            <?php if (isset($_GET['error'])): ?>
                <div class="alert alert-danger" role="alert">
                    <?= htmlspecialchars($_GET['error']) ?>
                </div>
            <?php endif; ?>

            <form action="../../controllers/UsuarioController.php" method="POST">
                <input type="hidden" name="accion" value="nuevo_usuario">

                <div class="mb-3">
                    <input type="text" name="nombre" class="form-control" placeholder="Nombre" required>
                </div>
                <div class="mb-3">
                    <input type="text" name="apellido" class="form-control" placeholder="Apellido" required>
                </div>
                <div class="mb-3">
                    <input type="email" name="email" class="form-control" placeholder="Correo electrónico" required>
                </div>
                <div class="mb-3">
                    <input type="text" name="dni" class="form-control" placeholder="DNI" required>
                </div>
                <div class="mb-3">
                    <input type="password" name="password" id="password" class="form-control" placeholder="Contraseña" required>
                    <div class="form-text">
                        <small>La contraseña debe tener al menos 8 caracteres, una mayúscula, una minúscula, un número y un carácter especial.</small>
                    </div>
                    <div id="password-strength" class="mt-2"></div>
                </div>
                <div class="mb-3">
                    <select name="rol" class="form-select">
                        <option value="Cliente">Cliente</option>
                        <option value="Entrenador">Entrenador</option>
                        <option value="Recepcionista">Recepcionista</option>
                        <option value="Administrador">Administrador</option>
                    </select>
                </div>
                <button type="submit" class="btn btn-primary w-100">Registrar</button>
                <a href="login.php" class="btn btn-secondary w-100 mt-2">Volver al login</a>
            </form>
        </div>
    </div>

    <script>
        document.getElementById('password').addEventListener('input', function() {
            const password = this.value;
            const strengthDiv = document.getElementById('password-strength');
            
            let strength = 0;
            let feedback = [];
            
            if (password.length >= 8) strength++;
            else feedback.push('Al menos 8 caracteres');
            
            if (/[A-Z]/.test(password)) strength++;
            else feedback.push('Una letra mayúscula');
            
            if (/[a-z]/.test(password)) strength++;
            else feedback.push('Una letra minúscula');
            
            if (/[0-9]/.test(password)) strength++;
            else feedback.push('Un número');
            
            if (/[^A-Za-z0-9]/.test(password)) strength++;
            else feedback.push('Un carácter especial');
            
            let color = 'danger';
            let text = 'Muy débil';
            
            if (strength >= 4) {
                color = 'success';
                text = 'Fuerte';
            } else if (strength >= 3) {
                color = 'warning';
                text = 'Media';
            } else if (strength >= 2) {
                color = 'info';
                text = 'Débil';
            }
            
            strengthDiv.innerHTML = `
                <div class="progress" style="height: 5px;">
                    <div class="progress-bar bg-${color}" style="width: ${(strength/5)*100}%"></div>
                </div>
                <small class="text-${color}">${text} ${feedback.length > 0 ? '- Falta: ' + feedback.join(', ') : ''}</small>
            `;
        });
    </script>
</body>
</html>
