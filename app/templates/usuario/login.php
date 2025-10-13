<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar Sesión - Cuerpo Sano</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../../../public/css/style.css">
</head>
<body class="login-bg">

    <div class="container d-flex justify-content-center align-items-center vh-100">
        <div class="card login-card p-4 shadow-lg">
            <h3 class="text-center mb-3 text-primary">Cuerpo Sano</h3>
            <h5 class="text-center mb-4 text-secondary">Iniciar Sesión</h5>

            <!-- Mensaje de confirmación -->
            <?php if (isset($_GET['msg'])): ?>
                <div class="alert alert-success text-center">
                    <?= htmlspecialchars($_GET['msg']) ?>
                </div>
            <?php endif; ?>

            <!-- Mensaje de error -->
            <?php if (isset($_GET['error'])): ?>
                <div class="alert alert-danger text-center">
                    <?= htmlspecialchars($_GET['error']) ?>
                </div>
            <?php endif; ?>

            <form method="POST" action="../../controllers/LoginController.php">
                <div class="mb-3">
                    <input type="text" name="dni" class="form-control" placeholder="DNI" required>
                </div>
                <div class="mb-3">
                    <input type="password" name="password" class="form-control" placeholder="Contraseña" required>
                </div>
                <button type="submit" class="btn btn-primary w-100">Ingresar</button>
            </form>

            <hr>

            <div class="d-grid gap-2">
                <a href="nuevo_usuario.php" class="btn btn-info text-white">Nuevo Usuario</a>
                <a href="cambiar_contrasena.php" class="btn btn-outline-light">Cambiar Contraseña</a>
            </div>
        </div>
    </div>

</body>
</html>
