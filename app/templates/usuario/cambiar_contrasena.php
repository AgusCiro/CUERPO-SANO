
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cambiar Contraseña</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../../../bts/public/css/style.css">
</head>
<body>
    <div class="container d-flex justify-content-center align-items-center vh-100">
        <div class="card p-4 shadow-lg" style="width: 400px;">
            <h3 class="text-center mb-4">Cambiar contraseña</h3>

            <?php if (isset($_GET['error'])): ?>
                <div class="alert alert-danger" role="alert">
                    <?php echo htmlspecialchars($_GET['error']); ?>
                </div>
            <?php endif; ?>

            <form action="../../controllers/UsuarioController.php" method="POST">
                <input type="hidden" name="accion" value="cambiar_contrasena">

                <div class="mb-3">
                    <input type="text" name="dni" class="form-control" placeholder="DNI" required>
                </div>
                <div class="mb-3">
                    <input type="password" name="password_actual" class="form-control" placeholder="Contraseña actual" required>
                </div>
                <div class="mb-3">
                    <input type="password" name="nueva_password" id="nueva_password" class="form-control" placeholder="Nueva contraseña" required>
                    <div class="form-text">
                        <small>La contraseña debe tener al menos 8 caracteres, una mayúscula, una minúscula, un número y un carácter especial.</small>
                    </div>
                    <div id="password-strength" class="mt-2"></div>
                </div>
                <button type="submit" class="btn btn-primary w-100">Actualizar</button>
                <a href="login.php" class="btn btn-secondary w-100 mt-2">Volver al login</a>
            </form>
        </div>
    </div>

    <script>
        const passwordActual = document.querySelector('input[name="password_actual"]');
        const nuevaPassword = document.getElementById('nueva_password');
        const strengthDiv = document.getElementById('password-strength');
        
        function validatePassword() {
            const password = nuevaPassword.value;
            const actualPassword = passwordActual.value;
            
            let strength = 0;
            let feedback = [];
            
            // Verificar si es igual a la contraseña actual
            if (password && actualPassword && password === actualPassword) {
                strengthDiv.innerHTML = `
                    <div class="alert alert-warning py-2">
                        <small><strong>⚠️ La nueva contraseña debe ser diferente a la actual</strong></small>
                    </div>
                `;
                return;
            }
            
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
        }
        
        nuevaPassword.addEventListener('input', validatePassword);
        passwordActual.addEventListener('input', validatePassword);
    </script>
</body>
</html>
