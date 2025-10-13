
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
                    <input type="password" name="nueva_password" class="form-control" placeholder="Nueva contraseña" required>
                </div>
                <button type="submit" class="btn btn-primary w-100">Actualizar</button>
                <a href="login.php" class="btn btn-secondary w-100 mt-2">Volver al login</a>
            </form>
        </div>
    </div>
</body>
</html>
